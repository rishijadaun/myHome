<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Login & Sign Up - StayNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        @media (max-width: 768px) { body { overflow-y: auto; -webkit-overflow-scrolling: touch; } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen">

    <!-- Main Content (No Header, No Footer) -->
    <main>
        <!-- Mobile Content -->
        <div class="md:hidden py-8 px-4 min-h-screen flex flex-col justify-center">
            <!-- Back to Home Bar -->
            <div class="flex items-center justify-between mb-4">
                <button onclick="window.history.back()" class="w-10 h-10 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-600 tap-effect">
                    <i class="fas fa-arrow-left text-sm"></i>
                </button>
                <a href="{{ route('user.home') }}" class="flex items-center gap-1.5">
                    <div class="w-8 h-8 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md">
                        <i class="fas fa-home"></i>
                    </div>
                    <span class="font-bold text-lg text-gray-900">Stay<span class="gradient-text">Nest</span></span>
                </a>
                <div class="w-10"></div>
            </div>

            <!-- Mobile Logo Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900" id="mob-auth-title">Welcome Back</h1>
                <p class="text-sm text-gray-500 mt-1" id="mob-auth-subtitle">Find & book your perfect stay</p>
            </div>

            <!-- Mobile Tabs Switcher -->
            <div class="flex gap-2 bg-gray-100 rounded-xl p-1 mb-6">
                <button onclick="switchTab('login')" id="loginTab" class="flex-1 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg tap-effect transition-all shadow-sm">Login</button>
                <button onclick="switchTab('signup')" id="signupTab" class="flex-1 py-2.5 text-gray-600 text-sm font-medium rounded-lg tap-effect transition-all">Sign Up</button>
            </div>

            <!-- Mobile Login Form -->
            <div id="loginForm" class="space-y-4">
                <form onsubmit="handleAuthSubmit(event, 'login')">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Email or Phone</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="text" placeholder="Enter email or phone number" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="password" id="mob_login_pass" placeholder="Enter your password" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                <button type="button" onclick="togglePass('mob_login_pass', this)" class="absolute right-4 top-3.5 text-gray-400">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand" checked>
                                <span class="text-xs text-gray-600">Remember me</span>
                            </label>
                            <button type="button" onclick="switchTab('forgot')" class="text-xs text-brand font-semibold">Forgot Password?</button>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30">
                            Login
                        </button>
                    </div>
                </form>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-gray-50 px-4 text-xs text-gray-500 font-semibold">OR</span>
                    </div>
                </div>
                <button type="button" onclick="handleSocialAuth('Google')" class="w-full bg-white border border-gray-200 text-gray-700 font-semibold py-3.5 rounded-xl tap-effect flex items-center justify-center gap-3 shadow-xs">
                    <i class="fab fa-google text-red-500"></i>
                    Continue with Google
                </button>
                <button type="button" onclick="handleSocialAuth('Facebook')" class="w-full bg-white border border-gray-200 text-gray-700 font-semibold py-3.5 rounded-xl tap-effect flex items-center justify-center gap-3 shadow-xs">
                    <i class="fab fa-facebook text-blue-600"></i>
                    Continue with Facebook
                </button>
            </div>

            <!-- Mobile Signup Form (Hidden) -->
            <div id="signupForm" class="space-y-4 hidden">
                <form onsubmit="handleAuthSubmit(event, 'signup')">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Full Name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="text" placeholder="Enter your full name" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="email" placeholder="Enter your email" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Phone</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="tel" placeholder="+91 98765 43210" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="password" id="mob_signup_pass" placeholder="Create a password" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                <button type="button" onclick="togglePass('mob_signup_pass', this)" class="absolute right-4 top-3.5 text-gray-400">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <label class="flex items-start gap-2">
                            <input type="checkbox" required class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand mt-0.5">
                            <span class="text-xs text-gray-600">I agree to the <a href="{{ route('user.about') }}" class="text-brand font-semibold">Terms</a> and <a href="{{ route('user.about') }}" class="text-brand font-semibold">Privacy Policy</a></span>
                        </label>
                        <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mobile Forgot Password Form (Hidden) -->
            <div id="forgotForm" class="space-y-4 hidden">
                <div class="bg-brand-light rounded-2xl p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-brand text-xl"></i>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Reset your password</div>
                            <div class="text-xs text-gray-600 mt-1">Enter your email address and we'll send you a link to reset your password.</div>
                        </div>
                    </div>
                </div>
                <form onsubmit="handleAuthSubmit(event, 'forgot')">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="email" placeholder="Enter your email" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30">
                            Send Reset Link
                        </button>
                        <button type="button" onclick="switchTab('login')" class="w-full text-center text-sm text-brand font-semibold py-2">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Login
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Desktop Content -->
        <div class="hidden md:block">
            <div class="min-h-screen flex">
                <!-- Left Side - Branding Hero -->
                <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-brand via-brand-dark to-teal-700 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -mr-48 -mt-48"></div>
                        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white rounded-full -ml-40 -mb-40"></div>
                    </div>
                    <div class="relative z-10 flex flex-col justify-between p-12 text-white">
                        <div>
                            <a href="{{ route('user.home') }}" class="flex items-center gap-2 mb-8">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center text-white font-bold text-2xl">
                                    <i class="fas fa-home"></i>
                                </div>
                                <span class="font-bold text-3xl tracking-tight">StayNest</span>
                            </a>
                            <h1 class="text-5xl font-bold mb-4 leading-tight">Find Your<br>Perfect Stay</h1>
                            <p class="text-lg text-white/80 max-w-md">Discover verified PGs, hostels & co-living spaces that feel like home with zero brokerage.</p>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Verified properties with top amenities</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Zero brokerage, direct from owners</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>24/7 customer support & live map routing</span>
                            </div>
                        </div>
                        <div class="text-sm text-white/60">
                            &copy; {{ date('Y') }} StayNest Technologies Pvt. Ltd. All rights reserved.
                        </div>
                    </div>
                </div>

                <!-- Right Side - Authentication Form Box -->
                <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 relative">
                    <a href="{{ route('user.home') }}" class="absolute top-8 right-8 text-sm text-gray-500 hover:text-brand font-semibold flex items-center gap-1.5 transition">
                        <i class="fas fa-home"></i> Back to Home
                    </a>

                    <div class="w-full max-w-md">
                        <div class="text-center mb-8">
                            <div class="lg:hidden flex items-center justify-center gap-2 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-brand to-brand-dark rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                    <i class="fas fa-home"></i>
                                </div>
                                <span class="font-bold text-2xl text-gray-900">Stay<span class="gradient-text">Nest</span></span>
                            </div>
                            <h2 id="desktop-title" class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                            <p id="desktop-subtitle" class="text-gray-500">Sign in to continue your journey</p>
                        </div>

                        <!-- Desktop Tabs Switcher -->
                        <div class="flex gap-2 bg-gray-100 rounded-xl p-1 mb-6">
                            <button onclick="switchTab('login')" id="loginTabD" class="flex-1 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg tap-effect transition-all shadow-sm">Login</button>
                            <button onclick="switchTab('signup')" id="signupTabD" class="flex-1 py-2.5 text-gray-600 text-sm font-medium rounded-lg tap-effect transition-all">Sign Up</button>
                        </div>

                        <!-- Desktop Login Form -->
                        <div id="loginFormD" class="space-y-4">
                            <form onsubmit="handleAuthSubmit(event, 'login')">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Email or Phone</label>
                                        <div class="relative">
                                            <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="text" placeholder="Enter email or phone number" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Password</label>
                                        <div class="relative">
                                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="password" id="desk_login_pass" placeholder="Enter your password" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                            <button type="button" onclick="togglePass('desk_login_pass', this)" class="absolute right-4 top-3.5 text-gray-400">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand" checked>
                                            <span class="text-sm text-gray-600">Remember me</span>
                                        </label>
                                        <button type="button" onclick="switchTab('forgot')" class="text-sm text-brand font-semibold hover:underline">Forgot Password?</button>
                                    </div>
                                    <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect">
                                        Login
                                    </button>
                                </div>
                            </form>

                            <div class="relative py-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="bg-gray-50 px-4 text-sm text-gray-500 font-semibold">OR</span>
                                </div>
                            </div>
                            <button type="button" onclick="handleSocialAuth('Google')" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-3 shadow-xs">
                                <i class="fab fa-google text-red-500 text-lg"></i>
                                Continue with Google
                            </button>
                            <button type="button" onclick="handleSocialAuth('Facebook')" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-3 shadow-xs">
                                <i class="fab fa-facebook text-blue-600 text-lg"></i>
                                Continue with Facebook
                            </button>
                        </div>

                        <!-- Desktop Signup Form (Hidden) -->
                        <div id="signupFormD" class="space-y-4 hidden">
                            <form onsubmit="handleAuthSubmit(event, 'signup')">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Full Name</label>
                                        <div class="relative">
                                            <i class="fas fa-user absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="text" placeholder="Enter your full name" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Email</label>
                                        <div class="relative">
                                            <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="email" placeholder="Enter your email" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Phone</label>
                                        <div class="relative">
                                            <i class="fas fa-phone absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="tel" placeholder="+91 98765 43210" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Password</label>
                                        <div class="relative">
                                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="password" id="desk_signup_pass" placeholder="Create a password" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                            <button type="button" onclick="togglePass('desk_signup_pass', this)" class="absolute right-4 top-3.5 text-gray-400">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <label class="flex items-start gap-2">
                                        <input type="checkbox" required class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand mt-0.5">
                                        <span class="text-sm text-gray-600">I agree to the <a href="{{ route('user.about') }}" class="text-brand font-semibold">Terms of Service</a> and <a href="{{ route('user.about') }}" class="text-brand font-semibold">Privacy Policy</a></span>
                                    </label>
                                    <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect">
                                        Create Account
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Desktop Forgot Password Form (Hidden) -->
                        <div id="forgotFormD" class="space-y-4 hidden">
                            <div class="bg-brand-light rounded-2xl p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-info-circle text-brand text-xl"></i>
                                    <div>
                                        <div class="font-semibold text-gray-900">Reset your password</div>
                                        <div class="text-sm text-gray-600 mt-1">Enter your email address and we'll send you a link to reset your password.</div>
                                    </div>
                                </div>
                            </div>
                            <form onsubmit="handleAuthSubmit(event, 'forgot')">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Email Address</label>
                                        <div class="relative">
                                            <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="email" placeholder="Enter your email" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect">
                                        Send Reset Link
                                    </button>
                                    <button type="button" onclick="switchTab('login')" class="w-full text-center text-sm text-brand font-semibold hover:underline py-2">
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ================= JAVASCRIPT FOR LOGIN/SIGNUP/FORGOT TABS ================= -->
    <script>
        function switchTab(tab) {
            // 1. Hide all forms on Mobile and Desktop
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('signupForm').classList.add('hidden');
            document.getElementById('forgotForm').classList.add('hidden');
            
            document.getElementById('loginFormD').classList.add('hidden');
            document.getElementById('signupFormD').classList.add('hidden');
            document.getElementById('forgotFormD').classList.add('hidden');
            
            // 2. Reset tab button classes
            const inactiveClass = 'flex-1 py-2.5 text-gray-600 text-sm font-medium rounded-lg tap-effect transition-all';
            const activeClass = 'flex-1 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg tap-effect shadow-sm transition-all';
            
            document.getElementById('loginTab').className = inactiveClass;
            document.getElementById('signupTab').className = inactiveClass;
            document.getElementById('loginTabD').className = inactiveClass;
            document.getElementById('signupTabD').className = inactiveClass;
            
            // 3. Activate selected tab
            if (tab === 'login') {
                document.getElementById('loginForm').classList.remove('hidden');
                document.getElementById('loginFormD').classList.remove('hidden');
                document.getElementById('loginTab').className = activeClass;
                document.getElementById('loginTabD').className = activeClass;
                
                const mobTitle = document.getElementById('mob-auth-title');
                const mobSub = document.getElementById('mob-auth-subtitle');
                if (mobTitle) mobTitle.innerText = 'Welcome Back';
                if (mobSub) mobSub.innerText = 'Find & book your perfect stay';
                
                const title = document.getElementById('desktop-title');
                const sub = document.getElementById('desktop-subtitle');
                if (title) title.innerText = 'Welcome Back';
                if (sub) sub.innerText = 'Sign in to continue your journey';
            } else if (tab === 'signup') {
                document.getElementById('signupForm').classList.remove('hidden');
                document.getElementById('signupFormD').classList.remove('hidden');
                document.getElementById('signupTab').className = activeClass;
                document.getElementById('signupTabD').className = activeClass;
                
                const mobTitle = document.getElementById('mob-auth-title');
                const mobSub = document.getElementById('mob-auth-subtitle');
                if (mobTitle) mobTitle.innerText = 'Create an Account';
                if (mobSub) mobSub.innerText = 'Join StayNest to explore verified PGs';

                const title = document.getElementById('desktop-title');
                const sub = document.getElementById('desktop-subtitle');
                if (title) title.innerText = 'Create an Account';
                if (sub) sub.innerText = 'Join StayNest to find & book verified stays';
            } else if (tab === 'forgot') {
                document.getElementById('forgotForm').classList.remove('hidden');
                document.getElementById('forgotFormD').classList.remove('hidden');
                
                const mobTitle = document.getElementById('mob-auth-title');
                const mobSub = document.getElementById('mob-auth-subtitle');
                if (mobTitle) mobTitle.innerText = 'Reset Password';
                if (mobSub) mobSub.innerText = 'Recover access to your account';

                const title = document.getElementById('desktop-title');
                const sub = document.getElementById('desktop-subtitle');
                if (title) title.innerText = 'Reset Password';
                if (sub) sub.innerText = 'Recover access to your StayNest account';
            }
        }

        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function handleAuthSubmit(e, action) {
            e.preventDefault();
            if (action === 'login') {
                alert('🎉 Login successful! Welcome back to StayNest.');
                window.location.href = "{{ route('user.profile') }}";
            } else if (action === 'signup') {
                alert('🎉 Account created successfully! Redirecting to profile...');
                window.location.href = "{{ route('user.profile') }}";
            } else if (action === 'forgot') {
                alert('📩 Password reset instructions have been sent to your email.');
                switchTab('login');
            }
        }

        function handleSocialAuth(provider) {
            alert(`Connecting with ${provider}... Redirecting to account.`);
            window.location.href = "{{ route('user.profile') }}";
        }
    </script>
</body>
</html>
