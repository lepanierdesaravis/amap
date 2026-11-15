<?php

namespace App\Models;

use App\Enums\DistributionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distribution extends Model
{
    protected $fillable = ['date', 'starts_at', 'ends_at', 'place', 'status', 'notes', 'closed_at', 'summaries_sent_at'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => DistributionStatus::class,
            'closed_at' => 'datetime',
            'summaries_sent_at' => 'datetime',
        ];
    }

    public function scopeUpcoming(Builder $query): void
    {
        $query->whereDate('date', '>=', today())->orderBy('date');
    }

    public function producers(): BelongsToMany
    {
        return $this->belongsToMany(Producer::class)->orderBy('name');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    /** Numéro de la distribution dans l'année (n°1 = première de janvier). */
    public function number(): int
    {
        return static::whereYear('date', $this->date->year)->whereDate('date', '<=', $this->date)->count();
    }

    public function label(): string
    {
        return ucfirst($this->date->translatedFormat('l j F Y'));
    }

    public function shortLabel(): string
    {
        return $this->date->translatedFormat('j M');
    }

    public function timeRange(): string
    {
        $format = fn (string $time) => ltrim(str_replace(':', 'h', substr($time, 0, 5)), '0');

        return $format($this->starts_at).' – '.$format($this->ends_at);
    }
}
