<?php

namespace App\Http\Controllers\Broker;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class BrokerAuthController extends Controller
{
    /**
     * Show the broker login view.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->roles()->whereIn('slug', ['broker', 'super_admin', 'admin'])->exists()) {
                return redirect()->route('broker.dashboard');
            }
        }

        return view('broker.login');
    }

    /**
     * Authenticate broker via API / AJAX or Web Form.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ], [
            'login.required' => 'Please enter your registered broker email or phone number.',
            'password.required' => 'Please enter your password.',
        ]);

        $throttleKey = 'broker-login:' . Str::lower($validated['login']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Too many failed login attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        $loginInput = trim($validated['login']);

        // Find user by email or phone
        $user = User::where(function ($q) use ($loginInput) {
            $q->where('email', $loginInput)
              ->orWhere('phone', $loginInput)
              ->orWhere('phone', '+91' . ltrim($loginInput, '0+91'));
        })->first();

        // Validate password
        if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
            RateLimiter::hit($throttleKey, 60);

            if ($user) {
                LoginHistory::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'login_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_method' => 'password',
                    'status' => 'failed',
                    'failure_reason' => 'Invalid broker password',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid broker email/phone or password.',
                'errors' => [
                    'login' => ['The provided credentials do not match our partner broker records.']
                ]
            ], 401);
        }

        // Validate Broker Role
        if (!$user->roles()->whereIn('slug', ['broker', 'super_admin', 'admin'])->exists()) {
            RateLimiter::hit($throttleKey, 60);

            return response()->json([
                'success' => false,
                'message' => 'Access Denied: This account is not registered as a partner broker.',
                'errors' => [
                    'login' => ['Partner broker account required for this portal.']
                ]
            ], 403);
        }

        // Validate Active Status
        if ($user->status !== 'active' || !$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your broker account is suspended or awaiting verification. Please contact StayNest admin.',
            ], 403);
        }

        // Clear rate limiter
        RateLimiter::clear($throttleKey);

        // Record successful login in login_history
        LoginHistory::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_method' => 'password',
            'status' => 'success',
        ]);

        // Authenticate Web Session
        $remember = $request->boolean('remember', true);
        Auth::login($user, $remember);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        // Generate Sanctum API Token
        $token = $user->createToken('staynest-broker-token')->plainTextToken;

        // Load profile
        $user->load(['profile', 'roles']);

        return response()->json([
            'success' => true,
            'message' => 'Login successful! Redirecting to broker dashboard...',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->profile?->full_name ?? ($user->profile?->first_name ?? $user->email),
                'email' => $user->email,
                'phone' => $user->phone,
                'company_name' => $user->profile?->company_name ?? 'Broker Partner',
                'role' => $user->roles->first()?->slug ?? 'broker',
                'kyc_verified' => !empty($user->kyc_verified_at),
            ],
            'redirect' => route('broker.dashboard'),
        ]);
    }

    /**
     * Log out broker from session and revoke tokens.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->where('name', 'staynest-broker-token')->delete();
        }

        Auth::logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
                'redirect' => route('broker.login'),
            ]);
        }

        return redirect()->route('broker.login')->with('success', 'Logged out of broker portal successfully.');
    }
}
