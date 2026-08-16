@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Overview Dashboard</h1>
        <p class="text-sm text-gray-500">Welcome back, Super Admin! Real-time analytics across StayNest.</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bookings') }}" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative">
            <i class="fas fa-bell"></i>
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
        </a>
        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
            <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold shadow-sm">SA</div>
            <div>
                <div class="text-sm font-bold text-gray-900 leading-tight">Rahul Administrator</div>
                <div class="text-xs text-brand font-semibold flex items-center gap-1">
                    <i class="fas fa-shield-alt text-[10px]"></i> Super Admin
                </div>
            </div>
        </div>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <a href="{{ route('admin.pgs') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand group-hover:scale-110 transition"><i class="fas fa-building text-xl"></i></div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+12% MoM</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">124</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Total PGs Listed</div>
        </a>

        <a href="{{ route('admin.brokers') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition"><i class="fas fa-user-tie text-xl"></i></div>
                <span class="text-xs font-semibold text-yellow-800 bg-yellow-100 px-2.5 py-1 rounded-full">8 pending</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">45</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Active Brokers</div>
        </a>

        <a href="{{ route('admin.bookings') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 group-hover:scale-110 transition"><i class="fas fa-calendar-check text-xl"></i></div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+24% MoM</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">389</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Total Bookings</div>
        </a>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500"><i class="fas fa-rupee-sign text-xl"></i></div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+18%</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">₹4.24L</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Platform Revenue (MTD)</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Booking Trends Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Overall Platform Bookings</h3>
                    <p class="text-xs text-gray-500">Weekly trend across all cities</p>
                </div>
                <select class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-brand">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>This Year</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="adminBookingChart"></canvas>
            </div>
        </div>

        <!-- PG Distribution Doughnut -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-900 text-lg mb-1">PG Category Breakdown</h3>
                <p class="text-xs text-gray-500 mb-4">124 verified property listings</p>
                <div class="relative w-44 h-44 mx-auto my-2">
                    <canvas id="adminPgChart"></canvas>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2.5 text-sm">
                <div class="flex justify-between items-center"><span class="text-gray-600 flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-brand"></span> Boys PGs</span><span class="font-bold text-gray-900">56 (45%)</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-600 flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span> Girls PGs</span><span class="font-bold text-gray-900">43 (35%)</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-600 flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Co-living / Co-Ed</span><span class="font-bold text-gray-900">25 (20%)</span></div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Approvals Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings Stream -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-lg">Latest Tenant Bookings</h3>
                <a href="{{ route('admin.bookings') }}" class="text-sm text-brand font-semibold hover:underline">View All (389)</a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between gap-3 p-3.5 bg-gray-50 hover:bg-gray-100/80 rounded-2xl transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0">RS</div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-gray-900 truncate">Rahul Sharma</div>
                            <div class="text-xs text-gray-500 truncate">Sunrise Premium PG • Twin Bed</div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-sm font-bold text-gray-900">₹17,000</div>
                        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-md">CONFIRMED</span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 p-3.5 bg-gray-50 hover:bg-gray-100/80 rounded-2xl transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0">PP</div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-gray-900 truncate">Priya Patel</div>
                            <div class="text-xs text-gray-500 truncate">Aura Women's Stay • Single Room</div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-sm font-bold text-gray-900">₹9,999</div>
                        <span class="text-[10px] font-bold text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded-md">PENDING</span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 p-3.5 bg-gray-50 hover:bg-gray-100/80 rounded-2xl transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0">AK</div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-gray-900 truncate">Amit Kumar</div>
                            <div class="text-xs text-gray-500 truncate">Urban Nest • Triple Sharing</div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-sm font-bold text-gray-900">₹12,500</div>
                        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-md">CONFIRMED</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Broker Approvals -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-900 text-lg">Pending Broker Applications</h3>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded-full">8</span>
                </div>
                <a href="{{ route('admin.brokers') }}" class="text-sm text-brand font-semibold hover:underline">Review All</a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between gap-3 p-3.5 bg-yellow-50/40 rounded-2xl border border-yellow-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0">NP</div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-gray-900 truncate">Neha Patel</div>
                            <div class="text-xs text-gray-500 truncate">Mumbai • 8 Properties to onboard</div>
                        </div>
                    </div>
                    <div class="flex gap-1.5 shrink-0">
                        <button onclick="alert('Broker Neha Patel approved!')" class="bg-brand hover:bg-brand-dark text-white text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect">Approve</button>
                        <button onclick="alert('Broker application rejected.')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect">Decline</button>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 p-3.5 bg-yellow-50/40 rounded-2xl border border-yellow-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0">RS</div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-gray-900 truncate">Rajesh Sharma</div>
                            <div class="text-xs text-gray-500 truncate">Bangalore • 5 Properties to onboard</div>
                        </div>
                    </div>
                    <div class="flex gap-1.5 shrink-0">
                        <button onclick="alert('Broker Rajesh Sharma approved!')" class="bg-brand hover:bg-brand-dark text-white text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect">Approve</button>
                        <button onclick="alert('Broker application rejected.')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect">Decline</button>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 p-3.5 bg-yellow-50/40 rounded-2xl border border-yellow-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0">AK</div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-gray-900 truncate">Anil Kumar Real Estate</div>
                            <div class="text-xs text-gray-500 truncate">Delhi NCR • 14 Properties</div>
                        </div>
                    </div>
                    <div class="flex gap-1.5 shrink-0">
                        <button onclick="alert('Broker Anil Kumar approved!')" class="bg-brand hover:bg-brand-dark text-white text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect">Approve</button>
                        <button onclick="alert('Broker application rejected.')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect">Decline</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Booking Chart
    const ctxBooking = document.getElementById('adminBookingChart');
    if (ctxBooking) {
        new Chart(ctxBooking, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Bookings',
                    data: [12, 19, 15, 25, 22, 30, 28],
                    borderColor: '#4bb59d',
                    backgroundColor: 'rgba(75, 181, 157, 0.12)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4bb59d',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // PG Distribution Chart
    const ctxPg = document.getElementById('adminPgChart');
    if (ctxPg) {
        new Chart(ctxPg, {
            type: 'doughnut',
            data: {
                labels: ['Boys PGs (45%)', 'Girls PGs (35%)', 'Co-living (20%)'],
                datasets: [{
                    data: [45, 35, 20],
                    backgroundColor: ['#4bb59d', '#ec4899', '#a855f7'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: { legend: { display: false } }
            }
        });
    }
</script>
@endpush
