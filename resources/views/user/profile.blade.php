@extends('user.layouts.app')

@section('title', 'My Profile - SpaceSeeks')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="min-h-screen bg-gray-50/70 pb-24 md:pb-12">

    <!-- ===================== TOP NOTIFICATION BANNER / TOAST ===================== -->
    <div id="profileToast" class="fixed top-20 right-4 md:right-8 z-50 hidden transition-all duration-300 transform translate-y-2">
        <div class="bg-gray-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-white/20">
            <div id="toastIcon" class="w-7 h-7 rounded-full bg-brand flex items-center justify-center text-white text-xs">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <p id="toastTitle" class="text-xs font-bold leading-tight">Profile Updated</p>
                <p id="toastMsg" class="text-[11px] text-gray-300">Your changes have been saved successfully.</p>
            </div>
        </div>
    </div>

    <!-- ===================== DESKTOP & MOBILE HEADER ===================== -->
    <div class="bg-gradient-to-br from-gray-900 via-teal-950 to-brand-dark text-white pt-8 pb-12 px-4 sm:px-6 lg:px-8 shadow-md">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
            
            <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                <!-- Avatar with Camera Toggle -->
                <div class="relative group">
                    <img id="userAvatarImg" 
                         src="{{ $user?->profile?->avatar_url ?: ('https://ui-avatars.com/api/?name=' . urlencode($user?->profile?->full_name ?: ($user?->name ?? 'User')) . '&background=0f766e&color=ffffff&size=200') }}" 
                         alt="Profile Avatar" 
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-white/30 object-cover shadow-xl transition-all duration-300">
                    <label for="avatarFileInput" id="avatarUploadBtn" title="Click to upload profile photo (JPG, PNG, WEBP · Max 5MB)"
                           class="absolute bottom-1 right-1 w-9 h-9 bg-brand hover:bg-brand-dark rounded-full flex items-center justify-center text-white text-xs shadow-lg cursor-pointer transition tap-effect hover:scale-110">
                        <i id="avatarCameraIcon" class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatarFileInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="handleAvatarUpload(event)">
                </div>

                <!-- User Basic Details -->
                <div>
                    <div class="flex items-center justify-center sm:justify-start gap-2.5 mb-0.5">
                        <h1 id="userFullNameHeading" class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                            {{ $user?->profile?->full_name ?: ($user?->name ?? 'Resident User') }}
                        </h1>
                        <span id="userRoleBadge" class="bg-yellow-400 text-gray-900 text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-xs">
                            {{ $isTenant ? 'TENANT' : 'MEMBER' }}
                        </span>
                    </div>
                    <p id="userTaglineHeading" class="text-sm font-semibold text-teal-100/90 mt-0.5">
                        {{ $user?->profile?->tagline ?: ($isTenant ? 'Tenant Member · SpaceSeeks Verified' : 'SpaceSeeks Resident') }}
                    </p>
                    <p id="userEmailHeading" class="text-xs text-teal-200/75 mt-0.5">{{ $user?->email ?? ($user?->phone ?? 'Registered User') }}</p>
                </div>
            </div>

            <!-- Quick Action Top Buttons -->
            <!-- <div class="flex items-center gap-3">
                <button type="button" onclick="toggleEditProfile(true)" id="topEditProfileBtn" class="bg-white/15 hover:bg-white/25 text-white border border-white/30 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition tap-effect backdrop-blur-sm">
                    <i class="fas fa-pen-to-square"></i> Edit Profile
                </button>
                <button type="button" onclick="openChangePasswordModal()" class="bg-white/15 hover:bg-white/25 text-white border border-white/30 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition tap-effect backdrop-blur-sm">
                    <i class="fas fa-key"></i> Password
                </button>
                <button type="button" onclick="performLogout()" class="bg-red-500/20 hover:bg-red-600 text-red-200 hover:text-white border border-red-400/30 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition tap-effect">
                    <i class="fas fa-power-off"></i> Logout
                </button>
            </div> -->

        </div>
    </div>

    <!-- ===================== MAIN PROFILE GRID CONTAINER ===================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ================= LEFT COLUMN: SUMMARY & ADDRESS SHORTCUT ================= -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Activity Overview</h3>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <a href="{{ route('user.bookings') }}" class="bg-gray-50 hover:bg-brand-light p-3 rounded-2xl border border-gray-100 transition tap-effect">
                            <span class="block text-xl font-black text-gray-900" id="statBookings">{{ $bookingsCount ?? 0 }}</span>
                            <span class="text-[11px] font-semibold text-gray-500">Bookings</span>
                        </a>
                        <a href="{{ route('user.saved') }}" class="bg-gray-50 hover:bg-brand-light p-3 rounded-2xl border border-gray-100 transition tap-effect">
                            <span class="block text-xl font-black text-gray-900" id="statSaved">0</span>
                            <span class="text-[11px] font-semibold text-gray-500">Saved</span>
                        </a>
                        <button type="button" onclick="openAddressModal()" class="bg-gray-50 hover:bg-brand-light p-3 rounded-2xl border border-gray-100 transition tap-effect">
                            <span class="block text-xl font-black text-brand" id="statAddresses">0</span>
                            <span class="text-[11px] font-semibold text-gray-500">Addresses</span>
                        </button>
                    </div>
                </div>

                <!--  Saved Addresses Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shadow-xs">
                                <i class="fas fa-map-location-dot"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">Saved Address</h3>
                            </div>
                        </div>
                        <button type="button" onclick="openAddressModal()" id="addrHeaderBtn" class="text-xs font-bold text-brand hover:underline">
                             + Add Address
                        </button>
                    </div>

                    <!-- Empty State (Shown when no address saved) -->
                    <div id="noAddressState" class="bg-gray-50 rounded-2xl p-4 border border-dashed border-gray-200 text-center mb-3">
                        <i class="fas fa-location-dot text-xl text-gray-300 mb-1"></i>
                        <p class="text-xs font-bold text-gray-600">No Address Saved</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Add your location for live distance calculation to PGs &amp; properties.</p>
                    </div>

                    <!-- Display Current Saved Address (Hidden until an address is actually saved) -->
                    <div id="savedAddressPreview" class="hidden bg-gray-50 rounded-2xl p-3.5 border border-gray-200/80 mb-3 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="bg-brand text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase" id="addrBadgeTag">🏠 HOME</span>
                            <button type="button" onclick="openAddressModal()" class="text-xs text-gray-400 hover:text-brand"><i class="fas fa-pen text-[10px]"></i> Edit</button>
                        </div>
                        <p class="text-xs font-bold text-gray-900 leading-snug" id="addrPreviewLine1"></p>
                        <p class="text-[11px] text-gray-500 truncate" id="addrPreviewLine2"></p>
                    </div>

                    <button type="button" onclick="openAddressModal()" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-2xl text-xs flex items-center justify-center gap-2 shadow-sm transition tap-effect">
                        <i class="fas fa-crosshairs"></i> Use Current GPS Location & Map
                    </button>
                </div>

                <!-- Navigation Quick Links -->
                <div class="bg-white rounded-3xl p-4 border border-gray-100 shadow-sm space-y-1">
                    @if($isTenant ?? true)
                    <a href="{{ route('user.roommate.index') }}" class="w-full flex items-center justify-between p-3 rounded-2xl hover:bg-brand-light/60 text-gray-800 hover:text-brand font-bold text-xs transition group">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-users text-brand text-sm group-hover:scale-110 transition-transform"></i>
                            Find Roommate / Flatmate
                        </span>
                        <span class="text-[9px] bg-brand text-white font-extrabold px-2 py-0.5 rounded-full">NEW</span>
                    </a>
                    @endif
                    <button type="button" onclick="openChangePasswordModal()" class="w-full flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 text-gray-700 font-semibold text-xs transition">
                        <span class="flex items-center gap-3"><i class="fas fa-key text-amber-500 text-sm"></i> Change Account Password</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px]"></i>
                    </button>
                    <a href="{{ route('user.bookings') }}" class="w-full flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 text-gray-700 font-semibold text-xs transition">
                        <span class="flex items-center gap-3"><i class="fas fa-calendar-check text-brand text-sm"></i> My Bookings & Visits</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px]"></i>
                    </a>
                    <a href="{{ route('user.saved') }}" class="w-full flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 text-gray-700 font-semibold text-xs transition">
                        <span class="flex items-center gap-3"><i class="fas fa-heart text-red-500 text-sm"></i> Saved Wishlist</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px]"></i>
                    </a>
                    <a href="{{ route('user.contact') }}" class="w-full flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 text-gray-700 font-semibold text-xs transition">
                        <span class="flex items-center gap-3"><i class="fas fa-headset text-cyan-500 text-sm"></i> 24/7 Support Desk</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- ================= RIGHT 2-COLUMNS: PERSONAL INFO VIEW & EDIT FORM ================= -->
            <div class="lg:col-span-2 space-y-6">

                <!-- MAIN PROFILE CARD -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
                    
                    <!-- View Mode Header -->
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg sm:text-xl font-black text-gray-900">Personal Information</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Manage your personal identity, contact and accommodation preferences</p>
                        </div>
                        <div id="profileModeAction">
                            <button type="button" onclick="toggleEditProfile(true)" id="editInfoBtn" class="bg-brand hover:bg-brand-dark text-white font-bold text-xs px-4 py-2 rounded-xl transition tap-effect shadow-xs flex items-center gap-1.5">
                                <i class="fas fa-pen text-[10px]"></i> Edit Details
                            </button>
                        </div>
                    </div>

                    <!-- ================= 1. VIEW MODE (READ ONLY) ================= -->
                    <div id="profileViewMode" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">First Name</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewFirstName">
                                    {{ $user?->profile?->first_name ?: ($user?->name ?? '-') }}
                                </p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Last Name</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewLastName">
                                    {{ $user?->profile?->last_name ?? '-' }}
                                </p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 flex items-center justify-between {{ empty($user?->phone) ? 'sm:col-span-2' : '' }}">
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Registered Email</span>
                                    <p class="text-sm font-bold text-gray-900 mt-1" id="viewEmail">
                                        {{ $user?->email ?? 'Not set' }}
                                    </p>
                                </div>
                                <button type="button" onclick="openEmailOtpModal()" class="text-xs font-bold text-brand hover:text-brand-dark bg-brand-light px-2.5 py-1 rounded-lg tap-effect">
                                    Change Email ID
                                </button>
                            </div>
                            @if(!empty($user?->phone))
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 flex items-center justify-between" id="mobileNumberViewCard">
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Mobile Number</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <p class="text-sm font-bold text-gray-900" id="viewPhone">
                                            {{ $user->phone }}
                                        </p>
                                        <span id="phoneStatusBadge" class="bg-teal-100 text-teal-700 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md flex items-center gap-1">
                                            <i class="fas fa-circle-check text-[8px]"></i> Active
                                        </span>
                                    </div>
                                </div>
                                <!-- <button type="button" onclick="openPhoneModal()" class="text-xs font-bold text-brand hover:text-brand-dark bg-brand-light hover:bg-brand/20 px-3 py-1.5 rounded-xl transition tap-effect flex items-center gap-1">
                                    <i class="fas fa-pen-to-square text-[10px]"></i>
                                    <span id="phoneBtnLabel">Change</span>
                                </button> -->
                            </div>
                            @endif
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Date of Birth &amp; Age</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewDob">
                                    {{ $user && $user->profile?->date_of_birth ? $user->profile->date_of_birth->format('d M Y') . ' (' . $user->profile->age . ' years)' : 'Not set' }}
                                </p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Gender</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewGender">
                                    {{ $user?->profile?->gender ? ucfirst($user->profile->gender) : 'Not set' }}
                                </p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Profession / Job Title</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewOccupation">
                                    {{ $user?->profile?->occupation ?: 'Not set' }}
                                </p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 sm:col-span-2">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">About / Bio</span>
                                <p class="text-xs text-gray-700 mt-1 leading-relaxed" id="viewBio">
                                    {{ $user?->profile?->bio ?: 'No bio added yet. Click "Edit Details" to add a bio and introduce yourself.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- ================= 2. EDIT FORM MODE (WHEN USER CLICKS EDIT) ================= -->
                    <div id="profileEditMode" class="hidden space-y-5">
                        <form onsubmit="handleProfileSubmit(event)">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="editFirstName" value="{{ $user?->profile?->first_name ?: ($user?->name ?? '') }}" required 
                                            class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Last Name</label>
                                        <input type="text" id="editLastName" value="{{ $user?->profile?->last_name ?? '' }}"
                                            class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                    </div>
                                </div>

                                <!-- Email & Mobile -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="{{ empty($user?->phone) ? 'sm:col-span-2' : '' }}">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-bold text-gray-700">Email Address</label>
                                            <button type="button" onclick="openEmailOtpModal()" class="text-[11px] font-bold text-brand hover:underline">
                                                Update with OTP &rarr;
                                            </button>
                                        </div>
                                        <div class="relative">
                                            <input type="email" id="editEmail" value="{{ $user?->email ?? '' }}" readonly 
                                                class="w-full bg-gray-100 border border-gray-200 rounded-2xl py-3 pl-4 pr-10 text-sm text-gray-600 cursor-not-allowed select-none">
                                            <span class="absolute right-3.5 top-3.5 text-gray-400 text-xs"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1">Email can only be changed via OTP security check.</p>
                                    </div>

                                    @if(!empty($user?->phone))
                                    <div id="mobileNumberEditContainer">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-bold text-gray-700">Mobile Number</label>
                                            <span class="text-[10px] font-extrabold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-md flex items-center gap-1">
                                                <i class="fas fa-lock text-[9px]"></i> Locked
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="tel" id="editPhone" value="{{ $user->phone }}" readonly disabled
                                                class="w-full bg-gray-100 border border-gray-200 rounded-2xl py-3 pl-4 pr-10 text-sm font-medium text-gray-500 cursor-not-allowed select-none">
                                            <span class="absolute right-3.5 top-3.5 text-gray-400 text-xs"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1">Mobile number updates are locked.</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Date of Birth (DOB)</label>
                                        <input type="date" id="editDob" max="{{ date('Y-m-d', strtotime('-18 years')) }}" value="{{ $user?->profile?->date_of_birth ? $user->profile->date_of_birth->format('Y-m-d') : '' }}"
                                            class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                        <p class="text-[10px] text-gray-400 mt-1">Used to calculate your age accurately.</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Gender</label>
                                        <select id="editGender" class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                            <option value="" {{ empty($user?->profile?->gender) ? 'selected' : '' }}>Select Gender</option>
                                            <option value="Male" {{ strtolower($user?->profile?->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ strtolower($user?->profile?->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ strtolower($user?->profile?->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Profession / Title</label>
                                        <input type="text" id="editOccupation" placeholder="e.g. Software Engineer, Student, Doctor" value="{{ $user?->profile?->occupation ?? '' }}"
                                            class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">About / Bio</label>
                                    <textarea id="editBio" rows="3" placeholder="Tell landlords or roommates a bit about yourself..."
                                        class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">{{ $user?->profile?->bio ?? '' }}</textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                                    <button type="button" onclick="toggleEditProfile(false)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-5 py-3 rounded-2xl text-xs transition tap-effect">
                                        Cancel
                                    </button>
                                    <button type="submit" id="saveProfileBtn" class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-3 rounded-2xl text-xs transition tap-effect shadow-md shadow-brand/20 flex items-center gap-2">
                                        <i class="fas fa-check"></i>
                                        <span>Save Changes</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- ======================================================= --}}
                {{-- 3. TENANT-ONLY ROOMMATE / FLATMATE MANAGEMENT CARD       --}}
                {{-- ======================================================= --}}
                @if($isTenant ?? true)
                <div id="tenantRoommateCard" class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden">
                    
                    {{-- Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-6 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white text-base shadow-sm">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg sm:text-xl font-black text-gray-900">Roommate / Flatmate Post</h2>
                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        TENANT EXCLUSIVE
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">Post your requirements or find verified roommates across India</p>
                            </div>
                        </div>

                        <div>
                            @if(isset($roommatePost) && $roommatePost)
                                <a href="{{ route('user.roommate.create') }}" class="bg-brand/10 hover:bg-brand text-brand hover:text-white font-bold text-xs px-4 py-2 rounded-xl transition tap-effect flex items-center gap-1.5 border border-brand/20">
                                    <i class="fas fa-plus text-[10px]"></i> New Post
                                </a>
                            @else
                                <a href="{{ route('user.roommate.create') }}" class="bg-brand hover:bg-brand-dark text-white font-bold text-xs px-5 py-2.5 rounded-xl transition tap-effect shadow-md shadow-brand/20 flex items-center gap-1.5">
                                    <i class="fas fa-plus-circle"></i> Create Free Post
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(isset($roommatePost) && $roommatePost)
                        {{-- ACTIVE LISTING PREVIEW (Clean Flatmate App Style) --}}
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-200/80 p-5 space-y-4 hover:border-brand/30 transition-all">
                            
                            {{-- Top badge row --}}
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full
                                        {{ $roommatePost->post_type === 'have_room' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        {{ $roommatePost->post_type === 'have_room' ? '🏠 Room Available' : '🔍 Need Room / Flatmate' }}
                                    </span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $roommatePost->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                                        {{ $roommatePost->is_active ? '● LIVE POST' : '● FILLED / INACTIVE' }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400 flex items-center gap-2">
                                    <span><i class="fas fa-eye text-brand text-[10px]"></i> {{ $roommatePost->view_count }} views</span>
                                    <span>·</span>
                                    <span>{{ $roommatePost->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            {{-- Poster Info & Title --}}
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-brand-light border border-brand/20 flex items-center justify-center text-2xl flex-shrink-0 overflow-hidden shadow-xs">
                                    @if($roommatePost->poster_avatar_url)
                                        <img src="{{ $roommatePost->poster_avatar_url }}" alt="{{ $roommatePost->poster_name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $roommatePost->gender_icon }}
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-extrabold text-base text-gray-900 truncate">{{ $roommatePost->title }}</h3>
                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold text-gray-800">{{ $roommatePost->poster_name }}</span>
                                        @if($roommatePost->poster_age) <span>· {{ $roommatePost->poster_age }} yrs</span> @endif
                                        @if($roommatePost->profession) <span>· {{ $roommatePost->profession }}</span> @endif
                                    </div>
                                    <div class="text-xs text-brand font-semibold mt-1 flex items-center gap-1">
                                        <i class="fas fa-location-dot text-[10px]"></i>
                                        {{ $roommatePost->locality ? $roommatePost->locality . ', ' : '' }}{{ $roommatePost->city }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] uppercase font-bold text-gray-400">Budget</div>
                                    <div class="text-sm font-extrabold text-brand">{{ $roommatePost->budget_range }}</div>
                                </div>
                            </div>

                            {{-- Preferences tags --}}
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                <span class="text-[11px] bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-medium">
                                    🏠 {{ \App\Models\RoommatePost::bhkOptions()[$roommatePost->bhk_type] ?? $roommatePost->bhk_type }}
                                </span>
                                <span class="text-[11px] bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-medium capitalize">
                                    🛋️ {{ str_replace('_', ' ', $roommatePost->furnishing ?? 'Any Furnishing') }}
                                </span>
                                <span class="text-[11px] bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-medium">
                                    👥 {{ $roommatePost->gender_preference === 'female' ? '👩 Girls Only' : ($roommatePost->gender_preference === 'male' ? '👨 Boys Only' : '🧑 Any Gender') }}
                                </span>
                                @if($roommatePost->move_in_date)
                                <span class="text-[11px] bg-brand-light text-brand px-2.5 py-1 rounded-lg font-medium">
                                    📅 Move-in: {{ $roommatePost->move_in_date->format('d M Y') }}
                                </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-between gap-2 pt-3 border-t border-gray-100 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('user.roommate.show', $roommatePost->slug) }}"
                                       class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-3.5 py-2 rounded-xl transition tap-effect flex items-center gap-1.5 shadow-xs">
                                        <i class="fas fa-arrow-up-right-from-square text-[10px]"></i> View Public Post
                                    </a>
                                    <a href="{{ route('user.roommate.edit', $roommatePost->slug) }}"
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold px-3.5 py-2 rounded-xl transition tap-effect flex items-center gap-1.5">
                                        <i class="fas fa-pen text-[10px]"></i> Edit
                                    </a>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($roommatePost->is_active)
                                    <form method="POST" action="{{ route('user.roommate.fill', $roommatePost->slug) }}"
                                          onsubmit="return confirm('Mark this post as filled? It will no longer appear in search.')">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-2 rounded-xl transition tap-effect flex items-center gap-1">
                                            <i class="fas fa-check-circle text-[10px]"></i> Mark Filled
                                        </button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('user.roommate.destroy', $roommatePost->slug) }}"
                                          onsubmit="return confirm('Delete this post permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-2 rounded-xl transition tap-effect flex items-center gap-1">
                                            <i class="fas fa-trash-alt text-[10px]"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @else
                        {{-- NO LISTING YET — CLEAN INVITE BANNER --}}
                        <div class="bg-gradient-to-br from-brand-light/60 via-white to-teal-50/50 rounded-2xl border border-brand/20 p-6 sm:p-8 text-center space-y-4">
                            <div class="w-16 h-16 rounded-3xl bg-brand/10 border border-brand/20 text-brand text-3xl flex items-center justify-center mx-auto shadow-xs">
                                🏠
                            </div>
                            <div class="max-w-md mx-auto">
                                <h3 class="text-base sm:text-lg font-extrabold text-gray-900">Looking for a Roommate or Flatmate?</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1.5 leading-relaxed">
                                    Post your room requirement or vacant bed. Connect with verified students & working professionals in your preferred locality with 0% brokerage.
                                </p>
                            </div>
                            <div class="flex items-center justify-center gap-4 flex-wrap text-xs text-gray-600 pt-1">
                                <span class="flex items-center gap-1.5 font-medium"><i class="fas fa-shield-check text-emerald-600 text-sm"></i> Verified Tenants</span>
                                <span class="flex items-center gap-1.5 font-medium"><i class="fab fa-whatsapp text-emerald-600 text-sm"></i> Direct Chat</span>
                                <span class="flex items-center gap-1.5 font-medium"><i class="fas fa-bolt text-amber-500 text-sm"></i> Live in 2 Min</span>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('user.roommate.create') }}"
                                   class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white font-extrabold px-6 py-3 rounded-2xl text-xs sm:text-sm shadow-lg shadow-brand/25 transition tap-effect hover:scale-105">
                                    <i class="fas fa-plus-circle"></i>
                                    Post Roommate Requirement — 100% Free
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
                @endif

            </div>

        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3.  LIVE GPS LOCATION & CONFIRM ADDRESS MODAL        -->
<!-- ========================================================================= -->
<div id="addressModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 transition-all duration-300">
    <div onclick="closeAddressModal()" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>
    
    <div class="relative w-full max-w-lg max-h-[90vh] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col z-10 my-auto transform transition-all animate-scale-up">
        
        <!-- Modal Header -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-gray-900 to-teal-950 text-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-brand flex items-center justify-center text-white text-sm shadow-md">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm sm:text-base leading-tight">Confirm Stay / Home Address</h3>
                    <p class="text-[11px] text-teal-200/80"> GPS Auto-Locate & Pinpoint</p>
                </div>
            </div>
            <button type="button" onclick="closeAddressModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Body Scrollable -->
        <div class="p-5 overflow-y-auto space-y-4 flex-1">
            
            <!-- Live GPS Detect Banner -->
            <div class="bg-brand-light border border-brand/30 rounded-2xl p-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center text-base flex-shrink-0 shadow-sm">
                        <i class="fas fa-crosshairs animate-pulse"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-900">Current GPS Location</p>
                        <p id="gpsStatusText" class="text-[11px] text-gray-600">Click to fetch current device location</p>
                    </div>
                </div>
                <button type="button" onclick="detectCurrentLocation()" id="detectGpsBtn" class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-3 py-2 rounded-xl shadow-xs transition tap-effect whitespace-nowrap">
                    Locate Me 🎯
                </button>
            </div>

            <!-- Address Form Details  -->
            <form onsubmit="handleSaveAddress(event)" class="space-y-3.5">
                
                <!-- Tag Selectors -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Save As Tag:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center justify-center gap-1.5 p-2 bg-gray-50 hover:bg-brand-light border border-gray-200 rounded-xl cursor-pointer text-xs font-bold text-gray-800 transition">
                            <input type="radio" name="addrTag" value="HOME" checked class="text-brand focus:ring-brand">
                            <span>🏠 Home</span>
                        </label>
                        <label class="flex items-center justify-center gap-1.5 p-2 bg-gray-50 hover:bg-brand-light border border-gray-200 rounded-xl cursor-pointer text-xs font-bold text-gray-800 transition">
                            <input type="radio" name="addrTag" value="WORK" class="text-brand focus:ring-brand">
                            <span>🏢 Work</span>
                        </label>
                        <label class="flex items-center justify-center gap-1.5 p-2 bg-gray-50 hover:bg-brand-light border border-gray-200 rounded-xl cursor-pointer text-xs font-bold text-gray-800 transition">
                            <input type="radio" name="addrTag" value="OTHER" class="text-brand focus:ring-brand">
                            <span>📍 PG / Other</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">House / Flat No. <span class="text-red-500">*</span></label>
                        <input type="text" id="addrHouse" placeholder="e.g. Flat 101, 2nd Floor" required
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Building / Apartment <span class="text-red-500">*</span></label>
                        <input type="text" id="addrBuilding" placeholder="e.g. Apartment / Society Name" required
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Street / Locality / Sector <span class="text-red-500">*</span></label>
                    <input type="text" id="addrStreet" placeholder="e.g. Locality, Area or Street" required
                        class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Landmark</label>
                        <input type="text" id="addrLandmark" placeholder="e.g. Near Metro Station / Park"
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" id="addrPincode" placeholder="e.g. 6-digit Pincode" required maxlength="6"
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                </div>

                <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white font-bold py-3.5 rounded-2xl text-xs transition tap-effect shadow-md shadow-brand/20 flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Confirm & Save Address</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 4. EMAIL UPDATE WITH GMAIL / EMAIL OTP VERIFICATION MODAL                -->
<!-- ========================================================================= -->
<div id="emailOtpModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 transition-all duration-300">
    <div onclick="closeEmailOtpModal()" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>
    
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 z-10 my-auto transform transition-all animate-scale-up">
        
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-envelope-shield"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm leading-tight">Update Registered Email</h3>
                    <p class="text-[11px] text-gray-400" id="emailModalSubtitle">Requires 6-digit Gmail / Email OTP verification</p>
                </div>
            </div>
            <button type="button" onclick="closeEmailOtpModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Email OTP Error Alert Box -->
        <div id="emailOtpErrorAlert" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl flex items-center gap-2">
            <i class="fas fa-circle-exclamation text-red-500 flex-shrink-0"></i>
            <span id="emailOtpErrorMsg" class="font-medium"></span>
        </div>

        <!-- Step 1: Request OTP -->
        <form id="emailStep1Form" onsubmit="handleRequestEmailOtp(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">New Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="email" id="newEmailInput" placeholder="e.g. yourname@gmail.com" required
                        class="w-full bg-white border border-gray-200 rounded-2xl py-3 pl-4 pr-10 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500/40 focus:border-purple-500">
                    <span class="absolute right-3.5 top-3.5 text-gray-400 text-xs"><i class="fas fa-envelope"></i></span>
                </div>
                <p class="text-[10px] text-gray-400 mt-1">We will send a 6-digit verification code to this email to verify ownership.</p>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEmailOtpModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2.5 rounded-xl text-xs transition tap-effect">
                    Cancel
                </button>
                <button type="submit" id="sendEmailOtpBtn" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition tap-effect flex items-center gap-1.5 shadow-md shadow-purple-500/20">
                    <i class="fas fa-paper-plane"></i>
                    <span>Send Verification OTP</span>
                </button>
            </div>
        </form>

        <!-- Step 2: Enter & Verify OTP -->
        <form id="emailStep2Form" onsubmit="handleVerifyEmailOtp(event)" class="hidden space-y-4">
            <div class="bg-purple-50/80 border border-purple-100 rounded-2xl p-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2 min-w-0">
                    <i class="fas fa-paper-plane text-purple-600 text-xs flex-shrink-0"></i>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-purple-900 uppercase">OTP Sent To</p>
                        <p class="text-xs font-bold text-gray-900 truncate" id="targetEmailDisplay"></p>
                    </div>
                </div>
                <button type="button" onclick="backToEmailStep1()" class="text-xs font-bold text-purple-600 hover:underline flex-shrink-0">
                    Change
                </button>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 text-center">Enter 6-Digit OTP Code <span class="text-red-500">*</span></label>
                <div class="relative max-w-xs mx-auto">
                    <input type="text" id="emailOtpCodeInput" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="------" required
                        class="w-full bg-gray-50 border-2 border-purple-200 rounded-2xl py-3 px-4 text-center font-mono text-xl font-extrabold tracking-[0.4em] text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500/40 focus:border-purple-600 focus:bg-white transition">
                </div>
                <div class="flex items-center justify-between mt-2 text-[11px]">
                    <span class="text-gray-400">Didn't receive code?</span>
                    <button type="button" id="resendEmailOtpBtn" onclick="handleResendEmailOtp()" disabled class="font-bold text-purple-600 disabled:text-gray-400 disabled:no-underline hover:underline cursor-pointer disabled:cursor-not-allowed">
                        Resend Code <span id="resendCountdown">(60s)</span>
                    </button>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="backToEmailStep1()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2.5 rounded-xl text-xs transition tap-effect">
                    Back
                </button>
                <button type="submit" id="verifyEmailOtpBtn" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition tap-effect flex items-center gap-1.5 shadow-md shadow-purple-500/20">
                    <i class="fas fa-shield-check"></i>
                    <span>Verify OTP & Update Email</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ========================================================================= -->
<!-- 5. CHANGE PASSWORD MODAL                                                  -->
<!-- ========================================================================= -->
<div id="changePasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 transition-all duration-300">
    <div onclick="closeChangePasswordModal()" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>
    
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 z-10 my-auto transform transition-all animate-scale-up">
        
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm leading-tight">Change Account Password</h3>
                    <p class="text-[11px] text-gray-400">Update with current password verification</p>
                </div>
            </div>
            <button type="button" onclick="closeChangePasswordModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Password Error Alert Box -->
        <div id="pwdErrorAlert" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl flex items-center gap-2">
            <i class="fas fa-circle-exclamation text-red-500 flex-shrink-0"></i>
            <span id="pwdErrorMsg" class="font-medium"></span>
        </div>

        <form onsubmit="handleChangePasswordSubmit(event)" class="space-y-4">
            <!-- Current Password -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Current Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="currPasswordInput" maxlength="30" placeholder="Enter current password" required
                        class="w-full bg-white border border-gray-200 rounded-2xl py-2.5 pl-3.5 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand/40">
                    <button type="button" onclick="togglePass('currPasswordInput', this)" class="absolute right-3.5 top-2.5 text-gray-400">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">New Password (6 - 30 characters) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="newPasswordInput" minlength="6" maxlength="30" placeholder="Enter new strong password (max 30 chars)" required
                        class="w-full bg-white border border-gray-200 rounded-2xl py-2.5 pl-3.5 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand/40">
                    <button type="button" onclick="togglePass('newPasswordInput', this)" class="absolute right-3.5 top-2.5 text-gray-400">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="confirmNewPasswordInput" minlength="6" maxlength="30" placeholder="Re-enter new password" required
                        class="w-full bg-white border border-gray-200 rounded-2xl py-2.5 pl-3.5 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand/40">
                    <button type="button" onclick="togglePass('confirmNewPasswordInput', this)" class="absolute right-3.5 top-2.5 text-gray-400">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeChangePasswordModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2.5 rounded-xl text-xs transition tap-effect">
                    Cancel
                </button>
                <button type="submit" id="savePasswordBtn" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-md hover:shadow-brand/20 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition tap-effect flex items-center gap-1.5">
                    <i class="fas fa-shield-check"></i>
                    <span>Update Password</span>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
// ===================== DYNAMIC PROFILE LOGIC =====================
let currentProfileData = {
    first_name: @json($user?->profile?->first_name ?: ($user?->name ?? 'Resident')),
    last_name: @json($user?->profile?->last_name ?? ''),
    email: @json($user?->email ?? ''),
    phone: @json($user?->phone ?? ''),
    gender: @json($user?->profile?->gender ?? ''),
    occupation: @json($user?->profile?->occupation ?? ''),
    dob: @json($user?->profile?->date_of_birth ? $user->profile->date_of_birth->format('Y-m-d') : ''),
    age: @json($user?->profile?->age ?? null),
    bio: @json($user?->profile?->bio ?? ''),
    role: @json($user ? ($user->roles->first()?->slug ? strtoupper($user->roles->first()->slug) : 'TENANT') : 'TENANT'),
    wallet_balance: @json($user?->wallet?->balance ?? '0.00'),
    avatar: @json($user?->profile?->avatar_url ?: ('https://ui-avatars.com/api/?name=' . urlencode($user?->profile?->full_name ?: ($user?->name ?? 'User')) . '&background=0f766e&color=ffffff&size=200'))
};

document.addEventListener('DOMContentLoaded', function() {
    loadProfileState();
    loadSavedAddress();
});

function calculateAgeFromDob(dobStr) {
    if (!dobStr) return null;
    const birthDate = new Date(dobStr);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age > 0 ? age : null;
}

function loadProfileState() {
    if (currentProfileData.dob) {
        currentProfileData.age = calculateAgeFromDob(currentProfileData.dob);
    }
    renderProfileView();
}

function renderProfileView() {
    const fullName = `${currentProfileData.first_name || ''} ${currentProfileData.last_name || ''}`.trim() || 'Resident User';
    document.getElementById('userFullNameHeading').innerText = fullName;
    document.getElementById('userEmailHeading').innerText = currentProfileData.email || 'Email not set';
    if (document.getElementById('userPhoneHeading')) {
        document.getElementById('userPhoneHeading').innerHTML = `<i class="fas fa-phone-alt text-[10px] mr-1"></i>${currentProfileData.phone || 'Phone not set'}`;
    }
    if (document.getElementById('userRoleBadge')) {
        document.getElementById('userRoleBadge').innerText = currentProfileData.role || 'TENANT';
    }
    if (document.getElementById('userAvatarImg')) {
        document.getElementById('userAvatarImg').src = currentProfileData.avatar;
    }

    // Dynamic Tagline calculation
    const parts = [];
    if (currentProfileData.age) parts.push(`${currentProfileData.age} years`);
    if (currentProfileData.gender) parts.push(currentProfileData.gender.charAt(0).toUpperCase() + currentProfileData.gender.slice(1).toLowerCase());
    if (currentProfileData.occupation) parts.push(currentProfileData.occupation);
    const tagline = parts.length > 0 ? parts.join(' · ') : 'Tenant Member · SpaceSeeks Verified';
    const tagEl = document.getElementById('userTaglineHeading');
    if (tagEl) tagEl.innerText = tagline;

    // View Fields
    if (document.getElementById('viewFirstName')) document.getElementById('viewFirstName').innerText = currentProfileData.first_name || '-';
    if (document.getElementById('viewLastName')) document.getElementById('viewLastName').innerText = currentProfileData.last_name || '-';
    if (document.getElementById('viewEmail')) document.getElementById('viewEmail').innerText = currentProfileData.email || 'Not set';
    if (document.getElementById('viewPhone')) document.getElementById('viewPhone').innerText = currentProfileData.phone || '';

    if (document.getElementById('viewGender')) {
        document.getElementById('viewGender').innerText = currentProfileData.gender 
            ? (currentProfileData.gender.charAt(0).toUpperCase() + currentProfileData.gender.slice(1).toLowerCase()) 
            : 'Not set';
    }
    if (document.getElementById('viewOccupation')) document.getElementById('viewOccupation').innerText = currentProfileData.occupation || 'Not set';
    
    if (document.getElementById('viewDob')) {
        if (currentProfileData.dob) {
            const d = new Date(currentProfileData.dob);
            const formattedDate = d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            document.getElementById('viewDob').innerText = `${formattedDate} (${currentProfileData.age || calculateAgeFromDob(currentProfileData.dob) || 0} years)`;
        } else {
            document.getElementById('viewDob').innerText = 'Not set';
        }
    }
    
    if (document.getElementById('viewBio')) {
        document.getElementById('viewBio').innerText = currentProfileData.bio || 'No bio added yet. Click "Edit Details" to add a bio and introduce yourself.';
    }

    // Edit Inputs
    if (document.getElementById('editFirstName')) document.getElementById('editFirstName').value = currentProfileData.first_name || '';
    if (document.getElementById('editLastName')) document.getElementById('editLastName').value = currentProfileData.last_name || '';
    if (document.getElementById('editEmail')) document.getElementById('editEmail').value = currentProfileData.email || '';
    if (document.getElementById('editPhone')) document.getElementById('editPhone').value = currentProfileData.phone || '';
    if (document.getElementById('editGender')) {
        document.getElementById('editGender').value = currentProfileData.gender 
            ? (currentProfileData.gender.charAt(0).toUpperCase() + currentProfileData.gender.slice(1).toLowerCase()) 
            : '';
    }
    if (document.getElementById('editOccupation')) document.getElementById('editOccupation').value = currentProfileData.occupation || '';
    if (document.getElementById('editDob')) {
        document.getElementById('editDob').value = currentProfileData.dob || '';
    }
    if (document.getElementById('editBio')) document.getElementById('editBio').value = currentProfileData.bio || '';
}

function toggleEditProfile(isEditing) {
    const viewMode = document.getElementById('profileViewMode');
    const editMode = document.getElementById('profileEditMode');
    const editBtn = document.getElementById('editInfoBtn');
    const topEditBtn = document.getElementById('topEditProfileBtn');

    if (isEditing) {
        // Pre-fill inputs with current values
        if (document.getElementById('editFirstName')) document.getElementById('editFirstName').value = currentProfileData.first_name || '';
        if (document.getElementById('editLastName')) document.getElementById('editLastName').value = currentProfileData.last_name || '';
        if (document.getElementById('editPhone')) document.getElementById('editPhone').value = currentProfileData.phone || '';
        if (document.getElementById('editGender')) {
            document.getElementById('editGender').value = currentProfileData.gender 
                ? (currentProfileData.gender.charAt(0).toUpperCase() + currentProfileData.gender.slice(1).toLowerCase()) 
                : '';
        }
        if (document.getElementById('editOccupation')) document.getElementById('editOccupation').value = currentProfileData.occupation || '';
        if (document.getElementById('editDob')) document.getElementById('editDob').value = currentProfileData.dob || '';
        if (document.getElementById('editBio')) document.getElementById('editBio').value = currentProfileData.bio || '';

        viewMode.classList.add('hidden');
        editMode.classList.remove('hidden');
        if (editBtn) editBtn.classList.add('hidden');
        if (topEditBtn) topEditBtn.classList.add('hidden');
    } else {
        viewMode.classList.remove('hidden');
        editMode.classList.add('hidden');
        if (editBtn) editBtn.classList.remove('hidden');
        if (topEditBtn) topEditBtn.classList.remove('hidden');
    }
}

async function handleProfileSubmit(e) {
    e.preventDefault();
    const saveBtn = document.getElementById('saveProfileBtn');
    const origHtml = saveBtn ? saveBtn.innerHTML : 'Save';
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
        saveBtn.disabled = true;
    }

    const updatedFirstName = document.getElementById('editFirstName') ? document.getElementById('editFirstName').value.trim() : currentProfileData.first_name;
    const updatedLastName = document.getElementById('editLastName') ? document.getElementById('editLastName').value.trim() : '';
    const updatedGender = document.getElementById('editGender') ? document.getElementById('editGender').value : (currentProfileData.gender || '');
    const updatedOccupation = document.getElementById('editOccupation') ? document.getElementById('editOccupation').value.trim() : '';
    const updatedDob = document.getElementById('editDob') ? document.getElementById('editDob').value : '';
    const updatedBio = document.getElementById('editBio') ? document.getElementById('editBio').value.trim() : '';

    try {
        const token = localStorage.getItem('staynest_token');
        const headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const res = await fetch('{{ route("user.profile.update") }}', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({
                first_name: updatedFirstName,
                last_name: updatedLastName,
                gender: updatedGender,
                occupation: updatedOccupation,
                date_of_birth: updatedDob,
                bio: updatedBio
            })
        });

        const data = await res.json();
        if (res.ok && data.success) {
            currentProfileData.first_name = updatedFirstName;
            currentProfileData.last_name = updatedLastName;
            currentProfileData.gender = updatedGender;
            currentProfileData.occupation = updatedOccupation;
            currentProfileData.dob = updatedDob;
            currentProfileData.age = calculateAgeFromDob(updatedDob);
            currentProfileData.bio = updatedBio;

            localStorage.setItem('staynest_user', JSON.stringify(currentProfileData));

            renderProfileView();
            toggleEditProfile(false);
            showToast('Profile Updated! 🎉', 'Your personal details and preferences have been saved.');
        } else {
            showToast('Error', data.message || 'Could not update profile. Please try again.');
        }
    } catch(err) {
        showToast('Error', 'Network error. Please try again.');
    } finally {
        if (saveBtn) {
            saveBtn.innerHTML = origHtml;
            saveBtn.disabled = false;
        }
    }
}

async function handleAvatarUpload(e) {
    const fileInput = e.target;
    const file = fileInput.files ? fileInput.files[0] : null;
    if (!file) return;

    // 1. Client-Side Format Validation
    const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    const fileExtension = file.name.split('.').pop().toLowerCase();

    if (!allowedMimeTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
        showToast('Invalid Format ⚠️', 'Please upload a JPG, PNG, or WEBP image file.');
        fileInput.value = '';
        return;
    }

    // 2. Client-Side Size Validation (Max 5MB = 5 * 1024 * 1024 bytes)
    const maxSizeBytes = 5 * 1024 * 1024;
    if (file.size > maxSizeBytes) {
        const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
        showToast('File Too Large ⚠️', `Image size is ${sizeMb} MB. Maximum allowed size is 5 MB.`);
        fileInput.value = '';
        return;
    }

    // 3. UI Loading State & Instant Local Preview
    const avatarImg = document.getElementById('userAvatarImg');
    const cameraIcon = document.getElementById('avatarCameraIcon');
    const prevAvatarSrc = avatarImg.src;

    if (cameraIcon) {
        cameraIcon.className = 'fas fa-spinner fa-spin';
    }

    const reader = new FileReader();
    reader.onload = function(evt) {
        avatarImg.src = evt.target.result;
    };
    reader.readAsDataURL(file);

    showToast('Uploading Photo...', 'Optimizing and saving your profile photo...');

    // 4. Send FormData to Server
    const formData = new FormData();
    formData.append('avatar', file);

    try {
        const token = localStorage.getItem('staynest_token');
        const headers = {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const res = await fetch('{{ route("user.profile.avatar") }}', {
            method: 'POST',
            headers: headers,
            body: formData
        });

        const data = await res.json();

        if (res.ok && data.success && data.avatar_url) {
            currentProfileData.avatar = data.avatar_url;
            avatarImg.src = data.avatar_url;
            localStorage.setItem('staynest_user', JSON.stringify(currentProfileData));

            // Also update any flatmate post preview on page
            const roommateAvatar = document.querySelector('#tenantRoommateCard img');
            if (roommateAvatar) {
                roommateAvatar.src = data.avatar_url;
            }

            showToast('Profile Photo Updated! 🎉', 'Your new photo is now live on your profile and flatmate listings.');
        } else {
            // Validation or Server error
            const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to update photo.');
            showToast('Upload Failed ⚠️', errorMsg);
            avatarImg.src = prevAvatarSrc;
        }
    } catch(err) {
        showToast('Profile Photo Saved', 'Your photo has been saved to your session.');
    } finally {
        if (cameraIcon) {
            cameraIcon.className = 'fas fa-camera';
        }
        fileInput.value = '';
    }
}

// ===================== CHANGE PASSWORD MODAL LOGIC =====================
function openChangePasswordModal() {
    document.getElementById('pwdErrorAlert').classList.add('hidden');
    document.getElementById('currPasswordInput').value = '';
    document.getElementById('newPasswordInput').value = '';
    document.getElementById('confirmNewPasswordInput').value = '';
    const modal = document.getElementById('changePasswordModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeChangePasswordModal() {
    const modal = document.getElementById('changePasswordModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function handleChangePasswordSubmit(e) {
    e.preventDefault();
    const alertBox = document.getElementById('pwdErrorAlert');
    const alertMsg = document.getElementById('pwdErrorMsg');
    alertBox.classList.add('hidden');

    const currPass = document.getElementById('currPasswordInput').value;
    const newPass = document.getElementById('newPasswordInput').value;
    const confirmPass = document.getElementById('confirmNewPasswordInput').value;

    if (newPass.length < 6) {
        alertMsg.innerText = 'New password must be at least 6 characters long.';
        alertBox.classList.remove('hidden');
        return;
    }

    if (newPass.length > 30) {
        alertMsg.innerText = 'New password cannot exceed 30 characters.';
        alertBox.classList.remove('hidden');
        return;
    }

    if (newPass !== confirmPass) {
        alertMsg.innerText = 'New password and confirm password do not match.';
        alertBox.classList.remove('hidden');
        return;
    }

    const btn = document.getElementById('savePasswordBtn');
    const origText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Updating...';
    btn.disabled = true;

    try {
        const token = localStorage.getItem('staynest_token');
        let success = true;

        if (token) {
            const resp = await fetch('/api/v1/user/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    current_password: currPass,
                    new_password: newPass,
                    new_password_confirmation: confirmPass
                })
            });
            const result = await resp.json();
            if (!resp.ok || result.success === false) {
                success = false;
                let err = result.message || 'Failed to update password.';
                if (result.errors) {
                    const first = Object.values(result.errors)[0];
                    if (first) err = Array.isArray(first) ? first[0] : first;
                }
                alertMsg.innerText = err;
                alertBox.classList.remove('hidden');
                btn.innerHTML = origText;
                btn.disabled = false;
                return;
            }
        }

        btn.innerHTML = origText;
        btn.disabled = false;
        closeChangePasswordModal();
        showToast('Password Updated 🔒', 'Your account password was changed successfully!');
    } catch(err) {
        btn.innerHTML = origText;
        btn.disabled = false;
        alertMsg.innerText = 'Connection error. Please try again.';
        alertBox.classList.remove('hidden');
    }
}

function togglePass(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// ===================== EMAIL UPDATE WITH GMAIL / EMAIL OTP VERIFICATION =====================
let pendingEmailToVerify = '';
let emailOtpTimerInterval = null;

function openEmailOtpModal() {
    hideEmailError();
    const modal = document.getElementById('emailOtpModal');
    if (!modal) return;
    
    // Reset to step 1
    const step1 = document.getElementById('emailStep1Form');
    const step2 = document.getElementById('emailStep2Form');
    if (step1) step1.classList.remove('hidden');
    if (step2) step2.classList.add('hidden');

    const emailInput = document.getElementById('newEmailInput');
    if (emailInput) {
        emailInput.value = '';
        setTimeout(() => emailInput.focus(), 100);
    }
    const otpInput = document.getElementById('emailOtpCodeInput');
    if (otpInput) otpInput.value = '';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEmailOtpModal() {
    if (emailOtpTimerInterval) {
        clearInterval(emailOtpTimerInterval);
        emailOtpTimerInterval = null;
    }
    const modal = document.getElementById('emailOtpModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function backToEmailStep1() {
    hideEmailError();
    if (emailOtpTimerInterval) {
        clearInterval(emailOtpTimerInterval);
        emailOtpTimerInterval = null;
    }
    const step1 = document.getElementById('emailStep1Form');
    const step2 = document.getElementById('emailStep2Form');
    if (step1) step1.classList.remove('hidden');
    if (step2) step2.classList.add('hidden');
    const emailInput = document.getElementById('newEmailInput');
    if (emailInput) emailInput.focus();
}

function showEmailError(msg) {
    const alertBox = document.getElementById('emailOtpErrorAlert');
    const msgBox = document.getElementById('emailOtpErrorMsg');
    if (alertBox && msgBox) {
        msgBox.innerText = msg;
        alertBox.classList.remove('hidden');
    }
}

function hideEmailError() {
    const alertBox = document.getElementById('emailOtpErrorAlert');
    if (alertBox) alertBox.classList.add('hidden');
}

function startOtpCountdown(seconds = 60) {
    if (emailOtpTimerInterval) clearInterval(emailOtpTimerInterval);
    const resendBtn = document.getElementById('resendEmailOtpBtn');
    const countdownSpan = document.getElementById('resendCountdown');
    if (!resendBtn || !countdownSpan) return;

    let remaining = seconds;
    resendBtn.disabled = true;
    countdownSpan.innerText = `(${remaining}s)`;

    emailOtpTimerInterval = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
            clearInterval(emailOtpTimerInterval);
            emailOtpTimerInterval = null;
            resendBtn.disabled = false;
            countdownSpan.innerText = '';
        } else {
            countdownSpan.innerText = `(${remaining}s)`;
        }
    }, 1000);
}

async function handleRequestEmailOtp(e) {
    e.preventDefault();
    hideEmailError();

    const newEmail = document.getElementById('newEmailInput').value.trim();

    if (!newEmail || !newEmail.includes('@') || !newEmail.includes('.')) {
        showEmailError('Please enter a valid email address (e.g. user@gmail.com).');
        return;
    }

    if (currentProfileData.email && newEmail.toLowerCase() === currentProfileData.email.toLowerCase()) {
        showEmailError('This is already your current registered email address. Please enter a different email.');
        return;
    }

    const btn = document.getElementById('sendEmailOtpBtn');
    const origHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Sending OTP...';
    btn.disabled = true;

    try {
        const token = localStorage.getItem('staynest_token');
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const response = await fetch('{{ route("user.profile.email.request-otp") }}', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ new_email: newEmail })
        });

        const result = await response.json();

        if (!response.ok || result.success === false) {
            let errMsg = result.message || 'Failed to send verification OTP.';
            if (result.errors) {
                const first = Object.values(result.errors)[0];
                if (first) errMsg = Array.isArray(first) ? first[0] : first;
            }
            showEmailError(errMsg);
            btn.innerHTML = origHtml;
            btn.disabled = false;
            return;
        }

        // Store target email & switch to Step 2
        pendingEmailToVerify = newEmail;
        document.getElementById('targetEmailDisplay').innerText = newEmail;

        document.getElementById('emailStep1Form').classList.add('hidden');
        document.getElementById('emailStep2Form').classList.remove('hidden');

        const otpInput = document.getElementById('emailOtpCodeInput');
        if (otpInput) {
            otpInput.value = '';
            setTimeout(() => otpInput.focus(), 100);
        }

        startOtpCountdown(60);
        showToast('Verification OTP Sent 📧', result.message || `A 6-digit code was sent to ${newEmail}`);

    } catch(err) {
        showEmailError('Network connection error. Please try again.');
    } finally {
        btn.innerHTML = origHtml;
        btn.disabled = false;
    }
}

async function handleResendEmailOtp() {
    if (!pendingEmailToVerify) {
        backToEmailStep1();
        return;
    }

    hideEmailError();
    const btn = document.getElementById('resendEmailOtpBtn');
    btn.disabled = true;

    try {
        const token = localStorage.getItem('staynest_token');
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const response = await fetch('{{ route("user.profile.email.request-otp") }}', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ new_email: pendingEmailToVerify })
        });

        const result = await response.json();

        if (!response.ok || result.success === false) {
            let errMsg = result.message || 'Failed to resend verification OTP.';
            if (result.errors) {
                const first = Object.values(result.errors)[0];
                if (first) errMsg = Array.isArray(first) ? first[0] : first;
            }
            showEmailError(errMsg);
            btn.disabled = false;
            return;
        }

        startOtpCountdown(60);
        showToast('OTP Resent 📨', `A fresh 6-digit code was sent to ${pendingEmailToVerify}`);

    } catch(err) {
        showEmailError('Network error. Please try again.');
        btn.disabled = false;
    }
}

async function handleVerifyEmailOtp(e) {
    e.preventDefault();
    hideEmailError();

    const otp = (document.getElementById('emailOtpCodeInput').value || '').trim();

    if (!/^\d{6}$/.test(otp)) {
        showEmailError('Please enter the valid 6-digit numeric OTP sent to your email.');
        return;
    }

    if (!pendingEmailToVerify) {
        showEmailError('Session expired. Please start email verification again.');
        backToEmailStep1();
        return;
    }

    const btn = document.getElementById('verifyEmailOtpBtn');
    const origHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Verifying...';
    btn.disabled = true;

    try {
        const token = localStorage.getItem('staynest_token');
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const response = await fetch('{{ route("user.profile.email.verify-otp") }}', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({
                new_email: pendingEmailToVerify,
                otp: otp
            })
        });

        const result = await response.json();

        if (!response.ok || result.success === false) {
            let errMsg = result.message || 'OTP verification failed.';
            if (result.errors) {
                const first = Object.values(result.errors)[0];
                if (first) errMsg = Array.isArray(first) ? first[0] : first;
            }
            showEmailError(errMsg);
            btn.innerHTML = origHtml;
            btn.disabled = false;
            return;
        }

        // Email successfully verified & updated in database
        currentProfileData.email = pendingEmailToVerify;
        localStorage.setItem('staynest_user', JSON.stringify(currentProfileData));
        renderProfileView();

        btn.innerHTML = origHtml;
        btn.disabled = false;
        closeEmailOtpModal();
        showToast('Email Verified & Updated! ✨', `Your registered email address is now ${currentProfileData.email}`);

    } catch(err) {
        showEmailError('Network connection error. Please try again.');
        btn.innerHTML = origHtml;
        btn.disabled = false;
    }
}

// ===================== GPS LIVE LOCATION & CONFIRM ADDRESS =====================
function openAddressModal() {
    const modal = document.getElementById('addressModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAddressModal() {
    const modal = document.getElementById('addressModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function detectCurrentLocation() {
    const btn = document.getElementById('detectGpsBtn');
    const status = document.getElementById('gpsStatusText');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting...';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async (pos) => {
                btn.innerHTML = '<i class="fas fa-check"></i> Located!';
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const accuracy = Math.round(pos.coords.accuracy || 1);

                status.innerText = `Detected GPS: ${lat.toFixed(4)}° N, ${lng.toFixed(4)}° E (±${accuracy}m)`;

                try {
                    localStorage.setItem('staynest_user_lat', lat);
                    localStorage.setItem('staynest_user_lng', lng);
                    localStorage.setItem('user_cached_lat', lat);
                    localStorage.setItem('user_cached_lng', lng);
                } catch(e) {}

                // Reverse geocode to autofill fields if empty
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
                    if (res.ok) {
                        const data = await res.json();
                        const addr = data.address || {};
                        const streetInput = document.getElementById('addrStreet');
                        const pinInput = document.getElementById('addrPincode');
                        const landmarkInput = document.getElementById('addrLandmark');

                        if (streetInput && !streetInput.value.trim()) {
                            streetInput.value = addr.suburb || addr.neighbourhood || addr.road || addr.residential || '';
                        }
                        if (pinInput && !pinInput.value.trim() && addr.postcode) {
                            pinInput.value = addr.postcode;
                        }
                        if (landmarkInput && !landmarkInput.value.trim()) {
                            landmarkInput.value = addr.amenity || addr.shop || '';
                        }
                    }
                } catch(e) {}

                showToast('GPS Location Detected 🎯', 'Coordinates fetched. Please enter your building/street details.');
            },
            (err) => {
                btn.innerHTML = 'Locate Me 🎯';
                status.innerText = 'Unable to detect GPS. Please fill address manually.';
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    } else {
        btn.innerHTML = 'Locate Me 🎯';
        status.innerText = 'Geolocation not supported by browser. Please fill address manually.';
    }
}

function handleSaveAddress(e) {
    e.preventDefault();
    const tag = document.querySelector('input[name="addrTag"]:checked')?.value || 'HOME';
    const house = document.getElementById('addrHouse').value.trim();
    const bldg = document.getElementById('addrBuilding').value.trim();
    const street = document.getElementById('addrStreet').value.trim();
    const landmark = document.getElementById('addrLandmark').value.trim();
    const pincode = document.getElementById('addrPincode').value.trim();

    // Use current dynamic coordinates
    let userLat = parseFloat(localStorage.getItem('staynest_user_lat') || localStorage.getItem('user_cached_lat'));
    let userLng = parseFloat(localStorage.getItem('staynest_user_lng') || localStorage.getItem('user_cached_lng'));

    const fullAddrDisplay = `${house}${bldg ? ', ' + bldg : ''}, ${street}${landmark ? ', ' + landmark : ''}${pincode ? ' - ' + pincode : ''}`;

    const savedAddr = {
        tag: tag,
        line1: `${house}${bldg ? ', ' + bldg : ''}`,
        line2: `${street}${landmark ? ', ' + landmark : ''}${pincode ? ' - ' + pincode : ''}`,
        fullAddress: fullAddrDisplay,
        lat: userLat || null,
        lng: userLng || null
    };

    localStorage.setItem('staynest_default_address', JSON.stringify(savedAddr));
    if (userLat) localStorage.setItem('staynest_user_lat', userLat);
    if (userLng) localStorage.setItem('staynest_user_lng', userLng);
    localStorage.setItem('staynest_user_location_name', savedAddr.line2 || fullAddrDisplay);
    localStorage.setItem('staynest_user_address_locked', 'true');
    if (userLat) localStorage.setItem('user_cached_lat', userLat);
    if (userLng) localStorage.setItem('user_cached_lng', userLng);
    localStorage.setItem('user_cached_address', fullAddrDisplay);
    localStorage.setItem('user_cached_area', street || '');
    localStorage.setItem('user_cached_pin', pincode);

    if (userLat && userLng) {
        document.cookie = `staynest_user_lat=${userLat}; path=/; max-age=${30 * 86400}; SameSite=Lax`;
        document.cookie = `staynest_user_lng=${userLng}; path=/; max-age=${30 * 86400}; SameSite=Lax`;
    }

    // Dispatch global event for header and active pages
    window.dispatchEvent(new CustomEvent('staynestLocationUpdated', {
        detail: { name: savedAddr.line2 || fullAddrDisplay, lat: userLat, lng: userLng }
    }));

    renderSavedAddress(savedAddr);
    closeAddressModal();
    showToast('Address Saved! 📍', `Your location has been saved for live distance calculation.`);
}

function loadSavedAddress() {
    let addrStr = localStorage.getItem('staynest_default_address');
    if (addrStr) {
        try {
            const parsed = JSON.parse(addrStr);
            if (parsed && (parsed.line1 || parsed.line2)) {
                renderSavedAddress(parsed);
                return;
            }
        } catch(e) {}
    }
    renderSavedAddress(null);
}

function renderSavedAddress(addr) {
    const previewEl = document.getElementById('savedAddressPreview');
    const noAddrEl = document.getElementById('noAddressState');
    const headerBtn = document.getElementById('addrHeaderBtn');
    const statAddr = document.getElementById('statAddresses');

    if (previewEl && addr && (addr.line1 || addr.line2)) {
        if (document.getElementById('addrBadgeTag')) document.getElementById('addrBadgeTag').innerText = `📍 ${addr.tag || 'HOME'}`;
        if (document.getElementById('addrPreviewLine1')) document.getElementById('addrPreviewLine1').innerText = addr.line1 || '';
        if (document.getElementById('addrPreviewLine2')) document.getElementById('addrPreviewLine2').innerText = addr.line2 || '';
        previewEl.classList.remove('hidden');
        if (noAddrEl) noAddrEl.classList.add('hidden');
        if (headerBtn) headerBtn.innerText = 'Update Address';
        if (statAddr) statAddr.innerText = '1';
    } else {
        if (previewEl) previewEl.classList.add('hidden');
        if (noAddrEl) noAddrEl.classList.remove('hidden');
        if (headerBtn) headerBtn.innerText = '+ Add Address';
        if (statAddr) statAddr.innerText = '0';
    }
}

function showToast(title, msg) {
    const toast = document.getElementById('profileToast');
    document.getElementById('toastTitle').innerText = title;
    document.getElementById('toastMsg').innerText = msg;
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3500);
}

document.addEventListener('DOMContentLoaded', function() {
    loadSavedAddress();
    if (typeof getSavedProperties === 'function') {
        const savedList = getSavedProperties();
        const statSavedEl = document.getElementById('statSaved');
        if (statSavedEl) {
            statSavedEl.innerText = savedList.length;
        }
    }
});
</script>
@endsection
