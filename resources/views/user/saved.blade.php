<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Saved Properties - StayNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { brand: { DEFAULT: '#4bb59d', light: '#e6f7f3', dark: '#3a9a85', 50: '#f0fdf9', 100: '#ccf0e8' } } } } }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; } .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .tap-effect { transition: all 0.2s; } .tap-effect:active { transform: scale(0.96); }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); } .pt-safe { padding-top: env(safe-area-inset-top, 20px); }
        .gradient-text { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); } .card-hover:hover { transform: translateY(-4px); }
        @media (max-width: 768px) { body { overflow-y: auto; -webkit-overflow-scrolling: touch; } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Mobile Header -->
    <header class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">
        <div class="px-4 py-1.5 flex justify-between items-center text-xs font-semibold text-gray-900 pt-safe">
            <span>9:41</span>
            <div class="flex items-center gap-1.5"><i class="fas fa-signal text-[10px]"></i><i class="fas fa-wifi text-[10px]"></i><div class="flex items-center gap-1"><span class="text-[10px]">85%</span><i class="fas fa-battery-three-quarters text-[10px]"></i></div></div>
        </div>
        <div class="px-4 py-3 flex items-center gap-3">
            <a href="{{ route('user.home') }}" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 tap-effect"><i class="fas fa-arrow-left text-sm"></i></a>
            <h1 class="text-lg font-bold text-gray-900">Saved Properties</h1>
        </div>
    </header>

    <!-- Desktop Header -->
    <header class="hidden md:block bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-12">
                    <a href="{{ route('user.home') }}" class="flex items-center gap-2 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand/30"><i class="fas fa-home"></i></div>
                        <span class="font-bold text-2xl text-gray-900 tracking-tight">Stay<span class="gradient-text">Nest</span></span>
                    </a>
                    <nav class="flex space-x-8">
                        <a href="{{ route('user.home') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">Home</a>
                        <a href="{{ route('user.search') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">Find PG</a>
                        <a href="{{ route('user.list-property') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">List Property</a>
                        <a href="{{ route('user.pricing') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">Pricing</a>
                        <a href="{{ route('user.about') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">About Us</a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.search') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition"><i class="fas fa-search"></i></a>
                    <a href="{{ route('user.bookings') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative"><i class="fas fa-bell"></i><span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span></a>
                    <a href="{{ route('user.login') }}" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
                    <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">List Property</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Mobile Content -->
        <div class="md:hidden pt-[100px] pb-24 px-4">
            <div class="space-y-4" id="mobileSavedList">
                <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 tap-effect relative">
                    <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 tap-effect z-10"><i class="fas fa-heart"></i></button>
                    <div class="relative h-40"><img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover"></div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-1.5"><h3 class="font-bold text-gray-900">Sunrise Premium PG</h3><span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded">BOYS</span></div>
                        <p class="text-gray-500 text-xs mb-3 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand text-[10px]"></i> Sector 62, Noida • 1.2 km</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="text-base font-bold text-gray-900">₹8,500<span class="text-[10px] font-normal text-gray-500">/mo</span></div>
                            <a href="{{ route('user.location') }}" class="bg-brand text-white text-xs font-semibold px-4 py-2 rounded-lg tap-effect">View</a>
                        </div>
                    </div>
                </div>

                <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 tap-effect relative">
                    <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 tap-effect z-10"><i class="fas fa-heart"></i></button>
                    <div class="relative h-40"><img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover"></div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-1.5"><h3 class="font-bold text-gray-900">Aura Women's Stay</h3><span class="bg-pink-50 text-pink-600 text-[10px] font-bold px-2 py-0.5 rounded">GIRLS</span></div>
                        <p class="text-gray-500 text-xs mb-3 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand text-[10px]"></i> Indiranagar, Bangalore • 0.5 km</p>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="text-base font-bold text-gray-900">₹9,999<span class="text-[10px] font-normal text-gray-500">/mo</span></div>
                            <a href="{{ route('user.location') }}" class="bg-brand text-white text-xs font-semibold px-4 py-2 rounded-lg tap-effect">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Content -->
        <div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Saved Properties</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="desktopSavedList">
                <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm card-hover border border-gray-100 group relative">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 transition tap-effect shadow-lg"><i class="fas fa-heart text-lg"></i></button>
                        <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-2 rounded-lg flex items-center gap-2"><i class="fas fa-star text-yellow-400"></i><span class="font-bold">4.8</span><span class="text-gray-300">(120 Reviews)</span></div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2"><h3 class="font-bold text-lg text-gray-900">Sunrise Premium PG</h3><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">BOYS</span></div>
                        <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-brand"></i> Sector 62, Noida • 1.2 km</p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div><span class="text-xs text-gray-500">Starts from</span><div class="text-xl font-bold text-gray-900">₹8,500<span class="text-sm font-normal text-gray-500">/month</span></div></div>
                            <a href="{{ route('user.detail') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-5 py-2.5 rounded-xl font-semibold transition tap-effect">View Details</a>
                        </div>
                    </div>
                </div>

                <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm card-hover border border-gray-100 group relative">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 transition tap-effect shadow-lg"><i class="fas fa-heart text-lg"></i></button>
                        <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-2 rounded-lg flex items-center gap-2"><i class="fas fa-star text-yellow-400"></i><span class="font-bold">4.9</span><span class="text-gray-300">(98 Reviews)</span></div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2"><h3 class="font-bold text-lg text-gray-900">Aura Women's Stay</h3><span class="bg-pink-50 text-pink-600 text-xs font-bold px-2.5 py-1 rounded-lg">GIRLS</span></div>
                        <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-brand"></i> Indiranagar, Bangalore • 0.5 km</p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div><span class="text-xs text-gray-500">Starts from</span><div class="text-xl font-bold text-gray-900">₹9,999<span class="text-sm font-normal text-gray-500">/month</span></div></div>
                            <a href="{{ route('user.detail') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-5 py-2.5 rounded-xl font-semibold transition tap-effect">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('user.partials.footer')

    <script>
        function removeSaved(btn) {
            const item = btn.closest('.saved-item');
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.9)';
                setTimeout(() => item.remove(), 200);
            }
        }
    </script>
</body>
</html>
