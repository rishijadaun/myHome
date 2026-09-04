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
    $roomConfigurations = $property ? ($property->room_configurations ?? collect()) : collect();
    $userReview = $userReview ?? null;
    $existingBooking = $existingBooking ?? null;
    $typeSlug = strtolower($property->propertyType?->slug ?? '');
    $propCat = strtolower($property->property_category ?? '');
    $genderPref = strtolower($property->gender_preference ?? '');
    $isSale = (bool) ($property->is_sale ?? false) || (($property->ad_type ?? '') === 'sale');
    $isCommercial = $propCat === 'commercial' || str_contains($typeSlug, 'commercial') || str_contains($typeSlug, 'office') || str_contains($typeSlug, 'shop') || str_contains($typeSlug, 'warehouse') || ($genderPref === 'not_applicable');
    $isPlot = $propCat === 'land-plot' || str_contains($typeSlug, 'plot') || str_contains($typeSlug, 'land');
    $isFlat = !$isCommercial && !$isPlot && (str_contains($typeSlug, 'flat') || str_contains($typeSlug, 'apartment') || str_contains($typeSlug, 'house') || str_contains($typeSlug, 'villa') || str_contains($typeSlug, 'builder') || in_array($genderPref, ['all', 'any']));
    $isPg = !$isCommercial && !$isPlot && !$isFlat;

    $salePriceDisplay = $property->display_price_formatted ?? ('₹' . number_format($property->expected_price ?: $property->monthly_rent));
    $propExpectedPrice = (float)($property->expected_price ?: $property->monthly_rent);
    $propTokenBooking = $property->booking_token_amount ? '₹' . number_format($property->booking_token_amount) : '₹1,00,000';
    $propOwnership = $property->ownership_type ?: 'Freehold (Clear Title)';
    $propPossession = $property->possession_status ?: 'Ready to Move';

    // Extract or format Apartment/Flat and Commercial configurations
    $propDescFull = ($property->description ?? '') . ' ' . ($property->name ?? '');
    
    // Flat Configuration
    $flatBhk = $property->bhk_type ?: '2 BHK';
    if (empty($property->bhk_type)) {
        if (preg_match('/\b(1|2|3|4|5)\s*BHK\b/i', $propDescFull, $mBhk)) {
            $flatBhk = strtoupper(trim($mBhk[0]));
        } elseif (stripos($propDescFull, 'studio') !== false || stripos($propDescFull, '1 RK') !== false) {
            $flatBhk = '1 RK / Studio';
        } elseif (stripos($propDescFull, 'villa') !== false) {
            $flatBhk = '3 BHK Luxury Villa';
        }
    }

    $flatFurnishing = $property->furnishing_status ?: 'Semi Furnished';
    if (empty($property->furnishing_status)) {
        if (preg_match('/\b(semi[\s-]*furnished)\b/i', $propDescFull)) {
            $flatFurnishing = 'Semi Furnished';
        } elseif (preg_match('/\b(fully[\s-]*furnished|fully\s+furnished)\b/i', $propDescFull)) {
            $flatFurnishing = 'Fully Furnished';
        } elseif (preg_match('/\b(unfurnished|raw|bare[\s-]*shell)\b/i', $propDescFull)) {
            $flatFurnishing = 'Unfurnished';
        } elseif (preg_match('/\b(furnished)\b/i', $propDescFull)) {
            $flatFurnishing = 'Furnished';
        }
    }

    $flatCarpetArea = $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' sq ft' : '1,100 sq ft';
    if (empty($property->carpet_area_sqft)) {
        if (preg_match('/(\d{3,5})\s*(?:sq\s*ft|sqft|sq\.ft)/i', $propDescFull, $mArea)) {
            $flatCarpetArea = number_format((int)$mArea[1]) . ' sq ft';
        } else {
            if (str_contains($flatBhk, '1 BHK') || str_contains($flatBhk, '1 RK')) $flatCarpetArea = '650 sq ft';
            elseif (str_contains($flatBhk, '2 BHK')) $flatCarpetArea = '1,100 sq ft';
            elseif (str_contains($flatBhk, '3 BHK')) $flatCarpetArea = '1,550 sq ft';
            elseif (str_contains($flatBhk, '4') || str_contains($flatBhk, '5')) $flatCarpetArea = '2,200 sq ft';
        }
    }

    $propCarpetArea = $flatCarpetArea;
    $rawSqft = max(100, (int)str_replace([',', ' sq ft'], '', $flatCarpetArea));
    $propRatePerSqft = $property->price_per_sqft ?: ('₹' . number_format(round($propExpectedPrice / $rawSqft)) . '/sq ft');

    $flatBaths = str_contains($flatBhk, '1 BHK') || str_contains($flatBhk, '1 RK') ? '1' : (str_contains($flatBhk, '3 BHK') ? '3' : '2');

    // Commercial Configuration
    $commercialType = 'Commercial Office / Retail Space';
    if (stripos($propDescFull, 'shop') !== false || stripos($propDescFull, 'retail') !== false) {
        $commercialType = 'Retail Commercial Shop';
    } elseif (stripos($propDescFull, 'showroom') !== false) {
        $commercialType = 'Commercial Showroom';
    } elseif (stripos($propDescFull, 'coworking') !== false || stripos($propDescFull, 'desk') !== false) {
        $commercialType = 'Co-Working Shared Office';
    } elseif (stripos($propDescFull, 'office') !== false) {
        $commercialType = 'Commercial Office Space';
    }

    $commercialFurnishing = 'Furnished Office Space';
    if (stripos($propDescFull, 'bare shell') !== false || stripos($propDescFull, 'raw') !== false) {
        $commercialFurnishing = 'Bare Shell';
    } elseif (stripos($propDescFull, 'warm shell') !== false) {
        $commercialFurnishing = 'Warm Shell (Ready Ceiling & AC)';
    }

    $commercialArea = '1,500 sq ft';
    if (preg_match('/(\d{3,5})\s*(?:sq\s*ft|sqft|sq\.ft)/i', $propDescFull, $mCArea)) {
        $commercialArea = number_format((int)$mCArea[1]) . ' sq ft';
    }
    
    if ($isSale) {
        $propSeoTitle = $propName . ' - Property For Sale in ' . ($property->area->name ?? '') . ', ' . ($property->city->name ?? 'India') . ' | ' . $salePriceDisplay . ' | StayNest';
        $propSeoDesc = 'Buy ' . $propName . ' in ' . $propLocation . ' on StayNest. Zero brokerage, ' . $salePriceDisplay . ', ' . $propRating . '★ rating. Clear title, verified legal documentation & site visit.';
        $propSeoKeywords = $propName . ', Property For Sale in ' . ($property->area->name ?? '') . ', in ' . ($property->city->name ?? '') . ', Buy Property, StayNest';
    } elseif ($isCommercial) {
        $propSeoTitle = $propName . ' - Commercial Space in ' . ($property->area->name ?? '') . ', ' . ($property->city->name ?? 'India') . ' | ₹' . $propRent . '/mo | StayNest';
        $propSeoDesc = 'Book ' . $propName . ' in ' . $propLocation . ' on StayNest. Zero brokerage, ₹' . $propRent . '/month, ' . $propRating . '★ rating. Modern amenities, verified biometric security & instant booking.';
        $propSeoKeywords = $propName . ', Commercial Space in ' . ($property->area->name ?? '') . ', in ' . ($property->city->name ?? '') . ', StayNest';
    } elseif ($isFlat) {
        $propSeoTitle = $propName . ' - Flat & House Rental in ' . ($property->area->name ?? '') . ', ' . ($property->city->name ?? 'India') . ' | ₹' . $propRent . '/mo | StayNest';
        $propSeoDesc = 'Book ' . $propName . ' in ' . $propLocation . ' on StayNest. Zero brokerage, ₹' . $propRent . '/month, ' . $propRating . '★ rating. Modern amenities, verified biometric security & instant booking.';
        $propSeoKeywords = $propName . ', Flat for Rent in ' . ($property->area->name ?? '') . ', in ' . ($property->city->name ?? '') . ', StayNest';
    } else {
        $propSeoTitle = $propName . ' - ' . ucfirst($property->gender_preference ?? 'Co-living') . ' PG in ' . ($property->area->name ?? '') . ', ' . ($property->city->name ?? 'India') . ' | ₹' . $propRent . '/mo | StayNest';
        $propSeoDesc = 'Book ' . $propName . ' in ' . $propLocation . ' on StayNest. Zero brokerage, ₹' . $propRent . '/month, ' . $propRating . '★ rating. Modern amenities, verified biometric security & instant booking.';
        $propSeoKeywords = $propName . ', PG in ' . ($property->area->name ?? '') . ', in ' . ($property->city->name ?? '') . ', StayNest';
    }
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
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "@id": "{{ $propCanonical }}#breadcrumb",
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
          "name": "Search",
          "item": "{{ route('user.search') }}"
        }@if($property && $property->city),
        {
          "@type": "ListItem",
          "position": 3,
          "name": "PG in {{ $property->city->name }}",
          "item": "{{ route('user.seo.city-area', ['city' => strtolower($property->city->slug ?: $property->city->name)]) }}"
        }@endif @if($property && $property->area),
        {
          "@type": "ListItem",
          "position": 4,
          "name": "{{ $property->area->name }}",
          "item": "{{ route('user.seo.city-area', ['city' => strtolower($property->city->slug ?: $property->city->name), 'area' => strtolower($property->area->slug ?: $property->area->name)]) }}"
        }@endif,
        {
          "@type": "ListItem",
          "position": {{ ($property && $property->city ? 1 : 0) + ($property && $property->area ? 1 : 0) + 3 }},
          "name": "{{ addslashes($propName) }}",
          "item": "{{ $propCanonical }}"
        }
      ]
    },
    {
      "@type": "{{ $isSale ? 'RealEstateListing' : ($isCommercial ? 'CommercialProperty' : ($isFlat ? 'Apartment' : 'LodgingBusiness')) }}",
      "@id": "{{ $propCanonical }}#property",
      "name": "{{ addslashes($propName) }}",
      "image": "{{ $propSeoImage }}",
      "url": "{{ $propCanonical }}",
      "telephone": "+91{{ $cleanPhone }}",
      "priceRange": "{{ $isSale ? $salePriceDisplay : '₹' . $propRent . ' - ₹' . $propDeposit }}",
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
      @if($propReviews > 0)
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $propRating }}",
        "reviewCount": "{{ max(1, (int)$propReviews) }}",
        "bestRating": "5",
        "worstRating": "1"
      },
      @endif
      @if(isset($approvedReviews) && $approvedReviews->count() > 0)
      "review": [
        @foreach($approvedReviews->take(5) as $r)
        {
          "@type": "Review",
          "reviewRating": {
            "@type": "Rating",
            "ratingValue": "{{ $r->rating }}",
            "bestRating": "5"
          },
          "author": {
            "@type": "Person",
            "name": "{{ addslashes($r->user->name ?? 'Verified Tenant') }}"
          },
          "datePublished": "{{ ($r->created_at ?? now())->format('Y-m-d') }}",
          "reviewBody": "{{ addslashes(strip_tags($r->comment ?? 'Great stay and verified amenities.')) }}"
        }@if(!$loop->last),@endif
        @endforeach
      ],
      @endif
      "amenityFeature": [
        @foreach($property->amenities ?? [] as $amenity)
        {
          "@type": "LocationFeatureSpecification",
          "name": "{{ addslashes($amenity->name) }}",
          "value": true
        }@if(!$loop->last),@endif
        @endforeach
      ],
      "makesOffer": {
        "@type": "Offer",
        "price": "{{ $isSale ? (float)($property->expected_price ?: $property->monthly_rent) : (float)$property->monthly_rent }}",
        "priceCurrency": "INR",
        "availability": "https://schema.org/InStock",
        "priceSpecification": {
          "@type": "UnitPriceSpecification",
          "price": "{{ $isSale ? (float)($property->expected_price ?: $property->monthly_rent) : (float)$property->monthly_rent }}",
          "priceCurrency": "INR",
          "unitText": "{{ $isSale ? 'TOTAL' : 'MONTH' }}"
        }
      }
    }
  ]
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
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(8px);
        color: #1f2937 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.8);
        display: flex !important;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 30 !important;
        margin-top: 0 !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
    }
    .slider-nav-btn:hover {
        background: #ffffff !important;
        color: #4bb59d !important;
        transform: translateY(-50%) scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
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
        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-gray-500 flex-wrap">
            <a href="{{ route('user.home') }}" class="hover:text-brand transition flex items-center gap-1"><i class="fas fa-home text-xs"></i> Home</a>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
            <a href="{{ route('user.search') }}" class="hover:text-brand transition">Search</a>
            @if($property && $property->city)
                <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                <a href="{{ route('user.seo.city-area', ['city' => strtolower($property->city->slug ?: $property->city->name)]) }}" class="hover:text-brand transition">{{ $property->city->name }}</a>
            @endif
            @if($property && $property->area)
                <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                <a href="{{ route('user.seo.city-area', ['city' => strtolower($property->city->slug ?: $property->city->name), 'area' => strtolower($property->area->slug ?: $property->area->name)]) }}" class="hover:text-brand transition">{{ $property->area->name }}</a>
            @endif
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-gray-900 font-semibold truncate max-w-[200px] sm:max-w-xs" aria-current="page">{{ $propName }}</span>
        </nav>

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
                    @foreach($propImages as $idx => $img)
                        <div class="swiper-slide h-full w-full bg-slate-950 flex items-center justify-center skeleton-shimmer">
                            <img src="{{ $img->image_url }}" alt="{{ $propName }} - Verified Stay in {{ $property->area->name ?? '' }}, {{ $property->city->name ?? 'India' }} (Photo {{ $idx + 1 }})" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}" decoding="async" class="w-full h-full object-cover object-center" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                        </div>
                    @endforeach
                </div>
                @if($propImages->count() > 1)
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev slider-nav-btn !left-3 sm:!left-5 hidden sm:flex" aria-label="Previous Slide">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </div>
                    <div class="swiper-button-next slider-nav-btn !right-3 sm:!right-5 hidden sm:flex" aria-label="Next Slide">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </div>
                @endif
            </div>

            <!-- Floating Badges on Hero Image -->
            <div class="absolute top-3.5 left-3.5 sm:left-5 z-10 flex items-center gap-2">
                <span class="{{ $propTagMeta['solid_badge'] }} text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-md">
                    <i class="fas fa-{{ $propTagMeta['icon'] }} text-[11px]"></i> {{ $propTagMeta['label'] }}
                </span>
                @if($isSale)
                    <span class="bg-amber-500 text-white text-xs font-black px-3 py-1.5 rounded-xl shadow-md uppercase tracking-wide flex items-center gap-1">
                        <i class="fas fa-tags text-[11px]"></i> FOR SALE
                    </span>
                @elseif($isCommercial)
                    <span class="bg-amber-50 text-amber-800 bg-white/95 backdrop-blur text-xs font-black px-3 py-1.5 rounded-xl shadow-md uppercase tracking-wide">
                        <i class="fas fa-store mr-1 text-amber-600"></i> COMMERCIAL
                    </span>
                @elseif($isFlat)
                    <span class="bg-indigo-50 text-indigo-700 bg-white/95 backdrop-blur text-xs font-black px-3 py-1.5 rounded-xl shadow-md uppercase tracking-wide">
                        <i class="fas fa-building mr-1 text-indigo-600"></i> FLAT / HOUSE
                    </span>
                @else
                    <span class="{{ $propGenderMeta['class'] }} bg-white/95 backdrop-blur text-xs font-black px-3 py-1.5 rounded-xl shadow-md uppercase tracking-wide">
                        {{ $propGenderMeta['label'] }} STAY
                    </span>
                @endif
            </div>

            <div class="absolute bottom-3.5 right-3.5 sm:right-5 z-10 bg-black/75 backdrop-blur text-white text-xs px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 shadow-md">
                <i class="fas fa-images text-yellow-400"></i>
                <span class="font-bold">{{ $propImages->count() }} Photos</span>
            </div>
        </div>

        <!-- Thumbnail Strip (Only when > 1 image) -->
        @if($propImages->count() > 1)
            <div id="thumbScrollContainer" class="flex items-center gap-2 sm:gap-3 mt-3.5 overflow-x-auto scroll-smooth no-scrollbar py-1">
                @foreach($propImages as $idx => $tImg)
                    <button type="button" onclick="goToSlide({{ $idx }})" data-thumb-idx="{{ $idx }}" class="thumb-btn flex-shrink-0 w-20 sm:w-24 md:w-28 aspect-[4/3] rounded-2xl overflow-hidden border-2 {{ $idx === 0 ? 'border-brand ring-2 ring-brand/30 shadow-md opacity-100 scale-102' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-gray-400' }} cursor-pointer transition-all duration-200 group">
                        <img src="{{ $tImg->thumbnail_url ?? $tImg->image_url }}" alt="{{ $propName }} thumbnail {{ $idx + 1 }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ================= STICKY DETAIL NAVIGATION TABS (ScrollSpy) ================= -->
    <div id="detailStickyNav" class="sticky top-[58px] md:top-20 z-40 bg-white/95 backdrop-blur-md border-y border-gray-200 shadow-xs mb-6 -mx-3 sm:-mx-6 lg:-mx-8 px-3 sm:px-6 lg:px-8 transition-all">
        <div class="max-w-7xl mx-auto flex items-center gap-6 sm:gap-8 overflow-x-auto no-scrollbar py-0">
            <a href="#sec-overview" data-target="sec-overview" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-bold text-brand border-b-2 border-brand transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Overview</span>
            </a>
            @if($isSale)
                <a href="#sec-pricing" data-target="sec-pricing" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                    <span>Valuation &amp; Price</span>
                </a>
            @elseif($isPg && $roomConfigurations && $roomConfigurations->count() > 0)
                <a href="#sec-pricing" data-target="sec-pricing" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                    <span>Room &amp; Pricing</span>
                </a>
            @elseif($isCommercial)
                <a href="#sec-pricing" data-target="sec-pricing" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                    <span>Commercial Lease</span>
                </a>
            @elseif($isFlat)
                <a href="#sec-pricing" data-target="sec-pricing" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                    <span>Rental Details</span>
                </a>
            @endif
            <a href="#sec-amenities" data-target="sec-amenities" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>{{ $isCommercial ? 'Features & Facilities' : ($isFlat ? 'Flat Amenities' : 'Amenities') }}</span>
            </a>
            <a href="#sec-location" data-target="sec-location" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Location</span>
            </a>
            <a href="#sec-rules" data-target="sec-rules" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>{{ $isCommercial ? 'Lease Terms' : ($isFlat ? 'Society Guidelines' : 'Rules & More') }}</span>
            </a>
            <a href="#sec-reviews" data-target="sec-reviews" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Reviews</span>
            </a>
            @if(isset($similarProperties) && $similarProperties->count() > 0)
                <a href="#sec-more-listing" data-target="sec-more-listing" class="detail-nav-tab py-3.5 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                    <span>Similar Listing</span>
                </a>
            @endif
        </div>
    </div>

    <!-- ================= 2. MAIN DETAILS & SIDEBAR ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- Left Column: Details, Amenities, Rules, Location -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. OVERVIEW SECTION -->
            <div id="sec-overview" class="space-y-6 scroll-mt-36 md:scroll-mt-40">
                @if($isPg && !$isSale && ($availBeds === 0 || ($property && $property->is_fully_booked)))
                    <div class="bg-gradient-to-r from-rose-500 to-amber-600 text-white rounded-3xl p-4 sm:p-5 flex items-center justify-between shadow-md">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl flex-shrink-0">
                                <i class="fas fa-bed-pulse"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-sm sm:text-base leading-tight">100% Fully Booked / No Vacancy Available</h3>
                                <p class="text-xs text-white/90 mt-0.5">All beds and rooms in this property are currently occupied. You can still contact the owner to enquire about future openings.</p>
                            </div>
                        </div>
                        <span class="hidden sm:inline-flex bg-white/20 border border-white/30 text-white text-xs font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider">Sold Out</span>
                    </div>
                @endif

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

                    <!-- Bed / Space Availability & Features Pill Row -->
                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-100">
                        @if($isSale)
                            <span class="inline-flex items-center gap-1.5 text-xs font-black px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-300">
                                <i class="fas fa-tags text-amber-600"></i> Valuation: {{ $salePriceDisplay }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 border border-blue-200">
                                <i class="fas fa-calculator text-blue-600"></i> Rate: {{ $propRatePerSqft }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fas fa-file-contract text-emerald-600"></i> {{ $propOwnership }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200">
                                <i class="fas fa-key text-purple-600"></i> {{ $propPossession }}
                            </span>
                        @elseif($isCommercial)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-200">
                                <i class="fas fa-store"></i> Ready to Occupy
                            </span>
                        @elseif($isFlat)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200">
                                <i class="fas fa-house-user"></i> Full Flat / House
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl {{ $availBeds > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                <i class="fas fa-bed"></i> {{ $availBeds > 0 ? $availBeds . ' Beds Available' : 'Fully Occupied' }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 border border-blue-200">
                            <i class="fas fa-shield-alt"></i> Verified Property
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-200">
                            <i class="fas fa-hand-holding-usd"></i> Zero Brokerage
                        </span>
                        @if(!$isSale)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 border border-purple-200">
                                <i class="fas fa-bolt"></i> 24/7 Power Backup
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 border border-teal-200">
                                <i class="fas fa-calendar-check"></i> {{ $propNoticePeriod }} Days Notice
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200">
                                <i class="fas fa-screwdriver-wrench"></i> {{ $propMaintenance > 0 ? '₹' . number_format($propMaintenance) . '/mo Maint.' : 'Zero Maintenance' }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- About / Description -->
                <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                    <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-brand"></i> {{ $isSale ? 'About this Property For Sale' : ($isCommercial ? 'About this Commercial Space' : ($isFlat ? 'About this Flat / House' : 'About this Stay')) }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $property->description ?? ($propName . ' offers premium, ' . ($isSale ? 'verified property for sale with clear title and prime connectivity.' : ($isCommercial ? 'commercial office/retail space.' : ($isFlat ? 'independent apartment/house rental.' : 'student and professional accommodation.')))) }}
                    </p>
                    
                    @if($property && $property->landmark)
                        <div class="mt-4 p-3 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-2.5 text-xs text-gray-700">
                            <i class="fas fa-compass text-brand text-base"></i>
                            <span><strong>Key Landmark:</strong> {{ $property->landmark }}</span>
                        </div>
                    @endif
                </div>

                @if($isFlat || $isSale)
                    <!-- 🏡 Apartment / Flat / Property Configuration -->
                    <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-home text-brand text-lg"></i> Property Specifications &amp; Area
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4">
                            <!-- BHK Type -->
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-gray-50 border border-gray-200/80 flex flex-col justify-between">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-500 mb-1.5">BHK / Config</span>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0"><i class="fas fa-door-open"></i></div>
                                    <span class="text-sm sm:text-base font-extrabold text-gray-900 truncate">{{ $flatBhk }}</span>
                                </div>
                            </div>

                            <!-- Furnishing -->
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-gray-50 border border-gray-200/80 flex flex-col justify-between">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-500 mb-1.5">Furnishing</span>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold shrink-0"><i class="fas fa-couch"></i></div>
                                    <span class="text-sm sm:text-base font-extrabold text-gray-900 truncate">{{ $flatFurnishing }}</span>
                                </div>
                            </div>

                            <!-- Carpet Area -->
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-gray-50 border border-gray-200/80 flex flex-col justify-between">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-500 mb-1.5">Carpet Area (sq ft)</span>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold shrink-0"><i class="fas fa-ruler-combined"></i></div>
                                    <span class="text-sm sm:text-base font-extrabold text-gray-900 truncate">{{ $propCarpetArea }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Key Specifications -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-3 border-t border-gray-100 text-xs">
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-bath text-indigo-500"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">{{ $flatBaths }}</strong> Bathrooms</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-compass text-teal-500"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">North-East</strong> Facing</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-layer-group text-purple-500"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">{{ $isSale ? $propOwnership : 'Mid-Rise (4th Floor)' }}</strong></span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-car text-blue-500"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">Covered</strong> Parking</span>
                            </div>
                        </div>
                    </div>
                @elseif($isCommercial)
                    <!-- 🏬 Commercial Space Configuration -->
                    <div class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm">
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-store text-amber-600 text-lg"></i> Commercial Space Configuration
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4">
                            <!-- Space Category -->
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-gray-50 border border-gray-200/80 flex flex-col justify-between">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-500 mb-1.5">Space Category</span>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold shrink-0"><i class="fas fa-briefcase"></i></div>
                                    <span class="text-sm sm:text-base font-extrabold text-gray-900 truncate">{{ $commercialType }}</span>
                                </div>
                            </div>

                            <!-- Furnishing Status -->
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-gray-50 border border-gray-200/80 flex flex-col justify-between">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-500 mb-1.5">Furnishing Status</span>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold shrink-0"><i class="fas fa-couch"></i></div>
                                    <span class="text-sm sm:text-base font-extrabold text-gray-900 truncate">{{ $commercialFurnishing }}</span>
                                </div>
                            </div>

                            <!-- Usable Carpet Area -->
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-gray-50 border border-gray-200/80 flex flex-col justify-between">
                                <span class="text-[11px] sm:text-xs font-semibold text-gray-500 mb-1.5">Carpet Area (sq ft)</span>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0"><i class="fas fa-ruler-combined"></i></div>
                                    <span class="text-sm sm:text-base font-extrabold text-gray-900 truncate">{{ $commercialArea }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Commercial Specs -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-3 border-t border-gray-100 text-xs">
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-car text-gray-400"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">Reserved</strong> Parking</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-bolt text-amber-500"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">100%</strong> Backup</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-elevator text-blue-500"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">Passenger</strong> Lifts</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-shield-halved text-emerald-500"></i>
                                <span class="text-gray-700 font-medium"><strong class="text-gray-900">24/7</strong> Security</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 2. PRICING & SPACE CONFIGURATION SECTION -->
            @if($isSale)
                <!-- Property Sale Valuation & Details -->
                <div id="sec-pricing" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-tags text-amber-600"></i> Property Valuation &amp; Purchase Details
                        </h2>
                        <span class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 px-3 py-1 rounded-xl">
                            100% Zero Brokerage Sale
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mb-4">
                        <div class="p-4 rounded-2xl border-2 border-amber-500/40 bg-amber-50/40 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">Expected Selling Price</span>
                                <h3 class="text-xl sm:text-2xl font-black text-gray-900 mt-1">{{ $salePriceDisplay }}</h3>
                                <p class="text-[11px] text-gray-600 mt-0.5">{{ $propRatePerSqft }} • Total Valuation</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-amber-200 flex items-center justify-between">
                                <span class="text-[11px] font-bold text-amber-700">0% Commission</span>
                                <button type="button" onclick="openBookStayModal('Property Purchase / Site Visit', {{ (int)$propExpectedPrice }})" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold rounded-xl transition tap-effect shadow-xs cursor-pointer">
                                    Book Site Visit
                                </button>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Booking Token Amount</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">{{ $propTokenBooking }}</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">Adjustable against total property price</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-[11px] text-gray-600">
                                <span>Ownership:</span>
                                <span class="font-bold text-gray-900">{{ $propOwnership }}</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Possession Status</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">{{ $propPossession }}</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">Direct seller deal with verified title docs</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-[11px] text-gray-600">
                                <span>Home Loan:</span>
                                <span class="font-bold text-emerald-600 flex items-center gap-1"><i class="fas fa-check-circle text-[10px]"></i> Pre-approved 80%</span>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($isCommercial)
                <!-- Commercial Space Lease & Pricing Details -->
                <div id="sec-pricing" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-store text-amber-600"></i> Commercial Space Lease &amp; Pricing
                        </h2>
                        <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1 rounded-xl">
                            100% Zero Brokerage
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div class="p-4 rounded-2xl border-2 border-amber-500/40 bg-amber-50/40 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">Monthly Lease Rent</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">₹{{ $propRent }}<span class="text-xs font-normal text-gray-500">/mo</span></h3>
                                <p class="text-[11px] text-gray-600 mt-0.5">Prime commercial unit with direct owner lease terms</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-amber-200 flex items-center justify-between">
                                <span class="text-[11px] font-bold text-amber-700">0% Commission</span>
                                <button type="button" onclick="openBookStayModal('Full Commercial Space', {{ (int)str_replace(',', '', $propRent) }})" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold rounded-xl transition tap-effect shadow-xs cursor-pointer">
                                    Book Space
                                </button>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Security Deposit</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">₹{{ $propDeposit }}</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">100% refundable commercial lease deposit</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-[11px] text-gray-600">
                                <span>Lock-in / Notice:</span>
                                <span class="font-bold text-gray-900">{{ $propNoticePeriod }} Days</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Maintenance &amp; Facilities</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">{{ $propMaintenance > 0 ? '₹' . number_format($propMaintenance) . '/mo' : 'Included in Rent' }}</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">Common building upkeep &amp; facilities</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-[11px] text-gray-600">
                                <span>Occupancy:</span>
                                <span class="font-bold text-emerald-600 flex items-center gap-1"><i class="fas fa-check-circle text-[10px]"></i> Ready to Move</span>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($isFlat)
                <!-- Flat & House Rental & Pricing Details -->
                <div id="sec-pricing" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-building text-indigo-600"></i> Flat &amp; House Rental Details
                        </h2>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-3 py-1 rounded-xl">
                            Full House Rental • Zero Brokerage
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div class="p-4 rounded-2xl border-2 border-indigo-500/40 bg-indigo-50/40 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-indigo-800 uppercase tracking-wider">Full Unit Monthly Rent</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">₹{{ $propRent }}<span class="text-xs font-normal text-gray-500">/mo</span></h3>
                                <p class="text-[11px] text-gray-600 mt-0.5">Complete private flat/house for family or working group</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-indigo-200 flex items-center justify-between">
                                <span class="text-[11px] font-bold text-indigo-700">Zero Commission</span>
                                <button type="button" onclick="openBookStayModal('Full Flat / House', {{ (int)str_replace(',', '', $propRent) }})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-xl transition tap-effect shadow-xs cursor-pointer">
                                    Book Flat
                                </button>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Security Deposit</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">₹{{ $propDeposit }}</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">Refundable deposit as per landlord agreement</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-[11px] text-gray-600">
                                <span>Notice Period:</span>
                                <span class="font-bold text-gray-900">{{ $propNoticePeriod }} Days</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/50 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Maintenance Charges</span>
                                <h3 class="text-lg sm:text-xl font-black text-gray-900 mt-1">{{ $propMaintenance > 0 ? '₹' . number_format($propMaintenance) . '/mo' : 'Zero Maintenance' }}</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">Society upkeep, lifts, water &amp; 24/7 security</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-[11px] text-gray-600">
                                <span>Occupancy:</span>
                                <span class="font-bold text-emerald-600 flex items-center gap-1"><i class="fas fa-check-circle text-[10px]"></i> Ready to Occupy</span>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($isPg && $roomConfigurations && $roomConfigurations->count() > 0)
                <!-- PG & Hostel Room Sharing & Pricing -->
                <div id="sec-pricing" class="bg-white rounded-3xl p-5 sm:p-7 border border-gray-100 shadow-sm scroll-mt-36 md:scroll-mt-40">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h2 class="text-base sm:text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-door-open text-brand"></i> Room Sharing &amp; Pricing
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-{{ min(3, max(1, $roomConfigurations->count())) }} gap-3.5">
                        @foreach($roomConfigurations as $rc)
                            @php
                                $isRoomBooked = ($rc->room_status === 'occupied' || $rc->room_status === 'booked' || $rc->room_status === 'sold_out' || (int)$rc->available_beds === 0);
                                $isPopular = !$isRoomBooked && (($rc->room_type_slug === 'double') || ($roomConfigurations->count() === 1));
                                $occLabel = $rc->max_occupancy == 1 ? 'Private Room' : ($rc->max_occupancy == 2 ? 'Shared Room' : ($rc->max_occupancy == 3 ? 'Triple Shared' : 'Group Sharing'));
                                $occTitle = $rc->max_occupancy == 1 ? 'Single Occupancy' : ($rc->max_occupancy == 2 ? 'Double Sharing' : ($rc->max_occupancy == 3 ? 'Triple Sharing' : ($rc->max_occupancy == 4 ? 'Four Sharing' : $rc->room_type_name)));
                                $occDesc = $rc->max_occupancy == 1 ? 'Attached washroom & personal space' : ($rc->max_occupancy . ' beds per room with separate wardrobes');
                            @endphp
                            <div class="p-4 rounded-2xl {{ $isRoomBooked ? 'border border-rose-200 bg-rose-50/20 relative' : ($isPopular ? 'border-2 border-brand bg-brand-light/30 relative shadow-xs' : 'border border-gray-200 bg-gray-50/50 hover:border-brand transition') }} flex flex-col justify-between">
                                @if($isRoomBooked)
                                    <span class="absolute -top-2.5 right-3 bg-rose-600 text-white text-[9px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider flex items-center gap-1 shadow-xs">
                                        <i class="fas fa-lock text-[8px]"></i> Full Booked
                                    </span>
                                @elseif($isPopular && $roomConfigurations->count() > 1)
                                    <span class="absolute -top-2.5 right-3 bg-brand text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase">Most Popular</span>
                                @endif
                                <div>
                                    <span class="text-xs font-bold {{ $isRoomBooked ? 'text-rose-700' : ($isPopular ? 'text-brand' : 'text-gray-500') }} uppercase tracking-wider">{{ $occLabel }}</span>
                                    <h3 class="text-sm font-black {{ $isRoomBooked ? 'text-gray-700' : 'text-gray-900' }} mt-1">{{ $occTitle }}</h3>
                                    <p class="text-[11px] text-gray-500 mt-0.5">{{ $occDesc }}</p>
                                </div>
                                <div class="mt-4 pt-3 {{ $isRoomBooked ? 'border-t border-rose-100' : ($isPopular ? 'border-t border-brand/20' : 'border-t border-gray-200') }} flex items-center justify-between">
                                    <div>
                                        <span class="text-base font-extrabold {{ $isRoomBooked ? 'text-gray-400 line-through' : ($isPopular ? 'text-brand-dark' : 'text-gray-900') }}">₹{{ number_format($rc->monthly_rent) }}</span>
                                        <span class="text-[11px] text-gray-500">/mo</span>
                                        @if($isRoomBooked)
                                            <span class="block text-[10px] font-bold text-rose-600">0 Beds Vacant</span>
                                        @endif
                                    </div>
                                    @if($isRoomBooked)
                                        <button type="button" disabled class="px-3 py-1.5 bg-gray-200 text-gray-400 text-[11px] font-bold rounded-xl cursor-not-allowed">
                                            Room Full
                                        </button>
                                    @else
                                        <button type="button" onclick="openBookStayModal('{{ addslashes($rc->room_type_name) }}', {{ $rc->monthly_rent }})" class="px-3 py-1.5 {{ $isPopular ? 'bg-brand hover:bg-brand-dark text-white' : 'bg-white hover:bg-brand hover:text-white text-brand border border-brand/30' }} text-[11px] font-bold rounded-xl transition tap-effect shadow-2xs cursor-pointer">
                                            Book Room
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

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
                            elseif (str_contains($slug, '2-wheeler') || str_contains($slug, 'bike') || str_contains($slug, 'motorcycle')) { $iconName = 'motorcycle'; $iconColor = 'text-indigo-600'; }
                            elseif (str_contains($slug, 'almirah') || str_contains($slug, 'wardrobe') || str_contains($slug, 'cupboard')) { $iconName = 'door-closed'; $iconColor = 'text-amber-600'; }
                            elseif (str_contains($slug, 'study') || str_contains($slug, 'desk') || str_contains($slug, 'table')) { $iconName = 'chair'; $iconColor = 'text-purple-600'; }
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

                    @if(isset($userReview) && $userReview)
                        <button type="button" onclick="openReviewModal()" id="reviewTriggerBtn" class="px-4 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-2xl text-xs font-bold transition tap-effect shadow-md shadow-brand/20 flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Your Review
                        </button>
                    @else
                        <button type="button" onclick="openReviewModal()" id="reviewTriggerBtn" class="px-4 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-2xl text-xs font-bold transition tap-effect shadow-md shadow-brand/20 flex items-center gap-2">
                            <i class="fas fa-pen"></i> Write a Review
                        </button>
                    @endif
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

                <!-- User's Existing Review Card (Pending or Approved) -->
                @if(isset($userReview) && $userReview)
                    <div class="mb-6 p-4 sm:p-5 rounded-2xl {{ $userReview->status === 'approved' ? 'bg-emerald-50/80 border-emerald-200 text-emerald-900' : 'bg-amber-50/80 border-amber-200 text-amber-900' }} border flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl {{ $userReview->status === 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center flex-shrink-0 text-sm font-bold shadow-xs">
                                <i class="fas {{ $userReview->status === 'approved' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            </div>
                            <div class="text-xs space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-bold {{ $userReview->status === 'approved' ? 'text-emerald-900' : 'text-amber-900' }}">
                                        {{ $userReview->status === 'approved' ? 'Your Published Review' : 'Your Review (Pending Moderation)' }}
                                    </p>
                                    <span class="{{ $userReview->status === 'approved' ? 'bg-emerald-200/80 text-emerald-900' : 'bg-amber-200/80 text-amber-900' }} font-bold px-2 py-0.5 rounded-md text-[10px] flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-500"></i> {{ $userReview->rating }} ★
                                    </span>
                                </div>
                                @if($userReview->title && $userReview->title !== 'Verified Resident Review')
                                    <p class="font-bold text-gray-900 text-xs">{{ $userReview->title }}</p>
                                @endif
                                <p class="{{ $userReview->status === 'approved' ? 'text-emerald-800' : 'text-amber-800' }}">"{{ $userReview->comment }}"</p>
                                <p class="text-[10px] {{ $userReview->status === 'approved' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $userReview->status === 'approved' ? 'Published live on ' . ($userReview->updated_at ? $userReview->updated_at->format('M d, Y') : 'Recently') : 'Your review will be visible publicly once approved by our moderation team.' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" onclick="openReviewModal()" class="self-start sm:self-auto px-3.5 py-2 {{ $userReview->status === 'approved' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-amber-600 hover:bg-amber-700' }} text-white text-xs font-bold rounded-xl transition tap-effect flex items-center gap-1.5 shadow-xs whitespace-nowrap">
                            <i class="fas fa-edit"></i> Edit Review
                        </button>
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
                            <span class="text-xs font-semibold text-gray-500 block">{{ $isSale ? 'Total Valuation' : 'Monthly Rent' }}</span>
                            <div class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">
                                {{ $isSale ? $salePriceDisplay : ('₹' . $propRent) }}<span class="text-xs font-normal text-gray-500">{{ $isSale ? '' : '/month' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-semibold text-gray-500 block">{{ $isSale ? 'Token Deposit' : 'Deposit' }}</span>
                            <span class="text-sm font-bold text-gray-800">{{ $isSale ? $propTokenBooking : ('₹' . $propDeposit) }}</span>
                        </div>
                    </div>

                    <!-- Details / Notice Badges -->
                    <div class="grid grid-cols-2 gap-2 mb-4 p-2.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs">
                        @if($isSale)
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase block leading-none mb-1">Rate</span>
                                <span class="font-bold text-gray-900 leading-tight flex items-center gap-1 text-xs">
                                    <i class="fas fa-calculator text-blue-500 text-[10px]"></i>
                                    {{ $propRatePerSqft }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 font-bold uppercase block leading-none mb-1">Possession</span>
                                <span class="font-bold text-gray-900 leading-tight flex items-center gap-1 text-xs">
                                    <i class="fas fa-key text-amber-500 text-[10px]"></i>
                                    {{ $propPossession }}
                                </span>
                            </div>
                        @else
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
                        @endif
                    </div>

                    <!-- Direct Book & Contact CTA -->
                    <div class="space-y-3">
                        @if(isset($hasActiveBooking) && $hasActiveBooking)
                            <div class="space-y-2.5">
                                <div class="p-3.5 bg-emerald-50 border border-emerald-200/80 rounded-2xl flex items-center gap-2.5 text-xs text-emerald-800">
                                    <i class="fas fa-check-circle text-emerald-600 text-sm flex-shrink-0"></i>
                                    <div>
                                        <span class="font-bold block">Already Booked (#{{ $existingBooking->booking_id }})</span>
                                        <span class="text-[10px] text-emerald-700">Status: {{ ucfirst($existingBooking->booking_status) }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('user.bookings') }}" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-sm shadow-md cursor-pointer">
                                    <i class="fas fa-calendar-check"></i> View My Reservation
                                </a>
                            </div>
                        @else
                            <button type="button" onclick="openBookStayModal('{{ $isSale ? 'Property Purchase / Site Visit' : ($isCommercial ? 'Full Commercial Space' : ($isFlat ? 'Full Flat / House' : 'Standard Stay')) }}', {{ (int)($isSale ? $propExpectedPrice : (int)str_replace(',', '', $propRent)) }})" class="w-full bg-gradient-to-r {{ $isSale ? 'from-amber-500 to-amber-600 shadow-amber-500/30' : ($isCommercial ? 'from-amber-600 to-amber-700 shadow-amber-500/30' : ($isFlat ? 'from-indigo-600 to-indigo-700 shadow-indigo-500/30' : 'from-brand to-brand-dark shadow-brand/30')) }} hover:shadow-lg text-white font-bold py-3.5 rounded-2xl transition tap-effect flex items-center justify-center gap-2 text-center text-sm shadow-md cursor-pointer">
                                <i class="fas {{ $isSale ? 'fa-building-circle-check' : ($isCommercial ? 'fa-store' : ($isFlat ? 'fa-building' : 'fa-calendar-check')) }}"></i> 
                                {{ $isSale ? 'Book Site Visit / Inquire' : ($isCommercial ? 'Book Commercial Space' : ($isFlat ? 'Book Flat / House Online' : 'Book Stay Online')) }}
                            </button>
                        @endif

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
        <div id="sec-more-listing" class="mt-12 sm:mt-16 pt-8 border-t border-gray-200 scroll-mt-36 md:scroll-mt-40 mb-20">
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
                                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 skeleton-shimmer">
                                        <img src="{{ $sim->display_image_url }}" alt="{{ $sim->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                                        <div class="absolute top-2 left-2 {{ $simTag['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-lg shadow-sm">
                                            <i class="fas fa-{{ $simTag['icon'] }}"></i> {{ $simTag['label'] }}
                                        </div>
                                        <div class="absolute bottom-2 left-2 bg-black/70 backdrop-blur text-white text-[10px] px-2 py-0.5 rounded-lg flex items-center gap-1 font-bold">
                                            <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $sim->rating ? number_format($sim->rating, 1) : '4.8' }}
                                        </div>
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
                            <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 skeleton-shimmer">
                                <img src="{{ $sim->display_image_url }}" alt="{{ $sim->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onload="this.parentElement.classList.remove('skeleton-shimmer')">
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
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Phone Number</label>
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

<!-- ================= WRITE / EDIT REVIEW MODAL (AUTH USERS) ================= -->
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
                    <h3 class="text-base sm:text-lg font-black text-gray-900">
                        {{ isset($userReview) && $userReview ? 'Edit Your Review' : 'Write a Review' }}
                    </h3>
                    <p class="text-[11px] text-gray-500">
                        {{ isset($userReview) && $userReview ? 'Update your review rating and feedback for this listing' : 'Only 1 review allowed per listing. Changes go live after admin approval.' }}
                    </p>
                </div>
            </div>
            <button onclick="closeReviewModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="reviewForm" onsubmit="submitReview(event)">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id ?? '' }}">
            <input type="hidden" name="rating" id="reviewRatingInput" value="{{ $userReview->rating ?? 5 }}">

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
                        <span id="ratingLabel" class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl ml-2">
                            {{ ($userReview->rating ?? 5) == 5 ? '5 - Excellent' : (($userReview->rating ?? 5) == 4 ? '4 - Very Good' : (($userReview->rating ?? 5) == 3 ? '3 - Good' : (($userReview->rating ?? 5) == 2 ? '2 - Fair' : '1 - Poor'))) }}
                        </span>
                    </div>
                </div>

                <!-- Review Title -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Review Title (Optional)</label>
                    <input type="text" name="title" id="reviewTitle" value="{{ $userReview && $userReview->title !== 'Verified Resident Review' ? $userReview->title : '' }}" placeholder="e.g. Great amenities and clean rooms!" oninput="validateBookingText(this)" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50">
                    <div id="reviewTitleError" class="text-[10px] text-rose-600 font-semibold mt-1 hidden"></div>
                </div>

                <!-- Review Comments -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Your Experience & Feedback <span class="text-rose-500">*</span></label>
                    <textarea name="comment" id="reviewComment" rows="4" required placeholder="Tell us about the food quality, WiFi speed, cleanliness, host behavior and room comfort..." oninput="validateBookingText(this)" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50">{{ $userReview->comment ?? '' }}</textarea>
                    <div id="reviewCommentError" class="text-[10px] text-rose-600 font-semibold mt-1 hidden"></div>
                </div>

                <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-2.5 text-[11px] text-blue-700">
                    <i class="fas fa-shield-alt text-blue-500 text-xs"></i>
                    <span>Only 1 verified review allowed per listing. You can update your review anytime.</span>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-xs transition">
                    Cancel
                </button>
                <button type="submit" id="reviewSubmitBtn" class="px-6 py-2.5 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-xs transition shadow-md shadow-brand/30 flex items-center gap-1.5">
                    <i class="fas {{ isset($userReview) && $userReview ? 'fa-save' : 'fa-paper-plane' }}"></i> 
                    {{ isset($userReview) && $userReview ? 'Update Review' : 'Submit Review' }}
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
            @if(isset($hasActiveBooking) && $hasActiveBooking)
                <a href="{{ route('user.bookings') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-2xl text-xs tap-effect shadow-md flex items-center gap-1.5 cursor-pointer">
                    <i class="fas fa-check-circle"></i> View Booking
                </a>
            @else
                <button type="button" onclick="openBookStayModal('{{ $isCommercial ? 'Full Commercial Space' : ($isFlat ? 'Full Flat / House' : 'Standard Stay') }}', {{ (int)str_replace(',', '', $propRent) }})" class="bg-gradient-to-r {{ $isCommercial ? 'from-amber-600 to-amber-700 shadow-amber-500/30' : ($isFlat ? 'from-indigo-600 to-indigo-700 shadow-indigo-500/30' : 'from-brand to-brand-dark shadow-brand/30') }} text-white font-bold py-2.5 px-5 rounded-2xl text-xs tap-effect shadow-md cursor-pointer">
                    {{ $isCommercial ? 'Book Space' : ($isFlat ? 'Book Flat' : 'Book Stay') }}
                </button>
            @endif
        </div>
    </div>
</div>

<!-- ================= BOOK STAY / SPACE MODAL (AUTH USERS) ================= -->
<div id="bookStayModal" class="fixed inset-0 z-[3000] hidden items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    <!-- Backdrop -->
    <div onclick="closeBookStayModal()" class="fixed inset-0 bg-slate-900/65 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Dialog (Wider max-w-3xl layout) -->
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-5 sm:p-7 md:p-8 z-10 border border-gray-100 animate-in fade-in zoom-in-95 duration-200 my-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl {{ $isCommercial ? 'bg-amber-50 text-amber-600' : ($isFlat ? 'bg-indigo-50 text-indigo-600' : 'bg-brand/10 text-brand') }} flex items-center justify-center text-lg font-bold flex-shrink-0">
                    <i class="fas {{ $isCommercial ? 'fa-store' : ($isFlat ? 'fa-building' : 'fa-calendar-check') }}"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-black text-gray-900 leading-tight">
                        {{ $isCommercial ? 'Book Commercial Space' : ($isFlat ? 'Book Flat / House Online' : 'Book Your Stay') }}
                    </h3>
                    <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                        <i class="fas fa-shield-check text-emerald-500"></i>
                        <span>Zero Advance Payment • Direct {{ $isCommercial ? 'Owner' : ($isFlat ? 'Landlord' : 'Host') }} Review &amp; Confirmation</span>
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeBookStayModal()" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition cursor-pointer" title="Close">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <form id="bookStayForm" onsubmit="submitBookingRequest(event)">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id ?? '' }}">
            <input type="hidden" name="base_rent" id="bookingBaseRentInput" value="{{ $property->monthly_rent ?? 0 }}">
            <input type="hidden" name="room_type_name" id="bookingRoomTypeInput" value="{{ $isCommercial ? 'Full Commercial Space' : ($isFlat ? 'Full Flat / House' : 'Standard Stay') }}">
            <input type="hidden" name="tenant_email" value="{{ auth()->user()?->email ?? '' }}">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 sm:gap-6">
                <!-- Left Column: Booking Parameters & Tenant Info (7 cols) -->
                <div class="md:col-span-7 space-y-4 text-xs">
                    <!-- Room / Unit Selection -->
                    @if($isCommercial)
                        <div>
                            <label class="block font-bold text-gray-700 mb-1.5">Selected Commercial Unit</label>
                            <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 text-xs block">Full Commercial Space</span>
                                        <span class="text-[10px] text-amber-800">Prime Business Unit • Zero Commission</span>
                                    </div>
                                </div>
                                <span class="font-black text-amber-900 text-xs">₹{{ $propRent }}/mo</span>
                            </div>
                        </div>
                    @elseif($isFlat)
                        <div>
                            <label class="block font-bold text-gray-700 mb-1.5">Selected Flat / House Unit</label>
                            <div class="p-3 bg-indigo-50/70 border border-indigo-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 text-xs block">Full Private Flat / House</span>
                                        <span class="text-[10px] text-indigo-800">Independent Living • Zero Brokerage</span>
                                    </div>
                                </div>
                                <span class="font-black text-indigo-900 text-xs">₹{{ $propRent }}/mo</span>
                            </div>
                        </div>
                    @elseif($isPg && $roomConfigurations && $roomConfigurations->count() > 0)
                        <div>
                            <label class="block font-bold text-gray-700 mb-1.5">Select Sharing / Room Type <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-{{ min(3, $roomConfigurations->count()) }} gap-2" id="bookingRoomTypeContainer">
                                @php $firstAvailableChecked = false; @endphp
                                @foreach($roomConfigurations as $idx => $rc)
                                    @php
                                        $isRoomBooked = ($rc->room_status === 'occupied' || $rc->room_status === 'booked' || $rc->room_status === 'sold_out' || (int)$rc->available_beds === 0);
                                        $shouldCheck = !$isRoomBooked && !$firstAvailableChecked;
                                        if ($shouldCheck) { $firstAvailableChecked = true; }
                                    @endphp
                                    @if($isRoomBooked)
                                        <div class="border-2 border-dashed border-gray-200 bg-gray-100/70 rounded-xl p-2.5 opacity-60 cursor-not-allowed flex flex-col justify-between select-none">
                                            <div class="font-bold text-gray-500 text-xs truncate flex items-center justify-between">
                                                <span>{{ $rc->room_type_name }}</span>
                                                <span class="text-[8px] bg-rose-100 text-rose-700 font-extrabold px-1 py-0.5 rounded uppercase">Full</span>
                                            </div>
                                            <div class="text-gray-400 font-bold text-xs mt-1">Booked</div>
                                        </div>
                                    @else
                                        <label class="room-opt-card border-2 {{ $shouldCheck ? 'border-brand bg-brand-light/30' : 'border-gray-200 bg-white' }} rounded-xl p-2.5 cursor-pointer flex flex-col justify-between transition hover:border-brand">
                                            <input type="radio" name="room_type_select" value="{{ $rc->room_type_name }}" data-rent="{{ $rc->monthly_rent }}" {{ $shouldCheck ? 'checked' : '' }} onchange="updateBookingRoom(this)" class="sr-only">
                                            <div class="font-bold text-gray-900 text-xs truncate">{{ $rc->room_type_name }}</div>
                                            <div class="text-brand font-black text-xs mt-1">₹{{ number_format($rc->monthly_rent) }}<span class="text-[9px] text-gray-400 font-normal">/mo</span></div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Check-in Date & Duration -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Check-in Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="check_in_date" id="bookingCheckInDate" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-semibold focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Stay Duration <span class="text-rose-500">*</span></label>
                            <select name="duration_months" id="bookingDurationMonths" required onchange="calculateBookingEstimate()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-semibold focus:ring-2 focus:ring-brand/50 cursor-pointer">
                                <option value="1">1 Month</option>
                                <option value="3">3 Months</option>
                                <option value="6">6 Months</option>
                                <option value="11" selected>11 Months (Standard Agreement)</option>
                                <option value="12">12 Months</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tenant Details (Auto filled) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Your Full Name </label>
                            <input type="text" name="tenant_name" id="bookingTenantName" readonly value="{{ auth()->user()?->name ?? '' }}" placeholder="Your Name" oninput="validateBookingText(this)" class="w-full bg-gray-100/90 text-gray-500 border border-gray-200 rounded-xl py-2.5 px-3 text-xs font-medium cursor-not-allowed select-none">
                            <div id="bookingTenantNameError" class="text-[10px] text-rose-600 font-semibold mt-1 hidden"></div>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Phone Number (Registered)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-xs font-bold text-gray-400">+91</span>
                                <input type="tel" name="tenant_phone" id="bookingTenantPhone" readonly value="{{ auth()->user()?->phone ? substr(preg_replace('/[^0-9]/', '', auth()->user()->phone), -10) : '' }}" placeholder="mobile number" class="w-full bg-gray-100/90 text-gray-500 border border-gray-200 rounded-xl py-2.5 pl-10 pr-3 text-xs font-medium cursor-not-allowed select-none">
                            </div>
                        </div>
                    </div>

                    <!-- Special Requests / Notes -->
                    <div>
                        <label class="block font-semibold text-gray-600 mb-1">Special Requests / Move-in Notes (Optional)</label>
                        <textarea name="special_requests" id="bookingSpecialRequests" rows="2" placeholder="e.g. Preferred floor, early move-in time, 2-wheeler parking need..." oninput="validateBookingText(this)" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs font-medium focus:ring-2 focus:ring-brand/50"></textarea>
                        <div id="bookingSpecialRequestsError" class="text-[10px] text-rose-600 font-semibold mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Right Column: Summary Card & Guarantee Box (5 cols) -->
                <div class="md:col-span-5 space-y-3.5 flex flex-col justify-between">
                    <!-- Property Summary Box -->
                    <div class="p-3.5 bg-gray-50/90 rounded-2xl border border-gray-100 flex items-center gap-3">
                        <img src="{{ $propImages->first()->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=120&q=80' }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0 shadow-xs">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm text-gray-900 truncate">{{ $propName }}</h4>
                            <p class="text-[11px] text-gray-500 truncate flex items-center gap-1 mt-0.5">
                                <i class="fas fa-map-marker-alt text-brand text-[10px]"></i> {{ $propLocation }}
                            </p>
                            <span class="text-[11px] font-bold text-brand mt-1 inline-block">Starting from ₹{{ $propRent }}/mo</span>
                        </div>
                    </div>

                    <!-- Financial Summary Box -->
                    <div class="p-4 bg-emerald-50/70 rounded-2xl border border-emerald-100 space-y-2 text-xs">
                        <h5 class="font-bold text-emerald-950 text-xs uppercase tracking-wider mb-1">Pricing Breakdown</h5>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Monthly Rent:</span>
                            <span class="font-bold text-gray-900" id="summaryRentText">₹{{ number_format($property->monthly_rent ?: 5000) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Security Deposit:</span>
                            <span class="font-bold text-gray-900" id="summaryDepositText">₹{{ number_format($property->security_deposit ?: 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600">
                            <span>StayNest Service Fee:</span>
                            <span class="font-bold text-emerald-700">₹0 (FREE)</span>
                        </div>
                        <div class="border-t border-emerald-200/80 pt-2 flex justify-between items-center font-black text-sm text-brand-dark">
                            <span>Payable at Move-in:</span>
                            <span id="summaryTotalText">₹{{ number_format(($property->monthly_rent ?: 5000) + ($property->security_deposit ?: 0)) }}</span>
                        </div>
                    </div>

                    <!-- Zero Advance Payment Notice -->
                    <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-2.5 text-[11px] text-amber-900 font-medium">
                        <i class="fas fa-hand-holding-dollar text-amber-600 text-base mt-0.5 flex-shrink-0"></i>
                        <span><strong>No payment required now!</strong> Your booking request is placed directly with the property owner. You pay only after approval and room check.</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeBookStayModal()" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-xs transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="bookingSubmitBtn" class="px-7 py-2.5 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-xs transition shadow-md shadow-brand/30 flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-paper-plane"></i> Submit Booking Request
                </button>
            </div>
        </form>
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
                if (typeof btn.scrollIntoView === 'function') {
                    btn.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
                }
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

        /* ================= DETAIL TABS SCROLLSPY & CLICK ACTIVE STATE ================= */
        const navTabs = document.querySelectorAll('.detail-nav-tab');
        const stickyNavContainer = document.getElementById('detailStickyNav');

        function getSectionsInOrder() {
            return Array.from(document.querySelectorAll('.detail-nav-tab')).map(tab => {
                const targetId = tab.getAttribute('data-target') || (tab.getAttribute('href') ? tab.getAttribute('href').replace('#', '') : null);
                return targetId ? document.getElementById(targetId) : null;
            }).filter(Boolean);
        }

        let isManualScrolling = false;
        let manualScrollTimer = null;

        function setActiveNavTab(targetId, shouldScrollNav = true) {
            navTabs.forEach(t => {
                const tTarget = t.getAttribute('data-target') || (t.getAttribute('href') ? t.getAttribute('href').replace('#', '') : null);
                if (tTarget === targetId) {
                    t.classList.add('text-brand', 'font-bold', 'border-brand');
                    t.classList.remove('text-gray-500', 'border-transparent', 'font-semibold');
                    
                    if (shouldScrollNav && stickyNavContainer) {
                        t.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                } else {
                    t.classList.remove('text-brand', 'font-bold', 'border-brand');
                    t.classList.add('text-gray-500', 'border-transparent', 'font-semibold');
                }
            });
        }

        navTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target') || (this.getAttribute('href') ? this.getAttribute('href').replace('#', '') : null);
                const targetEl = targetId ? document.getElementById(targetId) : null;
                if (!targetEl) return;

                isManualScrolling = true;
                if (manualScrollTimer) clearTimeout(manualScrollTimer);

                setActiveNavTab(targetId, true);

                const navHeight = stickyNavContainer ? stickyNavContainer.offsetHeight : 50;
                const topBarHeight = window.innerWidth < 768 ? 58 : 80;
                const totalOffset = topBarHeight + navHeight + 16;
                const elementPosition = targetEl.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - totalOffset;

                window.scrollTo({
                    top: Math.max(0, offsetPosition),
                    behavior: 'smooth'
                });

                manualScrollTimer = setTimeout(() => {
                    isManualScrolling = false;
                }, 800);
            });
        });

        let isScrollTicking = false;
        window.addEventListener('scroll', function() {
            if (isManualScrolling) return;

            if (!isScrollTicking) {
                window.requestAnimationFrame(function() {
                    const sections = getSectionsInOrder();
                    if (!sections.length) {
                        isScrollTicking = false;
                        return;
                    }

                    const scrollPos = window.pageYOffset || document.documentElement.scrollTop;
                    const navHeight = stickyNavContainer ? stickyNavContainer.offsetHeight : 50;
                    const topBarHeight = window.innerWidth < 768 ? 58 : 80;
                    const headerOffset = topBarHeight + navHeight + 35;

                    let activeId = sections[0].id;

                    for (let i = 0; i < sections.length; i++) {
                        const sec = sections[i];
                        const top = sec.offsetTop - headerOffset;
                        if (scrollPos >= top) {
                            activeId = sec.id;
                        }
                    }

                    if ((window.innerHeight + window.pageYOffset) >= (document.documentElement.scrollHeight - 60)) {
                        activeId = sections[sections.length - 1].id;
                    }

                    if (activeId) {
                        setActiveNavTab(activeId, false);
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

    function slidePrev() {
        if (detailSwiperInstance) {
            detailSwiperInstance.slidePrev();
        }
    }

    function slideNext() {
        if (detailSwiperInstance) {
            detailSwiperInstance.slideNext();
        }
    }

    function scrollThumbs(direction) {
        const container = document.getElementById('thumbScrollContainer');
        if (container) {
            const scrollAmount = 240;
            container.scrollBy({
                left: direction === 'left' ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
        }
        if (direction === 'left') {
            slidePrev();
        } else {
            slideNext();
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
        @if(!Auth::check())
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please log in to submit or edit your review for this listing.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#4bb59d',
                    confirmButtonText: 'Log In Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('user.login') }}?redirect=" + encodeURIComponent(window.location.href);
                    }
                });
            } else {
                if (confirm('Please log in to submit or edit your review. Go to login page?')) {
                    window.location.href = "{{ route('user.login') }}?redirect=" + encodeURIComponent(window.location.href);
                }
            }
            return;
        @endif

        const modal = document.getElementById('writeReviewModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Set initial rating based on existing review or default 5
            const currentRating = parseInt("{{ $userReview->rating ?? 5 }}") || 5;
            setRating(currentRating);
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
        const titleInput = document.getElementById('reviewTitle');
        const commentInput = document.getElementById('reviewComment');
        const comment = commentInput?.value.trim();

        if (!comment) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Feedback Required',
                    text: 'Please enter your review feedback.',
                    icon: 'warning',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('Please enter your review feedback.');
            }
            return;
        }

        // Run Real-Time Content Moderation for Gali, Profanity, Abuse, Sex, Spam
        const isTitleValid = checkBookingTextModeration(titleInput);
        const isCommentValid = checkBookingTextModeration(commentInput);

        if (!isTitleValid || !isCommentValid) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Restricted Content Detected ⚠️',
                    text: 'Your review title or feedback contains inappropriate/abusive words. Please remove restricted terms to proceed.',
                    icon: 'warning',
                    confirmButtonColor: '#ef4444'
                });
            } else {
                alert('Your review title or feedback contains inappropriate/abusive words. Please remove restricted terms to proceed.');
            }
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
                form.reset();
                closeReviewModal();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Review Submitted! ⭐',
                        text: data.message || 'Thank you! Your review has been submitted for moderation.',
                        icon: 'success',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert('✅ ' + data.message);
                    window.location.reload();
                }
            } else {
                if (res.status === 401) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Sign In Required',
                            text: 'Please sign in to submit your review.',
                            icon: 'info',
                            confirmButtonColor: '#4bb59d'
                        }).then(() => {
                            window.location.href = "{{ route('user.login') }}";
                        });
                    } else {
                        alert('⚠️ Please log in to submit your review.');
                        window.location.href = "{{ route('user.login') }}";
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Could Not Submit Review',
                            text: data.message || 'Please check your input and try again.',
                            icon: 'error',
                            confirmButtonColor: '#4bb59d'
                        });
                    } else {
                        alert('⚠️ ' + (data.message || 'Could not submit review. Please try again.'));
                    }
                }
            }
        } catch (err) {
            closeReviewModal();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Review Submitted! ⭐',
                    text: 'Thank you! Your review has been submitted and will appear publicly once approved.',
                    icon: 'success',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('✅ Thank you! Your review has been submitted for moderation and will appear publicly once approved.');
            }
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
            closeBookStayModal();
            closeShareModal();
            closeReportModal();
            closeReviewModal();
        }
    });

    // ================= BOOKING MODAL LOGIC =================
    function openBookStayModal(roomName = null, roomRent = null) {
        const isAuth = {{ Auth::check() ? 'true' : 'false' }};
        if (!isAuth) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Sign In Required',
                    text: 'Please sign in or create an account on StayNest to book this stay.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#4bb59d',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sign In Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('user.login') }}";
                    }
                });
            } else {
                if (confirm('Please sign in or create an account on StayNest to book this stay.')) {
                    window.location.href = "{{ route('user.login') }}";
                }
            }
            return;
        }

        const isAlreadyBooked = {{ (isset($hasActiveBooking) && $hasActiveBooking) ? 'true' : 'false' }};
        if (isAlreadyBooked) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Already Reserved! 🏨',
                    text: 'You already have an active or pending booking for this property (#{{ $existingBooking?->booking_id }}). You cannot book the same listing more than once.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#4bb59d',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'View My Bookings',
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('user.bookings') }}";
                    }
                });
            } else {
                alert('You already have an active or pending booking for this property (#{{ $existingBooking?->booking_id }}).');
                window.location.href = "{{ route('user.bookings') }}";
            }
            return;
        }

        if (roomName && roomRent) {
            const rentInput = document.getElementById('bookingBaseRentInput');
            const roomInput = document.getElementById('bookingRoomTypeInput');
            if (rentInput) rentInput.value = roomRent;
            if (roomInput) roomInput.value = roomName;
            
            const radios = document.querySelectorAll('input[name="room_type_select"]');
            radios.forEach(r => {
                if (r.value === roomName) {
                    r.checked = true;
                    updateBookingRoom(r);
                }
            });
        }

        calculateBookingEstimate();
        const modal = document.getElementById('bookStayModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeBookStayModal() {
        const modal = document.getElementById('bookStayModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function updateBookingRoom(radio) {
        const rent = parseFloat(radio.getAttribute('data-rent') || '{{ $property->monthly_rent ?? 0 }}');
        const roomName = radio.value;
        const rentInput = document.getElementById('bookingBaseRentInput');
        const roomInput = document.getElementById('bookingRoomTypeInput');
        if (rentInput) rentInput.value = rent;
        if (roomInput) roomInput.value = roomName;

        document.querySelectorAll('.room-opt-card').forEach(card => {
            card.classList.remove('border-brand', 'bg-brand-light/30');
            card.classList.add('border-gray-200', 'bg-white');
        });
        const parentCard = radio.closest('.room-opt-card');
        if (parentCard) {
            parentCard.classList.add('border-brand', 'bg-brand-light/30');
            parentCard.classList.remove('border-gray-200', 'bg-white');
        }

        calculateBookingEstimate();
    }

    function calculateBookingEstimate() {
        const rentInput = document.getElementById('bookingBaseRentInput');
        const rent = parseFloat((rentInput && rentInput.value) ? rentInput.value : '{{ $property->monthly_rent ?? 0 }}');
        const deposit = parseFloat('{{ $property->security_deposit ?? 0 }}');
        const total = rent + deposit;

        const rentEl = document.getElementById('summaryRentText');
        const depEl = document.getElementById('summaryDepositText');
        const totEl = document.getElementById('summaryTotalText');

        if (rentEl) rentEl.textContent = '₹' + rent.toLocaleString('en-IN');
        if (depEl) depEl.textContent = '₹' + deposit.toLocaleString('en-IN');
        if (totEl) totEl.textContent = '₹' + total.toLocaleString('en-IN');
    }

    // Client-side Content Moderation Filter (Gali / Profanity / Prohibited words)
    const prohibitedBookingTermsRegex = [
        { category: 'Vulgarity / Profanity', regex: /\b(fuck|fucking|fucked|fucker|fck|bitch|bitches|bastard|bastards|asshole|assholes|chutiya|chootiya|chutya|choot|bhenchod|behenchod|bc|madarchod|mc|gaand|gandu|harami|harampaye|lavde|lauda|loda|lodu|bhosadi|bhosdike|randi|kutiya|kamina|kameena|shit|cunt|cunts|pussy|dick)\b/i },
        { category: 'Sexual Content & Escorts', regex: /\b(sex|sexy|sexual|intercourse|call\s*girl|call\s*girls|call\s*boy|call\s*boys|escort|escorts|russian\s*girl|paid\s*sex|adult\s*service|adult|adults|sex\s*service|nude|nudes|nudity|naked|porn|xxx|xx|erotic|sensual\s*massage|happy\s*ending|sax\s*sux|sax|sux|onlyfans|night\s*service|sugar\s*daddy|hookup)\b/i },
        { category: 'Substances & Illicit Drugs', regex: /\b(cocaine|heroin|charas|ganja|weed|buy\s*weed|cannabis|meth|crystal\s*meth|mdma|ecstasy|lsd|smack|brown\s*sugar|narcotics|drug\s*party|afim|afeem)\b/i },
        { category: 'Abuse & Harassment', regex: /\b(kill\s*you|murder|rape|threat|assault|beat\s*up|violence|terrorist|jihad|hate\s*all)\b/i },
        { category: 'Scams & Phishing', regex: /\b(send\s*otp|share\s*otp|lottery\s*winner|crypto\s*investment|double\s*money|instant\s*loan\s*scam|free\s*recharge)\b/i }
    ];

    function checkBookingTextModeration(inputElement) {
        if (!inputElement) return true;
        const text = inputElement.value || '';
        const errDivId = inputElement.id + 'Error';
        const errDiv = document.getElementById(errDivId);

        if (!text.trim()) {
            if (errDiv) {
                errDiv.textContent = '';
                errDiv.classList.add('hidden');
            }
            inputElement.classList.remove('border-rose-500', 'bg-rose-50/40');
            return true;
        }

        for (const rule of prohibitedBookingTermsRegex) {
            if (rule.regex.test(text)) {
                const matched = text.match(rule.regex);
                const matchedWord = matched ? matched[0] : 'inappropriate term';
                const msg = `⚠️ Contains restricted content [${rule.category}]: "${matchedWord}". Please remove it.`;
                if (errDiv) {
                    errDiv.textContent = msg;
                    errDiv.classList.remove('hidden');
                }
                inputElement.classList.add('border-rose-500', 'bg-rose-50/40');
                return false;
            }
        }

        if (errDiv) {
            errDiv.textContent = '';
            errDiv.classList.add('hidden');
        }
        inputElement.classList.remove('border-rose-500', 'bg-rose-50/40');
        return true;
    }

    function validateBookingText(inputElement) {
        checkBookingTextModeration(inputElement);
    }

    async function submitBookingRequest(event) {
        event.preventDefault();

        // Validate text for prohibited / abusive words (Gali, xx, etc.)
        const nameInput = document.getElementById('bookingTenantName');
        const reqInput = document.getElementById('bookingSpecialRequests');
        const isNameValid = checkBookingTextModeration(nameInput);
        const isReqValid = checkBookingTextModeration(reqInput);

        if (!isNameValid || !isReqValid) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Restricted Content Detected ⚠️',
                    text: 'Your Name or Move-in Notes contain inappropriate/abusive words. Please remove restricted terms to proceed.',
                    icon: 'warning',
                    confirmButtonColor: '#ef4444'
                });
            } else {
                alert('Your Name or Move-in Notes contain inappropriate/abusive words. Please remove restricted terms to proceed.');
            }
            return;
        }

        const form = document.getElementById('bookStayForm');
        const btn = document.getElementById('bookingSubmitBtn');
        const origBtnHtml = btn ? btn.innerHTML : 'Submit Booking Request';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        }

        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('user.bookings.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeBookStayModal();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Booking Request Sent! 🎉',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4bb59d',
                        confirmButtonText: 'View My Bookings'
                    }).then(() => {
                        window.location.href = data.redirect_url || "{{ route('user.bookings') }}";
                    });
                } else {
                    alert(data.message || 'Booking Request Sent Successfully!');
                    window.location.href = data.redirect_url || "{{ route('user.bookings') }}";
                }
            } else {
                if (data.require_login) {
                    window.location.href = "{{ route('user.login') }}";
                } else if (data.already_booked) {
                    closeBookStayModal();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Already Reserved! 🏨',
                            text: data.message || 'You already have an active reservation for this property.',
                            icon: 'info',
                            confirmButtonColor: '#4bb59d',
                            confirmButtonText: 'View My Bookings'
                        }).then(() => {
                            window.location.href = data.redirect_url || "{{ route('user.bookings') }}";
                        });
                    } else {
                        alert(data.message);
                        window.location.href = data.redirect_url || "{{ route('user.bookings') }}";
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Booking Error',
                            text: data.message || 'Could not complete booking request. Please check your inputs.',
                            icon: 'error',
                            confirmButtonColor: '#4bb59d'
                        });
                    } else {
                        alert(data.message || 'Could not complete booking request.');
                    }
                }
            }
        } catch (err) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Network Error',
                    text: 'An unexpected error occurred while booking. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('An unexpected error occurred while booking. Please try again.');
            }
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origBtnHtml;
            }
        }
    }

    function openShareModal() {
        if (typeof window.nativeShare === 'function') {
            window.nativeShare(
                '{{ addslashes($propName) }} - StayNest',
                'Check out {{ addslashes($propName) }} in {{ addslashes($propLocation) }} starting at ₹{{ $propRent }}/mo on StayNest with zero brokerage!',
                '{{ url()->current() }}'
            );
        }
    }
</script>
@endpush
