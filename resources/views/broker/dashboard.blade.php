@extends('broker.layouts.app')

@section('title', 'Broker Dashboard')

@section('content')
<!-- Desktop Sticky Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Welcome, Vikram! 👋</h1>
        <p class="text-sm text-gray-500">Here's what's happening with your properties today.</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('broker.pgs') }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2 rounded-xl text-sm font-semibold tap-effect flex items-center gap-2 shadow-sm transition">
            <i class="fas fa-plus text-xs"></i> Add PG
        </a>
        <a href="{{ route('broker.bookings') }}" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative">
            <i class="fas fa-bell"></i>
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
        </a>
        <a href="{{ route('broker.profile') }}" class="flex items-center gap-3 pl-4 border-l border-gray-200 hover:opacity-80 transition">
            <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold shadow-sm">VS</div>
            <div>
                <div class="text-sm font-bold text-gray-900 leading-tight">Vikram Singh</div>
                <div class="text-xs text-brand font-semibold flex items-center gap-1">
                    <i class="fas fa-check-circle text-[10px]"></i> Verified Broker
                </div>
            </div>
        </a>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <a href="{{ route('broker.pgs') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand group-hover:scale-110 transition"><i class="fas fa-building text-xl"></i></div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+2 new</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">12</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Active PGs Listed</div>
        </a>
        <a href="{{ route('broker.bookings') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition"><i class="fas fa-calendar-check text-xl"></i></div>
                <span class="text-xs font-semibold text-yellow-700 bg-yellow-100 px-2.5 py-1 rounded-full">5 pending</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">48</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Total Bookings</div>
        </a>
        <a href="{{ route('broker.tenants') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 group-hover:scale-110 transition"><i class="fas fa-users text-xl"></i></div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+8 this mo</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">36</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Active Tenants</div>
        </a>
        <a href="{{ route('broker.earnings') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition"><i class="fas fa-rupee-sign text-xl"></i></div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+18% vs last mo</span>
            </div>
            <div class="text-3xl font-bold text-gray-900">₹2.48L</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Earnings (August)</div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Booking & Inquiries Trends</h3>
                    <p class="text-xs text-gray-500">Weekly breakdown of user bookings and inquiries</p>
                </div>
                <select class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-brand">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>This Year</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-900 text-lg mb-1">Occupancy Rate</h3>
                <p class="text-xs text-gray-500 mb-4">Across all 12 properties</p>
                <div class="relative w-44 h-44 mx-auto my-2">
                    <canvas id="occupancyChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <div class="text-3xl font-extrabold text-gray-900">78%</div>
                        <div class="text-xs font-semibold text-gray-400">Occupied</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2.5 text-sm">
                <div class="flex justify-between items-center"><span class="text-gray-600 flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-brand"></span> Total Beds</span><span class="font-bold text-gray-900">120</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-600 flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span> Occupied Beds</span><span class="font-bold text-brand">94</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-600 flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span> Available Beds</span><span class="font-bold text-orange-500">26</span></div>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts Banner -->
    <div class="bg-gradient-to-r from-brand to-brand-dark rounded-2xl p-6 text-white shadow-lg shadow-brand/20 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold">Have a new PG property to list?</h3>
            <p class="text-white/80 text-sm mt-1">Get verified tenants quickly with zero brokerage charges on StayNest.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('broker.pgs') }}" class="bg-white text-brand px-5 py-2.5 rounded-xl font-bold text-sm tap-effect shadow-md hover:bg-gray-50 transition">
                <i class="fas fa-plus mr-1"></i> Add New PG
            </a>
            <a href="{{ route('broker.earnings') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-5 py-2.5 rounded-xl font-semibold text-sm tap-effect transition">
                <i class="fas fa-file-invoice mr-1"></i> View Payouts
            </a>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Bookings -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-900 text-lg">Pending Booking Requests</h3>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded-full">5</span>
                </div>
                <a href="{{ route('broker.bookings') }}" class="text-sm text-brand font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between gap-3 p-3.5 bg-yellow-50/50 hover:bg-yellow-50 rounded-xl border border-yellow-100 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0">RS</div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate">Rahul Sharma</div>
                            <div class="text-xs text-gray-500 truncate">Sunrise Premium PG • Twin Sharing</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button onclick="alert('Booking for Rahul Sharma approved!')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect" title="Approve"><i class="fas fa-check text-xs"></i></button>
                        <button onclick="alert('Booking for Rahul Sharma rejected.')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect" title="Reject"><i class="fas fa-times text-xs"></i></button>
                        <a href="{{ route('broker.bookings') }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center tap-effect"><i class="fas fa-eye text-xs"></i></a>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3 p-3.5 bg-yellow-50/50 hover:bg-yellow-50 rounded-xl border border-yellow-100 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0">PP</div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate">Priya Patel</div>
                            <div class="text-xs text-gray-500 truncate">Aura Women's Stay • Single Room</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button onclick="alert('Booking for Priya Patel approved!')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect" title="Approve"><i class="fas fa-check text-xs"></i></button>
                        <button onclick="alert('Booking for Priya Patel rejected.')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect" title="Reject"><i class="fas fa-times text-xs"></i></button>
                        <a href="{{ route('broker.bookings') }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center tap-effect"><i class="fas fa-eye text-xs"></i></a>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3 p-3.5 bg-yellow-50/50 hover:bg-yellow-50 rounded-xl border border-yellow-100 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0">AK</div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate">Amit Kumar</div>
                            <div class="text-xs text-gray-500 truncate">Urban Nest Co-living • Triple Sharing</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button onclick="alert('Booking for Amit Kumar approved!')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect" title="Approve"><i class="fas fa-check text-xs"></i></button>
                        <button onclick="alert('Booking for Amit Kumar rejected.')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect" title="Reject"><i class="fas fa-times text-xs"></i></button>
                        <a href="{{ route('broker.bookings') }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center tap-effect"><i class="fas fa-eye text-xs"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-900 text-lg">Recent Tenant Reviews</h3>
                    <span class="bg-brand-50 text-brand text-xs font-bold px-2 py-0.5 rounded-full">4.8 ★</span>
                </div>
                <a href="{{ route('broker.reviews') }}" class="text-sm text-brand font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-3">
                <div class="p-3.5 bg-gray-50 hover:bg-gray-100/70 rounded-xl transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-brand-light rounded-full flex items-center justify-center text-brand text-xs font-bold">RS</div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 leading-tight">Rahul Sharma</div>
                                <div class="text-[11px] text-gray-500">Sunrise Premium PG</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-xs text-yellow-500">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 line-clamp-2">"Great place to stay! Food is amazing and staff is very helpful. High speed WiFi for work."</p>
                </div>
                <div class="p-3.5 bg-gray-50 hover:bg-gray-100/70 rounded-xl transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center text-pink-500 text-xs font-bold">PP</div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 leading-tight">Priya Patel</div>
                                <div class="text-[11px] text-gray-500">Aura Women's Stay</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-xs text-yellow-500">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 line-clamp-2">"Very clean and well-maintained. Good location near metro station. Safe environment."</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Booking Trends Chart
    const ctxBooking = document.getElementById('bookingChart');
    if (ctxBooking) {
        new Chart(ctxBooking, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Bookings',
                    data: [4, 7, 5, 9, 8, 12, 10],
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

    // Occupancy Chart
    const ctxOccupancy = document.getElementById('occupancyChart');
    if (ctxOccupancy) {
        new Chart(ctxOccupancy, {
            type: 'doughnut',
            data: {
                labels: ['Occupied (94)', 'Available (26)'],
                datasets: [{
                    data: [78, 22],
                    backgroundColor: ['#4bb59d', '#e5e7eb'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '78%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
</script>
@endpush
