<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Pricing & Plans - StayNest</title>
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
                        <a href="{{ route('user.pricing') }}" class="text-brand font-semibold border-b-2 border-brand px-1 pt-1 text-sm">Pricing</a>
                        <a href="{{ route('user.about') }}" class="text-gray-600 hover:text-brand font-medium transition text-sm">About Us</a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.login') }}" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
                    <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">List PG Free</a>
                </div>
            </div>
        </div>
    </header>

    <main class="py-12 md:py-20 max-w-7xl mx-auto px-4 md:px-6 space-y-16">
        <!-- Hero Section -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="bg-brand-light text-brand text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Transparent & Fair</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">Simple Pricing, <br><span class="gradient-text">Zero Hidden Charges</span></h1>
            <p class="text-gray-500 text-base md:text-lg">For tenants, browsing and booking is always 100% free with zero brokerage. For property owners, flexible plans to grow occupancy.</p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Tenant Card -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col justify-between space-y-6">
                <div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase">For Tenants</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-4">100% Free</h3>
                    <p class="text-xs text-gray-500 mt-1">Zero brokerage forever for all students & professionals</p>
                    <div class="text-4xl font-extrabold text-gray-900 my-6">₹0<span class="text-sm font-normal text-gray-400"> / forever</span></div>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Search unlimited verified PGs</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Direct owner & caretaker contact</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Free live location map routing</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Verified photos & transparent pricing</li>
                    </ul>
                </div>
                <a href="{{ route('user.search') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 rounded-xl text-center tap-effect transition text-sm">Explore PGs Now</a>
            </div>

            <!-- Owner Starter Card (Popular) -->
            <div class="bg-white rounded-3xl p-8 border-2 border-brand shadow-xl relative flex flex-col justify-between space-y-6">
                <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand text-white text-xs font-extrabold px-4 py-1 rounded-full uppercase tracking-wider shadow-sm">Most Popular</span>
                <div>
                    <span class="text-xs font-bold text-brand bg-brand-light px-3 py-1 rounded-full uppercase">Host Starter</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-4">Growth Partner</h3>
                    <p class="text-xs text-gray-500 mt-1">For single and multi-property PG owners</p>
                    <div class="text-4xl font-extrabold text-gray-900 my-6">₹0<span class="text-sm font-normal text-gray-400"> / listing fee</span></div>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Up to 5 PG property listings</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Unlimited tenant leads & inquiries</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Broker management dashboard access</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Rent payment tracking & automated receipts</li>
                    </ul>
                </div>
                <a href="{{ route('user.list-property') }}" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl text-center tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition text-sm">List Your PG Free</a>
            </div>

            <!-- Enterprise Card -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col justify-between space-y-6">
                <div>
                    <span class="text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full uppercase">Hostel Chains</span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-4">Enterprise</h3>
                    <p class="text-xs text-gray-500 mt-1">For co-living operators with 10+ locations</p>
                    <div class="text-4xl font-extrabold text-gray-900 my-6">Custom<span class="text-sm font-normal text-gray-400"> / solution</span></div>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Unlimited properties & beds</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Priority top-ranking search placement</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Dedicated account manager</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Custom API & ERP integrations</li>
                    </ul>
                </div>
                <a href="https://wa.me/919876543210" target="_blank" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 rounded-xl text-center tap-effect transition text-sm">Contact Sales Team</a>
            </div>
        </div>
    </main>

    @include('user.partials.footer')
</body>
</html>
