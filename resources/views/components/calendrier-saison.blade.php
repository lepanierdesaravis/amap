{{--
    Calendrier mois par mois. Survoler (ou toucher) une date montre le détail de ce qui est commandé.
    Les icônes « current » (contrat en cours de souscription) sont plus grandes que les autres.
    Avec « paged », un seul mois s'affiche (d'abord le premier avec une livraison), avec des flèches.
--}}
@props(['months', 'legend' => [], 'compact' => false, 'paged' => false])
@php($start = (int) collect($months)->search(fn ($m) => collect($m['cells'])->contains(fn ($c) => ! empty($c['entries']))))
<div {{ $attributes }} @if ($paged) x-data="{ month: {{ $start }}, last: {{ count($months) - 1 }} }" @endif>
    <div @class(['grid gap-x-6 gap-y-5', 'sm:grid-cols-2 xl:grid-cols-3' => ! $compact && ! $paged])>
        @foreach ($months as $i => $month)
            <div @if ($paged) x-show="month === {{ $i }}" @if ($i !== $start) x-cloak @endif @endif>
                @if ($paged)
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <button type="button" x-on:click="month--" x-bind:disabled="month === 0" aria-label="Mois précédent"
                                class="grid size-9 place-items-center rounded-full text-lg font-bold text-vert hover:bg-vert-clair disabled:opacity-30 disabled:hover:bg-transparent">‹</button>
                        <p class="font-bold" aria-live="polite">{{ $month['label'] }}</p>
                        <button type="button" x-on:click="month++" x-bind:disabled="month === last" aria-label="Mois suivant"
                                class="grid size-9 place-items-center rounded-full text-lg font-bold text-vert hover:bg-vert-clair disabled:opacity-30 disabled:hover:bg-transparent">›</button>
                    </div>
                @else
                    <p class="mb-1.5 text-sm font-bold">{{ $month['label'] }}</p>
                @endif
                <div class="grid grid-cols-7 gap-1 text-center text-[11px]">
                    @foreach (['L', 'M', 'M', 'J', 'V', 'S', 'D'] as $d)<div class="text-doux/70" aria-hidden="true">{{ $d }}</div>@endforeach
                    @foreach ($month['cells'] as $cell)
                        @if ($cell === null)
                            <div></div>
                        @elseif ($cell['distribution'])
                            @php($entries = collect($cell['entries']))
                            @php($hasCurrent = $entries->contains('current', true))
                            <div @if ($entries->isNotEmpty()) tabindex="0" @endif
                                 @class(['group relative flex min-h-12 flex-col items-center rounded-lg pt-0.5 pb-1 font-bold ring-1 outline-none focus-visible:ring-2 focus-visible:ring-terre',
                                         'bg-vert-clair ring-vert' => $hasCurrent || ($entries->isNotEmpty() && ! $compact),
                                         'bg-creme ring-sauge' => $entries->isNotEmpty() && ! $hasCurrent && $compact,
                                         'bg-white text-doux ring-sauge/60' => $entries->isEmpty(),
                                         'opacity-50' => $cell['past']])>
                                {{ $cell['day'] }}
                                @if ($entries->isNotEmpty())
                                    <span class="mt-0.5 flex flex-wrap items-center justify-center gap-0.5 px-0.5">
                                        @foreach ($entries->unique(fn ($e) => $e['icon'].$e['color'])->sortByDesc('current') as $e)
                                            <x-icone :icon="$e['icon']" :color="$e['color']" :size="$e['current'] || ! $compact ? '1.35rem' : '0.9rem'" />
                                        @endforeach
                                    </span>
                                    <div role="tooltip"
                                         @class(['pointer-events-none absolute top-full z-30 mt-1 hidden w-60 rounded-xl bg-brun p-3 text-left text-xs font-normal text-white shadow-lg group-hover:block group-focus:block',
                                                 'left-0' => $cell['column'] < 3,
                                                 'right-0' => $cell['column'] >= 3])>
                                        <p class="mb-1.5 font-bold">{{ $cell['date'] }}</p>
                                        <ul class="space-y-1">
                                            @foreach ($entries->sortByDesc('current') as $e)
                                                <li class="flex items-center gap-2">
                                                    <x-icone :icon="$e['icon']" :color="$e['color']" size="1.25rem" />
                                                    <span><strong>{{ $e['label'] }}</strong><br><span class="text-white/70">{{ $e['producer'] }}@if ($compact && ! $e['current']) · déjà commandé @endif</span></span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="min-h-12 pt-0.5 text-doux/40">{{ $cell['day'] }}</div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @if ($legend)
        <ul class="mt-4 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-doux">
            @foreach ($legend as $e)
                <li class="flex items-center gap-1.5"><x-icone :icon="$e['icon']" :color="$e['color']" size="1.1rem" /> {{ $e['name'] }} · {{ $e['producer'] }}</li>
            @endforeach
        </ul>
    @endif
</div>
