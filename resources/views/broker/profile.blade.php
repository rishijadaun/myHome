@extends('broker.layouts.app')

@section('title', 'Broker Profile & Settings - StayNest')

@section('content')
@php
    $brokerName = $profile->full_name ?: ($profile->first_name ? trim($profile->first_name . ' ' . ($profile->last_name ?? '')) : ($broker->email ?? 'Partner Broker'));
    $firstName = $profile->first_name ?: '';
    $lastName = $profile->last_name ?: '';
    
    $initials = strtoupper(substr($firstName ?: ($broker->email ?? 'P'), 0, 1) . substr($lastName ?: '', 0, 1));
    if (empty(trim($initials))) $initials = 'PG';
    
    $companyName = $profile->company_name ?: '';
    $joinedDate = $broker->created_at ? $broker->created_at->format('F Y') : now()->format('F Y');
    
    $officeAddress = $preferences['office_address'] ?? '';
    $operatingCity = $preferences['operating_city'] ?? '';
    $operatingArea = $preferences['operating_area'] ?? '';
    $gstin = $preferences['gstin'] ?? '';
    $reraNumber = $preferences['rera_number'] ?? '';
    $bio = $profile->bio ?? '';
        
    $bank = $bankDetails ?? [];
    $docs = $documents ?? [];
    $notifs = $notifications ?? [];

    $idProof = $docs['id_proof'] ?? null;
    $licenseProof = $docs['license_proof'] ?? null;
    $bankProof = $docs['bank_proof'] ?? null;

    $idProofStatus = $idProof['status'] ?? 'not_uploaded';
    $licenseProofStatus = $licenseProof['status'] ?? 'not_uploaded';
    $bankProofStatus = $bankProof['status'] ?? 'not_uploaded';

    $hasAnyRejected = ($idProofStatus === 'rejected') || ($licenseProofStatus === 'rejected') || ($bankProofStatus === 'rejected') || ($broker->status === 'suspended');
    $hasAnyPending = ($idProofStatus === 'pending_review') || ($licenseProofStatus === 'pending_review') || ($bankProofStatus === 'pending_review');
    $hasAnyUploaded = (!empty($idProof) && !empty($idProof['file_path'])) || (!empty($licenseProof) && !empty($licenseProof['file_path'])) || (!empty($bankProof) && !empty($bankProof['file_path']));
    
    // Broker is verified if admin has approved / kyc_verified_at is present and no documents are rejected
    $isKycVerified = !empty($broker->kyc_verified_at) && !$hasAnyRejected;
@endphp

<!-- Sticky Desktop Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 mb-1">
            <a href="{{ route('broker.dashboard') }}" class="hover:text-brand transition">Dashboard</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-brand">Profile & Settings</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Partner Profile & Settings</h1>
    </div>
    <div class="flex items-center gap-4">
        <a href="https://wa.me/919876543210?text={{ urlencode('Hi StayNest Support, I am partner broker: ' . $brokerName . ' (ID: ' . substr($broker->id, 0, 8) . '). I need assistance.') }}" target="_blank" class="bg-brand-50 hover:bg-brand-100 text-brand-dark px-4 py-2 rounded-xl text-xs font-bold tap-effect flex items-center gap-2 border border-brand-100 transition">
            <i class="fab fa-whatsapp text-sm"></i> Helpdesk Support
        </a>
        <a href="{{ route('broker.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-semibold tap-effect flex items-center gap-1.5 transition">
            <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
        </a>
    </div>
</header>

<!-- Main Container -->
<div class="p-4 md:p-8 space-y-6 max-w-7xl mx-auto">

    <!-- Flash Alert / Toast Notification -->
    <div id="brokerToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="brokerToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="brokerToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="brokerToastMessage">Profile updated successfully!</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-xs animate-fade-in">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl space-y-1 shadow-xs">
            <div class="flex items-center gap-2 font-bold text-sm">
                <i class="fas fa-exclamation-circle text-red-600"></i> Please fix the following errors:
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Hero Card -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <!-- Background Gradient Accent -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-brand-50 to-teal-100 rounded-full blur-3xl opacity-60 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-6 lg:gap-8">
            <!-- Profile Avatar / Upload -->
            <div class="relative group">
                <div id="avatarContainer" class="w-28 h-28 md:w-32 md:h-32 rounded-3xl bg-gradient-to-br from-brand to-brand-dark text-white flex items-center justify-center font-bold text-4xl shadow-xl shadow-brand/20 overflow-hidden border-4 border-white">
                    @if(!empty($profile->avatar_url))
                        <img id="avatarImage" src="{{ $profile->avatar_url }}" alt="{{ $brokerName }}" class="w-full h-full object-cover">
                    @else
                        <span id="avatarInitials">{{ $initials }}</span>
                    @endif
                </div>

                <!-- Camera Upload Button -->
                <button type="button" onclick="document.getElementById('avatarFileInput').click()" class="absolute -bottom-2 -right-2 w-10 h-10 rounded-2xl bg-white border border-gray-200 text-gray-700 flex items-center justify-center tap-effect shadow-md hover:bg-gray-50 hover:text-brand transition cursor-pointer" title="Upload Photo">
                    <i class="fas fa-camera text-sm"></i>
                </button>

                <!-- Hidden Avatar Upload Form -->
                <form id="avatarUploadForm" action="{{ route('broker.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="avatarFileInput" name="avatar" accept="image/*" onchange="handleAvatarSelected(this)">
                </form>
            </div>

            <!-- Profile Info & Badges -->
            <div class="flex-1 text-center md:text-left space-y-3">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
                            <h2 id="heroBrokerName" class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ $brokerName }}</h2>
                            @if($isKycVerified)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200 shadow-xs">
                                    <i class="fas fa-check-circle text-emerald-600"></i> Verified Partner
                                </span>
                            @elseif($hasAnyRejected)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-700 bg-rose-50 px-3 py-1 rounded-full border border-rose-200 shadow-xs">
                                    <i class="fas fa-circle-exclamation text-rose-600"></i> KYC Action Required
                                </span>
                            @elseif($hasAnyPending || $hasAnyUploaded)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200 shadow-xs">
                                    <i class="fas fa-clock text-amber-600"></i> KYC Pending Review
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded-full border border-gray-200 shadow-xs">
                                    <i class="fas fa-file-arrow-up text-gray-500"></i> KYC Incomplete
                                </span>
                            @endif
                        </div>
                        <p id="heroCompanyName" class="text-sm md:text-base text-gray-600 font-medium mt-0.5">
                            Managing Director at <span class="font-bold text-gray-900">{{ $companyName }}</span>
                        </p>
                    </div>

                    @if(!empty($profile->avatar_url))
                        <form id="removeAvatarForm" action="{{ route('broker.profile.avatar.remove') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 hover:underline font-semibold flex items-center gap-1 mx-auto md:mx-0">
                                <i class="fas fa-trash-alt text-[10px]"></i> Remove Photo
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Contact & Meta Chips -->
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-y-2 gap-x-4 text-xs text-gray-500 pt-1">
                    <span class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <i class="fas fa-envelope text-brand"></i> <span id="heroEmail">{{ $broker->email }}</span>
                    </span>
                    <span class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <i class="fas fa-phone text-brand"></i> <span id="heroPhone">{{ $broker->phone }}</span>
                    </span>
                    <span class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <i class="fas fa-map-marker-alt text-brand"></i> <span id="heroCity">{{ $operatingCity }}</span>
                    </span>
                    <span class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                        <i class="fas fa-calendar-alt text-brand"></i> Joined {{ $joinedDate }}
                    </span>
                </div>

                <!-- Quick Stats Pills -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-gray-100 text-center">
                    <div class="bg-gray-50 hover:bg-brand-50/50 p-2.5 rounded-2xl border border-gray-100 transition">
                        <div class="text-lg font-black text-gray-900">{{ $stats['totalProperties'] }}</div>
                        <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Properties</div>
                    </div>
                    <div class="bg-gray-50 hover:bg-brand-50/50 p-2.5 rounded-2xl border border-gray-100 transition">
                        <div class="text-lg font-black text-brand-dark">{{ $stats['activeTenants'] }}</div>
                        <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tenants</div>
                    </div>
                    <div class="bg-gray-50 hover:bg-brand-50/50 p-2.5 rounded-2xl border border-gray-100 transition">
                        <div class="text-lg font-black text-gray-900">{{ $stats['totalBookings'] }}</div>
                        <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Bookings</div>
                    </div>
                    <div class="bg-gray-50 hover:bg-brand-50/50 p-2.5 rounded-2xl border border-gray-100 transition">
                        <div class="text-lg font-black text-amber-500 flex items-center justify-center gap-1">
                            {{ $stats['avgRating'] }} <i class="fas fa-star text-xs"></i>
                        </div>
                        <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">{{ $stats['reviewsCount'] }} Reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-gray-200 no-scrollbar">
        <button onclick="switchTab('tab-general')" id="btn-tab-general" class="tab-btn active px-4 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2 whitespace-nowrap bg-brand text-white shadow-sm">
            <i class="fas fa-user-tie"></i> Business & Personal
        </button>
        <button onclick="switchTab('tab-bank')" id="btn-tab-bank" class="tab-btn px-4 py-2.5 rounded-xl font-bold text-sm text-gray-600 hover:bg-gray-100 transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-university"></i> Bank & Payout
        </button>
        <button onclick="switchTab('tab-kyc')" id="btn-tab-kyc" class="tab-btn px-4 py-2.5 rounded-xl font-bold text-sm text-gray-600 hover:bg-gray-100 transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-id-card"></i> KYC Documents
        </button>
        <button onclick="switchTab('tab-notifications')" id="btn-tab-notifications" class="tab-btn px-4 py-2.5 rounded-xl font-bold text-sm text-gray-600 hover:bg-gray-100 transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-bell"></i> Notifications
        </button>
        <button onclick="switchTab('tab-security')" id="btn-tab-security" class="tab-btn px-4 py-2.5 rounded-xl font-bold text-sm text-gray-600 hover:bg-gray-100 transition flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-lock"></i> Security & Password
        </button>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Tab Content Panels -->
        <div class="lg:col-span-2 space-y-6">

            <!-- TAB 1: Personal & Business Info -->
            <div id="tab-general" class="tab-panel space-y-6">
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Personal & Business Information</h3>
                            <p class="text-xs text-gray-500">Details displayed to potential tenants and partners on StayNest</p>
                        </div>
                        <span class="text-xs font-semibold text-brand bg-brand-50 px-3 py-1 rounded-full border border-brand-100">Live Sync</span>
                    </div>

                    <form id="profileDetailsForm" action="{{ route('broker.profile.update') }}" method="POST" onsubmit="handleProfileSubmit(event)" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}" placeholder="e.g. Rahul" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}" placeholder="e.g. Sharma" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Agency / Company Name</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $companyName) }}" placeholder="e.g. Singh Real Estate & PG Management" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Registered Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $broker->email) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone', $broker->phone) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Operating City / Region</label>
                                <input type="text" name="operating_city" value="{{ old('operating_city', $operatingCity) }}" placeholder="e.g. Noida, Delhi, Bangalore" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Operating Areas / Localities</label>
                            <input type="text" name="operating_area" value="{{ old('operating_area', $operatingArea) }}" placeholder="e.g. Sector 62, Indiranagar, HSR Layout" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Registered Office Address</label>
                            <textarea name="office_address" rows="2" placeholder="e.g. Tower B, 4th Floor, Sector 62, Noida, UP" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">{{ old('office_address', $officeAddress) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">GSTIN Number (Optional)</label>
                                <input type="text" name="gstin" value="{{ old('gstin', $gstin) }}" placeholder="e.g. 09AAAAA0000A1Z5" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">RERA / Real Estate Reg. No.</label>
                                <input type="text" name="rera_number" value="{{ old('rera_number', $reraNumber) }}" placeholder="e.g. UPRERAAGT12490" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">About Agency / Broker Bio</label>
                            <textarea name="bio" rows="3" placeholder="Brief summary about your management services and properties..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">{{ old('bio', $bio) }}</textarea>
                        </div>

                        <div class="pt-4 flex items-center gap-3">
                            <button type="submit" id="saveProfileBtn" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-teal-800 text-white font-bold px-7 py-3 rounded-xl tap-effect shadow-md shadow-brand/20 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> <span>Save Profile Details</span>
                            </button>
                            <span id="profileSaveSpinner" class="hidden text-brand text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-spinner fa-spin"></i> Saving...
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2: Bank & Payout Information -->
            <div id="tab-bank" class="tab-panel hidden space-y-6">
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Bank & Payout Settlement Account</h3>
                            <p class="text-xs text-gray-500">Rent collections and broker commissions are deposited into this account</p>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                            <i class="fas fa-shield-alt"></i> 256-bit Encrypted
                        </span>
                    </div>

                    <form id="bankDetailsForm" action="{{ route('broker.profile.bank') }}" method="POST" onsubmit="handleBankSubmit(event)" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Account Holder Name <span class="text-red-500">*</span></label>
                                <input type="text" name="account_holder_name" value="{{ old('account_holder_name', $bank['account_holder_name'] ?? ($brokerName !== 'Partner Broker' ? $brokerName : '')) }}" placeholder="Enter account holder name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Bank Name <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $bank['bank_name'] ?? '') }}" placeholder="e.g. HDFC Bank, ICICI, SBI" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Account Number <span class="text-red-500">*</span></label>
                                <input type="text" name="account_number" value="{{ old('account_number', $bank['account_number'] ?? '') }}" placeholder="Enter bank account number" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">IFSC Code <span class="text-red-500">*</span></label>
                                <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $bank['ifsc_code'] ?? '') }}" placeholder="e.g. HDFC0001234" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Account Type</label>
                                <select name="account_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                    <option value="current" {{ ($bank['account_type'] ?? '') === 'current' ? 'selected' : '' }}>Current Account (Business)</option>
                                    <option value="savings" {{ ($bank['account_type'] ?? '') === 'savings' ? 'selected' : '' }}>Savings Account (Individual)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Instant Payout UPI ID (Optional)</label>
                                <input type="text" name="upi_id" value="{{ old('upi_id', $bank['upi_id'] ?? '') }}" placeholder="e.g. yourname@okhdfcbank" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="bg-brand-50 p-4 rounded-2xl border border-brand-100 flex items-start gap-3 text-xs text-brand-dark">
                            <i class="fas fa-info-circle text-brand text-base mt-0.5"></i>
                            <div>
                                <strong class="font-bold">Automated Daily Settlements:</strong> Your earnings from tenant rent payments are automatically transferred to this bank account within 24-48 hours of payment receipt.
                            </div>
                        </div>

                        <div class="pt-2 flex items-center gap-3">
                            <button type="submit" id="saveBankBtn" class="bg-brand hover:bg-brand-dark text-white font-bold px-7 py-3 rounded-xl tap-effect shadow-md shadow-brand/20 transition flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> <span>Update Bank Account</span>
                            </button>
                            <span id="bankSaveSpinner" class="hidden text-brand text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-spinner fa-spin"></i> Saving...
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 3: KYC & Verification Documents -->
            <div id="tab-kyc" class="tab-panel hidden space-y-6">
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Partner Verification & KYC Documents</h3>
                            <p class="text-xs text-gray-500">Government compliance and regulatory documents for verified broker badge</p>
                        </div>
                        @if($isKycVerified)
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200 flex items-center gap-1.5 shadow-xs">
                                <i class="fas fa-badge-check"></i> ALL VERIFIED
                            </span>
                        @elseif($hasAnyRejected)
                            <span class="text-xs font-bold text-rose-700 bg-rose-50 px-3 py-1 rounded-full border border-rose-200 flex items-center gap-1.5 shadow-xs">
                                <i class="fas fa-circle-exclamation"></i> ACTION REQUIRED
                            </span>
                        @elseif($hasAnyPending || $hasAnyUploaded)
                            <span class="text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200 flex items-center gap-1.5 shadow-xs">
                                <i class="fas fa-hourglass-half"></i> PENDING VERIFICATION
                            </span>
                        @else
                            <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-full border border-gray-200 flex items-center gap-1.5">
                                <i class="fas fa-file-arrow-up"></i> DOCUMENTS REQUIRED
                            </span>
                        @endif
                    </div>

                    <!-- 3 Document Cards -->
                    @php
                        $docItems = [
                            'id_proof' => [
                                'title' => 'Government ID Proof (Aadhar / PAN)',
                                'desc' => 'National Identity proof for partner verification',
                                'icon' => 'fa-id-card',
                                'doc' => $docs['id_proof'] ?? null,
                                'default_name' => 'aadhar_card.pdf'
                            ],
                            'license_proof' => [
                                'title' => 'RERA License / Property Deed Certificate',
                                'desc' => 'State RERA agent license or PG property deed agreement',
                                'icon' => 'fa-file-contract',
                                'doc' => $docs['license_proof'] ?? null,
                                'default_name' => 'rera_cert.pdf'
                            ],
                            'bank_proof' => [
                                'title' => 'Cancelled Cheque / Bank Passbook',
                                'desc' => 'Proof of bank account ownership for payouts',
                                'icon' => 'fa-money-check-alt',
                                'doc' => $docs['bank_proof'] ?? null,
                                'default_name' => 'cheque.pdf'
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 gap-4">
                        @foreach($docItems as $type => $item)
                            @php
                                $d = $item['doc'];
                                $isUploaded = !empty($d) && !empty($d['file_path']);
                                $dStatus = $d['status'] ?? 'not_uploaded';
                                $allowReupload = !empty($d['allow_reupload']);
                                
                                $cardBorder = 'border-gray-200';
                                $cardBg = 'bg-gray-50';
                                if ($dStatus === 'verified') {
                                    if ($allowReupload) {
                                        $cardBorder = 'border-amber-300';
                                        $cardBg = 'bg-amber-50/20';
                                    } else {
                                        $cardBorder = 'border-emerald-200';
                                        $cardBg = 'bg-emerald-50/20';
                                    }
                                } elseif ($dStatus === 'rejected') {
                                    $cardBorder = 'border-rose-200';
                                    $cardBg = 'bg-rose-50/30';
                                } elseif ($dStatus === 'pending_review' || $isUploaded) {
                                    $cardBorder = 'border-amber-200';
                                    $cardBg = 'bg-amber-50/20';
                                }
                            @endphp

                            <div class="p-5 rounded-2xl border {{ $cardBorder }} {{ $cardBg }} flex flex-col md:flex-row md:items-center justify-between gap-4 transition shadow-xs">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-brand text-xl shadow-xs shrink-0">
                                        <i class="fas {{ $item['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-bold text-gray-900 text-sm">{{ $item['title'] }}</h4>
                                            @if($dStatus === 'verified')
                                                @if($allowReupload)
                                                    <span class="text-[10px] font-extrabold text-amber-800 bg-amber-100 px-2 py-0.5 rounded border border-amber-300 flex items-center gap-1">
                                                        <i class="fas fa-unlock text-[9px]"></i> RE-UPLOAD AUTHORIZED
                                                    </span>
                                                @else
                                                    <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-300 flex items-center gap-1">
                                                        <i class="fas fa-check-circle text-[9px]"></i> VERIFIED & LOCKED
                                                    </span>
                                                @endif
                                            @elseif($dStatus === 'rejected')
                                                <span class="text-[10px] font-extrabold text-rose-700 bg-rose-100 px-2 py-0.5 rounded border border-rose-300">
                                                    REJECTED
                                                </span>
                                            @elseif($isUploaded)
                                                <span class="text-[10px] font-extrabold text-amber-700 bg-amber-100 px-2 py-0.5 rounded border border-amber-300">
                                                    PENDING REVIEW
                                                </span>
                                            @else
                                                <span class="text-[10px] font-extrabold text-gray-500 bg-gray-200 px-2 py-0.5 rounded">
                                                    NOT UPLOADED
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $isUploaded ? 'File: ' . ($d['name'] ?? $item['default_name']) . ' • Uploaded ' . ($d['uploaded_at'] ?? 'recently') : $item['desc'] }}
                                        </p>

                                        @if(!empty($d['doc_number']))
                                            <div class="text-[11px] text-gray-600 mt-1 font-mono">
                                                Document No: <span class="font-bold text-gray-800 bg-white px-2 py-0.5 rounded border border-gray-200">{{ $d['doc_number'] }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($d['rejection_reason']))
                                            <div class="text-xs text-rose-700 font-semibold mt-2 bg-rose-50 p-2.5 rounded-xl border border-rose-200 flex items-start gap-2">
                                                <i class="fas fa-circle-exclamation text-rose-600 text-xs shrink-0 mt-0.5"></i>
                                                <span><strong>Admin Rejection Reason:</strong> {{ $d['rejection_reason'] }}</span>
                                            </div>
                                        @endif

                                        @if($dStatus === 'verified' && $allowReupload)
                                            <div class="text-xs text-amber-900 font-semibold mt-2 bg-amber-50 p-2.5 rounded-xl border border-amber-300 flex items-start gap-2">
                                                <i class="fas fa-unlock-alt text-amber-600 mt-0.5 shrink-0 text-sm"></i>
                                                <div>
                                                    <div><strong>Re-upload Permission Granted:</strong> Admin has unlocked this verified document. You may upload a replaced version below.</div>
                                                    @if(!empty($d['reupload_note']))
                                                        <div class="text-[11px] text-amber-800 mt-0.5"><strong>Admin Note:</strong> {{ $d['reupload_note'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                                    @if($isUploaded)
                                        <a href="{{ $d['file_path'] }}" target="_blank" class="px-3.5 py-2 bg-white border border-gray-200 text-gray-700 hover:text-brand rounded-xl text-xs font-semibold tap-effect flex items-center gap-1.5 transition shadow-xs">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    @endif

                                    @if($dStatus === 'verified')
                                        @if(!$allowReupload)
                                            <span class="px-3.5 py-2 bg-emerald-50 text-emerald-700 font-bold rounded-xl text-xs flex items-center gap-1.5 border border-emerald-200 shadow-xs" title="Document is verified and locked against edits">
                                                <i class="fas fa-lock text-emerald-600"></i> Locked
                                            </span>
                                        @else
                                            <button type="button" onclick="openDocUploadModal('{{ $type }}', '{{ addslashes($item['title']) }}')" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold tap-effect flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                                                <i class="fas fa-upload"></i> Re-upload
                                            </button>
                                        @endif
                                    @elseif($dStatus === 'rejected')
                                        <button type="button" onclick="openDocUploadModal('{{ $type }}', '{{ addslashes($item['title']) }}')" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold tap-effect flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                                            <i class="fas fa-upload"></i> Re-upload
                                        </button>
                                    @elseif($isUploaded)
                                        <button type="button" onclick="openDocUploadModal('{{ $type }}', '{{ addslashes($item['title']) }}')" class="px-4 py-2 bg-brand text-white hover:bg-brand-dark rounded-xl text-xs font-bold tap-effect flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                                            <i class="fas fa-upload"></i> Replace
                                        </button>
                                    @else
                                        <button type="button" onclick="openDocUploadModal('{{ $type }}', '{{ addslashes($item['title']) }}')" class="px-4 py-2 bg-brand text-white hover:bg-brand-dark rounded-xl text-xs font-bold tap-effect flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                                            <i class="fas fa-upload"></i> Upload
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- TAB 4: Notification Preferences -->
            <div id="tab-notifications" class="tab-panel hidden space-y-6">
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Communication & Notification Preferences</h3>
                            <p class="text-xs text-gray-500">Configure how and when StayNest sends you live alerts and updates</p>
                        </div>
                        <span class="text-xs font-semibold text-brand bg-brand-50 px-3 py-1 rounded-full border border-brand-100">Instant Save</span>
                    </div>

                    <form id="notificationForm" action="{{ route('broker.profile.notifications') }}" method="POST" onchange="handleNotificationChange(this)" class="space-y-4">
                        @csrf
                        
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-sm text-gray-900">WhatsApp Instant Booking Alerts</div>
                                <div class="text-xs text-gray-500">Get immediate WhatsApp notifications when a tenant books a bed in your PG</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="whatsapp_alerts" value="1" {{ !empty($notifs['whatsapp_alerts']) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-sm text-gray-900">SMS Verification & Rent Notifications</div>
                                <div class="text-xs text-gray-500">Receive SMS for login OTPs, payout settlements, and tenant check-ins</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="sms_alerts" value="1" {{ !empty($notifs['sms_alerts']) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-sm text-gray-900">Monthly Financial E-Statements (Email PDF)</div>
                                <div class="text-xs text-gray-500">Receive consolidated PDF report with full revenue breakdown on 1st of every month</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_statements" value="1" {{ !empty($notifs['email_statements']) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-sm text-gray-900">Prospective Tenant Inquiries & Visits</div>
                                <div class="text-xs text-gray-500">Alerts when users schedule a physical property visit or send an inquiry</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="inquiry_alerts" value="1" {{ !empty($notifs['inquiry_alerts']) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-sm text-gray-900">Partner Program Growth & Tips</div>
                                <div class="text-xs text-gray-500">Updates on local rental demand, pricing suggestions, and platform upgrades</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="marketing_updates" value="1" {{ !empty($notifs['marketing_updates']) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 5: Security & Password -->
            <div id="tab-security" class="tab-panel hidden space-y-6">
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Account Security & Password</h3>
                            <p class="text-xs text-gray-500">Keep your StayNest partner account secure with a strong password</p>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Encrypted</span>
                    </div>

                    <form id="passwordForm" action="{{ route('broker.profile.password') }}" method="POST" onsubmit="handlePasswordSubmit(event)" class="space-y-4 max-w-xl">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Current Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" id="currentPasswordInput" name="current_password" required maxlength="30" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition pr-10">
                                <button type="button" onclick="togglePasswordVisibility('currentPasswordInput')" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">New Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" id="newPasswordInput" name="new_password" required minlength="6" maxlength="30" placeholder="6 - 30 characters" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition pr-10">
                                    <button type="button" onclick="togglePasswordVisibility('newPasswordInput')" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Confirm New Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" id="confirmPasswordInput" name="new_password_confirmation" required minlength="6" maxlength="30" placeholder="Re-type new password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition pr-10">
                                    <button type="button" onclick="togglePasswordVisibility('confirmPasswordInput')" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex items-center gap-3">
                            <button type="submit" id="savePasswordBtn" class="bg-gray-900 hover:bg-black text-white font-bold px-7 py-3 rounded-xl tap-effect shadow-md transition flex items-center gap-2">
                                <i class="fas fa-key"></i> <span>Update Password</span>
                            </button>
                            <span id="passwordSaveSpinner" class="hidden text-brand text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-spinner fa-spin"></i> Updating...
                            </span>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Right Side: Account Verification & Help Widgets -->
        <div class="space-y-6">

            <!-- Partner Verification Status Card -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-gray-900 text-base">Account Status</h3>
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-envelope-circle-check text-brand text-sm"></i>
                            <span class="font-semibold text-gray-800">Email Verified</span>
                        </div>
                        <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded font-extrabold text-[10px]">ACTIVE</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-phone-volume text-brand text-sm"></i>
                            <span class="font-semibold text-gray-800">Phone Verified</span>
                        </div>
                        <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded font-extrabold text-[10px]">ACTIVE</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-certificate text-brand text-sm"></i>
                            <span class="font-semibold text-gray-800">KYC Verification</span>
                        </div>
                        @if($isKycVerified)
                            <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded font-extrabold text-[10px]">VERIFIED</span>
                        @elseif($hasAnyRejected)
                            <span class="text-rose-700 bg-rose-100 px-2 py-0.5 rounded font-extrabold text-[10px]">ACTION NEEDED</span>
                        @elseif($hasAnyPending || $hasAnyUploaded)
                            <span class="text-amber-700 bg-amber-100 px-2 py-0.5 rounded font-extrabold text-[10px]">PENDING</span>
                        @else
                            <span class="text-gray-500 bg-gray-200 px-2 py-0.5 rounded font-extrabold text-[10px]">INCOMPLETE</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-university text-brand text-sm"></i>
                            <span class="font-semibold text-gray-800">Payout Account</span>
                        </div>
                        <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded font-extrabold text-[10px]">CONNECTED</span>
                    </div>
                </div>
            </div>

            <!-- Dedicated Support & Relationship Manager Card -->
            @php
                $rmName = $assignedRm?->name ?? 'Ananya Sengupta';
                $rmDesignation = $assignedRm?->designation ?? 'Senior Key Account Lead';
                $rmZone = $assignedRm?->zone ?? 'North Zone (Noida & Delhi NCR)';
                $rmPhone = $assignedRm?->phone ?? '+91 98765 43210';
                $rmWhatsapp = $assignedRm?->whatsapp_number ?? '919876543210';
                $rmEmail = $assignedRm?->email ?? 'partners@staynest.com';
                $rmWorkingHours = $assignedRm?->working_hours ?? 'Mon - Sat: 9:00 AM - 7:30 PM';
                $rmAvatar = $assignedRm?->avatar_url ?? null;
            @endphp
            <div class="bg-gradient-to-br from-brand-50 to-teal-50 rounded-3xl p-6 border border-brand-100 text-center space-y-3.5 shadow-xs">
                <div class="relative w-16 h-16 mx-auto">
                    @if($rmAvatar)
                        <img src="{{ $rmAvatar }}" alt="{{ $rmName }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm border-2 border-white">
                    @else
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-brand text-2xl shadow-sm border-2 border-brand-100">
                            <i class="fas fa-headset"></i>
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white" title="Active & Available"></span>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-base">Partner Support Desk</h4>
                    <p class="text-xs text-gray-600 mt-0.5">Direct access to your dedicated Relationship Manager.</p>
                </div>

                <div class="bg-white/90 backdrop-blur-xs p-3.5 rounded-2xl border border-brand-100 text-xs text-left space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-semibold text-[11px] uppercase tracking-wider">Your Assigned Manager</span>
                        <span class="text-emerald-700 bg-emerald-100 text-[10px] font-bold px-2 py-0.5 rounded-full">ASSIGNED</span>
                    </div>
                    <div>
                        <div class="font-extrabold text-gray-900 text-sm flex items-center gap-1.5">
                            {{ $rmName }}
                        </div>
                        <div class="text-[11px] text-gray-500 font-medium">{{ $rmDesignation }} • <span class="text-brand-dark font-semibold">{{ $rmZone }}</span></div>
                    </div>
                    <div class="text-[10px] text-gray-400 border-t border-gray-100 pt-1.5 flex items-center gap-1">
                        <i class="fas fa-clock text-brand text-[9px]"></i> {{ $rmWorkingHours }}
                    </div>
                </div>

                <div class="space-y-2 pt-1">
                    <a href="https://wa.me/{{ $rmWhatsapp }}?text={{ urlencode('Hi ' . $rmName . ', I am partner broker: ' . $brokerName . ' (Broker ID: ' . substr($broker->id, 0, 8) . ', Email: ' . $broker->email . '). I need assistance with my StayNest properties.') }}" target="_blank" class="w-full bg-brand hover:bg-brand-dark text-white font-bold py-3 rounded-xl text-xs tap-effect shadow-md shadow-brand/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fab fa-whatsapp text-sm"></i> Chat on WhatsApp
                    </a>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="tel:{{ $rmPhone }}" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 rounded-xl text-xs border border-gray-200 tap-effect transition flex items-center justify-center gap-1.5">
                            <i class="fas fa-phone-alt text-brand text-xs"></i> Call RM
                        </a>
                        <a href="mailto:{{ $rmEmail }}?subject={{ urlencode('Partner Broker Support - ' . $brokerName) }}" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 rounded-xl text-xs border border-gray-200 tap-effect transition flex items-center justify-center gap-1.5">
                            <i class="fas fa-envelope text-gray-400 text-xs"></i> Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Profile ID Info -->
            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-200 text-xs text-gray-500 space-y-2">
                <div class="flex items-center justify-between font-semibold">
                    <span>Broker Partner UUID</span>
                    <button onclick="copyToClipboard('{{ $broker->id }}', 'Broker UUID copied!')" class="text-brand hover:underline flex items-center gap-1 cursor-pointer">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <div class="font-mono text-[11px] bg-white p-2.5 rounded-xl border border-gray-200 break-all text-gray-800 select-all">
                    {{ $broker->id }}
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Document Upload Modal -->
<div id="docUploadModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-gray-100 space-y-5 animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Upload Document</h3>
                <p id="modalDocTitle" class="text-xs text-gray-500">Select verification document file</p>
            </div>
            <button onclick="closeDocUploadModal()" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center tap-effect">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="docUploadForm" action="{{ route('broker.profile.documents') }}" method="POST" enctype="multipart/form-data" onsubmit="handleDocumentUpload(event)" class="space-y-4">
            @csrf
            <input type="hidden" id="modalDocType" name="doc_type" value="id_proof">

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Document / ID Number (Optional)</label>
                <input type="text" id="modalDocNumber" name="doc_number" placeholder="e.g. Aadhar / PAN / Reg. No." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Select File (PDF, JPG, PNG - Max 10MB) <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-300 hover:border-brand rounded-2xl p-6 text-center cursor-pointer transition bg-gray-50/50" onclick="document.getElementById('modalFileInput').click()">
                    <i class="fas fa-cloud-upload-alt text-brand text-3xl mb-2"></i>
                    <div id="modalFileLabel" class="text-xs font-semibold text-gray-700">Click to browse or drag file here</div>
                    <div class="text-[10px] text-gray-400 mt-1">Supports PDF, JPEG, PNG, WEBP</div>
                </div>
                <input type="file" id="modalFileInput" name="document" accept=".pdf,image/*" required class="hidden" onchange="handleModalFileSelected(this)">
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
                <button type="button" onclick="closeDocUploadModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs tap-effect transition">
                    Cancel
                </button>
                <button type="submit" id="modalUploadBtn" class="px-6 py-2.5 bg-brand hover:bg-brand-dark text-white font-bold rounded-xl text-xs tap-effect shadow-md transition flex items-center gap-2">
                    <i class="fas fa-upload"></i> <span id="modalUploadBtnText">Upload Document</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Tab Switching
    function switchTab(tabId) {
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-brand', 'text-white', 'shadow-sm');
            btn.classList.add('text-gray-600');
        });

        const activePanel = document.getElementById(tabId);
        if (activePanel) activePanel.classList.remove('hidden');

        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('active', 'bg-brand', 'text-white', 'shadow-sm');
            activeBtn.classList.remove('text-gray-600');
        }
    }

    // Toast Notification System
    function showToast(message, type = 'success') {
        const toast = document.getElementById('brokerToastNotification');
        const msgEl = document.getElementById('brokerToastMessage');
        const iconEl = document.getElementById('brokerToastIcon');

        msgEl.textContent = message;
        if (type === 'error') {
            iconEl.innerHTML = '<i class="fas fa-exclamation-circle text-red-400"></i>';
        } else {
            iconEl.innerHTML = '<i class="fas fa-check-circle text-emerald-400"></i>';
        }

        toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
        }, 4000);
    }

    // Copy to clipboard helper
    function copyToClipboard(text, successMsg) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(successMsg);
        });
    }

    // Password Visibility Toggle
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    // Handle Avatar Selection & Upload
    function handleAvatarSelected(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', '{{ csrf_token() }}');

        showToast('Uploading profile photo...', 'info');

        fetch('{{ route("broker.profile.avatar") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('avatarContainer');
                container.innerHTML = `<img id="avatarImage" src="${data.avatar_url}" class="w-full h-full object-cover">`;
                showToast(data.message);
            } else {
                showToast(data.message || 'Avatar upload failed', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Failed to upload photo. Please check file format.', 'error');
        });
    }

    // Handle Profile Details AJAX Submit
    function handleProfileSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('profileDetailsForm');
        const btn = document.getElementById('saveProfileBtn');
        const spinner = document.getElementById('profileSaveSpinner');

        btn.disabled = true;
        spinner.classList.remove('hidden');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            spinner.classList.add('hidden');

            if (data.success) {
                showToast(data.message);
                if (data.data) {
                    document.getElementById('heroBrokerName').textContent = data.data.name;
                    if (data.data.company_name) {
                        document.getElementById('heroCompanyName').innerHTML = `Managing Director at <span class="font-bold text-gray-900">${data.data.company_name}</span>`;
                    }
                    document.getElementById('heroEmail').textContent = data.data.email;
                    document.getElementById('heroPhone').textContent = data.data.phone;
                    if (data.data.operating_city) {
                        document.getElementById('heroCity').textContent = data.data.operating_city;
                    }
                }
            } else {
                showToast(data.message || 'Failed to update profile', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            spinner.classList.add('hidden');
            console.error(err);
            showToast('An error occurred while updating profile.', 'error');
        });
    }

    // Handle Bank Details AJAX Submit
    function handleBankSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('bankDetailsForm');
        const btn = document.getElementById('saveBankBtn');
        const spinner = document.getElementById('bankSaveSpinner');

        btn.disabled = true;
        spinner.classList.remove('hidden');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            spinner.classList.add('hidden');

            if (data.success) {
                showToast(data.message);
            } else {
                showToast(data.message || 'Failed to update bank details', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            spinner.classList.add('hidden');
            console.error(err);
            showToast('Error updating bank information.', 'error');
        });
    }

    // Handle Instant Notification Preferences Toggle
    function handleNotificationChange(form) {
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
            }
        })
        .catch(err => console.error(err));
    }

    // Handle Password Submit
    function handlePasswordSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('passwordForm');
        const btn = document.getElementById('savePasswordBtn');
        const spinner = document.getElementById('passwordSaveSpinner');

        const newPass = document.getElementById('newPasswordInput').value;
        const confirmPass = document.getElementById('confirmPasswordInput').value;

        if (newPass !== confirmPass) {
            showToast('New password confirmation does not match.', 'error');
            return;
        }

        btn.disabled = true;
        spinner.classList.remove('hidden');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            spinner.classList.add('hidden');

            if (data.success) {
                showToast(data.message);
                form.reset();
            } else {
                showToast(data.message || 'Password update failed.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            spinner.classList.add('hidden');
            console.error(err);
            showToast('Current password incorrect or validation error.', 'error');
        });
    }

    // Document Upload Modal Controls
    function openDocUploadModal(docType, docTitle) {
        document.getElementById('modalDocType').value = docType;
        document.getElementById('modalDocTitle').textContent = docTitle;
        document.getElementById('modalFileLabel').textContent = 'Click to browse or drag file here';
        document.getElementById('modalFileInput').value = '';
        document.getElementById('docUploadModal').classList.remove('hidden');
    }

    function closeDocUploadModal() {
        document.getElementById('docUploadModal').classList.add('hidden');
    }

    function handleModalFileSelected(input) {
        if (input.files && input.files[0]) {
            document.getElementById('modalFileLabel').innerHTML = `<span class="text-brand font-bold"><i class="fas fa-file-check mr-1"></i> ${input.files[0].name}</span>`;
        }
    }

    function handleDocumentUpload(e) {
        e.preventDefault();
        const form = document.getElementById('docUploadForm');
        const btn = document.getElementById('modalUploadBtn');
        const btnText = document.getElementById('modalUploadBtnText');

        btn.disabled = true;
        btnText.textContent = 'Uploading...';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btnText.textContent = 'Upload Document';
            closeDocUploadModal();

            if (data.success) {
                showToast(data.message);
                setTimeout(() => {
                    window.location.href = window.location.pathname + '?tab=kyc';
                }, 1000);
            } else {
                showToast(data.message || 'Document upload failed.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.textContent = 'Upload Document';
            console.error(err);
            showToast('Upload error. Please try a valid file.', 'error');
        });
    }

    // Auto Activate Tab from URL Query Param or Hash
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        const hash = window.location.hash.replace('#', '');
        if (tabParam) {
            const target = tabParam.startsWith('tab-') ? tabParam : 'tab-' + tabParam;
            if (document.getElementById(target)) {
                switchTab(target);
            }
        } else if (hash && document.getElementById(hash)) {
            switchTab(hash);
        }
    });
</script>
@endpush
@endsection
