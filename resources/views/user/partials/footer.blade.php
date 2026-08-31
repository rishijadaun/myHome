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

<!-- ========================================================================= -->
<!-- PREMIUM DESKTOP GLOBAL FOOTER COMPONENT (Desktop & Large Screen Optimized) -->
<!-- ========================================================================= -->
<footer class="hidden md:block bg-gradient-to-b from-slate-900 via-gray-900 to-slate-950 text-slate-300 border-t border-slate-800/80 relative overflow-hidden mt-0 font-sans selection:bg-brand/30 selection:text-white">
    <!-- Ambient Lighting Glows for Rich Glassmorphic Depth -->
    <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 bg-brand/10 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/3 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 left-1/3 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 pt-14 pb-10 relative z-10">



        <!-- ================= NEWSLETTER & CONTACT CALLOUT ROW ================= -->
        <div class="py-7 border-b border-slate-800/90 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 lg:gap-8">
            <div class="max-w-xl">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-brand/15 text-brand border border-brand/30">
                        <i class="fas fa-bell text-[10px]"></i> Stay Alerts
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Join 75,000+ students &amp; working professionals</span>
                </div>
                <h3 class="text-base lg:text-lg font-bold text-white tracking-tight">Never miss newly verified PG listings &amp; price drops</h3>
            </div>

            <div class="flex flex-wrap items-center gap-4 flex-shrink-0 w-full lg:w-auto">
                <!-- <form id="footerNewsletterForm" onsubmit="handleFooterNewsletter(event)" class="flex items-center bg-slate-950/80 border border-slate-700/80 focus-within:border-brand rounded-xl p-1.5 shadow-inner transition-all w-full sm:w-80">
                    <div class="pl-2.5 pr-1.5 text-slate-500">
                        <i class="fas fa-envelope text-xs"></i>
                    </div>
                    <input type="email" id="footerNewsletterEmail" required placeholder="Enter your email address..." class="bg-transparent text-xs text-white placeholder-slate-500 focus:outline-none w-full px-1 py-1">
                    <button type="submit" id="footerNewsletterBtn" class="bg-brand hover:bg-brand-dark text-white text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 flex-shrink-0 shadow-sm cursor-pointer active:scale-95">
                        <span>Subscribe</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </form> -->

                <!-- Quick Help / Support Pill -->
                <a href="{{ route('user.contact') }}" class="inline-flex items-center gap-2.5 bg-slate-800/80 hover:bg-slate-750 text-slate-200 hover:text-white border border-slate-700/80 hover:border-slate-600 rounded-xl px-4 py-2.5 text-xs font-semibold transition-all">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></div>
                    <i class="fas fa-comments text-brand text-sm"></i>
                    <div class="text-left">
                        <div class="text-[10px] text-slate-400 leading-none">Need assistance?</div>
                        <div class="text-xs font-bold leading-tight">Help Center</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- ================= MAIN 5-COLUMN NAVIGATION GRID ================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 py-11 border-b border-slate-800/90">
            
            <!-- Column 1: Brand & Company Identity (Col Span 4) -->
            <div class="md:col-span-2 lg:col-span-4 space-y-5 pr-0 lg:pr-4">
                <!-- Logo -->
                <a href="{{ route('user.home') }}" class="inline-flex items-center gap-2.5 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand/20 group-hover:scale-105 transition-transform">
                        <i class="fas fa-home"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-2xl text-white tracking-tight leading-none block">Stay<span class="text-brand">Nest</span></span>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest block">Zero Brokerage PG Network</span>
                    </div>
                </a>

                <p class="text-xs text-slate-400 leading-relaxed">
                    StayNest is India's highest-rated paying guest (PG) and co-living discovery platform. We connect verified tenants directly with verified homeowners with 100% transparency and zero brokerage.
                </p>

                <!-- Status Pill -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-950/40 border border-emerald-800/40 text-[11px] text-emerald-300 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>1,200+ Verified PGs Active Across India</span>
                </div>

                <!-- Social Links -->
                <div class="space-y-2 pt-1">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Follow StayNest</div>
                    <div class="flex items-center gap-2.5">
                        <a href="https://facebook.com/staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-brand text-slate-300 hover:text-white border border-slate-700/60 hover:border-brand flex items-center justify-center transition-all duration-200 hover:-translate-y-1 shadow-xs" title="Facebook">
                            <i class="fab fa-facebook-f text-xs"></i>
                        </a>
                        <a href="https://twitter.com/staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-brand text-slate-300 hover:text-white border border-slate-700/60 hover:border-brand flex items-center justify-center transition-all duration-200 hover:-translate-y-1 shadow-xs" title="Twitter / X">
                            <i class="fab fa-twitter text-xs"></i>
                        </a>
                        <a href="https://instagram.com/staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-brand text-slate-300 hover:text-white border border-slate-700/60 hover:border-brand flex items-center justify-center transition-all duration-200 hover:-translate-y-1 shadow-xs" title="Instagram">
                            <i class="fab fa-instagram text-xs"></i>
                        </a>
                        <a href="https://linkedin.com/company/staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-brand text-slate-300 hover:text-white border border-slate-700/60 hover:border-brand flex items-center justify-center transition-all duration-200 hover:-translate-y-1 shadow-xs" title="LinkedIn">
                            <i class="fab fa-linkedin-in text-xs"></i>
                        </a>
                        <a href="https://youtube.com/@staynest" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-800/80 hover:bg-brand text-slate-300 hover:text-white border border-slate-700/60 hover:border-brand flex items-center justify-center transition-all duration-200 hover:-translate-y-1 shadow-xs" title="YouTube">
                            <i class="fab fa-youtube text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Discover & Stay Categories (Col Span 2) -->
            <div class="col-span-2 space-y-3.5">
                <h4 class="text-white font-bold text-xs uppercase tracking-wider flex items-center gap-1.5 pb-1 border-b border-slate-800">
                    <i class="fas fa-bed text-brand text-xs"></i>
                    <span>Stay Types</span>
                </h4>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="{{ route('user.search', ['gender' => 'male']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-chevron-right text-[9px] text-brand/70"></i>
                            <span>Boys PG Hostels</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.search', ['gender' => 'female']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-chevron-right text-[9px] text-brand/70"></i>
                            <span>Girls PG Hostels</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.search', ['gender' => 'unisex']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-chevron-right text-[9px] text-brand/70"></i>
                            <span>Co-Living Spaces</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.search', ['type' => 'private']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-chevron-right text-[9px] text-brand/70"></i>
                            <span>Single Private Rooms</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.search', ['sharing' => 'double']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-chevron-right text-[9px] text-brand/70"></i>
                            <span>Double Sharing PGs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.search', ['food' => 'included']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-chevron-right text-[9px] text-brand/70"></i>
                            <span>PGs with 3-Meal Food</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.search', ['luxury' => 1]) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-chevron-right text-[9px] text-brand/70"></i>
                            <span>Luxury Student Living</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Top Metro Cities (Col Span 2) -->
            <div class="col-span-2 space-y-3.5">
                <h4 class="text-white font-bold text-xs uppercase tracking-wider flex items-center gap-1.5 pb-1 border-b border-slate-800">
                    <i class="fas fa-city text-brand text-xs"></i>
                    <span>Popular Cities</span>
                </h4>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="{{ route('user.seo.city-area', ['city' => 'gurgaon']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-location-dot text-[9px] text-brand/70"></i>
                            <span>PG in Gurgaon</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.seo.city-area', ['city' => 'bangalore']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-location-dot text-[9px] text-brand/70"></i>
                            <span>PG in Bangalore</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.seo.city-area', ['city' => 'noida']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-location-dot text-[9px] text-brand/70"></i>
                            <span>PG in Noida</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.seo.city-area', ['city' => 'delhi']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-location-dot text-[9px] text-brand/70"></i>
                            <span>PG in Delhi NCR</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.seo.city-area', ['city' => 'hyderabad']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-location-dot text-[9px] text-brand/70"></i>
                            <span>PG in Hyderabad</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.seo.city-area', ['city' => 'pune']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-location-dot text-[9px] text-brand/70"></i>
                            <span>PG in Pune</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.seo.city-area', ['city' => 'mumbai']) }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-location-dot text-[9px] text-brand/70"></i>
                            <span>PG in Mumbai</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Owners & Partner Portals (Col Span 2) -->
            <div class="col-span-2 space-y-3.5">
                <h4 class="text-white font-bold text-xs uppercase tracking-wider flex items-center gap-1.5 pb-1 border-b border-slate-800">
                    <i class="fas fa-building-user text-brand text-xs"></i>
                    <span>Owners &amp; Brokers</span>
                </h4>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="{{ route('user.list-property') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5 font-medium">
                            <i class="fas fa-plus text-[9px] text-brand"></i>
                            <span>List Property</span>
                            <span class="bg-brand/20 text-brand border border-brand/30 text-[9px] font-bold px-1.5 py-0.2 rounded">FREE</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('broker.login') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-briefcase text-[9px] text-brand/70"></i>
                            <span>Broker / Owner Login</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.pricing') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-tags text-[9px] text-brand/70"></i>
                            <span>Pricing &amp; Plans</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.bookings') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-calendar-check text-[9px] text-brand/70"></i>
                            <span>Tenant Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.saved') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-heart text-[9px] text-brand/70"></i>
                            <span>Saved Properties</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.contact') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-handshake text-[9px] text-brand/70"></i>
                            <span>Host RM Support</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 5: Company & Support (Col Span 2) -->
            <div class="col-span-2 space-y-3.5">
                <h4 class="text-white font-bold text-xs uppercase tracking-wider flex items-center gap-1.5 pb-1 border-b border-slate-800">
                    <i class="fas fa-circle-info text-brand text-xs"></i>
                    <span>Company &amp; Legal</span>
                </h4>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="{{ route('user.about') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-arrow-right text-[9px] text-brand/70"></i>
                            <span>About StayNest</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.contact') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-arrow-right text-[9px] text-brand/70"></i>
                            <span>Contact Us</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.terms') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-arrow-right text-[9px] text-brand/70"></i>
                            <span>Terms of Service</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.privacy') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-arrow-right text-[9px] text-brand/70"></i>
                            <span>Privacy Policy</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sitemap') }}" class="text-slate-400 hover:text-white hover:translate-x-1.5 transition-all inline-flex items-center gap-1.5">
                            <i class="fas fa-arrow-right text-[9px] text-brand/70"></i>
                            <span>XML Sitemap</span>
                        </a>
                    </li>
                </ul>

                <!-- App & Security Badges -->
                <!-- <div class="pt-2 border-t border-slate-800/80 space-y-2">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">App &amp; Security</div>
                    <button type="button" onclick="if(typeof installPwaApp==='function'){installPwaApp()}else{alert('StayNest Web App is ready on your browser!')}" class="w-full bg-slate-800/90 hover:bg-slate-750 text-slate-200 border border-slate-700/80 rounded-xl p-2 flex items-center gap-2 transition-all text-left group cursor-pointer">
                        <div class="w-7 h-7 rounded-lg bg-brand/20 text-brand flex items-center justify-center flex-shrink-0 group-hover:scale-105">
                            <i class="fas fa-mobile-screen text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[10px] text-slate-400 leading-tight">Fast Mobile PWA</div>
                            <div class="text-[11px] font-bold text-white truncate">Install Web App</div>
                        </div>
                    </button>
                </div> -->
            </div>
        </div>

        <!-- ================= EXPLORE PGS ACROSS INDIA - SEO DIRECTORY MATRIX ================= -->
        @php
            $seoFooterMatrix = [
                [
                    'label' => 'PG in Gurgaon',
                    'city' => 'gurgaon',
                    'badge' => 'Millennium City',
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
                    'label' => 'Business & IT Parks Gurgaon',
                    'city' => 'gurgaon',
                    'badge' => 'Tech Hub',
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
                    'badge' => 'Silicon Valley',
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
                    'label' => 'Tech Parks & IT Corridors Bangalore',
                    'city' => 'bangalore',
                    'badge' => 'IT Corridor',
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
                    'label' => 'PG in Noida & Greater Noida',
                    'city' => 'noida',
                    'badge' => 'NCR Express',
                    'is_tech_park' => false,
                    'items' => [
                        'Sector 62', 'Sector 63', 'Sector 15', 'Sector 16', 'Sector 18', 'Sector 125', 'Sector 126',
                        'Sector 137', 'Sector 143', 'Sector 135', 'Sector 50', 'Sector 51', 'Sector 52', 'Sector 44',
                        'Sector 49', 'Sector 59', 'Sector 74', 'Sector 75', 'Sector 76', 'Sector 142', 'Sector 1',
                        'Sector 2', 'Sector 6', 'Sector 19', 'Sector 27', 'Sector 34', 'Sector 35', 'Sector 36',
                        'Sector 37', 'Sector 41', 'Sector 56', 'Sector 57', 'Sector 58', 'Sector 61', 'Sector 78',
                        'Sector 79', 'Sector 100', 'Sector 104', 'Sector 110', 'Sector 120', 'Sector 128', 'Sector 132',
                        'Sector 144', 'Sector 168', 'Sector 22', 'Knowledge Park', 'Pari Chowk', 'Alpha 1', 'Beta 1', 'Delta 1'
                    ]
                ],
                [
                    'label' => 'PG in Delhi (North & South Campus)',
                    'city' => 'delhi',
                    'badge' => 'Capital City',
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
                    'badge' => 'Cyberabad',
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
                    'badge' => 'Oxford of the East',
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
                    'badge' => 'Financial Capital',
                    'is_tech_park' => false,
                    'items' => [
                        'Andheri East', 'Andheri West', 'Powai', 'Bandra', 'BKC', 'Thane', 'Vashi', 'Navi Mumbai',
                        'Goregaon', 'Malad', 'Borivali', 'Ghatkopar', 'Kanjurmarg', 'Airoli Mindspace', 'Mahape Millennium Business Park'
                    ]
                ]
            ];
        @endphp

        <!-- SEO Matrix Collapsible Accordion -->
        <div class="py-6 border-b border-slate-800/90 w-full">
            <details class="group w-full bg-slate-900/50 hover:bg-slate-900/80 border border-slate-800/90 rounded-2xl p-5 transition-all duration-300" id="explorePgsAccordion">
                <summary class="flex items-center justify-between cursor-pointer list-none select-none">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand/15 border border-brand/30 text-brand flex items-center justify-center text-sm shadow-xs">
                            <i class="fas fa-compass"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white tracking-tight flex items-center gap-2">
                                <span>Explore 500+ Localities &amp; Tech Parks Across India</span>
                                <!-- <span class="bg-brand/15 text-brand border border-brand/30 text-[10px] font-bold px-2 py-0.2 rounded-full">SEO DIRECTORY</span> -->
                            </div>
                            <p class="text-[11px] text-slate-400">Discover verified PGs near colleges, corporate business hubs &amp; metro stations</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 bg-slate-800/80 hover:bg-brand/15 border border-slate-700/80 hover:border-brand/40 text-slate-300 hover:text-white px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all">
                        <span class="group-open:hidden">Browse Directory</span>
                        <span class="hidden group-open:inline">Collapse Directory</span>
                        <i class="fas fa-chevron-down text-[10px] text-brand transition-transform duration-300 group-open:rotate-180"></i>
                    </div>
                </summary>

                <div class="pt-6 mt-4 border-t border-slate-800/90 space-y-4 text-xs">
                    @foreach($seoFooterMatrix as $matrixRow)
                        <div class="bg-slate-950/50 border border-slate-850/90 rounded-xl p-3.5 flex flex-col lg:flex-row lg:items-start gap-3 hover:border-slate-750 transition-colors">
                            <div class="lg:w-64 flex-shrink-0 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $matrixRow['is_tech_park'] ? 'bg-amber-400' : 'bg-brand' }}"></span>
                                <span class="font-bold text-white text-xs">{{ $matrixRow['label'] }}</span>
                                <span class="text-[10px] text-slate-500 bg-slate-800/80 px-1.5 py-0.2 rounded border border-slate-700/60 ml-auto lg:ml-0">{{ $matrixRow['badge'] }}</span>
                            </div>
                            
                            <div class="flex-1 flex flex-wrap gap-1.5 text-slate-400 leading-relaxed">
                                @foreach($matrixRow['items'] as $itemIdx => $itemName)
                                    @php
                                        $itemSlug = \Illuminate\Support\Str::slug($itemName);
                                        if ($matrixRow['is_tech_park']) {
                                            $targetUrl = route('user.search', ['city' => $matrixRow['city'], 'q' => $itemName]);
                                        } else {
                                            $targetUrl = route('user.seo.city-area', ['city' => $matrixRow['city'], 'area' => $itemSlug]);
                                        }
                                    @endphp
                                    <a href="{{ $targetUrl }}" class="text-slate-400 hover:text-brand hover:bg-brand/10 hover:border-brand/30 border border-transparent px-2 py-0.5 rounded text-[11px] transition-all" title="PG in {{ $itemName }}">
                                        {{ $itemName }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </details>
        </div>

        <!-- ================= BOTTOM COPYRIGHT, LEGAL & BACK TO TOP BAR ================= -->
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400">
            <div class="flex items-center gap-3">
                <span>&copy; {{ date('Y') }} StayNest Technologies Pvt. Ltd. All rights reserved.</span>
                <span class="text-slate-600">•</span>
                <span class="inline-flex items-center gap-1 text-slate-400">
                    <span>Made with</span>
                    <i class="fas fa-heart text-red-500 text-[10px] animate-pulse"></i>
                    <span>for India</span>
                    <span>🇮🇳</span>
                </span>
            </div>

            <!-- Legal Links -->
            <!-- <div class="flex items-center gap-5 text-xs text-slate-400">
                <a href="{{ route('user.terms') }}" class="hover:text-white hover:underline transition">Terms of Use</a>
                <span>•</span>
                <a href="{{ route('user.privacy') }}" class="hover:text-white hover:underline transition">Privacy &amp; Cookies</a>
                <span>•</span>
                <a href="{{ route('user.contact') }}" class="hover:text-white hover:underline transition">Support Center</a>
                <span>•</span>
                <a href="{{ route('sitemap') }}" class="hover:text-white hover:underline transition">HTML/XML Sitemap</a>
            </div> -->

            <!-- Back to Top Button -->
            <div>
                <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="inline-flex items-center gap-2 bg-slate-800/90 hover:bg-brand text-slate-300 hover:text-white border border-slate-700/80 hover:border-brand px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 shadow-xs cursor-pointer active:scale-95 group" title="Scroll to top of page">
                    <span>Back to Top</span>
                    <i class="fas fa-arrow-up text-[10px] group-hover:-translate-y-0.5 transition-transform"></i>
                </button>
            </div>
        </div>
    </div>
</footer>

<script>
    function handleFooterNewsletter(e) {
        e.preventDefault();
        const input = document.getElementById('footerNewsletterEmail');
        const btn = document.getElementById('footerNewsletterBtn');
        if (!input || !input.value) return;

        const email = input.value.trim();
        const originalHtml = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> <span>Subscribing...</span>';

        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check text-xs"></i> <span>Subscribed!</span>';
            btn.classList.remove('bg-brand', 'hover:bg-brand-dark');
            btn.classList.add('bg-emerald-600', 'text-white');
            input.value = '';
            
            // Show alert or toast if available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Subscribed Successfully!',
                    text: 'You will receive newly verified PG listings and exclusive discount alerts.',
                    timer: 3000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl shadow-xl' }
                });
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                btn.classList.add('bg-brand', 'hover:bg-brand-dark');
                btn.classList.remove('bg-emerald-600');
            }, 4000);
        }, 800);
    }
</script>
