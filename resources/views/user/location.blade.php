@extends('user.layouts.map')

@section('title', 'Explore PGs on Map - Find Verified Stays & Hostels Near You | StayNest')
@section('meta_description', 'Locate verified paying guest (PG) accommodations, boys & girls hostels, and luxury co-living spaces on an interactive GPS map. Filter by budget, gender, amenities, and get instant directions with zero brokerage.')
@section('meta_keywords', 'PG on map, find PG near me, interactive PG map, PG locator India, hostel map, student housing near me, StayNest map, PG directions')
@section('canonical', route('user.location'))
@section('og_type', 'website')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SearchResultsPage",
  "name": "Explore Verified PGs and Stays on Interactive Map",
  "description": "Locate verified paying guest accommodations, boys & girls PGs, and luxury co-living stays on an interactive map.",
  "url": "{{ route('user.location') }}",
  "mainEntity": {
    "@type": "ItemList",
    "name": "Map Located Accommodations",
    "itemListOrder": "https://schema.org/ItemListUnordered",
    "numberOfItems": {{ isset($properties) ? count($properties) : 20 }}
  }
}
</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        .leaflet-routing-container { display: none !important; }
        
        /* Price Badge Marker */
        .price-badge-container { background: transparent !important; border: none !important; }
        .custom-price-badge {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 800;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4), 0 0 0 2px rgba(255,255,255,0.1);
            border: 2px solid #ffffff;
            white-space: nowrap;
            transform: translate(-50%, -120%); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            z-index: 1000 !important;
            display: inline;
        }
        .custom-price-badge::before { content: '₹'; font-size: 16px; font-weight: 900; }
        .custom-price-badge:hover {
            background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%);
            transform: translate(-50%, -130%) scale(1.1);
            z-index: 9999 !important;
        }
        @media (max-width: 767px) {
            .custom-price-badge {
                padding: 5px 10px;
                border-radius: 16px;
                font-size: 11px;
                transform: translate(60%, -50%);
            }
            .custom-price-badge::before { font-size: 12px; }
            .custom-price-badge:hover { transform: translate(60%, -50%) scale(1.05); }
        }

        /* User Location Marker */
        .user-marker-icon {
            background: radial-gradient(circle, #ff3b30 40%, #dc2626 100%);
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 16px rgba(239, 68, 68, 0.7), 0 0 0 4px rgba(239, 68, 68, 0.35);
            width: 22px;
            height: 22px;
            position: relative;
            animation: radar-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .user-marker-icon::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }
        @keyframes radar-pulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7), 0 0 16px rgba(239, 68, 68, 0.9); }
            70% { box-shadow: 0 0 0 20px rgba(239, 68, 68, 0), 0 0 20px rgba(239, 68, 68, 0.4); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0), 0 0 16px rgba(239, 68, 68, 0.9); }
        }

        .leaflet-popup-content-wrapper {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 0;
            overflow: hidden;
        }
        .leaflet-popup-content { margin: 0; width: 300px !important;padding:5px; }
        .leaflet-container a.leaflet-popup-close-button {
            color: white;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            width: 26px;
            height: 26px;
            right: 8px;
            top: 8px;
        }

        /* Bottom Sheet */
        #sidebar { transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1); }
        @media (min-width: 768px) {
            #sidebar { transform: none !important; width: 420px; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        }
        @media (max-width: 767px) { #sidebar { width: 100% !important; } }

        .drag-handle { width: 36px; height: 4px; background: #e2e8f0; border-radius: 2px; }
        .drag-handle:active { background: #94a3b8; }

        .chip-active { background: #4bb59d !important; color: white !important; border-color: #4bb59d !important; }

        .fab-btn {
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            color: #475569;
            font-size: 18px;
        }
        .fab-btn:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 8px 24px rgba(0,0,0,0.18); }
        .fab-btn:active { transform: scale(0.92); }
        .fab-btn.active { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); color: white; }
        .fab-btn.primary { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); color: white; box-shadow: 0 8px 24px rgba(75, 181, 157, 0.4); }

        .pg-card { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .pg-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .pg-card:active { transform: scale(0.98); }
        .pg-card.active-card { border-color: #4bb59d !important; box-shadow: 0 0 0 2px rgba(75, 181, 157, 0.2); }

        /* Dual Action Buttons */
        .btn-directions {
            background: #eef2ff;
            color: #1a1a7f;
            border: 1px solid #dbeafe;
        }
        .btn-directions:hover { background: #1a1a7f; color: white; }
        
        .btn-view-property {
            background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%);
            color: white;
            border: none;
        }
        .btn-view-property:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Radius Slider */
        input[type="range"] {
            -webkit-appearance: none;
            appearance: none;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            outline: none;
        }
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            background: #1a1a7f;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        
        /* Layout overrides for map full height */
        footer { display: none !important; }
        main { display: flex; flex-direction: column; }
        .left-14 {
            left: 4rem;
        }
    </style>
@endpush

@section('content')
    <!-- Map Toolbar with Highlighted AI Search -->
    <div class="bg-white border-b border-gray-200 z-10 px-4 py-3 flex flex-col md:flex-row justify-between items-center gap-3 w-full shrink-0 shadow-sm pt-5 md:pt-4">
        
        <!-- Highlighted AI Smart Search Bar -->
        <div class="relative w-full md:max-w-md lg:max-w-lg flex items-center gap-2">
            <a href="{{ route('user.home') }}" class="flex-shrink-0" title="Back to Home">
                <button class="w-10 h-10 rounded-xl bg-slate-900 hover:bg-slate-800 flex items-center justify-center transition tap-effect shadow-xs">
                    <i class="fas fa-arrow-left text-white text-sm"></i>
                </button>
            </a>
            
            <div class="relative flex-1 group">
                <!-- Glowing AI Gradient Ring -->
                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 via-indigo-500 to-brand rounded-2xl blur-xs opacity-70 group-focus-within:opacity-100 group-hover:opacity-90 transition duration-300"></div>
                
                <div class="relative flex items-center bg-white rounded-xl shadow-sm border border-purple-100">
                    <!-- AI Wand Icon -->
                    <div class="pl-3.5 pr-1 flex items-center pointer-events-none">
                        <i class="fas fa-wand-magic-sparkles text-purple-600 text-sm animate-pulse"></i>
                    </div>

                    <input type="text" id="mapSearchInput" 
                        placeholder="✨ 'Boys PG under 8k in Noida'..." 
                        class="w-full bg-transparent py-2.5 pl-2 pr-5 text-xs sm:text-sm font-semibold text-gray-800 placeholder:text-gray-400 placeholder:font-normal focus:outline-none"
                        onkeypress="handleSearchKeyPress(event)"
                        oninput="handleSearchInput(this.value)">

                    <!-- Clear input button -->
                    <button type="button" id="clearSearchBtn" onclick="clearAISearchInput()" class="hidden absolute right-18 text-gray-400 hover:text-gray-600 text-xs p-1">
                        <i class="fas fa-times-circle"></i>
                    </button>

                    <!-- AI Search Submit Button -->
                    <button onclick="performAISmartSearch()" class="mr-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5 tap-effect shadow-md shadow-purple-500/20 transition-all cursor-pointer shrink-0">
                        <span>AI Search</span>
                        <i class="fas fa-sparkles text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto no-scrollbar pb-1 md:pb-0">
            <!-- Radius Slider -->
            <div class="flex items-center gap-2 bg-primary/5 px-3 py-1.5 rounded-xl border border-primary/20 shrink-0">
                <i class="fas fa-sliders text-primary text-xs"></i>
                <label class="text-xs font-bold text-primary whitespace-nowrap">Range: <span id="radiusLabel">10</span> km</label>
                <input type="range" id="radiusRange" min="1" max="50" value="10" oninput="updateRangeRadius(this.value)" class="w-20">
            </div>

            <button onclick="focusIndiaMap()" class="bg-primary hover:bg-primary-dark text-white text-xs font-semibold rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all tap-effect shrink-0">
                <i class="fas fa-flag"></i> India
            </button>
            
            <button onclick="goToMyLocation()" class="bg-gradient-to-r from-brand to-brand-dark text-white text-xs font-semibold rounded-xl px-3 py-1.5 flex items-center gap-2 transition-all tap-effect shadow-md shrink-0">
                <i class="fas fa-location-crosshairs"></i> My Location
            </button>
        </div>
    </div>

    <!-- Map & Sidebar Workspace -->
    <div class="flex flex-1 overflow-hidden relative w-full h-[70vh] md:h-auto min-h-[500px]" id="mainContainer">
        
        <!-- Sidebar / Bottom Sheet -->
        <aside id="sidebar" class="bg-white md:border-r border-gray-100 flex flex-col z-[500] flex-shrink-0 md:relative fixed bottom-0 left-0 h-[65vh] md:h-full shadow-sheet md:shadow-none rounded-t-3xl md:rounded-none overflow-hidden w-full md:w-[420px]">
            <div class="md:hidden flex justify-center pt-3 pb-2 cursor-grab active:cursor-grabbing" onclick="toggleMobileSidebar()">
                <div class="drag-handle"></div>
            </div>

            <div class="px-5 pb-3 flex-shrink-0 bg-white border-b border-gray-100 space-y-2.5">
                <!-- User Current Address -->
                <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 rounded-xl p-3 mt-2 md:mt-0 shadow-xs">
                    <div class="flex items-start gap-2.5">
                        <div onclick="goToMyLocation()" class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white flex-shrink-0 shadow-xs cursor-pointer hover:bg-red-600 transition" title="Center map on your location">
                            <i class="fas fa-location-crosshairs text-sm animate-pulse"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                <span class="text-[10px] font-extrabold text-red-600 uppercase tracking-wider" id="addressTypeLabel">Verified Home Address</span>
                                <span id="gpsLiveBadge" class="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-700 bg-emerald-100/90 px-1.5 py-0.2 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> VERIFIED HOME
                                </span>
                            </div>
                            <div id="userAddressDisplay" class="text-xs font-bold text-slate-800 leading-tight line-clamp-2 cursor-pointer hover:text-brand transition" onclick="goToMyLocation()">Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309</div>
                            
                            <!-- Switch buttons -->
                            <div class="flex items-center gap-2 mt-2 pt-1.5 border-t border-red-100/80">
                                <button type="button" onclick="useProfileHomeAddress()" id="btnUseHome" class="text-[10px] font-extrabold text-brand bg-white border border-brand/40 px-2 py-0.5 rounded-md hover:bg-brand hover:text-white transition shadow-xs">
                                    <i class="fas fa-house-user mr-0.5"></i> Profile Home
                                </button>
                                <button type="button" onclick="useDeviceGPS()" id="btnUseGps" class="text-[10px] font-bold text-gray-600 bg-white border border-gray-200 px-2 py-0.5 rounded-md hover:bg-gray-100 transition shadow-xs">
                                    <i class="fas fa-crosshairs mr-0.5"></i> Auto GPS
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Match Status Banner -->
                <!-- <div id="aiMatchStatusBanner" class="hidden p-3 rounded-2xl bg-gradient-to-r from-purple-50 via-indigo-50 to-brand-50 border border-purple-200/80 text-xs shadow-xs space-y-1.5 animate-fadeIn">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5 font-extrabold text-purple-900 text-xs">
                            <i class="fas fa-wand-magic-sparkles text-purple-600"></i>
                            <span>AI Stay Matcher Active</span>
                        </div>
                        <button type="button" onclick="resetAISearch()" class="text-[10px] font-bold text-purple-600 hover:text-purple-900 bg-white/90 px-2 py-0.5 rounded-md border border-purple-200 shadow-xs">
                            Reset Search
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-1.5" id="aiParsedBadges"></div>
                </div> -->

                <!-- Quick AI Suggestions Pills (20+ Rich Queries) -->
                <div>
                    <!-- <div class="flex items-center justify-between text-[10px] font-bold text-gray-400 mb-1.5">
                        <span class="flex items-center gap-1"><i class="fas fa-wand-magic-sparkles text-purple-600 animate-pulse"></i> Quick AI Suggestions (20+ Prompts)</span>
                        <span class="text-[9px] text-purple-600 font-semibold">Scroll &rarr;</span>
                    </div> -->
                    <div class="flex gap-1.5 overflow-x-auto no-scrollbar pb-1">
                        <button type="button" onclick="applyAIPrompt('Boys PG under 8000 in Noida')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 transition tap-effect">✨ Boys &lt; ₹8K Noida</button>
                        <button type="button" onclick="applyAIPrompt('Girls PG with AC in Bangalore')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 transition tap-effect">✨ Girls AC Bangalore</button>
                        <button type="button" onclick="applyAIPrompt('Co-ed unisex coliving stays')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-pink-50 hover:bg-pink-100 text-pink-700 border border-pink-200 transition tap-effect">✨ Co-Ed / Unisex Stays</button>
                        <button type="button" onclick="applyAIPrompt('Luxury stay with food and wifi in Delhi')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition tap-effect">✨ Food &amp; WiFi Delhi</button>
                        <button type="button" onclick="applyAIPrompt('Budget stays under 6000')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 transition tap-effect">✨ Under ₹6K Budget</button>
                        <button type="button" onclick="applyAIPrompt('Single private room PG')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-cyan-50 hover:bg-cyan-100 text-cyan-700 border border-cyan-200 transition tap-effect">✨ Single Private Room</button>
                        <button type="button" onclick="applyAIPrompt('Stays near metro station')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition tap-effect">✨ Near Metro Station</button>
                        <button type="button" onclick="applyAIPrompt('Luxury co-living in Gurgaon Cyber City')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 transition tap-effect">✨ Luxury Gurgaon PG</button>
                        <button type="button" onclick="applyAIPrompt('Boys PG in Hinjewadi Pune')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 transition tap-effect">✨ Boys Pune Hinjewadi</button>
                        <button type="button" onclick="applyAIPrompt('Girls PG in Hitec City Hyderabad')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition tap-effect">✨ Girls Hyderabad Hitec</button>
                        <button type="button" onclick="applyAIPrompt('Sector 62 Noida stays')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 transition tap-effect">✨ Sector 62 Noida</button>
                        <button type="button" onclick="applyAIPrompt('Koramangala Bangalore PG with AC')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 transition tap-effect">✨ Koramangala Bangalore</button>
                        <button type="button" onclick="applyAIPrompt('Orai city stays')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition tap-effect">✨ Orai City Stays</button>
                        <button type="button" onclick="applyAIPrompt('Jhansi and Kanpur stays')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 transition tap-effect">✨ Jhansi &amp; Kanpur</button>
                        <button type="button" onclick="applyAIPrompt('Zero deposit PG stays')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-lime-50 hover:bg-lime-100 text-lime-700 border border-lime-200 transition tap-effect">✨ Zero Deposit PGs</button>
                        <button type="button" onclick="applyAIPrompt('Attached balcony and private washroom PG')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 transition tap-effect">✨ Balcony &amp; Bath</button>
                        <button type="button" onclick="applyAIPrompt('PG with gym and fitness center')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 transition tap-effect">✨ Gym &amp; Fitness</button>
                        <button type="button" onclick="applyAIPrompt('Top rated 4.5 star stays')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-200 transition tap-effect">✨ Top Rated 4.5+</button>
                        <button type="button" onclick="applyAIPrompt('Knowledge Park Greater Noida student PG')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 transition tap-effect">✨ Gr. Noida Knowledge Park</button>
                        <button type="button" onclick="applyAIPrompt('Working professionals co-living')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-50 hover:bg-slate-200 text-slate-700 border border-slate-200 transition tap-effect">✨ Working Professionals</button>
                        <button type="button" onclick="applyAIPrompt('Student budget stay with food')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 transition tap-effect">✨ Student Budget &amp; Food</button>
                        <button type="button" onclick="applyAIPrompt('Stays with 3 meals breakfast lunch dinner')" class="whitespace-nowrap px-2.5 py-1 rounded-lg text-[10px] font-bold bg-fuchsia-50 hover:bg-fuchsia-100 text-fuchsia-700 border border-fuchsia-200 transition tap-effect">✨ 3-Times Food Included</button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <div class="flex gap-2">
                        <h2 class="text-base font-extrabold text-slate-900" id="listHeader">Nearby PGs</h2>
                        <p class="text-xs text-slate-500 mt-0.5"><span id="pgCountBadge" class="font-bold">0</span> properties found</p>
                    </div>
                </div>
                <!-- Open & Versatile Filter Pills -->
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <button onclick="filterPGs('all', this)" class="chip-active whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all">All Stays</button>
                    <button onclick="filterPGs('boys', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-male mr-1 text-blue-500"></i> Boys</button>
                    <button onclick="filterPGs('girls', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-female mr-1 text-pink-500"></i> Girls</button>
                    <button onclick="filterPGs('co-ed', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-users mr-1 text-purple-500"></i> Co-Ed / Unisex</button>
                    <button onclick="filterPGs('ac', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-snowflake mr-1 text-cyan-500"></i> AC</button>
                    <button onclick="filterPGs('wifi', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-wifi mr-1 text-indigo-500"></i> WiFi</button>
                    <button onclick="filterPGs('food', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-utensils mr-1 text-emerald-500"></i> Food</button>
                    <button onclick="filterPGs('gym', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-dumbbell mr-1 text-orange-500"></i> Gym</button>
                    <button onclick="filterPGs('single', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-door-closed mr-1 text-teal-500"></i> Single Room</button>
                    <button onclick="filterPGs('bath', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-bath mr-1 text-sky-500"></i> Attached Bath</button>
                    <button onclick="filterPGs('metro', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-train-subway mr-1 text-amber-500"></i> Near Metro</button>
                    <button onclick="filterPGs('under8k', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-tag mr-1 text-green-500"></i> Under ₹8K</button>
                    <button onclick="filterPGs('luxury', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-crown mr-1 text-yellow-500"></i> Luxury</button>
                    <button onclick="filterPGs('top_rated', this)" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-star mr-1 text-amber-400"></i> Top Rated 4.5+</button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto no-scrollbar px-4 py-4 space-y-3 bg-gray-50/50" id="pgListContainer"></div>
        </aside>

        <!-- Map Area -->
        <main class="flex-1 relative bg-gray-100 w-full h-full" id="mapContainer">
            <div id="map" class="w-full h-full absolute inset-0"></div>
            
            <!-- DESKTOP Floating Controls -->
            <div class="hidden md:flex absolute top-4 left-4 z-[400] flex-col gap-3">
                <button onclick="toggleDesktopSidebar()" id="toggleSidebarBtn" class="fab-btn active" title="Toggle List">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button onclick="toggleLayers()" id="layersBtn" class="fab-btn" title="Satellite View">
                    <i class="fas fa-layer-group"></i>
                </button>
            </div>

            <!-- MOBILE Floating Controls -->
            <div class="md:hidden absolute top-4 right-4 z-[400] flex flex-col gap-3">
                <button onclick="toggleLayers()" id="layersBtnMobile" class="fab-btn" title="Satellite View">
                    <i class="fas fa-layer-group"></i>
                </button>
            </div>

            <!-- MOBILE Toggle Button -->
            <div class="md:hidden absolute bottom-6 left-1/2 -translate-x-1/2 z-[600]">
                <button onclick="toggleMobileSidebar()" class="bg-slate-900 text-white px-5 py-3 rounded-full shadow-2xl font-bold text-sm flex items-center gap-2 tap-effect" id="mobileToggleBtn">
                    <i class="fas fa-chevron-up" id="mobileToggleIcon"></i>
                    <span id="mobileToggleText">Show List</span>
                </button>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

    <script>
        const indiaCenter = [20.5937, 78.9629];
        let userLocation = null;
        let userAddress = "Fetching location...";
        let routingControl = null;
        let currentLayer = 'standard';
        let activeFilter = 'all';
        let rawDatabaseProperties = @json($properties ?? []);
        let allCitiesList = @json($cities ?? []);
        let currentPGList = [];
        let markerMap = {};
        let activeAreaHighlightLayer = null;
        let radiusHighlightCircle = null;
        let cachedIndiaGeoData = null;
        let desktopSidebarVisible = true;
        let mobileSidebarOpen = false;
        let currentSearchRadiusKm = 10;

        // Haversine formula for real distance calculation
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return (R * c).toFixed(1);
        }

        function initMap() {
            map = L.map('map', { zoomControl: false }).setView(indiaCenter, 5);
            L.control.zoom({ position: 'topright' }).addTo(map);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap &copy; CARTO',
                subdomains: 'abcd'
            }).addTo(map);

            window.markersLayer = L.layerGroup().addTo(map);
        }

        function toggleLayers() {
            const btn = document.getElementById('layersBtn');
            const btnMobile = document.getElementById('layersBtnMobile');
            
            if (currentLayer === 'standard') {
                map.eachLayer((layer) => { if (layer._url && layer._url.includes('cartocdn')) map.removeLayer(layer); });
                L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri', maxZoom: 18 }).addTo(map);
                currentLayer = 'satellite';
                btn?.classList.add('active');
                btnMobile?.classList.add('active');
            } else {
                map.eachLayer((layer) => { if (layer._url && layer._url.includes('arcgisonline')) map.removeLayer(layer); });
                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap &copy; CARTO', subdomains: 'abcd' }).addTo(map);
                currentLayer = 'standard';
                btn?.classList.remove('active');
                btnMobile?.classList.remove('active');
            }
        }

        function clearAllHighlights() {
            if (activeAreaHighlightLayer) { map.removeLayer(activeAreaHighlightLayer); activeAreaHighlightLayer = null; }
            if (radiusHighlightCircle) { map.removeLayer(radiusHighlightCircle); radiusHighlightCircle = null; }
        }

        function highlightUserRadius(lat, lng, radiusKm) {
            clearAllHighlights();
            radiusHighlightCircle = L.circle([lat, lng], {
                radius: radiusKm * 1000,
                color: '#1a1a7f',
                weight: 3,
                opacity: 0.9,
                fillColor: '#1a1a7f',
                fillOpacity: 0.15
            }).addTo(map);
            map.fitBounds(radiusHighlightCircle.getBounds(), { padding: [30, 30] });
        }

        async function focusIndiaMap() {
            try {
                if (!cachedIndiaGeoData) {
                    const response = await fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries/IND.geo.json');
                    cachedIndiaGeoData = await response.json();
                }
                clearAllHighlights();
                activeAreaHighlightLayer = L.geoJSON(cachedIndiaGeoData, {
                    style: { color: '#10b981', weight: 3, opacity: 0.9, fillColor: '#10b981', fillOpacity: 0.25 }
                }).addTo(map);
                map.fitBounds(activeAreaHighlightLayer.getBounds(), { padding: [20, 20] });
            } catch (err) {
                clearAllHighlights();
                map.flyTo(indiaCenter, 5, { duration: 1.5 });
            }
        }

        // ---------------- AI SMART SEARCH NLP ENGINE ----------------
        function parseAIQuery(query) {
            const q = query.toLowerCase().trim();
            const result = {
                rawQuery: query,
                city: null,
                gender: null,
                maxPrice: null,
                amenities: [],
                keywords: []
            };

            // 1. Detect City / Locality
            const cityAliases = [
                { key: 'noida', name: 'Noida', aliases: ['noida', 'sector 62', 'sec 62', 'sector 18', 'electronic city noida'], coords: [28.6280, 77.3649] },
                { key: 'greater noida', name: 'Greater Noida', aliases: ['greater noida', 'gr noida', 'knowledge park', 'pari chowk'], coords: [28.4744, 77.5030] },
                { key: 'bangalore', name: 'Bangalore', aliases: ['bangalore', 'bengaluru', 'koramangala', 'indiranagar', 'hsr layout', 'hsr', 'whitefield', 'bellandur', 'marathahalli', 'electronic city bangalore', 'electronic city'], coords: [12.9716, 77.5946] },
                { key: 'delhi', name: 'New Delhi', aliases: ['delhi', 'new delhi', 'lajpat nagar', 'south ex', 'connaught place', 'cp', 'saket', 'north campus', 'hauz khas'], coords: [28.6139, 77.2090] },
                { key: 'gurgaon', name: 'Gurugram', aliases: ['gurgaon', 'gurugram', 'cyber city', 'dlf', 'sector 29'], coords: [28.4595, 77.0266] },
                { key: 'mumbai', name: 'Mumbai', aliases: ['mumbai', 'bandra', 'andheri', 'powai', 'thane', 'navi mumbai'], coords: [19.0760, 72.8777] },
                { key: 'pune', name: 'Pune', aliases: ['pune', 'hinjewadi', 'viman nagar', 'kothrud', 'wakad', 'baner'], coords: [18.5204, 73.8567] },
                { key: 'hyderabad', name: 'Hyderabad', aliases: ['hyderabad', 'hitec city', 'gachibowli', 'madhapur', 'kondapur'], coords: [17.3850, 78.4867] }
            ];

            for (const c of cityAliases) {
                if (c.aliases.some(alias => q.includes(alias))) {
                    result.city = c.name;
                    result.cityCoords = c.coords;
                    break;
                }
            }

            // Also check against dynamic allCitiesList
            if (!result.city && allCitiesList && allCitiesList.length > 0) {
                const matched = allCitiesList.find(c => q.includes(c.name.toLowerCase()));
                if (matched) {
                    result.city = matched.name;
                    result.cityCoords = [matched.latitude ? parseFloat(matched.latitude) : 28.6280, matched.longitude ? parseFloat(matched.longitude) : 77.3649];
                }
            }

            // 2. Detect Gender (Boys / Girls / Unisex)
            if (/\b(boys?|boy|male|men|gents?|ladko?|mens?)\b/i.test(q)) {
                result.gender = 'boys';
            } else if (/\b(girls?|girl|female|women|ladies|ladkiyo?|womens?)\b/i.test(q)) {
                result.gender = 'girls';
            } else if (/\b(unisex|co-ed|coliving|co living|couples?|mixed|anyone)\b/i.test(q)) {
                result.gender = 'co-ed';
            }

            // 3. Detect Budget / Max Price
            const priceMatch = q.match(/(?:under|below|less than|within|budget|max|upto|se kam|k andar|andar|<=|<)\s*(?:rs\.?|inr|₹)?\s*(\d+)(?:k)?/i)
                            || q.match(/(?:rs\.?|inr|₹)\s*(\d+)(?:k)?/i)
                            || q.match(/(\d+)\s*(?:k)\b/i);
            if (priceMatch) {
                let val = parseInt(priceMatch[1], 10);
                if (priceMatch[0].toLowerCase().includes('k') && val < 100) {
                    val = val * 1000;
                } else if (val < 100) {
                    val = val * 1000; // e.g. 'under 8' -> 8000
                }
                result.maxPrice = val;
            }

            // 4. Detect Amenities
            if (/\b(ac|air conditioner|airconditioned|cooling)\b/i.test(q)) result.amenities.push('AC');
            if (/\b(wifi|wi-fi|internet|broadband)\b/i.test(q)) result.amenities.push('WiFi');
            if (/\b(food|meals?|khana|mess|breakfast|dinner)\b/i.test(q)) result.amenities.push('Food');
            if (/\b(gym|fitness|workout)\b/i.test(q)) result.amenities.push('Gym');
            if (/\b(attached bath|bathroom|washroom|private bath)\b/i.test(q)) result.amenities.push('Attached Bath');
            if (/\b(balcony|terrace|open air)\b/i.test(q)) result.amenities.push('Balcony');
            if (/\b(security|cctv|safe|biometric|guard)\b/i.test(q)) result.amenities.push('Security');

            return result;
        }

        let aiSearchActive = false;
        let aiParsedResult = null;

        async function highlightAreaBoundary(locationName) {
            if (!locationName) return null;
            try {
                clearAllHighlights();

                const cleanName = locationName.trim();
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&polygon_geojson=1&countrycodes=in&q=${encodeURIComponent(cleanName)}`, {
                    headers: { 'Accept-Language': 'en' }
                });
                const results = await response.json();
                
                if (results && results.length > 0) {
                    const best = results[0];
                    const lat = parseFloat(best.lat);
                    const lon = parseFloat(best.lon);

                    if (best.geojson && (best.geojson.type === 'Polygon' || best.geojson.type === 'MultiPolygon')) {
                        activeAreaHighlightLayer = L.geoJSON(best.geojson, {
                            style: {
                                color: '#10b981',       // Light Green / Emerald Border
                                weight: 3,
                                opacity: 0.95,
                                fillColor: '#34d399',   // Light Green Fill
                                fillOpacity: 0.25,
                                dashArray: '6, 6'
                            }
                        }).addTo(map);

                        map.fitBounds(activeAreaHighlightLayer.getBounds(), { padding: [30, 30], maxZoom: 14 });
                        return { lat, lon, name: best.display_name, hasPolygon: true };
                    } else if (best.boundingbox && best.boundingbox.length === 4) {
                        const south = parseFloat(best.boundingbox[0]);
                        const north = parseFloat(best.boundingbox[1]);
                        const west = parseFloat(best.boundingbox[2]);
                        const east = parseFloat(best.boundingbox[3]);
                        
                        const bounds = [[south, west], [north, east]];
                        activeAreaHighlightLayer = L.rectangle(bounds, {
                            color: '#10b981',
                            weight: 3,
                            opacity: 0.95,
                            fillColor: '#34d399',
                            fillOpacity: 0.22,
                            dashArray: '6, 6'
                        }).addTo(map);

                        map.fitBounds(bounds, { padding: [30, 30], maxZoom: 14 });
                        return { lat, lon, name: best.display_name, hasPolygon: true };
                    } else {
                        radiusHighlightCircle = L.circle([lat, lon], {
                            radius: 8000,
                            color: '#10b981',
                            weight: 3,
                            opacity: 0.95,
                            fillColor: '#34d399',
                            fillOpacity: 0.22
                        }).addTo(map);
                        map.fitBounds(radiusHighlightCircle.getBounds(), { padding: [30, 30], maxZoom: 14 });
                        return { lat, lon, name: best.display_name, hasPolygon: false };
                    }
                }
            } catch (err) {
                console.warn("Boundary highlight error:", err);
            }
            return null;
        }

        async function performAISmartSearch() {
            const input = document.getElementById('mapSearchInput');
            const query = input.value.trim();
            if (!query) {
                resetAISearch();
                return;
            }

            const ai = parseAIQuery(query);
            aiParsedResult = ai;
            aiSearchActive = true;

            // Determine candidate location for boundary highlight (e.g. Orai, Jhansi, Kanpur, Noida, Bangalore, etc.)
            let searchLocation = ai.city;
            if (!searchLocation) {
                searchLocation = query.replace(/\b(boys?|girls?|unisex|pg|stays?|rooms?|hostels?|under|below|\d+k?|with|ac|wifi|food|gym|near|in|me|chahiye|ke|andar|city|district|state)\b/gi, '').trim();
                if (!searchLocation) searchLocation = query.trim();
            }

            // Highlight the searched City, State, or District boundary in Light Green
            let boundaryGeo = null;
            if (searchLocation) {
                boundaryGeo = await highlightAreaBoundary(searchLocation);
            }

            // Render AI status badges
            const banner = document.getElementById('aiMatchStatusBanner');
            const badgesContainer = document.getElementById('aiParsedBadges');
            banner.classList.remove('hidden');
            badgesContainer.innerHTML = '';

            const displayCity = ai.city || (boundaryGeo ? searchLocation : null);
            if (displayCity) {
                badgesContainer.innerHTML += `<span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-md text-[10px] flex items-center gap-1"><i class="fas fa-map-marked-alt text-emerald-600"></i> ${displayCity} (Highlighted)</span>`;
            }
            if (ai.gender) {
                const gLabel = ai.gender === 'boys' ? 'Boys Only' : (ai.gender === 'girls' ? 'Girls Only' : 'Co-ed / Unisex');
                badgesContainer.innerHTML += `<span class="bg-indigo-100 text-indigo-800 font-bold px-2 py-0.5 rounded-md text-[10px] flex items-center gap-1"><i class="fas fa-user-check text-indigo-600"></i> ${gLabel}</span>`;
            }
            if (ai.maxPrice) {
                badgesContainer.innerHTML += `<span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-md text-[10px] flex items-center gap-1"><i class="fas fa-tag text-emerald-600"></i> Under ₹${ai.maxPrice.toLocaleString('en-IN')}</span>`;
            }
            ai.amenities.forEach(am => {
                badgesContainer.innerHTML += `<span class="bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded-md text-[10px] flex items-center gap-1"><i class="fas fa-check text-blue-600"></i> ${am}</span>`;
            });

            if (!displayCity && !ai.gender && !ai.maxPrice && ai.amenities.length === 0) {
                badgesContainer.innerHTML += `<span class="bg-gray-100 text-gray-800 font-bold px-2 py-0.5 rounded-md text-[10px]"><i class="fas fa-search text-gray-500"></i> Keyword: "${query}"</span>`;
            }

            // Filter raw properties based on AI parsed filters
            let filtered = rawDatabaseProperties.filter(pg => {
                // City check
                if (ai.city) {
                    const pgCity = (pg.city || '').toLowerCase();
                    const pgLoc = (pg.location_text || '').toLowerCase();
                    const pgAddr = (pg.address || '').toLowerCase();
                    const targetCity = ai.city.toLowerCase();
                    if (!pgCity.includes(targetCity) && !pgLoc.includes(targetCity) && !pgAddr.includes(targetCity)) {
                        return false;
                    }
                }

                // Gender check
                if (ai.gender) {
                    const pgGender = (pg.gender || '').toLowerCase();
                    if (ai.gender === 'boys' && pgGender !== 'boys') return false;
                    if (ai.gender === 'girls' && pgGender !== 'girls') return false;
                    if (ai.gender === 'co-ed' && (pgGender !== 'co-ed' && pgGender !== 'unisex')) return false;
                }

                // Price check
                if (ai.maxPrice && pg.raw_price) {
                    if (pg.raw_price > ai.maxPrice) return false;
                }

                // Amenities check
                if (ai.amenities.length > 0 && pg.tags) {
                    const hasAnyAmenity = ai.amenities.some(am => 
                        pg.tags.some(t => t.toLowerCase().includes(am.toLowerCase()))
                    );
                    if (!hasAnyAmenity && pg.tags.length > 0) {
                        // Allow soft match
                    }
                }

                // If query has specific name terms
                if (!ai.city && !ai.gender && !ai.maxPrice) {
                    const qLower = query.toLowerCase();
                    const nameMatch = pg.name.toLowerCase().includes(qLower);
                    const addrMatch = (pg.address || '').toLowerCase().includes(qLower);
                    const tagMatch = pg.tags && pg.tags.some(t => t.toLowerCase().includes(qLower));
                    if (!nameMatch && !addrMatch && !tagMatch) return false;
                }

                return true;
            });

            // If no strict matches found, fallback gracefully
            if (filtered.length === 0 && ai.city) {
                filtered = rawDatabaseProperties.filter(pg => {
                    const targetCity = ai.city.toLowerCase();
                    return (pg.city || '').toLowerCase().includes(targetCity) || (pg.location_text || '').toLowerCase().includes(targetCity);
                });
            }
            if (filtered.length === 0) {
                filtered = rawDatabaseProperties;
            }

            // Move map to city center if boundary was not already fitted
            if (!boundaryGeo) {
                if (ai.cityCoords) {
                    map.flyTo(ai.cityCoords, 13, { duration: 1.2 });
                } else if (filtered.length > 0) {
                    map.flyTo([filtered[0].lat, filtered[0].lng], 14, { duration: 1.2 });
                }
            }

            // Calculate distance relative to boundary center if boundary was found
            const refCenterLat = boundaryGeo ? boundaryGeo.lat : (userLocation ? userLocation[0] : 28.6280);
            const refCenterLng = boundaryGeo ? boundaryGeo.lon : (userLocation ? userLocation[1] : 77.3649);

            renderCustomPGList(filtered, `AI Matches: "${query}"`, refCenterLat, refCenterLng);

            // On mobile, auto open list
            if (window.innerWidth < 768 && !mobileSidebarOpen) {
                setTimeout(() => toggleMobileSidebar(), 500);
            }
        }

        function renderCustomPGList(list, regionTitle, customLat, customLng) {
            markersLayer.clearLayers();
            markerMap = {};
            renderUserMarker();

            const refLat = customLat !== undefined ? customLat : (userLocation ? userLocation[0] : 28.6280);
            const refLng = customLng !== undefined ? customLng : (userLocation ? userLocation[1] : 77.3649);

            currentPGList = list.map(p => ({
                ...p,
                distance: calculateDistance(refLat, refLng, p.lat, p.lng)
            }));

            currentPGList.sort((a, b) => parseFloat(a.distance) - parseFloat(b.distance));

            currentPGList.forEach(pg => {
                const priceBadgeIcon = L.divIcon({
                    className: 'price-badge-container',
                    html: `<div id="badge-${pg.id}" class="custom-price-badge"><span>${pg.price}<span style="font-size:10px; font-weight:600; opacity:0.7">/mo</span></span></div>`,
                    iconSize: [0, 0]
                });

                const pgMarker = L.marker([pg.lat, pg.lng], { icon: priceBadgeIcon });
                
                pgMarker.bindPopup(`
                    <div class="font-sans">
                        <img src="${pg.image}" class="w-full h-32 object-cover rounded-t-xl" alt="${pg.name}">
                        <div style="padding: 14px;">
                            <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:6px;">
                                <h4 style="color:#0f172a; font-weight:800; font-size:15px; margin:0;">${pg.name}</h4>
                                <div style="background:#fef3c7; color:#92400e; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:700;">
                                    <i class="fas fa-star" style="color:#fbbf24;"></i> ${pg.rating}
                                </div>
                            </div>
                            <div style="color:#1a1a7f; font-size:12px; font-weight:700; margin-bottom:8px;">
                                <i class="fas fa-location-dot" style="color:#ef4444;"></i> ${pg.distance} km away &middot; <span style="color:#64748b; font-weight:500;">${pg.location_text || pg.city}</span>
                            </div>
                            <div style="color:#4bb59d; font-weight:800; font-size:18px; margin-bottom:12px;">₹${pg.price}<span style="font-size:12px; color:#94a3b8; font-weight:500;">/mo</span></div>
                            
                            <div style="display:flex; gap:8px;">
                                <button class="btn-directions" style="flex:1; padding:10px; border-radius:10px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s;" onclick="handleRouteClick(${pg.lat}, ${pg.lng})">
                                    <i class="fas fa-diamond-turn-right"></i> Directions
                                </button>
                                <a href="${pg.detail_url}" class="btn-view-property" style="flex:1; padding:10px; border-radius:10px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; text-decoration:none; color:white; text-align:center;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                `);

                markersLayer.addLayer(pgMarker);
                markerMap[pg.id] = pgMarker;
            });

            renderSidebarCards();
        }

        function generatePGsForRegion(centerLat, centerLng, regionName) {
            renderCustomPGList(rawDatabaseProperties, regionName || "Nearby");
        }

        function viewPropertyInSidebar(pgId, lat, lng) {
            map.flyTo([lat, lng], 15, { duration: 1 });
            const card = document.getElementById(`card-pg-${pgId}`);
            if (card) {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                document.querySelectorAll('.pg-card').forEach(c => c.classList.remove('active-card'));
                card.classList.add('active-card');
            }
            if (markerMap[pgId]) {
                markerMap[pgId].openPopup();
            }
            if (window.innerWidth < 768 && mobileSidebarOpen) {
                setTimeout(() => toggleMobileSidebar(), 300);
            }
        }

        function renderSidebarCards() {
            const container = document.getElementById('pgListContainer');
            if (!container) return;

            const filtered = currentPGList.filter(pg => {
                if (activeFilter === 'all') return true;
                const f = activeFilter.toLowerCase();
                
                if (f === 'boys') {
                    return (pg.gender && pg.gender.toLowerCase() === 'boys');
                }
                if (f === 'girls') {
                    return (pg.gender && pg.gender.toLowerCase() === 'girls');
                }
                if (f === 'co-ed' || f === 'unisex' || f === 'coliving') {
                    return (pg.gender && (pg.gender.toLowerCase() === 'co-ed' || pg.gender.toLowerCase() === 'unisex' || pg.gender.toLowerCase() === 'coliving'));
                }
                if (f === 'ac') {
                    return (pg.tags && pg.tags.some(t => t.toLowerCase().includes('ac')));
                }
                if (f === 'wifi') {
                    return (pg.tags && pg.tags.some(t => t.toLowerCase().includes('wifi')));
                }
                if (f === 'food') {
                    return (pg.tags && pg.tags.some(t => t.toLowerCase().includes('food') || t.toLowerCase().includes('meal') || t.toLowerCase().includes('mess')));
                }
                if (f === 'gym') {
                    return (pg.tags && pg.tags.some(t => t.toLowerCase().includes('gym') || t.toLowerCase().includes('fitness')));
                }
                if (f === 'single') {
                    return (pg.tags && pg.tags.some(t => t.toLowerCase().includes('single') || t.toLowerCase().includes('private'))) ||
                           (pg.name && pg.name.toLowerCase().includes('single'));
                }
                if (f === 'bath') {
                    return (pg.tags && pg.tags.some(t => t.toLowerCase().includes('bath') || t.toLowerCase().includes('attached')));
                }
                if (f === 'metro') {
                    return (pg.tags && pg.tags.some(t => t.toLowerCase().includes('metro'))) ||
                           (pg.location_text && pg.location_text.toLowerCase().includes('metro')) ||
                           (pg.address && pg.address.toLowerCase().includes('metro'));
                }
                if (f === 'under8k') {
                    return (pg.raw_price && pg.raw_price <= 8000);
                }
                if (f === 'luxury') {
                    return (pg.raw_price && pg.raw_price >= 12000) || (pg.tags && pg.tags.some(t => t.toLowerCase().includes('luxury')));
                }
                if (f === 'top_rated') {
                    return (parseFloat(pg.rating) >= 4.5);
                }

                // General tag/name match
                const tagMatch = pg.tags && pg.tags.some(t => t.toLowerCase().includes(f));
                const nameMatch = pg.name && pg.name.toLowerCase().includes(f);
                return tagMatch || nameMatch;
            });

            const headerEl = document.getElementById('listHeader');
            if (headerEl) headerEl.textContent = filtered.length > 0 ? 'Nearby PGs' : 'No PGs Found';
            
            const badgeEl = document.getElementById('pgCountBadge');
            if (badgeEl) badgeEl.textContent = filtered.length;

            container.innerHTML = '';

            if (filtered.length === 0) {
                container.innerHTML = `<div class="flex flex-col items-center justify-center py-12 text-center"><div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3"><i class="fas fa-search text-gray-400 text-2xl"></i></div><h3 class="font-bold text-slate-900 mb-1">No PGs found</h3><p class="text-xs text-slate-500">Try changing filters or range radius</p></div>`;
                return;
            }

            filtered.forEach(pg => {
                const card = document.createElement('div');
                card.className = 'pg-card bg-white border border-gray-100 rounded-2xl p-3 cursor-pointer flex gap-3';
                card.id = `card-pg-${pg.id}`;
                card.innerHTML = `
                    <div class="relative flex-shrink-0">
                        <img src="${pg.image}" class="w-24 h-24 rounded-xl object-cover bg-gray-100" alt="${pg.name}">
                        <div class="absolute top-1.5 left-1.5 bg-white/90 backdrop-blur rounded-md px-1.5 py-0.5 flex items-center gap-1 shadow-xs">
                            <i class="fas fa-star text-yellow-400 text-[9px]"></i>
                            <span class="text-[10px] font-bold text-slate-900">${pg.rating}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm leading-tight mb-1 truncate">${pg.name}</h3>
                            <div class="flex items-center gap-1 text-xs font-bold text-primary mb-1.5">
                                <i class="fas fa-location-dot text-red-500 text-[10px]"></i>
                                <span>${pg.distance} km away &middot; <span class="font-normal text-gray-500 text-[10px]">${pg.city}</span></span>
                            </div>
                            <div class="flex flex-wrap gap-1 mb-2">
                                ${(pg.tags || []).map(t => `<span class="text-[9px] bg-gray-50 text-slate-600 px-1.5 py-0.5 rounded border border-gray-100">${t}</span>`).join('')}
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <div><span class="text-base font-extrabold text-slate-900">₹${pg.price}</span><span class="text-[10px] text-slate-500 font-medium">/mo</span></div>
                            <div class="flex items-center gap-1.5">
                                <button class="bg-slate-900 hover:bg-brand text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg transition-all flex items-center gap-1 tap-effect" onclick="event.stopPropagation(); handleRouteClick(${pg.lat}, ${pg.lng})">
                                    <i class="fas fa-route"></i> Route
                                </button>
                                <a href="${pg.detail_url}" class="bg-brand hover:bg-brand-dark text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg transition-all flex items-center gap-1 tap-effect no-underline">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                card.onmouseenter = () => { const badge = document.getElementById(`badge-${pg.id}`); if (badge) badge.classList.add('active-badge'); };
                card.onmouseleave = () => { const badge = document.getElementById(`badge-${pg.id}`); if (badge) badge.classList.remove('active-badge'); };
                card.onclick = () => { 
                    map.flyTo([pg.lat, pg.lng], 15, { duration: 1 }); 
                    if (markerMap[pg.id]) markerMap[pg.id].openPopup();
                    if (window.innerWidth < 768 && mobileSidebarOpen) toggleMobileSidebar(); 
                };
                container.appendChild(card);
            });
        }

        function filterPGs(tag, btn) {
            activeFilter = tag;
            document.querySelectorAll('[onclick^="filterPGs"]').forEach(chip => chip.classList.remove('chip-active'));
            if (btn) btn.classList.add('chip-active');
            renderSidebarCards();
        }

        function handleRouteClick(pgLat, pgLng) {
            if (window.innerWidth < 768 && mobileSidebarOpen) toggleMobileSidebar();
            setTimeout(() => drawRouteToPG(pgLat, pgLng), 300);
        }

        function drawRouteToPG(pgLat, pgLng) {
            if (!userLocation) { alert("Please allow location access to calculate routes."); goToMyLocation(); return; }
            if (routingControl) map.removeControl(routingControl);
            routingControl = L.Routing.control({
                waypoints: [L.latLng(userLocation[0], userLocation[1]), L.latLng(pgLat, pgLng)],
                routeWhileDragging: false,
                lineOptions: { styles: [{ color: '#1a1a7f', weight: 6, opacity: 0.85 }] },
                addWaypoints: false, show: false, createMarker: function() { return null; }
            }).addTo(map);
            map.fitBounds(L.latLngBounds([userLocation, [pgLat, pgLng]]), { padding: [80, 80] });
        }

        function applyAIPrompt(promptText) {
            const input = document.getElementById('mapSearchInput');
            if (input) {
                input.value = promptText;
                document.getElementById('clearSearchBtn')?.classList.remove('hidden');
                performAISmartSearch();
            }
        }

        function resetAISearch() {
            aiSearchActive = false;
            aiParsedResult = null;
            clearAllHighlights();
            const input = document.getElementById('mapSearchInput');
            if (input) input.value = '';
            document.getElementById('clearSearchBtn')?.classList.add('hidden');
            document.getElementById('aiMatchStatusBanner')?.classList.add('hidden');
            activeFilter = 'all';
            document.querySelectorAll('[onclick^="filterPGs"]').forEach((chip, i) => {
                if (i === 0) chip.classList.add('chip-active');
                else chip.classList.remove('chip-active');
            });
            const refLat = userLocation ? userLocation[0] : 28.6280;
            const refLng = userLocation ? userLocation[1] : 77.3649;
            generatePGsForRegion(refLat, refLng, "Nearby");
        }

        function handleSearchInput(val) {
            const clearBtn = document.getElementById('clearSearchBtn');
            if (clearBtn) {
                if (val.trim()) clearBtn.classList.remove('hidden');
                else clearBtn.classList.add('hidden');
            }
        }

        function clearAISearchInput() {
            resetAISearch();
        }

        function handleSearchKeyPress(e) {
            if (e.key === 'Enter') performAISmartSearch();
        }

        function updateRangeRadius(newKm) {
            currentSearchRadiusKm = parseInt(newKm, 10);
            document.getElementById('radiusLabel').innerText = currentSearchRadiusKm;
            document.getElementById('radiusRange').value = currentSearchRadiusKm;

            if (userLocation) {
                highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                generatePGsForRegion(userLocation[0], userLocation[1], "My Radius Area");
            } else if (currentPGList.length > 0) {
                const first = currentPGList[0];
                highlightUserRadius(first.lat, first.lng, currentSearchRadiusKm);
                generatePGsForRegion(first.lat, first.lng, "Area");
            }
        }

        // Rotating AI Search Placeholder Prompts
        const aiSearchPlaceholders = [
            "✨ Ask AI: 'Boys PG under 8k in Noida'...",
            "✨ Ask AI: 'Girls PG with AC in Bangalore'...",
            "✨ Ask AI: 'Luxury co-living near Electronic City'...",
            "✨ Ask AI: 'Single room budget stay in Delhi'...",
            "✨ Ask AI: 'Stays with Food and High Speed WiFi'...",
            "✨ Ask AI: 'Co-ed stay with Gym under 12k'..."
        ];
        let placeholderIdx = 0;
        function cycleAIPlaceholders() {
            const input = document.getElementById('mapSearchInput');
            if (!input || document.activeElement === input || input.value.trim() !== '') return;
            placeholderIdx = (placeholderIdx + 1) % aiSearchPlaceholders.length;
            input.setAttribute('placeholder', aiSearchPlaceholders[placeholderIdx]);
        }
        setInterval(cycleAIPlaceholders, 3200);

        const profileHomeData = {
            tag: 'HOME',
            line1: 'Flat 402, B-Block, Tulip Heights',
            line2: 'Sector 62, Near Electronic City Metro, Noida, 201309',
            fullAddress: 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309',
            lat: 28.6280,
            lng: 77.3649
        };

        async function fetchUserAddress(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
                    headers: { 'Accept-Language': 'en' }
                });
                const data = await response.json();
                if (data && data.display_name) {
                    userAddress = data.display_name;
                } else {
                    userAddress = `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
                }
            } catch (err) { 
                userAddress = `Near Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
            }

            try {
                localStorage.setItem('user_cached_lat', lat);
                localStorage.setItem('user_cached_lng', lng);
                localStorage.setItem('user_cached_address', userAddress);
            } catch(e) {}

            const addressEl = document.getElementById('userAddressDisplay');
            if (addressEl) addressEl.textContent = userAddress;

            const badge = document.getElementById('gpsLiveBadge');
            if (badge) badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> GPS ACTIVE`;

            if (window.userMarker) {
                window.userMarker.setPopupContent(`
                    <div style="font-family:sans-serif; padding:4px;">
                        <b style='color:#ef4444; font-size:13px;'><i class='fas fa-street-view mr-1'></i> You Are Here</b>
                        <div style='font-size:11px; color:#475569; line-height:1.4; margin-top:4px;'>${userAddress}</div>
                        <div style='margin-top:6px; font-size:10px; color:#10b981; font-weight:700; display:flex; align-items:center; gap:4px;'>
                            <i class='fas fa-circle text-[8px] animate-pulse'></i> Live GPS Accurate Location
                        </div>
                    </div>
                `);
            }
        }

        function useProfileHomeAddress() {
            userLocation = [profileHomeData.lat, profileHomeData.lng];
            userAddress = profileHomeData.fullAddress;

            // Check if user has saved custom address in localStorage from profile
            try {
                const saved = localStorage.getItem('staynest_default_address');
                if (saved) {
                    const parsed = JSON.parse(saved);
                    if (parsed.line1 && parsed.line2) {
                        userAddress = `${parsed.line1}, ${parsed.line2}`;
                    }
                }
            } catch(e) {}

            const addrEl = document.getElementById('userAddressDisplay');
            if (addrEl) addrEl.textContent = userAddress;

            const typeLabel = document.getElementById('addressTypeLabel');
            if (typeLabel) typeLabel.textContent = 'Verified Home Address';

            const badge = document.getElementById('gpsLiveBadge');
            if (badge) badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> VERIFIED HOME`;

            const btnHome = document.getElementById('btnUseHome');
            const btnGps = document.getElementById('btnUseGps');
            if (btnHome) {
                btnHome.className = 'text-[10px] font-extrabold text-brand bg-white border border-brand/40 px-2 py-0.5 rounded-md shadow-xs';
            }
            if (btnGps) {
                btnGps.className = 'text-[10px] font-bold text-gray-600 bg-white border border-gray-200 px-2 py-0.5 rounded-md hover:bg-gray-100 transition shadow-xs';
            }

            try {
                localStorage.setItem('user_cached_lat', profileHomeData.lat);
                localStorage.setItem('user_cached_lng', profileHomeData.lng);
                localStorage.setItem('user_cached_address', userAddress);
                localStorage.setItem('staynest_user_lat', profileHomeData.lat);
                localStorage.setItem('staynest_user_lng', profileHomeData.lng);
                localStorage.setItem('staynest_user_address_locked', 'true');
            } catch(e) {}

            renderUserMarker();
            highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
            generatePGsForRegion(userLocation[0], userLocation[1], "Sector 62, Noida");
            map.flyTo(userLocation, 15, { duration: 1.2 });
            if (window.userMarker) {
                setTimeout(() => window.userMarker.openPopup(), 1200);
            }
        }

        function useDeviceGPS() {
            const badge = document.getElementById('gpsLiveBadge');
            if (badge) badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> LOCATING...`;

            const typeLabel = document.getElementById('addressTypeLabel');
            if (typeLabel) typeLabel.textContent = 'Device GPS Live Location';

            const btnHome = document.getElementById('btnUseHome');
            const btnGps = document.getElementById('btnUseGps');
            if (btnGps) {
                btnGps.className = 'text-[10px] font-extrabold text-blue-600 bg-white border border-blue-400 px-2 py-0.5 rounded-md shadow-xs';
            }
            if (btnHome) {
                btnHome.className = 'text-[10px] font-bold text-gray-600 bg-white border border-gray-200 px-2 py-0.5 rounded-md hover:bg-gray-100 transition shadow-xs';
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async (pos) => {
                        userLocation = [pos.coords.latitude, pos.coords.longitude];
                        await fetchUserAddress(pos.coords.latitude, pos.coords.longitude);
                        if (badge) badge.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> GPS ACTIVE`;
                        renderUserMarker();
                        highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                        generatePGsForRegion(userLocation[0], userLocation[1], "Your Live Location");
                        map.flyTo(userLocation, 15, { duration: 1.2 });
                        if (window.userMarker) {
                            setTimeout(() => window.userMarker.openPopup(), 1200);
                        }
                    },
                    (error) => {
                        alert("Could not retrieve device GPS. Switching to Verified Profile Home Address.");
                        useProfileHomeAddress();
                    },
                    { timeout: 8000, enableHighAccuracy: true, maximumAge: 0 }
                );
            } else {
                useProfileHomeAddress();
            }
        }

        function initUserLocation() {
            // Default to verified profile home location (Sector 62, Noida)
            useProfileHomeAddress();
        }

        function goToMyLocation() {
            if (userLocation) {
                map.flyTo(userLocation, 15, { duration: 1.2 });
                if (window.userMarker) {
                    setTimeout(() => window.userMarker.openPopup(), 1200);
                }
            } else {
                useProfileHomeAddress();
            }
        }

        function renderUserMarker() {
            if (!userLocation) return;

            const popupContent = `
                <div style="font-family:sans-serif; padding:4px;">
                    <b style='color:#ef4444; font-size:13px;'><i class='fas fa-street-view mr-1'></i> You Are Here</b>
                    <div style='font-size:11px; color:#475569; line-height:1.4; margin-top:4px;'>${userAddress}</div>
                    <div style='margin-top:6px; font-size:10px; color:#10b981; font-weight:700; display:flex; align-items:center; gap:4px;'>
                        <i class='fas fa-circle text-[8px] animate-pulse'></i> Live GPS Accurate Location
                    </div>
                </div>
            `;

            if (window.userMarker) {
                window.userMarker.setLatLng(userLocation);
                window.userMarker.setPopupContent(popupContent);
            } else {
                const userIcon = L.divIcon({
                    className: 'user-marker-container',
                    html: '<div class="user-marker-icon"></div>',
                    iconSize: [22, 22],
                    iconAnchor: [11, 11]
                });
                window.userMarker = L.marker(userLocation, { icon: userIcon, zIndexOffset: 1000 })
                    .bindPopup(popupContent)
                    .addTo(map);
            }
        }

        function toggleDesktopSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            desktopSidebarVisible = !desktopSidebarVisible;
            if (desktopSidebarVisible) { sidebar.style.width = ''; toggleBtn.classList.add('active'); }
            else { sidebar.style.width = '0px'; toggleBtn.classList.remove('active'); }
            setTimeout(() => { map.invalidateSize(); }, 350);
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const btnText = document.getElementById('mobileToggleText');
            const btnIcon = document.getElementById('mobileToggleIcon');
            mobileSidebarOpen = !mobileSidebarOpen;
            if (mobileSidebarOpen) {
                sidebar.style.transform = 'translateY(0)';
                btnText.textContent = 'Hide List';
                btnIcon.classList.remove('fa-chevron-up');
                btnIcon.classList.add('fa-chevron-down');
            } else {
                sidebar.style.transform = 'translateY(85%)';
                btnText.textContent = 'Show List';
                btnIcon.classList.remove('fa-chevron-down');
                btnIcon.classList.add('fa-chevron-up');
            }
        }

        let lastWidth = window.innerWidth;
        window.addEventListener('resize', () => {
            const currentWidth = window.innerWidth;
            const wasMobile = lastWidth < 768;
            const isMobile = currentWidth < 768;
            if (wasMobile !== isMobile) {
                const sidebar = document.getElementById('sidebar');
                if (isMobile) { sidebar.style.width = ''; if (!mobileSidebarOpen) sidebar.style.transform = 'translateY(85%)'; }
                else { sidebar.style.transform = ''; setTimeout(() => { map.invalidateSize(); }, 350); }
            }
            lastWidth = currentWidth;
        });

        window.onload = () => {
            initMap();
            if (window.innerWidth < 768) {
                document.getElementById('sidebar').style.transform = 'translateY(85%)';
                mobileSidebarOpen = false;
            }
            initUserLocation();
        };
    </script>
@endpush
