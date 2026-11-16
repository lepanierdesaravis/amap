<?php

namespace App\Models;

use App\Support\Icons;
use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'producer_id', 'name', 'description', 'unit', 'price_cents', 'is_amount',
        'icon', 'icon_color', 'category_label', 'photo', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_amount' => 'boolean'];
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function priceLabel(): string
    {
        return $this->is_amount
            ? Money::format($this->price_cents).' pour la saison'
            : Money::format($this->price_cents).' / '.$this->unit;
    }

    public function iconBadge(string $size = '2rem'): string
    {
        return Icons::badge($this->icon, $this->icon_color, $size);
    }

    public function photoUrl(): ?string
    {
        return $this->photo ? Storage::disk('public')->url($this->photo) : null;
    }

    /** Libellé de la légende du calendrier. */
    public function legendLabel(): string
    {
        return $this->category_label ?: $this->name;
    }
}
