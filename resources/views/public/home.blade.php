<x-layouts.app>
    {{-- Accroche --}}
    <section class="relative overflow-hidden">
        <img src="{{ asset('images/accueil.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-creme via-creme/90 to-creme/10"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 md:py-24">
            <div class="max-w-xl">
                <p class="kicker text-3xl">AMAP de {{ config('amap.city') }}</p>
                <h1 class="mt-1 text-4xl leading-tight font-extrabold md:text-5xl">Des paniers de nos fermes, en direct, tous les 15 jours.</h1>
                <p class="mt-4 text-lg text-doux">Légumes, fromages, pain, œufs, viande, fruits… Vous vous engagez pour une saison auprès de paysans d'ici, qui vivent de leur travail à un prix juste.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('adherer') }}" class="btn-primary px-6 py-3 text-lg">Adhérer à l'AMAP</a>
                    <a href="{{ route('producteurs.index') }}" class="btn-ghost px-6 py-3 text-lg">Découvrir nos producteurs</a>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-6xl px-4">
        {{-- Prochaine distribution et actualités --}}
        <div class="-mt-8 grid gap-4 md:grid-cols-3">
            <div class="card relative z-10 md:col-span-1">
                <p class="text-sm font-bold text-doux">📅 Prochaine distribution</p>
                @if ($next)
                    <p class="mt-1 font-display text-2xl font-bold">{{ $next->label() }}</p>
                    <p class="text-doux">{{ $next->timeRange() }} · {{ $next->place }}</p>
                    <p class="mt-2 text-sm text-doux">{{ $next->producers->count() }} producteurs présents</p>
                @else
                    <p class="mt-1 text-doux">Le calendrier sera bientôt publié.</p>
                @endif
                <a href="{{ route('calendrier') }}" class="mt-3 inline-block text-sm font-bold text-vert">Tout le calendrier →</a>
            </div>
            <div class="card relative z-10 md:col-span-2">
                @if ($page)
                    <p class="text-sm font-bold text-doux">📣 {{ $page->title }}</p>
                    <div class="prose-amap mt-2">{!! $page->content !!}</div>
                @endif
                @foreach ($openPurchases as $purchase)
                    <p class="mt-2 rounded-xl bg-terre-clair px-3 py-2 text-sm">
                        <strong>Commande groupée ouverte :</strong> {{ $purchase->title }}{{ str_contains($purchase->title, $purchase->producer->name) ? "" : " (".$purchase->producer->name.")" }}, jusqu'au {{ $purchase->closes_at->translatedFormat('j F') }}.
                        <a href="{{ route('espace.commandes-groupees.show', $purchase) }}" class="font-bold text-terre-fonce">Commander →</a>
                    </p>
                @endforeach
            </div>
        </div>

        {{-- Comment ça marche --}}
        <section class="mt-16">
            <p class="kicker">Comment ça marche ?</p>
            <h2 class="text-3xl font-extrabold">Simple comme un panier</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-4">
                @foreach ([
                    ['🤝', 'J\'adhère', 'Une cotisation annuelle de '.\App\Support\Money::format(config('amap.membership_fee_cents')).' et un compte sur ce site.'],
                    ['🧺', 'Je choisis mes paniers', 'Chez un ou plusieurs producteurs, pour la saison. Mon calendrier se remplit tout seul.'],
                    ['💶', 'Je paie d\'avance', 'En 1 à 3 fois, directement au producteur : il peut produire sereinement.'],
                    ['🧑‍🌾', 'Je récupère mon panier', 'Un samedi sur deux à '.config('amap.city').', et je donne un coup de main de temps en temps.'],
                ] as [$icon, $title, $text])
                    <div class="card">
                        <div class="text-3xl" aria-hidden="true">{{ $icon }}</div>
                        <h3 class="mt-2 text-lg font-bold">{{ $title }}</h3>
                        <p class="mt-1 text-sm text-doux">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Producteurs --}}
        <section class="mt-16">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <p class="kicker">Ils nous nourrissent</p>
                    <h2 class="text-3xl font-extrabold">Nos producteurs</h2>
                </div>
                <a href="{{ route('producteurs.index') }}" class="font-bold text-vert">Voir tous les producteurs →</a>
            </div>
            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($producers as $producer)
                    <x-producer-card :producer="$producer" />
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.app>
