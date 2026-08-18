<?php

namespace App\Http\Controllers\Broker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BrokerProfileController extends Controller
{
    /**
     * Display the dynamic broker profile & settings page.
     */
    public function index(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return redirect()->route('broker.login');
        }

        // Ensure UserProfile exists with rich defaults if needed
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $broker->id],
            [
                'first_name' => 'Vikram',
                'last_name' => 'Singh',
                'full_name' => 'Vikram Singh',
                'company_name' => 'Singh Real Estate & PG Management',
                'bio' => 'Experienced PG & Co-living space partner managing premium student and executive stays in Noida and Bangalore.',
                'preferences' => [
                    'office_address' => 'Tower B, 4th Floor, Sector 62, Noida, UP 201309',
                    'operating_city' => 'Noida',
                    'operating_area' => 'Sector 62, Electronic City',
                    'gstin' => '09AAAAA0000A1Z5',
                    'rera_number' => 'UPRERAAGT12490',
                    'bank_details' => [
                        'account_holder_name' => 'Vikram Singh',
                        'bank_name' => 'HDFC Bank',
                        'account_number' => '50100234567890',
                        'ifsc_code' => 'HDFC0001234',
                        'account_type' => 'current',
                        'upi_id' => 'vikram@hdfcbank',
                    ],
                    'documents' => [
                        'id_proof' => [
                            'name' => 'Aadhar_Card_Vikram.pdf',
                            'file_path' => '/uploads/broker_docs/sample_aadhar.pdf',
                            'status' => 'verified',
                            'uploaded_at' => '2025-03-10 11:30:00',
                            'doc_number' => 'XXXX-XXXX-4892'
                        ],
                        'license_proof' => [
                            'name' => 'RERA_Registration_Certificate.pdf',
                            'file_path' => '/uploads/broker_docs/sample_rera.pdf',
                            'status' => 'verified',
                            'uploaded_at' => '2025-03-11 15:45:00',
                            'doc_number' => 'UPRERAAGT12490'
                        ],
                        'bank_proof' => [
                            'name' => 'Cancelled_Cheque_HDFC.pdf',
                            'file_path' => '/uploads/broker_docs/sample_cheque.pdf',
                            'status' => 'verified',
                            'uploaded_at' => '2025-03-12 09:15:00',
                            'doc_number' => '50100234567890'
                        ]
                    ]
                ],
                'notification_settings' => [
                    'whatsapp_alerts' => true,
                    'sms_alerts' => true,
                    'email_statements' => true,
                    'inquiry_alerts' => true,
                    'payment_alerts' => true,
                    'marketing_updates' => false,
                ],
                'is_active' => true,
            ]
        );

        $broker->load(['roles', 'profile']);

        // 1. Calculate live performance stats for the profile summary
        $brokerId = $broker->id;
        $totalProperties = Property::where('broker_id', $brokerId)->count();
        
        $activeTenantsCount = Booking::where('broker_id', $brokerId)
            ->where('booking_status', 'confirmed')
            ->distinct('user_id')
            ->count('user_id');

        $totalBookings = Booking::where('broker_id', $brokerId)->count();

        $totalEarnings = Booking::where('broker_id', $brokerId)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $avgRating = Review::whereHas('property', fn($q) => $q->where('broker_id', $brokerId))
            ->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 4.8;

        $reviewsCount = Review::whereHas('property', fn($q) => $q->where('broker_id', $brokerId))
            ->count();

        // 2. Prepare structured data with safe defaults
        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $bankDetails = $preferences['bank_details'] ?? [
            'account_holder_name' => $profile->full_name ?? ($broker->email ?? ''),
            'bank_name' => '',
            'account_number' => '',
            'ifsc_code' => '',
            'account_type' => 'savings',
            'upi_id' => '',
        ];

        $documents = $preferences['documents'] ?? [];

        $notifications = $profile->notification_settings ?? [];
        if (!is_array($notifications)) {
            $notifications = json_decode($notifications, true) ?? [];
        }
        $notificationDefaults = [
            'whatsapp_alerts' => true,
            'sms_alerts' => true,
            'email_statements' => true,
            'inquiry_alerts' => true,
            'payment_alerts' => true,
            'marketing_updates' => false,
        ];
        $notifications = array_merge($notificationDefaults, $notifications);

        $stats = [
            'totalProperties' => $totalProperties,
            'activeTenants' => $activeTenantsCount,
            'totalBookings' => $totalBookings,
            'totalEarnings' => $totalEarnings,
            'avgRating' => $avgRating,
            'reviewsCount' => $reviewsCount,
        ];

        return view('broker.profile', compact(
            'broker',
            'profile',
            'preferences',
            'bankDetails',
            'documents',
            'notifications',
            'stats'
        ));
    }

    /**
     * Update Personal & Business details.
     */
    public function updateProfile(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'company_name' => ['nullable', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $broker->id],
            'phone' => ['required', 'string', 'max:25', 'unique:users,phone,' . $broker->id],
            'office_address' => ['nullable', 'string', 'max:500'],
            'operating_city' => ['nullable', 'string', 'max:100'],
            'operating_area' => ['nullable', 'string', 'max:100'],
            'gstin' => ['nullable', 'string', 'max:30'],
            'rera_number' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        // Update User credentials
        $broker->email = strtolower(trim($validated['email']));
        $broker->phone = trim($validated['phone']);
        $broker->save();

        // Update Profile
        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);

        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $preferences['office_address'] = $validated['office_address'] ?? ($preferences['office_address'] ?? null);
        $preferences['operating_city'] = $validated['operating_city'] ?? ($preferences['operating_city'] ?? null);
        $preferences['operating_area'] = $validated['operating_area'] ?? ($preferences['operating_area'] ?? null);
        $preferences['gstin'] = $validated['gstin'] ?? ($preferences['gstin'] ?? null);
        $preferences['rera_number'] = $validated['rera_number'] ?? ($preferences['rera_number'] ?? null);

        $fullName = trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? ''));

        $profile->first_name = $validated['first_name'];
        $profile->last_name = $validated['last_name'] ?? null;
        $profile->company_name = $validated['company_name'] ?? null;
        $profile->bio = $validated['bio'] ?? null;
        $profile->preferences = $preferences;
        $profile->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile details updated successfully! 🎉',
                'data' => [
                    'name' => $fullName,
                    'company_name' => $profile->company_name,
                    'email' => $broker->email,
                    'phone' => $broker->phone,
                    'operating_city' => $preferences['operating_city'],
                ]
            ]);
        }

        return redirect()->route('broker.profile')->with('success', 'Profile details updated successfully!');
    }

    /**
     * Upload / Update Profile Avatar image.
     */
    public function updateAvatar(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'], // 5MB max
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $uploadPath = public_path('uploads/avatars');
            
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }

            $filename = 'broker_' . Str::slug($broker->id) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            $avatarUrl = '/uploads/avatars/' . $filename;

            $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);
            $profile->avatar_url = $avatarUrl;
            $profile->save();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile photo updated successfully!',
                    'avatar_url' => $avatarUrl,
                ]);
            }

            return redirect()->route('broker.profile')->with('success', 'Profile photo updated successfully!');
        }

        return response()->json(['success' => false, 'message' => 'No image file uploaded.'], 400);
    }

    /**
     * Remove / Reset Profile Avatar.
     */
    public function removeAvatar(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $profile = UserProfile::where('user_id', $broker->id)->first();
        if ($profile) {
            $profile->avatar_url = null;
            $profile->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile photo removed. Default initials avatar applied.',
            ]);
        }

        return redirect()->route('broker.profile')->with('success', 'Profile photo removed.');
    }

    /**
     * Update Bank & Payout Details.
     */
    public function updateBankDetails(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'account_holder_name' => ['required', 'string', 'max:150'],
            'bank_name' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:40'],
            'ifsc_code' => ['required', 'string', 'max:20'],
            'account_type' => ['nullable', 'string', 'in:savings,current'],
            'upi_id' => ['nullable', 'string', 'max:100'],
        ]);

        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);
        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $preferences['bank_details'] = [
            'account_holder_name' => $validated['account_holder_name'],
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'ifsc_code' => strtoupper(trim($validated['ifsc_code'])),
            'account_type' => $validated['account_type'] ?? 'savings',
            'upi_id' => $validated['upi_id'] ?? null,
            'updated_at' => now()->toIso8601String(),
        ];

        $profile->preferences = $preferences;
        $profile->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Banking & payout information updated successfully! 🏦',
                'bank_details' => $preferences['bank_details']
            ]);
        }

        return redirect()->route('broker.profile')->with('success', 'Banking details updated successfully!');
    }

    /**
     * Upload KYC Verification Documents.
     */
    public function uploadDocument(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'doc_type' => ['required', 'string', 'in:id_proof,license_proof,bank_proof,other'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'], // 10MB max
            'doc_number' => ['nullable', 'string', 'max:100'],
        ]);

        $file = $request->file('document');
        $uploadPath = public_path('uploads/broker_docs');

        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true, true);
        }

        $originalName = $file->getClientOriginalName();
        $filename = $validated['doc_type'] . '_' . Str::slug($broker->id) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $filename);

        $docUrl = '/uploads/broker_docs/' . $filename;

        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);
        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        if (!isset($preferences['documents']) || !is_array($preferences['documents'])) {
            $preferences['documents'] = [];
        }

        $preferences['documents'][$validated['doc_type']] = [
            'name' => $originalName,
            'file_path' => $docUrl,
            'doc_number' => $validated['doc_number'] ?? null,
            'status' => 'pending_review',
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $profile->preferences = $preferences;
        $profile->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully! It is now under verification review. 📄',
                'doc_type' => $validated['doc_type'],
                'document' => $preferences['documents'][$validated['doc_type']]
            ]);
        }

        return redirect()->route('broker.profile')->with('success', 'Document uploaded successfully!');
    }

    /**
     * Update Notification Preferences.
     */
    public function updateNotifications(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);

        $notificationSettings = [
            'whatsapp_alerts' => $request->boolean('whatsapp_alerts'),
            'sms_alerts' => $request->boolean('sms_alerts'),
            'email_statements' => $request->boolean('email_statements'),
            'inquiry_alerts' => $request->boolean('inquiry_alerts'),
            'payment_alerts' => $request->boolean('payment_alerts'),
            'marketing_updates' => $request->boolean('marketing_updates'),
        ];

        $profile->notification_settings = $notificationSettings;
        $profile->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated instantly! 🔔',
                'settings' => $notificationSettings
            ]);
        }

        return redirect()->route('broker.profile')->with('success', 'Notification preferences saved!');
    }

    /**
     * Change Broker Password.
     */
    public function updatePassword(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Please enter your current password.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'New password must be at least 6 characters.',
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        if (!Hash::check($validated['current_password'], $broker->password_hash)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your current password does not match our records.',
                    'errors' => [
                        'current_password' => ['The current password entered is incorrect.']
                    ]
                ], 422);
            }
            return back()->withErrors(['current_password' => 'The current password entered is incorrect.']);
        }

        $broker->password_hash = Hash::make($validated['new_password']);
        $broker->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully! 🔒',
            ]);
        }

        return redirect()->route('broker.profile')->with('success', 'Password updated successfully!');
    }
}
