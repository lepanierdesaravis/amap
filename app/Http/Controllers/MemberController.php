<?php

namespace App\Http\Controllers;

use App\Models\ContractOffer;
use App\Models\Distribution;
use App\Models\GroupPurchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $next = Distribution::upcoming()->first();

        return view('member.dashboard', [
            'user' => $user,
            'next' => $next,
            'nextDeliveries' => $next ? $user->deliveries()->where('distribution_id', $next->id)->with('producer')->get() : collect(),
            'upcomingDeliveryDates' => $user->deliveries()->whereHas('distribution', fn ($q) => $q->whereDate('date', '>=', today()))
                ->with('distribution')->get()->groupBy('distribution_id')->count(),
            'openPurchases' => GroupPurchase::open()->with('producer')->orderBy('closes_at')->get(),
            'openOffers' => ContractOffer::open()->with('producer')->get(),
            'myOrders' => $user->groupOrders()->pluck('group_purchase_id'),
            'myContracts' => $user->contracts()->pluck('contract_offer_id'),
            'nextInstallments' => $user->installments()->with('producer')->whereDate('due_on', '>=', today()->startOfMonth())->orderBy('due_on')->take(3)->get(),
            'canOrder' => $user->canOrder(),
        ]);
    }

    public function contracts(Request $request): View
    {
        $user = $request->user();

        return view('member.contracts', [
            'offers' => ContractOffer::where('is_published', true)
                ->whereDate('ends_on', '>=', today())
                ->with('producer')->orderBy('closes_at')->get(),
            'contracts' => $user->contracts()->with(['offer.producer', 'lines.product'])->get()->keyBy('contract_offer_id'),
            'canOrder' => $user->canOrder(),
        ]);
    }

    public function groupPurchases(Request $request): View
    {
        return view('member.group-purchases', [
            'open' => GroupPurchase::open()->with(['producer', 'distribution'])->orderBy('closes_at')->get(),
            'upcoming' => GroupPurchase::where('opens_at', '>', now())->with('producer')->orderBy('opens_at')->get(),
            'myOrders' => $request->user()->groupOrders()->with(['groupPurchase.distribution', 'lines.item'])->latest()->get(),
            'canOrder' => $request->user()->canOrder(),
        ]);
    }

    public function calendar(Request $request): View
    {
        $deliveries = $request->user()->deliveries()
            ->with(['distribution', 'producer'])
            ->whereHas('distribution', fn ($q) => $q->whereDate('date', '>=', today()->subMonth()))
            ->get();

        $distributions = Distribution::whereDate('date', '>=', today()->subMonth())->orderBy('date')->take(30)->get();

        return view('member.calendar', [
            'distributions' => $distributions,
            'byDistribution' => $deliveries->groupBy('distribution_id'),
        ]);
    }

    public function payments(Request $request): View
    {
        return view('member.payments', [
            'installments' => $request->user()->installments()->with(['producer', 'payable'])->orderBy('due_on')->get()->groupBy('producer.name'),
        ]);
    }

    public function account(Request $request): View
    {
        return view('member.account', [
            'user' => $request->user(),
            'memberships' => $request->user()->memberships()->orderByDesc('year')->get(),
        ]);
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'phone_secondary' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:160'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:80'],
        ]);

        $request->user()->update([...$data, 'share_phone' => $request->boolean('share_phone')]);

        return back()->with('status', 'Vos informations sont enregistrées.');
    }
}
