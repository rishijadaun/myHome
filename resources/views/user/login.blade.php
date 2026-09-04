@extends('user.layouts.app')

@section('title', 'Login & Sign Up - StayNest')
@section('robots', 'noindex, nofollow')

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
        <div class="text-center mb-6" id="mob-header-box">
            <h1 class="text-2xl font-extrabold text-gray-900" id="mob-auth-title">Welcome Back</h1>
            <p class="text-sm text-gray-500 mt-1" id="mob-auth-subtitle">Sign in with email or phone</p>
        </div>

        <!-- Error Alert Box -->
        <div id="mob-error-alert" class="{{ session('flash_error') ? '' : 'hidden' }} mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center gap-2">
            <i class="fas fa-circle-exclamation text-red-500 text-sm flex-shrink-0"></i>
            <span id="mob-error-msg" class="font-medium">{{ session('flash_error') }}</span>
        </div>

        <!-- Success Alert Box -->
        <div id="mob-success-alert" class="{{ session('flash_success') ? '' : 'hidden' }} mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl flex items-center gap-2">
            <i class="fas fa-circle-check text-emerald-500 text-sm flex-shrink-0"></i>
            <span id="mob-success-msg" class="font-medium">{{ session('flash_success') }}</span>
        </div>

        <!-- Mobile Tabs Switcher -->
        <div id="mob-tabs-switcher" class="flex gap-2 bg-gray-100 rounded-xl p-1 mb-6">
            <button onclick="switchTab('login')" id="loginTab" class="flex-1 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg tap-effect transition-all shadow-sm">Login</button>
            <button onclick="switchTab('signup')" id="signupTab" class="flex-1 py-2.5 text-gray-600 text-sm font-medium rounded-lg tap-effect transition-all">Sign Up</button>
        </div>

        <!-- Mobile Login Form -->
        <div id="loginForm" class="space-y-4">
            <form onsubmit="handleApiAuth(event, 'login', 'mob')" novalidate>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-gray-700">Email or Mobile Number <span class="text-red-500">*</span></label>
                            <span class="text-[11px] text-gray-400 font-medium">3-150 chars</span>
                        </div>
                        <div class="relative">
                            <i class="fas fa-user-circle absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" 
                                id="mob_login_input" 
                                placeholder="Enter Email ID or Mobile Number" 
                                minlength="3" 
                                maxlength="150" 
                                required
                                oninput="validateLoginInput('mob')"
                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>
                        <p id="mob_login_input_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                            <span class="text-[11px] text-gray-400 font-medium">Min. 6 chars</span>
                        </div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="password" 
                                id="mob_login_pass" 
                                placeholder="Enter your password (6-100 characters)" 
                                minlength="6" 
                                maxlength="100" 
                                required
                                oninput="validateLoginPassword('mob')"
                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            <button type="button" onclick="togglePass('mob_login_pass', this)" class="absolute right-4 top-3.5 text-gray-400">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p id="mob_login_pass_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand" checked>
                            <span class="text-xs text-gray-600">Remember me</span>
                        </label>
                        <button type="button" onclick="switchTab('forgot')" class="text-xs text-brand font-semibold hover:underline">Forgot Password?</button>
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
            <a href="{{ route('auth.google') }}" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-3 shadow-xs transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Continue with Google
            </a>
        </div>

        <!-- Mobile Signup Form (Hidden) -->
        <div id="signupForm" class="space-y-4 hidden">
            <!-- Step 1: Registration Details Form -->
            <div id="mob_signup_step1" class="space-y-4">
                <form onsubmit="handleSignupRequest(event, 'mob')" novalidate>
                    <div class="space-y-3.5">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-gray-800 tracking-wide">Account Type <span class="text-red-500">*</span></label>
                                <span class="text-[10px] text-gray-400 font-medium">Select your role</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100/90 rounded-2xl border border-slate-200/80">
                                <!-- Tenant Option -->
                                <label id="mob_role_card_tenant" onclick="selectRole('mob', 'tenant')" class="relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none bg-white shadow-xs border-2 border-brand ring-2 ring-brand/15">
                                    <input type="radio" name="mob_role" value="tenant" checked class="sr-only">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                        <span>🧑</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-xs text-gray-900 leading-tight">Tenant</span>
                                            <span id="mob_role_badge_tenant" class="w-3.5 h-3.5 rounded-full bg-brand text-white flex items-center justify-center text-[8px] shadow-xs">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                        <p class="text-[9.5px] text-gray-500 truncate mt-0.5">Looking for PG or Flatmate</p>
                                    </div>
                                </label>

                                <!-- PG Owner Option -->
                                <label id="mob_role_card_broker" onclick="selectRole('mob', 'broker')" class="relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none bg-transparent border-2 border-transparent hover:bg-white/60">
                                    <input type="radio" name="mob_role" value="broker" class="sr-only">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                        <span>🏢</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-xs text-gray-700 leading-tight">PG Owner</span>
                                            <span id="mob_role_badge_broker" class="w-3.5 h-3.5 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[8px]">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                        <p class="text-[9.5px] text-gray-500 truncate mt-0.5">List & Manage</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="mob_first_name" placeholder="e.g. Rahul" minlength="2" maxlength="50" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="mob_last_name" placeholder="e.g. Sharma" maxlength="50"
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address (Gmail) <span class="text-red-500">*</span></label>
                            <input type="email" id="mob_signup_email" placeholder="name@gmail.com" minlength="5" maxlength="150" required
                                class="w-full bg-white border border-gray-200 rounded-xl py-3 px-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            <p class="text-[10px] text-gray-400 mt-1">We will send a 6-digit OTP code to verify this Gmail/Email address.</p>
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
                                       minlength="10"
                                       maxlength="10"
                                       inputmode="numeric"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                       class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-[84px] pr-3 text-sm font-semibold tracking-wider text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 pl-1">Exactly 10-digit mobile number</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" id="mob_signup_pass" placeholder="Min. 6 characters (max 100)" minlength="6" maxlength="100" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                <button type="button" onclick="togglePass('mob_signup_pass', this)" class="absolute right-3.5 top-3 text-gray-400">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" id="mob_signup_confirm_pass" placeholder="Re-enter password" minlength="6" maxlength="100" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                <button type="button" onclick="togglePass('mob_signup_confirm_pass', this)" class="absolute right-3.5 top-3 text-gray-400">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <label class="flex items-start gap-2">
                            <input type="checkbox" id="mob_signup_terms" required class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand mt-0.5" checked>
                            <span class="text-xs text-gray-600">I agree to StayNest <a href="{{ route('user.terms') }}" class="text-brand font-semibold hover:underline">Terms & Policies</a></span>
                        </label>
                        <button type="submit" id="mob_signup_step1_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                            <span>Send Email Verification OTP</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                            <div class="relative flex justify-center"><span class="bg-gray-50 px-4 text-xs text-gray-500 font-semibold">OR</span></div>
                        </div>

                        <button type="button" onclick="handleGoogleSignup('mob')" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-3 shadow-xs transition">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            Sign Up with Google
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Enter & Verify Registration Email OTP -->
            <div id="mob_signup_step2" class="space-y-4 hidden">
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base shrink-0">
                            <i class="fas fa-envelope-circle-check"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-xs sm:text-sm">Verification OTP Sent!</div>
                            <div class="text-xs text-gray-600 mt-0.5">
                                We sent a 6-digit code to <strong id="mob_signup_target_email" class="text-gray-900 font-bold"></strong>. Check your Gmail inbox.
                            </div>
                        </div>
                    </div>
                </div>

                <form onsubmit="handleSignupVerify(event, 'mob')" novalidate>
                    <div class="space-y-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 text-center">Enter 6-Digit Email OTP <span class="text-red-500">*</span></label>
                            <input type="tel" 
                                id="mob_signup_otp" 
                                placeholder="• • • • • •" 
                                maxlength="6" 
                                minlength="6" 
                                required
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                class="w-full bg-white border-2 border-brand/30 rounded-2xl py-3.5 px-4 text-center text-xl font-mono font-extrabold tracking-[0.3em] text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand">
                            <div class="flex items-center justify-between mt-2 text-xs">
                                <span class="text-gray-400 text-[11px]">Didn't receive OTP?</span>
                                <button type="button" id="mob_signup_resend_btn" onclick="handleResendSignupOtp('mob')" disabled class="font-bold text-brand disabled:text-gray-400 hover:underline cursor-pointer disabled:cursor-not-allowed">
                                    Resend Code <span id="mob_signup_countdown">(60s)</span>
                                </button>
                            </div>
                        </div>

                        <button type="submit" id="mob_signup_step2_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
                            <i class="fas fa-shield-check"></i>
                            <span>Verify OTP & Create Account</span>
                        </button>
                        <button type="button" onclick="backToSignupStep1('mob')" class="w-full text-center text-xs text-gray-500 font-semibold hover:text-brand py-1">
                            <i class="fas fa-arrow-left mr-1"></i> Edit Details / Change Email
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mobile Forgot Password Flow (Hidden) -->
        <div id="forgotForm" class="space-y-4 hidden">
            <!-- Step 1: Request OTP -->
            <div id="mob_forgot_step1" class="space-y-4">
                <div class="mb-2">
                    <button type="button" onclick="switchTab('login')" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-brand transition mb-3 group">
                        <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-0.5"></i> Back to Login
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center text-base shrink-0 shadow-2xs">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Reset Password</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Enter your email or phone to receive a 6-digit OTP code.</p>
                        </div>
                    </div>
                </div>

                <form onsubmit="handleForgotRequest(event, 'mob')" novalidate>
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-gray-700">Email or Mobile Number <span class="text-red-500">*</span></label>
                                <span class="text-[11px] text-gray-400 font-medium">3-150 chars</span>
                            </div>
                            <div class="relative">
                                <i class="fas fa-user-shield absolute left-4 top-3.5 text-gray-400"></i>
                                <input type="text" 
                                    id="mob_forgot_login" 
                                    placeholder="Enter Email ID or 10-Digit Mobile Number" 
                                    minlength="3" 
                                    maxlength="150" 
                                    required
                                    oninput="validateForgotLoginInput('mob')"
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                            </div>
                            <p id="mob_forgot_login_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                        </div>
                        <button type="submit" id="mob_forgot_step1_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/20 flex items-center justify-center gap-2">
                            <span>Send Verification Code</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                        <button type="button" onclick="switchTab('login')" class="w-full text-center text-xs text-gray-500 font-semibold hover:text-brand py-1">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Login
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Verify OTP & Reset Password -->
            <div id="mob_forgot_step2" class="space-y-4 hidden">
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600 mt-0.5">
                            <i class="fas fa-envelope-open-text text-sm"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-xs">Verification Code Sent via Gmail</div>
                            <div class="text-xs text-gray-600 mt-0.5">We've sent a 6-digit OTP code to <span id="mob_forgot_target_display" class="font-bold text-gray-900"></span>. Please check your inbox or spam folder.</div>
                        </div>
                    </div>
                </div>

                <form onsubmit="handleForgotReset(event, 'mob')" novalidate>
                    <div class="space-y-3.5">
                        <!-- 6-digit OTP -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-gray-700">6-Digit Verification Code <span class="text-red-500">*</span></label>
                                <span class="text-[11px] text-gray-400 font-medium">6 digits</span>
                            </div>
                            <input type="tel" 
                                id="mob_forgot_otp" 
                                placeholder="• • • • • •" 
                                maxlength="6" 
                                minlength="6" 
                                required
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                class="w-full bg-white border border-gray-200 rounded-xl py-3 px-4 text-center text-lg font-mono font-bold tracking-widest text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50">
                        </div>

                        <!-- New Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-gray-700">New Password <span class="text-red-500">*</span></label>
                                <span class="text-[11px] text-gray-400">Min. 6 chars</span>
                            </div>
                            <div class="relative">
                                <input type="password" 
                                    id="mob_forgot_new_pass" 
                                    placeholder="Enter new password (6-100 chars)" 
                                    minlength="6" 
                                    maxlength="100" 
                                    required
                                    oninput="checkPasswordStrength('mob_forgot_new_pass', 'mob_pwd_bar', 'mob_pwd_text')"
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                <button type="button" onclick="togglePass('mob_forgot_new_pass', this)" class="absolute right-3 top-3 text-gray-400">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <!-- Live strength bar -->
                            <div class="mt-1.5">
                                <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                    <div id="mob_pwd_bar" class="h-full bg-red-400 transition-all duration-300 w-0"></div>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span id="mob_pwd_text" class="text-[10px] font-semibold text-gray-400">Password strength</span>
                                    <span class="text-[10px] text-gray-400">Min. 6 chars</span>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" 
                                    id="mob_forgot_confirm_pass" 
                                    placeholder="Re-enter new password" 
                                    minlength="6" 
                                    maxlength="100" 
                                    required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                <button type="button" onclick="togglePass('mob_forgot_confirm_pass', this)" class="absolute right-3 top-3 text-gray-400">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <button type="button" id="mob_resend_otp_btn" onclick="resendForgotOtp('mob')" class="text-xs text-brand font-semibold hover:underline">
                                Resend OTP
                            </button>
                            <span id="mob_resend_timer" class="text-xs text-gray-400 font-mono hidden">60s</span>
                        </div>

                        <button type="submit" id="mob_forgot_reset_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/20 flex items-center justify-center gap-2">
                            <span>Save New Password</span>
                        </button>
                        <button type="button" onclick="backToForgotStep1('mob')" class="w-full text-center text-xs text-gray-500 font-semibold hover:text-brand py-1">
                            <i class="fas fa-arrow-left mr-1"></i> Change Email/Phone
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Success Screen -->
            <div id="mob_forgot_step3" class="space-y-4 text-center py-6 hidden">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-inner">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Password Reset Complete!</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">Your account password has been updated securely. You can now log in with your new credentials.</p>
                </div>
                <button type="button" onclick="proceedToLoginWithUser('mob')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30">
                    Proceed to Login
                </button>
            </div>
        </div>
    </div>

    <!-- ===================== DESKTOP SPLIT-SCREEN LAYOUT ===================== -->
    <div class="hidden md:block">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding Hero -->
            <div class=" lg:flex lg:w-1/2 bg-gradient-to-br from-brand via-brand-dark to-teal-700 relative overflow-hidden">
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
                        <h2 class="text-5xl font-bold mb-4 leading-tight">Find Your<br>Perfect Stay</h2>
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
                    <div class="text-center mb-6" id="desk-header-box">
                        <h2 id="desktop-title" class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                        <p id="desktop-subtitle" class="text-gray-500 text-sm">Sign in with email or phone</p>
                    </div>

                    <!-- Error Alert Box -->
                    <div id="desk-error-alert" class="{{ session('flash_error') ? '' : 'hidden' }} mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center gap-2">
                        <i class="fas fa-circle-exclamation text-red-500 text-sm flex-shrink-0"></i>
                        <span id="desk-error-msg" class="font-medium">{{ session('flash_error') }}</span>
                    </div>

                    <!-- Success Alert Box -->
                    <div id="desk-success-alert" class="{{ session('flash_success') ? '' : 'hidden' }} mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl flex items-center gap-2">
                        <i class="fas fa-circle-check text-emerald-500 text-sm flex-shrink-0"></i>
                        <span id="desk-success-msg" class="font-medium">{{ session('flash_success') }}</span>
                    </div>

                    <!-- Desktop Tabs Switcher -->
                    <div id="desk-tabs-switcher" class="flex gap-2 bg-gray-100 rounded-xl p-1 mb-6">
                        <button onclick="switchTab('login')" id="loginTabD" class="flex-1 py-2.5 bg-brand text-white text-sm font-semibold rounded-lg tap-effect transition-all shadow-sm">Login</button>
                        <button onclick="switchTab('signup')" id="signupTabD" class="flex-1 py-2.5 text-gray-600 text-sm font-medium rounded-lg tap-effect transition-all">Sign Up</button>
                    </div>

                    <!-- Desktop Login Form -->
                    <div id="loginFormD" class="space-y-4">
                        <form onsubmit="handleApiAuth(event, 'login', 'desk')" novalidate>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="block text-xs font-semibold text-gray-700">Email or Mobile  Number<span class="text-red-500">*</span></label>
                                        <span class="text-[11px] text-gray-400 font-medium">3-150 chars</span>
                                    </div>
                                    <div class="relative">
                                        <i class="fas fa-user-circle absolute left-4 top-3.5 text-gray-400"></i>
                                        <input type="text" 
                                            id="desk_login_input" 
                                            placeholder="Enter Email ID or Mobile Number" 
                                            minlength="3" 
                                            maxlength="150" 
                                            required
                                            oninput="validateLoginInput('desk')"
                                            class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                    </div>
                                    <p id="desk_login_input_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="block text-xs font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                                        <span class="text-[11px] text-gray-400 font-medium">Min. 6 chars</span>
                                    </div>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                                        <input type="password" 
                                            id="desk_login_pass" 
                                            placeholder="Enter your password (6-100 characters)" 
                                            minlength="6" 
                                            maxlength="100" 
                                            required
                                            oninput="validateLoginPassword('desk')"
                                            class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        <button type="button" onclick="togglePass('desk_login_pass', this)" class="absolute right-4 top-3.5 text-gray-400">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <p id="desk_login_pass_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
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
                        <a href="{{ route('auth.google') }}" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition tap-effect flex items-center justify-center gap-3 shadow-xs">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            Continue with Google
                        </a>
                    </div>

                    <!-- Desktop Signup Form (Hidden) -->
                    <div id="signupFormD" class="space-y-4 hidden">
                        <!-- Step 1: Registration Details Form -->
                        <div id="desk_signup_step1" class="space-y-4">
                            <form onsubmit="handleSignupRequest(event, 'desk')" novalidate>
                                <div class="space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-bold text-gray-800 tracking-wide">Account Type <span class="text-red-500">*</span></label>
                                            <span class="text-[10px] text-gray-400 font-medium">Select your role</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2.5 p-1 bg-slate-100/90 rounded-2xl border border-slate-200/80">
                                            <!-- Tenant Option -->
                                            <label id="desk_role_card_tenant" onclick="selectRole('desk', 'tenant')" class="relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none bg-white shadow-xs border-2 border-brand ring-2 ring-brand/15">
                                                <input type="radio" name="desk_role" value="tenant" checked class="sr-only">
                                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                                    <span>🧑</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-bold text-xs text-gray-900 leading-tight">Tenant</span>
                                                        <span id="desk_role_badge_tenant" class="w-3.5 h-3.5 rounded-full bg-brand text-white flex items-center justify-center text-[8px] shadow-xs">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </div>
                                                    <p class="text-[9.5px] text-gray-500 truncate mt-0.5">Looking for PG or Flatmate</p>
                                                </div>
                                            </label>

                                            <!-- PG Owner Option -->
                                            <label id="desk_role_card_broker" onclick="selectRole('desk', 'broker')" class="relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none bg-transparent border-2 border-transparent hover:bg-white/60">
                                                <input type="radio" name="desk_role" value="broker" class="sr-only">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                                    <span>🏢</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-bold text-xs text-gray-700 leading-tight">PG Owner</span>
                                                        <span id="desk_role_badge_broker" class="w-3.5 h-3.5 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[8px]">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </div>
                                                    <p class="text-[9.5px] text-gray-500 truncate mt-0.5">List & Manage</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                            <input type="text" id="desk_first_name" placeholder="e.g. Rahul" minlength="2" maxlength="50" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Last Name</label>
                                            <input type="text" id="desk_last_name" placeholder="e.g. Sharma" maxlength="50"
                                                class="w-full bg-white border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address (Gmail) <span class="text-red-500">*</span></label>
                                        <input type="email" id="desk_signup_email" placeholder="name@gmail.com" minlength="5" maxlength="150" required
                                            class="w-full bg-white border border-gray-200 rounded-xl py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        <p class="text-[10px] text-gray-400 mt-0.5">We will send a 6-digit OTP code to verify this Gmail/Email address.</p>
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
                                                   minlength="10"
                                                   maxlength="10"
                                                   inputmode="numeric"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                                   class="w-full bg-white border border-gray-200 rounded-xl py-2 pl-[84px] pr-3 text-sm font-semibold tracking-wider text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-0.5 pl-1">Exactly 10-digit mobile number</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="password" id="desk_signup_pass" placeholder="Min. 6 characters (max 100)" minlength="6" maxlength="100" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                            <button type="button" onclick="togglePass('desk_signup_pass', this)" class="absolute right-3 top-2 text-gray-400">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="password" id="desk_signup_confirm_pass" placeholder="Re-enter password" minlength="6" maxlength="100" required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                            <button type="button" onclick="togglePass('desk_signup_confirm_pass', this)" class="absolute right-3 top-2 text-gray-400">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <label class="flex items-start gap-2 pt-1">
                                        <input type="checkbox" id="desk_signup_terms" required class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand mt-0.5" checked>
                                        <span class="text-xs text-gray-600">I agree to the <a href="{{ route('user.terms') }}" class="text-brand font-semibold hover:underline">Terms</a> and <a href="{{ route('user.privacy') }}" class="text-brand font-semibold hover:underline">Privacy Policy</a></span>
                                    </label>
                                    <button type="submit" id="desk_signup_step1_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                        <span>Send Email Verification OTP</span>
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </button>

                                    <div class="relative py-2">
                                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                                        <div class="relative flex justify-center"><span class="bg-gray-50 px-4 text-xs text-gray-500 font-semibold">OR</span></div>
                                    </div>

                                    <button type="button" onclick="handleGoogleSignup('desk')" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-2.5 rounded-xl transition tap-effect flex items-center justify-center gap-3 shadow-xs">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                        </svg>
                                        Sign Up with Google
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 2: Enter & Verify Registration Email OTP -->
                        <div id="desk_signup_step2" class="space-y-4 hidden">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base shrink-0">
                                        <i class="fas fa-envelope-circle-check"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-xs sm:text-sm">Verification OTP Sent!</div>
                                        <div class="text-xs text-gray-600 mt-0.5">
                                            We sent a 6-digit code to <strong id="desk_signup_target_email" class="text-gray-900 font-bold"></strong>. Check your Gmail inbox.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form onsubmit="handleSignupVerify(event, 'desk')" novalidate>
                                <div class="space-y-3.5">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 text-center">Enter 6-Digit Email OTP <span class="text-red-500">*</span></label>
                                        <input type="tel" 
                                            id="desk_signup_otp" 
                                            placeholder="• • • • • •" 
                                            maxlength="6" 
                                            minlength="6" 
                                            required
                                            inputmode="numeric"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                            class="w-full bg-white border-2 border-brand/30 rounded-2xl py-3 px-4 text-center text-xl font-mono font-extrabold tracking-[0.3em] text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand">
                                        <div class="flex items-center justify-between mt-2 text-xs">
                                            <span class="text-gray-400 text-[11px]">Didn't receive OTP?</span>
                                            <button type="button" id="desk_signup_resend_btn" onclick="handleResendSignupOtp('desk')" disabled class="font-bold text-brand disabled:text-gray-400 hover:underline cursor-pointer disabled:cursor-not-allowed">
                                                Resend Code <span id="desk_signup_countdown">(60s)</span>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="submit" id="desk_signup_step2_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                        <i class="fas fa-shield-check"></i>
                                        <span>Verify OTP & Create Account</span>
                                    </button>
                                    <button type="button" onclick="backToSignupStep1('desk')" class="w-full text-center text-xs text-gray-500 font-semibold hover:text-brand py-1">
                                        <i class="fas fa-arrow-left mr-1"></i> Edit Details / Change Email
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Desktop Forgot Password Flow (Hidden) -->
                    <div id="forgotFormD" class="space-y-4 hidden">
                        <!-- Step 1: Request OTP -->
                        <div id="desk_forgot_step1" class="space-y-4">
                            <div class="mb-3">
                                <button type="button" onclick="switchTab('login')" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-brand transition mb-3 group">
                                    <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-0.5"></i> Back to Login
                                </button>
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-2xl bg-brand/10 text-brand flex items-center justify-center text-lg shrink-0 shadow-2xs">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                                        <p class="text-xs text-gray-500 mt-0.5">Enter your registered email or mobile number to receive a 6-digit verification code.</p>
                                    </div>
                                </div>
                            </div>

                            <form onsubmit="handleForgotRequest(event, 'desk')" novalidate>
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-semibold text-gray-700">Email or Mobile Number <span class="text-red-500">*</span></label>
                                            <span class="text-[11px] text-gray-400 font-medium">3-150 chars</span>
                                        </div>
                                        <div class="relative">
                                            <i class="fas fa-user-shield absolute left-4 top-3.5 text-gray-400"></i>
                                            <input type="text" 
                                                id="desk_forgot_login" 
                                                placeholder="Enter Email ID or 10-Digit Mobile Number" 
                                                minlength="3" 
                                                maxlength="150" 
                                                required
                                                oninput="validateForgotLoginInput('desk')"
                                                class="w-full bg-white border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                        </div>
                                        <p id="desk_forgot_login_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                                    </div>
                                    <button type="submit" id="desk_forgot_step1_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                        <span>Send Verification Code</span>
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </button>
                                    <button type="button" onclick="switchTab('login')" class="w-full text-center text-sm text-gray-500 font-semibold hover:text-brand py-1">
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Login
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 2: Verify OTP & Reset Password -->
                        <div id="desk_forgot_step2" class="space-y-4 hidden">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600 mt-0.5">
                                        <i class="fas fa-envelope-open-text text-base"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">Verification Code Sent via Gmail</div>
                                        <div class="text-xs text-gray-600 mt-0.5">We've sent a 6-digit OTP code to <span id="desk_forgot_target_display" class="font-bold text-gray-900"></span>. Please check your inbox or spam folder.</div>
                                    </div>
                                </div>
                            </div>

                            <form onsubmit="handleForgotReset(event, 'desk')" novalidate>
                                <div class="space-y-3.5">
                                    <!-- 6-digit OTP -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-semibold text-gray-700">6-Digit Verification Code <span class="text-red-500">*</span></label>
                                            <span class="text-[11px] text-gray-400 font-medium">6 digits</span>
                                        </div>
                                        <input type="tel" 
                                            id="desk_forgot_otp" 
                                            placeholder="• • • • • •" 
                                            maxlength="6" 
                                            minlength="6" 
                                            required
                                            inputmode="numeric"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                            class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-4 text-center text-lg font-mono font-bold tracking-widest text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50">
                                    </div>

                                    <!-- New Password -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <label class="block text-xs font-semibold text-gray-700">New Password <span class="text-red-500">*</span></label>
                                            <span class="text-[11px] text-gray-400">Min. 6 chars</span>
                                        </div>
                                        <div class="relative">
                                            <input type="password" 
                                                id="desk_forgot_new_pass" 
                                                placeholder="Enter new password (6-100 chars)" 
                                                minlength="6" 
                                                maxlength="100" 
                                                required
                                                oninput="checkPasswordStrength('desk_forgot_new_pass', 'desk_pwd_bar', 'desk_pwd_text')"
                                                class="w-full bg-white border border-gray-200 rounded-xl py-2.5 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                            <button type="button" onclick="togglePass('desk_forgot_new_pass', this)" class="absolute right-3 top-2.5 text-gray-400">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <!-- Live strength bar -->
                                        <div class="mt-1.5">
                                            <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                                                <div id="desk_pwd_bar" class="h-full bg-red-400 transition-all duration-300 w-0"></div>
                                            </div>
                                            <div class="flex justify-between items-center mt-1">
                                                <span id="desk_pwd_text" class="text-[10px] font-semibold text-gray-400">Password strength</span>
                                                <span class="text-[10px] text-gray-400">6-100 characters</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="password" 
                                                id="desk_forgot_confirm_pass" 
                                                placeholder="Re-enter new password" 
                                                minlength="6" 
                                                maxlength="100" 
                                                required
                                                class="w-full bg-white border border-gray-200 rounded-xl py-2.5 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                                            <button type="button" onclick="togglePass('desk_forgot_confirm_pass', this)" class="absolute right-3 top-2.5 text-gray-400">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-1">
                                        <button type="button" id="desk_resend_otp_btn" onclick="resendForgotOtp('desk')" class="text-xs text-brand font-semibold hover:underline">
                                            Resend OTP
                                        </button>
                                        <span id="desk_resend_timer" class="text-xs text-gray-400 font-mono hidden">60s</span>
                                    </div>

                                    <button type="submit" id="desk_forgot_reset_btn" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect flex items-center justify-center gap-2">
                                        <span>Save New Password</span>
                                    </button>
                                    <button type="button" onclick="backToForgotStep1('desk')" class="w-full text-center text-xs text-gray-500 font-semibold hover:text-brand py-1">
                                        <i class="fas fa-arrow-left mr-1"></i> Change Email/Phone
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 3: Success Screen -->
                        <div id="desk_forgot_step3" class="space-y-4 text-center py-6 hidden">
                            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-inner">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Password Reset Complete!</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">Your account password has been updated securely. You can now log in with your new credentials.</p>
                            </div>
                            <button type="button" onclick="proceedToLoginWithUser('desk')" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-semibold py-3.5 rounded-xl transition tap-effect">
                                Proceed to Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- ================= JAVASCRIPT FOR RESTFUL API LOGIN, SIGNUP & FORGOT PASSWORD ================= -->
    <script>
        // State storage for forgot password workflow
        const forgotState = {
            mob: { login: '', timer: null, timeLeft: 0 },
            desk: { login: '', timer: null, timeLeft: 0 }
        };

        // State storage for signup OTP verification workflow
        const signupState = {
            mob: { email: '', timer: null, timeLeft: 0 },
            desk: { email: '', timer: null, timeLeft: 0 }
        };

        function selectRole(mode, role) {
            const isTenant = (role === 'tenant');
            const radioInputs = document.querySelectorAll(`input[name="${mode}_role"]`);
            radioInputs.forEach(r => {
                if (r.value === role) r.checked = true;
            });

            const tenantCard = document.getElementById(`${mode}_role_card_tenant`);
            const brokerCard = document.getElementById(`${mode}_role_card_broker`);
            const tenantBadge = document.getElementById(`${mode}_role_badge_tenant`);
            const brokerBadge = document.getElementById(`${mode}_role_badge_broker`);

            const activeClasses = 'bg-white shadow-xs border-2 border-brand ring-2 ring-brand/15';
            const inactiveClasses = 'bg-transparent border-2 border-transparent hover:bg-white/60';

            if (isTenant) {
                if (tenantCard) tenantCard.className = `relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none ${activeClasses}`;
                if (brokerCard) brokerCard.className = `relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none ${inactiveClasses}`;
                
                if (tenantBadge) tenantBadge.className = 'w-3.5 h-3.5 rounded-full bg-brand text-white flex items-center justify-center text-[8px] shadow-xs';
                if (brokerBadge) brokerBadge.className = 'w-3.5 h-3.5 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[8px]';
            } else {
                if (brokerCard) brokerCard.className = `relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none ${activeClasses}`;
                if (tenantCard) tenantCard.className = `relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all duration-200 select-none ${inactiveClasses}`;
                
                if (brokerBadge) brokerBadge.className = 'w-3.5 h-3.5 rounded-full bg-brand text-white flex items-center justify-center text-[8px] shadow-xs';
                if (tenantBadge) tenantBadge.className = 'w-3.5 h-3.5 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[8px]';
            }
        }

        function switchTab(tab) {
            hideError('mob');
            hideError('desk');
            hideSuccess('mob');
            hideSuccess('desk');

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

            // 3. Handle Header and Tabs visibility (Hide when in Reset Password)
            const mobTabs = document.getElementById('mob-tabs-switcher');
            const deskTabs = document.getElementById('desk-tabs-switcher');
            const mobHeader = document.getElementById('mob-header-box');
            const deskHeader = document.getElementById('desk-header-box');

            if (tab === 'forgot') {
                if (mobTabs) mobTabs.classList.add('hidden');
                if (deskTabs) deskTabs.classList.add('hidden');
                if (mobHeader) mobHeader.classList.add('hidden');
                if (deskHeader) deskHeader.classList.add('hidden');
            } else {
                if (mobTabs) mobTabs.classList.remove('hidden');
                if (deskTabs) deskTabs.classList.remove('hidden');
                if (mobHeader) mobHeader.classList.remove('hidden');
                if (deskHeader) deskHeader.classList.remove('hidden');
            }
            
            // 4. Activate selected tab
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
                
                // Show Step 1 by default
                backToForgotStep1('mob');
                backToForgotStep1('desk');
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
            hideSuccess(mode);
            if (el && msgEl) {
                msgEl.innerText = message;
                el.classList.remove('hidden');
            }
        }

        function hideError(mode) {
            const el = document.getElementById(`${mode}-error-alert`);
            if (el) el.classList.add('hidden');
        }

        function showSuccess(mode, message) {
            const el = document.getElementById(`${mode}-success-alert`);
            const msgEl = document.getElementById(`${mode}-success-msg`);
            hideError(mode);
            if (el && msgEl) {
                msgEl.innerText = message;
                el.classList.remove('hidden');
            }
        }

        function hideSuccess(mode) {
            const el = document.getElementById(`${mode}-success-alert`);
            if (el) el.classList.add('hidden');
        }

        // ================= REAL-TIME CLIENT VALIDATIONS =================
        function validateLoginInput(mode) {
            const input = document.getElementById(`${mode}_login_input`);
            const errEl = document.getElementById(`${mode}_login_input_err`);
            const val = input.value.trim();

            if (!val) {
                errEl.classList.add('hidden');
                input.classList.remove('border-red-400');
                return true;
            }

            // Check Minimum & Maximum length limits
            if (val.length < 3) {
                errEl.innerText = `Login identifier must be at least 3 characters (${val.length}/3 entered).`;
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            if (val.length > 150) {
                errEl.innerText = 'Login identifier cannot exceed maximum limit of 150 characters.';
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            // Case A: User is entering an Email Address (contains letters or '@')
            if (val.includes('@') || /[a-zA-Z]/.test(val)) {
                if (val.length < 5) {
                    errEl.innerText = `Email address must be at least 5 characters (${val.length}/5 entered).`;
                    errEl.classList.remove('hidden');
                    input.classList.add('border-red-400');
                    return false;
                }

                // If user entered '@', validate full email structure
                if (val.includes('@')) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(val)) {
                        errEl.innerText = 'Please enter a valid email address (e.g. name@example.com).';
                        errEl.classList.remove('hidden');
                        input.classList.add('border-red-400');
                        return false;
                    }
                }
            } else if (/^\d+$/.test(val)) {
                // Case B: User is entering a purely Numeric Mobile Number
                if (val.length !== 10) {
                    errEl.innerText = `Mobile number must be exactly 10 digits (${val.length}/10 entered).`;
                    errEl.classList.remove('hidden');
                    input.classList.add('border-red-400');
                    return false;
                } else if (!/^[6-9]/.test(val)) {
                    errEl.innerText = 'Mobile number must start with 6, 7, 8, or 9.';
                    errEl.classList.remove('hidden');
                    input.classList.add('border-red-400');
                    return false;
                }
            }

            errEl.classList.add('hidden');
            input.classList.remove('border-red-400');
            return true;
        }

        function validateLoginPassword(mode) {
            const pass = document.getElementById(`${mode}_login_pass`);
            const errEl = document.getElementById(`${mode}_login_pass_err`);
            const val = pass.value;

            if (!val) {
                errEl.classList.add('hidden');
                pass.classList.remove('border-red-400');
                return true;
            }

            if (val.length < 6) {
                errEl.innerText = `Password must be at least 6 characters (${val.length}/6 entered).`;
                errEl.classList.remove('hidden');
                pass.classList.add('border-red-400');
                return false;
            }

            if (val.length > 100) {
                errEl.innerText = 'Password cannot exceed 100 characters.';
                errEl.classList.remove('hidden');
                pass.classList.add('border-red-400');
                return false;
            }

            errEl.classList.add('hidden');
            pass.classList.remove('border-red-400');
            return true;
        }

        function validateForgotLoginInput(mode) {
            const input = document.getElementById(`${mode}_forgot_login`);
            const errEl = document.getElementById(`${mode}_forgot_login_err`);
            const val = input.value.trim();

            if (!val) {
                errEl.classList.add('hidden');
                input.classList.remove('border-red-400');
                return true;
            }

            if (val.length < 3) {
                errEl.innerText = `Identifier must be at least 3 characters (${val.length}/3 entered).`;
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            if (val.length > 150) {
                errEl.innerText = 'Identifier cannot exceed maximum limit of 150 characters.';
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            // Case A: Email format (contains letters or '@')
            if (val.includes('@') || /[a-zA-Z]/.test(val)) {
                if (val.length < 5) {
                    errEl.innerText = `Email address must be at least 5 characters (${val.length}/5 entered).`;
                    errEl.classList.remove('hidden');
                    input.classList.add('border-red-400');
                    return false;
                }
                if (val.includes('@')) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(val)) {
                        errEl.innerText = 'Please enter a valid email address (e.g. name@example.com).';
                        errEl.classList.remove('hidden');
                        input.classList.add('border-red-400');
                        return false;
                    }
                }
            } else if (/^\d+$/.test(val)) {
                // Case B: Numeric mobile number
                if (val.length !== 10) {
                    errEl.innerText = `Mobile number must be exactly 10 digits (${val.length}/10 entered).`;
                    errEl.classList.remove('hidden');
                    input.classList.add('border-red-400');
                    return false;
                } else if (!/^[6-9]/.test(val)) {
                    errEl.innerText = 'Mobile number must start with 6, 7, 8, or 9.';
                    errEl.classList.remove('hidden');
                    input.classList.add('border-red-400');
                    return false;
                }
            }

            errEl.classList.add('hidden');
            input.classList.remove('border-red-400');
            return true;
        }

        function checkPasswordStrength(inputId, barId, textId) {
            const pass = document.getElementById(inputId).value;
            const bar = document.getElementById(barId);
            const text = document.getElementById(textId);

            if (!pass) {
                bar.style.width = '0%';
                text.innerText = 'Password strength';
                text.className = 'text-[10px] font-semibold text-gray-400';
                return;
            }

            let score = 0;
            if (pass.length >= 6) score += 25;
            if (pass.length >= 8) score += 25;
            if (/[0-9]/.test(pass) && /[a-zA-Z]/.test(pass)) score += 25;
            if (/[^A-Za-z0-9]/.test(pass)) score += 25;

            bar.style.width = score + '%';
            if (score <= 25) {
                bar.className = 'h-full bg-red-500 transition-all duration-300';
                text.innerText = 'Weak (min. 6 chars)';
                text.className = 'text-[10px] font-semibold text-red-500';
            } else if (score <= 50) {
                bar.className = 'h-full bg-amber-500 transition-all duration-300';
                text.innerText = 'Fair';
                text.className = 'text-[10px] font-semibold text-amber-500';
            } else if (score <= 75) {
                bar.className = 'h-full bg-blue-500 transition-all duration-300';
                text.innerText = 'Good';
                text.className = 'text-[10px] font-semibold text-blue-500';
            } else {
                bar.className = 'h-full bg-emerald-500 transition-all duration-300';
                text.innerText = 'Strong';
                text.className = 'text-[10px] font-semibold text-emerald-500';
            }
        }

        // ================= REST API AUTHENTICATION HANDLER (LOGIN) =================
        async function handleApiAuth(e, action, mode) {
            e.preventDefault();
            hideError(mode);
            hideSuccess(mode);

            const btn = document.getElementById(`${mode}_${action}_btn`);
            const originalText = btn.innerHTML;

            try {
                const loginInput = document.getElementById(`${mode}_login_input`).value.trim();
                const password = document.getElementById(`${mode}_login_pass`).value;
                
                // Min & Max length validations
                if (!loginInput) {
                    showError(mode, 'Please enter your registered email or mobile number.');
                    return;
                }

                if (loginInput.length < 3) {
                    showError(mode, 'Login identifier must be at least 3 characters long.');
                    return;
                }

                if (loginInput.length > 150) {
                    showError(mode, 'Login identifier cannot exceed maximum limit of 150 characters.');
                    return;
                }

                // Email format check if input contains '@' or letters
                if (loginInput.includes('@') || /[a-zA-Z]/.test(loginInput)) {
                    if (loginInput.length < 5 || loginInput.length > 150) {
                        showError(mode, 'Email address must be between 5 and 150 characters.');
                        return;
                    }
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(loginInput)) {
                        showError(mode, 'Please enter a valid email address format (e.g. name@example.com).');
                        return;
                    }
                } else if (/^\d+$/.test(loginInput)) {
                    if (loginInput.length !== 10 || !/^[6-9]/.test(loginInput)) {
                        showError(mode, 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.');
                        return;
                    }
                }

                if (!password) {
                    showError(mode, 'Please enter your password.');
                    return;
                }

                if (password.length < 6) {
                    showError(mode, 'Password must be at least 6 characters long.');
                    return;
                }

                if (password.length > 100) {
                    showError(mode, 'Password cannot exceed 100 characters.');
                    return;
                }

                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Signing in...';
                btn.disabled = true;

                const response = await fetch('/api/v1/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ login: loginInput, password: password })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'Authentication failed. Please check your credentials.';
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

        // ================= SIGNUP WITH GMAIL OTP VERIFICATION =================
        async function handleSignupRequest(e, mode) {
            e.preventDefault();
            hideError(mode);
            hideSuccess(mode);

            const role = document.querySelector(`input[name="${mode}_role"]:checked`)?.value || 'tenant';
            const firstName = document.getElementById(`${mode}_first_name`).value.trim();
            const lastName = document.getElementById(`${mode}_last_name`).value.trim();
            const email = document.getElementById(`${mode}_signup_email`).value.trim();
            const rawPhone = document.getElementById(`${mode}_signup_phone`).value.trim();
            const password = document.getElementById(`${mode}_signup_pass`).value;
            const confirmPassword = document.getElementById(`${mode}_signup_confirm_pass`).value;
            const terms = document.getElementById(`${mode}_signup_terms`);

            if (terms && !terms.checked) {
                showError(mode, 'Please agree to the Terms & Policies before proceeding.');
                return;
            }

            // Validations
            if (firstName.length < 2) {
                showError(mode, 'First name must be at least 2 characters.');
                return;
            }
            if (firstName.length > 50) {
                showError(mode, 'First name cannot exceed 50 characters.');
                return;
            }

            if (email.length < 5 || email.length > 150 || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError(mode, 'Please enter a valid Gmail / Email address (5-150 characters).');
                return;
            }

            const cleanPhone = rawPhone.replace(/\D/g, '');
            if (cleanPhone.length !== 10 || !/^[6-9]/.test(cleanPhone)) {
                showError(mode, 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.');
                return;
            }

            if (password.length < 6) {
                showError(mode, 'Password must be at least 6 characters long.');
                return;
            }

            if (password.length > 100) {
                showError(mode, 'Password cannot exceed 100 characters.');
                return;
            }

            if (password !== confirmPassword) {
                showError(mode, 'Password and Confirm Password do not match!');
                return;
            }

            const btn = document.getElementById(`${mode}_signup_step1_btn`);
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending OTP to Gmail...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/v1/auth/register/request-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        phone: '+91' + cleanPhone,
                        password: password,
                        password_confirmation: confirmPassword,
                        role: role
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'Registration failed. Please check your details.';
                    if (result.errors) {
                        const firstErr = Object.values(result.errors)[0];
                        if (firstErr) errMsg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    }
                    showError(mode, errMsg);
                    return;
                }

                // Save email to state
                signupState[mode].email = email;

                // Update Step 2 UI
                document.getElementById(`${mode}_signup_target_email`).innerText = email;
                document.getElementById(`${mode}_signup_otp`).value = '';

                // Transition to Step 2
                document.getElementById(`${mode}_signup_step1`).classList.add('hidden');
                document.getElementById(`${mode}_signup_step2`).classList.remove('hidden');

                showSuccess(mode, `Verification code dispatched to ${email}! Please enter the 6-digit OTP.`);

                // Start 60-second countdown for Resend button
                startSignupTimer(mode);

                // Focus OTP input
                setTimeout(() => {
                    document.getElementById(`${mode}_signup_otp`)?.focus();
                }, 100);

            } catch (err) {
                showError(mode, 'Network error. Please check your connection.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        async function handleSignupVerify(e, mode) {
            e.preventDefault();
            hideError(mode);
            hideSuccess(mode);

            const email = signupState[mode].email;
            const otpInput = document.getElementById(`${mode}_signup_otp`);
            const otp = otpInput.value.trim();

            if (!email) {
                showError(mode, 'Session expired or invalid email. Please fill the registration form again.');
                backToSignupStep1(mode);
                return;
            }

            if (!otp || otp.length !== 6) {
                showError(mode, 'Please enter the complete 6-digit OTP received in your Gmail inbox.');
                otpInput.focus();
                return;
            }

            const btn = document.getElementById(`${mode}_signup_step2_btn`);
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying OTP & Creating Account...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/v1/auth/register/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        otp: otp
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'OTP verification failed. Please check the code.';
                    if (result.errors) {
                        const firstErr = Object.values(result.errors)[0];
                        if (firstErr) errMsg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    }
                    showError(mode, errMsg);
                    return;
                }

                // Success: store Bearer token and user profile in localStorage
                if (result.data?.token) {
                    localStorage.setItem('staynest_token', result.data.token);
                    localStorage.setItem('staynest_user', JSON.stringify(result.data.user));
                }

                const userRole = result.data?.user?.role;
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Account Verified! Redirecting...';
                showSuccess(mode, 'Account created and verified successfully! Redirecting...');

                setTimeout(() => {
                    if (userRole === 'broker') {
                        window.location.href = "{{ route('broker.dashboard') }}";
                    } else {
                        window.location.href = "{{ route('user.profile') }}";
                    }
                }, 600);

            } catch (err) {
                showError(mode, 'Network error. Please try again.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        function startSignupTimer(mode) {
            const btn = document.getElementById(`${mode}_signup_resend_btn`);
            const countdownEl = document.getElementById(`${mode}_signup_countdown`);
            if (!btn || !countdownEl) return;

            signupState[mode].timeLeft = 60;
            btn.disabled = true;
            countdownEl.innerText = `(${signupState[mode].timeLeft}s)`;

            if (signupState[mode].timer) clearInterval(signupState[mode].timer);

            signupState[mode].timer = setInterval(() => {
                signupState[mode].timeLeft--;
                if (signupState[mode].timeLeft <= 0) {
                    clearInterval(signupState[mode].timer);
                    btn.disabled = false;
                    countdownEl.innerText = '';
                } else {
                    countdownEl.innerText = `(${signupState[mode].timeLeft}s)`;
                }
            }, 1000);
        }

        async function handleResendSignupOtp(mode) {
            const email = signupState[mode].email;
            if (!email) {
                backToSignupStep1(mode);
                return;
            }

            hideError(mode);
            const btn = document.getElementById(`${mode}_signup_resend_btn`);
            btn.disabled = true;

            const role = document.querySelector(`input[name="${mode}_role"]:checked`)?.value || 'tenant';
            const firstName = document.getElementById(`${mode}_first_name`).value.trim();
            const lastName = document.getElementById(`${mode}_last_name`).value.trim();
            const rawPhone = document.getElementById(`${mode}_signup_phone`).value.trim();
            const password = document.getElementById(`${mode}_signup_pass`).value;
            const cleanPhone = rawPhone.replace(/\D/g, '');

            try {
                const response = await fetch('/api/v1/auth/register/request-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        first_name: firstName || 'User',
                        last_name: lastName,
                        email: email,
                        phone: '+91' + cleanPhone,
                        password: password,
                        password_confirmation: password,
                        role: role
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    showError(mode, result.message || 'Failed to resend OTP.');
                    btn.disabled = false;
                    return;
                }

                showSuccess(mode, 'A fresh 6-digit OTP has been sent to your Gmail inbox.');
                startSignupTimer(mode);

            } catch (err) {
                showError(mode, 'Network error. Please try again.');
                btn.disabled = false;
            }
        }

        function backToSignupStep1(mode) {
            hideError(mode);
            hideSuccess(mode);
            if (signupState[mode].timer) clearInterval(signupState[mode].timer);
            document.getElementById(`${mode}_signup_step1`).classList.remove('hidden');
            document.getElementById(`${mode}_signup_step2`).classList.add('hidden');
        }

        // ================= FORGOT PASSWORD 3-STEP HANDLERS =================
        async function handleForgotRequest(e, mode) {
            e.preventDefault();
            hideError(mode);
            hideSuccess(mode);

            const inputEl = document.getElementById(`${mode}_forgot_login`);
            const loginVal = inputEl.value.trim();

            if (!loginVal) {
                showError(mode, 'Please enter your registered email address or 10-digit mobile number.');
                return;
            }

            if (loginVal.length < 3) {
                showError(mode, 'Identifier must be at least 3 characters long.');
                return;
            }

            if (loginVal.length > 150) {
                showError(mode, 'Identifier cannot exceed maximum limit of 150 characters.');
                return;
            }

            // Email check
            if (loginVal.includes('@') || /[a-zA-Z]/.test(loginVal)) {
                if (loginVal.length < 5 || loginVal.length > 150) {
                    showError(mode, 'Email address must be between 5 and 150 characters.');
                    return;
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(loginVal)) {
                    showError(mode, 'Please enter a valid email address format (e.g. name@example.com).');
                    return;
                }
            } else if (/^\d+$/.test(loginVal)) {
                if (loginVal.length !== 10 || !/^[6-9]/.test(loginVal)) {
                    showError(mode, 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.');
                    return;
                }
            }

            const btn = document.getElementById(`${mode}_forgot_step1_btn`);
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending Code...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/v1/auth/forgot-password/request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ login: loginVal })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'Account not found. Please verify your email or phone.';
                    if (result.errors) {
                        const firstErr = Object.values(result.errors)[0];
                        if (firstErr) errMsg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    }
                    showError(mode, errMsg);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                // Save login in state
                forgotState[mode].login = loginVal;

                // Update Step 2 UI
                document.getElementById(`${mode}_forgot_target_display`).innerText = result.data?.target || loginVal;

                // Switch to Step 2
                document.getElementById(`${mode}_forgot_step1`).classList.add('hidden');
                document.getElementById(`${mode}_forgot_step2`).classList.remove('hidden');
                document.getElementById(`${mode}_forgot_step3`).classList.add('hidden');

                // Start 60s Resend Timer
                startResendTimer(mode);

            } catch (err) {
                showError(mode, 'Failed to connect to server. Please try again.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        async function handleForgotReset(e, mode) {
            e.preventDefault();
            hideError(mode);
            hideSuccess(mode);

            const loginVal = forgotState[mode].login;
            const otp = document.getElementById(`${mode}_forgot_otp`).value.trim();
            const newPass = document.getElementById(`${mode}_forgot_new_pass`).value;
            const confirmPass = document.getElementById(`${mode}_forgot_confirm_pass`).value;

            if (!otp || otp.length !== 6) {
                showError(mode, 'Please enter the valid 6-digit OTP code.');
                return;
            }

            if (!newPass || newPass.length < 6) {
                showError(mode, 'New password must be at least 6 characters long.');
                return;
            }

            if (newPass.length > 100) {
                showError(mode, 'New password cannot exceed 100 characters.');
                return;
            }

            if (newPass !== confirmPass) {
                showError(mode, 'New password and confirm password do not match.');
                return;
            }

            const btn = document.getElementById(`${mode}_forgot_reset_btn`);
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Resetting Password...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/v1/auth/forgot-password/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        login: loginVal,
                        otp: otp,
                        password: newPass,
                        password_confirmation: confirmPass
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'Password reset failed. Please check the code.';
                    if (result.errors) {
                        const firstErr = Object.values(result.errors)[0];
                        if (firstErr) errMsg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    }
                    showError(mode, errMsg);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                // Show Step 3 (Success)
                document.getElementById(`${mode}_forgot_step1`).classList.add('hidden');
                document.getElementById(`${mode}_forgot_step2`).classList.add('hidden');
                document.getElementById(`${mode}_forgot_step3`).classList.remove('hidden');

            } catch (err) {
                showError(mode, 'Failed to update password. Please check your connection.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        function backToForgotStep1(mode) {
            hideError(mode);
            document.getElementById(`${mode}_forgot_step1`).classList.remove('hidden');
            document.getElementById(`${mode}_forgot_step2`).classList.add('hidden');
            document.getElementById(`${mode}_forgot_step3`).classList.add('hidden');
        }

        function proceedToLoginWithUser(mode) {
            const savedLogin = forgotState[mode].login;
            switchTab('login');
            if (savedLogin) {
                document.getElementById(`${mode}_login_input`).value = savedLogin;
                document.getElementById(`${mode}_login_pass`).value = '';
                document.getElementById(`${mode}_login_pass`).focus();
            }
            showSuccess(mode, 'Password reset successfully! Please sign in with your new password.');
        }

        function startResendTimer(mode) {
            const btn = document.getElementById(`${mode}_resend_otp_btn`);
            const timerEl = document.getElementById(`${mode}_resend_timer`);
            
            forgotState[mode].timeLeft = 60;
            btn.classList.add('hidden');
            timerEl.classList.remove('hidden');
            timerEl.innerText = 'Resend in 60s';

            if (forgotState[mode].timer) clearInterval(forgotState[mode].timer);

            forgotState[mode].timer = setInterval(() => {
                forgotState[mode].timeLeft--;
                if (forgotState[mode].timeLeft <= 0) {
                    clearInterval(forgotState[mode].timer);
                    btn.classList.remove('hidden');
                    timerEl.classList.add('hidden');
                } else {
                    timerEl.innerText = `Resend in ${forgotState[mode].timeLeft}s`;
                }
            }, 1000);
        }

        async function resendForgotOtp(mode) {
            const loginVal = forgotState[mode].login;
            if (!loginVal) return;

            hideError(mode);
            const btn = document.getElementById(`${mode}_resend_otp_btn`);
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            try {
                const response = await fetch('/api/v1/auth/forgot-password/request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ login: loginVal })
                });
                const result = await response.json();
                if (response.ok && result.success !== false) {
                    showSuccess(mode, 'A new 6-digit OTP code has been sent to your Gmail inbox!');
                    startResendTimer(mode);
                } else {
                    showError(mode, result.message || 'Failed to resend OTP.');
                }
            } catch (err) {
                showError(mode, 'Error sending OTP. Please try again.');
            } finally {
                btn.innerHTML = 'Resend OTP';
                btn.disabled = false;
            }
        }

        function handleSocialAuth(provider) {
            if (provider === 'Google') {
                window.location.href = "{{ route('auth.google') }}";
            }
        }

        function handleGoogleSignup(mode) {
            const role = document.querySelector(`input[name="${mode}_role"]:checked`)?.value || 'tenant';
            window.location.href = `{{ route('auth.google') }}?role=${encodeURIComponent(role)}`;
        }
    </script>
@endpush
