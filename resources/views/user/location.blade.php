@extends('user.layouts.map')

@section('title', 'Map View - StayNest')

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
            background: #ef4444;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.3);
            width: 18px;
            height: 18px;
            animation: pulse-ring 2s ease-out infinite;
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            100% { box-shadow: 0 0 0 12px rgba(239, 68, 68, 0); }
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
    <!-- Map Toolbar -->
    <div class="bg-white border-b border-gray-200 z-10 px-4 py-3 flex flex-col md:flex-row justify-between items-center gap-3 w-full shrink-0 shadow-sm pt-5 md:pt-4">
        
        <!-- Search Bar -->
        <div class="relative w-full md:max-w-md flex items-center gap-2">
            <a href="{{ route('user.home') }}" class="flex-shrink-0">
                <button class="w-10 h-10 rounded-lg bg-orange-500 hover:bg-orange-600 flex items-center justify-center transition tap-effect">
                    <i class="fas fa-arrow-left text-white"></i>
                </button>
            </a>
            <i class="fas fa-search absolute left-14 top-3 text-gray-400"></i>
            <input type="text" id="mapSearchInput" placeholder="Search cities (Noida, Delhi)..." 
                class="w-full bg-gray-50 border border-gray-200 focus:bg-white focus:border-brand rounded-xl py-2 pl-11 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 transition-all"
                onkeypress="handleSearchKeyPress(event)">
            <button onclick="performGlobalSearch()" class="absolute right-1 top-1 bg-brand text-white w-8 h-8 rounded-lg flex items-center justify-center tap-effect shadow-sm">
                <i class="fas fa-arrow-right text-xs"></i>
            </button>
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

            <div class="px-5 pb-3 flex-shrink-0 bg-white border-b border-gray-100">
                <!-- User Current Address -->
                <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 rounded-xl p-3 mb-3 mt-2 md:mt-0">
                    <div class="flex items-start gap-2">
                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-location-dot text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[10px] font-bold text-red-600 uppercase tracking-wider mb-0.5">Your Current Address</div>
                            <div id="userAddressDisplay" class="text-xs font-semibold text-slate-700 leading-tight line-clamp-2">Fetching location...</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900" id="listHeader">Nearby PGs</h2>
                        <p class="text-xs text-slate-500 mt-0.5"><span id="pgCountBadge">0</span> properties found</p>
                    </div>
                </div>
                
                <!-- Filter Pills -->
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <button onclick="filterPGs('all', this)" class="chip-active whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all">All</button>
                    <button onclick="filterPGs('Boys', this)" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-male mr-1"></i> Boys</button>
                    <button onclick="filterPGs('Girls', this)" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-female mr-1"></i> Girls</button>
                    <button onclick="filterPGs('AC', this)" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-snowflake mr-1"></i> AC</button>
                    <button onclick="filterPGs('WiFi', this)" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-bold border border-gray-200 bg-white text-slate-700 tap-effect transition-all"><i class="fas fa-wifi mr-1"></i> WiFi</button>
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

        async function performGlobalSearch() {
            const input = document.getElementById('mapSearchInput');
            const query = input.value.trim();
            if (!query) return;

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&polygon_geojson=1&q=${encodeURIComponent(query)}`);
                const results = await response.json();

                if (results && results.length > 0) {
                    const result = results[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);

                    if (result.geojson) {
                        clearAllHighlights();
                        activeAreaHighlightLayer = L.geoJSON(result.geojson, {
                            style: { color: '#10b981', weight: 3, opacity: 0.9, fillColor: '#10b981', fillOpacity: 0.25 }
                        }).addTo(map);
                        map.fitBounds(activeAreaHighlightLayer.getBounds(), { padding: [20, 20] });
                    } else {
                        clearAllHighlights();
                        map.flyTo([lat, lon], 13, { duration: 1.5 });
                    }

                    generatePGsForRegion(lat, lon, result.display_name);
                } else {
                    alert("Location not found! Try another city name.");
                }
            } catch (e) {
                alert("Error searching location.");
            }
        }

        function handleSearchKeyPress(e) {
            if (e.key === 'Enter') performGlobalSearch();
        }

        function updateRangeRadius(newKm) {
            currentSearchRadiusKm = parseInt(newKm, 10);
            document.getElementById('radiusLabel').innerText = currentSearchRadiusKm;
            document.getElementById('radiusRange').value = currentSearchRadiusKm;

            if (userLocation) {
                highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                generatePGsForRegion(userLocation[0], userLocation[1], "My Radius Area");
            }
        }

        function generatePGsForRegion(centerLat, centerLng, regionName) {
            markersLayer.clearLayers();
            markerMap = {};
            renderUserMarker();

            const prefixes = ["Comfort Stays", "Urban Living", "Elite Residency", "Green Haven", "Horizon Heights", "Skyline Stay", "Metro Nest", "Royal PG"];
            const tagOptions = [["Boys", "AC", "WiFi"], ["Girls", "Attached Bath", "Food"], ["Unisex", "Gym", "AC"], ["Boys", "Food", "Security"]];
            
            currentPGList = [];

            for (let i = 1; i <= 8; i++) {
                const offsetLat = (Math.random() - 0.5) * (currentSearchRadiusKm * 0.008);
                const offsetLng = (Math.random() - 0.5) * (currentSearchRadiusKm * 0.008);
                const priceVal = (Math.floor(Math.random() * 6) + 6) * 1000;
                const pgLat = centerLat + offsetLat;
                const pgLng = centerLng + offsetLng;
                
                const distance = userLocation ? calculateDistance(userLocation[0], userLocation[1], pgLat, pgLng) : (Math.random() * currentSearchRadiusKm).toFixed(1);

                const pg = {
                    id: i,
                    name: `${prefixes[(i - 1) % prefixes.length]}`,
                    lat: pgLat,
                    lng: pgLng,
                    distance: distance,
                    price: `${priceVal.toLocaleString('en-IN')}`,
                    rating: (4 + Math.random() * 0.9).toFixed(1),
                    tags: tagOptions[i % tagOptions.length]
                };
                currentPGList.push(pg);

                const priceBadgeIcon = L.divIcon({
                    className: 'price-badge-container',
                    html: `<div id="badge-${pg.id}" class="custom-price-badge"><span>${pg.price}<span style="font-size:10px; font-weight:600; opacity:0.7">/mo</span></span></div>`,
                    iconSize: [0, 0]
                });

                const pgMarker = L.marker([pg.lat, pg.lng], { icon: priceBadgeIcon });
                
                pgMarker.bindPopup(`
                    <div class="font-sans">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" class="w-full h-32 object-cover">
                        <div style="padding: 14px;">
                            <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:6px;">
                                <h4 style="color:#0f172a; font-weight:800; font-size:15px; margin:0;">${pg.name}</h4>
                                <div style="background:#fef3c7; color:#92400e; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:700;">
                                    <i class="fas fa-star" style="color:#fbbf24;"></i> ${pg.rating}
                                </div>
                            </div>
                            <div style="color:#1a1a7f; font-size:12px; font-weight:700; margin-bottom:8px;">
                                <i class="fas fa-location-dot" style="color:#ef4444;"></i> ${pg.distance} km away
                            </div>
                            <div style="color:#4bb59d; font-weight:800; font-size:18px; margin-bottom:12px;">₹${pg.price}<span style="font-size:12px; color:#94a3b8; font-weight:500;">/mo</span></div>
                            
                            <div style="display:flex; gap:8px;">
                                <button class="btn-directions" style="flex:1; padding:10px; border-radius:10px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s;" onclick="handleRouteClick(${pg.lat}, ${pg.lng})">
                                    <i class="fas fa-diamond-turn-right"></i> Directions
                                </button>
                                <button class="btn-view-property" style="flex:1; padding:10px; border-radius:10px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s;" onclick="viewPropertyInSidebar(${pg.id}, ${pg.lat}, ${pg.lng})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </div>
                        </div>
                    </div>
                `);

                markersLayer.addLayer(pgMarker);
                markerMap[pg.id] = pgMarker;
            }
            renderSidebarCards();
        }

        function viewPropertyInSidebar(pgId, lat, lng) {
            map.flyTo([lat, lng], 15, { duration: 1 });
            const card = document.getElementById(`card-pg-${pgId}`);
            if (card) {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                document.querySelectorAll('.pg-card').forEach(c => c.classList.remove('active-card'));
                card.classList.add('active-card');
            }
            if (window.innerWidth < 768 && mobileSidebarOpen) {
                setTimeout(() => toggleMobileSidebar(), 300);
            }
        }

        function renderSidebarCards() {
            const container = document.getElementById('pgListContainer');
            const filtered = currentPGList.filter(pg => {
                if (activeFilter === 'all') return true;
                return pg.tags.includes(activeFilter);
            });

            document.getElementById('listHeader').textContent = filtered.length > 0 ? 'Nearby PGs' : 'No PGs Found';
            document.getElementById('pgCountBadge').textContent = filtered.length;
            container.innerHTML = '';

            if (filtered.length === 0) {
                container.innerHTML = `<div class="flex flex-col items-center justify-center py-12 text-center"><div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3"><i class="fas fa-search text-gray-400 text-2xl"></i></div><h3 class="font-bold text-slate-900 mb-1">No PGs found</h3><p class="text-xs text-slate-500">Try changing filters</p></div>`;
                return;
            }

            filtered.forEach(pg => {
                const card = document.createElement('div');
                card.className = 'pg-card bg-white border border-gray-100 rounded-2xl p-3 cursor-pointer flex gap-3';
                card.id = `card-pg-${pg.id}`;
                card.innerHTML = `
                    <div class="relative flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-24 h-24 rounded-xl object-cover bg-gray-100">
                        <div class="absolute top-1.5 left-1.5 bg-white/90 backdrop-blur rounded-md px-1.5 py-0.5 flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400 text-[9px]"></i>
                            <span class="text-[10px] font-bold text-slate-900">${pg.rating}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm leading-tight mb-1 truncate">${pg.name}</h3>
                            <div class="flex items-center gap-1 text-xs font-bold text-primary mb-1.5">
                                <i class="fas fa-location-dot text-red-500 text-[10px]"></i>
                                <span>${pg.distance} km away</span>
                            </div>
                            <div class="flex flex-wrap gap-1 mb-2">
                                ${pg.tags.map(t => `<span class="text-[9px] bg-gray-50 text-slate-600 px-1.5 py-0.5 rounded border border-gray-100">${t}</span>`).join('')}
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div><span class="text-base font-extrabold text-slate-900">₹${pg.price}</span><span class="text-[10px] text-slate-500 font-medium">/mo</span></div>
                            <button class="bg-slate-900 hover:bg-brand text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 tap-effect" onclick="event.stopPropagation(); handleRouteClick(${pg.lat}, ${pg.lng})">
                                <i class="fas fa-route"></i> Route
                            </button>
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
            btn.classList.add('chip-active');
            renderSidebarCards();
        }

        function handleRouteClick(pgLat, pgLng) {
            if (window.innerWidth < 768 && mobileSidebarOpen) toggleMobileSidebar();
            setTimeout(() => drawRouteToPG(pgLat, pgLng), 300);
        }

        function drawRouteToPG(pgLat, pgLng) {
            if (!userLocation) { alert("Please allow location access."); goToMyLocation(); return; }
            if (routingControl) map.removeControl(routingControl);
            routingControl = L.Routing.control({
                waypoints: [L.latLng(userLocation[0], userLocation[1]), L.latLng(pgLat, pgLng)],
                routeWhileDragging: false,
                lineOptions: { styles: [{ color: '#1a1a7f', weight: 6, opacity: 0.85 }] },
                addWaypoints: false, show: false, createMarker: function() { return null; }
            }).addTo(map);
            map.fitBounds(L.latLngBounds([userLocation, [pgLat, pgLng]]), { padding: [80, 80] });
        }

        async function fetchUserAddress(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                const data = await response.json();
                userAddress = data.display_name || `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
                document.getElementById('userAddressDisplay').textContent = userAddress;
            } catch (err) { 
                userAddress = `Near Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
                document.getElementById('userAddressDisplay').textContent = userAddress;
            }
        }

        function initUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async (pos) => {
                        userLocation = [pos.coords.latitude, pos.coords.longitude];
                        await fetchUserAddress(pos.coords.latitude, pos.coords.longitude);
                        highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                        generatePGsForRegion(userLocation[0], userLocation[1], "Your Area");
                    },
                    (error) => {
                        console.warn("Geolocation error:", error);
                        userLocation = [28.5355, 77.3910];
                        userAddress = "Noida Sector 62, Uttar Pradesh, India";
                        document.getElementById('userAddressDisplay').textContent = userAddress;
                        highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                        generatePGsForRegion(userLocation[0], userLocation[1], "Default Area");
                    },
                    { timeout: 10000, enableHighAccuracy: false }
                );
            } else {
                userLocation = [28.5355, 77.3910];
                userAddress = "Noida Sector 62, Uttar Pradesh, India";
                document.getElementById('userAddressDisplay').textContent = userAddress;
                highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                generatePGsForRegion(userLocation[0], userLocation[1], "Default Area");
            }
        }

        function goToMyLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async (pos) => {
                        userLocation = [pos.coords.latitude, pos.coords.longitude];
                        await fetchUserAddress(pos.coords.latitude, pos.coords.longitude);
                        highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                        generatePGsForRegion(userLocation[0], userLocation[1], "Your Live Location");
                        map.flyTo(userLocation, 15, { duration: 1.2 });
                    },
                    (error) => {
                        console.warn("Location access denied or unavailable");
                        highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm);
                    },
                    { timeout: 10000, enableHighAccuracy: false }
                );
            } else { 
                highlightUserRadius(userLocation[0], userLocation[1], currentSearchRadiusKm); 
            }
        }

        function renderUserMarker() {
            if (window.innerWidth < 768) return;
            if (userLocation) {
                const userIcon = L.divIcon({ className: 'user-marker-container', html: '<div class="user-marker-icon"></div>', iconSize: [18, 18], iconAnchor: [9, 9] });
                L.marker(userLocation, { icon: userIcon }).bindPopup(`<b style='color:#ef4444'><i class='fas fa-street-view'></i> You Are Here</b><br><span style='font-size:11px;color:#64748b;'>${userAddress}</span>`).addTo(window.markersLayer);
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
