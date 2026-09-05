@extends('user.layouts.app')

@php
    $bhkLabels = \App\Models\RoommatePost::bhkOptions();
    $bhkLabel = $bhkLabels[$post->bhk_type] ?? $post->bhk_type;
    $locationText = ($post->locality ? $post->locality . ', ' : '') . ($post->city ?: 'City Center');
    $genderPref = strtolower($post->gender_preference ?? 'any');
    $posterGender = strtolower($post->poster_gender ?? 'male');
    $occupationText = ucfirst(str_replace('_', ' ', $post->occupation_type ?? 'working_professional'));
    $furnishingText = ucfirst(str_replace('_', ' ', $post->furnishing ?? 'furnished'));
    
    $seoShowTitle = $post->poster_name . ' — ' . $bhkLabel . ' Flatmate in ' . $post->city . ' | Zero Brokerage | SpaceSeeks';
    $seoShowDesc = 'Connect with ' . $post->poster_name . ' (' . ($post->poster_age ? $post->poster_age . ' yrs, ' : '') . $occupationText . ') for ' . $bhkLabel . ' in ' . $locationText . '. Rent: ' . $post->budget_range . '. Zero brokerage on SpaceSeeks.';
    $seoKeywords = $post->poster_name . ', ' . $bhkLabel . ' in ' . $post->city . ', flatmate in ' . $post->city . ', roommate ' . $locationText . ', zero brokerage flatmate';
    $seoImage = $post->poster_avatar_url ?: asset('images/favicon.png');
    $canonicalUrl = route('user.roommate.show', $post->slug);
@endphp

@section('title', $seoShowTitle)
@section('meta_description', $seoShowDesc)
@section('meta_keywords', $seoKeywords)
@section('meta_image', $seoImage)
@section('canonical', $canonicalUrl)
@section('og_type', 'profile')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "@id": "{{ $canonicalUrl }}#breadcrumb",
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
          "name": "Find Flatmate & Roommate",
          "item": "{{ route('user.roommate.index') }}"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "Flatmates in {{ ucfirst($post->city) }}",
          "item": "{{ route('user.roommate.index', ['city' => $post->city]) }}"
        },
        {
          "@type": "ListItem",
          "position": 4,
          "name": "{{ addslashes($post->poster_name) }}",
          "item": "{{ $canonicalUrl }}"
        }
      ]
    },
    {
      "@type": "Person",
      "@id": "{{ $canonicalUrl }}#person",
      "name": "{{ addslashes($post->poster_name) }}",
      "description": "{{ addslashes($post->description ?? $bhkLabel . ' flatmate in ' . $locationText) }}",
      "url": "{{ $canonicalUrl }}",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "{{ addslashes($post->locality ?: $post->city) }}",
        "addressRegion": "{{ addslashes($post->city) }}",
        "addressCountry": "IN"
      }
    },
    {
      "@type": "Accommodation",
      "@id": "{{ $canonicalUrl }}#accommodation",
      "name": "{{ addslashes($seoShowTitle) }}",
      "description": "{{ addslashes($seoShowDesc) }}",
      "url": "{{ $canonicalUrl }}",
      "offers": {
        "@type": "Offer",
        "price": "{{ (int)($post->budget_max ?: ($post->budget_min ?: 0)) }}",
        "priceCurrency": "INR",
        "availability": "https://schema.org/InStock",
        "url": "{{ $canonicalUrl }}"
      }
    }
  ]
}
</script>
@endpush

@push('styles')
<style>
    /* Scoped High-Impact Styling for Roommate Detail Page */
    .card-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -10px rgba(15, 23, 42, 0.12);
    }
    .wa-contact-header {
        background: linear-gradient(135deg, #075e54 0%, #128c7e 100%) !important;
        color: #ffffff !important;
    }
    .wa-main-btn {
        background: linear-gradient(135deg, #25D366 0%, #075e54 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 8px 20px -4px rgba(7, 94, 84, 0.4);
    }
    .wa-main-btn:hover {
        background: linear-gradient(135deg, #20bd5a 0%, #054840 100%) !important;
        box-shadow: 0 10px 24px -4px rgba(7, 94, 84, 0.5);
    }
    .badge-glow {
        box-shadow: 0 0 12px rgba(75, 181, 157, 0.25);
    }
    .hero-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.25s ease;
    }
    .hero-stat-card:hover {
        border-color: #4bb59d;
        box-shadow: 0 6px 18px -4px rgba(75, 181, 157, 0.15);
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f8fafc] pt-16 md:pt-8 pb-24 md:pb-16">
    <div class="w-full max-w-7xl mx-auto px-3 sm:px-4 md:px-6">

        <!-- Breadcrumb Navigation for SEO & Desktop UX -->
        <nav aria-label="Breadcrumb" class="hidden md:flex mb-5 text-xs text-gray-500 items-center justify-between px-1">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('user.home') }}" class="hover:text-[#4bb59d] transition flex items-center gap-1.5 text-gray-600 font-medium">
                    <i class="fas fa-home text-xs text-gray-400"></i> Home
                </a>
                <i class="fas fa-chevron-right text-[9px] text-gray-300"></i>
                <a href="{{ route('user.roommate.index') }}" class="hover:text-[#4bb59d] transition text-gray-600 font-medium">
                    Find Flatmate &amp; Roommate
                </a>
                @if(!empty($post->city))
                    <i class="fas fa-chevron-right text-[9px] text-gray-300"></i>
                    <a href="{{ route('user.roommate.index', ['city' => $post->city]) }}" class="hover:text-[#4bb59d] transition font-medium text-gray-700">
                        {{ ucfirst($post->city) }}
                    </a>
                @endif
                <i class="fas fa-chevron-right text-[9px] text-gray-300"></i>
                <span class="font-bold text-[#3a9a85]">{{ $post->poster_name }}</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fas fa-shield-check text-xs text-emerald-600"></i> 100% Zero Brokerage
                </span>
            </div>
        </nav>

        <!-- Detail Page Skeleton Shimmer Placeholder (For fast transitions & instant loading preview) -->
        <div id="roommateDetailSkeleton" class="hidden w-full space-y-6 animate-in fade-in duration-200">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Main Content Skeleton -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="h-6 w-36 bg-gray-200 rounded-xl skeleton-shimmer"></div>
                            <div class="h-5 w-24 bg-gray-100 rounded-lg skeleton-shimmer"></div>
                        </div>
                        <div class="flex items-center gap-5">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gray-200 skeleton-shimmer flex-shrink-0"></div>
                            <div class="flex-1 space-y-2.5">
                                <div class="h-7 w-48 bg-gray-200 rounded-lg skeleton-shimmer"></div>
                                <div class="h-4 w-36 bg-gray-100 rounded skeleton-shimmer"></div>
                                <div class="h-6 w-40 bg-gray-100 rounded-xl skeleton-shimmer"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                            <div class="h-16 bg-gray-100 rounded-2xl skeleton-shimmer"></div>
                            <div class="h-16 bg-gray-100 rounded-2xl skeleton-shimmer"></div>
                            <div class="h-16 bg-gray-100 rounded-2xl skeleton-shimmer"></div>
                        </div>
                        <div class="h-24 bg-gray-50 rounded-2xl border border-gray-100 skeleton-shimmer"></div>
                    </div>
                    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-4">
                        <div class="h-6 w-48 bg-gray-200 rounded-lg skeleton-shimmer"></div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="h-20 bg-gray-100 rounded-2xl skeleton-shimmer"></div>
                            <div class="h-20 bg-gray-100 rounded-2xl skeleton-shimmer"></div>
                            <div class="h-20 bg-gray-100 rounded-2xl skeleton-shimmer"></div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar Skeleton -->
                <div class="space-y-5">
                    <div class="bg-white rounded-3xl border border-gray-200 shadow-md p-6 space-y-4">
                        <div class="h-16 bg-gray-200 rounded-2xl skeleton-shimmer"></div>
                        <div class="h-12 bg-gray-200 rounded-2xl skeleton-shimmer"></div>
                        <div class="h-28 bg-gray-100 rounded-2xl skeleton-shimmer"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advertisement Banner -->
        @include('user.partials.ad-banner')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8" id="roommateDetailContent">

            {{-- ════════════════════ LEFT / MAIN CONTENT COLUMN ════════════════════ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Owner Management Bar (When viewing own listing) --}}
                @auth
                    @if(Auth::id() === $post->user_id)
                    <div class="bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 text-white rounded-3xl p-5 shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-teal-800/50">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-[#4bb59d]/20 text-[#4bb59d] flex items-center justify-center text-lg border border-[#4bb59d]/40 flex-shrink-0">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs font-black uppercase tracking-wider text-teal-300">Your Active Listing</h3>
                                    <span class="bg-emerald-500/20 text-emerald-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-400/30">Live on Search</span>
                                </div>
                                <p class="text-xs text-gray-300 mt-0.5">Manage inquiries, update preferences, or mark room as filled.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 w-full sm:w-auto flex-shrink-0">
                            <a href="{{ route('user.roommate.edit', $post->slug) }}" 
                               class="flex-1 sm:flex-initial bg-white/10 hover:bg-white/20 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition tap-effect flex items-center justify-center gap-1.5 border border-white/20 shadow-xs">
                                <i class="fas fa-pen-to-square text-xs"></i> Edit Details
                            </a>
                            <form method="POST" action="{{ route('user.roommate.fill', $post->slug) }}" onsubmit="return confirm('🎉 Mark this room as FILLED? Your active post count will reset to 0.');" class="flex-1 sm:flex-initial">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-4 py-2.5 rounded-xl text-xs transition tap-effect flex items-center justify-center gap-1.5 shadow-sm whitespace-nowrap cursor-pointer">
                                    <i class="fas fa-check-circle"></i> Mark FILLED
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                @endauth

                {{-- 1. HERO PROFILE & ROOM CARD --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 sm:p-7 md:p-8 relative overflow-hidden">
                    
                    {{-- Top Status Header Ribbon --}}
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 flex-wrap gap-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-black uppercase tracking-wider px-3 py-1.5 rounded-xl border bg-emerald-50 text-emerald-800 border-emerald-200 flex items-center gap-1.5 shadow-xs">
                                <i class="fas fa-door-open text-emerald-600 text-xs"></i>
                                <span>{{ $post->post_type === 'have_room' ? 'Room Available' : 'Looking for Flatmate' }}</span>
                            </span>
                       
                        </div>

                        <div class="flex items-center gap-3 text-xs text-gray-500 font-semibold">
                            <span class="flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200">
                                <i class="fas fa-eye text-gray-400 text-xs"></i> <span>{{ $post->view_count }} views</span>
                            </span>
                            <span class="flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200">
                                <i class="fas fa-clock text-gray-400 text-xs"></i> <span>{{ $post->created_at->diffForHumans() }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Avatar & Poster Info Details --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 pb-6">
                        
                        {{-- Avatar with Online Indicator --}}
                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-3xl flex-shrink-0 overflow-hidden border-2 border-emerald-100 shadow-md bg-gradient-to-br from-[#e6f7f3] via-teal-50 to-emerald-100 flex items-center justify-center text-4xl sm:text-5xl {{ $post->poster_avatar_url ? 'skeleton-shimmer' : '' }}">
                            @if($post->poster_avatar_url)
                                <img src="{{ $post->poster_avatar_url }}" alt="{{ $post->poster_name }}" class="w-full h-full object-cover" loading="eager" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                            @else
                                <span>{{ $post->gender_icon }}</span>
                            @endif
                            <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white shadow-sm" title="Online & Verified"></span>
                        </div>

                        {{-- Name, Occupation & Location --}}
                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">{{ $post->poster_name }}</h1>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200" title="Verified Member">
                                    <i class="fas fa-circle-check text-blue-500"></i> Verified
                                </span>
                            </div>

                            <div class="text-xs sm:text-sm font-bold text-gray-700 flex items-center gap-2 flex-wrap">
                                @if($post->poster_age)
                                    <span class="bg-gray-100 text-gray-800 px-2.5 py-0.5 rounded-md text-xs font-bold">{{ $post->poster_age }} yrs</span>
                                    <span class="text-gray-300">&bull;</span>
                                @endif
                                <span class="capitalize text-gray-700">{{ $posterGender === 'female' ? 'Female Flatmate' : ($posterGender === 'male' ? 'Male Flatmate' : 'Individual') }}</span>
                                <span class="text-gray-300">&bull;</span>
                                <span class="text-[#3a9a85] font-black">{{ $post->profession ?: $occupationText }}</span>
                            </div>

                            {{-- Location Pill --}}
                            <div class="inline-flex items-center gap-2 text-xs font-bold text-gray-800 bg-gray-50 hover:bg-gray-100 px-3.5 py-1.5 rounded-xl border border-gray-200 shadow-xs transition">
                                <i class="fas fa-location-dot text-[#4bb59d] text-sm"></i>
                                <span>{{ $locationText }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 4-Pillar Key Highlights Stat Grid --}}
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                        
                        {{-- 1. Monthly Rent --}}
                        <div class="hero-stat-card rounded-2xl p-4 flex items-center gap-3.5 bg-gradient-to-br from-[#e6f7f3]/90 to-teal-50/50 border-[#4bb59d]/40">
                            <div class="w-11 h-11 rounded-xl bg-[#4bb59d] text-white flex items-center justify-center text-base shadow-sm flex-shrink-0">
                                <i class="fas fa-indian-rupee-sign"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#3a9a85] block">Monthly Rent</span>
                                <span class="text-base sm:text-lg font-black text-gray-900 truncate block">{{ $post->budget_range }}</span>
                            </div>
                        </div>

                        {{-- 2. Move-in Date --}}
                        <div class="hero-stat-card rounded-2xl p-4 flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base shadow-xs flex-shrink-0">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block">Move-in Date</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900 truncate block">{{ $post->move_in_date ? $post->move_in_date->format('d M Y') : 'Immediate' }}</span>
                            </div>
                        </div>

                        {{-- 3. Brokerage / Fee --}}
                        <!-- <div class="hero-stat-card rounded-2xl p-4 flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-base shadow-xs flex-shrink-0">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block">Brokerage</span>
                                <span class="text-xs sm:text-sm font-black text-emerald-700 truncate block">Zero / Direct</span>
                            </div>
                        </div> -->

                        {{-- 4. Room Layout --}}
                        <div class="hero-stat-card rounded-2xl p-4 flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-base shadow-xs flex-shrink-0">
                                <i class="fas fa-house-user"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block">Layout</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900 truncate block">{{ $bhkLabel }}</span>
                            </div>
                        </div>

                    </div>

                    {{-- Description / Bio Card --}}
                    @if($post->description)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h2 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-3 flex items-center gap-2">
                            <i class="fas fa-align-left text-[#4bb59d]"></i>
                            <span>About Flat &amp; Flatmate</span>
                        </h2>
                        <div class="p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-gray-200 text-xs sm:text-sm text-gray-800 leading-relaxed whitespace-pre-line font-medium shadow-xs">
                            {{ $post->description }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 2. PREFERRED FLATMATE COMPATIBILITY CRITERIA CARD --}}
                <div class="rounded-3xl p-5 sm:p-7 md:p-8 border shadow-sm relative overflow-hidden transition-all
                    {{ $genderPref === 'female' 
                        ? 'bg-gradient-to-br from-pink-50/90 via-white to-rose-50/50 border-pink-200' 
                        : ($genderPref === 'male' 
                            ? 'bg-gradient-to-br from-blue-50/90 via-white to-sky-50/50 border-blue-200' 
                            : 'bg-gradient-to-br from-emerald-50/90 via-white to-teal-50/50 border-emerald-200') }}">
                    
                    {{-- Header Ribbon --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg shadow-xs
                                {{ $genderPref === 'female' ? 'bg-pink-100 text-pink-700' : ($genderPref === 'male' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                                ✨
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-500 block">Flatmate Compatibility</span>
                                <h2 class="text-base sm:text-lg font-black text-gray-900 leading-tight">
                                    Preferred Roommate Criteria
                                </h2>
                            </div>
                        </div>

                        {{-- Gender Requirement Hero Tag --}}
                        <div class="px-4 py-2 rounded-2xl text-xs font-black flex items-center gap-2 shadow-sm
                            {{ $genderPref === 'female' 
                                ? 'bg-pink-600 text-white shadow-pink-200' 
                                : ($genderPref === 'male' 
                                    ? 'bg-blue-600 text-white shadow-blue-200' 
                                    : 'bg-emerald-600 text-white shadow-emerald-200') }}">
                            <span class="text-base">{{ $genderPref === 'female' ? '👩' : ($genderPref === 'male' ? '👨' : '🧑‍🤝‍🧑') }}</span>
                            <span class="uppercase tracking-wide text-xs">
                                {{ $genderPref === 'female' ? 'Girls Only Preferred' : ($genderPref === 'male' ? 'Boys Only Preferred' : 'Any Gender Welcome') }}
                            </span>
                        </div>
                    </div>

                    {{-- 6 Quick Criteria Cards Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
                        
                        {{-- 1. Gender Requirement --}}
                        <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">{{ $genderPref === 'female' ? '👩' : ($genderPref === 'male' ? '👨' : '🧑‍🤝‍🧑') }}</span>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-md {{ $genderPref === 'female' ? 'bg-pink-100 text-pink-700' : ($genderPref === 'male' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">Requirement</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wide font-extrabold block">Gender</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900 capitalize">{{ $genderPref === 'any' ? 'Any Gender' : $genderPref . ' Only' }}</span>
                            </div>
                        </div>

                        {{-- 2. Occupation --}}
                        <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">💼</span>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 bg-[#e6f7f3] text-[#3a9a85] rounded-md">Profile</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wide font-extrabold block">Occupation</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900 capitalize">{{ $occupationText }}</span>
                            </div>
                        </div>

                        {{-- 3. Room Type --}}
                        <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">🏠</span>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 bg-purple-100 text-purple-700 rounded-md">Layout</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wide font-extrabold block">Room / Layout</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900">{{ $bhkLabel }}</span>
                            </div>
                        </div>

                        {{-- 4. Furnishing --}}
                        <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">🛋️</span>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-md">Setup</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wide font-extrabold block">Furnishing</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900 capitalize">{{ $furnishingText }}</span>
                            </div>
                        </div>

                        {{-- 5. Stay Duration --}}
                        <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">⏳</span>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 bg-amber-100 text-amber-800 rounded-md">Duration</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wide font-extrabold block">Min Duration</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900">{{ $post->preferred_duration_months ? $post->preferred_duration_months . ' Months' : 'Flexible' }}</span>
                            </div>
                        </div>

                        {{-- 6. Move-in Timeline --}}
                        <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">🚀</span>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md">Timeline</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wide font-extrabold block">Available From</span>
                                <span class="text-xs sm:text-sm font-black text-gray-900">{{ $post->move_in_date ? $post->move_in_date->format('d M Y') : 'Immediate' }}</span>
                            </div>
                        </div>

                    </div>

                    {{-- Lifestyle & House Habits Badges --}}
                    @if($post->lifestyle && count(array_filter($post->lifestyle)) > 0)
                    @php $allOpts = \App\Models\RoommatePost::lifestyleOptions(); @endphp
                    <div class="pt-5 border-t border-gray-200">
                        <div class="text-xs font-black uppercase tracking-wider text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-heart text-rose-500 text-xs"></i>
                            <span>Lifestyle &amp; House Habits</span>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:gap-2.5">
                            @foreach($post->lifestyle as $key => $val)
                                @if($val && isset($allOpts[$key]))
                                <div class="flex items-center gap-2 px-3.5 py-2 bg-white text-gray-800 border border-gray-200 rounded-2xl text-xs font-bold shadow-xs hover:border-[#4bb59d] hover:bg-[#e6f7f3]/30 transition-all">
                                    <span class="text-base">{{ $allOpts[$key]['icon'] }}</span>
                                    <span>{{ $allOpts[$key]['label'] }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 3. FLAT AMENITIES & FACILITIES SECTION --}}
                @if($post->amenities && count(array_filter($post->amenities)) > 0)
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-5 sm:p-7 md:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-xs font-black uppercase tracking-wider text-gray-500 flex items-center gap-2">
                            <i class="fas fa-sparkles text-[#4bb59d]"></i>
                            <span>Amenities &amp; Facilities in Flat</span>
                        </h2>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                            Included in Rent
                        </span>
                    </div>

                    @php $allAmenities = \App\Models\RoommatePost::amenitiesOptions(); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach($post->amenities as $key => $val)
                            @if($val && isset($allAmenities[$key]))
                            <div class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-gray-200 bg-slate-50/70 hover:bg-[#e6f7f3]/40 hover:border-[#4bb59d] transition-all text-center h-24 shadow-xs">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-xs flex items-center justify-center text-[#4bb59d] text-lg mb-1.5 border border-gray-100">
                                    <i class="{{ $allAmenities[$key]['icon'] }}"></i>
                                </div>
                                <span class="text-xs font-bold text-gray-800 truncate w-full">
                                    {{ $allAmenities[$key]['label'] }}
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- ════════════════════ RIGHT / STICKY SIDEBAR COLUMN ════════════════════ --}}
            <div class="space-y-5">

                {{-- Contact & WhatsApp Discussion Hero Card --}}
                <div id="messageSection" class="bg-white rounded-3xl border border-gray-200 shadow-md p-5 sticky top-24 overflow-hidden">
                    
                    {{-- WhatsApp Header Ribbon (High contrast, solid fallback styles) --}}
                    <div class="wa-contact-header -mx-5 -mt-5 p-4 sm:p-5 mb-5 shadow-sm flex items-center justify-between"
                         style="background: linear-gradient(135deg, #075e54 0%, #128c7e 100%); color: #ffffff;">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0">
                                <div class="w-11 h-11 rounded-2xl bg-white/20 text-white flex items-center justify-center text-xl font-bold border border-white/40 shadow-sm">
                                    {{ $posterGender === 'female' ? '👩' : ($posterGender === 'male' ? '👨' : '🧑') }}
                                </div>
                                <!-- <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-400 border-2 border-[#075e54] rounded-full"></span> -->
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-white flex items-center gap-1.5">
                                    <span>{{ $post->poster_name }}</span>
                                    <i class="fas fa-circle-check text-emerald-300 text-xs"></i>
                                </h3>
                                <p class="text-xs text-emerald-100 font-medium flex items-center gap-1.5 mt-0.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                                    <span>Online </span>
                                </p>
                            </div>
                        </div>
                        <span class="bg-white/20 text-white text-[11px] font-black px-2.5 py-1 rounded-lg border border-white/30 whitespace-nowrap">
                            Zero Brokerage
                        </span>
                    </div>

                    {{-- Primary WhatsApp Chat Action Button (Always 100% visible & high-contrast) --}}
                    <button type="button" 
                            onclick="openWhatsAppChat('{{ $post->slug }}', { poster_name: '{{ addslashes($post->poster_name) }}', poster_gender: '{{ $post->poster_gender }}', bhk_type: '{{ addslashes($bhkLabel) }}', budget_range: '{{ addslashes($post->budget_range) }}', locality: '{{ addslashes($locationText) }}' })"
                            class="wa-main-btn w-full mb-4 font-black py-4 px-4 rounded-2xl text-xs sm:text-sm transition tap-effect flex items-center justify-center gap-2.5 cursor-pointer"
                            style="background: linear-gradient(135deg, #25D366 0%, #075e54 100%); color: #ffffff;">
                        <i class="fab fa-whatsapp text-xl"></i>
                        <span>Open Chat &amp; Discuss</span>
                    </button>

                    <div class="relative flex py-1 items-center mb-4">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink mx-3 text-[11px] uppercase font-bold text-gray-500 tracking-wider">Or Quick In-App Message</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    {{-- Dynamic Notification Alerts --}}
                    <div id="showModAlert" class="hidden mb-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl p-3.5 font-semibold">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-ban text-red-500 text-sm flex-shrink-0 mt-0.5"></i>
                            <div id="showModMsg" class="leading-relaxed"></div>
                        </div>
                    </div>

                    <div id="showSuccessAlert" class="hidden mb-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-2xl p-3.5 font-bold">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-circle-check text-emerald-500 text-sm"></i>
                            <span id="showSuccessMsg">Message sent successfully!</span>
                        </div>
                    </div>

                    @auth
                        @if(Auth::id() === $post->user_id)
                            {{-- Owner View Mode --}}
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-center my-2">
                                <div class="w-10 h-10 rounded-2xl bg-[#4bb59d]/10 text-[#4bb59d] flex items-center justify-center mx-auto mb-2 text-base">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="text-xs font-extrabold text-gray-900">You are the Owner of this Listing</div>
                                <p class="text-xs text-gray-600 mt-1 mb-3">Prospective roommates message you here. View all threads and reply directly with WhatsApp style chatbot assistance.</p>
                                <button type="button" 
                                        onclick="openWhatsAppChat('{{ $post->slug }}', { poster_name: '{{ addslashes($post->poster_name) }}', poster_gender: '{{ $post->poster_gender }}', bhk_type: '{{ addslashes($bhkLabel) }}', budget_range: '{{ addslashes($post->budget_range) }}', locality: '{{ addslashes($locationText) }}' })"
                                        class="w-full bg-[#075e54] hover:bg-[#008069] text-white font-extrabold py-3 px-4 rounded-2xl text-xs shadow-md shadow-emerald-900/20 transition tap-effect flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fab fa-whatsapp text-base"></i>
                                    <span>💬 View &amp; Reply to Inquiries</span>
                                </button>
                            </div>
                        @else
                            {{-- Direct Inquiry Form (Logged in User) --}}
                            <form id="directMessageForm" onsubmit="handleDirectMessageSubmit(event)" class="space-y-3.5">
                                {{-- Sender Identity Pill --}}
                                <div class="bg-slate-50 border border-gray-200 rounded-2xl p-3 flex items-center gap-2.5 text-xs text-gray-700">
                                    <span class="text-gray-500 font-semibold">From:</span>
                                    <span class="font-extrabold text-gray-900 truncate">{{ Auth::user()->name }}</span>
                                    <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-circle-check text-[10px]"></i> Verified
                                    </span>
                                </div>

                                {{-- Quick Prompt Starter Chips --}}
                                <div>
                                    <div class="text-[10px] uppercase font-black text-gray-500 tracking-wider mb-2 flex items-center gap-1">
                                        <i class="fas fa-bolt text-amber-500"></i> Quick Tap Questions:
                                    </div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(['Is this room still available? 🔑', 'I would like to schedule a visit 🗓️', 'What is the deposit & monthly maintenance? 💰', 'What furnishing items are included? 🛋️'] as $prompt)
                                        <button type="button" 
                                                onclick="setShowQuickMsg('{{ addslashes($prompt) }}')"
                                                class="text-xs font-semibold bg-gray-100 hover:bg-[#e6f7f3] hover:text-[#3a9a85] text-gray-700 hover:border-[#4bb59d] px-3 py-1.5 rounded-xl border border-gray-200 transition tap-effect cursor-pointer text-left">
                                            {{ $prompt }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Textarea --}}
                                <div>
                                    <label for="showMsgInput" class="block text-xs font-bold text-gray-800 mb-1.5">
                                        Your Message <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="showMsgInput" rows="3" required maxlength="1000"
                                              placeholder="Introduce yourself (profession, move-in timeline) and ask your questions..."
                                              class="w-full bg-white border border-gray-300 focus:border-[#4bb59d] focus:ring-2 focus:ring-[#4bb59d]/20 rounded-2xl p-3 text-xs text-gray-900 font-medium outline-none transition leading-relaxed shadow-xs"></textarea>
                                    <div class="flex items-center justify-between text-[10px] text-gray-400 mt-1 font-medium">
                                        <span>Zero Brokerage &bull; Strictly Moderated</span>
                                        <span id="showMsgCharCount">0 / 1000</span>
                                    </div>
                                </div>

                                <button type="submit" id="showMsgSubmitBtn"
                                        class="w-full bg-[#4bb59d] hover:bg-[#3a9a85] text-white font-extrabold py-3.5 px-4 rounded-2xl text-xs shadow-md shadow-[#4bb59d]/30 transition tap-effect flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                    <span>Send Message to {{ $post->poster_name }}</span>
                                </button>
                            </form>
                        @endif
                    @else
                        {{-- Guest State Card (Highly legible, crisp, engaging) --}}
                        <div class="bg-gradient-to-br from-emerald-50 via-white to-teal-50 border border-emerald-200 rounded-2xl p-5 text-center mb-2 shadow-xs">
                            <div class="w-12 h-12 rounded-2xl bg-[#4bb59d]/15 text-[#3a9a85] text-2xl flex items-center justify-center mx-auto mb-3 shadow-xs">
                                💬
                            </div>
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-wide">
                                Login to Connect with {{ $post->poster_name }}
                            </h4>
                            <p class="text-xs text-gray-600 mt-1.5 mb-4 leading-relaxed">
                                Chat directly, ask questions, and discuss room terms in WhatsApp style. 100% Free &amp; Zero Brokerage for verified members.
                            </p>
                            <a href="{{ route('user.login') }}"
                               class="block w-full bg-[#4bb59d] hover:bg-[#3a9a85] text-white font-black py-3.5 px-4 rounded-2xl text-xs shadow-md shadow-[#4bb59d]/25 transition tap-effect no-underline">
                                <i class="fas fa-arrow-right-to-bracket mr-1.5"></i> Login to Message &amp; Chat
                            </a>
                        </div>
                    @endauth

                    {{-- Share Listing --}}
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center gap-2">
                        <button onclick="sharePost()" type="button"
                                class="flex-1 flex items-center justify-center gap-2 border border-gray-200 hover:border-[#4bb59d] text-gray-700 hover:text-[#3a9a85] rounded-xl py-2.5 text-xs font-bold transition-all cursor-pointer bg-white">
                            <i class="fas fa-share-nodes text-xs text-[#4bb59d]"></i> Share 
                        </button>
                    </div>

                    {{-- Report Post --}}
                    <button type="button" onclick="reportPost()"
                            class="w-full text-xs text-gray-400 hover:text-red-500 mt-2.5 transition flex items-center justify-center gap-1 cursor-pointer font-medium">
                        <i class="fas fa-flag text-[10px]"></i> Report 
                    </button>
                </div>

                {{-- Safety Tip Advisory Card --}}
                <div class="bg-amber-50/95 border border-amber-200 rounded-3xl p-5 text-xs text-amber-900 shadow-xs">
                    <div class="font-black flex items-center gap-2 mb-2 text-amber-900 text-xs">
                        <i class="fas fa-shield-halved text-amber-600 text-sm"></i> Safety &amp; Trust Guidelines
                    </div>
                    <p class="text-[11px] text-amber-800 leading-relaxed font-medium">
                        Always schedule flat visits during daytime. Never transfer any advance deposit or token money before inspecting the room and verifying tenancy agreements in person. SpaceSeeks is a 100% zero brokerage community.
                    </p>
                </div>
            </div>
        </div>

        {{-- ════════════════════ RELATED POSTS (MATCHING SEARCH CARD THEME) ════════════════════ --}}
        @if($related->isNotEmpty())
        <div class="mt-14">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">
                        More Roommate Posts in {{ ucfirst($post->city) }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium">Explore other verified flatmates &amp; shared rooms nearby</p>
                </div>
                <a href="{{ route('user.roommate.index', ['city' => $post->city]) }}" class="text-xs font-extrabold text-[#4bb59d] hover:text-[#3a9a85] hover:underline flex items-center gap-1">
                    <span>View all</span> &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
                @foreach($related as $rel)
                @php
                    $relPref = strtolower($rel->gender_preference ?? 'any');
                    $relBhk = $bhkLabels[$rel->bhk_type] ?? $rel->bhk_type;
                @endphp
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-200 card-lift group flex flex-col justify-between">
                    <div>
                        <div class="relative h-28 sm:h-36 overflow-hidden bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900 flex flex-col items-center justify-center p-2 text-center {{ $rel->poster_avatar_url ? 'skeleton-shimmer' : '' }}">
                            @if($rel->poster_avatar_url)
                                <img src="{{ $rel->poster_avatar_url }}" alt="{{ $rel->poster_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90" loading="lazy" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            @else
                                <div class="relative z-10 w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                    <span class="text-2xl sm:text-3xl">{{ $rel->gender_icon }}</span>
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 border-2 border-slate-950 rounded-full"></span>
                                </div>
                                <div class="relative z-10 text-white font-extrabold text-xs mt-1.5 truncate w-full px-2">
                                    {{ $rel->poster_name }}
                                </div>
                            @endif
                            
                            <div class="absolute top-2 right-2 {{ $relPref === 'female' ? 'bg-pink-600' : ($relPref === 'male' ? 'bg-blue-600' : 'bg-emerald-600') }} text-white text-[9px] font-black px-2 py-0.5 rounded-md shadow-sm z-20">
                                {{ $relPref === 'female' ? 'Girls Only' : ($relPref === 'male' ? 'Boys Only' : 'Any Gender') }}
                            </div>
                        </div>

                        <div class="p-3.5">
                            <h3 class="font-black text-xs sm:text-sm text-gray-900 group-hover:text-[#4bb59d] transition truncate flex items-center gap-1.5">
                                <span class="truncate">{{ $rel->poster_name }}</span>
                                <i class="fas fa-circle-check text-[#4bb59d] text-[11px] flex-shrink-0"></i>
                            </h3>
                            <p class="text-[11px] text-gray-500 truncate mt-0.5 font-medium">{{ $rel->locality ?: $rel->city }}</p>
                            <p class="text-xs font-bold text-emerald-700 mt-1 truncate">{{ $relBhk }}</p>
                        </div>
                    </div>

                    <div class="px-3.5 pb-3.5 pt-2 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-black text-gray-900">{{ $rel->budget_range }}</span>
                        <a href="{{ route('user.roommate.show', $rel->slug) }}" class="bg-gray-100 hover:bg-[#e6f7f3] hover:text-[#3a9a85] text-gray-800 px-3 py-1.5 rounded-xl text-[11px] font-extrabold transition tap-effect no-underline">
                            View
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

{{-- MOBILE FIXED BOTTOM ACTION BAR --}}
@auth
    @if(Auth::id() === $post->user_id)
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200 p-3 flex items-center gap-2.5 md:hidden shadow-2xl">
        <button type="button" 
                onclick="openWhatsAppChat('{{ $post->slug }}', { poster_name: '{{ addslashes($post->poster_name) }}', poster_gender: '{{ $post->poster_gender }}', bhk_type: '{{ addslashes($bhkLabel) }}', budget_range: '{{ addslashes($post->budget_range) }}', locality: '{{ addslashes($locationText) }}' })"
                class="flex-1 bg-[#075e54] text-white font-black py-3.5 px-4 rounded-2xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/25 transition tap-effect cursor-pointer">
            <i class="fab fa-whatsapp text-lg"></i>
            <span>View &amp; Reply to Inquiries</span>
        </button>
    </div>
    @else
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200 p-3 flex items-center gap-2.5 md:hidden shadow-2xl">
        <button type="button" 
                onclick="openWhatsAppChat('{{ $post->slug }}', { poster_name: '{{ addslashes($post->poster_name) }}', poster_gender: '{{ $post->poster_gender }}', bhk_type: '{{ addslashes($bhkLabel) }}', budget_range: '{{ addslashes($post->budget_range) }}', locality: '{{ addslashes($locationText) }}' })"
                class="flex-1 bg-gradient-to-r from-[#25D366] to-[#075e54] text-white font-black py-3.5 px-4 rounded-2xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/25 transition tap-effect cursor-pointer">
            <i class="fab fa-whatsapp text-lg"></i>
            <span>Chat on WhatsApp Style</span>
        </button>
    </div>
    @endif
@else
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200 p-3 flex items-center justify-between gap-3 md:hidden shadow-2xl">
        <div>
            <div class="text-xs font-black text-gray-900">{{ $post->budget_range }}</div>
            <div class="text-[10px] font-bold text-emerald-700">Zero Brokerage</div>
        </div>
        <a href="{{ route('user.login') }}" 
           class="bg-[#075e54] hover:bg-[#008069] text-white font-black px-5 py-2.5 rounded-2xl text-xs shadow-md flex items-center gap-1.5 no-underline">
            <i class="fab fa-whatsapp text-base"></i>
            <span>Login to Chat</span>
        </a>
    </div>
@endauth
@endsection

@push('scripts')
<script>
    // Quick starter message injector
    function setShowQuickMsg(msg) {
        const input = document.getElementById('showMsgInput');
        if (input) {
            input.value = msg;
            input.focus();
            const counter = document.getElementById('showMsgCharCount');
            if (counter) counter.innerText = `${msg.length} / 1000`;
        }
    }

    // Direct message form submit with live feedback
    async function handleDirectMessageSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('showMsgInput');
        const submitBtn = document.getElementById('showMsgSubmitBtn');
        const modAlert = document.getElementById('showModAlert');
        const modMsg = document.getElementById('showModMsg');
        const successAlert = document.getElementById('showSuccessAlert');
        const successMsg = document.getElementById('showSuccessMsg');

        if (!input || !submitBtn) return;
        const msg = input.value.trim();
        if (msg.length < 2) return;

        if (modAlert) modAlert.classList.add('hidden');
        if (successAlert) successAlert.classList.add('hidden');

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin text-xs"></i> <span>Sending Message...</span>`;

        try {
            const res = await fetch(`/find-roommate/{{ $post->slug }}/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                input.value = '';
                const counter = document.getElementById('showMsgCharCount');
                if (counter) counter.innerText = '0 / 1000';
                if (successAlert) {
                    successAlert.classList.remove('hidden');
                    if (successMsg) successMsg.innerText = data.message || 'Message sent! Flatmate has been notified.';
                }

                // Also automatically open chat modal after 600ms
                setTimeout(() => {
                    openWhatsAppChat('{{ $post->slug }}', {
                        poster_name: '{{ addslashes($post->poster_name) }}',
                        poster_gender: '{{ $post->poster_gender }}',
                        bhk_type: '{{ addslashes($bhkLabel) }}',
                        budget_range: '{{ addslashes($post->budget_range) }}',
                        locality: '{{ addslashes($locationText) }}'
                    });
                }, 700);
            } else {
                if (modAlert) {
                    modAlert.classList.remove('hidden');
                    if (modMsg) modMsg.innerText = data.message || 'Could not send message. Please review and try again.';
                }
            }
        } catch (err) {
            if (modAlert) {
                modAlert.classList.remove('hidden');
                if (modMsg) modMsg.innerText = 'Network error occurred. Please try again.';
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<i class="fas fa-paper-plane text-xs"></i> <span>Send Message to {{ $post->poster_name }}</span>`;
        }
    }

    // Share Post
    function sharePost() {
        if (navigator.share) {
            navigator.share({
                title: '{{ addslashes($post->poster_name) }} — Flatmate in {{ addslashes($post->city) }}',
                text: 'Looking for a flatmate: {{ addslashes($post->poster_name) }} in {{ addslashes($locationText) }}. Rent: {{ addslashes($post->budget_range) }}',
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('🔗 Link copied to clipboard!');
        }
    }

    // Report Post
    function reportPost() {
        const reason = prompt('Please describe the issue with this listing (e.g. wrong info, already rented, inappropriate):');
        if (reason && reason.trim()) {
            alert('Thank you. Our moderation team will inspect this post within 2 hours.');
        }
    }

    // Character counter
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('showMsgInput');
        const counter = document.getElementById('showMsgCharCount');
        if (input && counter) {
            input.addEventListener('input', () => {
                counter.innerText = `${input.value.length} / 1000`;
            });
        }
    });
</script>
@endpush
