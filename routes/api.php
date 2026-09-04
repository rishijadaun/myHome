<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\AppController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\BrokerController;

use App\Http\Controllers\Api\v1\AdminController;
use App\Http\Controllers\Api\v1\PropertySubmissionController;
use App\Http\Controllers\Api\v1\ContactInquiryController;

Route::prefix('v1')->middleware('throttle:api')->group(function () {

    // ---------------- CONTACT & INQUIRIES ----------------
    Route::post('contact', [ContactInquiryController::class, 'submit'])->middleware('throttle:contact-inquiry');
    Route::post('contact/submit', [ContactInquiryController::class, 'submit'])->middleware('throttle:contact-inquiry');

    // ---------------- 1. AUTHENTICATION (USER & BROKER) ----------------
    Route::prefix('auth')->group(function () {
        Route::post('register/request-otp', [AuthController::class, 'requestRegisterOtp'])->middleware('throttle:6,1');
        Route::post('register/verify-otp', [AuthController::class, 'verifyRegisterOtp'])->middleware('throttle:10,1');
        Route::post('register', [AuthController::class, 'register'])->middleware('throttle:register');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
        
        // Password Reset / Forgot Password Endpoints
        Route::post('forgot-password/request', [AuthController::class, 'forgotPasswordRequest'])->middleware('throttle:forgot-password');
        Route::post('forgot-password/verify', [AuthController::class, 'forgotPasswordVerify'])->middleware('throttle:forgot-password-verify');
        Route::post('forgot-password/reset', [AuthController::class, 'forgotPasswordReset'])->middleware('throttle:forgot-password');
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    // ---------------- 2. ANDROID PLAY STORE & PUBLIC DISCOVERY ----------------
    Route::prefix('app')->group(function () {
        Route::get('check-update', [AppController::class, 'checkUpdate']);
        Route::get('properties', [AppController::class, 'properties']);
        Route::get('locations', [AppController::class, 'locations']);
    });

    // ---------------- AI SEARCH ASSISTANT (REST API FOR ANDROID & CLIENTS) ----------------
    Route::post('ai/search', [\App\Http\Controllers\Api\v1\AiSearchController::class, 'search'])->middleware('throttle:30,1');

    // ---------------- 3. PROPERTY SUBMISSION & TYPES (DYNAMIC) ----------------
    Route::get('property-types', [PropertySubmissionController::class, 'types']);
    Route::post('properties/send-otp', [PropertySubmissionController::class, 'sendOtp'])->middleware('throttle:6,1');
    Route::post('properties/verify-otp', [PropertySubmissionController::class, 'verifyOtp'])->middleware('throttle:10,1');
    Route::post('properties/submit', [PropertySubmissionController::class, 'submit'])->middleware('throttle:property-submission');
    Route::get('properties/details/{id}', [PropertySubmissionController::class, 'details']);
    Route::post('properties/{id}/update', [PropertySubmissionController::class, 'update'])->middleware('throttle:property-submission');
    Route::post('properties/{id}/report', [\App\Http\Controllers\User\UserHomeController::class, 'report'])->middleware('throttle:10,1');
    Route::post('properties/{id}/review', [\App\Http\Controllers\User\UserHomeController::class, 'submitReview'])->middleware('throttle:10,1');

    // ---------------- 4. TENANT / USER MODULE APIS ----------------
    Route::prefix('user')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [UserController::class, 'dashboard']);
        Route::get('profile/{id?}', [UserController::class, 'profile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::get('bookings', [UserController::class, 'bookings']);
        Route::get('visits', [UserController::class, 'visits']);
        Route::post('visits', [UserController::class, 'scheduleVisit']);
        Route::post('change-password', [UserController::class, 'changePassword'])->middleware('throttle:5,1');
        Route::post('email/update', [UserController::class, 'updateEmail'])->middleware('throttle:5,1');
        Route::post('email/request-otp', [UserController::class, 'requestEmailOtp'])->middleware('throttle:5,1');
        Route::post('email/verify-otp', [UserController::class, 'verifyEmailOtp'])->middleware('throttle:10,1');
        Route::post('phone/update', [UserController::class, 'updatePhone'])->middleware('throttle:5,1');
        Route::post('saved/toggle', [UserController::class, 'toggleSaved']);
        Route::post('delete-account', [UserController::class, 'deleteAccount']);
    });

    // ---------------- 5. BROKER / OWNER MODULE APIS ----------------
    Route::prefix('broker')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [BrokerController::class, 'dashboard']);
        Route::get('listings', [BrokerController::class, 'listings']);
        Route::post('properties', [BrokerController::class, 'storeProperty'])->middleware('throttle:property-submission');
        Route::get('bookings', [BrokerController::class, 'bookings']);
        Route::put('bookings/{id}/status', [BrokerController::class, 'updateBookingStatus']);
        Route::get('visits', [BrokerController::class, 'visits']);
    });

    // ---------------- 6. ADMIN MODERATION & APPROVAL APIS ----------------
    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('users', [AdminController::class, 'users']);
        Route::get('properties', [AdminController::class, 'properties']);
        Route::put('properties/{id}/approve', [AdminController::class, 'approveProperty']);
        Route::put('properties/{id}/reject', [AdminController::class, 'rejectProperty']);
    });

});
