<?php

use App\Http\Controllers\User\UserHomeController;

Route::get('/', [UserHomeController::class, 'index'])->name('user.home');

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminBrokerController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminRelationshipManagerController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::match(['get', 'post'], '/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
        Route::get('/dashboard/chart-data', [AdminDashboardController::class, 'getChartData'])->name('dashboard.chart');
        
        // Dynamic Quick Actions from Dashboard
        Route::post('/properties/{id}/approve', [AdminDashboardController::class, 'approveProperty'])->name('properties.approve');
        Route::post('/properties/{id}/reject', [AdminDashboardController::class, 'rejectProperty'])->name('properties.reject');
        Route::post('/brokers/{id}/approve', [AdminDashboardController::class, 'approveBroker'])->name('brokers.approve');
        Route::post('/brokers/{id}/reject', [AdminDashboardController::class, 'rejectBroker'])->name('brokers.reject');

        // Manage PGs Routes
        Route::get('/pgs', [AdminPropertyController::class, 'index'])->name('pgs');
        Route::post('/pgs', [AdminPropertyController::class, 'store'])->name('pgs.store');
        Route::get('/pgs/{id}', [AdminPropertyController::class, 'show'])->name('pgs.show');
        Route::post('/pgs/{id}/toggle-status', [AdminPropertyController::class, 'toggleStatus'])->name('pgs.toggle');
        Route::post('/pgs/{id}/approve', [AdminPropertyController::class, 'approve'])->name('pgs.approve');
        Route::post('/pgs/{id}/update-tag', [AdminPropertyController::class, 'updateTag'])->name('pgs.update-tag');
        // Manage Reviews & Moderation
        Route::get('/reviews', [AdminPropertyController::class, 'indexReviews'])->name('reviews');
        Route::post('/reviews/{id}/approve', [AdminPropertyController::class, 'approveReview'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [AdminPropertyController::class, 'rejectReview'])->name('reviews.reject');
        Route::post('/reviews/{id}/reply', [AdminPropertyController::class, 'replyReview'])->name('reviews.reply');
        Route::delete('/reviews/{id}', [AdminPropertyController::class, 'destroyReview'])->name('reviews.destroy');

        // Manage Brokers & Relationship Manager Assignment Routes
        Route::get('/brokers', [AdminBrokerController::class, 'index'])->name('brokers');
        Route::post('/brokers', [AdminBrokerController::class, 'store'])->name('brokers.store');
        Route::get('/brokers/{id}', [AdminBrokerController::class, 'show'])->name('brokers.show');
        Route::post('/brokers/{id}/approve', [AdminBrokerController::class, 'approve'])->name('brokers.approve');
        Route::post('/brokers/{id}/reject', [AdminBrokerController::class, 'reject'])->name('brokers.reject');
        Route::post('/brokers/{id}/toggle-status', [AdminBrokerController::class, 'toggleStatus'])->name('brokers.toggle');
        Route::delete('/brokers/{id}', [AdminBrokerController::class, 'destroy'])->name('brokers.destroy');
        Route::post('/brokers/{id}/assign-rm', [AdminRelationshipManagerController::class, 'assignBroker'])->name('brokers.assign-rm');
        Route::post('/brokers/bulk-assign-rm', [AdminRelationshipManagerController::class, 'bulkAssign'])->name('brokers.bulk-assign-rm');
        Route::post('/brokers/auto-assign-rm', [AdminRelationshipManagerController::class, 'autoAssignByZone'])->name('brokers.auto-assign-rm');

        // Relationship Managers Team Management
        Route::get('/relationship-managers', [AdminRelationshipManagerController::class, 'index'])->name('relationship-managers.index');
        Route::post('/relationship-managers', [AdminRelationshipManagerController::class, 'store'])->name('relationship-managers.store');
        Route::get('/relationship-managers/{id}', [AdminRelationshipManagerController::class, 'show'])->name('relationship-managers.show');
        Route::match(['put', 'post'], '/relationship-managers/{id}', [AdminRelationshipManagerController::class, 'update'])->name('relationship-managers.update');
        Route::delete('/relationship-managers/{id}', [AdminRelationshipManagerController::class, 'destroy'])->name('relationship-managers.destroy');

        // Manage Users Routes
        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
        Route::post('/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Settings Routes
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/profile', [AdminSettingController::class, 'updateProfile'])->name('settings.profile');

        Route::view('/bookings', 'admin.bookings')->name('bookings');
    });
});

use App\Http\Controllers\Broker\BrokerAuthController;
use App\Http\Controllers\Broker\BrokerDashboardController;
use App\Http\Controllers\Broker\BrokerProfileController;
use App\Http\Controllers\Broker\BrokerPropertyController;

Route::prefix('broker')->name('broker.')->group(function () {
    // Guest Broker Routes
    Route::get('/login', [BrokerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [BrokerAuthController::class, 'login'])->name('login.submit');
    Route::match(['get', 'post'], '/logout', [BrokerAuthController::class, 'logout'])->name('logout');

    // Protected Broker Portal Routes
    Route::middleware('broker')->group(function () {
        Route::get('/', [BrokerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [BrokerDashboardController::class, 'index']);
        Route::get('/dashboard/chart-data', [BrokerDashboardController::class, 'getChartData'])->name('dashboard.chart');
        
        // 1-Click Quick Actions
        Route::post('/bookings/{id}/approve', [BrokerDashboardController::class, 'approveBooking'])->name('bookings.approve');
        Route::post('/bookings/{id}/reject', [BrokerDashboardController::class, 'rejectBooking'])->name('bookings.reject');

        // Dynamic PG Property Management
        Route::get('/pgs', [BrokerPropertyController::class, 'index'])->name('pgs');
        Route::post('/pgs', [BrokerPropertyController::class, 'store'])->name('pgs.store');
        Route::get('/pgs/{id}', [BrokerPropertyController::class, 'show'])->name('pgs.show');
        Route::post('/pgs/{id}/update', [BrokerPropertyController::class, 'update'])->name('pgs.update');
        Route::post('/pgs/{id}/toggle-status', [BrokerPropertyController::class, 'toggleStatus'])->name('pgs.toggle-status');
        Route::delete('/pgs/{id}', [BrokerPropertyController::class, 'destroy'])->name('pgs.destroy');

        Route::view('/bookings', 'broker.bookings')->name('bookings');
        Route::view('/tenants', 'broker.tenants')->name('tenants');
        Route::view('/earnings', 'broker.earnings')->name('earnings');
        Route::view('/reviews', 'broker.reviews')->name('reviews');
        
        // Dynamic Broker Profile & Settings
        Route::get('/profile', [BrokerProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [BrokerProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/avatar', [BrokerProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::post('/profile/avatar/remove', [BrokerProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
        Route::post('/profile/bank', [BrokerProfileController::class, 'updateBankDetails'])->name('profile.bank');
        Route::post('/profile/documents', [BrokerProfileController::class, 'uploadDocument'])->name('profile.documents');
        Route::post('/profile/notifications', [BrokerProfileController::class, 'updateNotifications'])->name('profile.notifications');
        Route::post('/profile/password', [BrokerProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

Route::get('/location', function () {
    return view('user.location');
})->name('user.location');

// Public User Routes
Route::name('user.')->group(function () {
    Route::view('/saved', 'user.saved')->name('saved');
    Route::view('/list-property', 'user.list-property')->name('list-property');
    Route::view('/list_property', 'user.list-property');
    Route::view('/bookings', 'user.bookings')->name('bookings');
    Route::view('/login', 'user.login')->name('login');
    Route::match(['get', 'post'], '/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('user.login');
    })->name('logout');
    Route::get('/search', [UserHomeController::class, 'search'])->name('search');
    Route::get('/properties', [UserHomeController::class, 'search']);
    Route::get('/profile', function () {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $authUser = \Illuminate\Support\Facades\Auth::user();
            if ($authUser->roles()->whereIn('slug', ['super_admin', 'admin'])->exists()) {
                return redirect()->route('admin.dashboard');
            }
            if ($authUser->roles()->where('slug', 'broker')->exists()) {
                return redirect()->route('broker.dashboard');
            }
        }
        return view('user.profile');
    })->name('profile');
    Route::view('/pricing', 'user.pricing')->name('pricing');
    Route::view('/about', 'user.about')->name('about');
    Route::view('/contact', 'user.contact')->name('contact');
    Route::view('/contact-us', 'user.contact');
    Route::view('/terms', 'user.terms')->name('terms');
    Route::view('/terms-and-conditions', 'user.terms');
    Route::view('/privacy', 'user.privacy')->name('privacy');
    Route::view('/privacy-policy', 'user.privacy');
    Route::view('/404', 'errors.404')->name('404');
    Route::get('/detail/{slug?}', [UserHomeController::class, 'show'])->name('detail');
    Route::get('/pg-details', [UserHomeController::class, 'show']);
    Route::post('/property/{id}/report', [UserHomeController::class, 'report'])->name('property.report');
    Route::post('/property/{id}/review', [UserHomeController::class, 'submitReview'])->name('property.review');
});

// Fallback Route for 404 Not Found
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

