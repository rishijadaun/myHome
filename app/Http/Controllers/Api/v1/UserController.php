<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\PropertyVisit;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Tenant / User Dashboard Summary API
     */
    public function dashboard(Request $request)
    {
        $user = $request->user()->load(['profile', 'wallet']);

        $activeBookingsCount = Booking::where('user_id', $user->id)
            ->whereIn('booking_status', ['confirmed', 'pending'])
            ->count();

        $scheduledVisitsCount = PropertyVisit::where('user_id', $user->id)
            ->where('status', 'scheduled')
            ->count();

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', 0)
            ->count();

        return $this->success('User dashboard loaded', [
            'user' => [
                'id' => $user->id,
                'name' => trim(($user->profile?->first_name ?? '') . ' ' . ($user->profile?->last_name ?? '')),
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar_url' => $user->profile?->avatar_url,
                'wallet_balance' => $user->wallet?->balance ?? '0.00',
            ],
            'stats' => [
                'active_bookings' => $activeBookingsCount,
                'scheduled_visits' => $scheduledVisitsCount,
                'unread_notifications' => $unreadNotifications,
            ],
        ]);
    }

    /**
     * Tenant Profile Details API (supports current user or ID lookup)
     */
    public function profile(Request $request, $id = null)
    {
        if ($id) {
            $user = User::where('id', $id)->with(['profile', 'roles', 'wallet'])->first();
            if (! $user) {
                return $this->error('User not found in database for ID: ' . $id, [], 404);
            }
        } else {
            $user = $request->user();
            if ($user) {
                $user->load(['profile', 'roles', 'wallet']);
            }
        }

        if (! $user) {
            return $this->error('Unauthenticated or user ID not specified', [], 401);
        }

        $primaryRole = $user->roles->first()?->slug ?? 'tenant';

        return $this->success('User profile fetched successfully', [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => $user->status,
                'role' => $primaryRole,
                'first_name' => $user->profile?->first_name,
                'last_name' => $user->profile?->last_name,
                'full_name' => trim(($user->profile?->first_name ?? '') . ' ' . ($user->profile?->last_name ?? '')),
                'avatar_url' => $user->profile?->avatar_url,
                'bio' => $user->profile?->bio,
                'wallet_balance' => $user->wallet?->balance ?? '0.00',
                'created_at' => $user->created_at,
            ],
        ]);
    }

    /**
     * Update Profile Details API
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
            'preferences' => ['nullable', 'array'],
        ]);

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return $this->success('Profile updated successfully', [
            'user' => $user->load('profile')
        ]);
    }

    /**
     * User's Bookings API
     */
    public function bookings(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::where('user_id', $user->id)
            ->with(['property.city', 'property.area', 'broker.profile'])
            ->latest()
            ->paginate(15);

        return $this->success('User bookings fetched', $bookings);
    }

    /**
     * User's Scheduled Visits API
     */
    public function visits(Request $request)
    {
        $user = $request->user();

        $visits = PropertyVisit::where('user_id', $user->id)
            ->with(['property.city', 'property.area'])
            ->latest()
            ->paginate(15);

        return $this->success('User scheduled visits fetched', $visits);
    }

    /**
     * Schedule a New Visit API
     */
    public function scheduleVisit(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'property_id' => ['required', 'string', 'exists:properties,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'feedback' => ['nullable', 'string'],
        ]);

        $visit = PropertyVisit::create([
            'user_id' => $user->id,
            'property_id' => $validated['property_id'],
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'scheduled',
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return $this->success('Property visit scheduled successfully', $visit, 201);
    }

    /**
     * Change User Password API
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'max:30'],
            'new_password' => ['required', 'string', 'min:6', 'max:30', 'confirmed'],
            'new_password_confirmation' => ['required_with:new_password', 'string', 'max:30'],
        ], [
            'current_password.required' => 'Please enter your current password.',
            'current_password.max' => 'Current password cannot exceed 30 characters.',
            'new_password.required' => 'Please enter your new password.',
            'new_password.min' => 'New password must be at least 6 characters.',
            'new_password.max' => 'New password cannot exceed 30 characters.',
            'new_password.confirmed' => 'New password and confirm password do not match.',
        ]);

        if (! \Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password_hash)) {
            return $this->error('Current password is incorrect.', [
                'current_password' => ['The current password entered is not valid.']
            ], 422);
        }

        $user->password_hash = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
        $user->save();

        return $this->success('Password changed successfully! Please keep your new password safe.');
    }

    /**
     * Toggle Saved Wishlist API
     */
    public function toggleSaved(Request $request)
    {
        $user = $request->user();
        $propertyId = $request->input('property_id');
        $propertyData = $request->input('property_data');

        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
        $prefs = $profile->preferences ?? [];
        $saved = $prefs['saved_properties'] ?? [];

        $existsIndex = -1;
        foreach ($saved as $idx => $item) {
            if ((is_array($item) && ($item['id'] ?? '') === $propertyId) || $item === $propertyId) {
                $existsIndex = $idx;
                break;
            }
        }

        if ($existsIndex > -1) {
            array_splice($saved, $existsIndex, 1);
            $action = 'removed';
        } else {
            $saved[] = $propertyData ?? $propertyId;
            $action = 'added';
        }

        $prefs['saved_properties'] = $saved;
        $profile->preferences = $prefs;
        $profile->save();

        return $this->success("Property {$action} to wishlist", [
            'action' => $action,
            'saved_count' => count($saved),
            'saved' => $saved
        ]);
    }

    /**
     * Update Registered Email Directly with Database Uniqueness Check
     */
    public function updateEmail(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'new_email' => ['required', 'email', 'max:150'],
            'otp' => ['nullable', 'string'],
        ], [
            'new_email.required' => 'Please enter a new email address.',
            'new_email.email' => 'Please enter a valid email format.',
        ]);

        $newEmail = strtolower(trim($validated['new_email']));

        // 1. Check if same as current user's email
        if (strtolower($user->email ?? '') === $newEmail) {
            return $this->error('This is already your current registered email address.', [
                'new_email' => ['Please enter a different email address.']
            ], 422);
        }

        // 2. Check if email already registered by another user in database
        $emailExists = User::where('email', $newEmail)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return $this->error('This email ID is already registered in database with another account.', [
                'new_email' => ['This email address is already taken. Please choose another one.']
            ], 422);
        }

        // 3. Update email in `users` table
        $user->email = $newEmail;
        $user->save();

        return $this->success('Registered email updated successfully in database!', [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        ]);
    }

    /**
     * Verify OTP and Update Registered Email in Database
     */
    public function verifyEmailOtp(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'new_email' => ['required', 'email', 'max:150'],
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Please enter the 6-digit OTP.',
            'otp.size' => 'OTP must be exactly 6 digits.',
        ]);

        $newEmail = strtolower(trim($validated['new_email']));
        $inputOtp = trim($validated['otp']);

        $cachedData = Cache::get("email_otp_{$user->id}");

        $isValidOtp = ($cachedData && $cachedData['email'] === $newEmail && $cachedData['otp'] === $inputOtp)
            || $inputOtp === '482910'; // master demo code

        if (! $isValidOtp) {
            return $this->error('Invalid or expired OTP code. Please try again.', [
                'otp' => ['The verification code entered is incorrect.']
            ], 422);
        }

        // Re-check database uniqueness before updating
        $emailExists = User::where('email', $newEmail)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return $this->error('This email is already in use by another user.', [
                'new_email' => ['Email conflict. Please try with another email.']
            ], 422);
        }

        // Update email in `users` table in database
        $user->email = $newEmail;
        $user->save();

        // Clear cached OTP
        Cache::forget("email_otp_{$user->id}");

        return $this->success('Registered email updated successfully in database!', [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        ]);
    }

    /**
     * Delete Account API
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->status = 'deleted';
        $user->is_active = 0;
        $user->save();
        $user->delete();

        return $this->success('Your account was deleted successfully');
    }
}
