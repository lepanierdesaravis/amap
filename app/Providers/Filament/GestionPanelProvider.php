<?php

namespace App\Providers\Filament;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Back-office des référents et administrateurs (/gestion).
 * La connexion se fait sur la page commune du site (/connexion).
 */
class GestionPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        // Dates au format français, quelle que soit la langue du navigateur.
        DateTimePicker::configureUsing(fn (DateTimePicker $c) => $c->native(false)->firstDayOfWeek(1)
            ->displayFormat($c instanceof DatePicker ? 'd/m/Y' : 'd/m/Y H:i'));
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('gestion')
            ->path('gestion')
            ->brandName(config('amap.name'))
            ->brandLogo(asset('images/logo-600.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('favicon.png'))
            ->homeUrl('/')
            ->font('Lato')
            ->colors([
                'primary' => Color::hex('#5F7A1F'),
                'warning' => Color::hex('#E0A82E'),
                'danger' => Color::hex('#C8381A'),
                'info' => Color::hex('#3F707E'),
                'gray' => Color::Stone,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Producteurs et contrats'),
                NavigationGroup::make('Commandes groupées'),
                NavigationGroup::make('Distributions'),
                NavigationGroup::make('Adhérents'),
                NavigationGroup::make('Site'),
            ])
            ->navigationItems([
                NavigationItem::make('Retour au site')
                    ->url('/')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->sort(100),
                NavigationItem::make('Mon espace adhérent')
                    ->url(fn () => route('espace.index'))
                    ->icon('heroicon-o-user')
                    ->sort(101),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
