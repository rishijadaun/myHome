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
      "description": "Find verified PGs, flats, and co-living spaces with zero brokerage across India.",
      "publisher": {
        "@id": "{{ route('user.home') }}/#organization"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ route('user.search') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "FAQPage",
      "@id": "{{ route('user.home') }}/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How does StayNest verify properties?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Every PG, Flat, and Commercial Space undergoes on-site physical verification, landlord checks, biometric security assessment, and hygiene inspections before receiving the Verified badge."
          }
        },
        {
          "@type": "Question",
          "name": "Is there any brokerage fee on StayNest?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No. StayNest operates on a 100% Zero Brokerage model. You connect directly with verified property owners and managers with zero hidden fees."
          }
        },
        {
          "@type": "Question",
          "name": "What amenities are typically included?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Accommodations include high-speed optical fiber WiFi, daily housekeeping, 3-tier security with CCTV, air conditioning, power backup, and hygienic North & South Indian meal options."
          }
        }
      ]
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

    /* Promo Banner Navigation Buttons */
    .promoSwiper .swiper-button-prev,
    .promoSwiper .swiper-button-next {
        width: 40px !important;
        height: 40px !important;
        background: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border-radius: 50% !important;
        color: #0f172a !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: 1px solid rgba(255, 255, 255, 0.7) !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        margin-top: 0 !important;
    }
    .promoSwiper .swiper-button-prev {
        left: 14px !important;
    }
    .promoSwiper .swiper-button-next {
        right: 14px !important;
    }
    @media (min-width: 1024px) {
        .promoSwiper .swiper-button-prev {
            left: 18px !important;
        }
        .promoSwiper .swiper-button-next {
            right: 18px !important;
        }
    }
    .promoSwiper .swiper-button-prev:hover,
    .promoSwiper .swiper-button-next:hover {
        background: #ffffff !important;
        color: #4bb59d !important;
        transform: translateY(-50%) scale(1.08) !important;
        box-shadow: 0 6px 22px rgba(0, 0, 0, 0.35) !important;
    }
    .promoSwiper .swiper-button-prev::after,
    .promoSwiper .swiper-button-next::after {
        font-size: 13px !important;
        font-weight: 900 !important;
    }
    @media (max-width: 639px) {
        .promoSwiper .swiper-button-prev,
        .promoSwiper .swiper-button-next {
            display: none !important;
        }
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

    /* ===================== PROPERTY TYPE SWITCHER CARDS (OPTION 3) ===================== */
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

    .property-type-card.active-roommate {
        border-color: #4c30dd !important;
        border-width: 2px !important;
        background-color: #f3f0ff !important;
        box-shadow: 0 4px 14px -2px rgba(76, 48, 221, 0.22) !important;
    }

    /* Roommate & Flatmate #4c30dd Dedicated Styles */
    .roommate-card {
        border-radius: 0px !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .roommate-card:hover {
        border-color: #4c30dd !important;
        box-shadow: 0 10px 25px -4px rgba(76, 48, 221, 0.2) !important;
    }
    .roommate-btn-view {
        background-color: #4c30dd !important;
        color: #ffffff !important;
        border-radius: 0px !important;
        font-weight: 700 !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 8px rgba(76, 48, 221, 0.35) !important;
    }
    .roommate-btn-view:hover {
        background-color: #3d24c0 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(76, 48, 221, 0.5) !important;
    }
    .roommate-post-banner {
        background: linear-gradient(135deg, #09051d 0%, #150d3a 50%, #2b1770 100%) !important;
        border: 1.5px solid rgba(76, 48, 221, 0.45) !important;
        border-radius: 0px !important;
        box-shadow: 0 12px 30px -5px rgba(9, 5, 29, 0.5) !important;
    }
    .roommate-btn-cta {
        background-color: #4c30dd !important;
        color: #ffffff !important;
        border-radius: 0px !important;
        font-weight: 800 !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 18px rgba(76, 48, 221, 0.45) !important;
    }
    .roommate-btn-cta:hover {
        background-color: #3b20c8 !important;
        color: #ffffff !important;
        box-shadow: 0 6px 24px rgba(76, 48, 221, 0.65) !important;
        transform: translateY(-1px);
    }

    .view-container-fade {
        animation: fadeInView 0.24s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @keyframes fadeInView {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
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
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:py-8 sm:px-14 md:py-9 md:px-16 lg:px-20" style="background:#0a2e24">
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
                                            <h1 class="text-xl sm:text-3xl md:text-4xl font-black leading-tight mb-1.5 text-white">
                                                Find Verified PGs & Co-Living in India <span style="color:#7eebd4">| Zero Brokerage</span>
                                            </h1>
                                            <p class="text-xs sm:text-sm text-white/70 mb-3 max-w-md line-clamp-2">Book directly with verified owners — WiFi, 3 Meals & 24/7 Security included.</p>
                                            <div class="flex flex-wrap gap-2.5">
                                                <a href="{{ route('user.search') }}" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white font-bold px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition shadow-lg shadow-brand/30">
                                                    <i class="fas fa-search"></i> Explore Stays
                                                </a>
                                                <a href="{{ route('user.list-property') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition">
                                                    Post Property Free
                                                </a>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0 mr-0 sm:mr-4 md:mr-6">
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
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:py-8 sm:px-14 md:py-9 md:px-16 lg:px-20" style="background:#0f0a2a">
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
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0 mr-0 sm:mr-4 md:mr-6">
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
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:py-8 sm:px-14 md:py-9 md:px-16 lg:px-20" style="background:#1a0a00">
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
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0 mr-0 sm:mr-4 md:mr-6">
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
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:py-8 sm:px-14 md:py-9 md:px-16 lg:px-20" style="background:#071b38">
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
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0 mr-0 sm:mr-4 md:mr-6">
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

                        {{-- Slide 5: Find Flatmates & Roommates --}}
                        <div class="swiper-slide !h-auto">
                            <div class="relative overflow-hidden rounded-3xl h-[260px] sm:h-[300px] md:h-[320px] w-full flex flex-col justify-end p-5 sm:py-8 sm:px-14 md:py-9 md:px-16 lg:px-20" style="background:#06241b">
                                <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                     alt="Find Flatmates and Roommates" class="absolute inset-0 w-full h-full object-cover opacity-35">
                                <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(6,36,27,0.97) 0%,rgba(16,77,59,0.80) 50%,rgba(10,30,24,0.3) 100%)"></div>
                                <div class="absolute top-0 right-0 w-64 h-64 rounded-full" style="background:radial-gradient(circle,rgba(52,211,153,0.3) 0%,transparent 70%);transform:translate(30%,-30%)"></div>
                                
                                <div class="relative z-10 w-full">
                                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                                        <div class="max-w-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center gap-1.5 bg-emerald-400/20 border border-emerald-400/40 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold text-emerald-300">
                                                    <i class="fas fa-handshake text-emerald-400"></i> ZERO BROKERAGE
                                                </span>
                                                <span class="inline-flex items-center gap-1 bg-white/10 border border-white/20 backdrop-blur-sm px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold text-white/80">
                                                    <i class="fas fa-users text-emerald-300 text-[10px]"></i> Direct Connect
                                                </span>
                                            </div>
                                            <h2 class="text-xl sm:text-3xl md:text-4xl font-black leading-tight mb-1.5 text-white">
                                                Find Flatmates & Roommates <span style="color:#34d399">| 100% Direct</span>
                                            </h2>
                                            <p class="text-xs sm:text-sm text-white/70 mb-3 max-w-md line-clamp-2">Connect with verified flatmates, shared flats & vacant rooms with zero brokerage fee.</p>
                                            <div class="flex flex-wrap gap-2.5">
                                                <a href="{{ route('user.roommate.index') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition shadow-lg shadow-emerald-500/30">
                                                    <i class="fas fa-search"></i> Find Flatmates
                                                </a>
                                                <a href="{{ route('user.roommate.create') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs sm:text-sm tap-ripple transition">
                                                    Post Free Listing
                                                </a>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex flex-col gap-2 flex-shrink-0 mr-0 sm:mr-4 md:mr-6">
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center min-w-[120px]">
                                                <p class="text-xl sm:text-2xl font-black text-white">500+</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Active Flatmates</p>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-2.5 text-center">
                                                <p class="text-xl sm:text-2xl font-black text-emerald-300">₹0</p>
                                                <p class="text-[10px] sm:text-[11px] text-white/70 font-medium">Brokerage Fee</p>
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

    {{-- ============================= PROPERTY TYPE SWITCHER CARDS (OPTION 3) ============================= --}}
    <section class="mt-3 sm:mt-6">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-4 gap-1.5 sm:gap-3 md:gap-4 max-w-4xl sm:max-w-5xl mx-auto" id="propertyTypeSwitcherGrid">
                
                <!-- Card 1: PG & Hostels (Main Priority) -->
                <button type="button" onclick="switchPropertyType('pg-hostel', false)" id="card-type-pg-hostel" 
                    class="property-type-card {{ $selectedType === 'pg-hostel' ? 'active-pg' : '' }} rounded-xl sm:rounded-2xl p-2 sm:p-3.5 md:p-4 text-center cursor-pointer tap-ripple flex flex-col items-center justify-center group overflow-hidden">
                    <div class="w-8 h-8 sm:w-12 md:w-14 sm:h-12 md:h-14 flex items-center justify-center mb-1 sm:mb-1.5 shrink-0">
                        <svg class="w-7 h-7 sm:w-11 md:w-13 sm:h-11 md:h-13 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Left taller green building -->
                            <rect x="11" y="14" width="22" height="42" rx="3" fill="#4bb59d" stroke="#134e48" stroke-width="2"/>
                            <rect x="15.5" y="19" width="4.5" height="5.5" rx="1" fill="#e6f7f3" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="24" y="19" width="4.5" height="5.5" rx="1" fill="#e6f7f3" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="15.5" y="28" width="4.5" height="5.5" rx="1" fill="#e6f7f3" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="24" y="28" width="4.5" height="5.5" rx="1" fill="#e6f7f3" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="15.5" y="37" width="4.5" height="5.5" rx="1" fill="#e6f7f3" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="24" y="37" width="4.5" height="5.5" rx="1" fill="#e6f7f3" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="19" y="47" width="6" height="9" rx="1" fill="#134e48"/>
                            <!-- Right building with pitched roof -->
                            <path d="M30 25L45 15L54 25V56H30V25Z" fill="#ffffff" stroke="#134e48" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M28 25L45 13L56 25" stroke="#134e48" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                            <path d="M30 25L45 15L54 25" fill="#34d399"/>
                            <rect x="36" y="29" width="5" height="6" rx="1" fill="#a7f3d0" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="45" y="29" width="5" height="6" rx="1" fill="#a7f3d0" stroke="#134e48" stroke-width="1.2"/>
                            <rect x="40" y="42" width="7" height="14" rx="1" fill="#4bb59d" stroke="#134e48" stroke-width="1.3"/>
                            <circle cx="8" cy="51" r="3.5" fill="#10b981"/>
                            <circle cx="56" cy="49" r="4" fill="#10b981"/>
                            <line x1="6" y1="56" x2="58" y2="56" stroke="#134e48" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <p class="font-extrabold text-[10px] sm:text-xs md:text-sm text-slate-900 leading-tight truncate w-full">PG & Hostels</p>
                    <p class="text-[8px] sm:text-[10px] md:text-xs text-slate-500 font-semibold mt-0.5 truncate w-full">{{ $propertyTypeCounts['pg'] > 0 ? $propertyTypeCounts['pg'] . '+ Stays' : '850+ Stays' }}</p>
                </button>

                <!-- Card 2: Flats & Houses -->
                <button type="button" onclick="switchPropertyType('flat-apartment', false)" id="card-type-flat-apartment" 
                    class="property-type-card {{ $selectedType === 'flat-apartment' ? 'active-flat' : '' }} rounded-xl sm:rounded-2xl p-2 sm:p-3.5 md:p-4 text-center cursor-pointer tap-ripple flex flex-col items-center justify-center group overflow-hidden">
                    <div class="w-8 h-8 sm:w-12 md:w-14 sm:h-12 md:h-14 flex items-center justify-center mb-1 sm:mb-1.5 shrink-0">
                        <svg class="w-7 h-7 sm:w-11 md:w-13 sm:h-11 md:h-13 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Left tall building -->
                            <rect x="13" y="12" width="22" height="44" rx="3" fill="#ffffff" stroke="#1e1b4b" stroke-width="2"/>
                            <rect x="17.5" y="16.5" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="25.5" y="16.5" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="17.5" y="24" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="25.5" y="24" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="17.5" y="31.5" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="25.5" y="31.5" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="17.5" y="39" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="25.5" y="39" width="5" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="21" y="48.5" width="6" height="7.5" rx="1" fill="#312e81"/>
                            <!-- Right building -->
                            <rect x="33" y="22" width="18" height="34" rx="2" fill="#e0e7ff" stroke="#1e1b4b" stroke-width="2"/>
                            <rect x="37" y="26.5" width="4" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="43.5" y="26.5" width="4" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="37" y="34" width="4" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="43.5" y="34" width="4" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="37" y="41.5" width="4" height="4.5" rx="1" fill="#6366f1"/>
                            <rect x="43.5" y="41.5" width="4" height="4.5" rx="1" fill="#6366f1"/>
                            <circle cx="9" cy="50" r="3.5" fill="#34d399"/>
                            <circle cx="55" cy="48" r="4" fill="#34d399"/>
                            <line x1="7" y1="56" x2="57" y2="56" stroke="#1e1b4b" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <p class="font-extrabold text-[10px] sm:text-xs md:text-sm text-slate-900 leading-tight truncate w-full">Flats & Houses</p>
                    <p class="text-[8px] sm:text-[10px] md:text-xs text-slate-500 font-semibold mt-0.5 truncate w-full">{{ $propertyTypeCounts['flat'] > 0 ? $propertyTypeCounts['flat'] . '+ Flats' : '0+ Flats' }}</p>
                </button>

                <!-- Card 3: Commercial -->
                <button type="button" onclick="switchPropertyType('commercial', false)" id="card-type-commercial" 
                    class="property-type-card {{ $selectedType === 'commercial' ? 'active-commercial' : '' }} rounded-xl sm:rounded-2xl p-2 sm:p-3.5 md:p-4 text-center cursor-pointer tap-ripple flex flex-col items-center justify-center group overflow-hidden">
                    <div class="w-8 h-8 sm:w-12 md:w-14 sm:h-12 md:h-14 flex items-center justify-center mb-1 sm:mb-1.5 shrink-0">
                        <svg class="w-7 h-7 sm:w-11 md:w-13 sm:h-11 md:h-13 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="11" y="22" width="42" height="34" rx="2" fill="#ffffff" stroke="#1e293b" stroke-width="2"/>
                            <rect x="9" y="16" width="46" height="7" rx="2" fill="#1e3a8a" stroke="#1e293b" stroke-width="1.8"/>
                            <path d="M10 23L13 32H51L54 23H10Z" fill="#3b82f6" stroke="#1e293b" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M19 23L18 32M28 23L27 32M37 23L37 32M46 23L47 32" stroke="#ffffff" stroke-width="2"/>
                            <path d="M13 32Q15.5 35 18 32Q20.5 35 23 32Q25.5 35 28 32Q30.5 35 33 32Q35.5 35 38 32Q40.5 35 43 32Q45.5 35 48 32Q50.5 35 51 32" fill="#60a5fa" stroke="#1e293b" stroke-width="1.5"/>
                            <rect x="16" y="37" width="10" height="12" rx="1" fill="#dbeafe" stroke="#1e293b" stroke-width="1.2"/>
                            <line x1="21" y1="37" x2="21" y2="49" stroke="#93c5fd" stroke-width="1"/>
                            <rect x="29" y="36" width="10" height="20" rx="1" fill="#1d4ed8" stroke="#1e293b" stroke-width="1.2"/>
                            <rect x="31" y="38" width="6" height="9" fill="#bfdbfe"/>
                            <circle cx="37" cy="48" r="0.9" fill="#ffffff"/>
                            <rect x="42" y="37" width="8" height="12" rx="1" fill="#dbeafe" stroke="#1e293b" stroke-width="1.2"/>
                            <circle cx="7" cy="50" r="3" fill="#22c55e"/>
                            <circle cx="57" cy="50" r="3" fill="#22c55e"/>
                            <line x1="5" y1="56" x2="59" y2="56" stroke="#1e293b" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <p class="font-extrabold text-[10px] sm:text-xs md:text-sm text-slate-900 leading-tight truncate w-full">Commercial</p>
                    <p class="text-[8px] sm:text-[10px] md:text-xs text-slate-500 font-semibold mt-0.5 truncate w-full">{{ $propertyTypeCounts['commercial'] > 0 ? $propertyTypeCounts['commercial'] . '+ Spaces' : '120+ Spaces' }}</p>
                </button>

                <!-- Card 4: Roommates & Flatmates (Theme #4c30dd) -->
                <button type="button" onclick="switchPropertyType('roommate', false)" id="card-type-roommate" 
                    class="property-type-card {{ $selectedType === 'roommate' ? 'active-roommate' : '' }} rounded-xl sm:rounded-2xl p-2 sm:p-3.5 md:p-4 text-center cursor-pointer tap-ripple flex flex-col items-center justify-center group overflow-hidden">
                    <div class="w-8 h-8 sm:w-12 md:w-14 sm:h-12 md:h-14 flex items-center justify-center mb-1 sm:mb-1.5 shrink-0">
                        <svg class="w-7 h-7 sm:w-11 md:w-13 sm:h-11 md:h-13 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background soft circle in #4c30dd tone -->
                            <circle cx="32" cy="32" r="26" fill="#f3f0ff" stroke="#4c30dd" stroke-width="1.8"/>
                            <!-- Left Avatar Figure -->
                            <circle cx="23" cy="22" r="7" fill="#4c30dd"/>
                            <path d="M12 45C12 36.7157 16.9249 32 23 32C29.0751 32 34 36.7157 34 45V48H12V45Z" fill="#6d4aff"/>
                            <!-- Right Avatar Figure -->
                            <circle cx="41" cy="22" r="7" fill="#2e1b8b"/>
                            <path d="M30 45C30 36.7157 34.9249 32 41 32C47.0751 32 52 36.7157 52 45V48H30V45Z" fill="#4c30dd"/>
                            <!-- Connected Handshake / Heart badge -->
                            <circle cx="32" cy="33" r="5" fill="#ffffff" stroke="#4c30dd" stroke-width="1.5"/>
                            <path d="M32 31.5C31.5 30.5 30 30.5 29.5 31.5C29 32.5 32 35 32 35C32 35 35 32.5 34.5 31.5C34 30.5 32.5 30.5 32 31.5Z" fill="#4c30dd"/>
                        </svg>
                    </div>
                    <p class="font-extrabold text-[10px] sm:text-xs md:text-sm text-slate-900 leading-tight truncate w-full">Roommates</p>
                    <p class="text-[8px] sm:text-[10px] md:text-xs text-slate-500 font-semibold mt-0.5 truncate w-full">{{ ($propertyTypeCounts['roommate'] ?? 0) > 0 ? ($propertyTypeCounts['roommate']) . '+ Flatmates' : '10+ Flatmates' }}</p>
                </button>
            </div>
        </div>
    </section>

    <!-- Home Property Switch Skeleton Shimmer Placeholder (Spacefind Style) -->
    <div id="homeSwitchSkeleton" class="hidden mt-8 sm:mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-in fade-in duration-200">
        <div class="mb-4 flex justify-between items-center">
            <div class="space-y-2">
                <div class="h-6 w-48 bg-gray-200 rounded-lg skeleton-shimmer"></div>
                <div class="h-3 w-64 bg-gray-100 rounded-md skeleton-shimmer"></div>
            </div>
            <div class="h-7 w-20 bg-gray-100 rounded-full skeleton-shimmer"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-6">
            @for($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 flex flex-col justify-between">
                <div class="h-28 sm:h-44 w-full skeleton-shimmer"></div>
                <div class="p-2.5 sm:p-4 space-y-2">
                    <div class="h-4 w-3/4 bg-gray-200 rounded-md skeleton-shimmer"></div>
                    <div class="h-3 w-1/2 bg-gray-100 rounded-md skeleton-shimmer"></div>
                    <div class="h-5 w-20 bg-gray-200 rounded-md skeleton-shimmer pt-1"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    {{-- ============================= PG / HOSTEL VIEW CONTAINER (DEFAULT PRIORITY) ============================= --}}
    <div id="pgViewContainer" class="{{ $selectedType === 'pg-hostel' ? '' : 'hidden' }}">

    {{-- ============================= 2. PG NEAR ME ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 inline-flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-location-crosshairs text-blue-600 text-sm"></i>
                        </span>
                        PG Near You
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
                                <div class="relative h-28 sm:h-36 overflow-hidden bg-gray-100 skeleton-shimmer">
                                    <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                                    @if($pg->is_sale)
                                        <div class="absolute top-2 left-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                            <i class="fas fa-tag text-[8px]"></i> For Sale
                                        </div>
                                    @elseif($pg->is_fully_booked || ((int)$pg->available_beds === 0 && $pg->available_beds !== null))
                                        <div class="absolute top-2 left-2 bg-rose-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                            <i class="fas fa-lock text-[8px]"></i> Full Booked
                                        </div>
                                    @else
                                        <div class="absolute top-2 left-2 {{ $tagMeta['solid_badge'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                            <i class="fas fa-{{ $tagMeta['icon'] }} text-[8px]"></i> {{ $tagMeta['label'] }}
                                        </div>
                                    @endif
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
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
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
                        <div class="relative h-44 overflow-hidden bg-gray-100 skeleton-shimmer">
                            <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                            @if($pg->is_sale)
                                <div class="absolute top-2.5 left-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                    <i class="fas fa-tag text-[9px]"></i> For Sale
                                </div>
                            @elseif($pg->is_fully_booked || ((int)$pg->available_beds === 0 && $pg->available_beds !== null))
                                <div class="absolute top-2.5 left-2.5 bg-rose-600 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                    <i class="fas fa-lock text-[9px]"></i> Full Booked
                                </div>
                            @else
                                <div class="absolute top-2.5 left-2.5 {{ $tagMeta['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-{{ $tagMeta['icon'] }} text-[9px]"></i> {{ $tagMeta['label'] }}
                                </div>
                            @endif
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
                                    <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                    <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
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
    @if($recommendedProperties->isNotEmpty())
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
                    @foreach ($recommendedProperties as $pg)
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
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
                                        </div>
                                        <span class="bg-brand hover:bg-brand-dark text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-brand/30 transition">View</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== DESKTOP: 4-Col Grid ===== --}}
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($recommendedProperties as $pg)
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
                                    <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                    <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
                                </div>
                                <span class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-brand/30 transition">View</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
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
                                    <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                    <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
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
                                            <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                            <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
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
                                    <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                    <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
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
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                    <span class="text-xs sm:text-base font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span>@endif</span>
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

    </div> {{-- ============================= END PG / HOSTEL VIEW CONTAINER ============================= --}}

    {{-- ============================= FLAT / HOUSE VIEW CONTAINER ============================= --}}
    <div id="flatViewContainer" class="{{ $selectedType === 'flat-apartment' ? '' : 'hidden' }}">
        <section class="mt-8 sm:mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="section-title flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-indigo-50 inline-flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-building text-indigo-600 text-sm"></i>
                            </span>
                            Flats & Houses Near You
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 ml-10">100% Verified 1BHK, 2BHK, 3BHK & Full Houses with Zero Brokerage</p>
                    </div>
                    <a href="{{ route('user.search') }}?type=flat-apartment" class="text-xs font-bold text-indigo-600 flex items-center gap-1.5 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-1.5 rounded-full tap-ripple transition">
                        See all Flats <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if($flatProperties->isNotEmpty())
                    {{-- ===== MOBILE: 2-Column Horizontal Slider (Max 8) ===== --}}
                    <div id="flatNearMeMobileContainer" class="md:hidden swiper flatNearMeSwiper overflow-hidden">
                        <div class="swiper-wrapper" id="flatNearMeSwiperWrapper">
                            @foreach ($flatNearMe as $pg)
                                @php
                                    $tagMeta = $pg->display_tag_meta;
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
                                            @if($pg->is_sale)
                                                <div class="absolute top-2 left-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                                    <i class="fas fa-tag text-[8px]"></i> For Sale
                                                </div>
                                            @else
                                                <div class="absolute top-2 left-2 bg-indigo-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                                    <i class="fas fa-building text-[8px]"></i> Flat
                                                </div>
                                            @endif
                                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ $pg->display_price_formatted }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Flat', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
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
                                                    @if($pg->is_sale)
                                                        <span class="bg-amber-50 text-amber-700 text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">For Sale</span>
                                                    @else
                                                        <span class="bg-indigo-50 text-indigo-700 text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">Flat</span>
                                                    @endif
                                                </div>
                                                <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                                    <i class="fas fa-map-marker-alt text-indigo-600 text-[9px]"></i> {{ $locationText }}
                                                </p>
                                                <p class="text-[10px] text-indigo-600 font-semibold mb-2 flex items-center gap-1 truncate pg-distance-badge" data-lat="{{ $pg->map_latitude }}" data-lng="{{ $pg->map_longitude }}">
                                                    <i class="fas fa-location-dot text-[9px]"></i>
                                                    <span class="dist-text">Calculating...</span>
                                                </p>
                                                <div class="flex flex-wrap gap-1 mb-2">
                                                    @forelse($pg->amenities->take(2) as $am)
                                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                            <i class="fas fa-{{ $am->icon ?? 'check' }} text-indigo-600 text-[8px]"></i> {{ $am->name }}
                                                        </span>
                                                    @empty
                                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                            <i class="fas fa-car text-indigo-600 text-[8px]"></i> Parking
                                                        </span>
                                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                            <i class="fas fa-bolt text-indigo-600 text-[8px]"></i> Power Backup
                                                        </span>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                                <div>
                                                    <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                                    <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
                                                </div>
                                                <span class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-indigo-500/30 transition">View</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ===== DESKTOP: 4-Col Grid (Max 8) ===== --}}
                    <div id="flatNearMeDesktopGrid" class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($flatNearMe as $pg)
                            @php
                                $tagMeta = $pg->display_tag_meta;
                                $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                                $displayImg = $pg->display_image_url;
                                $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                                $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                                $reviewCount = $pg->dynamic_reviews_count;
                            @endphp
                            <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                                <div class="relative h-44 overflow-hidden">
                                    <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @if($pg->is_sale)
                                        <div class="absolute top-2.5 left-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                            <i class="fas fa-tag text-[9px]"></i> For Sale
                                        </div>
                                    @else
                                        <div class="absolute top-2.5 left-2.5 bg-indigo-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                            <i class="fas fa-building text-[9px]"></i> Flat / Apartment
                                        </div>
                                    @endif
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ $pg->display_price_formatted }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Flat', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                        <i class="far fa-heart text-xs"></i>
                                    </button>
                                    <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $ratingVal }}
                                        @if($reviewCount > 0)
                                            <span class="text-gray-300 font-normal">({{ $reviewCount }})</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col flex-1 justify-between">
                                    <div>
                                        <div class="flex justify-between items-center gap-1 mb-1">
                                            <span class="font-bold text-base text-gray-900 truncate">{{ $pg->name }}</span>
                                            @if($pg->is_sale)
                                                <span class="bg-amber-50 text-amber-700 text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">For Sale</span>
                                            @else
                                                <span class="bg-indigo-50 text-indigo-700 text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">Verified Flat</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                            <i class="fas fa-map-marker-alt text-indigo-600 text-[11px]"></i> {{ $locationText }}
                                        </p>
                                        <p class="text-xs text-indigo-600 font-semibold mb-3 flex items-center gap-1 truncate pg-distance-badge" data-lat="{{ $pg->map_latitude }}" data-lng="{{ $pg->map_longitude }}">
                                            <i class="fas fa-location-dot text-[11px]"></i>
                                            <span class="dist-text">Calculating...</span>
                                        </p>
                                        <div class="flex flex-wrap gap-1.5 mb-4">
                                            @forelse($pg->amenities->take(3) as $am)
                                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                    <i class="fas fa-{{ $am->icon ?? 'check' }} text-indigo-600 text-[10px]"></i> {{ $am->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                    <i class="fas fa-car text-indigo-600 text-[10px]"></i> Parking
                                                </span>
                                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                    <i class="fas fa-bolt text-indigo-600 text-[10px]"></i> Power Backup
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                        <div>
                                            <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                            <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
                                        </div>
                                        <span class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-indigo-500/30 transition">View</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3xl p-8 sm:p-12 text-center bg-gradient-to-br from-indigo-50/70 via-white to-white border border-indigo-100 shadow-sm max-w-3xl mx-auto my-4">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                            <i class="fas fa-key"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Verified Flats & Houses Coming Soon!</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-6 max-w-md mx-auto">We are onboarding 100% verified 1BHK, 2BHK, 3BHK apartments and independent houses with zero brokerage across top residential hubs.</p>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('user.search') }}?type=flat-apartment" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-500/25 transition tap-ripple">
                                <i class="fas fa-search mr-1.5"></i> Search All Flats
                            </a>
                            <a href="{{ route('user.list-property') }}" class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-xs transition tap-ripple">
                                <i class="fas fa-plus-circle text-indigo-600 mr-1.5"></i> List Your Flat Free
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- ============================= FLAT RECOMMENDED FOR YOU ============================= --}}
        @if($flatRecommended->isNotEmpty())
        <section class="mt-8 sm:mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="section-title flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-indigo-50 inline-flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-heart text-indigo-600 text-sm"></i>
                            </span>
                            Recommended for You
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 ml-10">Handpicked top-rated apartments & houses with verified amenities</p>
                    </div>
                    <a href="{{ route('user.search') }}?type=flat-apartment" class="text-xs font-bold text-indigo-600 flex items-center gap-1.5 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-1.5 rounded-full tap-ripple transition">
                        View all <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
                <div class="md:hidden swiper flatRecommendedSwiper overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach ($flatRecommended as $pg)
                            @php
                                $tagMeta = $pg->display_tag_meta;
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
                                        <div class="absolute top-2 left-2 bg-indigo-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                            <i class="fas fa-{{ $tagMeta['icon'] ?? 'building' }} text-[8px]"></i> {{ $tagMeta['label'] !== 'No Tag' ? $tagMeta['label'] : 'Flat' }}
                                        </div>
                                        <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Flat', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
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
                                                <span class="bg-indigo-50 text-indigo-700 text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">Flat</span>
                                            </div>
                                            <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                                <i class="fas fa-map-marker-alt text-indigo-600 text-[9px]"></i> {{ $locationText }}
                                            </p>
                                            <p class="text-[10px] text-indigo-600 font-semibold mb-2 flex items-center gap-1 truncate">
                                                <i class="fas fa-circle-check text-[9px]"></i> {{ $pg->landmark ?? 'Top Rated Flat' }}
                                            </p>
                                            <div class="flex flex-wrap gap-1 mb-2">
                                                @forelse($pg->amenities->take(2) as $am)
                                                    <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                        <i class="fas fa-{{ $am->icon ?? 'check' }} text-indigo-600 text-[8px]"></i> {{ $am->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                        <i class="fas fa-car text-indigo-600 text-[8px]"></i> Parking
                                                    </span>
                                                    <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                        <i class="fas fa-bolt text-indigo-600 text-[8px]"></i> Power Backup
                                                    </span>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                            <div>
                                                <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                                <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
                                            </div>
                                            <span class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-indigo-500/30 transition">View</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ===== DESKTOP: 4-Col Grid ===== --}}
                <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($flatRecommended as $pg)
                        @php
                            $tagMeta = $pg->display_tag_meta;
                            $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                            $displayImg = $pg->display_image_url;
                            $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                            $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                            $reviewCount = $pg->dynamic_reviews_count;
                        @endphp
                        <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-2.5 left-2.5 bg-indigo-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-{{ $tagMeta['icon'] ?? 'building' }} text-[9px]"></i> {{ $tagMeta['label'] !== 'No Tag' ? $tagMeta['label'] : 'Flat / House' }}
                                </div>
                                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Flat', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
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
                                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">Verified Flat</span>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                        <i class="fas fa-map-marker-alt text-indigo-600 text-[11px]"></i> {{ $locationText }}
                                    </p>
                                    <p class="text-xs text-indigo-600 font-semibold mb-3 flex items-center gap-1 truncate">
                                        <i class="fas fa-circle-check text-[11px]"></i> {{ $pg->landmark ?? 'Verified Stay' }}
                                    </p>
                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        @forelse($pg->amenities->take(3) as $am)
                                            <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                <i class="fas fa-{{ $am->icon ?? 'check' }} text-indigo-600 text-[10px]"></i> {{ $am->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                <i class="fas fa-car text-indigo-600 text-[10px]"></i> Parking
                                            </span>
                                            <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                <i class="fas fa-bolt text-indigo-600 text-[10px]"></i> Power Backup
                                            </span>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                        <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
                                    </div>
                                    <span class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-indigo-500/30 transition">View</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </div> {{-- ============================= END FLAT / HOUSE VIEW CONTAINER ============================= --}}

    {{-- ============================= COMMERCIAL VIEW CONTAINER ============================= --}}
    <div id="commercialViewContainer" class="{{ $selectedType === 'commercial' ? '' : 'hidden' }}">
        <section class="mt-8 sm:mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="section-title flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-amber-50 inline-flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-store text-amber-600 text-sm"></i>
                            </span>
                            Commercial Spaces Near You
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 ml-10">Prime Retail Shops, Showrooms & Modern Office Spaces with Zero Brokerage</p>
                    </div>
                    <a href="{{ route('user.search') }}?type=commercial" class="text-xs font-bold text-amber-600 flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 px-3.5 py-1.5 rounded-full tap-ripple transition">
                        See all Commercial <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if($commercialProperties->isNotEmpty())
                    {{-- ===== MOBILE: 2-Column Horizontal Slider (Max 8) ===== --}}
                    <div id="commercialNearMeMobileContainer" class="md:hidden swiper commercialNearMeSwiper overflow-hidden">
                        <div class="swiper-wrapper" id="commercialNearMeSwiperWrapper">
                            @foreach ($commercialNearMe as $pg)
                                @php
                                    $tagMeta = $pg->display_tag_meta;
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
                                            <div class="absolute top-2 left-2 bg-amber-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                                <i class="fas fa-store text-[8px]"></i> Shop
                                            </div>
                                            <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ $pg->display_price_formatted }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Commercial', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
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
                                                    <span class="bg-amber-50 text-amber-800 text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">Commercial</span>
                                                </div>
                                                <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                                    <i class="fas fa-map-marker-alt text-amber-600 text-[9px]"></i> {{ $locationText }}
                                                </p>
                                                <p class="text-[10px] text-amber-700 font-semibold mb-2 flex items-center gap-1 truncate pg-distance-badge" data-lat="{{ $pg->map_latitude }}" data-lng="{{ $pg->map_longitude }}">
                                                    <i class="fas fa-location-dot text-[9px]"></i>
                                                    <span class="dist-text">Calculating...</span>
                                                </p>
                                                <div class="flex flex-wrap gap-1 mb-2">
                                                    @forelse($pg->amenities->take(2) as $am)
                                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                            <i class="fas fa-{{ $am->icon ?? 'check' }} text-amber-600 text-[8px]"></i> {{ $am->name }}
                                                        </span>
                                                    @empty
                                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                            <i class="fas fa-shield-alt text-amber-600 text-[8px]"></i> 24/7 Security
                                                        </span>
                                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                            <i class="fas fa-bolt text-amber-600 text-[8px]"></i> Power Backup
                                                        </span>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                                <div>
                                                    <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                                    <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
                                                </div>
                                                <span class="bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-amber-500/30 transition">View</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ===== DESKTOP: 4-Col Grid (Max 8) ===== --}}
                    <div id="commercialNearMeDesktopGrid" class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($commercialNearMe as $pg)
                            @php
                                $tagMeta = $pg->display_tag_meta;
                                $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                                $displayImg = $pg->display_image_url;
                                $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                                $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                                $reviewCount = $pg->dynamic_reviews_count;
                            @endphp
                            <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                                <div class="relative h-44 overflow-hidden">
                                    <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @if($pg->is_sale)
                                        <div class="absolute top-2.5 left-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                            <i class="fas fa-tag text-[9px]"></i> For Sale
                                        </div>
                                    @else
                                        <div class="absolute top-2.5 left-2.5 bg-amber-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                            <i class="fas fa-store text-[9px]"></i> Commercial
                                        </div>
                                    @endif
                                    <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ $pg->display_price_formatted }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Commercial', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                                        <i class="far fa-heart text-xs"></i>
                                    </button>
                                    <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $ratingVal }}
                                        @if($reviewCount > 0)
                                            <span class="text-gray-300 font-normal">({{ $reviewCount }})</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col flex-1 justify-between">
                                    <div>
                                        <div class="flex justify-between items-center gap-1 mb-1">
                                            <span class="font-bold text-base text-gray-900 truncate">{{ $pg->name }}</span>
                                            @if($pg->is_sale)
                                                <span class="bg-amber-50 text-amber-700 text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">For Sale</span>
                                            @else
                                                <span class="bg-amber-50 text-amber-700 text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">Verified Commercial</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                            <i class="fas fa-map-marker-alt text-amber-600 text-[11px]"></i> {{ $locationText }}
                                        </p>
                                        <p class="text-xs text-amber-700 font-semibold mb-3 flex items-center gap-1 truncate pg-distance-badge" data-lat="{{ $pg->map_latitude }}" data-lng="{{ $pg->map_longitude }}">
                                            <i class="fas fa-location-dot text-[11px]"></i>
                                            <span class="dist-text">Calculating...</span>
                                        </p>
                                        <div class="flex flex-wrap gap-1.5 mb-4">
                                            @forelse($pg->amenities->take(3) as $am)
                                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                    <i class="fas fa-{{ $am->icon ?? 'check' }} text-amber-600 text-[10px]"></i> {{ $am->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                    <i class="fas fa-shield-alt text-amber-600 text-[10px]"></i> 24/7 Security
                                                </span>
                                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                    <i class="fas fa-bolt text-amber-600 text-[10px]"></i> Power Backup
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                        <div>
                                            <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                            <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
                                        </div>
                                        <span class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-amber-500/30 transition">View</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3xl p-8 sm:p-12 text-center bg-gradient-to-br from-amber-50/70 via-white to-white border border-amber-100 shadow-sm max-w-3xl mx-auto my-4">
                        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                            <i class="fas fa-store"></i>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Prime Commercial Spaces</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-6 max-w-md mx-auto">Discover high-footfall retail shops, showrooms, warehouses, and modern office spaces with zero brokerage and direct owner contact.</p>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('user.search') }}?type=commercial" class="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/25 transition tap-ripple">
                                <i class="fas fa-search mr-1.5"></i> Explore All Commercial
                            </a>
                            <a href="{{ route('user.list-property') }}" class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl shadow-xs transition tap-ripple">
                                <i class="fas fa-plus-circle text-amber-600 mr-1.5"></i> List Commercial Space Free
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- ============================= COMMERCIAL RECOMMENDED FOR YOU ============================= --}}
        @if($commercialRecommended->isNotEmpty())
        <section class="mt-8 sm:mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="section-title flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-amber-50 inline-flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-heart text-amber-600 text-sm"></i>
                            </span>
                            Recommended for You
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 ml-10">Handpicked prime shops, showrooms & modern office spaces</p>
                    </div>
                    <a href="{{ route('user.search') }}?type=commercial" class="text-xs font-bold text-amber-600 flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 px-3.5 py-1.5 rounded-full tap-ripple transition">
                        View all <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
                <div class="md:hidden swiper commercialRecommendedSwiper overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach ($commercialRecommended as $pg)
                            @php
                                $tagMeta = $pg->display_tag_meta;
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
                                        <div class="absolute top-2 left-2 bg-amber-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                            <i class="fas fa-{{ $tagMeta['icon'] ?? 'store' }} text-[8px]"></i> {{ $tagMeta['label'] !== 'No Tag' ? $tagMeta['label'] : 'Shop' }}
                                        </div>
                                        <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Commercial', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
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
                                                <span class="bg-amber-50 text-amber-800 text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">Commercial</span>
                                            </div>
                                            <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                                <i class="fas fa-map-marker-alt text-amber-600 text-[9px]"></i> {{ $locationText }}
                                            </p>
                                            <p class="text-[10px] text-amber-700 font-semibold mb-2 flex items-center gap-1 truncate">
                                                <i class="fas fa-circle-check text-[9px]"></i> {{ $pg->landmark ?? 'Prime Space' }}
                                            </p>
                                            <div class="flex flex-wrap gap-1 mb-2">
                                                @forelse($pg->amenities->take(2) as $am)
                                                    <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                        <i class="fas fa-{{ $am->icon ?? 'check' }} text-amber-600 text-[8px]"></i> {{ $am->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                        <i class="fas fa-shield-alt text-amber-600 text-[8px]"></i> Security
                                                    </span>
                                                    <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                                        <i class="fas fa-bolt text-amber-600 text-[8px]"></i> Power Backup
                                                    </span>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                            <div>
                                                <span class="text-[9px] text-gray-400 block font-medium leading-none">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                                <span class="text-xs font-black text-gray-900 leading-tight">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-[9px] font-normal text-gray-500">/m</span>@endif</span>
                                            </div>
                                            <span class="bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-amber-500/30 transition">View</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ===== DESKTOP: 4-Col Grid ===== --}}
                <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($commercialRecommended as $pg)
                        @php
                            $tagMeta = $pg->display_tag_meta;
                            $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                            $displayImg = $pg->display_image_url;
                            $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($pg->city ? $pg->city->name : 'City Center');
                            $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                            $reviewCount = $pg->dynamic_reviews_count;
                        @endphp
                        <a href="{{ $slugUrl }}" class="pg-card tap-ripple" data-property-id="{{ $pg->id }}">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $displayImg }}" alt="{{ $pg->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-2.5 left-2.5 bg-amber-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-{{ $tagMeta['icon'] ?? 'store' }} text-[9px]"></i> {{ $tagMeta['label'] !== 'No Tag' ? $tagMeta['label'] : 'Commercial' }}
                                </div>
                                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: 'Commercial', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
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
                                        <span class="bg-amber-50 text-amber-800 text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">Commercial</span>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                        <i class="fas fa-map-marker-alt text-amber-600 text-[11px]"></i> {{ $locationText }}
                                    </p>
                                    <p class="text-xs text-amber-700 font-semibold mb-3 flex items-center gap-1 truncate">
                                        <i class="fas fa-circle-check text-[11px]"></i> {{ $pg->landmark ?? 'Prime Space' }}
                                    </p>
                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        @forelse($pg->amenities->take(3) as $am)
                                            <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                <i class="fas fa-{{ $am->icon ?? 'check' }} text-amber-600 text-[10px]"></i> {{ $am->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                <i class="fas fa-shield-alt text-amber-600 text-[10px]"></i> Security
                                            </span>
                                            <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                                <i class="fas fa-bolt text-amber-600 text-[10px]"></i> Power Backup
                                            </span>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-[10px] text-gray-400 block font-medium">{{ $pg->is_sale ? 'Price' : 'Rent' }}</span>
                                        <span class="text-base font-black text-gray-900">{{ $pg->display_price_formatted }}@if(!$pg->is_sale)<span class="text-xs font-normal text-gray-500">/mo</span>@endif</span>
                                    </div>
                                    <span class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-amber-500/30 transition">View</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </div> {{-- ============================= END COMMERCIAL VIEW CONTAINER ============================= --}}

    {{-- ============================= ROOMMATES / FLATMATES VIEW CONTAINER (OPTION 3) ============================= --}}
    <div id="roommateViewContainer" class="{{ $selectedType === 'roommate' ? '' : 'hidden' }}">
        <section class="mt-8 sm:mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <div>
                        <h2 class="section-title flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-[#f3f0ff] inline-flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-[#4c30dd] text-sm"></i>
                            </span>
                            Recent Flatmates & Roommates
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 ml-10">Explore verified flatmates & shared accommodations across top cities</p>
                    </div>
                    <a href="{{ route('user.roommate.index') }}" class="text-xs font-bold text-[#4c30dd] flex items-center gap-1.5 bg-[#f3f0ff] hover:bg-[#4c30dd]/15 px-3.5 py-1.5 rounded-full tap-ripple transition">
                        See all <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                {{-- 8 Recent Roommate Listings (Responsive Grid: 2-col mobile, 4-col desktop) --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5 sm:gap-6">
                    @forelse($roommatePosts->take(8) as $r)
                        @php
                            $rPref = strtolower($r->gender_preference ?? 'any');
                            $bhkLabels = [
                                '1rk' => '1 RK Flat',
                                '1bhk' => '1 BHK Flat',
                                '2bhk' => '2 BHK Flat',
                                '3bhk' => '3 BHK Flat',
                                '4bhk_plus' => '4+ BHK Villa',
                                'shared_room' => 'Shared Room',
                                'single_room' => 'Single Room',
                            ];
                            $rBhk = $bhkLabels[$r->bhk_type] ?? (str_ends_with(strtolower($r->bhk_type), 'bhk') ? strtoupper($r->bhk_type) : ucwords(str_replace('_', ' ', $r->bhk_type)));
                            $rSlugUrl = route('user.roommate.show', $r->slug);
                            $rAvatar = $r->poster_avatar_url;
                            $rLocation = ($r->locality ? $r->locality . ', ' : '') . ($r->city ?: 'City Center');
                        @endphp
                        <div class="roommate-card overflow-hidden flex flex-col justify-between h-full group">
                            <div>
                                <!-- Top Media Banner (0 padding, full bleed, no room badge) -->
                                <div class="relative h-36 sm:h-44 w-full p-0 m-0 overflow-hidden bg-gradient-to-br from-slate-950 to-[#1b104a] flex flex-col items-center justify-center text-center {{ $rAvatar ? 'skeleton-shimmer' : '' }}">
                                    @if($rAvatar)
                                        <img src="{{ $rAvatar }}" alt="{{ $r->poster_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 block p-0 m-0" loading="lazy" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>
                                    @else
                                        <div class="relative z-10 w-14 h-14 sm:w-16 sm:h-16 bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                            <span class="text-3xl sm:text-4xl">{{ $r->gender_icon }}</span>
                                        </div>
                                    @endif

                                    <!-- Top-Right Gender Preference -->
                                    <div class="absolute top-2.5 right-2.5 {{ $rPref === 'female' ? 'bg-pink-600' : ($rPref === 'male' ? 'bg-blue-600' : 'bg-[#4c30dd]') }} text-white text-[9px] font-extrabold px-2.5 py-0.5 shadow-xs z-20" style="{{ $rPref !== 'female' && $rPref !== 'male' ? 'background-color: #4c30dd !important;' : '' }}">
                                        {{ $rPref === 'female' ? '👩 Girls Only' : ($rPref === 'male' ? '👨 Boys Only' : '🧑 Any') }}
                                    </div>

                                    <!-- Bottom-Left Move-in Date -->
                                    <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur-md text-white text-[9px] font-medium px-2 py-0.5 z-20">
                                        <i class="fas fa-calendar-day text-[#a594fd] text-[8px] mr-1"></i>
                                        <span>{{ $r->move_in_date ? $r->move_in_date->format('d M') : 'Immediate' }}</span>
                                    </div>
                                </div>

                                <!-- Content Details -->
                                <div class="p-3 sm:p-4">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <a href="{{ $rSlugUrl }}" class="font-extrabold text-xs sm:text-base text-gray-900 group-hover:text-[#4c30dd] transition truncate block no-underline">
                                            {{ $r->poster_name }}
                                        </a>
                                        <i class="fas fa-circle-check text-xs flex-shrink-0" style="color: #4c30dd !important;" title="Verified Member"></i>
                                    </div>

                                    <p class="text-[10px] sm:text-xs text-gray-500 truncate flex items-center gap-1 mb-2">
                                        <i class="fas fa-map-marker-alt text-[10px] shrink-0" style="color: #4c30dd !important;"></i>
                                        <span class="truncate">{{ $rLocation }}</span>
                                    </p>

                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-[9px] sm:text-[11px] font-extrabold px-2 py-0.5" style="background-color: #f3f0ff !important; color: #4c30dd !important; border: 1px solid rgba(76, 48, 221, 0.25) !important;">
                                            {{ $rBhk }}
                                        </span>
                                        @if($r->profession)
                                            <span class="text-[9px] sm:text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-0.5 truncate max-w-[100px]">
                                                {{ $r->profession }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer with High Visibility #4c30dd View Button -->
                            <div class="p-3 sm:p-4 pt-0 border-t border-gray-100 flex items-center justify-between gap-2 mt-auto">
                                <div>
                                    <span class="text-[8px] sm:text-[9px] text-gray-400 uppercase font-bold block">Rent Budget</span>
                                    <span class="text-xs sm:text-sm font-black text-gray-900">{{ $r->budget_range }}</span>
                                </div>
                                <a href="{{ $rSlugUrl }}" class="roommate-btn-view px-3.5 sm:px-4 py-1.5 text-[10px] sm:text-xs font-bold tap-effect flex items-center gap-1.5 no-underline">
                                    <span>View</span>
                                    <i class="fas fa-arrow-right text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-white border border-gray-100 shadow-sm my-4">
                            <div class="w-12 h-12 text-[#4c30dd] flex items-center justify-center mx-auto mb-3 text-lg" style="background-color: #f3f0ff !important;">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">Looking for a flatmate?</h3>
                            <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Be the first to post your room vacancy or flatmate requirement.</p>
                            <a href="{{ route('user.roommate.create') }}" class="roommate-btn-cta mt-4 inline-block text-xs font-bold px-5 py-2.5 shadow-md transition">
                                Post Requirement Free
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Bottom Post Requirement Banner (Sharp, High-Contrast UI/UX) -->
                <div class="mt-8 relative overflow-hidden roommate-post-banner p-6 sm:p-8 md:p-10">
                    <!-- Background ambient glow circles -->
                    <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-[#4c30dd]/25 blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-10 -bottom-10 w-56 h-56 rounded-full bg-[#4c30dd]/20 blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-[#4c30dd] text-white flex items-center justify-center text-xl sm:text-3xl shadow-lg shadow-[#4c30dd]/50 shrink-0 border border-white/20" style="background-color: #4c30dd !important;">
                                <i class="fas fa-house-user"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5" style="background-color: rgba(76, 48, 221, 0.35) !important; color: #ddd6fe !important; border: 1px solid rgba(76, 48, 221, 0.7) !important;">
                                        <i class="fas fa-bolt text-amber-300 text-[9px]"></i> 100% Free Listing
                                    </span>
                                    <span class="text-xs text-purple-200/80 font-medium hidden sm:inline">&bull; Zero Brokerage &bull; Direct Connect</span>
                                </div>
                                <h3 class="text-lg sm:text-2xl font-black text-white leading-tight">
                                    Have a vacant room or searching for a flatmate?
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                                    Publish your requirement in 60 seconds with zero brokerage. Connect directly with verified flatmates & room owners.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                            <a href="{{ route('user.roommate.create') }}" class="roommate-btn-cta w-full sm:w-auto text-center px-7 py-3.5 text-xs sm:text-sm whitespace-nowrap no-underline tap-effect flex items-center justify-center gap-2">
                                <span>Post Requirement Free</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- ============================= 7. TOP CITIES (SWIPER SLIDER DESKTOP & MOBILE) ============================= --}}
    <section class="mt-8 sm:mt-10 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4 sm:mb-5">
                <div>
                    <h2 class="section-title">Top Cities</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Explore verified stays and properties across India</p>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Swiper Navigation Arrows (Desktop & Tablet) -->
                    <div class="hidden sm:flex items-center gap-1.5 mr-1">
                        <button type="button" class="topCitiesPrevBtn w-8 h-8 rounded-full border border-gray-200 bg-white hover:bg-brand hover:text-white text-gray-600 flex items-center justify-center text-xs transition shadow-xs tap-ripple cursor-pointer" title="Previous Cities">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" class="topCitiesNextBtn w-8 h-8 rounded-full border border-gray-200 bg-white hover:bg-brand hover:text-white text-gray-600 flex items-center justify-center text-xs transition shadow-xs tap-ripple cursor-pointer" title="Next Cities">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <a href="{{ route('user.search') }}{{ $selectedType && $selectedType !== 'pg-hostel' ? '?type=' . $selectedType : '' }}" id="allCitiesHeaderLink" class="text-xs font-bold text-brand flex items-center gap-1.5 bg-brand-light hover:bg-brand/15 px-3.5 py-1.5 rounded-full tap-ripple transition">
                        All Cities <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Top Cities Swiper Slider -->
            <div class="swiper topCitiesSwiper overflow-hidden !py-1">
                <div class="swiper-wrapper">
                    @forelse ($topCities as $c)
                        @php
                            $citySlug = strtolower($c->slug ?: \Illuminate\Support\Str::slug($c->name));
                            $citySearchUrl = ($selectedType === 'pg-hostel' || empty($selectedType))
                                ? route('user.seo.city-area', ['city' => $citySlug])
                                : route('user.search') . '?city=' . urlencode($c->name) . ($selectedType ? '&type=' . $selectedType : '');
                        @endphp
                        <div class="swiper-slide !h-auto">
                            <a href="{{ $citySearchUrl }}" data-city-name="{{ $c->name }}" class="city-card-link group relative rounded-2xl sm:rounded-3xl overflow-hidden aspect-[3/4] sm:aspect-[3/4.2] min-h-[190px] sm:min-h-[230px] tap-ripple shadow-sm hover:shadow-2xl hover:shadow-brand/25 border border-gray-100 hover:border-brand/40 hover:-translate-y-1.5 transition-all duration-300 flex items-center justify-center p-3 sm:p-4 text-center w-full">
                                <!-- Background Image -->
                                <img src="{{ $c->display_image_url }}" alt="{{ $c->name }} - Verified PGs & Stays" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&w=800&q=80';" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                                
                                <!-- Multi-stop Gradient Vignette for Center Text Legibility -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-slate-950/60 group-hover:via-slate-950/50 transition-colors duration-300"></div>

                                <!-- Top Floating Arrow Indicator -->
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-[10px] sm:text-xs opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all duration-300 shadow-sm">
                                        <i class="fas fa-arrow-up-right text-[10px]"></i>
                                    </span>
                                </div>

                                <!-- Centered City Name -->
                                <div class="relative z-10 text-center text-white px-2">
                                    <p class="font-black text-base sm:text-lg md:text-xl text-white leading-tight drop-shadow-lg tracking-tight">{{ $c->name }}</p>
                                </div>
                            </a>
                        </div>
                    @empty
                        @php
                            $fallbackCities = [
                                ['name' => 'Delhi NCR', 'slug' => 'delhi', 'img' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Bangalore', 'slug' => 'bangalore', 'img' => 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Noida', 'slug' => 'noida', 'img' => 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Mumbai', 'slug' => 'mumbai', 'img' => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Gurugram', 'slug' => 'gurgaon', 'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Hyderabad', 'slug' => 'hyderabad', 'img' => 'https://images.unsplash.com/photo-1576487248805-cf45f6bcc67f?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Ahmedabad', 'slug' => 'ahmedabad', 'img' => 'https://images.unsplash.com/photo-1599839575945-a9e5af0c3fa5?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Pune', 'slug' => 'pune', 'img' => 'https://images.unsplash.com/photo-1627894483216-2138af692e32?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Lucknow', 'slug' => 'lucknow', 'img' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Ghaziabad', 'slug' => 'ghaziabad', 'img' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Jaipur', 'slug' => 'jaipur', 'img' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&w=800&q=80'],
                                ['name' => 'Kolkata', 'slug' => 'kolkata', 'img' => 'https://images.unsplash.com/photo-1558431382-27e303142255?auto=format&fit=crop&w=800&q=80'],
                            ];
                        @endphp
                        @foreach ($fallbackCities as $fc)
                            @php
                                $fallbackSearchUrl = ($selectedType === 'pg-hostel' || empty($selectedType))
                                    ? route('user.seo.city-area', ['city' => $fc['slug']])
                                    : route('user.search') . '?city=' . urlencode($fc['name']) . ($selectedType ? '&type=' . $selectedType : '');
                            @endphp
                            <div class="swiper-slide !h-auto">
                                <a href="{{ $fallbackSearchUrl }}" data-city-name="{{ $fc['name'] }}" class="city-card-link group relative rounded-2xl sm:rounded-3xl overflow-hidden aspect-[3/4] sm:aspect-[3/4.2] min-h-[190px] sm:min-h-[230px] tap-ripple shadow-sm hover:shadow-2xl hover:shadow-brand/25 border border-gray-100 hover:border-brand/40 hover:-translate-y-1.5 transition-all duration-300 flex items-center justify-center p-3 sm:p-4 text-center w-full">
                                    <img src="{{ $fc['img'] }}" alt="{{ $fc['name'] }} - Verified PGs & Stays" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-slate-950/60"></div>
                                    <div class="absolute top-3 right-3 z-10">
                                        <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-[10px] sm:text-xs opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all duration-300 shadow-sm">
                                            <i class="fas fa-arrow-up-right text-[10px]"></i>
                                        </span>
                                    </div>
                                    <div class="relative z-10 text-center text-white px-2">
                                        <p class="font-black text-base sm:text-lg md:text-xl text-white leading-tight drop-shadow-lg tracking-tight">{{ $fc['name'] }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endforelse
                </div>
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
                    <h2 class="text-xl sm:text-3xl font-extrabold text-white mb-2 leading-tight">List your PG and fill<br class="sm:hidden"> beds faster</h2>
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

    <!-- StayNest Mobile App Section (Dual Smartphone Mockup Presentation) -->
   <section class="max-w-7xl mx-auto px-4 sm:px-6 mb-12 sm:mb-16">
    <div class="bg-white rounded-3xl border border-gray-900/10 relative overflow-hidden shadow-sm">
        <div class="grid lg:grid-cols-2 items-stretch">

            <!-- Left: Copy & App Store Badges -->
            <div class="p-8 sm:p-10 md:p-12 flex flex-col justify-center text-center lg:text-left order-2 lg:order-1">
                <span class="inline-flex items-center gap-1.5 self-center lg:self-start px-3.5 py-1.5 rounded-full bg-brand/10 text-brand text-xs font-bold uppercase tracking-wider mb-4 border border-brand/20 w-fit">
                    <i class="fas fa-mobile-screen-button"></i> StayNest Mobile App
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-900 mb-3 tracking-tight leading-tight">
                    Find your room. Find your people.
                </h2>
                <p class="text-gray-600 mb-7 sm:mb-8 text-sm sm:text-base lg:text-lg leading-relaxed max-w-md mx-auto lg:mx-0">
                    Search, shortlist &amp; book verified PGs, discover compatible flatmates with zero brokerage, and manage your stay seamlessly on the go.
                </p>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 mb-7 sm:mb-8">
                    <button type="button" onclick="installPwaApp('android')" class="bg-gray-900 text-white px-5 py-3 sm:px-6 sm:py-3.5 rounded-2xl flex items-center gap-3 hover:bg-gray-800 transition tap-effect shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform duration-200 cursor-pointer">
                        <i class="fab fa-google-play text-2xl sm:text-3xl"></i>
                        <div class="text-left">
                            <div class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-400">GET IT ON</div>
                            <div class="text-xs sm:text-sm md:text-base font-bold">Google Play</div>
                        </div>
                    </button>
                    <button type="button" onclick="installPwaApp('ios')" class="bg-gray-900 text-white px-5 py-3 sm:px-6 sm:py-3.5 rounded-2xl flex items-center gap-3 hover:bg-gray-800 transition tap-effect shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform duration-200 cursor-pointer">
                        <i class="fab fa-apple text-2xl sm:text-3xl text-white"></i>
                        <div class="text-left">
                            <div class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-400">Download on the</div>
                            <div class="text-xs sm:text-sm md:text-base font-bold">App Store</div>
                        </div>
                    </button>
                </div>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-2 pt-5 border-t border-gray-100 text-xs text-gray-500 font-semibold">
                    <span class="flex items-center gap-1.5"><i class="fas fa-star text-amber-400"></i> 4.9★ App Rating</span>
                    <span class="flex items-center gap-1.5"><i class="fas fa-users text-purple-600"></i> Flatmate Matching</span>
                    <span class="flex items-center gap-1.5"><i class="fas fa-shield-halved text-brand"></i> 100% Verified</span>
                </div>
            </div>

            <!-- Right: Dual Smartphone Mockup, on its own tinted panel -->
            <div class="relative order-1 lg:order-2 bg-white flex items-center justify-center px-0 py-10 sm:py-12 lg:py-0 overflow-hidden">
                <div class="absolute -right-16 -top-16 w-72 h-72 bg-brand/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-16 w-64 h-64 bg-teal-500/25 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative group w-full max-w-[300px] sm:max-w-[380px] lg:max-w-[420px] select-none">
                    <img src="{{ file_exists(public_path('images/app-image.png')) ? asset('images/app-image.png') : asset('images/app-image.webp') }}" alt="StayNest Mobile App Dual Screen Preview" loading="lazy" decoding="async" class="relative z-10 w-full h-auto object-contain drop-shadow-[0_25px_40px_rgba(0,0,0,0.45)] transition duration-500 group-hover:scale-[1.02]">
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

    // 1b. Top Cities — Autoplay slider with mobile app feel and desktop controls
    window.topCitiesSwiperInstance = new Swiper('.topCitiesSwiper', {
        slidesPerView: 2.3,
        spaceBetween: 12,
        loop: true,
        grabCursor: true,
        speed: 700,
        autoplay: {
            delay: 2600,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        navigation: {
            nextEl: '.topCitiesNextBtn',
            prevEl: '.topCitiesPrevBtn',
        },
        breakpoints: {
            480: {
                slidesPerView: 2.8,
                spaceBetween: 12,
            },
            640: {
                slidesPerView: 3.5,
                spaceBetween: 14,
            },
            768: {
                slidesPerView: 4.2,
                spaceBetween: 16,
            },
            1024: {
                slidesPerView: 5.2,
                spaceBetween: 16,
            },
            1280: {
                slidesPerView: 6,
                spaceBetween: 16,
            },
        },
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

        // 5. Flats & Houses Near You horizontal slider (mobile)
        window.flatNearMeSwiperInstance = new Swiper('.flatNearMeSwiper', {
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

        // 6. Flat Recommended horizontal slider (mobile)
        window.flatRecommendedSwiperInstance = new Swiper('.flatRecommendedSwiper', {
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

        // 7. Commercial Spaces Near You horizontal slider (mobile)
        window.commercialNearMeSwiperInstance = new Swiper('.commercialNearMeSwiper', {
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

        // 8. Commercial Recommended horizontal slider (mobile)
        window.commercialRecommendedSwiperInstance = new Swiper('.commercialRecommendedSwiper', {
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
            if (desktopGrid) desktopGrid.style.display = 'none';
            if (mobileContainer) mobileContainer.style.display = 'none';
        } else {
            emptyState.classList.add('hidden');
            if (desktopGrid) desktopGrid.style.display = '';
            if (mobileContainer) mobileContainer.style.display = '';
        }
    }

    // 4. Update distance text for Flat & Commercial cards
    document.querySelectorAll('#flatNearMeMobileContainer .pg-distance-badge, #flatNearMeDesktopGrid .pg-distance-badge, #commercialNearMeMobileContainer .pg-distance-badge, #commercialNearMeDesktopGrid .pg-distance-badge').forEach(badge => {
        const lat = parseFloat(badge.getAttribute('data-lat'));
        const lng = parseFloat(badge.getAttribute('data-lng'));
        const textSpan = badge.querySelector('.dist-text');
        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
            const distKm = getHaversineDistanceKm(userLat, userLng, lat, lng);
            if (textSpan) textSpan.textContent = formatDistance(distKm);
        }
    });

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

// ===================== SWITCH PROPERTY TYPE (OPTION 3) =====================
function switchPropertyType(type, triggerScroll = false) {
    const pgContainer = document.getElementById('pgViewContainer');
    const flatContainer = document.getElementById('flatViewContainer');
    const commercialContainer = document.getElementById('commercialViewContainer');
    const roommateContainer = document.getElementById('roommateViewContainer');
    const skeleton = document.getElementById('homeSwitchSkeleton');

    const cardPg = document.getElementById('card-type-pg-hostel');
    const cardFlat = document.getElementById('card-type-flat-apartment');
    const cardCommercial = document.getElementById('card-type-commercial');
    const cardRoommate = document.getElementById('card-type-roommate');

    if (typeof window.triggerHaptic === 'function') {
        window.triggerHaptic(10);
    }

    // Reset all cards
    [cardPg, cardFlat, cardCommercial, cardRoommate].forEach(c => {
        if (!c) return;
        c.classList.remove('active-pg', 'active-flat', 'active-commercial', 'active-roommate');
    });

    // Hide all containers & show skeleton shimmer
    [pgContainer, flatContainer, commercialContainer, roommateContainer].forEach(cnt => {
        if (cnt) cnt.classList.add('hidden');
    });
    if (skeleton) skeleton.classList.remove('hidden');

    let targetContainer = pgContainer;
    if (type === 'flat-apartment') {
        if (cardFlat) cardFlat.classList.add('active-flat');
        targetContainer = flatContainer;
    } else if (type === 'commercial') {
        if (cardCommercial) cardCommercial.classList.add('active-commercial');
        targetContainer = commercialContainer;
    } else if (type === 'roommate') {
        if (cardRoommate) cardRoommate.classList.add('active-roommate');
        targetContainer = roommateContainer;
    } else {
        // Default PG / Hostel
        type = 'pg-hostel';
        if (cardPg) cardPg.classList.add('active-pg');
        targetContainer = pgContainer;
    }

    setTimeout(() => {
        if (skeleton) skeleton.classList.add('hidden');
        if (targetContainer) {
            targetContainer.classList.remove('hidden');
            targetContainer.classList.add('view-container-fade');
        }
    }, 120);

    // Update URL query param without full reload
    try {
        const url = new URL(window.location.href);
        if (type === 'pg-hostel') {
            url.searchParams.delete('type');
        } else {
            url.searchParams.set('type', type);
        }
        window.history.replaceState({ propertyType: type }, '', url.toString());
    } catch (e) {}

    // Update all City Card links & All Cities button dynamically
    try {
        const cityLinks = document.querySelectorAll('.city-card-link');
        const allCitiesBtn = document.getElementById('allCitiesHeaderLink');
        const searchBase = "{{ route('user.search') }}";

        cityLinks.forEach(link => {
            const cityName = link.getAttribute('data-city-name') || '';
            const params = new URLSearchParams();
            if (cityName) params.set('city', cityName);
            if (type && type !== 'pg-hostel') {
                params.set('type', type);
            }
            link.href = searchBase + (params.toString() ? '?' + params.toString() : '');
        });

        if (allCitiesBtn) {
            if (type === 'roommate') {
                allCitiesBtn.href = "{{ route('user.roommate.index') }}";
            } else if (type && type !== 'pg-hostel') {
                allCitiesBtn.href = searchBase + '?type=' + encodeURIComponent(type);
            } else {
                allCitiesBtn.href = searchBase;
            }
        }
    } catch (e) {}

    // Refresh Swipers to ensure smooth rendering on container toggle
    setTimeout(() => {
        if (window.topCitiesSwiperInstance && typeof window.topCitiesSwiperInstance.update === 'function') {
            window.topCitiesSwiperInstance.update();
        }
        if (window.nearMeSwiperInstance && typeof window.nearMeSwiperInstance.update === 'function') {
            window.nearMeSwiperInstance.update();
        }
        if (window.flatNearMeSwiperInstance && typeof window.flatNearMeSwiperInstance.update === 'function') {
            window.flatNearMeSwiperInstance.update();
        }
        if (window.flatRecommendedSwiperInstance && typeof window.flatRecommendedSwiperInstance.update === 'function') {
            window.flatRecommendedSwiperInstance.update();
        }
        if (window.commercialNearMeSwiperInstance && typeof window.commercialNearMeSwiperInstance.update === 'function') {
            window.commercialNearMeSwiperInstance.update();
        }
        if (window.commercialRecommendedSwiperInstance && typeof window.commercialRecommendedSwiperInstance.update === 'function') {
            window.commercialRecommendedSwiperInstance.update();
        }
    }, 60);

    if (triggerScroll) {
        const target = document.getElementById(type === 'flat-apartment' ? 'flatViewContainer' : (type === 'commercial' ? 'commercialViewContainer' : (type === 'roommate' ? 'roommateViewContainer' : 'pgViewContainer')));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}
</script>
@endpush
