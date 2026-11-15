<x-layouts.app title="Calendrier">
    <x-page-header kicker="Un samedi sur deux" title="Le calendrier des distributions">
        De {{ str_replace(':', 'h', config('amap.distribution.starts_at')) }} à {{ str_replace(':', 'h', config('amap.distribution.ends_at')) }}, {{ config('amap.distribution.place') }}, {{ config('amap.distribution.address') }}.
        Vous découvrez l'AMAP ? Passez nous voir lors d'une distribution, sans rendez-vous.
    </x-page-header>
    <div class="mx-auto max-w-6xl space-y-10 px-4 py-10">
        @forelse ($months as $month => $distributions)
            <section>
                <h2 class="text-2xl font-bold">{{ ucfirst($month) }}</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    @foreach ($distributions as $d)
                        <div class="card flex gap-4">
                            <div class="w-16 shrink-0 rounded-xl bg-vert text-center text-white">
                                <p class="pt-1 text-xs uppercase">{{ $d->date->translatedFormat('D') }}</p>
                                <p class="text-3xl leading-none font-extrabold">{{ $d->date->day }}</p>
                                <p class="pb-1 text-xs">{{ $d->date->translatedFormat('M') }}</p>
                            </div>
                            <div>
                                <p class="font-bold">{{ $d->timeRange() }} · {{ $d->place }}</p>
                                <p class="mt-1 text-sm text-doux">{{ $d->producers->pluck('name')->implode(' · ') ?: 'Producteurs à confirmer' }}</p>
                                @if ($d->notes)<p class="mt-2 rounded-lg bg-miel-clair px-2 py-1 text-sm">ℹ️ {{ $d->notes }}</p>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <p class="text-doux">Le calendrier sera bientôt publié.</p>
        @endforelse
    </div>
</x-layouts.app>
