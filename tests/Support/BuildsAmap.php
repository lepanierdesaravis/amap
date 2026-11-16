<?php

namespace Tests\Support;

use App\Enums\DistributionStatus;
use App\Enums\Frequency;
use App\Enums\Participation;
use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Models\ContractOffer;
use App\Models\Distribution;
use App\Models\GroupPurchase;
use App\Models\Producer;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/** Petites briques pour construire une AMAP de test sans passer par le jeu de démonstration. */
trait BuildsAmap
{
    protected function setUpRoles(): void
    {
        $this->seed(RoleSeeder::class);
    }

    protected function member(bool $paid = true): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::Member->value);
        if ($paid) {
            $user->memberships()->create([
                'year' => now()->year,
                'amount_cents' => 1500,
                'payment_method' => PaymentMethod::Cheque,
                'paid_at' => now(),
            ]);
        }

        return $user;
    }

    protected function userWithRole(Role $role): User
    {
        return tap($this->member(), fn (User $u) => $u->assignRole($role->value));
    }

    protected function producer(?User $referent = null, array $attributes = []): Producer
    {
        $name = $attributes['name'] ?? 'Ferme '.Str::random(6);

        return Producer::create($attributes + [
            'name' => $name,
            'slug' => Str::slug($name),
            'participation' => Participation::Both,
            'referent_id' => $referent?->id,
            'is_published' => true,
        ]);
    }

    /** Distributions tous les 15 jours à partir d'une date, auxquelles le producteur participe. */
    protected function distributions(Producer $producer, int $count, string $first = '+1 month'): Collection
    {
        $date = Carbon::parse($first)->startOfDay();

        return collect(range(0, $count - 1))->map(function (int $i) use ($date, $producer) {
            $distribution = Distribution::create([
                'date' => $date->copy()->addWeeks(2 * $i),
                'place' => 'Thônes',
                'status' => DistributionStatus::Planned,
            ]);
            $distribution->producers()->attach($producer);

            return $distribution;
        });
    }

    protected function product(Producer $producer, int $priceCents = 1500): Product
    {
        return $producer->products()->create(['name' => 'Panier', 'unit' => 'panier', 'price_cents' => $priceCents, 'icon' => 'carotte', 'icon_color' => 'vert']);
    }

    protected function contractOffer(Producer $producer, array $attributes = []): ContractOffer
    {
        $offer = ContractOffer::create($attributes + [
            'producer_id' => $producer->id,
            'title' => 'Contrat '.now()->addYear()->year,
            'starts_on' => now()->addMonth()->startOfMonth(),
            'ends_on' => now()->addYear(),
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addWeek(),
            'frequencies' => [Frequency::Every->value, Frequency::Alternate->value, Frequency::Custom->value],
            'payment_plans' => [['installments' => 1, 'months' => [3]], ['installments' => 3, 'months' => [3, 6, 9]]],
            'is_published' => true,
        ]);
        $offer->products()->attach($producer->products()->pluck('id'));

        return $offer;
    }

    protected function groupPurchase(Producer $producer, Distribution $distribution, array $attributes = []): GroupPurchase
    {
        return GroupPurchase::create($attributes + [
            'title' => 'Huile d\'olive',
            'producer_id' => $producer->id,
            'referent_id' => $producer->referent_id,
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addWeek(),
            'distribution_id' => $distribution->id,
        ]);
    }
}
