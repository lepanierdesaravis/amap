<x-layouts.app title="Commandes groupées">
    <x-member-nav />
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="text-3xl font-extrabold">Commandes groupées</h1>
        <p class="mt-1 text-doux">Des commandes ponctuelles (vin, huile, agrumes, farine…), livrées lors d'une distribution.</p>

        <h2 class="mt-8 text-xl font-bold">En cours</h2>
        <div class="mt-3 grid gap-5 md:grid-cols-2">
            @forelse ($open as $p)
                <a href="{{ route('espace.commandes-groupees.show', $p) }}" class="card flex flex-col no-underline transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="kicker text-xl">{{ $p->producer->name }}</p>
                    <h3 class="text-xl font-bold">{{ $p->title }}</h3>
                    <p class="mt-2 text-sm text-doux">Commandes jusqu'au <strong>{{ $p->closes_at->translatedFormat('l j F à H\hi') }}</strong><br>Livraison le {{ $p->distribution->date->translatedFormat('l j F') }}</p>
                    <span class="btn-primary mt-4 self-start">{{ $myOrders->contains('group_purchase_id', $p->id) ? 'Voir ou modifier ma commande' : 'Commander' }}</span>
                </a>
            @empty
                <p class="text-doux">Aucune commande groupée ouverte en ce moment.</p>
            @endforelse
        </div>

        @if ($upcoming->isNotEmpty())
            <h2 class="mt-10 text-xl font-bold">Bientôt</h2>
            <ul class="mt-3 space-y-2">
                @foreach ($upcoming as $p)<li class="card py-3">{{ $p->title }} ({{ $p->producer->name }}) — ouverture le {{ $p->opens_at->translatedFormat('j F') }}</li>@endforeach
            </ul>
        @endif

        @if ($myOrders->isNotEmpty())
            <h2 class="mt-10 text-xl font-bold">Mes commandes</h2>
            <div class="mt-3 overflow-x-auto rounded-2xl bg-white ring-1 ring-brun/10">
                <table class="w-full text-sm">
                    <thead class="bg-brun text-left text-white"><tr><th class="px-4 py-2">Commande</th><th class="px-4 py-2">Articles</th><th class="px-4 py-2">Livraison</th><th class="px-4 py-2 text-right">Total</th></tr></thead>
                    <tbody class="divide-y divide-brun/10">
                        @foreach ($myOrders as $o)
                            <tr>
                                <td class="px-4 py-2 font-bold"><a href="{{ route('espace.commandes-groupees.show', $o->groupPurchase) }}" class="text-vert">{{ $o->groupPurchase->title }}</a></td>
                                <td class="px-4 py-2">{{ $o->lines->map(fn ($l) => $l->quantity.' × '.$l->item->name)->implode(', ') }}</td>
                                <td class="px-4 py-2">{{ $o->groupPurchase->distribution->date->translatedFormat('j M Y') }}</td>
                                <td class="px-4 py-2 text-right font-bold">{{ \App\Support\Money::format($o->total_cents) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layouts.app>
