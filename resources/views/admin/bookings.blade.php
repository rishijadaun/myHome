@extends('admin.layouts.app')

@section('title', 'Manage All Bookings - StayNest Admin')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">All System Bookings</h1>
        <p class="text-sm text-gray-500">{{ $totalBookings }} total platform bookings • {{ $pendingCount }} pending owner/admin confirmation</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.bookings.export') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-semibold tap-effect flex items-center gap-2 transition text-sm">
            <i class="fas fa-download text-xs"></i> Export CSV
        </a>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.bookings') }}" class="bg-white rounded-2xl p-5 border {{ empty($status) ? 'border-brand ring-2 ring-brand/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-brand/40 transition">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">{{ $totalBookings }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Bookings</div>
        </a>
        <a href="{{ route('admin.bookings', ['status' => 'CONFIRMED']) }}" class="bg-white rounded-2xl p-5 border {{ $status === 'CONFIRMED' ? 'border-green-500 ring-2 ring-green-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-green-300 transition">
            <div class="text-2xl md:text-3xl font-bold text-green-600">{{ $confirmedCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Confirmed &amp; Active</div>
        </a>
        <a href="{{ route('admin.bookings', ['status' => 'PENDING']) }}" class="bg-white rounded-2xl p-5 border {{ $status === 'PENDING' ? 'border-yellow-500 ring-2 ring-yellow-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-yellow-300 transition">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600">{{ $pendingCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Review</div>
        </a>
        <a href="{{ route('admin.bookings', ['status' => 'CANCELLED']) }}" class="bg-white rounded-2xl p-5 border {{ $status === 'CANCELLED' ? 'border-red-500 ring-2 ring-red-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-red-300 transition">
            <div class="text-2xl md:text-3xl font-bold text-red-600">{{ $cancelledCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Cancelled / Rejected</div>
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input name="search" value="{{ $search ?? '' }}" type="text" placeholder="Search by Booking ID, Tenant Name, Phone, PG property, Owner..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                @if(!empty($search))
                    <a href="{{ route('admin.bookings', ['status' => $status, 'date' => $dateFilter]) }}" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>

            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer">
                <option value="">All Statuses</option>
                <option value="CONFIRMED" {{ ($status ?? '') === 'CONFIRMED' ? 'selected' : '' }}>Confirmed &amp; Active</option>
                <option value="PENDING" {{ ($status ?? '') === 'PENDING' ? 'selected' : '' }}>Pending Review</option>
                <option value="CANCELLED" {{ ($status ?? '') === 'CANCELLED' ? 'selected' : '' }}>Cancelled / Rejected</option>
            </select>

            <select name="date" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer">
                <option value="">All Time</option>
                <option value="today" {{ ($dateFilter ?? '') === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ ($dateFilter ?? '') === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ ($dateFilter ?? '') === 'month' ? 'selected' : '' }}>This Month</option>
            </select>

            <button type="submit" class="px-5 py-3 bg-brand hover:bg-brand-dark text-white rounded-xl text-sm font-bold transition tap-effect shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                <i class="fas fa-filter text-xs"></i> Filter
            </button>
        </form>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Owner / Host</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Check-in</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $bk)
                        @php
                            $prop = $bk->property;
                            $propName = $prop?->name ?? 'Verified Property';
                            $propImg = $prop?->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=100&q=80';
                            $tenantName = $bk->effective_tenant_name;
                            $tenantPhone = preg_replace('/[^0-9]/', '', $bk->effective_tenant_phone);
                            if (strlen($tenantPhone) > 10) $tenantPhone = substr($tenantPhone, -10);
                            $tenantEmail = $bk->tenant_email ?: ($bk->user?->email ?? 'N/A');
                            $tenantInitial = strtoupper(substr($tenantName, 0, 2));

                            $ownerName = $bk->broker?->name ?? 'Owner';
                            $ownerPhone = preg_replace('/[^0-9]/', '', $bk->broker?->phone ?? '');
                            if (strlen($ownerPhone) > 10) $ownerPhone = substr($ownerPhone, -10);

                            $statusMeta = $bk->display_status;
                            $isPending = ($bk->broker_approval === 'pending' && $bk->booking_status === 'pending');
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition {{ $isPending ? 'bg-yellow-50/20' : '' }}">
                            <!-- Booking ID -->
                            <td class="px-6 py-4 text-sm font-mono font-bold text-brand">
                                #{{ $bk->booking_id }}
                                <span class="block text-[10px] font-normal text-gray-400 font-sans mt-0.5">{{ $bk->created_at ? $bk->created_at->diffForHumans() : '' }}</span>
                            </td>

                            <!-- Tenant -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-brand/10 text-brand font-black rounded-full flex items-center justify-center text-xs shadow-xs flex-shrink-0">
                                        {{ $tenantInitial }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $tenantName }}</div>
                                        <div class="text-xs text-gray-500">
                                            @if($tenantPhone)
                                                <span>+91 {{ $tenantPhone }}</span>
                                            @else
                                                <span>{{ $tenantEmail }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Property -->
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium max-w-[200px]">
                                <div class="truncate font-semibold">{{ $propName }}</div>
                                <span class="text-xs text-gray-400 truncate block">{{ $prop?->city?->name ?? 'India' }} • {{ $bk->room_type_name ?: 'Standard' }}</span>
                            </td>

                            <!-- Owner / Broker -->
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                <div class="font-bold text-gray-900 text-xs">{{ $ownerName }}</div>
                                <div class="text-[11px] text-gray-500">{{ $ownerPhone ? '+91 ' . $ownerPhone : 'N/A' }}</div>
                            </td>

                            <!-- Check-in Date -->
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                {{ $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'Immediate' }}
                                <span class="block text-[11px] text-gray-400 font-normal">{{ $bk->duration_months ?: 11 }} Months</span>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 text-sm font-black text-gray-900">
                                ₹{{ number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit)) }}
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4">
                                <span class="{{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} text-xs font-bold px-2.5 py-1 rounded-lg">
                                    {{ $statusMeta['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($isPending)
                                        <button onclick="approveAdminBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect shadow-xs cursor-pointer" title="Admin Approve & Confirm">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                        <button onclick="rejectAdminBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect shadow-xs cursor-pointer" title="Admin Cancel">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    @endif

                                    <button onclick="viewAdminBookingModal({{ json_encode($bk) }}, '{{ addslashes($tenantName) }}', '{{ $tenantPhone }}', '{{ $tenantEmail }}', '{{ addslashes($propName) }}', '{{ addslashes($ownerName) }}', '{{ $ownerPhone }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect cursor-pointer" title="View Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500 text-sm">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <i class="fas fa-calendar-times text-lg"></i>
                                </div>
                                <p class="font-bold text-gray-700">No bookings match the filter criteria</p>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting search query or status filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-3">
        @forelse($bookings as $bk)
            @php
                $prop = $bk->property;
                $propName = $prop?->name ?? 'Verified Property';
                $tenantName = $bk->effective_tenant_name;
                $tenantPhone = preg_replace('/[^0-9]/', '', $bk->effective_tenant_phone);
                if (strlen($tenantPhone) > 10) $tenantPhone = substr($tenantPhone, -10);
                $tenantEmail = $bk->tenant_email ?: ($bk->user?->email ?? 'N/A');
                $ownerName = $bk->broker?->name ?? 'Owner';
                $ownerPhone = preg_replace('/[^0-9]/', '', $bk->broker?->phone ?? '');
                if (strlen($ownerPhone) > 10) $ownerPhone = substr($ownerPhone, -10);
                $statusMeta = $bk->display_status;
                $isPending = ($bk->broker_approval === 'pending' && $bk->booking_status === 'pending');
            @endphp
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm space-y-3 {{ $isPending ? 'bg-yellow-50/20' : '' }}">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-mono font-bold text-brand">#{{ $bk->booking_id }}</span>
                        <h4 class="font-bold text-gray-900 text-sm mt-0.5">{{ $tenantName }}</h4>
                        <p class="text-xs text-gray-500">{{ $propName }}</p>
                    </div>
                    <span class="{{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} text-[10px] font-bold px-2 py-0.5 rounded-md">
                        {{ $statusMeta['label'] }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs bg-gray-50 p-2.5 rounded-xl">
                    <div>
                        <span class="text-gray-400 text-[10px] uppercase font-bold block">Check-in</span>
                        <span class="font-bold text-gray-800">{{ $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'Immediate' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-[10px] uppercase font-bold block">Amount</span>
                        <span class="font-black text-brand">₹{{ number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit)) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <div class="text-[11px] text-gray-500">
                        Host: <strong>{{ $ownerName }}</strong>
                    </div>

                    <div class="flex items-center gap-1.5 ml-auto">
                        @if($isPending)
                            <button onclick="approveAdminBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-lg shadow-xs cursor-pointer">
                                Approve
                            </button>
                            <button onclick="rejectAdminBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg shadow-xs cursor-pointer">
                                Reject
                            </button>
                        @endif
                        <button onclick="viewAdminBookingModal({{ json_encode($bk) }}, '{{ addslashes($tenantName) }}', '{{ $tenantPhone }}', '{{ $tenantEmail }}', '{{ addslashes($propName) }}', '{{ addslashes($ownerName) }}', '{{ $ownerPhone }}')" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg cursor-pointer">
                            View
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 text-xs border border-dashed border-gray-200">
                No bookings found.
            </div>
        @endforelse

        @if($bookings->hasPages())
            <div class="pt-2">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ================= ADMIN BOOKING DETAILS MODAL ================= -->
<div id="adminBookingModal" class="fixed inset-0 z-[3000] hidden items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div onclick="closeAdminBookingModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 z-10 border border-gray-100 animate-in fade-in zoom-in-95 duration-200 my-8">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-black text-gray-900" id="aModalId">Platform Booking Record</h3>
                <p class="text-xs text-gray-500">Full system reservation and verification audit</p>
            </div>
            <button onclick="closeAdminBookingModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition cursor-pointer">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <div class="space-y-4 text-xs">
            <!-- Tenant & Host Row -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-1">Tenant Information</span>
                    <h4 class="font-bold text-gray-900" id="aModalTenantName"></h4>
                    <p class="text-gray-600 text-[11px]" id="aModalTenantPhone"></p>
                    <p class="text-gray-500 text-[10px]" id="aModalTenantEmail"></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-1">Property Owner</span>
                    <h4 class="font-bold text-gray-900" id="aModalOwnerName"></h4>
                    <p class="text-gray-600 text-[11px]" id="aModalOwnerPhone"></p>
                </div>
            </div>

            <!-- Booking Specs -->
            <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Property</span>
                    <span class="font-bold text-gray-900 text-xs" id="aModalPropName"></span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Room Plan</span>
                    <span class="font-bold text-gray-900 text-xs" id="aModalRoomPlan"></span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Check-in Date</span>
                    <span class="font-bold text-gray-900 text-xs" id="aModalCheckIn"></span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Duration</span>
                    <span class="font-bold text-gray-900 text-xs" id="aModalDuration"></span>
                </div>
            </div>

            <!-- Financials -->
            <div class="p-3 bg-emerald-50/60 rounded-2xl border border-emerald-100 space-y-1.5">
                <div class="flex justify-between text-gray-600">
                    <span>Monthly Rent:</span>
                    <span class="font-bold text-gray-900" id="aModalRent"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Security Deposit:</span>
                    <span class="font-bold text-gray-900" id="aModalDeposit"></span>
                </div>
                <div class="border-t border-emerald-200/80 pt-1.5 flex justify-between font-black text-sm text-brand-dark">
                    <span>Total Amount:</span>
                    <span id="aModalTotal"></span>
                </div>
            </div>

            <!-- Special Requests -->
            <div id="aModalSpecialBox" class="p-3 bg-amber-50 rounded-2xl border border-amber-100 hidden">
                <span class="text-[10px] text-amber-800 font-bold uppercase block mb-0.5">Special Requests / Notes</span>
                <p class="text-gray-700" id="aModalSpecialText"></p>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
            <button onclick="closeAdminBookingModal()" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-xl transition cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function approveAdminBooking(bookingId, tenantName, bookingCode) {
        if (typeof Swal === 'undefined') {
            if (confirm(`Approve booking ${bookingCode} for ${tenantName}?`)) {
                submitApproveAdminBooking(bookingId);
            }
            return;
        }

        const result = await Swal.fire({
            title: `Approve Booking ${bookingCode}?`,
            text: `Confirm stay reservation for ${tenantName}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            submitApproveAdminBooking(bookingId);
        }
    }

    async function submitApproveAdminBooking(bookingId) {
        try {
            const response = await fetch(`/admin/bookings/${bookingId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Booking Approved! 🎉',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(data.message || 'Booking approved!');
                    window.location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not approve booking.',
                        icon: 'error',
                        confirmButtonColor: '#4bb59d'
                    });
                } else {
                    alert(data.message || 'Could not approve booking.');
                }
            }
        } catch (err) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Network Error',
                    text: 'An error occurred while approving booking.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('An error occurred while approving booking.');
            }
        }
    }

    async function rejectAdminBooking(bookingId, tenantName, bookingCode) {
        if (typeof Swal === 'undefined') {
            const reason = prompt(`Cancel Booking ${bookingCode}? Reason for cancellation:`, 'Cancelled by administration.');
            if (reason !== null) {
                submitRejectAdminBooking(bookingId, reason);
            }
            return;
        }

        const { value: reason } = await Swal.fire({
            title: `Cancel Booking ${bookingCode}?`,
            text: `Please provide cancellation reason for ${tenantName}'s booking:`,
            icon: 'warning',
            input: 'text',
            inputValue: 'Cancelled by administration.',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Cancel Booking',
            cancelButtonText: 'Keep'
        });

        if (reason !== undefined) {
            submitRejectAdminBooking(bookingId, reason);
        }
    }

    async function submitRejectAdminBooking(bookingId, reason) {
        try {
            const response = await fetch(`/admin/bookings/${bookingId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ reason: reason || 'Cancelled by admin' })
            });

            const data = await response.json();
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Booking Cancelled',
                        text: data.message,
                        icon: 'info',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(data.message || 'Booking cancelled.');
                    window.location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not cancel booking.',
                        icon: 'error',
                        confirmButtonColor: '#4bb59d'
                    });
                } else {
                    alert(data.message || 'Could not cancel booking.');
                }
            }
        } catch (err) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Network Error',
                    text: 'An error occurred while communicating with the server.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('An error occurred while communicating with the server.');
            }
        }
    }

    function viewAdminBookingModal(bk, tenantName, tenantPhone, tenantEmail, propName, ownerName, ownerPhone) {
        document.getElementById('aModalId').textContent = `Booking #${bk.booking_id}`;
        document.getElementById('aModalTenantName').textContent = tenantName;
        document.getElementById('aModalTenantPhone').textContent = tenantPhone ? `+91 ${tenantPhone}` : 'No phone provided';
        document.getElementById('aModalTenantEmail').textContent = tenantEmail;

        document.getElementById('aModalOwnerName').textContent = ownerName;
        document.getElementById('aModalOwnerPhone').textContent = ownerPhone ? `+91 ${ownerPhone}` : 'N/A';

        document.getElementById('aModalPropName').textContent = propName;
        document.getElementById('aModalRoomPlan').textContent = bk.room_type_name || 'Standard Stay';
        document.getElementById('aModalCheckIn').textContent = bk.check_in_date ? new Date(bk.check_in_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Immediate';
        document.getElementById('aModalDuration').textContent = (bk.duration_months || 11) + ' Months';
        document.getElementById('aModalRent').textContent = '₹' + Number(bk.base_rent).toLocaleString('en-IN') + '/mo';
        document.getElementById('aModalDeposit').textContent = '₹' + Number(bk.security_deposit || 0).toLocaleString('en-IN');
        document.getElementById('aModalTotal').textContent = '₹' + Number(bk.total_amount || (Number(bk.base_rent) + Number(bk.security_deposit || 0))).toLocaleString('en-IN');

        const specialBox = document.getElementById('aModalSpecialBox');
        const specialText = document.getElementById('aModalSpecialText');
        if (bk.special_requests) {
            specialText.textContent = bk.special_requests;
            specialBox.classList.remove('hidden');
        } else {
            specialBox.classList.add('hidden');
        }

        const modal = document.getElementById('adminBookingModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeAdminBookingModal() {
        const modal = document.getElementById('adminBookingModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdminBookingModal();
        }
    });
</script>
@endpush
