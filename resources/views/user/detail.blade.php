@extends('user.layouts.app')

@php
    $propName = $property->name ?? 'Sunrise Premium PG';
    $propTagMeta = $property ? $property->display_tag_meta : ['label' => 'Verified', 'icon' => 'check-circle', 'solid_badge' => 'bg-emerald-500 text-white'];
    $propGenderMeta = $property ? $property->gender_type_meta : ['label' => 'BOYS', 'class' => 'bg-blue-50 text-blue-600', 'btn_class' => 'bg-blue-600 text-white'];
    $propRent = $property ? number_format($property->monthly_rent) : '8,500';
    $propLocation = $property ? (($property->address ?: ($property->area->name ?? '')) . ', ' . ($property->city->name ?? 'Noida')) : 'Sector 62, Noida, Uttar Pradesh';
    $propRating = $property && $property->rating ? number_format($property->rating, 1) : '4.8';
    $propReviews = $property && $property->total_reviews ? $property->total_reviews : '120';
    $propImages = ($property && $property->images->count() > 0) ? $property->images : collect([(object)['image_url' => $property->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80']]);
    $propOwner = $property->broker ?? null;
    $ownerName = $propOwner->name ?? 'Vikram Singh';
    $ownerPhone = $propOwner->phone ?? '919876543210';
    $cleanPhone = preg_replace('/[^0-9]/', '', $ownerPhone);
@endphp

@section('title', $propName . ' - StayNest')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
@endpush

@section('content')
    <!-- Mobile Content -->
    <div class="md:hidden pt-[70px] pb-28">
        <!-- Image Gallery Slider -->
        <div class="relative h-72 mb-4">
            <div class="swiper mobileDetailSwiper h-full">
                <div class="swiper-wrapper">
                    @foreach($propImages as $img)
                        <div class="swiper-slide h-full">
                            <img src="{{ $img->image_url }}" class="w-full h-full object-cover" alt="{{ $propName }}">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="absolute top-3 left-3 z-10 {{ $propTagMeta['solid_badge'] }} text-white text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1 shadow-md">
                <i class="fas fa-{{ $propTagMeta['icon'] }} text-[9px]"></i> {{ $propTagMeta['label'] }}
            </div>
            <div class="absolute bottom-3 right-3 z-10 bg-black/70 backdrop-blur text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                <i class="fas fa-images"></i>
                <span class="font-semibold">{{ $propImages->count() }} Photos</span>
            </div>
        </div>

        <div class="px-4 space-y-6">
            <!-- Title & Price -->
            <div>
                <div class="flex justify-between items-start mb-2">
                    <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $propName }}</h1>
                    <span class="{{ $propGenderMeta['class'] }} text-xs font-bold px-2.5 py-1 rounded-lg flex-shrink-0 ml-2">{{ $propGenderMeta['label'] }}</span>
                </div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex items-center gap-1 bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-lg">
                        <i class="fas fa-check-circle text-[10px]"></i>
                        <span>Verified</span>
                    </div>
                    <div class="flex items-center gap-1 bg-yellow-50 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                        <i class="fas fa-star text-yellow-500 text-[10px]"></i>
                        <span>{{ $propRating }} ({{ $propReviews }} reviews)</span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-sm text-gray-600 mb-2">
                    <i class="fas fa-map-marker-alt text-brand"></i>
                    <span>{{ $propLocation }}</span>
                </div>
                @if($property && $property->latitude && $property->longitude)
                    <div class="flex items-center gap-1.5 text-sm text-brand font-semibold">
                        <i class="fas fa-route text-brand"></i>
                        <span>GPS Coordinates: {{ number_format($property->latitude, 3) }}, {{ number_format($property->longitude, 3) }}</span>
                    </div>
                @endif
            </div>

            <!-- Price Card -->
            <div class="bg-gradient-to-r from-brand-light to-brand-50 rounded-2xl p-4 border border-brand-100">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-600">Starting from</span>
                        <div class="text-2xl font-bold text-gray-900">₹{{ $propRent }}<span class="text-sm font-normal text-gray-600">/month</span></div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-400 line-through">₹{{ number_format((int)str_replace(',', '', $propRent) * 1.15) }}</div>
                        <div class="text-sm font-bold text-emerald-600">Verified Pricing</div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($property && $property->description)
                <div class="bg-white rounded-xl p-4 border border-gray-100">
                    <h2 class="text-base font-bold text-gray-900 mb-2">About Property</h2>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $property->description }}</p>
                </div>
            @endif

            <!-- Amenities -->
            <div>
                <h2 class="text-base font-bold text-gray-900 mb-3">Amenities</h2>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($property ? $property->amenities : [] as $am)
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-{{ $am->icon ?? 'wifi' }}"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $am->name }}</div>
                                <div class="text-xs text-gray-500">Available</div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">High-Speed WiFi</div>
                                <div class="text-xs text-gray-500">Included</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-white rounded-xl p-3 border border-gray-100">
                            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                                <i class="fas fa-snowflake"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Air Conditioning</div>
                                <div class="text-xs text-gray-500">Available</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- House Rules -->
            <div>
                <h2 class="text-base font-bold text-gray-900 mb-3">House Rules</h2>
                <div class="bg-white rounded-xl p-4 border border-gray-100 space-y-2">
                    @forelse($property ? $property->rules : [] as $rule)
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span class="text-gray-700">{{ $rule->rule_text }}</span>
                        </div>
                    @empty
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-times-circle text-red-500"></i>
                            <span class="text-gray-700">No smoking inside rooms</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span class="text-gray-700">Visitors allowed till 9 PM</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span class="text-gray-700">Valid ID proof required</span>
                        </div>
                    @endforelse
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
                <span class="text-gray-900 font-medium truncate">{{ $propName }}</span>
            </div>
        </div>

        <!-- Image Gallery -->
        <section class="max-w-7xl mx-auto px-6 mb-8">
            <div class="swiper detailSwiper rounded-3xl overflow-hidden shadow-lg">
                <div class="swiper-wrapper">
                    @foreach($propImages as $img)
                        <div class="swiper-slide">
                            <img src="{{ $img->image_url }}" class="w-full h-[480px] object-cover" alt="{{ $propName }}">
                        </div>
                    @endforeach
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
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $propName }}</h1>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1 {{ $propTagMeta['solid_badge'] }} text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                                        <i class="fas fa-{{ $propTagMeta['icon'] }}"></i>
                                        <span>{{ $propTagMeta['label'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 bg-yellow-50 text-yellow-700 text-sm font-bold px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-star text-yellow-500"></i>
                                        <span>{{ $propRating }} ({{ $propReviews }} reviews)</span>
                                    </div>
                                    <span class="{{ $propGenderMeta['class'] }} text-xs font-bold px-3 py-1.5 rounded-lg">{{ $propGenderMeta['label'] }} PG</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="w-11 h-11 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-600 transition shadow-sm">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                                <button type="button" onclick="heartToggle(this, { id: '{{ $property->id ?? '' }}', title: '{{ addslashes($propName) }}', price: '₹{{ $propRent }}', image: '{{ $propImages->first()->image_url ?? '' }}', location: '{{ addslashes($propLocation) }}', type: '{{ $propGenderMeta['label'] }}' })" data-prop-id="{{ $property->id ?? '' }}" class="w-11 h-11 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-red-500 transition shadow-sm">
                                    <i class="far fa-heart text-base"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt text-brand"></i>
                            <span>{{ $propLocation }}</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">About this Property</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $property->description ?? ($propName . ' offers comfortable and secure accommodation with verified amenities and close proximity to public transit.') }}</p>
                    </div>

                    <!-- Amenities -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Amenities</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($property ? $property->amenities : [] as $am)
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-{{ $am->icon ?? 'wifi' }} text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $am->name }}</div>
                                        <div class="text-sm text-gray-500">Verified</div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-wifi text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">High-Speed WiFi</div>
                                        <div class="text-sm text-gray-500">Included</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white rounded-xl p-4 border border-gray-100">
                                    <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                                        <i class="fas fa-snowflake text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Air Conditioning</div>
                                        <div class="text-sm text-gray-500">Available</div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- House Rules -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">House Rules</h2>
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($property ? $property->rules : [] as $rule)
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                                        <span class="text-gray-700">{{ $rule->rule_text }}</span>
                                    </div>
                                @empty
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                        <span class="text-gray-700">No smoking inside rooms</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                                        <span class="text-gray-700">Visitors allowed till 9 PM</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                                        <span class="text-gray-700">ID proof required</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                                        <span class="text-gray-700">Notice period 30 days</span>
                                    </div>
                                @endforelse
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
                                <div class="text-3xl font-bold text-gray-900">₹{{ $propRent }}<span class="text-base font-normal text-gray-500">/month</span></div>
                            </div>
                            <a href="{{ route('user.bookings') }}" class="block w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect mb-3 text-center">
                                Book Now
                            </a>
                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi, I am interested in ' . $propName) }}" target="_blank" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-emerald-600"></i>
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
                                    <span>Instant move-in available</span>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Info -->
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-3">Property Host / Broker</h3>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-full bg-brand-light text-brand flex items-center justify-center text-xl font-bold">
                                    {{ strtoupper(substr($ownerName, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $ownerName }}</div>
                                    <div class="text-xs text-gray-500">Verified Partner</div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi, I want to inquire about ' . $propName) }}" target="_blank" class="flex-1 bg-brand text-white font-semibold py-2.5 rounded-lg tap-effect text-sm text-center">
                                    <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                                </a>
                                <a href="tel:{{ $cleanPhone }}" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-lg tap-effect text-sm text-center">
                                    <i class="fas fa-phone mr-1"></i> Call
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Mobile Fixed Bottom Action Bar -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 pb-safe">
        <div class="px-4 py-3 flex items-center gap-3">
            <div class="flex-1">
                <div class="text-xs text-gray-500">Starting from</div>
                <div class="text-lg font-bold text-gray-900">₹{{ $propRent }}<span class="text-sm font-normal text-gray-500">/mo</span></div>
            </div>
            <a href="{{ route('user.bookings') }}" class="flex-1 bg-brand text-white font-semibold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 text-center">
                Book Now
            </a>
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hi, I am interested in ' . $propName) }}" target="_blank" class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-emerald-600 tap-effect">
                <i class="fab fa-whatsapp text-lg"></i>
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.detailSwiper', {
            loop: true,
            pagination: { el: '.detailSwiper .swiper-pagination', clickable: true },
            navigation: { nextEl: '.detailSwiper .swiper-button-next', prevEl: '.detailSwiper .swiper-button-prev' },
        });

        if (document.querySelector('.mobileDetailSwiper')) {
            new Swiper('.mobileDetailSwiper', {
                loop: true,
                pagination: { el: '.mobileDetailSwiper .swiper-pagination', clickable: true },
            });
        }
    });
</script>
@endpush
