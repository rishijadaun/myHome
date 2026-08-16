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
                <input type="text" id="searchInput" onkeyup="filterSearchResults()" placeholder="Search city, locality, PG name..." 
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
                        <option value="Noida">Noida</option>
                        <option value="Bangalore">Bangalore</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Gurugram">Gurugram</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Property Type</label>
                    <select id="desktopTypeFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">All Types</option>
                        <option value="BOYS">Boys PG</option>
                        <option value="GIRLS">Girls PG</option>
                        <option value="CO-ED">Co-living</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Budget Range</label>
                    <select id="desktopBudgetFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any Budget</option>
                        <option value="8000">Under ₹8,000</option>
                        <option value="10000">Under ₹10,000</option>
                        <option value="12000">Under ₹12,000</option>
                        <option value="15000">Under ₹15,000</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Gender</label>
                    <select id="desktopGenderFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all appearance-none cursor-pointer">
                        <option value="">Any</option>
                        <option value="BOYS">Boys</option>
                        <option value="GIRLS">Girls</option>
                        <option value="CO-ED">Co-ed</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterAC" onchange="filterSearchResults()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>AC Required</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterFood" onchange="filterSearchResults()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
                        <span>Food Included</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 hover:text-brand transition">
                        <input type="checkbox" id="filterWifi" onchange="filterSearchResults()" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand">
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
                    Showing <span class="font-bold text-gray-900" id="resultsCount">6</span> properties
                </p>
                <button onclick="resetAllFilters()" id="clearFiltersBtn" class="hidden text-[11px] sm:text-xs font-semibold text-red-500 hover:underline tap-effect flex items-center gap-1">
                    <i class="fas fa-undo-alt text-[9px]"></i> Clear Filters
                </button>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs sm:text-sm text-gray-500 font-medium"><i class="fas fa-sort-amount-down text-brand mr-1"></i> Sort:</span>
                <select id="sortBySelect" onchange="sortSearchResults()" class="bg-white border border-gray-200 rounded-xl py-1.5 px-2 text-xs sm:text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer shadow-sm">
                    <option value="recommended">Recommended</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="rating">Top Rated</option>
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
                        <button type="button" onclick="setDrawerCity('Noida', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect">Noida</button>
                        <button type="button" onclick="setDrawerCity('Bangalore', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect">Bangalore</button>
                        <button type="button" onclick="setDrawerCity('Delhi', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect">Delhi</button>
                        <button type="button" onclick="setDrawerCity('Mumbai', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect">Mumbai</button>
                        <button type="button" onclick="setDrawerCity('Gurugram', this)" class="drawer-btn drawer-city-btn py-2 px-3.5 rounded-full text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect">Gurugram</button>
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
                        <button type="button" onclick="setDrawerBudget('8000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹8,000</button>
                        <button type="button" onclick="setDrawerBudget('10000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹10,000</button>
                        <button type="button" onclick="setDrawerBudget('12000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left">Under ₹12,000</button>
                        <button type="button" onclick="setDrawerBudget('15000', this)" class="drawer-btn drawer-budget-btn py-2.5 px-3 rounded-xl text-xs font-semibold border border-gray-200 bg-gray-50 text-gray-700 tap-effect text-left col-span-2">Under ₹15,000</button>
                    </div>
                </div>

                <!-- 4. Amenities Checklist -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Must Have Amenities</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerAC" class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-snowflake text-brand"></i> AC Room</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerFood" class="w-4 h-4 rounded text-brand focus:ring-brand">
                            <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5"><i class="fas fa-utensils text-brand"></i> Food Included</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                            <input type="checkbox" id="drawerWifi" class="w-4 h-4 rounded text-brand focus:ring-brand">
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
        
        <!-- Property Card 1 -->
        <div class="property-card bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
            data-gender="BOYS" data-price="8500" data-rating="4.8" data-city="Noida" data-ac="true" data-food="true" data-wifi="true" data-security="true">
            <div>
                <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-green-500 text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                        <i class="fas fa-check-circle"></i> <span class="hidden sm:inline">Verified</span>
                    </div>
                    <button onclick="toggleSave(this)" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                        <i class="far fa-heart text-xs sm:text-sm"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold">4.8</span>
                        <span class="text-gray-300 hidden sm:inline">(120)</span>
                    </div>
                </div>
                <div class="p-2.5 sm:p-4">
                    <div class="flex justify-between items-start mb-1 gap-1">
                        <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">Sunrise Premium</h3>
                        <span class="bg-blue-50 text-blue-600 text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">BOYS</span>
                    </div>
                    <p class="text-gray-500 text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 prop-loc truncate">
                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> Sector 62, Noida
                    </p>
                    <div class="hidden sm:flex flex-wrap gap-1.5 mb-3">
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-wifi text-brand"></i> WiFi
                        </span>
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-snowflake text-brand"></i> AC
                        </span>
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-utensils text-brand"></i> Food
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-2.5 pb-2.5 sm:px-4 sm:pb-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-[9px] sm:text-xs text-gray-500 block">Rent</span>
                    <div class="text-xs sm:text-lg font-extrabold text-gray-900 leading-none">₹8,500<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></div>
                </div>
                <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm">View</a>
            </div>
        </div>

        <!-- Property Card 2 -->
        <div class="property-card bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
            data-gender="GIRLS" data-price="9999" data-rating="4.9" data-city="Bangalore" data-ac="false" data-food="true" data-wifi="true" data-security="true">
            <div>
                <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full">
                    <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-green-500 text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                        <i class="fas fa-check-circle"></i> <span class="hidden sm:inline">Verified</span>
                    </div>
                    <button onclick="toggleSave(this)" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                        <i class="far fa-heart text-xs sm:text-sm"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold">4.9</span>
                        <span class="text-gray-300 hidden sm:inline">(98)</span>
                    </div>
                </div>
                <div class="p-2.5 sm:p-4">
                    <div class="flex justify-between items-start mb-1 gap-1">
                        <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">Aura Women's</h3>
                        <span class="bg-pink-50 text-pink-600 text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">GIRLS</span>
                    </div>
                    <p class="text-gray-500 text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 prop-loc truncate">
                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> Indiranagar, BLR
                    </p>
                    <div class="hidden sm:flex flex-wrap gap-1.5 mb-3">
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-wifi text-brand"></i> WiFi
                        </span>
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-shield-alt text-brand"></i> Security
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-2.5 pb-2.5 sm:px-4 sm:pb-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-[9px] sm:text-xs text-gray-500 block">Rent</span>
                    <div class="text-xs sm:text-lg font-extrabold text-gray-900 leading-none">₹9,999<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></div>
                </div>
                <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm">View</a>
            </div>
        </div>

        <!-- Property Card 3 -->
        <div class="property-card bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
            data-gender="CO-ED" data-price="11500" data-rating="4.7" data-city="Bangalore" data-ac="true" data-food="false" data-wifi="true" data-security="true">
            <div>
                <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full">
                    <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-orange-500 text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                        <i class="fas fa-fire"></i> <span class="hidden sm:inline">Popular</span>
                    </div>
                    <button onclick="toggleSave(this)" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                        <i class="far fa-heart text-xs sm:text-sm"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold">4.7</span>
                        <span class="text-gray-300 hidden sm:inline">(75)</span>
                    </div>
                </div>
                <div class="p-2.5 sm:p-4">
                    <div class="flex justify-between items-start mb-1 gap-1">
                        <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">Urban Nest Co</h3>
                        <span class="bg-purple-50 text-purple-600 text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">CO-ED</span>
                    </div>
                    <p class="text-gray-500 text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 prop-loc truncate">
                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> HSR Layout, BLR
                    </p>
                    <div class="hidden sm:flex flex-wrap gap-1.5 mb-3">
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-dumbbell text-brand"></i> Gym
                        </span>
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-wifi text-brand"></i> WiFi
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-2.5 pb-2.5 sm:px-4 sm:pb-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-[9px] sm:text-xs text-gray-500 block">Rent</span>
                    <div class="text-xs sm:text-lg font-extrabold text-gray-900 leading-none">₹11,500<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></div>
                </div>
                <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm">View</a>
            </div>
        </div>

        <!-- Property Card 4 -->
        <div class="property-card bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
            data-gender="BOYS" data-price="10000" data-rating="4.6" data-city="Delhi" data-ac="true" data-food="false" data-wifi="true" data-security="true">
            <div>
                <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-green-500 text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                        <i class="fas fa-check-circle"></i> <span class="hidden sm:inline">Verified</span>
                    </div>
                    <button onclick="toggleSave(this)" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                        <i class="far fa-heart text-xs sm:text-sm"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold">4.6</span>
                        <span class="text-gray-300 hidden sm:inline">(85)</span>
                    </div>
                </div>
                <div class="p-2.5 sm:p-4">
                    <div class="flex justify-between items-start mb-1 gap-1">
                        <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">Metro Stay Prem</h3>
                        <span class="bg-blue-50 text-blue-600 text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">BOYS</span>
                    </div>
                    <p class="text-gray-500 text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 prop-loc truncate">
                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> Saket, Delhi
                    </p>
                    <div class="hidden sm:flex flex-wrap gap-1.5 mb-3">
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-snowflake text-brand"></i> AC
                        </span>
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-wifi text-brand"></i> WiFi
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-2.5 pb-2.5 sm:px-4 sm:pb-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-[9px] sm:text-xs text-gray-500 block">Rent</span>
                    <div class="text-xs sm:text-lg font-extrabold text-gray-900 leading-none">₹10,000<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></div>
                </div>
                <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm">View</a>
            </div>
        </div>

        <!-- Property Card 5 -->
        <div class="property-card bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
            data-gender="GIRLS" data-price="11500" data-rating="4.8" data-city="Bangalore" data-ac="false" data-food="true" data-wifi="true" data-security="true">
            <div>
                <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full">
                    <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-green-500 text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                        <i class="fas fa-check-circle"></i> <span class="hidden sm:inline">Verified</span>
                    </div>
                    <button onclick="toggleSave(this)" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                        <i class="far fa-heart text-xs sm:text-sm"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold">4.8</span>
                        <span class="text-gray-300 hidden sm:inline">(110)</span>
                    </div>
                </div>
                <div class="p-2.5 sm:p-4">
                    <div class="flex justify-between items-start mb-1 gap-1">
                        <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">Koramangala Stay</h3>
                        <span class="bg-pink-50 text-pink-600 text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">GIRLS</span>
                    </div>
                    <p class="text-gray-500 text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 prop-loc truncate">
                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> Koramangala, BLR
                    </p>
                    <div class="hidden sm:flex flex-wrap gap-1.5 mb-3">
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-utensils text-brand"></i> Food
                        </span>
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-broom text-brand"></i> Cleaning
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-2.5 pb-2.5 sm:px-4 sm:pb-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-[9px] sm:text-xs text-gray-500 block">Rent</span>
                    <div class="text-xs sm:text-lg font-extrabold text-gray-900 leading-none">₹11,500<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></div>
                </div>
                <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm">View</a>
            </div>
        </div>

        <!-- Property Card 6 -->
        <div class="property-card bg-white rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group flex flex-col justify-between w-full" 
            data-gender="CO-ED" data-price="13000" data-rating="4.9" data-city="Bangalore" data-ac="true" data-food="false" data-wifi="true" data-security="true">
            <div>
                <div class="relative h-32 sm:h-44 md:h-52 overflow-hidden w-full">
                    <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-orange-500 text-white text-[9px] sm:text-xs font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1 shadow-sm">
                        <i class="fas fa-fire"></i> <span class="hidden sm:inline">Popular</span>
                    </div>
                    <button onclick="toggleSave(this)" class="save-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-9 sm:h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                        <i class="far fa-heart text-xs sm:text-sm"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-gray-900/80 backdrop-blur text-white text-[9px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-xl flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold">4.9</span>
                        <span class="text-gray-300 hidden sm:inline">(95)</span>
                    </div>
                </div>
                <div class="p-2.5 sm:p-4">
                    <div class="flex justify-between items-start mb-1 gap-1">
                        <h3 class="font-bold text-xs sm:text-base text-gray-900 group-hover:text-brand transition prop-title truncate">Indiranagar PG</h3>
                        <span class="bg-purple-50 text-purple-600 text-[8px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded flex-shrink-0">CO-ED</span>
                    </div>
                    <p class="text-gray-500 text-[10px] sm:text-xs mb-1.5 flex items-center gap-1 prop-loc truncate">
                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> Indiranagar, BLR
                    </p>
                    <div class="hidden sm:flex flex-wrap gap-1.5 mb-3">
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-parking text-brand"></i> Parking
                        </span>
                        <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100 flex items-center gap-1">
                            <i class="fas fa-dumbbell text-brand"></i> Gym
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-2.5 pb-2.5 sm:px-4 sm:pb-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-[9px] sm:text-xs text-gray-500 block">Rent</span>
                    <div class="text-xs sm:text-lg font-extrabold text-gray-900 leading-none">₹13,000<span class="text-[9px] sm:text-xs font-normal text-gray-500">/mo</span></div>
                </div>
                <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold transition tap-effect shadow-sm">View</a>
            </div>
        </div>

    </div>

    <!-- Load More Button -->
    <div class="mt-10 text-center">
        <button onclick="loadMoreProperties(this)" class="bg-white border-2 border-gray-200 hover:border-brand hover:text-brand text-gray-700 font-bold py-3 px-8 rounded-2xl transition tap-effect shadow-sm flex items-center gap-2 mx-auto text-xs sm:text-sm">
            <i class="fas fa-spinner"></i> Load More Properties
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let drawerCity = '';
    let drawerGender = 'ALL';
    let drawerBudget = '';

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
        if (budget || drawerBudget) activeCount++;
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
            const matchBudget = !budget || elPrice <= parseInt(budget);

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
</script>
@endpush
