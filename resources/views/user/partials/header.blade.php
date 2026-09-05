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
            <div onclick="openGlobalLocationModal()" class="flex-1 min-w-0 flex items-center gap-2 cursor-pointer tap-effect" id="headerMobileLocationLink" title="Select or view current location">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="text-lg font-bold text-gray-900">StayNest</span>
                        <i class="fas fa-chevron-down text-xs text-brand"></i>
                    </div>
                    <p class="text-xs text-gray-600 truncate font-semibold" id="headerMobileLocationText">
                        <i class="fas fa-map-marker-alt text-brand text-[10px] mr-1"></i><span id="headerUserLiveLocationText">Detecting location...</span>
                    </p>
                </div>
            </div>

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
                <button type="button" onclick="openGlobalLocationModal()" class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-50 hover:bg-brand-light border border-gray-200 hover:border-brand/40 transition text-xs text-gray-700 hover:text-brand max-w-[220px] text-left cursor-pointer" title="Select or Change Location">
                    <i class="fas fa-location-dot text-brand text-xs flex-shrink-0 animate-pulse"></i>
                    <span class="truncate font-semibold" id="deskUserLiveLocationText">Detecting location...</span>
                    <i class="fas fa-chevron-down text-[9px] text-gray-400 flex-shrink-0"></i>
                </button>
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
</script>

<!-- ========================================================================= -->
<!-- GLOBAL LOCATION SELECTOR MODAL (Swiggy/Zepto style) -->
<!-- ========================================================================= -->
<div id="globalLocationModal" class="fixed inset-0 z-[100] hidden items-end md:items-center justify-center p-0 md:p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" aria-modal="true" role="dialog">
    <div class="bg-white w-full md:max-w-md rounded-t-3xl md:rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] md:max-h-[85vh] animate-in fade-in slide-in-from-bottom duration-300">
        
        <!-- Modal Header -->
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center">
                    <i class="fas fa-location-dot text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm md:text-base leading-tight">Choose your location</h3>
                    <p class="text-[11px] text-gray-500">Find stays near your preferred area</p>
                </div>
            </div>
            <button type="button" onclick="closeGlobalLocationModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition cursor-pointer" aria-label="Close Modal">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <!-- Search Bar -->
        <div class="p-4 border-b border-gray-100 bg-white">
            <div class="relative flex items-center">
                <i class="fas fa-search absolute left-3.5 text-gray-400 text-xs"></i>
                <input type="text" id="globalLocSearchInput" oninput="handleGlobalLocSearchInput(this.value)" placeholder="Search locality, sector, city (e.g. Noida Sector 62)" class="w-full pl-9 pr-8 py-2.5 bg-gray-50 hover:bg-gray-100/80 focus:bg-white border border-gray-200 focus:border-brand rounded-xl text-xs md:text-sm font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand/20 transition">
                <button type="button" id="globalLocClearBtn" onclick="clearGlobalLocSearch()" class="hidden absolute right-3 text-gray-400 hover:text-gray-600 cursor-pointer">
                    <i class="fas fa-times-circle text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="overflow-y-auto p-4 space-y-4 flex-1 overscroll-contain" id="globalLocModalBody">
            
            <!-- Live Search Results Container (Hidden by default, shown when searching) -->
            <div id="globalLocSearchResultsContainer" class="hidden space-y-2">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1 flex items-center justify-between">
                    <span>Search Results</span>
                    <span id="globalLocSearchSpinner" class="hidden text-brand text-xs"><i class="fas fa-spinner fa-spin"></i></span>
                </div>
                <div id="globalLocSearchResults" class="space-y-1.5">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Use Live GPS Button -->
            <button type="button" id="globalLocGpsBtn" onclick="detectLiveGpsFromModal()" class="w-full flex items-center gap-3.5 p-3 rounded-2xl bg-gradient-to-r from-brand/5 to-brand/10 hover:from-brand/15 hover:to-brand/20 border border-brand/20 transition group text-left cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-brand/20 group-hover:scale-105 transition-transform" id="globalLocGpsIconWrapper">
                    <i class="fas fa-crosshairs text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-xs md:text-sm text-gray-900 group-hover:text-brand transition flex items-center gap-1.5">
                        <span id="globalLocGpsBtnText">Use Current GPS Location</span>
                        <span class="text-[9px] bg-emerald-100 text-emerald-700 font-bold px-1.5 py-0.2 rounded">LIVE</span>
                    </div>
                    <p class="text-[11px] text-gray-500 truncate" id="globalLocGpsSubText">Auto-detect using device location</p>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400 group-hover:text-brand group-hover:translate-x-0.5 transition"></i>
            </button>

            <!-- Saved Addresses Section (if user has any saved) -->
            <div id="globalLocSavedAddrSection" class="hidden space-y-2">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Saved Addresses</div>
                <div id="globalLocSavedAddrList" class="space-y-1.5">
                    <!-- Populated dynamically -->
                </div>
            </div>

            <!-- Popular Localities & Cities -->
            <div class="space-y-2.5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Popular Localities & Hubs</div>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="selectGlobalLocation('Sector 62, Noida', 28.6280, 77.3649, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Sector 62, Noida
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Sector 18, Noida', 28.5708, 77.3260, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Sector 18, Noida
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Pari Chowk, Greater Noida', 28.4744, 77.5030, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Pari Chowk, Gr Noida
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Connaught Place, New Delhi', 28.6315, 77.2167, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Connaught Place, Delhi
                    </button>
                    <button type="button" onclick="selectGlobalLocation('South Extension, New Delhi', 28.5742, 77.2242, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 South Ext, Delhi
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Cyber City, Gurugram', 28.4906, 77.0898, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Cyber City, Gurugram
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Indirapuram, Ghaziabad', 28.6385, 77.3712, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Indirapuram, Ghaziabad
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Koramangala, Bengaluru', 12.9352, 77.6245, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Koramangala, Bengaluru
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Indiranagar, Bengaluru', 12.9784, 77.6408, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Indiranagar, Bengaluru
                    </button>
                    <button type="button" onclick="selectGlobalLocation('HSR Layout, Bengaluru', 12.9121, 77.6446, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 HSR Layout, Bengaluru
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Powai, Mumbai', 19.1176, 72.9060, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Powai, Mumbai
                    </button>
                    <button type="button" onclick="selectGlobalLocation('Hinjawadi, Pune', 18.5913, 73.7389, true)" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-brand hover:text-white border border-gray-200 text-xs font-semibold text-gray-700 transition cursor-pointer">
                        📍 Hinjawadi, Pune
                    </button>
                </div>
            </div>

            <!-- Interactive Map Link -->
            <div class="pt-2 border-t border-gray-100">
                <a href="{{ route('user.location') }}" onclick="closeGlobalLocationModal()" class="w-full py-2.5 px-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-200 flex items-center justify-between text-xs font-bold text-gray-700 hover:text-brand transition">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-brand"></i>
                        <span>Open Interactive Live Map</span>
                    </span>
                    <i class="fas fa-arrow-right text-[10px] text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // =========================================================================
    // Global Location Modal & Geolocation Engine
    // =========================================================================
    function updateHeaderLocationDisplay(locationName) {
        if (!locationName) return;
        const mobLabel = document.getElementById('headerUserLiveLocationText');
        const deskLabel = document.getElementById('deskUserLiveLocationText');
        if (mobLabel) mobLabel.textContent = locationName;
        if (deskLabel) deskLabel.textContent = locationName;
    }

    function openGlobalLocationModal() {
        const modal = document.getElementById('globalLocationModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        loadSavedAddressesInModal();
        
        // Auto focus search input on desktop
        if (window.innerWidth >= 768) {
            setTimeout(() => {
                const inp = document.getElementById('globalLocSearchInput');
                if (inp) inp.focus();
            }, 100);
        }
    }

    function closeGlobalLocationModal() {
        const modal = document.getElementById('globalLocationModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('globalLocationModal');
        if (modal && e.target === modal) {
            closeGlobalLocationModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeGlobalLocationModal();
        }
    });

    function selectGlobalLocation(name, lat, lng, isLocked = true) {
        if (!name || isNaN(lat) || isNaN(lng)) return;

        localStorage.setItem('staynest_user_location_name', name);
        localStorage.setItem('staynest_user_lat', lat);
        localStorage.setItem('staynest_user_lng', lng);
        localStorage.setItem('user_cached_lat', lat);
        localStorage.setItem('user_cached_lng', lng);
        localStorage.setItem('user_cached_address', name);
        if (isLocked) {
            localStorage.setItem('staynest_user_address_locked', 'true');
        }

        // Set cookies for server-side sorting (30 days)
        document.cookie = `staynest_user_lat=${lat}; path=/; max-age=${30 * 86400}; SameSite=Lax`;
        document.cookie = `staynest_user_lng=${lng}; path=/; max-age=${30 * 86400}; SameSite=Lax`;

        // Update header UI
        updateHeaderLocationDisplay(name);

        // Dispatch global custom event for page listeners (home, search, etc.)
        window.dispatchEvent(new CustomEvent('staynestLocationUpdated', {
            detail: { name, lat: parseFloat(lat), lng: parseFloat(lng) }
        }));

        closeGlobalLocationModal();
    }

    function loadSavedAddressesInModal() {
        const section = document.getElementById('globalLocSavedAddrSection');
        const list = document.getElementById('globalLocSavedAddrList');
        if (!section || !list) return;

        let saved = [];
        try {
            const raw = localStorage.getItem('staynest_default_address');
            if (raw) saved.push(JSON.parse(raw));
        } catch(e) {}

        try {
            const multi = JSON.parse(localStorage.getItem('staynest_saved_addresses') || '[]');
            if (Array.isArray(multi)) {
                multi.forEach(item => {
                    if (!saved.some(s => s.line1 === item.line1 && s.line2 === item.line2)) {
                        saved.push(item);
                    }
                });
            }
        } catch(e) {}

        if (saved.length > 0) {
            section.classList.remove('hidden');
            list.innerHTML = saved.map((addr) => {
                const tag = (addr.tag || 'HOME').toUpperCase();
                const title = addr.line1 || addr.tag || 'Saved Address';
                const sub = addr.line2 || addr.fullAddress || '';
                const lat = addr.lat || 28.6280;
                const lng = addr.lng || 77.3649;
                return `
                    <button type="button" onclick="selectGlobalLocation('${escapeHtml(title + ', ' + sub)}', ${lat}, ${lng}, true)" class="w-full flex items-center gap-3 p-2.5 rounded-xl bg-gray-50 hover:bg-brand/10 border border-gray-200 transition text-left cursor-pointer group">
                        <div class="w-8 h-8 rounded-lg bg-gray-200/80 text-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                            <i class="fas ${tag === 'HOME' ? 'fa-home' : (tag === 'WORK' ? 'fa-briefcase' : 'fa-location-dot')}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-bold text-gray-900 truncate">${escapeHtml(title)} <span class="text-[9px] bg-gray-200 text-gray-700 px-1 py-0.2 rounded font-semibold ml-1">${escapeHtml(tag)}</span></div>
                            <div class="text-[10px] text-gray-500 truncate">${escapeHtml(sub)}</div>
                        </div>
                        <i class="fas fa-check text-xs text-brand opacity-0 group-hover:opacity-100 transition"></i>
                    </button>
                `;
            }).join('');
        } else {
            section.classList.add('hidden');
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    // ----------------- Live GPS Detection from Modal -----------------
    function detectLiveGpsFromModal() {
        const btnText = document.getElementById('globalLocGpsBtnText');
        const subText = document.getElementById('globalLocGpsSubText');
        const iconWrapper = document.getElementById('globalLocGpsIconWrapper');

        if (btnText) btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting GPS...';
        if (subText) subText.textContent = 'Requesting hardware coordinates...';
        if (iconWrapper) iconWrapper.className = 'w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 animate-pulse';

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            resetGpsBtn();
            return;
        }

        navigator.geolocation.getCurrentPosition(
            async function(pos) {
                if (pos && pos.coords) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    if (subText) subText.textContent = 'Resolving address...';

                    // Reverse geocode
                    let resolvedName = await reverseGeocodeLiveCoords(lat, lng);
                    if (!resolvedName) {
                        resolvedName = `Live Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`;
                    }

                    selectGlobalLocation(resolvedName, lat, lng, false);
                    resetGpsBtn();
                }
            },
            function(err) {
                let msg = 'Unable to fetch location.';
                if (err.code === 1) msg = 'Location permission denied. Please allow GPS access.';
                else if (err.code === 2) msg = 'Location unavailable. Please select your area below.';
                else if (err.code === 3) msg = 'Location request timed out.';
                alert(msg);
                resetGpsBtn();
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    function resetGpsBtn() {
        const btnText = document.getElementById('globalLocGpsBtnText');
        const subText = document.getElementById('globalLocGpsSubText');
        const iconWrapper = document.getElementById('globalLocGpsIconWrapper');
        if (btnText) btnText.textContent = 'Use Current GPS Location';
        if (subText) subText.textContent = 'Auto-detect using device location';
        if (iconWrapper) iconWrapper.className = 'w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-brand/20';
    }

    // ----------------- Live Reverse Geocoding Helper -----------------
    async function reverseGeocodeLiveCoords(lat, lng) {
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 4000);
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=16&addressdetails=1`, {
                signal: controller.signal,
                headers: { 'Accept': 'application/json' }
            });
            clearTimeout(timeoutId);

            if (res.ok) {
                const data = await res.json();
                const addr = data.address || {};
                const locality = addr.suburb || addr.neighbourhood || addr.residential || addr.road || addr.village || addr.town || addr.city_district || '';
                const city = addr.city || addr.state_district || addr.county || addr.town || '';
                const state = addr.state || '';

                let formatted = '';
                if (locality && city) formatted = `${locality}, ${city}`;
                else if (city) formatted = state ? `${city}, ${state}` : city;
                else if (locality) formatted = locality;
                else if (data.display_name) {
                    formatted = data.display_name.split(',').slice(0, 2).join(',').trim();
                }
                if (formatted) return formatted;
            }
        } catch(e) {}

        // Fallback to BigDataCloud
        try {
            const bdcRes = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`);
            if (bdcRes.ok) {
                const bdcData = await bdcRes.json();
                const loc = bdcData.locality || bdcData.city || '';
                const sub = bdcData.principalSubdivision || '';
                if (loc) return sub ? `${loc}, ${sub}` : loc;
            }
        } catch(e) {}

        return null;
    }

    // ----------------- Live Search Input Autocomplete -----------------
    let globalLocSearchTimer = null;
    function handleGlobalLocSearchInput(val) {
        const clearBtn = document.getElementById('globalLocClearBtn');
        const resContainer = document.getElementById('globalLocSearchResultsContainer');
        const resList = document.getElementById('globalLocSearchResults');
        const spinner = document.getElementById('globalLocSearchSpinner');

        if (!val || val.trim().length === 0) {
            if (clearBtn) clearBtn.classList.add('hidden');
            if (resContainer) resContainer.classList.add('hidden');
            return;
        }

        if (clearBtn) clearBtn.classList.remove('hidden');
        if (resContainer) resContainer.classList.remove('hidden');
        if (spinner) spinner.classList.remove('hidden');

        clearTimeout(globalLocSearchTimer);
        globalLocSearchTimer = setTimeout(async () => {
            try {
                const query = val.trim();
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=in&limit=6&addressdetails=1`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (spinner) spinner.classList.add('hidden');

                if (response.ok) {
                    const items = await response.json();
                    if (!items || items.length === 0) {
                        resList.innerHTML = `<div class="p-3 text-center text-xs text-gray-500 bg-gray-50 rounded-xl">No localities found matching "${query}". Try another area.</div>`;
                        return;
                    }

                    resList.innerHTML = items.map(item => {
                        const displayName = item.display_name;
                        const parts = displayName.split(',');
                        const mainTitle = parts.slice(0, 2).join(',').trim();
                        const subTitle = parts.slice(2).join(',').trim();
                        const lat = parseFloat(item.lat);
                        const lng = parseFloat(item.lon);

                        return `
                            <button type="button" onclick="selectGlobalLocation('${escapeHtml(mainTitle)}', ${lat}, ${lng}, true)" class="w-full flex items-start gap-2.5 p-2.5 rounded-xl bg-gray-50 hover:bg-brand/10 border border-gray-200 transition text-left cursor-pointer group">
                                <div class="w-7 h-7 rounded-lg bg-brand/10 text-brand flex items-center justify-center flex-shrink-0 text-xs mt-0.5 group-hover:bg-brand group-hover:text-white transition">
                                    <i class="fas fa-map-pin"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-gray-900 group-hover:text-brand transition truncate">${escapeHtml(mainTitle)}</div>
                                    <div class="text-[10px] text-gray-500 truncate">${escapeHtml(subTitle)}</div>
                                </div>
                            </button>
                        `;
                    }).join('');
                }
            } catch(e) {
                if (spinner) spinner.classList.add('hidden');
            }
        }, 300);
    }

    function clearGlobalLocSearch() {
        const inp = document.getElementById('globalLocSearchInput');
        if (inp) {
            inp.value = '';
            handleGlobalLocSearchInput('');
            inp.focus();
        }
    }

    // ----------------- Initial Load & Background GPS Precision Guard -----------------
    (function() {
        const savedAddrStr = localStorage.getItem('staynest_default_address');
        const cachedLocName = localStorage.getItem('staynest_user_location_name');
        const isLocked = localStorage.getItem('staynest_user_address_locked') === 'true';

        if (cachedLocName) {
            updateHeaderLocationDisplay(cachedLocName);
        } else if (savedAddrStr) {
            try {
                const parsed = JSON.parse(savedAddrStr);
                const line = parsed.line2 || parsed.line1 || parsed.fullAddress || '';
                if (line) updateHeaderLocationDisplay(line);
            } catch(e) {}
        }

        // Live Device GPS Precision Guard
        // Coarse desktop ISP IP (accuracy > 500m) will NEVER overwrite a user's chosen location!
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async function(position) {
                    if (position && position.coords) {
                        const accuracy = position.coords.accuracy || 999999;
                        const hasCurrentLoc = !!localStorage.getItem('staynest_user_location_name');
                        const isLocLocked = localStorage.getItem('staynest_user_address_locked') === 'true';

                        // If user already has a locked/chosen location or if accuracy is coarse (desktop Delhi IP gateway), DO NOT overwrite
                        if (accuracy > 500 && (isLocLocked || hasCurrentLoc || savedAddrStr)) {
                            return;
                        }

                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const formatted = await reverseGeocodeLiveCoords(lat, lng);
                        if (formatted) {
                            localStorage.setItem('staynest_user_location_name', formatted);
                            localStorage.setItem('staynest_user_lat', lat);
                            localStorage.setItem('staynest_user_lng', lng);
                            localStorage.setItem('user_cached_lat', lat);
                            localStorage.setItem('user_cached_lng', lng);
                            localStorage.setItem('user_cached_address', formatted);
                            updateHeaderLocationDisplay(formatted);
                        }
                    }
                },
                function(err) {},
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }
    })();
</script>
