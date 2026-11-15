<x-layouts.app title="Adhérer">
    <x-page-header kicker="Rejoignez-nous" :title="$page?->title ?? 'Adhérer à l\'AMAP'" :image="asset('images/aravis.jpg')">
        {{ $page?->intro }}
    </x-page-header>
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-10 lg:grid-cols-[1fr_340px]">
        <div>
            <ol class="space-y-4">
                @foreach ([
                    ['Je crée mon compte', 'Quelques informations (nom, e-mail, téléphone). Cela prend une minute.', route('register'), 'Créer mon compte'],
                    ['Je règle ma cotisation', 'La cotisation annuelle est de '.\App\Support\Money::format($fee).', par chèque ou par virement (bientôt en ligne).', null, null],
                    ['Je choisis mes paniers', 'Dès que l\'adhésion est validée, je souscris mes contrats et je participe aux commandes groupées.', null, null],
                    ['Je participe', 'Je donne un coup de main lors d\'au moins deux distributions dans l\'année.', null, null],
                ] as $i => [$title, $text, $url, $cta])
                    <li class="card flex gap-4">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-terre text-lg font-extrabold text-white">{{ $i + 1 }}</span>
                        <div>
                            <h2 class="text-lg font-bold">{{ $title }}</h2>
                            <p class="mt-1 text-doux">{{ $text }}</p>
                            @if ($url)<a href="{{ $url }}" class="btn-primary mt-3">{{ $cta }}</a>@endif
                        </div>
                    </li>
                @endforeach
            </ol>
            @if ($page)
                <div class="prose-amap mt-10">{!! $page->content !!}</div>
            @endif
        </div>
        <aside class="space-y-4">
            <div class="card">
                <p class="font-bold">🙋 Nous rencontrer</p>
                <p class="mt-1 text-sm text-doux">Venez nous voir lors d'une distribution : vous y rencontrerez un responsable et les producteurs.</p>
                <a href="{{ route('calendrier') }}" class="mt-2 inline-block text-sm font-bold text-vert">Prochaines dates →</a>
            </div>
            <div class="card">
                <p class="font-bold">✉️ Une question ?</p>
                <a href="{{ route('contact') }}" class="mt-2 inline-block text-sm font-bold text-vert">Écrivez-nous →</a>
            </div>
        </aside>
    </div>
</x-layouts.app>
