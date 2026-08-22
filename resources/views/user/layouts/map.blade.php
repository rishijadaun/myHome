<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Explore PGs Near You on Interactive Map | StayNest')</title>
    <meta name="description" content="@yield('meta_description', 'Discover verified paying guest (PG) accommodations, hostels, and co-living spaces on an interactive GPS map. Filter by budget, gender, amenities, and get instant directions with zero brokerage.')">
    <meta name="keywords" content="@yield('meta_keywords', 'PG on map, find PG near me, interactive PG map, PG locator India, hostel map, student housing near me, StayNest map')">
    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="author" content="StayNest Technologies">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:locale" content="en_IN">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'Explore PGs Near You on Interactive Map | StayNest')">
    <meta property="og:description" content="@yield('meta_description', 'Discover verified paying guest (PG) accommodations, hostels, and co-living spaces on an interactive GPS map with zero brokerage.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="StayNest">
    <meta property="og:image" content="@yield('meta_image', asset('images/favicon.png'))">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Explore PGs Near You on Interactive Map | StayNest')">
    <meta name="twitter:description" content="@yield('meta_description', 'Discover verified paying guest (PG) accommodations, hostels, and co-living spaces on an interactive GPS map with zero brokerage.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/favicon.png'))">
    <meta name="twitter:site" content="@StayNestIndia">

    <!-- Mobile Theme Color -->
    <meta name="theme-color" content="#4bb59d">
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
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://unpkg.com">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'] },
                    colors: {
                        brand: { DEFAULT: '#4bb59d', light: '#e6f7f3', dark: '#3a9a85', 50: '#f0fdf9', 100: '#ccf0e8' },
                        primary: { DEFAULT: '#1a1a7f', light: '#eef2ff', dark: '#23239c' }
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/map.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased h-screen flex flex-col overflow-hidden">
    @yield('content')
    @stack('scripts')
</body>
</html>
