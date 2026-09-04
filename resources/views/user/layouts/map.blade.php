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
    <title>@yield('title', 'Explore PGs, Flats & Flatmates Near You on Interactive Map | StayNest')</title>
    <meta name="description" content="@yield('meta_description', 'Discover verified paying guest (PG) accommodations, flats, commercial spaces, and verified flatmates on an interactive GPS map with zero brokerage.')">
    <meta name="keywords" content="@yield('meta_keywords', 'explore near me, PG near me, flatmate near me, find PG on map, interactive PG map, flats near me, StayNest map')">
    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="author" content="StayNest Technologies">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:locale" content="en_IN">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'Explore PGs, Flats & Flatmates Near You on Interactive Map | StayNest')">
    <meta property="og:description" content="@yield('meta_description', 'Discover verified paying guest (PG) accommodations, flats, commercial spaces, and verified flatmates on an interactive GPS map with zero brokerage.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="StayNest">
    <meta property="og:image" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta property="og:image:secure_url" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/png">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Explore PGs Near You on Interactive Map | StayNest')">
    <meta name="twitter:description" content="@yield('meta_description', 'Discover verified paying guest (PG) accommodations, hostels, and co-living spaces on an interactive GPS map with zero brokerage.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/app-banner.png'))">
    <meta name="twitter:site" content="@StayNestIndia">

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
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preconnect" href="https://tile.openstreetmap.org" crossorigin>
    
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://tile.openstreetmap.org">
    <link rel="dns-prefetch" href="https://server.arcgisonline.com">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/map.css', 'resources/js/app.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        @media screen and (max-width: 767px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased h-screen flex flex-col overflow-hidden">
    @yield('content')
    @include('user.partials.pwa-modal')

    <script>
        window.triggerHaptic = function(ms = 12) {
            if ('vibrate' in navigator) {
                try { navigator.vibrate(ms); } catch(e){}
            }
        };
        document.addEventListener('touchstart', function(e) {
            const target = e.target.closest('.tap-effect, .chip-active, button, a, input');
            if (target) {
                window.triggerHaptic(10);
            }
        }, { passive: true });

        // PWA Service Worker & Prompt
        let deferredPrompt = null;
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        window.isPwaStandalone = function() {
            return window.matchMedia('(display-mode: standalone)').matches || 
                   window.navigator.standalone === true || 
                   document.referrer.includes('android-app://');
        };

        window.installPwaApp = async function(platform = 'auto') {
            window.triggerHaptic(20);
            if (window.isPwaStandalone()) {
                alert('StayNest is already installed on your device!');
                return;
            }
            if (deferredPrompt) {
                try {
                    deferredPrompt.prompt();
                    await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    window.closePwaModal();
                    return;
                } catch(err) {}
            }
            window.openPwaModal(platform);
        };

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
                if (modalBtnText) modalBtnText.innerText = 'Install StayNest App';
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closePwaModal = function() {
            window.triggerHaptic(10);
            const modal = document.getElementById('pwaInstallModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };

        window.triggerPwaPromptDirectly = async function() {
            window.triggerHaptic(20);
            if (deferredPrompt) {
                try {
                    deferredPrompt.prompt();
                    await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    window.closePwaModal();
                    return;
                } catch(e){}
            }
            const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            if (isIos) {
                window.openPwaModal('ios');
            } else {
                const androidBox = document.getElementById('pwaAndroidFallbackBox');
                if (androidBox) androidBox.classList.remove('hidden');
            }
        };
    </script>
    @stack('scripts')
</body>
</html>
