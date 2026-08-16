<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Broker Panel') - StayNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '#4bb59d',
                            light: '#e6f7f3',
                            dark: '#3a9a85',
                            50: '#f0fdf9',
                            100: '#ccf0e8'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-text { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .tap-effect { transition: all 0.2s; }
        .tap-effect:active { transform: scale(0.96); }
        .sidebar-link.active { background-color: #e6f7f3; color: #4bb59d; font-weight: 600; }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
        /* Custom scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
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
            <span class="font-bold text-lg">StayNest <span class="text-brand text-xs font-semibold">Broker</span></span>
        </div>
        <a href="{{ route('broker.bookings') }}" class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center tap-effect relative" aria-label="Notifications">
            <i class="fas fa-bell text-gray-700"></i>
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
        </a>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 z-50 transition-transform duration-300 lg:translate-x-0 flex flex-col justify-between shadow-lg lg:shadow-none">
        <div>
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <a href="{{ route('broker.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand/30">
                        <i class="fas fa-home"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xl leading-tight">StayNest</div>
                        <div class="text-xs text-brand font-semibold tracking-wide">Broker Panel</div>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center tap-effect">
                    <i class="fas fa-times text-gray-500 text-sm"></i>
                </button>
            </div>

            <nav class="p-4 space-y-1">
                <a href="{{ route('broker.dashboard') }}" class="sidebar-link {{ request()->routeIs('broker.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a href="{{ route('broker.pgs') }}" class="sidebar-link {{ request()->routeIs('broker.pgs') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-building w-5 text-center"></i>
                    <span class="text-sm font-medium">My PGs</span>
                    <span class="ml-auto bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">12</span>
                </a>
                <a href="{{ route('broker.bookings') }}" class="sidebar-link {{ request()->routeIs('broker.bookings') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span class="text-sm font-medium">Bookings</span>
                    <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">5</span>
                </a>
                <a href="{{ route('broker.tenants') }}" class="sidebar-link {{ request()->routeIs('broker.tenants') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span class="text-sm font-medium">Tenants</span>
                    <span class="ml-auto bg-blue-50 text-blue-600 text-xs font-bold px-2 py-0.5 rounded-full">36</span>
                </a>
                <a href="{{ route('broker.earnings') }}" class="sidebar-link {{ request()->routeIs('broker.earnings') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-rupee-sign w-5 text-center"></i>
                    <span class="text-sm font-medium">Earnings</span>
                </a>
                <a href="{{ route('broker.reviews') }}" class="sidebar-link {{ request()->routeIs('broker.reviews') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-star w-5 text-center"></i>
                    <span class="text-sm font-medium">Reviews</span>
                    <span class="ml-auto bg-yellow-50 text-yellow-600 text-xs font-bold px-2 py-0.5 rounded-full">4.8 ★</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Account</div>
            <a href="{{ route('broker.profile') }}" class="sidebar-link {{ request()->routeIs('broker.profile') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span class="text-sm font-medium">Profile & Settings</span>
            </a>
            <a href="{{ route('broker.login') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span class="text-sm font-medium">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Main Content Area -->
    <div class="lg:ml-64 pt-16 lg:pt-0 min-h-screen flex flex-col justify-between">
        <main>
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-100 py-4 px-8 text-center md:flex md:justify-between text-xs text-gray-500 mt-12">
            <div>&copy; {{ date('Y') }} StayNest Technologies. All rights reserved.</div>
            <div class="mt-2 md:mt-0 space-x-4">
                <a href="{{ route('user.home') }}" class="hover:text-brand transition">User Home</a>
                <a href="{{ route('broker.dashboard') }}" class="hover:text-brand transition">Broker Portal</a>
                <a href="#" class="hover:text-brand transition">Support</a>
            </div>
        </footer>
    </div>

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
