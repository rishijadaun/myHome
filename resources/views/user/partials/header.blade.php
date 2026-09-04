@php
    $headerProfileUrl = route('user.login');
    $headerProfileLabel = 'Login';
    $headerProfileIcon = 'fa-user';
    $headerRoleBadge = null;
    $isTenantLoggedIn = false;

    if (Auth::check()) {
        $authUser = Auth::user();
        $userRole = $authUser->getCachedRoleSlug();
        if ($userRole === 'admin') {
            $headerProfileUrl = route('admin.dashboard');
            $headerProfileLabel = 'Admin Dashboard';
            $headerProfileIcon = 'fa-shield-halved';
            $headerRoleBadge = 'ADMIN';
        } elseif ($userRole === 'broker') {
            $headerProfileUrl = route('broker.dashboard');
            $headerProfileLabel = 'Broker Dashboard';
            $headerProfileIcon = 'fa-gauge-high';
            $headerRoleBadge = 'BROKER';
        } else {
            $headerProfileUrl = route('user.profile');
            $rawProfileName = $authUser->profile?->first_name ?? $authUser->name ?? 'My Profile';
            $headerProfileLabel = mb_strlen($rawProfileName) > 20 ? mb_substr($rawProfileName, 0, 20) . '..' : $rawProfileName;
            $headerProfileIcon = 'fa-user-check';
            $headerRoleBadge = 'TENANT';
            $isTenantLoggedIn = true;
        }
    }
@endphp

<!-- Mobile App Header -->
<header class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100" id="mobileMainHeader">

    <!-- Mobile Top Smart App Download Banner (Dismissible for 30 Days / 1 Month) -->
    <div id="topAppDownloadBanner" class="hidden bg-gradient-to-r from-slate-900 via-gray-900 to-slate-950 text-white px-3.5 py-2 border-b border-white/10 items-center justify-between gap-2 transition-all duration-300 relative z-50">
        <div class="flex items-center gap-2.5 min-w-0">
            <button type="button" onclick="dismissTopAppBanner(event)" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 active:scale-90 text-gray-300 hover:text-white flex items-center justify-center text-[11px] flex-shrink-0 transition-transform cursor-pointer" title="Close for 1 month" aria-label="Close download banner">
                <i class="fas fa-times"></i>
            </button>
            <img src="{{ asset('images/favicon.png') }}" alt="StayNest App" class="w-8 h-8 rounded-xl shadow-xs flex-shrink-0 object-cover border border-white/15">
            <div class="min-w-0">
                <div class="flex items-center gap-1.5 leading-tight">
                    <span class="text-xs font-black text-white tracking-tight truncate">StayNest App</span>
                    <span class="text-[9px] bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-1 py-0.2 rounded font-bold">FREE</span>
                </div>
                <p class="text-[10px] text-gray-300 truncate">⭐ 4.8 • Fast PG & Flat Discovery</p>
            </div>
        </div>
        <button type="button" onclick="installPwaApp()" class="flex-shrink-0 bg-brand hover:bg-brand-dark active:scale-95 text-white text-[11px] font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md shadow-brand/30 transition-transform flex items-center gap-1 cursor-pointer">
            <i class="fas fa-arrow-down-to-bracket text-[10px]"></i> GET
        </button>
    </div>

    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <a href="{{ route('user.location') }}" class="flex-1 min-w-0 flex items-center gap-2" id="headerMobileLocationLink" title="Select or view current location">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="text-lg font-bold text-gray-900">StayNest</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </div>
                    <p class="text-xs text-gray-500 truncate" id="headerMobileLocationText">
                        <i class="fas fa-map-marker-alt text-brand text-[10px] mr-1"></i><span id="headerUserLiveLocationText">Detecting location...</span>
                    </p>
                </div>
            </a>

            <div class="flex items-center gap-2 ml-3">
                <a href="{{ route('user.saved') }}" class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center tap-effect shadow-xs" title="Saved">
                    <i class="fas fa-heart text-sm"></i>
                </a>
                @if($isTenantLoggedIn)
                <button type="button" 
                        id="mobRoommateChatBtn" 
                        onclick="handleGlobalChatClick()" 
                        class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center tap-effect shadow-xs relative cursor-pointer" 
                        title="Roommate Chat & Inquiries">
                    <i class="fab fa-whatsapp text-lg text-emerald-600"></i>
                    <span id="mobWaUnreadBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[8px] font-black px-1.5 py-0.2 rounded-full ring-2 ring-white">0</span>
                </button>
                @endif
                @auth
                    @php
                        $userActiveBkCount = \App\Models\Booking::where('user_id', auth()->id())->where('booking_status', '!=', 'cancelled')->where('broker_approval', '!=', 'rejected')->count();
                    @endphp
                    @if($userActiveBkCount > 0)
                        <a href="{{ route('user.bookings') }}" class="w-10 h-10 rounded-full bg-brand-light text-brand flex items-center justify-center tap-effect shadow-xs relative" title="My Bookings">
                            <i class="fas fa-calendar-check text-sm"></i>
                            <span class="absolute -top-1 -right-1 bg-brand text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-white">{{ $userActiveBkCount }}</span>
                        </a>
                    @endif
                @endauth
                
                <!-- Dynamic Auth Header Item for Mobile -->
                <div id="mobHeaderAuth">
                    @auth
                        <a href="{{ $headerProfileUrl }}" id="mobProfileLink" class="w-10 h-10 rounded-full bg-brand/10 border border-brand/30 flex items-center justify-center text-brand font-bold text-xs tap-effect" title="{{ $headerProfileLabel }}">
                            <i class="fas {{ $headerProfileIcon }} text-sm"></i>
                        </a>
                    @else
                        <a href="{{ route('user.login') }}" id="mobProfileLink" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 tap-effect" title="Login">
                            <i class="fas fa-user text-sm"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Desktop Header -->
<header class="hidden md:block bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center gap-12">
                <a href="{{ route('user.home') }}" class="flex items-center gap-2 cursor-pointer">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand/30">
                        <i class="fas fa-home"></i>
                    </div>
                    <span class="font-bold text-2xl text-gray-900 tracking-tight">Stay<span class="gradient-text">Nest</span></span>
                </a>

                <!-- Desktop Live Location Badge -->
                <a href="{{ route('user.location') }}" class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-50 hover:bg-brand-light border border-gray-200 hover:border-brand/40 transition text-xs text-gray-700 hover:text-brand max-w-[220px]" title="Change Location on Map">
                    <i class="fas fa-location-dot text-brand text-xs flex-shrink-0 animate-pulse"></i>
                    <span class="truncate font-semibold" id="deskUserLiveLocationText">Detecting location...</span>
                    <i class="fas fa-chevron-down text-[9px] text-gray-400 flex-shrink-0"></i>
                </a>
                <nav class="flex space-x-8">
                    <a href="{{ route('user.home') }}" class="{{ request()->routeIs('user.home') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Home</a>
                    <a href="{{ route('user.search') }}" class="{{ request()->routeIs('user.search') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Find Property</a>
                    <!-- <a href="{{ route('user.list-property') }}" class="{{ request()->routeIs('user.list-property') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">List Property</a> -->
                    <!-- <a href="{{ route('user.pricing') }}" class="{{ request()->routeIs('user.pricing') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Pricing</a> -->
                    <a href="{{ route('user.about') }}" class="{{ request()->routeIs('user.about*') || request()->is('about*') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">About Us</a>
                    <a href="{{ route('user.contact') }}" class="{{ request()->routeIs('user.contact*') || request()->is('contact*') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Contact Us</a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('user.search') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition" title="Search Properties faster">
                    <i class="fas fa-search"></i>
                </a>
                <a href="{{ route('user.saved') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition" title="Saved Properties">
                    <i class="fas fa-heart text-red-500"></i>
                </a>
                @if($isTenantLoggedIn)
                <button type="button" 
                        id="deskRoommateChatBtn" 
                        onclick="handleGlobalChatClick()" 
                        class="w-11 h-11 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 flex items-center justify-center transition relative cursor-pointer group" 
                        title="Roommate Messages & Chat">
                    <i class="fab fa-whatsapp text-xl text-emerald-600 group-hover:scale-110 transition-transform"></i>
                    <span id="deskWaUnreadBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full ring-2 ring-white">0</span>
                </button>
                @endif
                @auth
                    @if($userActiveBkCount > 0)
                        <a href="{{ route('user.bookings') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative" title="My Bookings">
                            <i class="fas fa-calendar-check text-gray-600"></i>
                            <span class="absolute -top-1 -right-1 bg-brand text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-white">{{ $userActiveBkCount }}</span>
                        </a>
                    @endif
                @endauth

                <!-- Dynamic Auth Buttons in Desktop Header -->
                <div id="deskHeaderAuth" class="flex items-center gap-2">
                    @auth
                        <a href="{{ $headerProfileUrl }}" id="deskProfileLink" class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-brand-light text-gray-800 hover:text-brand transition text-sm font-semibold border border-transparent hover:border-brand/20">
                            <div class="w-7 h-7 rounded-full bg-brand text-white flex items-center justify-center text-xs shadow-xs">
                                <i class="fas {{ $headerProfileIcon }}"></i>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="leading-tight">{{ $headerProfileLabel }}</span>
                                @if($headerRoleBadge)
                                    <span class="text-[9px] font-extrabold text-brand-dark tracking-wider">{{ $headerRoleBadge }}</span>
                                @endif
                            </div>
                        </a>
                        <button type="button" onclick="performLogout()" class="px-3.5 py-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 border border-red-200 hover:border-red-600 rounded-xl font-bold transition text-xs flex items-center gap-1.5">
                            <i class="fas fa-right-from-bracket text-xs"></i>
                            <span>Logout</span>
                        </button>
                    @else
                        <div id="clientGuestState" class="flex items-center gap-2">
                            <a href="{{ route('user.login') }}" class="px-4 py-2 rounded-xl bg-gray-100/90 hover:bg-brand/10 text-gray-900 hover:text-brand font-bold transition tap-effect text-sm border border-gray-200 hover:border-brand/40 flex items-center gap-1.5 shadow-2xs">
                                <i class="fas fa-right-to-bracket text-xs text-brand"></i>
                                <span>Log In</span>
                            </a>
                        </div>
                        <div id="clientAuthState" class="hidden flex items-center gap-2">
                            <a href="{{ route('user.profile') }}" id="clientProfileLink" class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-brand-light text-gray-800 hover:text-brand transition text-sm font-semibold border border-transparent hover:border-brand/20">
                                <div class="w-7 h-7 rounded-full bg-brand text-white flex items-center justify-center text-xs shadow-xs">
                                    <i class="fas fa-user" id="clientProfileIcon"></i>
                                </div>
                                <div class="flex flex-col text-left">
                                    <span id="clientUserName" class="leading-tight">Profile</span>
                                    <span id="clientRoleBadge" class="text-[9px] font-extrabold text-brand-dark tracking-wider">TENANT</span>
                                </div>
                            </a>
                            <button type="button" onclick="performLogout()" class="px-3.5 py-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 border border-red-200 hover:border-red-600 rounded-xl font-bold transition text-xs flex items-center gap-1.5">
                                <i class="fas fa-right-from-bracket text-xs"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    @endauth
                </div>

                @php
                    $isTenantUser = Auth::check() && !Auth::user()->roles()->whereIn('slug', ['super_admin', 'admin', 'broker'])->exists();
                @endphp

                @if(!$isTenantUser)
                <a href="{{ route('user.list-property') }}" id="headerPostPropertyBtn" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-5 py-2.5 rounded-xl font-bold transition tap-effect text-sm flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-plus-circle text-xs"></i>
                    <span>Post Property Free</span>
                </a>
                @else
                <a href="{{ route('user.roommate.create') }}" id="headerPostRoommateBtn" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-4 py-2.5 rounded-xl font-bold transition tap-effect text-sm flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-door-open text-xs"></i>
                    <span>Find Flatmate</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</header>

<script>
    // Check Client-Side Token State on Page Load
    document.addEventListener('DOMContentLoaded', function() {
        @if(!Auth::check())
            // Server session is not authenticated (logged out / guest)
            // Clear any stale tokens/user profile from localStorage to prevent showing old user name
            localStorage.removeItem('staynest_token');
            localStorage.removeItem('staynest_user');
            
            const guestBox = document.getElementById('clientGuestState');
            const authBox = document.getElementById('clientAuthState');
            if (guestBox) guestBox.classList.remove('hidden');
            if (authBox) authBox.classList.add('hidden');
        @else
            // User is authenticated on the server
            const token = localStorage.getItem('staynest_token');
            const userStr = localStorage.getItem('staynest_user');
            
            if (token && userStr) {
                try {
                    const user = JSON.parse(userStr);
                    const nameLabel = document.getElementById('clientUserName');
                    const roleBadge = document.getElementById('clientRoleBadge');
                    const profileIcon = document.getElementById('clientProfileIcon');
                    const clientProfileLink = document.getElementById('clientProfileLink');
                    const mobProfileLink = document.getElementById('mobProfileLink');
                    const bottomNavProfileLink = document.getElementById('bottomNavProfileLink');
                    const postPropertyBtn = document.getElementById('headerPostPropertyBtn');
                    
                    let targetUrl = "{{ route('user.profile') }}";
                    let rawName = user.first_name || user.name || "My Profile";
                    let displayTitle = rawName;
                    let badgeText = "TENANT";
                    let iconClass = "fa-user-check";

                    const role = (user.role || '').toLowerCase();
                    if (role === 'admin' || role === 'super_admin') {
                        targetUrl = "{{ route('admin.dashboard') }}";
                        displayTitle = "Dashboard";
                        badgeText = "ADMIN";
                        iconClass = "fa-shield-halved";
                    } else if (role === 'broker') {
                        targetUrl = "{{ route('broker.dashboard') }}";
                        displayTitle = "Dashboard";
                        badgeText = "BROKER";
                        iconClass = "fa-gauge-high";
                    } else {
                        // Tenant login: Hide Post Property Free button
                        if (postPropertyBtn) {
                            postPropertyBtn.style.display = 'none';
                        }
                    }

                    if (displayTitle && displayTitle.length > 20) {
                        displayTitle = displayTitle.substring(0, 20) + '..';
                    }

                    if (clientProfileLink) clientProfileLink.href = targetUrl;
                    if (mobProfileLink) mobProfileLink.href = targetUrl;
                    if (bottomNavProfileLink) bottomNavProfileLink.href = targetUrl;

                    if (nameLabel) {
                        nameLabel.innerText = displayTitle;
                        nameLabel.title = rawName;
                    }
                    if (roleBadge) roleBadge.innerText = badgeText;
                    if (profileIcon) profileIcon.className = 'fas ' + iconClass;
                } catch(e) {}
            }
        @endif
    });

    async function performLogout() {
        const token = localStorage.getItem('staynest_token');
        if (token) {
            try {
                await fetch('/api/v1/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            } catch(e) {}
        }
        localStorage.removeItem('staynest_token');
        localStorage.removeItem('staynest_user');
        window.location.href = "{{ route('user.logout') }}";
    }

    // Top Smart App Download Banner Logic (30 Days / 1 Month Dismissible)
    function checkTopAppBanner() {
        const banner = document.getElementById('topAppDownloadBanner');
        if (!banner) return;
        
        const dismissedUntil = localStorage.getItem('staynest_top_app_banner_dismissed_until');
        if (dismissedUntil && Date.now() < parseInt(dismissedUntil, 10)) {
            banner.style.display = 'none';
            banner.classList.add('hidden');
            banner.classList.remove('flex');
            adjustMainPadding(false);
            return;
        }

        if (window.innerWidth < 768) {
            banner.style.display = 'flex';
            banner.classList.remove('hidden');
            banner.classList.add('flex');
            adjustMainPadding(true);
        } else {
            banner.style.display = 'none';
            banner.classList.add('hidden');
            banner.classList.remove('flex');
            adjustMainPadding(false);
        }
    }

    function dismissTopAppBanner(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const banner = document.getElementById('topAppDownloadBanner');
        if (banner) {
            banner.style.maxHeight = banner.offsetHeight + 'px';
            banner.style.transition = 'max-height 0.3s ease, opacity 0.3s ease, padding 0.3s ease';
            banner.style.overflow = 'hidden';
            banner.style.opacity = '0';
            banner.style.maxHeight = '0px';
            banner.style.paddingTop = '0px';
            banner.style.paddingBottom = '0px';
            setTimeout(() => {
                banner.classList.add('hidden');
                banner.classList.remove('flex');
                adjustMainPadding(false);
            }, 300);
        }
        // 30 Days (1 Month) expiration timestamp in milliseconds
        const thirtyDaysMs = 30 * 24 * 60 * 60 * 1000;
        localStorage.setItem('staynest_top_app_banner_dismissed_until', (Date.now() + thirtyDaysMs).toString());
    }

    function adjustMainPadding(isBannerVisible) {
        const main = document.querySelector('main');
        if (main && !window.location.pathname.includes('/login')) {
            if (window.innerWidth < 768) {
                main.style.paddingTop = isBannerVisible ? '112px' : '65px';
            } else {
                main.style.paddingTop = '0px';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', checkTopAppBanner);
    window.addEventListener('resize', checkTopAppBanner);

    // =========================================================================
    // Dynamic Live User Geolocation & Address Detection for Header
    // =========================================================================
    (function() {
        function updateHeaderLocationDisplay(locationName) {
            if (!locationName) return;
            const mobLabel = document.getElementById('headerUserLiveLocationText');
            const deskLabel = document.getElementById('deskUserLiveLocationText');
            if (mobLabel) mobLabel.textContent = locationName;
            if (deskLabel) deskLabel.textContent = locationName;
        }

        // 1. Initial Fast Render from Cached Location or Saved Address
        const savedAddrStr = localStorage.getItem('staynest_default_address');
        const cachedLocName = localStorage.getItem('staynest_user_location_name');
        const isLocked = localStorage.getItem('staynest_user_address_locked') === 'true';

        if (cachedLocName) {
            updateHeaderLocationDisplay(cachedLocName);
        } else if (savedAddrStr) {
            try {
                const parsed = JSON.parse(savedAddrStr);
                const line = parsed.line2 || parsed.line1 || '';
                if (line) {
                    updateHeaderLocationDisplay(line);
                }
            } catch(e) {}
        } else {
            updateHeaderLocationDisplay('Sector 62, Noida, Delhi NCR');
        }

        // 2. Reverse Geocoding with Nominatim & BigDataCloud fallback
        async function reverseGeocodeLiveCoords(lat, lng) {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 4000);
                
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=16&addressdetails=1`, {
                    signal: controller.signal,
                    headers: { 'Accept': 'application/json' }
                });
                clearTimeout(timeoutId);

                if (response.ok) {
                    const data = await response.json();
                    const addr = data.address || {};
                    const locality = addr.suburb || addr.neighbourhood || addr.residential || addr.road || addr.village || addr.town || addr.city_district || '';
                    const city = addr.city || addr.state_district || addr.county || '';
                    const state = addr.state || '';

                    let formatted = '';
                    if (locality && city) {
                        formatted = `${locality}, ${city}`;
                    } else if (city) {
                        formatted = state ? `${city}, ${state}` : city;
                    } else if (locality) {
                        formatted = locality;
                    } else if (data.display_name) {
                        const parts = data.display_name.split(',');
                        formatted = parts.slice(0, 2).join(',').trim();
                    }

                    if (formatted) {
                        localStorage.setItem('staynest_user_location_name', formatted);
                        localStorage.setItem('staynest_user_lat', lat);
                        localStorage.setItem('staynest_user_lng', lng);
                        updateHeaderLocationDisplay(formatted);
                        return;
                    }
                }
            } catch(e) {}

            // Fallback to BigDataCloud
            try {
                const bdcRes = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`);
                if (bdcRes.ok) {
                    const bdcData = await bdcRes.json();
                    const loc = bdcData.locality || bdcData.city || '';
                    const sub = bdcData.principalSubdivision || '';
                    const bdcFormatted = loc ? (sub ? `${loc}, ${sub}` : loc) : '';
                    if (bdcFormatted) {
                        localStorage.setItem('staynest_user_location_name', bdcFormatted);
                        localStorage.setItem('staynest_user_lat', lat);
                        localStorage.setItem('staynest_user_lng', lng);
                        updateHeaderLocationDisplay(bdcFormatted);
                    }
                }
            } catch(e) {}
        }

        // 3. Live Browser GPS Geolocation Auto-Detection
        if (navigator.geolocation && !isLocked) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    if (position && position.coords) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        reverseGeocodeLiveCoords(lat, lng);
                    }
                },
                function(err) {
                    // Keep existing cached location or default on denial
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 300000 }
            );
        }
    })();
</script>
