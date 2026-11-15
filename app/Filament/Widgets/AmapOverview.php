<?php

namespace App\Filament\Widgets;

use App\Enums\InstallmentStatus;
use App\Models\Distribution;
use App\Models\GroupPurchase;
use App\Models\Installment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AmapOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $next = Distribution::upcoming()->withCount('deliveries')->first();
        $members = User::whereHas('memberships', fn ($q) => $q->where('year', now()->year)->whereNotNull('paid_at'))->count();
        $nextYearMembers = User::whereHas('memberships', fn ($q) => $q->where('year', now()->year + 1)->whereNotNull('paid_at'))->count();
        $openPurchases = GroupPurchase::open()->when(! $user->isAdmin(), fn ($q) => $q->where('referent_id', $user->id))->count();
        $expected = Installment::where('status', InstallmentStatus::Expected)
            ->when(! $user->isAdmin(), fn ($q) => $q->whereHas('producer', fn ($p) => $p->where('referent_id', $user->id)))
            ->count();

        return [
            Stat::make('Prochaine distribution', $next ? $next->date->translatedFormat('j F') : '—')
                ->description($next ? "{$next->deliveries_count} livraisons prévues" : 'Aucune date prévue')
                ->icon('heroicon-o-calendar-days'),
            Stat::make('Adhérents '.now()->year, $members)
                ->description("{$nextYearMembers} déjà inscrits pour ".(now()->year + 1))
                ->icon('heroicon-o-users'),
            Stat::make('Commandes groupées ouvertes', $openPurchases)
                ->icon('heroicon-o-shopping-bag'),
            Stat::make('Paiements à pointer', $expected)
                ->description('échéances en attente')
                ->icon('heroicon-o-check-badge'),
        ];
    }
}
