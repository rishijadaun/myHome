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
use Illuminate\Support\Facades\Cache;
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
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'email' => ['required_without:phone', 'nullable', 'email', 'min:5', 'max:150', 'unique:users,email'],
            'phone' => ['required_without:email', 'nullable', 'string', 'min:10', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'string', 'min:6', 'max:100'],
            'role' => ['nullable', 'string', 'in:tenant,broker,user'],
            'company_name' => ['nullable', 'string', 'max:200'],
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'first_name.max' => 'First name cannot exceed 50 characters.',
            'last_name.max' => 'Last name cannot exceed 50 characters.',
            'email.min' => 'Email address must be at least 5 characters.',
            'email.max' => 'Email address cannot exceed 150 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.max' => 'Password cannot exceed 100 characters.',
            'password.confirmed' => 'Password and Confirm Password do not match.',
            'password_confirmation.required_with' => 'Please confirm your password.',
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
            'login' => ['required', 'string', 'min:3', 'max:150'], // email or phone
            'password' => ['required', 'string', 'min:6', 'max:100'],
        ], [
            'login.required' => 'Please enter your email or phone number.',
            'login.min' => 'Login identifier must be at least 3 characters.',
            'login.max' => 'Login identifier cannot exceed 150 characters.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.max' => 'Password cannot exceed 100 characters.',
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
     * Step 1: Forgot Password - Request 6-digit OTP
     */
    public function forgotPasswordRequest(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'min:3', 'max:150'],
        ], [
            'login.required' => 'Please enter your registered email address or 10-digit mobile number.',
            'login.min' => 'Identifier must be at least 3 characters long.',
            'login.max' => 'Identifier cannot exceed 150 characters.',
        ]);

        $loginInput = trim($validated['login']);

        // Look up user by email or normalized phone
        $user = User::where(function ($q) use ($loginInput) {
            $q->where('email', strtolower($loginInput))
              ->orWhere('phone', $loginInput)
              ->orWhere('phone', '+91' . ltrim($loginInput, '0+91'));
        })->first();

        if (!$user) {
            return $this->error('No registered account found matching that email or mobile number.', [
                'login' => ['We could not find an account with this email/phone.']
            ], 404);
        }

        // Generate secure 6-digit OTP
        $otp = (string) rand(100000, 999999);

        // Store in Cache for 15 minutes
        $cacheData = [
            'otp' => $otp,
            'user_id' => $user->id,
            'login' => $loginInput,
            'email' => $user->email,
            'phone' => $user->phone,
            'created_at' => now()->timestamp,
        ];

        Cache::put("pwd_reset_{$user->id}", $cacheData, now()->addMinutes(15));
        Cache::put("pwd_reset_target_" . md5(strtolower($loginInput)), $cacheData, now()->addMinutes(15));

        // Also track in password_reset_tokens table
        if (!empty($user->email)) {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($otp), 'created_at' => now()]
            );
        }

        // Mask recipient for security and display
        $maskedTarget = '';
        if (str_contains($loginInput, '@') && !empty($user->email)) {
            $parts = explode('@', $user->email);
            $namePart = $parts[0];
            $maskedName = strlen($namePart) > 2 ? substr($namePart, 0, 2) . str_repeat('*', max(3, strlen($namePart) - 2)) : $namePart . '***';
            $maskedTarget = $maskedName . '@' . ($parts[1] ?? 'example.com');
        } elseif (!empty($user->phone)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
            $last4 = substr($cleanPhone, -4);
            $maskedTarget = '+91 ******' . $last4;
        } else {
            $maskedTarget = 'your registered contact info';
        }

        return $this->success('Verification code sent successfully!', [
            'target' => $maskedTarget,
            'expires_in' => '15 minutes',
        ]);
    }

    /**
     * Step 2: Forgot Password - Verify OTP Code
     */
    public function forgotPasswordVerify(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'min:3', 'max:150'],
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'login.required' => 'Please provide your email or mobile number.',
            'otp.required' => 'Please enter the 6-digit OTP.',
            'otp.size' => 'The OTP code must be exactly 6 digits.',
        ]);

        $loginInput = trim($validated['login']);
        $inputOtp = trim($validated['otp']);

        $user = User::where(function ($q) use ($loginInput) {
            $q->where('email', strtolower($loginInput))
              ->orWhere('phone', $loginInput)
              ->orWhere('phone', '+91' . ltrim($loginInput, '0+91'));
        })->first();

        if (!$user) {
            return $this->error('Account not found.', ['login' => ['User record not found.']], 404);
        }

        $cached = Cache::get("pwd_reset_{$user->id}") ?? Cache::get("pwd_reset_target_" . md5(strtolower($loginInput)));

        $isValidOtp = ($cached && ($cached['otp'] ?? '') === $inputOtp);

        if (!$isValidOtp) {
            return $this->error('Invalid or expired OTP code. Please try again or request a new code.', [
                'otp' => ['The OTP entered is incorrect or has expired.']
            ], 422);
        }

        return $this->success('OTP code verified successfully! Please set your new password.', [
            'verified' => true,
            'user_id' => $user->id
        ]);
    }

    /**
     * Step 3: Forgot Password - Reset and Save New Password
     */
    public function forgotPasswordReset(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'min:3', 'max:150'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'string', 'min:6', 'max:100'],
        ], [
            'login.required' => 'Login identifier is required.',
            'otp.required' => 'Verification OTP is required.',
            'otp.size' => 'OTP must be exactly 6 digits.',
            'password.required' => 'New password is required.',
            'password.min' => 'New password must be at least 6 characters.',
            'password.max' => 'New password cannot exceed 100 characters.',
            'password.confirmed' => 'New password and confirm password do not match.',
            'password_confirmation.required_with' => 'Please confirm your new password.',
        ]);

        $loginInput = trim($validated['login']);
        $inputOtp = trim($validated['otp']);

        $user = User::where(function ($q) use ($loginInput) {
            $q->where('email', strtolower($loginInput))
              ->orWhere('phone', $loginInput)
              ->orWhere('phone', '+91' . ltrim($loginInput, '0+91'));
        })->first();

        if (!$user) {
            return $this->error('User account not found.', ['login' => ['No account found.']], 404);
        }

        $cached = Cache::get("pwd_reset_{$user->id}") ?? Cache::get("pwd_reset_target_" . md5(strtolower($loginInput)));

        $isValidOtp = ($cached && ($cached['otp'] ?? '') === $inputOtp);

        if (!$isValidOtp) {
            return $this->error('Invalid or expired OTP code. Please request a new verification code.', [
                'otp' => ['Invalid verification code.']
            ], 422);
        }

        // Update User Password Hash
        $user->password_hash = Hash::make($validated['password']);
        $user->save();

        // Clear cached reset tokens
        Cache::forget("pwd_reset_{$user->id}");
        Cache::forget("pwd_reset_target_" . md5(strtolower($loginInput)));
        if (!empty($user->email)) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        }

        // Record history
        LoginHistory::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_method' => 'otp',
            'status' => 'success',
        ]);

        return $this->success('Password reset successfully! You can now log in with your new password.', [
            'login' => $user->email ?? $user->phone,
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
