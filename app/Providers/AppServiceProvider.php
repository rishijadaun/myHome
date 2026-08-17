<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('admin.*', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('properties')) {
                $brokerRole = \App\Models\Role::where('slug', 'broker')->first();
                $pendingBrokersCount = $brokerRole ? $brokerRole->users()->where(function ($q) {
                    $q->where('users.status', 'pending_verification')->orWhereNull('users.kyc_verified_at');
                })->count() : 0;

                $view->with('adminSidebarStats', [
                    'properties' => \App\Models\Property::count(),
                    'pendingBrokers' => $pendingBrokersCount,
                    'bookings' => \App\Models\Booking::count(),
                    'users' => \App\Models\User::count(),
                ]);
            }
        });
    }
}
