<x-layouts.app title="Mon calendrier">
    <x-member-nav />
    <div class="mx-auto max-w-6xl px-4 py-8">
        <h1 class="text-3xl font-extrabold">Mon calendrier</h1>
        <p class="mt-1 text-doux">Ce que je récupère à chaque distribution.</p>

        <section class="card mt-6 max-w-md" aria-labelledby="titre-vue-ensemble" x-data>
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 id="titre-vue-ensemble" class="text-xl font-bold">Vue d'ensemble</h2>
                    <p class="mt-1 text-sm text-doux">Touchez une date pour voir le détail.</p>
                </div>
                <button type="button" x-on:click="$refs.saison.showModal()" class="shrink-0 rounded-full px-3 py-1.5 text-sm font-semibold text-vert ring-1 ring-vert hover:bg-vert-clair">⤢ Agrandir</button>
            </div>
            <x-calendrier-saison :months="$months" :legend="$legend" paged class="mt-3" />

            <dialog x-ref="saison" x-on:click.self="$el.close()" aria-labelledby="titre-saison"
                    class="m-auto w-[min(72rem,calc(100vw-2rem))] max-h-[calc(100vh-2rem)] rounded-2xl bg-white p-0 shadow-2xl backdrop:bg-brun/60">
                <div class="sticky top-0 z-40 flex items-center justify-between gap-3 border-b border-sauge/40 bg-white px-6 py-4">
                    <h2 id="titre-saison" class="text-xl font-bold">Toute la saison</h2>
                    <button type="button" x-on:click="$refs.saison.close()" class="grid size-9 place-items-center rounded-full text-xl hover:bg-creme" aria-label="Fermer">✕</button>
                </div>
                <x-calendrier-saison :months="$months" :legend="$legend" class="px-6 pt-4 pb-28" />
            </dialog>
        </section>

        <h2 class="mt-10 text-xl font-bold">Date par date</h2>
        <ol class="mt-4 space-y-3">
            @foreach ($distributions as $d)
                @php($items = $byDistribution->get($d->id, collect()))
                @php($past = $d->date->isPast() && ! $d->date->isToday())
                <li @class(['card flex gap-4', 'opacity-50' => $past, 'ring-2 ring-vert' => $items->isNotEmpty() && ! $past])>
                    <div @class(['w-16 shrink-0 rounded-xl text-center', 'bg-vert text-white' => $items->isNotEmpty(), 'bg-creme text-doux' => $items->isEmpty()])>
                        <p class="pt-1 text-xs uppercase">{{ $d->date->translatedFormat('D') }}</p>
                        <p class="text-3xl leading-none font-extrabold">{{ $d->date->day }}</p>
                        <p class="pb-1 text-xs">{{ $d->date->translatedFormat('M') }}</p>
                    </div>
                    <div class="flex-1">
                        @if ($items->isEmpty())
                            <p class="pt-3 text-doux">Pas de panier pour moi.</p>
                        @else
                            <ul class="flex flex-wrap gap-2">
                                @foreach ($items as $item)
                                    <li class="rounded-xl bg-vert-clair px-3 py-1.5 text-sm"><x-icone :icon="$item->icon" :color="$item->icon_color" size="1.5rem" class="mr-1 align-middle" /> <strong>{{ $item->quantity }} × {{ $item->label }}</strong> <span class="text-doux">· {{ $item->producer->name }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                        <p class="mt-2 text-xs text-doux">{{ $d->timeRange() }} · {{ $d->place }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</x-layouts.app>
