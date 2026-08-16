<!-- Mobile App Header -->
<header class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">
    <div class="android-status-bar px-4 py-1.5 flex justify-between items-center text-xs font-semibold text-gray-900 pt-safe">
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

    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5 mb-0.5">
                    <h1 class="text-lg font-bold text-gray-900">Home</h1>
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </div>
                <p class="text-xs text-gray-500 truncate">468, Sangeet, 431, Saket, New...</p>
            </div>

            <div class="flex items-center gap-2 ml-3">
                <button class="w-11 h-11 rounded-full bg-gradient-to-br from-purple-400 to-green-400 flex items-center justify-center text-white tap-effect shadow-md">
                    <i class="fas fa-wallet text-sm"></i>
                </button>
                <button class="w-11 h-11 rounded-full bg-gradient-to-br from-yellow-300 to-orange-400 flex items-center justify-center text-white tap-effect shadow-md relative">
                    <i class="fas fa-gift text-sm"></i>
                    <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 bg-white text-gray-900 text-[8px] font-bold px-1.5 py-0.5 rounded-full border border-gray-200 whitespace-nowrap">100</span>
                </button>
                <button class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 tap-effect">
                    <i class="fas fa-user text-sm"></i>
                </button>
            </div>
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
                    <a href="{{ route('user.home') }}" class="text-brand font-semibold border-b-2 border-brand px-1 pt-1 text-sm">Home</a>
                    <a href="#" class="text-gray-600 hover:text-brand font-medium transition text-sm">Find PG</a>
                    <a href="#" class="text-gray-600 hover:text-brand font-medium transition text-sm">List Property</a>
                    <a href="#" class="text-gray-600 hover:text-brand font-medium transition text-sm">Pricing</a>
                    <a href="#" class="text-gray-600 hover:text-brand font-medium transition text-sm">About Us</a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <button class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                    <i class="fas fa-search"></i>
                </button>
                <button class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.4V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path>
                        <path d="M9 17a3 3 0 0 0 6 0"></path>
                    </svg>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <a href="/login" class="px-5 py-2.5 text-gray-700 font-medium hover:text-brand transition text-sm">Log In</a>
                <button class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-6 py-2.5 rounded-xl font-semibold transition tap-effect text-sm">
                    Download App
                </button>
            </div>
        </div>
    </div>
</header>
