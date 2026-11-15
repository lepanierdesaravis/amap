<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GroupOrderLine extends Model
{
    protected $fillable = ['group_order_id', 'group_purchase_item_id', 'quantity', 'unit_price_cents'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GroupOrder::class, 'group_order_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GroupPurchaseItem::class, 'group_purchase_item_id');
    }

    public function deliveries(): MorphMany
    {
        return $this->morphMany(Delivery::class, 'source');
    }
}
