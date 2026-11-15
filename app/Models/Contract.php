<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Contract extends Model
{
    protected $fillable = ['user_id', 'contract_offer_id', 'installments', 'payment_method', 'total_cents', 'validated_at'];

    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'validated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(ContractOffer::class, 'contract_offer_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(ContractLine::class);
    }

    public function installmentsDue(): MorphMany
    {
        return $this->morphMany(Installment::class, 'payable')->orderBy('number');
    }
}
