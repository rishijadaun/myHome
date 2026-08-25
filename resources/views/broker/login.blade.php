<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Partner Broker Login - StayNest Portal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text { background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .tap-effect { transition: all 0.2s; }
        .tap-effect:active { transform: scale(0.97); }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        .shake-effect { animation: shake 0.4s ease-in-out; }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center p-4 selection:bg-brand selection:text-white">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
        
        <!-- Left: Branding & Statistics -->
        <div class="md:w-1/2 bg-gradient-to-br from-brand via-brand-dark to-teal-900 p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-teal-400/10 rounded-full -ml-36 -mb-36 blur-2xl"></div>

            <div class="relative z-10">
                <a href="{{ route('user.home') }}" class="flex items-center gap-2.5 mb-10 inline-flex group">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white font-bold text-2xl group-hover:scale-105 transition shadow-lg"><i class="fas fa-home"></i></div>
                    <span class="font-extrabold text-3xl tracking-tight text-white">StayNest</span>
                </a>
                
                <!-- <div class="inline-block px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-bold uppercase tracking-wider mb-4 border border-white/20">
                    Partner Broker Network
                </div> -->

                <h1 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">Empower Your Property Business</h1>
                <p class="text-white/80 text-sm md:text-base leading-relaxed">List PG stays, track real-time tenant bookings, automate monthly rent collection, and manage KYC verifications seamlessly.</p>
            </div>

            <div class="relative z-10 mt-10 space-y-4">
                <!-- <div class="p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-sm">
                            <i class="fas fa-shield-check text-emerald-300"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Verified Partner Portal</div>
                            <div class="text-xs text-white/70">Secured with API OAuth & 256-bit encryption</div>
                        </div>
                    </div>
                </div> -->

                <div class="flex items-center justify-between text-xs text-white/70 font-medium">
                    <span>© {{ date('Y') }} StayNest Technologies Inc.</span>
                    <a href="{{ route('user.home') }}" class="hover:text-white transition flex items-center gap-1">
                        <i class="fas fa-arrow-left text-[10px]"></i> Main Website
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Dynamic Login Form -->
        <div class="md:w-1/2 p-8 md:p-12 flex items-center">
            <div class="w-full">
                
                <div class="mb-6">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-1.5">Broker Sign In</h2>
                    <p class="text-xs md:text-sm text-gray-500">Access your property catalog and tenant bookings</p>
                </div>

                <!-- Error / Alert Banner -->
                <div id="alertBanner" class="hidden mb-5 p-4 rounded-2xl text-xs font-medium flex items-center gap-3 transition">
                    <i id="alertIcon" class="fas fa-exclamation-circle text-base shrink-0"></i>
                    <span id="alertMessage">Error message</span>
                </div>

                @if(session('error'))
                    <div class="mb-5 p-4 rounded-2xl text-xs font-medium bg-red-50 text-red-700 border border-red-200 flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-base shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-5 p-4 rounded-2xl text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-3">
                        <i class="fas fa-check-circle text-base shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form id="brokerLoginForm" onsubmit="handleBrokerLogin(event)" class="space-y-4" novalidate>
                    
                    <!-- Login (Email or Phone) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Broker Email or Mobile *</label>
                            <span class="text-[11px] text-gray-400 font-semibold">3-150 chars</span>
                        </div>
                        <div class="relative">
                            <i class="fas fa-user-tie absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                            <input 
                                type="text" 
                                id="loginInput" 
                                name="login" 
                                value="vikram@broker.com" 
                                required 
                                minlength="3"
                                maxlength="150"
                                oninput="validateBrokerLoginInput()"
                                placeholder="e.g. vikram@broker.com or 9876543210"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition text-gray-800"
                            >
                        </div>
                        <p id="loginInputErr" class="text-[11px] text-red-500 mt-1 font-medium hidden"></p>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Password *</label>
                                <span class="text-[11px] text-gray-400 font-semibold">4-100 chars</span>
                            </div>
                            <a href="javascript:void(0)" onclick="openBrokerForgotModal()" class="text-xs text-brand font-bold hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                            <input 
                                type="password" 
                                id="passwordField" 
                                name="password" 
                                value="broker123" 
                                required 
                                minlength="4"
                                maxlength="100"
                                oninput="validateBrokerPassword()"
                                placeholder="Enter your password (min. 4 characters)"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition text-gray-800 font-mono"
                            >
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                                <i id="eyeIcon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p id="passwordFieldErr" class="text-[11px] text-red-500 mt-1 font-medium hidden"></p>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 text-brand rounded border-gray-300 focus:ring-brand/50 accent-brand cursor-pointer">
                            <span class="text-xs font-semibold text-gray-600">Keep me logged in</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn" 
                        class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:shadow-xl hover:opacity-95 transition-all text-sm flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="fas fa-sign-in-alt"></i> Sign In to Broker Portal
                    </button>
                </form>

                <!-- Quick Demo Autofill Chips -->
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fas fa-bolt text-amber-500"></i> Quick Demo Autofill
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="autofillBroker('rajesh.sharma@staynest.com', 'broker123')" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 text-xs font-semibold text-gray-700 tap-effect transition flex items-center gap-1.5 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Rajesh Sharma (Bangalore)
                        </button>
                    </div>
                </div>

                <div class="mt-6 text-center text-xs text-gray-500">
                    Need a broker partner account? 
                    <a href="{{ route('user.contact', ['user_type' => 'partner']) }}" class="text-brand font-bold hover:underline">Apply for Partnership</a>
                </div>

            </div>
        </div>

    </div>

    <!-- ================= BROKER FORGOT PASSWORD MODAL ================= -->
    <div id="brokerForgotModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100 relative animate-scaleUp">
            <button type="button" onclick="closeBrokerForgotModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center absolute top-5 right-5 transition">
                <i class="fas fa-times text-xs"></i>
            </button>

            <!-- Modal Alert Banner -->
            <div id="modalAlertBanner" class="hidden mb-4 p-3.5 rounded-xl text-xs font-medium flex items-center gap-2.5">
                <i id="modalAlertIcon" class="fas fa-exclamation-circle text-sm shrink-0"></i>
                <span id="modalAlertMsg"></span>
            </div>

            <!-- Modal Step 1: Request OTP -->
            <div id="bForgotStep1" class="space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Reset Broker Password</h3>
                        <p class="text-xs text-gray-500">Enter registered email or mobile to get OTP</p>
                    </div>
                </div>

                <form onsubmit="handleBrokerForgotRequest(event)" class="space-y-4" novalidate>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-gray-700">Email or Mobile Number *</label>
                            <span class="text-[10px] text-gray-400 font-semibold">3-150 chars</span>
                        </div>
                        <input 
                            type="text" 
                            id="bForgotLoginInput" 
                            required 
                            minlength="3" 
                            maxlength="150" 
                            placeholder="Enter Email or Mobile Number"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white text-gray-800"
                        >
                    </div>
                    <button type="submit" id="bForgotStep1Btn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-md shadow-brand/20 text-sm flex items-center justify-center gap-2">
                        <span>Send 6-Digit OTP</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>

            <!-- Modal Step 2: Verify OTP & Save New Password -->
            <div id="bForgotStep2" class="space-y-4 hidden">
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="text-xs font-bold text-gray-800">OTP Sent Successfully!</div>
                    <div class="text-xs text-gray-600 mt-0.5">Enter code sent to <span id="bForgotTargetDisplay" class="font-bold text-gray-900"></span></div>
                    <div id="bDemoOtpBadge" class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 bg-white border border-emerald-300 rounded text-[11px] font-mono font-bold text-emerald-800">
                        <span>Demo OTP:</span>
                        <span id="bDemoOtpVal" class="tracking-widest">123456</span>
                    </div>
                </div>

                <form onsubmit="handleBrokerForgotReset(event)" class="space-y-3.5" novalidate>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-gray-700">6-Digit Verification Code *</label>
                            <span class="text-[10px] text-gray-400 font-semibold">6 digits</span>
                        </div>
                        <input 
                            type="tel" 
                            id="bForgotOtpInput" 
                            required 
                            maxlength="6" 
                            minlength="6" 
                            inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                            placeholder="• • • • • •"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 text-center font-mono font-bold text-lg tracking-widest text-gray-900 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-gray-700">New Password *</label>
                            <span class="text-[10px] text-gray-400 font-semibold">4-100 chars</span>
                        </div>
                        <input 
                            type="password" 
                            id="bForgotNewPass" 
                            required 
                            minlength="4" 
                            maxlength="100" 
                            placeholder="Enter new password (4-100 chars)"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password *</label>
                        <input 
                            type="password" 
                            id="bForgotConfirmPass" 
                            required 
                            minlength="4" 
                            maxlength="100" 
                            placeholder="Re-enter new password"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white"
                        >
                    </div>

                    <button type="submit" id="bForgotResetBtn" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-md shadow-brand/20 text-sm flex items-center justify-center gap-2">
                        <span>Save & Update Password</span>
                    </button>
                </form>
            </div>

            <!-- Modal Step 3: Success Screen -->
            <div id="bForgotStep3" class="space-y-4 text-center py-4 hidden">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-xl shadow-inner">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Broker Password Reset!</h4>
                    <p class="text-xs text-gray-500 mt-1">Your password has been updated. You can now log into the Broker Portal.</p>
                </div>
                <button type="button" onclick="closeBrokerForgotModalAndFill()" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-semibold py-3 rounded-xl tap-effect shadow-md shadow-brand/20 text-sm">
                    Continue to Login
                </button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        let brokerResetLoginCache = '';

        // Toggle Password Visibility
        function togglePassword() {
            const pwd = document.getElementById('passwordField');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Real-Time Validation for Broker Login Input
        function validateBrokerLoginInput() {
            const input = document.getElementById('loginInput');
            const errEl = document.getElementById('loginInputErr');
            const val = input.value.trim();

            if (!val) {
                errEl.classList.add('hidden');
                input.classList.remove('border-red-400');
                return true;
            }

            if (val.length < 3) {
                errEl.innerText = `Broker identifier must be at least 3 characters (${val.length}/3 entered).`;
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            if (val.length > 150) {
                errEl.innerText = 'Broker identifier cannot exceed maximum limit of 150 characters.';
                errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
                return false;
            }

            // If entering Email (contains letters or '@')
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
                        errEl.innerText = 'Please enter a valid broker email address (e.g. name@example.com).';
                        errEl.classList.remove('hidden');
                        input.classList.add('border-red-400');
                        return false;
                    }
                }
            } else if (/^\d+$/.test(val)) {
                // If numeric phone
                if (val.length !== 10) {
                    errEl.innerText = `Mobile number must be exactly 10 digits (${val.length}/10 entered).`;
                    errEl.classList.remove('hidden');
                    input.classList.add('border-red-400');
                    return false;
                }
            }

            errEl.classList.add('hidden');
            input.classList.remove('border-red-400');
            return true;
        }

        // Real-Time Validation for Broker Password
        function validateBrokerPassword() {
            const pass = document.getElementById('passwordField');
            const errEl = document.getElementById('passwordFieldErr');
            const val = pass.value;

            if (!val) {
                errEl.classList.add('hidden');
                pass.classList.remove('border-red-400');
                return true;
            }

            if (val.length < 4) {
                errEl.innerText = `Password must be at least 4 characters (${val.length}/4 entered).`;
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

        // Demo Autofill Helper
        function autofillBroker(email, password) {
            document.getElementById('loginInput').value = email;
            document.getElementById('passwordField').value = password;
            validateBrokerLoginInput();
            validateBrokerPassword();
            hideAlert();
        }

        // Alert Helper
        function showAlert(message, type = 'error') {
            const banner = document.getElementById('alertBanner');
            const msgEl = document.getElementById('alertMessage');
            const icon = document.getElementById('alertIcon');

            msgEl.textContent = message;
            banner.className = `mb-5 p-4 rounded-2xl text-xs font-medium flex items-center gap-3 transition shake-effect ${
                type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
            }`;

            icon.className = type === 'error' ? 'fas fa-exclamation-triangle text-base shrink-0 text-red-500' : 'fas fa-check-circle text-base shrink-0 text-emerald-500';
            banner.classList.remove('hidden');
        }

        function hideAlert() {
            document.getElementById('alertBanner').classList.add('hidden');
        }

        // ================= FORGOT PASSWORD MODAL LOGIC =================
        function openBrokerForgotModal() {
            hideModalAlert();
            const currentLogin = document.getElementById('loginInput').value.trim();
            if (currentLogin) {
                document.getElementById('bForgotLoginInput').value = currentLogin;
            }
            document.getElementById('bForgotStep1').classList.remove('hidden');
            document.getElementById('bForgotStep2').classList.add('hidden');
            document.getElementById('bForgotStep3').classList.add('hidden');
            document.getElementById('brokerForgotModal').classList.remove('hidden');
            document.getElementById('brokerForgotModal').classList.add('flex');
        }

        function closeBrokerForgotModal() {
            document.getElementById('brokerForgotModal').classList.add('hidden');
            document.getElementById('brokerForgotModal').classList.remove('flex');
        }

        function closeBrokerForgotModalAndFill() {
            closeBrokerForgotModal();
            if (brokerResetLoginCache) {
                document.getElementById('loginInput').value = brokerResetLoginCache;
                document.getElementById('passwordField').value = '';
                document.getElementById('passwordField').focus();
            }
            showAlert('Password reset complete! Sign in with your new credentials.', 'success');
        }

        function showModalAlert(msg, type = 'error') {
            const banner = document.getElementById('modalAlertBanner');
            const msgEl = document.getElementById('modalAlertMsg');
            const icon = document.getElementById('modalAlertIcon');

            msgEl.textContent = msg;
            banner.className = `mb-4 p-3.5 rounded-xl text-xs font-medium flex items-center gap-2.5 ${
                type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
            }`;
            icon.className = type === 'error' ? 'fas fa-exclamation-circle text-sm shrink-0 text-red-500' : 'fas fa-check-circle text-sm shrink-0 text-emerald-500';
            banner.classList.remove('hidden');
        }

        function hideModalAlert() {
            document.getElementById('modalAlertBanner').classList.add('hidden');
        }

        async function handleBrokerForgotRequest(e) {
            e.preventDefault();
            hideModalAlert();

            const loginVal = document.getElementById('bForgotLoginInput').value.trim();
            if (!loginVal || loginVal.length < 3) {
                showModalAlert('Please enter a valid broker email or mobile number (min. 3 characters).', 'error');
                return;
            }

            if (loginVal.length > 150) {
                showModalAlert('Broker identifier cannot exceed maximum limit of 150 characters.', 'error');
                return;
            }

            // Email format check
            if (loginVal.includes('@') || /[a-zA-Z]/.test(loginVal)) {
                if (loginVal.length < 5 || loginVal.length > 150) {
                    showModalAlert('Email address must be between 5 and 150 characters.', 'error');
                    return;
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(loginVal)) {
                    showModalAlert('Please enter a valid broker email address format (e.g. name@example.com).', 'error');
                    return;
                }
            } else if (/^\d+$/.test(loginVal)) {
                if (loginVal.length !== 10) {
                    showModalAlert('Mobile number must be exactly 10 digits.', 'error');
                    return;
                }
            }

            const btn = document.getElementById('bForgotStep1Btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Sending...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/v1/auth/forgot-password/request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ login: loginVal })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'Broker account not found.';
                    if (result.errors) {
                        const firstErr = Object.values(result.errors)[0];
                        if (firstErr) errMsg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    }
                    showModalAlert(errMsg, 'error');
                    return;
                }

                brokerResetLoginCache = loginVal;
                document.getElementById('bForgotTargetDisplay').innerText = result.data?.target || loginVal;
                if (result.data?.demo_otp) {
                    document.getElementById('bDemoOtpVal').innerText = result.data.demo_otp;
                    document.getElementById('bDemoOtpBadge').classList.remove('hidden');
                }

                document.getElementById('bForgotStep1').classList.add('hidden');
                document.getElementById('bForgotStep2').classList.remove('hidden');

            } catch (err) {
                showModalAlert('Connection error. Please try again.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        async function handleBrokerForgotReset(e) {
            e.preventDefault();
            hideModalAlert();

            const otp = document.getElementById('bForgotOtpInput').value.trim();
            const newPass = document.getElementById('bForgotNewPass').value;
            const confirmPass = document.getElementById('bForgotConfirmPass').value;

            if (!otp || otp.length !== 6) {
                showModalAlert('Please enter the 6-digit OTP code.', 'error');
                return;
            }

            if (!newPass || newPass.length < 4) {
                showModalAlert('New password must be at least 4 characters long.', 'error');
                return;
            }

            if (newPass.length > 100) {
                showModalAlert('New password cannot exceed 100 characters.', 'error');
                return;
            }

            if (newPass !== confirmPass) {
                showModalAlert('New password and confirm password do not match.', 'error');
                return;
            }

            const btn = document.getElementById('bForgotResetBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Updating...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/v1/auth/forgot-password/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        login: brokerResetLoginCache,
                        otp: otp,
                        password: newPass,
                        password_confirmation: confirmPass
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    let errMsg = result.message || 'Password update failed.';
                    if (result.errors) {
                        const firstErr = Object.values(result.errors)[0];
                        if (firstErr) errMsg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
                    }
                    showModalAlert(errMsg, 'error');
                    return;
                }

                document.getElementById('bForgotStep2').classList.add('hidden');
                document.getElementById('bForgotStep3').classList.remove('hidden');

            } catch (err) {
                showModalAlert('Connection error. Please check your network.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        // Handle Broker Login via API / AJAX
        async function handleBrokerLogin(e) {
            e.preventDefault();
            hideAlert();

            const form = e.target;
            const submitBtn = document.getElementById('submitBtn');
            const login = document.getElementById('loginInput').value.trim();
            const password = document.getElementById('passwordField').value;
            const remember = form.remember.checked;

            // Enforce Min/Max Limits on Submit
            if (!login) {
                showAlert('Please enter your broker email or phone number.', 'error');
                return;
            }

            if (login.length < 3) {
                showAlert('Broker identifier must be at least 3 characters long.', 'error');
                return;
            }

            if (login.length > 150) {
                showAlert('Broker identifier cannot exceed maximum limit of 150 characters.', 'error');
                return;
            }

            // Email check if contains letters or '@'
            if (login.includes('@') || /[a-zA-Z]/.test(login)) {
                if (login.length < 5 || login.length > 150) {
                    showAlert('Email address must be between 5 and 150 characters.', 'error');
                    return;
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(login)) {
                    showAlert('Please enter a valid broker email address (e.g. name@example.com).', 'error');
                    return;
                }
            } else if (/^\d+$/.test(login)) {
                if (login.length !== 10) {
                    showAlert('Please enter a valid 10-digit mobile number.', 'error');
                    return;
                }
            }

            if (!password) {
                showAlert('Please enter your password.', 'error');
                return;
            }

            if (password.length < 4) {
                showAlert('Password must be at least 4 characters long.', 'error');
                return;
            }

            if (password.length > 100) {
                showAlert('Password cannot exceed 100 characters.', 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Authenticating...';

            try {
                const response = await fetch('{{ route('broker.login.submit') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ login, password, remember })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Store Token & User in LocalStorage for API requests
                    if (data.token) {
                        localStorage.setItem('staynest_token', data.token);
                    }
                    if (data.user) {
                        localStorage.setItem('broker_user', JSON.stringify(data.user));
                    }

                    showAlert(data.message || 'Login successful! Redirecting...', 'success');
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Verified! Redirecting...';

                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route('broker.dashboard') }}';
                    }, 600);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In to Broker Portal';

                    let errorMsg = data.message || 'Authentication failed. Please check your credentials.';
                    if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        if (firstKey && data.errors[firstKey][0]) {
                            errorMsg = data.errors[firstKey][0];
                        }
                    }
                    showAlert(errorMsg, 'error');
                }
            } catch (err) {
                console.error('Broker Login Error:', err);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In to Broker Portal';
                showAlert('Unable to connect to server. Please try again.', 'error');
            }
        }
    </script>
</body>
</html>
