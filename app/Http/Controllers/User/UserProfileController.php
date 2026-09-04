<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtpMail;
use App\Models\RoommatePost;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class UserProfileController extends Controller
{
    /**
     * Upload & update user profile avatar photo.
     * Includes strict validation on file type (JPG, JPEG, PNG, WEBP), file size (max 5MB),
     * auto-deletes old avatars, and synchronizes the photo with all active flatmate listings.
     */
    public function updateAvatar(Request $request)
    {
        // 1. Resolve authenticated user (Session or Sanctum Bearer token)
        $user = Auth::user();
        if (!$user && $request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if ($token && $token->tokenable) {
                $user = $token->tokenable;
            }
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to update your profile photo.'
            ], 401);
        }

        // 2. Strict validation on format and size
        $validated = $request->validate([
            'avatar' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5120', // 5 MB max
            ],
        ], [
            'avatar.required' => 'Please select an image file to upload.',
            'avatar.file'     => 'The uploaded item must be a valid file.',
            'avatar.image'    => 'The uploaded file must be an image.',
            'avatar.mimes'    => 'Supported image formats are JPG, JPEG, PNG, and WEBP only.',
            'avatar.max'      => 'Image file size cannot exceed 5 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Clean up previous custom avatar from disk if exists
            $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
            if ($profile->avatar_url && Str::startsWith($profile->avatar_url, '/uploads/avatars/')) {
                $oldPath = public_path(ltrim($profile->avatar_url, '/'));
                if (File::exists($oldPath)) {
                    @File::delete($oldPath);
                }
            }

            $prefix = 'user_' . Str::slug($user->id) . '_';
            $processed = app(\App\Services\ImageProcessingService::class)->processUpload($file, 'avatars', $prefix, [
                'max_width' => 600,
                'max_height' => 600,
                'quality' => 85,
            ]);

            $avatarUrl = $processed ? $processed['relative_url'] : '/uploads/avatars/default.webp';

            // Update profile
            $profile->avatar_url = $avatarUrl;
            $profile->save();

            // Synchronize with all active flatmate / roommate posts
            RoommatePost::where('user_id', $user->id)->update([
                'poster_avatar_url' => $avatarUrl
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Profile photo updated successfully! It is now live on your profile and roommate posts.',
                'avatar_url' => $avatarUrl,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file was received. Please select an image.'
        ], 400);
    }

    /**
     * Update personal profile details (Name, Bio, Gender, Occupation).
     * Mobile number editing is disabled.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user && $request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if ($token && $token->tokenable) {
                $user = $token->tokenable;
            }
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['nullable', 'string', 'max:100'],
            'gender'        => ['nullable', 'string', 'max:20'],
            'occupation'    => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'bio'           => ['nullable', 'string', 'max:1000'],
        ]);

        $dob = $request->input('date_of_birth') ?: $request->input('dob');

        $profile = UserProfile::firstOrNew(['user_id' => $user->id]);
        $profile->first_name = $validated['first_name'];
        $profile->last_name  = $validated['last_name'] ?? null;
        $profile->full_name  = trim($profile->first_name . ' ' . ($profile->last_name ?? ''));
        $profile->gender     = $validated['gender'] ?? $profile->gender;
        $profile->occupation = $validated['occupation'] ?? $profile->occupation;
        if ($dob) {
            $profile->date_of_birth = $dob;
        }
        $profile->bio = $validated['bio'] ?? null;
        $profile->save();

        // Calculate Age
        $age = $profile->age;

        // Synchronize with active roommate posts
        $syncData = [
            'poster_name'   => $profile->full_name,
            'poster_gender' => $profile->gender ? strtolower($profile->gender) : null,
            'poster_age'    => $age,
            'profession'    => $profile->occupation,
        ];
        if (!empty($user->phone)) {
            $syncData['contact_phone'] = $user->phone;
            $syncData['contact_whatsapp'] = $user->phone;
        }
        RoommatePost::where('user_id', $user->id)->update($syncData);

        return response()->json([
            'success' => true,
            'message' => 'Profile details updated successfully!',
            'data'    => [
                'first_name'    => $profile->first_name,
                'last_name'     => $profile->last_name,
                'full_name'     => $profile->full_name,
                'phone'         => $user->phone,
                'gender'        => $profile->gender,
                'occupation'    => $profile->occupation,
                'date_of_birth' => $profile->date_of_birth?->format('Y-m-d'),
                'age'           => $age,
                'tagline'       => $profile->tagline,
                'avatar_url'    => $profile->avatar_url,
                'bio'           => $profile->bio,
            ],
        ]);
    }

    /**
     * Mobile number direct update is disabled.
     */
    public function updatePhone(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Mobile number update is currently locked and cannot be modified.',
        ], 403);
    }

    /**
     * Step 1: Request OTP code sent to user's new email address
     */
    public function requestEmailOtp(Request $request)
    {
        $user = Auth::user();
        if (!$user && $request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if ($token && $token->tokenable) {
                $user = $token->tokenable;
            }
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'new_email' => ['required', 'email', 'max:150'],
        ], [
            'new_email.required' => 'Please enter your new email address.',
            'new_email.email'    => 'Please enter a valid email format (e.g. user@gmail.com).',
        ]);

        $newEmail = strtolower(trim($validated['new_email']));

        if (strtolower($user->email ?? '') === $newEmail) {
            return response()->json([
                'success' => false,
                'message' => 'This is already your current registered email address.',
                'errors'  => ['new_email' => ['Please enter a different email address.']],
            ], 422);
        }

        $emailExists = User::where('email', $newEmail)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already in use by another account.',
                'errors'  => ['new_email' => ['This email address is already taken. Please choose another one.']],
            ], 422);
        }

        // Generate secure 6-digit numeric OTP
        $otp = (string) rand(100000, 999999);

        // Store OTP in Cache for 10 minutes
        Cache::put("email_otp_{$user->id}", [
            'email'      => $newEmail,
            'otp'        => $otp,
            'user_id'    => $user->id,
            'created_at' => now()->timestamp,
        ], now()->addMinutes(10));

        // Attempt sending email OTP via high-deliverability Mailable
        try {
            $recipientName = $user->profile?->full_name ?: ($user->name ?: 'Resident');
            Mail::to($newEmail)->send(new EmailVerificationOtpMail($otp, $recipientName));
        } catch (\Throwable $e) {
            Log::error('Email OTP delivery failed: ' . $e->getMessage());
            if (config('mail.default') === 'smtp' && config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to dispatch email via SMTP: ' . $e->getMessage() . '. Please verify your Gmail SMTP credentials in .env.',
                ], 500);
            }
        }

        $resData = [
            'success'    => true,
            'message'    => "Verification code sent to {$newEmail}! Please check your Gmail / Email inbox and enter the 6-digit OTP.",
            'new_email'  => $newEmail,
            'expires_in' => '10 minutes',
        ];

        return response()->json($resData);
    }

    /**
     * Step 2: Verify OTP code and update user's email address in database
     */
    public function verifyEmailOtp(Request $request)
    {
        $user = Auth::user();
        if (!$user && $request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if ($token && $token->tokenable) {
                $user = $token->tokenable;
            }
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'new_email' => ['required', 'email', 'max:150'],
            'otp'       => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.size'     => 'OTP must be exactly 6 digits.',
        ]);

        $newEmail = strtolower(trim($validated['new_email']));
        $inputOtp = trim($validated['otp']);

        $cachedData = Cache::get("email_otp_{$user->id}");

        $isValidOtp = ($cachedData && $cachedData['email'] === $newEmail && $cachedData['otp'] === $inputOtp);

        if (!$isValidOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code. Please check the code or request a new one.',
                'errors'  => ['otp' => ['The verification code entered is incorrect or expired.']],
            ], 422);
        }

        // Re-check database uniqueness before saving
        $emailExists = User::where('email', $newEmail)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already in use by another account.',
                'errors'  => ['new_email' => ['Email conflict. Please try with another email.']],
            ], 422);
        }

        // Update email in `users` table
        $user->email = $newEmail;
        $user->save();

        // Clear cached OTP
        Cache::forget("email_otp_{$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'Your registered email address has been verified and updated successfully!',
            'email'   => $user->email,
        ]);
    }
}
