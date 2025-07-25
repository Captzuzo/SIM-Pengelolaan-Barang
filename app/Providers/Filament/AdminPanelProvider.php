<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Laporan\LaporanBulananPage;
use App\Filament\Pages\Laporan\LaporanHarianPage;
use App\Filament\Pages\Laporan\LaporanLabaPage;
use App\Filament\Pages\Laporan\LaporanTahunanPage;
use App\Filament\Resources\BarangResource;
use App\Filament\Resources\LaporanStokResource\Pages\LaporanStokPage;
use App\Models\Barang;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
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
use Illuminate\Routing\Route;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('penjualan')
            ->path('penjualan')
            // ->routes(function () {
            //     Route::redirect('/', 'admin/login');
            // })
            // ->id('admin')
            // ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->resources([
                BarangResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
                LaporanStokPage::class,
                LaporanLabaPage::class,
                LaporanHarianPage::class,
                LaporanBulananPage::class,
                LaporanTahunanPage::class,
            ])
            ->spa()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\DashboardOverview::class,
                // \App\Filament\Widgets\BarangStokMinimOverview::class,
                \App\Filament\Widgets\PelangganHutangOverview::class,
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}