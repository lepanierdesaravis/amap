@props(['title' => null, 'description' => null, 'wide' => false])
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('amap.name') }} — AMAP de {{ config('amap.city') }}</title>
    <meta name="description" content="{{ $description ?? config('amap.name').' : AMAP de '.config('amap.city').'. Paniers de producteurs locaux, en direct, distribués tous les 15 jours.' }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenu" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:rounded focus:bg-white focus:px-3 focus:py-2">Aller au contenu</a>

    <header class="sticky top-0 z-40 border-b border-brun/10 bg-creme/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center gap-4 px-4 py-2">
            <a href="{{ route('accueil') }}" class="shrink-0" aria-label="{{ config('amap.name') }}, accueil">
                <img src="{{ asset('images/logo-600.png') }}" alt="{{ config('amap.name') }}" class="h-14 w-auto">
            </a>

            @php($nav = [
                'amap' => "L'AMAP",
                'producteurs.index' => 'Producteurs',
                'calendrier' => 'Calendrier',
                'adherer' => 'Adhérer',
                'contact' => 'Contact',
            ])
            <nav class="ml-auto hidden items-center gap-1 lg:flex" aria-label="Navigation principale">
                @foreach ($nav as $route => $label)
                    <a href="{{ route($route) }}" @class(['rounded-lg px-3 py-2 font-bold no-underline hover:bg-vert-clair', 'text-vert' => request()->routeIs($route, $route.'*')])>{{ $label }}</a>
                @endforeach
            </nav>

            <div class="ml-auto flex items-center gap-2 lg:ml-2">
                @auth
                    <a href="{{ route('espace.index') }}" class="btn-vert py-2 text-sm">
                        <span aria-hidden="true">👤</span> Mon espace
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost py-2 text-sm">Se connecter</a>
                @endauth

                <details class="relative lg:hidden">
                    <summary class="btn-ghost list-none px-3 py-2" aria-label="Menu">☰</summary>
                    <nav class="absolute right-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-lg ring-1 ring-brun/10" aria-label="Navigation mobile">
                        @foreach ($nav as $route => $label)
                            <a href="{{ route($route) }}" class="block rounded-lg px-3 py-2 font-bold no-underline hover:bg-vert-clair">{{ $label }}</a>
                        @endforeach
                    </nav>
                </details>
            </div>
        </div>
    </header>

    <main id="contenu" class="flex-1">
        @if (session('status'))
            <div class="mx-auto mt-4 max-w-6xl px-4">
                <div class="rounded-xl bg-vert-clair px-4 py-3 font-bold text-vert-fonce ring-1 ring-vert/30" role="status">✓ {{ session('status') }}</div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="mt-16 bg-brun text-creme/90">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 md:grid-cols-3">
            <div>
                <p class="font-display text-xl font-bold text-white">{{ config('amap.name') }}</p>
                <p class="mt-1 text-sm">{{ config('amap.tagline') }} — {{ config('amap.city') }}</p>
                <p class="mt-4 font-hand text-2xl text-miel">Des paysans, des voisins, des paniers.</p>
            </div>
            <div class="text-sm">
                <p class="font-bold text-white">Distributions</p>
                <p class="mt-1">Un {{ config('amap.distribution.weekday') }} sur deux, de {{ str_replace(':', 'h', config('amap.distribution.starts_at')) }} à {{ str_replace(':', 'h', config('amap.distribution.ends_at')) }}</p>
                <p>{{ config('amap.distribution.place') }}<br>{{ config('amap.distribution.address') }}</p>
            </div>
            <div class="text-sm">
                <p class="font-bold text-white">Nous écrire</p>
                <p class="mt-1"><a href="{{ route('contact') }}" class="text-white underline">Formulaire de contact</a></p>
                <p class="mt-4"><a href="{{ route('page', 'mentions-legales') }}" class="underline">Mentions légales</a> · <a href="{{ route('page', 'confidentialite') }}" class="underline">Confidentialité</a></p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
