<x-layouts.app title="Espace producteur">
    <x-member-nav />
    <div class="mx-auto max-w-6xl px-4 py-8">
        <p class="kicker">Espace producteur</p>
        <h1 class="text-3xl font-extrabold">{{ $producer->name }}</h1>
        <p class="mt-1 text-doux">Pour modifier votre fiche ou vos produits, contactez votre référent{{ $producer->referent ? ' : '.$producer->referent->name : '' }}.</p>

        <h2 class="mt-8 text-xl font-bold">Mes prochaines distributions</h2>
        <div class="mt-3 grid gap-5 md:grid-cols-2">
            @forelse ($distributions as ['distribution' => $d, 'summary' => $summary])
                <section class="card">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <h3 class="text-lg font-bold">{{ $d->label() }}</h3>
                        <span class="text-sm text-doux">{{ $summary['members']->count() }} adhérent(s)</span>
                    </div>
                    @if ($summary['totals']->isNotEmpty())
                        <ul class="mt-3 space-y-1">
                            @foreach ($summary['totals'] as $label => $qty)
                                <li class="flex justify-between rounded-lg bg-creme px-3 py-1.5"><span>{{ $label }}</span><strong>{{ $qty }}</strong></li>
                            @endforeach
                        </ul>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('producteur.recapitulatif', $d) }}" class="btn-vert py-2 text-sm">⬇ Récapitulatif PDF</a>
                            <a href="{{ route('producteur.emargement', $d) }}" class="btn-ghost py-2 text-sm">⬇ Feuilles d'émargement</a>
                        </div>
                    @else
                        <p class="mt-3 text-sm text-doux">Aucune commande pour cette date.</p>
                    @endif
                </section>
            @empty
                <p class="text-doux">Aucune distribution prévue.</p>
            @endforelse
        </div>

        @if ($sharedOffers->isNotEmpty())
            <h2 class="mt-10 text-xl font-bold">Mes adhérents engagés</h2>
            <div class="mt-3 grid gap-3 md:grid-cols-2">
                @foreach ($sharedOffers as $offer)
                    <section class="card flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="font-bold">{{ $offer->title }}</h3>
                            <p class="text-sm text-doux">{{ $offer->contracts_count }} adhérent(s) · du {{ $offer->starts_on->translatedFormat('j M') }} au {{ $offer->ends_on->translatedFormat('j M Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('producteur.adherents', [$offer, 'pdf']) }}" class="btn-vert py-2 text-sm">⬇ PDF</a>
                            <a href="{{ route('producteur.adherents', [$offer, 'csv']) }}" class="btn-ghost py-2 text-sm">⬇ Tableur</a>
                        </div>
                    </section>
                @endforeach
            </div>
        @endif

        <h2 class="mt-10 text-xl font-bold">Pointage de mes paiements</h2>
        <div class="mt-3 overflow-x-auto rounded-2xl bg-white ring-1 ring-brun/10">
            <table class="w-full text-sm">
                <thead class="bg-brun text-left text-white"><tr><th class="px-4 py-2">Adhérent</th><th class="px-4 py-2">Encaissement</th><th class="px-4 py-2">Mode</th><th class="px-4 py-2 text-right">Montant</th><th class="px-4 py-2">État</th><th class="px-4 py-2"></th></tr></thead>
                <tbody class="divide-y divide-brun/10">
                    @forelse ($installments as $i)
                        <tr>
                            <td class="px-4 py-2 font-bold">{{ $i->user->sort_name }}</td>
                            <td class="px-4 py-2">{{ ucfirst($i->due_on->translatedFormat('F Y')) }}</td>
                            <td class="px-4 py-2">{{ $i->payment_method->getLabel() }}</td>
                            <td class="px-4 py-2 text-right font-bold">{{ \App\Support\Money::format($i->amount_cents) }}</td>
                            <td class="px-4 py-2"><span class="badge {{ ['attendu' => 'bg-creme text-doux', 'recu' => 'bg-miel-clair', 'encaisse' => 'bg-vert-clair text-vert-fonce'][$i->status->value] }}">{{ $i->status->getLabel() }}</span></td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('producteur.pointage', $i) }}" class="flex gap-1">
                                    @csrf
                                    @if ($i->status->value === 'attendu')<button name="status" value="recu" class="rounded-lg bg-miel-clair px-2 py-1 text-xs font-bold">Reçu</button>@endif
                                    @if ($i->status->value !== 'encaisse')<button name="status" value="encaisse" class="rounded-lg bg-vert px-2 py-1 text-xs font-bold text-white">Encaissé</button>@endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-3 text-doux">Aucun paiement à pointer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
