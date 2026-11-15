<?php

namespace App\Models;

use App\Enums\OpeningStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GroupPurchase extends Model
{
    protected $fillable = [
        'title', 'slug', 'producer_id', 'referent_id', 'description', 'photo',
        'opens_at', 'closes_at', 'distribution_id', 'payment_info', 'announce_by_email', 'announced_at',
    ];

    protected function casts(): array
    {
        return [
            'opens_at' => 'datetime',
            'closes_at' => 'datetime',
            'announce_by_email' => 'boolean',
            'announced_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (GroupPurchase $purchase) {
            if (blank($purchase->slug)) {
                $base = Str::slug($purchase->title);
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$base}-".$i++;
                }
                $purchase->slug = $slug;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function referent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referent_id');
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(GroupPurchaseItem::class)->orderBy('sort')->orderBy('id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(GroupOrder::class);
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
        return $this->status() === OpeningStatus::Open;
    }

    public function scopeOpen(Builder $query): void
    {
        $query->where('opens_at', '<=', now())->where('closes_at', '>=', now());
    }
}
