<?php

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'phone', 'phone_secondary', 'address', 'postal_code', 'city', 'share_phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'share_phone' => 'boolean',
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->first_name} {$this->last_name}"));
    }

    /** « Nom Prénom », pour les listes triées et les feuilles d'émargement. */
    protected function sortName(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->last_name} {$this->first_name}"));
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    /** Le back-office est réservé aux référents et administrateurs. */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() || $this->isReferent();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::Admin->value);
    }

    public function isReferent(): bool
    {
        return $this->hasRole(Role::Referent->value);
    }

    public function isProducer(): bool
    {
        return $this->hasRole(Role::Producer->value);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function hasPaidMembership(?int $year = null): bool
    {
        return $this->memberships()
            ->where('year', $year ?? now()->year)
            ->whereNotNull('paid_at')
            ->exists();
    }

    /** Peut commander : adhésion de l'année en cours ou de la suivante (campagne de décembre). */
    public function canOrder(): bool
    {
        return $this->memberships()
            ->whereIn('year', [now()->year, now()->year + 1])
            ->whereNotNull('paid_at')
            ->exists();
    }

    /** Producteurs gérés par ce référent (celui qui les a créés). */
    public function managedProducers(): HasMany
    {
        return $this->hasMany(Producer::class, 'referent_id');
    }

    /** Fiche producteur rattachée au compte de l'espace producteur. */
    public function producer(): HasOne
    {
        return $this->hasOne(Producer::class, 'user_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function groupOrders(): HasMany
    {
        return $this->hasMany(GroupOrder::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }
}
