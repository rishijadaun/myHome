@extends('user.layouts.app')

@php
    $isEditMode = isset($isEdit) && $isEdit && isset($post) && $post;
    $actionUrl = $isEditMode ? route('user.roommate.update', $post->slug) : route('user.roommate.store');
    $currentGenderPref = old('gender_preference', $isEditMode ? $post->gender_preference : 'any');
    $currentBhk = old('bhk_type', $isEditMode ? $post->bhk_type : '2bhk');
    $currentFurnishing = old('furnishing', $isEditMode ? $post->furnishing : 'semi_furnished');
    $currentLifestyle = old('lifestyle', $isEditMode ? ($post->lifestyle ?? []) : []);
    $currentAmenities = old('amenities', $isEditMode ? ($post->amenities ?? []) : []);
    
    $profileName = trim(($user?->profile?->first_name ?? '') . ' ' . ($user?->profile?->last_name ?? '')) ?: ($user?->name ?? 'Tenant');
    $profileAvatar = $user?->profile?->avatar_url;
    $profilePhone = $user?->phone ?? 'Registered Phone';
    $profileTagline = $user?->profile?->tagline ?: (($user?->profile?->gender ? ucfirst($user->profile->gender) : 'Tenant') . ' · ' . ($user?->profile?->occupation ?? 'Working Professional'));
@endphp

@section('title', ($isEditMode ? 'Edit Available Room Listing' : 'Find Flatmate or Roommate') . ' — StayNest')
@section('meta_description', 'Post your available room or shared flat on StayNest. Connect directly with verified flatmates with zero brokerage.')
@section('canonical', route('user.roommate.create'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="min-h-screen bg-[#f4f7fc] pt-16 md:pt-6 pb-24 md:pb-12">
    <div class="max-w-4xl mx-auto px-4 md:px-6">

        {{-- Top Navigation Bar --}}
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <a href="{{ route('user.roommate.index') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-brand font-bold transition">
                <i class="fas fa-arrow-left text-[11px]"></i> Back to Explore
            </a>
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-3 py-1 rounded-full flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Tenant Verified · 1 Active Post
                </span>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('flash_success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-3xl p-4 sm:p-5 flex items-start gap-3 text-xs text-emerald-900 shadow-xs animate-fade-in">
            <div class="w-8 h-8 rounded-2xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 text-sm">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-emerald-950 text-sm mb-0.5">Success</h4>
                <p class="leading-relaxed">{{ session('flash_success') }}</p>
            </div>
        </div>
        @endif

        @if(session('flash_info'))
        <div class="mb-5 bg-blue-50 border border-blue-200 rounded-3xl p-4 sm:p-5 flex items-start gap-3 text-xs text-blue-900 shadow-xs">
            <div class="w-8 h-8 rounded-2xl bg-blue-500 text-white flex items-center justify-center flex-shrink-0 text-sm">
                <i class="fas fa-info"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-blue-950 text-sm mb-0.5">Active Listing Found</h4>
                <p class="leading-relaxed">{{ session('flash_info') }}</p>
            </div>
        </div>
        @endif

        @if(session('flash_warning'))
        <div class="mb-5 bg-amber-50 border border-amber-200 rounded-3xl p-4 sm:p-5 flex items-start gap-3 text-xs text-amber-900 shadow-xs">
            <div class="w-8 h-8 rounded-2xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-sm">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-amber-950 text-sm mb-0.5">Notice</h4>
                <p class="leading-relaxed">{{ session('flash_warning') }}</p>
            </div>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-3xl p-4 sm:p-5 text-xs text-red-800 shadow-xs">
            <div class="font-bold flex items-center gap-1.5 mb-1.5 text-red-700 text-sm">
                <i class="fas fa-circle-exclamation"></i> Please resolve the following errors:
            </div>
            <ul class="list-disc list-inside space-y-1 text-red-600 font-medium">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Page Hero Header --}}
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-gradient-to-br from-brand via-brand-dark to-teal-900 rounded-3xl flex items-center justify-center text-white text-2xl mx-auto mb-3 shadow-lg shadow-brand/25">
                <i class="fas fa-door-open"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                {{ $isEditMode ? 'Manage Your Room Listing' : 'Find Flatmate or Roommate' }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-lg mx-auto">
                {{ $isEditMode ? 'Update your room pricing, amenities, or mark as filled when occupied.' : 'Have a vacant room or bed? Connect directly with verified flatmates with zero brokerage.' }}
            </p>
        </div>

        {{-- EDIT MODE: ACTIVE POST ACTIONS & MARK AS FILLED BANNER --}}
        @if($isEditMode)
        <div class="bg-gradient-to-br from-amber-500/10 via-brand-light to-emerald-500/10 border-2 border-brand/30 rounded-3xl p-5 mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-brand text-white flex items-center justify-center text-lg flex-shrink-0 shadow-xs">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-sm text-gray-900">Found a Flatmate / Room Occupied?</h3>
                        <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">
                            Marking this room as <strong>FILLED</strong> will archive this post, reset your active count to 0, and allow you to post a brand new room anytime.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto flex-shrink-0">
                    <form method="POST" action="{{ route('user.roommate.fill', $post->slug) }}" onsubmit="return confirm('🎉 Mark this room as FILLED? Your listing will be closed and your active post count will reset to 0.');">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-4 py-2.5 rounded-2xl text-xs shadow-md shadow-emerald-600/30 transition tap-effect flex items-center justify-center gap-1.5 whitespace-nowrap">
                            <i class="fas fa-circle-check"></i>
                            <span>Mark as FILLED </span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('user.roommate.destroy', $post->slug) }}" onsubmit="return confirm('Are you sure you want to permanently delete this listing?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold px-3 py-2.5 rounded-2xl text-xs transition tap-effect" title="Delete Listing">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- AUTO-CONNECTED USER PROFILE BANNER --}}
        <div class="bg-gradient-to-r from-slate-900 via-gray-900 to-teal-950 text-white rounded-3xl p-4 sm:p-5 mb-6 shadow-md flex items-center justify-between gap-3 border border-white/10">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="relative w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-white/10 flex-shrink-0 overflow-hidden border border-white/20 shadow-xs">
                    @if($profileAvatar)
                        <img src="{{ $profileAvatar }}" alt="{{ $profileName }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl text-white">
                            🧑
                        </div>
                    @endif
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] uppercase font-bold text-teal-300 tracking-wider">Posting As</span>
                        <i class="fas fa-shield-check text-emerald-400 text-[10px]"></i>
                    </div>
                    <div class="font-extrabold text-base text-white truncate">{{ $profileName }}</div>
                    <div class="text-xs text-teal-100/90 truncate mt-0.5 font-medium">
                        {{ $profileTagline }}
                    </div>
                </div>
            </div>

            <a href="{{ route('user.profile') }}" target="_blank" 
               class="text-xs text-brand hover:text-white bg-white/10 hover:bg-white/20 border border-white/15 px-3.5 py-2 rounded-2xl font-bold transition whitespace-nowrap flex-shrink-0 tap-effect">
                <i class="fas fa-user-pen mr-1"></i> Edit Profile
            </a>
        </div>

        {{-- Content Moderation Client Alert Box --}}
        <div id="contentModerationAlert" class="hidden mb-5 bg-red-50 border-2 border-red-300 text-red-800 rounded-3xl p-4 sm:p-5 text-xs font-semibold shadow-xs">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-2xl bg-red-500 text-white flex items-center justify-center flex-shrink-0 text-sm">
                    <i class="fas fa-ban"></i>
                </div>
                <div id="contentModerationMsg" class="leading-relaxed pt-0.5"></div>
            </div>
        </div>

        {{-- MAIN FORM CONTAINER --}}
        <form id="roommatePostForm" method="POST" action="{{ $actionUrl }}" onsubmit="return validateRoommateForm(event)"
              class="space-y-6 bg-white rounded-3xl border border-gray-200/80 shadow-xs p-5 sm:p-8">
            @csrf
            @if($isEditMode)
                @method('PUT')
            @endif

            {{-- ── 1. LOCATION (CITY & LOCALITY) ────────────────────── --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-brand text-white flex items-center justify-center text-[10px] font-bold">1</span>
                        Room Location <span class="text-red-500">*</span>
                    </label>
                    <span class="text-[10px] text-gray-400 font-semibold">Where is your flat located?</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">City <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fas fa-city absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="city" id="postCityInput"
                                   value="{{ old('city', $isEditMode ? $post->city : '') }}"
                                   placeholder="e.g. Noida, Gurgaon, Delhi, Bangalore" required
                                   list="createCityList"
                                   class="w-full bg-gray-50/80 border border-gray-200 rounded-2xl pl-9 pr-4 py-3 text-xs font-bold text-gray-900 focus:outline-none focus:border-brand focus:bg-white transition-all">
                        </div>
                        <datalist id="createCityList">
                            @foreach($popularCities as $c) <option value="{{ $c }}"> @endforeach
                        </datalist>
                        @error('city') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Locality / Sector / Society</label>
                        <div class="relative">
                            <i class="fas fa-location-dot absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="locality" id="postLocalityInput"
                                   value="{{ old('locality', $isEditMode ? $post->locality : '') }}"
                                   placeholder="e.g. Sector 62, Near Metro Station"
                                   class="w-full bg-gray-50/80 border border-gray-200 rounded-2xl pl-9 pr-4 py-3 text-xs font-bold text-gray-900 focus:outline-none focus:border-brand focus:bg-white transition-all">
                        </div>
                        @error('locality') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Quick City Chips --}}
                <div class="flex items-center gap-1.5 flex-wrap mt-2.5">
                    <span class="text-[10px] text-gray-400 font-bold mr-1">Quick Select:</span>
                    @foreach(['Noida', 'Gurgaon', 'Delhi', 'Bangalore', 'Mumbai', 'Pune', 'Hyderabad'] as $quickCity)
                    <button type="button" onclick="document.getElementById('postCityInput').value='{{ $quickCity }}'"
                            class="text-[10px] bg-gray-100/80 hover:bg-brand hover:text-white px-3 py-1 rounded-full font-bold text-gray-600 transition tap-effect">
                        {{ $quickCity }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- ── 2. FLAT & ROOM CONFIGURATION ─────────────────────── --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-brand text-white flex items-center justify-center text-[10px] font-bold">2</span>
                        Flat &amp; Room Configuration <span class="text-red-500">*</span>
                    </label>
                </div>

                {{-- BHK Type Pills --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Select Available Room Type</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($bhkOptions as $key => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="bhk_type" value="{{ $key }}" class="sr-only peer" {{ $currentBhk === $key ? 'checked' : '' }}>
                            <div class="peer-checked:border-brand peer-checked:bg-brand-light/70 peer-checked:text-brand font-bold text-xs border-2 border-gray-100 rounded-2xl p-3 text-center transition-all hover:border-brand/40 shadow-2xs">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('bhk_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Furnishing & Move-in Date --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Furnishing Status</label>
                        <select name="furnishing" class="w-full bg-gray-50/80 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:outline-none focus:border-brand focus:bg-white transition-all">
                            <option value="furnished" {{ $currentFurnishing === 'furnished' ? 'selected' : '' }}>🛋️ Fully Furnished</option>
                            <option value="semi_furnished" {{ $currentFurnishing === 'semi_furnished' ? 'selected' : '' }}>🪑 Semi-Furnished</option>
                            <option value="unfurnished" {{ $currentFurnishing === 'unfurnished' ? 'selected' : '' }}>📦 Unfurnished</option>
                            <option value="any" {{ $currentFurnishing === 'any' ? 'selected' : '' }}>No Preference</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Available Move-in Date</label>
                        <input type="date" name="move_in_date"
                               value="{{ old('move_in_date', $isEditMode && $post->move_in_date ? $post->move_in_date->format('Y-m-d') : '') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full bg-gray-50/80 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:outline-none focus:border-brand focus:bg-white transition-all">
                        @error('move_in_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── 3. MONTHLY RENT & BUDGET ─────────────────────────── --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-brand text-white flex items-center justify-center text-[10px] font-bold">3</span>
                        Monthly Rent per Flatmate <span class="text-red-500">*</span>
                    </label>
                    <span class="text-[10px] text-gray-400 font-semibold">Zero Brokerage</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Expected Rent (₹ / month) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-black text-sm">₹</span>
                            <input type="number" name="budget_max" id="rentInput"
                                   value="{{ old('budget_max', $isEditMode ? $post->budget_max : '') }}"
                                   placeholder="e.g. 12000" min="1000" step="500" required
                                   class="w-full bg-gray-50/80 border border-gray-200 rounded-2xl pl-8 pr-4 py-3 text-sm font-black text-gray-900 focus:outline-none focus:border-brand focus:bg-white transition-all">
                        </div>
                        @error('budget_max') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                        {{-- Quick Rent Chips --}}
                        <div class="flex items-center gap-1.5 flex-wrap mt-2.5">
                            <span class="text-[10px] text-gray-400 font-bold mr-1">Quick:</span>
                            @foreach([5000, 8000, 10000, 12000, 15000, 20000] as $amount)
                            <button type="button" onclick="document.getElementById('rentInput').value='{{ $amount }}'"
                                    class="text-[10px] bg-gray-100 hover:bg-brand hover:text-white px-2.5 py-0.5 rounded-full font-bold text-gray-600 transition tap-effect">
                                ₹{{ number_format($amount) }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Minimum Stay Duration</label>
                        <select name="preferred_duration_months" class="w-full bg-gray-50/80 border border-gray-200 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:outline-none focus:border-brand focus:bg-white transition-all">
                            <option value="">Flexible / Any Duration</option>
                            <option value="3" {{ old('preferred_duration_months', $isEditMode ? $post->preferred_duration_months : '') == 3 ? 'selected' : '' }}>Minimum 3 Months</option>
                            <option value="6" {{ old('preferred_duration_months', $isEditMode ? $post->preferred_duration_months : '') == 6 ? 'selected' : '' }}>Minimum 6 Months</option>
                            <option value="12" {{ old('preferred_duration_months', $isEditMode ? $post->preferred_duration_months : '') == 12 ? 'selected' : '' }}>Minimum 1 Year</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── 4. AMENITIES SECTION (MATCHING SCREENSHOT) ─────────── --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-brand text-white flex items-center justify-center text-[10px] font-bold">4</span>
                        Amenities in Flat
                    </label>
                    <span class="text-[10px] text-gray-400 font-semibold">Select all available</span>
                </div>

                {{-- Square Rounded Cards matching screenshot --}}
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5 sm:gap-3">
                    @foreach($amenitiesOptions as $key => $amenity)
                    <label class="cursor-pointer group">
                        <input type="checkbox" name="amenities[{{ $key }}]" value="1" class="sr-only peer"
                               {{ !empty($currentAmenities[$key]) ? 'checked' : '' }}>
                        <div class="flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-gray-100 bg-white group-hover:border-gray-200 peer-checked:border-brand peer-checked:bg-brand-light/60 peer-checked:shadow-xs transition-all text-center h-24">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 group-hover:bg-gray-100 peer-checked:bg-white flex items-center justify-center text-xl mb-1.5 transition-colors">
                                <i class="{{ $amenity['icon'] }} text-gray-700 peer-checked:text-brand text-lg"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-800 peer-checked:text-brand line-clamp-1">
                                {{ $amenity['label'] }}
                            </span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- ── 5. FLATMATE PREFERENCE & LIFESTYLE (HIGHLIGHTED) ──────────────────── --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="bg-gradient-to-br from-brand-light/50 via-white to-amber-50/40 p-5 rounded-3xl border border-brand/20 shadow-xs mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-black uppercase tracking-wider text-brand flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-xs font-black shadow-xs">5</span>
                            <span>✨ Preferred Flatmate &amp; Compatibility Criteria</span> <span class="text-red-500">*</span>
                        </label>
                        <span class="text-[10px] bg-brand text-white font-bold px-2 py-0.5 rounded-full">Key Match Factor</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                        Specify your ideal flatmate's gender, food preferences, and daily vibes to attract compatible roommates faster.
                    </p>

                    {{-- Gender Preference Highlight Cards --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 mb-2 flex items-center justify-between">
                            <span>Flatmate Gender Requirement <span class="text-red-500">*</span></span>
                            <span class="text-[10px] text-gray-400 font-medium">Select one</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2.5">
                            <label class="cursor-pointer">
                                <input type="radio" name="gender_preference" value="female" class="sr-only peer" {{ $currentGenderPref === 'female' ? 'checked' : '' }}>
                                <div class="peer-checked:border-pink-500 peer-checked:bg-pink-50/80 peer-checked:text-pink-800 peer-checked:shadow-sm font-black text-xs border-2 border-gray-200/80 bg-white rounded-2xl p-3.5 text-center transition-all hover:border-pink-300 shadow-2xs flex flex-col items-center gap-1">
                                    <span class="text-xl">👩</span>
                                    <span>Girls Only</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="gender_preference" value="male" class="sr-only peer" {{ $currentGenderPref === 'male' ? 'checked' : '' }}>
                                <div class="peer-checked:border-blue-500 peer-checked:bg-blue-50/80 peer-checked:text-blue-800 peer-checked:shadow-sm font-black text-xs border-2 border-gray-200/80 bg-white rounded-2xl p-3.5 text-center transition-all hover:border-blue-300 shadow-2xs flex flex-col items-center gap-1">
                                    <span class="text-xl">👨</span>
                                    <span>Boys Only</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="gender_preference" value="any" class="sr-only peer" {{ $currentGenderPref === 'any' ? 'checked' : '' }}>
                                <div class="peer-checked:border-emerald-500 peer-checked:bg-emerald-50/80 peer-checked:text-emerald-800 peer-checked:shadow-sm font-black text-xs border-2 border-gray-200/80 bg-white rounded-2xl p-3.5 text-center transition-all hover:border-emerald-300 shadow-2xs flex flex-col items-center gap-1">
                                    <span class="text-xl">🧑‍🤝‍🧑</span>
                                    <span>Any Gender</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Lifestyle Tags --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 flex items-center justify-between">
                            <span>Flat Habits &amp; Lifestyle Tags</span>
                            <span class="text-[10px] text-gray-400 font-medium">Select all that apply</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($lifestyleOptions as $key => $opt)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="lifestyle[{{ $key }}]" value="1" class="sr-only peer"
                                       {{ !empty($currentLifestyle[$key]) ? 'checked' : '' }}>
                                <div class="peer-checked:border-brand peer-checked:bg-brand-light/70 peer-checked:text-brand bg-white border-2 border-gray-200/80 rounded-2xl p-2.5 text-xs font-bold text-gray-700 transition-all flex items-center gap-2 hover:border-brand/40 shadow-2xs">
                                    <span>{{ $opt['icon'] }}</span>
                                    <span class="truncate">{{ $opt['label'] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 6. ROOM HIGHLIGHTS & PROHIBITED CONTENT FILTER ──────── --}}
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-brand text-white flex items-center justify-center text-[10px] font-bold">6</span>
                        About the Room &amp; Description
                    </label>
                    <span class="text-[10px] text-amber-600 font-bold flex items-center gap-1">
                        <i class="fas fa-shield-halved"></i> Strictly Filtered
                    </span>
                </div>

                <div class="relative">
                    <textarea name="description" id="postDescriptionInput" rows="4" maxlength="1500"
                              placeholder="e.g. Master bedroom available in a luxury 3BHK flat. Fully furnished with AC, high-speed WiFi, attached balcony, and cook available. Looking for a clean, non-smoking flatmate."
                              class="w-full bg-gray-50/80 border border-gray-200 rounded-2xl p-4 text-xs font-medium focus:outline-none focus:border-brand focus:bg-white transition-all leading-relaxed">{{ old('description', $isEditMode ? $post->description : '') }}</textarea>
                </div>
                <div class="flex items-center justify-between text-[10px] text-gray-400 mt-1.5">
                    <span>Describe room details, attached washroom, balcony, or house rules.</span>
                    <span id="charCount">0 / 1500</span>
                </div>
                @error('description')
                <p class="text-xs text-red-500 font-bold mt-2 bg-red-50 p-3 rounded-2xl border border-red-200 flex items-center gap-1.5">
                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                </p>
                @enderror
            </div>

            {{-- ── SUBMIT BUTTON & ACTIONS ─────────────────────────────── --}}
            <div class="pt-3">
                <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-brand via-brand-dark to-teal-900 hover:opacity-95 text-white font-extrabold py-4 px-6 rounded-2xl shadow-xl shadow-brand/25 transition-all text-xs sm:text-sm tap-effect flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle text-base"></i>
                    <span>{{ $isEditMode ? 'Save & Update Room Listing' : 'Publish Available Room — Free & Live Instantly' }}</span>
                </button>
                <p class="text-[10px] text-gray-400 text-center mt-2.5">
                    🔒 Zero Brokerage · Your post is shown across verified flatmate searches across India.
                </p>
            </div>

        </form>

    </div>
</div>

<script>
// Character Counter
const descEl = document.getElementById('postDescriptionInput');
const countEl = document.getElementById('charCount');
if (descEl && countEl) {
    countEl.innerText = `${descEl.value.length} / 1500`;
    descEl.addEventListener('input', () => {
        countEl.innerText = `${descEl.value.length} / 1500`;
    });
}

// Client-side Content Moderation Filter (Matches ContentModerationService patterns)
const prohibitedTermsRegex = [
    {
        category: 'Vulgarity / Profanity',
        regex: /\b(fuck|fucking|fucked|fucker|fck|bitch|bitches|bastard|asshole|arsehole|dickhead|chutiya|chootiya|chutya|choot|bhenchod|behenchod|bc|madarchod|mc|gaand|gandu|harami|harampaye|lavde|lauda|loda|lodu|bhosadi|bhosdike|randi|kutiya|kamina|kameena|shit|cunt|cunts|pussy|dick|penis|vagina|boobs|tits)\b/i
    },
    {
        category: 'Sexual Content & Escorts',
        regex: /\b(sex|sexy|sexual|intercourse|sax\s*sux|sax|sux|call\s*girl|call\s*girls|call\s*boy|call\s*boys|escort\s*service|escort|escorts|russian\s*girl|paid\s*service|paid\s*sex|adult\s*service|adult|sex\s*service|nude|nudes|nudity|naked|porn|xxx|erotic|sensual\s*massage|massage\s*with\s*extra|happy\s*ending|onlyfans|night\s*service|female\s*companion|gigolo|sugar\s*daddy|sugar\s*baby|hookup|casual\s*sex)\b/i
    },
    {
        category: 'Substances & Illicit Drugs',
        regex: /\b(cocaine|heroin|charas|ganja|weed|weed\s*available|weed\s*seller|buy\s*weed|cannabis|cannabis\s*sale|meth|crystal\s*meth|mdma|ecstasy|lsd|smack|brown\s*sugar|narcotics|drug\s*party|opium|afim|afeem|bhang\s*available|dealer\s*contact)\b/i
    }
];

function validateRoommateForm(e) {
    const alertBox = document.getElementById('contentModerationAlert');
    const alertMsg = document.getElementById('contentModerationMsg');
    alertBox.classList.add('hidden');

    const descInput = document.getElementById('postDescriptionInput');
    const cityInput = document.getElementById('postCityInput');
    const localityInput = document.getElementById('postLocalityInput');

    const fieldsToCheck = [
        { name: 'Description', input: descInput },
        { name: 'City', input: cityInput },
        { name: 'Locality', input: localityInput }
    ];

    for (const item of fieldsToCheck) {
        if (!item.input) continue;
        const text = item.input.value.trim();
        if (!text) continue;

        for (const rule of prohibitedTermsRegex) {
            const match = text.match(rule.regex);
            if (match) {
                e.preventDefault();
                const matchedWord = match[0];
                alertMsg.innerHTML = `⚠️ <strong>${item.name}</strong> contains prohibited content in category <strong>[${rule.category}]</strong> ("${matchedWord}"). Please remove inappropriate terms to continue.`;
                alertBox.classList.remove('hidden');
                item.input.classList.add('border-red-400', 'bg-red-50');
                item.input.focus();
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        }
        item.input.classList.remove('border-red-400', 'bg-red-50');
    }

    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Publishing Room Listing...</span>';
    btn.disabled = true;
    return true;
}
</script>
@endsection
