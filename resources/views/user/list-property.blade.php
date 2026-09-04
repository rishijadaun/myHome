@extends('user.layouts.app')

@php
    $currentPath = trim(request()->path(), '/');

    if ($currentPath === 'add-pg') {
        $pageHeading = 'Add PG Free Online';
        $pageHighlight = 'with Zero Brokerage';
        $pageTitle = 'Add PG Free - Post Your PG Online with Zero Brokerage | StayNest';
        $pageDesc = 'Add your PG or hostel on StayNest for FREE. Reach 50,000+ verified students and working professionals with zero brokerage fees. 24h fast verification.';
        $pageKeywords = 'add PG free, add PG online, post PG free, PG owner registration, zero brokerage PG listing, list hostel free, StayNest';
        $defaultTypeSlug = 'pg-hostel';
    } elseif ($currentPath === 'list-pg-free') {
        $pageHeading = 'List PG Free Online';
        $pageHighlight = 'Zero Brokerage Host Listing';
        $pageTitle = 'List PG Free - Post Paying Guest & Hostel with Zero Brokerage | StayNest';
        $pageDesc = 'List your paying guest accommodation, hostel, or co-living stay on StayNest for free. Get direct phone and WhatsApp inquiries from verified tenants.';
        $pageKeywords = 'list PG free, list PG online, post PG free, free PG listing, list hostel free, StayNest PG listing';
        $defaultTypeSlug = 'pg-hostel';
    } elseif ($currentPath === 'post-your-property' || $currentPath === 'list-your-property') {
        $pageHeading = 'List Your Property Free';
        $pageHighlight = 'Direct Landlord Listing';
        $pageTitle = 'List Your Property Free - Post PG, Flat & Commercial Space | StayNest';
        $pageDesc = 'Post your property online for free on StayNest. List PGs, residential flats, builder floors, and commercial shops with 0% brokerage forever.';
        $pageKeywords = 'list your property,rent your property faster, list your property free, post your property online, landlord property listing, zero brokerage property listing';
        $defaultTypeSlug = 'pg-hostel';
    } elseif ($currentPath === 'post-property' || $currentPath === 'post-property-free') {
        $pageHeading = 'Post Property Free';
        $pageHighlight = 'Reach 50,000+ Verified Tenants';
        $pageTitle = 'Post Property Free - List PG, Flat & Commercial Space Online | StayNest';
        $pageDesc = 'Post your rental property online for free. Connect directly with students, IT professionals, and families looking for rent with zero middleman commissions.';
        $pageKeywords = 'post property free, post property online, free rental property post, landlord registration, StayNest property listing';
        $defaultTypeSlug = 'pg-hostel';
    } elseif ($currentPath === 'add-property') {
        $pageHeading = 'Add Property Online Free';
        $pageHighlight = 'Zero Brokerage Direct Listing';
        $pageTitle = 'Add Property Free - Post PG, Flat & Commercial Space Online | StayNest';
        $pageDesc = 'Add your property to India\'s fastest growing verified rental discovery network. Zero listing fees, verified leads, instant 24h approval.';
        $pageKeywords = 'add property free, add property online, post property for rent, zero brokerage listing, StayNest';
        $defaultTypeSlug = 'pg-hostel';
    } elseif ($currentPath === 'post-flat') {
        $pageHeading = 'Post Flat & Apartment Free';
        $pageHighlight = 'For Rent with Zero Brokerage';
        $pageTitle = 'Post Flat Free - Rent 1BHK, 2BHK, 3BHK & Villas Online | StayNest';
        $pageDesc = 'Post your residential flat, apartment, villa, or builder floor for rent on StayNest with zero brokerage. Reach families and working bachelors directly.';
        $pageKeywords = 'post flat free, list flat for rent, rent apartment online, 1BHK flat for rent, 2BHK flat for rent, zero brokerage flat listing';
        $defaultTypeSlug = 'flat-apartment';
    } else {
        $pageHeading = 'List Your Property & PG';
        $pageHighlight = 'on StayNest Free';
        $pageTitle = 'List Your Property Free - Post PG, Hostel, Flat & Commercial Space Online | Zero Brokerage | StayNest';
        $pageDesc = 'List PG, Hostel, Flat & Commercial property for rent on StayNest for FREE. Post property online, reach 50,000+ verified tenants with 0% brokerage. Instant direct tenant calls & 24h verification.';
        $pageKeywords = 'list PG free, list your property, post your property, post property free, add PG online, post flat for rent, list hostel free, post commercial space, zero brokerage property listing, StayNest landlord registration, add property free India, PG owner registration';
        $defaultTypeSlug = 'pg-hostel';
    }
@endphp

@section('title', $pageTitle)
@section('meta_description', $pageDesc)
@section('meta_keywords', $pageKeywords)
@section('meta_image', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80')
@section('canonical', url()->current())

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Service",
      "@id": "{{ route('user.list-property') }}#service",
      "name": "StayNest Zero Brokerage Property Listing Service",
      "serviceType": "Real Estate Rental & PG Listing Service",
      "description": "Free platform for property owners, landlords, and PG managers to post paying guest accommodations, hostels, flats, and commercial properties with zero brokerage fees.",
      "provider": {
        "@type": "Organization",
        "name": "StayNest",
        "url": "{{ route('user.home') }}",
        "logo": "{{ asset('images/favicon.png') }}"
      },
      "areaServed": "IN",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "INR",
        "availability": "https://schema.org/InStock",
        "description": "100% Free Property & PG Listing with Instant Verification"
      }
    },
    {
      "@type": "HowTo",
      "@id": "{{ route('user.list-property') }}#howto",
      "name": "How to List Your PG or Property on StayNest in 3 Steps",
      "description": "Quick guide on posting your PG, flat, or commercial space online with zero brokerage.",
      "totalTime": "PT3M",
      "step": [
        {
          "@type": "HowToStep",
          "position": 1,
          "name": "Select Property Type & Location",
          "text": "Choose PG/Hostel, Flat/Apartment, or Commercial Space and pin your exact GPS location."
        },
        {
          "@type": "HowToStep",
          "position": 2,
          "name": "Set Rent, Room Sharing & Amenities",
          "text": "Specify monthly rent, security deposit, food options, WiFi, AC, and house rules."
        },
        {
          "@type": "HowToStep",
          "position": 3,
          "name": "Upload Photos & Submit",
          "text": "Add real property photos and submit for 24-hour verification to receive direct tenant calls."
        }
      ]
    },
    {
      "@type": "FAQPage",
      "@id": "{{ route('user.list-property') }}#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Is it really 100% free to list a PG or property on StayNest?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes! Listing your PG, hostel, flat, or commercial space on StayNest is completely free with zero listing fees and zero broker commissions."
          }
        },
        {
          "@type": "Question",
          "name": "How quickly will my property listing be verified?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Our dedicated verification team reviews and activates listings within 24 hours of submission."
          }
        },
        {
          "@type": "Question",
          "name": "Do I get direct contact details of tenants?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. Verified students and professionals will directly call and message you via phone and WhatsApp with zero intermediaries."
          }
        },
        {
          "@type": "Question",
          "name": "Can I post flats, apartments, and commercial offices too?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Absolutely. StayNest supports PGs, co-living hostels, 1BHK, 2BHK, 3BHK flats, independent houses, and commercial retail or office spaces."
          }
        }
      ]
    },
    {
      "@type": "BreadcrumbList",
      "@id": "{{ route('user.list-property') }}#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ route('user.home') }}"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "List Your Property Free",
          "item": "{{ route('user.list-property') }}"
        }
      ]
    }
  ]
}
</script>
@endpush

@push('styles')
<!-- Leaflet Map CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    /* ===================== PROPERTY TYPE SELECTOR CARDS (HOME PAGE MATCHING DESIGN) ===================== */
    .property-type-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .property-type-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -2px rgba(0,0,0,0.06);
    }
    .property-type-card.active-pg {
        border-color: #1fa37a !important;
        border-width: 2px !important;
        background-color: #eefaf6 !important;
        box-shadow: 0 4px 14px -2px rgba(31, 163, 122, 0.18) !important;
    }
    .property-type-card.active-flat {
        border-color: #4f46e5 !important;
        border-width: 2px !important;
        background-color: #eff4ff !important;
        box-shadow: 0 4px 14px -2px rgba(79, 70, 229, 0.18) !important;
    }
    .property-type-card.active-commercial {
        border-color: #ea580c !important;
        border-width: 2px !important;
        background-color: #fff9ed !important;
        box-shadow: 0 4px 14px -2px rgba(234, 88, 12, 0.18) !important;
    }
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

    /* ===================== STEPPER PROGRESS BAR & NODES ===================== */
    .stepper-progress-track {
        position: absolute;
        left: 20px;
        right: 20px;
        top: 24px;
        height: 4px;
        background-color: #e2e8f0;
        border-radius: 9999px;
        z-index: 1;
        pointer-events: none;
    }
    @media (min-width: 640px) {
        .stepper-progress-track {
            left: 48px;
            right: 48px;
        }
    }
    .stepper-progress-fill {
        height: 100%;
        background-color: #4bb59d;
        border-radius: 9999px;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stepper-node {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .stepper-node.active, .stepper-node.completed {
        background-color: #4bb59d !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(75, 181, 157, 0.35) !important;
    }
    .stepper-node.inactive {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        border: 1px solid #e2e8f0 !important;
    }
</style>
@endpush

@section('content')
@php
    $authUser = Auth::user();
    $isLoggedIn = !empty($authUser);
    $isAdmin = $authUser && ($authUser->roles()->whereIn('slug', ['super_admin', 'admin'])->exists() || ($authUser->role ?? '') === 'admin');
    $authName = $authUser?->profile?->first_name 
        ? ($authUser->profile->first_name . ' ' . ($authUser->profile->last_name ?? '')) 
        : ($authUser?->name ?? '');
    $authPhone = $authUser?->phone ?? '';
    $authEmail = $authUser?->email ?? '';
    // Regular brokers/users get auto-filled and locked to their own account. Admin is never locked to preserve original landlord details.
    $isLockedToAuth = $isLoggedIn && !$isAdmin;
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
                    {{ $pageHeading }} <span class="text-brand-light">{{ $pageHighlight }}</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-300 mb-6">
                    {{ $pageDesc }}
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
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-7 mb-8">
            <div class="flex items-start justify-between max-w-4xl mx-auto relative">
                <!-- Progress Line Behind Circles (Always visible) -->
                <div class="stepper-progress-track">
                    <div id="progress-bar-fill" class="stepper-progress-fill" style="width: 29%;"></div>
                </div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer flex-1 text-center" onclick="goToStep(1)">
                    <div id="step-node-1" class="stepper-node active">
                        1
                    </div>
                    <span id="step-label-1" class="block text-xs sm:text-sm font-bold text-slate-800 mt-3 text-center tracking-tight">Listing Type &amp; Location</span>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer flex-1 text-center" onclick="goToStep(2)">
                    <div id="step-node-2" class="stepper-node inactive">
                        2
                    </div>
                    <span id="step-label-2" class="block text-xs sm:text-sm font-bold text-slate-500 mt-3 text-center tracking-tight">Rooms &amp; Amenities</span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer flex-1 text-center" onclick="goToStep(3)">
                    <div id="step-node-3" class="stepper-node inactive">
                        3
                    </div>
                    <span id="step-label-3" class="block text-xs sm:text-sm font-bold text-slate-500 mt-3 text-center tracking-tight">Pricing &amp; Photos</span>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 flex flex-col items-center cursor-pointer flex-1 text-center" onclick="goToStep(4)">
                    <div id="step-node-4" class="stepper-node inactive">
                        4
                    </div>
                    <span id="step-label-4" class="block text-xs sm:text-sm font-bold text-slate-500 mt-3 text-center tracking-tight">Owner &amp; Submit</span>
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
                        
                        <!-- Listing Category & Type Selector -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <!-- Category Segmented Header -->
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Property Category *</h2>
                                    <p class="text-xs sm:text-sm text-gray-500">Pick what category of property you are listing. Sell or rent your property faster with StayNest.</p>
                                </div>
                            </div>

                            <!-- Category Segmented Control (Tabs matching screenshot) -->
                            <div class="grid grid-cols-3 gap-2 p-1.5 bg-gray-100/90 rounded-2xl mb-6">
                                <button type="button" onclick="selectPropertyCategory('residential')" id="catBtn_residential" class="category-tab-btn py-3 px-2 sm:px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all shadow-xs bg-white text-gray-900 border border-gray-200/80 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-home text-brand"></i>
                                    <span>Residential</span>
                                </button>
                                <button type="button" onclick="selectPropertyCategory('commercial')" id="catBtn_commercial" class="category-tab-btn py-3 px-2 sm:px-4 rounded-xl font-bold text-xs sm:text-sm transition-all text-gray-600 hover:text-gray-900 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-briefcase text-amber-500"></i>
                                    <span>Commercial</span>
                                </button>
                                <button type="button" onclick="selectPropertyCategory('land-plot')" id="catBtn_land-plot" class="category-tab-btn py-3 px-2 sm:px-4 rounded-xl font-bold text-xs sm:text-sm transition-all text-gray-600 hover:text-gray-900 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-vector-square text-indigo-500"></i>
                                    <span>Land / Plot</span>
                                    <span class="bg-rose-500 text-white text-[9px] px-1.5 py-0.2 rounded-full font-black hidden sm:inline">New</span>
                                </button>
                            </div>
                            <input type="hidden" id="propCategory" name="property_category" value="residential">

                            <!-- Looking To... (Rent / Sell Toggle matching screenshot) -->
                            <div class="mb-6 pb-6 border-b border-gray-100">
                                <label class="block text-xs sm:text-sm font-bold text-gray-800 mb-2.5">You're looking to... *</label>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="selectAdType('rent')" id="adTypeBtn_rent" class="ad-type-btn px-6 sm:px-8 py-3 rounded-2xl font-black text-xs sm:text-sm transition-all flex items-center gap-2 bg-brand text-white shadow-md shadow-brand/20 border-2 border-brand">
                                        <i class="fas fa-key text-xs"></i>
                                        <span>Rent</span>
                                    </button>
                                    <button type="button" onclick="selectAdType('sale')" id="adTypeBtn_sale" class="ad-type-btn px-6 sm:px-8 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center gap-2 bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200">
                                        <i class="fas fa-tags text-xs text-amber-500"></i>
                                        <span>Sell</span>
                                    </button>
                                </div>
                                <input type="hidden" id="propAdType" name="ad_type" value="rent">
                            </div>

                            <!-- Sub-Property Type Cards Grid -->
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-gray-800 mb-3" id="subTypeHeading">Select Property Type</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-4 max-w-3xl" id="listingTypeContainer">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="listing_type" value="pg-hostel" class="hidden" {{ ($defaultTypeSlug ?? 'pg-hostel') === 'pg-hostel' ? 'checked' : '' }} onchange="handleTypeChange('pg-hostel')">
                                        <div id="card-type-pg-hostel" class="property-type-card {{ ($defaultTypeSlug ?? 'pg-hostel') === 'pg-hostel' ? 'active-pg' : '' }} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-brand"><i class="fas fa-hotel"></i></div>
                                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">PG &amp; Hostels</p>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="listing_type" value="flat-apartment" class="hidden" {{ ($defaultTypeSlug ?? 'pg-hostel') === 'flat-apartment' ? 'checked' : '' }} onchange="handleTypeChange('flat-apartment')">
                                        <div id="card-type-flat-apartment" class="property-type-card {{ ($defaultTypeSlug ?? 'pg-hostel') === 'flat-apartment' ? 'active-flat' : '' }} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-indigo-500"><i class="fas fa-building"></i></div>
                                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Flats &amp; Apartments</p>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="listing_type" value="house-villa" class="hidden" {{ ($defaultTypeSlug ?? 'pg-hostel') === 'house-villa' ? 'checked' : '' }} onchange="handleTypeChange('house-villa')">
                                        <div id="card-type-house-villa" class="property-type-card {{ ($defaultTypeSlug ?? 'pg-hostel') === 'house-villa' ? 'active-flat' : '' }} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-emerald-600"><i class="fas fa-house-chimney"></i></div>
                                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">House / Villa</p>
                                        </div>
                                    </label>
                                </div>
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
                                    <span class="bg-brand text-white text-[9px] font-black px-2.5 py-0.5 rounded-full uppercase" id="addrBadgeTag">📍 PROPERTY LOCATION</span>
                                    <button type="button" onclick="openLocationModal(false)" class="text-xs font-bold text-brand hover:text-brand-dark flex items-center gap-1 bg-white px-2.5 py-1 rounded-lg border border-brand/30 shadow-2xs">
                                        <i class="fas fa-pen text-[10px]"></i> Edit Map
                                    </button>
                                </div>
                                <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug" id="locSummaryLine1">Location Not Set</p>
                                <p class="text-xs text-gray-600 mt-0.5 truncate" id="locSummaryLine2">Click 'Use Current GPS Location' or 'Edit Map' to pinpoint property location</p>
                                
                                <div class="mt-4 pt-3 border-t border-emerald-200/60 flex flex-col sm:flex-row items-center justify-between gap-2.5">
                                    <button type="button" id="useCurrentGpsBtn" onclick="useCurrentGpsDirect(this)" class="w-full sm:flex-1 bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-slate-900 text-white font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center gap-2 shadow-md shadow-brand/20 transition tap-effect">
                                        <i class="fas fa-crosshairs" id="useCurrentGpsIcon"></i>
                                        <span id="useCurrentGpsText">Use Current GPS Location</span>
                                    </button>
                                    <button type="button" id="useVerifiedHomeBtn" onclick="useVerifiedHomeAddressDirect()" class="w-full sm:w-auto bg-white border border-brand/40 hover:bg-brand-light text-brand font-extrabold py-2.5 px-3.5 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-2xs transition tap-effect whitespace-nowrap">
                                        <i class="fas fa-house-user"></i>
                                        <span id="verifiedHomeBtnText">Use Saved Address</span>
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
                                                <i class="fas fa-venus-mars mr-1"></i> Co-Living (Both)
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
                        
                        <!-- 1. Dynamic PG Sharing Options -->
                        <div id="pgRoomConfigs" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-door-open text-brand"></i> PG Room Sharing Types &amp; Rent
                                    </h2>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Select available sharing options and set monthly rent per bed.</p>
                                </div>
                                <span class="bg-brand/10 text-brand text-xs font-extrabold px-3 py-1 rounded-full uppercase">PG &amp; Hostel</span>
                            </div>

                            <div class="space-y-3">
                                <!-- Single Room -->
                                <div class="p-3.5 sm:p-4 border border-gray-200 rounded-2xl hover:border-brand/40 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white" id="card_room_single">
                                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                                        <input type="checkbox" id="chk_room_single" name="room_single" checked onchange="toggleRoomCardState('single')" class="w-5 h-5 text-brand rounded focus:ring-brand cursor-pointer">
                                        <div>
                                            <div class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                                <span>Single Occupancy (Private Room)</span>
                                                <span id="badge_status_single" class="bg-emerald-50 text-emerald-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-200">🟢 Vacant</span>
                                            </div>
                                            <div class="text-xs text-gray-500">1 person per room &bull; Personal space</div>
                                        </div>
                                    </label>
                                    <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                                        <select id="status_room_single" onchange="handleRoomStatusChange('single', this.value)" class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold rounded-xl px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer">
                                            <option value="available" selected>🟢 Vacant / Available</option>
                                            <option value="booked">🔴 Full / Booked</option>
                                        </select>
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-400 font-bold">₹</span>
                                            <input type="number" id="rent_room_single" min="500" max="1000000" maxlength="7" onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 1000000)" placeholder="Rent (₹)" value="12000" class="w-24 sm:w-28 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand font-semibold text-gray-800">
                                            <span class="text-[10px] text-gray-400">/mo</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Double Room -->
                                <div class="p-3.5 sm:p-4 border border-gray-200 rounded-2xl hover:border-brand/40 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white" id="card_room_double">
                                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                                        <input type="checkbox" id="chk_room_double" name="room_double" checked onchange="toggleRoomCardState('double')" class="w-5 h-5 text-brand rounded focus:ring-brand cursor-pointer">
                                        <div>
                                            <div class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                                <span>Double Sharing Room</span>
                                                <span id="badge_status_double" class="bg-emerald-50 text-emerald-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-200">🟢 Vacant</span>
                                            </div>
                                            <div class="text-xs text-gray-500">2 beds per room &bull; Shared accommodation</div>
                                        </div>
                                    </label>
                                    <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                                        <select id="status_room_double" onchange="handleRoomStatusChange('double', this.value)" class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold rounded-xl px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer">
                                            <option value="available" selected>🟢 Vacant / Available</option>
                                            <option value="booked">🔴 Full / Booked</option>
                                        </select>
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-400 font-bold">₹</span>
                                            <input type="number" id="rent_room_double" min="500" max="1000000" maxlength="7" onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 1000000)" placeholder="Rent (₹)" value="8500" class="w-24 sm:w-28 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand font-semibold text-gray-800">
                                            <span class="text-[10px] text-gray-400">/mo</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Triple Room -->
                                <div class="p-3.5 sm:p-4 border border-gray-200 rounded-2xl hover:border-brand/40 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white" id="card_room_triple">
                                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                                        <input type="checkbox" id="chk_room_triple" name="room_triple" onchange="toggleRoomCardState('triple')" class="w-5 h-5 text-brand rounded focus:ring-brand cursor-pointer">
                                        <div>
                                            <div class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                                <span>Triple Sharing Room</span>
                                                <span id="badge_status_triple" class="bg-emerald-50 text-emerald-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-200">🟢 Vacant</span>
                                            </div>
                                            <div class="text-xs text-gray-500">3 beds per room &bull; Budget friendly</div>
                                        </div>
                                    </label>
                                    <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                                        <select id="status_room_triple" onchange="handleRoomStatusChange('triple', this.value)" class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold rounded-xl px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer">
                                            <option value="available" selected>🟢 Vacant / Available</option>
                                            <option value="booked">🔴 Full / Booked</option>
                                        </select>
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-400 font-bold">₹</span>
                                            <input type="number" id="rent_room_triple" min="500" max="1000000" maxlength="7" onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 1000000)" placeholder="Rent (₹)" value="6500" class="w-24 sm:w-28 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand font-semibold text-gray-800">
                                            <span class="text-[10px] text-gray-400">/mo</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Four Sharing Room -->
                                <div class="p-3.5 sm:p-4 border border-gray-200 rounded-2xl hover:border-brand/40 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white" id="card_room_four">
                                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                                        <input type="checkbox" id="chk_room_four" name="room_four" onchange="toggleRoomCardState('four')" class="w-5 h-5 text-brand rounded focus:ring-brand cursor-pointer">
                                        <div>
                                            <div class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                                <span>Four Sharing Room</span>
                                                <span id="badge_status_four" class="bg-emerald-50 text-emerald-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-200">🟢 Vacant</span>
                                            </div>
                                            <div class="text-xs text-gray-500">4 beds per room &bull; Economical option</div>
                                        </div>
                                    </label>
                                    <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                                        <select id="status_room_four" onchange="handleRoomStatusChange('four', this.value)" class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold rounded-xl px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer">
                                            <option value="available" selected>🟢 Vacant / Available</option>
                                            <option value="booked">🔴 Full / Booked</option>
                                        </select>
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-400 font-bold">₹</span>
                                            <input type="number" id="rent_room_four" min="500" max="1000000" maxlength="7" onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 1000000)" placeholder="Rent (₹)" value="5000" class="w-24 sm:w-28 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs text-right focus:outline-none focus:ring-1 focus:ring-brand font-semibold text-gray-800">
                                            <span class="text-[10px] text-gray-400">/mo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Total Bed Capacity * <span class="text-[10px] text-gray-400 font-normal">(Max 5,000)</span></label>
                                    <input type="number" id="propTotalBeds" name="total_beds" min="1" max="5000" maxlength="4" onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 5000); clearError(this);" value="20" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                    <div class="error-msg hidden" id="err-propTotalBeds">Total bed capacity must be between 1 and 5,000.</div>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Currently Available Beds</label>
                                    <input type="number" id="propAvailableBeds" name="available_beds" min="0" max="5000" maxlength="4" onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 5000); clearError(this);" value="6" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                    <div class="error-msg hidden" id="err-propAvailableBeds">Available beds cannot exceed total bed capacity.</div>
                                </div>
                            </div>

                            <!-- Entire Property Fully Booked Toggle (Visible only in Edit Listing mode) -->
                            <div id="fullyBookedToggleContainer" class="hidden mt-4 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm flex-shrink-0 shadow-sm">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs sm:text-sm text-gray-900">Mark Property as 100% Fully Booked</div>
                                        <div class="text-[11px] text-gray-600">Sets available beds to 0 &amp; displays "Fully Booked / Sold Out" notice to tenants</div>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="propIsFullyBooked" name="is_fully_booked" value="1" onchange="handleFullyBookedToggle(this)" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </div>             
                        </div>

                        <!-- 2. Dynamic Flat / House Configuration -->
                        <div id="flatConfigs" class="hidden bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-building text-indigo-600"></i> Apartment &amp; Residential Specifications
                                    </h2>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Specify configuration, carpet area, furnishings, and floor details.</p>
                                </div>
                                <span class="bg-indigo-50 text-indigo-700 text-xs font-extrabold px-3 py-1 rounded-full uppercase">Residential</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                <div id="bhkTypeField">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">BHK / Space Configuration *</label>
                                    <select id="flatBhkType" name="bhk_type" onchange="updateLivePreview()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                        <option value="1 RK / Studio">1 RK / Studio</option>
                                        <option value="1 BHK">1 BHK</option>
                                        <option value="2 BHK" selected>2 BHK</option>
                                        <option value="3 BHK">3 BHK</option>
                                        <option value="4 BHK">4 BHK</option>
                                        <option value="5+ BHK / Luxury Villa">5+ BHK / Luxury Villa</option>
                                    </select>
                                </div>
                                <div id="furnishingField">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Furnishing Status *</label>
                                    <select id="flatFurnishing" name="furnishing_status" onchange="updateLivePreview()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                        <option value="Fully Furnished">Fully Furnished</option>
                                        <option value="Semi Furnished" selected>Semi Furnished</option>
                                        <option value="Unfurnished">Unfurnished (Raw Shell)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Carpet Area (sq ft) *</label>
                                    <input type="number" id="flatCarpetArea" name="carpet_area_sqft" min="0" max="500000" onkeydown="preventNegative(event)" oninput="sanitizePositive(this); updateLivePreview();" placeholder="e.g. 1150" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Bathrooms</label>
                                    <select id="flatBathrooms" name="bathrooms" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                        <option value="1">1 Bathroom</option>
                                        <option value="2" selected>2 Bathrooms</option>
                                        <option value="3">3 Bathrooms</option>
                                        <option value="4">4 Bathrooms</option>
                                        <option value="5+">5+ Bathrooms</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Balconies</label>
                                    <select id="flatBalconies" name="balconies" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                        <option value="0">0 Balconies</option>
                                        <option value="1">1 Balcony</option>
                                        <option value="2" selected>2 Balconies</option>
                                        <option value="3">3 Balconies</option>
                                        <option value="3+">3+ Balconies</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Property Floor</label>
                                    <input type="number" id="flatFloorNo" name="floor_number" min="0" max="150" placeholder="e.g. 4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Total Floors</label>
                                    <input type="number" id="flatTotalFloors" name="total_floors" min="1" max="150" placeholder="e.g. 14" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Facing Direction</label>
                                    <select id="flatFacing" name="facing_direction" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                        <option value="North-East" selected>North-East (Vastu Compliant)</option>
                                        <option value="East">East</option>
                                        <option value="North">North</option>
                                        <option value="North-West">North-West</option>
                                        <option value="West">West</option>
                                        <option value="South">South</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Society / Project Name (Optional)</label>
                                    <input type="text" id="flatProjectName" name="project_name" placeholder="e.g. ATS Greens / DLF Phase 5" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Dynamic Commercial Configuration -->
                        <div id="commercialConfigs" class="hidden bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-briefcase text-amber-500"></i> Commercial Space Specifications
                                    </h2>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Details about commercial office, retail shop, or warehouse space.</p>
                                </div>
                                <span class="bg-amber-50 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase">Commercial</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Commercial Space Type *</label>
                                    <select id="commSpaceType" name="commercial_space_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                        <option value="Ready-to-use Office" selected>Ready-to-use Office (Plug &amp; Play)</option>
                                        <option value="Bare Shell Office">Bare Shell / Raw Office</option>
                                        <option value="Retail Shop">Retail Shop (Ground Floor)</option>
                                        <option value="Showroom">Showroom / High Street Space</option>
                                        <option value="Warehouse / Godown">Warehouse / Industrial Godown</option>
                                        <option value="Cloud Kitchen / Restaurant">Cloud Kitchen / Restaurant</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Carpet Area (sq ft) *</label>
                                    <input type="number" id="commCarpetArea" min="0" max="5000000" onkeydown="preventNegative(event)" oninput="sanitizePositive(this); updateLivePreview();" placeholder="e.g. 1500" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Dedicated Washrooms</label>
                                    <select id="commWashrooms" name="commercial_washrooms" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                        <option value="Private Washroom" selected>Private Attached Washroom</option>
                                        <option value="Shared Floor Washroom">Shared Floor Washroom</option>
                                        <option value="Both Private &amp; Shared">Both Private &amp; Shared</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Floor Level &amp; Total Floors</label>
                                    <input type="text" id="commFloorDetails" name="commercial_floor" placeholder="e.g. 2nd Floor (of 8 Floors)" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Road Frontage / Width (ft)</label>
                                    <input type="number" id="commRoadFrontage" name="commercial_frontage" min="0" placeholder="e.g. 45 ft main road" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Reserved Car / Bike Parking</label>
                                    <input type="text" id="commParking" name="commercial_parking" placeholder="e.g. 2 Cars + 5 Bikes" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                </div>
                            </div>
                        </div>

                        <!-- 4. Dynamic Land / Plot Configuration -->
                        <div id="plotConfigs" class="hidden bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-vector-square text-teal-600"></i> Land &amp; Plot Specifications
                                    </h2>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Plot area, dimensions, facing road, and boundary details.</p>
                                </div>
                                <span class="bg-teal-50 text-teal-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase">Land / Plot</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Plot / Land Type *</label>
                                    <select id="plotLandType" name="plot_land_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500/50">
                                        <option value="Residential Plot" selected>Residential Plot (Gated Layout)</option>
                                        <option value="Commercial Land">Commercial Plot / Land</option>
                                        <option value="Industrial Land">Industrial Land / Factory Plot</option>
                                        <option value="Agricultural / Farmhouse">Agricultural / Farmhouse Land</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Plot Area (sq. yards / sq ft) *</label>
                                    <input type="number" id="plotCarpetArea" min="0" max="5000000" onkeydown="preventNegative(event)" oninput="sanitizePositive(this); updateLivePreview();" placeholder="e.g. 200 Sq. Yards" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500/50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Plot Dimensions (L x B in ft)</label>
                                    <input type="text" id="plotDimensions" name="plot_dimensions" placeholder="e.g. 30 x 60 ft" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500/50">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Open Sides</label>
                                    <select id="plotOpenSides" name="plot_open_sides" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-500/50">
                                        <option value="1 Side Open">1 Side Open</option>
                                        <option value="2 Sides Open (Corner Plot)" selected>2 Sides Open (Corner Plot)</option>
                                        <option value="3 Sides Open">3 Sides Open</option>
                                        <option value="4 Sides Open (Independent)">4 Sides Open (Independent Island)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Facing Road Width (ft)</label>
                                    <input type="number" id="plotRoadWidth" name="plot_road_width" min="0" placeholder="e.g. 40 ft road" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-500/50">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Boundary Wall</label>
                                    <select id="plotBoundaryWall" name="plot_boundary_wall" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-500/50">
                                        <option value="Constructed" selected>Boundary Wall Constructed</option>
                                        <option value="Not Constructed">No Boundary Wall</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Universal Amenities & Facilities Selector -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-concierge-bell text-brand"></i> Amenities &amp; Facilities
                                    </h2>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Select all amenities and highlights available at your property.</p>
                                </div>
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-extrabold px-3 py-1 rounded-full">Included</span>
                            </div>

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
                                        <span class="text-xs sm:text-sm font-semibold">CCTV &amp; 24x7 Security</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="lift" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-elevator text-sky-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Lift / Elevator</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="parking" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-square-parking text-blue-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Vehicle Parking (4W)</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="parking-2-wheeler" class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-motorcycle text-indigo-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">2-Wheeler Parking</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="almirah" class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-door-closed text-amber-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Almirah / Wardrobe</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="study-table" class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-chair text-purple-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Study Table &amp; Chair</span>
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
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="gym" class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-dumbbell text-rose-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Gym &amp; Fitness</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="swimming-pool" class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-person-swimming text-cyan-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Swimming Pool</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="refrigerator" checked class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-temperature-low text-cyan-600"></i>
                                        <span class="text-xs sm:text-sm font-semibold">Fridge / Refrigerator</span>
                                    </div>
                                </label>
                                <label class="amenity-chip cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="ev-charging" class="hidden">
                                    <div class="flex items-center gap-3 p-3.5 border-2 border-gray-200 rounded-2xl transition hover:border-brand/40">
                                        <i class="fas fa-charging-station text-emerald-500"></i>
                                        <span class="text-xs sm:text-sm font-semibold">EV Charging Station</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(1)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3.5 rounded-xl transition">
                                <i class="fas fa-arrow-left text-xs mr-1"></i> Back
                            </button>
                            <button type="button" onclick="goToStep(3)" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
                                Continue to Pricing &amp; Photos <i class="fas fa-arrow-right text-xs"></i>
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

                        <!-- Pricing & Financials (Rent vs Sell Dynamic) -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            
                            <!-- RENT PRICING CONTAINER -->
                            <div id="rentPricingContainer" class="space-y-4">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-rupee-sign text-brand"></i> Rental Pricing & Deposits
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Monthly Starting Rent (₹) * <span class="text-[10px] text-brand font-bold">(₹500 - ₹10,00,000)</span></label>
                                        <input type="number" id="propRent" name="monthly_rent" min="500" max="1000000" maxlength="7" placeholder="e.g. 6500" 
                                               onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 1000000); updateLivePreview(); clearError(this);" 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                        <div class="error-msg hidden" id="err-propRent">Monthly starting rent must be between ₹500 and ₹10,00,000.</div>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Security Deposit (₹) <span class="text-[10px] text-gray-400 font-normal">(Max ₹20,00,000)</span></label>
                                        <input type="number" id="propDeposit" name="security_deposit" min="0" max="2000000" maxlength="7" placeholder="e.g. 10000" 
                                               onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 2000000); clearError(this);" 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                                        <div class="error-msg hidden" id="err-propDeposit">Security deposit cannot exceed ₹20,00,000.</div>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Maintenance (₹ / mo) <span class="text-[10px] text-gray-400 font-normal">(Max ₹1,00,000)</span></label>
                                        <input type="number" id="propMaintenance" name="maintenance_charges" min="0" max="100000" maxlength="6" placeholder="e.g. 0" 
                                               onkeydown="preventNegative(event)" oninput="sanitizePositive(this, 100000); clearError(this);" 
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

                            <!-- SALE PRICING CONTAINER (Shown when Sell is chosen) -->
                            <div id="salePricingContainer" class="hidden space-y-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-tags text-amber-500"></i> Selling Price & Ownership Details
                                    </h2>
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                        Property For Sale
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mb-4">Set your expected selling valuation and property legal terms.</p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Expected Total Selling Price (₹) *</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-3.5 text-base font-bold text-gray-400">₹</span>
                                            <input type="number" id="propExpectedPrice" name="expected_price" min="10000" max="1000000000" placeholder="e.g. 4500000 (45 Lakh)" 
                                                   onkeydown="preventNegative(event)" oninput="handleSalePriceChange(this); updateLivePreview(); clearError(this);" 
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-3 text-base font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:bg-white transition">
                                        </div>
                                        <!-- Live Indian Words Converter (e.g. ₹ 45.00 Lakh / ₹ 1.25 Crore) -->
                                        <div id="salePriceInWords" class="text-xs font-bold text-amber-700 mt-1.5 flex items-center gap-1.5">
                                            <i class="fas fa-calculator text-[10px]"></i> <span id="priceWordsText">Enter price (e.g. ₹45 Lakh, ₹1.5 Crore)</span>
                                        </div>
                                        <div class="error-msg hidden" id="err-propExpectedPrice">Please enter the expected selling price.</div>
                                    </div>

                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Booking / Token Amount (₹)</label>
                                        <input type="number" id="propTokenAmount" name="booking_token_amount" min="0" max="100000000" placeholder="e.g. 100000" 
                                               onkeydown="preventNegative(event)" oninput="sanitizePositive(this); clearError(this);" 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:bg-white transition">
                                    </div>

                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Ownership Type</label>
                                        <select id="propOwnership" name="ownership_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                            <option value="Freehold" selected>Freehold (Clear Title)</option>
                                            <option value="Leasehold">Leasehold</option>
                                            <option value="Cooperative Society">Co-operative Society</option>
                                            <option value="Power of Attorney">Power of Attorney (POA)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Possession Status</label>
                                        <select id="propPossession" name="possession_status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                            <option value="Ready to Move" selected>Ready to Move (Immediate)</option>
                                            <option value="Under Construction (Within 3 Months)">Under Construction (Within 3 Months)</option>
                                            <option value="Under Construction (Within 6 Months)">Under Construction (Within 6 Months)</option>
                                            <option value="Under Construction (1-2 Years)">Under Construction (1-2 Years)</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center gap-3 pt-6 sm:pt-7">
                                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                            <input type="checkbox" id="propNegotiable" name="price_negotiable" value="1" class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                                            <span class="text-xs sm:text-sm font-bold text-gray-800">Price is Negotiable</span>
                                        </label>
                                    </div>
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
                                    0 / 10 Photos Added
                                </span>
                            </div>

                            <!-- Dropzone Box (Allows Multiple file selection, Maximum 10 photos) -->
                            <div onclick="document.getElementById('photoInput').click()" 
                                 ondragover="event.preventDefault(); this.classList.add('border-brand', 'bg-brand-50/20');"
                                 ondragleave="this.classList.remove('border-brand', 'bg-brand-50/20');"
                                 ondrop="event.preventDefault(); this.classList.remove('border-brand', 'bg-brand-50/20'); handlePhotoDrop(event);"
                                 class="border-2 border-dashed border-gray-300 hover:border-brand bg-gray-50/60 hover:bg-brand-50/20 rounded-2xl p-8 text-center cursor-pointer transition mb-4">
                                <input type="file" id="photoInput" multiple accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden" onchange="handlePhotoUpload(this)">
                                <div class="w-14 h-14 bg-brand/10 text-brand rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="text-sm font-bold text-gray-800 mb-1">Click to select photos or drag & drop</div>
                                <div class="text-xs text-gray-500">Supports JPG, PNG, WEBP (Maximum 10 photos only, max 5MB each)</div>
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
                                    <!-- <span class="text-[11px] text-gray-400">Required</span> -->
                                </div>
                                <p class="text-xs text-gray-500 mb-3">Set clear expectations for tenants. Click presets below to add quickly.</p>
                                
                                <!-- Preset Quick Chips -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <button type="button" onclick="appendRule('Opposite gender entry restricted to visiting hours in common areas')" class="text-xs font-semibold bg-gray-100 hover:bg-brand-light hover:text-brand border border-gray-200 px-3 py-1 rounded-lg transition">
                                        + Opposite Gender Policy
                                    </button>
                                    <button type="button" onclick="appendRule('No parties or loud gatherings allowed on premises')" class="text-xs font-semibold bg-gray-100 hover:bg-brand-light hover:text-brand border border-gray-200 px-3 py-1 rounded-lg transition">
                                        + No Party / Events
                                    </button>
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
                                @if($isLockedToAuth)
                                    <span id="ownerLockBadge" class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-3 py-1 rounded-full border border-emerald-300 flex items-center gap-1 shadow-xs">
                                        <i class="fas fa-lock text-[9px] text-emerald-600"></i> Locked to Logged-in Account
                                    </span>
                                @elseif($isAdmin)
                                    <span id="ownerLockBadge" class="bg-purple-100 text-purple-800 text-[10px] font-extrabold px-3 py-1 rounded-full border border-purple-300 flex items-center gap-1 shadow-xs">
                                        <i class="fas fa-user-shield text-[9px] text-purple-600"></i> Admin Mode &bull; Landlord Property Preserved
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-6">Our verification team and interested tenants will reach out to these contact details.</p>

                            @if($isLockedToAuth)
                                <!-- Verified Logged-in Account Badge -->
                                <div id="ownerProfileBanner" class="mb-5 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200/90 rounded-2xl p-4 flex items-center justify-between shadow-xs">
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
                            @elseif($isAdmin)
                                <div id="ownerProfileBanner" class="mb-5 bg-gradient-to-r from-purple-50 via-indigo-50 to-purple-50 border border-purple-200/90 rounded-2xl p-4 flex items-center justify-between shadow-xs">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center text-base font-bold shadow-xs shrink-0">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div>
                                            <div class="text-[11px] font-extrabold text-purple-900 uppercase tracking-wider flex items-center gap-1.5">
                                                <span>Admin Edit Access: Landlord Profile Preserved</span>
                                                <span class="bg-purple-200 text-purple-950 text-[9px] px-2 py-0.5 rounded-full font-black">ADMIN MODE</span>
                                            </div>
                                            <div id="adminOwnerPreview" class="text-xs font-bold text-gray-900 mt-0.5">Original property creator profile is preserved &bull; Admin details will NOT replace owner</div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-purple-700 font-semibold hidden sm:flex items-center gap-1">
                                        <i class="fas fa-shield-halved text-purple-600"></i> Admin Controls
                                    </span>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                        Owner / Landlord Full Name *
                                        @if($isLockedToAuth)
                                            <span class="text-[10px] text-emerald-700 font-bold ml-1">(Auto-Filled & Locked)</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="ownerName" name="owner_name" required 
                                               value="{{ $isLockedToAuth ? $authName : '' }}"
                                               {{ $isLockedToAuth ? 'readonly' : '' }}
                                               placeholder="e.g. Rajesh Sharma" 
                                               oninput="clearError(this)"
                                               class="w-full {{ $isLockedToAuth ? 'bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border-gray-200 select-none' : 'bg-gray-50 text-gray-900' }} border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                                        @if($isLockedToAuth)
                                            <span class="absolute right-4 top-3.5 text-gray-400 text-xs" title="Locked to logged-in profile"><i class="fas fa-lock"></i></span>
                                        @endif
                                    </div>
                                    <div class="error-msg hidden" id="err-ownerName">Please enter owner / contact person name.</div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                            Mobile Phone Number (10 Digits) *
                                            @if($isLockedToAuth)
                                                <span class="text-[10px] text-emerald-700 font-bold ml-1">(Auto-Filled & Locked)</span>
                                            @endif
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-3.5 text-sm font-semibold text-gray-500">+91</span>
                                            <input type="tel" id="ownerPhone" name="owner_phone" required 
                                                   value="{{ $isLockedToAuth ? $authPhone : '' }}"
                                                   {{ $isLockedToAuth ? 'readonly' : '' }}
                                                   placeholder="98765 43210" maxlength="10"
                                                   onkeydown="preventNegative(event)" oninput="sanitizePhone(this); clearError(this);"
                                                   class="w-full {{ $isLockedToAuth ? 'bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border-gray-200 select-none' : 'bg-gray-50 text-gray-900' }} border border-gray-200 rounded-xl pl-14 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                                            @if($isLockedToAuth)
                                                <span class="absolute right-4 top-3.5 text-gray-400 text-xs" title="Locked to logged-in profile"><i class="fas fa-lock"></i></span>
                                            @endif
                                        </div>
                                        <div class="error-msg hidden" id="err-ownerPhone">Please enter a valid 10-digit Indian mobile number.</div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-xs sm:text-sm font-semibold text-gray-700">
                                                Email Address (Gmail / Email) *
                                                @if($isLockedToAuth)
                                                    <span class="text-[10px] text-emerald-700 font-bold ml-1">(Auto-Filled & Verified)</span>
                                                @else
                                                    <!-- <span class="text-[10px] text-brand font-bold ml-1">(6-digit OTP will be sent here)</span> -->
                                                @endif
                                            </label>
                                            <span id="emailVerifyBadge" class="hidden text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 items-center gap-1">
                                                <i class="fas fa-check-circle text-emerald-600"></i> Verified
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="email" id="ownerEmail" name="owner_email" required 
                                                   value="{{ $isLockedToAuth ? $authEmail : '' }}"
                                                   {{ $isLockedToAuth ? 'readonly' : '' }}
                                                   placeholder="rajesh@gmail.com" 
                                                   oninput="clearError(this); if (typeof resetEmailVerification === 'function') resetEmailVerification();"
                                                   class="w-full {{ $isLockedToAuth ? 'bg-gray-100/80 text-gray-800 font-semibold cursor-not-allowed border-gray-200 select-none' : 'bg-gray-50 text-gray-900' }} border border-gray-200 rounded-xl px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                                            @if($isLockedToAuth)
                                                <span class="absolute right-4 top-3.5 text-gray-400 text-xs" title="Locked to logged-in profile"><i class="fas fa-lock"></i></span>
                                            @endif
                                        </div>
                                        <div class="error-msg hidden" id="err-ownerEmail">Please enter a valid Gmail / Email address to receive your OTP.</div>
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
                                <div class="font-bold text-sm text-amber-950">Review &  Approval Workflow</div>
                                <div>Upon submission, your listing will be reviewed by the StayNest admin team within 24 hours to ensure quality and prevent spam. Once verified, it will be instantly published live to thousands of searchers.</div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(3)" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3.5 rounded-xl transition">
                                <i class="fas fa-arrow-left text-xs mr-1"></i> Back
                            </button>
                            <button type="submit" id="submitBtn" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-slate-900 text-white font-bold px-10 py-4 rounded-2xl shadow-xl shadow-brand/30 transition flex items-center gap-3">
                                <span id="submitSpinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
                                <i class="fas fa-paper-plane"></i> Submit for  Approval
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
                            <img id="previewImage" src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Property listing preview" class="w-full h-full object-cover">
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
                                <i class="fas fa-map-marker-alt text-brand"></i> ****
                            </p>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200/60">
                                <div>
                                    <span class="text-[10px] text-gray-400 block uppercase">Starting From</span>
                                    <span id="previewRent" class="text-base font-extrabold text-brand">₹*,***</span><span class="text-xs text-gray-500">/mo</span>
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
                    <span id="mapCoordinatesBadge" class="text-gray-300 font-mono text-[10px]">Lat: --, Lng: --</span>
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

<!-- ================= EMAIL OTP VERIFICATION MODAL ================= -->
<div id="listingOtpModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center shadow-2xl relative animate-in fade-in zoom-in duration-300 border border-gray-100">
        <!-- Close Button -->
        <button type="button" onclick="closeOtpModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-gray-100 text-gray-400 hover:text-gray-700 hover:bg-gray-200 flex items-center justify-center transition">
            <i class="fas fa-times"></i>
        </button>

        <div class="w-16 h-16 bg-brand-light/30 text-brand rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 shadow-sm border border-brand/20">
            <i class="fas fa-envelope-circle-check"></i>
        </div>

        <h3 class="text-xl font-extrabold text-gray-900 mb-1.5">Verify Your Email ID</h3>
        <p class="text-xs text-gray-600 mb-4 leading-relaxed">
            We've sent a 6-digit verification code to<br/>
            <span id="otpModalTargetEmail" class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-md inline-block mt-1">owner@gmail.com</span>
        </p>

        <!-- OTP Input Box -->
        <div class="mb-5">
            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-2">Enter 6-Digit OTP</label>
            <div class="flex justify-center gap-2 sm:gap-2.5" id="otpInputContainer">
                <input type="text" maxlength="1" class="otp-digit w-10 h-12 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-black rounded-xl border-2 border-gray-200 focus:border-brand focus:ring-4 focus:ring-brand/20 focus:outline-none transition bg-gray-50 text-gray-900" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                <input type="text" maxlength="1" class="otp-digit w-10 h-12 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-black rounded-xl border-2 border-gray-200 focus:border-brand focus:ring-4 focus:ring-brand/20 focus:outline-none transition bg-gray-50 text-gray-900" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="otp-digit w-10 h-12 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-black rounded-xl border-2 border-gray-200 focus:border-brand focus:ring-4 focus:ring-brand/20 focus:outline-none transition bg-gray-50 text-gray-900" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="otp-digit w-10 h-12 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-black rounded-xl border-2 border-gray-200 focus:border-brand focus:ring-4 focus:ring-brand/20 focus:outline-none transition bg-gray-50 text-gray-900" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="otp-digit w-10 h-12 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-black rounded-xl border-2 border-gray-200 focus:border-brand focus:ring-4 focus:ring-brand/20 focus:outline-none transition bg-gray-50 text-gray-900" inputmode="numeric" pattern="[0-9]*">
                <input type="text" maxlength="1" class="otp-digit w-10 h-12 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-black rounded-xl border-2 border-gray-200 focus:border-brand focus:ring-4 focus:ring-brand/20 focus:outline-none transition bg-gray-50 text-gray-900" inputmode="numeric" pattern="[0-9]*">
            </div>
            <div id="otpModalError" class="text-xs text-rose-600 font-bold mt-2.5 hidden"></div>
        </div>

        <!-- Verify & Submit Button -->
        <button type="button" id="btnVerifyOtpAndSubmit" onclick="submitOtpVerification()" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-slate-900 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center justify-center gap-2 cursor-pointer text-sm">
            <span id="otpVerifySpinner" class="hidden"><i class="fas fa-spinner fa-spin"></i></span>
            <i class="fas fa-shield-check"></i>
            <span>Verify & Submit for Approval</span>
        </button>

        <!-- Timer & Resend -->
        <div class="mt-4 flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
            <span id="otpCountdownText"><i class="fas fa-clock text-gray-400 mr-1"></i> Resend in <strong id="otpTimerSeconds" class="text-brand">60</strong>s</span>
            <button type="button" id="btnResendOtp" onclick="resendListingOtp()" disabled class="text-xs font-bold text-gray-400 cursor-not-allowed transition hover:underline">
                Resend OTP
            </button>
        </div>
    </div>
</div>

<!-- ================= SUCCESS MODAL (ADMIN APPROVAL QUEUE / UPDATE COMPLETE) ================= -->
<div id="successModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center shadow-2xl relative animate-in fade-in zoom-in duration-300">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 id="successModalHeading" class="text-xl font-extrabold text-gray-900 mb-2">Submitted for  Approval!</h3>
        <p id="successModalMessage" class="text-xs sm:text-sm text-gray-600 mb-4">
            Your listing has been submitted successfully and assigned to our review team.
        </p>

        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 mb-6">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Tracking Reference ID</div>
            <div id="successTrackingId" class="text-lg font-mono font-bold text-brand">STAY-XXXXXX</div>
            <div id="successStatusBadge" class="text-xs text-amber-700 mt-2 font-medium flex items-center justify-center gap-1">
                <i class="fas fa-clock text-amber-500"></i> Verification Status: <span class="font-bold">Pending Review</span>
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

<!-- ======================= LANDLORD SEO AUTHORITY & VALUE PROPOSITION SECTIONS ======================= -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 space-y-16 pb-12">
    
    <!-- 1. Key Benefits for Property Owners -->
    <section class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-sm">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <span class="bg-brand-light text-brand text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider">Owner & Landlord Benefits</span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-gray-900 mt-3 tracking-tight">Why Post Your Property on <span class="gradient-text">StayNest</span>?</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-2">Connect directly with 50,000+ verified students and working professionals looking for immediate move-in.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-brand/30 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1.5">100% Free • Zero Brokerage</h3>
                <p class="text-xs text-gray-500 leading-relaxed">No hidden fees, no commission on monthly rent, and no middleman cuts. Keep 100% of your rental income.</p>
            </div>

            <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-brand/30 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-phone-volume"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1.5">Direct Tenant Calls &amp; WhatsApp</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Interested tenants connect directly with you via phone call or WhatsApp message for instant booking inquiries.</p>
            </div>

            <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-brand/30 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1.5">Verified In 24 Hours</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Our automated review and audit team approves your listing within 24 hours to award your property the trust-boosting Verified Badge.</p>
            </div>

            <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-brand/30 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-map-location-dot"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1.5">Hyperlocal GPS Map Ranking</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Your property appears in locality and landmark searches near nearby colleges, tech parks, and metro stations.</p>
            </div>

            <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-brand/30 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1.5">Verified Quality Tenants</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Get inquiries from verified college students, software engineers, and corporate employees with reliable KYC records.</p>
            </div>

            <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-brand/30 transition hover:shadow-md">
                <div class="w-12 h-12 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-xl mb-4">
                    <i class="fas fa-gauge-high"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-1.5">Dedicated Owner Portal</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Manage multiple rooms, track tenant inquiries, update monthly rents, and handle lease renewals from one dashboard.</p>
            </div>
        </div>
    </section>

    <!-- 2. How to List in 3 Steps -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 rounded-3xl p-6 sm:p-10 text-white shadow-xl">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="bg-brand/20 text-brand-light text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Fast &amp; Simple</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-3">How to Post Your Property in 3 Easy Steps</h2>
            <p class="text-xs sm:text-sm text-gray-400 mt-1.5">Complete the quick 3-minute form above and go live in 24 hours.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center backdrop-blur-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-brand flex items-center justify-center text-white font-extrabold text-lg mx-auto mb-4 shadow-lg shadow-brand/30">1</div>
                <h3 class="text-base font-bold mb-2">Select Type &amp; Pin GPS</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Choose whether you are listing a PG/Hostel, Flat, or Commercial space. Use 1-click GPS detection to auto-fill your exact address.</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center backdrop-blur-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-brand flex items-center justify-center text-white font-extrabold text-lg mx-auto mb-4 shadow-lg shadow-brand/30">2</div>
                <h3 class="text-base font-bold mb-2">Set Pricing &amp; Amenities</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Specify monthly rent, security deposit, room sharing configs (Single, Double, Triple), meal inclusions, WiFi, and house rules.</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center backdrop-blur-sm relative">
                <div class="w-12 h-12 rounded-2xl bg-brand flex items-center justify-center text-white font-extrabold text-lg mx-auto mb-4 shadow-lg shadow-brand/30">3</div>
                <h3 class="text-base font-bold mb-2">Upload Photos &amp; Go Live</h3>
                <p class="text-xs text-gray-400 leading-relaxed">Add real photos of the rooms and building. Once verified by our team, your listing starts receiving direct tenant calls.</p>
            </div>
        </div>
    </section>

    <!-- 3. What You Can List on StayNest -->
    <section class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-sm">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Property Types You Can List for Free</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1.5">We cater to all rental accommodation categories across top Indian cities.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-emerald-100 bg-emerald-50/40 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold"><i class="fas fa-bed"></i></div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">PGs &amp; Co-Living Hostels</h3>
                        <p class="text-[11px] text-emerald-700 font-semibold">Boys, Girls &amp; Unisex Stays</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 leading-relaxed mb-3">List single private rooms, double sharing, triple sharing with 3 meals, high-speed WiFi, daily housekeeping &amp; CCTV security.</p>
                <span class="text-xs font-bold text-emerald-700 flex items-center gap-1"><i class="fas fa-check"></i> Zero Brokerage Guaranteed</span>
            </div>

            <div class="border border-indigo-100 bg-indigo-50/40 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold"><i class="fas fa-building"></i></div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Flats &amp; Apartments</h3>
                        <p class="text-[11px] text-indigo-700 font-semibold">1BHK, 2BHK, 3BHK &amp; Villas</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 leading-relaxed mb-3">List fully-furnished, semi-furnished, or unfurnished residential flats, builder floors, and independent houses directly to families &amp; bachelors.</p>
                <span class="text-xs font-bold text-indigo-700 flex items-center gap-1"><i class="fas fa-check"></i> Direct Landlord Agreement</span>
            </div>

            <div class="border border-amber-100 bg-amber-50/40 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold"><i class="fas fa-store"></i></div>
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base">Commercial Spaces</h3>
                        <p class="text-[11px] text-amber-700 font-semibold">Shops, Offices &amp; Coworking</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 leading-relaxed mb-3">Post commercial retail shops, office spaces, showrooms, clinic setups, and shared office desks to reach verified business owners.</p>
                <span class="text-xs font-bold text-amber-700 flex items-center gap-1"><i class="fas fa-check"></i> Fast Verified Leads</span>
            </div>
        </div>
    </section>

    <!-- 4. Landlord Frequently Asked Questions -->
    <section class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-sm">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="bg-brand-light text-brand text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider">Got Questions?</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2.5">Landlord &amp; PG Owner FAQs</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Everything you need to know about listing your property on StayNest.</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4 text-xs sm:text-sm">
            <details class="group bg-gray-50 rounded-2xl p-4 sm:p-5 border border-gray-200/80 transition" open>
                <summary class="font-bold text-gray-900 cursor-pointer flex items-center justify-between gap-2 list-none">
                    <span>How much does it cost to list my PG or property on StayNest?</span>
                    <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-gray-600 mt-3 leading-relaxed">It is <strong>100% FREE</strong> to list your PG, hostel, flat, or commercial space on StayNest. We do not charge any listing registration fees or take any commissions from your rental bookings.</p>
            </details>

            <details class="group bg-gray-50 rounded-2xl p-4 sm:p-5 border border-gray-200/80 transition">
                <summary class="font-bold text-gray-900 cursor-pointer flex items-center justify-between gap-2 list-none">
                    <span>How quickly will my listing be verified and visible to tenants?</span>
                    <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-gray-600 mt-3 leading-relaxed">Our moderation team reviews listings within <strong>24 hours</strong>. Once verified, your property receives the green Verified badge and becomes instantly discoverable on search results and interactive GPS maps.</p>
            </details>

            <details class="group bg-gray-50 rounded-2xl p-4 sm:p-5 border border-gray-200/80 transition">
                <summary class="font-bold text-gray-900 cursor-pointer flex items-center justify-between gap-2 list-none">
                    <span>How do tenants contact me?</span>
                    <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-gray-600 mt-3 leading-relaxed">Tenants view your listing and can directly call you or message you on WhatsApp using your verified phone number. There are zero intermediaries involved.</p>
            </details>

            <details class="group bg-gray-50 rounded-2xl p-4 sm:p-5 border border-gray-200/80 transition">
                <summary class="font-bold text-gray-900 cursor-pointer flex items-center justify-between gap-2 list-none">
                    <span>Can I list multiple properties or full hostel blocks?</span>
                    <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-gray-600 mt-3 leading-relaxed">Yes! You can list as many properties and room sharing combinations as you manage. Property managers and brokers can also register for our dedicated <strong>Broker Portal</strong> to bulk-manage unlimited listings.</p>
            </details>
        </div>
    </section>

    <!-- 5. Top Cities Where Owners List PGs -->
    <section class="bg-gray-50 rounded-3xl p-6 sm:p-8 border border-gray-200/80 text-center">
        <h2 class="text-lg sm:text-xl font-black text-gray-900 mb-2">List Your PG Across Top Tech &amp; Education Hubs in India</h2>
        <p class="text-xs text-gray-500 mb-5 max-w-xl mx-auto">Get high occupancy and zero vacancy by listing in your city today.</p>
        <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
            <a href="{{ route('user.seo.city-area', ['city' => 'bangalore']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Bangalore</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'noida']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Noida</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'gurgaon']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Gurgaon</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'delhi']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Delhi</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'greater-noida']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Greater Noida</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'hyderabad']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Hyderabad</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'pune']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Pune</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'mumbai']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Mumbai</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'ghaziabad']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Ghaziabad</a>
            <a href="{{ route('user.seo.city-area', ['city' => 'lucknow']) }}" class="bg-white border border-gray-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded-xl font-semibold shadow-2xs transition">Post PG in Lucknow</a>
        </div>
    </section>

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

        const defaultSlug = '{{ $defaultTypeSlug ?? "pg-hostel" }}';
        if (defaultSlug.includes('commercial')) {
            selectPropertyCategory('commercial');
        } else if (defaultSlug.includes('plot')) {
            selectPropertyCategory('land-plot');
        } else {
            selectPropertyCategory('residential');
        }
        renderSubtypeCards(defaultSlug);
        updatePricingVisibility();
        updateLivePreview();
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

    // Load verified saved home address / current location if exists
    function loadCachedLocationToForm() {
        let city = '';
        let area = '';
        let fullAddress = '';
        let pincode = '';
        let landmark = '';
        let lat = null;
        let lng = null;
        let tag = null;

        // Check if staynest_default_address is saved from Profile or Location page
        try {
            const saved = localStorage.getItem('staynest_default_address');
            if (saved) {
                const parsed = JSON.parse(saved);
                if (parsed.line1 || parsed.line2 || parsed.fullAddress) {
                    fullAddress = parsed.fullAddress || [parsed.line1, parsed.line2].filter(Boolean).join(', ');
                    tag = parsed.tag === 'HOME' ? 'SAVED HOME ADDRESS' : (parsed.tag ? `SAVED ${parsed.tag}` : 'SAVED ADDRESS');
                    if (parsed.lat) lat = parseFloat(parsed.lat);
                    if (parsed.lng) lng = parseFloat(parsed.lng);
                    if (parsed.city) city = parsed.city;
                    if (parsed.area) area = parsed.area;
                    if (parsed.pincode) pincode = parsed.pincode;
                    if (parsed.landmark) landmark = parsed.landmark;
                }
            }
        } catch(e) {}

        if (!fullAddress) {
            const cachedCity = localStorage.getItem('user_cached_city');
            const cachedArea = localStorage.getItem('user_cached_area');
            const cachedAddr = localStorage.getItem('user_cached_address');
            const cachedPin = localStorage.getItem('user_cached_pin');
            const cachedLat = parseFloat(localStorage.getItem('user_cached_lat'));
            const cachedLng = parseFloat(localStorage.getItem('user_cached_lng'));

            if (cachedCity) city = cachedCity;
            if (cachedArea) area = cachedArea;
            if (cachedAddr) fullAddress = cachedAddr;
            if (cachedPin) pincode = cachedPin;
            if (!isNaN(cachedLat) && cachedLat !== 0) {
                lat = cachedLat;
                lng = cachedLng;
            }
        }

        const pCity = document.getElementById('propCity');
        const pArea = document.getElementById('propArea');
        const pAddr = document.getElementById('propAddress');
        const pPin = document.getElementById('propPincode');
        const pLand = document.getElementById('propLandmark');
        const pLat = document.getElementById('propLatitude');
        const pLng = document.getElementById('propLongitude');

        if (fullAddress) {
            if (pCity && !pCity.value.trim() && city) pCity.value = city;
            if (pArea && !pArea.value.trim() && area) pArea.value = area;
            if (pAddr && !pAddr.value.trim()) pAddr.value = fullAddress;
            if (pPin && !pPin.value.trim() && pincode) pPin.value = pincode;
            if (pLand && !pLand.value.trim() && landmark) pLand.value = landmark;
            if (pLat && !pLat.value && lat) pLat.value = lat;
            if (pLng && !pLng.value && lng) pLng.value = lng;

            // Populate Modal Fields
            const mCity = document.getElementById('modalCity');
            const mArea = document.getElementById('modalArea');
            const mAddr = document.getElementById('modalAddress');
            const mPin = document.getElementById('modalPincode');
            const mLand = document.getElementById('modalLandmark');
            if (mCity && city) mCity.value = city;
            if (mArea && area) mArea.value = area;
            if (mAddr) mAddr.value = fullAddress;
            if (mPin && pincode) mPin.value = pincode;
            if (mLand && landmark) mLand.value = landmark;

            updateLocationSummaryCard(city, area, fullAddress, pincode, tag);
            const homeBtnText = document.getElementById('verifiedHomeBtnText');
            if (homeBtnText) homeBtnText.innerText = area ? `Saved Home (${area})` : 'Use Saved Address';
        } else {
            updateLocationSummaryCard('', '', '', '', null);
        }
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

    // Direct 1-Click Switch to Saved Profile Address
    function useVerifiedHomeAddressDirect() {
        let homeData = null;

        try {
            const saved = localStorage.getItem('staynest_default_address');
            if (saved) {
                const parsed = JSON.parse(saved);
                if (parsed.line1 || parsed.line2 || parsed.fullAddress) {
                    homeData = {
                        tag: parsed.tag === 'HOME' ? 'SAVED HOME ADDRESS' : (parsed.tag ? `SAVED ${parsed.tag}` : 'SAVED ADDRESS'),
                        fullAddress: parsed.fullAddress || [parsed.line1, parsed.line2].filter(Boolean).join(', '),
                        city: parsed.city || '',
                        area: parsed.area || '',
                        pincode: parsed.pincode || '',
                        landmark: parsed.landmark || '',
                        lat: parseFloat(parsed.lat) || 28.6280,
                        lng: parseFloat(parsed.lng) || 77.3649
                    };
                }
            }
        } catch(e) {}

        if (!homeData) {
            const cachedAddr = localStorage.getItem('user_cached_address');
            const cachedLat = parseFloat(localStorage.getItem('user_cached_lat'));
            const cachedLng = parseFloat(localStorage.getItem('user_cached_lng'));
            if (cachedAddr) {
                homeData = {
                    tag: 'CACHED LOCATION',
                    fullAddress: cachedAddr,
                    city: localStorage.getItem('user_cached_city') || '',
                    area: localStorage.getItem('user_cached_area') || '',
                    pincode: localStorage.getItem('user_cached_pin') || '',
                    landmark: '',
                    lat: (!isNaN(cachedLat) && cachedLat !== 0) ? cachedLat : 28.6280,
                    lng: (!isNaN(cachedLng) && cachedLng !== 0) ? cachedLng : 77.3649
                };
            }
        }

        if (!homeData || !homeData.fullAddress) {
            showZeptoToast('No Saved Address 📍', 'Please use GPS location or click Edit Map to set address.');
            openLocationModal(false);
            return;
        }

        const pCity = document.getElementById('propCity');
        const pArea = document.getElementById('propArea');
        const pAddr = document.getElementById('propAddress');
        const pPin = document.getElementById('propPincode');
        const pLand = document.getElementById('propLandmark');
        const pLat = document.getElementById('propLatitude');
        const pLng = document.getElementById('propLongitude');

        if (pCity && homeData.city) { pCity.value = homeData.city; clearError(pCity); }
        if (pArea && homeData.area) { pArea.value = homeData.area; clearError(pArea); }
        if (pAddr) { pAddr.value = homeData.fullAddress; clearError(pAddr); }
        if (pPin && homeData.pincode) pPin.value = homeData.pincode;
        if (pLand && homeData.landmark) pLand.value = homeData.landmark;
        if (pLat && homeData.lat) pLat.value = homeData.lat;
        if (pLng && homeData.lng) pLng.value = homeData.lng;

        // Populate Modal Fields (Zepto Style)
        const mCity = document.getElementById('modalCity');
        const mArea = document.getElementById('modalArea');
        const mAddr = document.getElementById('modalAddress');
        const mPin = document.getElementById('modalPincode');
        const mLand = document.getElementById('modalLandmark');
        if (mCity && homeData.city) mCity.value = homeData.city;
        if (mArea && homeData.area) mArea.value = homeData.area;
        if (mAddr) mAddr.value = homeData.fullAddress;
        if (mPin && homeData.pincode) mPin.value = homeData.pincode;
        if (mLand && homeData.landmark) mLand.value = homeData.landmark;

        if (propertyMap && propertyMarker && homeData.lat && homeData.lng) {
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
            propertyMarker.bindPopup(`<strong>${homeData.area || 'Location'}, ${homeData.city || ''}</strong><br>${homeData.fullAddress}<br><span style="font-size:10px; color:#059669; font-weight:700;">🎯 Saved Address</span>`).openPopup();
        }

        try {
            localStorage.setItem('user_cached_lat', homeData.lat);
            localStorage.setItem('user_cached_lng', homeData.lng);
            localStorage.setItem('user_cached_address', homeData.fullAddress);
            if (homeData.city) localStorage.setItem('user_cached_city', homeData.city);
            if (homeData.area) localStorage.setItem('user_cached_area', homeData.area);
            if (homeData.pincode) localStorage.setItem('user_cached_pin', homeData.pincode);
        } catch(e) {}

        const badge = document.getElementById('locPreciseBadge');
        if (badge) badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> 🎯 Saved Location Applied';

        updateLocationSummaryCard(homeData.city, homeData.area, homeData.fullAddress, homeData.pincode, homeData.tag);
        updateLivePreview();
        showZeptoToast('Saved Address Loaded 🏠', `${homeData.area || homeData.city || 'Saved address'} applied to listing!`);
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

        if (tagEl) {
            tagEl.innerText = tag ? `📍 ${tag}` : '📍 PROPERTY LOCATION';
        }
        const title = [area, city].filter(Boolean).join(', ');
        if (line1) line1.innerText = title || (address ? 'Location Selected' : 'Location Not Set');
        if (line2) line2.innerText = address || 'Click "Use Current GPS Location" or "Edit Map" to set property address';
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

    // Clamp positive numbers and enforce maximum length & upper limit
    function sanitizePositive(input, maxVal = null) {
        if (!input) return;
        if (input.value !== '') {
            let clean = input.value.toString().replace(/\D/g, '');
            const effectiveMax = maxVal !== null ? maxVal : (input.max ? Number(input.max) : null);
            const effectiveMaxLength = input.maxLength > 0 ? input.maxLength : (input.getAttribute('maxlength') ? Number(input.getAttribute('maxlength')) : null);

            if (effectiveMaxLength && clean.length > effectiveMaxLength) {
                clean = clean.slice(0, effectiveMaxLength);
            }
            if (effectiveMax !== null && Number(clean) > effectiveMax) {
                clean = String(effectiveMax);
            }
            if (clean !== '' && Number(clean) < 0) {
                clean = '0';
            }
            input.value = clean;
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
    function validateStep(step) {
        let isValid = true;

        if (step === 1) {
            const name = document.getElementById('propName');
            const city = document.getElementById('propCity');
            const area = document.getElementById('propArea');
            const address = document.getElementById('propAddress');

            if (!name || !name.value.trim() || name.value.trim().length < 3) {
                showError('propName', 'Please enter a valid property title (at least 3 characters).');
                isValid = false;
            }
            if (!city || !city.value.trim() || city.value.trim().length < 2) {
                showError('propCity', 'Please enter a valid city name.');
                isValid = false;
            }
            if (!area || !area.value.trim()) {
                showError('propArea', 'Please specify area or sector.');
                isValid = false;
            }
            if (!address || !address.value.trim() || address.value.trim().length < 5) {
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
            const typeRadio = document.querySelector('input[name="listing_type"]:checked');
            const typeVal = typeRadio ? typeRadio.value : 'pg-hostel';
            const isPgType = (typeVal === 'pg-hostel' || typeVal === 'co-living');

            if (isPgType && currentAdType === 'rent') {
                const totalBeds = document.getElementById('propTotalBeds');
                const availBeds = document.getElementById('propAvailableBeds');
                if (totalBeds) {
                    const tbVal = Number(totalBeds.value);
                    if (isNaN(tbVal) || tbVal < 1 || tbVal > 5000) {
                        showError('propTotalBeds', 'Total bed capacity must be between 1 and 5,000.');
                        isValid = false;
                    }
                    if (availBeds) {
                        const abVal = Number(availBeds.value);
                        if (abVal > tbVal) {
                            showError('propAvailableBeds', 'Available beds cannot exceed total bed capacity (' + tbVal + ').');
                            isValid = false;
                        }
                    }
                }
            }
        }

        if (step === 3) {
            const rent = document.getElementById('propRent');
            const expPrice = document.getElementById('propExpectedPrice');
            const deposit = document.getElementById('propDeposit');
            const maintenance = document.getElementById('propMaintenance');
            const desc = document.getElementById('propDescription');
            const rules = document.getElementById('propRules');

            if (currentAdType === 'sale') {
                if (!expPrice || !expPrice.value || Number(expPrice.value) < 1000) {
                    showError('propExpectedPrice', 'Please enter a valid expected selling price (min ₹1,000).');
                    isValid = false;
                }
            } else {
                if (!rent || !rent.value || Number(rent.value) < 100 || Number(rent.value) > 1000000) {
                    showError('propRent', 'Monthly starting rent must be between ₹500 and ₹10,00,000.');
                    isValid = false;
                }

                if (deposit && deposit.value !== '' && Number(deposit.value) > 2000000) {
                    showError('propDeposit', 'Security deposit cannot exceed ₹20,00,000.');
                    isValid = false;
                }

                if (maintenance && maintenance.value !== '' && Number(maintenance.value) > 100000) {
                    showError('propMaintenance', 'Maintenance charges cannot exceed ₹1,00,000 / month.');
                    isValid = false;
                }
            }

            if (!desc || !desc.value.trim() || desc.value.trim().length < 20) {
                showError('propDescription', 'Please enter a detailed description (at least 20 characters).');
                isValid = false;
            }

            if (!rules || !rules.value.trim() || rules.value.trim().length < 5) {
                showError('propRules', 'Please specify the house rules / property guidelines.');
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
        
        // If moving forward, validate current steps
        if (step > currentStep) {
            for (let s = currentStep; s < step; s++) {
                if (!validateStep(s)) {
                    return;
                }
            }
        }

        currentStep = step;

        // 1. Show only target step pane, hide others
        for (let i = 1; i <= 4; i++) {
            const pane = document.getElementById(`step-pane-${i}`);
            if (pane) {
                if (i === step) {
                    pane.classList.remove('hidden');
                } else {
                    pane.classList.add('hidden');
                }
            }
        }

        // 2. Update Progress Bar Fill Width
        const fillEl = document.getElementById('progress-bar-fill');
        if (fillEl) {
            const widths = ['29%', '58%', '85%', '100%'];
            fillEl.style.width = widths[step - 1];
        }

        // 3. Update Step Node Circles & Label Text
        for (let i = 1; i <= 4; i++) {
            const node = document.getElementById(`step-node-${i}`);
            const label = document.getElementById(`step-label-${i}`);

            if (i === step) {
                // Active Step
                if (node) {
                    node.className = 'stepper-node active';
                    node.innerHTML = `${i}`;
                }
                if (label) {
                    label.className = 'block text-xs sm:text-sm font-bold text-slate-800 mt-3 text-center tracking-tight';
                }
            } else if (i < step) {
                // Completed Step
                if (node) {
                    node.className = 'stepper-node completed';
                    node.innerHTML = '<i class="fas fa-check text-sm"></i>';
                }
                if (label) {
                    label.className = 'block text-xs sm:text-sm font-bold text-slate-700 mt-3 text-center tracking-tight';
                }
            } else {
                // Upcoming Step
                if (node) {
                    node.className = 'stepper-node inactive';
                    node.innerHTML = `${i}`;
                }
                if (label) {
                    label.className = 'block text-xs sm:text-sm font-bold text-slate-500 mt-3 text-center tracking-tight';
                }
            }
        }

        // Scroll to top of listing form for seamless UX
        const formEl = document.getElementById('propertyListingForm');
        if (formEl) {
            formEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    let currentCategory = 'residential';
    let currentAdType = 'rent';

    function selectPropertyCategory(cat, initialSlug = null) {
        currentCategory = cat;
        const propCatInput = document.getElementById('propCategory');
        if (propCatInput) propCatInput.value = cat;

        document.querySelectorAll('.category-tab-btn').forEach(btn => {
            btn.className = 'category-tab-btn py-3 px-2 sm:px-4 rounded-xl font-bold text-xs sm:text-sm transition-all text-gray-500 hover:text-gray-900 flex items-center justify-center gap-1.5';
        });
        const activeBtn = document.getElementById(`catBtn_${cat}`);
        if (activeBtn) {
            activeBtn.className = 'category-tab-btn py-3 px-2 sm:px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all shadow-xs bg-white text-gray-900 border border-gray-200/80 flex items-center justify-center gap-1.5';
        }

        renderSubtypeCards(initialSlug);
        updatePricingVisibility();
        updateLivePreview();
    }

    function selectAdType(type, initialSlug = null) {
        currentAdType = type;
        const propAdTypeInput = document.getElementById('propAdType');
        if (propAdTypeInput) propAdTypeInput.value = type;

        const rentBtn = document.getElementById('adTypeBtn_rent');
        const saleBtn = document.getElementById('adTypeBtn_sale');

        if (type === 'rent') {
            if (rentBtn) rentBtn.className = 'ad-type-btn px-6 sm:px-8 py-3 rounded-2xl font-black text-xs sm:text-sm transition-all flex items-center gap-2 bg-brand text-white shadow-md shadow-brand/20 border-2 border-brand';
            if (saleBtn) saleBtn.className = 'ad-type-btn px-6 sm:px-8 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center gap-2 bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200';
        } else {
            if (rentBtn) rentBtn.className = 'ad-type-btn px-6 sm:px-8 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center gap-2 bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200';
            if (saleBtn) saleBtn.className = 'ad-type-btn px-6 sm:px-8 py-3 rounded-2xl font-black text-xs sm:text-sm transition-all flex items-center gap-2 bg-amber-500 text-white shadow-md shadow-amber-500/20 border-2 border-amber-500';
        }

        renderSubtypeCards(initialSlug);
        updatePricingVisibility();
        updateLivePreview();
    }

    function updatePricingVisibility() {
        const rentBox = document.getElementById('rentPricingContainer');
        const saleBox = document.getElementById('salePricingContainer');

        if (currentAdType === 'sale') {
            if (rentBox) rentBox.classList.add('hidden');
            if (saleBox) saleBox.classList.remove('hidden');
        } else {
            if (rentBox) rentBox.classList.remove('hidden');
            if (saleBox) saleBox.classList.add('hidden');
        }
    }

    function formatIndianCurrencyWords(num) {
        if (!num || isNaN(num) || num <= 0) return 'Enter price';
        num = Number(num);
        if (num >= 10000000) {
            const cr = (num / 10000000).toFixed(2);
            return `₹ ${cr.replace(/\.00$/, '')} Crore`;
        } else if (num >= 100000) {
            const lakh = (num / 100000).toFixed(2);
            return `₹ ${lakh.replace(/\.00$/, '')} Lakh`;
        } else if (num >= 1000) {
            const k = (num / 1000).toFixed(1);
            return `₹ ${k.replace(/\.0$/, '')} Thousand`;
        }
        return `₹ ${num.toLocaleString('en-IN')}`;
    }

    function handleSalePriceChange(input) {
        sanitizePositive(input, 1000000000);
        const val = Number(input.value);
        const wordsEl = document.getElementById('priceWordsText');
        if (wordsEl) {
            wordsEl.innerText = formatIndianCurrencyWords(val);
        }
    }

    function handleTypeChange(type) {
        document.querySelectorAll('.property-type-card').forEach(card => {
            card.classList.remove('active-pg', 'active-flat', 'active-commercial');
        });

        const checkedRadio = document.querySelector(`input[name="listing_type"][value="${type}"]`);
        if (checkedRadio) {
            checkedRadio.checked = true;
            const card = checkedRadio.closest('label')?.querySelector('.property-type-card');
            if (card) {
                if (type === 'pg-hostel' || type === 'co-living') {
                    card.classList.add('active-pg');
                } else if (type.includes('commercial')) {
                    card.classList.add('active-commercial');
                } else {
                    card.classList.add('active-flat');
                }
            }
        }

        const pgGender = document.getElementById('pgGenderField');
        const pgRoomConfigs = document.getElementById('pgRoomConfigs');
        const flatConfigs = document.getElementById('flatConfigs');
        const commConfigs = document.getElementById('commercialConfigs');
        const plotConfigs = document.getElementById('plotConfigs');

        // Hide ALL Step 2 config boxes first
        if (pgRoomConfigs) pgRoomConfigs.classList.add('hidden');
        if (flatConfigs) flatConfigs.classList.add('hidden');
        if (commConfigs) commConfigs.classList.add('hidden');
        if (plotConfigs) plotConfigs.classList.add('hidden');

        // Show ONLY the single matching active config box
        if (type === 'pg-hostel' || type === 'co-living') {
            if (pgGender) pgGender.classList.remove('hidden');
            if (pgRoomConfigs) pgRoomConfigs.classList.remove('hidden');
        } else if (type.includes('commercial') || type.includes('office') || type.includes('shop') || type.includes('warehouse')) {
            if (pgGender) pgGender.classList.add('hidden');
            if (commConfigs) commConfigs.classList.remove('hidden');
        } else if (type.includes('plot') || type.includes('land')) {
            if (pgGender) pgGender.classList.add('hidden');
            if (plotConfigs) plotConfigs.classList.remove('hidden');
        } else {
            // Flat, House, Villa, Builder floor, Apartment
            if (pgGender) pgGender.classList.add('hidden');
            if (flatConfigs) flatConfigs.classList.remove('hidden');
        }

        updateLivePreview();
    }

    function renderSubtypeCards(initialSlug = null) {
        const container = document.getElementById('listingTypeContainer');
        if (!container) return;

        let cardsHtml = '';
        if (currentCategory === 'residential') {
            if (currentAdType === 'rent') {
                const sel = initialSlug || 'pg-hostel';
                cardsHtml = `
                    <label class="cursor-pointer">
                        <input type="radio" name="listing_type" value="pg-hostel" class="hidden" ${sel === 'pg-hostel' ? 'checked' : ''} onchange="handleTypeChange('pg-hostel')">
                        <div id="card-type-pg-hostel" class="property-type-card ${sel === 'pg-hostel' ? 'active-pg' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-brand"><i class="fas fa-hotel"></i></div>
                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">PG &amp; Hostels</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="listing_type" value="flat-apartment" class="hidden" ${sel === 'flat-apartment' ? 'checked' : ''} onchange="handleTypeChange('flat-apartment')">
                        <div id="card-type-flat-apartment" class="property-type-card ${sel === 'flat-apartment' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-indigo-500"><i class="fas fa-building"></i></div>
                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Flats &amp; Apartments</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="listing_type" value="house-villa" class="hidden" ${sel === 'house-villa' ? 'checked' : ''} onchange="handleTypeChange('house-villa')">
                        <div id="card-type-house-villa" class="property-type-card ${sel === 'house-villa' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-emerald-600"><i class="fas fa-house-chimney"></i></div>
                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">House / Villa</p>
                        </div>
                    </label>
                `;
            } else {
                const sel = initialSlug || 'flat-apartment';
                cardsHtml = `
                    <label class="cursor-pointer">
                        <input type="radio" name="listing_type" value="flat-apartment" class="hidden" ${sel === 'flat-apartment' ? 'checked' : ''} onchange="handleTypeChange('flat-apartment')">
                        <div id="card-type-flat-apartment" class="property-type-card ${sel === 'flat-apartment' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-indigo-500"><i class="fas fa-building"></i></div>
                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Flats &amp; Apartments</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="listing_type" value="house-villa" class="hidden" ${sel === 'house-villa' ? 'checked' : ''} onchange="handleTypeChange('house-villa')">
                        <div id="card-type-house-villa" class="property-type-card ${sel === 'house-villa' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-emerald-600"><i class="fas fa-house-chimney"></i></div>
                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">House / Villa</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="listing_type" value="builder-floor" class="hidden" ${sel === 'builder-floor' ? 'checked' : ''} onchange="handleTypeChange('builder-floor')">
                        <div id="card-type-builder-floor" class="property-type-card ${sel === 'builder-floor' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                            <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-purple-600"><i class="fas fa-layer-group"></i></div>
                            <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Builder Floor</p>
                        </div>
                    </label>
                `;
            }
        } else if (currentCategory === 'commercial') {
            const sel = initialSlug || 'commercial-office';
            cardsHtml = `
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="commercial-office" class="hidden" ${sel === 'commercial-office' ? 'checked' : ''} onchange="handleTypeChange('commercial-office')">
                    <div id="card-type-commercial-office" class="property-type-card ${sel === 'commercial-office' ? 'active-commercial' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-amber-500"><i class="fas fa-briefcase"></i></div>
                        <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Office Space</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="commercial-shop" class="hidden" ${sel === 'commercial-shop' ? 'checked' : ''} onchange="handleTypeChange('commercial-shop')">
                    <div id="card-type-commercial-shop" class="property-type-card ${sel === 'commercial-shop' ? 'active-commercial' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-blue-500"><i class="fas fa-store"></i></div>
                        <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Retail Shop / Showroom</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="commercial-warehouse" class="hidden" ${sel === 'commercial-warehouse' ? 'checked' : ''} onchange="handleTypeChange('commercial-warehouse')">
                    <div id="card-type-commercial-warehouse" class="property-type-card ${sel === 'commercial-warehouse' ? 'active-commercial' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-slate-700"><i class="fas fa-warehouse"></i></div>
                        <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Warehouse / Godown</p>
                    </div>
                </label>
            `;
        } else { // Land/Plot
            const sel = initialSlug || 'plot-residential';
            cardsHtml = `
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="plot-residential" class="hidden" ${sel === 'plot-residential' ? 'checked' : ''} onchange="handleTypeChange('plot-residential')">
                    <div id="card-type-plot-residential" class="property-type-card ${sel === 'plot-residential' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-indigo-500"><i class="fas fa-map"></i></div>
                        <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Residential Plot</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="plot-commercial" class="hidden" ${sel === 'plot-commercial' ? 'checked' : ''} onchange="handleTypeChange('plot-commercial')">
                    <div id="card-type-plot-commercial" class="property-type-card ${sel === 'plot-commercial' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-amber-500"><i class="fas fa-chart-area"></i></div>
                        <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Commercial Land</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="listing_type" value="plot-agricultural" class="hidden" ${sel === 'plot-agricultural' ? 'checked' : ''} onchange="handleTypeChange('plot-agricultural')">
                    <div id="card-type-plot-agricultural" class="property-type-card ${sel === 'plot-agricultural' ? 'active-flat' : ''} rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center cursor-pointer flex flex-col items-center justify-center group h-full">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 flex items-center justify-center mb-1 text-2xl text-emerald-600"><i class="fas fa-seedling"></i></div>
                        <p class="font-extrabold text-[11px] sm:text-sm text-slate-900 leading-tight">Agricultural / Farm Land</p>
                    </div>
                </label>
            `;
        }

        container.innerHTML = cardsHtml;
        const checkedRadio = container.querySelector('input[type="radio"]:checked') || container.querySelector('input[type="radio"]');
        if (checkedRadio) {
            checkedRadio.checked = true;
            handleTypeChange(checkedRadio.value);
        }
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
        const fillEl = document.getElementById('progress-bar-fill');
        if (fillEl) fillEl.style.width = `${Math.max(10, fillPercent)}%`;

        // Update Stepper Nodes
        for (let i = 1; i <= 4; i++) {
            const node = document.getElementById(`step-node-${i}`);
            if (node) {
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
        }

        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    function handleRoomStatusChange(type, status) {
        const badge = document.getElementById(`badge_status_${type}`);
        const select = document.getElementById(`status_room_${type}`);
        const card = document.getElementById(`card_room_${type}`);
        if (status === 'booked') {
            if (badge) {
                badge.innerText = '🔴 Full / Booked';
                badge.className = 'bg-rose-50 text-rose-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-rose-200';
            }
            if (select) {
                select.className = 'bg-rose-50 text-rose-800 border border-rose-200 text-xs font-bold rounded-xl px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-rose-400 cursor-pointer';
            }
            if (card) {
                card.classList.add('bg-rose-50/20', 'border-rose-200');
            }
        } else {
            if (badge) {
                badge.innerText = '🟢 Vacant';
                badge.className = 'bg-emerald-50 text-emerald-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-200';
            }
            if (select) {
                select.className = 'bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold rounded-xl px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer';
            }
            if (card) {
                card.classList.remove('bg-rose-50/20', 'border-rose-200');
            }
        }
    }

    function toggleRoomCardState(type) {
        const chk = document.getElementById(`chk_room_${type}`);
        const select = document.getElementById(`status_room_${type}`);
        const rent = document.getElementById(`rent_room_${type}`);
        const card = document.getElementById(`card_room_${type}`);
        if (!chk || !chk.checked) {
            if (select) select.disabled = true;
            if (rent) rent.disabled = true;
            if (card) card.classList.add('opacity-50');
        } else {
            if (select) select.disabled = false;
            if (rent) rent.disabled = false;
            if (card) card.classList.remove('opacity-50');
        }
    }

    function handleFullyBookedToggle(el) {
        const availBeds = document.getElementById('propAvailableBeds');
        if (el.checked) {
            if (availBeds) {
                availBeds.dataset.prevVal = availBeds.value || '6';
                availBeds.value = '0';
            }
        } else {
            if (availBeds && availBeds.value === '0') {
                availBeds.value = availBeds.dataset.prevVal || '6';
            }
        }
    }

    function updateLivePreview() {
        const name = document.getElementById('propName')?.value.trim() || 'PG or Property Name';
        const city = document.getElementById('propCity')?.value.trim() || '****';
        const area = document.getElementById('propArea')?.value.trim() || '****';
        const rent = document.getElementById('propRent')?.value.trim() || '*,***';
        const expPrice = document.getElementById('propExpectedPrice')?.value.trim() || '';
        
        const typeRadio = document.querySelector('input[name="listing_type"]:checked');
        const typeVal = typeRadio ? typeRadio.value : 'pg-hostel';
        
        const genderRadio = document.querySelector('input[name="gender_preference"]:checked');
        const genderVal = genderRadio ? genderRadio.value : 'co-ed';

        const previewTitle = document.getElementById('previewTitle');
        if (previewTitle) previewTitle.innerText = name;

        const previewLocation = document.getElementById('previewLocation');
        if (previewLocation) previewLocation.innerHTML = `<i class="fas fa-map-marker-alt text-brand"></i> ${area}, ${city}`;

        const previewPriceLabel = document.getElementById('previewPriceLabel');
        const previewRentPeriod = document.getElementById('previewRentPeriod');
        const previewRent = document.getElementById('previewRent');

        if (previewRent) {
            if (currentAdType === 'sale') {
                if (previewPriceLabel) previewPriceLabel.innerText = 'Expected Price';
                if (previewRentPeriod) previewRentPeriod.style.display = 'none';
                previewRent.innerText = expPrice ? formatIndianCurrencyWords(expPrice) : '₹ Total Valuation';
                previewRent.className = 'text-base font-extrabold text-amber-600';
            } else {
                if (previewPriceLabel) previewPriceLabel.innerText = 'Starting From';
                if (previewRentPeriod) previewRentPeriod.style.display = '';
                previewRent.innerText = `₹${Number(rent >= 500 ? rent : (rent > 0 ? rent : 0)).toLocaleString('en-IN')}`;
                previewRent.className = 'text-base font-extrabold text-brand';
            }
        }

        const typeBadge = document.getElementById('previewTypeBadge');
        if (typeBadge) {
            if (currentAdType === 'sale') {
                typeBadge.innerText = 'For Sale';
                typeBadge.className = 'absolute top-2 left-2 bg-amber-500 text-white text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider';
            } else {
                typeBadge.className = 'absolute top-2 left-2 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg';
                if (typeVal === 'pg-hostel') typeBadge.innerText = 'PG / Hostel';
                else if (typeVal === 'co-living') typeBadge.innerText = 'Co-Living';
                else if (typeVal.includes('flat') || typeVal.includes('house')) typeBadge.innerText = 'Flat / House';
                else typeBadge.innerText = 'Commercial';
            }
        }

        // Show Gender Preference in Live Listing Preview ONLY when pg-hostel (or co-living) is selected and rent
        const genderBadge = document.getElementById('previewGenderBadge');
        if (genderBadge) {
            if ((typeVal === 'pg-hostel' || typeVal === 'co-living') && currentAdType === 'rent') {
                genderBadge.classList.remove('hidden');
                genderBadge.style.display = '';
                if (genderVal === 'boys') {
                    genderBadge.innerText = 'Boys Only';
                    genderBadge.className = 'absolute top-2 right-2 bg-blue-600/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg';
                } else if (genderVal === 'girls') {
                    genderBadge.innerText = 'Girls Only';
                    genderBadge.className = 'absolute top-2 right-2 bg-pink-600/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg';
                } else {
                    genderBadge.innerText = 'Co-Living';
                    genderBadge.className = 'absolute top-2 right-2 bg-purple-600/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg';
                }
            } else {
                genderBadge.classList.add('hidden');
                genderBadge.style.display = 'none';
            }
        }
    }

    // Maximum 10 photos allowed
    const MAX_PROPERTY_PHOTOS = 10;

    // Multiple Photo Uploads (Enforces max 10 photos limit)
    function handlePhotoUpload(input) {
        if (input.files && input.files.length > 0) {
            const remainingSlots = MAX_PROPERTY_PHOTOS - uploadedPhotos.length;
            if (remainingSlots <= 0) {
                alert('You can upload a maximum of 10 photos only.');
                input.value = '';
                return;
            }

            const files = Array.from(input.files);
            if (files.length > remainingSlots) {
                alert(`You can only add ${remainingSlots} more photo(s). Only the first ${remainingSlots} photo(s) will be uploaded.`);
            }

            const filesToProcess = files.slice(0, remainingSlots);
            filesToProcess.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File "${file.name}" is larger than 5MB.`);
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (uploadedPhotos.length < MAX_PROPERTY_PHOTOS) {
                        uploadedPhotos.push(e.target.result);
                        renderPhotoPreviews();
                    }
                };
                reader.readAsDataURL(file);
            });
            input.value = ''; // Reset input to allow selecting again
        }
    }

    function handlePhotoDrop(e) {
        if (e.dataTransfer && e.dataTransfer.files.length > 0) {
            const remainingSlots = MAX_PROPERTY_PHOTOS - uploadedPhotos.length;
            if (remainingSlots <= 0) {
                alert('You can upload a maximum of 10 photos only.');
                return;
            }

            const imageFiles = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
            if (imageFiles.length > remainingSlots) {
                alert(`You can only add ${remainingSlots} more photo(s). Only the first ${remainingSlots} photo(s) will be uploaded.`);
            }

            const filesToProcess = imageFiles.slice(0, remainingSlots);
            filesToProcess.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File "${file.name}" is larger than 5MB.`);
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(ev) {
                    if (uploadedPhotos.length < MAX_PROPERTY_PHOTOS) {
                        uploadedPhotos.push(ev.target.result);
                        renderPhotoPreviews();
                    }
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function addPhotoUrl() {
        const urlInput = document.getElementById('photoUrlInput');
        const url = urlInput.value.trim();
        if (url) {
            if (uploadedPhotos.length >= MAX_PROPERTY_PHOTOS) {
                alert('You can upload a maximum of 10 photos only.');
                return;
            }
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
        if (countBadge) {
            countBadge.innerText = `${uploadedPhotos.length} / ${MAX_PROPERTY_PHOTOS} Photos Added`;
            if (uploadedPhotos.length >= MAX_PROPERTY_PHOTOS) {
                countBadge.className = 'bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full';
            } else {
                countBadge.className = 'bg-brand/10 text-brand text-xs font-bold px-3 py-1 rounded-full';
            }
        }
        if (errPhotos && uploadedPhotos.length > 0) errPhotos.classList.add('hidden');

        if (uploadedPhotos.length === 0) {
            grid.innerHTML = '<div class="col-span-2 sm:col-span-4 p-6 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 text-center text-xs text-gray-400"><i class="fas fa-images text-2xl text-gray-300 mb-2 block"></i>No photos uploaded yet. Select images above to add (Max 10).</div>';
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

        // Reveal "Mark Property as 100% Fully Booked" toggle in Edit Mode
        const fullyBookedToggleBox = document.getElementById('fullyBookedToggleContainer');
        if (fullyBookedToggleBox) {
            fullyBookedToggleBox.classList.remove('hidden');
        }

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

        // 1. Accurately resolve Category & Ad Type
        const listingType = (p.listing_type || '').toLowerCase();
        let category = p.property_category;
        if (!category) {
            if (listingType.includes('commercial') || listingType.includes('office') || listingType.includes('shop') || listingType.includes('warehouse')) {
                category = 'commercial';
            } else if (listingType.includes('plot') || listingType.includes('land')) {
                category = 'land-plot';
            } else {
                category = 'residential';
            }
        }

        const adType = p.ad_type || (p.is_sale ? 'sale' : 'rent');

        // Step 1: Set Ad Type and Category (passes listingType to preserve active subtype card)
        selectAdType(adType, listingType);
        selectPropertyCategory(category, listingType);

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

        // Explicitly trigger Type Change to show the exact Step 2 configuration pane and hide PG rooms for flat/commercial/plot
        handleTypeChange(listingType);

        if (p.gender_preference) {
            const gRadio = document.querySelector(`input[name="gender_preference"][value="${p.gender_preference}"]`);
            if (gRadio) gRadio.checked = true;
        }

        // Configuration & Real Estate Fields (Flat, Commercial, Plot)
        const bhkSelect = document.getElementById('flatBhkType');
        const furnSelect = document.getElementById('flatFurnishing');
        const flatCarpetInput = document.getElementById('flatCarpetArea');
        const commCarpetInput = document.getElementById('commCarpetArea');
        const plotCarpetInput = document.getElementById('plotCarpetArea');
        const commSpaceSelect = document.getElementById('commSpaceType');

        if (bhkSelect && p.bhk_type) bhkSelect.value = p.bhk_type;
        if (furnSelect && p.furnishing_status) furnSelect.value = p.furnishing_status;
        if (flatCarpetInput && p.carpet_area_sqft) flatCarpetInput.value = p.carpet_area_sqft;
        if (commCarpetInput && p.carpet_area_sqft) commCarpetInput.value = p.carpet_area_sqft;
        if (plotCarpetInput && p.carpet_area_sqft) plotCarpetInput.value = p.carpet_area_sqft;
        if (commSpaceSelect && p.commercial_space_type) commSpaceSelect.value = p.commercial_space_type;

        // Step 3: Pricing, Sale Details & Beds
        const rentInput = document.getElementById('propRent');
        const expPriceInput = document.getElementById('propExpectedPrice');
        const tokenInput = document.getElementById('propTokenAmount');
        const ownSelect = document.getElementById('propOwnership');
        const possSelect = document.getElementById('propPossession');
        const negCheckbox = document.getElementById('propNegotiable');
        const depositInput = document.getElementById('propDeposit');
        const maintInput = document.getElementById('propMaintenance');
        const noticeSelect = document.querySelector('select[name="notice_period_days"]');
        const totalBedsInput = document.getElementById('propTotalBeds');
        const availBedsInput = document.getElementById('propAvailableBeds');
        const descInput = document.getElementById('propDescription');
        const rulesInput = document.getElementById('propRules');

        if (rentInput) rentInput.value = p.monthly_rent || '';
        if (expPriceInput) {
            expPriceInput.value = p.expected_price || (p.ad_type === 'sale' ? p.monthly_rent : '');
            handleSalePriceChange(expPriceInput);
        }
        if (tokenInput) tokenInput.value = p.booking_token_amount || '';
        if (ownSelect && p.ownership_type) ownSelect.value = p.ownership_type;
        if (possSelect && p.possession_status) possSelect.value = p.possession_status;
        if (negCheckbox) negCheckbox.checked = !!p.price_negotiable;
        if (depositInput) depositInput.value = p.security_deposit || '';
        if (maintInput) maintInput.value = p.maintenance_charges !== undefined ? p.maintenance_charges : '';
        if (noticeSelect && p.notice_period_days !== undefined) noticeSelect.value = String(p.notice_period_days);
        if (totalBedsInput) totalBedsInput.value = p.total_beds || '';
        if (availBedsInput) availBedsInput.value = p.available_beds || '';
        if (descInput) descInput.value = p.description || '';
        if (rulesInput) rulesInput.value = p.house_rules || '';

        // Room Sharing Configurations (Only for PG / Hostel listings)
        const isPg = (listingType === 'pg-hostel' || listingType === 'co-living');
        if (isPg && p.room_sharing && Array.isArray(p.room_sharing)) {
            const chkSingle = document.getElementById('chk_room_single');
            const chkDouble = document.getElementById('chk_room_double');
            const chkTriple = document.getElementById('chk_room_triple');
            const chkFour = document.getElementById('chk_room_four');

            const rentSingle = document.getElementById('rent_room_single');
            const rentDouble = document.getElementById('rent_room_double');
            const rentTriple = document.getElementById('rent_room_triple');
            const rentFour = document.getElementById('rent_room_four');

            if (chkSingle) chkSingle.checked = false;
            if (chkDouble) chkDouble.checked = false;
            if (chkTriple) chkTriple.checked = false;
            if (chkFour) chkFour.checked = false;

            p.room_sharing.forEach(rs => {
                const type = (rs.type || '').toLowerCase();
                const rent = rs.rent || p.monthly_rent;
                const status = (rs.status || (rs.is_available === false ? 'booked' : 'available')).toLowerCase();
                if (type === 'single') {
                    if (chkSingle) { chkSingle.checked = true; toggleRoomCardState('single'); }
                    if (rentSingle && rent) rentSingle.value = rent;
                    const statusEl = document.getElementById('status_room_single');
                    if (statusEl) { statusEl.value = status; handleRoomStatusChange('single', status); }
                } else if (type === 'double') {
                    if (chkDouble) { chkDouble.checked = true; toggleRoomCardState('double'); }
                    if (rentDouble && rent) rentDouble.value = rent;
                    const statusEl = document.getElementById('status_room_double');
                    if (statusEl) { statusEl.value = status; handleRoomStatusChange('double', status); }
                } else if (type === 'triple') {
                    if (chkTriple) { chkTriple.checked = true; toggleRoomCardState('triple'); }
                    if (rentTriple && rent) rentTriple.value = rent;
                    const statusEl = document.getElementById('status_room_triple');
                    if (statusEl) { statusEl.value = status; handleRoomStatusChange('triple', status); }
                } else if (type === 'four') {
                    if (chkFour) { chkFour.checked = true; toggleRoomCardState('four'); }
                    if (rentFour && rent) rentFour.value = rent;
                    const statusEl = document.getElementById('status_room_four');
                    if (statusEl) { statusEl.value = status; handleRoomStatusChange('four', status); }
                }
            });
        }

        // Entire Property Fully Booked Toggle state (Shown ONLY on Edit Property Listing)
        const isFullyBooked = (p.available_beds !== undefined && Number(p.available_beds) === 0) || p.status === 'fully_booked';
        const fullyBookedToggle = document.getElementById('propIsFullyBooked');
        if (fullyBookedToggle) fullyBookedToggle.checked = isFullyBooked;
        const fullyBookedContainer = document.getElementById('fullyBookedToggleContainer');
        if (fullyBookedContainer) fullyBookedContainer.classList.remove('hidden');

        // Amenities
        if (p.amenities && Array.isArray(p.amenities)) {
            document.querySelectorAll('input[name="amenities[]"]').forEach(cb => {
                const isSelected = p.amenities.includes(cb.value) || 
                                   (cb.value === 'refrigerator' && p.amenities.includes('fridge')) ||
                                   (cb.value === 'fridge' && p.amenities.includes('refrigerator'));
                cb.checked = isSelected;
            });
        }

        // Photos (Maximum 10 photos)
        if (p.photos && Array.isArray(p.photos) && p.photos.length > 0) {
            uploadedPhotos = [...p.photos].slice(0, MAX_PROPERTY_PHOTOS);
            renderPhotoPreviews();
        }

        // Step 4: Owner Info (Load Original Landlord / Broker Details)
        const ownerNameInput = document.getElementById('ownerName');
        const ownerPhoneInput = document.getElementById('ownerPhone');
        const ownerEmailInput = document.getElementById('ownerEmail');

        const isAdminUser = {{ $isAdmin ? 'true' : 'false' }};

        if (ownerNameInput && p.owner_name !== undefined) ownerNameInput.value = p.owner_name || '';
        if (ownerPhoneInput && p.owner_phone !== undefined) ownerPhoneInput.value = p.owner_phone || '';
        if (ownerEmailInput && p.owner_email !== undefined) ownerEmailInput.value = p.owner_email || '';

        // If in Edit mode or if user is Admin, make owner inputs editable and remove readonly locks
        if (editPropertyId || isAdminUser) {
            if (ownerNameInput) {
                ownerNameInput.readOnly = false;
                ownerNameInput.className = 'w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition';
            }
            if (ownerPhoneInput) {
                ownerPhoneInput.readOnly = false;
                ownerPhoneInput.className = 'w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl pl-14 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition';
            }
            if (ownerEmailInput) {
                ownerEmailInput.readOnly = false;
                ownerEmailInput.className = 'w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-xl px-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition';
            }
            const adminOwnerPreview = document.getElementById('adminOwnerPreview');
            if (adminOwnerPreview) {
                adminOwnerPreview.innerText = `${p.owner_name || 'Landlord'} • ${p.owner_phone || p.owner_email || 'Verified Owner Contact'}`;
            }
        }

        updateLocationSummaryCard(p.city, p.area, p.address, p.pincode);
        updatePricingVisibility();
        updateLivePreview();
    }

    let isEmailVerified = {{ $isLoggedIn ? 'true' : 'false' }};
    let currentVerificationToken = null;
    let pendingSubmissionPayload = null;
    let otpCountdownTimer = null;
    let otpRemainingSeconds = 60;

    function resetEmailVerification() {
        const isAuthUser = {{ $isLoggedIn ? 'true' : 'false' }};
        if (!isAuthUser) {
            isEmailVerified = false;
            currentVerificationToken = null;
            const badge = document.getElementById('emailVerifyBadge');
            if (badge) badge.classList.add('hidden');
        }
    }

    function openOtpModal(targetEmail) {
        const modal = document.getElementById('listingOtpModal');
        const emailLabel = document.getElementById('otpModalTargetEmail');
        const errEl = document.getElementById('otpModalError');
        if (emailLabel) emailLabel.innerText = targetEmail;
        if (errEl) { errEl.classList.add('hidden'); errEl.innerText = ''; }

        // Clear digits
        document.querySelectorAll('.otp-digit').forEach(inp => inp.value = '');

        if (modal) {
            modal.classList.remove('hidden');
            const firstInp = document.querySelector('.otp-digit');
            if (firstInp) setTimeout(() => firstInp.focus(), 150);
        }
        startOtpTimer();
    }

    function closeOtpModal() {
        const modal = document.getElementById('listingOtpModal');
        if (modal) modal.classList.add('hidden');
        if (otpCountdownTimer) clearInterval(otpCountdownTimer);
    }

    function startOtpTimer() {
        if (otpCountdownTimer) clearInterval(otpCountdownTimer);
        otpRemainingSeconds = 60;
        const timerEl = document.getElementById('otpTimerSeconds');
        const resendBtn = document.getElementById('btnResendOtp');
        const countdownText = document.getElementById('otpCountdownText');

        if (resendBtn) {
            resendBtn.disabled = true;
            resendBtn.className = 'text-xs font-bold text-gray-400 cursor-not-allowed transition';
        }
        if (countdownText) countdownText.classList.remove('hidden');

        otpCountdownTimer = setInterval(() => {
            otpRemainingSeconds--;
            if (timerEl) timerEl.innerText = otpRemainingSeconds;

            if (otpRemainingSeconds <= 0) {
                clearInterval(otpCountdownTimer);
                if (resendBtn) {
                    resendBtn.disabled = false;
                    resendBtn.className = 'text-xs font-bold text-brand hover:underline cursor-pointer transition';
                }
                if (countdownText) countdownText.classList.add('hidden');
            }
        }, 1000);
    }

    function setupOtpDigitInputs() {
        const container = document.getElementById('otpInputContainer');
        if (!container) return;

        const inputs = container.querySelectorAll('.otp-digit');
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const val = input.value.replace(/\D/g, '');
                input.value = val ? val[0] : '';
                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                // Auto trigger verification if all 6 filled
                const allFilled = Array.from(inputs).every(i => i.value.length === 1);
                if (allFilled) {
                    submitOtpVerification();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                if (pasteData) {
                    for (let i = 0; i < inputs.length; i++) {
                        inputs[i].value = pasteData[i] || '';
                    }
                    const nextEmpty = Array.from(inputs).find(i => !i.value);
                    if (nextEmpty) nextEmpty.focus();
                    else inputs[inputs.length - 1].focus();

                    if (pasteData.length >= 6) {
                        submitOtpVerification();
                    }
                }
            });
        });
    }

    async function sendListingOtp(email, ownerName, propertyTitle) {
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');
        if (submitBtn) submitBtn.disabled = true;
        if (spinner) spinner.classList.remove('hidden');

        try {
            const res = await fetch('/api/v1/properties/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    email: email,
                    owner_name: ownerName,
                    property_title: propertyTitle
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                openOtpModal(email);
                return true;
            } else {
                const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Failed to send OTP.');
                alert('⚠️ Verification Notice:\n' + msg);
                return false;
            }
        } catch(err) {
            console.error('Send OTP error:', err);
            alert('A network error occurred while sending the OTP code. Please try again.');
            return false;
        } finally {
            if (submitBtn) submitBtn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
        }
    }

    async function resendListingOtp() {
        const ownerEmailEl = document.getElementById('ownerEmail');
        const ownerNameEl = document.getElementById('ownerName');
        const nameEl = document.getElementById('propName');

        const email = ownerEmailEl?.value.trim();
        const name = ownerNameEl?.value.trim() || 'Property Owner';
        const title = nameEl?.value.trim() || 'your listing';

        if (!email) {
            alert('Please enter your email address first.');
            return;
        }

        const resendBtn = document.getElementById('btnResendOtp');
        if (resendBtn) {
            resendBtn.disabled = true;
            resendBtn.innerText = 'Sending...';
        }

        const success = await sendListingOtp(email, name, title);
        if (resendBtn) {
            resendBtn.innerText = 'Resend OTP';
        }
    }

    async function submitOtpVerification() {
        const ownerEmailEl = document.getElementById('ownerEmail');
        const email = ownerEmailEl?.value.trim();
        const errEl = document.getElementById('otpModalError');
        const verifyBtn = document.getElementById('btnVerifyOtpAndSubmit');
        const spinner = document.getElementById('otpVerifySpinner');

        const digits = Array.from(document.querySelectorAll('.otp-digit')).map(i => i.value).join('');

        if (digits.length !== 6) {
            if (errEl) {
                errEl.innerText = 'Please enter all 6 digits of the OTP code.';
                errEl.classList.remove('hidden');
            }
            return;
        }

        if (errEl) errEl.classList.add('hidden');
        if (verifyBtn) verifyBtn.disabled = true;
        if (spinner) spinner.classList.remove('hidden');

        try {
            const res = await fetch('/api/v1/properties/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    email: email,
                    otp: digits
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                isEmailVerified = true;
                currentVerificationToken = data.data.verification_token;

                const badge = document.getElementById('emailVerifyBadge');
                if (badge) badge.classList.remove('hidden');

                closeOtpModal();

                // If we have a pending payload, submit it immediately!
                if (pendingSubmissionPayload) {
                    pendingSubmissionPayload.verification_token = currentVerificationToken;
                    pendingSubmissionPayload.otp = digits;
                    await executePropertySubmission(pendingSubmissionPayload);
                }
            } else {
                const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Invalid OTP entered.');
                if (errEl) {
                    errEl.innerText = msg;
                    errEl.classList.remove('hidden');
                }
            }
        } catch(err) {
            console.error('Verify OTP error:', err);
            if (errEl) {
                errEl.innerText = 'A network error occurred while verifying the code. Please try again.';
                errEl.classList.remove('hidden');
            }
        } finally {
            if (verifyBtn) verifyBtn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
        }
    }

    function initLoggedInContact() {
        const isAuthUser = {{ $isLoggedIn ? 'true' : 'false' }};
        const isAdminUser = {{ $isAdmin ? 'true' : 'false' }};
        const ownerNameInput = document.getElementById('ownerName');
        const ownerPhoneInput = document.getElementById('ownerPhone');
        const ownerEmailInput = document.getElementById('ownerEmail');

        // Never override fields if Admin is managing listings or if editing existing property
        if (isAdminUser || editPropertyId || isAuthUser) return;

        try {
            const rawUser = localStorage.getItem('staynest_user') || localStorage.getItem('broker_user');
            if (rawUser) {
                const user = JSON.parse(rawUser);
                const name = user.name || user.full_name || (user.profile ? (user.profile.first_name + ' ' + (user.profile.last_name || '')) : '');
                const phone = user.phone || '';
                const email = user.email || '';

                if (name && ownerNameInput && !ownerNameInput.value) {
                    ownerNameInput.value = name;
                }
                if (phone && ownerPhoneInput && !ownerPhoneInput.value) {
                    ownerPhoneInput.value = phone;
                }
                if (email && ownerEmailInput && !ownerEmailInput.value) {
                    ownerEmailInput.value = email;
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
        const expPriceEl = document.getElementById('propExpectedPrice');
        const tokenEl = document.getElementById('propTokenAmount');
        const ownEl = document.getElementById('propOwnership');
        const possEl = document.getElementById('propPossession');
        const negEl = document.getElementById('propNegotiable');
        const bhkEl = document.getElementById('flatBhkType');
        const furnEl = document.getElementById('flatFurnishing');
        const depEl = document.getElementById('propDeposit');
        const carpetEl = (document.getElementById('flatCarpetArea') && !document.getElementById('flatConfigs')?.classList.contains('hidden')) 
            ? document.getElementById('flatCarpetArea') 
            : (document.getElementById('commCarpetArea') && !document.getElementById('commercialConfigs')?.classList.contains('hidden'))
            ? document.getElementById('commCarpetArea')
            : document.getElementById('plotCarpetArea');
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

        const isSale = currentAdType === 'sale';
        const expectedPriceVal = isSale ? Number(expPriceEl?.value || 0) : null;

        if (isSale) {
            if (!expectedPriceVal || expectedPriceVal < 1000) {
                goToStep(3);
                showError('propExpectedPrice', 'Please specify expected selling price (min ₹1,000).');
                expPriceEl?.focus();
                return;
            }
        } else {
            if (!rentEl?.value || Number(rentEl.value) < 100) {
                goToStep(3);
                showError('propRent', 'Please specify a starting monthly rent.');
                rentEl?.focus();
                return;
            }
        }

        // Step 4 Owner Validations
        const ownerNameVal = ownerNameEl?.value.trim() || '';
        const ownerPhoneVal = (ownerPhoneEl?.value || '').replace(/\D/g, '');
        const ownerEmailVal = ownerEmailEl?.value.trim() || '';

        if (!ownerNameVal || ownerNameVal.length < 2) {
            goToStep(4);
            showError('ownerName', 'Please enter the owner or landlord full name.');
            ownerNameEl?.focus();
            return;
        }

        if (ownerPhoneVal.length < 10) {
            goToStep(4);
            showError('ownerPhone', 'Please enter a valid 10-digit Indian mobile number.');
            ownerPhoneEl?.focus();
            return;
        }

        const isAuthUser = {{ $isLoggedIn ? 'true' : 'false' }};
        const isAdminUser = {{ $isAdmin ? 'true' : 'false' }};

        // For guests (not logged in), email is strictly required for OTP
        if (!isAuthUser && !isAdminUser) {
            if (!ownerEmailVal || !ownerEmailVal.includes('@') || !ownerEmailVal.includes('.')) {
                goToStep(4);
                showError('ownerEmail', 'Please enter a valid Gmail / Email address to receive the verification OTP.');
                ownerEmailEl?.focus();
                return;
            }
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

        const finalPhone = ownerPhoneVal.length >= 10 ? ownerPhoneVal.slice(-10) : '9876543210';

        // Collect checked amenities
        const amenities = [];
        document.querySelectorAll('input[name="amenities[]"]:checked').forEach(cb => {
            amenities.push(cb.value);
        });

        const depositVal = depEl?.value ? Number(depEl.value) : null;
        const maintVal = maintEl?.value ? Number(maintEl.value) : 0;
        const noticeVal = noticeSelect?.value ? Number(noticeSelect.value) : 30;

        const descriptionVal = descEl?.value.trim() || 'Premium accommodation in prime location with full modern amenities.';
        const rulesVal = rulesEl?.value.trim() || '• Standard safety guidelines apply\n• Maintain property cleanliness';

        const selectedListingType = typeRadio ? typeRadio.value : 'pg-hostel';
        const isPgType = !isSale && (selectedListingType === 'pg-hostel' || selectedListingType === 'co-living');

        // Collect checked room sharing types, availability status & pricing (ONLY for PG / Hostels)
        const roomSharing = [];
        if (isPgType) {
            if (document.getElementById('chk_room_single')?.checked) {
                const statusVal = document.getElementById('status_room_single')?.value || 'available';
                roomSharing.push({
                    type: 'single',
                    name: 'Single Occupancy (Private Room)',
                    rent: Math.max(500, Number(document.getElementById('rent_room_single')?.value || 12000)),
                    is_available: statusVal === 'available',
                    status: statusVal
                });
            }
            if (document.getElementById('chk_room_double')?.checked) {
                const statusVal = document.getElementById('status_room_double')?.value || 'available';
                roomSharing.push({
                    type: 'double',
                    name: 'Double Sharing Room',
                    rent: Math.max(500, Number(document.getElementById('rent_room_double')?.value || 8500)),
                    is_available: statusVal === 'available',
                    status: statusVal
                });
            }
            if (document.getElementById('chk_room_triple')?.checked) {
                const statusVal = document.getElementById('status_room_triple')?.value || 'available';
                roomSharing.push({
                    type: 'triple',
                    name: 'Triple Sharing Room',
                    rent: Math.max(500, Number(document.getElementById('rent_room_triple')?.value || 6500)),
                    is_available: statusVal === 'available',
                    status: statusVal
                });
            }
            if (document.getElementById('chk_room_four')?.checked) {
                const statusVal = document.getElementById('status_room_four')?.value || 'available';
                roomSharing.push({
                    type: 'four',
                    name: 'Four Sharing Room',
                    rent: Math.max(500, Number(document.getElementById('rent_room_four')?.value || 5000)),
                    is_available: statusVal === 'available',
                    status: statusVal
                });
            }
        }

        let calculatedGender = 'co-ed';
        if (selectedListingType.includes('commercial')) {
            calculatedGender = null;
        } else if (selectedListingType.includes('flat') || selectedListingType.includes('house') || selectedListingType.includes('plot') || selectedListingType.includes('builder')) {
            calculatedGender = 'all';
        } else {
            calculatedGender = genderRadio ? genderRadio.value : 'co-ed';
        }

        const rentAmount = isSale ? (expectedPriceVal || 5000) : Math.max(100, Number(rentEl?.value || 5000));

        const isFullyBooked = document.getElementById('propIsFullyBooked')?.checked || (aBedsEl && Number(aBedsEl.value) === 0);
        const finalTotalBeds = Math.max(1, Number(tBedsEl?.value || 10));
        const finalAvailableBeds = isFullyBooked ? 0 : Math.max(0, Number(aBedsEl?.value ?? 6));

        const payload = {
            listing_type: selectedListingType,
            ad_type: currentAdType || 'rent',
            property_category: currentCategory || 'residential',
            expected_price: expectedPriceVal,
            booking_token_amount: tokenEl?.value ? Number(tokenEl.value) : null,
            price_negotiable: negEl?.checked ? 1 : 0,
            ownership_type: ownEl?.value || 'Freehold',
            possession_status: possEl?.value || 'Ready to Move',
            carpet_area_sqft: carpetEl?.value ? Number(carpetEl.value) : null,
            bhk_type: bhkEl?.value || null,
            furnishing_status: furnEl?.value || null,
            name: nameEl.value.trim(),
            city: cityEl.value.trim(),
            area: areaEl?.value.trim() || '',
            address: addrEl.value.trim(),
            landmark: landEl?.value.trim() || '',
            pincode: pinEl?.value.trim() || '',
            latitude: latEl?.value ? Number(latEl.value) : null,
            longitude: lngEl?.value ? Number(lngEl.value) : null,
            gender_preference: calculatedGender,
            monthly_rent: rentAmount,
            security_deposit: isSale ? 0 : depositVal,
            maintenance_charges: maintVal,
            notice_period_days: noticeVal,
            total_beds: finalTotalBeds,
            available_beds: finalAvailableBeds,
            is_fully_booked: isFullyBooked ? 1 : 0,
            description: descriptionVal,
            house_rules: rulesVal,
            owner_name: ownerNameVal,
            owner_phone: finalPhone,
            owner_email: ownerEmailVal || null,
            amenities: amenities,
            room_sharing: roomSharing,
            photos: uploadedPhotos,
            verification_token: currentVerificationToken
        };

        // GUEST OTP VERIFICATION FLOW
        if (!isAuthUser && !isAdminUser && !editPropertyId) {
            if (!isEmailVerified || !currentVerificationToken) {
                pendingSubmissionPayload = payload;
                await sendListingOtp(ownerEmailVal, ownerNameVal, payload.name);
                return;
            }
        }

        await executePropertySubmission(payload);
    }

    async function executePropertySubmission(payload) {
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
                alert('⚠️ Submission Notice:\n' + errorMsg);
            }
        } catch (err) {
            console.error('Submission error:', err);
            alert('A network error occurred while submitting your listing. Please try again.');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
        }
    }

    // Call on DOM readiness
    document.addEventListener('DOMContentLoaded', function() {
        setupOtpDigitInputs();
    });
</script>
@endpush
