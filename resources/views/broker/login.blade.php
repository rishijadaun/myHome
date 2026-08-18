<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Broker Login - StayNest Portal</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '#4bb59d',
                            light: '#e6f7f3',
                            dark: '#3a9a85',
                            50: '#f0fdf9',
                            100: '#ccf0e8'
                        }
                    }
                }
            }
        }
    </script>
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
                
                <div class="inline-block px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-xs font-bold uppercase tracking-wider mb-4 border border-white/20">
                    Partner Broker Network
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">Empower Your Property Business</h1>
                <p class="text-white/80 text-sm md:text-base leading-relaxed">List PG stays, track real-time tenant bookings, automate monthly rent collection, and manage KYC verifications seamlessly.</p>
            </div>

            <div class="relative z-10 mt-10 space-y-4">
                <div class="p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-sm">
                            <i class="fas fa-shield-check text-emerald-300"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Verified Partner Portal</div>
                            <div class="text-xs text-white/70">Secured with API OAuth & 256-bit encryption</div>
                        </div>
                    </div>
                </div>

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

                <form id="brokerLoginForm" onsubmit="handleBrokerLogin(event)" class="space-y-4">
                    
                    <!-- Login (Email or Phone) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Broker Email or Mobile *</label>
                        <div class="relative">
                            <i class="fas fa-user-tie absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                            <input 
                                type="text" 
                                id="loginInput" 
                                name="login" 
                                value="vikram@broker.com" 
                                required 
                                placeholder="e.g. vikram@broker.com or 9876543210"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition text-gray-800"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Password *</label>
                            <a href="javascript:void(0)" onclick="handleForgotPassword()" class="text-xs text-brand font-bold hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                            <input 
                                type="password" 
                                id="passwordField" 
                                name="password" 
                                value="broker123" 
                                required 
                                placeholder="Enter your password"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition text-gray-800 font-mono"
                            >
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                                <i id="eyeIcon" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
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
                        <button type="button" onclick="autofillBroker('vikram@broker.com', 'broker123')" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 text-xs font-semibold text-gray-700 tap-effect transition flex items-center gap-1.5 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Vikram Singh (Delhi NCR)
                        </button>
                        <button type="button" onclick="autofillBroker('rajesh.sharma@staynest.com', 'broker123')" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 text-xs font-semibold text-gray-700 tap-effect transition flex items-center gap-1.5 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Rajesh Sharma (Bangalore)
                        </button>
                    </div>
                </div>

                <div class="mt-6 text-center text-xs text-gray-500">
                    Need a broker partner account? 
                    <a href="javascript:void(0)" onclick="alert('Please contact StayNest Administrator or visit /admin to onboard your agency.')" class="text-brand font-bold hover:underline">Apply for Partnership</a>
                </div>

            </div>
        </div>

    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

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

        // Demo Autofill Helper
        function autofillBroker(email, password) {
            document.getElementById('loginInput').value = email;
            document.getElementById('passwordField').value = password;
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

        function handleForgotPassword() {
            const loginVal = document.getElementById('loginInput').value;
            if (!loginVal) {
                showAlert('Please enter your broker email in the input above first.', 'error');
                return;
            }
            showAlert(`Password reset request submitted for ${loginVal}. Please contact administrator.`, 'success');
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
