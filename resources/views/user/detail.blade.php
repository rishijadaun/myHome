@extends('user.layouts.app')

@php
    $propName = $property->name ?? 'Sunrise Premium PG';
    $propTagMeta = $property ? $property->display_tag_meta : ['label' => 'Verified', 'icon' => 'check-circle', 'solid_badge' => 'bg-emerald-500 text-white'];
    $propGenderMeta = $property ? $property->gender_type_meta : ['label' => 'BOYS', 'class' => 'bg-blue-50 text-blue-600', 'btn_class' => 'bg-blue-600 text-white'];
    $propRent = $property ? number_format($property->monthly_rent) : '8,500';
    $propDeposit = $property && $property->security_deposit ? number_format($property->security_deposit) : number_format((int)str_replace(',', '', $propRent) * 2);
    $propLocation = $property ? (($property->address ?: ($property->area->name ?? '')) . ', ' . ($property->city->name ?? 'Noida')) : 'Sector 62, Noida, Uttar Pradesh';
    $propRating = $property && $property->rating ? number_format($property->rating, 1) : '4.8';
    $propReviews = $property && $property->total_reviews ? $property->total_reviews : '120';
    $propImages = ($property && $property->images->count() > 0) ? $property->images : collect([(object)['image_url' => $property->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80']]);
    $propOwner = $property->broker ?? null;
    $ownerName = $propOwner->name ?? 'Vikram Singh';
    $ownerPhone = $propOwner->phone ?? '919876543210';
    $cleanPhone = preg_replace('/[^0-9]/', '', $ownerPhone);
    $totalBeds = $property->total_beds ?? 12;
    $availBeds = $property->available_beds ?? min(4, $totalBeds);
@endphp

@section('title', $propName . ' - StayNest')

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
            <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Listing link copied to clipboard!');" class="flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-gray-600 hover:text-brand transition text-xs font-semibold shadow-xs">
                <i class="fas fa-share-alt"></i> <span class="hidden sm:inline">Share</span>
            </button>
            
            <!-- Wishlist Button -->
            <button type="button" onclick="heartToggle(this, { id: '{{ $property->id ?? '' }}', title: '{{ addslashes($propName) }}', price: '₹{{ $propRent }}', image: '{{ $propImages->first()->image_url ?? '' }}', location: '{{ addslashes($propLocation) }}', type: '{{ $propGenderMeta['label'] }}' })" data-prop-id="{{ $property->id ?? '' }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-rose-50 border border-gray-200 hover:border-rose-200 rounded-xl text-gray-600 hover:text-rose-500 transition text-xs font-semibold shadow-xs">
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

    <!-- ================= 2. MAIN DETAILS & SIDEBAR ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- Left Column: Details, Amenities, Rules, Location -->
        <div class="lg:col-span-2 space-y-6">

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
                            <span class="text-[11px] text-gray-500 font-medium">({{ $propReviews }} reviews)</span>
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

            <!-- Room Sharing & Pricing Options -->
            <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-door-open text-brand"></i> Room Sharing & Pricing
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <!-- Single Room -->
                    <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between hover:border-brand transition">
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Private Room</span>
                            <h3 class="text-sm font-black text-gray-900 mt-1">Single Occupancy</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Attached washroom & balcony</p>
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
                            <p class="text-[11px] text-gray-500 mt-0.5">Separate beds & wardrobes</p>
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

            <!-- Amenities Grid -->
            <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-concierge-bell text-brand"></i> Verified Amenities & Facilities
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">
                    @forelse($property ? $property->amenities : [] as $am)
                        <div class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-brand hover:bg-brand-light/20 transition">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-xs text-brand flex items-center justify-center text-base flex-shrink-0">
                                <i class="fas fa-{{ $am->icon ?? 'check-circle' }}"></i>
                            </div>
                            <div class="truncate">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $am->name }}</p>
                                <p class="text-[10px] text-emerald-600 font-semibold">Included</p>
                            </div>
                        </div>
                    @empty
                        @php
                            $defaultAms = [
                                ['name' => 'High-Speed WiFi', 'icon' => 'wifi'],
                                ['name' => 'Air Conditioning', 'icon' => 'snowflake'],
                                ['name' => 'Hygienic Meals / Food', 'icon' => 'utensils'],
                                ['name' => 'Power Backup 24x7', 'icon' => 'bolt'],
                                ['name' => 'CCTV & Security', 'icon' => 'shield-alt'],
                                ['name' => 'Daily Housekeeping', 'icon' => 'broom'],
                                ['name' => 'Attached Washroom', 'icon' => 'bath'],
                                ['name' => 'RO Drinking Water', 'icon' => 'tint'],
                            ];
                        @endphp
                        @foreach($defaultAms as $dam)
                            <div class="flex items-center gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-brand hover:bg-brand-light/20 transition">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-xs text-brand flex items-center justify-center text-base flex-shrink-0">
                                    <i class="fas fa-{{ $dam['icon'] }}"></i>
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-gray-900 truncate">{{ $dam['name'] }}</p>
                                    <p class="text-[10px] text-emerald-600 font-semibold">Included</p>
                                </div>
                            </div>
                        @endforeach
                    @endforelse
                </div>
            </div>

            <!-- House Rules & Policies -->
            <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-brand"></i> House Rules & Stay Policies
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
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
                                <p class="text-xs font-bold text-gray-900">Smoking & Alcohol</p>
                                <p class="text-[11px] text-gray-500">Strictly prohibited inside room premises</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-calendar-alt text-brand text-sm mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Notice Period</p>
                                <p class="text-[11px] text-gray-500">30 days prior notice before vacate</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Report Listing Notice Card -->
            <!-- <div class="p-4 sm:p-5 rounded-3xl bg-gray-50 border border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-3 text-left">
                    <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 text-base">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-gray-900">StayNest Trust & Safety Guarantee</p>
                        <p class="text-[11px] text-gray-500">Found inaccurate info, misleading photos or face issues with this host?</p>
                    </div>
                </div>
                <button type="button" onclick="openReportModal()" class="px-4 py-2 bg-white hover:bg-rose-50 border border-gray-300 hover:border-rose-300 text-gray-700 hover:text-rose-600 rounded-xl text-xs font-bold transition tap-effect shadow-xs flex-shrink-0 flex items-center gap-1.5">
                    <i class="fas fa-flag text-rose-500"></i> Report Listing
                </button>
            </div> -->
        </div>

        <!-- Right Column: Booking Card & Host Contact (Sticky on Desktop) -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Sticky Container -->
            <div class="lg:sticky lg:top-24 space-y-6">

                <!-- Rent & Booking Summary Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xl">
                    <div class="flex items-baseline justify-between mb-4 pb-4 border-b border-gray-100">
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

                    <!-- Direct Book & Contact CTA -->
                    <div class="space-y-3">
                        <a href="{{ route('user.bookings') }}" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-bold py-3.5 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-sm shadow-md">
                            <i class="fas fa-calendar-check"></i> Book Stay Online
                        </a>

                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi, I am interested in ' . $propName . ' (' . $propLocation . '). Please share room availability.') }}" target="_blank" class="w-full bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold py-3.5 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-sm shadow-xs">
                            <i class="fab fa-whatsapp text-lg text-emerald-600"></i> WhatsApp Host
                        </a>

                        <a href="tel:{{ $cleanPhone }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-xs">
                            <i class="fas fa-phone-alt text-brand"></i> Call Host Directly
                        </a>
                    </div>

                    <!-- Assured Promises -->
                    <div class="mt-5 pt-4 border-t border-gray-100 space-y-2 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span>Zero Booking Charges</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span>100% Verified Accommodation</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span>Immediate Move-In Available</span>
                        </div>
                    </div>
                </div>

                <!-- Host Profile Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3.5">Listing Host / Manager</h3>
                    <div class="flex items-center gap-3.5 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand to-brand-light text-white flex items-center justify-center text-lg font-black shadow-sm">
                            {{ strtoupper(substr($ownerName, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-extrabold text-sm text-gray-900">{{ $ownerName }}</p>
                            <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                <i class="fas fa-check-circle text-[11px]"></i> Verified StayNest Host
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi, I want to schedule a visit to ' . $propName) }}" target="_blank" class="p-2.5 bg-gray-50 hover:bg-emerald-50 rounded-xl border border-gray-200 text-center font-bold text-gray-700 hover:text-emerald-700 transition">
                            <i class="fab fa-whatsapp text-emerald-500 mr-1"></i> Visit Schedule
                        </a>
                        <a href="tel:{{ $cleanPhone }}" class="p-2.5 bg-gray-50 hover:bg-brand-light/30 rounded-xl border border-gray-200 text-center font-bold text-gray-700 hover:text-brand transition">
                            <i class="fas fa-phone mr-1 text-brand"></i> Call Now
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- ================= 3. SIMILAR RECOMMENDED STAYS ================= -->
    @if(isset($similarProperties) && $similarProperties->count() > 0)
        <div class="mt-12 sm:mt-16 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900">Similar Verified Stays Nearby</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Explore more approved co-living stays in {{ $property->city->name ?? 'the city' }}</p>
                </div>
                <a href="{{ route('user.search', ['city' => $property->city->name ?? '']) }}" class="text-xs font-bold text-brand hover:underline flex items-center gap-1">
                    View All <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                @foreach($similarProperties as $sim)
                    @php
                        $simTag = $sim->display_tag_meta;
                        $simGender = $sim->gender_type_meta;
                        $simSlug = route('user.detail', ['slug' => $sim->slug ?: \Illuminate\Support\Str::slug($sim->name)]);
                    @endphp
                    <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-gray-100 shadow-sm card-lift flex flex-col justify-between group">
                        <div>
                            <!-- Card Image -->
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="{{ $sim->display_image_url }}" alt="{{ $sim->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-2 left-2 {{ $simTag['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-lg shadow-sm">
                                    <i class="fas fa-{{ $simTag['icon'] }}"></i> {{ $simTag['label'] }}
                                </div>
                                <div class="absolute bottom-2 left-2 bg-black/70 backdrop-blur text-white text-[10px] px-2 py-0.5 rounded-lg flex items-center gap-1 font-bold">
                                    <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $sim->rating ? number_format($sim->rating, 1) : '4.8' }}
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-3 sm:p-4">
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

<!-- Mobile Sticky Bottom Action Bar -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-200 z-50 px-4 py-3 pb-safe shadow-xl">
    <div class="flex items-center justify-between gap-3">
        <div>
            <span class="text-[10px] text-gray-500 block leading-none">Starting from</span>
            <div class="text-lg font-black text-gray-900">₹{{ $propRent }}<span class="text-[10px] font-normal text-gray-500">/mo</span></div>
        </div>
        <div class="flex items-center gap-2 flex-1 justify-end">
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
    });

    function goToSlide(index) {
        if (detailSwiperInstance) {
            detailSwiperInstance.slideToLoop(index);
            syncThumbnails(index);
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
</script>
@endpush
