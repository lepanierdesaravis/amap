<?php

namespace App\Models;

use App\Enums\MessageAudience;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Message envoyé depuis « Échanges entre adhérents » (gardé pour la modération). */
class MemberMessage extends Model
{
    protected $fillable = ['sender_id', 'audience', 'producer_id', 'subject', 'body', 'recipients_count'];

    protected function casts(): array
    {
        return ['audience' => MessageAudience::class];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }
}
