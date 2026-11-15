@props(['producer'])
<a href="{{ route('producteurs.show', $producer) }}" class="group card flex flex-col overflow-hidden p-0 no-underline transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="aspect-[4/3] overflow-hidden bg-vert-clair">
        @if ($producer->coverUrl())
            <img src="{{ $producer->coverUrl() }}" alt="" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        @endif
    </div>
    <div class="flex flex-1 flex-col p-4">
        <h3 class="text-lg font-bold">{{ $producer->name }}</h3>
        @if ($producer->tagline)<p class="mt-1 text-sm text-doux">{{ $producer->tagline }}</p>@endif
        <div class="mt-auto flex flex-wrap gap-1.5 pt-3">
            @foreach ($producer->productTypeLabels() as $type)
                <span class="badge bg-vert-clair text-vert-fonce">{{ $type }}</span>
            @endforeach
            @if (in_array('bio', $producer->labels ?? []))
                <span class="badge bg-[#e3f0da] text-[#3f6a1c]">AB Bio</span>
            @endif
        </div>
    </div>
</a>
