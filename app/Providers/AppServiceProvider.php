<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

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
        $this->configureRateLimiting();

        \Illuminate\Support\Facades\View::composer('admin.*', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('properties')) {
                $brokerRole = \App\Models\Role::where('slug', 'broker')->first();
                $pendingBrokersCount = $brokerRole ? $brokerRole->users()->where(function ($q) {
                    $q->where('users.status', 'pending_verification')->orWhereNull('users.kyc_verified_at');
                })->count() : 0;

                $pendingReviewsCount = \Illuminate\Support\Facades\Schema::hasTable('reviews')
                    ? \App\Models\Review::where('status', 'pending')->count()
                    : 0;

                $pendingContactsCount = \Illuminate\Support\Facades\Schema::hasTable('contact_inquiries')
                    ? \App\Models\ContactInquiry::where('status', 'new')->count()
                    : 0;

                $totalContactsCount = \Illuminate\Support\Facades\Schema::hasTable('contact_inquiries')
                    ? \App\Models\ContactInquiry::count()
                    : 0;

                $pendingReportsCount = \Illuminate\Support\Facades\Schema::hasTable('property_reports')
                    ? \App\Models\PropertyReport::where('status', 'pending')->count()
                    : 0;

                $totalReportsCount = \Illuminate\Support\Facades\Schema::hasTable('property_reports')
                    ? \App\Models\PropertyReport::count()
                    : 0;

                $pendingKycCount = $brokerRole ? $brokerRole->users()->where(function ($q) {
                    $q->whereNull('users.kyc_verified_at');
                })->count() : 0;

                $view->with('adminSidebarStats', [
                    'properties' => \App\Models\Property::count(),
                    'pendingBrokers' => $pendingBrokersCount,
                    'pendingKyc' => $pendingKycCount,
                    'bookings' => \App\Models\Booking::count(),
                    'users' => \App\Models\User::count(),
                    'pendingReviews' => $pendingReviewsCount,
                    'totalReviews' => \Illuminate\Support\Facades\Schema::hasTable('reviews') ? \App\Models\Review::count() : 0,
                    'pendingContacts' => $pendingContactsCount,
                    'totalContacts' => $totalContactsCount,
                    'pendingReports' => $pendingReportsCount,
                    'totalReports' => $totalReportsCount,
                    'roommates' => \Illuminate\Support\Facades\Schema::hasTable('roommate_posts') ? \App\Models\RoommatePost::count() : 0,
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

    /**
     * Configure rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Global baseline rate limiter for API (60 requests/minute)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                return response()->json([
                    'success' => false,
                    'message' => "Too many API requests. Please slow down and try again in {$retryAfter} seconds.",
                    'retry_after' => (int) $retryAfter,
                ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
            });
        });

        // Strict rate limiter for Login (5 attempts/minute per IP + login identity)
        RateLimiter::for('login', function (Request $request) {
            $loginInput = $request->input('login', $request->input('email', ''));
            $throttleKey = Str::transliterate(Str::lower($loginInput) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey)->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                $msg = "Too many failed login attempts. Please try again in {$retryAfter} seconds.";

                if ($request->expectsJson() || $request->wantsJson() || str_starts_with($request->path(), 'api/')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'retry_after' => (int) $retryAfter,
                        'errors' => ['login' => [$msg]]
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }

                return back()->withErrors(['login' => $msg, 'email' => $msg])->withInput($request->only('email', 'login'));
            });
        });

        // Strict rate limiter for User/Broker Registration (5 attempts/minute per IP)
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                $msg = "Too many registration attempts. Please wait {$retryAfter} seconds before trying again.";

                if ($request->expectsJson() || $request->wantsJson() || str_starts_with($request->path(), 'api/')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'retry_after' => (int) $retryAfter,
                        'errors' => ['email' => [$msg]]
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }

                return back()->withErrors(['email' => $msg])->withInput();
            });
        });

        // Contact Inquiries Rate Limiter (5 requests/minute per IP)
        RateLimiter::for('contact-inquiry', function (Request $request) {
            $key = 'contact_' . $request->ip();

            return Limit::perMinute(5)->by($key)->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                $msg = "Too many inquiry submissions from your network. Please wait {$retryAfter} seconds before trying again.";

                if ($request->expectsJson() || $request->wantsJson() || str_starts_with($request->path(), 'api/')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'retry_after' => (int) $retryAfter,
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }

                return back()->with('error', $msg)->withInput();
            });
        });

        // Forgot Password OTP Request Rate Limiter (4 requests/minute per IP + login)
        RateLimiter::for('forgot-password', function (Request $request) {
            $loginInput = $request->input('login', $request->input('email', ''));
            $key = 'pwd_req_' . Str::transliterate(Str::lower($loginInput) . '|' . $request->ip());

            return Limit::perMinute(4)->by($key)->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                $msg = "Too many password reset requests. Please wait {$retryAfter} seconds before requesting a new code.";

                if ($request->expectsJson() || $request->wantsJson() || str_starts_with($request->path(), 'api/')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'retry_after' => (int) $retryAfter,
                        'errors' => ['login' => [$msg]]
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }

                return back()->withErrors(['login' => $msg])->withInput();
            });
        });

        // Forgot Password OTP Verify Rate Limiter (8 attempts/minute per IP)
        RateLimiter::for('forgot-password-verify', function (Request $request) {
            $key = 'pwd_verify_' . $request->ip();

            return Limit::perMinute(8)->by($key)->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                $msg = "Too many verification attempts. Please wait {$retryAfter} seconds before trying again.";

                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'retry_after' => (int) $retryAfter,
                    'errors' => ['otp' => [$msg]]
                ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
            });
        });

        // Property Submission & Update Rate Limiter (10 requests/minute per User or IP)
        RateLimiter::for('property-submission', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();

            return Limit::perMinute(10)->by($key)->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                $msg = "Too many property submission requests. Please wait {$retryAfter} seconds before submitting again.";

                if ($request->expectsJson() || $request->wantsJson() || str_starts_with($request->path(), 'api/')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'retry_after' => (int) $retryAfter,
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }

                return back()->withErrors(['property' => $msg]);
            });
        });
    }
}
