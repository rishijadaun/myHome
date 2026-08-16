<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>@yield('title', 'Map View - StayNest')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/map.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased h-screen flex flex-col overflow-hidden">
    @yield('content')
    @stack('scripts')
</body>
</html>
