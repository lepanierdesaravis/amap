<x-layouts.app :title="$page->title">
    <x-page-header :title="$page->title">{{ $page->intro }}</x-page-header>
    <article class="prose-amap mx-auto max-w-3xl px-4 py-10">{!! $page->content !!}</article>
</x-layouts.app>
