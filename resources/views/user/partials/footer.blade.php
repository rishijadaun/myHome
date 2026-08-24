<!-- Mobile Bottom Navigation -->
@php
    $bottomNavProfileUrl = route('user.login');
    $bottomNavProfileLabel = 'Profile';
    if (Auth::check()) {
        $u = Auth::user();
        if ($u->roles()->whereIn('slug', ['super_admin', 'admin'])->exists()) {
            $bottomNavProfileUrl = route('admin.dashboard');
            $bottomNavProfileLabel = 'Dashboard';
        } elseif ($u->roles()->where('slug', 'broker')->exists()) {
            $bottomNavProfileUrl = route('broker.dashboard');
            $bottomNavProfileLabel = 'Dashboard';
        } else {
            $bottomNavProfileUrl = route('user.profile');
            $bottomNavProfileLabel = 'Profile';
        }
    }
@endphp
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 pb-safe shadow-lg">
    <div class="grid grid-cols-5 h-16">
        <a href="{{ route('user.home') }}" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-home text-xl {{ request()->routeIs('user.home') ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[10px] font-medium {{ request()->routeIs('user.home') ? 'text-brand font-semibold' : 'text-gray-500' }}">Home</span>
        </a>
        <a href="{{ route('user.search') }}" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-search text-xl {{ request()->routeIs('user.search') ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[10px] font-medium {{ request()->routeIs('user.search') ? 'text-brand font-semibold' : 'text-gray-500' }}">Search</span>
        </a>
        <a href="{{ route('user.location') }}" class="flex flex-col items-center justify-center gap-1 tap-effect -translate-y-4">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white shadow-lg shadow-brand/40">
                <i class="fas fa-map-marker-alt text-xl text-white"></i>
            </div>
            <span class="text-[10px] font-medium text-gray-500 -mt-1">Map</span>
        </a>
        <a href="{{ route('user.saved') }}" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-heart text-xl {{ request()->routeIs('user.saved') ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[10px] font-medium {{ request()->routeIs('user.saved') ? 'text-brand font-semibold' : 'text-gray-500' }}">Saved</span>
        </a>
        <a href="{{ $bottomNavProfileUrl }}" id="bottomNavProfileLink" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-user text-xl {{ (request()->routeIs('user.profile') || request()->is('profile')) ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[10px] font-medium {{ (request()->routeIs('user.profile') || request()->is('profile')) ? 'text-brand font-semibold' : 'text-gray-500' }}">{{ $bottomNavProfileLabel }}</span>
        </a>
    </div>
</nav>

<!-- Desktop Global Footer Component -->
<footer class="hidden md:block bg-gray-900 text-gray-300 mt-0">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-8">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        <i class="fas fa-home"></i>
                    </div>
                    <span class="font-bold text-2xl text-white tracking-tight">Stay<span class="text-brand">Nest</span></span>
                </div>
                <p class="text-sm text-gray-400 mb-4 max-w-md">India's leading zero-brokerage paying guest (PG) and co-living discovery network. 100% verified rooms for students and working professionals.</p>
                <div class="flex gap-3">
                    <a href="https://facebook.com/staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-brand flex items-center justify-center transition tap-effect"><i class="fab fa-facebook-f text-sm"></i></a>
                    <a href="https://twitter.com/staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-brand flex items-center justify-center transition tap-effect"><i class="fab fa-twitter text-sm"></i></a>
                    <a href="https://instagram.com/staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-brand flex items-center justify-center transition tap-effect"><i class="fab fa-instagram text-sm"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Quick Links</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('user.home') }}" class="hover:text-brand transition">Home</a></li>
                    <li><a href="{{ route('user.search') }}" class="hover:text-brand transition">Search PGs</a></li>
                    <li><a href="{{ route('user.pricing') }}" class="hover:text-brand transition">Pricing Plans</a></li>
                    <li><a href="{{ route('user.list-property') }}" class="hover:text-brand transition">List Property</a></li>
                    <li><a href="{{ route('user.contact') }}" class="hover:text-brand transition">Contact Us</a></li>
                    <li><a href="{{ route('user.terms') }}" class="hover:text-brand transition">Terms of Service</a></li>
                    <li><a href="{{ route('user.privacy') }}" class="hover:text-brand transition">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Explore Cities</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('user.search', ['city' => 'Ghaziabad']) }}" class="hover:text-brand transition">Ghaziabad PGs</a></li>
                    <li><a href="{{ route('user.search', ['city' => 'Gurgaon']) }}" class="hover:text-brand transition">Gurgaon PGs</a></li>
                    <li><a href="{{ route('user.search', ['city' => 'Noida']) }}" class="hover:text-brand transition">Noida PGs</a></li>
                    <li><a href="{{ route('user.search', ['city' => 'Delhi']) }}" class="hover:text-brand transition">Delhi PGs</a></li>
                    <li><a href="{{ route('user.search', ['city' => 'Bangalore']) }}" class="hover:text-brand transition">Bangalore PGs</a></li>
                    <li><a href="{{ route('user.search', ['city' => 'Mumbai']) }}" class="hover:text-brand transition">Mumbai PGs</a></li>
                    <li><a href="{{ route('user.search', ['city' => 'Hyderabad']) }}" class="hover:text-brand transition">Hyderabad PGs</a></li>
                    <li><a href="{{ route('user.search', ['city' => 'Pune']) }}" class="hover:text-brand transition">Pune PGs</a></li> 
                    <li><a href="{{ route('user.search', ['city' => 'Lucknow']) }}" class="hover:text-brand transition">Lucknow PGs</a></li> 
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Portals</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('user.bookings') }}" class="hover:text-brand transition">My Bookings</a></li>
                    <li><a href="{{ route('broker.login') }}" class="hover:text-brand transition">Broker Portal</a></li>
                    <!-- <li><a href="{{ route('admin.login') }}" class="hover:text-brand transition">Admin Console</a></li> -->
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} StayNest. All rights reserved.</p>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <a href="{{ route('user.terms') }}" class="hover:text-brand transition">Terms</a>
                <span>•</span>
                <a href="{{ route('user.privacy') }}" class="hover:text-brand transition">Privacy</a>
                <span>•</span>
                <a href="{{ route('user.contact') }}" class="hover:text-brand transition">Contact</a>
                <span>•</span>
                <a href="{{ route('sitemap') }}" class="hover:text-brand transition">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
