<?php

namespace App\Models;

use App\Enums\Audience;
use App\Enums\Frequency;
use App\Enums\OpeningStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractOffer extends Model
{
    protected $fillable = [
        'producer_id', 'title', 'starts_on', 'ends_on', 'opens_at', 'closes_at',
        'frequencies', 'payment_plans', 'max_contracts', 'share_members_with_producer',
        'terms', 'is_published', 'announce_to', 'announced_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'opens_at' => 'datetime',
            'closes_at' => 'datetime',
            'frequencies' => 'array',
            'payment_plans' => 'array',
            'is_published' => 'boolean',
            'share_members_with_producer' => 'boolean',
            'announce_to' => Audience::class,
            'announced_at' => 'datetime',
        ];
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->orderBy('sort')->orderBy('name');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /** Commandes ponctuelles liées à cet engagement (ex. pain à commander tous les 15 jours). */
    public function groupPurchases(): HasMany
    {
        return $this->hasMany(GroupPurchase::class);
    }

    /** Places restantes, ou null si le nombre de contrats n'est pas limité. */
    public function remainingPlaces(?User $except = null): ?int
    {
        if (! $this->max_contracts) {
            return null;
        }
        $taken = $this->contracts()->when($except, fn ($q) => $q->where('user_id', '!=', $except->id))->count();

        return max(0, $this->max_contracts - $taken);
    }

    /** Complet pour cet adhérent : un adhérent déjà engagé garde sa place. */
    public function isFullFor(?User $user = null): bool
    {
        return $this->remainingPlaces($user) === 0;
    }

    public function status(): OpeningStatus
    {
        return match (true) {
            now()->lt($this->opens_at) => OpeningStatus::Upcoming,
            now()->gt($this->closes_at) => OpeningStatus::Closed,
            default => OpeningStatus::Open,
        };
    }

    public function isOpen(): bool
    {
        return $this->is_published && $this->status() === OpeningStatus::Open;
    }

    public function scopeOpen(Builder $query): void
    {
        $query->where('is_published', true)->where('opens_at', '<=', now())->where('closes_at', '>=', now());
    }

    /** @return list<Frequency> */
    public function allowedFrequencies(): array
    {
        return collect($this->frequencies ?? [])
            ->map(fn ($f) => $f instanceof Frequency ? $f : Frequency::tryFrom($f))
            ->filter()->sortBy(fn (Frequency $f) => array_search($f, Frequency::cases(), true))->values()->all();
    }

    /** Distributions de la saison où le producteur est présent. */
    public function distributions(): Collection
    {
        return $this->producer->distributions()
            ->whereDate('date', '>=', $this->starts_on)
            ->whereDate('date', '<=', $this->ends_on)
            ->orderBy('date')
            ->get();
    }

    /**
     * Échéanciers proposés, triés par nombre de règlements.
     *
     * @return list<array{installments: int, months: list<int>}>
     */
    public function paymentPlans(): array
    {
        return collect($this->payment_plans ?? [])
            ->map(fn ($p) => ['installments' => (int) $p['installments'], 'months' => array_map('intval', $p['months'] ?? [])])
            ->sortBy('installments')->values()->all();
    }
}
