@php
    $headerProfileUrl = route('user.login');
    $headerProfileLabel = 'Login';
    $headerProfileIcon = 'fa-user';
    $headerRoleBadge = null;

    if (Auth::check()) {
        $authUser = Auth::user();
        if ($authUser->roles()->whereIn('slug', ['super_admin', 'admin'])->exists()) {
            $headerProfileUrl = route('admin.dashboard');
            $headerProfileLabel = 'Admin Dashboard';
            $headerProfileIcon = 'fa-shield-halved';
            $headerRoleBadge = 'ADMIN';
        } elseif ($authUser->roles()->where('slug', 'broker')->exists()) {
            $headerProfileUrl = route('broker.dashboard');
            $headerProfileLabel = 'Broker Dashboard';
            $headerProfileIcon = 'fa-gauge-high';
            $headerRoleBadge = 'BROKER';
        } else {
            $headerProfileUrl = route('user.profile');
            $headerProfileLabel = $authUser->profile?->first_name ?? 'My Profile';
            $headerProfileIcon = 'fa-user-check';
            $headerRoleBadge = 'TENANT';
        }
    }
@endphp

<!-- Mobile App Header -->
<header class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">

    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <a href="{{ route('user.location') }}" class="flex-1 min-w-0 flex items-center gap-2">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="text-lg font-bold text-gray-900">StayNest</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </div>
                    <p class="text-xs text-gray-500 truncate"><i class="fas fa-map-marker-alt text-brand text-[10px] mr-1"></i>Sector 62, Noida, Delhi NCR</p>
                </div>
            </a>

            <div class="flex items-center gap-2 ml-3">
                <a href="{{ route('user.saved') }}" class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center tap-effect shadow-xs" title="Saved">
                    <i class="fas fa-heart text-sm"></i>
                </a>
                <a href="{{ route('user.bookings') }}" class="w-10 h-10 rounded-full bg-brand-light text-brand flex items-center justify-center tap-effect shadow-xs relative" title="Bookings">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute -top-1 -right-1 bg-brand text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-white">2</span>
                </a>
                
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
                <nav class="flex space-x-8">
                    <a href="{{ route('user.home') }}" class="{{ request()->routeIs('user.home') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Home</a>
                    <a href="{{ route('user.search') }}" class="{{ request()->routeIs('user.search') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Find PG</a>
                    <!-- <a href="{{ route('user.list-property') }}" class="{{ request()->routeIs('user.list-property') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">List Property</a> -->
                    <!-- <a href="{{ route('user.pricing') }}" class="{{ request()->routeIs('user.pricing') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Pricing</a> -->
                    <a href="{{ route('user.about') }}" class="{{ request()->routeIs('user.about*') || request()->is('about*') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">About Us</a>
                    <a href="{{ route('user.contact') }}" class="{{ request()->routeIs('user.contact*') || request()->is('contact*') ? 'text-brand font-semibold border-b-2 border-brand' : 'text-gray-600 hover:text-brand font-medium' }} transition text-sm py-2">Contact Us</a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('user.search') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition" title="Search PGs">
                    <i class="fas fa-search"></i>
                </a>
                <a href="{{ route('user.saved') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition" title="Saved Properties">
                    <i class="fas fa-heart text-red-500"></i>
                </a>
                <a href="{{ route('user.bookings') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative" title="My Bookings">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </a>

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
                            <a href="{{ route('user.login') }}" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
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

                <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">
                    List PG Free
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    // Check Client-Side Token State on Page Load
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('staynest_token');
        const userStr = localStorage.getItem('staynest_user');
        
        if (token && userStr) {
            try {
                const user = JSON.parse(userStr);
                const guestBox = document.getElementById('clientGuestState');
                const authBox = document.getElementById('clientAuthState');
                const nameLabel = document.getElementById('clientUserName');
                const roleBadge = document.getElementById('clientRoleBadge');
                const profileIcon = document.getElementById('clientProfileIcon');
                const clientProfileLink = document.getElementById('clientProfileLink');
                const mobProfileLink = document.getElementById('mobProfileLink');
                const bottomNavProfileLink = document.getElementById('bottomNavProfileLink');
                
                let targetUrl = "{{ route('user.profile') }}";
                let displayTitle = user.first_name || "My Profile";
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
                }

                if (clientProfileLink) clientProfileLink.href = targetUrl;
                if (mobProfileLink) mobProfileLink.href = targetUrl;
                if (bottomNavProfileLink) bottomNavProfileLink.href = targetUrl;

                if (guestBox && authBox) {
                    guestBox.classList.add('hidden');
                    authBox.classList.remove('hidden');
                    if (nameLabel) nameLabel.innerText = displayTitle;
                    if (roleBadge) roleBadge.innerText = badgeText;
                    if (profileIcon) profileIcon.className = 'fas ' + iconClass;
                }
            } catch(e) {}
        }
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
</script>
