<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Find PG & Co-living Spaces - StayNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { DEFAULT: '#4bb59d', light: '#e6f7f3', dark: '#3a9a85', 50: '#f0fdf9', 100: '#ccf0e8' } }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .tap-effect { transition: all 0.2s; }
        .tap-effect:active { transform: scale(0.96); }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); }
        .pt-safe { padding-top: env(safe-area-inset-top, 20px); }
        .gradient-text { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-4px); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Mobile Header -->
    <header class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">
        <div class="px-4 py-1.5 flex justify-between items-center text-xs font-semibold text-gray-900 pt-safe">
            <span>9:41</span>
            <div class="flex items-center gap-1.5"><i class="fas fa-signal text-[10px]"></i><i class="fas fa-wifi text-[10px]"></i><div class="flex items-center gap-1"><span class="text-[10px]">85%</span><i class="fas fa-battery-three-quarters text-[10px]"></i></div></div>
        </div>
        <div class="px-4 py-3">
            <div class="relative">
                <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-sm"></i>
                <input id="mobileSearchInput" onkeyup="filterSearchResults()" type="text" placeholder="Search by city, landmark, or PG..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-10 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
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
                        <a href="{{ route('user.search') }}" class="text-brand font-semibold border-b-2 border-brand px-1 pt-1 text-sm">Find PG</a>
                        <a href="{{ route('user.list-property') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">List Property</a>
                        <a href="{{ route('user.pricing') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">Pricing</a>
                        <a href="{{ route('user.about') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">About Us</a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.saved') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition"><i class="fas fa-heart"></i></a>
                    <a href="{{ route('user.bookings') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative"><i class="fas fa-bell"></i><span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span></a>
                    <a href="{{ route('user.login') }}" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
                    <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">List Property</a>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-[90px] md:pt-8 pb-24 md:pb-16 max-w-7xl mx-auto px-4 md:px-6">
        <!-- Desktop Search & Filter Bar -->
        <div class="bg-white rounded-3xl p-4 md:p-6 border border-gray-100 shadow-sm mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="relative md:col-span-2">
                    <i class="fas fa-map-marker-alt absolute left-4 top-3.5 text-brand text-sm"></i>
                    <input id="desktopSearchInput" onkeyup="filterSearchResults()" type="text" placeholder="Search by city (Noida, Bangalore, Delhi)..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <select id="genderSearchFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="">All Gender Types</option>
                        <option value="BOYS">Boys PG</option>
                        <option value="GIRLS">Girls PG</option>
                        <option value="CO-ED">Co-living / Co-Ed</option>
                    </select>
                </div>
                <div>
                    <select id="budgetSearchFilter" onchange="filterSearchResults()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="">Any Budget</option>
                        <option value="8000">Under ₹8,000 / mo</option>
                        <option value="10000">Under ₹10,000 / mo</option>
                        <option value="15000">Under ₹15,000 / mo</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Quick Cities Chips -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar mb-6">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Popular:</span>
            <button onclick="setCityQuery('Noida')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition">Noida</button>
            <button onclick="setCityQuery('Bangalore')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition">Bangalore</button>
            <button onclick="setCityQuery('Delhi')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition">Delhi</button>
            <button onclick="setCityQuery('Mumbai')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition">Mumbai</button>
            <button onclick="setCityQuery('Gurugram')" class="px-3.5 py-1.5 bg-white border border-gray-200 hover:border-brand text-gray-700 text-xs font-semibold rounded-full whitespace-nowrap tap-effect transition">Gurugram</button>
        </div>

        <!-- Property Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="searchGrid">
            <!-- Property 1 -->
            <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm card-hover border border-gray-100 group" data-gender="BOYS" data-price="8500" data-city="Noida">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <a href="{{ route('user.saved') }}" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 tap-effect shadow-md"><i class="fas fa-heart"></i></a>
                    <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                        <i class="fas fa-star text-yellow-400"></i><span class="font-bold">4.8</span><span>(120 reviews)</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-gray-900 prop-title">Sunrise Premium PG</h3>
                        <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">BOYS</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4 flex items-center gap-1.5 prop-loc"><i class="fas fa-map-marker-alt text-brand"></i> Sector 62, Noida • Near Metro</p>
                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-4 py-2.5 border-y border-gray-100">
                        <span><i class="fas fa-wifi text-brand mr-1"></i> WiFi</span>
                        <span><i class="fas fa-utensils text-brand mr-1"></i> Meals</span>
                        <span><i class="fas fa-snowflake text-brand mr-1"></i> AC</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-500">Monthly Rent</span>
                            <div class="text-xl font-extrabold text-gray-900">₹8,500<span class="text-xs font-normal text-gray-500">/mo</span></div>
                        </div>
                        <a href="{{ route('user.detail') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold text-sm tap-effect shadow-md shadow-brand/20">View Room</a>
                    </div>
                </div>
            </div>

            <!-- Property 2 -->
            <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm card-hover border border-gray-100 group" data-gender="GIRLS" data-price="9999" data-city="Bangalore">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <a href="{{ route('user.saved') }}" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 tap-effect shadow-md"><i class="fas fa-heart"></i></a>
                    <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                        <i class="fas fa-star text-yellow-400"></i><span class="font-bold">4.9</span><span>(98 reviews)</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-gray-900 prop-title">Aura Women's Stay</h3>
                        <span class="bg-pink-50 text-pink-600 text-xs font-bold px-2.5 py-1 rounded-lg">GIRLS</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4 flex items-center gap-1.5 prop-loc"><i class="fas fa-map-marker-alt text-brand"></i> Indiranagar, Bangalore • 0.5 km Metro</p>
                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-4 py-2.5 border-y border-gray-100">
                        <span><i class="fas fa-shield-alt text-brand mr-1"></i> Biometric</span>
                        <span><i class="fas fa-utensils text-brand mr-1"></i> Food</span>
                        <span><i class="fas fa-tshirt text-brand mr-1"></i> Laundry</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-500">Monthly Rent</span>
                            <div class="text-xl font-extrabold text-gray-900">₹9,999<span class="text-xs font-normal text-gray-500">/mo</span></div>
                        </div>
                        <a href="{{ route('user.detail') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold text-sm tap-effect shadow-md shadow-brand/20">View Room</a>
                    </div>
                </div>
            </div>

            <!-- Property 3 -->
            <div class="property-card bg-white rounded-3xl overflow-hidden shadow-sm card-hover border border-gray-100 group" data-gender="CO-ED" data-price="12500" data-city="Bangalore">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <a href="{{ route('user.saved') }}" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 tap-effect shadow-md"><i class="fas fa-heart"></i></a>
                    <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                        <i class="fas fa-star text-yellow-400"></i><span class="font-bold">4.7</span><span>(64 reviews)</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-gray-900 prop-title">Urban Nest Co-living</h3>
                        <span class="bg-purple-50 text-purple-600 text-xs font-bold px-2.5 py-1 rounded-lg">CO-ED</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-4 flex items-center gap-1.5 prop-loc"><i class="fas fa-map-marker-alt text-brand"></i> HSR Layout, Bangalore</p>
                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-4 py-2.5 border-y border-gray-100">
                        <span><i class="fas fa-dumbbell text-brand mr-1"></i> Gym</span>
                        <span><i class="fas fa-gamepad text-brand mr-1"></i> Lounge</span>
                        <span><i class="fas fa-wifi text-brand mr-1"></i> 100 Mbps</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-500">Monthly Rent</span>
                            <div class="text-xl font-extrabold text-gray-900">₹12,500<span class="text-xs font-normal text-gray-500">/mo</span></div>
                        </div>
                        <a href="{{ route('user.detail') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold text-sm tap-effect shadow-md shadow-brand/20">View Room</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('user.partials.footer')

    <script>
        function setCityQuery(city) {
            document.getElementById('desktopSearchInput').value = city;
            const mob = document.getElementById('mobileSearchInput');
            if (mob) mob.value = city;
            filterSearchResults();
        }

        function filterSearchResults() {
            const query = (document.getElementById('desktopSearchInput').value || document.getElementById('mobileSearchInput').value).toLowerCase();
            const gender = document.getElementById('genderSearchFilter').value;
            const maxBudget = document.getElementById('budgetSearchFilter').value;

            document.querySelectorAll('.property-card').forEach(el => {
                const title = el.querySelector('.prop-title').textContent.toLowerCase();
                const loc = el.querySelector('.prop-loc').textContent.toLowerCase();
                const elCity = el.getAttribute('data-city').toLowerCase();
                const elGender = el.getAttribute('data-gender');
                const elPrice = parseInt(el.getAttribute('data-price'));

                const matchQuery = title.includes(query) || loc.includes(query) || elCity.includes(query);
                const matchGender = !gender || elGender === gender;
                const matchBudget = !maxBudget || elPrice <= parseInt(maxBudget);

                if (matchQuery && matchGender && matchBudget) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
