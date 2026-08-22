@extends('user.layouts.app')

@section('title', 'StayNest - Find 100% Verified PGs & Co-Living Spaces in India | Zero Brokerage')
@section('meta_description', 'Search & book 1,200+ verified PGs, luxury hostels, and co-living rooms in Bangalore, Noida, Delhi, Mumbai, Pune, and Gurgaon. Zero brokerage, free WiFi, meals & biometric security.')
@section('meta_keywords', 'PG near me, Paying Guest Bangalore, Paying Guest Noida, Boys PG, Girls PG, Co-living Hostels, Zero Brokerage PG, StayNest India')
@section('canonical', route('user.home'))

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "{{ route('user.home') }}/#organization",
      "name": "StayNest",
      "url": "{{ route('user.home') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('images/favicon.png') }}"
      },
      "sameAs": [
        "https://www.facebook.com/staynest",
        "https://twitter.com/staynest",
        "https://www.instagram.com/staynest"
      ],
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+91-98765-43210",
        "contactType": "customer service",
        "areaServed": "IN",
        "availableLanguage": ["English", "Hindi"]
      }
    },
    {
      "@type": "WebSite",
      "@id": "{{ route('user.home') }}/#website",
      "url": "{{ route('user.home') }}",
      "name": "StayNest",
      "description": "Find verified PGs and co-living spaces with zero brokerage across India.",
      "publisher": {
        "@id": "{{ route('user.home') }}/#organization"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ route('user.search') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
  ]
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    /* ===================== ANDROID APP DESIGN SYSTEM ===================== */
    :root {
        --brand: #4bb59d;
        --brand-dark: #3a9a85;
        --brand-light: #e6f7f3;
    }

    /* Ripple tap effect */
    .tap-ripple {
        position: relative;
        overflow: hidden;
        -webkit-tap-highlight-color: transparent;
    }
    .tap-ripple::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0);
        transition: background 0.15s;
    }
    .tap-ripple:active::after {
        background: rgba(0,0,0,0.06);
    }

    /* Category pill scroll */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Category pills */
    .cat-chip {
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }
    .cat-chip.active {
        background-color: var(--brand) !important;
        color: white !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(75,181,157,0.35);
    }
    .cat-chip.active i, .cat-chip.active span.p-badge {
        color: white !important;
    }

    /* Uniform Property Card */
    .pg-card {
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .pg-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px -4px rgba(0,0,0,0.1);
        border-color: #e2e8f0;
    }
    .pg-card:active {
        transform: scale(0.98);
    }

    /* Swiper overrides */
    .swiper-pagination-bullet-active {
        background-color: var(--brand) !important;
        width: 22px !important;
        border-radius: 6px !important;
    }

    /* Section heading typography */
    .section-title {
        font-size: 1.125rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }
    @media (min-width: 640px) {
        .section-title { font-size: 1.375rem; }
    }

    /* Budget card */
    .budget-card {
        border-radius: 20px;
        background: #fff;
        border: 1.5px solid #f1f5f9;
        transition: all 0.2s;
    }
    .budget-card:hover, .budget-card:active {
        border-color: var(--brand);
        background: var(--brand-light);
        transform: translateY(-2px);
    }

    /* Promo Swiper Navigation Arrows */
    .promoSwiper .swiper-button-prev,
    .promoSwiper .swiper-button-next {
        display: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.18);
        color: #1f2937;
        top: 50%;
        margin-top: -20px;
    }
    @media (min-width: 640px) {
        .promoSwiper .swiper-button-prev,
        .promoSwiper .swiper-button-next {
            display: flex;
        }
    }
    .promoSwiper .swiper-button-prev { left: 16px; }
    .promoSwiper .swiper-button-next { right: 16px; }
    .promoSwiper .swiper-button-prev::after,
    .promoSwiper .swiper-button-next::after {
        font-size: 14px;
        font-weight: 900;
        color: #1f2937;
    }
    .promoSwiper .swiper-button-prev:hover,
    .promoSwiper .swiper-button-next:hover {
        background: rgba(255,255,255,1);
        box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    }
</style>
@endpush

@section('content')
<div class="w-full bg-gray-50/50 min-h-screen pb-24 md:pb-0">

    {{-- ============================= 1. PROMO BANNER SWIPER ============================= --}}
    <section class="pt-4 sm:pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative">
                <div class="swiper promoSwiper rounded-3xl overflow-hidden shadow-xl">
                    <div class="swiper-wrapper">

                        {{-- Slide 1: Zero Brokerage --}}
                        <div class="swiper-slide !h-auto">
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:p-8 md:p-10" style="background:#0a2e24">
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                     alt="Zero Brokerage" class="absolute inset-0 w-full h-full object-cover opacity-40">
                                <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,46,36,0.97) 0%,rgba(26,107,85,0.75) 50%,rgba(0,0,0,0.35) 100%)"></div>
                                <div class="absolute top-0 right-0 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(75,181,157,0.25) 0%,transparent 70%);transform:translate(30%,-30%)"></div>
                                
                                <div class="relative z-10 w-full">
                                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                        <div class="max-w-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center gap-1.5 bg-yellow-400/20 border border-yellow-400/40 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold text-yellow-300">
                                                    <i class="fas fa-bolt text-yellow-400"></i> LIMITED OFFER
                                                </span>
                                                <span class="inline-flex items-center gap-1 bg-white/10 border border-white/20 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold text-white/80">
                                                    <i class="fas fa-shield-halved text-brand text-[10px]"></i> 100% Verified
                                                </span>
                                            </div>
                                            <h3 class="text-xl sm:text-3xl md:text-4xl font-black leading-tight mb-1.5 text-white">
                                                Save up to <span style="color:#7eebd4">₹15,000</span> on Zero Brokerage
                                            </h3>
                                            <p class="text-xs sm:text-sm text-white/70 mb-3 max-w-md line-clamp-2">Book directly with verified owners — WiFi, 3 Meals & 24/7 Security included.</p>
                                            <div class="flex flex-wrap gap-2.5">
                                                <a href="{{ route('user.search') }}" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white font-bold px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition shadow-lg shadow-brand/30">
                                                    <i class="fas fa-search"></i> Explore Stays
                                                </a>
                                                <a href="{{ route('user.list-property') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition">
                                                    List PG Free
                                                </a>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0">
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center min-w-[120px]">
                                                <p class="text-xl sm:text-2xl font-black text-white">50K+</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Happy Students</p>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center">
                                                <p class="text-xl sm:text-2xl font-black text-white">₹0</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Brokerage</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Slide 2: Co-Living --}}
                        <div class="swiper-slide !h-auto">
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:p-8 md:p-10" style="background:#0f0a2a">
                                <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                     alt="Co-Living" class="absolute inset-0 w-full h-full object-cover opacity-35">
                                <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(15,10,42,0.97) 0%,rgba(49,46,129,0.75) 50%,rgba(0,0,0,0.3) 100%)"></div>
                                <div class="absolute top-0 right-0 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(99,102,241,0.3) 0%,transparent 70%);transform:translate(30%,-30%)"></div>
                                
                                <div class="relative z-10 w-full">
                                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                        <div class="max-w-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center gap-1.5 bg-purple-400/20 border border-purple-400/40 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold text-purple-300">
                                                    <i class="fas fa-star text-purple-300"></i> PREMIUM CO-LIVING
                                                </span>
                                                <span class="inline-flex items-center gap-1 bg-white/10 border border-white/20 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold text-white/80">
                                                    <i class="fas fa-users text-cyan-400 text-[10px]"></i> Community
                                                </span>
                                            </div>
                                            <h2 class="text-xl sm:text-3xl md:text-4xl font-black leading-tight mb-1.5 text-white">
                                                Co-Living Spaces <span style="color:#67e8f9">from ₹4,999/mo</span>
                                            </h2>
                                            <p class="text-xs sm:text-sm text-white/70 mb-3 max-w-md line-clamp-2">Gym, gaming lounge, high-speed WiFi & a vibrant student community.</p>
                                            <div class="flex flex-wrap gap-2.5">
                                                <a href="{{ route('user.search') }}?type=coliving" class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-600 text-white font-bold px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition shadow-lg shadow-indigo-500/30">
                                                    <i class="fas fa-users"></i> Browse Co-Living
                                                </a>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0">
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center min-w-[120px]">
                                                <p class="text-xl sm:text-2xl font-black text-white">200+</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Co-Living Spaces</p>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center">
                                                <p class="text-xl sm:text-2xl font-black text-cyan-300">4.8★</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Avg Rating</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Slide 3: Girls Special --}}
                        <div class="swiper-slide !h-auto">
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:p-8 md:p-10" style="background:#1a0a00">
                                <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                     alt="Girls PG" class="absolute inset-0 w-full h-full object-cover opacity-40">
                                <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(26,10,0,0.97) 0%,rgba(154,58,0,0.65) 50%,rgba(0,0,0,0.3) 100%)"></div>
                                <div class="absolute top-0 right-0 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(251,146,60,0.25) 0%,transparent 70%);transform:translate(30%,-30%)"></div>
                                
                                <div class="relative z-10 w-full">
                                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                        <div class="max-w-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center gap-1.5 bg-orange-400/20 border border-orange-400/40 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold text-orange-300">
                                                    <i class="fas fa-home text-orange-300"></i> GIRLS SPECIAL
                                                </span>
                                                <span class="inline-flex items-center gap-1 bg-white/10 border border-white/20 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold text-white/80">
                                                    <i class="fas fa-lock text-orange-400 text-[10px]"></i> Safe & Secure
                                                </span>
                                            </div>
                                            <h2 class="text-xl sm:text-3xl md:text-4xl font-black leading-tight mb-1.5 text-white">
                                                Safe Girls PGs <span style="color:#fb923c">CCTV + 24/7 Guard</span>
                                            </h2>
                                            <p class="text-xs sm:text-sm text-white/70 mb-3 max-w-md line-clamp-2">100% Verified, female-friendly PGs with biometric entry & meals included.</p>
                                            <div class="flex flex-wrap gap-2.5">
                                                <a href="{{ route('user.search') }}?gender=GIRLS" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition shadow-lg shadow-orange-500/30">
                                                    <i class="fas fa-female"></i> Find Girls PG
                                                </a>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0">
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center min-w-[120px]">
                                                <p class="text-xl sm:text-2xl font-black text-white">500+</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Girls PGs Listed</p>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center">
                                                <p class="text-xl sm:text-2xl font-black text-orange-300">24/7</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Security Guard</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Slide 4: Boys PG Special --}}
                        <div class="swiper-slide !h-auto">
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:p-8 md:p-10" style="background:#071b38">
                                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                     alt="Boys PG" class="absolute inset-0 w-full h-full object-cover opacity-35">
                                <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(7,27,56,0.97) 0%,rgba(30,64,175,0.75) 50%,rgba(15,23,42,0.3) 100%)"></div>
                                <div class="absolute top-0 right-0 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(59,130,246,0.3) 0%,transparent 70%);transform:translate(30%,-30%)"></div>
                                
                                <div class="relative z-10 w-full">
                                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                        <div class="max-w-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center gap-1.5 bg-blue-500/25 border border-blue-400/40 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold text-blue-300">
                                                    <i class="fas fa-mars text-blue-300"></i> BOYS SPECIAL
                                                </span>
                                                <span class="inline-flex items-center gap-1 bg-white/10 border border-white/20 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold text-white/80">
                                                    <i class="fas fa-utensils text-cyan-300 text-[10px]"></i> 3 Meals & Gym
                                                </span>
                                            </div>
                                            <h2 class="text-xl sm:text-3xl md:text-4xl font-black leading-tight mb-1.5 text-white">
                                                Top Boys PGs <span style="color:#60a5fa">from ₹5,499/mo</span>
                                            </h2>
                                            <p class="text-xs sm:text-sm text-white/70 mb-3 max-w-md line-clamp-2">High-speed WiFi, hygienic food, gym & zero restrictions.</p>
                                            <div class="flex flex-wrap gap-2.5">
                                                <a href="{{ route('user.search') }}?gender=BOYS" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition shadow-lg shadow-blue-600/30">
                                                    <i class="fas fa-mars"></i> Explore Boys PG
                                                </a>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0">
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center min-w-[120px]">
                                                <p class="text-xl sm:text-2xl font-black text-white">800+</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Boys PGs Listed</p>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center">
                                                <p class="text-xl sm:text-2xl font-black text-blue-300">3 Meals</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Included Daily</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Custom nav arrows (desktop 640px+) --}}
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>

                    {{-- Animated pagination --}}
                    <div class="swiper-pagination !bottom-4"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= 2. PG NEAR ME ============================= --}}
    <section class="mt-8 sm:mt-10 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 inline-flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-location-crosshairs text-blue-600 text-sm"></i>
                        </span>
                        PG Near Me
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 ml-10">Verified stays near your current location</p>
                </div>
                <a href="{{ route('user.search', ['sort' => 'distance-asc', 'near_me' => 1]) }}" class="text-xs font-bold text-brand flex items-center gap-1.5 bg-brand-light hover:bg-brand/15 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    See all <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
            <div id="nearMeMobileContainer" class="md:hidden swiper nearMeSwiper overflow-hidden">
                <div class="swiper-wrapper" id="nearMeSwiperWrapper">
                    @forelse ($nearMeProperties as $pg)
                        @php
                            $tagMeta = $pg->display_tag_meta;
                            $genderMeta = $pg->gender_type_meta;
                            $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                            $displayImg = $pg->display_image_url;
                            $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                            $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                            $reviewCount = $pg->dynamic_reviews_count;
                        @endphp
                        <div class="swiper-slide !h-auto">
                            <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                                <div class="relative h-28 sm:h-36 overflow-hidden">
                                    <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                    <div class="absolute top-2 left-2 {{ $tagMeta['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-{{ $tagMeta['icon'] }} text-[8px]"></i> {{ $tagMeta['label'] }}
                                    </div>
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                        <i class="far fa-heart text-[10px]"></i>
                                    </button>
                                    <div class="absolute bottom-1.5 left-2 bg-black/75 backdrop-blur text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-[8px]"></i> {{ $ratingVal }}
                                        @if($reviewCount > 0)
                                            <span class="text-gray-300 font-normal">({{ $reviewCount }})</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-2.5 sm:p-3 flex flex-col flex-1 justify-between">
                                    <div>
                                        <div class="flex justify-between items-center gap-1 mb-1">
                                            <span class="font-bold text-xs text-gray-900 truncate">{{ $pg->name }}</span>
                                            <span class="{{ $genderMeta['class'] }} text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $locationText }}
                                        </p>
                                        <p class="text-[10px] text-blue-600 font-semibold mb-2 flex items-center gap-1 truncate pg-distance-badge" data-lat="{{ $pg->map_latitude }}" data-lng="{{ $pg->map_longitude }}">
                                            <i class="fas fa-location-dot text-[9px]"></i>
                                            <span class="dist-text">Calculating...</span>
                                        </p>
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @forelse($pg->amenities->take(2) as $am)
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[8px]"></i> {{ $am->name }}
                                                </span>
                                            @empty
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-wifi text-brand text-[8px]"></i> High-Speed WiFi
                                                </span>
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-snowflake text-brand text-[8px]"></i> AC
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                        <div>
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">Rent</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">₹{{ number_format($pg->monthly_rent) }}<span class="text-[9px] font-normal text-gray-500">/m</span></span>
                                        </div>
                                        <span class="bg-brand hover:bg-brand-dark text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-brand/30 transition">View</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="swiper-slide p-6 text-center text-gray-400 text-xs">No approved properties near this location.</div>
                    @endforelse
                </div>
            </div>

            {{-- ===== DESKTOP: 4-Col Grid ===== --}}
            <div id="nearMeDesktopGrid" class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($nearMeProperties as $pg)
                    @php
                        $tagMeta = $pg->display_tag_meta;
                        $genderMeta = $pg->gender_type_meta;
                        $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                        $displayImg = $pg->display_image_url;
                        $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                        $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                        $reviewCount = $pg->dynamic_reviews_count;
                    @endphp
                    <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                        <div class="relative h-44 overflow-hidden">
                            <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-2.5 left-2.5 {{ $tagMeta['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                <i class="fas fa-{{ $tagMeta['icon'] }} text-[9px]"></i> {{ $tagMeta['label'] }}
                            </div>
                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                <i class="far fa-heart text-xs"></i>
                            </button>
                            <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $ratingVal }}
                                @if($reviewCount > 0)
                                    <span class="text-gray-300 font-normal">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                                @else
                                    <span class="text-gray-300 font-normal">(0 reviews)</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <div class="flex justify-between items-center gap-1 mb-1">
                                    <span class="font-bold text-base text-gray-900 truncate">{{ $pg->name }}</span>
                                    <span class="{{ $genderMeta['class'] }} text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                    <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ $locationText }}
                                </p>
                                <p class="text-xs text-blue-600 font-semibold mb-3 flex items-center gap-1 truncate pg-distance-badge" data-lat="{{ $pg->map_latitude }}" data-lng="{{ $pg->map_longitude }}">
                                    <i class="fas fa-location-dot text-[11px]"></i>
                                    <span class="dist-text">Calculating...</span>
                                </p>
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @forelse($pg->amenities->take(3) as $am)
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[10px]"></i> {{ $am->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                                        </span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-snowflake text-brand text-[10px]"></i> AC
                                        </span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-utensils text-brand text-[10px]"></i> Meals
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-[10px] text-gray-400 block font-medium">Rent</span>
                                    <span class="text-base font-black text-gray-900">₹{{ number_format($pg->monthly_rent) }}<span class="text-xs font-normal text-gray-500">/mo</span></span>
                                </div>
                                <span class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-brand/30 transition">View</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 p-8 text-center text-gray-400 text-sm">No approved properties found near this area.</div>
                @endforelse
            </div>

            {{-- 5 km Radius Empty State (Shown when 0 PGs within 5 km) --}}
            <div id="nearMe5kmEmptyState" class="hidden p-8 rounded-3xl bg-white border border-gray-100 text-center max-w-xl mx-auto shadow-xs my-2">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2 text-xl shadow-xs">
                    <i class="fas fa-location-dot"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900">No verified PGs found within 5 km</h4>
                <p class="text-xs text-gray-500 mt-1 mb-3">There are currently no active listings within a 5 km radius of your location.</p>
                <a href="{{ route('user.search') }}" class="inline-flex items-center gap-1.5 bg-brand hover:bg-brand-dark text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs">
                    <i class="fas fa-search text-[10px]"></i> Explore All PGs
                </a>
            </div>
        </div>
    </section>

    {{-- ============================= 3. RECOMMENDED FOR YOU ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-brand-light inline-flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-heart text-brand text-sm"></i>
                        </span>
                        Recommended for You
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 ml-10">Handpicked top-rated stays with verified amenities</p>
                </div>
                <a href="{{ route('user.search') }}" class="text-xs font-bold text-brand flex items-center gap-1.5 bg-brand-light hover:bg-brand/15 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    View all <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
            <div class="md:hidden swiper recommendedSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse ($recommendedProperties as $pg)
                        @php
                            $tagMeta = $pg->display_tag_meta;
                            $genderMeta = $pg->gender_type_meta;
                            $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                            $displayImg = $pg->display_image_url;
                            $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                            $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                            $reviewCount = $pg->dynamic_reviews_count;
                        @endphp
                        <div class="swiper-slide !h-auto">
                            <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                                <div class="relative h-28 sm:h-36 overflow-hidden">
                                    <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                    <div class="absolute top-2 left-2 {{ $tagMeta['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-{{ $tagMeta['icon'] }} text-[8px]"></i> {{ $tagMeta['label'] }}
                                    </div>
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                        <i class="far fa-heart text-[10px]"></i>
                                    </button>
                                    <div class="absolute bottom-1.5 left-2 bg-black/75 backdrop-blur text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-[8px]"></i> {{ $ratingVal }}
                                        @if($reviewCount > 0)
                                            <span class="text-gray-300 font-normal">({{ $reviewCount }})</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-2.5 sm:p-3 flex flex-col flex-1 justify-between">
                                    <div>
                                        <div class="flex justify-between items-center gap-1 mb-1">
                                            <span class="font-bold text-xs text-gray-900 truncate">{{ $pg->name }}</span>
                                            <span class="{{ $genderMeta['class'] }} text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $locationText }}
                                        </p>
                                        <p class="text-[10px] text-brand font-semibold mb-2 flex items-center gap-1 truncate">
                                            <i class="fas fa-circle-check text-[9px]"></i> {{ $pg->landmark ?? 'Top Rated Stay' }}
                                        </p>
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @forelse($pg->amenities->take(2) as $am)
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[8px]"></i> {{ $am->name }}
                                                </span>
                                            @empty
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-wifi text-brand text-[8px]"></i> WiFi
                                                </span>
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-utensils text-brand text-[8px]"></i> Food
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                        <div>
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">Rent</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">₹{{ number_format($pg->monthly_rent) }}<span class="text-[9px] font-normal text-gray-500">/m</span></span>
                                        </div>
                                        <span class="bg-brand hover:bg-brand-dark text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-brand/30 transition">View</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="swiper-slide p-6 text-center text-gray-400 text-xs">No recommended stays found.</div>
                    @endforelse
                </div>
            </div>

            {{-- ===== DESKTOP: 4-Col Grid ===== --}}
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($recommendedProperties as $pg)
                    @php
                        $tagMeta = $pg->display_tag_meta;
                        $genderMeta = $pg->gender_type_meta;
                        $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                        $displayImg = $pg->display_image_url;
                        $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                        $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                        $reviewCount = $pg->dynamic_reviews_count;
                    @endphp
                    <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                        <div class="relative h-44 overflow-hidden">
                            <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-2.5 left-2.5 {{ $tagMeta['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                <i class="fas fa-{{ $tagMeta['icon'] }} text-[9px]"></i> {{ $tagMeta['label'] }}
                            </div>
                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                <i class="far fa-heart text-xs"></i>
                            </button>
                            <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $ratingVal }}
                                @if($reviewCount > 0)
                                    <span class="text-gray-300 font-normal">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                                @else
                                    <span class="text-gray-300 font-normal">(0 reviews)</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <div class="flex justify-between items-center gap-1 mb-1">
                                    <span class="font-bold text-base text-gray-900 truncate">{{ $pg->name }}</span>
                                    <span class="{{ $genderMeta['class'] }} text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                    <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ $locationText }}
                                </p>
                                <p class="text-xs text-brand font-semibold mb-3 flex items-center gap-1 truncate">
                                    <i class="fas fa-circle-check text-[11px]"></i> {{ $pg->landmark ?? 'Verified Stay' }}
                                </p>
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @forelse($pg->amenities->take(3) as $am)
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[10px]"></i> {{ $am->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                                        </span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-snowflake text-brand text-[10px]"></i> AC
                                        </span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-utensils text-brand text-[10px]"></i> Meals
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-[10px] text-gray-400 block font-medium">Rent</span>
                                    <span class="text-base font-black text-gray-900">₹{{ number_format($pg->monthly_rent) }}<span class="text-xs font-normal text-gray-500">/mo</span></span>
                                </div>
                                <span class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-brand/30 transition">View</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 p-8 text-center text-gray-400 text-sm">No recommended properties available.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============================= 4. POPULAR GIRLS PG ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-pink-50 inline-flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-venus text-pink-600 text-sm"></i>
                        </span>
                        Popular Girls PG
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 ml-10">Top-rated safe & verified stays with meals, CCTV & biometric security for girls</p>
                </div>
                <a href="{{ route('user.search') }}?gender=GIRLS" class="text-xs font-bold text-pink-600 flex items-center gap-1.5 bg-pink-50 hover:bg-pink-100 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    View all <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
            <div class="md:hidden swiper girlsPgSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse ($girlsProperties as $pg)
                        @php
                            $tagMeta = $pg->display_tag_meta;
                            $genderMeta = $pg->gender_type_meta;
                            $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                            $displayImg = $pg->display_image_url;
                            $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                            $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                            $reviewCount = $pg->dynamic_reviews_count;
                        @endphp
                        <div class="swiper-slide !h-auto">
                            <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                                <div class="relative h-28 sm:h-36 overflow-hidden">
                                    <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                    <div class="absolute top-2 left-2 {{ $tagMeta['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-{{ $tagMeta['icon'] }} text-[8px]"></i> {{ $tagMeta['label'] }}
                                    </div>
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                        <i class="far fa-heart text-[10px]"></i>
                                    </button>
                                    <div class="absolute bottom-1.5 left-2 bg-black/75 backdrop-blur text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-[8px]"></i> {{ $ratingVal }}
                                        @if($reviewCount > 0)
                                            <span class="text-gray-300 font-normal">({{ $reviewCount }})</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-2.5 sm:p-3 flex flex-col flex-1 justify-between">
                                    <div>
                                        <div class="flex justify-between items-center gap-1 mb-1">
                                            <span class="font-bold text-xs text-gray-900 truncate">{{ $pg->name }}</span>
                                            <span class="{{ $genderMeta['class'] }} text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $locationText }}
                                        </p>
                                        <p class="text-[10px] text-pink-600 font-semibold mb-2 flex items-center gap-1 truncate">
                                            <i class="fas fa-shield-heart text-[9px]"></i> {{ $pg->landmark ?? 'Safe & Secure Stay' }}
                                        </p>
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @forelse($pg->amenities->take(2) as $am)
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[8px]"></i> {{ $am->name }}
                                                </span>
                                            @empty
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-wifi text-brand text-[8px]"></i> WiFi
                                                </span>
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-shield-alt text-brand text-[8px]"></i> 24/7 Security
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                        <div>
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">Rent</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">₹{{ number_format($pg->monthly_rent) }}<span class="text-[9px] font-normal text-gray-500">/m</span></span>
                                        </div>
                                        <span class="bg-pink-600 hover:bg-pink-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-pink-500/30 transition">View</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="swiper-slide p-6 text-center text-gray-400 text-xs">No girls properties found.</div>
                    @endforelse
                </div>
            </div>

            {{-- ===== DESKTOP: 4-Col Grid ===== --}}
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($girlsProperties as $pg)
                    @php
                        $tagMeta = $pg->display_tag_meta;
                        $genderMeta = $pg->gender_type_meta;
                        $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                        $displayImg = $pg->display_image_url;
                        $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                        $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                        $reviewCount = $pg->dynamic_reviews_count;
                    @endphp
                    <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                        <div class="relative h-44 overflow-hidden">
                            <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-2.5 left-2.5 {{ $tagMeta['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                <i class="fas fa-{{ $tagMeta['icon'] }} text-[9px]"></i> {{ $tagMeta['label'] }}
                            </div>
                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                <i class="far fa-heart text-xs"></i>
                            </button>
                            <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $ratingVal }}
                                @if($reviewCount > 0)
                                    <span class="text-gray-300 font-normal">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                                @else
                                    <span class="text-gray-300 font-normal">(0 reviews)</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <div class="flex justify-between items-center gap-1 mb-1">
                                    <span class="font-bold text-base text-gray-900 truncate">{{ $pg->name }}</span>
                                    <span class="{{ $genderMeta['class'] }} text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                    <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ $locationText }}
                                </p>
                                <p class="text-xs text-pink-600 font-semibold mb-3 flex items-center gap-1 truncate">
                                    <i class="fas fa-shield-heart text-[11px]"></i> {{ $pg->landmark ?? 'Safe, CCTV & Meals Included' }}
                                </p>
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @forelse($pg->amenities->take(3) as $am)
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[10px]"></i> {{ $am->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                                        </span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-snowflake text-brand text-[10px]"></i> AC
                                        </span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-shield-alt text-brand text-[10px]"></i> Security
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-[10px] text-gray-400 block font-medium">Rent</span>
                                    <span class="text-base font-black text-gray-900">₹{{ number_format($pg->monthly_rent) }}<span class="text-xs font-normal text-gray-500">/mo</span></span>
                                </div>
                                <span class="bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-pink-500/30 transition">View</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 p-8 text-center text-gray-400 text-sm">No girls properties found.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============================= 5. POPULAR BOYS PG ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 inline-flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-mars text-blue-600 text-sm"></i>
                        </span>
                        Popular Boys PG
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 ml-10">Top-rated verified stays with meals & amenities for boys</p>
                </div>
                <a href="{{ route('user.search') }}?gender=BOYS" class="text-xs font-bold text-blue-600 flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    View all <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
            <div class="md:hidden swiper boysPgSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse ($boysProperties as $pg)
                        @php
                            $tagMeta = $pg->display_tag_meta;
                            $genderMeta = $pg->gender_type_meta;
                            $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                            $displayImg = $pg->display_image_url;
                            $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                            $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                            $reviewCount = $pg->dynamic_reviews_count;
                        @endphp
                        <div class="swiper-slide !h-auto">
                            <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                                <div class="relative h-28 sm:h-36 overflow-hidden">
                                    <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                    <div class="absolute top-2 left-2 {{ $tagMeta['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-{{ $tagMeta['icon'] }} text-[8px]"></i> {{ $tagMeta['label'] }}
                                    </div>
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                        <i class="far fa-heart text-[10px]"></i>
                                    </button>
                                    <div class="absolute bottom-1.5 left-2 bg-black/75 backdrop-blur text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-[8px]"></i> {{ $ratingVal }}
                                        @if($reviewCount > 0)
                                            <span class="text-gray-300 font-normal">({{ $reviewCount }})</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-2.5 sm:p-3 flex flex-col flex-1 justify-between">
                                    <div>
                                        <div class="flex justify-between items-center gap-1 mb-1">
                                            <span class="font-bold text-xs text-gray-900 truncate">{{ $pg->name }}</span>
                                            <span class="{{ $genderMeta['class'] }} text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $locationText }}
                                        </p>
                                        <p class="text-[10px] text-blue-600 font-semibold mb-2 flex items-center gap-1 truncate">
                                            <i class="fas fa-utensils text-[9px]"></i> {{ $pg->landmark ?? 'Meals & WiFi' }}
                                        </p>
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @forelse($pg->amenities->take(2) as $am)
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[8px]"></i> {{ $am->name }}
                                                </span>
                                            @empty
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-wifi text-brand text-[8px]"></i> WiFi
                                                </span>
                                                <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                    <i class="fas fa-snowflake text-brand text-[8px]"></i> AC
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                        <div>
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">Rent</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">₹{{ number_format($pg->monthly_rent) }}<span class="text-[9px] font-normal text-gray-500">/m</span></span>
                                        </div>
                                        <span class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-blue-500/30 transition">View</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="swiper-slide p-6 text-center text-gray-400 text-xs">No boys properties found.</div>
                    @endforelse
                </div>
            </div>

            {{-- ===== DESKTOP: 4-Col Grid ===== --}}
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($boysProperties as $pg)
                    @php
                        $tagMeta = $pg->display_tag_meta;
                        $genderMeta = $pg->gender_type_meta;
                        $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                        $displayImg = $pg->display_image_url;
                        $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                        $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                        $reviewCount = $pg->dynamic_reviews_count;
                    @endphp
                    <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                        <div class="relative h-44 overflow-hidden">
                            <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-2.5 left-2.5 {{ $tagMeta['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                <i class="fas fa-{{ $tagMeta['icon'] }} text-[9px]"></i> {{ $tagMeta['label'] }}
                            </div>
                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                <i class="far fa-heart text-xs"></i>
                            </button>
                            <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $ratingVal }}
                                @if($reviewCount > 0)
                                    <span class="text-gray-300 font-normal">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                                @else
                                    <span class="text-gray-300 font-normal">(0 reviews)</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <div class="flex justify-between items-center gap-1 mb-1">
                                    <span class="font-bold text-base text-gray-900 truncate">{{ $pg->name }}</span>
                                    <span class="{{ $genderMeta['class'] }} text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                    <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ $locationText }}
                                </p>
                                <p class="text-xs text-blue-600 font-semibold mb-3 flex items-center gap-1 truncate">
                                    <i class="fas fa-utensils text-[11px]"></i> {{ $pg->landmark ?? '3 Meals & WiFi Included' }}
                                </p>
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @forelse($pg->amenities->take(3) as $am)
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[10px]"></i> {{ $am->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                                        </span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-snowflake text-brand text-[10px]"></i> AC
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-[10px] text-gray-400 block font-medium">Rent</span>
                                    <span class="text-base font-black text-gray-900">₹{{ number_format($pg->monthly_rent) }}<span class="text-xs font-normal text-gray-500">/mo</span></span>
                                </div>
                                <span class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-blue-500/30 transition">View</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 p-8 text-center text-gray-400 text-sm">No boys properties found.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============================= 5. RECENTLY ADDED ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-orange-50 inline-flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-orange-500 text-sm"></i>
                        </span>
                        Recently Added
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 ml-10">Fresh verified listings published this week</p>
                </div>
                <a href="{{ route('user.search') }}" class="text-xs font-bold text-brand flex items-center gap-1.5 bg-brand-light hover:bg-brand/15 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    See all <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- Responsive Grid (2-col mobile, 4-col desktop) --}}
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @forelse ($recentProperties as $pg)
                    @php
                        $tagMeta = $pg->display_tag_meta;
                        $genderMeta = $pg->gender_type_meta;
                        $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                        $displayImg = $pg->display_image_url;
                        $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                        $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                        $reviewCount = $pg->dynamic_reviews_count;
                    @endphp
                    <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                        <div class="relative h-32 sm:h-44 overflow-hidden">
                            <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-2.5 left-2.5 {{ $tagMeta['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                <i class="fas fa-{{ $tagMeta['icon'] }} text-[9px]"></i> {{ $tagMeta['label'] }}
                            </div>
                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                <i class="far fa-heart text-xs"></i>
                            </button>
                            <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $ratingVal }}
                                @if($reviewCount > 0)
                                    <span class="text-gray-300 font-normal">({{ $reviewCount }})</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <div class="flex justify-between items-center gap-1 mb-1">
                                    <span class="font-bold text-xs sm:text-base text-gray-900 truncate leading-tight">{{ $pg->name }}</span>
                                    <span class="{{ $genderMeta['class'] }} text-[9px] sm:text-[10px] font-extrabold px-1.5 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                                </div>
                                <p class="text-[11px] sm:text-xs text-gray-500 flex items-center gap-1 mb-2.5 truncate">
                                    <i class="fas fa-map-marker-alt text-brand text-[10px]"></i> {{ $locationText }}
                                </p>
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @forelse($pg->amenities->take(2) as $am)
                                        <span class="text-[10px] sm:text-xs bg-gray-50 text-gray-600 px-1.5 sm:px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand text-[9px]"></i> {{ $am->name }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] sm:text-xs bg-gray-50 text-gray-600 px-1.5 sm:px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-wifi text-brand text-[9px]"></i> WiFi
                                        </span>
                                        <span class="text-[10px] sm:text-xs bg-gray-50 text-gray-600 px-1.5 sm:px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                            <i class="fas fa-snowflake text-brand text-[9px]"></i> AC
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="pt-2.5 sm:pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 block font-medium">Rent</span>
                                    <span class="text-xs sm:text-base font-black text-gray-900">₹{{ number_format($pg->monthly_rent) }}<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></span>
                                </div>
                                <span class="bg-brand hover:bg-brand-dark text-white text-[11px] sm:text-xs font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl shadow-sm shadow-brand/30 transition">View</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 p-8 text-center text-gray-400 text-sm">No new listings published recently.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============================= 6. EXPLORE BY BUDGET ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="section-title mb-4">Explore by Budget</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-5">
                @foreach([
                    ['label' => 'Under ₹5K', 'sub' => 'Pocket Friendly', 'icon' => 'wallet', 'bg' => 'bg-green-50', 'color' => 'text-green-600', 'url' => route('user.search', ['budget' => '0-5000', 'min_price' => 0, 'max_price' => 5000])],
                    ['label' => '₹6K–₹10K', 'sub' => 'Mid-Range Comfort', 'icon' => 'bed', 'bg' => 'bg-blue-50', 'color' => 'text-blue-600', 'url' => route('user.search', ['budget' => '6000-10000', 'min_price' => 6000, 'max_price' => 10000])],
                    ['label' => '₹10K–₹15K', 'sub' => 'Premium Living', 'icon' => 'gem', 'bg' => 'bg-purple-50', 'color' => 'text-purple-600', 'url' => route('user.search', ['budget' => '10000-15000', 'min_price' => 10000, 'max_price' => 15000])],
                    ['label' => '₹15K+', 'sub' => 'Luxury Co-Living', 'icon' => 'crown', 'bg' => 'bg-amber-50', 'color' => 'text-amber-600', 'url' => route('user.search', ['budget' => '15000-plus', 'min_price' => 15000])],
                ] as $b)
                <a href="{{ $b['url'] }}" class="budget-card flex flex-col justify-between p-4 sm:p-5 tap-ripple">
                    <div>
                        <div class="w-11 h-11 rounded-2xl {{ $b['bg'] }} flex items-center justify-center mb-3">
                            <i class="fas fa-{{ $b['icon'] }} {{ $b['color'] }} text-base"></i>
                        </div>
                        <p class="font-extrabold text-sm sm:text-base text-gray-900 leading-tight">{{ $b['label'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $b['sub'] }}</p>
                    </div>
                    <span class="text-xs font-bold text-brand flex items-center gap-1 mt-4">Browse <i class="fas fa-arrow-right text-[10px]"></i></span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= 7. EXPLORE TOP CITIES ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title">Top Cities</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Explore verified student & co-living hubs across India</p>
                </div>
                <a href="{{ route('user.search') }}" class="text-xs font-bold text-brand flex items-center gap-1.5 bg-brand-light hover:bg-brand/15 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    All Cities <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4">
                @forelse ($topCities as $c)
                    <a href="{{ route('user.search') }}?city={{ urlencode($c->name) }}" class="relative rounded-2xl sm:rounded-3xl overflow-hidden aspect-[4/3] sm:aspect-square tap-ripple group shadow-md border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <img src="{{ $c->display_image_url }}" alt="{{ $c->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 sm:bottom-3.5 sm:left-3.5 text-left text-white pr-2">
                            <p class="font-black text-sm sm:text-base leading-tight drop-shadow-md tracking-tight">{{ $c->name }}</p>
                            <p class="text-[10px] text-gray-200 font-medium opacity-90 drop-shadow-sm">{{ $c->properties_count > 0 ? $c->properties_count . ' verified stays' : 'Explore stays' }}</p>
                        </div>
                    </a>
                @empty
                    @php
                        $fallbackCities = [
                            ['name' => 'Delhi NCR', 'img' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&w=600&q=80', 'stays' => '2,800+'],
                            ['name' => 'Bangalore', 'img' => 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?auto=format&fit=crop&w=600&q=80', 'stays' => '3,400+'],
                            ['name' => 'Noida', 'img' => 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=600&q=80', 'stays' => '1,200+'],
                            ['name' => 'Mumbai', 'img' => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=600&q=80', 'stays' => '1,900+'],
                            ['name' => 'Gurugram', 'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=600&q=80', 'stays' => '1,500+'],
                            ['name' => 'Pune', 'img' => 'https://images.unsplash.com/photo-1627894483216-2138af692e32?auto=format&fit=crop&w=600&q=80', 'stays' => '950+'],
                        ];
                    @endphp
                    @foreach ($fallbackCities as $fc)
                        <a href="{{ route('user.search') }}?city={{ urlencode($fc['name']) }}" class="relative rounded-2xl sm:rounded-3xl overflow-hidden aspect-[4/3] sm:aspect-square tap-ripple group shadow-md border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <img src="{{ $fc['img'] }}" alt="{{ $fc['name'] }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                            <div class="absolute bottom-3 left-3 sm:bottom-3.5 sm:left-3.5 text-left text-white pr-2">
                                <p class="font-black text-sm sm:text-base leading-tight drop-shadow-md tracking-tight">{{ $fc['name'] }}</p>
                                <p class="text-[10px] text-gray-200 font-medium opacity-90 drop-shadow-sm">{{ $fc['stays'] }} verified stays</p>
                            </div>
                        </a>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- ============================= 8. WHY STAYNEST ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-brand-light/60 via-white to-white rounded-3xl border border-brand-100 p-6 sm:p-10 shadow-sm">
                <div class="text-center mb-8">
                    <p class="text-xs font-bold text-brand uppercase tracking-widest mb-1.5">StayNest Promise</p>
                    <h2 class="text-xl sm:text-3xl font-black text-gray-900">Why 50,000+ Choose Us</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                    @foreach([
                        ['icon' => 'shield-halved', 'bg' => 'bg-brand-light', 'color' => 'text-brand', 'title' => '100% Verified', 'sub' => 'Physically inspected rooms'],
                        ['icon' => 'hand-holding-dollar', 'bg' => 'bg-orange-50', 'color' => 'text-orange-500', 'title' => 'Zero Brokerage', 'sub' => 'Direct owner bookings'],
                        ['icon' => 'headset', 'bg' => 'bg-blue-50', 'color' => 'text-blue-500', 'title' => '24/7 Support', 'sub' => 'Always on standby'],
                        ['icon' => 'bolt', 'bg' => 'bg-purple-50', 'color' => 'text-purple-500', 'title' => 'Instant Move-in', 'sub' => 'Digital KYC ready'],
                    ] as $w)
                    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm flex flex-col items-start gap-3">
                        <div class="w-12 h-12 {{ $w['bg'] }} rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-{{ $w['icon'] }} {{ $w['color'] }} text-lg"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm sm:text-base text-gray-900">{{ $w['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $w['sub'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= 9. HOST YOUR PG CTA ============================= --}}
    <section class="mt-8 sm:mt-10 mb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-900 rounded-3xl p-6 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 shadow-xl">
                <div>
                    <p class="text-xs font-bold text-brand uppercase tracking-widest mb-1.5">For Property Owners</p>
                    <h1 class="text-xl sm:text-3xl font-extrabold text-white mb-2 leading-tight">List your PG and fill<br class="sm:hidden"> beds faster</h1>
                    <p class="text-xs sm:text-sm text-gray-400 max-w-lg">Reach 50,000+ students & working professionals searching for verified accommodations every month.</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('user.list-property') }}" class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-3 rounded-2xl text-sm tap-ripple transition shadow-lg shadow-brand/30">
                        List Free
                    </a>
                    <a href="{{ route('user.pricing') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-5 py-3 rounded-2xl text-sm tap-ripple transition border border-white/20">
                        Pricing
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 mb-12 sm:mb-16 hidden md:block">
        <div class="bg-gradient-to-br from-brand-50/80 via-teal-50/30 to-white rounded-3xl p-6 sm:p-8 md:p-12 border border-brand/15 relative overflow-hidden shadow-xs">
            <div class="absolute right-0 top-0 w-80 sm:w-96 h-80 sm:h-96 bg-brand/10 rounded-full -mr-24 -mt-24 blur-3xl pointer-events-none"></div>
            <div class="absolute left-0 bottom-0 w-64 h-64 bg-teal-500/5 rounded-full -ml-20 -mb-20 blur-2xl pointer-events-none"></div>
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12 relative z-10">
                <div class="max-w-xl text-center lg:text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand/10 text-brand text-xs font-bold uppercase tracking-wider mb-3">
                        <i class="fas fa-mobile-screen"></i> StayNest Mobile App
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-900 mb-3 tracking-tight leading-tight">Get the StayNest App</h2>
                    <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base lg:text-lg leading-relaxed">Search, shortlist &amp; book your favourite verified stays, pay rent with zero brokerage, and get instant owner assistance on the go.</p>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4">
                        <button type="button" class="bg-gray-900 text-white px-5 py-3 sm:px-6 sm:py-3.5 rounded-2xl flex items-center gap-3 hover:bg-gray-800 transition tap-effect shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform duration-200">
                            <i class="fab fa-google-play text-2xl sm:text-3xl text-emerald-400"></i>
                            <div class="text-left">
                                <div class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-400">GET IT ON</div>
                                <div class="text-xs sm:text-sm md:text-base font-bold">Google Play</div>
                            </div>
                        </button>
                        <!-- <button type="button" class="bg-gray-900 text-white px-5 py-3 sm:px-6 sm:py-3.5 rounded-2xl flex items-center gap-3 hover:bg-gray-800 transition tap-effect shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform duration-200">
                            <i class="fab fa-apple text-2xl sm:text-3xl text-white"></i>
                            <div class="text-left">
                                <div class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-400">Download on the</div>
                                <div class="text-xs sm:text-sm md:text-base font-bold">App Store</div>
                            </div>
                        </button> -->
                    </div>
                    <!-- <div class="mt-6 sm:mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 text-xs text-gray-500 font-medium">
                        <span class="flex items-center gap-1.5"><i class="fas fa-star text-yellow-400"></i> 4.8★ Rating</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 hidden sm:inline-block"></span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-download text-brand"></i> 50K+ Downloads</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 hidden sm:inline-block"></span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-shield-alt text-blue-500"></i> 100% Free</span>
                    </div> -->
                </div>
                <div class="flex items-center justify-center w-full lg:w-auto flex-shrink-0">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-brand to-teal-500 rounded-[2.5rem] blur-xl opacity-20 group-hover:opacity-35 transition duration-500"></div>
                        <div class="relative bg-gray-900 p-2 sm:p-2.5 rounded-[2.2rem] sm:rounded-[2.5rem] shadow-2xl border-4 border-gray-800/80 max-w-[220px] sm:max-w-[260px] md:max-w-[280px] lg:max-w-[300px]">
                            <div class="absolute top-2.5 left-1/2 -translate-x-1/2 w-12 h-1 bg-gray-700 rounded-full z-20"></div>
                            <img src="/images/app-banner.png" alt="StayNest Mobile App Preview" loading="lazy" decoding="async" class="w-full h-auto object-contain rounded-[1.8rem] sm:rounded-[2.1rem] transition duration-500 group-hover:scale-[1.01]">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. Promo banner — autoplay with nav & dynamic bullets
    new Swiper('.promoSwiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        speed: 700,
        autoplay: { delay: 4500, disableOnInteraction: false },
        pagination: { el: '.promoSwiper .swiper-pagination', clickable: true, dynamicBullets: true },
        navigation: { nextEl: '.promoSwiper .swiper-button-next', prevEl: '.promoSwiper .swiper-button-prev' },
    });

    // 2. Mobile 2-Column Horizontal Sliders (Near Me & Recommended)
    if (window.innerWidth < 768) {
        window.nearMeSwiperInstance = new Swiper('.nearMeSwiper', {
            slidesPerView: 2.05,
            spaceBetween: 10,
            grabCursor: true,
            observer: true,
            observeParents: true,
            breakpoints: {
                480: { slidesPerView: 2.3, spaceBetween: 12 },
                640: { slidesPerView: 2.8, spaceBetween: 14 },
            }
        });

        new Swiper('.recommendedSwiper', {
            slidesPerView: 2.05,
            spaceBetween: 10,
            grabCursor: true,
            breakpoints: {
                480: { slidesPerView: 2.3, spaceBetween: 12 },
                640: { slidesPerView: 2.8, spaceBetween: 14 },
            }
        });

        // 3. Girls PG horizontal card slider (mobile)
        new Swiper('.girlsPgSwiper', {
            slidesPerView: 2.05,
            spaceBetween: 10,
            grabCursor: true,
            breakpoints: {
                480: { slidesPerView: 2.3, spaceBetween: 12 },
                640: { slidesPerView: 2.8, spaceBetween: 14 },
            }
        });

        // 4. Boys PG horizontal card slider (mobile)
        new Swiper('.boysPgSwiper', {
            slidesPerView: 2.05,
            spaceBetween: 10,
            grabCursor: true,
            breakpoints: {
                480: { slidesPerView: 2.3, spaceBetween: 12 },
                640: { slidesPerView: 2.8, spaceBetween: 14 },
            }
        });
    }

    // 4. Dynamic GPS Geolocation Distance Calculator for "PG Near Me" section
    initNearMeGeolocation();
});

// Haversine Distance Formula in Kilometers
function getHaversineDistanceKm(lat1, lon1, lat2, lon2) {
    const R = 6371; // Earth radius in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

function formatDistance(distKm) {
    if (distKm < 1) {
        const meters = Math.max(100, Math.round(distKm * 1000));
        return meters + ' m away';
    } else if (distKm < 10) {
        return distKm.toFixed(1) + ' km away';
    } else if (distKm < 100) {
        return Math.round(distKm) + ' km away';
    } else {
        return Math.round(distKm).toLocaleString() + ' km away';
    }
}

function updateAllDistances(userLat, userLng) {
    if (!userLat || !userLng) return;

    try {
        localStorage.setItem('staynest_user_lat', userLat);
        localStorage.setItem('staynest_user_lng', userLng);
    } catch (e) {}

    const MAX_ALLOWED_DISTANCE_KM = 5.0; // Strictly below 5 km

    // 1. Process Mobile Slides
    const mobileSlides = document.querySelectorAll('.nearMeSwiper .swiper-slide');
    let visibleMobileCount = 0;
    const mobileItems = [];

    mobileSlides.forEach(slide => {
        const badge = slide.querySelector('.pg-distance-badge');
        if (!badge) return;
        const lat = parseFloat(badge.getAttribute('data-lat'));
        const lng = parseFloat(badge.getAttribute('data-lng'));
        const textSpan = badge.querySelector('.dist-text');

        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
            const distKm = getHaversineDistanceKm(userLat, userLng, lat, lng);
            if (distKm <= MAX_ALLOWED_DISTANCE_KM) {
                slide.style.display = '';
                if (textSpan) textSpan.textContent = formatDistance(distKm);
                mobileItems.push({ element: slide, distance: distKm });
                visibleMobileCount++;
            } else {
                slide.style.display = 'none';
            }
        } else {
            slide.style.display = 'none';
        }
    });

    const MAX_HOME_DISPLAY = 8; // Strictly show maximum 8 listings on home page

    // Sort mobile slides by nearest distance (closest first) and limit to max 8
    const mobileWrapper = document.getElementById('nearMeSwiperWrapper');
    if (mobileWrapper && mobileItems.length > 0) {
        mobileItems.sort((a, b) => a.distance - b.distance);
        mobileItems.forEach((item, idx) => {
            if (idx < MAX_HOME_DISPLAY) {
                item.element.style.display = '';
                mobileWrapper.appendChild(item.element);
            } else {
                item.element.style.display = 'none';
            }
        });
    }

    // 2. Process Desktop Grid Cards
    const desktopCards = document.querySelectorAll('#nearMeDesktopGrid > a.pg-card');
    let visibleDesktopCount = 0;
    const desktopItems = [];

    desktopCards.forEach(card => {
        const badge = card.querySelector('.pg-distance-badge');
        if (!badge) return;
        const lat = parseFloat(badge.getAttribute('data-lat'));
        const lng = parseFloat(badge.getAttribute('data-lng'));
        const textSpan = badge.querySelector('.dist-text');

        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
            const distKm = getHaversineDistanceKm(userLat, userLng, lat, lng);
            if (distKm <= MAX_ALLOWED_DISTANCE_KM) {
                card.style.display = '';
                if (textSpan) textSpan.textContent = formatDistance(distKm);
                desktopItems.push({ element: card, distance: distKm });
                visibleDesktopCount++;
            } else {
                card.style.display = 'none';
            }
        } else {
            card.style.display = 'none';
        }
    });

    // Sort desktop cards by nearest distance (closest first) and limit to max 8
    const desktopGrid = document.getElementById('nearMeDesktopGrid');
    if (desktopGrid && desktopItems.length > 0) {
        desktopItems.sort((a, b) => a.distance - b.distance);
        desktopItems.forEach((item, idx) => {
            if (idx < MAX_HOME_DISPLAY) {
                item.element.style.display = '';
                desktopGrid.appendChild(item.element);
            } else {
                item.element.style.display = 'none';
            }
        });
    }

    // 3. Toggle Empty State for < 5 km
    const emptyState = document.getElementById('nearMe5kmEmptyState');
    const mobileContainer = document.getElementById('nearMeMobileContainer');
    if (emptyState) {
        if (visibleMobileCount === 0 && visibleDesktopCount === 0) {
            emptyState.classList.remove('hidden');
            if (desktopGrid) desktopGrid.classList.add('hidden');
            if (mobileContainer) mobileContainer.classList.add('hidden');
        } else {
            emptyState.classList.add('hidden');
            if (desktopGrid) desktopGrid.classList.remove('hidden');
            if (mobileContainer) mobileContainer.classList.remove('hidden');
        }
    }

    // Update Swiper instance
    if (window.nearMeSwiperInstance && typeof window.nearMeSwiperInstance.update === 'function') {
        window.nearMeSwiperInstance.update();
    }
}

function getEffectiveUserCoordinates() {
    // 1. If user has a saved / profile address (like Sector 62, Noida), prioritize it
    const isAddressLocked = localStorage.getItem('staynest_user_address_locked') === 'true';
    const savedAddrStr = localStorage.getItem('staynest_default_address');
    
    if (savedAddrStr) {
        try {
            const parsed = JSON.parse(savedAddrStr);
            if (parsed.lat && parsed.lng) {
                return { lat: parseFloat(parsed.lat), lng: parseFloat(parsed.lng), isLocked: true };
            }
            const fullStr = ((parsed.line1 || '') + ' ' + (parsed.line2 || '')).toLowerCase();
            if (fullStr.includes('noida') || fullStr.includes('sector 62') || fullStr.includes('201309')) {
                return { lat: 28.6280, lng: 77.3649, isLocked: true };
            } else if (fullStr.includes('bangalore') || fullStr.includes('bengaluru') || fullStr.includes('indiranagar')) {
                return { lat: 12.9716, lng: 77.5946, isLocked: true };
            } else if (fullStr.includes('delhi') || fullStr.includes('south ex')) {
                return { lat: 28.5742, lng: 77.2242, isLocked: true };
            }
        } catch(e) {}
    }

    // 2. Check cached coordinates if locked
    const cachedLat = parseFloat(localStorage.getItem('staynest_user_lat') || localStorage.getItem('user_cached_lat'));
    const cachedLng = parseFloat(localStorage.getItem('staynest_user_lng') || localStorage.getItem('user_cached_lng'));
    if (!isNaN(cachedLat) && !isNaN(cachedLng) && cachedLat !== 0 && cachedLng !== 0 && isAddressLocked) {
        return { lat: cachedLat, lng: cachedLng, isLocked: true };
    }

    // 3. Default fallback to Noida Sector 62 (prevents Delhi network/ISP routing glitches)
    return { lat: 28.6280, lng: 77.3649, isLocked: false };
}

function initNearMeGeolocation() {
    const eff = getEffectiveUserCoordinates();
    updateAllDistances(eff.lat, eff.lng);

    // If user has locked their address in profile, DO NOT let desktop ISP network glitch flip it to Delhi!
    if (eff.isLocked) {
        return;
    }

    // If user is guest / not locked, use live device GPS
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                if (pos && pos.coords) {
                    const accuracy = pos.coords.accuracy || 1000;
                    // Only apply if accuracy is high (< 1500m) to prevent rough ISP gateway misplacement
                    if (accuracy <= 1500) {
                        updateAllDistances(pos.coords.latitude, pos.coords.longitude);
                    }
                }
            },
            function(err) {
                const curLat = parseFloat(localStorage.getItem('staynest_user_lat')) || 28.6280;
                const curLng = parseFloat(localStorage.getItem('staynest_user_lng')) || 77.3649;
                updateAllDistances(curLat, curLng);
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
        );
    }
}
</script>
@endpush
