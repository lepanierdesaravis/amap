<x-layouts.app title="Mes paiements">
    <x-member-nav />
    <div class="mx-auto max-w-4xl px-4 py-8">
        <h1 class="text-3xl font-extrabold">Mes paiements</h1>
        <p class="mt-1 text-doux">Vos règlements vont directement aux producteurs. Le producteur ou son référent indique ici quand il les a reçus puis encaissés.</p>
        @forelse ($installments as $producer => $items)
            <section class="card mt-6">
                <h2 class="text-lg font-bold">{{ $producer }}</h2>
                <ul class="mt-3 divide-y divide-brun/10">
                    @foreach ($items as $i)
                        <li class="flex flex-wrap items-center justify-between gap-2 py-2 text-sm">
                            <span>{{ ucfirst($i->due_on->translatedFormat('F Y')) }} · {{ $i->payment_method->getLabel() }}{{ $i->cheque_number ? ' n° '.$i->cheque_number : '' }}</span>
                            <span class="flex items-center gap-3">
                                <strong>{{ \App\Support\Money::format($i->amount_cents) }}</strong>
                                <span @class(['badge', 'bg-creme text-doux' => $i->status->value === 'attendu', 'bg-miel-clair text-[#8d6400]' => $i->status->value === 'recu', 'bg-vert-clair text-vert-fonce' => $i->status->value === 'encaisse'])>{{ $i->status->getLabel() }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @empty
            <p class="mt-6 text-doux">Aucun paiement pour le moment.</p>
        @endforelse
    </div>
</x-layouts.app>
