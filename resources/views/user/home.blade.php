@extends('user.layouts.app')

@section('title', 'StayNest - Find Your Perfect Stay')

@section('content')
    <!-- Mobile Content -->
    <div class="md:hidden pt-[110px] pb-24">

        <!-- Mobile Promo Slider -->
        <div class="px-4 mb-4">
            <div class="swiper promoSwiper rounded-2xl overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl p-5 text-white relative overflow-hidden min-h-[180px]">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-3">Super Savings!</h3>
                                    <div class="inline-block bg-blue-400/30 backdrop-blur-sm rounded-xl px-4 py-2 mb-3 border border-white/20">
                                        <div class="text-[10px] font-medium opacity-90">Up to</div>
                                        <div class="text-3xl font-bold leading-none">80% <span class="text-sm font-semibold">OFF</span></div>
                                    </div>
                                    <p class="text-sm opacity-90 mb-4">on first 3 bookings</p>
                                    <button class="bg-gray-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg tap-effect hover:bg-gray-800 transition">BOOK NOW</button>
                                </div>
                                <div class="flex items-center justify-center w-28 h-28">
                                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Promo" class="w-24 h-24 object-cover rounded-full border-4 border-white/30 shadow-xl">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-2xl p-5 text-white relative overflow-hidden min-h-[180px]">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-3">Premium PGs!</h3>
                                    <div class="inline-block bg-purple-400/30 backdrop-blur-sm rounded-xl px-4 py-2 mb-3 border border-white/20">
                                        <div class="text-[10px] font-medium opacity-90">Starting at</div>
                                        <div class="text-3xl font-bold leading-none">₹4,999 <span class="text-sm font-semibold">/mo</span></div>
                                    </div>
                                    <p class="text-sm opacity-90 mb-4">Fully furnished rooms</p>
                                    <button class="bg-gray-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg tap-effect hover:bg-gray-800 transition">EXPLORE NOW</button>
                                </div>
                                <div class="flex items-center justify-center w-28 h-28">
                                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Promo" class="w-24 h-24 object-cover rounded-full border-4 border-white/30 shadow-xl">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="bg-gradient-to-br from-brand via-brand-dark to-teal-700 rounded-2xl p-5 text-white relative overflow-hidden min-h-[180px]">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-3">Zero Brokerage!</h3>
                                    <div class="inline-block bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 mb-3 border border-white/20">
                                        <div class="text-[10px] font-medium opacity-90">Save up to</div>
                                        <div class="text-3xl font-bold leading-none">₹15,000 <span class="text-sm font-semibold">/year</span></div>
                                    </div>
                                    <p class="text-sm opacity-90 mb-4">Direct from owners</p>
                                    <button class="bg-gray-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg tap-effect hover:bg-gray-800 transition">VIEW ALL</button>
                                </div>
                                <div class="flex items-center justify-center w-28 h-28">
                                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Promo" class="w-24 h-24 object-cover rounded-full border-4 border-white/30 shadow-xl">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- Mobile Category Tabs -->
        <div class="px-4 mb-6">
            <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                <button class="flex flex-col items-center gap-1.5 min-w-[72px] tap-effect">
                    <div class="w-14 h-14 bg-brand rounded-2xl flex items-center justify-center text-white shadow-lg shadow-brand/30">
                        <i class="fas fa-th-large text-xl"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-brand">All</span>
                </button>
                <button class="flex flex-col items-center gap-1.5 min-w-[72px] tap-effect">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 border border-blue-100">
                        <i class="fas fa-male text-xl"></i>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600">Boys</span>
                </button>
                <button class="flex flex-col items-center gap-1.5 min-w-[72px] tap-effect">
                    <div class="w-14 h-14 bg-pink-50 rounded-2xl flex items-center justify-center text-pink-500 border border-pink-100">
                        <i class="fas fa-female text-xl"></i>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600">Girls</span>
                </button>
                <button class="flex flex-col items-center gap-1.5 min-w-[72px] tap-effect">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 border border-purple-100">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600">Co-living</span>
                </button>
                <button class="flex flex-col items-center gap-1.5 min-w-[72px] tap-effect">
                    <div class="w-14 h-14 bg-cyan-50 rounded-2xl flex items-center justify-center text-cyan-500 border border-cyan-100">
                        <i class="fas fa-snowflake text-xl"></i>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600">AC</span>
                </button>
                <button class="flex flex-col items-center gap-1.5 min-w-[72px] tap-effect">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 border border-orange-100">
                        <i class="fas fa-utensils text-xl"></i>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600">Meals</span>
                </button>
                <button class="flex flex-col items-center gap-1.5 min-w-[72px] tap-effect">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500 border border-indigo-100">
                        <i class="fas fa-wifi text-xl"></i>
                    </div>
                    <span class="text-[11px] font-medium text-gray-600">WiFi</span>
                </button>
            </div>
        </div>

        <!-- PG NEAR ME Section -->
        <section class="mb-6">
            <div class="px-4 mb-3 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-brand-light rounded-lg flex items-center justify-center">
                        <i class="fas fa-location-crosshairs text-brand text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">PG Near Me</h2>
                        <p class="text-[10px] text-gray-500">Within 2 km of your location</p>
                    </div>
                </div>
                <a href="#" class="text-brand font-semibold text-xs">View All →</a>
            </div>

            <div class="flex gap-3 overflow-x-auto no-scrollbar px-4 pb-2">
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Sunrise Premium PG', 'location' => 'Saket, New Delhi', 'price' => '₹8,500', 'distance' => '0.3 km', 'badge' => 'Verified', 'badgeClass' => 'bg-green-500', 'badgeIcon' => 'check-circle', 'rating' => '4.8'],
                    ['image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Aura Women\'s Stay', 'location' => 'Saket, New Delhi', 'price' => '₹9,999', 'distance' => '0.8 km', 'badge' => 'Verified', 'badgeClass' => 'bg-green-500', 'badgeIcon' => 'check-circle', 'rating' => '4.9'],
                    ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Urban Nest Co-living', 'location' => 'Hauz Khas, Delhi', 'price' => '₹11,500', 'distance' => '1.2 km', 'badge' => 'Popular', 'badgeClass' => 'bg-orange-500', 'badgeIcon' => 'fire', 'rating' => '4.7'],
                    ['image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Green Valley PG', 'location' => 'Malviya Nagar', 'price' => '₹7,200', 'distance' => '1.5 km', 'badge' => 'Verified', 'badgeClass' => 'bg-green-500', 'badgeIcon' => 'check-circle', 'rating' => '4.6'],
                ] as $card)
                    <div class="min-w-[240px] bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 tap-effect">
                        <div class="relative h-32">
                            <img src="{{ $card['image'] }}" alt="PG" class="w-full h-full object-cover">
                            <div class="absolute top-2 left-2 {{ $card['badgeClass'] }} text-white text-[9px] font-bold px-2 py-1 rounded-md flex items-center gap-1">
                                <i class="fas fa-{{ $card['badgeIcon'] }}"></i> {{ $card['badge'] }}
                            </div>
                            <div class="absolute top-2 right-2 bg-brand text-white text-[9px] font-bold px-2 py-1 rounded-md flex items-center gap-1">
                                <i class="fas fa-route"></i> {{ $card['distance'] }}
                            </div>
                        </div>
                        <div class="p-3">
                            <h3 class="font-bold text-sm text-gray-900 truncate">{{ $card['title'] }}</h3>
                            <p class="text-[10px] text-gray-500 mb-2 truncate">{{ $card['location'] }}</p>
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-bold text-gray-900">{{ $card['price'] }}<span class="text-[9px] font-normal text-gray-500">/mo</span></div>
                                <div class="flex items-center gap-1 text-[10px]">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span class="font-semibold">{{ $card['rating'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- RECENTLY ADDED Section -->
        <section class="mb-6">
            <div class="px-4 mb-3 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-500 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Recently Added</h2>
                        <p class="text-[10px] text-gray-500">Fresh listings this week</p>
                    </div>
                </div>
                <a href="#" class="text-brand font-semibold text-xs">View All →</a>
            </div>

            <div class="flex gap-3 overflow-x-auto no-scrollbar px-4 pb-2">
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Metro View PG', 'location' => 'Near Saket Metro', 'price' => '₹6,500', 'since' => '2h ago', 'rating' => '4.5'],
                    ['image' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Skyline Residency', 'location' => 'Vasant Kunj', 'price' => '₹9,200', 'since' => '5h ago', 'rating' => '4.7'],
                    ['image' => 'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Royal Stay PG', 'location' => 'Chattarpur', 'price' => '₹7,800', 'since' => '1d ago', 'rating' => '4.4'],
                    ['image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80', 'title' => 'Comfort Inn PG', 'location' => 'Mehrauli', 'price' => '₹8,100', 'since' => '1d ago', 'rating' => '4.6'],
                ] as $card)
                    <div class="min-w-[240px] bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 tap-effect relative">
                        <div class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-bold px-2 py-1 rounded-md flex items-center gap-1 pulse-new z-10">
                            <i class="fas fa-bolt"></i> NEW
                        </div>
                        <div class="relative h-32">
                            <img src="{{ $card['image'] }}" alt="PG" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2 bg-black/60 backdrop-blur text-white text-[9px] px-2 py-1 rounded-md flex items-center gap-1">
                                <i class="fas fa-clock"></i> {{ $card['since'] }}
                            </div>
                        </div>
                        <div class="p-3">
                            <h3 class="font-bold text-sm text-gray-900 truncate">{{ $card['title'] }}</h3>
                            <p class="text-[10px] text-gray-500 mb-2 truncate">{{ $card['location'] }}</p>
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-bold text-gray-900">{{ $card['price'] }}<span class="text-[9px] font-normal text-gray-500">/mo</span></div>
                                <div class="flex items-center gap-1 text-[10px]">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span class="font-semibold">{{ $card['rating'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Mobile Promo Banner -->
        <div class="px-4 mb-6">
            <div class="bg-gradient-to-r from-brand-light to-brand-50 rounded-2xl p-4 border border-brand-100">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-bold text-brand uppercase tracking-wider mb-1">🎉 SPECIAL OFFER</div>
                        <h3 class="font-bold text-gray-900 text-sm mb-1">Get 15% off on first booking</h3>
                        <p class="text-xs text-gray-600 mb-2">Use code: WELCOME15</p>
                        <button class="bg-brand text-white text-xs font-semibold px-4 py-2 rounded-lg tap-effect">Claim Now</button>
                    </div>
                    <div class="w-20 h-20 bg-brand/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-gift text-3xl text-brand"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended for You Section -->
        <section class="px-4 mb-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Recommended for You</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Handpicked stays near you</p>
                </div>
                <a href="#" class="text-brand font-semibold text-xs">View All →</a>
            </div>

            <div class="space-y-4">
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Sunrise Premium PG', 'type' => 'BOYS', 'typeClass' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 62, Noida • 1.2 km', 'price' => '₹8,500', 'rating' => '4.8', 'reviews' => '120', 'badge' => 'Verified', 'badgeClass' => 'bg-green-500', 'badgeIcon' => 'check-circle'],
                    ['image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Aura Women\'s Stay', 'type' => 'GIRLS', 'typeClass' => 'bg-pink-50 text-pink-600', 'location' => 'Indiranagar, Bangalore • 0.5 km', 'price' => '₹9,999', 'rating' => '4.9', 'reviews' => '98', 'badge' => 'Verified', 'badgeClass' => 'bg-green-500', 'badgeIcon' => 'check-circle'],
                    ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Urban Nest Co-living', 'type' => 'CO-ED', 'typeClass' => 'bg-purple-50 text-purple-600', 'location' => 'HSR Layout, Bangalore • 2.1 km', 'price' => '₹11,500', 'rating' => '4.7', 'reviews' => '75', 'badge' => 'Popular', 'badgeClass' => 'bg-orange-500', 'badgeIcon' => 'fire'],
                ] as $card)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 tap-effect">
                        <div class="relative h-44">
                            <img src="{{ $card['image'] }}" alt="PG" class="w-full h-full object-cover">
                            <div class="absolute top-3 left-3 {{ $card['badgeClass'] }} text-white text-[10px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1">
                                <i class="fas fa-{{ $card['badgeIcon'] }}"></i> {{ $card['badge'] }}
                            </div>
                            <button class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 tap-effect">
                                <i class="far fa-heart"></i>
                            </button>
                            <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-[10px] px-2.5 py-1.5 rounded-lg flex items-center gap-1.5">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="font-bold">{{ $card['rating'] }}</span>
                                <span class="text-gray-300">({{ $card['reviews'] }})</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-1.5">
                                <h3 class="font-bold text-gray-900">{{ $card['title'] }}</h3>
                                <span class="{{ $card['typeClass'] }} text-[10px] font-bold px-2 py-0.5 rounded">{{ $card['type'] }}</span>
                            </div>
                            <p class="text-gray-500 text-xs mb-3 flex items-center gap-1">
                                <i class="fas fa-map-marker-alt text-brand text-[10px]"></i>
                                {{ $card['location'] }}
                            </p>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <span class="text-[10px] text-gray-500">Starts from</span>
                                    <div class="text-base font-bold text-gray-900">{{ $card['price'] }}<span class="text-[10px] font-normal text-gray-500">/mo</span></div>
                                </div>
                                <button class="bg-brand text-white text-xs font-semibold px-4 py-2 rounded-lg tap-effect">View</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Why Choose StayNest -->
        <section class="px-4 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Why Choose StayNest?</h2>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <div class="w-10 h-10 bg-brand-light rounded-xl flex items-center justify-center text-brand mb-3">
                        <i class="fas fa-shield-alt text-lg"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">Verified</h3>
                    <p class="text-gray-600 text-xs">All properties verified</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 mb-3">
                        <i class="fas fa-headset text-lg"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">24/7 Support</h3>
                    <p class="text-gray-600 text-xs">Always here to help</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 mb-3">
                        <i class="fas fa-tag text-lg"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">Best Price</h3>
                    <p class="text-gray-600 text-xs">Unbeatable prices</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-100">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 mb-3">
                        <i class="fas fa-calendar-check text-lg"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">Easy Booking</h3>
                    <p class="text-gray-600 text-xs">Just a few clicks</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Desktop Content -->
    <div class="hidden md:block">
        <!-- Desktop Hero Slider -->
        <section class="mb-12">
            <div class="swiper heroSwiper max-w-7xl mx-auto px-6">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="relative rounded-3xl overflow-hidden h-[500px]">
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" alt="Modern PG" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
                            <div class="absolute inset-0 flex items-center px-16">
                                <div class="max-w-2xl text-white">
                                    <div class="flex items-center gap-2 text-brand-light font-semibold mb-4 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full w-fit">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Verified PGs with Top Amenities</span>
                                    </div>
                                    <h1 class="text-5xl font-bold mb-4 leading-tight">Find Your <span class="text-brand-light">Perfect Stay</span></h1>
                                    <p class="text-lg mb-8 text-gray-200">Discover verified PGs, hostels & co-living spaces that feel like home.</p>
                                    <a href="#" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white px-8 py-3.5 rounded-xl font-semibold transition tap-effect shadow-lg shadow-brand/30">
                                        Explore Now <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="relative rounded-3xl overflow-hidden h-[500px]">
                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" alt="Co-living" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
                            <div class="absolute inset-0 flex items-center px-16">
                                <div class="max-w-2xl text-white">
                                    <div class="flex items-center gap-2 text-brand-light font-semibold mb-4 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full w-fit">
                                        <i class="fas fa-star"></i>
                                        <span>Premium Co-living Spaces</span>
                                    </div>
                                    <h1 class="text-5xl font-bold mb-4 leading-tight">Live <span class="text-brand-light">Better Together</span></h1>
                                    <p class="text-lg mb-8 text-gray-200">Experience modern co-living with amazing communities and world-class amenities.</p>
                                    <a href="#" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white px-8 py-3.5 rounded-xl font-semibold transition tap-effect shadow-lg shadow-brand/30">
                                        View Spaces <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="relative rounded-3xl overflow-hidden h-[500px]">
                            <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" alt="Affordable PG" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
                            <div class="absolute inset-0 flex items-center px-16">
                                <div class="max-w-2xl text-white">
                                    <div class="flex items-center gap-2 text-brand-light font-semibold mb-4 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full w-fit">
                                        <i class="fas fa-tag"></i>
                                        <span>Zero Brokerage PGs</span>
                                    </div>
                                    <h1 class="text-5xl font-bold mb-4 leading-tight">Save More, <span class="text-brand-light">Live Better</span></h1>
                                    <p class="text-lg mb-8 text-gray-200">Get 15% off on your first month's rent. No hidden charges, no brokerage fees.</p>
                                    <a href="#" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white px-8 py-3.5 rounded-xl font-semibold transition tap-effect shadow-lg shadow-brand/30">
                                        Claim Offer <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>

        <!-- Desktop Search Form -->
        <section class="max-w-7xl mx-auto px-6 mb-12">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <div class="grid grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Location</label>
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20 transition-all">
                            <i class="fas fa-map-marker-alt text-brand text-lg"></i>
                            <input type="text" placeholder="Enter location" class="bg-transparent w-full text-sm focus:outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Budget</label>
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20 transition-all">
                            <i class="fas fa-wallet text-brand text-lg"></i>
                            <input type="text" placeholder="₹0 - ₹20,000" class="bg-transparent w-full text-sm focus:outline-none font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Gender</label>
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20 transition-all">
                            <i class="fas fa-user text-brand text-lg"></i>
                            <select class="bg-transparent w-full text-sm focus:outline-none font-medium appearance-none cursor-pointer">
                                <option>Any</option>
                                <option>Boys</option>
                                <option>Girls</option>
                                <option>Co-living</option>
                            </select>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Move-in Date</label>
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20 transition-all">
                            <i class="fas fa-calendar text-brand text-lg"></i>
                            <input type="text" placeholder="Select date" class="bg-transparent w-full text-sm focus:outline-none font-medium">
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            Search PGs
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filter Chips -->
        <div class="max-w-7xl mx-auto px-6 mb-8 overflow-x-auto no-scrollbar">
            <div class="flex gap-3">
                <button class="flex items-center gap-2 bg-brand text-white px-5 py-2.5 rounded-xl font-medium whitespace-nowrap tap-effect shadow-md shadow-brand/30">
                    <i class="fas fa-th-large"></i> All
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    <i class="fas fa-male text-blue-500"></i> Boys
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    <i class="fas fa-female text-pink-500"></i> Girls
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    <i class="fas fa-users text-purple-500"></i> Co-living
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    <i class="fas fa-snowflake text-cyan-500"></i> AC
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    <i class="fas fa-utensils text-orange-500"></i> Meals
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    <i class="fas fa-wifi text-indigo-500"></i> WiFi
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    <i class="fas fa-parking text-green-500"></i> Parking
                </button>
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium whitespace-nowrap hover:border-brand hover:text-brand transition tap-effect">
                    More <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Recommended Section -->
        <section class="max-w-7xl mx-auto px-6 mb-12">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Recommended for You</h2>
                    <p class="text-gray-500 text-sm mt-1">Handpicked stays with top amenities & great reviews</p>
                </div>
                <a href="#" class="text-brand font-semibold text-sm hover:underline flex items-center gap-2">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([
                    ['image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Sunrise Premium PG', 'type' => 'BOYS', 'typeClass' => 'bg-blue-50 text-blue-600', 'location' => 'Sector 62, Noida • 1.2 km', 'price' => '₹8,500', 'rating' => '4.8', 'reviews' => '120 Reviews', 'badge' => 'Verified', 'badgeClass' => 'bg-green-500', 'badgeIcon' => 'check-circle', 'amenities' => [['icon' => 'wifi', 'label' => 'WiFi'], ['icon' => 'utensils', 'label' => 'Food'], ['icon' => 'snowflake', 'label' => 'AC'], ['icon' => 'tshirt', 'label' => 'Laundry']]],
                    ['image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Aura Women\'s Stay', 'type' => 'GIRLS', 'typeClass' => 'bg-pink-50 text-pink-600', 'location' => 'Indiranagar, Bangalore • 0.5 km', 'price' => '₹9,999', 'rating' => '4.9', 'reviews' => '98 Reviews', 'badge' => 'Verified', 'badgeClass' => 'bg-green-500', 'badgeIcon' => 'check-circle', 'amenities' => [['icon' => 'wifi', 'label' => 'WiFi'], ['icon' => 'shield-alt', 'label' => 'Security'], ['icon' => 'broom', 'label' => 'Cleaning'], ['icon' => 'utensils', 'label' => 'Food']]],
                    ['image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'title' => 'Urban Nest Co-living', 'type' => 'CO-ED', 'typeClass' => 'bg-purple-50 text-purple-600', 'location' => 'HSR Layout, Bangalore • 2.1 km', 'price' => '₹11,500', 'rating' => '4.7', 'reviews' => '75 Reviews', 'badge' => 'Popular', 'badgeClass' => 'bg-orange-500', 'badgeIcon' => 'fire', 'amenities' => [['icon' => 'wifi', 'label' => 'WiFi'], ['icon' => 'dumbbell', 'label' => 'Gym'], ['icon' => 'gamepad', 'label' => 'Games'], ['icon' => 'parking', 'label' => 'Parking']]],
                ] as $card)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm card-hover border border-gray-100 group">
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $card['image'] }}" alt="PG" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute top-3 left-3 {{ $card['badgeClass'] }} text-white text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-lg">
                                <i class="fas fa-{{ $card['badgeIcon'] }}"></i> {{ $card['badge'] }}
                            </div>
                            <button class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition tap-effect shadow-lg">
                                <i class="far fa-heart text-lg"></i>
                            </button>
                            <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-2 rounded-lg flex items-center gap-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="font-bold">{{ $card['rating'] }}</span>
                                <span class="text-gray-300">({{ $card['reviews'] }})</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-lg text-gray-900">{{ $card['title'] }}</h3>
                                <span class="{{ $card['typeClass'] }} text-xs font-bold px-2.5 py-1 rounded-lg">{{ $card['type'] }}</span>
                            </div>
                            <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5">
                                <i class="fas fa-map-marker-alt text-brand"></i> {{ $card['location'] }}
                            </p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach ($card['amenities'] as $amenity)
                                    <span class="text-xs bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg flex items-center gap-1.5 border border-gray-100">
                                        <i class="fas fa-{{ $amenity['icon'] }} text-brand"></i> {{ $amenity['label'] }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <span class="text-xs text-gray-500">Starts from</span>
                                    <div class="text-xl font-bold text-gray-900">{{ $card['price'] }}<span class="text-sm font-normal text-gray-500">/month</span></div>
                                </div>
                                <a href="#" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-5 py-2.5 rounded-xl font-semibold transition tap-effect">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Features Section -->
        <section class="max-w-7xl mx-auto px-6 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition tap-effect group">
                    <div class="w-14 h-14 bg-brand-light rounded-2xl flex items-center justify-center text-brand mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Verified Properties</h3>
                    <p class="text-gray-600 text-sm">All properties are verified for your safety</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition tap-effect group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600 text-sm">Always here to help you anytime</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition tap-effect group">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-tag text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Best Price Guarantee</h3>
                    <p class="text-gray-600 text-sm">Find the best stays at unbeatable prices</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-xl transition tap-effect group">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Easy Booking</h3>
                    <p class="text-gray-600 text-sm">Book your perfect stay in just a few clicks</p>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex justify-between items-end mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 mb-2">Explore Top Cities</h2>
                            <p class="text-slate-500">Find properties in India's most sought-after locations</p>
                        </div>
                        <a href="search.html" class="text-brand font-semibold hover:underline flex items-center gap-1">View all cities <i class="fas fa-arrow-right text-xs"></i></a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <a href="search.html" class="group relative h-48 rounded-2xl overflow-hidden cursor-pointer card-lift">
                            <img src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-lg font-bold mb-1">Bangalore</h3>
                                <div class="flex items-center gap-2 text-sm text-white/80"><i class="fas fa-home"></i> 2,400+ PGs</div>
                            </div>
                        </a>
                        <a href="search.html" class="group relative h-48 rounded-2xl overflow-hidden cursor-pointer card-lift">
                            <img src="https://images.unsplash.com/photo-1587474260584-136574528ed5?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-lg font-bold mb-1">Delhi NCR</h3>
                                <div class="flex items-center gap-2 text-sm text-white/80"><i class="fas fa-home"></i> 1,800+ PGs</div>
                            </div>
                        </a>
                        <a href="search.html" class="group relative h-48 rounded-2xl overflow-hidden cursor-pointer card-lift">
                            <img src="https://images.unsplash.com/photo-1570168007204-dfb528c6958f?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-lg font-bold mb-1">Mumbai</h3>
                                <div class="flex items-center gap-2 text-sm text-white/80"><i class="fas fa-home"></i> 1,500+ PGs</div>
                            </div>
                        </a>
                        <a href="search.html" class="group relative h-48 rounded-2xl overflow-hidden cursor-pointer card-lift">
                            <img src="https://images.unsplash.com/photo-1572435555646-7ad9a149ad91?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=600&amp;q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-lg font-bold mb-1">Hyderabad</h3>
                                <div class="flex items-center gap-2 text-sm text-white/80"><i class="fas fa-home"></i> 1,200+ PGs</div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
            
        <!-- App Download Section -->
        <section class="max-w-7xl mx-auto px-6 mb-12">
            <div class="bg-gradient-to-br from-brand-50 to-white rounded-3xl p-12 border border-brand-100 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-96 h-96 bg-brand/5 rounded-full -mr-48 -mt-48 blur-3xl"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div class="max-w-xl">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Get the StayNest App</h2>
                        <p class="text-gray-600 mb-8 text-lg">Search, shortlist & book your favourite stay on the go.</p>
                        <div class="flex gap-4">
                            <button class="bg-gray-900 text-white px-6 py-3.5 rounded-xl flex items-center gap-3 hover:bg-gray-800 transition tap-effect shadow-lg">
                                <i class="fab fa-google-play text-3xl"></i>
                                <div class="text-left">
                                    <div class="text-[10px] uppercase tracking-wider">GET IT ON</div>
                                    <div class="text-base font-bold">Google Play</div>
                                </div>
                            </button>
                            <button class="bg-gray-900 text-white px-6 py-3.5 rounded-xl flex items-center gap-3 hover:bg-gray-800 transition tap-effect shadow-lg">
                                <i class="fab fa-apple text-3xl"></i>
                                <div class="text-left">
                                    <div class="text-[10px] uppercase tracking-wider">Download on the</div>
                                    <div class="text-base font-bold">App Store</div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="bg-white p-4 rounded-2xl shadow-xl">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=StayNestApp" alt="QR Code" class="w-32 h-32">
                        </div>
                        <img src="https://images.unsplash.com/photo-1512941937669-90a1b68c812f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Mobile app" class="w-48 h-96 object-cover rounded-3xl shadow-2xl">
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
