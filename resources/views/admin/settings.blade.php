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
        <p class="text-sm text-gray-500">Manage global SpaceSeeks platform rules, commission margins, payment gateways and admin profile</p>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Flash Alert / Toast Anchor -->
    <div id="settingsToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="settingsToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="settingsToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="settingsToastMessage">Settings saved successfully</span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-gray-200 gap-4 overflow-x-auto no-scrollbar">
        <button type="button" onclick="switchSettingsTab('general')" id="tabBtn-general" class="settings-tab-btn pb-3 px-2 text-sm font-bold text-brand border-b-2 border-brand transition flex items-center gap-2 cursor-pointer">
            <i class="fas fa-sliders-h"></i> Platform & Policies
        </button>
        <button type="button" onclick="switchSettingsTab('payment')" id="tabBtn-payment" class="settings-tab-btn pb-3 px-2 text-sm font-medium text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition flex items-center gap-2 cursor-pointer">
            <i class="fas fa-credit-card"></i> Payment Gateway
        </button>
        <button type="button" onclick="switchSettingsTab('profile')" id="tabBtn-profile" class="settings-tab-btn pb-3 px-2 text-sm font-medium text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition flex items-center gap-2 cursor-pointer">
            <i class="fas fa-user-shield"></i> Admin Profile & Security
        </button>
    </div>

    <!-- 1. Platform & Policies Form -->
    <form id="generalSettingsForm" onsubmit="handleSaveGeneralSettings(event)" class="settings-pane space-y-6">
        <!-- General Information -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-building text-brand"></i> General Platform Settings
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Brand name, contact details and public metadata</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Name *</label>
                    <input type="text" name="platform_name" value="{{ $settings['platform_name'] ?? 'SpaceSeeks' }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Support Email *</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'support@spaceseeks.com' }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Support Helpline Phone *</label>
                    <input type="tel" name="support_phone" value="{{ $settings['support_phone'] ?? '+91 98765 43210' }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Tagline</label>
                    <input type="text" name="platform_tagline" value="{{ $settings['platform_tagline'] ?? 'Premium Verified Co-Living & PGs across India' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Description</label>
                <textarea name="platform_description" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">{{ $settings['platform_description'] ?? 'Making PG and co-living simple, safe, and comfortable with zero brokerage and verified amenities across India.' }}</textarea>
            </div>
        </div>

        <!-- Booking Policies & Commission -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-brand"></i> Booking Policies & Commission Margins
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Rules for tenant booking lifecycle and broker payouts</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Default Tenant Notice Period (Days) *</label>
                    <input type="number" name="notice_period_days" value="{{ $settings['notice_period_days'] ?? 30 }}" min="1" max="180" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Platform Broker Commission (%) *</label>
                    <input type="number" name="broker_commission_percentage" value="{{ $settings['broker_commission_percentage'] ?? 10 }}" min="0" max="100" step="0.5" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <!-- Toggles -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">Instant Auto-Approve Bookings</div>
                        <div class="text-xs text-gray-500">Automatically confirm paid tenant reservations without requiring manual broker approval</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_approve_bookings" value="1" {{ (!isset($settings['auto_approve_bookings']) || !empty($settings['auto_approve_bookings'])) ? 'checked' : '' }} class="sr-only toggle-switch">
                        <div class="w-11 h-6 bg-gray-200 rounded-full toggle-bg transition-colors"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform toggle-dot"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">Mandatory Broker KYC Verification</div>
                        <div class="text-xs text-gray-500">Brokers must have verified ID & bank details before publishing new property stays</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="mandatory_broker_kyc" value="1" {{ (!isset($settings['mandatory_broker_kyc']) || !empty($settings['mandatory_broker_kyc'])) ? 'checked' : '' }} class="sr-only toggle-switch">
                        <div class="w-11 h-6 bg-gray-200 rounded-full toggle-bg transition-colors"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform toggle-dot"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">Automated SMS & WhatsApp Alerts</div>
                        <div class="text-xs text-gray-500">Trigger instant notifications to tenants on invoice generation, booking confirmation & rent reminders</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_sms_whatsapp_alerts" value="1" {{ (!isset($settings['auto_sms_whatsapp_alerts']) || !empty($settings['auto_sms_whatsapp_alerts'])) ? 'checked' : '' }} class="sr-only toggle-switch">
                        <div class="w-11 h-6 bg-gray-200 rounded-full toggle-bg transition-colors"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform toggle-dot"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">Platform Maintenance Mode</div>
                        <div class="text-xs text-gray-500">Temporarily show maintenance banner to non-admin visitors</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ (!empty($settings['maintenance_mode'])) ? 'checked' : '' }} class="sr-only toggle-switch">
                        <div class="w-11 h-6 bg-gray-200 rounded-full toggle-bg transition-colors"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform toggle-dot"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex justify-end">
            <button type="submit" id="saveGeneralBtn" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition text-sm flex items-center justify-center gap-2 cursor-pointer">
                <i class="fas fa-save"></i> Save Platform Settings
            </button>
        </div>
    </form>

    <!-- 2. Payment Gateway Form -->
    <form id="paymentSettingsForm" onsubmit="handleSavePaymentSettings(event)" class="settings-pane space-y-6 hidden">
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-credit-card text-brand"></i> Razorpay Payment Gateway API
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Configure live production and sandbox payment credentials</p>
                </div>
                <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
                    <i class="fas fa-check-circle"></i> Connected
                </span>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Razorpay Key ID *</label>
                        <input type="text" name="razorpay_key_id" value="{{ $settings['razorpay_key_id'] ?? 'rzp_live_9381kdf89241' }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Razorpay Key Secret *</label>
                        <input type="password" name="razorpay_key_secret" value="{{ $settings['razorpay_key_secret'] ?? 'sec_live_k89214710928341' }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 font-mono">
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4 bg-emerald-50/60 rounded-2xl border border-emerald-100 text-xs">
                    <i class="fas fa-shield-alt text-emerald-600 text-base shrink-0"></i>
                    <span class="text-emerald-900 font-medium">Payment gateway webhooks and UPI / NetBanking routes are encrypted and active in this workspace.</span>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" id="savePaymentBtn" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition text-sm flex items-center justify-center gap-2 cursor-pointer">
                <i class="fas fa-save"></i> Save Payment Keys
            </button>
        </div>
    </form>

    <!-- 3. Admin Profile & Security Form -->
    <form id="profileSettingsForm" onsubmit="handleSaveProfileSettings(event)" class="settings-pane space-y-6 hidden">
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-user-shield text-brand"></i> Administrator Profile & Credentials
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Manage your master administrator account information and login password</p>
                </div>
                <span class="bg-brand-light text-brand text-xs font-bold px-3 py-1 rounded-full uppercase">
                    {{ $adminUser?->roles?->first()?->name ?? 'Super Admin' }}
                </span>
            </div>

            <!-- Profile Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ $adminUser?->profile?->first_name ?? 'SpaceSeeks' }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Last Name</label>
                    <input type="text" name="last_name" value="{{ $adminUser?->profile?->last_name ?? 'Admin' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Admin Email Address *</label>
                    <input type="email" name="email" value="{{ $adminUser?->email ?? 'admin@gmail.com' }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Admin Phone Number</label>
                    <input type="text" name="phone" value="{{ $adminUser?->phone ?? '+91 98765 43210' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 font-mono">
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="pt-6 border-t border-gray-100 space-y-4">
                <h3 class="text-sm font-bold text-gray-900">Change Admin Password (Optional)</h3>
                <p class="text-xs text-gray-500">Leave blank if you do not wish to update your administrator password.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Current Password</label>
                        <input type="password" name="current_password" maxlength="30" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">New Password</label>
                        <input type="password" name="new_password" minlength="6" maxlength="30" placeholder="6 - 30 characters" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" minlength="6" maxlength="30" placeholder="Repeat new password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" id="saveProfileBtn" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition text-sm flex items-center justify-center gap-2 cursor-pointer">
                <i class="fas fa-user-check"></i> Update Admin Profile
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Switch Tabs
    function switchSettingsTab(tabName) {
        document.querySelectorAll('.settings-tab-btn').forEach(b => {
            b.classList.remove('text-brand', 'border-brand', 'font-bold');
            b.classList.add('text-gray-500', 'border-transparent', 'font-medium');
        });
        const activeBtn = document.getElementById(`tabBtn-${tabName}`);
        if (activeBtn) {
            activeBtn.classList.remove('text-gray-500', 'border-transparent', 'font-medium');
            activeBtn.classList.add('text-brand', 'border-brand', 'font-bold');
        }

        document.querySelectorAll('.settings-pane').forEach(p => p.classList.add('hidden'));
        if (tabName === 'general') {
            document.getElementById('generalSettingsForm').classList.remove('hidden');
        } else if (tabName === 'payment') {
            document.getElementById('paymentSettingsForm').classList.remove('hidden');
        } else if (tabName === 'profile') {
            document.getElementById('profileSettingsForm').classList.remove('hidden');
        }
    }

    // Dynamic Toast Messenger
    function showSettingsToast(message, type = 'success') {
        const toast = document.getElementById('settingsToastNotification');
        const text = document.getElementById('settingsToastMessage');
        const icon = document.getElementById('settingsToastIcon');

        text.textContent = message;
        if (type === 'success') {
            icon.innerHTML = '<i class="fas fa-check-circle text-emerald-400 text-base"></i>';
        } else {
            icon.innerHTML = '<i class="fas fa-exclamation-circle text-red-400 text-base"></i>';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3200);
    }

    // Save General Settings
    async function handleSaveGeneralSettings(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('saveGeneralBtn');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1"></i> Saving...';

        try {
            const res = await fetch('{{ route('admin.settings.update') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await res.json();

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Save Platform Settings';

            if (res.ok && data.success) {
                showSettingsToast(data.message || 'Settings saved successfully!', 'success');
            } else {
                showSettingsToast(data.message || 'Failed to save settings', 'error');
            }
        } catch (err) {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Save Platform Settings';
            showSettingsToast('Connection error. Please try again.', 'error');
        }
    }

    // Save Payment Settings
    async function handleSavePaymentSettings(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('savePaymentBtn');
        const formData = new FormData(form);

        // Append general settings fields so validation passes
        const genForm = document.getElementById('generalSettingsForm');
        new FormData(genForm).forEach((val, key) => {
            if (!formData.has(key)) {
                formData.append(key, val);
            }
        });

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1"></i> Saving...';

        try {
            const res = await fetch('{{ route('admin.settings.update') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await res.json();

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Save Payment Keys';

            if (res.ok && data.success) {
                showSettingsToast('Payment gateway keys updated successfully!', 'success');
            } else {
                showSettingsToast(data.message || 'Failed to save payment keys', 'error');
            }
        } catch (err) {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1"></i> Save Payment Keys';
            showSettingsToast('Connection error. Please try again.', 'error');
        }
    }

    // Save Profile & Password
    async function handleSaveProfileSettings(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('saveProfileBtn');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1"></i> Updating...';

        try {
            const res = await fetch('{{ route('admin.settings.profile') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await res.json();

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-check mr-1"></i> Update Admin Profile';

            if (res.ok && data.success) {
                showSettingsToast(data.message || 'Profile updated successfully!', 'success');
                // Clear password inputs
                form.querySelectorAll('input[type="password"]').forEach(i => i.value = '');
            } else {
                showSettingsToast(data.message || 'Failed to update profile', 'error');
            }
        } catch (err) {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-check mr-1"></i> Update Admin Profile';
            showSettingsToast('Connection error. Please try again.', 'error');
        }
    }
</script>
@endpush
