<x-layouts.app title="L'AMAP">
    <x-page-header kicker="AMAP de {{ config('amap.city') }}" :title="$page?->title ?? 'L\'AMAP'" :image="asset('images/aravis.jpg')">
        {{ $page?->intro }}
    </x-page-header>
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-10 lg:grid-cols-[1fr_320px]">
        <article class="prose-amap text-lg">{!! $page?->content !!}</article>
        <aside class="space-y-4">
            <div class="card">
                <p class="font-bold">📅 Les distributions</p>
                <p class="mt-1 text-sm text-doux">Un {{ config('amap.distribution.weekday') }} sur deux, de {{ str_replace(':', 'h', config('amap.distribution.starts_at')) }} à {{ str_replace(':', 'h', config('amap.distribution.ends_at')) }}, {{ config('amap.distribution.place') }}.</p>
                <a href="{{ route('calendrier') }}" class="mt-2 inline-block text-sm font-bold text-vert">Voir le calendrier →</a>
            </div>
            <div class="card">
                <p class="font-bold">📄 Documents</p>
                <ul class="mt-2 space-y-1 text-sm">
                    <li><a href="{{ asset('documents/charte-des-amap.pdf') }}" class="font-bold text-vert">La charte des AMAP</a></li>
                    <li><a href="{{ asset('documents/code-de-bonne-conduite.pdf') }}" class="font-bold text-vert">Notre code de bonne conduite</a></li>
                </ul>
            </div>
            <a href="{{ route('adherer') }}" class="btn-primary w-full py-3">Je souhaite adhérer</a>
        </aside>
    </div>
</x-layouts.app>
