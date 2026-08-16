<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home');
})->name('user.home');

Route::prefix('admin')->group(function () {
    Route::view('/', 'admin.dashboard')->name('admin.dashboard');
});

Route::prefix('broker')->group(function () {
    Route::view('/', 'broker.dashboard')->name('broker.dashboard');
});

Route::get('/location', function () {
    return view('user.location');
})->name('user.location');
