@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Top Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Overview Dashboard</h1>
        <p class="text-sm text-gray-500">Welcome back, {{ Auth::user()->profile->first_name ?? 'Super Admin' }}! Real-time analytics across StayNest.</p>
    </div>
    <div class="flex items-center gap-4">
        <!-- Date Badge -->
        <div class="hidden xl:flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600">
            <i class="far fa-calendar-alt text-brand"></i>
            <span>{{ now()->format('D, d M Y') }}</span>
        </div>

        <!-- Notification Bell -->
        <a href="{{ route('admin.bookings') }}" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition relative" title="Notifications">
            <i class="fas fa-bell"></i>
            @if(($pendingProperties + $pendingBrokers) > 0)
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white animate-pulse"></span>
            @endif
        </a>

        <!-- Admin Profile Pill -->
        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
            <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold shadow-sm">
                {{ strtoupper(substr(Auth::user()->profile->first_name ?? 'A', 0, 1) . substr(Auth::user()->profile->last_name ?? 'D', 0, 1)) }}
            </div>
            <div>
                <div class="text-sm font-bold text-gray-900 leading-tight">
                    {{ Auth::user()->profile->full_name ?? Auth::user()->email }}
                </div>
                <div class="text-xs text-brand font-semibold flex items-center gap-1">
                    <i class="fas fa-shield-alt text-[10px]"></i> 
                    {{ Auth::user()->roles->first()->name ?? 'Super Admin' }}
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Dashboard Container -->
<div class="p-4 md:p-8 space-y-6">

    <!-- Flash Alert / Toast Anchor -->
    <div id="toastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="toastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="toastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="toastMessage">Action performed successfully</span>
        </div>
    </div>

    <!-- Flagged Listing Moderation Alert Banner -->
    @if(($pendingReportsCount ?? 0) > 0)
        <div class="bg-gradient-to-r from-rose-500 via-rose-600 to-pink-600 rounded-2xl p-4 text-white shadow-lg shadow-rose-500/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg shrink-0">
                    <i class="fas fa-shield-virus"></i>
                </div>
                <div>
                    <h4 class="font-black text-sm">Flagged Listing Moderation Required ({{ $pendingReportsCount }} Pending {{ $pendingReportsCount > 1 ? 'Reports' : 'Report' }})</h4>
                    <p class="text-xs text-white/90">Users have reported property listings with potential violations. Review and take moderation action.</p>
                </div>
            </div>
            <a href="{{ route('admin.reports') }}" class="bg-white text-rose-600 hover:bg-rose-50 font-extrabold text-xs px-4 py-2.5 rounded-xl transition shadow-xs shrink-0 flex items-center justify-center gap-1.5 no-underline">
                <span>Investigate Reports</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    @endif

    <!-- 1. Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Properties -->
        <a href="{{ route('admin.pgs') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand group-hover:scale-110 transition">
                    <i class="fas fa-building text-xl"></i>
                </div>
                @if($pendingProperties > 0)
                    <span class="text-xs font-semibold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full">
                        {{ $pendingProperties }} pending
                    </span>
                @else
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                        {{ $verifiedProperties }} verified
                    </span>
                @endif
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($totalProperties) }}</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Total PGs Listed</div>
        </a>

        <!-- Active Brokers -->
        <a href="{{ route('admin.brokers') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                @if($pendingBrokers > 0)
                    <span class="text-xs font-semibold text-yellow-800 bg-yellow-100 px-2.5 py-1 rounded-full">
                        {{ $pendingBrokers }} pending KYC
                    </span>
                @else
                    <span class="text-xs font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full">
                        All Active
                    </span>
                @endif
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($activeBrokers) }}</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Active Brokers ({{ $totalBrokers }} total)</div>
        </a>

        <!-- Total Bookings -->
        <a href="{{ route('admin.bookings') }}" class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 group-hover:scale-110 transition">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                    {{ $confirmedBookings }} confirmed
                </span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($totalBookings) }}</div>
            <div class="text-sm text-gray-500 font-medium mt-1">Total Bookings</div>
        </a>

        <!-- Platform Revenue -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500">
                    <i class="fas fa-rupee-sign text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                    MTD Active
                </span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">
                @if($revenueThisMonth >= 100000)
                    ₹{{ number_format($revenueThisMonth / 100000, 2) }}L
                @else
                    ₹{{ number_format($revenueThisMonth) }}
                @endif
            </div>
            <div class="text-sm text-gray-500 font-medium mt-1">
                Platform Revenue (₹{{ number_format($totalRevenue / 100000, 2) }}L Total)
            </div>
        </div>
    </div>

    <!-- 2. Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Booking Trends Line Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Overall Platform Bookings</h3>
                    <p class="text-xs text-gray-500">Real-time tenant booking activity across all cities</p>
                </div>
                <div class="flex items-center gap-2">
                    <select id="chartPeriodSelect" onchange="updateBookingChartPeriod(this.value)" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/40 transition">
                        <option value="7days">Last 7 Days</option>
                        <option value="30days">Last 30 Days</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="adminBookingChart"></canvas>
            </div>
        </div>

        <!-- PG Category Breakdown Doughnut Chart -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-bold text-gray-900 text-lg">PG Category Breakdown</h3>
                    <span class="text-xs font-semibold text-brand bg-brand-50 px-2 py-0.5 rounded-md">Live</span>
                </div>
                <p class="text-xs text-gray-500 mb-4">{{ $totalProperties }} verified & listed property stays</p>
                <div class="relative w-44 h-44 mx-auto my-2">
                    <canvas id="adminPgChart"></canvas>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2.5 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand"></span> Boys PGs
                    </span>
                    <span class="font-bold text-gray-900">{{ $pgCategories['boys']['count'] }} ({{ $pgCategories['boys']['percent'] }}%)</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span> Girls PGs
                    </span>
                    <span class="font-bold text-gray-900">{{ $pgCategories['girls']['count'] }} ({{ $pgCategories['girls']['percent'] }}%)</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Co-living / Co-Ed
                    </span>
                    <span class="font-bold text-gray-900">{{ $pgCategories['coed']['count'] }} ({{ $pgCategories['coed']['percent'] }}%)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Recent Activity & Approvals Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Latest Tenant Bookings Stream -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">Latest Tenant Bookings</h3>
                        <p class="text-xs text-gray-500">Recent reservations created by tenants</p>
                    </div>
                    <a href="{{ route('admin.bookings') }}" class="text-xs font-bold text-brand hover:underline">
                        View All ({{ $totalBookings }})
                    </a>
                </div>

                <div class="space-y-3" id="recentBookingsList">
                    @forelse($recentBookings as $booking)
                        @php
                            $tenantName = $booking->user->profile->full_name ?? ($booking->user->name ?? 'Tenant User');
                            $firstName = $booking->user->profile->first_name ?? $tenantName;
                            $lastName = $booking->user->profile->last_name ?? '';
                            $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                            if (empty(trim($initials))) $initials = 'TN';
                            
                            $status = strtolower($booking->booking_status ?? 'pending');
                            $statusClasses = 'text-yellow-800 bg-yellow-100';
                            if ($status === 'confirmed') {
                                $statusClasses = 'text-green-700 bg-green-100';
                            } elseif ($status === 'completed') {
                                $statusClasses = 'text-blue-700 bg-blue-100';
                            } elseif ($status === 'cancelled') {
                                $statusClasses = 'text-red-700 bg-red-100';
                            }
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-3.5 bg-gray-50/70 hover:bg-gray-100/90 rounded-2xl transition border border-gray-100">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                    {{ $initials }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 truncate">{{ $tenantName }}</div>
                                    <div class="text-xs text-gray-500 truncate">
                                        {{ $booking->property->name ?? 'Property Stay' }} 
                                        @if(!empty($booking->property->city->name))
                                            • {{ $booking->property->city->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-sm font-bold text-gray-900">₹{{ number_format($booking->total_amount ?? $booking->base_rent) }}</div>
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md {{ $statusClasses }}">
                                    {{ $status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-2 text-xl">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div class="text-xs font-semibold text-gray-500">No recent bookings recorded</div>
                            <p class="text-[11px] text-gray-400 mt-0.5">New tenant bookings will automatically appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Showing {{ $recentBookings->count() }} of {{ $totalBookings }} bookings</span>
                <a href="{{ route('admin.bookings') }}" class="text-brand font-semibold hover:underline">Manage All Bookings &rarr;</a>
            </div>
        </div>

        <!-- Pending Moderation (Properties & Brokers) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-gray-900 text-lg">Pending Moderation Queue</h3>
                        <span id="pendingQueueBadge" class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                            {{ $pendingPropertiesList->count() + $pendingBrokersList->count() }}
                        </span>
                    </div>
                    <a href="{{ route('admin.pgs') }}" class="text-xs font-bold text-brand hover:underline">Review All</a>
                </div>

                <!-- Tabs -->
                <div class="flex items-center gap-2 mb-4 p-1 bg-gray-100/80 rounded-xl text-xs font-bold">
                    <button type="button" onclick="switchModerationTab('properties')" id="tabBtnProperties" class="flex-1 py-1.5 px-3 rounded-lg bg-white shadow-xs text-gray-900 transition">
                        Properties ({{ $pendingPropertiesList->count() }})
                    </button>
                    <button type="button" onclick="switchModerationTab('brokers')" id="tabBtnBrokers" class="flex-1 py-1.5 px-3 rounded-lg text-gray-500 hover:text-gray-900 transition">
                        Brokers KYC ({{ $pendingBrokersList->count() }})
                    </button>
                </div>

                <!-- Properties List -->
                <div id="tabContentProperties" class="space-y-3">
                    @forelse($pendingPropertiesList as $property)
                        <div id="property-card-{{ $property->id }}" class="flex items-center justify-between gap-3 p-3.5 bg-amber-50/40 rounded-2xl border border-amber-100 transition duration-200">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 truncate">{{ $property->name }}</div>
                                    <div class="text-xs text-gray-500 truncate">
                                        {{ $property->city->name ?? 'City' }} • ₹{{ number_format($property->monthly_rent) }}/mo • By {{ $property->broker->profile->first_name ?? ($property->broker->name ?? 'Partner Broker') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" onclick="approveProperty('{{ $property->id }}')" class="bg-brand hover:bg-brand-dark text-white text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect shadow-xs transition" title="Approve & Publish Live">
                                    Approve
                                </button>
                                <button type="button" onclick="rejectProperty('{{ $property->id }}')" class="bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 text-xs font-semibold px-2.5 py-1.5 rounded-xl tap-effect transition" title="Reject Listing">
                                    Decline
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-2 text-base">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="text-xs font-semibold text-gray-600">All properties are approved!</div>
                            <p class="text-[11px] text-gray-400 mt-0.5">No pending listings waiting for administrative moderation.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Brokers List -->
                <div id="tabContentBrokers" class="space-y-3 hidden">
                    @forelse($pendingBrokersList as $broker)
                        @php
                            $bName = $broker->profile->full_name ?? ($broker->name ?? $broker->email);
                            $bFirst = $broker->profile->first_name ?? $bName;
                            $bLast = $broker->profile->last_name ?? '';
                            $bInitials = strtoupper(substr($bFirst, 0, 1) . substr($bLast, 0, 1));
                        @endphp
                        <div id="broker-card-{{ $broker->id }}" class="flex items-center justify-between gap-3 p-3.5 bg-yellow-50/40 rounded-2xl border border-yellow-100 transition duration-200">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                    {{ $bInitials ?: 'BR' }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 truncate">{{ $bName }}</div>
                                    <div class="text-xs text-gray-500 truncate">
                                        {{ $broker->phone ?? $broker->email }} • {{ $broker->properties->count() }} Properties
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" onclick="approveBroker('{{ $broker->id }}')" class="bg-brand hover:bg-brand-dark text-white text-xs font-semibold px-3 py-1.5 rounded-xl tap-effect shadow-xs transition" title="Approve Broker KYC">
                                    Approve
                                </button>
                                <button type="button" onclick="rejectBroker('{{ $broker->id }}')" class="bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 text-xs font-semibold px-2.5 py-1.5 rounded-xl tap-effect transition" title="Reject Broker">
                                    Decline
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-2 text-base">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="text-xs font-semibold text-gray-600">All brokers are verified!</div>
                            <p class="text-[11px] text-gray-400 mt-0.5">No broker applications pending verification.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>1-Click verification automatically sends email alerts to partners</span>
                <a href="{{ route('admin.brokers') }}" class="text-brand font-semibold hover:underline">Manage All Brokers &rarr;</a>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Global CSRF Setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Dynamic Toast Messenger
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toastNotification');
        const text = document.getElementById('toastMessage');
        const icon = document.getElementById('toastIcon');

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

    // Switch Moderation Tabs
    function switchModerationTab(tab) {
        const btnProps = document.getElementById('tabBtnProperties');
        const btnBrokers = document.getElementById('tabBtnBrokers');
        const contentProps = document.getElementById('tabContentProperties');
        const contentBrokers = document.getElementById('tabContentBrokers');

        if (tab === 'properties') {
            btnProps.classList.add('bg-white', 'shadow-xs', 'text-gray-900');
            btnProps.classList.remove('text-gray-500');
            btnBrokers.classList.remove('bg-white', 'shadow-xs', 'text-gray-900');
            btnBrokers.classList.add('text-gray-500');

            contentProps.classList.remove('hidden');
            contentBrokers.classList.add('hidden');
        } else {
            btnBrokers.classList.add('bg-white', 'shadow-xs', 'text-gray-900');
            btnBrokers.classList.remove('text-gray-500');
            btnProps.classList.remove('bg-white', 'shadow-xs', 'text-gray-900');
            btnProps.classList.add('text-gray-500');

            contentBrokers.classList.remove('hidden');
            contentProps.classList.add('hidden');
        }
    }

    // 1-Click Approve Property via AJAX
    async function approveProperty(propertyId) {
        const card = document.getElementById(`property-card-${propertyId}`);
        if (!confirm('Are you sure you want to verify and publish this property listing?')) return;

        try {
            const res = await fetch(`/admin/properties/${propertyId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message || 'Property approved successfully!', 'success');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
                }
            } else {
                showToast(data.message || 'Failed to approve property', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while approving property', 'error');
        }
    }

    // 1-Click Reject Property via AJAX
    async function rejectProperty(propertyId) {
        const card = document.getElementById(`property-card-${propertyId}`);
        if (!confirm('Are you sure you want to reject this property listing?')) return;

        try {
            const res = await fetch(`/admin/properties/${propertyId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message || 'Property marked as rejected', 'success');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
                }
            } else {
                showToast(data.message || 'Failed to reject property', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while rejecting property', 'error');
        }
    }

    // 1-Click Approve Broker via AJAX
    async function approveBroker(brokerId) {
        const card = document.getElementById(`broker-card-${brokerId}`);
        if (!confirm('Are you sure you want to approve this broker and verify their KYC?')) return;

        try {
            const res = await fetch(`/admin/brokers/${brokerId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message || 'Broker verified successfully!', 'success');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
                }
            } else {
                showToast(data.message || 'Failed to approve broker', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while approving broker', 'error');
        }
    }

    // 1-Click Reject Broker via AJAX
    async function rejectBroker(brokerId) {
        const card = document.getElementById(`broker-card-${brokerId}`);
        if (!confirm('Are you sure you want to decline this broker application?')) return;

        try {
            const res = await fetch(`/admin/brokers/${brokerId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message || 'Broker rejected', 'success');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
                }
            } else {
                showToast(data.message || 'Failed to decline broker', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while declining broker', 'error');
        }
    }

    // ----------------------------------------------------
    // CHARTS INITIALIZATION
    // ----------------------------------------------------
    let bookingChartInstance = null;

    // Booking Trends Chart
    const ctxBooking = document.getElementById('adminBookingChart');
    if (ctxBooking) {
        const initialLabels = {!! json_encode($chartData['labels'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!};
        const initialData = {!! json_encode($chartData['data'] ?? [2, 4, 3, 5, 4, 6, 5]) !!};

        bookingChartInstance = new Chart(ctxBooking, {
            type: 'line',
            data: {
                labels: initialLabels,
                datasets: [{
                    label: 'Bookings',
                    data: initialData,
                    borderColor: '#4bb59d',
                    backgroundColor: 'rgba(75, 181, 157, 0.12)',
                    borderWidth: 3,
                    tension: 0.35,
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
                        backgroundColor: '#111827',
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(ctx) {
                                return ` ${ctx.parsed.y} Bookings`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0, color: '#9ca3af' },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Dynamic Chart Period Switcher
    async function updateBookingChartPeriod(range) {
        if (!bookingChartInstance) return;

        try {
            const res = await fetch(`{{ route('admin.dashboard.chart') }}?range=${range}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.labels && data.data) {
                bookingChartInstance.data.labels = data.labels;
                bookingChartInstance.data.datasets[0].data = data.data;
                bookingChartInstance.update();
            }
        } catch (err) {
            console.error('Error fetching chart data:', err);
        }
    }

    // PG Distribution Doughnut Chart
    const ctxPg = document.getElementById('adminPgChart');
    if (ctxPg) {
        const pgData = {!! json_encode($pgCategories) !!};
        const boysCount = pgData.boys.count || 0;
        const girlsCount = pgData.girls.count || 0;
        const coedCount = pgData.coed.count || 0;

        new Chart(ctxPg, {
            type: 'doughnut',
            data: {
                labels: ['Boys PGs', 'Girls PGs', 'Co-Living / Co-Ed'],
                datasets: [{
                    data: [boysCount || 1, girlsCount, coedCount],
                    backgroundColor: ['#4bb59d', '#ec4899', '#a855f7'],
                    hoverOffset: 4,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 10,
                        cornerRadius: 10,
                        displayColors: true
                    }
                }
            }
        });
    }
</script>
@endpush
