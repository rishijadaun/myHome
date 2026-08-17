<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - StayNest Control Center</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
                            100: '#ccf0e8',
                            600: '#3a9a85',
                            700: '#2c7a69',
                        }
                    },
                    animation: {
                        'shake': 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        shake: {
                            '10%, 90%': { transform: 'translate3d(-1px, 0, 0)' },
                            '20%, 80%': { transform: 'translate3d(2px, 0, 0)' },
                            '30%, 50%, 70%': { transform: 'translate3d(-4px, 0, 0)' },
                            '40%, 60%': { transform: 'translate3d(4px, 0, 0)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .tap-effect { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .tap-effect:active { transform: scale(0.97); }
        .glass-panel {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-950 via-slate-900 to-gray-900 font-sans min-h-screen flex items-center justify-center p-4 sm:p-6 text-gray-800 antialiased selection:bg-brand selection:text-white relative overflow-x-hidden">

    <!-- Ambient Glowing Orbs -->
    <div class="fixed top-1/4 -left-24 w-96 h-96 bg-brand/15 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
    <div class="fixed bottom-1/4 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Brand Header Badge -->
        <div class="text-center mb-6">
            <a href="{{ route('user.home') }}" class="inline-flex items-center gap-2.5 px-4 py-2 bg-white/10 hover:bg-white/15 border border-white/15 rounded-full text-white text-xs font-semibold backdrop-blur-md transition tap-effect mb-4 shadow-lg shadow-black/20">
                <i class="fas fa-home text-brand"></i>
                <span>StayNest Platform</span>
                <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
                <span class="text-gray-300 font-normal">v2.4</span>
            </a>
            <div class="w-16 h-16 bg-gradient-to-br from-brand via-brand-600 to-brand-700 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto shadow-xl shadow-brand/25 ring-4 ring-brand/20 mb-3.5 transform transition hover:rotate-6">
                <i class="fas fa-shield-halved"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Admin Console</h1>
            <!-- <p class="text-xs text-gray-400 mt-1 font-medium">Authorized administrative credentials required</p> -->
        </div>

        <!-- Main Card -->
        <div class="glass-panel rounded-3xl shadow-2xl p-6 sm:p-8 border border-white/40 relative overflow-hidden">
            
            <!-- Dynamic Alert / Notification Banner -->
            <div id="alertBanner" class="{{ (session('error') || session('success') || $errors->any()) ? 'block' : 'hidden' }} mb-5 rounded-2xl p-3.5 text-xs font-medium border flex items-start gap-3 transition-all duration-300 {{ session('success') ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-red-50 border-red-200 text-red-800 animate-shake' }}">
                <div id="alertIcon" class="mt-0.5 shrink-0 text-sm">
                    @if(session('success'))
                        <i class="fas fa-check-circle text-emerald-600"></i>
                    @else
                        <i class="fas fa-triangle-exclamation text-red-600"></i>
                    @endif
                </div>
                <div id="alertText" class="flex-1 leading-relaxed">
                    @if(session('success'))
                        {{ session('success') }}
                    @elseif(session('error'))
                        {{ session('error') }}
                    @elseif($errors->any())
                        {{ $errors->first() }}
                    @endif
                </div>
                <button type="button" onclick="hideAlert()" class="text-gray-400 hover:text-gray-700 shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Login Form -->
            <form id="adminLoginForm" action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4" novalidate onsubmit="handleAdminLogin(event)">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="adminEmail" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                        <span>Admin Email</span>
                        <span id="emailValidationMsg" class="text-[11px] font-semibold text-red-500 hidden normal-case"></span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 transition" id="emailIconWrap">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <input 
                            type="email" 
                            id="adminEmail" 
                            name="email" 
                            value="{{ old('email', 'admin@staynest.com') }}" 
                            required 
                            autocomplete="email"
                            placeholder="admin@staynest.com"
                            oninput="validateEmailField()"
                            class="w-full bg-gray-50/80 border border-gray-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand focus:bg-white transition"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="adminPassword" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                        <span>Master Password</span>
                        <span id="passwordValidationMsg" class="text-[11px] font-semibold text-red-500 hidden normal-case"></span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 transition" id="pwdIconWrap">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input 
                            type="password" 
                            id="adminPassword" 
                            name="password" 
                            value="admin123" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••"
                            oninput="validatePasswordField()"
                            class="w-full bg-gray-50/80 border border-gray-200 rounded-xl py-3 pl-10 pr-11 text-sm font-medium text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand focus:bg-white transition"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 transition focus:outline-none"
                            aria-label="Toggle password visibility"
                        >
                            <i id="pwdEyeIcon" class="fas fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-gray-600 font-medium">
                        <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 text-brand rounded border-gray-300 focus:ring-brand/50 accent-brand">
                        <span>Remember session</span>
                    </label>
                    <!-- <button type="button" onclick="showForgotModal()" class="text-brand hover:text-brand-dark font-bold hover:underline transition">
                        Forgot Key?
                    </button> -->
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        id="submitBtn" 
                        class="w-full bg-gradient-to-r from-brand to-brand-dark hover:from-brand-600 hover:to-brand-700 text-white font-bold py-3.5 px-4 rounded-xl tap-effect shadow-lg shadow-brand/25 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed text-sm"
                    >
                        <span id="btnText">Secure Sign In</span>
                        <i id="btnIcon" class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-gray-100">
                <button 
                    type="button" 
                    onclick="fillDemoCredentials()" 
                    class="w-full group bg-slate-50 hover:bg-brand-50/70 border border-slate-200 hover:border-brand-200/80 rounded-xl p-3 text-left transition tap-effect flex items-center justify-between"
                >
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-lg bg-slate-200 group-hover:bg-brand text-slate-600 group-hover:text-white flex items-center justify-center text-xs transition">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Demo Super Admin</div>
                            <div class="text-xs font-mono text-gray-800 font-bold truncate">admin@staynest.com &bull; admin123</div>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-brand group-hover:translate-x-0.5 transition shrink-0 ml-2">
                        Auto-Fill <i class="fas fa-bolt text-[10px]"></i>
                    </span>
                </button>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-6 text-center space-y-2">
            <div class="flex items-center justify-center gap-4 text-xs font-medium text-gray-400">
                <a href="{{ route('user.home') }}" class="hover:text-brand transition flex items-center gap-1.5">
                    <i class="fas fa-arrow-left text-[10px]"></i> Homepage
                </a>
                <span class="text-gray-600">&bull;</span>
                <a href="{{ route('broker.login') }}" class="hover:text-brand transition flex items-center gap-1.5">
                    <i class="fas fa-user-tie text-[10px]"></i> Broker Login
                </a>
                <span class="text-gray-600">&bull;</span>
                <a href="{{ route('user.login') }}" class="hover:text-brand transition flex items-center gap-1.5">
                    <i class="fas fa-user text-[10px]"></i> Tenant Portal
                </a>
            </div>
            <div class="text-[11px] text-gray-400">
                &copy; {{ date('Y') }} StayNest Technologies Ltd. All rights reserved.
            </div>
        </div>

    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-gray-100 text-center animate-shake">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fas fa-key"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-base">Super Admin Key Recovery</h3>
            <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                For security reasons, root access recovery instructions must be initiated by contacting the internal systems administrator or executing the artisan CLI recovery command.
            </p>
            <div class="mt-4 p-2.5 bg-gray-50 rounded-lg text-[11px] font-mono text-gray-700 border border-gray-200">
                php artisan tinker &rarr; Hash::make('newpass')
            </div>
            <button type="button" onclick="hideForgotModal()" class="mt-5 w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2.5 rounded-xl text-xs tap-effect transition">
                Understood, Close
            </button>
        </div>
    </div>

    <!-- Client-Side Dynamic Controller Script -->
    <script>
        // Password Visibility Toggle
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('adminPassword');
            const eyeIcon = document.getElementById('pwdEyeIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                pwdInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Auto Fill Demo Credentials
        function fillDemoCredentials() {
            const email = document.getElementById('adminEmail');
            const pwd = document.getElementById('adminPassword');
            email.value = 'admin@staynest.com';
            pwd.value = 'admin123';
            clearValidationErrors();
            showAlert('Demo credentials filled! Click "Secure Sign In" to authenticate.', 'success');
        }

        // Real-Time Email Validation
        function validateEmailField() {
            const emailInput = document.getElementById('adminEmail');
            const errorMsg = document.getElementById('emailValidationMsg');
            const val = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!val) {
                setEmailError('Email is required');
                return false;
            } else if (!emailRegex.test(val)) {
                setEmailError('Invalid email format');
                return false;
            } else {
                clearEmailError();
                return true;
            }
        }

        function setEmailError(msg) {
            const emailInput = document.getElementById('adminEmail');
            const errorMsg = document.getElementById('emailValidationMsg');
            emailInput.classList.add('border-red-400', 'bg-red-50/40', 'focus:ring-red-300');
            emailInput.classList.remove('border-gray-200', 'bg-gray-50/80');
            errorMsg.textContent = msg;
            errorMsg.classList.remove('hidden');
        }

        function clearEmailError() {
            const emailInput = document.getElementById('adminEmail');
            const errorMsg = document.getElementById('emailValidationMsg');
            emailInput.classList.remove('border-red-400', 'bg-red-50/40', 'focus:ring-red-300');
            emailInput.classList.add('border-gray-200', 'bg-gray-50/80');
            errorMsg.classList.add('hidden');
        }

        // Real-Time Password Validation
        function validatePasswordField() {
            const pwdInput = document.getElementById('adminPassword');
            const errorMsg = document.getElementById('passwordValidationMsg');
            const val = pwdInput.value;

            if (!val) {
                setPasswordError('Password required');
                return false;
            } else if (val.length < 4) {
                setPasswordError('Min 4 characters');
                return false;
            } else {
                clearPasswordError();
                return true;
            }
        }

        function setPasswordError(msg) {
            const pwdInput = document.getElementById('adminPassword');
            const errorMsg = document.getElementById('passwordValidationMsg');
            pwdInput.classList.add('border-red-400', 'bg-red-50/40', 'focus:ring-red-300');
            pwdInput.classList.remove('border-gray-200', 'bg-gray-50/80');
            errorMsg.textContent = msg;
            errorMsg.classList.remove('hidden');
        }

        function clearPasswordError() {
            const pwdInput = document.getElementById('adminPassword');
            const errorMsg = document.getElementById('passwordValidationMsg');
            pwdInput.classList.remove('border-red-400', 'bg-red-50/40', 'focus:ring-red-300');
            pwdInput.classList.add('border-gray-200', 'bg-gray-50/80');
            errorMsg.classList.add('hidden');
        }

        function clearValidationErrors() {
            clearEmailError();
            clearPasswordError();
        }

        // Show/Hide Alert Banner
        function showAlert(msg, type = 'error') {
            const banner = document.getElementById('alertBanner');
            const text = document.getElementById('alertText');
            const icon = document.getElementById('alertIcon');

            text.textContent = msg;
            banner.classList.remove('hidden', 'bg-red-50', 'border-red-200', 'text-red-800', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-800', 'animate-shake');

            if (type === 'success') {
                banner.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
                icon.innerHTML = '<i class="fas fa-check-circle text-emerald-600"></i>';
            } else {
                banner.classList.add('bg-red-50', 'border-red-200', 'text-red-800', 'animate-shake');
                icon.innerHTML = '<i class="fas fa-triangle-exclamation text-red-600"></i>';
            }

            banner.classList.remove('hidden');
        }

        function hideAlert() {
            document.getElementById('alertBanner').classList.add('hidden');
        }

        // Modal Helpers
        function showForgotModal() {
            document.getElementById('forgotModal').classList.remove('hidden');
            document.getElementById('forgotModal').classList.add('flex');
        }

        function hideForgotModal() {
            document.getElementById('forgotModal').classList.add('hidden');
            document.getElementById('forgotModal').classList.remove('flex');
        }

        // Dynamic Form Submission Handler (AJAX with fallback)
        async function handleAdminLogin(e) {
            e.preventDefault();
            hideAlert();

            const isEmailValid = validateEmailField();
            const isPasswordValid = validatePasswordField();

            if (!isEmailValid || !isPasswordValid) {
                showAlert('Please fill in all required fields correctly.');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const email = document.getElementById('adminEmail').value.trim();
            const password = document.getElementById('adminPassword').value;
            const remember = document.querySelector('input[name="remember"]').checked;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Set Loading UI
            submitBtn.disabled = true;
            btnText.textContent = 'Authenticating...';
            btnIcon.className = 'fas fa-circle-notch fa-spin text-xs';

            try {
                const response = await fetch('{{ route('admin.login.submit') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        remember: remember ? 1 : 0
                    })
                });

                if (response.status === 419) {
                    submitBtn.disabled = false;
                    btnText.textContent = 'Session Expired';
                    btnIcon.className = 'fas fa-rotate-right text-xs';
                    showAlert('Session or security token expired. Refreshing page for a fresh session...', 'error');
                    setTimeout(() => window.location.reload(), 1000);
                    return;
                }

                const data = await response.json();

                if (response.ok && data.success) {
                    // Success UI State
                    btnText.textContent = 'Access Granted!';
                    btnIcon.className = 'fas fa-check text-xs';
                    submitBtn.classList.remove('from-brand', 'to-brand-dark');
                    submitBtn.classList.add('from-emerald-500', 'to-teal-600');
                    showAlert(data.message || 'Login successful! Redirecting to Dashboard...', 'success');

                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route('admin.dashboard') }}';
                    }, 600);
                } else {
                    // Reset Button
                    submitBtn.disabled = false;
                    btnText.textContent = 'Secure Sign In';
                    btnIcon.className = 'fas fa-arrow-right text-xs';

                    let errorMsg = data.message || 'Authentication failed. Please check your credentials.';
                    if (data.errors) {
                        const firstErrKey = Object.keys(data.errors)[0];
                        if (firstErrKey && data.errors[firstErrKey][0]) {
                            errorMsg = data.errors[firstErrKey][0];
                        }
                    }
                    showAlert(errorMsg, 'error');
                }
            } catch (err) {
                console.warn('AJAX login error:', err);
                submitBtn.disabled = false;
                btnText.textContent = 'Secure Sign In';
                btnIcon.className = 'fas fa-arrow-right text-xs';
                showAlert('Connection error or session expired. Please refresh the page and try again.', 'error');
            }
        }
    </script>
</body>
</html>
