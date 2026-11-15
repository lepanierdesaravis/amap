<?php

namespace App\Services;

use App\Enums\Frequency;
use App\Models\ContractOffer;
use App\Models\Distribution;
use App\Support\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Calculs d'un contrat : dates de livraison et échéancier.
 * Aucun accès en écriture à la base : utilisé en direct par l'écran de souscription.
 */
class ContractPlanner
{
    /**
     * Distributions retenues pour une ligne de contrat.
     *
     * @param  Collection<int, Distribution>  $distributions  distributions de l'offre, triées par date
     * @param  int  $alternateStart  0 = à partir de la première date, 1 = à partir de la deuxième
     * @param  list<int>  $customIds  dates choisies par l'adhérent (fréquence « au choix »)
     * @return list<int>
     */
    public function distributionIds(Collection $distributions, Frequency $frequency, int $alternateStart = 0, array $customIds = []): array
    {
        $ids = $distributions->pluck('id')->values();

        return match ($frequency) {
            Frequency::Every => $ids->all(),
            Frequency::Alternate => $ids->filter(fn ($id, $i) => $i % 2 === $alternateStart % 2)->values()->all(),
            Frequency::Custom => $ids->intersect($customIds)->values()->all(),
        };
    }

    /**
     * Échéances : montants (centimes restants sur la dernière) et dates d'encaissement.
     *
     * @return list<array{number: int, amount_cents: int, due_on: Carbon}>
     */
    public function installments(ContractOffer $offer, int $totalCents, int $count): array
    {
        if ($totalCents <= 0) {
            return [];
        }

        $plan = collect($offer->paymentPlans())->firstWhere('installments', $count)
            ?? ['installments' => 1, 'months' => [$offer->starts_on->month]];

        $months = $plan['months'] ?: [$offer->starts_on->month];
        $amounts = Money::split($totalCents, $plan['installments']);

        return collect($amounts)->map(function (int $amount, int $i) use ($months, $offer) {
            $month = $months[$i] ?? end($months);

            return [
                'number' => $i + 1,
                'amount_cents' => $amount,
                'due_on' => Carbon::create($offer->starts_on->year, $month, 1),
            ];
        })->all();
    }
}
