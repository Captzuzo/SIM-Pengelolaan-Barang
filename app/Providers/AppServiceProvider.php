<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Penjualan;
use App\Observers\PenjualanObserver;

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
    }
}