<?php

namespace App\Services;

use App\Models\Distribution;
use App\Models\Producer;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Documents d'une distribution : récapitulatif producteur et feuilles d'émargement.
 * Tout est calculé à partir de la table des livraisons.
 */
class DistributionDocuments
{
    /**
     * @return array{totals: Collection, members: Collection}
     */
    public function summary(Distribution $distribution, Producer $producer): array
    {
        $deliveries = $distribution->deliveries()
            ->where('producer_id', $producer->id)
            ->with('user')
            ->get();

        return [
            'totals' => $deliveries->groupBy('label')
                ->map(fn ($group) => $group->sum('quantity'))
                ->sortKeys(),
            'members' => $deliveries->groupBy('user_id')
                ->map(fn ($group) => [
                    'user' => $group->first()->user,
                    'lines' => $group->groupBy('label')->map(fn ($g) => $g->sum('quantity'))->sortKeys(),
                ])
                ->sortBy(fn ($m) => Str::lower($m['user']->sort_name))
                ->values(),
        ];
    }

    /**
     * Une feuille par produit : adhérent / quantité / signature.
     *
     * @return Collection<string, Collection> libellé du produit => lignes triées par nom
     */
    public function signInSheets(Distribution $distribution, ?Producer $producer = null): Collection
    {
        return $distribution->deliveries()
            ->when($producer, fn ($q) => $q->where('producer_id', $producer->id))
            ->with(['user', 'producer'])
            ->get()
            ->groupBy(fn ($d) => $d->producer->name.' — '.$d->label)
            ->sortKeys()
            ->map(fn ($group) => $group->groupBy('user_id')
                ->map(fn ($g) => ['user' => $g->first()->user, 'quantity' => $g->sum('quantity')])
                ->sortBy(fn ($row) => Str::lower($row['user']->sort_name))
                ->values());
    }

    public function summaryPdf(Distribution $distribution, Producer $producer): DomPdf
    {
        return Pdf::loadView('pdf.summary', [
            'distribution' => $distribution,
            'producer' => $producer,
            ...$this->summary($distribution, $producer),
        ])->setPaper('a4');
    }

    public function signInSheetsPdf(Distribution $distribution, ?Producer $producer = null): DomPdf
    {
        return Pdf::loadView('pdf.sign-in-sheets', [
            'distribution' => $distribution,
            'producer' => $producer,
            'sheets' => $this->signInSheets($distribution, $producer),
        ])->setPaper('a4');
    }

    public function filename(string $kind, Distribution $distribution, ?Producer $producer = null): string
    {
        return collect([$kind, $distribution->date->format('Y-m-d'), $producer?->slug])->filter()->implode('-').'.pdf';
    }
}
