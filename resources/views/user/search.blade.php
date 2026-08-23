@extends('user.layouts.app')
@php
    $searchCity = request('city');
    $searchQ = request('q') ?? request('search');
    $searchGender = request('gender');
    
    if ($searchCity) {
        $seoSearchTitle = 'PG in ' . ucfirst($searchCity) . ' - Best Boys, Girls & Luxury Co-Living Stays | StayNest';
        $seoSearchDesc = 'Explore verified PGs and hostels in ' . ucfirst($searchCity) . ' starting from ₹5,000/mo. Zero brokerage, free WiFi, daily meals & biometric security. Compare rooms on StayNest.';
    } elseif ($searchQ) {
        $seoSearchTitle = 'Search Results for "' . e($searchQ) . '" - Verified PGs & Hostels | StayNest';
        $seoSearchDesc = 'Browse top matching PG accommodations and student co-living spaces for "' . e($searchQ) . '" with zero brokerage on StayNest.';
    } elseif ($searchGender) {
        $seoSearchTitle = ucfirst($searchGender) . ' PG Accommodations - Verified Hostels & Stays | StayNest';
        $seoSearchDesc = 'Find top-rated ' . strtolower($searchGender) . ' PGs and co-living stays across major Indian cities with zero brokerage on StayNest.';
    } else {
        $seoSearchTitle = 'Find PG Near You - 1,200+ Verified Boys, Girls & Co-Living Stays | StayNest';
        $seoSearchDesc = 'Discover 1,200+ verified PGs, luxury hostels, and co-living spaces across Bangalore, Noida, Delhi, Mumbai, Pune, and Gurgaon. Zero brokerage, instant booking on StayNest.';
    }
    $seoSearchKeywords = 'PG in ' . ($searchCity ?: 'India') . ', paying guest, boys PG, girls PG, co-living spaces, luxury hostels, StayNest';
@endphp

@section('title', $seoSearchTitle)
@section('meta_description', $seoSearchDesc)
@section('meta_keywords', $seoSearchKeywords)
@section('canonical', url()->current())

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SearchResultsPage",
  "name": "{{ addslashes($seoSearchTitle) }}",
  "description": "{{ addslashes($seoSearchDesc) }}",
  "url": "{{ url()->current() }}",
  "mainEntity": {
    "@type": "ItemList",
    "name": "Verified Paying Guest Accommodations",
    "itemListOrder": "https://schema.org/ItemListOrderDescending",
    "numberOfItems": 20
  }
}
</script>
@endpush

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
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')
<div class="pt-16 md:pt-8 pb-24 md:pb-16 w-full max-w-7xl mx-auto px-2 sm:px-4 md:px-6">

    <!-- ================= UNIFIED TOP SEARCH & FILTER TOGGLE HEADER ================= -->
    <div class="mb-4 sm:mb-5">
        <!-- Main Search Form (Supports Natural Language, Hinglish & Keyword Searches) -->
        <form id="mainSearchForm" action="{{ route('user.search') }}" method="GET" class="flex items-center gap-2 sm:gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3.5 sm:left-4 top-3.5 text-gray-400 text-sm"></i>
                <input type="text" 
                       id="searchInput" 
                       name="q" 
                       value="{{ $searchQuery ?? '' }}" 
                       placeholder="Search  (e.g. 'Noida sec 62 boys PG 8k AC food' or 'Girls PG Bangalore')..." 
                       class="w-full bg-white border border-gray-200 rounded-2xl py-3 pl-10 sm:pl-11 pr-16 sm:pr-20 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50 shadow-sm transition"
                       autocomplete="off">
                
                <div class="absolute right-2 top-2 flex items-center gap-1">
                    @if(!empty($searchQuery))
                        <button type="button" onclick="clearSearchInput()" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center text-xs transition" title="Clear Search">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                    <button type="submit" class="w-8 h-8 rounded-xl bg-brand hover:bg-brand-dark text-white flex items-center justify-center text-xs shadow-sm transition tap-effect" title="Search">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Hidden Inputs to Preserve Filters Across Searches -->
            <input type="hidden" name="city" id="hiddenCityInput" value="{{ $selectedCity ?? '' }}">
            <input type="hidden" name="gender" id="hiddenGenderInput" value="{{ $selectedGender ?? '' }}">
            <input type="hidden" name="budget" id="hiddenBudgetInput" value="{{ $budget ?? '' }}">
            <input type="hidden" name="sort" id="hiddenSortInput" value="{{ $sort ?? 'recommended' }}">
            @if(!empty($selectedPropertyType)) <input type="hidden" name="type" id="hiddenTypeInput" value="{{ $selectedPropertyType }}"> @endif
            @if(!empty($filterAC)) <input type="hidden" name="ac" value="1"> @endif
            @if(!empty($filterFood)) <input type="hidden" name="food" value="1"> @endif
            @if(!empty($filterWifi)) <input type="hidden" name="wifi" value="1"> @endif
            @if(!empty($filterSecurity)) <input type="hidden" name="security" value="1"> @endif

            <!-- App-style Filter Toggle Button (Desktop & Mobile) -->
            <button type="button" onclick="toggleFilterBar()" id="mainFilterToggleBtn" class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 h-11 bg-white border border-gray-200 hover:border-brand hover:text-brand rounded-2xl text-gray-700 tap-effect shadow-sm flex-shrink-0 transition">
                <i class="fas fa-sliders-h text-sm text-brand"></i>
                <span class="text-xs sm:text-sm font-semibold">Filters</span>
                @php
                    $activeBadgeCount = count($activeFilterChips ?? []);
                @endphp
                <span id="activeFilterBadge" class="{{ $activeBadgeCount > 0 ? '' : 'hidden' }} w-5 h-5 bg-brand text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">
                    {{ $activeBadgeCount }}
                </span>
            </button>

            <!-- Reset Filter Button (Desktop) -->
            <a href="{{ route('user.search') }}" id="desktopQuickResetBtn" class="{{ $activeBadgeCount > 0 || !empty($searchQuery) ? 'flex' : 'hidden' }} md:flex items-center gap-1.5 px-4 h-11 border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-2xl font-semibold transition tap-effect text-sm flex-shrink-0 no-underline">
                <i class="fas fa-undo-alt text-xs"></i> Reset
            </a>
        </form>

        <!-- Quick Natural Query Suggestion Pills -->
        <div class="mt-2.5 flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1">
            <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider whitespace-nowrap mr-1 flex items-center gap-1">
                <i class="fas fa-bolt text-yellow-500"></i> Try:
            </span>
            <button type="button" onclick="applyQuickPrompt('Noida sector 62 me boys PG 8k ke andar AC food ke saath')" class="text-[11px] font-semibold bg-white hover:bg-brand-light hover:text-brand hover:border-brand px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                📍 Boys Noida Sec 62 &lt; 8k
            </button>
            <button type="button" onclick="applyQuickPrompt('girls pg in Bangalore with AC under 12k')" class="text-[11px] font-semibold bg-white hover:bg-brand-light hover:text-brand hover:border-brand px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                👩 Girls Bangalore AC
            </button>
            <button type="button" onclick="applyQuickPrompt('Co-Ed unisex coliving stays in Delhi with Gym & WiFi')" class="text-[11px] font-semibold bg-white hover:bg-brand-light hover:text-brand hover:border-brand px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                👥 Co-Living Delhi
            </button>
            <button type="button" onclick="applyQuickPrompt('single room chahiye attached washroom ke saath')" class="text-[11px] font-semibold bg-white hover:bg-brand-light hover:text-brand hover:border-brand px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                🚪 Single Room Attached Bath
            </button>
            <button type="button" onclick="applyQuickPrompt('Boys PG under 6000')" class="text-[11px] font-semibold bg-white hover:bg-brand-light hover:text-brand hover:border-brand px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                💰 Budget &lt; ₹6,000
            </button>
        </div>

        <!-- ================= AI INTENT SMART SEARCH SUMMARY BANNER ================= -->
        <!-- @if(!empty($searchQuery) || count($activeFilterChips ?? []) > 0)
            <div class="mt-3 bg-gradient-to-r from-purple-50 via-indigo-50/50 to-teal-50/60 rounded-2xl p-3.5 sm:p-4 border border-purple-100 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2 pb-2 border-b border-purple-100/60">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-purple-600 to-brand flex items-center justify-center text-white text-xs shadow-sm">
                            <i class="fas fa-wand-magic-sparkles"></i>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">StayNest AI Smart Search Engine</h4>
                            <p class="text-[11px] text-gray-500">{{ $summaryMessage }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-purple-100/80 text-purple-800 text-[10px] sm:text-xs font-bold px-2.5 py-1 rounded-lg border border-purple-200/60">
                            {{ $properties->count() }} Verified Matches
                        </span>
                        <a href="{{ route('user.search') }}" class="text-[11px] font-bold text-red-500 hover:underline flex items-center gap-1 ml-1 no-underline">
                            <i class="fas fa-times-circle"></i> Clear All
                        </a>
                    </div>
                </div>

         
                @if(count($activeFilterChips ?? []) > 0)
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mr-1">Active Filters:</span>
                        @foreach($activeFilterChips as $chip)
                            <span class="inline-flex items-center gap-1.5 bg-white text-gray-800 hover:bg-red-50 hover:text-red-700 border border-purple-200/80 rounded-xl px-2.5 py-1 text-xs font-bold shadow-xs transition group">
                                <span>{{ $chip['label'] }}</span>
                                <button type="button" onclick="removeFilterParam('{{ $chip['clear_param'] }}')" class="text-gray-400 group-hover:text-red-600 font-black text-xs ml-0.5" title="Remove filter">
                                    &times;
                                </button>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif -->

        <!-- ================= EXPANDABLE DESKTOP FILTER BAR (TOGGLED) ================= -->
        <div id="desktopFilterPanel" class="hidden mt-4 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 transition-all duration-300">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">City / Location</label>
                    <select id="desktopCitySelect" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">All Cities</option>
                        @foreach($cities as $c)
                            <option value="{{ $c->name }}" {{ strcasecmp($selectedCity ?? '', $c->name) === 0 ? 'selected' : '' }}>{{ $c->name }} ({{ $c->properties_count }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Room For / Gender</label>
                    <select id="desktopGenderFilter" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any Gender</option>
                        <option value="BOYS" {{ ($selectedGender ?? '') === 'BOYS' ? 'selected' : '' }}>Boys PG</option>
                        <option value="GIRLS" {{ ($selectedGender ?? '') === 'GIRLS' ? 'selected' : '' }}>Girls PG</option>
                        <option value="CO-ED" {{ ($selectedGender ?? '') === 'CO-ED' ? 'selected' : '' }}>Co-Living / Unisex</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Budget Range</label>
                    <select id="desktopBudgetFilter" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
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
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Sorting</label>
                    <select id="desktopSortFilter" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="recommended" {{ ($sort ?? '') === 'recommended' && !request('near_me') ? 'selected' : '' }}>StayNest Recommended</option>
                        <option value="distance-asc" {{ ($sort ?? '') === 'distance-asc' || request('near_me') ? 'selected' : '' }}>Distance: Low to High</option>
                        <option value="price-asc" {{ ($sort ?? '') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-desc" {{ ($sort ?? '') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ ($sort ?? '') === 'rating' ? 'selected' : '' }}>Top Rated</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-4 sm:gap-6 w-full sm:w-auto">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterAC" {{ !empty($filterAC) ? 'checked' : '' }} onchange="applyDesktopFilterChange()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>❄️ AC Room</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterFood" {{ !empty($filterFood) ? 'checked' : '' }} onchange="applyDesktopFilterChange()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>🍱 Food Included</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterWifi" {{ !empty($filterWifi) ? 'checked' : '' }} onchange="applyDesktopFilterChange()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>📶 Free WiFi</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterSecurity" {{ !empty($filterSecurity) ? 'checked' : '' }} onchange="applyDesktopFilterChange()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>🛡️ 24x7 Security</span>
                    </label>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ route('user.search') }}" class="px-5 py-2.5 border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-xl font-semibold transition tap-effect flex items-center gap-1.5 text-sm no-underline">
                        <i class="fas fa-undo-alt text-xs"></i> Reset
                    </a>
                    <button onclick="applyDesktopFilterChange()" type="button" class="bg-gradient-to-r from-brand to-brand-dark text-white px-7 py-2.5 rounded-xl font-semibold transition tap-effect shadow-md shadow-brand/30 flex items-center gap-2">
                        <i class="fas fa-check"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Row 2: Results Count & Sort By Dropdown -->
        <div class="flex items-center justify-between mt-2.5 px-1">
            <div class="flex items-center gap-2">
                <h1 class="text-xs sm:text-sm text-gray-600 font-normal">
                    Showing <span class="font-bold text-gray-900" id="resultsCount">{{ $properties->count() }}</span> verified properties{{ !empty($selectedCity) ? ' in ' . $selectedCity : '' }}
                </h1>
                @if($activeBadgeCount > 0 || !empty($searchQuery))
                    <a href="{{ route('user.search') }}" class="text-[11px] sm:text-xs font-semibold text-red-500 hover:underline tap-effect flex items-center gap-1 no-underline">
                        <i class="fas fa-undo-alt text-[9px]"></i> Clear Filters
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs sm:text-sm text-gray-500 font-medium"><i class="fas fa-sort-amount-down text-brand mr-1"></i> Sort:</span>
                <select id="sortBySelect" onchange="handleSortDropdownChange(this.value)" class="bg-white border border-gray-200 rounded-xl py-1.5 px-2 text-xs sm:text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer shadow-sm">
                    <option value="recommended" {{ ($sort ?? '') === 'recommended' && !request('near_me') ? 'selected' : '' }}>Recommended</option>
                    <option value="distance-asc" {{ ($sort ?? '') === 'distance-asc' || request('near_me') ? 'selected' : '' }}>Distance: Low to High</option>
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
                    @if($activeBadgeCount > 0)
                        <span class="bg-brand-light text-brand text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $activeBadgeCount }} active</span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.search') }}" class="text-xs font-bold text-red-500 hover:text-red-600 tap-effect flex items-center gap-1 no-underline">
                        <i class="fas fa-undo-alt text-[10px]"></i> Reset All
                    </a>
                    <button type="button" onclick="closeMobileFilterDrawer()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 tap-effect">
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
                        <button type="button" onclick="setDrawerCity('', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border {{ empty($selectedCity) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect">All Cities</button>
                        @foreach($cities->take(6) as $c)
                            <button type="button" onclick="setDrawerCity('{{ $c->name }}', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border {{ strcasecmp($selectedCity ?? '', $c->name) === 0 ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect">{{ $c->name }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Gender / Room For Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Room For</label>
                    <div class="grid grid-cols-4 gap-2" id="drawerGenderGroup">
                        <button type="button" onclick="setDrawerGender('', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border {{ empty($selectedGender) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-center">All</button>
                        <button type="button" onclick="setDrawerGender('BOYS', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border {{ ($selectedGender ?? '') === 'BOYS' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-center">Boys</button>
                        <button type="button" onclick="setDrawerGender('GIRLS', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border {{ ($selectedGender ?? '') === 'GIRLS' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-center">Girls</button>
                        <button type="button" onclick="setDrawerGender('CO-ED', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border {{ ($selectedGender ?? '') === 'CO-ED' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-center">Co-ed</button>
                    </div>
                </div>

                <!-- 3. Budget Range -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Monthly Budget Limit</label>
                    <div class="grid grid-cols-2 gap-2" id="drawerBudgetGroup">
                        <button type="button" onclick="setDrawerBudget('', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ empty($budget) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Any Budget</button>
                        <button type="button" onclick="setDrawerBudget('6000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($budget ?? '') === '6000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹6,000</button>
                        <button type="button" onclick="setDrawerBudget('8000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($budget ?? '') === '8000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹8,000</button>
                        <button type="button" onclick="setDrawerBudget('10000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($budget ?? '') === '10000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹10,000</button>
                        <button type="button" onclick="setDrawerBudget('12000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($budget ?? '') === '12000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹12,000</button>
                        <button type="button" onclick="setDrawerBudget('15000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($budget ?? '') === '15000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹15,000</button>
                        <button type="button" onclick="setDrawerBudget('15000+', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($budget ?? '') === '15000+' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left col-span-2">₹15,000+ Luxury</button>
                    </div>
                </div>

                <!-- 4. Amenities Checklist -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Must Have Amenities</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerAC" {{ !empty($filterAC) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-snowflake text-brand"></i> AC Room</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerFood" {{ !empty($filterFood) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-utensils text-brand"></i> Food Included</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerWifi" {{ !empty($filterWifi) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-wifi text-brand"></i> Free WiFi</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerSecurity" {{ !empty($filterSecurity) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-shield-alt text-brand"></i> 24x7 Security</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Drawer Footer Apply Button -->
            <div class="p-4 border-t border-gray-100 bg-white">
                <button type="button" onclick="applyDrawerFilters()" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-2xl tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i> Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- ================= FULL WIDTH PROPERTY GRID (2-COLUMNS IN MOBILE, 3-4 COLUMNS IN DESKTOP) ================= -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-4 md:gap-6 w-full" id="searchGrid">
        
        @forelse($properties as $index => $pg)
            @php
                $genderUpper = strtoupper($pg->gender_preference ?? 'CO-ED');
                $tagMeta = $pg->display_tag_meta;
                $genderMeta = $pg->gender_type_meta;
                $slugUrl = route('user.detail', ['slug' => $pg->slug ?: \Illuminate\Support\Str::slug($pg->name)]);
                $displayImg = $pg->display_image_url;
                $cityName = $pg->city ? $pg->city->name : '';
                $locationText = ($pg->area ? $pg->area->name . ', ' : '') . ($cityName ?: 'City Center');
                $ratingVal = $pg->dynamic_rating > 0 ? $pg->dynamic_rating : 'New';
                $reviewCount = $pg->dynamic_reviews_count;
                
                $hasAC = $pg->amenities->contains(fn($a) => stripos($a->name, 'ac') !== false || stripos($a->slug, 'ac') !== false);
                $hasFood = $pg->amenities->contains(fn($a) => stripos($a->name, 'food') !== false || stripos($a->name, 'meal') !== false || stripos($a->slug, 'food') !== false);
                $hasWifi = $pg->amenities->contains(fn($a) => stripos($a->name, 'wifi') !== false || stripos($a->slug, 'wifi') !== false);
                $hasSecurity = $pg->amenities->contains(fn($a) => stripos($a->name, 'security') !== false || stripos($a->name, 'cctv') !== false || stripos($a->slug, 'security') !== false);
                
                $matchScore = $pg->match_score ?? null;
                $matchBreakdown = $pg->match_breakdown ?? [];
            @endphp
            <div class="property-card {{ $index >= 12 ? 'extra-property-card hidden' : '' }} bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
                data-gender="{{ $genderUpper }}" 
                data-price="{{ (int)$pg->monthly_rent }}" 
                data-rating="{{ $ratingVal }}" 
                data-city="{{ $cityName }}" 
                data-lat="{{ $pg->map_latitude }}" 
                data-lng="{{ $pg->map_longitude }}" 
                data-distance="999999"
                data-ac="{{ $hasAC ? 'true' : 'false' }}" 
                data-food="{{ $hasFood ? 'true' : 'false' }}" 
                data-wifi="{{ $hasWifi ? 'true' : 'false' }}" 
                data-security="{{ $hasSecurity ? 'true' : 'false' }}">
                <div>
                    <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full bg-gray-100">
                        <img src="{{ $displayImg }}" alt="{{ $pg->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        
                        <!-- Verified Solid Tag -->
                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 {{ $tagMeta['solid_badge'] }} text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                            <i class="fas fa-{{ $tagMeta['icon'] }}"></i> <span class="hidden sm:inline">{{ $tagMeta['label'] }}</span>
                        </div>

                        <!-- Match Score Badge (AI Matching) -->
                        @if($matchScore)
                            <div class="absolute top-2 right-10 sm:top-3 sm:right-14 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-[9px] sm:text-xs font-black px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl shadow-md flex items-center gap-1">
                                <i class="fas fa-bolt text-yellow-300 text-[8px] sm:text-[10px]"></i>
                                <span>{{ $matchScore }}% Match</span>
                            </div>
                        @endif

                        <!-- Heart / Wishlist Toggle Button -->
                        <button type="button" onclick="event.preventDefault(); event.stopPropagation(); heartToggle(this, { id: '{{ $pg->id }}', slug: '{{ $pg->slug ?: \Illuminate\Support\Str::slug($pg->name) }}', title: '{{ addslashes($pg->name) }}', price: '{{ number_format($pg->monthly_rent) }}', image: '{{ $displayImg }}', location: '{{ addslashes($locationText) }}', type: '{{ $genderMeta['label'] }}', rating: '{{ $ratingVal }}' })" data-prop-id="{{ $pg->id }}" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                            <i class="far fa-heart text-xs sm:text-sm"></i>
                        </button>
                        
                        <!-- Star Rating -->
                        <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="font-bold">{{ $ratingVal }}</span>
                            @if($reviewCount > 0)
                                <span class="text-gray-300 hidden sm:inline">({{ $reviewCount }})</span>
                            @else
                                <span class="text-gray-300 hidden sm:inline">(0)</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-2.5 sm:p-4">
                        <div class="flex justify-between items-start mb-1 gap-1">
                            <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">{{ $pg->name }}</h3>
                            <span class="{{ $genderMeta['class'] }} text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">{{ $genderMeta['label'] }}</span>
                        </div>
                        <p class="text-gray-500 text-[10px] sm:text-xs mb-1 flex items-center gap-1 prop-loc truncate">
                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> {{ $locationText }}
                        </p>
                        <p class="text-blue-600 font-semibold text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 pg-search-distance-badge" data-lat="{{ $pg->map_latitude }}" data-lng="{{ $pg->map_longitude }}">
                            <i class="fas fa-location-dot text-[9px] sm:text-[10px]"></i>
                            <span class="dist-text">Calculating...</span>
                        </p>

                        <!-- Match Breakdown Micro-tags (if matched) -->
                        @if(!empty($matchBreakdown))
                            <div class="mb-2 hidden sm:flex flex-wrap gap-1">
                                @foreach(array_slice($matchBreakdown, 0, 2) as $mb)
                                    @if($mb['matched'])
                                        <span class="text-[9px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-1.5 py-0.5 rounded-md font-semibold flex items-center gap-0.5 truncate">
                                            <i class="fas fa-check text-[7px]"></i> {{ $mb['feature'] }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="hidden sm:flex flex-wrap gap-1.5 mb-2">
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
                    <a href="{{ $slugUrl }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm no-underline">View</a>
                </div>
            </div>
        @empty
        @endforelse

    </div>

    <!-- ================= NO MATCHING RESULTS CONTACT & RECOMMENDATIONS CARD ================= -->
    <div id="noResultsCard" class="{{ $properties->isEmpty() ? '' : 'hidden' }} bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl text-center max-w-2xl mx-auto my-8">
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
            <i class="fas fa-headset"></i>
        </div>
        <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">No Matching Verified PGs Found</h3>
        <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6">
            We couldn't find an exact verified match with your selected filters. Don't worry! Our offline concierge team can manually find and arrange verified PGs for you within <strong>2 hours</strong>.
        </p>

        <!-- Direct Contact Box -->
        <!-- <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 sm:p-6 mb-6 text-left">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Direct Support Desk</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="tel:+919876543210" class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl hover:border-brand transition shadow-xs no-underline">
                    <div class="w-10 h-10 rounded-lg bg-brand-light text-brand flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fas fa-phone-volume"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium">Direct Hotline</p>
                        <p class="text-xs sm:text-sm font-bold text-gray-900">+91 98765 43210</p>
                    </div>
                </a>
                <a href="https://wa.me/919876543210?text=Hi%20StayNest%20Team%2C%20I%20am%20looking%20for%20a%20PG%20in%20{{ urlencode($selectedArea ?? ($selectedCity ?? 'India')) }}" target="_blank" class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl hover:border-emerald-500 transition shadow-xs no-underline">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base flex-shrink-0">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-medium">WhatsApp Support</p>
                        <p class="text-xs sm:text-sm font-bold text-emerald-600">+91 98765 43210</p>
                    </div>
                </a>
            </div>
        </div> -->

        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('user.search') }}" class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-2.5 rounded-xl text-xs sm:text-sm transition tap-effect shadow-md shadow-brand/30 flex items-center gap-2 no-underline">
                <i class="fas fa-undo-alt"></i> Reset All Filters
            </a>
            <a href="{{ route('user.contact') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm transition tap-effect flex items-center gap-2 no-underline">
                <i class="fas fa-envelope"></i> Contact Us Form
            </a>
        </div>
    </div>

    <!-- Load More Properties Button (Show only when more than 12 records) -->
    <div class="mt-10 text-center {{ $properties->count() > 12 ? '' : 'hidden' }}" id="loadMoreContainer">
        <button id="loadMoreBtn" onclick="loadMoreProperties(this)" class="bg-white border-2 border-gray-200 hover:border-brand hover:text-brand text-gray-700 font-bold py-3.5 px-8 rounded-2xl transition tap-effect shadow-sm flex items-center gap-2 mx-auto text-xs sm:text-sm cursor-pointer">
            <i class="fas fa-plus-circle text-brand text-sm" id="loadMoreIcon"></i>
            <span id="loadMoreLabel">Load More Properties</span>
            <span class="bg-brand-light text-brand text-[11px] font-extrabold px-2.5 py-0.5 rounded-full" id="remainingCountBadge">{{ $properties->count() - 12 }} more</span>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let drawerCity = '{{ addslashes($selectedCity ?? '') }}';
    let drawerGender = '{{ strtoupper($selectedGender ?? '') }}';
    let drawerBudget = '{{ addslashes($budget ?? '') }}';

    // ================= MAIN SEARCH FORM SUBMISSION =================
    function handleMainSearchSubmit(e) {
        // Form submits normally via GET to /search?q=...
    }

    function applyQuickPrompt(promptText) {
        const input = document.getElementById('searchInput');
        if (input) {
            input.value = promptText;
            document.getElementById('mainSearchForm').submit();
        }
    }

    function clearSearchInput() {
        const input = document.getElementById('searchInput');
        if (input) {
            input.value = '';
            document.getElementById('mainSearchForm').submit();
        }
    }

    function removeFilterParam(paramKey) {
        const form = document.getElementById('mainSearchForm');
        if (!form) return;

        if (paramKey === 'city') {
            const hCity = document.getElementById('hiddenCityInput');
            if (hCity) hCity.value = '';
        } else if (paramKey === 'gender') {
            const hGen = document.getElementById('hiddenGenderInput');
            if (hGen) hGen.value = '';
        } else if (paramKey === 'budget') {
            const hBud = document.getElementById('hiddenBudgetInput');
            if (hBud) hBud.value = '';
        } else if (paramKey === 'ac' || paramKey === 'food' || paramKey === 'wifi' || paramKey === 'security') {
            const el = form.querySelector(`input[name="${paramKey}"]`);
            if (el) el.remove();
        }

        // Also check if natural search query was present, if removing a filter chip, re-submit form
        form.submit();
    }

    function handleSortDropdownChange(sortVal) {
        const hSort = document.getElementById('hiddenSortInput');
        if (hSort) hSort.value = sortVal;
        const dSort = document.getElementById('desktopSortFilter');
        if (dSort) dSort.value = sortVal;
        document.getElementById('mainSearchForm').submit();
    }

    // ================= DESKTOP EXPANDABLE PANEL FILTER CHANGE =================
    function applyDesktopFilterChange() {
        const form = document.getElementById('mainSearchForm');
        if (!form) return;

        const cityVal = document.getElementById('desktopCitySelect')?.value || '';
        const genderVal = document.getElementById('desktopGenderFilter')?.value || '';
        const budgetVal = document.getElementById('desktopBudgetFilter')?.value || '';
        const sortVal = document.getElementById('desktopSortFilter')?.value || 'recommended';

        const acCheck = document.getElementById('filterAC')?.checked;
        const foodCheck = document.getElementById('filterFood')?.checked;
        const wifiCheck = document.getElementById('filterWifi')?.checked;
        const securityCheck = document.getElementById('filterSecurity')?.checked;

        // Update hidden inputs
        document.getElementById('hiddenCityInput').value = cityVal;
        document.getElementById('hiddenGenderInput').value = genderVal;
        document.getElementById('hiddenBudgetInput').value = budgetVal;
        document.getElementById('hiddenSortInput').value = sortVal;

        // Remove old amenity inputs and re-add if checked
        ['ac', 'food', 'wifi', 'security'].forEach(k => {
            const old = form.querySelector(`input[name="${k}"]`);
            if (old) old.remove();
        });

        if (acCheck) appendHiddenInput(form, 'ac', '1');
        if (foodCheck) appendHiddenInput(form, 'food', '1');
        if (wifiCheck) appendHiddenInput(form, 'wifi', '1');
        if (securityCheck) appendHiddenInput(form, 'security', '1');

        form.submit();
    }

    function appendHiddenInput(form, name, value) {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = name;
        inp.value = value;
        form.appendChild(inp);
    }

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
        const form = document.getElementById('mainSearchForm');
        if (!form) return;

        document.getElementById('hiddenCityInput').value = drawerCity;
        document.getElementById('hiddenGenderInput').value = drawerGender;
        document.getElementById('hiddenBudgetInput').value = drawerBudget;

        const acCheck = document.getElementById('drawerAC')?.checked;
        const foodCheck = document.getElementById('drawerFood')?.checked;
        const wifiCheck = document.getElementById('drawerWifi')?.checked;
        const securityCheck = document.getElementById('drawerSecurity')?.checked;

        ['ac', 'food', 'wifi', 'security'].forEach(k => {
            const old = form.querySelector(`input[name="${k}"]`);
            if (old) old.remove();
        });

        if (acCheck) appendHiddenInput(form, 'ac', '1');
        if (foodCheck) appendHiddenInput(form, 'food', '1');
        if (wifiCheck) appendHiddenInput(form, 'wifi', '1');
        if (securityCheck) appendHiddenInput(form, 'security', '1');

        closeMobileFilterDrawer();
        form.submit();
    }

    function loadMoreProperties(btn) {
        const hiddenCards = Array.from(document.querySelectorAll('#searchGrid .extra-property-card.hidden'));
        if (!hiddenCards.length) {
            btn.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> All Properties Loaded';
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-default');
            return;
        }

        const icon = document.getElementById('loadMoreIcon');
        const label = document.getElementById('loadMoreLabel');
        const badge = document.getElementById('remainingCountBadge');

        if (icon) icon.className = 'fas fa-spinner fa-spin text-brand text-sm';
        if (label) label.textContent = 'Loading properties...';
        if (badge) badge.classList.add('hidden');
        btn.disabled = true;

        setTimeout(() => {
            // Reveal next 8 properties
            const toReveal = hiddenCards.slice(0, 8);
            toReveal.forEach(card => {
                card.classList.remove('hidden');
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                card.style.transition = 'all 0.3s ease-out';
                requestAnimationFrame(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            });

            // Recalculate remaining hidden cards
            const remaining = document.querySelectorAll('#searchGrid .extra-property-card.hidden').length;

            if (remaining > 0) {
                btn.disabled = false;
                if (icon) icon.className = 'fas fa-plus-circle text-brand text-sm';
                if (label) label.textContent = 'Load More Properties';
                if (badge) {
                    badge.textContent = `${remaining} more`;
                    badge.classList.remove('hidden');
                }
            } else {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-sm"></i> All Properties Loaded';
                btn.classList.add('opacity-70', 'cursor-default');
                btn.classList.remove('hover:border-brand', 'hover:text-brand', 'cursor-pointer');
            }
        }, 200);
    }

    // ================= GPS LIVE DISTANCE CALCULATION & DISTANCE SORTING =================
    function getHaversineDistanceKm(lat1, lon1, lat2, lon2) {
        const R = 6371;
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

    function updateSearchDistances(userLat, userLng) {
        if (!userLat || !userLng) return;

        try {
            localStorage.setItem('staynest_user_lat', userLat);
            localStorage.setItem('staynest_user_lng', userLng);
        } catch (e) {}

        const cards = document.querySelectorAll('#searchGrid .property-card');
        if (!cards.length) return;

        const cardItems = [];

        cards.forEach(card => {
            const lat = parseFloat(card.getAttribute('data-lat'));
            const lng = parseFloat(card.getAttribute('data-lng'));
            const textSpan = card.querySelector('.dist-text');

            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                const distKm = getHaversineDistanceKm(userLat, userLng, lat, lng);
                card.setAttribute('data-distance', distKm);
                if (textSpan) textSpan.textContent = formatDistance(distKm);
                cardItems.push({ element: card, distance: distKm });
            } else {
                card.setAttribute('data-distance', '999999');
                if (textSpan) textSpan.textContent = '1.2 km away';
                cardItems.push({ element: card, distance: 999999 });
            }
        });

        // Check if current sort order is distance-asc or near_me
        const currentSort = '{{ $sort ?? "" }}';
        const urlParams = new URLSearchParams(window.location.search);
        const isDistanceSort = currentSort === 'distance-asc' || urlParams.get('sort') === 'distance-asc' || urlParams.get('near_me') === '1';

        if (isDistanceSort && cardItems.length > 0) {
            const grid = document.getElementById('searchGrid');
            if (grid) {
                cardItems.sort((a, b) => a.distance - b.distance);
                const hasHiddenInitially = document.querySelectorAll('#searchGrid .extra-property-card.hidden').length > 0;
                const visibleCount = Array.from(cards).filter(c => !c.classList.contains('hidden')).length;
                cardItems.forEach((item, idx) => {
                    grid.appendChild(item.element);
                    if (hasHiddenInitially) {
                        if (idx < visibleCount) {
                            item.element.classList.remove('hidden');
                        } else {
                            item.element.classList.add('hidden', 'extra-property-card');
                        }
                    }
                });
            }
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

        // 3. Default fallback to Noida Sector 62
        return { lat: 28.6280, lng: 77.3649, isLocked: false };
    }

    document.addEventListener('DOMContentLoaded', function() {
        const eff = getEffectiveUserCoordinates();
        updateSearchDistances(eff.lat, eff.lng);

        // If user has locked their address in profile, DO NOT let desktop ISP network glitch flip it to Delhi!
        if (eff.isLocked) {
            return;
        }

        // 2. Request live visit GPS location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    if (pos && pos.coords) {
                        const accuracy = pos.coords.accuracy || 1000;
                        if (accuracy <= 1500) {
                            updateSearchDistances(pos.coords.latitude, pos.coords.longitude);
                        }
                    }
                },
                function(err) {
                    const curLat = parseFloat(localStorage.getItem('staynest_user_lat')) || 28.6280;
                    const curLng = parseFloat(localStorage.getItem('staynest_user_lng')) || 77.3649;
                    updateSearchDistances(curLat, curLng);
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
            );
        }
    });
</script>
@endpush
