@extends('broker.layouts.app')

@section('title', 'Broker Dashboard')

@section('content')
@php
    $brokerName = $broker->profile?->full_name ?? ($broker->profile?->first_name ?? ($broker->email ?? 'Partner Broker'));
    $firstName = $broker->profile?->first_name ?? 'Partner';
    $initials = strtoupper(substr($firstName, 0, 1) . substr($broker->profile?->last_name ?? 'B', 0, 1));
    if (empty(trim($initials))) $initials = 'BR';
    $isKycVerified = !empty($broker->kyc_verified_at);
@endphp

<!-- Desktop Sticky Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ $firstName }}! 👋</h1>
        <p class="text-sm text-gray-500">Here's real-time performance and occupancy across your properties today.</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('broker.pgs') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-teal-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold tap-effect flex items-center gap-2 shadow-sm transition cursor-pointer">
            <i class="fas fa-plus text-xs"></i> Add PG
        </a>
        <a href="{{ route('broker.bookings') }}" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative cursor-pointer" title="Pending Bookings">
            <i class="fas fa-bell"></i>
            @if($pendingBookingsCount > 0)
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-amber-500 rounded-full ring-2 ring-white animate-pulse"></span>
            @endif
        </a>
        <a href="{{ route('broker.profile') }}" class="flex items-center gap-3 pl-4 border-l border-gray-200 hover:opacity-80 transition cursor-pointer">
            <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs shadow-sm">
                {{ $initials }}
            </div>
            <div>
                <div class="text-sm font-bold text-gray-900 leading-tight">{{ $brokerName }}</div>
                <div class="text-xs {{ $isKycVerified ? 'text-emerald-600' : 'text-amber-600' }} font-semibold flex items-center gap-1">
                    <i class="fas {{ $isKycVerified ? 'fa-check-circle' : 'fa-clock' }} text-[10px]"></i>
                    {{ $isKycVerified ? 'Verified Partner' : 'KYC Pending' }}
                </div>
            </div>
        </a>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Flash Alert / Toast Anchor -->
    <div id="brokerToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="brokerToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="brokerToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="brokerToastMessage">Action completed</span>
        </div>
    </div>

    <!-- Top Metric Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Active PGs -->
        <a href="{{ route('broker.pgs') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md hover:border-brand/30 transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand group-hover:scale-110 transition"><i class="fas fa-building text-xl"></i></div>
                @if($newPropertiesThisMonth > 0)
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">+{{ $newPropertiesThisMonth }} this mo</span>
                @else
                    <span class="text-xs font-semibold text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full">Active</span>
                @endif
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($totalProperties) }}</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Active PGs Listed</div>
        </a>

        <!-- Total Bookings -->
        <a href="{{ route('broker.bookings') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-300 transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition"><i class="fas fa-calendar-check text-xl"></i></div>
                @if($pendingBookingsCount > 0)
                    <span class="text-xs font-semibold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full">{{ $pendingBookingsCount }} pending</span>
                @else
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Up to date</span>
                @endif
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($totalBookings) }}</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Total Bookings</div>
        </a>

        <!-- Active Tenants -->
        <a href="{{ route('broker.tenants') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md hover:border-purple-300 transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 group-hover:scale-110 transition"><i class="fas fa-users text-xl"></i></div>
                @if($newTenantsThisMonth > 0)
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">+{{ $newTenantsThisMonth }} new</span>
                @else
                    <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-full">Occupants</span>
                @endif
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($activeTenantsCount) }}</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Active Staying Tenants</div>
        </a>

        <!-- Monthly Earnings -->
        <a href="{{ route('broker.earnings') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md hover:border-orange-300 transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition"><i class="fas fa-rupee-sign text-xl"></i></div>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Live Revenue</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">₹{{ number_format($monthRevenue / 100000, 2) }}L</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Gross Revenue ({{ date('F') }})</div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. Booking Trends Line Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Booking & Inquiries Trends</h3>
                    <p class="text-xs text-gray-500">Real-time tenant reservation trends across all your listings</p>
                </div>
                <div>
                    <select id="chartPeriodSelect" onchange="updateBookingChart(this.value)" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition cursor-pointer">
                        <option value="7days">Last 7 Days</option>
                        <option value="30days">Last 30 Days</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="brokerBookingChart"></canvas>
            </div>
        </div>

        <!-- 2. Occupancy Rate Donut Chart -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-900 text-lg mb-1">Occupancy Rate</h3>
                <p class="text-xs text-gray-500 mb-4">Across all {{ $totalProperties }} registered properties</p>
                
                <div class="relative w-44 h-44 mx-auto my-2">
                    <canvas id="brokerOccupancyChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <div class="text-3xl font-extrabold text-gray-900">{{ $occupancyRate }}%</div>
                        <div class="text-xs font-semibold text-gray-400">Occupied</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2.5 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 flex items-center gap-2 text-xs font-medium"><span class="w-2.5 h-2.5 rounded-full bg-brand"></span> Total Beds</span>
                    <span class="font-extrabold text-gray-900 text-xs">{{ $totalBeds }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 flex items-center gap-2 text-xs font-medium"><span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span> Occupied Beds</span>
                    <span class="font-extrabold text-brand text-xs">{{ $occupiedBeds }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 flex items-center gap-2 text-xs font-medium"><span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span> Available Beds</span>
                    <span class="font-extrabold text-orange-500 text-xs">{{ $availableBeds }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Promo Banner -->
    <div class="bg-gradient-to-r from-brand via-brand-dark to-teal-800 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-brand/20 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10">
            <h3 class="text-xl md:text-2xl font-extrabold">Have a new PG property or flat to list?</h3>
            <p class="text-white/80 text-xs md:text-sm mt-1.5 max-w-xl leading-relaxed">Reach thousands of student and working professional tenants in your area with zero listing fees on StayNest.</p>
        </div>
        <div class="flex flex-wrap gap-3 relative z-10 shrink-0">
            <a href="{{ route('broker.pgs') }}" class="bg-white text-brand px-5 py-3 rounded-xl font-bold text-sm tap-effect shadow-md hover:bg-gray-50 transition flex items-center gap-1.5 cursor-pointer">
                <i class="fas fa-plus"></i> Add New PG
            </a>
            <!-- <a href="{{ route('broker.earnings') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-5 py-3 rounded-xl font-semibold text-sm tap-effect transition flex items-center gap-1.5 cursor-pointer">
                <i class="fas fa-file-invoice"></i> View Payouts
            </a> -->
        </div>
    </div>

    <!-- Recent Activity & Pending Bookings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- 1. Pending Booking Requests (with 1-Click Approve/Reject) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-900 text-lg">Pending Booking Requests</h3>
                    <span id="pendingRequestsBadge" class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $pendingBookings->count() }}</span>
                </div>
                <a href="{{ route('broker.bookings') }}" class="text-xs text-brand font-bold hover:underline">View All Bookings</a>
            </div>

            <div class="space-y-3" id="pendingBookingsContainer">
                @forelse($pendingBookings as $b)
                    @php
                        $tName = $b->user?->profile?->full_name ?? ($b->user?->name ?? 'Tenant');
                        $tFirst = $b->user?->profile?->first_name ?? $tName;
                        $tInitials = strtoupper(substr($tFirst, 0, 1) . substr($b->user?->profile?->last_name ?? 'T', 0, 1));
                        $propName = $b->property?->name ?? 'PG Stay';
                    @endphp
                    <div id="booking-item-{{ $b->id }}" class="flex items-center justify-between gap-3 p-4 bg-amber-50/40 hover:bg-amber-50/80 rounded-2xl border border-amber-100 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                {{ $tInitials }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-gray-900 truncate">{{ $tName }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $propName }} • ₹{{ number_format($b->total_amount) }}/mo</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <!-- 1-Click Approve -->
                            <button type="button" onclick="handleApproveBooking('{{ $b->id }}', '{{ addslashes($tName) }}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl tap-effect shadow-xs flex items-center gap-1 cursor-pointer" title="Approve Booking">
                                <i class="fas fa-check text-[10px]"></i> Approve
                            </button>
                            
                            <!-- 1-Click Reject -->
                            <button type="button" onclick="handleRejectBooking('{{ $b->id }}', '{{ addslashes($tName) }}')" class="w-8 h-8 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect cursor-pointer" title="Decline Request">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400">
                        <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="text-xs font-semibold text-gray-600">All booking requests are processed!</div>
                        <p class="text-[11px] text-gray-400 mt-0.5">New reservations from tenants will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 2. Recent Tenant Reviews -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-900 text-lg">Recent Tenant Reviews</h3>
                    <span class="bg-brand-light text-brand text-xs font-bold px-2.5 py-0.5 rounded-full">{{ number_format($avgRating, 1) }} ★</span>
                </div>
                <a href="{{ route('broker.reviews') }}" class="text-xs text-brand font-bold hover:underline">View All Reviews</a>
            </div>

            <div class="space-y-3">
                @forelse($recentReviews as $rev)
                    @php
                        $rName = $rev->user?->profile?->full_name ?? ($rev->user?->name ?? 'Tenant');
                        $rInitials = strtoupper(substr($rName, 0, 2));
                        $rProp = $rev->property?->name ?? 'PG Stay';
                    @endphp
                    <div class="p-4 bg-gray-50/80 hover:bg-gray-50 rounded-2xl border border-gray-100 transition space-y-1.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 bg-brand-light text-brand rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ $rInitials }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900 leading-tight">{{ $rName }}</div>
                                    <div class="text-[11px] text-gray-400 truncate max-w-[180px]">{{ $rProp }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($rev->rating))
                                        <i class="fas fa-star text-[10px]"></i>
                                    @else
                                        <i class="far fa-star text-[10px] text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">"{{ $rev->comment }}"</p>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400">
                        <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="text-xs font-semibold text-gray-600">No reviews yet</div>
                        <p class="text-[11px] text-gray-400 mt-0.5">Reviews submitted by your verified tenants will show up here.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const apiToken = localStorage.getItem('staynest_token') || '';

    // Dynamic Toast Messenger
    function showBrokerToast(message, type = 'success') {
        const toast = document.getElementById('brokerToastNotification');
        const text = document.getElementById('brokerToastMessage');
        const icon = document.getElementById('brokerToastIcon');

        text.textContent = message;
        if (type === 'success') {
            icon.innerHTML = '<i class="fas fa-check-circle text-emerald-400 text-base"></i>';
        } else {
            icon.innerHTML = '<i class="fas fa-exclamation-circle text-red-400 text-base"></i>';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3200);
    }

    // Chart.js Instances
    let bookingChartInstance = null;

    document.addEventListener('DOMContentLoaded', () => {
        initBookingChart();
        initOccupancyChart();
    });

    // 1. Line Chart Initialization
    function initBookingChart() {
        const ctx = document.getElementById('brokerBookingChart');
        if (!ctx) return;

        const initialLabels = @json($chartData['labels']);
        const initialData = @json($chartData['bookings']);

        bookingChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialLabels,
                datasets: [{
                    label: 'Bookings',
                    data: initialData,
                    borderColor: '#4bb59d',
                    backgroundColor: 'rgba(75, 181, 157, 0.12)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4bb59d',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleFont: { size: 12, family: 'Inter' },
                        bodyFont: { size: 12, family: 'Inter' },
                        padding: 10,
                        cornerRadius: 10,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, family: 'Inter' }, color: '#9ca3af' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { stepSize: 1, font: { size: 11, family: 'Inter' }, color: '#9ca3af' }
                    }
                }
            }
        });
    }

    // Dynamic Chart Period Filter via API
    async function updateBookingChart(period) {
        try {
            const res = await fetch(`{{ route('broker.dashboard.chart') }}?period=${period}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': apiToken ? `Bearer ${apiToken}` : '',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success && bookingChartInstance) {
                bookingChartInstance.data.labels = data.labels;
                bookingChartInstance.data.datasets[0].data = data.bookings;
                bookingChartInstance.update();
            }
        } catch (err) {
            console.error('Failed to update broker chart:', err);
        }
    }

    // 2. Donut Occupancy Chart Initialization
    function initOccupancyChart() {
        const ctx = document.getElementById('brokerOccupancyChart');
        if (!ctx) return;

        const occupied = {{ $occupiedBeds }};
        const available = {{ $availableBeds }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Occupied', 'Available'],
                datasets: [{
                    data: [occupied > 0 ? occupied : 1, available > 0 ? available : 1],
                    backgroundColor: ['#4bb59d', '#e5e7eb'],
                    borderWidth: 0,
                    cutout: '76%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 10,
                        cornerRadius: 10
                    }
                }
            }
        });
    }

    // 1-Click Approve Booking
    async function handleApproveBooking(bookingId, tenantName) {
        if (!confirm(`Confirm and approve booking request for ${tenantName}?`)) return;

        try {
            const res = await fetch(`/broker/bookings/${bookingId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': apiToken ? `Bearer ${apiToken}` : '',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showBrokerToast(data.message || 'Booking approved!', 'success');
                removeBookingRow(bookingId);
            } else {
                showBrokerToast(data.message || 'Failed to approve booking', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Connection error. Please try again.', 'error');
        }
    }

    // 1-Click Reject Booking
    async function handleRejectBooking(bookingId, tenantName) {
        if (!confirm(`Decline booking request for ${tenantName}?`)) return;

        try {
            const res = await fetch(`/broker/bookings/${bookingId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': apiToken ? `Bearer ${apiToken}` : '',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showBrokerToast(data.message || 'Booking declined', 'success');
                removeBookingRow(bookingId);
            } else {
                showBrokerToast(data.message || 'Failed to decline booking', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Connection error. Please try again.', 'error');
        }
    }

    function removeBookingRow(bookingId) {
        const item = document.getElementById(`booking-item-${bookingId}`);
        if (item) {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.95)';
            setTimeout(() => {
                item.remove();
                const badge = document.getElementById('pendingRequestsBadge');
                if (badge) {
                    let count = parseInt(badge.textContent) - 1;
                    badge.textContent = Math.max(0, count);
                }
            }, 250);
        }
    }
</script>
@endpush
