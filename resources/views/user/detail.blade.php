<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Sunrise Premium PG - StayNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { DEFAULT: '#4bb59d', light: '#e6f7f3', dark: '#3a9a85', 50: '#f0fdf9', 100: '#ccf0e8' }
                    }
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
        @media (max-width: 768px) { body { overflow-y: auto; -webkit-overflow-scrolling: touch; } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Mobile Header -->
    <header class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="px-4 py-1.5 flex justify-between items-center text-xs font-semibold text-gray-900 pt-safe">
            <span>9:41</span>
            <div class="flex items-center gap-1.5">
                <i class="fas fa-signal text-[10px]"></i>
                <i class="fas fa-wifi text-[10px]"></i>
                <div class="flex items-center gap-1">
                    <span class="text-[10px]">85%</span>
                    <i class="fas fa-battery-three-quarters text-[10px]"></i>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 flex items-center justify-between">
            <button onclick="window.history.back()" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 tap-effect">
                <i class="fas fa-arrow-left text-sm"></i>
            </button>
            <div class="flex items-center gap-2">
                <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 tap-effect">
                    <i class="fas fa-share-alt text-sm"></i>
                </button>
                <a href="{{ route('user.saved') }}" class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-red-500 tap-effect">
                    <i class="fas fa-heart text-sm"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Desktop Header -->
    <header class="hidden md:block bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-12">
                    <a href="{{ route('user.home') }}" class="flex items-center gap-2 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand/30">
                            <i class="fas fa-home"></i>
                        </div>
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
                    <a href="{{ route('user.search') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition"><i class="fas fa-search"></i></a>
                    <a href="{{ route('user.saved') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative"><i class="fas fa-heart text-red-500"></i></a>
                    <a href="{{ route('user.bookings') }}" class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative"><i class="fas fa-bell"></i><span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span></a>
                    <a href="{{ route('user.login') }}" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
                    <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">
                        List PG Free
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Mobile Content -->
        <div class="md:hidden pt-[100px] pb-28">
            <!-- Image Gallery -->
            <div class="relative h-72 mb-4">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover">
                <div class="absolute bottom-3 right-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                    <i class="fas fa-images"></i>
                    <span class="font-semibold">1/8</span>
                </div>
            </div>

            <div class="px-4 space-y-6">
                <!-- Title & Price -->
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <h1 class="text-xl font-bold text-gray-900">Sunrise Premium PG</h1>
                        <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">BOYS</span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex items-center gap-1 bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-lg">
                            <i class="fas fa-check-circle text-[10px]"></i>
                            <span>Verified</span>
                        </div>
                        <div class="flex items-center gap-1 bg-yellow-50 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                            <i class="fas fa-star text-[10px]"></i>
                            <span>4.8 (120 reviews)</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-sm text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt text-brand"></i>
                        <span>Sector 62, Noida, Uttar Pradesh</span>
                    </div>
                    <a href="{{ route('user.location') }}" class="flex items-center gap-1.5 text-sm text-brand font-semibold">
                        <i class="fas fa-route text-brand"></i>
                        <span>1.2 km from your location • Open in Map</span>
                    </a>
                </div>

                <!-- Price Card -->
                <div class="bg-gradient-to-r from-brand-light to-brand-50 rounded-2xl p-4 border border-brand-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-600">Starting from</span>
                            <div class="text-2xl font-bold text-gray-900">₹8,500<span class="text-sm font-normal text-gray-600">/month</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-600 line-through">₹10,000</div>
                            <div class="text-sm font-bold text-green-600">15% OFF</div>
                        </div>
                    </div>
                </div>

                <!-- Room Types -->
                <div>
                    <h2 class="text-base font-bold text-gray-900 mb-3">Available Rooms</h2>
                    <div class="space-y-3">
                        <div class="bg-white rounded-xl p-4 border border-gray-100 tap-effect">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900">Twin Sharing</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">2 beds • Attached bathroom</p>
                                </div>
                                <span class="text-sm font-bold text-gray-900">₹8,500<span class="text-xs font-normal text-gray-500">/mo</span></span>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100">WiFi</span>
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100">AC</span>
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100">Food</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-4 border border-gray-100 tap-effect">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900">Triple Sharing</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">3 beds • Common bathroom</p>
                                </div>
                                <span class="text-sm font-bold text-gray-900">₹6,500<span class="text-xs font-normal text-gray-500">/mo</span></span>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100">WiFi</span>
                                <span class="text-[10px] bg-gray-50 text-gray-600 px-2 py-1 rounded-md border border-gray-100">Food</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div>
                    <h2 class="text-base font-bold text-gray-900 mb-3">Amenities</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">High-Speed WiFi</div>
                                <div class="text-xs text-gray-500">100 Mbps</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Meals Included</div>
                                <div class="text-xs text-gray-500">Breakfast & Dinner</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-snowflake"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Air Conditioning</div>
                                <div class="text-xs text-gray-500">In all rooms</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Laundry Service</div>
                                <div class="text-xs text-gray-500">Weekly</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">24/7 Security</div>
                                <div class="text-xs text-gray-500">CCTV & Guards</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-broom"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Housekeeping</div>
                                <div class="text-xs text-gray-500">Daily cleaning</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- House Rules -->
                <div>
                    <h2 class="text-base font-bold text-gray-900 mb-3">House Rules</h2>
                    <div class="bg-white rounded-xl p-4 border border-gray-100 space-y-2">
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-times-circle text-red-500"></i>
                            <span class="text-gray-700">No smoking inside rooms</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-times-circle text-red-500"></i>
                            <span class="text-gray-700">No parties or events</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span class="text-gray-700">Visitors allowed till 9 PM</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span class="text-gray-700">Pets not allowed</span>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-base font-bold text-gray-900">Reviews (120)</h2>
                        <a href="#" class="text-brand text-xs font-semibold">View All</a>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-gray-100 mb-3">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Rahul Sharma</div>
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span>5.0</span>
                                    <span>•</span>
                                    <span>2 months ago</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Great place to stay! The food is amazing and the staff is very helpful. Highly recommended for working professionals.</p>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Priya Patel</div>
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span>4.5</span>
                                    <span>•</span>
                                    <span>3 months ago</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Very clean and well-maintained property. Good location near metro station. Value for money!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Content -->
        <div class="hidden md:block">
            <!-- Breadcrumb -->
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <a href="{{ route('user.home') }}" class="hover:text-brand transition">Home</a>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    <a href="{{ route('user.search') }}" class="hover:text-brand transition">Search</a>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    <span class="text-gray-900 font-medium">Sunrise Premium PG</span>
                </div>
            </div>

            <!-- Image Gallery -->
            <section class="max-w-7xl mx-auto px-6 mb-8">
                <div class="swiper detailSwiper rounded-3xl overflow-hidden shadow-lg">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" class="w-full h-[500px] object-cover">
                        </div>
                        <div class="swiper-slide">
                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" class="w-full h-[500px] object-cover">
                        </div>
                        <div class="swiper-slide">
                            <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" class="w-full h-[500px] object-cover">
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next !text-white"></div>
                    <div class="swiper-button-prev !text-white"></div>
                </div>
            </section>

            <!-- Property Details Grid -->
            <section class="max-w-7xl mx-auto px-6 mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Title -->
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Sunrise Premium PG</h1>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Verified Property</span>
                                        </div>
                                        <div class="flex items-center gap-1 bg-yellow-50 text-yellow-700 text-sm font-bold px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-star"></i>
                                            <span>4.8 (120 reviews)</span>
                                        </div>
                                        <span class="bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1.5 rounded-lg">BOYS PG</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied!');" class="w-11 h-11 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                    <a href="{{ route('user.saved') }}" class="w-11 h-11 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-red-500 transition">
                                        <i class="fas fa-heart"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt text-brand"></i>
                                <span>Sector 62, Noida, Uttar Pradesh 201309</span>
                            </div>
                            <a href="{{ route('user.location') }}" class="inline-flex items-center gap-2 text-brand font-semibold text-sm hover:underline">
                                <i class="fas fa-route text-brand"></i>
                                <span>1.2 km from your location • Open in Map Routing</span>
                            </a>
                        </div>

                        <!-- Description -->
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900 mb-3">About this Property</h2>
                            <p class="text-gray-600 leading-relaxed">Sunrise Premium PG offers comfortable and affordable accommodation for working professionals and students. Located in the heart of Sector 62, Noida, this property provides easy access to metro stations, shopping centers, and major IT hubs. With modern amenities, delicious meals, and a vibrant community, it's the perfect place to call home.</p>
                        </div>

                        <!-- Room Types -->
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Available Rooms</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">Twin Sharing</h3>
                                            <p class="text-sm text-gray-500 mt-1">2 beds • Attached bathroom</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 line-through">₹10,000</div>
                                            <div class="text-xl font-bold text-gray-900">₹8,500<span class="text-sm font-normal text-gray-500">/mo</span></div>
                                            <div class="text-xs font-bold text-green-600">15% OFF</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100">WiFi</span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100">AC</span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100">Food</span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100">Laundry</span>
                                    </div>
                                    <a href="{{ route('user.bookings') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3 rounded-xl tap-effect text-center">Book This Room</a>
                                </div>
                                <div class="bg-white rounded-2xl p-6 border border-gray-100 card-hover">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">Triple Sharing</h3>
                                            <p class="text-sm text-gray-500 mt-1">3 beds • Common bathroom</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 line-through">₹8,000</div>
                                            <div class="text-xl font-bold text-gray-900">₹6,500<span class="text-sm font-normal text-gray-500">/mo</span></div>
                                            <div class="text-xs font-bold text-green-600">18% OFF</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100">WiFi</span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100">Food</span>
                                        <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg border border-gray-100">Cleaning</span>
                                    </div>
                                    <a href="{{ route('user.bookings') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3 rounded-xl tap-effect text-center">Book This Room</a>
                                </div>
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Amenities</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-wifi text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">High-Speed WiFi</div>
                                        <div class="text-sm text-gray-500">100 Mbps</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-utensils text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Meals Included</div>
                                        <div class="text-sm text-gray-500">Breakfast & Dinner</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-snowflake text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Air Conditioning</div>
                                        <div class="text-sm text-gray-500">In all rooms</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-tshirt text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Laundry Service</div>
                                        <div class="text-sm text-gray-500">Weekly</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-shield-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">24/7 Security</div>
                                        <div class="text-sm text-gray-500">CCTV & Guards</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-broom text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Housekeeping</div>
                                        <div class="text-sm text-gray-500">Daily cleaning</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- House Rules -->
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">House Rules</h2>
                            <div class="bg-white rounded-2xl p-6 border border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                        <span class="text-gray-700">No smoking inside rooms</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                        <span class="text-gray-700">No parties or events</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                        <span class="text-gray-700">Visitors allowed till 9 PM</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                        <span class="text-gray-700">Pets not allowed</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                        <span class="text-gray-700">ID proof required</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                        <span class="text-gray-700">6 months minimum stay</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold text-gray-900">Reviews (120)</h2>
                                <button class="text-brand font-semibold text-sm hover:underline">View All Reviews</button>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                                    <div class="flex items-start gap-4 mb-3">
                                        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-12 h-12 rounded-full object-cover">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="font-semibold text-gray-900">Rahul Sharma</div>
                                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                        <span>5.0</span>
                                                        <span>•</span>
                                                        <span>2 months ago</span>
                                                    </div>
                                                </div>
                                                <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded-lg font-semibold">Verified Stay</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600">Great place to stay! The food is amazing and the staff is very helpful. Highly recommended for working professionals. The location is perfect with easy access to metro and markets.</p>
                                </div>
                                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                                    <div class="flex items-start gap-4 mb-3">
                                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-12 h-12 rounded-full object-cover">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="font-semibold text-gray-900">Priya Patel</div>
                                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                        <span>4.5</span>
                                                        <span>•</span>
                                                        <span>3 months ago</span>
                                                    </div>
                                                </div>
                                                <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded-lg font-semibold">Verified Stay</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600">Very clean and well-maintained property. Good location near metro station. Value for money! The WiFi speed is excellent for remote work.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-24 space-y-4">
                            <!-- Price Card -->
                            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-lg">
                                <div class="mb-4">
                                    <span class="text-sm text-gray-500">Starting from</span>
                                    <div class="text-3xl font-bold text-gray-900">₹8,500<span class="text-base font-normal text-gray-500">/month</span></div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-sm text-gray-500 line-through">₹10,000</span>
                                        <span class="text-sm font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded">15% OFF</span>
                                    </div>
                                </div>
                                <a href="{{ route('user.bookings') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect mb-3 text-center">
                                    Book Now
                                </a>
                                <a href="https://wa.me/919876543210" target="_blank" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                    <i class="fab fa-whatsapp text-green-600"></i>
                                    Contact Owner
                                </a>
                                <div class="mt-4 pt-4 border-t border-gray-100 space-y-2 text-sm">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-check-circle text-brand"></i>
                                        <span>Free cancellation within 24h</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-check-circle text-brand"></i>
                                        <span>Zero brokerage fee</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-check-circle text-brand"></i>
                                        <span>Move-in within 48 hours</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Owner Info -->
                            <div class="bg-white rounded-2xl p-6 border border-gray-100">
                                <h3 class="font-bold text-gray-900 mb-3">Property Owner</h3>
                                <div class="flex items-center gap-3 mb-4">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-12 h-12 rounded-full object-cover">
                                    <div>
                                        <div class="font-semibold text-gray-900">Vikram Singh</div>
                                        <div class="text-xs text-gray-500">Verified Owner • 5 properties</div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="https://wa.me/919876543210" target="_blank" class="flex-1 bg-brand text-white font-semibold py-2.5 rounded-lg tap-effect text-sm text-center">
                                        <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                                    </a>
                                    <a href="tel:+919876543210" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-lg tap-effect text-sm text-center">
                                        <i class="fas fa-phone mr-1"></i> Call
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    @include('user.partials.footer')

    <!-- Mobile Fixed Bottom Action Bar -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 pb-safe">
        <div class="px-4 py-3 flex items-center gap-3">
            <div class="flex-1">
                <div class="text-xs text-gray-500">Starting from</div>
                <div class="text-lg font-bold text-gray-900">₹8,500<span class="text-sm font-normal text-gray-500">/mo</span></div>
            </div>
            <a href="{{ route('user.bookings') }}" class="flex-1 bg-brand text-white font-semibold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 text-center">
                Book Now
            </a>
            <a href="https://wa.me/919876543210" target="_blank" class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 tap-effect">
                <i class="fab fa-whatsapp text-lg"></i>
            </a>
        </div>
    </div>

    <script>
        const detailSwiper = new Swiper('.detailSwiper', {
            loop: true,
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });
    </script>
</body>
</html>
