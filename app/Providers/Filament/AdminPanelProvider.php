<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Notifications\Livewire\DatabaseNotifications;

// Adding Widgets
// use App\Filament\Resources\VentaLineaResource\Widgets\StatsOverviewWidget;
use App\Filament\Resources\VentaLineaResource\Widgets\StatsOverview;
// use App\Filament\Widgets\StatsOverviewWidget;
// use App\Filament\Widgets\StatsOverviewWidget;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Orange,
                'warning' => Color::Yellow,
                'danger' => Color::Red,
                'success' => Color::Green,
                'info' => Color::Blue,
            ])
            ->brandLogo(asset('images/logo.svg'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/GLOBALISOTIPOCC-01.ico'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Daniel Gamboa -- "Welcome Daniel Gamboa" on admin dashboard
                // Widgets\AccountWidget::class,
                StatsOverview::class,
                // Daniel Gamboa -- "Stats Overview" on admin dashboard
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            // ->livewireComponents([
            //     DatabaseNotifications::class,
            // ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
