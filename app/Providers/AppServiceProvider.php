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

                $pendingReviewsCount = \Illuminate\Support\Facades\Schema::hasTable('reviews')
                    ? \App\Models\Review::where('status', 'pending')->count()
                    : 0;

                $view->with('adminSidebarStats', [
                    'properties' => \App\Models\Property::count(),
                    'pendingBrokers' => $pendingBrokersCount,
                    'bookings' => \App\Models\Booking::count(),
                    'users' => \App\Models\User::count(),
                    'pendingReviews' => $pendingReviewsCount,
                    'totalReviews' => \Illuminate\Support\Facades\Schema::hasTable('reviews') ? \App\Models\Review::count() : 0,
                ]);
            }
        });

        \Illuminate\Support\Facades\View::composer('broker.*', function ($view) {
            $brokerId = \Illuminate\Support\Facades\Auth::id();
            if ($brokerId && \Illuminate\Support\Facades\Schema::hasTable('properties')) {
                $propCount = \App\Models\Property::where('broker_id', $brokerId)->count();
                $pendingCount = \App\Models\Booking::where('broker_id', $brokerId)->where('booking_status', 'pending')->count();
                $tenantCount = \App\Models\Booking::where('broker_id', $brokerId)->where('booking_status', 'confirmed')->distinct('user_id')->count('user_id');
                $rating = \App\Models\Review::whereHas('property', fn($q)=>$q->where('broker_id', $brokerId))->avg('rating');

                $view->with('brokerSidebarStats', [
                    'properties' => $propCount,
                    'pendingBookings' => $pendingCount,
                    'tenants' => $tenantCount,
                    'rating' => $rating ? number_format($rating, 1) . ' ★' : '4.8 ★',
                ]);
            }
        });
    }
}
