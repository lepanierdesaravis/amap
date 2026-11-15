<?php

namespace App\Models;

use App\Enums\InstallmentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Installment extends Model
{
    protected $fillable = [
        'user_id', 'producer_id', 'payable_type', 'payable_id', 'number', 'amount_cents', 'due_on',
        'payment_method', 'status', 'cheque_number', 'marked_by', 'marked_at',
    ];

    protected function casts(): array
    {
        return [
            'due_on' => 'date',
            'payment_method' => PaymentMethod::class,
            'status' => InstallmentStatus::class,
            'marked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function mark(InstallmentStatus $status, User $by): void
    {
        $this->update(['status' => $status, 'marked_by' => $by->id, 'marked_at' => now()]);
    }
}
