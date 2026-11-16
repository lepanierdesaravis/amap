<?php

namespace App\Models;

use App\Enums\Frequency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ContractLine extends Model
{
    protected $fillable = ['contract_id', 'product_id', 'quantity', 'frequency', 'distribution_ids', 'unit_price_cents', 'is_amount'];

    protected function casts(): array
    {
        return [
            'frequency' => Frequency::class,
            'distribution_ids' => 'array',
            'is_amount' => 'boolean',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function deliveries(): MorphMany
    {
        return $this->morphMany(Delivery::class, 'source');
    }

    public function totalCents(): int
    {
        // Un montant d'engagement vaut pour toute la saison.
        return $this->is_amount
            ? $this->unit_price_cents * $this->quantity
            : $this->unit_price_cents * $this->quantity * count($this->distribution_ids);
    }
}
