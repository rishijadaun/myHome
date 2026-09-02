<!-- Mobile Bottom Navigation -->
@php
    $bottomNavProfileUrl = route('user.login');
    $bottomNavProfileLabel = 'Profile';
    if (Auth::check()) {
        $u = Auth::user();
        $userRole = $u->getCachedRoleSlug();
        if ($userRole === 'admin') {
            $bottomNavProfileUrl = route('admin.dashboard');
            $bottomNavProfileLabel = 'Dashboard';
        } elseif ($userRole === 'broker') {
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
        <a href="{{ $bottomNavProfileUrl }}" id="bottomNavProfileLink" class="flex flex-col items-center justify-center gap-1 tap-effect px-0.5 relative">
            <i class="fas fa-user text-lg sm:text-xl {{ (request()->routeIs('user.profile') || request()->is('profile')) ? 'text-brand' : 'text-gray-400' }}"></i>
            <span id="bottomNavProfileBadge" class="hidden absolute top-1 right-3.5 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white animate-pulse"></span>
            <span class="text-[9px] sm:text-[10px] font-medium truncate {{ (request()->routeIs('user.profile') || request()->is('profile')) ? 'text-brand font-bold' : 'text-gray-500' }}">{{ $bottomNavProfileLabel }}</span>
        </a>
    </div>
</nav>

<!-- ======================================================= -->
<!-- DESKTOP GLOBAL FOOTER — Hidden on Mobile, Shown md:block -->
<!-- ======================================================= -->
<footer class="hidden md:block bg-[#0b0f1a] text-slate-400 border-t border-white/[0.06] relative overflow-hidden">
    {{-- Subtle ambient glow decorations --}}
    <div class="pointer-events-none absolute -top-32 -left-32 w-[500px] h-[500px] bg-brand/5 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute bottom-0 right-0 w-[400px] h-[400px] bg-emerald-500/5 rounded-full blur-3xl" aria-hidden="true"></div>

    {{-- ============================================================== --}}
    {{-- TOP MINI STAT BAR (Social Proof Stripe)                        --}}
    {{-- ============================================================== --}}
    <div class="border-b border-white/[0.06] bg-white/[0.02]">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between gap-4 text-[11px]">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 text-slate-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                    <span class="font-semibold">1,200+</span>
                    <span class="text-slate-500">Verified PGs Active</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300 border-l border-white/10 pl-6">
                    <i class="fas fa-users text-brand text-[10px]"></i>
                    <span class="font-semibold">75,000+</span>
                    <span class="text-slate-500">Happy Tenants</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300 border-l border-white/10 pl-6">
                    <i class="fas fa-city text-brand text-[10px]"></i>
                    <span class="font-semibold">18+</span>
                    <span class="text-slate-500">Cities Covered</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300 border-l border-white/10 pl-6">
                    <i class="fas fa-star text-amber-400 text-[10px]"></i>
                    <span class="font-semibold">4.8/5</span>
                    <span class="text-slate-500">Avg. Rating</span>
                </div>
            </div>

            @php
                $isTenantAuth = Auth::check() && !Auth::user()->roles()->whereIn('slug', ['super_admin', 'admin', 'broker'])->exists();
            @endphp

            <div class="flex items-center gap-4">
                @if(!$isTenantAuth)
                <a href="{{ route('user.list-property') }}" id="footerListPropertyBtn" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-brand/15 hover:bg-brand/25 text-brand border border-brand/30 hover:border-brand/50 font-semibold transition-all text-[11px]">
                    <i class="fas fa-plus text-[9px]"></i>
                    List Property Free
                </a>
                <a href="{{ route('broker.login') }}" id="footerBrokerLoginBtn" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/10 hover:border-white/20 font-semibold transition-all text-[11px]">
                    <i class="fas fa-briefcase text-[9px]"></i>
                    Owner / Broker Login
                </a>
                @else
                <a href="{{ route('user.roommate.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-brand/15 hover:bg-brand/25 text-brand border border-brand/30 hover:border-brand/50 font-semibold transition-all text-[11px]">
                    <i class="fas fa-door-open text-[9px]"></i>
                    Find Flatmate / Post Roommate
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- MAIN FOOTER BODY — 5 Column Grid                               --}}
    {{-- ============================================================== --}}
    <div class="max-w-7xl mx-auto px-6 pt-12 pb-10 relative z-10">
        <div class="grid grid-cols-12 gap-10">

            {{-- COL 1-4: Brand Identity --}}
            <div class="col-span-4 space-y-6">
                {{-- Logo --}}
                <a href="{{ route('user.home') }}" class="inline-flex items-center gap-3 group" title="StayNest Home">
                    <div class="w-11 h-11 bg-gradient-to-br from-brand to-brand-dark rounded-2xl flex items-center justify-center text-white shadow-lg shadow-brand/30 group-hover:scale-105 transition-transform duration-200 flex-shrink-0">
                        <i class="fas fa-home text-lg"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-[22px] text-white tracking-tight leading-none block">Stay<span class="text-brand">Nest</span></span>
                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest block mt-0.5">Zero Brokerage PG Network</span>
                    </div>
                </a>

                {{-- Tagline --}}
                <p class="text-xs text-slate-400 leading-relaxed max-w-xs">
                    India's highest-rated PG & co-living discovery platform. Connecting verified tenants directly with verified homeowners — <span class="text-slate-200 font-medium">100% transparency, zero brokerage.</span>
                </p>

                {{-- 3 compact trust badges --}}
                <!-- <div class="space-y-2">
                    <div class="flex items-center gap-2.5 text-[11px] text-slate-400 bg-white/[0.04] border border-white/[0.07] rounded-xl px-3 py-2">
                        <i class="fas fa-handshake-angle text-brand text-sm flex-shrink-0"></i>
                        <span><span class="text-white font-semibold">Zero Brokerage</span> — direct owner connections</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-[11px] text-slate-400 bg-white/[0.04] border border-white/[0.07] rounded-xl px-3 py-2">
                        <i class="fas fa-shield-check text-emerald-400 text-sm flex-shrink-0"></i>
                        <span><span class="text-white font-semibold">100% Verified</span> — physical room inspections</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-[11px] text-slate-400 bg-white/[0.04] border border-white/[0.07] rounded-xl px-3 py-2">
                        <i class="fas fa-headset text-cyan-400 text-sm flex-shrink-0"></i>
                        <span><span class="text-white font-semibold">24/7 Support</span> — dedicated RM for every booking</span>
                    </div>
                </div> -->

                {{-- Social Icons --}}
                <div>
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-2.5">Follow us</p>
                    <div class="flex items-center gap-2">
                        <a href="https://facebook.com/staynest" target="_blank" rel="noopener noreferrer" title="Facebook"
                           class="w-8 h-8 rounded-lg bg-white/[0.06] hover:bg-brand border border-white/10 hover:border-brand text-slate-400 hover:text-white flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fab fa-facebook-f text-[11px]"></i>
                        </a>
                        <a href="https://instagram.com/staynest" target="_blank" rel="noopener noreferrer" title="Instagram"
                           class="w-8 h-8 rounded-lg bg-white/[0.06] hover:bg-brand border border-white/10 hover:border-brand text-slate-400 hover:text-white flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fab fa-instagram text-[11px]"></i>
                        </a>
                        <a href="https://twitter.com/staynest" target="_blank" rel="noopener noreferrer" title="Twitter / X"
                           class="w-8 h-8 rounded-lg bg-white/[0.06] hover:bg-brand border border-white/10 hover:border-brand text-slate-400 hover:text-white flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fab fa-twitter text-[11px]"></i>
                        </a>
                        <a href="https://linkedin.com/company/staynest" target="_blank" rel="noopener noreferrer" title="LinkedIn"
                           class="w-8 h-8 rounded-lg bg-white/[0.06] hover:bg-brand border border-white/10 hover:border-brand text-slate-400 hover:text-white flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fab fa-linkedin-in text-[11px]"></i>
                        </a>
                        <a href="https://youtube.com/@staynest" target="_blank" rel="noopener noreferrer" title="YouTube"
                           class="w-8 h-8 rounded-lg bg-white/[0.06] hover:bg-red-500 border border-white/10 hover:border-red-500 text-slate-400 hover:text-white flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fab fa-youtube text-[11px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- COL 5-6: Stay Types --}}
            <div class="col-span-2 space-y-4">
                <h5 class="text-[11px] font-extrabold text-white uppercase tracking-widest flex items-center gap-1.5 after:flex-1 after:h-px after:bg-white/10 after:ml-2">
                    <i class="fas fa-bed text-brand"></i>
                    Stay Types
                </h5>
                <ul class="space-y-2.5">
                    @foreach([
                        ['Boys PG Hostels',      route('user.search', ['gender' => 'male'])],
                        ['Girls PG Hostels',     route('user.search', ['gender' => 'female'])],
                        ['Co-Living Spaces',     route('user.search', ['gender' => 'unisex'])],
                        ['Private Rooms',        route('user.search', ['type' => 'private'])],
                        ['Double Sharing PGs',   route('user.search', ['sharing' => 'double'])],
                        ['PGs with Food',        route('user.search', ['food' => 'included'])],
                        ['Luxury PG Living',     route('user.search', ['luxury' => 1])],
                    ] as [$label, $url])
                    <li>
                        <a href="{{ $url }}" class="group text-[12px] text-slate-400 hover:text-white transition-colors duration-150 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                    {{-- Roommate Finder — new feature link --}}
                    <!-- <li class="pt-1">
                        <a href="{{ route('user.roommate.index') }}" class="group text-[12px] text-brand/80 hover:text-brand transition-colors duration-150 flex items-center gap-2 font-semibold">
                            <span class="w-1 h-1 rounded-full bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            🤝 Find Roommate / Flatmate
                            <span class="text-[9px] font-bold bg-brand/15 border border-brand/30 text-brand px-1.5 py-0.5 rounded">NEW</span>
                        </a>
                    </li> -->
                </ul>
            </div>

            {{-- COL 7-8: Popular Cities --}}
            <div class="col-span-2 space-y-4">
                <h5 class="text-[11px] font-extrabold text-white uppercase tracking-widest flex items-center gap-1.5 after:flex-1 after:h-px after:bg-white/10 after:ml-2">
                    <i class="fas fa-location-dot text-brand"></i>
                    Popular Cities
                </h5>
                <ul class="space-y-2.5">
                    @foreach([
                        ['PG in Gurgaon',    route('user.seo.city-area', ['city' => 'gurgaon'])],
                        ['PG in Bangalore',  route('user.seo.city-area', ['city' => 'bangalore'])],
                        ['PG in Noida',      route('user.seo.city-area', ['city' => 'noida'])],
                        ['PG in Delhi NCR',  route('user.seo.city-area', ['city' => 'delhi'])],
                        ['PG in Hyderabad',  route('user.seo.city-area', ['city' => 'hyderabad'])],
                        ['PG in Pune',       route('user.seo.city-area', ['city' => 'pune'])],
                        ['PG in Mumbai',     route('user.seo.city-area', ['city' => 'mumbai'])],
                        ['PG in Lucknow',    route('user.seo.city-area', ['city' => 'lucknow'])],
                    ] as [$label, $url])
                    <li>
                        <a href="{{ $url }}" class="group text-[12px] text-slate-400 hover:text-white transition-colors duration-150 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            @if(!$isTenantAuth)
            {{-- COL 9-10: For Owners (Guests & Non-Tenants) --}}
            <div class="col-span-2 space-y-4">
                <h5 class="text-[11px] font-extrabold text-white uppercase tracking-widest flex items-center gap-1.5 after:flex-1 after:h-px after:bg-white/10 after:ml-2">
                    <i class="fas fa-building-user text-brand"></i>
                    For Owners
                </h5>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('user.list-property') }}" class="group text-[12px] text-brand hover:text-white transition-colors duration-150 flex items-center gap-2 font-semibold">
                            <span class="w-1 h-1 rounded-full bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            List Property
                            <span class="text-[9px] font-bold bg-brand/20 border border-brand/40 text-brand px-1.5 py-0.5 rounded">FREE</span>
                        </a>
                    </li>
                    @foreach([
                        ['Broker / Owner Login', route('broker.login')],
                        ['Pricing & Plans',      route('user.pricing')],
                        ['Saved Properties',     route('user.saved')],
                        ['Host RM Support',      route('user.contact')],
                    ] as [$label, $url])
                    <li>
                        <a href="{{ $url }}" class="group text-[12px] text-slate-400 hover:text-white transition-colors duration-150 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            {{-- COL 9-10: For Tenants (Tenant Specific Quick Links) --}}
            <div class="col-span-2 space-y-4">
                <h5 class="text-[11px] font-extrabold text-white uppercase tracking-widest flex items-center gap-1.5 after:flex-1 after:h-px after:bg-white/10 after:ml-2">
                    <i class="fas fa-users text-brand"></i>
                    Tenant Hub
                </h5>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('user.roommate.index') }}" class="group text-[12px] text-brand hover:text-white transition-colors duration-150 flex items-center gap-2 font-semibold">
                            <span class="w-1 h-1 rounded-full bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            Find Flatmates
                            <span class="text-[9px] font-bold bg-brand/20 border border-brand/40 text-brand px-1.5 py-0.5 rounded">NEW</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('broker.login') }}" class="group text-[12px] text-slate-300 hover:text-white transition-colors duration-150 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                           Broker Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.bookings') }}" class="group text-[12px] text-slate-300 hover:text-white transition-colors duration-150 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            My Bookings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.saved') }}" class="group text-[12px] text-slate-300 hover:text-white transition-colors duration-150 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            Saved Properties
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            {{-- COL 11-12: Company & Contact --}}
            <div class="col-span-2 space-y-4">
                <h5 class="text-[11px] font-extrabold text-white uppercase tracking-widest flex items-center gap-1.5 after:flex-1 after:h-px after:bg-white/10 after:ml-2">
                    <i class="fas fa-circle-info text-brand"></i>
                    Company
                </h5>
                <ul class="space-y-2.5">
                    @foreach([
                        ['About StayNest',   route('user.about')],
                        ['Contact Us',       route('user.contact')],
                        ['Terms of Service', route('user.terms')],
                        ['Privacy Policy',   route('user.privacy')],
                        ['XML Sitemap',      route('sitemap')],
                    ] as [$label, $url])
                    <li>
                        <a href="{{ $url }}" class="group text-[12px] text-slate-400 hover:text-white transition-colors duration-150 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand group-hover:scale-125 transition-all flex-shrink-0"></span>
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>

                {{-- Contact Quick CTA --}}
                <div class="pt-2">
                    <a href="{{ route('user.contact') }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-white/[0.06] hover:bg-brand/20 border border-white/10 hover:border-brand/40 text-slate-300 hover:text-white rounded-xl px-3 py-2.5 text-[11px] font-semibold transition-all group">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                        <i class="fas fa-comments text-brand group-hover:text-white transition-colors text-sm"></i>
                        <div class="text-left leading-tight">
                            <div class="text-[10px] text-slate-500 leading-none">Need assistance?</div>
                            <div class="text-[11px] font-bold text-slate-200">Contact Help Center</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        {{-- ============================================================== --}}
        {{-- SEO DIRECTORY ACCORDION                                         --}}
        {{-- ============================================================== --}}
        @php
            $seoFooterMatrix = [
                [
                    'label' => 'PG in Gurgaon',
                    'city' => 'gurgaon',
                    'badge' => 'Millennium City',
                    'color' => 'brand',
                    'is_tech_park' => false,
                    'items' => [
                        'Sector 29','Sector 43','Sector 20','Sector 53','Sector 44','Sector 30','Sector 18','Sector 23',
                        'Sector 48','Sector 69','Sector 38','Sector 15','Sector 40','Sector 46','Sector 55','Sector 57',
                        'Sector 17','Sector 21','Sector 22','Sector 24','Sector 25','Sector 28','Sector 31','Sector 33',
                        'Sector 45','Sector 47','Sector 49','Sector 50','Sector 56','Sector 58','DLF Phase 1','DLF Phase 2',
                        'DLF Phase 3','DLF Phase 4','Sohna Road','Golf Course Road','MG Road','Palam Vihar','Udyog Vihar',
                        'Cyber City','South City 1','South City 2','Manesar','Sector 67','Sector 14',
                    ]
                ],
                [
                    'label' => 'Business & IT Parks — Gurgaon',
                    'city' => 'gurgaon',
                    'badge' => 'Tech Hub',
                    'color' => 'amber',
                    'is_tech_park' => true,
                    'items' => [
                        'DLF Cyber City','DLF CyberHub','DLF Cyber Park','DLF Corporate Park','DLF Corporate Greens',
                        'DLF Pinnacle','DLF Gateway Tower','DLF Square','DLF Star Tower','DLF Infinity Tower A',
                        'DLF Infinity Tower B','Udyog Vihar','Golf Course Road','Sohna Road','Golf Course Ext. Road',
                        'MG Road','IFFCO Chowk','HUDA City Centre','Global Business Park','Global Foyer',
                        'One Horizon Center','Two Horizon Center','Candor TechSpace Sec 21','Candor TechSpace Sec 48',
                        'Emaar Digital Greens','Emaar Business District','Unitech Cyber Park','Vatika Business Park',
                        'Vatika Atrium','Vatika Professional Point','JMD Megapolis','JMD Regent Plaza','JMD Pacific Square',
                        'Spaze I-Tech Park','Spaze Business Park','Bestech Business Tower','Bestech Cyber Park',
                        'Orchid Business Park','Time Tower','Signature Towers','Raheja Square','Paras Trade Centre',
                        'Ansal Corporate Plaza','Vipul Tech Square','Vipul Trade Centre','M3M IFC','M3M Urbana Business Park',
                        'AIPL Business Club','Elan Town Centre','Manesar',
                    ]
                ],
                [
                    'label' => 'PG in Bangalore',
                    'city' => 'bangalore',
                    'badge' => 'Silicon Valley of India',
                    'color' => 'brand',
                    'is_tech_park' => false,
                    'items' => [
                        'Whitefield','Koramangala','HSR Layout','Electronic City','Indiranagar','JP Nagar','BTM Layout',
                        'Marathahalli','Yelahanka','Basavanagudi','SG Palya','MG Road','Suddaguntepalya','Silk Board',
                        'Hebbal','KR Puram','Mahadevapura','Kadubeesanahalli','Bellandur','Jayanagar','Sarjapur Road',
                        'Bommanahalli','Bannerghatta Road','Hennur Road','Domlur','CV Raman Nagar','Rajajinagar',
                        'Malleswaram','Vijayanagar','Yeshwanthpur','Nagawara','Thanisandra','Kogilu','Hoodi',
                        'Kadugodi','Varthur','Brookefield','Panathur','Harlur Road','Kasturi Nagar','Devanahalli',
                        'Kengeri','Banaswadi','Banashankari','Haralur','Kodihalli','Kothanur','Chikkasandra','Yerapannahalli',
                    ]
                ],
                [
                    'label' => 'Tech Parks & IT Corridors — Bangalore',
                    'city' => 'bangalore',
                    'badge' => 'IT Corridor',
                    'color' => 'amber',
                    'is_tech_park' => true,
                    'items' => [
                        'Manyata Tech Park','ITPL Whitefield','Electronic City','RMZ Ecospace','RMZ Infinity',
                        'Embassy Tech Village','Embassy GolfLinks','Embassy One','Bagmane Tech Park','Bagmane Constellation',
                        'Prestige Tech Park','Outer Ring Road','Whitefield IT Corridor','EPIP Zone Whitefield',
                        'Salarpuria Sattva Knowledge City','Brigade Tech Gardens','Divyasree Tech Park','Kalyani Tech Park',
                        'Cessna Business Park','Global Village Tech Park','Pritech Park','RGA Tech Park',
                        'Wipro Campus Sarjapur','Infosys Campus Electronic City','TCS Banyan Park','SAP Labs India',
                        'Microsoft IDC','UB City','Diamond District','Koramangala IT Hub','HSR Layout Tech Hub',
                        'Karle Town Centre','Orion Business Park','Peenya Industrial Estate','Bannerghatta Road IT Corridor',
                        'Yelahanka Aerospace Zone',
                    ]
                ],
                [
                    'label' => 'PG in Noida & Greater Noida',
                    'city' => 'noida',
                    'badge' => 'NCR Express',
                    'color' => 'brand',
                    'is_tech_park' => false,
                    'items' => [
                        'Sector 62','Sector 63','Sector 15','Sector 16','Sector 18','Sector 125','Sector 126',
                        'Sector 137','Sector 143','Sector 135','Sector 50','Sector 51','Sector 52','Sector 44',
                        'Sector 49','Sector 59','Sector 74','Sector 75','Sector 76','Sector 142','Sector 1',
                        'Sector 2','Sector 6','Sector 19','Sector 27','Sector 34','Sector 35','Sector 36',
                        'Sector 37','Sector 41','Sector 56','Sector 57','Sector 58','Sector 61','Sector 78',
                        'Sector 79','Sector 100','Sector 104','Sector 110','Sector 120','Sector 128','Sector 132',
                        'Sector 144','Sector 168','Sector 22','Knowledge Park','Pari Chowk','Alpha 1','Beta 1','Delta 1',
                    ]
                ],
                [
                    'label' => 'PG in Delhi',
                    'city' => 'delhi',
                    'badge' => 'Capital City',
                    'color' => 'brand',
                    'is_tech_park' => false,
                    'items' => [
                        'North Campus','South Campus','Saket','Hauz Khas','Laxmi Nagar','Karol Bagh','Mukherjee Nagar',
                        'GTB Nagar','Dwarka','Janakpuri','Pitampura','Rohini','Patel Nagar','Lajpat Nagar',
                        'Malviya Nagar','Okhla','Kalkaji','Munirka','Vijay Nagar','Kamla Nagar','Connaught Place',
                    ]
                ],
                [
                    'label' => 'PG & Tech Hubs — Hyderabad',
                    'city' => 'hyderabad',
                    'badge' => 'Cyberabad',
                    'color' => 'brand',
                    'is_tech_park' => false,
                    'items' => [
                        'Hitec City','Gachibowli','Madhapur','Kondapur','Kukatpally','Banjara Hills','Jubilee Hills',
                        'Financial District','Manikonda','Ameerpet','Begumpet','SR Nagar','Miyapur','Dilsukhnagar',
                        'Raheja Mindspace','DLF Cyber City Hyd','Cyber Towers',
                    ]
                ],
                [
                    'label' => 'PG & Tech Parks — Pune',
                    'city' => 'pune',
                    'badge' => 'Oxford of the East',
                    'color' => 'brand',
                    'is_tech_park' => false,
                    'items' => [
                        'Hinjewadi IT Park','Viman Nagar','Kharadi EON Free Zone','Baner','Wakad','Kothrud',
                        'Hadapsar','Magarpatta City','Aundh','Senapati Bapat Road','Shivaji Nagar','Pimple Saudagar',
                        'Bavdhan','Koregaon Park','Kalyani Nagar',
                    ]
                ],
                [
                    'label' => 'PG in Mumbai & Navi Mumbai',
                    'city' => 'mumbai',
                    'badge' => 'Financial Capital',
                    'color' => 'brand',
                    'is_tech_park' => false,
                    'items' => [
                        'Andheri East','Andheri West','Powai','Bandra','BKC','Thane','Vashi','Navi Mumbai',
                        'Goregaon','Malad','Borivali','Ghatkopar','Kanjurmarg','Airoli Mindspace','Mahape Millennium Business Park',
                    ]
                ],
            ];
        @endphp

        <div class="mt-10 border-t border-white/[0.06]">
            <details class="group" id="footerSeoDirectory">
                {{-- Accordion Header --}}
                <summary class="flex items-center justify-between gap-4 py-5 cursor-pointer list-none select-none">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-brand/15 border border-brand/25 text-brand flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fas fa-compass"></i>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white">Explore 500+ Localities &amp; Tech Parks Across India</span>
                            <span class="text-[11px] text-slate-500 ml-2">PGs near colleges, corporate hubs &amp; metro stations</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-400 group-hover:text-white border border-white/10 group-hover:border-white/20 px-3 py-1.5 rounded-lg transition-all bg-white/[0.04] group-hover:bg-white/[0.08] flex-shrink-0">
                        <span class="group-open:hidden">View Directory</span>
                        <span class="hidden group-open:inline">Hide Directory</span>
                        <i class="fas fa-chevron-down text-[9px] text-brand transition-transform duration-300 group-open:rotate-180"></i>
                    </div>
                </summary>

                {{-- Accordion Body --}}
                <div class="pb-6 space-y-3 text-xs border-t border-white/[0.06] pt-5">
                    @foreach($seoFooterMatrix as $row)
                        <div class="flex items-start gap-4 py-3 border-b border-white/[0.04] last:border-b-0">
                            {{-- Row Label --}}
                            <div class="w-56 flex-shrink-0 pt-0.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $row['is_tech_park'] ? 'bg-amber-400' : 'bg-brand' }}"></span>
                                    <span class="font-bold text-[11px] text-white">{{ $row['label'] }}</span>
                                </div>
                                <span class="text-[10px] text-slate-600 mt-0.5 block pl-3">{{ $row['badge'] }}</span>
                            </div>

                            {{-- Row Items --}}
                            <div class="flex-1 flex flex-wrap gap-1">
                                @foreach($row['items'] as $itemName)
                                    @php
                                        $itemSlug = \Illuminate\Support\Str::slug($itemName);
                                        $targetUrl = $row['is_tech_park']
                                            ? route('user.search', ['city' => $row['city'], 'q' => $itemName])
                                            : route('user.seo.city-area', ['city' => $row['city'], 'area' => $itemSlug]);
                                    @endphp
                                    <a href="{{ $targetUrl }}"
                                       class="text-[11px] text-slate-500 hover:text-white hover:bg-white/[0.07] border border-transparent hover:border-white/10 px-2 py-0.5 rounded transition-all duration-100"
                                       title="PG in {{ $itemName }}">{{ $itemName }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </details>
        </div>

        {{-- ============================================================== --}}
        {{-- BOTTOM BAR — Copyright, Legal, Back to Top                     --}}
        {{-- ============================================================== --}}
        <div class="border-t border-white/[0.06] pt-6 flex items-center justify-between gap-4">
            {{-- Left: Copyright + made with --}}
            <div class="flex items-center gap-3 text-[11px] text-slate-500 flex-wrap">
                <span>&copy; {{ date('Y') }} StayNest Technologies Pvt. Ltd. All rights reserved.</span>
                <span class="text-white/10">|</span>
                <span class="inline-flex items-center gap-1.5">
                    Made with <i class="fas fa-heart text-red-500 text-[9px] animate-pulse"></i> for India <span>🇮🇳</span>
                </span>
            </div>

            {{-- Center: Legal Links --}}
            <!-- <div class="flex items-center gap-4 text-[11px] text-slate-500">
                <a href="{{ route('user.terms') }}" class="hover:text-slate-200 transition-colors">Terms</a>
                <span class="text-white/10">·</span>
                <a href="{{ route('user.privacy') }}" class="hover:text-slate-200 transition-colors">Privacy</a>
                <span class="text-white/10">·</span>
                <a href="{{ route('user.contact') }}" class="hover:text-slate-200 transition-colors">Contact</a>
                <span class="text-white/10">·</span>
                <a href="{{ route('sitemap') }}" class="hover:text-slate-200 transition-colors">Sitemap</a>
            </div> -->

            {{-- Right: Back to Top --}}
            <button type="button"
                    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 hover:text-white border border-white/10 hover:border-white/25 bg-white/[0.04] hover:bg-white/[0.08] px-3 py-1.5 rounded-lg transition-all cursor-pointer group"
                    title="Back to top">
                <i class="fas fa-arrow-up text-[9px] group-hover:-translate-y-0.5 transition-transform text-brand"></i>
                Back to Top
            </button>
        </div>
    </div>
</footer>
