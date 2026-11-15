<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GroupOrder extends Model
{
    protected $fillable = ['group_purchase_id', 'user_id', 'total_cents', 'validated_at'];

    protected function casts(): array
    {
        return ['validated_at' => 'datetime'];
    }

    public function groupPurchase(): BelongsTo
    {
        return $this->belongsTo(GroupPurchase::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(GroupOrderLine::class);
    }

    public function installmentsDue(): MorphMany
    {
        return $this->morphMany(Installment::class, 'payable')->orderBy('number');
    }
}
