@extends('broker.layouts.app')

@section('title', 'Broker Profile & Settings')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Profile & Settings</h1>
        <p class="text-sm text-gray-500">Manage your business profile, verification documents and preferences</p>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Profile Card Header -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm flex flex-col md:flex-row items-center md:items-start gap-6">
        <div class="relative">
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-brand to-brand-dark text-white flex items-center justify-center font-bold text-3xl shadow-xl shadow-brand/20">
                VS
            </div>
            <button onclick="alert('Photo upload option')" class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-700 flex items-center justify-center tap-effect shadow-sm hover:bg-gray-50">
                <i class="fas fa-camera text-xs"></i>
            </button>
        </div>
        <div class="flex-1 text-center md:text-left space-y-2">
            <div class="flex flex-col md:flex-row md:items-center gap-2">
                <h2 class="text-2xl font-bold text-gray-900">Vikram Singh</h2>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-100 mx-auto md:mx-0 w-fit">
                    <i class="fas fa-check-circle text-brand"></i> Verified StayNest Partner
                </span>
            </div>
            <p class="text-sm text-gray-500">Owner at <span class="font-semibold text-gray-800">Singh Real Estate & PG Management</span></p>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-xs text-gray-500 pt-2">
                <span><i class="fas fa-envelope text-brand mr-1"></i> vikram@broker.com</span>
                <span><i class="fas fa-phone text-brand mr-1"></i> +91 98765 00000</span>
                <span><i class="fas fa-map-marker-alt text-brand mr-1"></i> Noida / Bangalore</span>
                <span><i class="fas fa-calendar-alt text-brand mr-1"></i> Joined March 2025</span>
            </div>
        </div>
    </div>

    <!-- Settings Tabs / Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Personal & Business Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">Personal & Business Details</h3>
                <form onsubmit="event.preventDefault(); alert('Profile details saved successfully!');" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Full Name</label>
                            <input type="text" value="Vikram Singh" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Agency / Brand Name</label>
                            <input type="text" value="Singh Real Estate & PG Management" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Email Address</label>
                            <input type="email" value="vikram@broker.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Phone Number</label>
                            <input type="tel" value="+91 98765 00000" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Registered Office Address</label>
                        <textarea rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">Tower B, 4th Floor, Sector 62, Noida, UP 201309</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">GSTIN (Optional)</label>
                            <input type="text" value="09AAAAA0000A1Z5" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">RERA / ID Number</label>
                            <input type="text" value="UPRERAAGT12490" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                    </div>
                    <div class="pt-3">
                        <button type="submit" class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-3 rounded-xl tap-effect shadow-md transition">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password & Security -->
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">Security & Password</h3>
                <form onsubmit="event.preventDefault(); alert('Password updated successfully!');" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Current Password</label>
                            <input type="password" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">New Password</label>
                            <input type="password" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                    </div>
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white font-semibold text-sm px-6 py-2.5 rounded-xl tap-effect transition">
                        Change Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Preferences & Quick Info -->
        <div class="space-y-6">
            <!-- Notification Settings -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-lg border-b border-gray-100 pb-3">Notification Preferences</h3>
                <div class="space-y-3 text-sm">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <div class="font-semibold text-gray-800">Booking Alerts (WhatsApp)</div>
                            <div class="text-xs text-gray-500">Instant notification for new tenant bookings</div>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 accent-brand">
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <div class="font-semibold text-gray-800">SMS Notifications</div>
                            <div class="text-xs text-gray-500">Receive OTP and rent payment confirmations</div>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 accent-brand">
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <div class="font-semibold text-gray-800">Monthly E-Statements</div>
                            <div class="text-xs text-gray-500">Monthly earnings and payout summary PDF</div>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 accent-brand">
                    </label>
                </div>
            </div>

            <!-- KYC Documents -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-lg border-b border-gray-100 pb-3">Verification Documents</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-id-card text-brand text-sm"></i>
                            <span class="font-semibold text-gray-800">Aadhar / PAN Card</span>
                        </div>
                        <span class="text-green-700 bg-green-100 px-2 py-0.5 rounded font-bold">VERIFIED</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-contract text-brand text-sm"></i>
                            <span class="font-semibold text-gray-800">Property Ownership Deeds</span>
                        </div>
                        <span class="text-green-700 bg-green-100 px-2 py-0.5 rounded font-bold">VERIFIED</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-university text-brand text-sm"></i>
                            <span class="font-semibold text-gray-800">Bank Account Cheque</span>
                        </div>
                        <span class="text-green-700 bg-green-100 px-2 py-0.5 rounded font-bold">VERIFIED</span>
                    </div>
                </div>
            </div>

            <!-- Need Help -->
            <div class="bg-brand-50 rounded-3xl p-6 border border-brand-100 text-center space-y-3">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-brand text-xl mx-auto shadow-xs">
                    <i class="fas fa-headset"></i>
                </div>
                <h4 class="font-bold text-gray-900">Partner Support Helpdesk</h4>
                <p class="text-xs text-gray-600">Need help with onboarding new properties, payouts or verification?</p>
                <a href="https://wa.me/919876500000" target="_blank" class="inline-block w-full bg-brand text-white font-bold py-2.5 rounded-xl text-xs tap-effect shadow-sm hover:bg-brand-dark transition">
                    <i class="fab fa-whatsapp mr-1"></i> Chat with Relationship Manager
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
