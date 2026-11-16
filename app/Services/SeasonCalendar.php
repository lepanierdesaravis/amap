<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\Distribution;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Calendrier mois par mois, avec les icônes de ce que l'adhérent récupère à chaque distribution.
 * Utilisé par l'écran de souscription et par « Mon calendrier ».
 */
class SeasonCalendar
{
    /**
     * Une entrée par produit livré : icône, libellé, producteur.
     * `current` distingue le contrat en cours de souscription (icône plus grande).
     *
     * @return array{icon: ?string, color: ?string, label: string, producer: string, current: bool}
     */
    public static function entry(?string $icon, ?string $color, string $label, string $producer, bool $current = false): array
    {
        return compact('icon', 'color', 'label', 'producer', 'current');
    }

    /**
     * @param  Collection<int, Delivery>  $deliveries  avec la relation producer
     * @return array<int, list<array>> identifiant de distribution => entrées
     */
    public static function entriesFromDeliveries(Collection $deliveries, bool $current = false): array
    {
        return $deliveries->groupBy('distribution_id')
            ->map(fn (Collection $items) => $items->map(fn (Delivery $d) => self::entry(
                $d->icon, $d->icon_color, $d->quantity.' × '.$d->label, $d->producer->name, $current,
            ))->values()->all())
            ->all();
    }

    /**
     * @param  Collection<int, Distribution>  $distributions
     * @param  array<int, list<array>>  $entries
     * @return list<array{label: string, cells: list<?array>}>
     */
    public function months(Carbon $from, Carbon $to, Collection $distributions, array $entries): array
    {
        $byDate = $distributions->keyBy(fn (Distribution $d) => $d->date->toDateString());

        $months = [];
        for ($month = $from->copy()->startOfMonth(); $month->lte($to); $month->addMonth()) {
            $cells = array_fill(0, $month->dayOfWeekIso - 1, null);
            for ($day = $month->copy(); $day->month === $month->month; $day->addDay()) {
                $distribution = $byDate->get($day->toDateString());
                $cells[] = [
                    'day' => $day->day,
                    'date' => ucfirst($day->translatedFormat('l j F')),
                    'column' => $day->dayOfWeekIso - 1,
                    'past' => $day->lt(today()),
                    'distribution' => $distribution?->id,
                    'entries' => $distribution ? ($entries[$distribution->id] ?? []) : [],
                ];
            }
            $months[] = ['label' => ucfirst($month->translatedFormat('F Y')), 'cells' => $cells];
        }

        return $months;
    }

    /**
     * Légende : une ligne par icône différente.
     *
     * @param  array<int, list<array>>  $entries
     * @return list<array>
     */
    public function legend(array $entries): array
    {
        return collect($entries)->flatten(1)
            ->unique(fn ($e) => $e['icon'].'|'.$e['color'].'|'.$e['producer'])
            ->map(fn ($e) => $e + ['name' => preg_replace('/^\d+ × /', '', $e['label'])])
            ->sortByDesc('current')->values()->all();
    }
}
