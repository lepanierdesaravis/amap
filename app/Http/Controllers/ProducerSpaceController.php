<?php

namespace App\Http\Controllers;

use App\Enums\InstallmentStatus;
use App\Models\Distribution;
use App\Models\Installment;
use App\Models\Producer;
use App\Services\DistributionDocuments;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Espace producteur : consultation des distributions et pointage des paiements.
 * Les modifications de la fiche passent par le référent (décision du 15/11/2026).
 */
class ProducerSpaceController extends Controller
{
    public function index(Request $request, DistributionDocuments $documents): View
    {
        $producer = $this->producer($request);

        $distributions = $producer->distributions()->whereDate('date', '>=', today())->orderBy('date')->take(6)->get();

        return view('producer.index', [
            'producer' => $producer,
            'distributions' => $distributions->map(fn (Distribution $d) => ['distribution' => $d, 'summary' => $documents->summary($d, $producer)]),
            'installments' => $producer->installments()->with('user')->orderBy('status')->orderBy('due_on')->get(),
        ]);
    }

    public function summary(Request $request, Distribution $distribution, DistributionDocuments $documents): Response
    {
        $producer = $this->producer($request);

        return $documents->summaryPdf($distribution, $producer)->download($documents->filename('recapitulatif', $distribution, $producer));
    }

    public function signInSheets(Request $request, Distribution $distribution, DistributionDocuments $documents): Response
    {
        $producer = $this->producer($request);

        return $documents->signInSheetsPdf($distribution, $producer)->download($documents->filename('emargement', $distribution, $producer));
    }

    public function mark(Request $request, Installment $installment): RedirectResponse
    {
        Gate::authorize('update', $installment);
        $data = $request->validate(['status' => ['required', Rule::enum(InstallmentStatus::class)]]);
        $installment->mark(InstallmentStatus::from($data['status']), $request->user());

        return back()->with('status', 'Paiement pointé.');
    }

    private function producer(Request $request): Producer
    {
        return $request->user()->producer ?? abort(404, 'Aucune fiche producteur n\'est rattachée à ce compte.');
    }
}
