<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingController extends Controller
{
    /**
     * Display platform configuration & admin profile settings.
     */
    public function index(Request $request)
    {
        $settings = PlatformSetting::all()->pluck('value', 'key')->toArray();
        $adminUser = Auth::user() ? Auth::user()->load('profile', 'roles') : null;

        return view('admin.settings', compact('settings', 'adminUser'));
    }

    /**
     * Update global platform & policy configuration.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'platform_name' => ['required', 'string', 'max:100'],
            'support_email' => ['required', 'email', 'max:150'],
            'support_phone' => ['required', 'string', 'max:50'],
            'platform_tagline' => ['nullable', 'string', 'max:190'],
            'platform_description' => ['nullable', 'string', 'max:500'],
            
            'notice_period_days' => ['required', 'integer', 'min:1', 'max:180'],
            'broker_commission_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            
            'auto_approve_bookings' => ['nullable', 'boolean'],
            'mandatory_broker_kyc' => ['nullable', 'boolean'],
            'auto_sms_whatsapp_alerts' => ['nullable', 'boolean'],
            
            'razorpay_key_id' => ['nullable', 'string', 'max:100'],
            'razorpay_key_secret' => ['nullable', 'string', 'max:100'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        // Save General
        PlatformSetting::set('platform_name', $validated['platform_name'], 'general', 'string');
        PlatformSetting::set('support_email', $validated['support_email'], 'general', 'string');
        PlatformSetting::set('support_phone', $validated['support_phone'], 'general', 'string');
        PlatformSetting::set('platform_tagline', $validated['platform_tagline'] ?? '', 'general', 'string');
        PlatformSetting::set('platform_description', $validated['platform_description'] ?? '', 'general', 'string');

        // Save Policies
        PlatformSetting::set('notice_period_days', $validated['notice_period_days'], 'booking', 'number');
        PlatformSetting::set('broker_commission_percentage', $validated['broker_commission_percentage'], 'booking', 'number');
        PlatformSetting::set('auto_approve_bookings', $request->boolean('auto_approve_bookings') ? '1' : '0', 'booking', 'boolean');
        PlatformSetting::set('mandatory_broker_kyc', $request->boolean('mandatory_broker_kyc') ? '1' : '0', 'booking', 'boolean');
        PlatformSetting::set('auto_sms_whatsapp_alerts', $request->boolean('auto_sms_whatsapp_alerts') ? '1' : '0', 'booking', 'boolean');

        // Save Payment
        if (isset($validated['razorpay_key_id'])) {
            PlatformSetting::set('razorpay_key_id', $validated['razorpay_key_id'], 'payment', 'string');
        }
        if (isset($validated['razorpay_key_secret']) && !empty($validated['razorpay_key_secret'])) {
            PlatformSetting::set('razorpay_key_secret', $validated['razorpay_key_secret'], 'payment', 'string');
        }

        // Save Security
        PlatformSetting::set('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0', 'security', 'boolean');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Platform configuration & policy settings saved successfully! 🎉',
            ]);
        }

        return redirect()->route('admin.settings')->with('success', 'Platform settings saved successfully!');
    }

    /**
     * Update current logged in administrator credentials & profile.
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $admin->id],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $admin->id],
            'current_password' => ['nullable', 'string', 'max:30'],
            'new_password' => ['nullable', 'string', 'min:6', 'max:30', 'confirmed'],
        ], [
            'current_password.max' => 'Current password cannot exceed 30 characters.',
            'new_password.min' => 'New password must be at least 6 characters.',
            'new_password.max' => 'New password cannot exceed 30 characters.',
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        // If updating password, verify current password
        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $admin->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password does not match.',
                ], 422);
            }
            $admin->password_hash = Hash::make($validated['new_password']);
        }

        $admin->email = strtolower(trim($validated['email']));
        if (!empty($validated['phone'])) {
            $admin->phone = trim($validated['phone']);
        }
        $admin->save();

        // Update profile
        $fullName = trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? ''));
        UserProfile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'] ?? '',
                'full_name' => $fullName,
                'is_active' => true,
            ]
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Administrator profile updated successfully! 🎉',
                'name' => $fullName,
                'email' => $admin->email,
            ]);
        }

        return redirect()->route('admin.settings')->with('success', 'Administrator profile updated successfully!');
    }
}
