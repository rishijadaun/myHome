@extends('user.layouts.app')

@section('title', 'My Profile - StayNest')

@section('content')
<!-- Mobile Content -->
<div class="md:hidden pt-[70px] pb-24">
    <!-- Profile Header -->
    <div class="bg-gradient-to-br from-brand to-brand-dark text-white px-4 py-6 mb-4">
        <div class="flex items-center gap-4">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-20 h-20 rounded-full border-4 border-white/30 object-cover">
                <button onclick="alert('Profile photo update options')" class="absolute bottom-0 right-0 w-7 h-7 bg-white rounded-full flex items-center justify-center text-brand shadow-lg">
                    <i class="fas fa-camera text-xs"></i>
                </button>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">Rahul Sharma</h2>
                <p class="text-sm text-white/80">rahul.sharma@email.com</p>
                <p class="text-xs text-white/70 mt-1">+91 98765 43210</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3 mt-6 pt-4 border-t border-white/20">
            <a href="{{ route('user.bookings') }}" class="text-center tap-effect">
                <div class="text-xl font-bold">12</div>
                <div class="text-xs text-white/80">Bookings</div>
            </a>
            <a href="{{ route('user.saved') }}" class="text-center tap-effect">
                <div class="text-xl font-bold">5</div>
                <div class="text-xs text-white/80">Saved</div>
            </a>
            <a href="{{ route('user.detail') }}" class="text-center tap-effect">
                <div class="text-xl font-bold">8</div>
                <div class="text-xs text-white/80">Reviews</div>
            </a>
        </div>
    </div>

    <!-- Menu Items -->
    <div class="px-4 space-y-2">
        <button onclick="alert('Edit Personal Information modal')" class="w-full bg-white rounded-xl p-4 flex items-center gap-4 tap-effect border border-gray-100">
            <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center text-brand">
                <i class="fas fa-user"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-gray-900 text-sm">Personal Information</div>
                <div class="text-xs text-gray-500">Name, email, phone</div>
            </div>
            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
        </button>

        <a href="{{ route('user.location') }}" class="w-full bg-white rounded-xl p-4 flex items-center gap-4 tap-effect border border-gray-100">
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-gray-900 text-sm">Saved Addresses & Map</div>
                <div class="text-xs text-gray-500">Home, work, location route</div>
            </div>
            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
        </a>

        <button onclick="alert('Payment options & UPI management')" class="w-full bg-white rounded-xl p-4 flex items-center gap-4 tap-effect border border-gray-100">
            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-gray-900 text-sm">Payment Methods</div>
                <div class="text-xs text-gray-500">Cards, UPI, wallets</div>
            </div>
            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
        </button>

        <button onclick="alert('Notification preferences updated')" class="w-full bg-white rounded-xl p-4 flex items-center gap-4 tap-effect border border-gray-100">
            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500">
                <i class="fas fa-bell"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-gray-900 text-sm">Notifications</div>
                <div class="text-xs text-gray-500">Push, email, SMS</div>
            </div>
            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
        </button>

        <button onclick="alert('Security settings: Password & 2FA')" class="w-full bg-white rounded-xl p-4 flex items-center gap-4 tap-effect border border-gray-100">
            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-500">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-gray-900 text-sm">Privacy & Security</div>
                <div class="text-xs text-gray-500">Password, 2FA</div>
            </div>
            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
        </button>

        <a href="{{ route('user.contact') }}" class="w-full bg-white rounded-xl p-4 flex items-center gap-4 tap-effect border border-gray-100">
            <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center text-cyan-500">
                <i class="fas fa-headset"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-gray-900 text-sm">Help & 24/7 Support</div>
                <div class="text-xs text-gray-500">Live chat, FAQs, contact us</div>
            </div>
            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
        </a>

        <a href="{{ route('user.about') }}" class="w-full bg-white rounded-xl p-4 flex items-center gap-4 tap-effect border border-gray-100">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-gray-900 text-sm">About StayNest</div>
                <div class="text-xs text-gray-500">Version 2.1.0 • Our Story</div>
            </div>
            <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
        </a>

        <a href="{{ route('user.login') }}" class="w-full bg-red-50 rounded-xl p-4 flex items-center gap-4 tap-effect border border-red-100 mt-4">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-500">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <div class="flex-1 text-left">
                <div class="font-semibold text-red-600 text-sm">Logout</div>
                <div class="text-xs text-red-400">Sign out of your account</div>
            </div>
        </a>
    </div>
</div>

<!-- Desktop Content -->
<div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 sticky top-24 shadow-sm">
                <div class="text-center">
                    <div class="relative inline-block">
                        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-32 h-32 rounded-full border-4 border-brand-light object-cover mx-auto">
                        <button onclick="alert('Update Profile Image')" class="absolute bottom-2 right-2 w-10 h-10 bg-brand rounded-full flex items-center justify-center text-white shadow-lg hover:bg-brand-dark transition">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mt-4">Rahul Sharma</h2>
                    <p class="text-gray-500 text-sm">rahul.sharma@email.com</p>
                    <p class="text-gray-500 text-sm">+91 98765 43210</p>
                    <div class="flex items-center justify-center gap-2 mt-3">
                        <span class="bg-brand-light text-brand text-xs font-bold px-3 py-1 rounded-full">
                            <i class="fas fa-check-circle mr-1"></i> Verified
                        </span>
                        <span class="bg-yellow-50 text-yellow-600 text-xs font-bold px-3 py-1 rounded-full">
                            <i class="fas fa-crown mr-1"></i> Premium
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('user.bookings') }}" class="text-center tap-effect hover:bg-gray-50 rounded-xl p-2 transition">
                        <div class="text-2xl font-bold text-gray-900">12</div>
                        <div class="text-xs text-gray-500">Bookings</div>
                    </a>
                    <a href="{{ route('user.saved') }}" class="text-center tap-effect hover:bg-gray-50 rounded-xl p-2 transition">
                        <div class="text-2xl font-bold text-gray-900">5</div>
                        <div class="text-xs text-gray-500">Saved</div>
                    </a>
                    <a href="{{ route('user.detail') }}" class="text-center tap-effect hover:bg-gray-50 rounded-xl p-2 transition">
                        <div class="text-2xl font-bold text-gray-900">8</div>
                        <div class="text-xs text-gray-500">Reviews</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                    <button onclick="alert('Editing personal information modal opened.')" class="text-brand font-semibold text-sm hover:underline">Edit</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Full Name</label>
                        <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 text-sm font-medium">Rahul Sharma</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Email</label>
                        <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 text-sm font-medium">rahul.sharma@email.com</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Phone</label>
                        <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 text-sm font-medium">+91 98765 43210</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Date of Birth</label>
                        <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 text-sm font-medium">March 15, 1995</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Address</label>
                        <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-200 text-sm font-medium">468, Sangeet, 431, Saket, New Delhi, 110017</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('user.location') }}" class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition tap-effect text-left block shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Saved Addresses & Map</div>
                            <div class="text-sm text-gray-500">Live navigation routing</div>
                        </div>
                    </div>
                </a>
                <button onclick="alert('Payment Methods & UPI manager')" class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition tap-effect text-left shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                            <i class="fas fa-credit-card text-xl"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Payment Methods</div>
                            <div class="text-sm text-gray-500">Cards, UPI, wallets</div>
                        </div>
                    </div>
                </button>
                <button onclick="alert('Notification preferences')" class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition tap-effect text-left shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Notifications</div>
                            <div class="text-sm text-gray-500">Manage preferences</div>
                        </div>
                    </div>
                </button>
                <button onclick="alert('Security settings: Password & 2FA')" class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition tap-effect text-left shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-500">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Privacy & Security</div>
                            <div class="text-sm text-gray-500">Password, 2FA</div>
                        </div>
                    </div>
                </button>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    <a href="{{ route('user.bookings') }}" class="flex items-start gap-4 pb-4 border-b border-gray-100 hover:bg-gray-50 p-2 rounded-xl transition">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-500 flex-shrink-0">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900 text-sm">Booking confirmed</div>
                            <div class="text-xs text-gray-500">Sunrise Premium PG • Aug 1, 2026</div>
                        </div>
                        <span class="text-xs text-gray-400">2 days ago</span>
                    </a>
                    <a href="{{ route('user.saved') }}" class="flex items-start gap-4 pb-4 border-b border-gray-100 hover:bg-gray-50 p-2 rounded-xl transition">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 flex-shrink-0">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900 text-sm">Saved property</div>
                            <div class="text-xs text-gray-500">Aura Women's Stay</div>
                        </div>
                        <span class="text-xs text-gray-400">5 days ago</span>
                    </a>
                    <a href="{{ route('user.detail') }}" class="flex items-start gap-4 hover:bg-gray-50 p-2 rounded-xl transition">
                        <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center text-yellow-500 flex-shrink-0">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900 text-sm">Review posted</div>
                            <div class="text-xs text-gray-500">Urban Nest Co-living • 5 stars</div>
                        </div>
                        <span class="text-xs text-gray-400">1 week ago</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
