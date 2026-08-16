<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - StayNest</title>
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
        .tap-effect { transition: all 0.2s; }
        .tap-effect:active { transform: scale(0.96); }
    </style>
</head>
<body class="bg-gray-900 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 md:p-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-brand/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>

        <div class="text-center mb-8 relative z-10">
            <div class="w-14 h-14 bg-gradient-to-br from-brand to-brand-dark rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto shadow-lg shadow-brand/30 mb-4">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">Admin Control Center</h1>
            <p class="text-xs text-gray-500 mt-1">Authorized personnel only</p>
        </div>

        <form class="space-y-4 relative z-10" onsubmit="event.preventDefault(); window.location.href='{{ route('admin.dashboard') }}';">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Admin Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <input type="email" value="admin@staynest.com" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Master Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <input id="adminPwd" type="password" value="admin123" required class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-11 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                    <button type="button" onclick="toggleAdminPassword()" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600"><i id="adminEye" class="fas fa-eye text-xs"></i></button>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                    <input type="checkbox" checked class="accent-brand rounded"> Remember Session
                </label>
                <a href="#" onclick="alert('Recovery instructions sent to super admin root phone.')" class="text-brand font-bold hover:underline">Forgot Key?</a>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition">
                Secure Sign In
            </button>
        </form>

        <div class="mt-6 p-3.5 bg-gray-50 rounded-xl border border-gray-200/70 text-center">
            <div class="text-[11px] text-gray-500 font-mono">Demo: <span class="font-semibold text-gray-800">admin@staynest.com</span> / <span class="font-semibold text-gray-800">admin123</span></div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('user.home') }}" class="text-xs text-gray-400 hover:text-brand transition"><i class="fas fa-arrow-left mr-1"></i> Back to Homepage</a>
        </div>
    </div>

    <script>
        function toggleAdminPassword() {
            const pwd = document.getElementById('adminPwd');
            const icon = document.getElementById('adminEye');
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
