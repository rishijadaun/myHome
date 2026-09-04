@extends('user.layouts.app')

@php
    $selectedCity = request('city', '');
    $selectedType = request('type', '');
    $selectedGender = request('gender', '');
    $selectedBhk = request('bhk', '');
    $selectedFurnishing = request('furnishing', '');
    $selectedOccupation = request('occupation', '');
    $selectedBudget = request('budget', '');
    $selectedBudgetMax = request('budget_max', '');
    $searchQ = request('q') ?? request('search', '');
    $sort = request('sort', 'newest');
    $filterAC = request('ac', '');
    $filterFridge = request('fridge', '');
    $filterWifi = request('wifi', '');
    $filterFood = request('food', '');

    $activeFilterChips = [];
    if (!empty($selectedCity)) $activeFilterChips[] = ['label' => '📍 ' . $selectedCity, 'param' => 'city'];
    if (!empty($selectedGender)) $activeFilterChips[] = ['label' => '👤 ' . (in_array(strtolower($selectedGender), ['female', 'girls']) ? 'Girls Only' : (in_array(strtolower($selectedGender), ['male', 'boys']) ? 'Boys Only' : 'Any Gender')), 'param' => 'gender'];
    if (!empty($selectedBhk)) $activeFilterChips[] = ['label' => '🏠 ' . ($bhkOptions[$selectedBhk] ?? $selectedBhk), 'param' => 'bhk'];
    if (!empty($selectedFurnishing)) $activeFilterChips[] = ['label' => '🛋️ ' . ucfirst(str_replace('_', ' ', $selectedFurnishing)), 'param' => 'furnishing'];
    if (!empty($selectedBudget)) $activeFilterChips[] = ['label' => '💰 < ₹' . number_format((int)$selectedBudget), 'param' => 'budget'];
    if (!empty($selectedOccupation)) $activeFilterChips[] = ['label' => '💼 ' . ucfirst(str_replace('_', ' ', $selectedOccupation)), 'param' => 'occupation'];
    if (!empty($filterAC)) $activeFilterChips[] = ['label' => '❄️ AC', 'param' => 'ac'];
    if (!empty($filterFridge)) $activeFilterChips[] = ['label' => '🧊 Fridge', 'param' => 'fridge'];
    if (!empty($filterWifi)) $activeFilterChips[] = ['label' => '📶 WiFi', 'param' => 'wifi'];
    if (!empty($filterFood)) $activeFilterChips[] = ['label' => '🍱 Food', 'param' => 'food'];

    $activeBadgeCount = count($activeFilterChips);

    // Dynamic SEO Titles
    if ($selectedCity) {
        $seoTitle = 'Find Flatmates & Shared Rooms in ' . ucfirst($selectedCity) . ' — Zero Brokerage | StayNest';
        $seoDesc = 'Browse ' . $totalActive . '+ verified roommate and flatmate profiles in ' . ucfirst($selectedCity) . '. Connect directly with verified flatmates via WhatsApp with zero brokerage.';
    } elseif ($searchQ) {
        $seoTitle = 'Search Flatmates for "' . e($searchQ) . '" — Zero Brokerage | StayNest';
        $seoDesc = 'Find top matching flatmates and shared rooms for "' . e($searchQ) . '" with zero brokerage on StayNest.';
    } else {
        $seoTitle = 'Find Flatmate & Roommate in India — 100% Verified, Zero Brokerage | StayNest';
        $seoDesc = 'Discover verified flatmates, private rooms, and shared apartments across Delhi, Noida, Gurgaon, Bangalore, Mumbai, and Pune. Direct chat on StayNest.';
    }
    $seoKeywords = 'flatmate in ' . ($selectedCity ?: 'India') . ', roommate in ' . ($selectedCity ?: 'India') . ', shared room, 2 bhk flatmate, girls pg flatmate, boys room, zero brokerage flatmate';
    $canonicalUrl = url()->current();
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('meta_keywords', $seoKeywords)
@section('canonical', $canonicalUrl)
@section('og_type', 'website')

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
        }@if(!empty($selectedCity)),
        {
          "@type": "ListItem",
          "position": 3,
          "name": "Flatmates in {{ ucfirst($selectedCity) }}",
          "item": "{{ route('user.roommate.index', ['city' => $selectedCity]) }}"
        }@endif
      ]
    },
    {
      "@type": "ItemList",
      "@id": "{{ $canonicalUrl }}#roommatelist",
      "name": "{{ addslashes($seoTitle) }}",
      "description": "{{ addslashes($seoDesc) }}",
      "numberOfItems": {{ $posts->total() }},
      "itemListElement": [
        @foreach($posts->take(10) as $i => $item)
        {
          "@type": "ListItem",
          "position": {{ $i + 1 }},
          "url": "{{ route('user.roommate.show', $item->slug) }}",
          "name": "{{ addslashes($item->poster_name) }} — {{ addslashes($bhkOptions[$item->bhk_type] ?? $item->bhk_type) }} in {{ addslashes($item->city) }}"
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
  ]
}
</script>
@endpush

@push('styles')
<style>
    .roommate-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .roommate-card:hover {
        border-color: #4c30dd !important;
        box-shadow: 0 10px 25px -4px rgba(76, 48, 221, 0.2) !important;
        transform: translateY(-3px);
    }
    .roommate-btn-view {
        background-color: #4c30dd !important;
        color: #ffffff !important;
        border-radius: 4px !important;
        font-weight: 700 !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 8px rgba(76, 48, 221, 0.35) !important;
    }
    .roommate-btn-view:hover {
        background-color: #3d24c0 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(76, 48, 221, 0.5) !important;
    }
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

    <!-- Breadcrumb Navigation for SEO & Desktop UX (Matching search.blade.php) -->
    <nav aria-label="Breadcrumb" class="hidden md:flex mb-3.5 text-xs text-gray-500 items-center gap-1.5 flex-wrap px-1">
        <a href="{{ route('user.home') }}" class="hover:text-brand transition flex items-center gap-1 text-gray-600 font-medium">
            <i class="fas fa-home text-[11px]"></i> Home
        </a>
        <i class="fas fa-chevron-right text-[8px] text-gray-400"></i>
        <a href="{{ route('user.roommate.index') }}" class="hover:text-brand transition text-gray-600 font-medium">
            Find Flatmate &amp; Roommate
        </a>
        @if(!empty($selectedCity))
            <i class="fas fa-chevron-right text-[8px] text-gray-400"></i>
            <span class="font-bold text-brand">{{ ucfirst($selectedCity) }}</span>
        @endif
        @if(!empty($selectedGender))
            <i class="fas fa-chevron-right text-[8px] text-gray-400"></i>
            <span class="font-bold text-brand">{{ ucfirst(strtolower($selectedGender)) }}</span>
        @endif
    </nav>

    <!-- Page Semantic Title for SEO -->
    <div class="mb-3 px-1">
        <h1 class="text-lg sm:text-2xl font-black text-gray-900 tracking-tight">
            @if(!empty($selectedCity))
                Find Flatmates &amp; Shared Rooms in {{ ucfirst($selectedCity) }}
            @elseif(!empty($searchQ))
                Roommate Search results for "{{ $searchQ }}"
            @else
                Find Verified Flatmates &amp; Shared Rooms in India
            @endif
        </h1>
        <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">
            Connect directly with verified flatmates &bull; Zero brokerage &bull; Instant WhatsApp discussion
        </p>
    </div>

    <!-- ================= UNIFIED TOP SEARCH & FILTER TOGGLE HEADER ================= -->
    <div class="mb-4 sm:mb-5">
        <form id="mainSearchForm" action="{{ route('user.roommate.index') }}" method="GET" class="flex items-center gap-2 sm:gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3.5 sm:left-4 top-3.5 text-gray-400 text-sm"></i>
                <input type="text" 
                       id="searchInput" 
                       name="q" 
                       value="{{ $searchQ ?? '' }}" 
                       placeholder="Search flatmates (e.g. 'Noida sec 62 boys', 'Girls room Bangalore 8k', 'AC flatmate')..." 
                       class="w-full bg-white border border-gray-200 rounded-2xl py-3 pl-10 sm:pl-11 pr-16 sm:pr-20 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50 shadow-sm transition"
                       autocomplete="off">
                
                <div class="absolute right-2 top-2 flex items-center gap-1">
                    @if(!empty($searchQ))
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
            <input type="hidden" name="bhk" id="hiddenBhkInput" value="{{ $selectedBhk ?? '' }}">
            <input type="hidden" name="furnishing" id="hiddenFurnishingInput" value="{{ $selectedFurnishing ?? '' }}">
            <input type="hidden" name="occupation" id="hiddenOccupationInput" value="{{ $selectedOccupation ?? '' }}">
            <input type="hidden" name="budget" id="hiddenBudgetInput" value="{{ $selectedBudget ?? '' }}">
            <input type="hidden" name="sort" id="hiddenSortInput" value="{{ $sort ?? 'newest' }}">
            @if(!empty($filterAC)) <input type="hidden" name="ac" value="1"> @endif
            @if(!empty($filterFridge)) <input type="hidden" name="fridge" value="1"> @endif
            @if(!empty($filterWifi)) <input type="hidden" name="wifi" value="1"> @endif
            @if(!empty($filterFood)) <input type="hidden" name="food" value="1"> @endif

            <!-- App-style Filter Toggle Button (Desktop & Mobile) -->
            <button type="button" onclick="toggleFilterBar()" id="mainFilterToggleBtn" class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 sm:px-5 h-11 bg-white border border-gray-200 hover:border-brand hover:text-brand rounded-2xl text-gray-700 tap-effect shadow-sm flex-shrink-0 transition">
                <i class="fas fa-sliders-h text-sm text-brand"></i>
                <span class="text-xs sm:text-sm font-semibold">Filters</span>
                <span id="activeFilterBadge" class="{{ $activeBadgeCount > 0 ? '' : 'hidden' }} w-5 h-5 bg-brand text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">
                    {{ $activeBadgeCount }}
                </span>
            </button>

            <!-- Map View CTA Button (Navigate to Explore Near Me with Roommates Tab) -->
            <a href="{{ route('user.location', ['type' => 'roommate']) }}" class="flex items-center justify-center gap-1.5 sm:gap-2 px-3.5 sm:px-5 h-11 bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white rounded-2xl text-xs sm:text-sm font-bold tap-effect shadow-md shadow-brand/25 flex-shrink-0 transition no-underline" title="Explore Roommates Near You on Map">
                <i class="fas fa-map-location-dot text-sm"></i>
                <span class="hidden sm:inline">Map View</span>
                <span class="sm:hidden">Map</span>
            </a>

            <!-- Reset Filter Button (Desktop) -->
            <a href="{{ route('user.roommate.index') }}" id="desktopQuickResetBtn" class="{{ $activeBadgeCount > 0 || !empty($searchQ) ? 'flex' : 'hidden' }} md:flex items-center gap-1.5 px-4 h-11 border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-2xl font-semibold transition tap-effect text-sm flex-shrink-0 no-underline">
                <i class="fas fa-undo-alt text-xs"></i> Reset
            </a>
        </form>

        <!-- Quick Natural Query Suggestion Pills -->
        <div class="mt-2.5 flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1">
            <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider whitespace-nowrap mr-1 flex items-center gap-1">
                <i class="fas fa-bolt text-yellow-500"></i> Try:
            </span>
            <button type="button" onclick="applyQuickPrompt('female', 'gender')" class="text-[11px] font-semibold bg-white hover:bg-pink-50 hover:text-pink-700 hover:border-pink-300 px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                👩 Girls Only Stays
            </button>
            <button type="button" onclick="applyQuickPrompt('male', 'gender')" class="text-[11px] font-semibold bg-white hover:bg-blue-50 hover:text-blue-700 hover:border-blue-300 px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                👨 Boys Only Stays
            </button>
            <button type="button" onclick="applyQuickPrompt('single_room', 'bhk')" class="text-[11px] font-semibold bg-white hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                🏠 Single Private Room
            </button>
            <button type="button" onclick="applyQuickPrompt('furnished', 'furnishing')" class="text-[11px] font-semibold bg-white hover:bg-purple-50 hover:text-purple-700 hover:border-purple-300 px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                🛋️ Fully Furnished Flat
            </button>
            <button type="button" onclick="applyQuickPrompt('8000', 'budget')" class="text-[11px] font-semibold bg-white hover:bg-brand-light hover:text-brand hover:border-brand px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap transition tap-effect">
                💰 Budget &lt; ₹8,000
            </button>
        </div>

        <!-- ================= EXPANDABLE DESKTOP FILTER BAR (TOGGLED) ================= -->
        <div id="desktopFilterPanel" class="hidden mt-4 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 transition-all duration-300">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">City / Location</label>
                    <select id="desktopCitySelect" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">All India / Any City</option>
                        @foreach($popularCities as $c)
                            <option value="{{ $c }}" {{ strcasecmp($selectedCity ?? '', $c) === 0 ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Preferred Roommate / Gender</label>
                    <select id="desktopGenderFilter" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any Gender</option>
                        <option value="female" {{ in_array(strtolower($selectedGender ?? ''), ['female', 'girls']) ? 'selected' : '' }}>👩 Girls Only</option>
                        <option value="male" {{ in_array(strtolower($selectedGender ?? ''), ['male', 'boys']) ? 'selected' : '' }}>👨 Boys Only</option>
                        <option value="any" {{ ($selectedGender ?? '') === 'any' ? 'selected' : '' }}>🧑‍🤝‍🧑 Any Gender</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Room / BHK Type</label>
                    <select id="desktopBhkFilter" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any BHK / Room</option>
                        @foreach($bhkOptions as $key => $label)
                            <option value="{{ $key }}" {{ ($selectedBhk ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Monthly Budget Range</label>
                    <select id="desktopBudgetFilter" onchange="applyDesktopFilterChange()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any Budget</option>
                        <option value="6000" {{ in_array(($selectedBudget ?? ''), ['6000', '0-6000']) ? 'selected' : '' }}>Under ₹6,000</option>
                        <option value="8000" {{ ($selectedBudget ?? '') === '8000' ? 'selected' : '' }}>Under ₹8,000</option>
                        <option value="10000" {{ in_array(($selectedBudget ?? ''), ['6000-10000', '10000']) ? 'selected' : '' }}>₹6K – ₹10K</option>
                        <option value="12000" {{ ($selectedBudget ?? '') === '12000' ? 'selected' : '' }}>Under ₹12,000</option>
                        <option value="15000" {{ in_array(($selectedBudget ?? ''), ['10000-15000', '15000']) ? 'selected' : '' }}>₹10K – ₹15K</option>
                        <option value="15000+" {{ in_array(($selectedBudget ?? ''), ['15000-plus', '15000+']) ? 'selected' : '' }}>₹15K+ Luxury</option>
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
                        <input type="checkbox" id="filterFridge" {{ !empty($filterFridge) ? 'checked' : '' }} onchange="applyDesktopFilterChange()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>🧊 Refrigerator</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterWifi" {{ !empty($filterWifi) ? 'checked' : '' }} onchange="applyDesktopFilterChange()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>📶 High Speed WiFi</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterFood" {{ !empty($filterFood) ? 'checked' : '' }} onchange="applyDesktopFilterChange()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>🍱 Food / Cook Option</span>
                    </label>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ route('user.roommate.index') }}" class="px-5 py-2.5 border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-600 hover:text-red-600 rounded-xl font-semibold transition tap-effect flex items-center gap-1.5 text-sm no-underline">
                        <i class="fas fa-undo-alt text-xs"></i> Reset
                    </a>
                    <button onclick="applyDesktopFilterChange()" type="button" class="bg-gradient-to-r from-brand to-brand-dark text-white px-7 py-2.5 rounded-xl font-semibold transition tap-effect shadow-md shadow-brand/30 flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-check"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Row 2: Results Count & Sort By Dropdown -->
        <div class="flex items-center justify-between mt-3 px-1">
            <div class="flex items-center gap-2">
                <p class="text-xs sm:text-sm text-gray-600 font-normal">
                    Showing <span class="font-bold text-gray-900" id="resultsCount">{{ $posts->total() }}</span> verified roommate listings{{ !empty($selectedCity) ? ' in ' . $selectedCity : '' }}
                </p>
                @if($activeBadgeCount > 0 || !empty($searchQ))
                    <a href="{{ route('user.roommate.index') }}" class="text-[11px] sm:text-xs font-semibold text-red-500 hover:underline tap-effect flex items-center gap-1 no-underline">
                        <i class="fas fa-undo-alt text-[9px]"></i> Clear Filters
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs sm:text-sm text-gray-500 font-medium"><i class="fas fa-sort-amount-down text-brand mr-1"></i> Sort:</span>
                <select id="sortBySelect" onchange="handleSortDropdownChange(this.value)" class="bg-white border border-gray-200 rounded-xl py-1.5 px-2 text-xs sm:text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer shadow-sm">
                    <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>✨ Newest Listed</option>
                    <option value="price-asc" {{ ($sort ?? '') === 'price-asc' ? 'selected' : '' }}>💰 Rent: Low to High</option>
                    <option value="price-desc" {{ ($sort ?? '') === 'price-desc' ? 'selected' : '' }}>💎 Rent: High to Low</option>
                    <option value="immediate" {{ ($sort ?? '') === 'immediate' ? 'selected' : '' }}>⚡ Immediate Move-in</option>
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
                    <a href="{{ route('user.roommate.index') }}" class="text-xs font-bold text-red-500 hover:text-red-600 tap-effect flex items-center gap-1 no-underline">
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
                        <button type="button" onclick="setDrawerCity('', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border {{ empty($selectedCity) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect">All India</button>
                        @foreach($popularCities as $c)
                            <button type="button" onclick="setDrawerCity('{{ $c }}', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border {{ strcasecmp($selectedCity ?? '', $c) === 0 ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect">{{ $c }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Preferred Gender Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Preferred Roommate</label>
                    <div class="grid grid-cols-3 gap-2" id="drawerGenderGroup">
                        <button type="button" onclick="setDrawerGender('', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border {{ empty($selectedGender) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-center">All</button>
                        <button type="button" onclick="setDrawerGender('female', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border {{ in_array(strtolower($selectedGender ?? ''), ['female', 'girls']) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-center">👩 Girls Only</button>
                        <button type="button" onclick="setDrawerGender('male', this)" class="drawer-btn drawer-gender-btn py-2.5 px-2 rounded-xl text-xs font-semibold border {{ in_array(strtolower($selectedGender ?? ''), ['male', 'boys']) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-center">👨 Boys Only</button>
                    </div>
                </div>

                <!-- 3. Room / BHK Type -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Room / BHK Type</label>
                    <div class="grid grid-cols-2 gap-2" id="drawerBhkGroup">
                        <button type="button" onclick="setDrawerBhk('', this)" class="drawer-btn drawer-bhk-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ empty($selectedBhk) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Any BHK</button>
                        @foreach($bhkOptions as $key => $label)
                            <button type="button" onclick="setDrawerBhk('{{ $key }}', this)" class="drawer-btn drawer-bhk-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($selectedBhk ?? '') === $key ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Budget Range -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Monthly Budget Limit</label>
                    <div class="grid grid-cols-2 gap-2" id="drawerBudgetGroup">
                        <button type="button" onclick="setDrawerBudget('', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ empty($selectedBudget) ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Any Budget</button>
                        <button type="button" onclick="setDrawerBudget('6000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($selectedBudget ?? '') === '6000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹6,000</button>
                        <button type="button" onclick="setDrawerBudget('8000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($selectedBudget ?? '') === '8000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹8,000</button>
                        <button type="button" onclick="setDrawerBudget('12000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($selectedBudget ?? '') === '12000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">Under ₹12,000</button>
                        <button type="button" onclick="setDrawerBudget('15000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($selectedBudget ?? '') === '15000' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">₹10K – ₹15K</button>
                        <button type="button" onclick="setDrawerBudget('15000+', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border {{ ($selectedBudget ?? '') === '15000+' ? 'border-brand bg-brand text-white active' : 'border-gray-200 bg-gray-50 text-gray-700' }} tap-effect text-left">₹15K+ Luxury</button>
                    </div>
                </div>

                <!-- 5. Key Amenities -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Key Amenities</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 cursor-pointer">
                            <input type="checkbox" id="drawerFilterAC" {{ !empty($filterAC) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span>❄️ AC Room</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 cursor-pointer">
                            <input type="checkbox" id="drawerFilterFridge" {{ !empty($filterFridge) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span>🧊 Fridge</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 cursor-pointer">
                            <input type="checkbox" id="drawerFilterWifi" {{ !empty($filterWifi) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span>📶 Free WiFi</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 cursor-pointer">
                            <input type="checkbox" id="drawerFilterFood" {{ !empty($filterFood) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span>🍱 Food / Meals</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Drawer Fixed Footer -->
            <div class="p-4 border-t border-gray-100 bg-white flex items-center gap-3">
                <a href="{{ route('user.roommate.index') }}" class="w-1/3 py-3 text-center border border-gray-200 text-gray-700 font-bold rounded-2xl text-xs tap-effect no-underline">
                    Reset
                </a>
                <button type="button" onclick="applyMobileDrawerFilters()" class="w-2/3 py-3 bg-gradient-to-r from-brand to-brand-dark text-white font-bold rounded-2xl text-xs tap-effect shadow-md shadow-brand/30">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- ================= SKELETON SHIMMER PLACEHOLDER GRID (Shown during search/filtering/loading) ================= -->
    <div id="roommateSkeletonGrid" class="hidden grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-4 md:gap-6 w-full animate-in fade-in duration-200">
        @for($s = 0; $s < 8; $s++)
        <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 flex flex-col justify-between w-full">
            <div>
                <!-- Top Media Box Skeleton -->
                <div class="relative h-36 sm:h-44 md:h-48 w-full bg-gray-200 skeleton-shimmer flex items-center justify-center p-3">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gray-300/60 skeleton-shimmer"></div>
                    <div class="absolute top-2 left-2 w-12 h-4 bg-gray-300/80 rounded-lg skeleton-shimmer"></div>
                    <div class="absolute top-2 right-2 w-16 h-4 bg-gray-300/80 rounded-lg skeleton-shimmer"></div>
                    <div class="absolute bottom-2 left-2 w-14 h-3.5 bg-gray-300/80 rounded-lg skeleton-shimmer"></div>
                </div>

                <!-- Content Body Skeleton -->
                <div class="p-3 sm:p-4 space-y-2.5">
                    <div class="flex items-center justify-between gap-1">
                        <div class="h-4 sm:h-5 w-2/3 bg-gray-200 rounded-lg skeleton-shimmer"></div>
                        <div class="h-3 w-8 bg-gray-100 rounded skeleton-shimmer"></div>
                    </div>
                    <div class="h-3 w-1/2 bg-gray-100 rounded-md skeleton-shimmer"></div>
                    <div class="h-4 sm:h-5 w-3/4 bg-gray-100 rounded-lg skeleton-shimmer"></div>

                    <!-- Mini Amenities Pills Skeleton -->
                    <div class="flex items-center gap-1.5 pt-1">
                        <div class="h-4 w-10 bg-gray-100 rounded skeleton-shimmer"></div>
                        <div class="h-4 w-12 bg-gray-100 rounded skeleton-shimmer"></div>
                        <div class="h-4 w-8 bg-gray-100 rounded skeleton-shimmer"></div>
                    </div>
                </div>
            </div>

            <!-- Footer Skeleton -->
            <div class="p-3 sm:p-4 pt-2 border-t border-gray-50 flex items-center justify-between gap-1">
                <div>
                    <div class="h-2.5 w-10 bg-gray-100 rounded skeleton-shimmer mb-1"></div>
                    <div class="h-4 sm:h-5 w-16 bg-gray-200 rounded-md skeleton-shimmer"></div>
                </div>
                <div class="h-7 sm:h-8 w-14 sm:w-16 bg-gray-200 rounded-xl skeleton-shimmer"></div>
            </div>
        </div>
        @endfor
    </div>

    <!-- ================= FULL WIDTH ROOMMATE GRID (2-COLUMNS IN MOBILE, 3-4 COLUMNS IN DESKTOP MATCHING SEARCH) ================= -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-4 md:gap-6 w-full" id="roommateGrid">
        
        @forelse($posts as $index => $post)
            @php
                $genderPref = strtolower($post->gender_preference ?? 'any');
                $bhkLabels = [
                    '1rk' => '1 RK Flat',
                    '1bhk' => '1 BHK Flat',
                    '2bhk' => '2 BHK Flat',
                    '3bhk' => '3 BHK Flat',
                    '4bhk_plus' => '4+ BHK Villa',
                    'shared_room' => 'Shared Room',
                    'single_room' => 'Single Room',
                    'studio' => 'Studio Room',
                ];
                $bhkLabel = $bhkLabels[$post->bhk_type] ?? ($bhkOptions[$post->bhk_type] ?? (str_ends_with(strtolower($post->bhk_type), 'bhk') ? strtoupper($post->bhk_type) : ucwords(str_replace('_', ' ', $post->bhk_type))));
                $slugUrl = route('user.roommate.show', $post->slug);
                $locationText = ($post->locality ? $post->locality . ', ' : '') . ($post->city ?: 'City Center');
                $displayAvatar = $post->poster_avatar_url;
            @endphp
            <div class="roommate-card overflow-hidden flex flex-col justify-between h-full group">
                <div>
                    <!-- Top Media Banner (0 padding, full bleed, no room badge) -->
                    <div class="relative h-44 sm:h-52 w-full p-0 m-0 overflow-hidden bg-gradient-to-br from-slate-950 to-[#1b104a] flex flex-col items-center justify-center text-center {{ $displayAvatar ? 'skeleton-shimmer' : '' }}">
                        @if($displayAvatar)
                            <img src="{{ $displayAvatar }}" alt="{{ $post->poster_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 block p-0 m-0" loading="lazy" decoding="async" onload="this.parentElement.classList.remove('skeleton-shimmer')">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>
                        @else
                            {{-- Central Profile Avatar Tile --}}
                            <div class="relative z-10 w-14 h-14 sm:w-16 sm:h-16 bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <span class="text-3xl sm:text-4xl">{{ $post->gender_icon }}</span>
                            </div>
                        @endif

                        <!-- Top-Right Gender Preference (Pink for Girls, Blue for Boys, Purple for Any) -->
                        <div class="absolute top-2.5 right-2.5 {{ in_array($genderPref, ['female', 'girls']) ? 'bg-pink-600' : (in_array($genderPref, ['male', 'boys']) ? 'bg-blue-600' : 'bg-[#4c30dd]') }} text-white text-[9px] font-extrabold px-2.5 py-0.5 shadow-xs z-20" style="{{ !in_array($genderPref, ['female', 'girls', 'male', 'boys']) ? 'background-color: #4c30dd !important;' : '' }}">
                            {{ in_array($genderPref, ['female', 'girls']) ? '👩 Girls Only' : (in_array($genderPref, ['male', 'boys']) ? '👨 Boys Only' : '🧑 Any') }}
                        </div>

                        <!-- Bottom-Left Move-in Date -->
                        <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur-md text-white text-[9px] font-medium px-2 py-0.5 z-20">
                            <i class="fas fa-calendar-day text-[#a594fd] text-[8px] mr-1"></i>
                            <span>{{ $post->move_in_date ? $post->move_in_date->format('d M') : 'Immediate' }}</span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-3 sm:p-4">
                        <div class="flex items-center justify-between gap-1 mb-1">
                            <a href="{{ $slugUrl }}" class="font-extrabold text-sm sm:text-base text-gray-900 group-hover:text-[#4c30dd] transition truncate block no-underline">
                                {{ $post->poster_name }}
                            </a>
                            <i class="fas fa-circle-check text-xs flex-shrink-0" style="color: #4c30dd !important;" title="Verified Member"></i>
                        </div>

                        <p class="text-[10px] sm:text-xs text-gray-500 truncate flex items-center gap-1 mb-2">
                            <i class="fas fa-map-marker-alt text-[10px] shrink-0" style="color: #4c30dd !important;"></i>
                            <span class="truncate">{{ $locationText }}</span>
                        </p>

                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[9px] sm:text-[11px] font-extrabold px-2 py-0.5" style="background-color: #f3f0ff !important; color: #4c30dd !important; border: 1px solid rgba(76, 48, 221, 0.25) !important;">
                                {{ $bhkLabel }}
                            </span>
                            @if($post->profession)
                                <span class="text-[9px] sm:text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-0.5 truncate max-w-[100px]">
                                    {{ $post->profession }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Footer with High Visibility #4c30dd View Button -->
                <div class="p-3 sm:p-4 pt-0 border-t border-gray-100 flex items-center justify-between gap-2 mt-auto">
                    <div>
                        <span class="text-[8px] sm:text-[9px] text-gray-400 uppercase font-bold block">Rent Budget</span>
                        <span class="text-xs sm:text-sm font-black text-gray-900">{{ $post->budget_range }}</span>
                    </div>
                    <a href="{{ $slugUrl }}" class="roommate-btn-view px-3.5 sm:px-4 py-1.5 text-[10px] sm:text-xs font-bold tap-effect flex items-center gap-1.5 no-underline">
                        <span>View</span>
                        <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-gray-100 shadow-sm my-4">
                <h3 class="text-base font-bold text-gray-900">No listings found</h3>
                <a href="{{ route('user.roommate.index') }}" class="text-brand text-sm font-bold mt-2 block">Clear Filters</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if($posts->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $posts->links() }}
    </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
    let drawerCity = '{{ addslashes($selectedCity ?? '') }}';
    let drawerGender = '{{ addslashes($selectedGender ?? '') }}';
    let drawerBhk = '{{ addslashes($selectedBhk ?? '') }}';
    let drawerBudget = '{{ addslashes($selectedBudget ?? '') }}';

    // ================= SKELETON SHIMMER LOADER =================
    window.showRoommateSkeleton = function() {
        const grid = document.getElementById('roommateGrid');
        const skeleton = document.getElementById('roommateSkeletonGrid');
        if (grid && skeleton) {
            grid.classList.add('hidden');
            skeleton.classList.remove('hidden');
            window.scrollTo({ top: Math.max(0, (skeleton.offsetTop || 200) - 100), behavior: 'smooth' });
        }
    };

    // Attach Skeleton Loader to main form submission
    const mainForm = document.getElementById('mainSearchForm');
    if (mainForm) {
        mainForm.addEventListener('submit', function() {
            window.showRoommateSkeleton();
        });
    }

    // Attach Skeleton Loader to pagination links
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function() {
            window.showRoommateSkeleton();
        });
    });

    // Clear search input
    function clearSearchInput() {
        const input = document.getElementById('searchInput');
        if (input) {
            input.value = '';
            window.showRoommateSkeleton();
            document.getElementById('mainSearchForm').submit();
        }
    }

    // Toggle Desktop Filter Panel
    function toggleFilterBar() {
        if (window.innerWidth < 768) {
            openMobileFilterDrawer();
        } else {
            const panel = document.getElementById('desktopFilterPanel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }
    }

    // Quick Natural Prompt helper
    function applyQuickPrompt(val, type) {
        const form = document.getElementById('mainSearchForm');
        if (type === 'gender') document.getElementById('hiddenGenderInput').value = val;
        if (type === 'bhk') document.getElementById('hiddenBhkInput').value = val;
        if (type === 'furnishing') document.getElementById('hiddenFurnishingInput').value = val;
        if (type === 'budget') document.getElementById('hiddenBudgetInput').value = val;
        window.showRoommateSkeleton();
        form.submit();
    }

    // Apply Desktop Filters
    function applyDesktopFilterChange() {
        const form = document.getElementById('mainSearchForm');
        document.getElementById('hiddenCityInput').value = document.getElementById('desktopCitySelect').value;
        document.getElementById('hiddenGenderInput').value = document.getElementById('desktopGenderFilter').value;
        document.getElementById('hiddenBhkInput').value = document.getElementById('desktopBhkFilter').value;
        document.getElementById('hiddenBudgetInput').value = document.getElementById('desktopBudgetFilter').value;
        window.showRoommateSkeleton();
        form.submit();
    }

    // Sort Dropdown
    function handleSortDropdownChange(sortVal) {
        document.getElementById('hiddenSortInput').value = sortVal;
        window.showRoommateSkeleton();
        document.getElementById('mainSearchForm').submit();
    }

    // ================= MOBILE DRAWER LOGIC =================
    function openMobileFilterDrawer() {
        const drawer = document.getElementById('mobileFilterDrawer');
        const backdrop = document.getElementById('filterDrawerBackdrop');
        const sheet = document.getElementById('filterDrawerSheet');
        if (!drawer || !sheet) return;

        drawer.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            sheet.classList.remove('translate-y-full');
            sheet.classList.add('translate-y-0');
        }, 10);
    }

    function closeMobileFilterDrawer() {
        const drawer = document.getElementById('mobileFilterDrawer');
        const backdrop = document.getElementById('filterDrawerBackdrop');
        const sheet = document.getElementById('filterDrawerSheet');
        if (!drawer || !sheet) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        sheet.classList.remove('translate-y-0');
        sheet.classList.add('translate-y-full');
        setTimeout(() => {
            drawer.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }

    function setDrawerCity(city, btn) {
        drawerCity = city;
        document.querySelectorAll('.drawer-city-btn').forEach(b => {
            b.classList.remove('active', 'bg-brand', 'text-white', 'border-brand');
            b.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-200');
        });
        btn.classList.add('active', 'bg-brand', 'text-white', 'border-brand');
        btn.classList.remove('bg-gray-50', 'text-gray-700', 'border-gray-200');
    }

    function setDrawerGender(gender, btn) {
        drawerGender = gender;
        document.querySelectorAll('.drawer-gender-btn').forEach(b => {
            b.classList.remove('active', 'bg-brand', 'text-white', 'border-brand');
            b.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-200');
        });
        btn.classList.add('active', 'bg-brand', 'text-white', 'border-brand');
        btn.classList.remove('bg-gray-50', 'text-gray-700', 'border-gray-200');
    }

    function setDrawerBhk(bhk, btn) {
        drawerBhk = bhk;
        document.querySelectorAll('.drawer-bhk-btn').forEach(b => {
            b.classList.remove('active', 'bg-brand', 'text-white', 'border-brand');
            b.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-200');
        });
        btn.classList.add('active', 'bg-brand', 'text-white', 'border-brand');
        btn.classList.remove('bg-gray-50', 'text-gray-700', 'border-gray-200');
    }

    function setDrawerBudget(budget, btn) {
        drawerBudget = budget;
        document.querySelectorAll('.drawer-budget-btn').forEach(b => {
            b.classList.remove('active', 'bg-brand', 'text-white', 'border-brand');
            b.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-200');
        });
        btn.classList.add('active', 'bg-brand', 'text-white', 'border-brand');
        btn.classList.remove('bg-gray-50', 'text-gray-700', 'border-gray-200');
    }

    function applyMobileDrawerFilters() {
        const form = document.getElementById('mainSearchForm');
        document.getElementById('hiddenCityInput').value = drawerCity;
        document.getElementById('hiddenGenderInput').value = drawerGender;
        document.getElementById('hiddenBhkInput').value = drawerBhk;
        document.getElementById('hiddenBudgetInput').value = drawerBudget;

        // Amenities
        const ac = document.getElementById('drawerFilterAC')?.checked ? '1' : '';
        const fridge = document.getElementById('drawerFilterFridge')?.checked ? '1' : '';
        const wifi = document.getElementById('drawerFilterWifi')?.checked ? '1' : '';
        const food = document.getElementById('drawerFilterFood')?.checked ? '1' : '';

        // Add or remove hidden inputs
        appendHiddenInput(form, 'ac', ac);
        appendHiddenInput(form, 'fridge', fridge);
        appendHiddenInput(form, 'wifi', wifi);
        appendHiddenInput(form, 'food', food);

        closeMobileFilterDrawer();
        window.showRoommateSkeleton();
        form.submit();
    }

    function appendHiddenInput(form, name, value) {
        let input = form.querySelector(`input[name="${name}"]`);
        if (value) {
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                form.appendChild(input);
            }
            input.value = value;
        } else if (input) {
            input.remove();
        }
    }
</script>
@endpush
