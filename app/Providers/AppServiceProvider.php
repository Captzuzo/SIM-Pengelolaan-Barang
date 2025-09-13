<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Penjualan;
use App\Observers\PenjualanObserver;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Blade;
use Filament\Navigation\UserMenuItem;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        Penjualan::observe(PenjualanObserver::class);
        // Gate::before(function ($user, $ability) {
        //     return $user->hasRole('Admin') ? true : null;
        // });

        // \Filament\Support\Facades\FilamentView::registerRenderHook(
        //     'head.end',
        //     fn(): string => view('components.session-warning-script')->render(),
        // );
        // Filament::serving(function () {
        //     \Filament\Support\Facades\FilamentView::registerRenderHook(
        //         'body.end',
        //         fn(): string => view('components.logout-confirmation')->render(),
        //     );
        // });
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                // Tidak daftarkan UserMenuItem::make()->label('Logout')...
            ]);
        });

        FilamentView::registerRenderHook(
            'filament::user-menu.before',
            fn(): string => view('components.logout-modal')->render(),
        );

        FilamentAsset::register([
            Js::make('flatpickr-locale-id', 'https://npmcdn.com/flatpickr/dist/l10n/id.js'),
        ]);
    }
}
