<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupPurchaseItem extends Model
{
    protected $fillable = ['group_purchase_id', 'name', 'packaging', 'price_cents', 'max_per_member', 'sort'];

    public function groupPurchase(): BelongsTo
    {
        return $this->belongsTo(GroupPurchase::class);
    }

    public function fullName(): string
    {
        return $this->packaging ? "{$this->name} ({$this->packaging})" : $this->name;
    }
}
