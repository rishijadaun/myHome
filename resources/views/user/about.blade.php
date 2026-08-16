@extends('user.layouts.app')

@section('title', 'About Us - StayNest')

@section('content')
<div class="pt-20 md:pt-10 pb-20 max-w-7xl mx-auto px-4 md:px-6 space-y-16">
    <!-- Hero -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span class="bg-brand-light text-brand text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Our Story & Mission</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight">Redefining How India <br><span class="gradient-text">Lives & Stays</span></h1>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed">StayNest was born out of a simple struggle: finding a safe, hygienic, and welcoming paying guest accommodation without paying extortionate brokerage fees. We are building India's most trusted co-living and PG discovery network.</p>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
            <div class="text-3xl md:text-4xl font-extrabold text-brand mb-1">50,000+</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Happy Residents</div>
        </div>
        <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
            <div class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-1">1,200+</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Verified PGs Listed</div>
        </div>
        <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
            <div class="text-3xl md:text-4xl font-extrabold text-brand mb-1">18+</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Tier 1 & 2 Cities</div>
        </div>
        <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
            <div class="text-3xl md:text-4xl font-extrabold text-yellow-500 mb-1">4.8 ★</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Average Rating</div>
        </div>
    </div>

    <!-- Core Values -->
    <div class="bg-white rounded-3xl p-6 md:p-12 border border-gray-100 shadow-sm">
        <div class="text-center max-w-2xl mx-auto mb-10 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">What Makes StayNest Different?</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-2">Every property on our platform meets strict quality, safety and hygiene standards.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-brand-light text-brand flex items-center justify-center font-bold text-xl"><i class="fas fa-check-circle"></i></div>
                <h3 class="font-bold text-gray-900 text-lg">100% In-Person Verified</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Our ground team personally visits and inspects each PG for cleanliness, amenities, Wi-Fi speed, and biometric security.</p>
            </div>
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl"><i class="fas fa-hand-holding-usd"></i></div>
                <h3 class="font-bold text-gray-900 text-lg">Zero Brokerage Guarantee</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Direct contact with property caretakers and owners. No middleman commissions, ever.</p>
            </div>
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl"><i class="fas fa-map-marked-alt"></i></div>
                <h3 class="font-bold text-gray-900 text-lg">Smart Route Mapping</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Integrated interactive map routing to help you discover PGs closest to your college or tech park.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="bg-gradient-to-r from-brand to-brand-dark rounded-3xl p-8 md:p-12 text-white text-center space-y-6 shadow-xl shadow-brand/20">
        <h2 class="text-2xl md:text-4xl font-extrabold">Ready to find your next home?</h2>
        <p class="text-white/80 max-w-xl mx-auto text-xs md:text-sm">Join thousands of students and professionals who found their dream accommodation on StayNest.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('user.search') }}" class="bg-white text-brand font-bold px-8 py-3.5 rounded-xl tap-effect shadow-md hover:bg-gray-50 transition text-sm">Find PG Now</a>
            <a href="{{ route('user.list-property') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold px-8 py-3.5 rounded-xl tap-effect transition text-sm border border-white/30">List Property</a>
        </div>
    </div>
</div>
@endsection
