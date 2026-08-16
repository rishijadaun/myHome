<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>About Us - StayNest</title>
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
        .gradient-text { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .tap-effect { transition: all 0.2s; }
        .tap-effect:active { transform: scale(0.96); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Desktop Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-12">
                    <a href="{{ route('user.home') }}" class="flex items-center gap-2 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand/30"><i class="fas fa-home"></i></div>
                        <span class="font-bold text-2xl text-gray-900 tracking-tight">Stay<span class="gradient-text">Nest</span></span>
                    </a>
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('user.home') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">Home</a>
                        <a href="{{ route('user.search') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">Find PG</a>
                        <a href="{{ route('user.list-property') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">List Property</a>
                        <a href="{{ route('user.pricing') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">Pricing</a>
                        <a href="{{ route('user.about') }}" class="text-brand font-semibold border-b-2 border-brand px-1 pt-1 text-sm">About Us</a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.login') }}" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
                    <a href="{{ route('user.search') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">Explore PGs</a>
                </div>
            </div>
        </div>
    </header>

    <main class="py-12 md:py-20 max-w-7xl mx-auto px-4 md:px-6 space-y-16">
        <!-- Hero -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="bg-brand-light text-brand text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Our Story & Mission</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">Redefining How India <br><span class="gradient-text">Lives & Stays</span></h1>
            <p class="text-gray-500 text-base md:text-lg leading-relaxed">StayNest was born out of a simple struggle: finding a safe, hygienic, and welcoming paying guest accommodation without paying extortionate brokerage fees. We are building India's most trusted co-living and PG discovery network.</p>
        </div>

        <!-- Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
                <div class="text-4xl font-extrabold text-brand mb-1">50,000+</div>
                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Happy Residents</div>
            </div>
            <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
                <div class="text-4xl font-extrabold text-gray-900 mb-1">1,200+</div>
                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Verified PGs Listed</div>
            </div>
            <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
                <div class="text-4xl font-extrabold text-brand mb-1">18+</div>
                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Tier 1 & 2 Cities</div>
            </div>
            <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-sm">
                <div class="text-4xl font-extrabold text-yellow-500 mb-1">4.8 ★</div>
                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Average Rating</div>
            </div>
        </div>

        <!-- Core Values -->
        <div class="bg-white rounded-3xl p-8 md:p-12 border border-gray-100 shadow-sm">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-gray-900">What Makes StayNest Different?</h2>
                <p class="text-sm text-gray-500 mt-2">Every property on our platform meets strict quality, safety and hygiene standards.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
            <h2 class="text-3xl md:text-4xl font-extrabold">Ready to find your next home?</h2>
            <p class="text-white/80 max-w-xl mx-auto text-sm">Join thousands of students and professionals who found their dream accommodation on StayNest.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('user.search') }}" class="bg-white text-brand font-bold px-8 py-3.5 rounded-xl tap-effect shadow-md hover:bg-gray-50 transition text-sm">Find PG Now</a>
                <a href="{{ route('user.list-property') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold px-8 py-3.5 rounded-xl tap-effect transition text-sm border border-white/30">List Property</a>
            </div>
        </div>
    </main>

    @include('user.partials.footer')
</body>
</html>
