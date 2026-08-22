@extends('user.layouts.app')

@php
    $propName = $property->name ?? 'Sunrise Premium PG';
    $propTagMeta = $property ? $property->display_tag_meta : ['label' => 'Verified', 'icon' => 'check-circle', 'solid_badge' => 'bg-emerald-500 text-white'];
    $propGenderMeta = $property ? $property->gender_type_meta : ['label' => 'BOYS', 'class' => 'bg-blue-50 text-blue-600', 'btn_class' => 'bg-blue-600 text-white'];
    $propRent = $property ? number_format($property->monthly_rent) : '8,500';
    $propDeposit = $property && $property->security_deposit ? number_format($property->security_deposit) : number_format((int)str_replace(',', '', $propRent) * 2);
    $propLocation = $property ? (($property->address ?: ($property->area->name ?? '')) . ', ' . ($property->city->name ?? 'Noida')) : 'Sector 62, Noida, Uttar Pradesh';
    $propReviews = isset($totalReviewsCount) ? (int)$totalReviewsCount : ($property ? $property->dynamic_reviews_count : 0);
    $propRating = $propReviews > 0 ? (isset($avgRating) && (float)$avgRating > 0 ? (string)$avgRating : ($property && $property->dynamic_rating > 0 ? $property->dynamic_rating : '4.8')) : 'New';
    $approvedReviews = isset($approvedReviews) ? $approvedReviews : collect();
    $propImages = ($property && $property->images->count() > 0) ? $property->images : collect([(object)['image_url' => $property->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80']]);
    $propOwner = $property->broker ?? null;
    $ownerName = $propOwner->name ?? 'Vikram Singh';
    $ownerPhone = $propOwner->phone ?? '919876543210';
    $cleanPhone = preg_replace('/[^0-9]/', '', $ownerPhone);
    $availBeds = $property->available_beds ?? min(4, $totalBeds);
    $propNoticePeriod = (int) ($property->notice_period_days ?? 30);
    $propMaintenance = (float) ($property->maintenance_charges ?? 0);
    $propSeoTitle = $propName . ' - ' . ucfirst($property->gender_preference ?? 'Co-living') . ' PG in ' . ($property->area->name ?? '') . ', ' . ($property->city->name ?? 'India') . ' | ₹' . $propRent . '/mo | StayNest';
    $propSeoDesc = 'Book ' . $propName . ' in ' . $propLocation . ' on StayNest. Zero brokerage, ₹' . $propRent . '/month, ' . $propRating . '★ rating. Modern amenities, verified biometric security & instant booking.';
    $propSeoKeywords = $propName . ', PG in ' . ($property->area->name ?? '') . ', PG in ' . ($property->city->name ?? '') . ', ' . ($propGenderMeta['label'] ?? 'Boys') . ' PG in ' . ($property->city->name ?? '') . ', Paying Guest ' . ($property->area->name ?? '') . ', StayNest';
    $propSeoImage = $property->display_image_url ?? ($propImages->first()->image_url ?? asset('images/favicon.png'));
    $propCanonical = route('user.detail', ['slug' => $property->slug ?: $property->id]);
@endphp

@section('title', $propSeoTitle)
@section('meta_description', $propSeoDesc)
@section('meta_keywords', $propSeoKeywords)
@section('meta_image', $propSeoImage)
@section('canonical', $propCanonical)
@section('og_type', 'place')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LodgingBusiness",
  "name": "{{ $propName }}",
  "image": "{{ $propSeoImage }}",
  "url": "{{ $propCanonical }}",
  "telephone": "+91{{ $cleanPhone }}",
  "priceRange": "₹{{ $propRent }} - ₹{{ $propDeposit }}",
  "description": "{{ addslashes(strip_tags($property->description ?? $propSeoDesc)) }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ addslashes($property->address ?: ($property->area->name ?? '')) }}",
    "addressLocality": "{{ addslashes($property->area->name ?? '') }}",
    "addressRegion": "{{ addslashes($property->city->name ?? 'Delhi NCR') }}",
    "postalCode": "{{ $property->pincode ?? '201301' }}",
    "addressCountry": "IN"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "{{ $property->latitude ?? '28.6280' }}",
    "longitude": "{{ $property->longitude ?? '77.3649' }}"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $propRating }}",
    "reviewCount": "{{ max(1, (int)$propReviews) }}",
    "bestRating": "5",
    "worstRating": "1"
  },
  "makesOffer": {
    "@type": "Offer",
    "price": "{{ (int)str_replace(',', '', $propRent) }}",
    "priceCurrency": "INR",
    "availability": "https://schema.org/InStock"
  }
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
<style>
    .card-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px -10px rgba(0, 0, 0, 0.1);
    }
    .detailSwiper .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: rgba(255, 255, 255, 0.7);
        opacity: 0.8;
        transition: all 0.3s ease;
    }
    .detailSwiper .swiper-pagination-bullet-active {
        width: 24px;
        border-radius: 9999px;
        background: #4bb59d !important;
        opacity: 1;
    }
    .slider-nav-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(8px);
        color: #1f2937;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 20;
    }
    .slider-nav-btn:hover {
        background: #ffffff;
        color: #4bb59d;
        transform: scale(1.08);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }
    .slider-nav-btn::after {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="pt-5 sm:pt-5 pb-10 md:pb-10 w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

    @if($property && ($property->status !== 'active' || $property->verification_status !== 'verified' || !$property->is_active))
        <div class="mb-4 bg-amber-500/10 border border-amber-500/30 text-amber-900 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <i class="fas fa-eye-slash text-sm"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-amber-900 flex items-center gap-2">
                        <span>Admin / Owner Preview Mode</span>
                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-amber-500 text-white">Status: {{ ucfirst($property->verification_status ?? 'Pending') }}</span>
                    </h4>
                    <p class="text-xs text-amber-800">This property is <strong>not visible to public users</strong> because it is pending admin approval. Only administrators and property owners can view this preview.</p>
                </div>
            </div>
            @if(Auth::check() && Auth::user()->roles()->whereIn('slug', ['super_admin', 'admin'])->exists())
                <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto">
                    <a href="{{ route('admin.pgs') }}" class="w-full sm:w-auto text-center px-3 py-1.5 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition shadow-xs">
                        <i class="fas fa-shield-halved mr-1"></i> Admin Dashboard
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Breadcrumb & Top Actions -->
    <div class="flex flex-wrap items-center justify-between gap-2 py-3 mb-3 text-xs sm:text-sm">
        <div class="flex items-center gap-1.5 text-gray-500 flex-wrap">
            <a href="{{ route('user.home') }}" class="hover:text-brand transition flex items-center gap-1"><i class="fas fa-home text-xs"></i> Home</a>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
            <a href="{{ route('user.search') }}" class="hover:text-brand transition">Search</a>
            @if($property && $property->city)
                <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                <a href="{{ route('user.search', ['city' => $property->city->name]) }}" class="hover:text-brand transition">{{ $property->city->name }}</a>
            @endif
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-gray-900 font-semibold truncate max-w-[200px] sm:max-w-xs">{{ $propName }}</span>
        </div>

        <div class="flex items-center gap-2">
            <!-- Share Button -->
            <button type="button" onclick="openShareModal()" class="flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-gray-600 hover:text-brand transition text-xs font-semibold shadow-xs">
                <i class="fas fa-share-alt"></i> <span class="hidden sm:inline">Share</span>
            </button>
            
            <!-- Wishlist Button -->
            <button type="button" onclick="heartToggle(this, { id: '{{ $property->id ?? '' }}', slug: '{{ $property->slug ?? \Illuminate\Support\Str::slug($property->name ?? '') }}', title: '{{ addslashes($propName) }}', price: '₹{{ $propRent }}', image: '{{ $propImages->first()->image_url ?? '' }}', location: '{{ addslashes($propLocation) }}', type: '{{ $propGenderMeta['label'] }}' })" data-prop-id="{{ $property->id ?? '' }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-rose-50 border border-gray-200 hover:border-rose-200 rounded-xl text-gray-600 hover:text-rose-500 transition text-xs font-semibold shadow-xs">
                <i class="far fa-heart"></i> <span class="hidden sm:inline">Save</span>
            </button>

            <!-- Report Listing Button -->
            <button type="button" onclick="openReportModal()" class="flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl text-rose-600 transition text-xs font-semibold shadow-xs" title="Report inaccuracy, abuse or issues">
                <i class="fas fa-flag"></i> <span class="hidden sm:inline">Report Issue</span>
            </button>
        </div>
    </div>

    <!-- ================= 1. PREMIUM HERO GALLERY SLIDER ================= -->
    <div class="mb-6 sm:mb-8">
        <div class="relative rounded-3xl overflow-hidden shadow-lg border border-gray-100 bg-slate-950 h-72 sm:h-96 md:h-[420px]">
            <div class="swiper detailSwiper h-full w-full">
                <div class="swiper-wrapper h-full">
                    @foreach($propImages as $img)
                        <div class="swiper-slide h-full w-full bg-slate-950 flex items-center justify-center">
                            <img src="{{ $img->image_url }}" alt="{{ $propName }}" class="w-full h-full object-cover object-center">
                        </div>
                    @endforeach
                </div>
                @if($propImages->count() > 1)
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev slider-nav-btn !left-3 sm:!left-5 !top-1/2 !-translate-y-1/2 hidden sm:flex">
                        <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
                    </div>
                    <div class="swiper-button-next slider-nav-btn !right-3 sm:!right-5 !top-1/2 !-translate-y-1/2 hidden sm:flex">
                        <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                    </div>
                @endif
            </div>

            <!-- Floating Badges on Hero Image -->
            <div class="absolute top-3.5 left-3.5 sm:left-5 z-10 flex items-center gap-2">
                <span class="{{ $propTagMeta['solid_badge'] }} text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-md">
                    <i class="fas fa-{{ $propTagMeta['icon'] }} text-[11px]"></i> {{ $propTagMeta['label'] }}
                </span>
                <span class="{{ $propGenderMeta['class'] }} bg-white/95 backdrop-blur text-xs font-black px-3 py-1.5 rounded-xl shadow-md uppercase tracking-wide">
                    {{ $propGenderMeta['label'] }} STAY
                </span>
            </div>

            <div class="absolute bottom-3.5 right-3.5 sm:right-5 z-10 bg-black/75 backdrop-blur text-white text-xs px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 shadow-md">
                <i class="fas fa-images text-yellow-400"></i>
                <span class="font-bold">{{ $propImages->count() }} Photos</span>
            </div>
        </div>

        <!-- Thumbnail Strip with Tight Spacing & Active Indicator -->
        @if($propImages->count() > 1)
            <div class="flex items-center gap-2 sm:gap-3 mt-3.5 overflow-x-auto no-scrollbar py-1">
                @foreach($propImages as $idx => $tImg)
                    <button type="button" onclick="goToSlide({{ $idx }})" data-thumb-idx="{{ $idx }}" class="thumb-btn flex-shrink-0 w-20 sm:w-24 md:w-28 aspect-[4/3] rounded-2xl overflow-hidden border-2 {{ $idx === 0 ? 'border-brand ring-2 ring-brand/30 shadow-md opacity-100 scale-102' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-gray-400' }} cursor-pointer transition-all duration-200 group">
                        <img src="{{ $tImg->image_url }}" alt="{{ $propName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ================= STICKY DETAIL NAVIGATION TABS (ScrollSpy) ================= -->
    <div id="detailStickyNav" class="sticky top-[58px] md:top-20 z-40 bg-white/95 backdrop-blur-md border-y border-gray-200 shadow-xs mb-6 -mx-3 sm:-mx-6 lg:-mx-8 px-3 sm:px-6 lg:px-8 transition-all">
        <div class="max-w-7xl mx-auto flex items-center gap-6 sm:gap-8 overflow-x-auto no-scrollbar py-0">
            <a href="#sec-overview" data-target="sec-overview" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-bold text-gray-900 border-b-2 border-brand transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Overview</span>
            </a>
            <a href="#sec-pricing" data-target="sec-pricing" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Room &amp; Pricing</span>
            </a>
            <a href="#sec-amenities" data-target="sec-amenities" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Amenities</span>
            </a>
           
            <a href="#sec-location" data-target="sec-location" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Location</span>
            </a>
            <a href="#sec-rules" data-target="sec-rules" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Rules &amp; More</span>
            </a>
             <a href="#sec-reviews" data-target="sec-reviews" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Reviews</span>
            </a>
             <a href="#sec-more-listing" data-target="sec-more-listing" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Similar Listing</span>
            </a>
        </div>
    </div>

    <!-- ================= 2. MAIN DETAILS & SIDEBAR ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- Left Column: Details, Amenities, Rules, Location -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. OVERVIEW SECTION -->
            <div id="sec-overview" class="space-y-6 scroll-mt-36 md:scroll-mt-40">
                <!-- Title, Badges & Header Info -->
                <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                        <div>
                            <h1 class="text-xl sm:text-3xl font-black text-gray-900 leading-tight mb-2">{{ $propName }}</h1>
                            <p class="text-xs sm:text-sm text-gray-600 flex items-center gap-1.5">
                                <i class="fas fa-map-marker-alt text-brand text-sm"></i>
                                <span>{{ $propLocation }}</span>
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-1.5 rounded-2xl flex items-center gap-1.5 shadow-xs">
                                <i class="fas fa-star text-yellow-500 text-sm"></i>
                                <span class="font-extrabold text-sm">{{ $propRating }}</span>
                                <span class="text-[11px] text-gray-500 font-medium">({{ $propReviews }} {{ Str::plural('review', (int)$propReviews) }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bed Availability & Features Pill Row -->
                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-100">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl {{ $availBeds > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                            <i class="fas fa-bed"></i> {{ $availBeds > 0 ? $availBeds . ' Beds Available' : 'Fully Occupied' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 border border-blue-200">
                            <i class="fas fa-shield-alt"></i> Verified Property
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200">
                            <i class="fas fa-bolt"></i> 24/7 Power Backup
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-200">
                            <i class="fas fa-hand-holding-usd"></i> Zero Brokerage
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 border border-teal-200">
                            <i class="fas fa-calendar-check"></i> {{ $propNoticePeriod }} Days Notice
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200">
                            <i class="fas fa-screwdriver-wrench"></i> {{ $propMaintenance > 0 ? '₹' . number_format($propMaintenance) . '/mo Maint.' : 'Zero Maintenance' }}
                        </span>
                    </div>
                </div>

                <!-- About / Description -->
                <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                    <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-brand"></i> About this Stay
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $property->description ?? ($propName . ' offers premium, fully furnished student and professional accommodation. Located in a prime area with 24/7 security, high-speed WiFi, hygienic meals, and daily housekeeping.') }}
                    </p>
                    
                    @if($property && $property->landmark)
                        <div class="mt-4 p-3 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-2.5 text-xs text-gray-700">
                            <i class="fas fa-compass text-brand text-base"></i>
                            <span><strong>Key Landmark:</strong> {{ $property->landmark }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. ROOM SHARING & PRICING SECTION -->
            <div id="sec-pricing" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-door-open text-brand"></i> Room Sharing &amp; Pricing
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <!-- Single Room -->
                    <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between hover:border-brand transition">
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Private Room</span>
                            <h3 class="text-sm font-black text-gray-900 mt-1">Single Occupancy</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Attached washroom &amp; balcony</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <span class="text-base font-extrabold text-gray-900">₹{{ number_format((int)str_replace(',', '', $propRent) * 1.4) }}</span>
                            <span class="text-[11px] text-gray-500">/mo</span>
                        </div>
                    </div>

                    <!-- Double Sharing -->
                    <div class="p-4 rounded-2xl border-2 border-brand bg-brand-light/30 flex flex-col justify-between relative shadow-xs">
                        <span class="absolute -top-2.5 right-3 bg-brand text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase">Most Popular</span>
                        <div>
                            <span class="text-xs font-bold text-brand uppercase tracking-wider">Shared Room</span>
                            <h3 class="text-sm font-black text-gray-900 mt-1">Double Sharing</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Separate beds &amp; wardrobes</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-brand/20">
                            <span class="text-base font-extrabold text-brand-dark">₹{{ $propRent }}</span>
                            <span class="text-[11px] text-gray-500">/mo</span>
                        </div>
                    </div>

                    <!-- Triple Sharing -->
                    <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between hover:border-brand transition">
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Budget Shared</span>
                            <h3 class="text-sm font-black text-gray-900 mt-1">Triple Sharing</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Spacious room with study desk</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <span class="text-base font-extrabold text-gray-900">₹{{ number_format((int)str_replace(',', '', $propRent) * 0.85) }}</span>
                            <span class="text-[11px] text-gray-500">/mo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. AMENITIES SECTION -->
            <div id="sec-amenities" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-concierge-bell text-brand"></i> Verified Amenities &amp; Facilities
                    </h2>
                    @if($property && $property->amenities->count() > 0)
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                            {{ $property->amenities->count() }} Amenities Included
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-3 sm:gap-4">
                    @forelse($property && $property->amenities ? $property->amenities : [] as $am)
                        @php
                            $iconName = 'check-circle';
                            $iconColor = 'text-brand';
                            $slug = strtolower($am->slug ?? $am->name ?? '');
                            if (str_contains($slug, 'wifi') || str_contains($slug, 'internet')) { $iconName = 'wifi'; $iconColor = 'text-brand'; }
                            elseif (str_contains($slug, 'ac') || str_contains($slug, 'air')) { $iconName = 'snowflake'; $iconColor = 'text-cyan-500'; }
                            elseif (str_contains($slug, 'food') || str_contains($slug, 'meal')) { $iconName = 'utensils'; $iconColor = 'text-orange-500'; }
                            elseif (str_contains($slug, 'laundry') || str_contains($slug, 'wash')) { $iconName = 'tshirt'; $iconColor = 'text-indigo-500'; }
                            elseif (str_contains($slug, 'power') || str_contains($slug, 'backup')) { $iconName = 'bolt'; $iconColor = 'text-yellow-500'; }
                            elseif (str_contains($slug, 'cctv') || str_contains($slug, 'security')) { $iconName = 'shield-alt'; $iconColor = 'text-emerald-600'; }
                            elseif (str_contains($slug, 'housekeeping') || str_contains($slug, 'clean')) { $iconName = 'broom'; $iconColor = 'text-teal-600'; }
                            elseif (str_contains($slug, 'water') || str_contains($slug, 'ro')) { $iconName = 'tint'; $iconColor = 'text-sky-500'; }
                            elseif (str_contains($slug, 'bath') || str_contains($slug, 'washroom')) { $iconName = 'bath'; $iconColor = 'text-purple-500'; }
                            elseif (str_contains($slug, 'parking')) { $iconName = 'square-parking'; $iconColor = 'text-blue-600'; }
                            elseif (str_contains($slug, 'fridge') || str_contains($slug, 'refrigerator')) { $iconName = 'temperature-low'; $iconColor = 'text-cyan-600'; }
                            elseif (!empty($am->icon)) { $iconName = $am->icon; }
                        @endphp
                        <div class="flex items-center gap-3 p-3.5 bg-emerald-50/40 rounded-2xl border border-emerald-100 hover:border-brand hover:bg-brand-light/30 transition">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-xs {{ $iconColor }} flex items-center justify-center text-base flex-shrink-0 border border-emerald-100">
                                <i class="fas fa-{{ $iconName }}"></i>
                            </div>
                            <div class="truncate">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $am->name }}</p>
                                <p class="text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                                    <i class="fas fa-check-circle text-[9px]"></i> Available
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 sm:col-span-3 p-6 text-center text-gray-500 text-xs bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <i class="fas fa-concierge-bell text-2xl text-gray-300 mb-1.5 block"></i>
                            Standard accommodation facilities provided. Contact owner for full amenity inventory.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 4. FOOD & MEALS SECTION -->
            <!-- <div id="sec-food" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-utensils text-brand"></i> Food &amp; Meals Plan
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-4">
                    <div class="p-4 rounded-2xl bg-orange-50/60 border border-orange-100/80">
                        <div class="flex items-center gap-2 text-orange-600 font-bold text-xs mb-1">
                            <i class="fas fa-sun"></i> Breakfast
                        </div>
                        <p class="text-sm font-black text-gray-900">8:00 AM - 10:30 AM</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">Poha, Idli/Dosa, Parathas &amp; Tea</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100/80">
                        <div class="flex items-center gap-2 text-amber-600 font-bold text-xs mb-1">
                            <i class="fas fa-cloud-sun"></i> Lunch / Tiffin
                        </div>
                        <p class="text-sm font-black text-gray-900">1:00 PM - 3:00 PM</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">Dal, Seasonal Veg, Roti, Rice &amp; Salad</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100/80">
                        <div class="flex items-center gap-2 text-indigo-600 font-bold text-xs mb-1">
                            <i class="fas fa-moon"></i> Dinner
                        </div>
                        <p class="text-sm font-black text-gray-900">8:00 PM - 10:30 PM</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">Special Veg/Paneer, Dal Tadka &amp; Sweet</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs text-gray-600">
                    <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fas fa-seedling text-emerald-500"></i> Pure Veg &amp; Egg Options
                    </div>
                    <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fas fa-tint text-blue-500"></i> RO Purified Water
                    </div>
                    <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fas fa-snowflake text-cyan-500"></i> Common Refrigerator
                    </div>
                    <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                        <i class="fas fa-kitchen-set text-purple-500"></i> Microwave Access
                    </div>
                </div>
            </div> -->

          

            <!-- 6. LOCATION & NEIGHBORHOOD SECTION -->
            @php
                $mapLat = $property->map_latitude ?? $property->latitude ?? null;
                $mapLng = $property->map_longitude ?? $property->longitude ?? null;
                $fullAddressString = trim(($property->address ? $property->address . ', ' : '') . ($property->area->name ?? '') . ', ' . ($property->city->name ?? '') . ($property->pincode ? ' - ' . $property->pincode : ''));
                if (empty($fullAddressString)) {
                    $fullAddressString = $propLocation;
                }
                
                if ($mapLat && $mapLng && is_numeric($mapLat) && is_numeric($mapLng) && floatval($mapLat) != 0 && floatval($mapLng) != 0) {
                    $mapEmbedUrl = "https://maps.google.com/maps?q=" . $mapLat . "," . $mapLng . "&hl=en&z=15&output=embed";
                    $googleDirectionsUrl = "https://www.google.com/maps/dir/?api=1&destination=" . $mapLat . "," . $mapLng;
                } else {
                    $mapEmbedUrl = "https://maps.google.com/maps?q=" . urlencode($fullAddressString . ', India') . "&hl=en&z=15&output=embed";
                    $googleDirectionsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode(($property->name ?? $propName) . ' ' . $fullAddressString);
                }
            @endphp
            <div id="sec-location" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <div>
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-map-location-dot text-brand"></i> Location &amp; Neighborhood
                        </h2>
                        <!-- <p class="text-xs text-gray-500 mt-0.5">{{ $fullAddressString }}</p> -->
                    </div>
                    <a href="{{ $googleDirectionsUrl }}" target="_blank" class="px-4 py-2 bg-brand-light hover:bg-brand text-brand hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>

                <!-- Address Details Card -->
                <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-100 mb-4 flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center text-base flex-shrink-0 mt-0.5">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <div class="text-xs space-y-1">
                        <p class="font-bold text-gray-900">{{ $property->address ?: $propName }}</p>
                        <p class="text-gray-600">{{ ($property->area->name ?? '') . ($property->city ? ', ' . $property->city->name : '') . ($property->pincode ? ' - ' . $property->pincode : '') }}</p>
                        @if($property && $property->landmark)
                            <p class="text-emerald-700 font-semibold flex items-center gap-1 pt-0.5">
                                <i class="fas fa-compass text-[11px]"></i> <strong>Landmark:</strong> {{ $property->landmark }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                    <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base font-bold flex-shrink-0">
                            <i class="fas fa-train-subway"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Nearest Metro</p>
                            <p class="text-[11px] text-gray-500">~500m (5-7 mins walk)</p>
                        </div>
                    </div>
                    <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold flex-shrink-0">
                            <i class="fas fa-building-columns"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Tech Parks / Hubs</p>
                            <p class="text-[11px] text-gray-500">Close to IT Parks &amp; Unis</p>
                        </div>
                    </div>
                    <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base font-bold flex-shrink-0">
                            <i class="fas fa-basket-shopping"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Market &amp; Hospital</p>
                            <p class="text-[11px] text-gray-500">Within walking distance</p>
                        </div>
                    </div>
                </div> -->

                <!-- Google Maps Interactive Iframe -->
                <div class="rounded-2xl overflow-hidden border border-gray-200 h-64 sm:h-72 relative bg-slate-100 shadow-inner">
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{{ $mapEmbedUrl }}" loading="lazy" class="w-full h-full border-0"></iframe>
                    
                    <!-- Map Pin Floating Card -->
                    <div class="absolute bottom-3 left-3 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-xl shadow-md border border-gray-100 text-xs flex items-center gap-2 max-w-[90%] pointer-events-none">
                        <i class="fas fa-location-dot text-brand text-sm"></i>
                        <span class="font-bold text-gray-900 truncate">{{ $property->name ?? $propName }}</span>
                    </div>
                </div>
            </div>

            <!-- 7. HOUSE RULES & POLICIES SECTION -->
            <div id="sec-rules" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-brand"></i> House Rules &amp; Stay Policies
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <!-- Notice Period Policy -->
                    <div class="flex items-start gap-3 p-3.5 bg-teal-50/50 rounded-2xl border border-teal-100">
                        <i class="fas fa-calendar-check text-teal-600 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Notice Period</p>
                            <p class="text-[11px] text-gray-600">{{ $propNoticePeriod }} days prior notice required before vacating room</p>
                        </div>
                    </div>

                    <!-- Maintenance Policy -->
                    <div class="flex items-start gap-3 p-3.5 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                        <i class="fas fa-screwdriver-wrench text-indigo-600 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Maintenance Charges</p>
                            <p class="text-[11px] text-gray-600">{{ $propMaintenance > 0 ? '₹' . number_format($propMaintenance) . ' per month maintenance' : 'Zero maintenance charges (Included in rent)' }}</p>
                        </div>
                    </div>

                    @forelse($property ? $property->rules : [] as $rule)
                        <div class="flex items-start gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-check-circle text-emerald-500 text-sm mt-0.5 flex-shrink-0"></i>
                            <span class="text-xs sm:text-sm font-medium text-gray-800">{{ $rule->rule_text }}</span>
                        </div>
                    @empty
                        <div class="flex items-start gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-clock text-brand text-sm mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Gate Closing Time</p>
                                <p class="text-[11px] text-gray-500">10:30 PM (Exceptions with prior intimation)</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-user-friends text-brand text-sm mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Visitor Policy</p>
                                <p class="text-[11px] text-gray-500">Allowed in visitor lounge till 8:00 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-ban text-rose-500 text-sm mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Smoking &amp; Alcohol</p>
                                <p class="text-[11px] text-gray-500">Strictly prohibited inside room premises</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
              <!-- 5. REVIEWS & RATINGS SECTION -->
            <div id="sec-reviews" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-5 border-b border-gray-100">
                    <div>
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-star text-yellow-400"></i> Verified Resident Reviews
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Authentic feedback from verified guests &amp; residents</p>
                    </div>

                    <button type="button" onclick="openReviewModal()" id="reviewTriggerBtn" class="px-4 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-2xl text-xs font-bold transition tap-effect shadow-md shadow-brand/20 flex items-center gap-2">
                        <i class="fas fa-pen"></i> Write a Review
                    </button>
                </div>

                <!-- Rating Score Summary Box -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 p-4 sm:p-5 bg-gray-50/80 rounded-2xl border border-gray-100 items-center">
                    <div class="text-center sm:border-r sm:border-gray-200 sm:pr-4">
                        <div class="text-3xl sm:text-4xl font-black text-gray-900 leading-none mb-1">{{ $propRating }}</div>
                        <div class="flex items-center justify-center gap-1 text-yellow-400 text-sm mb-1">
                            @php
                                $starScore = is_numeric($propRating) ? round((float)$propRating) : 5;
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $starScore ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 font-semibold">{{ $propReviews }} Verified {{ Str::plural('Review', (int)$propReviews) }}</p>
                    </div>

                    <div class="sm:col-span-2 space-y-1.5 text-xs text-gray-600">
                        @php
                            $fiveStar = $approvedReviews->where('rating', '>=', 4.5)->count();
                            $fourStar = $approvedReviews->where('rating', '>=', 3.5)->where('rating', '<', 4.5)->count();
                            $threeStar = $approvedReviews->where('rating', '>=', 2.5)->where('rating', '<', 3.5)->count();
                            $twoStar = $approvedReviews->where('rating', '>=', 1.5)->where('rating', '<', 2.5)->count();
                            $oneStar = $approvedReviews->where('rating', '<', 1.5)->count();
                            $totalRev = max(1, $approvedReviews->count());
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="w-7 font-bold text-gray-700">5 ★</span>
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ ($fiveStar / $totalRev) * 100 }}%"></div>
                            </div>
                            <span class="w-6 text-right text-gray-500 font-medium">{{ $fiveStar }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-7 font-bold text-gray-700">4 ★</span>
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ ($fourStar / $totalRev) * 100 }}%"></div>
                            </div>
                            <span class="w-6 text-right text-gray-500 font-medium">{{ $fourStar }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-7 font-bold text-gray-700">3 ★</span>
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-500 rounded-full" style="width: {{ ($threeStar / $totalRev) * 100 }}%"></div>
                            </div>
                            <span class="w-6 text-right text-gray-500 font-medium">{{ $threeStar }}</span>
                        </div>
                    </div>
                </div>

                <!-- User Pending Review Banner -->
                @if(isset($userPendingReview) && $userPendingReview)
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-sm">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="text-xs">
                            <p class="font-bold text-amber-900">Your review is currently pending moderation</p>
                            <p class="text-amber-700 mt-0.5">You submitted a <strong>{{ $userPendingReview->rating }}★</strong> review: "{{ Str::limit($userPendingReview->comment, 80) }}". It will be published live once approved by our team.</p>
                        </div>
                    </div>
                @endif

                <!-- Approved Reviews List -->
                <div class="space-y-4">
                    @forelse($approvedReviews as $rev)
                        @php
                            $reviewerProfile = $rev->user?->profile;
                            $reviewerFullName = $reviewerProfile ? trim(($reviewerProfile->first_name ?? '') . ' ' . ($reviewerProfile->last_name ?? '')) : '';
                            $reviewerName = !empty($reviewerFullName) 
                                ? $reviewerFullName 
                                : ($rev->user?->name ?: ($rev->user?->email ? ucfirst(explode('@', $rev->user->email)[0]) : 'Verified Resident'));
                            $reviewerInitial = strtoupper(substr($reviewerName, 0, 1) ?: 'U');
                        @endphp
                        <div class="p-4 sm:p-5 rounded-2xl bg-gray-50/70 border border-gray-100 transition hover:border-gray-200 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand to-brand-light text-white font-black flex items-center justify-center text-sm shadow-xs flex-shrink-0">
                                        {{ $reviewerInitial }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-bold text-xs sm:text-sm text-gray-900">{{ $reviewerName }}</h4>
                                            <span class="text-[9px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full flex items-center gap-1">
                                                <i class="fas fa-check-circle text-[8px]"></i> Verified Stay
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $rev->created_at ? $rev->created_at->diffForHumans() : 'Recent' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 bg-white px-2.5 py-1 rounded-xl border border-gray-200 text-yellow-400 text-xs shadow-xs">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="{{ $s <= $rev->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                    <span class="text-[11px] font-bold text-gray-800 ml-1">{{ number_format($rev->rating, 1) }}</span>
                                </div>
                            </div>

                            @if($rev->title && $rev->title !== 'Verified Resident Review')
                                <h5 class="text-xs sm:text-sm font-bold text-gray-800">{{ $rev->title }}</h5>
                            @endif

                            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">{{ $rev->comment }}</p>

                            @if($rev->broker_reply)
                                <div class="p-3 bg-emerald-50/70 border-l-2 border-brand rounded-r-xl text-xs space-y-1">
                                    <p class="font-bold text-emerald-900 flex items-center gap-1">
                                        <i class="fas fa-reply text-brand text-[10px]"></i> Host Response:
                                    </p>
                                    <p class="text-emerald-800 text-[11px]">{{ $rev->broker_reply }}</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 px-4 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                            <div class="w-12 h-12 rounded-2xl bg-yellow-50 text-yellow-500 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="far fa-star"></i>
                            </div>
                            <h4 class="font-bold text-sm text-gray-800 mb-1">No Reviews Yet</h4>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">Be the first verified resident to share your stay experience and help others find their ideal room.</p>
                            <button type="button" onclick="openReviewModal()" class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs font-bold transition tap-effect shadow-md shadow-brand/20">
                                Write the First Review
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Booking Card & Host Contact (Sticky on Desktop) -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Sticky Container -->
            <div class="lg:sticky lg:top-40 space-y-6">

                <!-- Rent & Booking Summary Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xl">
                    <div class="flex items-baseline justify-between mb-3 pb-3 border-b border-gray-100">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 block">Monthly Rent</span>
                            <div class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">
                                ₹{{ $propRent }}<span class="text-xs font-normal text-gray-500">/month</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-semibold text-gray-500 block">Deposit</span>
                            <span class="text-sm font-bold text-gray-800">₹{{ $propDeposit }}</span>
                        </div>
                    </div>

                    <!-- Notice Period & Maintenance Badges -->
                    <div class="grid grid-cols-2 gap-2 mb-4 p-2.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs">
                        <div>
                            <span class="text-[10px] text-gray-400 font-bold uppercase block leading-none mb-1">Maintenance</span>
                            <span class="font-bold text-gray-900 leading-tight flex items-center gap-1 text-xs">
                                <i class="fas fa-screwdriver-wrench text-indigo-500 text-[10px]"></i>
                                {{ $propMaintenance > 0 ? '₹' . number_format($propMaintenance) . '/mo' : 'Included (₹0)' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] text-gray-400 font-bold uppercase block leading-none mb-1">Notice Period</span>
                            <span class="font-bold text-gray-900 leading-tight flex items-center gap-1 text-xs">
                                <i class="fas fa-calendar-day text-teal-600 text-[10px]"></i>
                                {{ $propNoticePeriod }} Days
                            </span>
                        </div>
                    </div>

                    <!-- Direct Book & Contact CTA -->
                    <div class="space-y-3">
                        <a href="{{ route('user.bookings') }}" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-bold py-3.5 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-sm shadow-md">
                            <i class="fas fa-calendar-check"></i> Book Stay Online
                        </a>

                        <!-- <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi, I am interested in ' . $propName . ' (' . $propLocation . '). Please share room availability.') }}" target="_blank" class="w-full bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold py-3.5 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-sm shadow-xs">
                            <i class="fab fa-whatsapp text-lg text-emerald-600"></i> WhatsApp Host
                        </a>

                        <a href="tel:{{ $cleanPhone }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-xs">
                            <i class="fas fa-phone-alt text-brand"></i> Call Host Directly
                        </a> -->
                    </div>

                    <!-- Assured Promises -->
                    <div class="mt-5 pt-4 border-t border-gray-100 space-y-2 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span>Zero Booking Charges</span>
                        </div>
                        <!-- <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span>100% Verified Accommodation</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span>Immediate Move-In Available</span>
                        </div> -->
                    </div>
                </div>

                <!-- Host Profile Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3.5">Owner</h3>
                    <div class="flex items-center gap-3.5 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand to-brand-light text-white flex items-center justify-center text-lg font-black shadow-sm">
                            {{ strtoupper(substr($ownerName, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-extrabold text-sm text-gray-900">{{ $ownerName }}</p>
                            <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                <i class="fas fa-check-circle text-[11px]"></i> Verified 
                            </p>
                        </div>
                    </div>
                    <!-- Property Owner -->
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <a href="https://wa.me/91{{ $cleanPhone }}?text={{ urlencode('Hi, I want to schedule a visit to ' . $propName) }}" target="_blank" class="p-2.5 bg-gray-50 hover:bg-emerald-50 rounded-xl border border-gray-200 text-center font-bold text-gray-700 hover:text-emerald-700 transition">
                            <i class="fab fa-whatsapp text-emerald-500 mr-1"></i> Chat
                        </a>
                        <a href="tel:91{{ $cleanPhone }}" class="p-2.5 bg-gray-50 hover:bg-brand-light/30 rounded-xl border border-gray-200 text-center font-bold text-gray-700 hover:text-brand transition">
                            <i class="fas fa-phone mr-1 text-brand"></i> Call Now
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- ================= 3. SIMILAR RECOMMENDED STAYS ================= -->
    @if(isset($similarProperties) && $similarProperties->count() > 0)
        <div  id="sec-more-listing" class="mt-12 sm:mt-16 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900">Similar Verified Stays Nearby</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Explore more approved co-living stays in {{ $property->city->name ?? 'the city' }}</p>
                </div>
                <a href="{{ route('user.search', ['city' => $property->city->name ?? '']) }}" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
                    View All <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- ===== MOBILE: 2-Column Horizontal Slider ===== -->
            <div class="md:hidden swiper similarSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($similarProperties as $sim)
                        @php
                            $simTag = $sim->display_tag_meta;
                            $simGender = $sim->gender_type_meta;
                            $simSlug = route('user.detail', ['slug' => $sim->slug ?: \Illuminate\Support\Str::slug($sim->name)]);
                        @endphp
                        <div class="swiper-slide !h-auto">
                            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm card-lift flex flex-col justify-between h-full group">
                                <div>
                                    <!-- Card Image -->
                                    <div class="relative aspect-[4/3] overflow-hidden">
                                        <img src="{{ $sim->display_image_url }}" alt="{{ $sim->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute top-2 left-2 {{ $simTag['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-lg shadow-sm">
                                            <i class="fas fa-{{ $simTag['icon'] }}"></i> {{ $simTag['label'] }}
                                        </div>
                                        <div class="absolute bottom-2 left-2 bg-black/70 backdrop-blur text-white text-[10px] px-2 py-0.5 rounded-lg flex items-center gap-1 font-bold">
                                            <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $sim->rating ? number_format($sim->rating, 1) : '4.8' }}
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-2.5">
                                        <div class="flex justify-between items-start mb-1 gap-1">
                                            <h3 class="font-bold text-xs text-gray-900 group-hover:text-brand transition truncate">{{ $sim->name }}</h3>
                                            <span class="{{ $simGender['class'] }} text-[8px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">{{ $simGender['label'] }}</span>
                                        </div>
                                        <p class="text-gray-500 text-[10px] mb-1 flex items-center gap-1 truncate">
                                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ ($sim->area ? $sim->area->name . ', ' : '') . ($sim->city ? $sim->city->name : '') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="px-2.5 pb-2.5 pt-2 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[9px] text-gray-400 block">Rent</span>
                                        <span class="text-xs font-extrabold text-gray-900">₹{{ number_format($sim->monthly_rent) }}<span class="text-[8px] font-normal text-gray-500">/mo</span></span>
                                    </div>
                                    <a href="{{ $simSlug }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1 rounded-lg text-[10px] font-bold transition shadow-xs">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ===== DESKTOP: 4-Col Grid ===== -->
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                @foreach($similarProperties as $sim)
                    @php
                        $simTag = $sim->display_tag_meta;
                        $simGender = $sim->gender_type_meta;
                        $simSlug = route('user.detail', ['slug' => $sim->slug ?: \Illuminate\Support\Str::slug($sim->name)]);
                    @endphp
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm card-lift flex flex-col justify-between group">
                        <div>
                            <!-- Card Image -->
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="{{ $sim->display_image_url }}" alt="{{ $sim->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-2.5 left-2.5 {{ $simTag['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-lg shadow-sm">
                                    <i class="fas fa-{{ $simTag['icon'] }}"></i> {{ $simTag['label'] }}
                                </div>
                                <div class="absolute bottom-2.5 left-2.5 bg-black/70 backdrop-blur text-white text-[10px] px-2.5 py-0.5 rounded-lg flex items-center gap-1 font-bold">
                                    <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $sim->rating ? number_format($sim->rating, 1) : '4.8' }}
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-3.5 sm:p-4">
                                <div class="flex justify-between items-start mb-1 gap-1">
                                    <h3 class="font-bold text-xs sm:text-sm text-gray-900 group-hover:text-brand transition truncate">{{ $sim->name }}</h3>
                                    <span class="{{ $simGender['class'] }} text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">{{ $simGender['label'] }}</span>
                                </div>
                                <p class="text-gray-500 text-[10px] sm:text-xs mb-2 flex items-center gap-1 truncate">
                                    <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ ($sim->area ? $sim->area->name . ', ' : '') . ($sim->city ? $sim->city->name : '') }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-3 pb-3 sm:px-4 sm:pb-4 pt-2 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] text-gray-400 block">Rent</span>
                                <span class="text-xs sm:text-sm font-extrabold text-gray-900">₹{{ number_format($sim->monthly_rent) }}<span class="text-[9px] font-normal text-gray-500">/mo</span></span>
                            </div>
                            <a href="{{ $simSlug }}" class="bg-brand hover:bg-brand-dark text-white px-3 py-1.5 rounded-xl text-[10px] sm:text-xs font-bold transition shadow-xs">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- ================= REPORT / FEEDBACK MODAL ================= -->
<div id="reportListingModal" class="fixed inset-0 z-[3000] hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div onclick="closeReportModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Dialog -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 z-10 border border-gray-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-sm font-bold">
                    <i class="fas fa-flag"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-gray-900">Report Listing / Feedback</h3>
                    <p class="text-[11px] text-gray-500">Help us maintain verified, quality stays on StayNest</p>
                </div>
            </div>
            <button onclick="closeReportModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="reportForm" onsubmit="submitReport(event)">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id ?? '' }}">

            <div class="space-y-4">
                <!-- Reason Selection -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">What is the issue with this listing? <span class="text-rose-500">*</span></label>
                    <select name="reason" id="reportReason" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer">
                        <option value="">-- Select Report Reason --</option>
                        <option value="Incorrect Price / Hidden Charges">💰 Incorrect Price / Hidden Charges</option>
                        <option value="Fake or Misleading Photos">📷 Fake or Misleading Photos</option>
                        <option value="Property Closed / Already Full">🚫 Property Closed / Already Full</option>
                        <option value="Fraud / Fake Location / Does Not Exist">⚠️ Fraud / Inaccurate Location / Does Not Exist</option>
                        <option value="Host Unreachable / Rude / Abusive Behavior">📞 Host Unreachable / Rude / Abusive Behavior</option>
                        <option value="Amenities Not Available as Described">⚡ Amenities Not Available as Described</option>
                        <option value="Other Issue">💬 Other Issue</option>
                    </select>
                </div>

                <!-- Description / Feedback Textarea -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Provide Additional Details</label>
                    <textarea name="description" id="reportDescription" rows="3" placeholder="Explain what happened or what details are inaccurate..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
                </div>

                <!-- Contact details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Your Name</label>
                        <input type="text" name="reporter_name" value="{{ auth()->user()?->name ?? '' }}" placeholder="Name (optional)" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 px-3 text-xs focus:ring-2 focus:ring-brand/50">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Phone (10 Digits)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-xs font-semibold text-gray-400">+91</span>
                            <input type="tel" name="reporter_phone" id="reporter_phone" maxlength="10" minlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" value="{{ auth()->user()?->phone ? substr(preg_replace('/[^0-9]/', '', auth()->user()->phone), -10) : '' }}" placeholder="9876543210" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-10 pr-3 text-xs focus:ring-2 focus:ring-brand/50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-xs transition">
                    Cancel
                </button>
                <button type="submit" id="reportSubmitBtn" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition shadow-md shadow-rose-600/30 flex items-center gap-1.5">
                    <i class="fas fa-paper-plane"></i> Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= WRITE REVIEW MODAL (AUTH USERS) ================= -->
<div id="writeReviewModal" class="fixed inset-0 z-[3000] hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div onclick="closeReviewModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Dialog -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 z-10 border border-gray-100 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-sm font-bold">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-gray-900">Write a Review</h3>
                    <p class="text-[11px] text-gray-500">Your review will be published after admin approval</p>
                </div>
            </div>
            <button onclick="closeReviewModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="reviewForm" onsubmit="submitReview(event)">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id ?? '' }}">
            <input type="hidden" name="rating" id="reviewRatingInput" value="5">

            <div class="space-y-4">
                <!-- Star Rating Selector -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Overall Rating <span class="text-rose-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5 text-2xl" id="starRatingSelector">
                            @for($s = 1; $s <= 5; $s++)
                                <button type="button" onclick="setRating({{ $s }})" onmouseover="highlightStars({{ $s }})" onmouseleave="resetStars()" class="star-rating-btn text-yellow-400 hover:scale-110 transition-transform">
                                    <i class="fas fa-star" data-star="{{ $s }}"></i>
                                </button>
                            @endfor
                        </div>
                        <span id="ratingLabel" class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl ml-2">5 - Excellent</span>
                    </div>
                </div>

                <!-- Review Title -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Review Title (Optional)</label>
                    <input type="text" name="title" id="reviewTitle" placeholder="e.g. Great amenities and clean rooms!" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>

                <!-- Review Comments -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Your Experience & Feedback <span class="text-rose-500">*</span></label>
                    <textarea name="comment" id="reviewComment" rows="4" required placeholder="Tell us about the food quality, WiFi speed, cleanliness, host behavior and room comfort..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
                </div>

                <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-2.5 text-[11px] text-blue-700">
                    <i class="fas fa-shield-alt text-blue-500 text-xs"></i>
                    <span>Reviews are moderated by our team to maintain authentic, quality experiences.</span>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-xs transition">
                    Cancel
                </button>
                <button type="submit" id="reviewSubmitBtn" class="px-6 py-2.5 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-xs transition shadow-md shadow-brand/30 flex items-center gap-1.5">
                    <i class="fas fa-paper-plane"></i> Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= SHARE THIS PROPERTY MODAL ================= -->
<div id="sharePropertyModal" class="fixed inset-0 z-[3000] hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div onclick="closeShareModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <!-- Modal Dialog (Matches Provided Design) -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-7 z-10 border border-gray-100 animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Share this property</h3>
                <p class="text-sm text-gray-500 mt-1">Share this amazing stay with your friends and family</p>
            </div>
            <button type="button" onclick="closeShareModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition -mt-1 -mr-1" title="Close">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <div class="space-y-5">
            <!-- Property Link Input Box -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Property Link</label>
                <div class="flex items-center gap-2 border-2 border-[#4bb59d]/80 hover:border-[#4bb59d] focus-within:border-[#4bb59d] focus-within:ring-4 focus-within:ring-[#4bb59d]/15 rounded-2xl p-1.5 pl-3.5 bg-white transition shadow-2xs">
                    <input type="text" id="sharePropertyUrlInput" readonly value="{{ url()->current() }}" onclick="copySharePropertyLink()" class="w-full bg-transparent text-xs sm:text-sm text-gray-800 font-medium outline-none truncate select-all cursor-pointer">
                    <button type="button" onclick="copySharePropertyLink()" id="shareCopyBtn" class="w-9 h-9 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 flex items-center justify-center text-gray-600 hover:text-gray-900 transition flex-shrink-0 cursor-pointer" title="Copy to clipboard">
                        <i class="far fa-copy text-sm" id="shareCopyIcon"></i>
                    </button>
                </div>
                <p id="shareCopySuccessMsg" class="hidden text-xs font-semibold text-emerald-600 mt-1.5 flex items-center gap-1">
                    <i class="fas fa-circle-check"></i> Link copied to clipboard!
                </p>
            </div>

            <!-- Share on Social Media -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2.5">Share on social media</label>
                <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
                    <!-- Twitter / X -->
                    <button type="button" onclick="shareToTwitter()" class="flex items-center justify-center gap-2 py-2.5 px-3 border border-gray-200 hover:border-gray-300 rounded-2xl hover:bg-gray-50 transition text-sm font-medium text-gray-800 group shadow-2xs cursor-pointer">
                        <i class="fab fa-twitter text-[#1DA1F2] text-base group-hover:scale-110 transition-transform"></i>
                        <span>Twitter</span>
                    </button>

                    <!-- Facebook -->
                    <button type="button" onclick="shareToFacebook()" class="flex items-center justify-center gap-2 py-2.5 px-3 border border-gray-200 hover:border-gray-300 rounded-2xl hover:bg-gray-50 transition text-sm font-medium text-gray-800 group shadow-2xs cursor-pointer">
                        <i class="fab fa-facebook text-[#1877F2] text-base group-hover:scale-110 transition-transform"></i>
                        <span>Facebook</span>
                    </button>

                    <!-- WhatsApp -->
                    <button type="button" onclick="shareToWhatsApp()" class="flex items-center justify-center gap-2 py-2.5 px-3 border border-gray-200 hover:border-gray-300 rounded-2xl hover:bg-gray-50 transition text-sm font-medium text-gray-800 group shadow-2xs cursor-pointer">
                        <i class="fab fa-whatsapp text-[#25D366] text-lg group-hover:scale-110 transition-transform"></i>
                        <span>WhatsApp</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer Close Button -->
        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end">
            <button type="button" onclick="closeShareModal()" class="px-6 py-2 rounded-2xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-800 font-semibold text-sm transition cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Mobile Sticky Bottom Action Bar -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-200 z-50 px-4 py-3 pb-safe shadow-xl">
    <div class="flex items-center justify-between gap-3">
        <div>
            <span class="text-[10px] text-gray-500 block leading-none">Starting from</span>
            <div class="text-lg font-black text-gray-900">₹{{ $propRent }}<span class="text-[10px] font-normal text-gray-500">/mo</span></div>
        </div>
        <div class="flex items-center gap-2 flex-1 justify-end">
            <button type="button" onclick="openShareModal()" class="w-11 h-11 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-center text-gray-700 hover:text-brand text-base tap-effect cursor-pointer" title="Share Property">
                <i class="fas fa-share-alt"></i>
            </button>
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi, I am interested in ' . $propName) }}" target="_blank" class="w-11 h-11 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-center text-emerald-600 text-lg tap-effect">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="{{ route('user.bookings') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-2.5 px-5 rounded-2xl text-xs tap-effect shadow-md shadow-brand/30">
                Book Stay
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    let detailSwiperInstance = null;

    function syncThumbnails(realIdx) {
        document.querySelectorAll('.thumb-btn').forEach((btn) => {
            const idx = parseInt(btn.getAttribute('data-thumb-idx'));
            if (idx === realIdx) {
                btn.classList.add('border-brand', 'ring-2', 'ring-brand/30', 'shadow-md', 'opacity-100', 'scale-102');
                btn.classList.remove('border-gray-200', 'opacity-70');
            } else {
                btn.classList.remove('border-brand', 'ring-2', 'ring-brand/30', 'shadow-md', 'opacity-100', 'scale-102');
                btn.classList.add('border-gray-200', 'opacity-70');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const slideCount = document.querySelectorAll('.detailSwiper .swiper-slide').length;
        if (slideCount > 1) {
            detailSwiperInstance = new Swiper('.detailSwiper', {
                loop: true,
                pagination: { el: '.detailSwiper .swiper-pagination', clickable: true },
                navigation: { nextEl: '.detailSwiper .swiper-button-next', prevEl: '.detailSwiper .swiper-button-prev' },
                autoplay: { delay: 4500, disableOnInteraction: false },
                on: {
                    slideChange: function() {
                        syncThumbnails(this.realIndex);
                    }
                }
            });
        }

        if (document.querySelector('.similarSwiper')) {
            new Swiper('.similarSwiper', {
                slidesPerView: 2,
                spaceBetween: 10,
                breakpoints: {
                    480: { slidesPerView: 2, spaceBetween: 12 },
                    640: { slidesPerView: 2.5, spaceBetween: 14 }
                }
            });
        }

        /* ================= DETAIL TABS SCROLLSPY ================= */
        const navTabs = document.querySelectorAll('.detail-nav-tab');
        const sections = [
            document.getElementById('sec-overview'),
            document.getElementById('sec-pricing'),
            document.getElementById('sec-amenities'),
            document.getElementById('sec-reviews'),
            document.getElementById('sec-location'),
            document.getElementById('sec-more-listing'),
            document.getElementById('sec-rules')
        ].filter(Boolean);

        navTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const targetEl = document.getElementById(targetId);
                if (!targetEl) return;

                const headerOffset = window.innerWidth < 768 ? 120 : 155;
                const elementPosition = targetEl.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                setActiveNavTab(targetId);
            });
        });

        function setActiveNavTab(targetId) {
            navTabs.forEach(t => {
                if (t.getAttribute('data-target') === targetId) {
                    t.classList.add('text-gray-900', 'font-bold', 'border-brand');
                    t.classList.remove('text-gray-500', 'border-transparent', 'font-semibold');
                    t.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                } else {
                    t.classList.remove('text-gray-900', 'font-bold', 'border-brand');
                    t.classList.add('text-gray-500', 'border-transparent', 'font-semibold');
                }
            });
        }

        let isScrollTicking = false;
        window.addEventListener('scroll', function() {
            if (!isScrollTicking) {
                window.requestAnimationFrame(function() {
                    const scrollPos = window.pageYOffset || document.documentElement.scrollTop;
                    const headerOffset = window.innerWidth < 768 ? 130 : 165;

                    let activeId = sections[0]?.id;

                    for (let i = 0; i < sections.length; i++) {
                        const sec = sections[i];
                        const top = sec.offsetTop - headerOffset;
                        if (scrollPos >= top) {
                            activeId = sec.id;
                        }
                    }

                    if (activeId) {
                        navTabs.forEach(t => {
                            if (t.getAttribute('data-target') === activeId) {
                                t.classList.add('text-gray-900', 'font-bold', 'border-brand');
                                t.classList.remove('text-gray-500', 'border-transparent', 'font-semibold');
                            } else {
                                t.classList.remove('text-gray-900', 'font-bold', 'border-brand');
                                t.classList.add('text-gray-500', 'border-transparent', 'font-semibold');
                            }
                        });
                    }

                    isScrollTicking = false;
                });
                isScrollTicking = true;
            }
        }, { passive: true });
    });

    function goToSlide(index) {
        if (detailSwiperInstance) {
            detailSwiperInstance.slideToLoop(index);
            syncThumbnails(index);
        }
    }

    /* Review Star Selector & Submission Handlers */
    let selectedRating = 5;
    const ratingLabels = {
        1: '1 - Terrible',
        2: '2 - Poor',
        3: '3 - Average',
        4: '4 - Very Good',
        5: '5 - Excellent'
    };

    function setRating(val) {
        selectedRating = val;
        const ratingInput = document.getElementById('reviewRatingInput');
        if (ratingInput) ratingInput.value = val;
        const ratingLabel = document.getElementById('ratingLabel');
        if (ratingLabel) ratingLabel.innerText = ratingLabels[val] || (val + ' Stars');
        updateStarIcons(val);
    }

    function highlightStars(val) {
        updateStarIcons(val);
        const ratingLabel = document.getElementById('ratingLabel');
        if (ratingLabel) ratingLabel.innerText = ratingLabels[val] || (val + ' Stars');
    }

    function resetStars() {
        updateStarIcons(selectedRating);
        const ratingLabel = document.getElementById('ratingLabel');
        if (ratingLabel) ratingLabel.innerText = ratingLabels[selectedRating] || (selectedRating + ' Stars');
    }

    function updateStarIcons(val) {
        document.querySelectorAll('#starRatingSelector [data-star]').forEach(el => {
            const star = parseInt(el.getAttribute('data-star'));
            if (star <= val) {
                el.className = 'fas fa-star text-yellow-400';
            } else {
                el.className = 'far fa-star text-gray-300';
            }
        });
    }

    function isUserAuthenticated() {
        const serverAuth = {{ Auth::check() ? 'true' : 'false' }};
        if (serverAuth) return true;
        const token = localStorage.getItem('staynest_token');
        const userStr = localStorage.getItem('staynest_user');
        return !!(token || userStr);
    }

    function openReviewModal() {
        if (!isUserAuthenticated()) {
            window.location.href = "{{ route('user.login') }}";
            return;
        }
        const modal = document.getElementById('writeReviewModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeReviewModal() {
        const modal = document.getElementById('writeReviewModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function submitReview(e) {
        e.preventDefault();
        const form = document.getElementById('reviewForm');
        const submitBtn = document.getElementById('reviewSubmitBtn');
        const comment = document.getElementById('reviewComment')?.value.trim();

        if (!comment) {
            alert('Please enter your review feedback.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        const token = localStorage.getItem('staynest_token') || '';
        const rawUser = localStorage.getItem('staynest_user');
        let userId = '';
        if (rawUser) {
            try {
                const parsed = JSON.parse(rawUser);
                userId = parsed.id || '';
            } catch(err) {}
        }

        try {
            const formData = new FormData(form);
            if (userId) {
                formData.append('auth_user_id', userId);
            }

            const headers = {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json'
            };
            if (token) {
                headers['Authorization'] = 'Bearer ' + token;
            }

            const res = await fetch("{{ route('user.property.review', ['id' => $property->id ?? 'dummy']) }}", {
                method: 'POST',
                headers: headers,
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert('✅ ' + data.message);
                form.reset();
                closeReviewModal();
                window.location.reload();
            } else {
                if (res.status === 401) {
                    alert('⚠️ Please log in to submit your review.');
                    window.location.href = "{{ route('user.login') }}";
                } else {
                    alert('⚠️ ' + (data.message || 'Could not submit review. Please try again.'));
                }
            }
        } catch (err) {
            alert('✅ Thank you! Your review has been submitted for moderation and will appear publicly once approved.');
            closeReviewModal();
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Review';
        }
    }

    function openReportModal() {
        const modal = document.getElementById('reportListingModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeReportModal() {
        const modal = document.getElementById('reportListingModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function submitReport(e) {
        e.preventDefault();
        const form = document.getElementById('reportForm');
        const submitBtn = document.getElementById('reportSubmitBtn');
        const reason = document.getElementById('reportReason').value;
        const phone = document.getElementById('reporter_phone')?.value.trim();

        if (!reason) {
            alert('Please select a report reason.');
            return;
        }

        if (phone && !/^[0-9]{10}$/.test(phone)) {
            alert('Please enter a valid 10-digit mobile number.');
            document.getElementById('reporter_phone')?.focus();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        try {
            const formData = new FormData(form);
            const res = await fetch("{{ route('user.property.report', ['id' => $property->id ?? 'dummy']) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert('✅ ' + data.message);
                form.reset();
                closeReportModal();
            } else {
                alert('⚠️ ' + (data.message || 'Could not submit report. Please try again.'));
            }
        } catch (err) {
            alert('✅ Thank you. Your feedback has been submitted to our moderation team.');
            closeReportModal();
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Report';
        }
    }

    /* ================= SHARE PROPERTY MODAL HANDLERS ================= */
    function openShareModal() {
        const modal = document.getElementById('sharePropertyModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            const urlInput = document.getElementById('sharePropertyUrlInput');
            if (urlInput) {
                urlInput.value = window.location.href;
            }
        }
    }

    function closeShareModal() {
        const modal = document.getElementById('sharePropertyModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function copySharePropertyLink() {
        const url = window.location.href;
        const icon = document.getElementById('shareCopyIcon');
        const msg = document.getElementById('shareCopySuccessMsg');
        const input = document.getElementById('sharePropertyUrlInput');

        if (input) {
            input.value = url;
            input.select();
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(() => {
                showCopySuccess();
            }).catch(() => {
                fallbackCopyText(url);
            });
        } else {
            fallbackCopyText(url);
        }

        function showCopySuccess() {
            if (icon) icon.className = 'fas fa-check text-emerald-600 text-sm';
            if (msg) msg.classList.remove('hidden');
            setTimeout(() => {
                if (icon) icon.className = 'far fa-copy text-sm';
                if (msg) msg.classList.add('hidden');
            }, 2500);
        }

        function fallbackCopyText(text) {
            try {
                if (input) {
                    input.select();
                    document.execCommand('copy');
                    showCopySuccess();
                }
            } catch (e) {
                prompt('Copy this link:', text);
            }
        }
    }

    function shareToTwitter() {
        const title = "{{ addslashes($propName) }} - Verified PG & Co-Living Stay on StayNest";
        const url = window.location.href;
        const shareUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(title) + '&url=' + encodeURIComponent(url);
        window.open(shareUrl, '_blank', 'width=600,height=450,scrollbars=yes');
    }

    function shareToFacebook() {
        const url = window.location.href;
        const shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
        window.open(shareUrl, '_blank', 'width=600,height=450,scrollbars=yes');
    }

    function shareToWhatsApp() {
        const title = "Check out {{ addslashes($propName) }} (₹{{ $propRent }}/mo) on StayNest:";
        const url = window.location.href;
        const shareUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(title + '\n' + url);
        window.open(shareUrl, '_blank');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeShareModal();
            closeReportModal();
            closeReviewModal();
        }
    });
</script>
@endpush
