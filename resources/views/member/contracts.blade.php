<x-layouts.app title="Mes contrats">
    <x-member-nav />
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="text-3xl font-extrabold">Mes contrats</h1>
        <p class="mt-1 text-doux">Un contrat vous engage pour la saison auprès d'un producteur. Choisissez vos produits et leur rythme : vos dates s'affichent automatiquement.</p>

        <div class="mt-6 grid gap-5 md:grid-cols-2">
            @forelse ($offers as $offer)
                @php($contract = $contracts->get($offer->id))
                <article class="card flex flex-col">
                    <div class="flex items-start gap-4">
                        <div class="size-16 shrink-0 overflow-hidden rounded-xl bg-vert-clair">
                            @if ($offer->producer->coverUrl())<img src="{{ $offer->producer->coverUrl() }}" alt="" class="h-full w-full object-cover">@endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold">{{ $offer->producer->name }}</h2>
                            <p class="text-sm text-doux">{{ $offer->title }} · {{ $offer->starts_on->translatedFormat('j M') }} → {{ $offer->ends_on->translatedFormat('j M Y') }}</p>
                        </div>
                        <span class="badge {{ $offer->isOpen() ? 'bg-vert-clair text-vert-fonce' : 'bg-brun/10 text-brun' }}">{{ $offer->status()->getLabel() }}</span>
                    </div>

                    @if ($contract)
                        <ul class="mt-4 space-y-1 rounded-xl bg-vert-clair p-3 text-sm">
                            @foreach ($contract->lines as $line)
                                <li>✓ {{ $line->quantity }} × {{ $line->product->name }} — {{ $line->frequency->shortLabel() }} ({{ count($line->distribution_ids) }} livraisons)</li>
                            @endforeach
                            <li class="pt-1 font-bold">Total : {{ \App\Support\Money::format($contract->total_cents) }} en {{ $contract->installments }} fois</li>
                        </ul>
                    @endif

                    <div class="mt-auto pt-4">
                        @if ($offer->isOpen() && $canOrder)
                            <a href="{{ route('espace.contrats.show', $offer) }}" class="{{ $contract ? 'btn-ghost' : 'btn-primary' }}">{{ $contract ? 'Modifier mon contrat' : 'Souscrire' }}</a>
                            <span class="ml-2 text-xs text-doux">jusqu'au {{ $offer->closes_at->translatedFormat('j F à H\hi') }}</span>
                        @elseif ($contract)
                            <a href="{{ route('espace.contrats.show', $offer) }}" class="btn-ghost">Voir mon contrat</a>
                        @elseif (! $offer->isOpen())
                            <p class="text-sm text-doux">{{ $offer->status()->getLabel() === 'À venir' ? 'Souscriptions à partir du '.$offer->opens_at->translatedFormat('j F') : 'Souscriptions clôturées.' }}</p>
                        @else
                            <p class="text-sm text-doux">Souscription possible dès que votre adhésion est validée.</p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="text-doux">Aucun contrat n'est proposé pour le moment.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
