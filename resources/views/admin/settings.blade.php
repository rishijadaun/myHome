@extends('admin.layouts.app')

@section('title', 'Platform Settings')

@push('styles')
<style>
    .toggle-switch:checked ~ .toggle-bg { background-color: #4bb59d; }
    .toggle-switch:checked ~ .toggle-dot { transform: translateX(100%); }
</style>
@endpush

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Platform Configuration & Settings</h1>
        <p class="text-sm text-gray-500">Manage global StayNest rules, commission margins and payment integrations</p>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <form onsubmit="event.preventDefault(); alert('Global platform settings saved successfully!');" class="space-y-6">
        <!-- General Settings -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-cog text-brand"></i> General Platform Settings
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Name</label>
                    <input type="text" value="StayNest" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Support Email</label>
                    <input type="email" value="support@staynest.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Support Helpline Phone</label>
                    <input type="tel" value="+91 98765 43210" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Tagline</label>
                    <input type="text" value="Premium Verified Co-Living & PGs" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Description</label>
                <textarea rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">Making PG and co-living simple, safe, and comfortable with zero brokerage and verified amenities across India.</textarea>
            </div>
        </div>

        <!-- Booking Policies -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-calendar-check text-brand"></i> Booking Policies & Commission
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Default Tenant Notice Period (days)</label>
                    <input type="number" value="30" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Broker Commission (%)</label>
                    <input type="number" value="10" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">Instant Auto-Approve Bookings</div>
                        <div class="text-xs text-gray-500">Automatically confirm paid tenant bookings without manual broker approval</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only toggle-switch">
                        <div class="w-11 h-6 bg-gray-200 rounded-full toggle-bg transition-colors"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform toggle-dot"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">Mandatory Broker KYC Verification</div>
                        <div class="text-xs text-gray-500">Brokers must submit PAN/Aadhar and get admin approval before listing properties</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only toggle-switch">
                        <div class="w-11 h-6 bg-gray-200 rounded-full toggle-bg transition-colors"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform toggle-dot"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">Automated SMS & WhatsApp Alerts</div>
                        <div class="text-xs text-gray-500">Trigger instant WhatsApp notifications to tenants on rent generation</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only toggle-switch">
                        <div class="w-11 h-6 bg-gray-200 rounded-full toggle-bg transition-colors"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform toggle-dot"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Payment Gateway -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-credit-card text-brand"></i> Payment Gateway (Razorpay API)
            </h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Razorpay Key ID</label>
                        <input type="text" value="rzp_live_9381kdf89241" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Razorpay Key Secret</label>
                        <input type="password" value="sec_live_k89214710928341" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 font-mono">
                    </div>
                </div>
                <div class="flex items-center gap-2 p-3.5 bg-green-50 rounded-2xl border border-green-100 text-xs">
                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                    <span class="text-green-800 font-semibold">Payment gateway webhook and production API keys are connected and operational.</span>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <button type="button" onclick="alert('Changes reverted.')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3.5 rounded-xl tap-effect transition text-sm">Cancel</button>
            <button type="submit" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition text-sm flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Save Platform Settings
            </button>
        </div>
    </form>
</div>
@endsection
