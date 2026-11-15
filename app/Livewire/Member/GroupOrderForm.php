<?php

namespace App\Livewire\Member;

use App\Models\GroupPurchase;
use App\Services\OrderService;
use App\Support\Money;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GroupOrderForm extends Component
{
    public GroupPurchase $purchase;

    /** @var array<int, int> identifiant d'article => quantité */
    public array $quantities = [];

    public bool $hasOrder = false;

    public function mount(GroupPurchase $purchase): void
    {
        $this->purchase = $purchase->load(['items', 'producer', 'distribution']);
        foreach ($purchase->items as $item) {
            $this->quantities[$item->id] = 0;
        }

        $order = auth()->user()->groupOrders()->where('group_purchase_id', $purchase->id)->with('lines')->first();
        if ($order) {
            foreach ($order->lines as $line) {
                $this->quantities[$line->group_purchase_item_id] = $line->quantity;
            }
            $this->hasOrder = true;
        }
    }

    #[Computed]
    public function editable(): bool
    {
        return $this->purchase->isOpen() && auth()->user()->canOrder();
    }

    #[Computed]
    public function totalCents(): int
    {
        return $this->purchase->items->sum(fn ($item) => $item->price_cents * (int) ($this->quantities[$item->id] ?? 0));
    }

    public function increment(int $itemId): void
    {
        $item = $this->purchase->items->firstWhere('id', $itemId);
        $max = $item?->max_per_member ?? 99;
        $this->quantities[$itemId] = min($max, (int) $this->quantities[$itemId] + 1);
    }

    public function decrement(int $itemId): void
    {
        $this->quantities[$itemId] = max(0, (int) $this->quantities[$itemId] - 1);
    }

    public function save(OrderService $orders): void
    {
        try {
            $order = $orders->saveGroupOrder(auth()->user(), $this->purchase, $this->quantities);
        } catch (ValidationException $e) {
            $this->addError('save', collect($e->errors())->flatten()->first());

            return;
        }

        session()->flash('status', $order
            ? 'Votre commande est enregistrée. Un e-mail de confirmation vous a été envoyé.'
            : 'Votre commande est annulée.');
        $this->redirectRoute('espace.commandes-groupees.index', navigate: false);
    }

    public function money(int $cents): string
    {
        return Money::format($cents);
    }

    public function render()
    {
        return view('livewire.member.group-order-form')->title($this->purchase->title);
    }
}
