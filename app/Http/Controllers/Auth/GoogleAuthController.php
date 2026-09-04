<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google OAuth authentication page.
     */
    public function redirectToGoogle(Request $request)
    {
        try {
            $requestedRole = $request->get('role', 'tenant');
            if (in_array($requestedRole, ['broker', 'tenant'])) {
                session(['google_signup_role' => $requestedRole]);
            }

            $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');
            return Socialite::driver('google')->redirectUrl($redirectUrl)->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route('user.login')->with('flash_error', 'Unable to connect to Google. Please check your Google credentials in configuration.');
        }
    }

    /**
     * Handle the callback returned from Google OAuth.
     */
    public function handleGoogleCallback(Request $request)
    {
        // Check if user denied permissions or cancelled
        if ($request->has('error') || !$request->has('code')) {
            $errorDesc = $request->get('error_description', 'Google sign-in was cancelled.');
            return redirect()->route('user.login')->with('flash_error', $errorDesc);
        }

        try {
            $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->redirectUrl($redirectUrl)->user();
        } catch (\Exception $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            return redirect()->route('user.login')->with('flash_error', 'Google authentication failed: ' . $e->getMessage());
        }

        $email = $googleUser->getEmail();
        $googleId = (string) $googleUser->getId();

        if (empty($email)) {
            return redirect()->route('user.login')->with('flash_error', 'Could not retrieve email address from Google.');
        }

        try {
            DB::beginTransaction();

            // 1. Check if user exists by google_id or email
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            $rawUser = $googleUser->user ?? [];
            $fullName = trim($googleUser->getName() ?: '');
            $firstName = $rawUser['given_name'] ?? null;
            $lastName = $rawUser['family_name'] ?? null;

            if (empty($firstName) && !empty($fullName)) {
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? null;
            }

            if (empty($firstName)) {
                $firstName = ucfirst(explode('@', $email)[0]);
            }

            if (empty($fullName)) {
                $fullName = trim(($firstName ?? '') . ' ' . ($lastName ?? ''));
            }

            $avatarUrl = $googleUser->getAvatar();

            if ($user) {
                // Update existing user with Google details if missing
                $dirty = false;
                if (empty($user->google_id)) {
                    $user->google_id = $googleId;
                    $dirty = true;
                }
                if (empty($user->auth_provider) || $user->auth_provider === 'local') {
                    $user->auth_provider = 'google';
                    $dirty = true;
                }
                if (empty($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $dirty = true;
                }
                if ($user->status !== 'active') {
                    $user->status = 'active';
                    $user->is_active = 1;
                    $dirty = true;
                }
                if ($dirty) {
                    $user->save();
                }

                // Ensure UserProfile exists & sync name and avatar from Google
                $profile = UserProfile::firstOrNew(['user_id' => $user->id]);
                if (!empty($firstName)) {
                    $profile->first_name = $firstName;
                }
                if (!empty($lastName)) {
                    $profile->last_name = $lastName;
                }
                if (!empty($avatarUrl)) {
                    $profile->avatar_url = $avatarUrl;
                }
                $profile->is_active = 1;
                $profile->version = $profile->version ?: 1;
                $profile->save();

                // Ensure Wallet exists
                Wallet::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'id' => (string) Str::uuid(),
                        'balance' => 0.00,
                        'currency_code' => 'INR',
                        'is_active' => 1,
                        'version' => 1,
                    ]
                );

            } else {
                // Create brand new User
                $userId = (string) Str::uuid();

                $user = User::create([
                    'id' => $userId,
                    'email' => $email,
                    'google_id' => $googleId,
                    'auth_provider' => 'google',
                    'password_hash' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'is_active' => 1,
                    'version' => 1,
                ]);

                // Create User Profile (MySQL automatically generates full_name from first_name + last_name)
                UserProfile::create([
                    'user_id' => $userId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'avatar_url' => $avatarUrl,
                    'is_active' => 1,
                    'version' => 1,
                ]);

                // Assign role (broker if requested, otherwise default tenant)
                $requestedRole = session('google_signup_role', 'tenant');
                $roleSlug = in_array($requestedRole, ['broker', 'tenant']) ? $requestedRole : 'tenant';
                session()->forget('google_signup_role');

                $role = Role::where('slug', $roleSlug)->first();
                if ($role) {
                    UserRole::create([
                        'id' => (string) Str::uuid(),
                        'user_id' => $userId,
                        'role_id' => $role->id,
                        'is_primary' => 1,
                        'is_active' => 1,
                        'created_at' => now(),
                    ]);
                }

                // Create initial Wallet
                Wallet::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $userId,
                    'balance' => 0.00,
                    'currency_code' => 'INR',
                    'is_active' => 1,
                    'version' => 1,
                ]);
            }

            // Record in login history
            LoginHistory::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_method' => 'social_google',
                'status' => 'success',
            ]);

            DB::commit();

            // Load fresh profile & role relations
            $user->load(['profile', 'roles']);

            // Log into Laravel Web Session
            Auth::login($user, true);
            $request->session()->regenerate();

            // Generate API Bearer token for client apps
            $token = $user->createToken('staynest-api-token')->plainTextToken;
            session([
                'staynest_auth_token' => $token,
                'staynest_auth_user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'avatar' => $avatarUrl,
                ]
            ]);

            // Redirection based on role
            if ($user->roles()->whereIn('slug', ['super_admin', 'admin'])->exists()) {
                return redirect()->route('admin.dashboard')->with('flash_success', 'Signed in as Administrator via Google.');
            }

            if ($user->roles()->where('slug', 'broker')->exists()) {
                return redirect()->route('broker.dashboard')->with('flash_success', 'Signed in to PG Owner Portal via Google.');
            }

            return redirect()->intended(route('user.profile'))->with('flash_success', 'Signed in successfully with Google!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Google OAuth Registration/Login Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('user.login')->with('flash_error', 'An error occurred during Google sign-in. Please try again.');
        }
    }
}
