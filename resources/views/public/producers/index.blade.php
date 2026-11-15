<x-layouts.app title="Nos producteurs">
    <x-page-header kicker="Ils nous nourrissent" title="Nos producteurs" :image="asset('images/producteurs.jpg')">
        Des fermes à taille humaine, respectueuses de la nature, de l'environnement et de l'animal.
    </x-page-header>
    <div class="mx-auto max-w-6xl px-4 py-8">
        <nav class="flex flex-wrap gap-2" aria-label="Filtrer par type de produit">
            <a href="{{ route('producteurs.index') }}" @class(['badge px-3 py-1.5 text-sm no-underline ring-1', 'bg-vert text-white ring-vert' => ! $type, 'bg-white text-brun ring-brun/15' => $type])>Tous</a>
            @foreach ($types as $key => $label)
                <a href="{{ route('producteurs.index', ['type' => $key]) }}" @class(['badge px-3 py-1.5 text-sm no-underline ring-1', 'bg-vert text-white ring-vert' => $type === $key, 'bg-white text-brun ring-brun/15' => $type !== $key])>{{ $label }}</a>
            @endforeach
        </nav>
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($producers as $producer)
                <x-producer-card :producer="$producer" />
            @empty
                <p class="text-doux">Aucun producteur pour ce type de produit.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
