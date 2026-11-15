<?php

namespace App\Models;

use App\Enums\Participation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Producer extends Model
{
    protected $fillable = [
        'name', 'slug', 'farmer_names', 'address', 'city', 'phone', 'email', 'website',
        'social_links', 'tagline', 'description', 'cover_photo', 'photos', 'documents',
        'product_types', 'labels', 'participation', 'cheque_payee', 'iban', 'siret',
        'referent_id', 'user_id', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'photos' => 'array',
            'documents' => 'array',
            'product_types' => 'array',
            'labels' => 'array',
            'participation' => Participation::class,
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // L'adresse de la fiche publique est générée à partir du nom.
        static::saving(function (Producer $producer) {
            if (blank($producer->slug)) {
                $producer->slug = static::uniqueSlug($producer->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function referent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referent_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort')->orderBy('name');
    }

    public function contractOffers(): HasMany
    {
        return $this->hasMany(ContractOffer::class);
    }

    public function groupPurchases(): HasMany
    {
        return $this->hasMany(GroupPurchase::class);
    }

    public function distributions(): BelongsToMany
    {
        return $this->belongsToMany(Distribution::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function coverUrl(): ?string
    {
        $path = $this->cover_photo ?? ($this->photos[0] ?? null);

        return $path ? Storage::disk('public')->url($path) : null;
    }

    /** @return list<string> */
    public function productTypeLabels(): array
    {
        return collect($this->product_types ?? [])
            ->map(fn ($key) => config("amap.product_types.$key", $key))
            ->values()->all();
    }
}
