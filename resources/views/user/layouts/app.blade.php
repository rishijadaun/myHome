<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon & Web Manifest -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4bb59d">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SpaceSeeks">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'SpaceSeeks - Best Paying Guest & Co-Living PG Discovery Network in India')</title>
    <meta name="description" content="@yield('meta_description', 'Discover 100% verified PGs, hostels, and co-living spaces across India with zero brokerage. Explore amenities, high-speed WiFi, food options, and instant direct host booking on SpaceSeeks.')">
    <meta name="keywords" content="@yield('meta_keywords', 'PG near me, Paying Guest, Co-living spaces, Boys PG, Girls PG, Luxury Hostels, Bangalore PG, Noida PG, Delhi PG, SpaceSeeks')">
    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="author" content="SpaceSeeks Technologies">
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    <meta name="revisit-after" content="2 days">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:locale" content="en_IN">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'SpaceSeeks - Find Your Perfect PG')">
    <meta property="og:description" content="@yield('meta_description', 'Discover 100% verified PGs, hostels, and co-living spaces across India with zero brokerage on SpaceSeeks.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="SpaceSeeks">
    <meta property="og:image" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta property="og:image:secure_url" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="SpaceSeeks - Verified PG & Co-Living Spaces">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'SpaceSeeks - Find Your Perfect PG')">
    <meta name="twitter:description" content="@yield('meta_description', 'Discover 100% verified PGs, hostels, and co-living spaces across India with zero brokerage on SpaceSeeks.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta name="twitter:site" content="@SpaceSeeksIndia">
    <meta name="twitter:creator" content="@SpaceSeeksIndia">


    <!-- Mobile Theme Color -->
    <meta name="theme-color" content="#4bb59d">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SpaceSeeks">

    <!-- Dynamic Schema.org JSON-LD Structured Data -->
    @stack('schema')
    
    <!-- Performance Resource Preconnects & DNS Prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://images.unsplash.com">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        @include('user.partials.pwa-modal')
        @include('user.roommate.partials.whatsapp-chat-modal')
        @include('user.partials.roommate-notifier')

        <!-- PWA Native App Install Toast / Banner (Mobile Only) -->
        <div id="pwaInstallBanner" class="fixed bottom-20 left-4 right-4 z-[9999] hidden md:hidden">
            <div class="bg-gray-900/95 backdrop-blur-xl border border-white/15 text-white p-4 rounded-2xl shadow-2xl flex items-center justify-between gap-3 transform transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white text-lg font-bold shadow-md flex-shrink-0">
                        <i class="fas fa-home"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold leading-tight">Install SpaceSeeks App</h4>
                        <p class="text-[11px] text-gray-300">Fast, zero-brokerage PG search on your phone</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button id="pwaInstallBtn" onclick="installPwaApp('auto')" type="button" class="bg-brand hover:bg-brand-dark text-white font-bold text-xs px-3.5 py-2 rounded-xl tap-effect shadow-md cursor-pointer">
                        Install
                    </button>
                    <button onclick="dismissPwaBanner()" type="button" class="text-gray-400 hover:text-white p-1 tap-effect cursor-pointer" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Native App Helpers: Haptic Touch, Native Share & Universal PWA App Install -->
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
                    title: title || 'SpaceSeeks - Verified PG & Co-Living',
                    text: text || 'Check out this verified PG stay on SpaceSeeks with zero brokerage!',
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

        // 3. PWA Service Worker Registration & Universal App Install Prompt
        let deferredPrompt = null;

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }

        // Capture PWA beforeinstallprompt on all devices (mobile, tablet, desktop)
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show mobile banner if not dismissed yet
            if (window.innerWidth < 768) {
                const dismissed = localStorage.getItem('staynest_pwa_dismissed');
                if (!dismissed) {
                    const banner = document.getElementById('pwaInstallBanner');
                    if (banner) banner.classList.remove('hidden');
                }
            }
        });

        // Detect if app is currently running in Standalone PWA mode
        window.isPwaStandalone = function() {
            return window.matchMedia('(display-mode: standalone)').matches || 
                   window.navigator.standalone === true || 
                   document.referrer.includes('android-app://');
        };

        // Universal Function to Trigger PWA Download / Install on Google Play & App Store Button Clicks
        window.installPwaApp = async function(platform = 'auto') {
            window.triggerHaptic(20);

            // If already installed and opened as app
            if (window.isPwaStandalone()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'App Already Installed!',
                        text: 'You are currently using the official SpaceSeeks app on your device.',
                        confirmButtonColor: '#4bb59d',
                        confirmButtonText: 'Great!'
                    });
                } else {
                    alert('SpaceSeeks is already installed on your device!');
                }
                return;
            }

            // Direct native PWA browser prompt if available
            if (deferredPrompt) {
                try {
                    deferredPrompt.prompt();
                    const choice = await deferredPrompt.userChoice;
                    if (choice.outcome === 'accepted') {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Installing SpaceSeeks App...',
                                showConfirmButton: false,
                                timer: 2500
                            });
                        }
                    }
                    deferredPrompt = null;
                    window.dismissPwaBanner();
                    window.closePwaModal();
                    return;
                } catch(err) {}
            }

            // If native prompt is not active, open custom interactive guide modal
            window.openPwaModal(platform);
        };

        // Open PWA Modal with Platform-Specific Guidance
        window.openPwaModal = function(platform = 'auto') {
            const modal = document.getElementById('pwaInstallModal');
            if (!modal) return;

            const isIos = platform === 'ios' || /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            const isAndroid = platform === 'android' || /Android/.test(navigator.userAgent);

            const directBox = document.getElementById('pwaDirectTriggerBox');
            const iosBox = document.getElementById('pwaIosGuideBox');
            const androidBox = document.getElementById('pwaAndroidFallbackBox');
            const modalBtnText = document.getElementById('pwaModalBtnText');

            if (isIos) {
                if (directBox) directBox.classList.add('hidden');
                if (iosBox) iosBox.classList.remove('hidden');
                if (androidBox) androidBox.classList.add('hidden');
            } else if (isAndroid) {
                if (directBox) directBox.classList.remove('hidden');
                if (iosBox) iosBox.classList.add('hidden');
                if (androidBox) androidBox.classList.remove('hidden');
                if (modalBtnText) modalBtnText.innerText = 'Install for Android (PWA)';
            } else {
                if (directBox) directBox.classList.remove('hidden');
                if (iosBox) iosBox.classList.add('hidden');
                if (androidBox) androidBox.classList.add('hidden');
                if (modalBtnText) modalBtnText.innerText = 'Install SpaceSeeks App';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        // Close PWA Modal
        window.closePwaModal = function() {
            window.triggerHaptic(10);
            const modal = document.getElementById('pwaInstallModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };

        // Trigger Direct Install from inside the modal
        window.triggerPwaPromptDirectly = async function() {
            window.triggerHaptic(20);
            if (deferredPrompt) {
                try {
                    deferredPrompt.prompt();
                    const choice = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    window.closePwaModal();
                    window.dismissPwaBanner();
                    return;
                } catch(e){}
            }

            // If prompt unavailable, check platform
            const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            if (isIos) {
                window.openPwaModal('ios');
            } else {
                const androidBox = document.getElementById('pwaAndroidFallbackBox');
                if (androidBox) androidBox.classList.remove('hidden');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Install via Browser Menu',
                        html: 'Tap your browser menu (<i class="fas fa-ellipsis-vertical"></i>) and choose <strong>"Install app"</strong> or <strong>"Add to Home Screen"</strong>.',
                        confirmButtonColor: '#4bb59d'
                    });
                }
            }
        };

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
