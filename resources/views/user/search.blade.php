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
<div class="pt-20 md:pt-8 pb-20 max-w-7xl mx-auto px-4 md:px-6">

    <!-- ================= MOBILE HEADER: SEARCH + FILTER TOGGLE + SORT BY ONLY ================= -->
    <div class="md:hidden mb-5">
        <!-- Row 1: Search Input & App-Style Filter Toggle Button -->
        <div class="flex items-center gap-2 mb-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                <input type="text" id="mobileSearchInput" onkeyup="filterSearchResults()" placeholder="Search by locality, landmark, city..." 
                    class="w-full bg-white border border-gray-200 rounded-2xl py-3 pl-11 pr-4 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50 shadow-sm">
            </div>
            <!-- App-style Filter Toggle Button -->
            <button onclick="openMobileFilterDrawer()" id="mobileFilterToggleBtn" class="relative flex items-center justify-center gap-1.5 px-3.5 h-11 bg-white border border-gray-200 rounded-2xl text-gray-700 hover:text-brand hover:border-brand tap-effect shadow-sm flex-shrink-0">
                <i class="fas fa-sliders-h text-sm text-brand"></i>
                <span class="text-xs font-semibold">Filter</span>
                <span id="activeFilterBadge" class="hidden w-5 h-5 bg-brand text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">0</span>
            </button>
        </div>

        <!-- Row 2: Results Count & Sort By Filter Dropdown -->
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <p class="text-xs text-gray-600">
                    <span class="font-bold text-gray-900" id="resultsCountMobile">6</span> properties found
                </p>
                <button onclick="resetAllFilters()" id="mobileResetLink" class="hidden text-[11px] font-semibold text-red-500 hover:underline tap-effect">
                    <i class="fas fa-undo-alt text-[9px]"></i> Clear
                </button>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs text-gray-500 font-medium"><i class="fas fa-sort-amount-down text-brand text-xs mr-0.5"></i> Sort:</span>
                <select id="sortBySelectMobile" onchange="syncSort(this.value)" class="bg-white border border-gray-200 rounded-xl py-1.5 px-2.5 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer shadow-sm">
                    <option value="recommended">Recommended</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="rating">Top Rated</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ================= MOBILE APP-STYLE FILTER BOTTOM SHEET DRAWER (ALL OTHER FILTERS GO HERE) ================= -->
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

    <!-- ================= DESKTOP SEARCH & FILTERS ================= -->
    <div class="hidden md:block mb-8">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="relative">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Location</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-4 top-3.5 text-brand"></i>
                        <input type="text" id="desktopSearchInput" onkeyup="filterSearchResults()" placeholder="Enter city or locality..." 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all">
                    </div>
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
                        <i class="fas fa-search"></i> Search Properties
                    </button>
                </div>
            </div>
        </div>

        <!-- Desktop Quick Popular Cities -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar mt-6 mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Popular Cities:</span>
            <button onclick="setCityQuery('Noida')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand hover:text-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition shadow-sm">Noida</button>
            <button onclick="setCityQuery('Bangalore')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand hover:text-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition shadow-sm">Bangalore</button>
            <button onclick="setCityQuery('Delhi')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand hover:text-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition shadow-sm">Delhi</button>
            <button onclick="setCityQuery('Mumbai')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand hover:text-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition shadow-sm">Mumbai</button>
            <button onclick="setCityQuery('Gurugram')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand hover:text-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition shadow-sm">Gurugram</button>
        </div>
    </div>

    <!-- Desktop Results Count & Sort By -->
    <div class="hidden md:flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <p class="text-sm text-gray-600">
                Showing <span class="font-bold text-gray-900" id="resultsCount">6</span> properties
            </p>
            <button onclick="resetAllFilters()" id="clearFiltersBtn" class="hidden text-xs font-semibold text-red-500 hover:underline tap-effect">
                <i class="fas fa-times-circle mr-1"></i> Clear Filters
            </button>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500">Sort by:</span>
            <select id="sortBySelect" onchange="syncSort(this.value)" class="bg-white border border-gray-200 rounded-xl py-2 px-3 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer shadow-sm">
                <option value="recommended">Recommended</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="rating">Top Rated</option>
            </select>
        </div>
    </div>

    <!-- ================= PROPERTY GRID ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="searchGrid">
        
        <!-- Property Card 1 -->
        <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group" 
            data-gender="BOYS" data-price="8500" data-rating="4.8" data-city="Noida" data-ac="true" data-food="true" data-wifi="true" data-security="true">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
                <button onclick="toggleSave(this)" class="save-btn absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                    <i class="far fa-heart"></i>
                </button>
                <div class="absolute bottom-4 left-4 bg-gray-900/80 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-bold">4.8</span>
                    <span class="text-gray-300">(120)</span>
                </div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-brand transition prop-title">Sunrise Premium PG</h3>
                    <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-lg">BOYS</span>
                </div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5 prop-loc">
                    <i class="fas fa-map-marker-alt text-brand text-xs"></i> Sector 62, Noida • 1.2 km
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-snowflake text-brand text-[10px]"></i> AC
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-utensils text-brand text-[10px]"></i> Food
                    </span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-500">Starts from</span>
                        <div class="text-xl font-extrabold text-gray-900">₹8,500<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    </div>
                    <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold transition tap-effect shadow-md shadow-brand/20">View</a>
                </div>
            </div>
        </div>

        <!-- Property Card 2 -->
        <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group" 
            data-gender="GIRLS" data-price="9999" data-rating="4.9" data-city="Bangalore" data-ac="false" data-food="true" data-wifi="true" data-security="true">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
                <button onclick="toggleSave(this)" class="save-btn absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                    <i class="far fa-heart"></i>
                </button>
                <div class="absolute bottom-4 left-4 bg-gray-900/80 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-bold">4.9</span>
                    <span class="text-gray-300">(98)</span>
                </div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-brand transition prop-title">Aura Women's Stay</h3>
                    <span class="bg-pink-50 text-pink-600 text-[10px] font-bold px-2 py-1 rounded-lg">GIRLS</span>
                </div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5 prop-loc">
                    <i class="fas fa-map-marker-alt text-brand text-xs"></i> Indiranagar, Bangalore • 0.5 km
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-shield-alt text-brand text-[10px]"></i> Security
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-broom text-brand text-[10px]"></i> Cleaning
                    </span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-500">Starts from</span>
                        <div class="text-xl font-extrabold text-gray-900">₹9,999<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    </div>
                    <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold transition tap-effect shadow-md shadow-brand/20">View</a>
                </div>
            </div>
        </div>

        <!-- Property Card 3 -->
        <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group" 
            data-gender="CO-ED" data-price="11500" data-rating="4.7" data-city="Bangalore" data-ac="true" data-food="false" data-wifi="true" data-security="true">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm">
                    <i class="fas fa-fire"></i> Popular
                </div>
                <button onclick="toggleSave(this)" class="save-btn absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                    <i class="far fa-heart"></i>
                </button>
                <div class="absolute bottom-4 left-4 bg-gray-900/80 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-bold">4.7</span>
                    <span class="text-gray-300">(75)</span>
                </div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-brand transition prop-title">Urban Nest Co-living</h3>
                    <span class="bg-purple-50 text-purple-600 text-[10px] font-bold px-2 py-1 rounded-lg">CO-ED</span>
                </div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5 prop-loc">
                    <i class="fas fa-map-marker-alt text-brand text-xs"></i> HSR Layout, Bangalore • 2.1 km
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-dumbbell text-brand text-[10px]"></i> Gym
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-gamepad text-brand text-[10px]"></i> Games
                    </span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-500">Starts from</span>
                        <div class="text-xl font-extrabold text-gray-900">₹11,500<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    </div>
                    <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold transition tap-effect shadow-md shadow-brand/20">View</a>
                </div>
            </div>
        </div>

        <!-- Property Card 4 -->
        <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group" 
            data-gender="BOYS" data-price="10000" data-rating="4.6" data-city="Delhi" data-ac="true" data-food="false" data-wifi="true" data-security="true">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
                <button onclick="toggleSave(this)" class="save-btn absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                    <i class="far fa-heart"></i>
                </button>
                <div class="absolute bottom-4 left-4 bg-gray-900/80 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-bold">4.6</span>
                    <span class="text-gray-300">(85)</span>
                </div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-brand transition prop-title">Metro Stay Premium</h3>
                    <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-lg">BOYS</span>
                </div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5 prop-loc">
                    <i class="fas fa-map-marker-alt text-brand text-xs"></i> Saket, Delhi • 0.8 km
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-snowflake text-brand text-[10px]"></i> AC
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-shield-alt text-brand text-[10px]"></i> Security
                    </span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-500">Starts from</span>
                        <div class="text-xl font-extrabold text-gray-900">₹10,000<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    </div>
                    <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold transition tap-effect shadow-md shadow-brand/20">View</a>
                </div>
            </div>
        </div>

        <!-- Property Card 5 -->
        <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group" 
            data-gender="GIRLS" data-price="11500" data-rating="4.8" data-city="Bangalore" data-ac="false" data-food="true" data-wifi="true" data-security="true">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm">
                    <i class="fas fa-check-circle"></i> Verified
                </div>
                <button onclick="toggleSave(this)" class="save-btn absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                    <i class="far fa-heart"></i>
                </button>
                <div class="absolute bottom-4 left-4 bg-gray-900/80 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-bold">4.8</span>
                    <span class="text-gray-300">(110)</span>
                </div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-brand transition prop-title">Koramangala Hub</h3>
                    <span class="bg-pink-50 text-pink-600 text-[10px] font-bold px-2 py-1 rounded-lg">GIRLS</span>
                </div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5 prop-loc">
                    <i class="fas fa-map-marker-alt text-brand text-xs"></i> Koramangala, Bangalore • 1.5 km
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-utensils text-brand text-[10px]"></i> Food
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-broom text-brand text-[10px]"></i> Cleaning
                    </span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-500">Starts from</span>
                        <div class="text-xl font-extrabold text-gray-900">₹11,500<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    </div>
                    <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold transition tap-effect shadow-md shadow-brand/20">View</a>
                </div>
            </div>
        </div>

        <!-- Property Card 6 -->
        <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 card-lift group" 
            data-gender="CO-ED" data-price="13000" data-rating="4.9" data-city="Bangalore" data-ac="true" data-food="false" data-wifi="true" data-security="true">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm">
                    <i class="fas fa-fire"></i> Popular
                </div>
                <button onclick="toggleSave(this)" class="save-btn absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-sm">
                    <i class="far fa-heart"></i>
                </button>
                <div class="absolute bottom-4 left-4 bg-gray-900/80 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-bold">4.9</span>
                    <span class="text-gray-300">(95)</span>
                </div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-brand transition prop-title">Indiranagar Premium</h3>
                    <span class="bg-purple-50 text-purple-600 text-[10px] font-bold px-2 py-1 rounded-lg">CO-ED</span>
                </div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5 prop-loc">
                    <i class="fas fa-map-marker-alt text-brand text-xs"></i> Indiranagar, Bangalore • 0.9 km
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-wifi text-brand text-[10px]"></i> WiFi
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-dumbbell text-brand text-[10px]"></i> Gym
                    </span>
                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100 flex items-center gap-1">
                        <i class="fas fa-car text-brand text-[10px]"></i> Parking
                    </span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-xs text-gray-500">Starts from</span>
                        <div class="text-xl font-extrabold text-gray-900">₹13,000<span class="text-sm font-normal text-gray-500">/mo</span></div>
                    </div>
                    <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl text-sm font-bold transition tap-effect shadow-md shadow-brand/20">View</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Load More Button -->
    <div class="mt-10 text-center">
        <button onclick="loadMoreProperties(this)" class="bg-white border-2 border-gray-200 hover:border-brand hover:text-brand text-gray-700 font-bold py-3.5 px-8 rounded-2xl transition tap-effect shadow-sm flex items-center gap-2 mx-auto">
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

    // ================= MOBILE FILTER DRAWER OPEN / CLOSE =================
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

    function setCityQuery(city) {
        const dInput = document.getElementById('desktopSearchInput');
        const mInput = document.getElementById('mobileSearchInput');
        if (dInput) dInput.value = city;
        if (mInput) mInput.value = city;
        filterSearchResults();
    }

    function syncSort(val) {
        const dSort = document.getElementById('sortBySelect');
        const mSort = document.getElementById('sortBySelectMobile');
        if (dSort) dSort.value = val;
        if (mSort) mSort.value = val;
        sortSearchResults();
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
        const dInput = document.getElementById('desktopSearchInput');
        const mInput = document.getElementById('mobileSearchInput');
        if (dInput) dInput.value = '';
        if (mInput) mInput.value = '';

        // Reset selects
        const deskType = document.getElementById('desktopTypeFilter');
        const deskGender = document.getElementById('desktopGenderFilter');
        const deskBudget = document.getElementById('desktopBudgetFilter');
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
        syncSort('recommended');

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
        const query = (document.getElementById('desktopSearchInput')?.value || document.getElementById('mobileSearchInput')?.value || '').toLowerCase().trim();
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
        const mobReset = document.getElementById('mobileResetLink');
        const drawerBadge = document.getElementById('drawerActiveCount');

        if (activeCount > 0) {
            if (badge) {
                badge.innerText = activeCount;
                badge.classList.remove('hidden');
            }
            if (clearBtn) clearBtn.classList.remove('hidden');
            if (mobReset) mobReset.classList.remove('hidden');
            if (drawerBadge) {
                drawerBadge.innerText = `${activeCount} active`;
                drawerBadge.classList.remove('hidden');
            }
        } else {
            if (badge) badge.classList.add('hidden');
            if (clearBtn) clearBtn.classList.add('hidden');
            if (mobReset) mobReset.classList.add('hidden');
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

            // City filter from drawer
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

        const countMobEl = document.getElementById('resultsCountMobile');
        if (countMobEl) countMobEl.innerText = visibleCount;
    }

    function sortSearchResults() {
        const sortVal = document.getElementById('sortBySelect')?.value || document.getElementById('sortBySelectMobile')?.value;
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
