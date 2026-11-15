<?php

namespace App\Livewire\Member;

use App\Enums\Frequency;
use App\Enums\PaymentMethod;
use App\Models\ContractOffer;
use App\Models\Distribution;
use App\Services\ContractPlanner;
use App\Services\OrderService;
use App\Support\Money;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Souscription d'un contrat : l'adhérent choisit quantité et fréquence,
 * le calendrier et le panier se mettent à jour en direct.
 */
#[Layout('components.layouts.app')]
class ContractSubscription extends Component
{
    public ContractOffer $offer;

    /** @var array<int, array{quantity: int, frequency: string, start: int, custom: list<int>}> */
    public array $lines = [];

    public int $installments = 1;

    public string $paymentMethod = 'cheque';

    public bool $saved = false;

    public function mount(ContractOffer $offer): void
    {
        abort_unless($offer->is_published, 404);
        $this->offer = $offer->load(['producer', 'products']);

        $defaultFrequency = ($offer->allowedFrequencies()[0] ?? Frequency::Every)->value;
        foreach ($offer->products as $product) {
            $this->lines[$product->id] = ['quantity' => 0, 'frequency' => $defaultFrequency, 'start' => 0, 'custom' => []];
        }
        $this->installments = $offer->paymentPlans()[0]['installments'] ?? 1;

        // Reprise d'un contrat déjà enregistré.
        $contract = auth()->user()->contracts()->where('contract_offer_id', $offer->id)->with('lines')->first();
        if ($contract) {
            $ids = $this->distributions->pluck('id')->values();
            foreach ($contract->lines as $line) {
                $first = array_search($line->distribution_ids[0] ?? null, $ids->all(), true);
                $this->lines[$line->product_id] = [
                    'quantity' => $line->quantity,
                    'frequency' => $line->frequency->value,
                    'start' => $first === false ? 0 : $first % 2,
                    'custom' => $line->distribution_ids,
                ];
            }
            $this->installments = $contract->installments;
            $this->paymentMethod = $contract->payment_method->value;
            $this->saved = true;
        }
    }

    #[Computed]
    public function distributions(): Collection
    {
        return $this->offer->distributions();
    }

    #[Computed]
    public function editable(): bool
    {
        return $this->offer->isOpen() && auth()->user()->canOrder();
    }

    /** @return array<int, list<int>> identifiant du produit => distributions retenues */
    #[Computed]
    public function selectedDates(): array
    {
        $planner = app(ContractPlanner::class);

        return collect($this->lines)->map(fn ($line) => $line['quantity'] > 0
            ? $planner->distributionIds($this->distributions, Frequency::from($line['frequency']), (int) $line['start'], array_map('intval', $line['custom']))
            : [])->all();
    }

    #[Computed]
    public function totalCents(): int
    {
        return $this->offer->products->sum(fn ($p) => $p->price_cents * $this->lines[$p->id]['quantity'] * count($this->selectedDates[$p->id]));
    }

    #[Computed]
    public function schedule(): array
    {
        return app(ContractPlanner::class)->installments($this->offer, $this->totalCents, $this->installments);
    }

    /** Mois de la saison, semaine par semaine, avec les icônes des produits choisis. */
    #[Computed]
    public function months(): array
    {
        $byDate = $this->distributions->keyBy(fn (Distribution $d) => $d->date->toDateString());
        $icons = [];
        foreach ($this->offer->products as $product) {
            foreach ($this->selectedDates[$product->id] as $id) {
                $icons[$id][] = $product->icon ?: '🧺';
            }
        }

        $months = [];
        for ($month = $this->offer->starts_on->copy()->startOfMonth(); $month->lte($this->offer->ends_on); $month->addMonth()) {
            $cells = array_fill(0, $month->dayOfWeekIso - 1, null);
            for ($day = $month->copy(); $day->month === $month->month; $day->addDay()) {
                $distribution = $byDate->get($day->toDateString());
                $cells[] = [
                    'day' => $day->day,
                    'distribution' => $distribution?->id,
                    'icons' => $distribution ? array_unique($icons[$distribution->id] ?? []) : [],
                ];
            }
            $months[] = ['label' => ucfirst($month->translatedFormat('F Y')), 'cells' => $cells];
        }

        return $months;
    }

    public function increment(int $productId): void
    {
        $this->lines[$productId]['quantity'] = min(20, $this->lines[$productId]['quantity'] + 1);
    }

    public function decrement(int $productId): void
    {
        $this->lines[$productId]['quantity'] = max(0, $this->lines[$productId]['quantity'] - 1);
    }

    public function toggleDate(int $productId, int $distributionId): void
    {
        $custom = collect($this->lines[$productId]['custom'])->map(fn ($id) => (int) $id);
        $this->lines[$productId]['custom'] = $custom->contains($distributionId)
            ? $custom->reject(fn ($id) => $id === $distributionId)->values()->all()
            : $custom->push($distributionId)->all();
    }

    public function save(OrderService $orders): void
    {
        $lines = collect($this->lines)->map(fn ($line, $productId) => [
            'product_id' => (int) $productId,
            'quantity' => (int) $line['quantity'],
            'frequency' => Frequency::from($line['frequency']),
            'distribution_ids' => $this->selectedDates[$productId],
        ])->all();

        try {
            $orders->saveContract(auth()->user(), $this->offer, $lines, $this->installments, PaymentMethod::from($this->paymentMethod));
        } catch (ValidationException $e) {
            $this->addError('save', collect($e->errors())->flatten()->first());

            return;
        }

        $this->saved = true;
        session()->flash('status', 'Votre contrat est enregistré. Un e-mail de confirmation vous a été envoyé.');
        $this->redirectRoute('espace.contrats.index', navigate: false);
    }

    public function money(int $cents): string
    {
        return Money::format($cents);
    }

    public function render()
    {
        return view('livewire.member.contract-subscription')
            ->title($this->offer->producer->name.' — souscrire');
    }
}
