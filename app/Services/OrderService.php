<?php

namespace App\Services;

use App\Enums\Frequency;
use App\Enums\InstallmentStatus;
use App\Enums\PaymentMethod;
use App\Mail\ContractConfirmed;
use App\Mail\GroupOrderConfirmed;
use App\Models\Contract;
use App\Models\ContractOffer;
use App\Models\GroupOrder;
use App\Models\GroupPurchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Enregistre les contrats et commandes groupées, avec leurs livraisons et échéances.
 */
class OrderService
{
    public function __construct(private ContractPlanner $planner) {}

    /**
     * @param  array<int, array{product_id: int, quantity: int, frequency: Frequency, distribution_ids: list<int>}>  $lines
     */
    public function saveContract(User $user, ContractOffer $offer, array $lines, int $installments, PaymentMethod $method): Contract
    {
        $this->ensureCanOrder($user, $offer->isOpen(), 'Les souscriptions de ce contrat sont fermées.');

        $products = $offer->products()->get()->keyBy('id');
        // Un montant d'engagement peut n'avoir aucune date : les livraisons viennent alors des commandes liées.
        $lines = array_values(array_filter($lines, fn ($l) => $l['quantity'] > 0
            && (count($l['distribution_ids']) > 0 || $products->get($l['product_id'])?->is_amount)));
        if ($lines === []) {
            throw ValidationException::withMessages(['lines' => 'Choisissez au moins un produit.']);
        }

        $validDistributions = $offer->distributions()->pluck('id');

        $contract = DB::transaction(function () use ($user, $offer, $lines, $installments, $method, $validDistributions, $products) {
            // Places limitées : premier arrivé, premier servi. Le verrou évite de dépasser la limite.
            $locked = ContractOffer::whereKey($offer->id)->lockForUpdate()->first();
            if ($locked->isFullFor($user)) {
                throw ValidationException::withMessages(['order' => 'Ce contrat est complet : toutes les places ont été prises.']);
            }

            $contract = Contract::firstOrNew(['user_id' => $user->id, 'contract_offer_id' => $offer->id]);
            if ($contract->exists) {
                $this->clear($contract->lines()->get(), $contract);
                $contract->lines()->delete();
            }
            $contract->fill(['installments' => $installments, 'payment_method' => $method, 'total_cents' => 0, 'validated_at' => now()])->save();

            $total = 0;
            foreach ($lines as $data) {
                $product = $products->get($data['product_id']) ?? throw ValidationException::withMessages(['lines' => 'Produit inconnu.']);
                $line = $contract->lines()->create([
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                    'frequency' => $data['frequency'],
                    'distribution_ids' => $validDistributions->intersect($data['distribution_ids'])->values()->all(),
                    'unit_price_cents' => $product->price_cents,
                    'is_amount' => $product->is_amount,
                ]);
                foreach ($line->distribution_ids as $distributionId) {
                    $line->deliveries()->create([
                        'user_id' => $user->id,
                        'distribution_id' => $distributionId,
                        'producer_id' => $offer->producer_id,
                        'label' => $product->name,
                        'icon' => $product->icon,
                        'icon_color' => $product->icon_color,
                        'quantity' => $line->quantity,
                    ]);
                }
                $total += $line->totalCents();
            }

            $contract->update(['total_cents' => $total]);
            foreach ($this->planner->installments($offer, $total, $installments) as $i) {
                $contract->installmentsDue()->create($i + [
                    'user_id' => $user->id,
                    'producer_id' => $offer->producer_id,
                    'payment_method' => $method,
                    'status' => InstallmentStatus::Expected,
                ]);
            }

            return $contract;
        });

        Mail::to($user)->send(new ContractConfirmed($contract->fresh(['offer.producer', 'lines.product', 'installmentsDue'])));

        return $contract;
    }

    /** @param  array<int, int>  $quantities  identifiant d'article => quantité */
    public function saveGroupOrder(User $user, GroupPurchase $purchase, array $quantities): ?GroupOrder
    {
        $this->ensureCanOrder($user, $purchase->isOpen(), 'Cette commande groupée est clôturée.');

        $items = $purchase->items()->get()->keyBy('id');
        $quantities = collect($quantities)
            ->map(fn ($q) => max(0, (int) $q))
            ->filter(fn ($q, $id) => $q > 0 && $items->has($id));

        foreach ($quantities as $id => $q) {
            $max = $items[$id]->max_per_member;
            if ($max && $q > $max) {
                throw ValidationException::withMessages(['quantities' => "« {$items[$id]->fullName()} » : {$max} au maximum par adhérent."]);
            }
        }

        // Adhérent engagé sur le contrat lié : la commande est déduite de son solde, sans paiement à part.
        $contract = $purchase->contractFor($user);

        $order = DB::transaction(function () use ($user, $purchase, $items, $quantities, $contract) {
            $order = GroupOrder::firstWhere(['user_id' => $user->id, 'group_purchase_id' => $purchase->id]);
            if ($order) {
                $this->clear($order->lines()->get(), $order);
                $order->lines()->delete();
                if ($quantities->isEmpty()) {
                    $order->delete();

                    return null;
                }
            }
            if ($quantities->isEmpty()) {
                return null;
            }

            $order ??= new GroupOrder(['user_id' => $user->id, 'group_purchase_id' => $purchase->id]);
            $order->fill(['contract_id' => $contract?->id, 'total_cents' => 0, 'validated_at' => now()])->save();

            $total = 0;
            $icon = $purchase->deliveryIcon();
            foreach ($quantities as $id => $q) {
                $item = $items[$id];
                $line = $order->lines()->create(['group_purchase_item_id' => $id, 'quantity' => $q, 'unit_price_cents' => $item->price_cents]);
                $line->deliveries()->create([
                    'user_id' => $user->id,
                    'distribution_id' => $purchase->distribution_id,
                    'producer_id' => $purchase->producer_id,
                    'label' => $item->fullName(),
                    'icon' => $icon[0],
                    'icon_color' => $icon[1],
                    'quantity' => $q,
                ]);
                $total += $q * $item->price_cents;
            }
            $order->update(['total_cents' => $total]);
            if ($contract) {
                return $order;
            }
            $order->installmentsDue()->create([
                'user_id' => $user->id,
                'producer_id' => $purchase->producer_id,
                'number' => 1,
                'amount_cents' => $total,
                'due_on' => $purchase->distribution->date,
                // Commande ponctuelle sur un engagement : réglée sur place au producteur.
                'payment_method' => $purchase->contract_offer_id ? PaymentMethod::OnSite : PaymentMethod::Cheque,
                'status' => InstallmentStatus::Expected,
            ]);

            return $order;
        });

        if ($order) {
            Mail::to($user)->send(new GroupOrderConfirmed($order->fresh(['groupPurchase.producer', 'groupPurchase.distribution', 'lines.item'])));
        }

        return $order;
    }

    private function ensureCanOrder(User $user, bool $isOpen, string $closedMessage): void
    {
        if (! $user->canOrder()) {
            throw ValidationException::withMessages(['order' => 'Votre adhésion doit être validée avant de commander.']);
        }
        if (! $isOpen) {
            throw ValidationException::withMessages(['order' => $closedMessage]);
        }
    }

    /** Supprime les livraisons et échéances d'une commande avant de l'enregistrer à nouveau. */
    private function clear($lines, Contract|GroupOrder $payable): void
    {
        if ($payable->installmentsDue()->where('status', '!=', InstallmentStatus::Expected)->exists()) {
            throw ValidationException::withMessages(['order' => 'Un paiement a déjà été pointé : contactez votre référent pour modifier cette commande.']);
        }

        foreach ($lines as $line) {
            $line->deliveries()->delete();
        }
        $payable->installmentsDue()->delete();
    }
}
