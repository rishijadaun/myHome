<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RoommatePost;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
            $uploadDir = public_path('uploads/avatars');

            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            // Clean up previous custom avatar from disk if exists
            $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
            if ($profile->avatar_url && Str::startsWith($profile->avatar_url, '/uploads/avatars/')) {
                $oldPath = public_path(ltrim($profile->avatar_url, '/'));
                if (File::exists($oldPath)) {
                    @File::delete($oldPath);
                }
            }

            // Secure new filename
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }

            $filename = 'user_' . Str::slug($user->id) . '_' . time() . '_' . Str::random(6) . '.' . $extension;
            $file->move($uploadDir, $filename);

            $avatarUrl = '/uploads/avatars/' . $filename;

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
        RoommatePost::where('user_id', $user->id)->update([
            'poster_name'   => $profile->full_name,
            'poster_gender' => $profile->gender ? strtolower($profile->gender) : null,
            'poster_age'    => $age,
            'profession'    => $profile->occupation,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile details updated successfully!',
            'data'    => [
                'first_name'    => $profile->first_name,
                'last_name'     => $profile->last_name,
                'full_name'     => $profile->full_name,
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
}
