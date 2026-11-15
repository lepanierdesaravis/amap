<?php

namespace App\Models;

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
        'frequencies', 'payment_plans', 'terms', 'is_published',
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
