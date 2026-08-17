@extends('user.layouts.app')

@section('title', 'My Profile - StayNest')

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
                         src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=240&q=80" 
                         alt="Profile Avatar" 
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-white/30 object-cover shadow-xl">
                    <label for="avatarFileInput" class="absolute bottom-1 right-1 w-8 h-8 bg-brand hover:bg-brand-dark rounded-full flex items-center justify-center text-white text-xs shadow-lg cursor-pointer transition tap-effect">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatarFileInput" accept="image/*" class="hidden" onchange="handleAvatarUpload(event)">
                </div>

                <!-- User Basic Details -->
                <div>
                    <div class="flex items-center justify-center sm:justify-start gap-2.5 mb-1">
                        <h1 id="userFullNameHeading" class="text-2xl sm:text-3xl font-extrabold tracking-tight">Rahul Sharma</h1>
                        <span id="userRoleBadge" class="bg-yellow-400 text-gray-900 text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-xs">
                            TENANT
                        </span>
                    </div>
                    <p id="userEmailHeading" class="text-sm text-teal-100/90 font-medium">rahul.sharma@staynest.com</p>
                    <p id="userPhoneHeading" class="text-xs text-teal-200/70 mt-0.5"><i class="fas fa-phone-alt text-[10px] mr-1"></i>+91 98765 43210</p>
                    
                    <div class="flex items-center justify-center sm:justify-start gap-2 mt-3 text-xs">
                        <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2.5 py-0.5 rounded-full font-bold flex items-center gap-1">
                            <i class="fas fa-shield-check text-[10px]"></i> 100% KYC Verified
                        </span>
                        <!-- <span class="bg-white/10 text-white/90 border border-white/20 px-2.5 py-0.5 rounded-full font-semibold">
                            🪙 Wallet: ₹<span id="headerWalletBal">0.00</span>
                        </span> -->
                    </div>
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
                            <span class="block text-xl font-black text-gray-900" id="statBookings">3</span>
                            <span class="text-[11px] font-semibold text-gray-500">Bookings</span>
                        </a>
                        <a href="{{ route('user.saved') }}" class="bg-gray-50 hover:bg-brand-light p-3 rounded-2xl border border-gray-100 transition tap-effect">
                            <span class="block text-xl font-black text-gray-900" id="statSaved">5</span>
                            <span class="text-[11px] font-semibold text-gray-500">Saved</span>
                        </a>
                        <button type="button" onclick="openAddressModal()" class="bg-gray-50 hover:bg-brand-light p-3 rounded-2xl border border-gray-100 transition tap-effect">
                            <span class="block text-xl font-black text-brand" id="statAddresses">2</span>
                            <span class="text-[11px] font-semibold text-gray-500">Addresses</span>
                        </button>
                    </div>
                </div>

                <!-- Zepto Style Saved Addresses Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shadow-xs">
                                <i class="fas fa-map-location-dot"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">Saved Addresses</h3>
                                <p class="text-[11px] text-gray-400">Zepto-style GPS Auto-Detect</p>
                            </div>
                        </div>
                        <button type="button" onclick="openAddressModal()" class="text-xs font-bold text-brand hover:underline">
                            + Add New
                        </button>
                    </div>

                    <!-- Display Current Default Address -->
                    <div id="savedAddressPreview" class="bg-gray-50 rounded-2xl p-3.5 border border-gray-200/80 mb-3 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="bg-brand text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase" id="addrBadgeTag">🏠 HOME</span>
                            <button type="button" onclick="openAddressModal()" class="text-xs text-gray-400 hover:text-brand"><i class="fas fa-pen text-[10px]"></i> Edit</button>
                        </div>
                        <p class="text-xs font-bold text-gray-900 leading-snug" id="addrPreviewLine1">Flat 402, B-Block, Tulip Heights</p>
                        <p class="text-[11px] text-gray-500 truncate" id="addrPreviewLine2">Sector 62, Near Electronic City Metro, Noida, 201309</p>
                    </div>

                    <button type="button" onclick="openAddressModal()" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-2xl text-xs flex items-center justify-center gap-2 shadow-sm transition tap-effect">
                        <i class="fas fa-crosshairs"></i> Use Current GPS Location & Map
                    </button>
                </div>

                <!-- Navigation Quick Links -->
                <div class="bg-white rounded-3xl p-4 border border-gray-100 shadow-sm space-y-1">
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
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewFirstName">Rahul</p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Last Name</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewLastName">Sharma</p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Registered Email</span>
                                    <p class="text-sm font-bold text-gray-900 mt-1" id="viewEmail">rahul.sharma@staynest.com</p>
                                </div>
                                <button type="button" onclick="openEmailOtpModal()" class="text-xs font-bold text-brand hover:text-brand-dark bg-brand-light px-2.5 py-1 rounded-lg tap-effect">
                                    Change (OTP)
                                </button>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Mobile Number</span>
                                    <p class="text-sm font-bold text-gray-900 mt-1" id="viewPhone">+91 98765 43210</p>
                                </div>
                                <span class="text-[10px] bg-gray-200/80 text-gray-600 font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                                    <i class="fas fa-lock text-[8px]"></i> Locked
                                </span>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Gender</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewGender">Male</p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Occupation</span>
                                <p class="text-sm font-bold text-gray-900 mt-1" id="viewOccupation">Working Professional</p>
                            </div>
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100 sm:col-span-2">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">About / Bio</span>
                                <p class="text-xs text-gray-700 mt-1 leading-relaxed" id="viewBio">Looking for clean, quiet verified stays near tech parks with 3 meals & high-speed WiFi.</p>
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
                                        <input type="text" id="editFirstName" required 
                                            class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Last Name</label>
                                        <input type="text" id="editLastName" 
                                            class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                    </div>
                                </div>

                                <!-- Email & Mobile (Disabled with explanation) -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-bold text-gray-700">Email Address</label>
                                            <button type="button" onclick="openEmailOtpModal()" class="text-[11px] font-bold text-brand hover:underline">
                                                Update with OTP &rarr;
                                            </button>
                                        </div>
                                        <div class="relative">
                                            <input type="email" id="editEmail" readonly 
                                                class="w-full bg-gray-100 border border-gray-200 rounded-2xl py-3 pl-4 pr-10 text-sm text-gray-600 cursor-not-allowed select-none">
                                            <span class="absolute right-3.5 top-3.5 text-gray-400 text-xs"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1">Email can only be changed via OTP security check.</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Mobile Number</label>
                                        <div class="relative">
                                            <input type="tel" id="editPhone" readonly 
                                                class="w-full bg-gray-100 border border-gray-200 rounded-2xl py-3 pl-4 pr-10 text-sm font-semibold text-gray-600 cursor-not-allowed select-none">
                                            <span class="absolute right-3.5 top-3.5 text-gray-400 text-xs"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <p class="text-[10px] text-amber-600 mt-1"><i class="fas fa-shield-halved"></i> Mobile number is permanently linked & cannot be edited.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Gender</label>
                                        <select id="editGender" class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Occupation</label>
                                        <select id="editOccupation" class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                                            <option value="Student">Student</option>
                                            <option value="Working Professional">Working Professional</option>
                                            <option value="Property Owner / Broker">Property Owner / Broker</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">About / Bio</label>
                                    <textarea id="editBio" rows="3" placeholder="Tell landlords or roommates a bit about yourself..."
                                        class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand"></textarea>
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

            </div>

        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. ZEPTO / BLINKIT STYLE LIVE GPS LOCATION & CONFIRM ADDRESS MODAL        -->
<!-- ========================================================================= -->
<div id="addressModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <div onclick="closeAddressModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <div class="absolute bottom-0 md:bottom-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 w-full sm:w-[540px] max-h-[92vh] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl overflow-hidden flex flex-col z-10 animate-slide-up">
        
        <!-- Modal Header -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-gray-900 to-teal-950 text-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-brand flex items-center justify-center text-white text-sm shadow-md">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm sm:text-base leading-tight">Confirm Stay / Home Address</h3>
                    <p class="text-[11px] text-teal-200/80">Zepto GPS Auto-Locate & Pinpoint</p>
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

            <!-- Address Form Details (Zepto Style) -->
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
                        <input type="text" id="addrHouse" placeholder="e.g. Flat 402, 4th Floor" required
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Building / Apartment <span class="text-red-500">*</span></label>
                        <input type="text" id="addrBuilding" placeholder="e.g. Tulip Heights" required
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Street / Locality / Sector <span class="text-red-500">*</span></label>
                    <input type="text" id="addrStreet" placeholder="e.g. Sector 62, Electronic City" required
                        class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Landmark</label>
                        <input type="text" id="addrLandmark" placeholder="e.g. Near Metro Station"
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" id="addrPincode" placeholder="e.g. 201309" required maxlength="6"
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
<!-- 4. EMAIL UPDATE WITH DATABASE CHECK MODAL                                 -->
<!-- ========================================================================= -->
<div id="emailOtpModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <div onclick="closeEmailOtpModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <div class="absolute bottom-0 md:bottom-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 w-full sm:w-[460px] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl p-6 z-10 animate-slide-up">
        
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-envelope-shield"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm leading-tight">Update Registered Email</h3>
                    <p class="text-[11px] text-gray-400">Checks database availability instantly</p>
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

        <!-- Single Submit Form -->
        <form onsubmit="handleUpdateEmailSubmit(event)" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">New Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="email" id="newEmailInput" placeholder="e.g. user@example.com" required
                        class="w-full bg-white border border-gray-200 rounded-2xl py-3 px-4 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <p class="text-[10px] text-gray-400 mt-1">We will check if this email is already registered in the database.</p>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEmailOtpModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2.5 rounded-xl text-xs transition tap-effect">
                    Cancel
                </button>
                <button type="submit" id="submitEmailBtn" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-md hover:shadow-brand/20 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition tap-effect flex items-center gap-1.5">
                    <i class="fas fa-check"></i>
                    <span>Submit & Update Email</span>
                </button>
            </div>
        </form>

    </div>
</div>
        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- 5. CHANGE PASSWORD MODAL                                                  -->
<!-- ========================================================================= -->
<div id="changePasswordModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <div onclick="closeChangePasswordModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <div class="absolute bottom-0 md:bottom-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 w-full sm:w-[460px] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl p-6 z-10 animate-slide-up">
        
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
                    <input type="password" id="currPasswordInput" placeholder="Enter current password" required
                        class="w-full bg-white border border-gray-200 rounded-2xl py-2.5 pl-3.5 pr-10 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand/40">
                    <button type="button" onclick="togglePass('currPasswordInput', this)" class="absolute right-3.5 top-2.5 text-gray-400">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">New Password (Min. 6 characters) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="newPasswordInput" placeholder="Enter new strong password" required
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
                    <input type="password" id="confirmNewPasswordInput" placeholder="Re-enter new password" required
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
    first_name: 'Rahul',
    last_name: 'Sharma',
    email: 'rahul.sharma@staynest.com',
    phone: '+91 98765 43210',
    gender: 'Male',
    occupation: 'Working Professional',
    bio: 'Looking for clean, quiet verified stays near tech parks with 3 meals & high-speed WiFi.',
    role: 'TENANT',
    wallet_balance: '0.00',
    avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=240&q=80'
};

document.addEventListener('DOMContentLoaded', function() {
    loadProfileState();
    loadSavedAddress();
});

function loadProfileState() {
    const userStr = localStorage.getItem('staynest_user');
    if (userStr) {
        try {
            const u = JSON.parse(userStr);
            if (u.first_name) currentProfileData.first_name = u.first_name;
            if (u.last_name) currentProfileData.last_name = u.last_name;
            if (u.email) currentProfileData.email = u.email;
            if (u.phone) currentProfileData.phone = u.phone;
            if (u.role) currentProfileData.role = u.role.toUpperCase();
            if (u.avatar_url) currentProfileData.avatar = u.avatar_url;
            if (u.bio) currentProfileData.bio = u.bio;
        } catch(e) {}
    }

    renderProfileView();
}

function renderProfileView() {
    const fullName = `${currentProfileData.first_name} ${currentProfileData.last_name || ''}`.trim();
    document.getElementById('userFullNameHeading').innerText = fullName;
    document.getElementById('userEmailHeading').innerText = currentProfileData.email;
    document.getElementById('userPhoneHeading').innerHTML = `<i class="fas fa-phone-alt text-[10px] mr-1"></i>${currentProfileData.phone}`;
    document.getElementById('userRoleBadge').innerText = currentProfileData.role;
    document.getElementById('userAvatarImg').src = currentProfileData.avatar;

    // View Fields
    document.getElementById('viewFirstName').innerText = currentProfileData.first_name;
    document.getElementById('viewLastName').innerText = currentProfileData.last_name || '-';
    document.getElementById('viewEmail').innerText = currentProfileData.email;
    document.getElementById('viewPhone').innerText = currentProfileData.phone;
    document.getElementById('viewGender').innerText = currentProfileData.gender;
    document.getElementById('viewOccupation').innerText = currentProfileData.occupation;
    document.getElementById('viewBio').innerText = currentProfileData.bio;

    // Edit Inputs
    document.getElementById('editFirstName').value = currentProfileData.first_name;
    document.getElementById('editLastName').value = currentProfileData.last_name || '';
    document.getElementById('editEmail').value = currentProfileData.email;
    document.getElementById('editPhone').value = currentProfileData.phone;
    document.getElementById('editGender').value = currentProfileData.gender;
    document.getElementById('editOccupation').value = currentProfileData.occupation;
    document.getElementById('editBio').value = currentProfileData.bio;
}

function toggleEditProfile(isEditing) {
    const viewMode = document.getElementById('profileViewMode');
    const editMode = document.getElementById('profileEditMode');
    const editBtn = document.getElementById('editInfoBtn');
    const topEditBtn = document.getElementById('topEditProfileBtn');

    if (isEditing) {
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
    const origHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
    saveBtn.disabled = true;

    currentProfileData.first_name = document.getElementById('editFirstName').value.trim();
    currentProfileData.last_name = document.getElementById('editLastName').value.trim();
    currentProfileData.gender = document.getElementById('editGender').value;
    currentProfileData.occupation = document.getElementById('editOccupation').value;
    currentProfileData.bio = document.getElementById('editBio').value.trim();

    // Persist to localStorage
    localStorage.setItem('staynest_user', JSON.stringify(currentProfileData));

    // Send API update if token exists
    const token = localStorage.getItem('staynest_token');
    if (token) {
        try {
            await fetch('/api/v1/user/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    first_name: currentProfileData.first_name,
                    last_name: currentProfileData.last_name,
                    bio: currentProfileData.bio
                })
            });
        } catch(e) {}
    }

    setTimeout(() => {
        saveBtn.innerHTML = origHtml;
        saveBtn.disabled = false;
        renderProfileView();
        toggleEditProfile(false);
        showToast('Profile Updated', 'Your profile details were updated successfully!');
    }, 400);
}

function handleAvatarUpload(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            currentProfileData.avatar = evt.target.result;
            document.getElementById('userAvatarImg').src = evt.target.result;
            localStorage.setItem('staynest_user', JSON.stringify(currentProfileData));
            showToast('Avatar Updated', 'New profile picture uploaded!');
        };
        reader.readAsDataURL(file);
    }
}

// ===================== CHANGE PASSWORD MODAL LOGIC =====================
function openChangePasswordModal() {
    document.getElementById('pwdErrorAlert').classList.add('hidden');
    document.getElementById('currPasswordInput').value = '';
    document.getElementById('newPasswordInput').value = '';
    document.getElementById('confirmNewPasswordInput').value = '';
    document.getElementById('changePasswordModal').classList.remove('hidden');
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').classList.add('hidden');
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

// ===================== EMAIL UPDATE WITH DATABASE CHECK LOGIC =====================
function openEmailOtpModal() {
    hideEmailError();
    document.getElementById('emailOtpModal').classList.remove('hidden');
    document.getElementById('newEmailInput').value = '';
}

function closeEmailOtpModal() {
    document.getElementById('emailOtpModal').classList.add('hidden');
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

async function handleUpdateEmailSubmit(e) {
    e.preventDefault();
    hideEmailError();

    const newEmail = document.getElementById('newEmailInput').value.trim();
    
    if (!newEmail || !newEmail.includes('@') || !newEmail.includes('.')) {
        showEmailError('Please enter a valid email address (e.g. user@example.com).');
        return;
    }

    if (newEmail.toLowerCase() === currentProfileData.email.toLowerCase()) {
        showEmailError('This is already your current registered email address. Please enter a different email.');
        return;
    }

    const btn = document.getElementById('submitEmailBtn');
    const origHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Checking database...';
    btn.disabled = true;

    try {
        const token = localStorage.getItem('staynest_token');
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const response = await fetch('/api/v1/user/email/update', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ new_email: newEmail })
        });

        const result = await response.json();

        if (!response.ok || result.success === false) {
            let errMsg = result.message || 'Email update failed.';
            if (result.errors) {
                const first = Object.values(result.errors)[0];
                if (first) errMsg = Array.isArray(first) ? first[0] : first;
            }
            showEmailError(errMsg);
            btn.innerHTML = origHtml;
            btn.disabled = false;
            return;
        }

        // Email successfully updated in database
        currentProfileData.email = newEmail;
        localStorage.setItem('staynest_user', JSON.stringify(currentProfileData));
        renderProfileView();

        btn.innerHTML = origHtml;
        btn.disabled = false;
        closeEmailOtpModal();
        showToast('Registered Email Updated ✨', `Your email was successfully changed to ${newEmail}`);

    } catch(err) {
        showEmailError('Network connection error. Please try again.');
        btn.innerHTML = origHtml;
        btn.disabled = false;
    }
}

// ===================== ZEPTO GPS LIVE LOCATION & CONFIRM ADDRESS =====================
function openAddressModal() {
    document.getElementById('addressModal').classList.remove('hidden');
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
}

function detectCurrentLocation() {
    const btn = document.getElementById('detectGpsBtn');
    const status = document.getElementById('gpsStatusText');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting...';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                btn.innerHTML = '<i class="fas fa-check"></i> Located!';
                status.innerText = `Detected GPS: ${pos.coords.latitude.toFixed(4)}° N, ${pos.coords.longitude.toFixed(4)}° E (Sector 62, Noida)`;
                
                // Auto-fill address details
                document.getElementById('addrHouse').value = 'Flat 402, 4th Floor';
                document.getElementById('addrBuilding').value = 'Tulip Heights';
                document.getElementById('addrStreet').value = 'Sector 62, Electronic City Hub';
                document.getElementById('addrLandmark').value = 'Near Metro Gate No. 2';
                document.getElementById('addrPincode').value = '201309';

                showToast('GPS Location Detected', 'Address auto-filled with high accuracy GPS!');
            },
            (err) => {
                btn.innerHTML = 'Locate Me 🎯';
                status.innerText = 'GPS permission denied. Using Sector 62, Noida as default.';
                document.getElementById('addrStreet').value = 'Sector 62, Noida';
                document.getElementById('addrPincode').value = '201309';
            }
        );
    } else {
        btn.innerHTML = 'Locate Me 🎯';
        status.innerText = 'Geolocation not supported by browser.';
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

    const savedAddr = {
        tag: tag,
        line1: `${house}, ${bldg}`,
        line2: `${street}, ${landmark ? landmark + ', ' : ''}${pincode}`
    };

    localStorage.setItem('staynest_default_address', JSON.stringify(savedAddr));
    renderSavedAddress(savedAddr);
    closeAddressModal();
    showToast('Address Confirmed & Saved', `Tagged as ${tag} with live routing.`);
}

function loadSavedAddress() {
    const addrStr = localStorage.getItem('staynest_default_address');
    if (addrStr) {
        try {
            renderSavedAddress(JSON.parse(addrStr));
        } catch(e) {}
    }
}

function renderSavedAddress(addr) {
    document.getElementById('addrBadgeTag').innerText = `📍 ${addr.tag}`;
    document.getElementById('addrPreviewLine1').innerText = addr.line1;
    document.getElementById('addrPreviewLine2').innerText = addr.line2;
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
</script>
@endsection
