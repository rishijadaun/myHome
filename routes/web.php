<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home');
})->name('user.home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/login', 'admin.login')->name('login');
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::view('/dashboard', 'admin.dashboard');
    Route::view('/pgs', 'admin.pgs')->name('pgs');
    Route::view('/brokers', 'admin.brokers')->name('brokers');
    Route::view('/bookings', 'admin.bookings')->name('bookings');
    Route::view('/users', 'admin.users')->name('users');
    Route::view('/settings', 'admin.settings')->name('settings');
});

Route::prefix('broker')->name('broker.')->group(function () {
    Route::view('/login', 'broker.login')->name('login');
    Route::view('/', 'broker.dashboard')->name('dashboard');
    Route::view('/dashboard', 'broker.dashboard');
    Route::view('/pgs', 'broker.pgs')->name('pgs');
    Route::view('/bookings', 'broker.bookings')->name('bookings');
    Route::view('/tenants', 'broker.tenants')->name('tenants');
    Route::view('/earnings', 'broker.earnings')->name('earnings');
    Route::view('/reviews', 'broker.reviews')->name('reviews');
    Route::view('/profile', 'broker.profile')->name('profile');
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
    Route::view('/search', 'user.search')->name('search');
    Route::view('/profile', 'user.profile')->name('profile');
    Route::view('/pricing', 'user.pricing')->name('pricing');
    Route::view('/about', 'user.about')->name('about');
    Route::view('/contact', 'user.contact')->name('contact');
    Route::view('/contact-us', 'user.contact');
    Route::view('/terms', 'user.terms')->name('terms');
    Route::view('/terms-and-conditions', 'user.terms');
    Route::view('/privacy', 'user.privacy')->name('privacy');
    Route::view('/privacy-policy', 'user.privacy');
    Route::view('/404', 'errors.404')->name('404');
    Route::view('/detail', 'user.detail')->name('detail');
    Route::view('/pg-details', 'user.detail');
});

// Fallback Route for 404 Not Found
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

