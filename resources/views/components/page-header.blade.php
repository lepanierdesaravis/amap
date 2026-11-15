@props(['kicker' => null, 'title', 'image' => null])
<section class="relative overflow-hidden bg-vert-clair">
    @if ($image)
        <img src="{{ $image }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-25">
    @endif
    <div class="relative mx-auto max-w-6xl px-4 py-10 md:py-14">
        @if ($kicker)<p class="kicker">{{ $kicker }}</p>@endif
        <h1 class="text-3xl font-extrabold md:text-4xl">{{ $title }}</h1>
        @if ($slot->isNotEmpty())
            <div class="mt-3 max-w-3xl text-lg text-doux">{{ $slot }}</div>
        @endif
    </div>
</section>
