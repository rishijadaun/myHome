<?php

use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\User\RoommateController;
use App\Http\Controllers\User\UserProfileController;

Route::get('/', [UserHomeController::class, 'index'])->name('user.home');

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminBrokerController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminRelationshipManagerController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminRoommateController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit')->middleware('throttle:login');
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
        Route::post('/pgs', [AdminPropertyController::class, 'store'])->name('pgs.store')->middleware('throttle:property-submission');
        Route::get('/pgs/{id}', [AdminPropertyController::class, 'show'])->name('pgs.show');
        Route::post('/pgs/{id}/toggle-status', [AdminPropertyController::class, 'toggleStatus'])->name('pgs.toggle');
        Route::post('/pgs/{id}/toggle-recommended', [AdminPropertyController::class, 'toggleRecommended'])->name('pgs.toggle-recommended');
        Route::post('/pgs/{id}/approve', [AdminPropertyController::class, 'approve'])->name('pgs.approve');
        Route::post('/pgs/{id}/update-tag', [AdminPropertyController::class, 'updateTag'])->name('pgs.update-tag');
        Route::delete('/pgs/{id}', [AdminPropertyController::class, 'destroy'])->name('pgs.destroy');
        Route::delete('/properties/{id}', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');
        // Manage Reviews & Moderation
        Route::get('/reviews', [AdminPropertyController::class, 'indexReviews'])->name('reviews');
        Route::post('/reviews/{id}/approve', [AdminPropertyController::class, 'approveReview'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [AdminPropertyController::class, 'rejectReview'])->name('reviews.reject');
        Route::post('/reviews/{id}/reply', [AdminPropertyController::class, 'replyReview'])->name('reviews.reply');
        Route::delete('/reviews/{id}', [AdminPropertyController::class, 'destroyReview'])->name('reviews.destroy');

        // Manage Reported Listings & Moderation Routes
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
        Route::get('/reports/{id}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::post('/reports/{id}/status', [AdminReportController::class, 'updateStatus'])->name('reports.status');
        Route::post('/reports/{id}/property-action', [AdminReportController::class, 'takePropertyAction'])->name('reports.property-action');
        Route::delete('/reports/{id}', [AdminReportController::class, 'destroy'])->name('reports.destroy');

        // Manage Roommates / Flatmates Routes
        Route::get('/roommates', [AdminRoommateController::class, 'index'])->name('roommates');
        Route::delete('/roommates/{id}', [AdminRoommateController::class, 'destroy'])->name('roommates.destroy');
        Route::post('/roommates/{id}/status', [AdminRoommateController::class, 'toggleStatus'])->name('roommates.status');
        Route::post('/roommates/bulk-delete', [AdminRoommateController::class, 'bulkDelete'])->name('roommates.bulk-delete');

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

        // Manage Contact Inquiries Routes
        Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts');
        Route::get('/contacts/export', [AdminContactController::class, 'export'])->name('contacts.export');
        Route::get('/contacts/{id}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::post('/contacts/{id}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.status');
        Route::delete('/contacts/{id}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

        // Settings Routes
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/profile', [AdminSettingController::class, 'updateProfile'])->name('settings.profile');

        // System Bookings Management Routes
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
        Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
        Route::post('/bookings/{id}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{id}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    });
});

use App\Http\Controllers\Broker\BrokerAuthController;
use App\Http\Controllers\Broker\BrokerDashboardController;
use App\Http\Controllers\Broker\BrokerProfileController;
use App\Http\Controllers\Broker\BrokerPropertyController;
use App\Http\Controllers\Broker\BrokerBookingController;

Route::prefix('broker')->name('broker.')->group(function () {
    // Guest Broker Routes
    Route::get('/login', [BrokerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [BrokerAuthController::class, 'login'])->name('login.submit')->middleware('throttle:login');
    Route::match(['get', 'post'], '/logout', [BrokerAuthController::class, 'logout'])->name('logout');

    // Protected Broker Portal Routes
    Route::middleware('broker')->group(function () {
        Route::get('/', [BrokerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [BrokerDashboardController::class, 'index']);
        Route::get('/dashboard/chart-data', [BrokerDashboardController::class, 'getChartData'])->name('dashboard.chart');
        
        // 1-Click Quick Actions
        Route::post('/bookings/{id}/approve', [BrokerBookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{id}/reject', [BrokerBookingController::class, 'reject'])->name('bookings.reject');
        Route::post('/bookings/{id}/complete', [BrokerBookingController::class, 'complete'])->name('bookings.complete');
        Route::get('/bookings/export', [BrokerBookingController::class, 'export'])->name('bookings.export');

        // Dynamic PG Property Management
        Route::get('/pgs', [BrokerPropertyController::class, 'index'])->name('pgs');
        Route::post('/pgs', [BrokerPropertyController::class, 'store'])->name('pgs.store')->middleware('throttle:property-submission');
        Route::get('/pgs/{id}', [BrokerPropertyController::class, 'show'])->name('pgs.show');
        Route::post('/pgs/{id}/update', [BrokerPropertyController::class, 'update'])->name('pgs.update');
        Route::post('/pgs/{id}/toggle-status', [BrokerPropertyController::class, 'toggleStatus'])->name('pgs.toggle-status');
        Route::delete('/pgs/{id}', [BrokerPropertyController::class, 'destroy'])->name('pgs.destroy');

        // Property Owner Bookings
        Route::get('/bookings', [BrokerBookingController::class, 'index'])->name('bookings');
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

use App\Http\Controllers\User\UserBookingController;

Route::get('/explore-near-me', [UserHomeController::class, 'location'])->name('user.location');
Route::permanentRedirect('/location', '/explore-near-me');

// Public User Routes
Route::name('user.')->group(function () {
    // ── Roommate / Flatmate Finder ─────────────────────────────────────
    Route::get('/find-roommate', [RoommateController::class, 'index'])->name('roommate.index');
    Route::get('/find-roommate/post', [RoommateController::class, 'create'])->name('roommate.create');
    Route::post('/find-roommate/post', [RoommateController::class, 'store'])->name('roommate.store');
    Route::get('/find-roommate/unread-stats', [RoommateController::class, 'getUnreadStats'])->name('roommate.unreadStats');
    Route::get('/find-roommate/{slug}', [RoommateController::class, 'show'])->name('roommate.show');
    Route::get('/find-roommate/{slug}/edit', [RoommateController::class, 'edit'])->name('roommate.edit');
    Route::put('/find-roommate/{slug}', [RoommateController::class, 'update'])->name('roommate.update');
    Route::post('/find-roommate/{slug}/fill', [RoommateController::class, 'markFilled'])->name('roommate.fill');
    Route::delete('/find-roommate/{slug}', [RoommateController::class, 'destroy'])->name('roommate.destroy');
    Route::get('/find-roommate/{slug}/messages', [RoommateController::class, 'getMessages'])->name('roommate.messages');
    Route::post('/find-roommate/{slug}/message', [RoommateController::class, 'sendMessage'])->name('roommate.message');
    Route::post('/find-roommate/{slug}/chat/bot-reply', [RoommateController::class, 'getBotReply'])->name('roommate.botReply');
    // Also accept /flatmate as an alias
    Route::get('/flatmate', fn() => redirect()->route('user.roommate.index', [], 301));
    Route::get('/roommate', fn() => redirect()->route('user.roommate.index', [], 301));
    // ──────────────────────────────────────────────────────────────────

    Route::view('/saved', 'user.saved')->name('saved');
    Route::view('/list-property', 'user.list-property')->name('list-property');
    Route::view('/list_property', 'user.list-property');
    Route::view('/list-pg-free', 'user.list-property')->name('list-pg-free');
    Route::view('/post-your-property', 'user.list-property')->name('post-your-property');
    Route::view('/post-property', 'user.list-property')->name('post-property');
    Route::view('/post-property-free', 'user.list-property');
    Route::view('/list-your-property', 'user.list-property');
    Route::view('/add-pg', 'user.list-property');
    Route::view('/add-property', 'user.list-property');
    Route::view('/post-flat', 'user.list-property');
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings');
    Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{id}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');
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
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('user.login')->with('flash_info', 'Please sign in to view your profile.');
        }

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user->roles()->whereIn('slug', ['super_admin', 'admin'])->exists()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->roles()->where('slug', 'broker')->exists()) {
            return redirect()->route('broker.dashboard');
        }

        // Ensure UserProfile record is guaranteed to exist
        \App\Models\UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $user->name ?: 'Tenant',
                'is_active' => true,
                'version' => 1,
            ]
        );

        $user->load(['profile', 'roommatePost', 'roles', 'wallet']);
        $roommatePost = $user->roommatePost;
        $isTenant = $user->isTenant();

        return view('user.profile', compact('user', 'roommatePost', 'isTenant'));
    })->name('profile');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/update', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::view('/pricing', 'user.pricing')->name('pricing');
    Route::view('/about-us', 'user.about')->name('about_us');
    Route::view('/about-us', 'user.about')->name('about');
    Route::view('/about', 'user.about');
    
    Route::view('/contact-us', 'user.contact')->name('contact_us');
    Route::view('/contact-us', 'user.contact')->name('contact');
    Route::view('/contact', 'user.contact');
    Route::post('/contact-us', [\App\Http\Controllers\Api\v1\ContactInquiryController::class, 'submit'])->name('contact.submit');
    Route::post('/contact', [\App\Http\Controllers\Api\v1\ContactInquiryController::class, 'submit']);
    // SEO-Friendly Legal Pages (canonical URLs)
    Route::view('/terms-of-service', 'user.terms')->name('terms');
    Route::view('/privacy-policy', 'user.privacy')->name('privacy');
    // Legacy URL redirects → 301 to canonical SEO URLs (preserves backlinks & crawl equity)
    Route::get('/terms', fn() => redirect()->route('user.terms', [], 301));
    Route::get('/terms-and-conditions', fn() => redirect()->route('user.terms', [], 301));
    Route::get('/privacy', fn() => redirect()->route('user.privacy', [], 301));
    Route::view('/404', 'errors.404')->name('404');
    Route::get('/detail/{slug?}', [UserHomeController::class, 'show'])->name('detail');
    Route::get('/pg-details', [UserHomeController::class, 'show']);
    Route::post('/property/{id}/report', [UserHomeController::class, 'report'])->name('property.report');
    Route::post('/property/{id}/review', [UserHomeController::class, 'submitReview'])->name('property.review');
    Route::post('/ai/search', [\App\Http\Controllers\Api\v1\AiSearchController::class, 'search'])->name('ai.search');
});

// Clean Programmatic SEO Landing Pages
Route::get('/pg-in-{city}/{area?}', [UserHomeController::class, 'seoSearch'])->name('user.seo.city-area');
Route::get('/flats-in-{city}/{area?}', [UserHomeController::class, 'seoFlatSearch'])->name('user.seo.flats');
Route::get('/commercial-in-{city}/{area?}', [UserHomeController::class, 'seoCommercialSearch'])->name('user.seo.commercial');
Route::get('/properties-for-sale-in-{city}/{area?}', [UserHomeController::class, 'seoSaleSearch'])->name('user.seo.sale');

// Dynamic XML Sitemap for SEO
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap', [\App\Http\Controllers\SitemapController::class, 'index']);

Route::post('/ai/search', [\App\Http\Controllers\Api\v1\AiSearchController::class, 'search'])->name('ai.search');

// Fallback Route for 404 Not Found
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

