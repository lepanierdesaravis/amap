<x-layouts.app :title="$producer->name" :description="$producer->tagline">
    <section class="relative">
        <div class="h-64 bg-vert-clair md:h-80">
            @if ($producer->coverUrl())
                <img src="{{ $producer->coverUrl() }}" alt="" class="h-full w-full object-cover">
            @endif
        </div>
        <div class="mx-auto -mt-16 max-w-6xl px-4">
            <div class="card relative">
                <a href="{{ route('producteurs.index') }}" class="text-sm font-bold text-doux no-underline">← Tous les producteurs</a>
                <h1 class="mt-1 text-3xl font-extrabold md:text-4xl">{{ $producer->name }}</h1>
                <p class="mt-1 text-doux">{{ collect([$producer->farmer_names, $producer->city])->filter()->implode(' · ') }}</p>
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @foreach ($producer->productTypeLabels() as $label)<span class="badge bg-vert-clair text-vert-fonce">{{ $label }}</span>@endforeach
                    @foreach ($producer->labels ?? [] as $label)<span class="badge bg-[#e3f0da] text-[#3f6a1c]">{{ config("amap.labels.$label", $label) }}</span>@endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-10 lg:grid-cols-[1fr_320px]">
        <div>
            @if ($producer->tagline)<p class="font-hand text-3xl text-vert">{{ $producer->tagline }}</p>@endif
            <div class="prose-amap mt-4 text-lg">{!! $producer->description !!}</div>

            @if (count($producer->photos ?? []))
                <div class="mt-8 grid grid-cols-2 gap-3 md:grid-cols-3">
                    @foreach ($producer->photos as $photo)
                        <img src="{{ Storage::disk('public')->url($photo) }}" alt="" loading="lazy" class="aspect-[4/3] w-full rounded-xl object-cover">
                    @endforeach
                </div>
            @endif

            @if ($producer->products->isNotEmpty())
                <section x-data="vitrine" x-on:keydown.escape.prevent="close()">
                <h2 class="mt-10 text-2xl font-bold">Produits proposés</h2>
                <p class="mt-1 text-sm text-doux">Touchez un produit pour le voir en grand.</p>
                {{-- Groupés par type de produit quand il y en a plusieurs (bœuf, veau, porc…). --}}
                @php($groups = $producer->products->groupBy(fn ($p) => $p->is_amount ? 'Engagements' : ($p->category_label ?: '')))
                @foreach ($groups as $category => $products)
                @if ($groups->count() > 1 && $category !== '')
                    <h3 class="mt-6 text-lg font-bold text-vert">{{ $category }}</h3>
                @endif
                <ul @class(['divide-y divide-brun/10 overflow-hidden rounded-2xl bg-white ring-1 ring-brun/10', 'mt-2' => $groups->count() > 1, 'mt-4' => $groups->count() === 1])>
                    @foreach ($products as $product)
                        <li role="button" tabindex="0" aria-haspopup="dialog" aria-label="{{ $product->name }} : voir le détail"
                            x-on:click="open({{ $product->id }}, $el)" x-on:keydown.enter.prevent="open({{ $product->id }}, $el)" x-on:keydown.space.prevent="open({{ $product->id }}, $el)"
                            class="flex cursor-pointer items-center gap-3 px-4 py-3 transition-colors outline-none hover:bg-creme focus-visible:bg-creme focus-visible:ring-2 focus-visible:ring-terre focus-visible:ring-inset">
                            @if ($product->photo)
                                <img src="{{ $product->photoUrl() }}" alt="" loading="lazy" class="size-12 shrink-0 rounded-full object-cover">
                            @else
                                {!! $product->iconBadge('3rem') !!}
                            @endif
                            <div class="min-w-0 flex-1"><p class="font-bold">{{ $product->name }}</p>@if ($product->description)<p class="line-clamp-2 text-sm text-doux">{{ $product->description }}</p>@endif</div>
                            <span class="text-sm font-bold whitespace-nowrap">{{ $product->priceLabel() }}</span>
                        </li>
                    @endforeach
                </ul>
                @endforeach

                {{-- Fenêtre du produit : texte complet et photo en grand. --}}
                <dialog x-ref="dialog" x-on:click.self="close()" x-on:cancel.prevent="close()" aria-label="Détail du produit"
                        class="m-auto max-h-[calc(100dvh-2rem)] w-[min(40rem,calc(100vw-2rem))] overflow-visible bg-transparent p-0 backdrop:bg-brun/60">
                    <div x-ref="panel" class="max-h-[calc(100dvh-2rem)] origin-top-left overflow-y-auto rounded-2xl bg-white shadow-2xl">
                        @foreach ($producer->products as $product)
                            <article data-product="{{ $product->id }}" x-show="current === {{ $product->id }}" x-cloak class="relative">
                                <button type="button" x-on:click="close()" aria-label="Fermer"
                                        class="absolute top-3 right-3 z-10 grid size-10 place-items-center rounded-full bg-white/90 text-xl shadow hover:bg-creme">✕</button>
                                <div class="grid place-items-center bg-creme p-4 sm:p-6">
                                    @if ($product->photo)
                                        <img data-photo src="{{ $product->photoUrl() }}" alt="{{ $product->name }}" loading="lazy" class="max-h-[50dvh] w-auto max-w-full rounded-xl object-contain">
                                    @else
                                        <span data-photo class="py-6">{!! $product->iconBadge('8rem') !!}</span>
                                    @endif
                                </div>
                                <div class="p-5 sm:p-6">
                                    <p data-fade class="text-sm font-semibold text-vert">{{ $product->is_amount ? 'Engagement' : $product->category_label }}</p>
                                    <h3 data-fade class="mt-1 text-2xl font-extrabold">{{ $product->name }}</h3>
                                    <p data-fade class="mt-2 inline-block rounded-full bg-vert-clair px-3 py-1 font-bold">{{ $product->priceLabel() }}</p>
                                    @if ($product->description)
                                        <p data-fade class="mt-4 leading-relaxed whitespace-pre-line text-doux">{{ $product->description }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </dialog>
                </section>
            @endif
        </div>

        <aside class="space-y-4">
            @foreach ($offers as $offer)
                <div class="card ring-2 ring-vert/40">
                    <p class="font-bold">🧺 {{ $offer->title }}</p>
                    <p class="mt-1 text-sm text-doux">Du {{ $offer->starts_on->translatedFormat('j F') }} au {{ $offer->ends_on->translatedFormat('j F Y') }}</p>
                    @if ($offer->isOpen())
                        <a href="{{ route('espace.contrats.show', $offer) }}" class="btn-primary mt-3 w-full">Souscrire</a>
                    @else
                        <p class="mt-2 text-sm">Souscriptions {{ $offer->status()->getLabel() === 'À venir' ? 'à partir du '.$offer->opens_at->translatedFormat('j F') : 'clôturées' }}.</p>
                    @endif
                </div>
            @endforeach

            @if ($nextDates->isNotEmpty())
                <div class="card">
                    <p class="font-bold">📅 Prochaines distributions</p>
                    <ul class="mt-2 space-y-1 text-sm">
                        @foreach ($nextDates as $d)<li>{{ $d->label() }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="card text-sm">
                <p class="font-bold">📍 Contact</p>
                @if ($producer->address)<p class="mt-1">{{ $producer->address }}</p>@endif
                @if ($producer->phone)<p class="mt-1"><a href="tel:{{ preg_replace('/\s+/', '', $producer->phone) }}" class="text-vert">{{ $producer->phone }}</a></p>@endif
                @if ($producer->website)<p class="mt-1"><a href="{{ $producer->website }}" class="font-bold text-vert" rel="noopener" target="_blank">Site internet ↗</a></p>@endif
                @foreach (array_filter($producer->social_links ?? []) as $network => $url)
                    <p class="mt-1"><a href="{{ $url }}" class="font-bold text-vert" rel="noopener" target="_blank">{{ ucfirst($network) }} ↗</a></p>
                @endforeach
            </div>

            @if (count($producer->documents ?? []))
                <div class="card text-sm">
                    <p class="font-bold">📄 Documents</p>
                    <ul class="mt-2 space-y-1">
                        @foreach ($producer->documents as $doc)
                            <li><a href="{{ Storage::disk('public')->url($doc) }}" class="font-bold text-vert" target="_blank">{{ Str::of(basename($doc))->beforeLast('.')->replace(['-', '_'], ' ')->ucfirst() }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</x-layouts.app>
