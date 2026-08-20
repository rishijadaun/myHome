@extends('user.layouts.app')

@section('title', 'List Your PG & Property Online - 0% Commission | StayNest')

@push('styles')
<!-- SEO Meta Tags -->
<meta name="description" content="List your PG, Hostel, Flat or Property for rent on StayNest for FREE. Reach 50,000+ verified students and working professionals with 0% brokerage.">
<meta name="keywords" content="list PG online, list property for rent, PG owner registration, zero brokerage property listing, list hostel, list flat">
<meta property="og:title" content="List Your PG & Property Online - Zero Commission | StayNest">
<meta property="og:description" content="Get verified tenants fast. List your PG, Co-living, Flat or commercial space on StayNest. Free listing & instant admin verification.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/list-property') }}">
<meta property="og:image" content="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80">
<meta name="twitter:card" content="summary_large_image">

<!-- Schema.org JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "RealEstateListing",
  "name": "StayNest Property & PG Listing Service",
  "description": "Platform for landlords and PG owners to list paying guest accommodations, flats, and co-living spaces with zero brokerage.",
  "url": "{{ url('/list-property') }}",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "INR",
    "description": "Free property listing with instant admin verification"
  }
}
</script>

<!-- Leaflet Map CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    .type-pill.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border-color: #059669;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
    }
    .amenity-chip input:checked + div {
        border-color: #10b981;
        background-color: #f0fdf9;
        color: #047857;
    }
    .input-error {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    .error-msg {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    #propertyMapPicker {
        height: 280px;
        width: 100%;
        border-radius: 1rem;
        z-index: 10;
    }
    .custom-map-marker {
        background: #10b981;
        border: 3px solid #ffffff;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
    }
</style>
@endpush

@section('content')
@php
    $authUser = Auth::user();
    $isLoggedIn = !empty($authUser);
    $authName = $authUser?->profile?->first_name 
        ? ($authUser->profile->first_name . ' ' . ($authUser->profile->last_name ?? '')) 
        : ($authUser?->name ?? '');
    $authPhone = $authUser?->phone ?? '';
    $authEmail = $authUser?->email ?? '';
@endphp

<!--   Top Notification Toast -->
<div id="zeptoToast" class="fixed top-6 right-4 md:right-8 z-50 hidden transition-all duration-300 transform translate-y-2">
    <div class="bg-gray-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-white/20">
        <div id="zeptoToastIcon" class="w-8 h-8 rounded-xl bg-brand flex items-center justify-center text-white text-xs shadow-sm">
            <i class="fas fa-location-dot"></i>
        </div>
        <div>
            <p id="zeptoToastTitle" class="text-xs font-bold leading-tight">GPS Location Detected</p>
            <p id="zeptoToastMsg" class="text-[11px] text-gray-300">Address auto-filled with 1m accuracy!</p>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gray-50/60 pb-10 pt-10 md:pt-5">
    
    <!-- Hero Banner Section (Auto-hides after 40s, shown once per day) -->
    <div id="listPropertyHeroBanner" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 transition-all duration-700 ease-in-out">
        <div class="bg-gradient-to-r from-slate-900 via-brand-dark to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden">
            <!-- Dismiss button -->
            <button type="button" onclick="dismissHeroBanner()" title="Close banner" class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition">
                <i class="fas fa-times text-sm"></i>
            </button>

            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-brand/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand/20 border border-brand/30 text-brand-light text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-bolt text-yellow-400"></i> 100% Zero Brokerage Listing
                    </span>
                    <span id="heroBannerCountdownBadge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10 text-gray-300 text-[11px] font-medium">
                        <i class="fas fa-clock text-[10px] text-brand-light"></i> <span id="bannerTimerText">Hides in 40s</span>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight mb-3">
                    List Your Property & PG on <span class="text-brand-light">StayNest</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-300 mb-6">
                    Reach over 50,000+ verified students and working professionals. Currently optimized for PGs & Hostels with full support for Flats, Apartments, and Commercial listings.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-white/10 text-xs sm:text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-brand"></i>
                        <span>Zero Listing Fees</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-brand"></i>
                        <span>Verified In 24h</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-brand"></i>
                        <span>Direct Tenant Leads</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-headset text-brand"></i>
                        <span>24/7 Owner Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Stepper Navigation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-8">
            <div class="flex items-center justify-between max-w-4xl mx-auto relative">
                <!-- Progress Line -->
                <div class="absolute left-0 top-1/3 -translate-y-1/2 w-full h-1 bg-gray-100 z-0">
                    <div id="progress-bar-fill" class="h-full bg-brand transition-all duration-500" style="width: 25%;"></div>
                </div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(1)">
                    <div id="step-node-1" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-brand text-white shadow-md transition-all">
                        1
                    </div>
                    <span class="text-xs font-semibold text-gray-800 mt-2 hidden sm:block">Listing Type & Location</span>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(2)">
                    <div id="step-node-2" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-600 transition-all">
                        2
                    </div>
                    <span class="text-xs font-semibold text-gray-500 mt-2 hidden sm:block">Rooms & Amenities</span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(3)">
                    <div id="step-node-3" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-600 transition-all">
                        3
                    </div>
                    <span class="text-xs font-semibold text-gray-500 mt-2 hidden sm:block">Pricing & Photos</span>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(4)">
                    <div id="step-node-4" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-600 transition-all">
                        4
                    </div>
                    <span class="text-xs font-semibold text-gray-500 mt-2 hidden sm:block">Owner & Submit</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Form Area (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <form id="propertyListingForm" novalidate onsubmit="event.preventDefault(); handleListingSubmit();">
                    
                    <!-- Hidden Lat/Lng values -->
                    <input type="hidden" id="propLatitude" name="latitude">
                    <input type="hidden" id="propLongitude" name="longitude">

                    <!-- ================= STEP 1: LISTING TYPE & BASIC INFO ================= -->
                    <div id="step-pane-1" class="step-pane space-y-6">
                        
                        <!-- Listing Type Selector -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Select Listing Type *</h2>
                                    <p class="text-xs sm:text-sm text-gray-500">Pick what you are listing. Fields adapt automatically.</p>
                                </div>
                                <!-- <span class="bg-brand/10 text-brand text-xs font-bold px-3 py-1 rounded-full">Dynamic Filter</span> -->
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="listingTypeContainer">
                                <label class="cursor-pointer">
                                    <input type="radio" name="listing_type" value="pg-hostel" class="hidden" checked onchange="handleTypeChange('pg-hostel')">
                                    <div class="type-pill active border-2 border-gray-200 rounded-2xl p-4 text-center transition-all hover:border-brand/40">
                                        <i class="fas fa-bed text-xl mb-2 block"></i>
                                        <div class="font-bold text-xs sm:text-sm">PG / Hostel</div>
                                        <div class="text-[10px] opacity-80 mt-0.5">Students & Working</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="listing_type" value="co-living" class="hidden" onchange="handleTypeChange('co-living')">
                                    <div class="type-pill border-2 border-gray-200 rounded-2xl p-4 text-center transition-all hover:border-brand/40">
                                        <i class="fas fa-users text-xl mb-2 block"></i>
                                        <div class="font-bold text-xs sm:text-sm">Co-Living</div>
                                        <div class="text-[10px] opacity-80 mt-0.5">Managed Stays</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="listing_type" value="flat-apartment" class="hidden" onchange="handleTypeChange('flat-apartment')">
                                    <div class="type-pill border-2 border-gray-200 rounded-2xl p-4 text-center transition-all hover:border-brand/40">
                                        <i class="fas fa-building text-xl mb-2 block"></i>
                                        <div class="font-bold text-xs sm:text-sm">Flat / House</div>
                                        <div class="text-[10px] opacity-80 mt-0.5">1/2/3 BHK Rentals</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="listing_type" value="commercial" class="hidden" onchange="handleTypeChange('commercial')">
                                    <div class="type-pill border-2 border-gray-200 rounded-2xl p-4 text-center transition-all hover:border-brand/40">
                                        <i class="fas fa-store text-xl mb-2 block"></i>
                                        <div class="font-bold text-xs sm:text-sm">Commercial</div>
                                        <div class="text-[10px] opacity-80 mt-0.5">Shops / Office</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Basic Information & Zepto-Style Location -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-map-location-dot text-brand"></i> Property Location
                                </h2>
                                <button type="button" onclick="openLocationModal(false)" class="text-xs font-bold text-brand hover:underline flex items-center gap-1.5 bg-brand-light px-2.5 py-1 rounded-lg">
                                    <i class="fas fa-map text-brand"></i> Open Map
                                </button>
                            </div>

                            <!-- Zepto / Blinkit Style Selected Location Summary Card (as in Location & Profile) -->
                            <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 rounded-2xl p-4 sm:p-5 border border-emerald-200/80 mb-6 shadow-xs relative overflow-hidden">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="bg-brand text-white text-[9px] font-black px-2.5 py-0.5 rounded-full uppercase" id="addrBadgeTag">📍 VERIFIED HOME ADDRESS</span>
                                    <button type="button" onclick="openLocationModal(false)" class="text-xs font-bold text-brand hover:text-brand-dark flex items-center gap-1 bg-white px-2.5 py-1 rounded-lg border border-brand/30 shadow-2xs">
                                        <i class="fas fa-pen text-[10px]"></i> Edit Map
                                    </button>
                                </div>
                                <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug" id="locSummaryLine1">Sector 62, Noida</p>
                                <p class="text-xs text-gray-600 mt-0.5 truncate" id="locSummaryLine2">Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309</p>
                                
                                <div class="mt-4 pt-3 border-t border-emerald-200/60 flex flex-col sm:flex-row items-center justify-between gap-2.5">
                                    <button type="button" id="useCurrentGpsBtn" onclick="useCurrentGpsDirect(this)" class="w-full sm:flex-1 bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-slate-900 text-white font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center gap-2 shadow-md shadow-brand/20 transition tap-effect">
                                        <i class="fas fa-crosshairs" id="useCurrentGpsIcon"></i>
                                        <span id="useCurrentGpsText">Use Current GPS Location</span>
                                    </button>
                                    <button type="button" onclick="useVerifiedHomeAddressDirect()" class="w-full sm:w-auto bg-white border border-brand/40 hover:bg-brand-light text-brand font-extrabold py-2.5 px-3.5 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-2xs transition tap-effect whitespace-nowrap">
                                        <i class="fas fa-house-user"></i>
                                        <span>Verified Home (Sector 62)</span>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Property / PG Title *</label>
                                    <input type="text" id="propName" name="name" required placeholder="e.g. PG or Property Name" 
                                           oninput="updateLivePreview(); clearError(this); checkClientModeration();" 
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                    <div class="error-msg hidden" id="err-propName">Please enter a valid property title (at least 3 characters).</div>
                                </div>

                                <!-- Dynamic PG Gender Field -->
                                <div id="pgGenderField">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Gender Preference *</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="gender_preference" value="boys" class="hidden peer" onchange="updateLivePreview()">
                                            <div class="peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 py-3 text-center border-2 border-gray-200 rounded-xl text-xs sm:text-sm font-semibold transition hover:bg-gray-50">
                                                <i class="fas fa-mars mr-1"></i> Boys Only
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="gender_preference" value="girls" class="hidden peer" onchange="updateLivePreview()">
                                            <div class="peer-checked:bg-pink-50 peer-checked:border-pink-500 peer-checked:text-pink-700 py-3 text-center border-2 border-gray-200 rounded-xl text-xs sm:text-sm font-semibold transition hover:bg-gray-50">
                                                <i class="fas fa-venus mr-1"></i> Girls Only
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="gender_preference" value="co-ed" class="hidden peer" checked onchange="updateLivePreview()">
                                            <div class="peer-checked:bg-purple-50 peer-checked:border-purple-500 peer-checked:text-purple-700 py-3 text-center border-2 border-gray-200 rounded-xl text-xs sm:text-sm font-semibold transition hover:bg-gray-50">
                                                <i class="fas fa-venus-mars mr-1"></i> Co-ed (Both)
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Form Address Details -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                            City * <span class="text-[10px] text-brand font-bold">(Auto-Detected)</span>
                                        </label>
                                        <input type="text" id="propCity" name="city" required placeholder="e.g. Noida, Bangalore, Delhi" 
                                               oninput="syncMainToModal(); updateLivePreview(); clearError(this); checkClientModeration();" 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                        <div class="error-msg hidden" id="err-propCity">Please enter the city name.</div>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                            Area / Locality * <span class="text-[10px] text-brand font-bold">(Auto-Detected)</span>
                                        </label>
                                        <input type="text" id="propArea" name="area" placeholder="e.g. Sector 62 / Indiranagar" 
                                               oninput="syncMainToModal(); updateLivePreview(); clearError(this); checkClientModeration();" 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                        <div class="error-msg hidden" id="err-propArea">Please specify the area or sector.</div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                        Full Street Address * <span class="text-[10px] text-brand font-bold">(Auto-Detected)</span>
                                    </label>
                                    <textarea id="propAddress" name="address" required rows="2" placeholder="Building name, flat/plot no, street, near landmark" 
                                              oninput="syncMainToModal(); updateLivePreview(); clearError(this); checkClientModeration();" 
                                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition"></textarea>
                                    <div class="error-msg hidden" id="err-propAddress">Please enter full address for tenants.</div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Pincode</label>
                                        <input type="text" id="propPincode" name="pincode" placeholder="e.g. 201301" maxlength="6"
                                               onkeydown="return event.key !== '-' && event.key !== 'e'"
                                               oninput="syncMainToModal();"
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Prominent Landmark</label>
                                        <input type="text" id="propLandmark" name="landmark" placeholder="e.g. Near Metro Station Gate 2" 
                                               oninput="syncMainToModal(); checkClientModeration();" 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" onclick="goToStep(2)" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
                                Continue to Rooms & Amenities <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ================= STEP 2: ROOMS & AMENITIES ================= -->
                    <div id="step-pane-2" class="step-pane hidden space-y-6">
                        
                        <!-- Dynamic PG Sharing Options -->
                        <div id="pgRoomConfigs" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-door-open text-brand"></i> PG Room Sharing Types & Capacity
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 mb-6">Select available sharing options in your PG / Hostel (All amounts must be positive).</p>

                            <div class="space-y-3">
                                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl hover:border-brand/40 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="room_single" checked class="w-5 h-5 text-brand rounded focus:ring-brand">
                                        <div>
                                            <div class="font-bold text-sm text-gray-900">Single Occupancy (Private Room)</div>
                                            <div class="text-xs text-gray-500">1 person per room</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400">₹</span>
                                        <input type="number" min="0" onkeydown="preventNegative(event)" oninput="sanitizePositive(this)" placeholder="Rent (₹)" value="12000" class="w-28 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand">
                                    </div>
                                </label>

                                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl hover:border-brand/40 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="room_double" checked class="w-5 h-5 text-brand rounded focus:ring-brand">
                                        <div>
                                            <div class="font-bold text-sm text-gray-900">Double Sharing Room</div>
                                            <div class="text-xs text-gray-500">2 beds per room</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400">₹</span>
                                        <input type="number" min="0" onkeydown="preventNegative(event)" oninput="sanitizePositive(this)" placeholder="Rent (₹)" value="8500" class="w-28 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand">
                                    </div>
                                </label>

                                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-2xl hover:border-brand/40 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="room_triple" class="w-5 h-5 text-brand rounded focus:ring-brand">
                                        <div>
                                            <div class="font-bold text-sm text-gray-900">Triple Sharing Room</div>
                                            <div class="text-xs text-gray-500">3 beds per room</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400">₹</span>
                                        <input type="number" min="0" onkeydown="preventNegative(event)" oninput="sanitizePositive(this)" placeholder="Rent (₹)" value="6500" class="w-28 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand">
                                    </div>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Total Bed Capacity *</label>
                                    <input type="number" id="propTotalBeds" name="total_beds" min="1" onkeydown="preventNegative(event)" oninput="sanitizePositive(this)" value="20" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Currently Available Beds</label>
                                    <input type="number" id="propAvailableBeds" name="available_beds" min="0" onkeydown="preventNegative(event)" oninput="sanitizePositive(this)" value="6" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Flat / House Configuration -->
                        <div id="flatConfigs" class="hidden bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-home text-brand"></i> Apartment / Flat Configuration
                            </h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">BHK Type</label>
                                    <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                                        <option>1 BHK</option>
                                        <option selected>2 BHK</option>
                                        <option>3 BHK</option>
                                        <option>4+ BHK / Studio</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Furnishing</label>
                                    <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                                        <option>Fully Furnished</option>
                                        <option selected>Semi Furnished</option>
                                        <option>Unfurnished</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Carpet Area (sq ft)</label>
                                    <input type="number" min="0" onkeydown="preventNegative(event)" oninput="sanitizePositive(this)" placeholder="e.g. 1100" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Amenities Selector -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-concierge-bell text-brand"></i> Amenities & Facilities
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 mb-6">Select all amenities included at your property.</p>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="amenitiesGrid">
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="wifi" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-wifi text-brand"></i>
                                        <span class="text-xs sm:text-sm font-semibold">High-Speed WiFi</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="ac" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-snowflake text-cyan-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Air Conditioning</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="food" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-utensils text-orange-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Meals / Food</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="laundry" class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-tshirt text-indigo-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Laundry Facility</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="power-backup" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-bolt text-yellow-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Power Backup</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="cctv" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-shield-alt text-emerald-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">CCTV & Security</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="housekeeping" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-broom text-teal-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Daily Housekeeping</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="ro-water" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-tint text-sky-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">RO Drinking Water</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="attached-washroom" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-bath text-purple-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Attached Washroom</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(1)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3.5 rounded-xl transition">
                                <i class="fas fa-arrow-left text-xs mr-1"></i> Back
                            </button>
                            <button type="button" onclick="goToStep(3)" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
                                Continue to Pricing & Photos <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ================= STEP 3: PRICING, POLICIES & PHOTOS ================= -->
                    <div id="step-pane-3" class="step-pane hidden space-y-6">
                        
                        <!-- AI Content Moderation Alert Banner -->
                        <div id="moderationAlertBox" class="hidden bg-red-50 border-2 border-red-200 rounded-2xl p-4 sm:p-5 flex items-start gap-3.5 shadow-sm animate-shake">
                            <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fas fa-shield-virus"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-red-900 flex items-center gap-2">
                                    <span>AI Safety & Content Moderation Flag</span>
                                    <span class="bg-red-200 text-red-800 text-[10px] font-black uppercase px-2 py-0.5 rounded-full">Blocked</span>
                                </h4>
                                <p class="text-xs text-red-700 mt-1 leading-relaxed" id="moderationAlertMsg">
                                    Prohibited content detected. Please remove offensive or non-compliant text to continue.
                                </p>
                            </div>
                        </div>

                        <!-- Pricing Details -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-rupee-sign text-brand"></i> Pricing & Deposits
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Monthly Starting Rent (₹) * <span class="text-[10px] text-brand font-bold">(Min ₹500)</span></label>
                                    <input type="number" id="propRent" name="monthly_rent" required min="500" placeholder="e.g. 6500" 
                                           onkeydown="preventNegative(event)" oninput="sanitizePositive(this); updateLivePreview(); clearError(this);" 
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                    <div class="error-msg hidden" id="err-propRent">Monthly starting rent cannot be less than ₹500.</div>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Security Deposit (₹) <span class="text-[10px] text-gray-400 font-normal">(Optional)</span></label>
                                    <input type="number" id="propDeposit" name="security_deposit" min="0" placeholder="e.g. 5000" 
                                           onkeydown="preventNegative(event)" oninput="sanitizePositive(this); clearError(this);" 
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Maintenance (₹ / mo)</label>
                                    <input type="number" id="propMaintenance" name="maintenance_charges" min="0" placeholder="e.g. 0" 
                                           onkeydown="preventNegative(event)" oninput="sanitizePositive(this); clearError(this);" 
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Notice Period</label>
                                    <select name="notice_period_days" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        <option value="0">No Notice Period</option>
                                        <option value="15">15 Days</option>
                                        <option value="30" selected>30 Days (Standard)</option>
                                        <option value="60">60 Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Multiple Photos Uploader -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-camera text-brand"></i> Property Photos
                                    </h2>
                                    <p class="text-xs text-gray-500 mt-0.5">Select multiple photos at once. Click any photo to set it as Cover.</p>
                                </div>
                                <span id="photoCountBadge" class="bg-brand/10 text-brand text-xs font-bold px-3 py-1 rounded-full">
                                    0 Photos Added
                                </span>
                            </div>

                            <!-- Dropzone Box (Allows Multiple file selection) -->
                            <div onclick="document.getElementById('photoInput').click()" 
                                 ondragover="event.preventDefault(); this.classList.add('border-brand', 'bg-brand-50/20');"
                                 ondragleave="this.classList.remove('border-brand', 'bg-brand-50/20');"
                                 ondrop="event.preventDefault(); this.classList.remove('border-brand', 'bg-brand-50/20'); handlePhotoDrop(event);"
                                 class="border-2 border-dashed border-gray-300 hover:border-brand bg-gray-50/60 hover:bg-brand-50/20 rounded-2xl p-8 text-center cursor-pointer transition mb-4">
                                <input type="file" id="photoInput" multiple accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden" onchange="handlePhotoUpload(this)">
                                <div class="w-14 h-14 bg-brand/10 text-brand rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="text-sm font-bold text-gray-800 mb-1">Click to select multiple photos or drag & drop</div>
                                <div class="text-xs text-gray-500">Supports JPG, PNG, WEBP (Add as many photos as you want, max 5MB each)</div>
                            </div>

                            <!-- Or Add Photo via URL -->
                            <div class="flex gap-2 mb-4">
                                <input type="url" id="photoUrlInput" placeholder="Or paste an image URL (e.g. https://...)" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-brand">
                                <button type="button" onclick="addPhotoUrl()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-xs transition">
                                    Add URL
                                </button>
                            </div>

                            <!-- Photo Previews Grid with Delete & Set Cover -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="photoPreviewGrid">
                                <!-- Rendered dynamically by JavaScript -->
                            </div>
                        </div>

                        <!-- Description & House Rules (Both Required with AI Content Moderation) -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-align-left text-brand"></i> Property Description *
                                    </h2>
                                    <span class="text-[11px] text-gray-400">Min 20 characters</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-3">Provide a clear description of your property, amenities, nearby hotspots, and facilities.</p>
                                <textarea id="propDescription" name="description" required rows="3" 
                                          oninput="clearError(this); checkClientModeration();" 
                                          placeholder="e.g. Luxurious PG near metro station with daily housekeeping, 3-times hygienic meals, high-speed WiFi, and 24/7 security..." 
                                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition"></textarea>
                                <div class="error-msg hidden" id="err-propDescription">Please enter a detailed description (at least 20 characters).</div>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-gavel text-brand"></i> House Rules & Guidelines *
                                    </h2>
                                    <span class="text-[11px] text-gray-400">Required</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-3">Set clear expectations for tenants. Click presets below to add quickly.</p>
                                
                                <!-- Preset Quick Chips -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <button type="button" onclick="appendRule('No loud music after 10:00 PM')" class="text-xs font-semibold bg-gray-100 hover:bg-brand-light hover:text-brand border border-gray-200 px-3 py-1 rounded-lg transition">
                                        + No loud music (10 PM)
                                    </button>
                                    <button type="button" onclick="appendRule('Gates close at 11:00 PM')" class="text-xs font-semibold bg-gray-100 hover:bg-brand-light hover:text-brand border border-gray-200 px-3 py-1 rounded-lg transition">
                                        + Gates close at 11 PM
                                    </button>
                                    <button type="button" onclick="appendRule('Visitors allowed till 8:00 PM in common areas')" class="text-xs font-semibold bg-gray-100 hover:bg-brand-light hover:text-brand border border-gray-200 px-3 py-1 rounded-lg transition">
                                        + Visitors policy
                                    </button>
                                    <button type="button" onclick="appendRule('Smoking / drinking strictly prohibited inside rooms')" class="text-xs font-semibold bg-gray-100 hover:bg-brand-light hover:text-brand border border-gray-200 px-3 py-1 rounded-lg transition">
                                        + No smoking inside
                                    </button>
                                    <button type="button" onclick="appendRule('Maintain cleanliness in shared washrooms and kitchen')" class="text-xs font-semibold bg-gray-100 hover:bg-brand-light hover:text-brand border border-gray-200 px-3 py-1 rounded-lg transition">
                                        + Cleanliness guideline
                                    </button>
                                </div>

                                <textarea id="propRules" name="house_rules" required rows="3" 
                                          oninput="clearError(this); checkClientModeration();" 
                                          placeholder="Specify tenant house rules, gate timings, visitor policies..." 
                                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition"></textarea>
                                <div class="error-msg hidden" id="err-propRules">Please specify the house rules / tenant guidelines.</div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(2)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3.5 rounded-xl transition">
                                <i class="fas fa-arrow-left text-xs mr-1"></i> Back
                            </button>
                            <button type="button" onclick="goToStep(4)" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
                                Continue to Owner Details <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ================= STEP 4: OWNER INFO & SUBMIT ================= -->
                    <div id="step-pane-4" class="step-pane hidden space-y-6">
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-user-shield text-brand"></i> Owner / Landlord Contact Info
                                </h2>
                                @if($isLoggedIn)
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-3 py-1 rounded-full border border-emerald-300 flex items-center gap-1 shadow-xs">
                                        <i class="fas fa-lock text-[9px] text-emerald-600"></i> Locked to Logged-in Account
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-6">Our verification team and interested tenants will reach out to these contact details.</p>

                            @if($isLoggedIn)
                                <!-- Verified Logged-in Account Badge -->
                                <div class="mb-5 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200/90 rounded-2xl p-4 flex items-center justify-between shadow-xs">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-base font-bold shadow-xs shrink-0">
                                            <i class="fas fa-shield-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-[11px] font-extrabold text-emerald-900 uppercase tracking-wider flex items-center gap-1.5">
                                                <span>Auto-Filled from Logged In Profile</span>
                                                <span class="bg-emerald-200 text-emerald-950 text-[9px] px-2 py-0.5 rounded-full font-black">ACTIVE</span>
                                            </div>
                                            <div class="text-xs font-bold text-gray-900 mt-0.5">{{ $authName ?: 'StayNest Member' }} &bull; {{ $authPhone ?: $authEmail }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-emerald-700 font-semibold hidden sm:flex items-center gap-1">
                                        <i class="fas fa-check-circle text-emerald-600"></i> Verified
                                    </span>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                        Your Full Name *
                                        @if($isLoggedIn)
                                            <span class="text-[10px] text-emerald-700 font-bold ml-1">(Auto-Filled & Locked)</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="ownerName" name="owner_name" required 
                                               value="{{ $authName }}"
                                               {{ $isLoggedIn ? 'readonly' : '' }}
                                               placeholder="e.g. Rajesh Sharma" 
                                               oninput="clearError(this)"
                                               class="w-full {{ $isLoggedIn ? 'bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border-gray-200 select-none' : 'bg-gray-50 text-gray-900' }} border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                                        @if($isLoggedIn)
                                            <span class="absolute right-4 top-3.5 text-gray-400 text-xs" title="Locked to logged-in profile"><i class="fas fa-lock"></i></span>
                                        @endif
                                    </div>
                                    <div class="error-msg hidden" id="err-ownerName">Please enter owner / contact person name.</div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                            Mobile Phone Number (10 Digits) *
                                            @if($isLoggedIn)
                                                <span class="text-[10px] text-emerald-700 font-bold ml-1">(Auto-Filled & Locked)</span>
                                            @endif
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-3.5 text-sm font-semibold text-gray-500">+91</span>
                                            <input type="tel" id="ownerPhone" name="owner_phone" required 
                                                   value="{{ $authPhone }}"
                                                   {{ $isLoggedIn ? 'readonly' : '' }}
                                                   placeholder="98765 43210" maxlength="10"
                                                   onkeydown="preventNegative(event)" oninput="sanitizePhone(this); clearError(this);"
                                                   class="w-full {{ $isLoggedIn ? 'bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border-gray-200 select-none' : 'bg-gray-50 text-gray-900' }} border border-gray-200 rounded-xl pl-14 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                                            @if($isLoggedIn)
                                                <span class="absolute right-4 top-3.5 text-gray-400 text-xs" title="Locked to logged-in profile"><i class="fas fa-lock"></i></span>
                                            @endif
                                        </div>
                                        <div class="error-msg hidden" id="err-ownerPhone">Please enter a valid 10-digit Indian mobile number.</div>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                            Email Address
                                            @if($isLoggedIn)
                                                <span class="text-[10px] text-emerald-700 font-bold ml-1">(Auto-Filled & Locked)</span>
                                            @endif
                                        </label>
                                        <div class="relative">
                                            <input type="email" id="ownerEmail" name="owner_email" 
                                                   value="{{ $authEmail }}"
                                                   {{ $isLoggedIn ? 'readonly' : '' }}
                                                   placeholder="rajesh@example.com" 
                                                   class="w-full {{ $isLoggedIn ? 'bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border-gray-200 select-none' : 'bg-gray-50 text-gray-900' }} border border-gray-200 rounded-xl px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                                            @if($isLoggedIn)
                                                <span class="absolute right-4 top-3.5 text-gray-400 text-xs" title="Locked to logged-in profile"><i class="fas fa-lock"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-start gap-3 mt-4">
                                    <i class="fab fa-whatsapp text-emerald-600 text-xl mt-0.5"></i>
                                    <div>
                                        <div class="font-bold text-xs sm:text-sm text-emerald-900">Instant Tenant Inquiry Alerts on WhatsApp</div>
                                        <div class="text-xs text-emerald-700 mt-0.5">Receive direct tenant leads and visit booking notifications on WhatsApp.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Approval Disclosure Notice -->
                        <div class="bg-amber-50 rounded-2xl p-5 border border-amber-200 flex items-start gap-3">
                            <i class="fas fa-shield-check text-amber-600 text-xl mt-0.5"></i>
                            <div class="text-xs text-amber-900 space-y-1">
                                <div class="font-bold text-sm text-amber-950">Review & Admin Approval Workflow</div>
                                <div>Upon submission, your listing will be reviewed by the StayNest admin team within 24 hours to ensure quality and prevent spam. Once verified, it will be instantly published live to thousands of searchers.</div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(3)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3.5 rounded-xl transition">
                                <i class="fas fa-arrow-left text-xs mr-1"></i> Back
                            </button>
                            <button type="submit" id="submitBtn" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-slate-900 text-white font-bold px-10 py-4 rounded-2xl shadow-xl shadow-brand/30 transition flex items-center gap-3">
                                <span id="submitSpinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
                                <i class="fas fa-paper-plane"></i> Submit for Admin Approval
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Right Column: Sticky Live Listing Preview & Owner Perks (1 Col) -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Live Preview Card -->
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                            <i class="fas fa-eye text-brand"></i> Live Listing Preview
                        </h3>
                        <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            Pending Approval
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 mb-5">
                        <div class="relative aspect-video bg-gray-200">
                            <img id="previewImage" src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" class="w-full h-full object-cover">
                            <span id="previewTypeBadge" class="absolute top-2 left-2 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                PG / Hostel
                            </span>
                            <span id="previewGenderBadge" class="absolute top-2 right-2 bg-purple-600/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                Co-ed
                            </span>
                        </div>
                        <div class="p-4">
                            <h4 id="previewTitle" class="font-bold text-gray-900 text-sm mb-1 line-clamp-1">
                                PG or Property Name
                            </h4>
                            <p id="previewLocation" class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                <i class="fas fa-map-marker-alt text-brand"></i> xxxx
                            </p>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200/60">
                                <div>
                                    <span class="text-[10px] text-gray-400 block uppercase">Starting From</span>
                                    <span id="previewRent" class="text-base font-extrabold text-brand">₹X,XXX</span><span class="text-xs text-gray-500">/mo</span>
                                </div>
                                <span class="bg-brand/10 text-brand text-xs font-semibold px-2.5 py-1 rounded-lg">
                                    0% Brokerage
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Stats -->
                    <div class="space-y-2.5 text-xs text-gray-600 border-t border-gray-100 pt-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span>Listed across mobile app & web</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span>Admin phone verification in &lt;24 hrs</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span>Free lead notifications on WhatsApp</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- FAQ Section for SEO and Landlords -->
        <!-- <div class="mt-16 bg-white rounded-3xl p-8 shadow-sm border border-gray-100 max-w-4xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Frequently Asked Questions by Owners</h3>
            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <h4 class="font-bold text-sm text-gray-900 mb-1">How much does it cost to list a PG or Property?</h4>
                    <p class="text-xs text-gray-600">Listing on StayNest is 100% free with zero commission or brokerage fees. You keep 100% of your rent.</p>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <h4 class="font-bold text-sm text-gray-900 mb-1">What happens after I submit my listing?</h4>
                    <p class="text-xs text-gray-600">Your property goes to our verification queue. An admin reviews the photos and address within 24 hours. Once approved, the listing goes live instantly.</p>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <h4 class="font-bold text-sm text-gray-900 mb-1">Can I list Flats, Apartments, or Commercial space later?</h4>
                    <p class="text-xs text-gray-600">Yes! The listing engine supports PGs, Co-living spaces, Flats, and Commercial shops with dedicated search filters.</p>
                </div>
            </div>
        </div> -->

    </div>

</div>

<!-- ========================================================================= -->
<!-- ZEPTO / BLINKIT STYLE LIVE GPS LOCATION & CONFIRM ADDRESS MODAL (AS IN PROFILE) -->
<!-- ========================================================================= -->
<div id="locationModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300 flex items-center justify-center p-3 sm:p-4">
    <div onclick="closeLocationModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
    
    <div class="relative w-full max-w-lg sm:max-w-xl max-h-[92vh] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col z-10 animate-slide-up border border-gray-100">
        
        <!-- Modal Header -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-gray-900 to-teal-950 text-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-brand flex items-center justify-center text-white text-sm shadow-md">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm sm:text-base leading-tight">Confirm PG / Property Location</h3>
                    <p class="text-[11px] text-teal-200/80">Zepto GPS Auto-Locate & Pinpoint</p>
                </div>
            </div>
            <button type="button" onclick="closeLocationModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition">
                <i class="fas fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Body Scrollable -->
        <div class="p-5 overflow-y-auto space-y-4 flex-1">
            
            <!-- Live GPS Detect Banner (Zepto Style) -->
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
                <button type="button" onclick="detectLiveLocation()" id="detectLocationBtn" class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-3.5 py-2.5 rounded-xl shadow-xs transition tap-effect whitespace-nowrap flex items-center gap-1.5">
                    <i class="fas fa-location-arrow text-[10px]" id="gpsIcon"></i>
                    <span id="gpsBtnText">Locate Me 🎯</span>
                </button>
            </div>

            <!-- Embedded Leaflet Map Picker in Modal -->
            <div class="rounded-2xl overflow-hidden border-2 border-brand/30 relative shadow-sm">
                <!-- Search Bar -->
                <div class="absolute top-2.5 left-2.5 right-2.5 z-[1000] flex gap-2">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                        <input type="text" id="mapSearchInput" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); searchLocationOnMap(); }" placeholder="Search locality or landmark on map..." class="w-full bg-white/95 backdrop-blur-md border border-gray-200 rounded-xl py-1.5 pl-8 pr-3 text-xs shadow-md focus:outline-none focus:ring-2 focus:ring-brand">
                    </div>
                    <button type="button" onclick="searchLocationOnMap()" class="bg-slate-900 hover:bg-slate-800 text-white px-3 py-1.5 rounded-xl text-xs font-semibold shadow-md transition">
                        Search
                    </button>
                </div>

                <div id="propertyMapPicker" style="height: 220px; width: 100%;"></div>

                <div class="bg-slate-900/90 text-white px-3 py-1.5 flex items-center justify-between text-[11px]">
                    <span id="mapStatusText">💡 Drag pin or click map to set location</span>
                    <span id="mapCoordinatesBadge" class="text-gray-300 font-mono text-[10px]">Lat: 28.6280, Lng: 77.3649</span>
                </div>
            </div>

            <!-- Modal Address Form Details (Zepto Style) -->
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                        <input type="text" id="modalCity" placeholder="e.g. Noida" oninput="syncModalToMain();"
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Area / Locality <span class="text-red-500">*</span></label>
                        <input type="text" id="modalArea" placeholder="e.g. Sector 62" oninput="syncModalToMain();"
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Full Street Address <span class="text-red-500">*</span></label>
                    <textarea id="modalAddress" rows="2" placeholder="Building name, plot/flat no, street" oninput="syncModalToMain();"
                        class="w-full bg-white border border-gray-200 rounded-xl py-2 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Landmark</label>
                        <input type="text" id="modalLandmark" placeholder="e.g. Near Metro Station" oninput="syncModalToMain();"
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pincode</label>
                        <input type="text" id="modalPincode" placeholder="e.g. 201301" maxlength="6" oninput="syncModalToMain();"
                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/40">
                    </div>
                </div>

                <button type="button" onclick="confirmLocationModal()" class="w-full bg-brand hover:bg-brand-dark text-white font-bold py-3.5 rounded-2xl text-xs transition tap-effect shadow-md shadow-brand/20 flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Confirm & Save Location</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= SUCCESS MODAL (ADMIN APPROVAL QUEUE / UPDATE COMPLETE) ================= -->
<div id="successModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center shadow-2xl relative animate-in fade-in zoom-in duration-300">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 id="successModalHeading" class="text-xl font-extrabold text-gray-900 mb-2">Submitted for Admin Approval!</h3>
        <p id="successModalMessage" class="text-xs sm:text-sm text-gray-600 mb-4">
            Your listing has been submitted successfully and assigned to our review team.
        </p>

        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 mb-6">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Tracking Reference ID</div>
            <div id="successTrackingId" class="text-lg font-mono font-bold text-brand">STAY-XXXXXX</div>
            <div id="successStatusBadge" class="text-xs text-amber-700 mt-2 font-medium flex items-center justify-center gap-1">
                <i class="fas fa-clock text-amber-500"></i> Verification Status: <span class="font-bold">Pending Review (24h)</span>
            </div>
        </div>

        <div class="space-y-3">
            @auth
                @if(Auth::user()->roles()->where('slug', 'broker')->exists())
                    <a href="{{ route('broker.pgs') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition">
                        <i class="fas fa-arrow-left mr-1.5"></i> Return to Broker PGs
                    </a>
                @elseif(Auth::user()->roles()->whereIn('slug', ['super_admin', 'admin'])->exists())
                    <a href="{{ route('admin.pgs') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition">
                        <i class="fas fa-arrow-left mr-1.5"></i> Return to Admin Console
                    </a>
                @else
                    <a href="{{ route('user.home') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition">
                        Explore StayNest
                    </a>
                @endif
            @else
                <a href="{{ route('user.home') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition">
                    Explore StayNest
                </a>
            @endauth
            <button onclick="document.getElementById('successModal').classList.add('hidden'); window.location.href = '{{ url('/list-property') }}';" class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-200 transition text-sm">
                List Another Property
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet Map JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    let currentStep = 1;
    let propertyMap = null;
    let propertyMarker = null;
    let gpsAccuracyCircle = null;
    let isGeocoding = false;

    let uploadedPhotos = [];
    let bannerCountdownInterval = null;
    let editPropertyId = null;

    document.addEventListener('DOMContentLoaded', () => {
        initLoggedInContact();
        initHeroBannerTimer();
        renderPhotoPreviews();
        initPropertyMap();
        checkForEditMode();
    });

    // Auto-hide hero banner after 40 seconds & only show once per day
    function initHeroBannerTimer() {
        const banner = document.getElementById('listPropertyHeroBanner');
        if (!banner) return;

        const storageKey = 'staynest_list_prop_banner_date';
        const todayDate = new Date().toDateString(); // e.g. "Sun Aug 16 2026"
        const lastShown = localStorage.getItem(storageKey);

        // If banner was already shown today, hide immediately
        if (lastShown === todayDate) {
            banner.classList.add('hidden');
            return;
        }

        // First visit today: record date and show banner
        localStorage.setItem(storageKey, todayDate);
        banner.classList.remove('hidden');

        // Countdown timer for 40 seconds
        let secondsLeft = 40;
        const timerText = document.getElementById('bannerTimerText');

        bannerCountdownInterval = setInterval(() => {
            secondsLeft--;
            if (timerText) {
                timerText.innerText = `Hides in ${secondsLeft}s`;
            }
            if (secondsLeft <= 0) {
                clearInterval(bannerCountdownInterval);
                dismissHeroBanner();
            }
        }, 1000);
    }

    function dismissHeroBanner() {
        if (bannerCountdownInterval) {
            clearInterval(bannerCountdownInterval);
        }

        const banner = document.getElementById('listPropertyHeroBanner');
        if (!banner) return;

        banner.style.transition = 'all 0.7s cubic-bezier(0.4, 0, 0.2, 1)';
        banner.style.opacity = '0';
        banner.style.transform = 'scale(0.97) translateY(-15px)';

        setTimeout(() => {
            banner.style.maxHeight = '0';
            banner.style.marginBottom = '0';
            banner.style.paddingTop = '0';
            banner.style.paddingBottom = '0';
            banner.style.overflow = 'hidden';
        }, 50);

        setTimeout(() => {
            banner.classList.add('hidden');
        }, 750);
    }

    // Initialize Interactive Leaflet Map Picker
    function initPropertyMap() {
        if (propertyMap) {
            setTimeout(() => { propertyMap.invalidateSize(); }, 200);
            return;
        }

        const mapEl = document.getElementById('propertyMapPicker');
        if (!mapEl) return;

        // Check for cached user location in localStorage (like in location & profile pages)
        let defaultLat = 28.6280;
        let defaultLng = 77.3649;

        const cachedLat = parseFloat(localStorage.getItem('user_cached_lat'));
        const cachedLng = parseFloat(localStorage.getItem('user_cached_lng'));
        if (!isNaN(cachedLat) && !isNaN(cachedLng) && cachedLat !== 0) {
            defaultLat = cachedLat;
            defaultLng = cachedLng;
        }

        propertyMap = L.map('propertyMapPicker', {
            center: [defaultLat, defaultLng],
            zoom: 16,
            maxZoom: 19,
            zoomControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(propertyMap);

        // Custom Leaflet Pin Marker
        const pinIcon = L.divIcon({
            className: 'custom-map-pin',
            html: '<div style="background:#10b981; width:36px; height:36px; border-radius:50% 50% 50% 0; transform:rotate(-45deg); display:flex; align-items:center; justify-content:center; border:3px solid white; box-shadow:0 4px 14px rgba(0,0,0,0.4);"><i class="fas fa-map-marker-alt text-white text-sm" style="transform:rotate(45deg);"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 36],
            popupAnchor: [0, -36]
        });

        propertyMarker = L.marker([defaultLat, defaultLng], {
            draggable: true,
            icon: pinIcon
        }).addTo(propertyMap);

        propertyMarker.bindPopup('<strong>Property Location</strong><br>Drag pin or click on map to autofill address').openPopup();

        // Drag end listener
        propertyMarker.on('dragend', function (e) {
            const coord = propertyMarker.getLatLng();
            if (gpsAccuracyCircle) {
                gpsAccuracyCircle.setLatLng(coord);
            }
            fetchAddressFromCoordinates(coord.lat, coord.lng);
        });

        // Map click listener to move marker anywhere
        propertyMap.on('click', function (e) {
            propertyMarker.setLatLng(e.latlng);
            if (gpsAccuracyCircle) {
                gpsAccuracyCircle.setLatLng(e.latlng);
            }
            propertyMarker.openPopup();
            fetchAddressFromCoordinates(e.latlng.lat, e.latlng.lng);
        });

        // Load cached/saved location to form fields if currently blank
        loadCachedLocationToForm();
    }

    // Load verified home address / current location (like in location & profile pages)
    function loadCachedLocationToForm() {
        let city = 'Noida';
        let area = 'Sector 62';
        let fullAddress = 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309';
        let pincode = '201309';
        let landmark = 'Near Electronic City Metro';
        let lat = 28.6280;
        let lng = 77.3649;
        let tag = 'VERIFIED HOME ADDRESS';

        // Check if staynest_default_address is saved from Profile or Location page
        try {
            const saved = localStorage.getItem('staynest_default_address');
            if (saved) {
                const parsed = JSON.parse(saved);
                if (parsed.line1 || parsed.line2) {
                    fullAddress = [parsed.line1, parsed.line2].filter(Boolean).join(', ');
                    tag = parsed.tag === 'HOME' ? 'VERIFIED HOME ADDRESS' : (parsed.tag ? `VERIFIED ${parsed.tag}` : 'VERIFIED HOME ADDRESS');
                }
            }
        } catch(e) {}

        const cachedCity = localStorage.getItem('user_cached_city');
        const cachedArea = localStorage.getItem('user_cached_area');
        const cachedAddr = localStorage.getItem('user_cached_address');
        const cachedPin = localStorage.getItem('user_cached_pin');
        const cachedLat = parseFloat(localStorage.getItem('user_cached_lat'));
        const cachedLng = parseFloat(localStorage.getItem('user_cached_lng'));

        // Prevent ISP broadband Delhi fallback from overriding verified Noida address
        if (cachedCity && cachedCity !== 'Delhi' && cachedCity !== 'Connaught Place') city = cachedCity;
        if (cachedArea && cachedArea !== 'Connaught Place' && cachedArea !== 'Delhi') area = cachedArea;
        if (cachedAddr && !cachedAddr.includes('Connaught Place')) fullAddress = cachedAddr;
        if (cachedPin) pincode = cachedPin;
        if (!isNaN(cachedLat) && cachedLat !== 0 && cachedLat > 28.5 && cachedLat < 28.7 && cachedLng > 77.3 && cachedLng < 77.5) {
            lat = cachedLat;
            lng = cachedLng;
        }

        const pCity = document.getElementById('propCity');
        const pArea = document.getElementById('propArea');
        const pAddr = document.getElementById('propAddress');
        const pPin = document.getElementById('propPincode');
        const pLand = document.getElementById('propLandmark');
        const pLat = document.getElementById('propLatitude');
        const pLng = document.getElementById('propLongitude');

        if (pCity && !pCity.value.trim()) pCity.value = city;
        if (pArea && !pArea.value.trim()) pArea.value = area;
        if (pAddr && !pAddr.value.trim()) pAddr.value = fullAddress;
        if (pPin && !pPin.value.trim()) pPin.value = pincode;
        if (pLand && !pLand.value.trim()) pLand.value = landmark;
        if (pLat && !pLat.value) pLat.value = lat;
        if (pLng && !pLng.value) pLng.value = lng;

        // Populate Modal Fields
        const mCity = document.getElementById('modalCity');
        const mArea = document.getElementById('modalArea');
        const mAddr = document.getElementById('modalAddress');
        const mPin = document.getElementById('modalPincode');
        const mLand = document.getElementById('modalLandmark');
        if (mCity) mCity.value = city;
        if (mArea) mArea.value = area;
        if (mAddr) mAddr.value = fullAddress;
        if (mPin) mPin.value = pincode;
        if (mLand) mLand.value = landmark;

        updateLocationSummaryCard(city, area, fullAddress, pincode, tag);
    }

    // Smart Address & Metro Location Resolver (Accurately identifies Noida, Gurgaon, Bangalore, etc.)
    function resolveSmartLocation(data, lat, lon) {
        const addr = (data && data.address) ? data.address : {};
        const displayName = (data && data.display_name) ? data.display_name : '';
        const displayLower = displayName.toLowerCase();

        const stateDist = (addr.state_district || '').toLowerCase();
        const county = (addr.county || '').toLowerCase();
        const suburb = (addr.suburb || '').toLowerCase();
        const cityDist = (addr.city_district || '').toLowerCase();
        const town = (addr.town || '').toLowerCase();
        const municipality = (addr.municipality || '').toLowerCase();

        // 1. Precise City Resolution
        let city = '';
        if (displayLower.includes('greater noida')) {
            city = 'Greater Noida';
        } else if (
            displayLower.includes('noida') || 
            stateDist.includes('gautam buddha nagar') || 
            county.includes('gautam buddha nagar') || 
            cityDist.includes('noida') || 
            suburb.includes('sector 62') || suburb.includes('sector 59') || suburb.includes('sector 63') || suburb.includes('sector 18') || suburb.includes('sector 128') ||
            (lat >= 28.45 && lat <= 28.66 && lon >= 77.30 && lon <= 77.45)
        ) {
            city = 'Noida';
        } else if (displayLower.includes('gurugram') || displayLower.includes('gurgaon')) {
            city = 'Gurugram';
        } else if (displayLower.includes('ghaziabad')) {
            city = 'Ghaziabad';
        } else if (displayLower.includes('faridabad')) {
            city = 'Faridabad';
        } else if (displayLower.includes('bangalore') || displayLower.includes('bengaluru')) {
            city = 'Bangalore';
        } else if (displayLower.includes('delhi') && !displayLower.includes('noida')) {
            city = 'Delhi';
        } else {
            city = addr.city || addr.town || addr.city_district || addr.district || addr.state_district || addr.state || 'Noida';
        }

        // 2. Precise Area / Locality / Sector
        let area = addr.suburb || addr.neighbourhood || addr.residential || addr.subdistrict || addr.quarter || addr.village || addr.road || '';
        if ((!area || area.toLowerCase() === 'noida' || area.toLowerCase() === 'delhi') && (city === 'Noida' || displayLower.includes('sector'))) {
            const sectorMatch = displayName.match(/sector[-\s]*\d+[a-z]?/i);
            if (sectorMatch) {
                area = sectorMatch[0].toUpperCase();
            } else if (suburb) {
                area = suburb;
            } else {
                area = 'Sector 62';
            }
        }

        // 3. Full Street Address Construction
        const road = addr.road || '';
        const building = addr.building || addr.house_number || addr.amenity || '';
        let addressParts = [building, road, area, city].filter(Boolean);
        let fullAddress = addressParts.length > 0 ? addressParts.join(', ') : displayName;

        // 4. Pincode & Landmark
        const pincode = addr.postcode || (city === 'Noida' ? '201309' : '');
        const landmark = addr.amenity || addr.shop || addr.office || addr.tourism || addr.historic || addr.leisure || '';

        return { city, area, fullAddress, pincode, landmark };
    }

    // Reverse Geocoding: Converts (lat, lon) -> City, Area, Address, Pincode & Autofills Each Box
    async function fetchAddressFromCoordinates(lat, lon, accuracy = null) {
        if (isGeocoding) return;
        isGeocoding = true;

        const statusText = document.getElementById('mapStatusText');
        const badge = document.getElementById('mapCoordinatesBadge');
        if (statusText) statusText.innerHTML = '<i class="fas fa-spinner fa-spin text-emerald-400"></i> Pinpoint GPS reverse-geocoding (1m precision)...';
        
        const accLabel = accuracy ? ` | 🎯 ±${accuracy}m` : '';
        if (badge) badge.innerText = `Lat: ${Number(lat).toFixed(6)}, Lng: ${Number(lon).toFixed(6)}${accLabel}`;

        document.getElementById('propLatitude').value = lat;
        document.getElementById('propLongitude').value = lon;

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`, {
                headers: { 'Accept-Language': 'en' }
            });
            const data = await res.json();

            const resolved = resolveSmartLocation(data, lat, lon);
            const city = resolved.city;
            const area = resolved.area;
            const fullAddress = resolved.fullAddress;
            const pincode = resolved.pincode;
            const landmark = resolved.landmark;

            // Cache in localStorage to prevent loss/overwriting (just like in location page)
            try {
                localStorage.setItem('user_cached_lat', lat);
                localStorage.setItem('user_cached_lng', lon);
                localStorage.setItem('user_cached_address', fullAddress);
                localStorage.setItem('user_cached_city', city);
                localStorage.setItem('user_cached_area', area);
                if (pincode) localStorage.setItem('user_cached_pin', pincode);
            } catch(e) {}

            // Populate Main Form Fields
            if (city) {
                const cityInput = document.getElementById('propCity');
                if (cityInput) { cityInput.value = city; clearError(cityInput); }
            }

            if (area) {
                const areaInput = document.getElementById('propArea');
                if (areaInput) { areaInput.value = area; clearError(areaInput); }
            }

            if (fullAddress) {
                const addrInput = document.getElementById('propAddress');
                if (addrInput) { addrInput.value = fullAddress; clearError(addrInput); }
            }

            if (pincode) {
                const pinInput = document.getElementById('propPincode');
                if (pinInput) pinInput.value = pincode;
            }

            if (landmark) {
                const landInput = document.getElementById('propLandmark');
                if (landInput && !landInput.value.trim()) {
                    landInput.value = landmark;
                }
            }

            // Populate Modal Fields (Zepto Style)
            const mCity = document.getElementById('modalCity');
            const mArea = document.getElementById('modalArea');
            const mAddr = document.getElementById('modalAddress');
            const mPin = document.getElementById('modalPincode');
            const mLand = document.getElementById('modalLandmark');

            if (mCity) mCity.value = city;
            if (mArea) mArea.value = area;
            if (mAddr) mAddr.value = fullAddress;
            if (mPin) mPin.value = pincode;
            if (mLand && !mLand.value.trim()) mLand.value = landmark;

            updateLocationSummaryCard(city, area, fullAddress, pincode, 'CURRENT LOCATION');

            if (statusText) {
                statusText.innerHTML = `📍 <strong>${area || city}</strong> detected (1m GPS lock) & address filled!`;
            }

            // Update popup
            if (propertyMarker) {
                propertyMarker.bindPopup(`<strong>${area || city || 'Location'}</strong><br>${fullAddress}<br><span style="font-size:10px; color:#059669; font-weight:700;">🎯 1m Live GPS Locked</span>`).openPopup();
            }

            // Update live sticky preview
            updateLivePreview();
        } catch (err) {
            console.warn('Geocoding error:', err);
            if (statusText) statusText.innerHTML = '📍 Location pinned. Verify or edit details below.';
        } finally {
            isGeocoding = false;
        }
    }

    // Direct Live Geolocation fetch on "Use Current GPS Location" button (DO NOT OPEN MODAL)
    function useCurrentGpsDirect(btn) {
        const icon = btn ? btn.querySelector('i') : document.getElementById('useCurrentGpsIcon');
        const text = btn ? btn.querySelector('span') : document.getElementById('useCurrentGpsText');

        if (!navigator.geolocation) {
            showZeptoToast('GPS Notice', 'Geolocation is not supported by your browser.');
            return;
        }

        if (icon) icon.className = 'fas fa-spinner fa-spin text-white';
        if (text) text.innerText = 'Detecting 1m GPS Location...';
        if (btn) btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            async (pos) => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;
                const accuracy = Math.round(pos.coords.accuracy || 1);

                // Update Map if map exists
                if (propertyMap) {
                    if (gpsAccuracyCircle) propertyMap.removeLayer(gpsAccuracyCircle);
                    gpsAccuracyCircle = L.circle([lat, lon], {
                        radius: Math.max(1, accuracy),
                        color: '#10b981',
                        weight: 2,
                        fillColor: '#10b981',
                        fillOpacity: 0.15,
                        dashArray: '3, 3'
                    }).addTo(propertyMap);

                    propertyMap.setView([lat, lon], 18);
                    if (propertyMarker) propertyMarker.setLatLng([lat, lon]);
                }

                await fetchAddressFromCoordinates(lat, lon, accuracy);

                if (icon) icon.className = 'fas fa-check-circle text-emerald-300';
                if (text) text.innerText = `Current Location Applied (±${accuracy}m)!`;
                if (btn) btn.disabled = false;

                const badge = document.getElementById('locPreciseBadge');
                if (badge) badge.innerText = `🎯 1m Live GPS Lock (±${accuracy}m)`;

                showZeptoToast('Location Auto-Filled 🎯', 'City, Area, Address & Pincode directly applied!');

                setTimeout(() => {
                    if (icon) icon.className = 'fas fa-crosshairs';
                    if (text) text.innerText = 'Use Current GPS Location';
                }, 3500);
            },
            (err) => {
                if (icon) icon.className = 'fas fa-crosshairs';
                if (text) text.innerText = 'Use Current GPS Location';
                if (btn) btn.disabled = false;
                showZeptoToast('GPS Note', 'Location permission denied or unavailable. Click "Edit Map" to set location.');
            },
            { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
        );
    }

    // Direct 1-Click Switch to Verified Home Address (Sector 62, Noida - identical to Location page)
    function useVerifiedHomeAddressDirect() {
        const homeData = {
            tag: 'VERIFIED HOME ADDRESS',
            line1: 'Sector 62, Noida',
            fullAddress: 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309',
            city: 'Noida',
            area: 'Sector 62',
            pincode: '201309',
            landmark: 'Near Electronic City Metro',
            lat: 28.6280,
            lng: 77.3649
        };

        // Check if user has custom address in staynest_default_address
        try {
            const saved = localStorage.getItem('staynest_default_address');
            if (saved) {
                const parsed = JSON.parse(saved);
                if (parsed.line1 || parsed.line2) {
                    homeData.fullAddress = [parsed.line1, parsed.line2].filter(Boolean).join(', ');
                }
            }
        } catch(e) {}

        const pCity = document.getElementById('propCity');
        const pArea = document.getElementById('propArea');
        const pAddr = document.getElementById('propAddress');
        const pPin = document.getElementById('propPincode');
        const pLand = document.getElementById('propLandmark');
        const pLat = document.getElementById('propLatitude');
        const pLng = document.getElementById('propLongitude');

        if (pCity) { pCity.value = homeData.city; clearError(pCity); }
        if (pArea) { pArea.value = homeData.area; clearError(pArea); }
        if (pAddr) { pAddr.value = homeData.fullAddress; clearError(pAddr); }
        if (pPin) pPin.value = homeData.pincode;
        if (pLand) pLand.value = homeData.landmark;
        if (pLat) pLat.value = homeData.lat;
        if (pLng) pLng.value = homeData.lng;

        // Populate Modal Fields (Zepto Style)
        const mCity = document.getElementById('modalCity');
        const mArea = document.getElementById('modalArea');
        const mAddr = document.getElementById('modalAddress');
        const mPin = document.getElementById('modalPincode');
        const mLand = document.getElementById('modalLandmark');
        if (mCity) mCity.value = homeData.city;
        if (mArea) mArea.value = homeData.area;
        if (mAddr) mAddr.value = homeData.fullAddress;
        if (mPin) mPin.value = homeData.pincode;
        if (mLand) mLand.value = homeData.landmark;

        if (propertyMap && propertyMarker) {
            propertyMap.setView([homeData.lat, homeData.lng], 17);
            propertyMarker.setLatLng([homeData.lat, homeData.lng]);
            if (gpsAccuracyCircle) {
                propertyMap.removeLayer(gpsAccuracyCircle);
                gpsAccuracyCircle = L.circle([homeData.lat, homeData.lng], {
                    radius: 5,
                    color: '#10b981',
                    weight: 2,
                    fillColor: '#10b981',
                    fillOpacity: 0.15,
                    dashArray: '3, 3'
                }).addTo(propertyMap);
            }
            propertyMarker.bindPopup(`<strong>${homeData.area}, ${homeData.city}</strong><br>${homeData.fullAddress}<br><span style="font-size:10px; color:#059669; font-weight:700;">🎯 Verified Home Address</span>`).openPopup();
        }

        try {
            localStorage.setItem('user_cached_lat', homeData.lat);
            localStorage.setItem('user_cached_lng', homeData.lng);
            localStorage.setItem('user_cached_address', homeData.fullAddress);
            localStorage.setItem('user_cached_city', homeData.city);
            localStorage.setItem('user_cached_area', homeData.area);
            localStorage.setItem('user_cached_pin', homeData.pincode);
        } catch(e) {}

        const badge = document.getElementById('locPreciseBadge');
        if (badge) badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> 🎯 1m Live GPS Lock';

        updateLocationSummaryCard(homeData.city, homeData.area, homeData.fullAddress, homeData.pincode, homeData.tag);
        updateLivePreview();
        showZeptoToast('Verified Home Loaded 🏠', 'Sector 62, Noida address applied to listing!');
    }

    // ===================== ZEPTO / PROFILE STYLE LOCATION MODAL HANDLERS =====================
    function openLocationModal(autoDetect = false) {
        const modal = document.getElementById('locationModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        syncMainToModal();
        setTimeout(() => {
            if (propertyMap) {
                propertyMap.invalidateSize();
            }
            if (autoDetect) {
                detectLiveLocation();
            }
        }, 200);
    }

    function closeLocationModal() {
        const modal = document.getElementById('locationModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function confirmLocationModal() {
        syncModalToMain();
        closeLocationModal();
        showZeptoToast('Location Confirmed & Saved 🎯', 'Address and live GPS coordinates applied!');
    }

    function syncModalToMain() {
        const mCity = document.getElementById('modalCity')?.value || '';
        const mArea = document.getElementById('modalArea')?.value || '';
        const mAddr = document.getElementById('modalAddress')?.value || '';
        const mLand = document.getElementById('modalLandmark')?.value || '';
        const mPin = document.getElementById('modalPincode')?.value || '';

        const pCity = document.getElementById('propCity');
        const pArea = document.getElementById('propArea');
        const pAddr = document.getElementById('propAddress');
        const pPin = document.getElementById('propPincode');
        const pLand = document.getElementById('propLandmark');

        if (pCity && mCity) { pCity.value = mCity; clearError(pCity); }
        if (pArea && mArea) { pArea.value = mArea; clearError(pArea); }
        if (pAddr && mAddr) { pAddr.value = mAddr; clearError(pAddr); }
        if (pPin && mPin) { pPin.value = mPin; }
        if (pLand && mLand) { pLand.value = mLand; }

        updateLocationSummaryCard(mCity, mArea, mAddr, mPin);
        updateLivePreview();
    }

    function syncMainToModal() {
        const pCity = document.getElementById('propCity')?.value || '';
        const pArea = document.getElementById('propArea')?.value || '';
        const pAddr = document.getElementById('propAddress')?.value || '';
        const pPin = document.getElementById('propPincode')?.value || '';
        const pLand = document.getElementById('propLandmark')?.value || '';

        const mCity = document.getElementById('modalCity');
        const mArea = document.getElementById('modalArea');
        const mAddr = document.getElementById('modalAddress');
        const mPin = document.getElementById('modalPincode');
        const mLand = document.getElementById('modalLandmark');

        if (mCity) mCity.value = pCity;
        if (mArea) mArea.value = pArea;
        if (mAddr) mAddr.value = pAddr;
        if (mPin) mPin.value = pPin;
        if (mLand) mLand.value = pLand;

        updateLocationSummaryCard(pCity, pArea, pAddr, pPin);
    }

    function updateLocationSummaryCard(city, area, address, pincode, tag = null) {
        const line1 = document.getElementById('locSummaryLine1');
        const line2 = document.getElementById('locSummaryLine2');
        const tagEl = document.getElementById('addrBadgeTag');

        if (tag && tagEl) {
            tagEl.innerText = `📍 ${tag}`;
        }
        if (line1) line1.innerText = [area, city].filter(Boolean).join(', ') || 'Sector 62, Noida';
        if (line2) line2.innerText = address || 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309';
    }

    // Search locality / landmark directly on the map
    async function searchLocationOnMap() {
        const input = document.getElementById('mapSearchInput');
        const query = input.value.trim();
        if (!query) return;

        const statusText = document.getElementById('mapStatusText');
        if (statusText) statusText.innerHTML = `<i class="fas fa-spinner fa-spin text-emerald-400"></i> Searching "${query}" on map...`;

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`);
            const results = await res.json();

            if (results && results.length > 0) {
                const item = results[0];
                const lat = parseFloat(item.lat);
                const lon = parseFloat(item.lon);

                propertyMap.flyTo([lat, lon], 18, { duration: 1.2 });
                propertyMarker.setLatLng([lat, lon]);
                if (gpsAccuracyCircle) {
                    gpsAccuracyCircle.setLatLng([lat, lon]);
                }
                fetchAddressFromCoordinates(lat, lon);
            } else {
                alert(`No location found for "${query}". Try searching with city name.`);
                if (statusText) statusText.innerHTML = '💡 Drag the pin or click anywhere on the map to autofill address';
            }
        } catch (e) {
            console.error('Search error:', e);
        }
    }

    // Prevent negative typing (minus, 'e', etc.)
    function preventNegative(e) {
        if (e.key === '-' || e.key === 'e' || e.key === 'E' || e.key === '+') {
            e.preventDefault();
            return false;
        }
    }

    // Clamp positive numbers
    function sanitizePositive(input) {
        if (input.value !== '' && Number(input.value) < 0) {
            input.value = 0;
        }
    }

    // Sanitize phone number to digits only
    function sanitizePhone(input) {
        input.value = input.value.replace(/\D/g, '').slice(0, 10);
    }

    function clearError(input) {
        input.classList.remove('input-error');
        const errEl = document.getElementById(`err-${input.id}`);
        if (errEl) errEl.classList.add('hidden');
    }

    function showError(inputId, message) {
        const input = document.getElementById(inputId);
        if (input) input.classList.add('input-error');
        const errEl = document.getElementById(`err-${inputId}`);
        if (errEl) {
            if (message) errEl.innerText = message;
            errEl.classList.remove('hidden');
        }
    }

    // Live Geolocation Detection on Button Click (1m High Precision)
    function detectLiveLocation() {
        const btn = document.getElementById('detectLocationBtn');
        const icon = document.getElementById('gpsIcon');
        const btnText = document.getElementById('gpsBtnText');

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        icon.className = 'fas fa-spinner fa-spin';
        btnText.innerText = 'Locking 1m GPS...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                const accuracy = Math.round(position.coords.accuracy || 1);

                // Add or update accuracy circle on map
                if (gpsAccuracyCircle) {
                    propertyMap.removeLayer(gpsAccuracyCircle);
                }
                gpsAccuracyCircle = L.circle([lat, lon], {
                    radius: Math.max(1, accuracy),
                    color: '#10b981',
                    weight: 2,
                    fillColor: '#10b981',
                    fillOpacity: 0.15,
                    dashArray: '3, 3'
                }).addTo(propertyMap);

                if (propertyMap && propertyMarker) {
                    propertyMap.flyTo([lat, lon], 19, { duration: 1.5 });
                    propertyMarker.setLatLng([lat, lon]);
                }

                await fetchAddressFromCoordinates(lat, lon, accuracy);

                icon.className = 'fas fa-check';
                btnText.innerText = `GPS Locked (±${accuracy}m)!`;
                btn.disabled = false;

                const statusEl = document.getElementById('gpsStatusText');
                if (statusEl) {
                    statusEl.innerText = `Detected GPS: ${lat.toFixed(4)}° N, ${lon.toFixed(4)}° E (±${accuracy}m Lock)`;
                }

                showZeptoToast('GPS Location Detected 🎯', 'Address auto-filled with 1m accuracy!');

                setTimeout(() => {
                    btnText.innerText = 'Locate Me 🎯';
                    icon.className = 'fas fa-location-arrow';
                }, 3500);
            },
            (error) => {
                icon.className = 'fas fa-location-arrow';
                btnText.innerText = 'Locate Me 🎯';
                btn.disabled = false;
                
                const statusEl = document.getElementById('gpsStatusText');
                if (statusEl) {
                    statusEl.innerText = '⚠️ GPS permission not granted. Drag map pin or select location manually.';
                }
                showZeptoToast('GPS Location Note', 'Drag pin or click map to pinpoint your property location.');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    function showZeptoToast(title, msg) {
        const toast = document.getElementById('zeptoToast');
        if (!toast) return;
        document.getElementById('zeptoToastTitle').innerText = title;
        document.getElementById('zeptoToastMsg').innerText = msg;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    // Quick preset rule chips handler
    function appendRule(text) {
        const el = document.getElementById('propRules');
        if (!el) return;
        const current = el.value.trim();
        if (current.includes(text)) return;
        el.value = current ? current + '\n• ' + text : '• ' + text;
        clearError(el);
        checkClientModeration();
    }

    // Client-side AI Content Moderation Filter
    const prohibitedTermsRegex = [
        { category: 'Vulgarity / Profanity', regex: /\b(fuck|fucking|fucked|fucker|fck|bitch|bitches|bastard|bastards|asshole|assholes|chutiya|chootiya|chutya|choot|bhenchod|behenchod|bc|madarchod|mc|gaand|gandu|harami|lavde|lauda|loda|lodu|randi|kutiya|kamina|shit|cunt|cunts|pussy|dick)\b/i },
        { category: 'Sexual Content & Escorts', regex: /\b(sex|sexy|sexual|intercourse|call\s*girl|call\s*girls|call\s*boy|call\s*boys|escort|escorts|russian\s*girl|paid\s*sex|adult\s*service|adult|adults|sex\s*service|nude|nudes|nudity|naked|porn|xxx|erotic|sensual\s*massage|happy\s*ending|sax\s*sux|sax|sux|onlyfans|night\s*service|sugar\s*daddy|hookup)\b/i },
        { category: 'Substances & Illicit Drugs', regex: /\b(cocaine|heroin|charas|ganja|weed|buy\s*weed|cannabis|meth|crystal\s*meth|mdma|ecstasy|lsd|smack|brown\s*sugar|narcotics|drug\s*party|afim|afeem)\b/i },
        { category: 'Abuse & Harassment', regex: /\b(kill\s*you|murder|rape|threat|assault|beat\s*up|violence|terrorist|jihad|hate\s*all)\b/i },
        { category: 'Scams & Phishing', regex: /\b(send\s*otp|share\s*otp|lottery\s*winner|crypto\s*investment|double\s*money|instant\s*loan\s*scam|free\s*recharge)\b/i }
    ];

    function checkClientModeration() {
        const title = document.getElementById('propName')?.value || '';
        const desc = document.getElementById('propDescription')?.value || '';
        const rules = document.getElementById('propRules')?.value || '';
        const landmark = document.getElementById('propLandmark')?.value || '';
        const address = document.getElementById('propAddress')?.value || '';
        const area = document.getElementById('propArea')?.value || '';

        const alertBox = document.getElementById('moderationAlertBox');
        const alertMsg = document.getElementById('moderationAlertMsg');

        const texts = [
            { name: 'Property Title', val: title },
            { name: 'Description', val: desc },
            { name: 'House Rules', val: rules },
            { name: 'Landmark', val: landmark },
            { name: 'Address', val: address },
            { name: 'Area', val: area }
        ];

        for (const item of texts) {
            if (!item.val || !item.val.trim()) continue;
            for (const rule of prohibitedTermsRegex) {
                if (rule.regex.test(item.val)) {
                    const matched = item.val.match(rule.regex);
                    const matchedWord = matched ? matched[0] : 'inappropriate term';
                    const msg = `The ${item.name} contains prohibited content in category: [${rule.category}]. Please remove "${matchedWord}" to proceed.`;
                    if (alertBox && alertMsg) {
                        alertMsg.innerHTML = `⚠️ <strong>${item.name}</strong> contains prohibited content in category <strong>[${rule.category}]</strong> ("${matchedWord}"). Please remove inappropriate terms to proceed.`;
                        alertBox.classList.remove('hidden');
                    }
                    return { passed: false, message: msg };
                }
            }
        }

        if (alertBox) alertBox.classList.add('hidden');
        return { passed: true };
    }

    // Step Validation
    function validateStep(step) {
        let isValid = true;

        if (step === 1) {
            const name = document.getElementById('propName');
            const city = document.getElementById('propCity');
            const area = document.getElementById('propArea');
            const address = document.getElementById('propAddress');

            if (!name.value.trim() || name.value.trim().length < 3) {
                showError('propName', 'Please enter a valid property title (at least 3 characters).');
                isValid = false;
            }
            if (!city.value.trim() || city.value.trim().length < 2) {
                showError('propCity', 'Please enter a valid city name.');
                isValid = false;
            }
            if (!area.value.trim()) {
                showError('propArea', 'Please specify area or sector.');
                isValid = false;
            }
            if (!address.value.trim() || address.value.trim().length < 5) {
                showError('propAddress', 'Please enter a complete address.');
                isValid = false;
            }

            const mod = checkClientModeration();
            if (!mod.passed) {
                alert(mod.message);
                isValid = false;
            }
        }

        if (step === 2) {
            const totalBeds = document.getElementById('propTotalBeds');
            if (totalBeds && (Number(totalBeds.value) < 1 || isNaN(totalBeds.value))) {
                alert('Total bed capacity must be at least 1.');
                isValid = false;
            }
        }

        if (step === 3) {
            const rent = document.getElementById('propRent');
            const desc = document.getElementById('propDescription');
            const rules = document.getElementById('propRules');

            if (!rent.value || Number(rent.value) < 500) {
                showError('propRent', 'Monthly starting rent cannot be less than ₹500.');
                isValid = false;
            }

            if (!desc.value.trim() || desc.value.trim().length < 20) {
                showError('propDescription', 'Please enter a detailed description (at least 20 characters).');
                isValid = false;
            }

            if (!rules.value.trim() || rules.value.trim().length < 5) {
                showError('propRules', 'Please specify the house rules / tenant guidelines.');
                isValid = false;
            }

            const mod = checkClientModeration();
            if (!mod.passed) {
                alert(mod.message);
                isValid = false;
            }
        }

        return isValid;
    }

    function goToStep(step) {
        if (step < 1 || step > 4) return;

        // If moving forward, validate current step
        if (step > currentStep) {
            for (let s = currentStep; s < step; s++) {
                if (!validateStep(s)) return;
            }
        }

        currentStep = step;

        // Hide all panes
        document.querySelectorAll('.step-pane').forEach(el => el.classList.add('hidden'));
        const activePane = document.getElementById(`step-pane-${currentStep}`);
        if (activePane) activePane.classList.remove('hidden');

        if (currentStep === 1 && propertyMap) {
            setTimeout(() => { propertyMap.invalidateSize(); }, 200);
        }

        // Update Stepper fill
        const fillPercent = ((currentStep - 1) / 3) * 100;
        document.getElementById('progress-bar-fill').style.width = `${Math.max(10, fillPercent)}%`;

        // Update Stepper Nodes
        for (let i = 1; i <= 4; i++) {
            const node = document.getElementById(`step-node-${i}`);
            if (i === currentStep) {
                node.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-brand text-white shadow-md transition-all';
                node.innerHTML = i;
            } else if (i < currentStep) {
                node.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-emerald-600 text-white transition-all';
                node.innerHTML = '<i class="fas fa-check"></i>';
            } else {
                node.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-600 transition-all';
                node.innerHTML = i;
            }
        }

        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    function handleTypeChange(type) {
        document.querySelectorAll('.type-pill').forEach(pill => pill.classList.remove('active'));
        const checkedRadio = document.querySelector(`input[name="listing_type"][value="${type}"]`);
        if (checkedRadio) {
            checkedRadio.closest('label').querySelector('.type-pill').classList.add('active');
        }

        const pgGender = document.getElementById('pgGenderField');
        const pgRoomConfigs = document.getElementById('pgRoomConfigs');
        const flatConfigs = document.getElementById('flatConfigs');

        if (type === 'pg-hostel' || type === 'co-living') {
            if (pgGender) pgGender.classList.remove('hidden');
            if (pgRoomConfigs) pgRoomConfigs.classList.remove('hidden');
            if (flatConfigs) flatConfigs.classList.add('hidden');
        } else {
            if (pgGender) pgGender.classList.add('hidden');
            if (pgRoomConfigs) pgRoomConfigs.classList.add('hidden');
            if (flatConfigs) flatConfigs.classList.remove('hidden');
        }

        updateLivePreview();
    }

    function updateLivePreview() {
        const name = document.getElementById('propName').value.trim() || 'PG or Property Name';
        const city = document.getElementById('propCity').value.trim() || 'xxxx';
        const area = document.getElementById('propArea').value.trim() || 'xxxxx';
        const rent = document.getElementById('propRent').value.trim() || 'X,XXX';
        
        const typeRadio = document.querySelector('input[name="listing_type"]:checked');
        const typeVal = typeRadio ? typeRadio.value : 'pg-hostel';
        
        const genderRadio = document.querySelector('input[name="gender_preference"]:checked');
        const genderVal = genderRadio ? genderRadio.value : 'co-ed';

        document.getElementById('previewTitle').innerText = name;
        document.getElementById('previewLocation').innerHTML = `<i class="fas fa-map-marker-alt text-brand"></i> ${area}, ${city}`;
        document.getElementById('previewRent').innerText = `₹${Number(rent >= 500 ? rent : (rent > 0 ? rent : 0)).toLocaleString('en-IN')}`;

        const typeBadge = document.getElementById('previewTypeBadge');
        if (typeVal === 'pg-hostel') typeBadge.innerText = 'PG / Hostel';
        else if (typeVal === 'co-living') typeBadge.innerText = 'Co-Living';
        else if (typeVal === 'flat-apartment') typeBadge.innerText = 'Flat / House';
        else typeBadge.innerText = 'Commercial';

        const genderBadge = document.getElementById('previewGenderBadge');
        if (genderVal === 'boys') genderBadge.innerText = 'Boys Only';
        else if (genderVal === 'girls') genderBadge.innerText = 'Girls Only';
        else genderBadge.innerText = 'Co-ed';
    }

    // Multiple Photo Uploads (Accumulates without clearing)
    function handlePhotoUpload(input) {
        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File "${file.name}" is larger than 5MB.`);
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedPhotos.push(e.target.result);
                    renderPhotoPreviews();
                };
                reader.readAsDataURL(file);
            });
            input.value = ''; // Reset input to allow selecting again
        }
    }

    function handlePhotoDrop(e) {
        if (e.dataTransfer && e.dataTransfer.files.length > 0) {
            Array.from(e.dataTransfer.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        uploadedPhotos.push(ev.target.result);
                        renderPhotoPreviews();
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    function addPhotoUrl() {
        const urlInput = document.getElementById('photoUrlInput');
        const url = urlInput.value.trim();
        if (url) {
            uploadedPhotos.push(url);
            urlInput.value = '';
            renderPhotoPreviews();
        }
    }

    function setCoverPhoto(index) {
        if (index > 0 && index < uploadedPhotos.length) {
            const item = uploadedPhotos.splice(index, 1)[0];
            uploadedPhotos.unshift(item);
            renderPhotoPreviews();
        }
    }

    function renderPhotoPreviews() {
        const grid = document.getElementById('photoPreviewGrid');
        const countBadge = document.getElementById('photoCountBadge');
        const errPhotos = document.getElementById('err-photos');
        if (!grid) return;

        grid.innerHTML = '';
        if (countBadge) countBadge.innerText = `${uploadedPhotos.length} Photo${uploadedPhotos.length === 1 ? '' : 's'} Added`;
        if (errPhotos && uploadedPhotos.length > 0) errPhotos.classList.add('hidden');

        if (uploadedPhotos.length === 0) {
            grid.innerHTML = '<div class="col-span-2 sm:col-span-4 p-6 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 text-center text-xs text-gray-400"><i class="fas fa-images text-2xl text-gray-300 mb-2 block"></i>No photos uploaded yet. Select images above to add.</div>';
            document.getElementById('previewImage').src = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
            return;
        }

        uploadedPhotos.forEach((url, idx) => {
            const div = document.createElement('div');
            div.className = 'relative group rounded-2xl overflow-hidden aspect-video border-2 ' + (idx === 0 ? 'border-brand shadow-sm' : 'border-gray-200');
            div.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover cursor-pointer" onclick="setCoverPhoto(${idx})" title="Click to make cover photo">
                ${idx === 0 
                    ? '<span class="absolute bottom-2 left-2 bg-brand text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow"><i class="fas fa-star mr-0.5"></i> Cover</span>' 
                    : `<button type="button" onclick="setCoverPhoto(${idx})" class="absolute bottom-2 left-2 bg-black/60 hover:bg-black text-white text-[10px] px-2 py-0.5 rounded-md opacity-0 group-hover:opacity-100 transition">Set Cover</button>`
                }
                <button type="button" onclick="removePhoto(${idx})" class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-xs opacity-90 group-hover:opacity-100 shadow transition">
                    <i class="fas fa-times"></i>
                </button>
            `;
            grid.appendChild(div);
        });

        if (uploadedPhotos.length > 0) {
            document.getElementById('previewImage').src = uploadedPhotos[0];
        }
    }

    function removePhoto(index) {
        uploadedPhotos.splice(index, 1);
        renderPhotoPreviews();
    }

    async function checkForEditMode() {
        const urlParams = new URLSearchParams(window.location.search);
        const editId = urlParams.get('edit_id') || urlParams.get('id');
        if (!editId) return;

        editPropertyId = editId;

        // Update hero banner and submit buttons for Edit Mode
        const bannerTitle = document.querySelector('#listPropertyHeroBanner h1');
        if (bannerTitle) {
            bannerTitle.innerHTML = `Edit Your Property Listing on <span class="text-brand-light">StayNest</span>`;
        }

        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.innerHTML = `
                <span class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>Save & Update Listing</span>
                    <i id="submitSpinner" class="fas fa-spinner fa-spin hidden"></i>
                </span>
            `;
        }

        // Add prominent Edit Mode alert banner above form with Quick Save Button
        const formContainer = document.getElementById('propertyListingForm');
        if (formContainer && formContainer.parentNode) {
            const existingAlert = document.getElementById('editModeAlertBanner');
            if (!existingAlert) {
                const editBanner = document.createElement('div');
                editBanner.id = 'editModeAlertBanner';
                editBanner.className = 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6';
                editBanner.innerHTML = `
                    <div class="bg-amber-500/15 border border-amber-500/30 text-amber-900 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                                <i class="fas fa-pen-to-square"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-extrabold uppercase tracking-wider text-amber-800">Edit Mode Active</div>
                                <div class="text-sm font-bold text-gray-900" id="editingPropTitle">Loading property details...</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="handleListingSubmit()" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-brand/20 transition tap-effect flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-check-circle"></i> Save Changes Now
                            </button>
                            <a href="{{ route('broker.pgs') }}" class="px-3.5 py-2.5 rounded-xl bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 border border-gray-200 transition shadow-xs">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                    </div>
                `;
                formContainer.parentNode.insertBefore(editBanner, formContainer);
            }
        }

        // Fetch property details from API
        try {
            const res = await fetch(`/api/v1/properties/details/${editPropertyId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success && data.data) {
                populateFormWithProperty(data.data);
            }
        } catch(err) {
            console.error('Failed to load edit property data:', err);
        }
    }

    function populateFormWithProperty(p) {
        const titleEl = document.getElementById('editingPropTitle');
        if (titleEl) titleEl.innerText = p.name;

        // Step 1: Basic Info & Location
        const nameInput = document.getElementById('propName');
        const cityInput = document.getElementById('propCity');
        const areaInput = document.getElementById('propArea');
        const addressInput = document.getElementById('propAddress');
        const landmarkInput = document.getElementById('propLandmark');
        const pincodeInput = document.getElementById('propPincode');
        const latInput = document.getElementById('propLatitude');
        const lngInput = document.getElementById('propLongitude');

        if (nameInput) nameInput.value = p.name || '';
        if (cityInput) cityInput.value = p.city || '';
        if (areaInput) areaInput.value = p.area || '';
        if (addressInput) addressInput.value = p.address || '';
        if (landmarkInput) landmarkInput.value = p.landmark || '';
        if (pincodeInput) pincodeInput.value = p.pincode || '';
        if (latInput && p.latitude) latInput.value = p.latitude;
        if (lngInput && p.longitude) lngInput.value = p.longitude;

        if (p.latitude && p.longitude && propertyMap && propertyMarker) {
            const lat = Number(p.latitude);
            const lng = Number(p.longitude);
            propertyMap.setView([lat, lng], 17);
            propertyMarker.setLatLng([lat, lng]);
            propertyMarker.bindPopup(`<strong>${p.name}</strong><br>${p.address || ''}`).openPopup();
        }

        // Step 2: Listing Type & Gender
        if (p.listing_type) {
            const radio = document.querySelector(`input[name="listing_type"][value="${p.listing_type}"]`);
            if (radio) {
                radio.checked = true;
                handleTypeChange(p.listing_type);
            }
        }

        if (p.gender_preference) {
            const gRadio = document.querySelector(`input[name="gender_preference"][value="${p.gender_preference}"]`);
            if (gRadio) gRadio.checked = true;
        }

        // Step 2 & 3: Pricing & Beds
        const rentInput = document.getElementById('propRent');
        const depositInput = document.getElementById('propDeposit');
        const totalBedsInput = document.getElementById('propTotalBeds');
        const availBedsInput = document.getElementById('propAvailableBeds');
        const descInput = document.getElementById('propDescription');
        const rulesInput = document.getElementById('propRules');

        if (rentInput) rentInput.value = p.monthly_rent || '';
        if (depositInput) depositInput.value = p.security_deposit || '';
        if (totalBedsInput) totalBedsInput.value = p.total_beds || '';
        if (availBedsInput) availBedsInput.value = p.available_beds || '';
        if (descInput) descInput.value = p.description || '';
        if (rulesInput) rulesInput.value = p.house_rules || '';

        // Amenities
        if (p.amenities && Array.isArray(p.amenities)) {
            document.querySelectorAll('input[name="amenities[]"]').forEach(cb => {
                cb.checked = p.amenities.includes(cb.value);
            });
        }

        // Photos
        if (p.photos && Array.isArray(p.photos) && p.photos.length > 0) {
            uploadedPhotos = [...p.photos];
            renderPhotoPreviews();
        }

        // Step 4: Owner Info (Retain locked profile details if authenticated)
        const ownerNameInput = document.getElementById('ownerName');
        const ownerPhoneInput = document.getElementById('ownerPhone');
        const ownerEmailInput = document.getElementById('ownerEmail');

        const isAuthUser = {{ $isLoggedIn ? 'true' : 'false' }};
        if (!isAuthUser) {
            if (ownerNameInput && p.owner_name) ownerNameInput.value = p.owner_name;
            if (ownerPhoneInput && p.owner_phone) ownerPhoneInput.value = p.owner_phone;
            if (ownerEmailInput && p.owner_email) ownerEmailInput.value = p.owner_email;
        } else {
            if (ownerNameInput && !ownerNameInput.value && p.owner_name) ownerNameInput.value = p.owner_name;
            if (ownerPhoneInput && !ownerPhoneInput.value && p.owner_phone) ownerPhoneInput.value = p.owner_phone;
            if (ownerEmailInput && !ownerEmailInput.value && p.owner_email) ownerEmailInput.value = p.owner_email;
        }

        updateLocationSummaryCard(p.city, p.area, p.address, p.pincode);
        updateLivePreview();
    }

    function initLoggedInContact() {
        const isAuthUser = {{ $isLoggedIn ? 'true' : 'false' }};
        const ownerNameInput = document.getElementById('ownerName');
        const ownerPhoneInput = document.getElementById('ownerPhone');
        const ownerEmailInput = document.getElementById('ownerEmail');

        if (isAuthUser) return; // Already handled server-side by Blade

        try {
            const rawUser = localStorage.getItem('staynest_user') || localStorage.getItem('broker_user');
            if (rawUser) {
                const user = JSON.parse(rawUser);
                const name = user.name || user.full_name || (user.profile ? (user.profile.first_name + ' ' + (user.profile.last_name || '')) : '');
                const phone = user.phone || '';
                const email = user.email || '';

                if (name && ownerNameInput && !ownerNameInput.value) {
                    ownerNameInput.value = name;
                    ownerNameInput.readOnly = true;
                    ownerNameInput.className = 'w-full bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none transition select-none';
                }
                if (phone && ownerPhoneInput && !ownerPhoneInput.value) {
                    ownerPhoneInput.value = phone;
                    ownerPhoneInput.readOnly = true;
                    ownerPhoneInput.className = 'w-full bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border border-gray-200 rounded-xl pl-14 pr-10 py-3 text-sm focus:outline-none transition select-none';
                }
                if (email && ownerEmailInput && !ownerEmailInput.value) {
                    ownerEmailInput.value = email;
                    ownerEmailInput.readOnly = true;
                    ownerEmailInput.className = 'w-full bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border border-gray-200 rounded-xl px-4 pr-10 py-3 text-sm focus:outline-none transition select-none';
                }
            }
        } catch(e) {}
    }

    async function handleListingSubmit() {
        // Collect DOM elements
        const nameEl = document.getElementById('propName');
        const cityEl = document.getElementById('propCity');
        const areaEl = document.getElementById('propArea');
        const addrEl = document.getElementById('propAddress');
        const landEl = document.getElementById('propLandmark');
        const pinEl = document.getElementById('propPincode');
        const latEl = document.getElementById('propLatitude');
        const lngEl = document.getElementById('propLongitude');
        const rentEl = document.getElementById('propRent');
        const depEl = document.getElementById('propDeposit');
        const maintEl = document.getElementById('propMaintenance');
        const tBedsEl = document.getElementById('propTotalBeds');
        const aBedsEl = document.getElementById('propAvailableBeds');
        const descEl = document.getElementById('propDescription');
        const rulesEl = document.getElementById('propRules');
        const ownerNameEl = document.getElementById('ownerName');
        const ownerPhoneEl = document.getElementById('ownerPhone');
        const ownerEmailEl = document.getElementById('ownerEmail');

        // Validation Checks
        if (!nameEl?.value.trim() || nameEl.value.trim().length < 2) {
            goToStep(1);
            showError('propName', 'Please enter a valid property title.');
            nameEl?.focus();
            return;
        }

        if (!cityEl?.value.trim() || cityEl.value.trim().length < 2) {
            goToStep(1);
            showError('propCity', 'Please enter a valid city name.');
            cityEl?.focus();
            return;
        }

        if (!addrEl?.value.trim() || addrEl.value.trim().length < 3) {
            goToStep(1);
            showError('propAddress', 'Please enter the street address.');
            addrEl?.focus();
            return;
        }

        if (!rentEl?.value || Number(rentEl.value) < 100) {
            goToStep(3);
            showError('propRent', 'Please specify a starting monthly rent.');
            rentEl?.focus();
            return;
        }

        // Moderation Check
        const modCheck = checkClientModeration();
        if (!modCheck.passed) {
            alert(modCheck.message);
            return;
        }

        const typeRadio = document.querySelector('input[name="listing_type"]:checked');
        const genderRadio = document.querySelector('input[name="gender_preference"]:checked');
        const noticeSelect = document.querySelector('select[name="notice_period_days"]');

        const phoneRaw = (ownerPhoneEl?.value || '').replace(/\D/g, '');
        const finalPhone = phoneRaw.length >= 10 ? phoneRaw.slice(-10) : '9876543210';

        // Collect checked amenities
        const amenities = [];
        document.querySelectorAll('input[name="amenities[]"]:checked').forEach(cb => {
            amenities.push(cb.value);
        });

        const depositVal = depEl?.value ? Number(depEl.value) : null;
        const maintVal = maintEl?.value ? Number(maintEl.value) : 0;
        const noticeVal = noticeSelect?.value ? Number(noticeSelect.value) : 30;

        const descriptionVal = descEl?.value.trim() || 'Premium accommodation and paying guest stay in prime location with full amenities.';
        const rulesVal = rulesEl?.value.trim() || '• Gate closes at 11:00 PM\n• No smoking inside rooms\n• Maintain cleanliness';

        const payload = {
            listing_type: typeRadio ? typeRadio.value : 'pg-hostel',
            name: nameEl.value.trim(),
            city: cityEl.value.trim(),
            area: areaEl?.value.trim() || '',
            address: addrEl.value.trim(),
            landmark: landEl?.value.trim() || '',
            pincode: pinEl?.value.trim() || '',
            latitude: latEl?.value ? Number(latEl.value) : null,
            longitude: lngEl?.value ? Number(lngEl.value) : null,
            gender_preference: genderRadio ? genderRadio.value : 'co-ed',
            monthly_rent: Math.max(100, Number(rentEl.value)),
            security_deposit: depositVal,
            maintenance_charges: maintVal,
            notice_period_days: noticeVal,
            total_beds: Math.max(1, Number(tBedsEl?.value || 10)),
            available_beds: Math.max(0, Number(aBedsEl?.value || 0)),
            description: descriptionVal,
            house_rules: rulesVal,
            owner_name: ownerNameEl?.value.trim() || 'Property Manager',
            owner_phone: finalPhone,
            owner_email: ownerEmailEl?.value.trim() || null,
            amenities: amenities,
            photos: uploadedPhotos
        };

        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');

        if (submitBtn) submitBtn.disabled = true;
        if (spinner) spinner.classList.remove('hidden');

        const endpoint = editPropertyId 
            ? `/api/v1/properties/${editPropertyId}/update` 
            : '/api/v1/properties/submit';

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok && data.success) {
                const successModal = document.getElementById('successModal');
                if (editPropertyId) {
                    const heading = document.getElementById('successModalHeading');
                    const msg = document.getElementById('successModalMessage');
                    const badge = document.getElementById('successStatusBadge');
                    const tracking = document.getElementById('successTrackingId');

                    if (heading) heading.innerText = 'Property Listing Updated! 🎉';
                    if (msg) msg.innerText = `Changes for "${payload.name}" have been saved and applied.`;
                    if (tracking) tracking.innerText = 'UPDATED';
                    if (badge) badge.innerHTML = `<i class="fas fa-check-circle text-emerald-500"></i> Status: <span class="font-bold text-emerald-700">Live & Saved</span>`;
                } else {
                    const trackingId = data.data?.tracking_id || 'STAY-SUBMITTED';
                    const tracking = document.getElementById('successTrackingId');
                    if (tracking) tracking.innerText = trackingId;
                }

                if (successModal) successModal.classList.remove('hidden');
            } else {
                const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Submission failed.');
                alert('⚠️ Submission Blocked / Failed:\n' + errorMsg);
            }
        } catch (err) {
            console.error('Submission error:', err);
            alert('A network error occurred while submitting your listing. Please try again.');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
        }
    }
</script>
@endpush
