@extends('user.layouts.app')

@section('title', 'Login & Sign Up - SpaceSeeks | Verified PG Network')
@section('meta_description', 'Sign in or create your SpaceSeeks account to discover verified PGs, hostels & co-living spaces across India with zero brokerage.')
@section('robots', 'noindex, nofollow')

@push('styles')
<style>

    /* Hero Mesh Background & Gradient Waves */
    .staynest-hero-bg {
        background: radial-gradient(circle at 10% 15%, rgba(16, 185, 129, 0.22) 0%, transparent 45%),
                    radial-gradient(circle at 90% 85%, rgba(5, 150, 105, 0.28) 0%, transparent 50%),
                    radial-gradient(circle at 50% 50%, #032b23 0%, #021f19 100%);
    }

    .glass-pill {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .auth-input-container {
        position: relative;
        transition: all 0.2s ease;
    }
    .auth-input-container input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    .auth-input-container input:focus {
        background-color: #ffffff;
        border-color: #057a55;
        box-shadow: 0 0 0 3px rgba(5, 122, 85, 0.15);
    }
    .auth-input-container:focus-within .auth-icon {
        color: #057a55;
    }

    .brand-btn-gradient {
        background: linear-gradient(135deg, #057a55 0%, #046c4e 100%);
        box-shadow: 0 4px 14px -2px rgba(5, 122, 85, 0.35);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .brand-btn-gradient:hover {
        background: linear-gradient(135deg, #068a60 0%, #057a55 100%);
        box-shadow: 0 6px 20px -2px rgba(5, 122, 85, 0.45);
        transform: translateY(-1px);
    }
    .brand-btn-gradient:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px -1px rgba(5, 122, 85, 0.3);
    }

    .auth-card-shadow {
        box-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(241, 245, 249, 0.8);
    }

    .shimmer-badge {
        background: linear-gradient(90deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.05) 100%);
        background-size: 200% 100%;
        animation: shimmerFlow 3.5s infinite;
    }
    @keyframes shimmerFlow {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f8fafc] flex flex-col justify-center font-jakarta selection:bg-emerald-600 selection:text-white">

    <!-- ========================================================================= -->
    <!--                                MOBILE VIEW                                -->
    <!-- ========================================================================= -->
    <div class="md:hidden min-h-screen py-6 px-4 flex flex-col justify-between bg-gradient-to-b from-[#f0fdf4]/50 via-slate-50 to-slate-100">
        <!-- Top App Bar -->
        <div class="flex items-center justify-between mb-4">
            <button onclick="window.history.back()" class="w-9 h-9 rounded-xl bg-white shadow-2xs border border-slate-200 flex items-center justify-center text-slate-700 tap-effect" aria-label="Go Back">
                <i class="fas fa-arrow-left text-xs"></i>
            </button>
            <a href="{{ route('user.home') }}" class="flex items-center" title="SpaceSeeks Home">
                <img src="{{ asset('images/spaceseeks-logo.png') }}" alt="SpaceSeeks" class="h-8 w-auto object-contain">
            </a>
            <a href="{{ route('user.home') }}" class="w-9 h-9 rounded-xl bg-white shadow-2xs border border-slate-200 flex items-center justify-center text-[#057a55] tap-effect" aria-label="Home">
                <i class="fas fa-house-chimney text-xs"></i>
            </a>
        </div>

        <!-- Main Card on Mobile -->
        <div class="bg-white rounded-3xl p-6 mb-4 auth-card-shadow border border-slate-100/80">
            <!-- Mobile Header -->
            <div class="text-center mb-5" id="mob-header-box">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight" id="mob-auth-title">Welcome Back</h1>
                <p class="text-xs text-slate-500 mt-1" id="mob-auth-subtitle">Sign in to your SpaceSeeks account</p>
            </div>

            <!-- Error Box -->
            <div id="mob-error-alert" class="{{ session('flash_error') ? '' : 'hidden' }} mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-start gap-2.5 shadow-2xs">
                <i class="fas fa-circle-exclamation text-red-500 text-sm mt-0.5 shrink-0"></i>
                <span id="mob-error-msg" class="font-medium leading-relaxed">{{ session('flash_error') }}</span>
            </div>

            <!-- Success Box -->
            <div id="mob-success-alert" class="{{ session('flash_success') ? '' : 'hidden' }} mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-start gap-2.5 shadow-2xs">
                <i class="fas fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                <span id="mob-success-msg" class="font-medium leading-relaxed">{{ session('flash_success') }}</span>
            </div>

            <!-- Segmented Pill Switcher -->
            <div id="mob-tabs-switcher" class="flex p-1 bg-slate-100 rounded-xl mb-5">
                <button onclick="switchTab('login')" id="loginTab" class="flex-1 py-2.5 bg-[#057a55] text-white text-xs font-bold rounded-lg tap-effect transition-all shadow-xs">Login</button>
                <button onclick="switchTab('signup')" id="signupTab" class="flex-1 py-2.5 text-slate-600 text-xs font-semibold rounded-lg tap-effect transition-all">Sign Up</button>
            </div>

            <!-- MOBILE LOGIN FORM -->
            <div id="loginForm" class="space-y-4">
                <form onsubmit="handleApiAuth(event, 'login', 'mob')" novalidate>
                    <div class="space-y-3.5">
                        <!-- Email or Mobile -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email or Mobile Number <span class="text-red-500">*</span></label>
                            <div class="auth-input-container">
                                <i class="fas fa-envelope auth-icon absolute left-3.5 top-3 text-slate-400 text-sm pointer-events-none transition-colors"></i>
                                <input type="text" 
                                    id="mob_login_input" 
                                    placeholder="name@example.com" 
                                    minlength="3" 
                                    maxlength="150" 
                                    required
                                    oninput="validateLoginInput('mob')"
                                    class="w-full rounded-xl py-2.5 pl-10 pr-3 text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                            </div>
                            <p id="mob_login_input_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="auth-input-container">
                                <i class="fas fa-lock auth-icon absolute left-3.5 top-3 text-slate-400 text-sm pointer-events-none transition-colors"></i>
                                <input type="password" 
                                    id="mob_login_pass" 
                                    placeholder="••••••••••••" 
                                    minlength="6" 
                                    maxlength="100" 
                                    required
                                    oninput="validateLoginPassword('mob')"
                                    class="w-full rounded-xl py-2.5 pl-10 pr-10 text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                <button type="button" onclick="togglePass('mob_login_pass', this)" class="absolute right-3.5 top-2.5 text-slate-400 hover:text-slate-700 p-1" aria-label="Toggle Password Visibility">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                            <p id="mob_login_pass_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between pt-0.5">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#057a55] focus:ring-[#057a55]" checked>
                                <span class="text-xs text-slate-600 font-medium">Remember me</span>
                            </label>
                            <button type="button" onclick="switchTab('forgot')" class="text-xs text-[#057a55] font-semibold hover:underline">Forgot Password?</button>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="mob_login_btn" class="w-full brand-btn-gradient text-white font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-2 text-sm mt-2">
                            <span>Sign In</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                    <div class="relative flex justify-center"><span class="bg-white px-3 text-[10px] text-slate-400 font-bold uppercase tracking-wider">or continue with</span></div>
                </div>

                <!-- Google SSO -->
                <a href="{{ route('auth.google') }}" class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-medium py-2.5 rounded-xl tap-effect flex items-center justify-center gap-2.5 shadow-2xs transition text-xs">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    Continue with Google
                </a>

                <!-- Switch Footer -->
                <div class="text-center pt-2">
                    <p class="text-xs text-slate-500">
                        Don't have an account? 
                        <button type="button" onclick="switchTab('signup')" class="text-[#057a55] font-bold hover:underline ml-1">Sign Up</button>
                    </p>
                </div>
            </div>

            <!-- MOBILE SIGNUP FORM -->
            <div id="signupForm" class="space-y-4 hidden">
                <div id="mob_signup_step1" class="space-y-3.5">
                    <form onsubmit="handleSignupRequest(event, 'mob')" novalidate>
                        <div class="space-y-3">
                            <!-- Account Type Selector -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Account Type <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-2.5">
                                    <!-- Tenant -->
                                    <label id="mob_role_card_tenant" onclick="selectRole('mob', 'tenant')" class="relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all select-none bg-white shadow-xs border-2 border-[#057a55] ring-2 ring-emerald-500/10">
                                        <input type="radio" name="mob_role" value="tenant" checked class="sr-only">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#057a55] flex items-center justify-center text-sm shrink-0">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-xs text-slate-900 leading-tight">Tenant</span>
                                                <span id="mob_role_badge_tenant" class="w-4 h-4 rounded-full bg-[#057a55] text-white flex items-center justify-center text-[9px] shadow-xs">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            </div>
                                            <p class="text-[10px] text-slate-500 truncate mt-0.5">Looking for PG</p>
                                        </div>
                                    </label>

                                    <!-- PG Owner -->
                                    <label id="mob_role_card_broker" onclick="selectRole('mob', 'broker')" class="relative flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer transition-all select-none bg-slate-50 border-2 border-transparent hover:bg-white">
                                        <input type="radio" name="mob_role" value="broker" class="sr-only">
                                        <div class="w-8 h-8 rounded-lg bg-slate-200/70 text-slate-600 flex items-center justify-center text-sm shrink-0">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-xs text-slate-700 leading-tight">PG Owner</span>
                                                <span id="mob_role_badge_broker" class="w-4 h-4 rounded-full border border-slate-300 text-transparent flex items-center justify-center text-[9px]">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            </div>
                                            <p class="text-[10px] text-slate-500 truncate mt-0.5">List & Manage</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Name fields (2 cols) -->
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                    <div class="auth-input-container">
                                        <i class="fas fa-user auth-icon absolute left-3 top-2.5 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                        <input type="text" id="mob_first_name" placeholder="e.g. Rahul" minlength="2" maxlength="50" required
                                            class="w-full rounded-xl py-2 pl-8 pr-2.5 text-xs text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Last Name</label>
                                    <div class="auth-input-container">
                                        <i class="fas fa-user auth-icon absolute left-3 top-2.5 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                        <input type="text" id="mob_last_name" placeholder="e.g. Sharma" maxlength="50"
                                            class="w-full rounded-xl py-2 pl-8 pr-2.5 text-xs text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                    </div>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address (Gmail) <span class="text-red-500">*</span></label>
                                <div class="auth-input-container">
                                    <i class="fas fa-envelope auth-icon absolute left-3 top-2.5 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                    <input type="email" id="mob_signup_email" placeholder="name@gmail.com" minlength="5" maxlength="150" required
                                        class="w-full rounded-xl py-2 pl-8 pr-3 text-xs text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">We will send a 6-digit OTP code to verify this Gmail address.</p>
                            </div>

                            <!-- Mobile Number -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                                <div class="flex rounded-xl border border-slate-200 bg-[#f8fafc] focus-within:bg-white focus-within:border-[#057a55] focus-within:ring-2 focus-within:ring-emerald-500/15 transition-all overflow-hidden">
                                    <div class="flex items-center gap-1 bg-slate-100 px-2.5 py-2 border-r border-slate-200 text-xs font-bold text-slate-700 select-none shrink-0">
                                        <span>🇮🇳</span>
                                        <span class="tracking-wide text-slate-800">+91</span>
                                    </div>
                                    <input type="tel" 
                                           id="mob_signup_phone" 
                                           placeholder="98765 43210" 
                                           required
                                           minlength="10"
                                           maxlength="10"
                                           inputmode="numeric"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                           class="w-full bg-transparent px-3 py-2 text-xs font-semibold tracking-wider text-slate-900 focus:outline-none placeholder:text-slate-400 placeholder:font-normal placeholder:tracking-normal">
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                                <div class="auth-input-container">
                                    <i class="fas fa-lock auth-icon absolute left-3 top-2.5 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                    <input type="password" id="mob_signup_pass" placeholder="••••••••••••" minlength="6" maxlength="100" required
                                        oninput="checkPasswordStrength('mob_signup_pass', 'mob_signup_pwd_bar', 'mob_signup_pwd_text')"
                                        class="w-full rounded-xl py-2 pl-8 pr-8 text-xs text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                    <button type="button" onclick="togglePass('mob_signup_pass', this)" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-700 p-0.5">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                                <div class="mt-1 flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 h-1 rounded-full overflow-hidden">
                                        <div id="mob_signup_pwd_bar" class="h-full bg-red-400 transition-all duration-300 w-0"></div>
                                    </div>
                                    <span id="mob_signup_pwd_text" class="text-[10px] font-semibold text-slate-400 shrink-0">Password strength</span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                                <div class="auth-input-container">
                                    <i class="fas fa-lock auth-icon absolute left-3 top-2.5 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                    <input type="password" id="mob_signup_confirm_pass" placeholder="••••••••••••" minlength="6" maxlength="100" required
                                        class="w-full rounded-xl py-2 pl-8 pr-8 text-xs text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                    <button type="button" onclick="togglePass('mob_signup_confirm_pass', this)" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-700 p-0.5">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Terms -->
                            <label class="flex items-start gap-2 pt-0.5 cursor-pointer select-none">
                                <input type="checkbox" id="mob_signup_terms" required class="w-4 h-4 rounded border-slate-300 text-[#057a55] focus:ring-[#057a55] mt-0.5" checked>
                                <span class="text-[11px] text-slate-600">I agree to SpaceSeeks <a href="{{ route('user.terms') }}" class="text-[#057a55] font-semibold hover:underline">Terms of Service</a> and <a href="{{ route('user.privacy') }}" class="text-[#057a55] font-semibold hover:underline">Privacy Policy</a></span>
                            </label>

                            <!-- Submit -->
                            <button type="submit" id="mob_signup_step1_btn" class="w-full brand-btn-gradient text-white font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm mt-1">
                                <span>Send Verification Code</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </button>

                            <!-- Divider -->
                            <div class="relative py-1.5">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                                <div class="relative flex justify-center"><span class="bg-white px-2.5 text-[10px] text-slate-400 font-bold uppercase tracking-wider">or sign up with</span></div>
                            </div>

                            <button type="button" onclick="handleGoogleSignup('mob')" class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-medium py-2.5 rounded-xl tap-effect flex items-center justify-center gap-2 shadow-2xs transition text-xs">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                Sign Up with Google
                            </button>

                            <p class="text-[10px] text-center text-slate-400 mt-2">By creating an account, you agree to receive important updates from SpaceSeeks.</p>
                        </div>
                    </form>
                </div>

                <!-- Signup Step 2 (OTP) -->
                <div id="mob_signup_step2" class="space-y-4 hidden">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 shadow-2xs">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#057a55] flex items-center justify-center text-sm shrink-0">
                                <i class="fas fa-envelope-circle-check"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-xs">Verification Code Sent!</div>
                                <div class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                                    We sent a 6-digit code to <strong id="mob_signup_target_email" class="text-[#057a55] font-bold"></strong>. Please check your Gmail inbox.
                                </div>
                            </div>
                        </div>
                    </div>

                    <form onsubmit="handleSignupVerify(event, 'mob')" novalidate>
                        <div class="space-y-3.5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-2 text-center">Enter 6-Digit Email OTP <span class="text-red-500">*</span></label>
                                <input type="tel" 
                                    id="mob_signup_otp" 
                                    placeholder="• • • • • •" 
                                    maxlength="6" 
                                    minlength="6" 
                                    required
                                    inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                    class="w-full bg-white border-2 border-emerald-500/40 rounded-2xl py-3 px-3 text-center text-xl font-mono font-bold tracking-[0.3em] text-slate-900 focus:outline-none focus:border-[#057a55] focus:ring-4 focus:ring-emerald-500/15 transition-all">
                                <div class="flex items-center justify-between mt-2 text-xs">
                                    <span class="text-slate-400 text-[11px]">Didn't receive OTP?</span>
                                    <button type="button" id="mob_signup_resend_btn" onclick="handleResendSignupOtp('mob')" disabled class="font-semibold text-[#057a55] disabled:text-slate-400 hover:underline cursor-pointer disabled:cursor-not-allowed text-xs">
                                        Resend Code <span id="mob_signup_countdown">(60s)</span>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" id="mob_signup_step2_btn" class="w-full brand-btn-gradient text-white font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <i class="fas fa-shield-check text-xs"></i>
                                <span>Verify OTP & Create Account</span>
                            </button>
                            <button type="button" onclick="backToSignupStep1('mob')" class="w-full text-center text-xs text-slate-500 font-semibold hover:text-[#057a55] py-1">
                                <i class="fas fa-arrow-left mr-1"></i> Edit Details / Change Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MOBILE FORGOT PASSWORD FLOW -->
            <div id="forgotForm" class="space-y-4 hidden">
                <!-- Step 1 -->
                <div id="mob_forgot_step1" class="space-y-3.5">
                    <div class="mb-2">
                        <button type="button" onclick="switchTab('login')" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#057a55] transition mb-2">
                            <i class="fas fa-arrow-left"></i> Back to Login
                        </button>
                        <h2 class="text-lg font-bold text-slate-900">Reset Password</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Enter your registered email address to receive a 6-digit OTP code.</p>
                    </div>

                    <form onsubmit="handleForgotRequest(event, 'mob')" novalidate>
                        <div class="space-y-3.5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Registered Email Address <span class="text-red-500">*</span></label>
                                <div class="auth-input-container">
                                    <i class="fas fa-envelope auth-icon absolute left-3.5 top-3 text-slate-400 text-sm pointer-events-none transition-colors"></i>
                                    <input type="email" 
                                        id="mob_forgot_login" 
                                        placeholder="name@example.com" 
                                        minlength="5" 
                                        maxlength="150" 
                                        required
                                        oninput="validateForgotLoginInput('mob')"
                                        class="w-full rounded-xl py-2.5 pl-10 pr-3 text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                </div>
                                <p id="mob_forgot_login_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                            </div>
                            <button type="submit" id="mob_forgot_step1_btn" class="w-full brand-btn-gradient text-white font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <span>Send Verification Code</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 2 -->
                <div id="mob_forgot_step2" class="space-y-4 hidden">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-3.5 shadow-2xs">
                        <div class="flex items-start gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0 text-[#057a55] text-xs mt-0.5">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-xs">Verification Code Sent!</div>
                                <div class="text-[11px] text-slate-600 mt-0.5">We sent a 6-digit OTP code to <span id="mob_forgot_target_display" class="font-bold text-[#057a55]"></span>.</div>
                            </div>
                        </div>
                    </div>

                    <form onsubmit="handleForgotReset(event, 'mob')" novalidate>
                        <div class="space-y-3.5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">6-Digit Verification Code <span class="text-red-500">*</span></label>
                                <input type="tel" 
                                    id="mob_forgot_otp" 
                                    placeholder="• • • • • •" 
                                    maxlength="6" 
                                    minlength="6" 
                                    required
                                    inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                    class="w-full bg-white border-2 border-emerald-500/40 rounded-xl py-2 px-3 text-center text-lg font-mono font-bold tracking-widest text-slate-900 focus:outline-none focus:border-[#057a55] transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">New Password <span class="text-red-500">*</span></label>
                                <div class="auth-input-container">
                                    <i class="fas fa-lock auth-icon absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                                    <input type="password" 
                                        id="mob_forgot_new_pass" 
                                        placeholder="Enter new password (min 6 chars)" 
                                        minlength="6" 
                                        maxlength="100" 
                                        required
                                        oninput="checkPasswordStrength('mob_forgot_new_pass', 'mob_pwd_bar', 'mob_pwd_text')"
                                        class="w-full rounded-xl py-2 pl-9 pr-9 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                    <button type="button" onclick="togglePass('mob_forgot_new_pass', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                                <div class="mt-1 flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 h-1 rounded-full overflow-hidden">
                                        <div id="mob_pwd_bar" class="h-full bg-red-400 transition-all duration-300 w-0"></div>
                                    </div>
                                    <span id="mob_pwd_text" class="text-[10px] font-semibold text-slate-400 shrink-0">Password strength</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                                <div class="auth-input-container">
                                    <i class="fas fa-lock auth-icon absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                                    <input type="password" 
                                        id="mob_forgot_confirm_pass" 
                                        placeholder="Re-enter new password" 
                                        minlength="6" 
                                        maxlength="100" 
                                        required
                                        class="w-full rounded-xl py-2 pl-9 pr-9 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                    <button type="button" onclick="togglePass('mob_forgot_confirm_pass', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-0.5">
                                <button type="button" id="mob_resend_otp_btn" onclick="resendForgotOtp('mob')" class="text-xs text-[#057a55] font-semibold hover:underline">Resend OTP</button>
                                <span id="mob_resend_timer" class="text-xs text-slate-400 font-mono font-bold hidden">60s</span>
                            </div>

                            <button type="submit" id="mob_forgot_reset_btn" class="w-full brand-btn-gradient text-white font-semibold py-3 rounded-xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm">
                                <span>Save New Password</span>
                            </button>
                            <button type="button" onclick="backToForgotStep1('mob')" class="w-full text-center text-xs text-slate-500 font-semibold hover:text-[#057a55] py-0.5">
                                <i class="fas fa-arrow-left mr-1"></i> Change Email
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3 -->
                <div id="mob_forgot_step3" class="space-y-4 text-center py-5 hidden">
                    <div class="w-14 h-14 bg-emerald-100 text-[#057a55] rounded-2xl flex items-center justify-center mx-auto text-xl shadow-2xs border border-emerald-200">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Password Reset Complete!</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Your account password has been updated securely. You can now log in with your new credentials.</p>
                    </div>
                    <button type="button" onclick="proceedToLoginWithUser('mob')" class="w-full brand-btn-gradient text-white font-semibold py-3 rounded-xl tap-effect text-xs sm:text-sm">
                        Proceed to Login
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Footer -->
        <div class="text-center text-[11px] text-slate-400 py-2">
            &copy; {{ date('Y') }} SpaceSeeks. 100% Zero Brokerage Verified PGs.
        </div>
    </div>


    <!-- ========================================================================= -->
    <!--                       DESKTOP / TABLET SPLIT-SCREEN                       -->
    <!-- ========================================================================= -->
    <div class="hidden md:block min-h-screen">
        <div class="min-h-screen flex">
            
            <!-- ================= LEFT HERO BRAND PANEL ================= -->
            <div class="w-1/2 xl:w-7/12 bg-[#032a22] staynest-hero-bg relative overflow-hidden flex flex-col justify-between p-8 xl:p-12 2xl:p-14 text-white">
                <!-- Background Ambient Glow & Grid -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:40px_40px] pointer-events-none"></div>

                <!-- Top Brand Header -->
                <div class="relative z-10 flex items-center justify-between">
                    <a href="{{ route('user.home') }}" class="inline-flex items-center gap-3 group" title="SpaceSeeks Home">
                        <div class="bg-white/95 px-3.5 py-2 rounded-2xl shadow-xl group-hover:scale-105 transition-transform">
                            <img src="{{ asset('images/spaceseeks-logo.png') }}" alt="SpaceSeeks" class="h-8 w-auto object-contain">
                        </div>
                    </a>

                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-950/70 border border-emerald-500/30 text-xs font-semibold text-emerald-300 shimmer-badge shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Zero Brokerage</span>
                    </div>
                </div>

                <!-- Center Section (Text propositions + Inset Hero Bedroom Card) -->
                <div class="relative z-10 my-auto py-6 grid grid-cols-1 xl:grid-cols-12 gap-8 items-center">
                    <!-- Left Proposition Column -->
                    <div class="xl:col-span-7 space-y-6">
                        <div>
                            <h1 class="text-3xl xl:text-4xl 2xl:text-[44px] font-black leading-[1.15] tracking-tight text-white">
                                Find Your <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-teal-200 to-emerald-400">Ideal Stay</span> <br>
                                Directly with Hosts.
                            </h1>
                            <p class="text-slate-300/90 text-sm leading-relaxed mt-3 max-w-md">
                                Explore verified PGs, hostels & co-living spaces across India with genuine amenities, zero broker commission, and instant schedule visits.
                            </p>
                        </div>

                        <!-- 3 Proposition Feature Cards -->
                        <div class="space-y-3 max-w-md">
                            <!-- Card 1 -->
                            <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-white/[0.04] backdrop-blur-md border border-white/10 hover:bg-white/[0.08] transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0 border border-teal-400/30 text-sm mt-0.5">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-white">Zero Brokerage Guarantee</div>
                                    <div class="text-xs text-slate-300/80 mt-0.5">Direct landlord contact with ₹0 extra commission.</div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-white/[0.04] backdrop-blur-md border border-white/10 hover:bg-white/[0.08] transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0 border border-emerald-400/30 text-sm mt-0.5">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-white">Instant Visit Scheduling</div>
                                    <div class="text-xs text-slate-300/80 mt-0.5">Schedule on-site visits with 1-click confirmation.</div>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-white/[0.04] backdrop-blur-md border border-white/10 hover:bg-white/[0.08] transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-300 flex items-center justify-center shrink-0 border border-cyan-400/30 text-sm mt-0.5">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-white">Verified & Safe Listings</div>
                                    <div class="text-xs text-slate-300/80 mt-0.5">Only genuine and verified properties.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Inset Visual Card (Modern Bedroom Card with Floating Pill) -->
                    <div class="hidden xl:block xl:col-span-5">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border-2 border-white/15 group">
                            <!-- Hero Bedroom Photo -->
                            <img src="{{ asset('images/auth-bedroom.jpg') }}" alt="SpaceSeeks Verified PG Room" class="w-full h-[380px] 2xl:h-[420px] object-cover group-hover:scale-105 transition-transform duration-700">

                            <!-- Subtle gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/20 pointer-events-none"></div>

                            <!-- Floating Glass Pill at Bottom -->
                            <div class="absolute bottom-4 left-4 right-4 flex justify-center">
                                <div class="glass-pill px-4 py-2.5 rounded-2xl flex items-center justify-between w-full shadow-lg">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-white">
                                        <i class="fas fa-location-dot text-emerald-400"></i>
                                        <span>Live Better Together</span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white text-xs">
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Stats & Social Proof Bar -->
                <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between">
                    <!-- Numbers Stat Row -->
                    <div class="flex items-center gap-6 2xl:gap-8">
                        <div>
                            <div class="text-xl 2xl:text-2xl font-black text-white leading-none">10K+</div>
                            <div class="text-[10px] 2xl:text-xs text-emerald-200/80 mt-1 font-medium">Verified Listings</div>
                        </div>
                        <div class="w-px h-8 bg-white/15"></div>
                        <div>
                            <div class="text-xl 2xl:text-2xl font-black text-white leading-none">50+</div>
                            <div class="text-[10px] 2xl:text-xs text-emerald-200/80 mt-1 font-medium">Cities</div>
                        </div>
                        <div class="w-px h-8 bg-white/15"></div>
                        <div>
                            <div class="text-xl 2xl:text-2xl font-black text-white leading-none">1M+</div>
                            <div class="text-[10px] 2xl:text-xs text-emerald-200/80 mt-1 font-medium">Happy Users</div>
                        </div>
                    </div>

                    <!-- Social Proof / Cursive Typography -->
                    <div class="flex items-center gap-4">
                        <div class="hidden 2xl:flex items-center gap-2.5">
                            <div class="flex -space-x-2">
                                <img class="w-8 h-8 rounded-full border-2 border-[#032a22] object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Tenant">
                                <img class="w-8 h-8 rounded-full border-2 border-[#032a22] object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80" alt="Tenant">
                                <img class="w-8 h-8 rounded-full border-2 border-[#032a22] object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80" alt="Tenant">
                            </div>
                            <div class="text-xs">
                                <div class="font-bold text-white flex items-center gap-1 text-xs">
                                    <span>4.9 / 5.0</span>
                                    <span class="text-amber-400 text-[10px]">★★★★★</span>
                                </div>
                                <span class="text-[10px] text-slate-300/80">Loved by 50,000+ Tenants</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="font-handwriting text-2xl 2xl:text-3xl text-emerald-300 font-bold leading-tight -rotate-3 select-none">
                                More Than <br> A Stay
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ================= RIGHT AUTHENTICATION PANEL ================= -->
            <div class="w-1/2 xl:w-5/12 min-h-screen flex flex-col justify-between items-center p-6 sm:p-10 bg-[#f8fafc] relative overflow-y-auto">
                
                <!-- Floating Top Right Action -->
                <div class="w-full flex justify-end">
                    <div id="desk_top_action_login" class="block">
                        <a href="{{ route('user.home') }}" class="text-xs font-semibold text-slate-600 hover:text-[#057a55] bg-white px-4 py-2 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-2 transition tap-effect">
                            <i class="fas fa-home text-[#057a55]"></i> Back to Home
                        </a>
                    </div>
                    <div id="desk_top_action_signup" class="hidden">
                        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                            <span>Already have an account?</span>
                            <button type="button" onclick="switchTab('login')" class="font-bold text-[#057a55] hover:text-[#046c4e] bg-white px-3.5 py-1.5 rounded-xl border border-slate-200/80 shadow-2xs transition tap-effect">
                                Login
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Center Auth Card Container -->
                <div class="w-full max-w-[440px] my-auto py-6">
                    <div class="bg-white rounded-[28px] p-7 sm:p-9 auth-card-shadow border border-slate-100/90 relative">
                        
                        <!-- Desktop Header Box -->
                        <div class="text-center mb-6" id="desk-header-box">
                            <h2 id="desktop-title" class="text-2xl 2xl:text-3xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h2>
                            <p id="desktop-subtitle" class="text-slate-500 text-xs mt-1">Sign in to your SpaceSeeks account</p>
                        </div>

                        <!-- Error Alert Box -->
                        <div id="desk-error-alert" class="{{ session('flash_error') ? '' : 'hidden' }} mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl flex items-start gap-2.5 shadow-2xs">
                            <i class="fas fa-circle-exclamation text-red-500 text-sm mt-0.5 shrink-0"></i>
                            <span id="desk-error-msg" class="font-medium leading-relaxed">{{ session('flash_error') }}</span>
                        </div>

                        <!-- Success Alert Box -->
                        <div id="desk-success-alert" class="{{ session('flash_success') ? '' : 'hidden' }} mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-2xl flex items-start gap-2.5 shadow-2xs">
                            <i class="fas fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span id="desk-success-msg" class="font-medium leading-relaxed">{{ session('flash_success') }}</span>
                        </div>

                        <!-- Desktop Segmented Switcher (Login / Sign Up) -->
                        <div id="desk-tabs-switcher" class="flex p-1 bg-slate-100 rounded-2xl mb-6">
                            <button onclick="switchTab('login')" id="loginTabD" class="flex-1 py-2.5 bg-[#057a55] text-white text-xs font-bold rounded-xl tap-effect transition-all shadow-xs">Login</button>
                            <button onclick="switchTab('signup')" id="signupTabD" class="flex-1 py-2.5 text-slate-600 text-xs font-semibold rounded-xl tap-effect transition-all">Sign Up</button>
                        </div>

                        <!-- ================= DESKTOP LOGIN FORM ================= -->
                        <div id="loginFormD" class="space-y-4">
                            <form onsubmit="handleApiAuth(event, 'login', 'desk')" novalidate>
                                <div class="space-y-4">
                                    <!-- Email or Mobile -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email or Mobile Number <span class="text-red-500">*</span></label>
                                        <div class="auth-input-container">
                                            <i class="fas fa-envelope auth-icon absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none transition-colors"></i>
                                            <input type="text" 
                                                id="desk_login_input" 
                                                placeholder="name@example.com" 
                                                minlength="3" 
                                                maxlength="150" 
                                                required
                                                oninput="validateLoginInput('desk')"
                                                class="w-full rounded-2xl py-3 pl-11 pr-4 text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                        </div>
                                        <p id="desk_login_input_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                                    </div>

                                    <!-- Password -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                                        <div class="auth-input-container">
                                            <i class="fas fa-lock auth-icon absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none transition-colors"></i>
                                            <input type="password" 
                                                id="desk_login_pass" 
                                                placeholder="••••••••••••" 
                                                minlength="6" 
                                                maxlength="100" 
                                                required
                                                oninput="validateLoginPassword('desk')"
                                                class="w-full rounded-2xl py-3 pl-11 pr-11 text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                            <button type="button" onclick="togglePass('desk_login_pass', this)" class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-700 p-1" aria-label="Toggle Password Visibility">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                        </div>
                                        <p id="desk_login_pass_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                                    </div>

                                    <!-- Remember & Forgot -->
                                    <div class="flex items-center justify-between pt-0.5">
                                        <label class="flex items-center gap-2 cursor-pointer select-none">
                                            <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#057a55] focus:ring-[#057a55]" checked>
                                            <span class="text-xs text-slate-600 font-medium">Remember me</span>
                                        </label>
                                        <button type="button" onclick="switchTab('forgot')" class="text-xs text-[#057a55] font-semibold hover:underline">Forgot Password?</button>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" id="desk_login_btn" class="w-full brand-btn-gradient text-white font-semibold py-3.5 rounded-2xl tap-effect flex items-center justify-center gap-2 text-sm mt-2">
                                        <span>Sign In</span>
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </button>
                                </div>
                            </form>

                            <!-- Divider -->
                            <div class="relative py-2.5">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                                <div class="relative flex justify-center"><span class="bg-white px-3 text-[10px] text-slate-400 font-bold uppercase tracking-wider">or continue with</span></div>
                            </div>

                            <!-- Google SSO -->
                            <a href="{{ route('auth.google') }}" class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-medium py-3 rounded-2xl tap-effect flex items-center justify-center gap-2.5 shadow-2xs transition text-xs sm:text-sm">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                Continue with Google
                            </a>

                            <!-- Switch to Sign up link -->
                            <div class="text-center pt-2">
                                <p class="text-xs text-slate-500">
                                    Don't have an account? 
                                    <button type="button" onclick="switchTab('signup')" class="text-[#057a55] font-bold hover:underline ml-1">Sign Up</button>
                                </p>
                            </div>

                            <!-- Trust Badge -->
                            <div class="pt-2 text-center flex items-center justify-center gap-1.5 text-[11px] text-slate-400">
                                <i class="fas fa-lock text-[10px]"></i>
                                <span>Your data is secure with us</span>
                            </div>
                        </div>

                        <!-- ================= DESKTOP SIGNUP FORM ================= -->
                        <div id="signupFormD" class="space-y-4 hidden">
                            <!-- Step 1 Form -->
                            <div id="desk_signup_step1" class="space-y-3.5">
                                <form onsubmit="handleSignupRequest(event, 'desk')" novalidate>
                                    <div class="space-y-3.5">
                                        <!-- Role Selection Cards -->
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Account Type <span class="text-red-500">*</span></label>
                                            <div class="grid grid-cols-2 gap-3">
                                                <!-- Tenant -->
                                                <label id="desk_role_card_tenant" onclick="selectRole('desk', 'tenant')" class="relative flex items-center gap-2.5 p-3 rounded-2xl cursor-pointer transition-all select-none bg-white shadow-xs border-2 border-[#057a55] ring-2 ring-emerald-500/10">
                                                    <input type="radio" name="desk_role" value="tenant" checked class="sr-only">
                                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-[#057a55] flex items-center justify-center text-sm shrink-0">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between">
                                                            <span class="font-bold text-xs text-slate-900 leading-tight">Tenant</span>
                                                            <span id="desk_role_badge_tenant" class="w-4 h-4 rounded-full bg-[#057a55] text-white flex items-center justify-center text-[9px] shadow-xs">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-[10px] text-slate-500 truncate mt-0.5">Looking for PG</p>
                                                    </div>
                                                </label>

                                                <!-- PG Owner -->
                                                <label id="desk_role_card_broker" onclick="selectRole('desk', 'broker')" class="relative flex items-center gap-2.5 p-3 rounded-2xl cursor-pointer transition-all select-none bg-slate-50 border-2 border-transparent hover:bg-white">
                                                    <input type="radio" name="desk_role" value="broker" class="sr-only">
                                                    <div class="w-8 h-8 rounded-xl bg-slate-200/70 text-slate-600 flex items-center justify-center text-sm shrink-0">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between">
                                                            <span class="font-bold text-xs text-slate-700 leading-tight">PG Owner</span>
                                                            <span id="desk_role_badge_broker" class="w-4 h-4 rounded-full border border-slate-300 text-transparent flex items-center justify-center text-[9px]">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-[10px] text-slate-500 truncate mt-0.5">List & Manage</p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Name Fields (2 cols) -->
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                                <div class="auth-input-container">
                                                    <i class="fas fa-user auth-icon absolute left-3.5 top-3 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                                    <input type="text" id="desk_first_name" placeholder="e.g. Rahul" minlength="2" maxlength="50" required
                                                        class="w-full rounded-xl py-2.5 pl-9 pr-3 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">Last Name</label>
                                                <div class="auth-input-container">
                                                    <i class="fas fa-user auth-icon absolute left-3.5 top-3 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                                    <input type="text" id="desk_last_name" placeholder="e.g. Sharma" maxlength="50"
                                                        class="w-full rounded-xl py-2.5 pl-9 pr-3 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Gmail Address -->
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address (Gmail) <span class="text-red-500">*</span></label>
                                            <div class="auth-input-container">
                                                <i class="fas fa-envelope auth-icon absolute left-3.5 top-3 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                                <input type="email" id="desk_signup_email" placeholder="name@gmail.com" minlength="5" maxlength="150" required
                                                    class="w-full rounded-xl py-2.5 pl-9 pr-3 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-1">We will send a 6-digit OTP code to verify this Gmail address.</p>
                                        </div>

                                        <!-- Mobile Number -->
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                                            <div class="flex rounded-xl border border-slate-200 bg-[#f8fafc] focus-within:bg-white focus-within:border-[#057a55] focus-within:ring-2 focus-within:ring-emerald-500/15 transition-all overflow-hidden">
                                                <div class="flex items-center gap-1.5 bg-slate-100 px-3 py-2.5 border-r border-slate-200 text-xs font-bold text-slate-700 select-none shrink-0">
                                                    <span>🇮🇳</span>
                                                    <span class="tracking-wide text-slate-800">+91</span>
                                                </div>
                                                <input type="tel" 
                                                       id="desk_signup_phone" 
                                                       placeholder="98765 43210" 
                                                       required
                                                       minlength="10"
                                                       maxlength="10"
                                                       inputmode="numeric"
                                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                                       class="w-full bg-transparent px-3 py-2 text-xs sm:text-sm font-semibold tracking-wider text-slate-900 focus:outline-none placeholder:text-slate-400 placeholder:font-normal placeholder:tracking-normal">
                                            </div>
                                        </div>

                                        <!-- Password -->
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                                            <div class="auth-input-container">
                                                <i class="fas fa-lock auth-icon absolute left-3.5 top-3 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                                <input type="password" id="desk_signup_pass" placeholder="••••••••••••" minlength="6" maxlength="100" required
                                                    oninput="checkPasswordStrength('desk_signup_pass', 'desk_signup_pwd_bar', 'desk_signup_pwd_text')"
                                                    class="w-full rounded-xl py-2.5 pl-9 pr-9 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                                <button type="button" onclick="togglePass('desk_signup_pass', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 p-1">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                            </div>
                                            <!-- Password strength bar -->
                                            <div class="mt-1 flex items-center gap-2">
                                                <div class="flex-1 bg-slate-200 h-1 rounded-full overflow-hidden">
                                                    <div id="desk_signup_pwd_bar" class="h-full bg-red-400 transition-all duration-300 w-0"></div>
                                                </div>
                                                <span id="desk_signup_pwd_text" class="text-[10px] font-semibold text-slate-400 shrink-0">Password strength</span>
                                            </div>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                                            <div class="auth-input-container">
                                                <i class="fas fa-lock auth-icon absolute left-3.5 top-3 text-slate-400 text-xs pointer-events-none transition-colors"></i>
                                                <input type="password" id="desk_signup_confirm_pass" placeholder="••••••••••••" minlength="6" maxlength="100" required
                                                    class="w-full rounded-xl py-2.5 pl-9 pr-9 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                                <button type="button" onclick="togglePass('desk_signup_confirm_pass', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 p-1">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Terms -->
                                        <label class="flex items-start gap-2 pt-0.5 cursor-pointer select-none">
                                            <input type="checkbox" id="desk_signup_terms" required class="w-4 h-4 rounded border-slate-300 text-[#057a55] focus:ring-[#057a55] mt-0.5" checked>
                                            <span class="text-xs text-slate-600">I agree to SpaceSeeks <a href="{{ route('user.terms') }}" class="text-[#057a55] font-semibold hover:underline">Terms of Service</a> and <a href="{{ route('user.privacy') }}" class="text-[#057a55] font-semibold hover:underline">Privacy Policy</a></span>
                                        </label>

                                        <!-- Submit -->
                                        <button type="submit" id="desk_signup_step1_btn" class="w-full brand-btn-gradient text-white font-semibold py-3.5 rounded-2xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm mt-1">
                                            <span>Send Verification Code</span>
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </button>

                                        <!-- Social Divider -->
                                        <div class="relative py-2">
                                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                                            <div class="relative flex justify-center"><span class="bg-white px-2.5 text-[10px] text-slate-400 font-bold uppercase tracking-wider">or sign up with</span></div>
                                        </div>

                                        <!-- Social Sign Up Buttons -->
                                        <div class="grid grid-cols-1 gap-2">
                                            <button type="button" onclick="handleGoogleSignup('desk')" class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-medium py-2.5 rounded-xl tap-effect flex items-center justify-center gap-2 shadow-2xs transition text-xs">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                                </svg>
                                                Sign Up with Google
                                            </button>
                                        </div>

                                        <p class="text-[10px] text-center text-slate-400 mt-2">By creating an account, you agree to receive important updates from SpaceSeeks.</p>
                                    </div>
                                </form>
                            </div>

                            <!-- Step 2 (OTP Verification) -->
                            <div id="desk_signup_step2" class="space-y-4 hidden">
                                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 shadow-2xs">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#057a55] flex items-center justify-center text-sm shrink-0">
                                            <i class="fas fa-envelope-circle-check"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-xs">Verification Code Sent!</div>
                                            <div class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                                                We sent a 6-digit code to <strong id="desk_signup_target_email" class="text-[#057a55] font-bold"></strong>. Check your Gmail inbox.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form onsubmit="handleSignupVerify(event, 'desk')" novalidate>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-2 text-center">Enter 6-Digit Email OTP <span class="text-red-500">*</span></label>
                                            <input type="tel" 
                                                id="desk_signup_otp" 
                                                placeholder="• • • • • •" 
                                                maxlength="6" 
                                                minlength="6" 
                                                required
                                                inputmode="numeric"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                                class="w-full bg-white border-2 border-emerald-500/40 rounded-2xl py-3 px-3 text-center text-xl font-mono font-bold tracking-[0.3em] text-slate-900 focus:outline-none focus:border-[#057a55] focus:ring-4 focus:ring-emerald-500/15 transition-all">
                                            <div class="flex items-center justify-between mt-2 text-xs">
                                                <span class="text-slate-400 text-[11px]">Didn't receive OTP?</span>
                                                <button type="button" id="desk_signup_resend_btn" onclick="handleResendSignupOtp('desk')" disabled class="font-semibold text-[#057a55] disabled:text-slate-400 hover:underline cursor-pointer disabled:cursor-not-allowed text-xs">
                                                    Resend Code <span id="desk_signup_countdown">(60s)</span>
                                                </button>
                                            </div>
                                        </div>

                                        <button type="submit" id="desk_signup_step2_btn" class="w-full brand-btn-gradient text-white font-semibold py-3.5 rounded-2xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm">
                                            <i class="fas fa-shield-check text-xs"></i>
                                            <span>Verify OTP & Create Account</span>
                                        </button>
                                        <button type="button" onclick="backToSignupStep1('desk')" class="w-full text-center text-xs text-slate-500 font-semibold hover:text-[#057a55] py-1">
                                            <i class="fas fa-arrow-left mr-1"></i> Edit Details / Change Email
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- ================= DESKTOP FORGOT PASSWORD ================= -->
                        <div id="forgotFormD" class="space-y-4 hidden">
                            <!-- Step 1: Request OTP -->
                            <div id="desk_forgot_step1" class="space-y-4">
                                <div>
                                    <button type="button" onclick="switchTab('login')" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#057a55] transition mb-2 group">
                                        <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-0.5"></i> Back to Login
                                    </button>
                                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Reset Password</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Enter your registered email address to receive a 6-digit OTP code.</p>
                                </div>

                                <form onsubmit="handleForgotRequest(event, 'desk')" novalidate>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Registered Email Address <span class="text-red-500">*</span></label>
                                            <div class="auth-input-container">
                                                <i class="fas fa-envelope auth-icon absolute left-4 top-3.5 text-slate-400 text-sm pointer-events-none transition-colors"></i>
                                                <input type="email" 
                                                    id="desk_forgot_login" 
                                                    placeholder="name@example.com" 
                                                    minlength="5" 
                                                    maxlength="150" 
                                                    required
                                                    oninput="validateForgotLoginInput('desk')"
                                                    class="w-full rounded-2xl py-3 pl-11 pr-4 text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                            </div>
                                            <p id="desk_forgot_login_err" class="text-[11px] text-red-500 mt-1 hidden font-medium"></p>
                                        </div>
                                        <button type="submit" id="desk_forgot_step1_btn" class="w-full brand-btn-gradient text-white font-semibold py-3.5 rounded-2xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm">
                                            <span>Send Verification Code</span>
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Step 2: Verify OTP & Reset Password -->
                            <div id="desk_forgot_step2" class="space-y-4 hidden">
                                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 shadow-2xs">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 text-[#057a55] text-xs mt-0.5">
                                            <i class="fas fa-envelope-open-text"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-xs">Verification Code Sent!</div>
                                            <div class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">We sent a 6-digit OTP code to <span id="desk_forgot_target_display" class="font-bold text-[#057a55]"></span>. Check your inbox.</div>
                                        </div>
                                    </div>
                                </div>

                                <form onsubmit="handleForgotReset(event, 'desk')" novalidate>
                                    <div class="space-y-3.5">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">6-Digit Verification Code <span class="text-red-500">*</span></label>
                                            <input type="tel" 
                                                id="desk_forgot_otp" 
                                                placeholder="• • • • • •" 
                                                maxlength="6" 
                                                minlength="6" 
                                                required
                                                inputmode="numeric"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                                class="w-full bg-white border-2 border-emerald-500/40 rounded-xl py-2.5 px-3 text-center text-lg font-mono font-bold tracking-widest text-slate-900 focus:outline-none focus:border-[#057a55] transition-all">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">New Password <span class="text-red-500">*</span></label>
                                            <div class="auth-input-container">
                                                <i class="fas fa-lock auth-icon absolute left-3.5 top-3 text-slate-400 text-xs pointer-events-none"></i>
                                                <input type="password" 
                                                    id="desk_forgot_new_pass" 
                                                    placeholder="Enter new password (min 6 chars)" 
                                                    minlength="6" 
                                                    maxlength="100" 
                                                    required
                                                    oninput="checkPasswordStrength('desk_forgot_new_pass', 'desk_pwd_bar', 'desk_pwd_text')"
                                                    class="w-full rounded-xl py-2.5 pl-9 pr-9 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                                <button type="button" onclick="togglePass('desk_forgot_new_pass', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                            </div>
                                            <div class="mt-1 flex items-center gap-2">
                                                <div class="flex-1 bg-slate-200 h-1 rounded-full overflow-hidden">
                                                    <div id="desk_pwd_bar" class="h-full bg-red-400 transition-all duration-300 w-0"></div>
                                                </div>
                                                <span id="desk_pwd_text" class="text-[10px] font-semibold text-slate-400 shrink-0">Password strength</span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                                            <div class="auth-input-container">
                                                <i class="fas fa-lock auth-icon absolute left-3.5 top-3 text-slate-400 text-xs pointer-events-none"></i>
                                                <input type="password" 
                                                    id="desk_forgot_confirm_pass" 
                                                    placeholder="Re-enter new password" 
                                                    minlength="6" 
                                                    maxlength="100" 
                                                    required
                                                    class="w-full rounded-xl py-2.5 pl-9 pr-9 text-xs sm:text-sm text-slate-900 font-medium focus:outline-none placeholder:text-slate-400">
                                                <button type="button" onclick="togglePass('desk_forgot_confirm_pass', this)" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between pt-0.5">
                                            <button type="button" id="desk_resend_otp_btn" onclick="resendForgotOtp('desk')" class="text-xs text-[#057a55] font-semibold hover:underline">Resend OTP</button>
                                            <span id="desk_resend_timer" class="text-xs text-slate-400 font-mono font-bold hidden">60s</span>
                                        </div>

                                        <button type="submit" id="desk_forgot_reset_btn" class="w-full brand-btn-gradient text-white font-semibold py-3.5 rounded-2xl tap-effect flex items-center justify-center gap-2 text-xs sm:text-sm">
                                            <span>Save New Password</span>
                                        </button>
                                        <button type="button" onclick="backToForgotStep1('desk')" class="w-full text-center text-xs text-slate-500 font-semibold hover:text-[#057a55] py-1">
                                            <i class="fas fa-arrow-left mr-1"></i> Change Email
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Step 3: Success Screen -->
                            <div id="desk_forgot_step3" class="space-y-4 text-center py-6 hidden">
                                <div class="w-16 h-16 bg-emerald-100 text-[#057a55] rounded-3xl flex items-center justify-center mx-auto text-2xl shadow-sm border border-emerald-200">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Password Reset Complete!</h3>
                                    <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Your account password has been updated securely. You can now log in with your new credentials.</p>
                                </div>
                                <button type="button" onclick="proceedToLoginWithUser('desk')" class="w-full brand-btn-gradient text-white font-semibold py-3.5 rounded-2xl tap-effect text-xs sm:text-sm">
                                    Proceed to Login
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Subtle Footer -->
                <div class="w-full text-center pb-2">
                    <div class="flex items-center justify-center gap-2 text-xs text-slate-400 font-medium">
                        <span>Better Spaces. Brighter Tomorrows.</span>
                        <span class="w-8 h-0.5 bg-emerald-500 rounded-full inline-block"></span>
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

            const activeClasses = 'relative flex items-center gap-2.5 p-3 rounded-2xl cursor-pointer transition-all duration-200 select-none bg-white shadow-xs border-2 border-[#057a55] ring-2 ring-emerald-500/10';
            const inactiveClasses = 'relative flex items-center gap-2.5 p-3 rounded-2xl cursor-pointer transition-all duration-200 select-none bg-slate-50 border-2 border-transparent hover:bg-white';

            if (isTenant) {
                if (tenantCard) tenantCard.className = activeClasses;
                if (brokerCard) brokerCard.className = inactiveClasses;
                
                if (tenantBadge) tenantBadge.className = 'w-4 h-4 rounded-full bg-[#057a55] text-white flex items-center justify-center text-[9px] shadow-xs';
                if (brokerBadge) brokerBadge.className = 'w-4 h-4 rounded-full border border-slate-300 text-transparent flex items-center justify-center text-[9px]';
            } else {
                if (brokerCard) brokerCard.className = activeClasses;
                if (tenantCard) tenantCard.className = inactiveClasses;
                
                if (brokerBadge) brokerBadge.className = 'w-4 h-4 rounded-full bg-[#057a55] text-white flex items-center justify-center text-[9px] shadow-xs';
                if (tenantBadge) tenantBadge.className = 'w-4 h-4 rounded-full border border-slate-300 text-transparent flex items-center justify-center text-[9px]';
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
            const inactiveClass = 'flex-1 py-2.5 text-slate-600 text-xs font-semibold rounded-xl tap-effect transition-all';
            const activeClass = 'flex-1 py-2.5 bg-[#057a55] text-white text-xs font-bold rounded-xl tap-effect transition-all shadow-xs';
            
            document.getElementById('loginTab').className = inactiveClass;
            document.getElementById('signupTab').className = inactiveClass;
            document.getElementById('loginTabD').className = inactiveClass;
            document.getElementById('signupTabD').className = inactiveClass;

            // 3. Handle Top Action Buttons & Header
            const mobTabs = document.getElementById('mob-tabs-switcher');
            const deskTabs = document.getElementById('desk-tabs-switcher');
            const mobHeader = document.getElementById('mob-header-box');
            const deskHeader = document.getElementById('desk-header-box');
            const deskTopLogin = document.getElementById('desk_top_action_login');
            const deskTopSignup = document.getElementById('desk_top_action_signup');

            if (tab === 'forgot') {
                if (mobTabs) mobTabs.classList.add('hidden');
                if (deskTabs) deskTabs.classList.add('hidden');
                if (mobHeader) mobHeader.classList.add('hidden');
                if (deskHeader) deskHeader.classList.add('hidden');
                if (deskTopLogin) deskTopLogin.classList.remove('hidden');
                if (deskTopSignup) deskTopSignup.classList.add('hidden');
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
                document.getElementById('mob-auth-subtitle').innerText = 'Sign in to your SpaceSeeks account';
                document.getElementById('desktop-title').innerText = 'Welcome Back';
                document.getElementById('desktop-subtitle').innerText = 'Sign in to your SpaceSeeks account';

                if (deskTopLogin) deskTopLogin.classList.remove('hidden');
                if (deskTopSignup) deskTopSignup.classList.add('hidden');
            } else if (tab === 'signup') {
                document.getElementById('signupForm').classList.remove('hidden');
                document.getElementById('signupFormD').classList.remove('hidden');
                document.getElementById('signupTab').className = activeClass;
                document.getElementById('signupTabD').className = activeClass;
                
                document.getElementById('mob-auth-title').innerText = 'Create Your Account';
                document.getElementById('mob-auth-subtitle').innerText = 'Join SpaceSeeks to find & book verified stays';
                document.getElementById('desktop-title').innerText = 'Create Your Account';
                document.getElementById('desktop-subtitle').innerText = 'Join SpaceSeeks to find & book verified stays';

                if (deskTopLogin) deskTopLogin.classList.add('hidden');
                if (deskTopSignup) deskTopSignup.classList.remove('hidden');
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

            if (val.length < 5) {
                errEl.innerText = `Email address must be at least 5 characters (${val.length}/5 entered).`;
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            if (val.length > 150) {
                errEl.innerText = 'Email address cannot exceed maximum limit of 150 characters.';
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(val)) {
                errEl.innerText = 'Please enter a valid email address (e.g. name@example.com).';
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
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
                text.className = 'text-[10px] font-semibold text-slate-400 shrink-0';
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
                text.className = 'text-[10px] font-semibold text-red-500 shrink-0';
            } else if (score <= 50) {
                bar.className = 'h-full bg-amber-500 transition-all duration-300';
                text.innerText = 'Fair';
                text.className = 'text-[10px] font-semibold text-amber-500 shrink-0';
            } else if (score <= 75) {
                bar.className = 'h-full bg-blue-500 transition-all duration-300';
                text.innerText = 'Good';
                text.className = 'text-[10px] font-semibold text-blue-500 shrink-0';
            } else {
                bar.className = 'h-full bg-[#057a55] transition-all duration-300';
                text.innerText = 'Strong password';
                text.className = 'text-[10px] font-semibold text-[#057a55] shrink-0';
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
                showError(mode, 'Please enter your registered email address.');
                return;
            }

            if (loginVal.length < 5 || loginVal.length > 150) {
                showError(mode, 'Email address must be between 5 and 150 characters.');
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(loginVal)) {
                showError(mode, 'Please enter a valid email address format (e.g. name@example.com).');
                return;
            }

            const btn = document.getElementById(`${mode}_forgot_step1_btn`);
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending OTP to Email...';
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
                    let errMsg = result.message || 'No registered account found matching this email address.';
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

                showSuccess(mode, `Verification code dispatched to ${result.data?.target || loginVal}! Please check your inbox and enter the 6-digit OTP.`);

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
