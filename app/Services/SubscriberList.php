<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractOffer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Liste des souscripteurs d'un contrat (prénom, nom, e-mail, engagement),
 * pour le référent et, s'il l'autorise, pour le producteur.
 */
class SubscriberList
{
    /** @return Collection<int, array{first_name: string, last_name: string, email: string, engagement: string}> */
    public function rows(ContractOffer $offer): Collection
    {
        return $offer->contracts()->with(['user', 'lines.product'])->get()
            ->sortBy(fn (Contract $c) => Str::ascii($c->user->sort_name))
            ->map(fn (Contract $c) => [
                'first_name' => $c->user->first_name,
                'last_name' => $c->user->last_name,
                'email' => $c->user->email,
                'engagement' => $c->lines->map(fn ($l) => $l->is_amount ? $l->product->name : "{$l->quantity} × {$l->product->name}")->join(', '),
            ])->values();
    }

    public function csv(ContractOffer $offer): StreamedResponse
    {
        $rows = $this->rows($offer);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM : accents corrects à l'ouverture dans un tableur
            fputcsv($out, ['Prénom', 'Nom', 'E-mail', 'Engagement'], ';');
            foreach ($rows as $row) {
                fputcsv($out, array_values($row), ';');
            }
            fclose($out);
        }, $this->filename($offer, 'csv'), ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function pdf(ContractOffer $offer): Response
    {
        return Pdf::loadView('pdf.subscribers', ['offer' => $offer, 'rows' => $this->rows($offer)])
            ->setPaper('a4')->download($this->filename($offer, 'pdf'));
    }

    private function filename(ContractOffer $offer, string $extension): string
    {
        return 'adherents-'.Str::slug($offer->producer->name.'-'.$offer->title).'.'.$extension;
    }
}
