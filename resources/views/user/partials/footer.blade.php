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
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl border-t border-gray-200/80 z-50 pb-safe shadow-lg">
    <div class="grid grid-cols-5 h-16 max-w-lg mx-auto">
        <a href="{{ route('user.home') }}" class="flex flex-col items-center justify-center gap-1 tap-effect px-0.5">
            <i class="fas fa-home text-lg sm:text-xl {{ request()->routeIs('user.home') ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[9px] sm:text-[10px] font-medium truncate {{ request()->routeIs('user.home') ? 'text-brand font-bold' : 'text-gray-500' }}">Home</span>
        </a>
        <a href="{{ route('user.search') }}" class="flex flex-col items-center justify-center gap-1 tap-effect px-0.5">
            <i class="fas fa-search text-lg sm:text-xl {{ request()->routeIs('user.search') ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[9px] sm:text-[10px] font-medium truncate {{ request()->routeIs('user.search') ? 'text-brand font-bold' : 'text-gray-500' }}">Search</span>
        </a>
        <a href="{{ route('user.location') }}" class="flex flex-col items-center justify-center gap-0.5 tap-effect -translate-y-3.5 px-0.5">
            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white shadow-lg shadow-brand/40 border-2 border-white">
                <i class="fas fa-map-marker-alt text-lg sm:text-xl text-white"></i>
            </div>
            <span class="text-[9px] sm:text-[10px] font-medium text-gray-500">Map</span>
        </a>
        <a href="{{ route('user.saved') }}" class="flex flex-col items-center justify-center gap-1 tap-effect px-0.5">
            <i class="fas fa-heart text-lg sm:text-xl {{ request()->routeIs('user.saved') ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[9px] sm:text-[10px] font-medium truncate {{ request()->routeIs('user.saved') ? 'text-brand font-bold' : 'text-gray-500' }}">Saved</span>
        </a>
        <a href="{{ $bottomNavProfileUrl }}" id="bottomNavProfileLink" class="flex flex-col items-center justify-center gap-1 tap-effect px-0.5">
            <i class="fas fa-user text-lg sm:text-xl {{ (request()->routeIs('user.profile') || request()->is('profile')) ? 'text-brand' : 'text-gray-400' }}"></i>
            <span class="text-[9px] sm:text-[10px] font-medium truncate {{ (request()->routeIs('user.profile') || request()->is('profile')) ? 'text-brand font-bold' : 'text-gray-500' }}">{{ $bottomNavProfileLabel }}</span>
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
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'ghaziabad']) }}" class="hover:text-brand transition">Ghaziabad PGs</a></li>
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'gurgaon']) }}" class="hover:text-brand transition">Gurgaon PGs</a></li>
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'noida']) }}" class="hover:text-brand transition">Noida PGs</a></li>
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'delhi']) }}" class="hover:text-brand transition">Delhi PGs</a></li>
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'bangalore']) }}" class="hover:text-brand transition">Bangalore PGs</a></li>
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'mumbai']) }}" class="hover:text-brand transition">Mumbai PGs</a></li>
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'hyderabad']) }}" class="hover:text-brand transition">Hyderabad PGs</a></li>
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'pune']) }}" class="hover:text-brand transition">Pune PGs</a></li> 
                    <li><a href="{{ route('user.seo.city-area', ['city' => 'lucknow']) }}" class="hover:text-brand transition">Lucknow PGs</a></li> 
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Portals</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('user.bookings') }}" class="hover:text-brand transition">My Bookings</a></li>
                    <li><a href="{{ route('broker.login') }}" class="hover:text-brand transition">Broker/Builder Portal</a></li>
                </ul>
            </div>
            <!-- <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Get the App</h4>
                <div class="space-y-2">
                    <button type="button" onclick="installPwaApp('android')" class="w-full bg-gray-800 hover:bg-gray-700 text-white px-3.5 py-2 rounded-xl flex items-center gap-2.5 transition tap-effect border border-gray-700/80 cursor-pointer">
                        <i class="fab fa-google-play text-emerald-400 text-base"></i>
                        <div class="text-left leading-tight">
                            <div class="text-[8px] uppercase tracking-wider text-gray-400">GET IT ON</div>
                            <div class="text-xs font-bold">Google Play</div>
                        </div>
                    </button>
                    <button type="button" onclick="installPwaApp('ios')" class="w-full bg-gray-800 hover:bg-gray-700 text-white px-3.5 py-2 rounded-xl flex items-center gap-2.5 transition tap-effect border border-gray-700/80 cursor-pointer">
                        <i class="fab fa-apple text-white text-base"></i>
                        <div class="text-left leading-tight">
                            <div class="text-[8px] uppercase tracking-wider text-gray-400">Download on</div>
                            <div class="text-xs font-bold">App Store</div>
                        </div>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- ================= EXPLORE PGS ACROSS INDIA - SEO DIRECTORY MATRIX (COLLAPSIBLE TOGGLE) ================= -->
        <div class="border-t border-gray-800 pt-6 pb-2 mb-4">
            <details class="group" id="explorePgsAccordion">
                <summary class="flex items-center justify-between cursor-pointer list-none text-xs sm:text-sm font-bold text-gray-400 hover:text-white tracking-wider uppercase select-none transition py-2">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-compass text-brand"></i>
                        <span>Explore PGs &amp; Tech Parks Across India</span>
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-gray-500 group-hover:text-brand font-medium">
                        <span class="group-open:hidden">Show Directory</span>
                        <span class="hidden group-open:inline">Hide Directory</span>
                        <i class="fas fa-chevron-down text-[10px] group-open:rotate-180 transition-transform duration-300"></i>
                    </span>
                </summary>

                <div class="pt-6 pb-2 space-y-4 text-[11px] sm:text-xs">
            @php
                $seoFooterMatrix = [
                    [
                        'label' => 'PG in Gurgaon',
                        'city' => 'gurgaon',
                        'is_tech_park' => false,
                        'items' => [
                            'Sector 29', 'Sector 43', 'Sector 20', 'Sector 53', 'Sector 44', 'Sector 30', 'Sector 18', 'Sector 23',
                            'Sector 48', 'Sector 69', 'Sector 38', 'Sector 15', 'Sector 40', 'Sector 46', 'Sector 55', 'Sector 57',
                            'Sector 17', 'Sector 21', 'Sector 22', 'Sector 24', 'Sector 25', 'Sector 28', 'Sector 31', 'Sector 33',
                            'Sector 45', 'Sector 47', 'Sector 49', 'Sector 50', 'Sector 56', 'Sector 58', 'DLF Phase 1', 'DLF Phase 2',
                            'DLF Phase 3', 'DLF Phase 4', 'Sohna Road', 'Golf Course Road', 'MG Road', 'Palam Vihar', 'Udyog Vihar',
                            'Cyber City', 'South City 1', 'South City 2', 'Manesar', 'Sector 67', 'Sector 14'
                        ]
                    ],
                    [
                        'label' => 'Business Parks Gurgaon',
                        'city' => 'gurgaon',
                        'is_tech_park' => true,
                        'items' => [
                            'DLF Cyber City', 'DLF CyberHub', 'DLF Cyber Park', 'DLF Corporate Park', 'DLF Corporate Greens',
                            'DLF Pinnacle', 'DLF Gateway Tower', 'DLF Square', 'DLF Star Tower', 'DLF Infinity Tower A',
                            'DLF Infinity Tower B', 'Udyog Vihar', 'Golf Course Road', 'Sohna Road', 'Golf Course Ext. Road',
                            'MG Road', 'IFFCO Chowk', 'HUDA City Centre', 'Global Business Park', 'Global Foyer',
                            'One Horizon Center', 'Two Horizon Center', 'Candor TechSpace Sec 21', 'Candor TechSpace Sec 48',
                            'Emaar Digital Greens', 'Emaar Business District', 'Unitech Cyber Park', 'Vatika Business Park',
                            'Vatika Atrium', 'Vatika Professional Point', 'JMD Megapolis', 'JMD Regent Plaza', 'JMD Pacific Square',
                            'Spaze I-Tech Park', 'Spaze Business Park', 'Bestech Business Tower', 'Bestech Cyber Park',
                            'Orchid Business Park', 'Time Tower', 'Signature Towers', 'Raheja Square', 'Paras Trade Centre',
                            'Ansal Corporate Plaza', 'Vipul Tech Square', 'Vipul Trade Centre', 'M3M IFC', 'M3M Urbana Business Park',
                            'AIPL Business Club', 'Elan Town Centre', 'Manesar'
                        ]
                    ],
                    [
                        'label' => 'PG in Bangalore',
                        'city' => 'bangalore',
                        'is_tech_park' => false,
                        'items' => [
                            'Whitefield', 'Koramangala', 'HSR Layout', 'Electronic City', 'Indiranagar', 'JP Nagar', 'BTM Layout',
                            'Marathahalli', 'Yelahanka', 'Basavanagudi', 'SG Palya', 'MG Road', 'Suddaguntepalya', 'Silk Board',
                            'Hebbal', 'KR Puram', 'Mahadevapura', 'Kadubeesanahalli', 'Bellandur', 'Jayanagar', 'Sarjapur Road',
                            'Bommanahalli', 'Bannerghatta Road', 'Hennur Road', 'Domlur', 'CV Raman Nagar', 'Rajajinagar',
                            'Malleswaram', 'Vijayanagar', 'Yeshwanthpur', 'Nagawara', 'Thanisandra', 'Kogilu', 'Hoodi',
                            'Kadugodi', 'Varthur', 'Brookefield', 'Panathur', 'Harlur Road', 'Kasturi Nagar', 'Devanahalli',
                            'Kengeri', 'Banaswadi', 'Banashankari', 'Haralur', 'Kodihalli', 'Kothanur', 'Chikkasandra', 'Yerapannahalli'
                        ]
                    ],
                    [
                        'label' => 'Business Parks Bangalore',
                        'city' => 'bangalore',
                        'is_tech_park' => true,
                        'items' => [
                            'Manyata Tech Park', 'ITPL Whitefield', 'Electronic City', 'RMZ Ecospace', 'RMZ Infinity',
                            'Embassy Tech Village', 'Embassy GolfLinks', 'Embassy One', 'Bagmane Tech Park', 'Bagmane Constellation',
                            'Prestige Tech Park', 'Outer Ring Road', 'Whitefield IT Corridor', 'EPIP Zone Whitefield',
                            'Salarpuria Sattva Knowledge City', 'Brigade Tech Gardens', 'Divyasree Tech Park', 'Kalyani Tech Park',
                            'Cessna Business Park', 'Global Village Tech Park', 'Pritech Park', 'RGA Tech Park',
                            'Wipro Campus Sarjapur', 'Infosys Campus Electronic City', 'TCS Banyan Park', 'SAP Labs India',
                            'Microsoft IDC', 'UB City', 'Diamond District', 'Koramangala IT Hub', 'HSR Layout Tech Hub',
                            'Karle Town Centre', 'Orion Business Park', 'Peenya Industrial Estate', 'Bannerghatta Road IT Corridor',
                            'Yelahanka Aerospace Zone'
                        ]
                    ],
                    [
                        'label' => 'PG in Noida',
                        'city' => 'noida',
                        'is_tech_park' => false,
                        'items' => [
                            'Sector 62', 'Sector 63', 'Sector 15', 'Sector 16', 'Sector 18', 'Sector 125', 'Sector 126',
                            'Sector 137', 'Sector 143', 'Sector 135', 'Sector 50', 'Sector 51', 'Sector 52', 'Sector 44',
                            'Sector 49', 'Sector 59', 'Sector 74', 'Sector 75', 'Sector 76', 'Sector 142', 'Sector 1',
                            'Sector 2', 'Sector 6', 'Sector 19', 'Sector 27', 'Sector 34', 'Sector 35', 'Sector 36',
                            'Sector 37', 'Sector 41', 'Sector 56', 'Sector 57', 'Sector 58', 'Sector 61', 'Sector 78',
                            'Sector 79', 'Sector 100', 'Sector 104', 'Sector 110', 'Sector 120', 'Sector 128', 'Sector 132',
                            'Sector 144', 'Sector 168', 'Sector 22'
                        ]
                    ],
                    [
                        'label' => 'PG in Greater Noida',
                        'city' => 'greater-noida',
                        'is_tech_park' => false,
                        'items' => [
                            'Knowledge Park', 'Pari Chowk', 'Alpha 1', 'Alpha 2', 'Beta 1', 'Beta 2', 'Gamma 1', 'Gamma 2',
                            'Delta 1', 'Delta 2', 'Omega 1', 'Omega 2', 'Sector 2', 'Sector 3', 'Sector 16C', 'Jagat Farm',
                            'Tugalpur', 'Shahberi', 'Bisrakh', 'Patwari', 'Kasna', 'Surajpur'
                        ]
                    ],
                    [
                        'label' => 'PG in Delhi',
                        'city' => 'delhi',
                        'is_tech_park' => false,
                        'items' => [
                            'North Campus', 'South Campus', 'Saket', 'Hauz Khas', 'Laxmi Nagar', 'Karol Bagh', 'Mukherjee Nagar',
                            'GTB Nagar', 'Dwarka', 'Janakpuri', 'Pitampura', 'Rohini', 'Patel Nagar', 'Lajpat Nagar',
                            'Malviya Nagar', 'Okhla', 'Kalkaji', 'Munirka', 'Vijay Nagar', 'Kamla Nagar', 'Connaught Place'
                        ]
                    ],
                    [
                        'label' => 'PG & Tech Hubs Hyderabad',
                        'city' => 'hyderabad',
                        'is_tech_park' => false,
                        'items' => [
                            'Hitec City', 'Gachibowli', 'Madhapur', 'Kondapur', 'Kukatpally', 'Banjara Hills', 'Jubilee Hills',
                            'Financial District', 'Manikonda', 'Ameerpet', 'Begumpet', 'SR Nagar', 'Miyapur', 'Dilsukhnagar',
                            'Raheja Mindspace', 'DLF Cyber City Hyd', 'Cyber Towers'
                        ]
                    ],
                    [
                        'label' => 'PG & Tech Parks Pune',
                        'city' => 'pune',
                        'is_tech_park' => false,
                        'items' => [
                            'Hinjewadi IT Park', 'Viman Nagar', 'Kharadi EON Free Zone', 'Baner', 'Wakad', 'Kothrud',
                            'Hadapsar', 'Magarpatta City', 'Aundh', 'Senapati Bapat Road', 'Shivaji Nagar', 'Pimple Saudagar',
                            'Bavdhan', 'Koregaon Park', 'Kalyani Nagar'
                        ]
                    ],
                    [
                        'label' => 'PG in Mumbai & Navi Mumbai',
                        'city' => 'mumbai',
                        'is_tech_park' => false,
                        'items' => [
                            'Andheri East', 'Andheri West', 'Powai', 'Bandra', 'BKC', 'Thane', 'Vashi', 'Navi Mumbai',
                            'Goregaon', 'Malad', 'Borivali', 'Ghatkopar', 'Kanjurmarg', 'Airoli Mindspace', 'Mahape Millennium Business Park'
                        ]
                    ]
                ];
            @endphp

            <div class="space-y-4 text-[11px] sm:text-xs">
                @foreach($seoFooterMatrix as $matrixRow)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-1.5 md:gap-3 py-2 border-b border-gray-800/60 last:border-b-0">
                        <div class="md:col-span-3 font-semibold text-gray-300">
                            <span class="text-white font-bold">{{ $matrixRow['label'] }}</span>
                            <span class="text-gray-500 ml-1">:</span>
                        </div>
                        <div class="md:col-span-9 text-gray-400 leading-relaxed">
                            @foreach($matrixRow['items'] as $itemIdx => $itemName)
                                @php
                                    $itemSlug = \Illuminate\Support\Str::slug($itemName);
                                    if ($matrixRow['is_tech_park']) {
                                        $targetUrl = route('user.search', ['city' => $matrixRow['city'], 'q' => $itemName]);
                                    } else {
                                        $targetUrl = route('user.seo.city-area', ['city' => $matrixRow['city'], 'area' => $itemSlug]);
                                    }
                                @endphp
                                <a href="{{ $targetUrl }}" class="hover:text-brand transition text-gray-400 hover:underline inline-block py-0.5">{{ $itemName }}</a>@if(!$loop->last)<span class="text-gray-600 px-1.5 font-normal select-none">|</span>@endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
                </div>
            </details>
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
