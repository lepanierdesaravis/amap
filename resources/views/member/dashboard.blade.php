<x-layouts.app title="Mon espace">
    <x-member-nav />
    <div class="mx-auto max-w-6xl px-4 py-8">
        <p class="kicker">Bonjour {{ $user->first_name }} !</p>
        <h1 class="text-3xl font-extrabold">Mon tableau de bord</h1>

        @unless ($canOrder)
            <div class="mt-6 rounded-2xl bg-miel-clair p-5 ring-1 ring-miel/40">
                <p class="text-lg font-bold">Votre adhésion n'est pas encore validée</p>
                <p class="mt-1 text-doux">Réglez votre cotisation de {{ \App\Support\Money::format(config('amap.membership_fee_cents')) }} lors d'une permanence ou par virement. Dès qu'elle est enregistrée, vous pouvez souscrire vos contrats et commander.</p>
                <a href="{{ route('adherer') }}" class="btn-primary mt-3">Comment adhérer ?</a>
            </div>
        @endunless

        <div class="mt-6 grid gap-5 lg:grid-cols-3">
            {{-- Prochaine distribution --}}
            <section class="card lg:col-span-2">
                <p class="text-sm font-bold text-doux">📅 Prochaine distribution</p>
                @if ($next)
                    <h2 class="mt-1 text-2xl font-bold">{{ $next->label() }}</h2>
                    <p class="text-doux">{{ $next->timeRange() }} · {{ $next->place }}</p>
                    @if ($nextDeliveries->isNotEmpty())
                        <p class="mt-4 font-bold">Je récupère :</p>
                        <ul class="mt-2 grid gap-2 sm:grid-cols-2">
                            @foreach ($nextDeliveries as $d)
                                <li class="flex items-center gap-3 rounded-xl bg-vert-clair px-3 py-2">
                                    <span class="text-2xl" aria-hidden="true">{{ $d->icon ?: '🧺' }}</span>
                                    <span><strong>{{ $d->quantity }} × {{ $d->label }}</strong><br><span class="text-sm text-doux">{{ $d->producer->name }}</span></span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 rounded-xl bg-creme px-3 py-2 text-doux">Rien à récupérer pour vous à cette date.</p>
                    @endif
                    <a href="{{ route('espace.calendrier') }}" class="mt-4 inline-block font-bold text-vert">Mon calendrier ({{ $upcomingDeliveryDates }} dates à venir) →</a>
                @else
                    <p class="mt-1 text-doux">Aucune distribution prévue pour le moment.</p>
                @endif
            </section>

            {{-- Paiements --}}
            <section class="card">
                <p class="text-sm font-bold text-doux">💶 Mes prochains règlements</p>
                @forelse ($nextInstallments as $i)
                    <div class="mt-3 flex items-center justify-between gap-2 text-sm">
                        <span>{{ ucfirst($i->due_on->translatedFormat('F')) }} · {{ $i->producer->name }}</span>
                        <span class="text-right"><strong>{{ \App\Support\Money::format($i->amount_cents) }}</strong><br><span class="badge bg-creme text-doux">{{ $i->status->getLabel() }}</span></span>
                    </div>
                @empty
                    <p class="mt-2 text-sm text-doux">Aucun règlement à venir.</p>
                @endforelse
                <a href="{{ route('espace.paiements') }}" class="mt-4 inline-block text-sm font-bold text-vert">Tous mes paiements →</a>
            </section>
        </div>

        <div class="mt-5 grid gap-5 md:grid-cols-2">
            <section class="card">
                <p class="text-sm font-bold text-doux">🛍️ Commandes groupées ouvertes</p>
                @forelse ($openPurchases as $p)
                    <a href="{{ route('espace.commandes-groupees.show', $p) }}" class="mt-3 flex items-center justify-between gap-3 rounded-xl bg-terre-clair px-4 py-3 no-underline hover:ring-1 hover:ring-terre">
                        <span><strong>{{ $p->title }}</strong><br><span class="text-sm text-doux">jusqu'au {{ $p->closes_at->translatedFormat('j F à H\hi') }}</span></span>
                        <span class="badge {{ $myOrders->contains($p->id) ? 'bg-vert text-white' : 'bg-terre text-white' }}">{{ $myOrders->contains($p->id) ? '✓ Commandé' : 'Commander' }}</span>
                    </a>
                @empty
                    <p class="mt-2 text-sm text-doux">Pas de commande groupée en ce moment. Vous recevrez un e-mail à la prochaine.</p>
                @endforelse
            </section>
            <section class="card">
                <p class="text-sm font-bold text-doux">🧺 Contrats ouverts aux souscriptions</p>
                @forelse ($openOffers as $o)
                    <a href="{{ route('espace.contrats.show', $o) }}" class="mt-3 flex items-center justify-between gap-3 rounded-xl bg-vert-clair px-4 py-3 no-underline hover:ring-1 hover:ring-vert">
                        <span><strong>{{ $o->producer->name }}</strong><br><span class="text-sm text-doux">{{ $o->title }} · jusqu'au {{ $o->closes_at->translatedFormat('j F') }}</span></span>
                        <span class="badge {{ $myContracts->contains($o->id) ? 'bg-vert text-white' : 'bg-terre text-white' }}">{{ $myContracts->contains($o->id) ? '✓ Souscrit' : 'Souscrire' }}</span>
                    </a>
                @empty
                    <p class="mt-2 text-sm text-doux">Aucune souscription ouverte en ce moment.</p>
                @endforelse
            </section>
        </div>
    </div>
</x-layouts.app>
