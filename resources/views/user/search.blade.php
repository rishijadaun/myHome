@extends('user.layouts.app')

@section('title', 'Search Properties - StayNest')

@push('styles')
<style>
    .drawer-btn {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .drawer-btn.active {
        background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%);
        color: white !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(75, 181, 157, 0.3);
    }
    .card-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="pt-16 md:pt-8 pb-24 md:pb-16 w-full max-w-7xl mx-auto px-2 sm:px-4 md:px-6">

    <!-- ================= UNIFIED TOP SEARCH & FILTER TOGGLE HEADER ================= -->
    <div class="mb-4 sm:mb-5">
        <!-- Search Row with App-Style Filter Toggle Button -->
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3.5 sm:left-4 top-3.5 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" value="{{ $searchQuery ?? '' }}" onkeyup="filterSearchResults()" placeholder="Search city, locality, PG name..." 
                    class="w-full bg-white border border-gray-200 rounded-2xl py-3 pl-10 sm:pl-11 pr-3 sm:pr-4 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50 shadow-sm transition">
            </div>

            <!-- App-style Filter Toggle Button (Desktop & Mobile) -->
            <button onclick="toggleFilterBar()" id="mainFilterToggleBtn" class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 h-11 bg-white border border-gray-200 hover:border-brand hover:text-brand rounded-2xl text-gray-700 tap-effect shadow-sm flex-shrink-0 transition">
                <i class="fas fa-sliders-h text-sm text-brand"></i>
                <span class="text-xs sm:text-sm font-semibold">Filters</span>
                <span id="activeFilterBadge" class="hidden w-5 h-5 bg-brand text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">0</span>
            </button>

            <!-- Reset Filter Button (Desktop) -->
            <button onclick="resetAllFilters()" id="desktopQuickResetBtn" class="hidden md:flex items-center gap-1.5 px-4 h-11 border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-2xl font-semibold transition tap-effect text-sm flex-shrink-0">
                <i class="fas fa-undo-alt text-xs"></i> Reset
            </button>
        </div>

        <!-- ================= EXPANDABLE DESKTOP FILTER BAR (TOGGLED) ================= -->
        <div id="desktopFilterPanel" class="hidden mt-4 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 transition-all duration-300">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">City / Location</label>
                    <select id="desktopCitySelect" onchange="setCityFromSelect(this.value)" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">All Cities</option>
                        @foreach($cities as $c)
                            <option value="{{ $c->name }}" {{ strcasecmp($selectedCity ?? '', $c->name) === 0 ? 'selected' : '' }}>{{ $c->name }} ({{ $c->properties_count }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Property Type</label>
                    <select id="desktopTypeFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">All Types</option>
                        <option value="BOYS" {{ ($selectedGender ?? '') === 'BOYS' ? 'selected' : '' }}>Boys PG</option>
                        <option value="GIRLS" {{ ($selectedGender ?? '') === 'GIRLS' ? 'selected' : '' }}>Girls PG</option>
                        <option value="CO-ED" {{ ($selectedGender ?? '') === 'CO-ED' ? 'selected' : '' }}>Co-living</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Budget Range</label>
                    <select id="desktopBudgetFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any Budget</option>
                        <option value="6000" {{ in_array(($budget ?? ''), ['0-6000', '6000', 'under-6k']) || (($maxPrice ?? '') == 6000 && ($minPrice ?? 0) == 0) ? 'selected' : '' }}>Under ₹6,000</option>
                        <option value="8000" {{ ($budget ?? '') === '8000' || ($maxPrice ?? '') == 8000 ? 'selected' : '' }}>Under ₹8,000</option>
                        <option value="10000" {{ in_array(($budget ?? ''), ['6000-10000', '10000']) || (($maxPrice ?? '') == 10000 && ($minPrice ?? '') == 6000) ? 'selected' : '' }}>₹6K – ₹10K</option>
                        <option value="12000" {{ ($budget ?? '') === '12000' || ($maxPrice ?? '') == 12000 ? 'selected' : '' }}>Under ₹12,000</option>
                        <option value="15000" {{ in_array(($budget ?? ''), ['10000-15000', '15000']) || (($maxPrice ?? '') == 15000 && ($minPrice ?? '') == 10000) ? 'selected' : '' }}>₹10K – ₹15K</option>
                        <option value="15000+" {{ in_array(($budget ?? ''), ['15000-plus', '20000', '15000+']) || (($minPrice ?? '') == 15000 && empty($maxPrice)) ? 'selected' : '' }}>₹15K+ Luxury</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Gender</label>
                    <select id="desktopGenderFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any</option>
                        <option value="BOYS" {{ ($selectedGender ?? '') === 'BOYS' ? 'selected' : '' }}>Boys</option>
                        <option value="GIRLS" {{ ($selectedGender ?? '') === 'GIRLS' ? 'selected' : '' }}>Girls</option>
                        <option value="CO-ED" {{ ($selectedGender ?? '') === 'CO-ED' ? 'selected' : '' }}>Co-ed</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterAC" {{ request()->boolean('ac') ? 'checked' : '' }} onchange="filterSearchResults()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>AC Required</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterFood" {{ request()->boolean('food') ? 'checked' : '' }} onchange="filterSearchResults()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>Food Included</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterWifi" {{ request()->boolean('wifi') ? 'checked' : '' }} onchange="filterSearchResults()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>WiFi</span>
                    </label>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="resetAllFilters()" type="button" class="px-5 py-2.5 border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-xl font-semibold transition tap-effect flex items-center gap-1.5 text-sm">
                        <i class="fas fa-undo-alt text-xs"></i> Reset Filter
                    </button>
                    <button onclick="filterSearchResults()" type="button" class="bg-gradient-to-r from-brand to-brand-dark text-white px-7 py-2.5 rounded-xl font-semibold transition tap-effect shadow-md shadow-brand/30 flex items-center gap-2">
                        <i class="fas fa-check"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Row 2: Results Count & Sort By Dropdown -->
        <div class="flex items-center justify-between mt-2.5 px-1">
            <div class="flex items-center gap-2">
                <p class="text-xs sm:text-sm text-gray-600">
                    Showing <span class="font-bold text-gray-900" id="resultsCount">{{ $properties->count() }}</span> properties
                </p>
                <button onclick="resetAllFilters()" id="clearFiltersBtn" class="hidden text-[11px] sm:text-xs font-semibold text-red-500 hover:underline tap-effect flex items-center gap-1">
                    <i class="fas fa-undo-alt text-[9px]"></i> Clear Filters
                </button>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs sm:text-sm text-gray-500 font-medium"><i class="fas fa-sort-amount-down text-brand mr-1"></i> Sort:</span>
                <select id="sortBySelect" onchange="sortSearchResults()" class="bg-white border border-gray-200 rounded-xl py-1.5 px-2 text-xs sm:text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer shadow-sm">
                    <option value="recommended" {{ ($sort ?? '') === 'recommended' ? 'selected' : '' }}>Recommended</option>
                    <option value="price-asc" {{ ($sort ?? '') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price-desc" {{ ($sort ?? '') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="rating" {{ ($sort ?? '') === 'rating' ? 'selected' : '' }}>Top Rated</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ================= MOBILE APP-STYLE FILTER BOTTOM SHEET DRAWER ================= -->
    <div id="mobileFilterDrawer" class="fixed inset-0 z-[2000] hidden">
        <!-- Backdrop overlay -->
        <div onclick="closeMobileFilterDrawer()" id="filterDrawerBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300"></div>

        <!-- Drawer Sheet Container -->
        <div id="filterDrawerSheet" class="absolute bottom-0 left-0 right-0 max-h-[88vh] bg-white rounded-t-3xl shadow-2xl flex flex-col transform translate-y-full transition-transform duration-300 ease-out pb-safe">
            <!-- Pull Handle -->
            <div class="pt-3 pb-1 flex justify-center cursor-pointer" onclick="closeMobileFilterDrawer()">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Drawer Header -->
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-bold text-gray-900">Filters</h3>
                    <span id="drawerActiveCount" class="bg-brand-light text-brand text-xs font-bold px-2.5 py-0.5 rounded-full hidden">0 active</span>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="resetAllFilters()" class="text-xs font-bold text-red-500 hover:text-red-600 tap-effect flex items-center gap-1">
                        <i class="fas fa-undo-alt text-[10px]"></i> Reset All
                    </button>
                    <button onclick="closeMobileFilterDrawer()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 tap-effect">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Drawer Scrollable Body -->
            <div class="px-5 py-4 overflow-y-auto space-y-6 flex-1 no-scrollbar">
                <!-- 1. Popular City Selector -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Popular Cities</label>
                    <div class="flex flex-wrap gap-2" id="drawerCityGroup">
                        <button type="button" onclick="setDrawerCity('', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border border-brand bg-brand text-white tap-effect active">All Cities</button>
                        @foreach($cities->take(6) as $c)
                            <button type="button" onclick="setDrawerCity('{{ $c->name }}', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect">{{ $c->name }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Gender / Sharing Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Room For</label>
                    <div class="grid grid-cols-4 gap-2" id="drawerGenderGroup">
                        <button type="button" onclick="setDrawerGender('ALL', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border border-brand bg-brand text-white tap-effect text-center active">All</button>
                        <button type="button" onclick="setDrawerGender('BOYS', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-center">Boys</button>
                        <button type="button" onclick="setDrawerGender('GIRLS', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-center">Girls</button>
                        <button type="button" onclick="setDrawerGender('CO-ED', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-center">Co-ed</button>
                    </div>
                </div>

                <!-- 3. Budget Range -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Monthly Budget Limit</label>
                    <div class="grid grid-cols-2 gap-2" id="drawerBudgetGroup">
                        <button type="button" onclick="setDrawerBudget('', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-brand bg-brand text-white tap-effect text-left active">Any Budget</button>
                        <button type="button" onclick="setDrawerBudget('6000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹6,000</button>
                        <button type="button" onclick="setDrawerBudget('8000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹8,000</button>
                        <button type="button" onclick="setDrawerBudget('10000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹10,000</button>
                        <button type="button" onclick="setDrawerBudget('12000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹12,000</button>
                        <button type="button" onclick="setDrawerBudget('15000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹15,000</button>
                        <button type="button" onclick="setDrawerBudget('15000+', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left col-span-2">₹15,000+ Luxury</button>
                    </div>
                </div>

                <!-- 4. Amenities Checklist -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Must Have Amenities</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerAC" {{ request()->boolean('ac') ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-snowflake text-brand"></i> AC Room</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerFood" {{ request()->boolean('food') ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-utensils text-brand"></i> Food Included</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerWifi" {{ request()->boolean('wifi') ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-wifi text-brand"></i> Free WiFi</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerSecurity" class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-shield-alt text-brand"></i> 24x7 Security</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Drawer Footer Apply Button -->
            <div class="p-4 border-t border-gray-100 bg-white">
                <button onclick="applyDrawerFilters()" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-2xl tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i> Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- ================= FULL WIDTH PROPERTY GRID (2-COLUMNS IN MOBILE, 3-4 COLUMNS IN DESKTOP) ================= -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-4 md:gap-6 w-full" id="searchGrid">
        
        @forelse($properties as $pg)
            @php
                $genderUpper = strtoupper($pg->gender_preference ?? 'CO-ED');
                $tagMeta = $pg->display_tag_meta;
                $genderMeta = $pg->gender_type_meta;
                $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                $displayImg = $pg->display_image_url;
                $cityName = $pg->city ? $pg->city->name : '';
                $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($cityName ?: 'City Center');
                $ratingVal = $pg->rating ? number_format($pg->rating, 1) : '4.8';
                $reviewCount = $pg->total_reviews ?: (75 + (abs(crc32($pg->id)) % 80));
                
                $hasAC = $pg->amenities->contains(fn($a) => stripos($a->name, 'ac') !== false || stripos($a->slug, 'ac') !== false);
                $hasFood = $pg->amenities->contains(fn($a) => stripos($a->name, 'food') !== false || stripos($a->name, 'meal') !== false || stripos($a->slug, 'food') !== false);
                $hasWifi = $pg->amenities->contains(fn($a) => stripos($a->name, 'wifi') !== false || stripos($a->slug, 'wifi') !== false);
                $hasSecurity = $pg->amenities->contains(fn($a) => stripos($a->name, 'security') !== false || stripos($a->name, 'cctv') !== false || stripos($a->slug, 'security') !== false);
            @endphp
            <div class="property-card bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
                data-gender="{{ $genderUpper }}" 
                data-price="{{ (int)$pg->monthly_rent }}" 
                data-rating="{{ $ratingVal }}" 
                data-city="{{ $cityName }}" 
                data-ac="{{ $hasAC ? 'true' : 'false' }}" 
                data-food="{{ $hasFood ? 'true' : 'false' }}" 
                data-wifi="{{ $hasWifi ? 'true' : 'false' }}" 
                data-security="{{ $hasSecurity ? 'true' : 'false' }}">
                <div>
                    <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full">
                        <img src="{{ $displayImg }}" alt="{{ $pg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 {{ $tagMeta['solid_badge'] }} text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                            <i class="fas fa-{{ $tagMeta['icon'] }}"></i> <span class="hidden sm:inline">{{ $tagMeta['label'] }}</span>
                        </div>
                        <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                            <i class="far fa-heart text-xs sm:text-sm"></i>
                        </button>
                        <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="font-bold">{{ $ratingVal }}</span>
                            <span class="text-gray-300 hidden sm:inline">({{ $reviewCount }})</span>
                        </div>
                    </div>
                    <div class="p-2.5 sm:p-4">
                        <div class="flex justify-between items-start mb-1 gap-1">
                            <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">{{ $pg->name }}</h3>
                            <span class="{{ $genderMeta['class'] }} text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                        </div>
                        <p class="text-gray-500 text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 prop-loc truncate">
                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $locationText }}
                        </p>
                        <div class="hidden sm:flex flex-wrap gap-1.5 mb-3">
                            @forelse($pg->amenities->take(3) as $am)
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                                    <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-brand"></i> {{ $am->name }}
                                </span>
                            @empty
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                                    <i class="fas fa-wifi text-brand"></i> WiFi
                                </span>
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                                    <i class="fas fa-snowflake text-brand"></i> AC
                                </span>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="px-2.5 pb-2.5 sm:px-4 sm:pb-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-[9px] sm:text-xs text-gray-500 block">Rent</span>
                        <div class="text-xs sm:text-lg font-extrabold text-gray-900 leading-none">₹{{ number_format($pg->monthly_rent) }}<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></div>
                    </div>
                    <a href="{{ $slugUrl }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm">View</a>
                </div>
            </div>
        @empty
        @endforelse

    </div>

    <!-- No Results Found Contact Card (Hidden by default, shown when 0 results) -->
    <div id="noResultsCard" class="{{ $properties->isEmpty() ? '' : 'hidden' }} bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl text-center max-w-2xl mx-auto my-8">
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
            <i class="fas fa-headset"></i>
        </div>
        <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">No Matching PGs Found</h3>
        <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6">
            We couldn't find an exact verified match with your selected filters. Don't worry! Our offline team can manually find and arrange verified PGs for you within <strong>2 hours</strong>.
        </p>

        <!-- Direct Contact Box -->
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 sm:p-6 mb-6 text-left">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Direct Support Desk</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="tel:+919876543210" class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl hover:border-brand transition shadow-xs">
                    <div class="w-10 h-10 rounded-lg bg-brand-light text-brand flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fas fa-phone-volume"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium">Direct Hotline</p>
                        <p class="text-xs sm:text-sm font-bold text-gray-900">+91 98765 43210</p>
                    </div>
                </a>
                <a href="https://wa.me/919876543210?text=Hi%20StayNest%20Team%2C%20I%20am%20looking%20for%20a%20PG" target="_blank" class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl hover:border-emerald-500 transition shadow-xs">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base flex-shrink-0">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium">WhatsApp Support</p>
                        <p class="text-xs sm:text-sm font-bold text-emerald-600">+91 98765 43210</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            <button onclick="resetAllFilters()" class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-2.5 rounded-xl text-xs sm:text-sm transition tap-effect shadow-md shadow-brand/30 flex items-center gap-2">
                <i class="fas fa-undo-alt"></i> Reset All Filters
            </button>
            <a href="{{ route('user.contact') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm transition tap-effect flex items-center gap-2">
                <i class="fas fa-envelope"></i> Contact Us Form
            </a>
        </div>
    </div>

    <!-- Load More Button -->
    <div class="mt-10 text-center {{ $properties->isEmpty() ? 'hidden' : '' }}" id="loadMoreContainer">
        <button onclick="loadMoreProperties(this)" class="bg-white border-2 border-gray-200 hover:border-brand hover:text-brand text-gray-700 font-bold py-3 px-8 rounded-2xl transition tap-effect shadow-sm flex items-center gap-2 mx-auto text-xs sm:text-sm">
            <i class="fas fa-spinner"></i> Load More Properties
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let drawerCity = '{{ addslashes($selectedCity ?? '') }}';
    let drawerGender = '{{ strtoupper($selectedGender ?? 'ALL') }}';
    let drawerBudget = '{{ addslashes($budget ?? ($maxPrice ?? '')) }}';

    // ================= TOGGLE FILTER BAR / DRAWER =================
    function toggleFilterBar() {
        if (window.innerWidth >= 768) {
            // Desktop: toggle expandable panel
            const panel = document.getElementById('desktopFilterPanel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
        } else {
            // Mobile: open bottom sheet drawer
            openMobileFilterDrawer();
        }
    }

    function openMobileFilterDrawer() {
        const drawer = document.getElementById('mobileFilterDrawer');
        const backdrop = document.getElementById('filterDrawerBackdrop');
        const sheet = document.getElementById('filterDrawerSheet');

        drawer.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            sheet.classList.remove('translate-y-full');
            sheet.classList.add('translate-y-0');
        }, 10);

        document.body.style.overflow = 'hidden';
    }

    function closeMobileFilterDrawer() {
        const drawer = document.getElementById('mobileFilterDrawer');
        const backdrop = document.getElementById('filterDrawerBackdrop');
        const sheet = document.getElementById('filterDrawerSheet');

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        sheet.classList.remove('translate-y-0');
        sheet.classList.add('translate-y-full');

        setTimeout(() => {
            drawer.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    // ================= DRAWER SELECTIONS =================
    function setDrawerCity(city, btn) {
        document.querySelectorAll('.drawer-city-btn').forEach(b => {
            b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
            b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
        });
        btn.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
        btn.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
        drawerCity = city;

        const dCity = document.getElementById('desktopCitySelect');
        if (dCity) dCity.value = city;
    }

    function setCityFromSelect(city) {
        drawerCity = city;
        document.querySelectorAll('.drawer-city-btn').forEach(b => {
            const match = (city === '' && b.textContent.trim() === 'All Cities') || b.textContent.trim().toLowerCase() === city.toLowerCase();
            if (match) {
                b.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
            } else {
                b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
            }
        });
        filterSearchResults();
    }

    function setDrawerGender(gender, btn) {
        document.querySelectorAll('.drawer-gender-btn').forEach(b => {
            b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
            b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
        });
        btn.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
        btn.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
        drawerGender = gender;
    }

    function setDrawerBudget(budget, btn) {
        document.querySelectorAll('.drawer-budget-btn').forEach(b => {
            b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
            b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
        });
        btn.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
        btn.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
        drawerBudget = budget;
    }

    function applyDrawerFilters() {
        // Sync drawer values with main desktop filters
        const deskGen = document.getElementById('desktopGenderFilter');
        if (deskGen) deskGen.value = (drawerGender === 'ALL') ? '' : drawerGender;

        const deskType = document.getElementById('desktopTypeFilter');
        if (deskType) deskType.value = (drawerGender === 'ALL') ? '' : drawerGender;

        const deskBud = document.getElementById('desktopBudgetFilter');
        if (deskBud) deskBud.value = drawerBudget;

        const acCheck = document.getElementById('drawerAC')?.checked;
        const foodCheck = document.getElementById('drawerFood')?.checked;
        const wifiCheck = document.getElementById('drawerWifi')?.checked;

        const deskAC = document.getElementById('filterAC');
        const deskFood = document.getElementById('filterFood');
        const deskWifi = document.getElementById('filterWifi');
        if (deskAC) deskAC.checked = acCheck;
        if (deskFood) deskFood.checked = foodCheck;
        if (deskWifi) deskWifi.checked = wifiCheck;

        filterSearchResults();
        closeMobileFilterDrawer();
    }

    function toggleSave(btn) {
        const icon = btn.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas', 'text-red-500');
            btn.classList.add('text-red-500');
        } else {
            icon.classList.remove('fas', 'text-red-500');
            icon.classList.add('far');
            btn.classList.remove('text-red-500');
        }
    }

    // ================= RESET ALL FILTERS =================
    function resetAllFilters() {
        // Clear search inputs
        const sInput = document.getElementById('searchInput');
        if (sInput) sInput.value = '';

        // Reset selects
        const dCity = document.getElementById('desktopCitySelect');
        const deskType = document.getElementById('desktopTypeFilter');
        const deskGender = document.getElementById('desktopGenderFilter');
        const deskBudget = document.getElementById('desktopBudgetFilter');
        if (dCity) dCity.value = '';
        if (deskType) deskType.value = '';
        if (deskGender) deskGender.value = '';
        if (deskBudget) deskBudget.value = '';

        // Reset Checkboxes
        const ac = document.getElementById('filterAC');
        const food = document.getElementById('filterFood');
        const wifi = document.getElementById('filterWifi');
        if (ac) ac.checked = false;
        if (food) food.checked = false;
        if (wifi) wifi.checked = false;

        const dAC = document.getElementById('drawerAC');
        const dFood = document.getElementById('drawerFood');
        const dWifi = document.getElementById('drawerWifi');
        const dSec = document.getElementById('drawerSecurity');
        if (dAC) dAC.checked = false;
        if (dFood) dFood.checked = false;
        if (dWifi) dWifi.checked = false;
        if (dSec) dSec.checked = false;

        // Reset Drawer Button selections UI
        drawerCity = '';
        drawerGender = 'ALL';
        drawerBudget = '';

        document.querySelectorAll('.drawer-city-btn').forEach((b, idx) => {
            if (idx === 0) {
                b.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
            } else {
                b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
            }
        });

        document.querySelectorAll('.drawer-gender-btn').forEach((b, idx) => {
            if (idx === 0) {
                b.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
            } else {
                b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
            }
        });

        document.querySelectorAll('.drawer-budget-btn').forEach((b, idx) => {
            if (idx === 0) {
                b.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
            } else {
                b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
                b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
            }
        });

        // Reset sort
        const sSelect = document.getElementById('sortBySelect');
        if (sSelect) sSelect.value = 'recommended';

        // Re-filter results
        filterSearchResults();

        // Close drawer if open
        const drawer = document.getElementById('mobileFilterDrawer');
        if (drawer && !drawer.classList.contains('hidden')) {
            closeMobileFilterDrawer();
        }
    }

    // ================= UNIFIED FILTER SEARCH RESULTS =================
    function filterSearchResults() {
        const query = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
        const deskType = document.getElementById('desktopTypeFilter')?.value || '';
        const deskGender = document.getElementById('desktopGenderFilter')?.value || '';
        const budget = document.getElementById('desktopBudgetFilter')?.value || drawerBudget || '';
        const ac = document.getElementById('filterAC')?.checked || document.getElementById('drawerAC')?.checked;
        const food = document.getElementById('filterFood')?.checked || document.getElementById('drawerFood')?.checked;
        const wifi = document.getElementById('filterWifi')?.checked || document.getElementById('drawerWifi')?.checked;
        const security = document.getElementById('drawerSecurity')?.checked;

        // Calculate active filters count for badges
        let activeCount = 0;
        if (query) activeCount++;
        if (drawerCity) activeCount++;
        if (deskType) activeCount++;
        if (deskGender || drawerGender !== 'ALL') activeCount++;
        if (budget) activeCount++;
        if (ac) activeCount++;
        if (food) activeCount++;
        if (wifi) activeCount++;
        if (security) activeCount++;

        // Update badges & reset links
        const badge = document.getElementById('activeFilterBadge');
        const clearBtn = document.getElementById('clearFiltersBtn');
        const drawerBadge = document.getElementById('drawerActiveCount');

        if (activeCount > 0) {
            if (badge) {
                badge.innerText = activeCount;
                badge.classList.remove('hidden');
            }
            if (clearBtn) clearBtn.classList.remove('hidden');
            if (drawerBadge) {
                drawerBadge.innerText = `${activeCount} active`;
                drawerBadge.classList.remove('hidden');
            }
        } else {
            if (badge) badge.classList.add('hidden');
            if (clearBtn) clearBtn.classList.add('hidden');
            if (drawerBadge) drawerBadge.classList.add('hidden');
        }

        let visibleCount = 0;

        document.querySelectorAll('.property-card').forEach(el => {
            const title = el.querySelector('.prop-title')?.textContent.toLowerCase() || '';
            const loc = el.querySelector('.prop-loc')?.textContent.toLowerCase() || '';
            const elCity = (el.getAttribute('data-city') || '').toLowerCase();
            const elGender = el.getAttribute('data-gender') || '';
            const elPrice = parseInt(el.getAttribute('data-price') || '0');
            const elAC = el.getAttribute('data-ac') === 'true';
            const elFood = el.getAttribute('data-food') === 'true';
            const elWifi = el.getAttribute('data-wifi') === 'true';
            const elSec = el.getAttribute('data-security') === 'true';

            // Text match
            const matchQuery = !query || title.includes(query) || loc.includes(query) || elCity.includes(query);

            // City filter from drawer/select
            const matchCity = !drawerCity || elCity.includes(drawerCity.toLowerCase());

            // Gender filter
            let matchGender = true;
            if (drawerGender === 'BOYS') matchGender = elGender === 'BOYS';
            else if (drawerGender === 'GIRLS') matchGender = elGender === 'GIRLS';
            else if (drawerGender === 'CO-ED') matchGender = elGender === 'CO-ED';

            const matchDeskType = !deskType || elGender === deskType;
            const matchDeskGender = !deskGender || elGender === deskGender;

            // Budget filter logic matching specific buckets or numeric caps
            let matchBudget = true;
            if (budget) {
                if (budget === '6000' || budget === '0-6000' || budget === 'under-6k') {
                    matchBudget = elPrice <= 6000;
                } else if (budget === '8000') {
                    matchBudget = elPrice <= 8000;
                } else if (budget === '10000' || budget === '6000-10000') {
                    matchBudget = (elPrice >= 6000 && elPrice <= 10000) || elPrice <= 10000;
                } else if (budget === '12000') {
                    matchBudget = elPrice <= 12000;
                } else if (budget === '15000' || budget === '10000-15000') {
                    matchBudget = (elPrice >= 10000 && elPrice <= 15000) || elPrice <= 15000;
                } else if (budget === '15000+' || budget === '15000-plus' || budget === '20000') {
                    matchBudget = elPrice >= 15000;
                } else if (!isNaN(parseInt(budget))) {
                    matchBudget = elPrice <= parseInt(budget);
                }
            }

            // Amenities
            const matchAC = !ac || elAC;
            const matchFood = !food || elFood;
            const matchWifi = !wifi || elWifi;
            const matchSec = !security || elSec;

            if (matchQuery && matchCity && matchGender && matchDeskType && matchDeskGender && matchBudget && matchAC && matchFood && matchWifi && matchSec) {
                el.style.display = '';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });

        const countEl = document.getElementById('resultsCount');
        if (countEl) countEl.innerText = visibleCount;

        // Toggle No Results Contact Box vs Load More
        const noResults = document.getElementById('noResultsCard');
        const loadMore = document.getElementById('loadMoreContainer');
        if (visibleCount === 0) {
            if (noResults) noResults.classList.remove('hidden');
            if (loadMore) loadMore.classList.add('hidden');
        } else {
            if (noResults) noResults.classList.add('hidden');
            if (loadMore) loadMore.classList.remove('hidden');
        }
    }

    function sortSearchResults() {
        const sortVal = document.getElementById('sortBySelect')?.value;
        const grid = document.getElementById('searchGrid');
        if (!grid) return;

        const cards = Array.from(grid.querySelectorAll('.property-card'));
        cards.sort((a, b) => {
            const priceA = parseInt(a.getAttribute('data-price') || '0');
            const priceB = parseInt(b.getAttribute('data-price') || '0');
            const ratingA = parseFloat(a.getAttribute('data-rating') || '0');
            const ratingB = parseFloat(b.getAttribute('data-rating') || '0');

            if (sortVal === 'price-asc') return priceA - priceB;
            if (sortVal === 'price-desc') return priceB - priceA;
            if (sortVal === 'rating') return ratingB - ratingA;
            return 0;
        });

        cards.forEach(card => grid.appendChild(card));
    }

    function loadMoreProperties(btn) {
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Loading...';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check"></i> All Properties Loaded';
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }, 800);
    }

    // Initialize UI on page load based on URL parameters
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const cityParam = urlParams.get('city');
        const genderParam = urlParams.get('gender') || urlParams.get('type');
        const budgetParam = urlParams.get('budget');
        const minPriceParam = urlParams.get('min_price');
        const maxPriceParam = urlParams.get('max_price');

        if (cityParam) {
            drawerCity = cityParam;
            document.querySelectorAll('.drawer-city-btn').forEach(b => {
                if (b.textContent.trim().toLowerCase() === cityParam.toLowerCase()) {
                    b.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
                    b.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
                } else if (b.textContent.trim() === 'All Cities') {
                    b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
                    b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
                }
            });
        }

        if (genderParam) {
            const gUpper = genderParam.toUpperCase();
            drawerGender = gUpper;
            document.querySelectorAll('.drawer-gender-btn').forEach(b => {
                if (b.textContent.trim().toUpperCase() === gUpper || (gUpper === 'MALE' && b.textContent.trim() === 'Boys') || (gUpper === 'FEMALE' && b.textContent.trim() === 'Girls')) {
                    b.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
                    b.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
                } else {
                    b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
                    b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
                }
            });
        }

        if (budgetParam || maxPriceParam || minPriceParam) {
            const effectiveBudget = budgetParam || maxPriceParam || (minPriceParam ? '15000+' : '');
            drawerBudget = effectiveBudget;
            
            document.querySelectorAll('.drawer-budget-btn').forEach(b => {
                const btnText = b.textContent;
                let isMatch = false;
                if ((effectiveBudget === '6000' || effectiveBudget === '0-6000') && btnText.includes('6,000')) isMatch = true;
                else if ((effectiveBudget === '8000') && btnText.includes('8,000')) isMatch = true;
                else if ((effectiveBudget === '10000' || effectiveBudget === '6000-10000') && btnText.includes('10,000')) isMatch = true;
                else if ((effectiveBudget === '12000') && btnText.includes('12,000')) isMatch = true;
                else if ((effectiveBudget === '15000' || effectiveBudget === '10000-15000') && btnText.includes('15,000') && !btnText.includes('+')) isMatch = true;
                else if ((effectiveBudget === '15000+' || effectiveBudget === '15000-plus' || (minPriceParam && parseInt(minPriceParam) >= 15000)) && btnText.includes('15,000+')) isMatch = true;

                if (isMatch) {
                    b.classList.add('border-brand', 'bg-brand', 'text-white', 'active');
                    b.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
                } else {
                    b.classList.remove('border-brand', 'bg-brand', 'text-white', 'active');
                    b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
                }
            });
        }

        // Run filter check on load
        filterSearchResults();
    });
</script>
@endpush
