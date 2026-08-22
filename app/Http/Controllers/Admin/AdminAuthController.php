<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->roles()->whereIn('slug', ['super_admin', 'admin'])->exists()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.login');
    }

    /**
     * Handle an admin authentication attempt.
     */
    public function login(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'min:5', 'max:150'],
            'password' => ['required', 'string', 'min:4', 'max:100'],
            'remember' => ['nullable', 'boolean'],
        ], [
            'email.required' => 'Admin email address is required.',
            'email.min' => 'Admin email address must be at least 5 characters.',
            'email.max' => 'Admin email address cannot exceed 150 characters.',
            'email.email' => 'Please provide a valid administrative email address.',
            'password.required' => 'Master password is required.',
            'password.min' => 'Password must be at least 4 characters long.',
            'password.max' => 'Password cannot exceed 100 characters.',
        ]);

        $email = strtolower(trim($validated['email']));
        $throttleKey = 'admin-login:' . Str::transliterate($email . '|' . $request->ip());

        // 2. Rate Limiting (5 attempts per minute)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $msg = "Too many failed login attempts. Please try again in {$seconds} seconds.";
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'errors' => ['email' => [$msg]]
                ], 429);
            }

            return back()->withErrors(['email' => $msg])->withInput($request->only('email'));
        }

        // 3. User lookup & Authentication
        $user = User::where('email', $email)->with('roles')->first();

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
                    'failure_reason' => 'Invalid admin credentials',
                ]);
            }

            $errMsg = 'Invalid email or password. Please verify administrative credentials.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMsg,
                    'errors' => ['email' => [$errMsg]]
                ], 401);
            }

            return back()->withErrors(['email' => $errMsg])->withInput($request->only('email'));
        }

        // 4. Check account status
        if ($user->status !== 'active' || !$user->is_active) {
            $errMsg = 'This administrator account is currently suspended or inactive. Please contact system support.';
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMsg,
                    'errors' => ['email' => [$errMsg]]
                ], 403);
            }

            return back()->withErrors(['email' => $errMsg])->withInput($request->only('email'));
        }

        // 5. Role Authorization Check (super_admin or admin)
        $isAdmin = $user->roles()->whereIn('slug', ['super_admin', 'admin'])->exists();
        if (!$isAdmin) {
            RateLimiter::hit($throttleKey, 60);

            LoginHistory::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_method' => 'password',
                'status' => 'failed',
                'failure_reason' => 'Unauthorized role attempt on admin portal',
            ]);

            $errMsg = 'Access Denied: Your account does not have administrator privileges.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMsg,
                    'errors' => ['email' => [$errMsg]]
                ], 403);
            }

            return back()->withErrors(['email' => $errMsg])->withInput($request->only('email'));
        }

        // 6. Success: Clear rate limiter & Log user into session
        RateLimiter::clear($throttleKey);

        $remember = $request->boolean('remember');
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // 7. Record Login History
        LoginHistory::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_method' => 'password',
            'status' => 'success',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin authentication successful! Redirecting...',
                'redirect' => route('admin.dashboard'),
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->profile ? ($user->profile->first_name . ' ' . $user->profile->last_name) : 'Administrator',
                ]
            ]);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been safely signed out of the Administrator Console.');
    }
}
