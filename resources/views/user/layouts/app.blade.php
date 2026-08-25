<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon & Web Manifest -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'StayNest - Best Paying Guest & Co-Living PG Discovery Network in India')</title>
    <meta name="description" content="@yield('meta_description', 'Discover 100% verified PGs, hostels, and co-living spaces across India with zero brokerage. Explore amenities, high-speed WiFi, food options, and instant direct host booking on StayNest.')">
    <meta name="keywords" content="@yield('meta_keywords', 'PG near me, Paying Guest, Co-living spaces, Boys PG, Girls PG, Luxury Hostels, Bangalore PG, Noida PG, Delhi PG, StayNest')">
    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="author" content="StayNest Technologies">
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    <meta name="revisit-after" content="2 days">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:locale" content="en_IN">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'StayNest - Find Your Perfect PG')">
    <meta property="og:description" content="@yield('meta_description', 'Discover 100% verified PGs, hostels, and co-living spaces across India with zero brokerage on StayNest.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="StayNest">
    <meta property="og:image" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta property="og:image:secure_url" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="StayNest - Verified PG & Co-Living Spaces">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'StayNest - Find Your Perfect PG')">
    <meta name="twitter:description" content="@yield('meta_description', 'Discover 100% verified PGs, hostels, and co-living spaces across India with zero brokerage on StayNest.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta name="twitter:site" content="@StayNestIndia">
    <meta name="twitter:creator" content="@StayNestIndia">


    <!-- Mobile Theme Color -->
    <meta name="theme-color" content="#4bb59d">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="StayNest">

    <!-- Dynamic Schema.org JSON-LD Structured Data -->
    @stack('schema')
    
    <!-- Performance Resource Preconnects & DNS Prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://images.unsplash.com">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        (function(){
            var _w = console.warn;
            console.warn = function(){
                if (arguments[0] && typeof arguments[0] === 'string' && arguments[0].indexOf('cdn.tailwindcss.com') !== -1) return;
                _w.apply(console, arguments);
            };
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { DEFAULT: '#4bb59d', light: '#e6f7f3', dark: '#3a9a85', 50: '#f0fdf9', 100: '#ccf0e8' },
                        primary: { DEFAULT: '#1a1a7f', light: '#eef2ff', dark: '#23239c' }
                    }
                }
            }
        }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .tap-effect { transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
        .tap-effect:active { transform: scale(0.95); }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); }
        .pt-safe { padding-top: env(safe-area-inset-top, 20px); }
        .gradient-text { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-4px); }
        .ios-header { background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border-bottom: 0.5px solid rgba(0, 0, 0, 0.08); }
        .ios-tab-bar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border-top: 0.5px solid rgba(0, 0, 0, 0.08); box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.05); }
        .center-action-btn { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); box-shadow: 0 4px 16px rgba(75, 181, 157, 0.4), 0 0 0 4px rgba(255, 255, 255, 0.9); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .center-action-btn:active { transform: scale(0.92); box-shadow: 0 2px 10px rgba(75, 181, 157, 0.4), 0 0 0 4px rgba(255, 255, 255, 0.9); }
        
        /* Mobile Input Auto-Zoom Prevent (iOS Safari Fix) */
        @media screen and (max-width: 767px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }

        /* Native App Skeleton Shimmer */
        .skeleton-shimmer {
            background: linear-gradient(90deg, #f0f3f6 25%, #e2e8f0 37%, #f0f3f6 63%);
            background-size: 400% 100%;
            animation: shimmer 1.4s ease infinite;
        }
        @keyframes shimmer {
            0% { background-position: 100% 50%; }
            100% { background-position: 0 50%; }
        }

        /* Native App Horizontal Scroll Snap */
        .app-scroll-snap {
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .app-scroll-snap > * {
            scroll-snap-align: start;
        }

        @media (max-width: 768px) { 
            body { 
                overflow-y: auto; 
                -webkit-overflow-scrolling: touch; 
                overscroll-behavior-y: contain;
            } 
        }
    </style>
    
    @stack('styles')
</head>
@php
    $isLoginPage = request()->routeIs('user.login') || request()->is('login');
@endphp

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">
    @if(!$isLoginPage)
        @include('user.partials.header')
    @endif

    <main class="flex-1 flex flex-col {{ $isLoginPage ? '' : 'pt-[65px] md:pt-0' }}">
        @yield('content')
    </main>

    @if(!$isLoginPage)
        @include('user.partials.footer')
        @include('user.partials.floating-actions')
        @include('user.partials.wishlist-helper')

        <!-- PWA Native App Install Toast / Banner (Mobile Only) -->
        <div id="pwaInstallBanner" class="fixed bottom-20 left-4 right-4 z-[9999] hidden md:hidden">
            <div class="bg-gray-900/95 backdrop-blur-xl border border-white/15 text-white p-4 rounded-2xl shadow-2xl flex items-center justify-between gap-3 transform transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white text-lg font-bold shadow-md flex-shrink-0">
                        <i class="fas fa-home"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold leading-tight">Install StayNest App</h4>
                        <p class="text-[11px] text-gray-300">Fast, zero-brokerage PG search on your phone</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button id="pwaInstallBtn" type="button" class="bg-brand hover:bg-brand-dark text-white font-bold text-xs px-3.5 py-2 rounded-xl tap-effect shadow-md">
                        Install
                    </button>
                    <button onclick="dismissPwaBanner()" type="button" class="text-gray-400 hover:text-white p-1 tap-effect" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Native App Helpers: Haptic Touch, Native Share & PWA Service Worker -->
    <script>
        // 1. Global Haptic Vibration Feedback Helper
        window.triggerHaptic = function(ms = 12) {
            if ('vibrate' in navigator) {
                try { navigator.vibrate(ms); } catch(e){}
            }
        };

        // Attach Haptic touch listener on interactive elements
        document.addEventListener('touchstart', function(e) {
            const target = e.target.closest('.tap-effect, .center-action-btn, button, a, input, select');
            if (target) {
                window.triggerHaptic(10);
            }
        }, { passive: true });

        // 2. Global Native Web Share Sheet Helper
        window.nativeShare = function(title, text, url) {
            window.triggerHaptic(15);
            const shareUrl = url || window.location.href;
            if (navigator.share) {
                navigator.share({
                    title: title || 'StayNest - Verified PG & Co-Living',
                    text: text || 'Check out this verified PG stay on StayNest with zero brokerage!',
                    url: shareUrl
                }).catch(() => {});
            } else if (navigator.clipboard) {
                navigator.clipboard.writeText(shareUrl).then(() => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Link copied to clipboard!',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                });
            }
        };

        // 3. PWA Service Worker Registration & App Install Prompt
        let deferredPrompt = null;
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            if (window.innerWidth < 768) {
                e.preventDefault();
                deferredPrompt = e;
                const dismissed = localStorage.getItem('staynest_pwa_dismissed');
                if (!dismissed) {
                    const banner = document.getElementById('pwaInstallBanner');
                    if (banner) banner.classList.remove('hidden');
                }
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const installBtn = document.getElementById('pwaInstallBtn');
            if (installBtn) {
                installBtn.addEventListener('click', async () => {
                    window.triggerHaptic(20);
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        const { outcome } = await deferredPrompt.userChoice;
                        deferredPrompt = null;
                        dismissPwaBanner();
                    }
                });
            }
        });

        window.dismissPwaBanner = function() {
            window.triggerHaptic(10);
            const banner = document.getElementById('pwaInstallBanner');
            if (banner) banner.classList.add('hidden');
            localStorage.setItem('staynest_pwa_dismissed', 'true');
        };
    </script>

    @stack('scripts')
</body>
</html>
