<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\AppController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\BrokerController;
use App\Http\Controllers\Api\v1\AdminController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
    });

    Route::middleware('auth:sanctum')->post('user/delete-account', [UserController::class, 'deleteAccount']);
    Route::get('app/check-update', [AppController::class, 'checkUpdate']);

    Route::prefix('user')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [UserController::class, 'dashboard']);
        Route::get('profile', [UserController::class, 'profile']);
    });

    Route::prefix('broker')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [BrokerController::class, 'dashboard']);
        Route::get('listings', [BrokerController::class, 'listings']);
    });

    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('users', [AdminController::class, 'users']);
    });
});
