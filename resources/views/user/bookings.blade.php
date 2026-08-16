<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>My Bookings - StayNest</title>
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
            <h1 class="text-lg font-bold text-gray-900">My Bookings</h1>
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
                    <a href="{{ route('user.bookings') }}" class="w-11 h-11 rounded-full bg-brand-light text-brand flex items-center justify-center font-bold transition relative"><i class="fas fa-bell"></i></a>
                    <a href="{{ route('user.login') }}" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
                    <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">List Property</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Mobile Content -->
        <div class="md:hidden pt-[100px] pb-24 px-4">
            <div class="flex gap-2 mb-6 overflow-x-auto no-scrollbar" id="mobileBookingTabs">
                <button onclick="filterUserBookings(this, 'UPCOMING')" class="tab-btn px-4 py-2 bg-brand text-white text-xs font-semibold rounded-full whitespace-nowrap tap-effect">Upcoming (2)</button>
                <button onclick="filterUserBookings(this, 'COMPLETED')" class="tab-btn px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-medium rounded-full whitespace-nowrap tap-effect">Completed (5)</button>
                <button onclick="filterUserBookings(this, 'CANCELLED')" class="tab-btn px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-medium rounded-full whitespace-nowrap tap-effect">Cancelled (1)</button>
            </div>

            <div class="space-y-4" id="userBookingMobileList">
                <!-- Booking Card 1 -->
                <div class="user-bk-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100" data-status="UPCOMING">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Sunrise Premium PG</h3>
                            <p class="text-xs text-gray-500 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand text-[10px]"></i> Sector 62, Noida</p>
                        </div>
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-lg">CONFIRMED</span>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div><div class="text-[10px] text-gray-500 uppercase font-semibold">Check-in</div><div class="text-sm font-bold text-gray-900">Aug 20, 2026</div></div>
                            <div><div class="text-[10px] text-gray-500 uppercase font-semibold">Room Type</div><div class="text-sm font-bold text-gray-900">Twin Sharing</div></div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="text-sm font-bold text-gray-900">₹17,000 <span class="text-xs font-normal text-gray-500">paid</span></div>
                            <div class="flex gap-2">
                                <a href="{{ route('user.location') }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg tap-effect">Directions</a>
                                <a href="https://wa.me/919876543210" target="_blank" class="px-3 py-1.5 bg-brand text-white text-xs font-semibold rounded-lg tap-effect">Contact Host</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Card 2 -->
                <div class="user-bk-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100" data-status="UPCOMING">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Urban Nest Co-living</h3>
                            <p class="text-xs text-gray-500 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand text-[10px]"></i> HSR Layout, Bangalore</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2.5 py-1 rounded-lg">PENDING</span>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div><div class="text-[10px] text-gray-500 uppercase font-semibold">Check-in</div><div class="text-sm font-bold text-gray-900">Sep 15, 2026</div></div>
                            <div><div class="text-[10px] text-gray-500 uppercase font-semibold">Room Type</div><div class="text-sm font-bold text-gray-900">Single Room</div></div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="text-sm font-bold text-gray-900">₹12,500 <span class="text-xs font-normal text-gray-500">pending</span></div>
                            <div class="flex gap-2">
                                <button onclick="alert('Proceeding to Razorpay checkout...')" class="px-3 py-1.5 bg-brand text-white text-xs font-semibold rounded-lg tap-effect">Pay Online</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Content -->
        <div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">My Bookings</h1>
            <div class="flex gap-4 mb-8 border-b border-gray-200" id="desktopBookingTabs">
                <button onclick="filterUserBookings(this, 'UPCOMING')" class="tab-btn px-6 py-3 text-brand font-semibold border-b-2 border-brand -mb-px">Upcoming (2)</button>
                <button onclick="filterUserBookings(this, 'COMPLETED')" class="tab-btn px-6 py-3 text-gray-500 font-medium hover:text-gray-700 transition">Completed (5)</button>
                <button onclick="filterUserBookings(this, 'CANCELLED')" class="tab-btn px-6 py-3 text-gray-500 font-medium hover:text-gray-700 transition">Cancelled (1)</button>
            </div>

            <div class="space-y-6" id="userBookingDesktopList">
                <div class="user-bk-card bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center gap-6" data-status="UPCOMING">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-32 h-32 rounded-2xl object-cover">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Sunrise Premium PG</h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand"></i> Sector 62, Noida, Uttar Pradesh</p>
                            </div>
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-xl">CONFIRMED</span>
                        </div>
                        <div class="grid grid-cols-4 gap-6 my-4">
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Check-in</div><div class="text-sm font-bold text-gray-900">Aug 20, 2026</div></div>
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Room Type</div><div class="text-sm font-bold text-gray-900">Twin Sharing</div></div>
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Duration</div><div class="text-sm font-bold text-gray-900">11 Months</div></div>
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Total Paid</div><div class="text-sm font-bold text-brand">₹17,000</div></div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('user.location') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl tap-effect hover:bg-gray-200 transition"><i class="fas fa-directions mr-1"></i> Directions Map</a>
                            <a href="https://wa.me/919876543210" target="_blank" class="px-5 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl tap-effect hover:shadow-lg hover:shadow-brand/30 transition"><i class="fab fa-whatsapp mr-1"></i> Contact Host</a>
                            <button onclick="alert('Downloading Invoice #SN-8392014.pdf')" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl tap-effect hover:bg-gray-50 transition"><i class="fas fa-file-invoice mr-1"></i> Invoice</button>
                        </div>
                    </div>
                </div>

                <div class="user-bk-card bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-center gap-6" data-status="UPCOMING">
                    <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-32 h-32 rounded-2xl object-cover">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Urban Nest Co-living</h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand"></i> HSR Layout, Bangalore, Karnataka</p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1.5 rounded-xl">PENDING PAYMENT</span>
                        </div>
                        <div class="grid grid-cols-4 gap-6 my-4">
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Check-in</div><div class="text-sm font-bold text-gray-900">Sep 15, 2026</div></div>
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Room Type</div><div class="text-sm font-bold text-gray-900">Single Room</div></div>
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Duration</div><div class="text-sm font-bold text-gray-900">6 Months</div></div>
                            <div><div class="text-xs text-gray-500 uppercase font-semibold mb-1">Amount Due</div><div class="text-sm font-bold text-orange-500">₹12,500</div></div>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="alert('Proceeding to Razorpay payment gateway...')" class="px-5 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl tap-effect hover:shadow-lg hover:shadow-brand/30 transition"><i class="fas fa-lock mr-1"></i> Pay Now (₹12,500)</button>
                            <button onclick="alert('Booking cancellation initiated.');" class="px-5 py-2.5 bg-white border border-red-200 text-red-600 text-sm font-semibold rounded-xl tap-effect hover:bg-red-50 transition">Cancel Booking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('user.partials.footer')

    <script>
        function filterUserBookings(btn, status) {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-brand', 'text-white', 'text-brand', 'border-b-2', 'border-brand', '-mb-px', 'font-semibold');
                b.classList.add('text-gray-500', 'bg-white', 'font-medium');
            });
            btn.classList.add('text-brand', 'font-semibold');
            if (window.innerWidth >= 768) {
                btn.classList.add('border-b-2', 'border-brand', '-mb-px');
            } else {
                btn.classList.add('bg-brand', 'text-white');
                btn.classList.remove('text-brand', 'bg-white');
            }
        }
    </script>
</body>
</html>
