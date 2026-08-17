@extends('user.layouts.app')

@section('title', 'Login & Sign Up - StayNest')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center">

    <!-- ===================== MOBILE LAYOUT ===================== -->
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
            <p class="text-sm text-gray-500 mt-1" id="mob-auth-subtitle">Sign in with email or phone</p>
        </div>

        <!-- Error Alert Box -->
        <div id="mob-error-alert" class="hidden mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center gap-2">
            <i class="fas fa-circle-exclamation text-red-500 text-sm flex-shrink-0"></i>
            <span id="mob-error-msg" class="font-medium"></span>
        </div>

        <!-- Mobile Tabs Switcher -->
        <div class="flex gap-2 bg-gray-100 rounded-xl p-1 mb-6">
            <button onclick="switchTab('login')" id="loginTab" class="flex-1 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg tap-effect transition-all shadow-sm">Login</button>
            <button onclick="switchTab('signup')" id="signupTab" class="flex-1 py-2.5 text-gray-600 text-sm font-medium rounded-lg tap-effect transition-all">Sign Up</button>
        </div>

        <!-- Mobile Login Form -->
        <div id="loginForm" class="space-y-4">
            <form onsubmit="handleApiAuth(event, 'login', 'mob')">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Email or 10-Digit Mobile</label>
                        <div class="relative">
                            <i class="fas fa-user-circle absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" id="mob_login_input" placeholder="e.g. admin@staynest.com or 9876543210" required
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
                    <button type="submit" id="mob_login_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                        <span>Login</span>
                    </button>
                </div>
            </form>

            <div class="relative py-3">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center"><span class="bg-gray-50 px-4 text-xs text-gray-500 font-semibold">OR</span></div>
            </div>
            <button type="button" onclick="handleSocialAuth('Google')" class="w-full bg-white border border-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-3 shadow-xs">
                <i class="fab fa-google text-red-500"></i> Continue with Google
            </button>
        </div>

        <!-- Mobile Signup Form (Hidden) -->
        <div id="signupForm" class="space-y-4 hidden">
            <form onsubmit="handleApiAuth(event, 'signup', 'mob')">
                <div class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">I am registering as:</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer">
                                <input type="radio" name="mob_role" value="tenant" checked class="text-brand focus:ring-brand">
                                <span class="text-xs font-bold text-gray-800">🧑 Tenant</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-xl cursor-pointer">
                                <input type="radio" name="mob_role" value="broker" class="text-brand focus:ring-brand">
                                <span class="text-xs font-bold text-gray-800">🏢 PG Owner</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="mob_first_name" placeholder="e.g. Rahul" required
                                class="w-full bg-white border border-gray-200 rounded-xl py-3 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Last Name</label>
                            <input type="text" id="mob_last_name" placeholder="e.g. Sharma"
                                class="w-full bg-white border border-gray-200 rounded-xl py-3 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="mob_signup_email" placeholder="name@example.com" required
                            class="w-full bg-white border border-gray-200 rounded-xl py-3 px-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                    </div>

                    <!-- Mobile Number with India Flag and +91 Prefix -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <div class="absolute left-1.5 top-1.5 bottom-1.5 flex items-center gap-1.5 bg-gray-100 border border-gray-200 px-2.5 rounded-lg text-xs font-bold text-gray-800 select-none z-10">
                                <span class="text-sm leading-none">🇮🇳</span>
                                <span class="tracking-wide text-gray-700">+91</span>
                            </div>
                            <input type="tel" 
                                   id="mob_signup_phone" 
                                   placeholder="98765 43210" 
                                   required
                                   maxlength="10"
                                   inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                   class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-[84px] pr-3 text-sm font-semibold tracking-wider text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 pl-1">Enter 10-digit mobile number only</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="mob_signup_pass" placeholder="Min. 6 characters" required
                                class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            <button type="button" onclick="togglePass('mob_signup_pass', this)" class="absolute right-3.5 top-3 text-gray-400">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="mob_signup_confirm_pass" placeholder="Re-enter password" required
                                class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            <button type="button" onclick="togglePass('mob_signup_confirm_pass', this)" class="absolute right-3.5 top-3 text-gray-400">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <label class="flex items-start gap-2">
                        <input type="checkbox" required class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand mt-0.5" checked>
                        <span class="text-xs text-gray-600">I agree to StayNest <a href="{{ route('user.terms') }}" class="text-brand font-semibold hover:underline">Terms & Policies</a></span>
                    </label>
                    <button type="submit" id="mob_signup_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                        <span>Create Account</span>
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
                        <div class="text-xs text-gray-600 mt-1">Enter your registered email address to receive reset instructions.</div>
                    </div>
                </div>
            </div>
            <form onsubmit="handleForgot(event, 'mob')">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" placeholder="Enter your email" required
                            class="w-full bg-white border border-gray-200 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                    </div>
                    <button type="submit" class="w-full bg-brand text-white font-semibold py-3.5 rounded-xl tap-effect">
                        Send Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================== DESKTOP SPLIT-SCREEN LAYOUT ===================== -->
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
                        <p class="text-lg text-white/80 max-w-md">Discover verified PGs, hostels & co-living spaces with 100% zero brokerage across India.</p>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Verified properties with genuine amenities</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Zero brokerage, direct landlord booking</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Smart AI Matcher & instant schedule visit</span>
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
                    <div class="text-center mb-6">
                        <h2 id="desktop-title" class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                        <p id="desktop-subtitle" class="text-gray-500 text-sm">Sign in with email or phone</p>
                    </div>

                    <!-- Error Alert Box -->
                    <div id="desk-error-alert" class="hidden mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center gap-2">
                        <i class="fas fa-circle-exclamation text-red-500 text-sm flex-shrink-0"></i>
                        <span id="desk-error-msg" class="font-medium"></span>
                    </div>

                    <!-- Desktop Tabs Switcher -->
                    <div class="flex gap-2 bg-gray-100 rounded-xl p-1 mb-6">
                        <button onclick="switchTab('login')" id="loginTabD" class="flex-1 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg tap-effect transition-all shadow-sm">Login</button>
                        <button onclick="switchTab('signup')" id="signupTabD" class="flex-1 py-2.5 text-gray-600 text-sm font-medium rounded-lg tap-effect transition-all">Sign Up</button>
                    </div>

                    <!-- Desktop Login Form -->
                    <div id="loginFormD" class="space-y-4">
                        <form onsubmit="handleApiAuth(event, 'login', 'desk')">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Email or 10-Digit Mobile</label>
                                    <div class="relative">
                                        <i class="fas fa-user-circle absolute left-4 top-3.5 text-gray-400"></i>
                                        <input type="text" id="desk_login_input" placeholder="e.g. admin@staynest.com or 9876543210" required
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
                                <button type="submit" id="desk_login_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                    <span>Login</span>
                                </button>
                            </div>
                        </form>

                        <div class="relative py-3">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                            <div class="relative flex justify-center"><span class="bg-gray-50 px-4 text-xs text-gray-500 font-semibold">OR</span></div>
                        </div>
                        <button type="button" onclick="handleSocialAuth('Google')" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition tap-effect flex items-center justify-center gap-3 shadow-xs">
                            <i class="fab fa-google text-red-500 text-lg"></i> Continue with Google
                        </button>
                    </div>

                    <!-- Desktop Signup Form (Hidden) -->
                    <div id="signupFormD" class="space-y-4 hidden">
                        <form onsubmit="handleApiAuth(event, 'signup', 'desk')">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Account Type:</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center gap-2 p-2 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                                            <input type="radio" name="desk_role" value="tenant" checked class="text-brand focus:ring-brand">
                                            <span class="text-xs font-bold text-gray-800">🧑 Tenant</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-2 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-brand transition">
                                            <input type="radio" name="desk_role" value="broker" class="text-brand focus:ring-brand">
                                            <span class="text-xs font-bold text-gray-800">🏢 PG Owner</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="desk_first_name" placeholder="e.g. Rahul" required
                                            class="w-full bg-white border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Last Name</label>
                                        <input type="text" id="desk_last_name" placeholder="e.g. Sharma"
                                            class="w-full bg-white border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" id="desk_signup_email" placeholder="name@example.com" required
                                        class="w-full bg-white border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                </div>

                                <!-- Desktop Mobile Number with India Flag and +91 Prefix -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-1.5 top-1.5 bottom-1.5 flex items-center gap-1.5 bg-gray-100 border border-gray-200 px-2.5 rounded-lg text-xs font-bold text-gray-800 select-none z-10">
                                            <span class="text-sm leading-none">🇮🇳</span>
                                            <span class="tracking-wide text-gray-700">+91</span>
                                        </div>
                                        <input type="tel" 
                                               id="desk_signup_phone" 
                                               placeholder="98765 43210" 
                                               required
                                               maxlength="10"
                                               inputmode="numeric"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                               class="w-full bg-white border border-gray-200 rounded-xl py-2 pl-[84px] pr-3 text-sm font-semibold tracking-wider text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50">
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-0.5 pl-1">Enter 10-digit mobile number only</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="password" id="desk_signup_pass" placeholder="At least 6 characters" required
                                            class="w-full bg-white border border-gray-200 rounded-xl py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        <button type="button" onclick="togglePass('desk_signup_pass', this)" class="absolute right-3 top-2 text-gray-400">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="password" id="desk_signup_confirm_pass" placeholder="Re-enter password" required
                                            class="w-full bg-white border border-gray-200 rounded-xl py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        <button type="button" onclick="togglePass('desk_signup_confirm_pass', this)" class="absolute right-3 top-2 text-gray-400">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <label class="flex items-start gap-2 pt-1">
                                    <input type="checkbox" required class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand mt-0.5" checked>
                                    <span class="text-xs text-gray-600">I agree to the <a href="{{ route('user.terms') }}" class="text-brand font-semibold hover:underline">Terms</a> and <a href="{{ route('user.privacy') }}" class="text-brand font-semibold hover:underline">Privacy Policy</a></span>
                                </label>
                                <button type="submit" id="desk_signup_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                    <span>Create Account</span>
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
                        <form onsubmit="handleForgot(event, 'desk')">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Email Address</label>
                                    <input type="email" placeholder="Enter your email" required
                                        class="w-full bg-white border border-gray-200 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                </div>
                                <button type="submit" class="w-full bg-brand text-white font-semibold py-3.5 rounded-xl transition tap-effect">
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
</div>
@endsection

@push('scripts')
    <!-- ================= JAVASCRIPT FOR RESTFUL API LOGIN & SIGNUP ================= -->
    <script>
        function switchTab(tab) {
            hideError('mob');
            hideError('desk');

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
                
                document.getElementById('mob-auth-title').innerText = 'Welcome Back';
                document.getElementById('mob-auth-subtitle').innerText = 'Sign in with email or phone';
                document.getElementById('desktop-title').innerText = 'Welcome Back';
                document.getElementById('desktop-subtitle').innerText = 'Sign in with email or phone';
            } else if (tab === 'signup') {
                document.getElementById('signupForm').classList.remove('hidden');
                document.getElementById('signupFormD').classList.remove('hidden');
                document.getElementById('signupTab').className = activeClass;
                document.getElementById('signupTabD').className = activeClass;
                
                document.getElementById('mob-auth-title').innerText = 'Create an Account';
                document.getElementById('mob-auth-subtitle').innerText = 'Join StayNest to explore verified PGs';
                document.getElementById('desktop-title').innerText = 'Create an Account';
                document.getElementById('desktop-subtitle').innerText = 'Join StayNest to find & book verified stays';
            } else if (tab === 'forgot') {
                document.getElementById('forgotForm').classList.remove('hidden');
                document.getElementById('forgotFormD').classList.remove('hidden');
                
                document.getElementById('mob-auth-title').innerText = 'Reset Password';
                document.getElementById('mob-auth-subtitle').innerText = 'Recover access to your account';
                document.getElementById('desktop-title').innerText = 'Reset Password';
                document.getElementById('desktop-subtitle').innerText = 'Recover access to your StayNest account';
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

        function showError(mode, message) {
            const el = document.getElementById(`${mode}-error-alert`);
            const msgEl = document.getElementById(`${mode}-error-msg`);
            if (el && msgEl) {
                msgEl.innerText = message;
                el.classList.remove('hidden');
            }
        }

        function hideError(mode) {
            const el = document.getElementById(`${mode}-error-alert`);
            if (el) el.classList.add('hidden');
        }

        // ================= REST API AUTHENTICATION HANDLER =================
        async function handleApiAuth(e, action, mode) {
            e.preventDefault();
            hideError(mode);

            const btn = document.getElementById(`${mode}_${action}_btn`);
            const originalText = btn.innerHTML;

            try {
                let url = '';
                let payload = {};

                if (action === 'login') {
                    url = '/api/v1/auth/login';
                    const loginInput = document.getElementById(`${mode}_login_input`).value.trim();
                    const password = document.getElementById(`${mode}_login_pass`).value;
                    
                    if (!loginInput || !password) {
                        showError(mode, 'Please enter your email/phone and password.');
                        return;
                    }
                    payload = { login: loginInput, password: password };
                } else if (action === 'signup') {
                    url = '/api/v1/auth/register';
                    const role = document.querySelector(`input[name="${mode}_role"]:checked`)?.value || 'tenant';
                    const firstName = document.getElementById(`${mode}_first_name`).value.trim();
                    const lastName = document.getElementById(`${mode}_last_name`).value.trim();
                    const email = document.getElementById(`${mode}_signup_email`).value.trim();
                    const rawPhone = document.getElementById(`${mode}_signup_phone`).value.trim();
                    const password = document.getElementById(`${mode}_signup_pass`).value;
                    const confirmPassword = document.getElementById(`${mode}_signup_confirm_pass`).value;

                    // 10-Digit Phone Validation (Digits only, must be 10 digits starting with 6-9)
                    const cleanPhone = rawPhone.replace(/\D/g, '');
                    if (cleanPhone.length !== 10 || !/^[6-9]/.test(cleanPhone)) {
                        showError(mode, 'Please enter a valid 10-digit Indian mobile number (e.g. 9876543210).');
                        return;
                    }

                    // Password length check
                    if (password.length < 6) {
                        showError(mode, 'Password must be at least 6 characters long.');
                        return;
                    }

                    // Confirm Password Match Check
                    if (password !== confirmPassword) {
                        showError(mode, 'Password and Confirm Password do not match!');
                        return;
                    }

                    payload = {
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        phone: '+91' + cleanPhone,
                        password: password,
                        password_confirmation: confirmPassword,
                        role: role
                    };
                }

                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                btn.disabled = true;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'Authentication failed. Please check your details.';
                    if (result.errors) {
                        const firstErr = Object.values(result.errors)[0];
                        if (firstErr) errMsg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    }
                    showError(mode, errMsg);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                // Success: store Bearer token and user profile in localStorage
                if (result.data?.token) {
                    localStorage.setItem('staynest_token', result.data.token);
                    localStorage.setItem('staynest_user', JSON.stringify(result.data.user));
                }

                const role = result.data?.user?.role;
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Success! Redirecting...';

                setTimeout(() => {
                    if (role === 'broker') {
                        window.location.href = "{{ route('broker.dashboard') }}";
                    } else {
                        window.location.href = "{{ route('user.profile') }}";
                    }
                }, 500);

            } catch (err) {
                showError(mode, 'Server connection error. Please check your network.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        function handleForgot(e, mode) {
            e.preventDefault();
            alert('📩 Password reset instructions have been sent to your email address.');
            switchTab('login');
        }

        function handleSocialAuth(provider) {
            alert(`Connecting with ${provider}... Redirecting.`);
            window.location.href = "{{ route('user.profile') }}";
        }
    </script>
@endpush
