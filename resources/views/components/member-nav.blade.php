@php($items = [
    'espace.index' => ['🏠', 'Tableau de bord'],
    'espace.contrats.*' => ['🧺', 'Mes contrats'],
    'espace.commandes-groupees.*' => ['🛍️', 'Commandes groupées'],
    'espace.calendrier' => ['📅', 'Mon calendrier'],
    'espace.paiements' => ['💶', 'Mes paiements'],
    'espace.echanges' => ['💬', 'Échanges'],
    'espace.compte' => ['👤', 'Mon compte'],
])
<div class="border-b border-brun/10 bg-white">
    <div class="mx-auto flex max-w-6xl items-center gap-1 overflow-x-auto px-4 lg:flex-wrap lg:overflow-visible">
        @foreach ($items as $pattern => [$icon, $label])
            <a href="{{ route(str_replace('.*', '.index', $pattern)) }}"
               @class(['flex shrink-0 items-center gap-1.5 border-b-[3px] px-2.5 py-3 text-sm font-bold no-underline',
                       'border-terre text-brun' => request()->routeIs($pattern),
                       'border-transparent text-doux hover:text-brun' => ! request()->routeIs($pattern)])>
                <span aria-hidden="true">{{ $icon }}</span>{{ $label }}
            </a>
        @endforeach
        <div class="ml-auto flex shrink-0 items-center gap-1 pl-4">
            @if (auth()->user()->isProducer())
                <a href="{{ route('producteur.index') }}" class="rounded-lg px-3 py-2 text-sm font-bold text-miel no-underline hover:bg-miel-clair">🚜 Espace producteur</a>
            @endif
            @if (auth()->user()->canAccessPanel(filament()->getDefaultPanel()))
                <a href="{{ url('/gestion') }}" class="rounded-lg px-3 py-2 text-sm font-bold text-ardoise no-underline hover:bg-ardoise-clair">🛠️ Gestion</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-lg px-3 py-2 text-sm text-doux hover:bg-creme">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>
