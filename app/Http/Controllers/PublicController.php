<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\GroupPurchase;
use App\Models\Page;
use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'next' => Distribution::upcoming()->with('producers')->first(),
            'producers' => Producer::published()->inRandomOrder()->take(6)->get(),
            'openPurchases' => GroupPurchase::open()->with('producer')->orderBy('closes_at')->get(),
            'page' => Page::content('accueil'),
        ]);
    }

    public function amap(): View
    {
        return view('public.amap', ['page' => Page::content('l-amap')]);
    }

    public function producers(Request $request): View
    {
        $type = $request->string('type')->toString();

        return view('public.producers.index', [
            'producers' => Producer::published()
                ->when($type, fn ($q) => $q->whereJsonContains('product_types', $type))
                ->orderBy('name')->get(),
            'type' => $type,
            'types' => collect(config('amap.product_types'))
                ->filter(fn ($label, $key) => Producer::published()->whereJsonContains('product_types', $key)->exists()),
        ]);
    }

    public function producer(Producer $producer): View
    {
        abort_unless($producer->is_published || auth()->user()?->can('update', $producer), 404);

        return view('public.producers.show', [
            'producer' => $producer->load(['products' => fn ($q) => $q->where('is_active', true)]),
            'offers' => $producer->contractOffers()->where('is_published', true)->whereDate('ends_on', '>=', today())->get(),
            'nextDates' => $producer->distributions()->whereDate('date', '>=', today())->orderBy('date')->take(4)->get(),
        ]);
    }

    public function calendar(): View
    {
        $distributions = Distribution::upcoming()->with('producers')->get();

        return view('public.calendar', [
            'months' => $distributions->groupBy(fn (Distribution $d) => $d->date->translatedFormat('F Y')),
        ]);
    }

    public function join(): View
    {
        return view('public.join', [
            'page' => Page::content('adherer'),
            'fee' => config('amap.membership_fee_cents'),
        ]);
    }

    public function page(string $slug): View
    {
        return view('public.page', ['page' => Page::where('slug', $slug)->firstOrFail()]);
    }
}
