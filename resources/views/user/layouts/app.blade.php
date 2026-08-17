<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>@yield('title', 'StayNest - Find Your Perfect PG')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        @media (max-width: 768px) { body { overflow-y: auto; -webkit-overflow-scrolling: touch; } }
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
    @endif
    
    @stack('scripts')
</body>
</html>
