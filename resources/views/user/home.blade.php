@extends('user.layouts.app')

@section('title', 'StayNest - Find Verified PGs & Co-living Spaces Across India')

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
<div class="w-full bg-gray-50/50 min-h-screen pb-24 md:pb-12">

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
                                            <h2 class="text-xl sm:text-3xl md:text-4xl font-black leading-tight mb-1.5 text-white">
                                                Save up to <span style="color:#7eebd4">₹15,000</span> on Zero Brokerage
                                            </h2>
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
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="section-title flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 inline-flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-location-crosshairs text-blue-600 text-sm"></i>
                        </span>
                        PG Near Me
                    </h2>
                    <p class="text-xs text-gray-500 mt-1 ml-10">Verified stays within 2 km of your location</p>
                </div>
                <a href="{{ route('user.search') }}" class="text-xs font-bold text-brand flex items-center gap-1.5 bg-brand-light hover:bg-brand/15 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    See all <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
            <div class="md:hidden swiper nearMeSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ([
                        ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Sunrise Premium PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 62, Noida', 'dist' => '0.4 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '8,500', 'rating' => '4.8', 'reviews' => '120', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC']]],
                        ['image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Aura Women\'s Stay', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Indiranagar, BLR', 'dist' => '0.5 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '9,999', 'rating' => '4.9', 'reviews' => '98', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'shield-alt','l' => 'Security']]],
                        ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Urban Nest Co-Living', 'type' => 'CO-ED', 'typeColor' => 'bg-purple-50 text-purple-600', 'location' => 'HSR Layout, BLR', 'dist' => '1.2 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '11,500', 'rating' => '4.7', 'reviews' => '75', 'badge' => 'Popular', 'badgeBg' => 'bg-orange-500', 'badgeIcon' => 'fire', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'wifi','l' => 'WiFi']]],
                        ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Metro Heights PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Saket, Delhi', 'dist' => '0.8 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '10,000', 'rating' => '4.6', 'reviews' => '85', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'snowflake','l' => 'AC'],['i' => 'wifi','l' => 'WiFi']]],
                        ['image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Green Valley PG', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Koramangala, BLR', 'dist' => '0.3 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '9,200', 'rating' => '4.8', 'reviews' => '60', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'utensils','l' => 'Food'],['i' => 'wifi','l' => 'WiFi']]],
                        ['image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Skyline Residency', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Connaught Place, Delhi', 'dist' => '1.1 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '12,000', 'rating' => '4.5', 'reviews' => '44', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'snowflake','l' => 'AC']]],
                    ] as $pg)
                    <div class="swiper-slide !h-auto">
                        <a href="{{ route('user.detail') }}" class="pg-card tap-ripple">
                            <div class="relative h-28 sm:h-36 overflow-hidden">
                                <img src="{{ $pg['image'] }}" alt="{{ $pg['title'] }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 left-2 {{ $pg['badgeBg'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-{{ $pg['badgeIcon'] }} text-[8px]"></i> {{ $pg['badge'] }}
                                </div>
                                <button onclick="event.preventDefault();heartToggle(this)" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 shadow tap-ripple">
                                    <i class="far fa-heart text-[10px]"></i>
                                </button>
                                <div class="absolute bottom-1.5 left-2 bg-black/75 backdrop-blur text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1">
                                    <i class="fas fa-star text-yellow-400 text-[8px]"></i> {{ $pg['rating'] }}
                                </div>
                            </div>
                            <div class="p-2.5 sm:p-3 flex flex-col flex-1 justify-between">
                                <div>
                                    <div class="flex justify-between items-center gap-1 mb-1">
                                        <span class="font-bold text-xs text-gray-900 truncate">{{ $pg['title'] }}</span>
                                        <span class="{{ $pg['typeColor'] }} text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">{{ $pg['type'] }}</span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $pg['location'] }}
                                    </p>
                                    <p class="text-[10px] {{ $pg['distColor'] }} font-semibold mb-2 flex items-center gap-1 truncate">
                                        <i class="fas fa-{{ $pg['distIcon'] }} text-[9px]"></i> {{ $pg['dist'] }}
                                    </p>
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($pg['amenities'] as $am)
                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                            <i class="fas fa-{{ $am['i'] }} text-brand text-[8px]"></i> {{ $am['l'] }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-[9px] text-gray-400 block font-medium leading-none">Rent</span>
                                        <span class="text-xs font-black text-gray-900 leading-tight">₹{{ $pg['price'] }}<span class="text-[9px] font-normal text-gray-500">/m</span></span>
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
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Sunrise Premium PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 62, Noida', 'dist' => '0.4 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '8,500', 'rating' => '4.8', 'reviews' => '120', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC'],['i' => 'utensils','l' => 'Food']]],
                    ['image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Aura Women\'s Stay', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Indiranagar, BLR', 'dist' => '0.5 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '9,999', 'rating' => '4.9', 'reviews' => '98', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'shield-alt','l' => 'Security']]],
                    ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Urban Nest Co-Living', 'type' => 'CO-ED', 'typeColor' => 'bg-purple-50 text-purple-600', 'location' => 'HSR Layout, BLR', 'dist' => '1.2 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '11,500', 'rating' => '4.7', 'reviews' => '75', 'badge' => 'Popular', 'badgeBg' => 'bg-orange-500', 'badgeIcon' => 'fire', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'wifi','l' => 'WiFi']]],
                    ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Metro Heights PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Saket, Delhi', 'dist' => '0.8 km away', 'distColor' => 'text-blue-600', 'distIcon' => 'location-dot', 'price' => '10,000', 'rating' => '4.6', 'reviews' => '85', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'snowflake','l' => 'AC'],['i' => 'wifi','l' => 'WiFi']]],
                ] as $pg)
                <a href="{{ route('user.detail') }}" class="pg-card tap-ripple">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $pg['image'] }}" alt="{{ $pg['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2.5 left-2.5 {{ $pg['badgeBg'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                            <i class="fas fa-{{ $pg['badgeIcon'] }} text-[9px]"></i> {{ $pg['badge'] }}
                        </div>
                        <button onclick="event.preventDefault();heartToggle(this)" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow">
                            <i class="far fa-heart text-xs"></i>
                        </button>
                        <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $pg['rating'] }} <span class="text-gray-300 font-normal">({{ $pg['reviews'] }})</span>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <div class="flex justify-between items-center gap-1 mb-1">
                                <span class="font-bold text-base text-gray-900 truncate">{{ $pg['title'] }}</span>
                                <span class="{{ $pg['typeColor'] }} text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">{{ $pg['type'] }}</span>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ $pg['location'] }}
                            </p>
                            <p class="text-xs {{ $pg['distColor'] }} font-semibold mb-3 flex items-center gap-1">
                                <i class="fas fa-{{ $pg['distIcon'] }} text-[11px]"></i> {{ $pg['dist'] }}
                            </p>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($pg['amenities'] as $am)
                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                    <i class="fas fa-{{ $am['i'] }} text-brand text-[10px]"></i> {{ $am['l'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium">Rent</span>
                                <span class="text-base font-black text-gray-900">₹{{ $pg['price'] }}<span class="text-xs font-normal text-gray-500">/mo</span></span>
                            </div>
                            <span class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-brand/30 transition">View</span>
                        </div>
                    </div>
                </a>
                @endforeach
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
                            <i class="fas fa-sparkles text-brand text-sm"></i>
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
                    @foreach ([
                        ['image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Koramangala Hub', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Koramangala, BLR', 'dist' => 'Food Included', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '11,500', 'rating' => '4.8', 'reviews' => '110', 'badge' => 'Guest Favourite', 'badgeBg' => 'bg-amber-500', 'badgeIcon' => 'crown', 'amenities' => [['i' => 'utensils','l' => 'Food'],['i' => 'broom','l' => 'Cleaning']]],
                        ['image' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Indiranagar Stay', 'type' => 'CO-ED', 'typeColor' => 'bg-purple-50 text-purple-600', 'location' => 'Indiranagar, BLR', 'dist' => 'Gym & Parking', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '13,000', 'rating' => '4.9', 'reviews' => '95', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'parking','l' => 'Parking']]],
                        ['image' => 'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Chattarpur PG', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Chattarpur, Delhi', 'dist' => 'Metro 0.4 km', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '7,800', 'rating' => '4.7', 'reviews' => '42', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'shield-alt','l' => 'Security']]],
                        ['image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Mehrauli Suites', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Mehrauli, Delhi', 'dist' => 'Food Included', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '8,100', 'rating' => '4.7', 'reviews' => '50', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'utensils','l' => 'Food'],['i' => 'snowflake','l' => 'AC']]],
                        ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Sunshine Residency', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 18, Noida', 'dist' => 'WiFi & AC', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '9,500', 'rating' => '4.6', 'reviews' => '33', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC']]],
                        ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Royal Residences', 'type' => 'CO-ED', 'typeColor' => 'bg-purple-50 text-purple-600', 'location' => 'Andheri, Mumbai', 'dist' => 'Gym Included', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '14,500', 'rating' => '4.8', 'reviews' => '72', 'badge' => 'Popular', 'badgeBg' => 'bg-orange-500', 'badgeIcon' => 'fire', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'wifi','l' => 'WiFi']]],
                    ] as $pg)
                    <div class="swiper-slide !h-auto">
                        <a href="{{ route('user.detail') }}" class="pg-card tap-ripple">
                            <div class="relative h-28 sm:h-36 overflow-hidden">
                                <img src="{{ $pg['image'] }}" alt="{{ $pg['title'] }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 left-2 {{ $pg['badgeBg'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-{{ $pg['badgeIcon'] }} text-[8px]"></i> {{ $pg['badge'] }}
                                </div>
                                <button onclick="event.preventDefault();heartToggle(this)" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 shadow tap-ripple">
                                    <i class="far fa-heart text-[10px]"></i>
                                </button>
                                <div class="absolute bottom-1.5 left-2 bg-black/75 backdrop-blur text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1">
                                    <i class="fas fa-star text-yellow-400 text-[8px]"></i> {{ $pg['rating'] }}
                                </div>
                            </div>
                            <div class="p-2.5 sm:p-3 flex flex-col flex-1 justify-between">
                                <div>
                                    <div class="flex justify-between items-center gap-1 mb-1">
                                        <span class="font-bold text-xs text-gray-900 truncate">{{ $pg['title'] }}</span>
                                        <span class="{{ $pg['typeColor'] }} text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">{{ $pg['type'] }}</span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $pg['location'] }}
                                    </p>
                                    <p class="text-[10px] {{ $pg['distColor'] }} font-semibold mb-2 flex items-center gap-1 truncate">
                                        <i class="fas fa-{{ $pg['distIcon'] }} text-[9px]"></i> {{ $pg['dist'] }}
                                    </p>
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($pg['amenities'] as $am)
                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                            <i class="fas fa-{{ $am['i'] }} text-brand text-[8px]"></i> {{ $am['l'] }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-[9px] text-gray-400 block font-medium leading-none">Rent</span>
                                        <span class="text-xs font-black text-gray-900 leading-tight">₹{{ $pg['price'] }}<span class="text-[9px] font-normal text-gray-500">/m</span></span>
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
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Koramangala Hub', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Koramangala, BLR', 'dist' => 'Food Included', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '11,500', 'rating' => '4.8', 'reviews' => '110', 'badge' => 'Guest Favourite', 'badgeBg' => 'bg-amber-500', 'badgeIcon' => 'crown', 'amenities' => [['i' => 'utensils','l' => 'Food'],['i' => 'broom','l' => 'Cleaning']]],
                    ['image' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Indiranagar Stay', 'type' => 'CO-ED', 'typeColor' => 'bg-purple-50 text-purple-600', 'location' => 'Indiranagar, BLR', 'dist' => 'Gym & Parking', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '13,000', 'rating' => '4.9', 'reviews' => '95', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'parking','l' => 'Parking']]],
                    ['image' => 'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Chattarpur PG', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Chattarpur, Delhi', 'dist' => 'Metro 0.4 km', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '7,800', 'rating' => '4.7', 'reviews' => '42', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'shield-alt','l' => 'Security']]],
                    ['image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Mehrauli Suites', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Mehrauli, Delhi', 'dist' => 'Food Included', 'distColor' => 'text-brand', 'distIcon' => 'circle-check', 'price' => '8,100', 'rating' => '4.7', 'reviews' => '50', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'utensils','l' => 'Food'],['i' => 'snowflake','l' => 'AC']]],
                ] as $pg)
                <a href="{{ route('user.detail') }}" class="pg-card tap-ripple">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $pg['image'] }}" alt="{{ $pg['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2.5 left-2.5 {{ $pg['badgeBg'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                            <i class="fas fa-{{ $pg['badgeIcon'] }} text-[9px]"></i> {{ $pg['badge'] }}
                        </div>
                        <button onclick="event.preventDefault();heartToggle(this)" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow">
                            <i class="far fa-heart text-xs"></i>
                        </button>
                        <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $pg['rating'] }} <span class="text-gray-300 font-normal">({{ $pg['reviews'] }})</span>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <div class="flex justify-between items-center gap-1 mb-1">
                                <span class="font-bold text-base text-gray-900 truncate">{{ $pg['title'] }}</span>
                                <span class="{{ $pg['typeColor'] }} text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">{{ $pg['type'] }}</span>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ $pg['location'] }}
                            </p>
                            <p class="text-xs {{ $pg['distColor'] }} font-semibold mb-3 flex items-center gap-1">
                                <i class="fas fa-{{ $pg['distIcon'] }} text-[11px]"></i> {{ $pg['dist'] }}
                            </p>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($pg['amenities'] as $am)
                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                    <i class="fas fa-{{ $am['i'] }} text-brand text-[10px]"></i> {{ $am['l'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium">Rent</span>
                                <span class="text-base font-black text-gray-900">₹{{ $pg['price'] }}<span class="text-xs font-normal text-gray-500">/mo</span></span>
                            </div>
                            <span class="bg-brand hover:bg-brand-dark text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-brand/30 transition">View</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= 4. POPULAR BOYS PG ============================= --}}
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
                    <p class="text-xs text-gray-500 mt-1 ml-10">Top-rated verified stays with meals & gym for boys</p>
                </div>
                <a href="{{ route('user.search') }}?gender=BOYS" class="text-xs font-bold text-blue-600 flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 px-3.5 py-1.5 rounded-full tap-ripple transition">
                    View all <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            {{-- ===== MOBILE: 2-Column Horizontal Slider ===== --}}
            <div class="md:hidden swiper boysPgSwiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ([
                        ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Elite Boys Residency', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 62, Noida', 'dist' => '3 Meals + AC', 'distColor' => 'text-blue-600', 'distIcon' => 'utensils', 'price' => '8,500', 'rating' => '4.8', 'reviews' => '140', 'badge' => 'Trending', 'badgeBg' => 'bg-blue-600', 'badgeIcon' => 'fire', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC']]],
                        ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Metro Heights Boys PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Saket, Delhi', 'dist' => 'Gym + WiFi', 'distColor' => 'text-blue-600', 'distIcon' => 'dumbbell', 'price' => '9,500', 'rating' => '4.7', 'reviews' => '95', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'wifi','l' => 'WiFi']]],
                        ['image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Mehrauli Smart Suites', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Mehrauli, Delhi', 'dist' => '0.2 km Metro', 'distColor' => 'text-blue-600', 'distIcon' => 'train-subway', 'price' => '8,100', 'rating' => '4.6', 'reviews' => '58', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC']]],
                        ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Tech Park Boys PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Electronic City, BLR', 'dist' => 'Gaming Lounge', 'distColor' => 'text-blue-600', 'distIcon' => 'gamepad', 'price' => '10,500', 'rating' => '4.9', 'reviews' => '115', 'badge' => 'Top Rated', 'badgeBg' => 'bg-amber-500', 'badgeIcon' => 'star', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'dumbbell','l' => 'Gym']]],
                        ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Stanza Boys Living', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Koramangala, BLR', 'dist' => '3 Meals Daily', 'distColor' => 'text-blue-600', 'distIcon' => 'utensils', 'price' => '11,000', 'rating' => '4.8', 'reviews' => '80', 'badge' => 'Popular', 'badgeBg' => 'bg-orange-500', 'badgeIcon' => 'fire', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'utensils','l' => 'Food']]],
                        ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Cyber City Boys Stay', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Gurugram, HR', 'dist' => 'High Speed WiFi', 'distColor' => 'text-blue-600', 'distIcon' => 'wifi', 'price' => '12,500', 'rating' => '4.7', 'reviews' => '64', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'snowflake','l' => 'AC'],['i' => 'dumbbell','l' => 'Gym']]],
                    ] as $pg)
                    <div class="swiper-slide !h-auto">
                        <a href="{{ route('user.detail') }}" class="pg-card tap-ripple">
                            <div class="relative h-28 sm:h-36 overflow-hidden">
                                <img src="{{ $pg['image'] }}" alt="{{ $pg['title'] }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 left-2 {{ $pg['badgeBg'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-{{ $pg['badgeIcon'] }} text-[8px]"></i> {{ $pg['badge'] }}
                                </div>
                                <button onclick="event.preventDefault();heartToggle(this)" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 shadow tap-ripple">
                                    <i class="far fa-heart text-[10px]"></i>
                                </button>
                                <div class="absolute bottom-1.5 left-2 bg-black/75 backdrop-blur text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1">
                                    <i class="fas fa-star text-yellow-400 text-[8px]"></i> {{ $pg['rating'] }}
                                </div>
                            </div>
                            <div class="p-2.5 sm:p-3 flex flex-col flex-1 justify-between">
                                <div>
                                    <div class="flex justify-between items-center gap-1 mb-1">
                                        <span class="font-bold text-xs text-gray-900 truncate">{{ $pg['title'] }}</span>
                                        <span class="{{ $pg['typeColor'] }} text-[8px] font-extrabold px-1 py-0.5 rounded flex-shrink-0">{{ $pg['type'] }}</span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 flex items-center gap-1 mb-1 truncate">
                                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $pg['location'] }}
                                    </p>
                                    <p class="text-[10px] {{ $pg['distColor'] }} font-semibold mb-2 flex items-center gap-1 truncate">
                                        <i class="fas fa-{{ $pg['distIcon'] }} text-[9px]"></i> {{ $pg['dist'] }}
                                    </p>
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($pg['amenities'] as $am)
                                        <span class="text-[9px] bg-gray-50 text-gray-600 px-1.5 py-0.5 rounded flex items-center gap-0.5 border border-gray-100">
                                            <i class="fas fa-{{ $am['i'] }} text-brand text-[8px]"></i> {{ $am['l'] }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-gray-100 flex items-center justify-between mt-auto">
                                    <div>
                                        <span class="text-[9px] text-gray-400 block font-medium leading-none">Rent</span>
                                        <span class="text-xs font-black text-gray-900 leading-tight">₹{{ $pg['price'] }}<span class="text-[9px] font-normal text-gray-500">/m</span></span>
                                    </div>
                                    <span class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm shadow-blue-500/30 transition">View</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== DESKTOP: 4-Col Grid ===== --}}
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Elite Boys Residency', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 62, Noida', 'dist' => '3 Meals + AC', 'distColor' => 'text-blue-600', 'distIcon' => 'utensils', 'price' => '8,500', 'rating' => '4.8', 'reviews' => '140', 'badge' => 'Trending', 'badgeBg' => 'bg-blue-600', 'badgeIcon' => 'fire', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC'],['i' => 'utensils','l' => 'Food']]],
                    ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Metro Heights Boys PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Saket, Delhi', 'dist' => 'Gym + WiFi', 'distColor' => 'text-blue-600', 'distIcon' => 'dumbbell', 'price' => '9,500', 'rating' => '4.7', 'reviews' => '95', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC']]],
                    ['image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Mehrauli Smart Suites', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Mehrauli, Delhi', 'dist' => '0.2 km from Metro', 'distColor' => 'text-blue-600', 'distIcon' => 'train-subway', 'price' => '8,100', 'rating' => '4.6', 'reviews' => '58', 'badge' => 'Verified', 'badgeBg' => 'bg-emerald-500', 'badgeIcon' => 'check-circle', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC']]],
                    ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Tech Park Boys PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Electronic City, BLR', 'dist' => 'Gaming Lounge', 'distColor' => 'text-blue-600', 'distIcon' => 'gamepad', 'price' => '10,500', 'rating' => '4.9', 'reviews' => '115', 'badge' => 'Top Rated', 'badgeBg' => 'bg-amber-500', 'badgeIcon' => 'star', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'dumbbell','l' => 'Gym'],['i' => 'utensils','l' => 'Food']]],
                ] as $pg)
                <a href="{{ route('user.detail') }}" class="pg-card tap-ripple">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $pg['image'] }}" alt="{{ $pg['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2.5 left-2.5 {{ $pg['badgeBg'] }} text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                            <i class="fas fa-{{ $pg['badgeIcon'] }} text-[9px]"></i> {{ $pg['badge'] }}
                        </div>
                        <button onclick="event.preventDefault();heartToggle(this)" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow">
                            <i class="far fa-heart text-xs"></i>
                        </button>
                        <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $pg['rating'] }} <span class="text-gray-300 font-normal">({{ $pg['reviews'] }})</span>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <div class="flex justify-between items-center gap-1 mb-1">
                                <span class="font-bold text-base text-gray-900 truncate">{{ $pg['title'] }}</span>
                                <span class="{{ $pg['typeColor'] }} text-[10px] font-extrabold px-2 py-0.5 rounded flex-shrink-0">{{ $pg['type'] }}</span>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mb-1 truncate">
                                <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ $pg['location'] }}
                            </p>
                            <p class="text-xs {{ $pg['distColor'] }} font-semibold mb-3 flex items-center gap-1">
                                <i class="fas fa-{{ $pg['distIcon'] }} text-[11px]"></i> {{ $pg['dist'] }}
                            </p>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($pg['amenities'] as $am)
                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                    <i class="fas fa-{{ $am['i'] }} text-brand text-[10px]"></i> {{ $am['l'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium">Rent</span>
                                <span class="text-base font-black text-gray-900">₹{{ $pg['price'] }}<span class="text-xs font-normal text-gray-500">/mo</span></span>
                            </div>
                            <span class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-blue-500/30 transition">View</span>
                        </div>
                    </div>
                </a>
                @endforeach
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
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Sunrise Premium PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 62, Noida', 'price' => '8,500', 'rating' => '4.8', 'reviews' => '120', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'snowflake','l' => 'AC']]],
                    ['image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Aura Women\'s Stay', 'type' => 'GIRLS', 'typeColor' => 'bg-pink-50 text-pink-600', 'location' => 'Indiranagar, BLR', 'price' => '9,999', 'rating' => '4.9', 'reviews' => '98', 'amenities' => [['i' => 'wifi','l' => 'WiFi'],['i' => 'shield-alt','l' => 'Security']]],
                    ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Urban Nest Co-Living', 'type' => 'CO-ED', 'typeColor' => 'bg-purple-50 text-purple-600', 'location' => 'HSR Layout, BLR', 'price' => '11,500', 'rating' => '4.7', 'reviews' => '75', 'amenities' => [['i' => 'dumbbell','l' => 'Gym'],['i' => 'wifi','l' => 'WiFi']]],
                    ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'title' => 'Metro Heights PG', 'type' => 'BOYS', 'typeColor' => 'bg-blue-50 text-blue-600', 'location' => 'Saket, Delhi', 'price' => '10,000', 'rating' => '4.6', 'reviews' => '85', 'amenities' => [['i' => 'snowflake','l' => 'AC'],['i' => 'wifi','l' => 'WiFi']]],
                ] as $pg)
                <a href="{{ route('user.detail') }}" class="pg-card tap-ripple">
                    <div class="relative h-32 sm:h-44 overflow-hidden">
                        <img src="{{ $pg['image'] }}" alt="{{ $pg['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2.5 left-2.5 bg-red-500 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-sm">
                            <i class="fas fa-bolt text-[9px]"></i> NEW
                        </div>
                        <button onclick="event.preventDefault();heartToggle(this)" class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-400 hover:text-red-500 shadow tap-ripple transition">
                            <i class="far fa-heart text-xs"></i>
                        </button>
                        <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400 text-[9px]"></i> {{ $pg['rating'] }}
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <div class="flex justify-between items-center gap-1 mb-1">
                                <span class="font-bold text-xs sm:text-base text-gray-900 truncate leading-tight">{{ $pg['title'] }}</span>
                                <span class="{{ $pg['typeColor'] }} text-[9px] sm:text-[10px] font-extrabold px-1.5 py-0.5 rounded flex-shrink-0">{{ $pg['type'] }}</span>
                            </div>
                            <p class="text-[11px] sm:text-xs text-gray-500 flex items-center gap-1 mb-2.5 truncate">
                                <i class="fas fa-map-marker-alt text-brand text-[10px]"></i> {{ $pg['location'] }}
                            </p>
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach($pg['amenities'] as $am)
                                <span class="text-[10px] sm:text-xs bg-gray-50 text-gray-600 px-1.5 sm:px-2 py-0.5 rounded-md flex items-center gap-1 border border-gray-100">
                                    <i class="fas fa-{{ $am['i'] }} text-brand text-[9px]"></i> {{ $am['l'] }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-2.5 sm:pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-[9px] sm:text-[10px] text-gray-400 block font-medium">Rent</span>
                                <span class="text-xs sm:text-base font-black text-gray-900">₹{{ $pg['price'] }}<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></span>
                            </div>
                            <span class="bg-brand hover:bg-brand-dark text-white text-[11px] sm:text-xs font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl shadow-sm shadow-brand/30 transition">View</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= 6. EXPLORE BY BUDGET ============================= --}}
    <section class="mt-8 sm:mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="section-title mb-4">Explore by Budget</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-5">
                @foreach([
                    ['label' => 'Under ₹6K', 'sub' => 'Pocket Friendly', 'icon' => 'wallet', 'bg' => 'bg-green-50', 'color' => 'text-green-600', 'q' => '6000'],
                    ['label' => '₹6K–₹10K', 'sub' => 'Mid-Range Comfort', 'icon' => 'bed', 'bg' => 'bg-blue-50', 'color' => 'text-blue-600', 'q' => '10000'],
                    ['label' => '₹10K–₹15K', 'sub' => 'Premium Living', 'icon' => 'gem', 'bg' => 'bg-purple-50', 'color' => 'text-purple-600', 'q' => '15000'],
                    ['label' => '₹15K+', 'sub' => 'Luxury Co-Living', 'icon' => 'crown', 'bg' => 'bg-amber-50', 'color' => 'text-amber-600', 'q' => '20000'],
                ] as $b)
                <a href="{{ route('user.search') }}?budget={{ $b['q'] }}" class="budget-card flex flex-col justify-between p-4 sm:p-5 tap-ripple">
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
                <h2 class="section-title">Top Cities</h2>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach ([
                    ['city' => 'Noida', 'stays' => '1,200+', 'image' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],
                    ['city' => 'Bangalore', 'stays' => '3,400+', 'image' => 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],
                    ['city' => 'Delhi NCR', 'stays' => '2,800+', 'image' => 'https://images.unsplash.com/photo-1587474260584-136574528ed5?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],
                    ['city' => 'Mumbai', 'stays' => '1,900+', 'image' => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],
                    ['city' => 'Gurugram', 'stays' => '1,500+', 'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],
                    ['city' => 'Pune', 'stays' => '950+', 'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],
                ] as $c)
                <a href="{{ route('user.search') }}?city={{ $c['city'] }}" class="relative rounded-2xl overflow-hidden aspect-square tap-ripple group shadow-sm border border-gray-100">
                    <img src="{{ $c['image'] }}" alt="{{ $c['city'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-2.5 left-0 right-0 text-center text-white px-1">
                        <p class="font-bold text-xs sm:text-sm leading-tight">{{ $c['city'] }}</p>
                        <p class="text-[10px] text-gray-300">{{ $c['stays'] }} stays</p>
                    </div>
                </a>
                @endforeach
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
    <section class="mt-8 sm:mt-10 mb-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-900 rounded-3xl p-6 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 shadow-xl">
                <div>
                    <p class="text-xs font-bold text-brand uppercase tracking-widest mb-1.5">For Property Owners</p>
                    <h3 class="text-xl sm:text-3xl font-extrabold text-white mb-2 leading-tight">List your PG and fill<br class="sm:hidden"> beds faster</h3>
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
        new Swiper('.nearMeSwiper', {
            slidesPerView: 2.05,
            spaceBetween: 10,
            grabCursor: true,
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

        // 3. Boys PG horizontal card slider (mobile)
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
});

function heartToggle(btn) {
    const icon = btn.querySelector('i');
    icon.classList.toggle('far');
    icon.classList.toggle('fas');
    icon.classList.toggle('text-red-500');
}
</script>
@endpush
