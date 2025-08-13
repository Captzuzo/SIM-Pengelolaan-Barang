<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Laporan\LaporanBulananPage;
use App\Filament\Pages\Laporan\LaporanHarianPage;
use App\Filament\Pages\Laporan\LaporanLabaPage;
use App\Filament\Pages\Laporan\LaporanTahunanPage;
use App\Filament\Resources\BarangResource;
use App\Filament\Resources\LaporanStokResource\Pages\LaporanStokPage;
use App\Filament\Widgets\PenjualanChart;
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
            ->id('ht')
            ->path('ht')
            ->brandName('Penjualan-Barang')
            // ->brandLogo(asset('img/ht.png'), height: 40)
            ->favicon(asset('img/ht.png'))
            // ->routes(function () {
            //     Route::redirect('/', 'admin/login');
            // })
            // ->id('admin')
            // ->path('admin')
            ->login()
            // ->logout()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                // 'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->resources([
                BarangResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
                LaporanStokPage::class,
                // LaporanLabaPage::class,
                LaporanHarianPage::class,
                // LaporanBulananPage::class,
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
                PenjualanChart::class,
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
            ])
            ->renderHook(
                'panels::body.end',
                fn() => <<<HTML
        <!-- SweetAlert CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            let formChanged = false;

            // Handler untuk beforeunload browser
            function beforeUnloadHandler(e) {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            }

            // Tambah handler hanya sekali
            window.addEventListener('beforeunload', beforeUnloadHandler);

            // Deteksi perubahan di form
            document.addEventListener('input', function (e) {
                if (e.target.closest('form')) {
                    formChanged = true;
                }
            });

            // Reset flag saat form disubmit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    formChanged = false;
                    window.removeEventListener('beforeunload', beforeUnloadHandler);
                });
            });

            // Tangkap klik navigasi sidebar/menu
            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', function (e) {
                    // Abaikan link internal (#) dan jika tidak ada perubahan
                    if (!formChanged || link.href.includes('#')) return;

                    e.preventDefault(); // Hentikan navigasi langsung

                    // Tampilkan SweetAlert
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perubahan belum disimpan',
                        text: 'Apakah Anda yakin ingin meninggalkan halaman ini?',
                        showCancelButton: true,
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Ya, tinggalkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formChanged = false;
                            window.removeEventListener('beforeunload', beforeUnloadHandler);
                            window.location.href = link.href;
                        }
                    });
                });
            });
        </script>
    HTML,
            );
    }
}