<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
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
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * User & Broker Registration API
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required_without:phone', 'nullable', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required_without:email', 'nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'string'],
            'role' => ['nullable', 'string', 'in:tenant,broker,user'],
            'company_name' => ['nullable', 'string', 'max:200'],
        ], [
            'password.confirmed' => 'Password and Confirm Password do not match.',
            'password.min' => 'Password must be at least 6 characters.',
            'email.unique' => 'This email address is already registered.',
            'phone.unique' => 'This phone number is already registered.',
        ]);

        $roleSlug = ($validated['role'] ?? 'tenant') === 'broker' ? 'broker' : 'tenant';

        // Normalize Phone format (+91XXXXXXXXXX)
        $phone = null;
        if (!empty($validated['phone'])) {
            $digits = preg_replace('/[^0-9]/', '', $validated['phone']);
            if (strlen($digits) === 10) {
                $phone = '+91' . $digits;
            } else if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
                $phone = '+' . $digits;
            } else {
                $phone = $validated['phone'];
            }
        }

        // Check phone uniqueness after normalization
        if ($phone && User::where('phone', $phone)->exists()) {
            return $this->error('This phone number is already registered.', [
                'phone' => ['This mobile number is already associated with an account.']
            ], 422);
        }

        try {
            DB::beginTransaction();

            $userId = (string) Str::uuid();

            // 1. Create User in `users` table
            $user = User::create([
                'id' => $userId,
                'email' => $validated['email'] ?? null,
                'phone' => $phone,
                'password_hash' => Hash::make($validated['password']),
                'status' => 'active',
                'is_active' => 1,
                'version' => 1,
            ]);

            // 2. Create User Profile in `user_profiles` table
            UserProfile::create([
                'user_id' => $userId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'] ?? null,
                'company_name' => $validated['company_name'] ?? null,
                'is_active' => 1,
                'version' => 1,
            ]);

            // 3. Assign Role in `user_roles` table
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

            // 4. Initialize Wallet in `wallets` table
            Wallet::create([
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'balance' => 0.00,
                'currency_code' => 'INR',
                'is_active' => 1,
                'version' => 1,
            ]);

            // 5. Record in `login_history` table
            LoginHistory::create([
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_method' => 'password',
                'status' => 'success',
            ]);

            DB::commit();

            // 6. Log into Web Session if request comes from web browser
            Auth::login($user);

            // 7. Generate Sanctum Bearer Token
            $token = $user->createToken('staynest-api-token')->plainTextToken;

            // Load profile and roles
            $user->load(['profile', 'roles']);

            return $this->success('Registration successful! Redirecting...', [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'first_name' => $user->profile?->first_name,
                    'last_name' => $user->profile?->last_name,
                    'company_name' => $user->profile?->company_name,
                    'role' => $roleSlug,
                    'status' => $user->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Registration failed: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * User & Broker Login API
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string'], // email or phone
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Please enter your email or phone number.',
            'password.required' => 'Please enter your password.',
        ]);

        $loginInput = trim($validated['login']);

        // Find user by email or phone
        $user = User::where(function ($q) use ($loginInput) {
            $q->where('email', $loginInput)
              ->orWhere('phone', $loginInput)
              ->orWhere('phone', '+91' . ltrim($loginInput, '0+91'));
        })->first();

        if (! $user || ! Hash::check($validated['password'], $user->password_hash)) {
            // Log failed attempt if user found
            if ($user) {
                LoginHistory::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'login_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_method' => 'password',
                    'status' => 'failed',
                    'failure_reason' => 'Invalid password',
                ]);
            }

            return $this->error('Invalid email/phone or password', [
                'login' => ['The provided credentials do not match our records.']
            ], 401);
        }

        // Validate Active Status (ONLY active accounts are allowed to log in)
        if ($user->status !== 'active' || !$user->is_active) {
            LoginHistory::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_method' => 'password',
                'status' => 'failed',
                'failure_reason' => 'Inactive or non-active account (' . $user->status . ')',
            ]);

            $statusMsg = match ($user->status) {
                'pending_verification' => 'Your account is pending verification and approval by StayNest Admin.',
                'rejected' => 'Your account registration was rejected. Please contact StayNest support.',
                'suspended' => 'Your account has been suspended by administrator. Please contact StayNest support.',
                default => 'Your account is currently inactive. Only active accounts are permitted to log in.',
            };

            return $this->error($statusMsg, [
                'login' => [$statusMsg]
            ], 403);
        }

        // Record successful login in `login_history`
        LoginHistory::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_method' => 'password',
            'status' => 'success',
        ]);

        // Log into Web Session
        Auth::login($user);

        // Generate Sanctum Token
        $token = $user->createToken('staynest-api-token')->plainTextToken;

        // Load profile and primary role
        $user->load(['profile', 'roles']);
        $primaryRole = $user->roles->first()?->slug ?? 'tenant';

        return $this->success('Login successful! Redirecting...', [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->profile?->first_name,
                'last_name' => $user->profile?->last_name,
                'company_name' => $user->profile?->company_name,
                'avatar_url' => $user->profile?->avatar_url,
                'role' => $primaryRole,
                'status' => $user->status,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Get Current Authenticated User Profile API
     */
    public function me(Request $request)
    {
        $user = $request->user()->load(['profile', 'roles', 'wallet']);
        $primaryRole = $user->roles->first()?->slug ?? 'tenant';

        return $this->success('User profile loaded', [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->profile?->first_name,
                'last_name' => $user->profile?->last_name,
                'company_name' => $user->profile?->company_name,
                'avatar_url' => $user->profile?->avatar_url,
                'bio' => $user->profile?->bio,
                'role' => $primaryRole,
                'wallet_balance' => $user->wallet?->balance ?? '0.00',
                'status' => $user->status,
            ]
        ]);
    }

    /**
     * Logout API (Revoke Token and Session)
     */
    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->success('Logged out successfully');
    }
}
