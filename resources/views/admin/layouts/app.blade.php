<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>@yield('title', 'Admin Panel') - StayNest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .tap-effect { transition: all 0.2s; }
        .tap-effect:active { transform: scale(0.96); }
        .sidebar-link.active { background-color: #e6f7f3; color: #4bb59d; font-weight: 600; }
        .sidebar-link.active i { color: #4bb59d; }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased min-h-screen">

    <!-- Mobile Top Bar -->
    <div class="lg:hidden fixed top-0 left-0 right-0 bg-white border-b border-gray-100 z-40 px-4 py-3 flex items-center justify-between shadow-sm">
        <button onclick="toggleSidebar()" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center tap-effect" aria-label="Open Menu">
            <i class="fas fa-bars text-gray-700"></i>
        </button>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-brand to-brand-dark rounded-lg flex items-center justify-center text-white font-bold">
                <i class="fas fa-home text-sm"></i>
            </div>
            <span class="font-bold text-lg">StayNest <span class="text-brand text-xs font-semibold">Admin</span></span>
        </div>
        <a href="{{ route('admin.bookings') }}" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center tap-effect relative" aria-label="Notifications">
            <i class="fas fa-bell text-gray-700"></i>
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
        </a>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar fixed top-0 left-0 h-screen w-64 bg-white border-r border-gray-100 z-50 transition-transform duration-300 lg:translate-x-0 flex flex-col shadow-lg lg:shadow-none overflow-hidden">
        <!-- Fixed Header / Logo -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-shrink-0 bg-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand/30 shrink-0">
                    <i class="fas fa-home"></i>
                </div>
                <div>
                    <div class="font-bold text-xl leading-tight text-gray-900">StayNest</div>
                    <div class="text-xs text-brand font-semibold tracking-wide">Admin Panel</div>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center tap-effect cursor-pointer" aria-label="Close Menu">
                <i class="fas fa-times text-gray-500 text-sm"></i>
            </button>
        </div>

        <!-- Scrollable Navigation Menu -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-1 overscroll-contain">
            <div class="px-3 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Main Navigation</div>
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.pgs') }}" class="sidebar-link {{ request()->routeIs('admin.pgs') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-building w-5 text-center"></i>
                    <span class="text-sm font-medium">All Properties</span>
                    <span class="ml-auto bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['properties'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.brokers') }}" class="sidebar-link {{ request()->routeIs('admin.brokers*') && !request()->routeIs('admin.broker-kyc*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-user-tie w-5 text-center"></i>
                    <span class="text-sm font-medium">Brokers</span>
                    @if(($adminSidebarStats['pendingBrokers'] ?? 0) > 0)
                        <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['pendingBrokers'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.broker-kyc.index') }}" class="sidebar-link {{ request()->routeIs('admin.broker-kyc*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-id-card-clip w-5 text-center text-teal-600"></i>
                    <span class="text-sm font-medium">Broker KYC</span>
                    @if(($adminSidebarStats['pendingKyc'] ?? 0) > 0)
                        <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $adminSidebarStats['pendingKyc'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.bookings') }}" class="sidebar-link {{ request()->routeIs('admin.bookings') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span class="text-sm font-medium">Bookings</span>
                    <span class="ml-auto bg-blue-50 text-blue-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['bookings'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span class="text-sm font-medium">Users</span>
                    <span class="ml-auto bg-purple-50 text-purple-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['users'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.roommates') }}" class="sidebar-link {{ request()->routeIs('admin.roommates*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-handshake w-5 text-center text-emerald-600"></i>
                    <span class="text-sm font-medium">Flatmates / Roommates</span>
                    <span class="ml-auto bg-emerald-50 text-emerald-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['roommates'] ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.reviews') }}" class="sidebar-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-star w-5 text-center text-amber-500"></i>
                    <span class="text-sm font-medium">Reviews</span>
                    @if(($adminSidebarStats['pendingReviews'] ?? 0) > 0)
                        <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $adminSidebarStats['pendingReviews'] }}</span>
                    @else
                        <span class="ml-auto bg-amber-50 text-amber-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['totalReviews'] ?? 0 }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.contacts') }}" class="sidebar-link {{ request()->routeIs('admin.contacts*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-envelope-open-text w-5 text-center text-teal-600"></i>
                    <span class="text-sm font-medium">Contact Inquiries</span>
                    @if(($adminSidebarStats['pendingContacts'] ?? 0) > 0)
                        <span class="ml-auto bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $adminSidebarStats['pendingContacts'] }}</span>
                    @else
                        <span class="ml-auto bg-teal-50 text-teal-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['totalContacts'] ?? 0 }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-flag w-5 text-center text-rose-500"></i>
                    <span class="text-sm font-medium">Reported Listings</span>
                    @if(($adminSidebarStats['pendingReports'] ?? 0) > 0)
                        <span class="ml-auto bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $adminSidebarStats['pendingReports'] }}</span>
                    @else
                        <span class="ml-auto bg-rose-50 text-rose-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $adminSidebarStats['totalReports'] ?? 0 }}</span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Fixed Bottom Profile & System Menu -->
        <div class="p-4 border-t border-gray-100 flex-shrink-0 bg-white space-y-1">
            @if(Auth::check())
            <div class="px-3 py-2.5 mb-2 bg-brand-50/60 border border-brand-100/80 rounded-xl flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand to-brand-dark text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-brand/30 shrink-0">
                    {{ strtoupper(substr(Auth::user()->profile->first_name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden min-w-0">
                    <div class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->profile->first_name ?? 'Admin' }} {{ Auth::user()->profile->last_name ?? '' }}</div>
                    <div class="text-[10px] text-brand-dark font-medium truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>
            @endif
            <div class="px-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">System</div>
            <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-cog w-5 text-center"></i>
                <span class="text-sm font-medium">Settings</span>
            </a>
            <a href="{{ route('admin.logout') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span class="text-sm font-medium">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="lg:ml-64 pt-16 lg:pt-0 min-h-screen flex flex-col justify-between">
        <main>
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-100 py-4 px-8 text-center md:flex md:justify-between text-xs text-gray-500 mt-12">
            <div>&copy; {{ date('Y') }} StayNest Technologies. Administrator Console v2.4.</div>
            <div class="mt-2 md:mt-0 space-x-4">
                <a href="{{ route('user.home') }}" class="hover:text-brand transition">User Home</a>
                <a href="{{ route('broker.dashboard') }}" class="hover:text-brand transition">Broker Portal</a>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition">Admin Dashboard</a>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
