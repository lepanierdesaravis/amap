<x-layouts.app title="Mon calendrier">
    <x-member-nav />
    <div class="mx-auto max-w-4xl px-4 py-8">
        <h1 class="text-3xl font-extrabold">Mon calendrier</h1>
        <p class="mt-1 text-doux">Ce que je récupère à chaque distribution.</p>
        <ol class="mt-6 space-y-3">
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
                                    <li class="rounded-xl bg-vert-clair px-3 py-1.5 text-sm"><span aria-hidden="true">{{ $item->icon ?: '🧺' }}</span> <strong>{{ $item->quantity }} × {{ $item->label }}</strong> <span class="text-doux">· {{ $item->producer->name }}</span></li>
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
