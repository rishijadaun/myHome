<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broker Login - StayNest</title>
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
        .tap-effect:active { transform: scale(0.96); }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        <!-- Left: Branding -->
        <div class="md:w-1/2 bg-gradient-to-br from-brand via-brand-dark to-teal-800 p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
            <div class="relative z-10">
                <a href="{{ route('user.home') }}" class="flex items-center gap-2 mb-12 inline-flex group">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white font-bold text-2xl group-hover:scale-105 transition"><i class="fas fa-home"></i></div>
                    <span class="font-bold text-3xl tracking-tight">StayNest</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Broker Portal</h1>
                <p class="text-white/80 text-base md:text-lg leading-relaxed">Manage your PG properties, track tenant bookings, accept payments, and grow your rental business seamlessly.</p>
            </div>
            <div class="relative z-10 mt-12">
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold border-2 border-brand-dark">VS</div>
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold border-2 border-brand-dark">NP</div>
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold border-2 border-brand-dark">+45</div>
                    </div>
                    <div class="text-sm text-white/90 font-medium">Join 45+ verified partner brokers across India</div>
                </div>
            </div>
        </div>

        <!-- Right: Form -->
        <div class="md:w-1/2 p-8 md:p-12 flex items-center">
            <div class="w-full">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-500">Sign in to access your broker dashboard</p>
                </div>
                <form class="space-y-5" onsubmit="event.preventDefault(); window.location.href='{{ route('broker.dashboard') }}';">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email or Phone</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" value="vikram@broker.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50" required placeholder="Enter email or mobile">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                            <input id="passwordField" type="password" value="broker123" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3.5 pl-12 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50" required placeholder="Enter password">
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600"><i id="eyeIcon" class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 text-brand rounded focus:ring-brand accent-brand">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" onclick="alert('Password reset link sent to registered email.');" class="text-sm text-brand font-semibold hover:underline">Forgot password?</a>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:shadow-xl hover:opacity-95 transition-all">
                        Sign In to Broker Panel
                    </button>
                </form>
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">Not a partner broker yet? <a href="#" onclick="alert('Broker registration form opening soon.');" class="text-brand font-semibold hover:underline">Apply Now</a></p>
                </div>
                <div class="mt-6 p-4 bg-brand-50 rounded-xl border border-brand-100">
                    <div class="text-xs text-brand font-bold mb-1 flex items-center gap-1">
                        <i class="fas fa-key"></i> Demo Credentials
                    </div>
                    <div class="text-xs text-gray-600 font-mono">Email: <span class="font-semibold text-gray-800">vikram@broker.com</span> | Password: <span class="font-semibold text-gray-800">broker123</span></div>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('user.home') }}" class="text-xs text-gray-400 hover:text-brand transition"><i class="fas fa-arrow-left mr-1"></i> Back to StayNest Homepage</a>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>
