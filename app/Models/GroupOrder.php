<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GroupOrder extends Model
{
    protected $fillable = ['group_purchase_id', 'user_id', 'contract_id', 'total_cents', 'validated_at'];

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

    /** Engagement sur lequel la commande est déduite (pas de paiement à part). */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
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
