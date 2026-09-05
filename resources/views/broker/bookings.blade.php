@extends('broker.layouts.app')

@section('title', 'Tenant Bookings - SpaceSeeks Broker Portal')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tenant Bookings</h1>
        <p class="text-sm text-gray-500">{{ $totalCount }} total reservations • {{ $pendingCount }} pending owner confirmation</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('broker.bookings.export') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold tap-effect flex items-center gap-2 transition">
            <i class="fas fa-download text-xs"></i> Export CSV
        </a>
        <a href="{{ route('broker.pgs') }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2.5 rounded-xl text-sm font-semibold tap-effect flex items-center gap-2 transition shadow-xs">
            <i class="fas fa-plus text-xs"></i> Manage Properties
        </a>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- 5-Column Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <a href="{{ route('broker.bookings', ['status' => 'ALL']) }}" class="bg-white rounded-2xl p-4 sm:p-5 border {{ ($activeTab ?? 'ALL') === 'ALL' ? 'border-brand ring-2 ring-brand/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-brand/40 transition">
            <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Bookings</div>
        </a>
        <a href="{{ route('broker.bookings', ['status' => 'PENDING']) }}" class="bg-white rounded-2xl p-4 sm:p-5 border {{ ($activeTab ?? '') === 'PENDING' ? 'border-yellow-500 ring-2 ring-yellow-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-yellow-300 transition">
            <div class="text-xl sm:text-2xl font-bold text-yellow-600 flex items-center justify-between">
                <span>{{ $pendingCount }}</span>
                @if($pendingCount > 0)
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 animate-pulse"></span>
                @endif
            </div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Review</div>
        </a>
        <a href="{{ route('broker.bookings', ['status' => 'CONFIRMED']) }}" class="bg-white rounded-2xl p-4 sm:p-5 border {{ ($activeTab ?? '') === 'CONFIRMED' ? 'border-green-500 ring-2 ring-green-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-green-300 transition">
            <div class="text-xl sm:text-2xl font-bold text-green-600">{{ $confirmedCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active Stays</div>
        </a>
        <a href="{{ route('broker.bookings', ['status' => 'COMPLETED']) }}" class="bg-white rounded-2xl p-4 sm:p-5 border {{ ($activeTab ?? '') === 'COMPLETED' ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-blue-300 transition">
            <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ $completedCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Completed Stays</div>
        </a>
        <a href="{{ route('broker.bookings', ['status' => 'CANCELLED']) }}" class="bg-white rounded-2xl p-4 sm:p-5 border {{ ($activeTab ?? '') === 'CANCELLED' ? 'border-red-500 ring-2 ring-red-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-red-300 transition col-span-2 lg:col-span-1">
            <div class="text-xl sm:text-2xl font-bold text-red-600">{{ $cancelledCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Cancelled / Declined</div>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm space-y-4">
        <!-- Segmented Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
            <a href="{{ route('broker.bookings', ['status' => 'ALL', 'date' => $dateFilter ?? '', 'search' => $search ?? '']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? 'ALL') === 'ALL' ? 'bg-brand text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                All ({{ $totalCount }})
            </a>
            <a href="{{ route('broker.bookings', ['status' => 'PENDING', 'date' => $dateFilter ?? '', 'search' => $search ?? '']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? '') === 'PENDING' ? 'bg-yellow-500 text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                Pending ({{ $pendingCount }})
            </a>
            <a href="{{ route('broker.bookings', ['status' => 'CONFIRMED', 'date' => $dateFilter ?? '', 'search' => $search ?? '']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? '') === 'CONFIRMED' ? 'bg-green-600 text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                Active Stays ({{ $confirmedCount }})
            </a>
            <a href="{{ route('broker.bookings', ['status' => 'COMPLETED', 'date' => $dateFilter ?? '', 'search' => $search ?? '']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? '') === 'COMPLETED' ? 'bg-blue-600 text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                Completed ({{ $completedCount }})
            </a>
            <a href="{{ route('broker.bookings', ['status' => 'CANCELLED', 'date' => $dateFilter ?? '', 'search' => $search ?? '']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? '') === 'CANCELLED' ? 'bg-red-600 text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                Cancelled ({{ $cancelledCount }})
            </a>
        </div>

        <!-- Search & Date Filter Form -->
        <form method="GET" action="{{ route('broker.bookings') }}" class="flex flex-col md:flex-row gap-3">
            <input type="hidden" name="status" value="{{ $activeTab ?? 'ALL' }}">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                <input name="search" value="{{ $search ?? '' }}" type="text" placeholder="Search by Booking ID, Tenant Name, Phone, Email, Property..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-10 pr-4 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                @if(!empty($search))
                    <a href="{{ route('broker.bookings', ['status' => $activeTab ?? 'ALL', 'date' => $dateFilter ?? '']) }}" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>

            <select name="date" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 cursor-pointer">
                <option value="">All Dates</option>
                <option value="today" {{ ($dateFilter ?? '') === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ ($dateFilter ?? '') === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ ($dateFilter ?? '') === 'month' ? 'selected' : '' }}>This Month</option>
            </select>

            <button type="submit" class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white rounded-xl text-xs sm:text-sm font-bold transition tap-effect shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                <i class="fas fa-filter text-xs"></i> Filter
            </button>
        </form>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Plan &amp; Duration</th>
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
                            $propName = $prop?->name ?? 'Your Property';
                            $propImg = $prop?->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=100&q=80';
                            $tenantName = $bk->effective_tenant_name;
                            $tenantPhone = preg_replace('/[^0-9]/', '', $bk->effective_tenant_phone);
                            if (strlen($tenantPhone) > 10) $tenantPhone = substr($tenantPhone, -10);
                            $tenantEmail = $bk->tenant_email ?: ($bk->user?->email ?? 'N/A');
                            $tenantInitial = strtoupper(substr($tenantName, 0, 2));
                            $statusMeta = $bk->display_status;
                            $isPending = ($bk->broker_approval === 'pending' && $bk->booking_status === 'pending');
                            $isConfirmed = ($bk->broker_approval === 'approved' || $bk->booking_status === 'confirmed');
                            $isCompleted = ($bk->booking_status === 'completed');
                            $checkInFormatted = $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'Immediate';
                            $durationText = ($bk->duration_months ?: 11) . ' Months';
                            $amountText = '₹' . number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit));
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition {{ $isPending ? 'bg-yellow-50/20' : '' }}">
                            <!-- Booking ID -->
                            <td class="px-6 py-4 text-sm font-mono font-bold text-brand">
                                #{{ $bk->booking_id }}
                                <span class="block text-[10px] font-normal text-gray-400 font-sans mt-0.5">{{ $bk->created_at ? $bk->created_at->diffForHumans() : '' }}</span>
                            </td>

                            <!-- Tenant Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-brand/10 text-brand font-black rounded-full flex items-center justify-center text-xs shadow-xs flex-shrink-0">
                                        {{ $tenantInitial }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $tenantName }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1.5">
                                            @if($tenantPhone)
                                                <a href="tel:{{ $tenantPhone }}" class="hover:text-brand transition flex items-center gap-1">
                                                    <i class="fas fa-phone-alt text-[10px] text-gray-400"></i> +91 {{ $tenantPhone }}
                                                </a>
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
                                <span class="text-xs text-gray-400 truncate block">{{ $prop?->city?->name ?? 'India' }}</span>
                            </td>

                            <!-- Plan & Duration -->
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <span class="font-bold text-gray-900 block">{{ $bk->room_type_name ?: 'Standard Stay' }}</span>
                                <span class="text-gray-500">{{ $durationText }}</span>
                            </td>

                            <!-- Check-in Date -->
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                {{ $checkInFormatted }}
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 text-sm font-black text-gray-900">
                                {{ $amountText }}
                                <span class="block text-[10px] font-normal text-gray-400">Rent: ₹{{ number_format($bk->base_rent) }}/mo</span>
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
                                        <button onclick="approveBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect shadow-xs cursor-pointer" title="Accept & Confirm Stay">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                        <button onclick="rejectBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect shadow-xs cursor-pointer" title="Decline Request">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    @elseif($isConfirmed && !$isCompleted)
                                        <button onclick="completeBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="w-8 h-8 rounded-lg bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center tap-effect shadow-xs cursor-pointer" title="Mark Stay Completed / Checked Out">
                                            <i class="fas fa-check-double text-xs"></i>
                                        </button>
                                    @endif

                                    @if($tenantPhone)
                                        <a href="https://wa.me/91{{ $tenantPhone }}?text={{ urlencode('Hi ' . $tenantName . ', regarding your booking #' . $bk->booking_id . ' for ' . $propName . ' on SpaceSeeks.') }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center tap-effect" title="WhatsApp Tenant">
                                            <i class="fab fa-whatsapp text-sm"></i>
                                        </a>
                                    @endif

                                    <!-- View Details Modal Trigger -->
                                    <button type="button"
                                        onclick="openBrokerBookingDetails(this)"
                                        data-booking-id="{{ $bk->booking_id }}"
                                        data-id="{{ $bk->id }}"
                                        data-tenant-name="{{ $tenantName }}"
                                        data-tenant-phone="{{ $tenantPhone }}"
                                        data-tenant-email="{{ $tenantEmail }}"
                                        data-prop-name="{{ $propName }}"
                                        data-prop-img="{{ $propImg }}"
                                        data-check-in="{{ $checkInFormatted }}"
                                        data-duration="{{ $durationText }}"
                                        data-room-type="{{ $bk->room_type_name ?: 'Standard Stay' }}"
                                        data-rent="{{ number_format($bk->base_rent) }}"
                                        data-deposit="{{ number_format($bk->security_deposit) }}"
                                        data-total="{{ $amountText }}"
                                        data-status="{{ $statusMeta['label'] }}"
                                        data-is-pending="{{ $isPending ? '1' : '0' }}"
                                        data-is-confirmed="{{ ($isConfirmed && !$isCompleted) ? '1' : '0' }}"
                                        data-special-requests="{{ $bk->special_requests ?? '' }}"
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect cursor-pointer" title="View Full Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <!-- Receipt Trigger -->
                                    <button type="button"
                                        onclick="openBrokerInvoice(this)"
                                        data-booking-id="{{ $bk->booking_id }}"
                                        data-tenant-name="{{ $tenantName }}"
                                        data-prop-name="{{ $propName }}"
                                        data-check-in="{{ $checkInFormatted }}"
                                        data-room-type="{{ $bk->room_type_name ?: 'Standard Room' }}"
                                        data-duration="{{ $durationText }}"
                                        data-rent="{{ number_format($bk->base_rent) }}"
                                        data-deposit="{{ number_format($bk->security_deposit) }}"
                                        data-total="{{ $amountText }}"
                                        class="w-8 h-8 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 flex items-center justify-center tap-effect cursor-pointer" title="Print Receipt">
                                        <i class="fas fa-file-invoice text-xs"></i>
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
                                <p class="font-bold text-gray-700">No booking requests found</p>
                                <p class="text-xs text-gray-400 mt-1">Tenant booking requests for your properties will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($bookings as $bk)
                @php
                    $prop = $bk->property;
                    $propName = $prop?->name ?? 'Your Property';
                    $propImg = $prop?->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=100&q=80';
                    $tenantName = $bk->effective_tenant_name;
                    $tenantPhone = preg_replace('/[^0-9]/', '', $bk->effective_tenant_phone);
                    if (strlen($tenantPhone) > 10) $tenantPhone = substr($tenantPhone, -10);
                    $tenantEmail = $bk->tenant_email ?: ($bk->user?->email ?? 'N/A');
                    $statusMeta = $bk->display_status;
                    $isPending = ($bk->broker_approval === 'pending' && $bk->booking_status === 'pending');
                    $isConfirmed = ($bk->broker_approval === 'approved' || $bk->booking_status === 'confirmed');
                    $isCompleted = ($bk->booking_status === 'completed');
                    $checkInFormatted = $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'Immediate';
                    $durationText = ($bk->duration_months ?: 11) . ' Months';
                    $amountText = '₹' . number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit));
                @endphp
                <div class="p-4 space-y-3 {{ $isPending ? 'bg-yellow-50/20' : '' }}">
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
                            <span class="font-bold text-gray-800">{{ $checkInFormatted }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase font-bold block">Total Amount</span>
                            <span class="font-black text-brand">{{ $amountText }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 gap-2 flex-wrap">
                        @if($tenantPhone)
                            <a href="https://wa.me/91{{ $tenantPhone }}" target="_blank" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg flex items-center gap-1">
                                <i class="fab fa-whatsapp"></i> Chat
                            </a>
                        @endif

                        <div class="flex items-center gap-1.5 ml-auto">
                            @if($isPending)
                                <button onclick="approveBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-lg shadow-xs cursor-pointer">
                                    Accept
                                </button>
                                <button onclick="rejectBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg shadow-xs cursor-pointer">
                                    Decline
                                </button>
                            @elseif($isConfirmed && !$isCompleted)
                                <button onclick="completeBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-lg shadow-xs cursor-pointer">
                                    Complete
                                </button>
                            @endif

                            <button type="button"
                                onclick="openBrokerBookingDetails(this)"
                                data-booking-id="{{ $bk->booking_id }}"
                                data-id="{{ $bk->id }}"
                                data-tenant-name="{{ $tenantName }}"
                                data-tenant-phone="{{ $tenantPhone }}"
                                data-tenant-email="{{ $tenantEmail }}"
                                data-prop-name="{{ $propName }}"
                                data-prop-img="{{ $propImg }}"
                                data-check-in="{{ $checkInFormatted }}"
                                data-duration="{{ $durationText }}"
                                data-room-type="{{ $bk->room_type_name ?: 'Standard Stay' }}"
                                data-rent="{{ number_format($bk->base_rent) }}"
                                data-deposit="{{ number_format($bk->security_deposit) }}"
                                data-total="{{ $amountText }}"
                                data-status="{{ $statusMeta['label'] }}"
                                data-is-pending="{{ $isPending ? '1' : '0' }}"
                                data-is-confirmed="{{ ($isConfirmed && !$isCompleted) ? '1' : '0' }}"
                                data-special-requests="{{ $bk->special_requests ?? '' }}"
                                class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg cursor-pointer">
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 text-sm">
                    <p class="font-bold">No bookings found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="p-4 border-t border-gray-100 flex items-center justify-between">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ================= BROKER BOOKING DETAILS MODAL ================= -->
<div id="brokerBookingModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-y-auto">
    <div onclick="closeBrokerBookingModal()" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>
    <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 z-10 border border-gray-100 shadow-2xl space-y-4 my-auto">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-gray-900" id="bModalId">Booking Details</h3>
                <p class="text-xs text-gray-500">Tenant reservation review record</p>
            </div>
            <button onclick="closeBrokerBookingModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition cursor-pointer">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <div class="space-y-3.5 text-xs">
            <!-- Tenant Information Card -->
            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 space-y-1">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Tenant Information</span>
                <div class="font-bold text-sm text-gray-900" id="bModalTenantName"></div>
                <div class="text-gray-600 flex items-center gap-2 pt-0.5">
                    <span id="bModalTenantPhone"></span>
                    <span>•</span>
                    <span id="bModalTenantEmail"></span>
                </div>
                <div class="pt-2 flex items-center gap-2">
                    <a id="bModalCallLink" href="#" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold transition flex items-center gap-1">
                        <i class="fas fa-phone-alt text-[10px]"></i> Call
                    </a>
                    <a id="bModalWhatsappLink" href="#" target="_blank" class="px-3 py-1.5 bg-emerald-600 text-white rounded-xl font-bold transition flex items-center gap-1">
                        <i class="fab fa-whatsapp text-xs"></i> WhatsApp
                    </a>
                </div>
            </div>

            <!-- Property & Plan Grid -->
            <div class="grid grid-cols-2 gap-2.5 p-3.5 bg-gray-50/70 rounded-2xl border border-gray-100">
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Property</span>
                    <span class="font-bold text-gray-900 text-xs" id="bModalPropName"></span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Room Plan</span>
                    <span class="font-bold text-gray-900 text-xs" id="bModalRoomPlan"></span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Check-in Date</span>
                    <span class="font-bold text-gray-900 text-xs" id="bModalCheckIn"></span>
                </div>
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Duration</span>
                    <span class="font-bold text-gray-900 text-xs" id="bModalDuration"></span>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 space-y-1.5">
                <div class="flex justify-between text-gray-600">
                    <span>Monthly Rent:</span>
                    <span class="font-bold text-gray-900" id="bModalRent"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Security Deposit:</span>
                    <span class="font-bold text-gray-900" id="bModalDeposit"></span>
                </div>
                <div class="border-t border-gray-200 pt-1.5 flex justify-between font-black text-sm text-brand-dark">
                    <span>Estimated Total:</span>
                    <span id="bModalTotal"></span>
                </div>
            </div>

            <!-- Special Requests / Notes -->
            <div id="bModalSpecialBox" class="p-3 bg-amber-50 rounded-2xl border border-amber-100 hidden">
                <span class="text-[10px] text-amber-800 font-bold uppercase block mb-0.5">Move-in Requests / Notes</span>
                <p class="text-gray-700" id="bModalSpecialText"></p>
            </div>
        </div>

        <div class="mt-5 pt-3 border-t border-gray-100 flex items-center justify-between gap-2" id="bModalFooter">
            <div class="flex items-center gap-1.5" id="bModalActionBtns">
                <!-- Injected conditionally by JS -->
            </div>
            <button onclick="closeBrokerBookingModal()" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-xl transition cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<!-- ================= BROKER INVOICE / RECEIPT MODAL ================= -->
<div id="brokerInvoiceModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-y-auto">
    <div onclick="closeBrokerInvoiceModal()" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>
    <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 z-10 border border-gray-100 shadow-2xl space-y-4 my-auto printable-receipt">
        <div class="flex items-start justify-between pb-4 border-b border-gray-200">
            <div>
                <div class="flex items-center gap-1.5 text-brand font-black text-xl mb-0.5">
                    <i class="fas fa-house-chimney"></i> SpaceSeeks
                </div>
                <p class="text-[10px] text-gray-500">Property Host Booking Voucher</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-mono text-gray-400 block leading-none">INVOICE NO.</span>
                <span class="text-xs font-mono font-bold text-gray-900" id="bInvNumber"></span>
            </div>
        </div>

        <div class="space-y-3 text-xs">
            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 space-y-1">
                <div class="flex justify-between">
                    <span class="text-gray-500">Tenant:</span>
                    <span class="font-bold text-gray-900" id="bInvTenant"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Property:</span>
                    <span class="font-bold text-gray-900" id="bInvProp"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Check-in:</span>
                    <span class="font-bold text-gray-900" id="bInvCheckIn"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Plan:</span>
                    <span class="font-bold text-gray-900" id="bInvPlan"></span>
                </div>
            </div>

            <div class="space-y-1.5 py-1">
                <div class="flex justify-between text-gray-600">
                    <span>Monthly Rent:</span>
                    <span class="font-bold text-gray-900" id="bInvRent"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Security Deposit:</span>
                    <span class="font-bold text-gray-900" id="bInvDeposit"></span>
                </div>
                <div class="border-t border-gray-200 pt-1.5 flex justify-between font-black text-sm text-gray-900">
                    <span>Total Move-in:</span>
                    <span class="text-brand font-black" id="bInvTotal"></span>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5 no-print">
            <button onclick="closeBrokerInvoiceModal()" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-50 transition cursor-pointer">
                Close
            </button>
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-brand hover:bg-brand-dark text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                <i class="fas fa-print"></i> Print Voucher
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function approveBrokerBooking(bookingId, tenantName, bookingCode) {
        if (typeof Swal === 'undefined') {
            if (confirm(`Do you want to confirm the stay for ${tenantName} (${bookingCode})?`)) {
                submitApproveBrokerBooking(bookingId);
            }
            return;
        }

        const result = await Swal.fire({
            title: `Accept Booking ${bookingCode}?`,
            text: `Do you want to confirm the stay for ${tenantName}? This will notify the tenant.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Accept Booking',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            submitApproveBrokerBooking(bookingId);
        }
    }

    async function submitApproveBrokerBooking(bookingId) {
        const currentCsrf = (typeof window.getBrokerCsrfToken === 'function' ? window.getBrokerCsrfToken() : (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'));

        try {
            const response = await fetch(`/broker/bookings/${bookingId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': currentCsrf
                },
                body: JSON.stringify({
                    _token: currentCsrf
                })
            });

            if (response.status === 419) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Session Expired',
                        text: 'Your security session has expired. Refreshing the page...',
                        icon: 'warning',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => window.location.reload());
                } else {
                    alert('Session expired. Refreshing page...');
                    window.location.reload();
                }
                return;
            }

            const data = await response.json();
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Booking Accepted! 🎉',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(data.message || 'Booking accepted!');
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
                    text: 'An error occurred while updating booking status.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('An error occurred while updating booking status.');
            }
        }
    }

    async function rejectBrokerBooking(bookingId, tenantName, bookingCode) {
        if (typeof Swal === 'undefined') {
            const reason = prompt(`Decline Booking ${bookingCode}? Reason for declining:`, 'No beds/rooms currently available for requested dates.');
            if (reason !== null) {
                submitRejectBrokerBooking(bookingId, reason);
            }
            return;
        }

        const { value: reason } = await Swal.fire({
            title: `Decline Booking ${bookingCode}?`,
            text: `Please state the reason for declining ${tenantName}'s booking request:`,
            icon: 'warning',
            input: 'text',
            inputValue: 'No beds/rooms currently available for requested dates.',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Decline Request',
            cancelButtonText: 'Cancel'
        });

        if (reason !== undefined) {
            submitRejectBrokerBooking(bookingId, reason);
        }
    }

    async function submitRejectBrokerBooking(bookingId, reason) {
        const currentCsrf = (typeof window.getBrokerCsrfToken === 'function' ? window.getBrokerCsrfToken() : (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'));

        try {
            const response = await fetch(`/broker/bookings/${bookingId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': currentCsrf
                },
                body: JSON.stringify({
                    _token: currentCsrf,
                    reason: reason || 'Declined due to unavailability.'
                })
            });

            if (response.status === 419) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Session Expired',
                        text: 'Your security session has expired. Refreshing the page...',
                        icon: 'warning',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => window.location.reload());
                } else {
                    alert('Session expired. Refreshing page...');
                    window.location.reload();
                }
                return;
            }

            const data = await response.json();
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Booking Declined',
                        text: data.message,
                        icon: 'info',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(data.message || 'Booking declined.');
                    window.location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not decline booking.',
                        icon: 'error',
                        confirmButtonColor: '#4bb59d'
                    });
                } else {
                    alert(data.message || 'Could not decline booking.');
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

    async function completeBrokerBooking(bookingId, tenantName, bookingCode) {
        if (typeof Swal === 'undefined') {
            if (confirm(`Mark booking ${bookingCode} for ${tenantName} as Completed (Checked Out)?`)) {
                submitCompleteBrokerBooking(bookingId);
            }
            return;
        }

        const result = await Swal.fire({
            title: `Complete Stay ${bookingCode}?`,
            text: `Mark stay for ${tenantName} as Completed / Checked Out? This will release the bed inventory.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Complete Stay',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            submitCompleteBrokerBooking(bookingId);
        }
    }

    async function submitCompleteBrokerBooking(bookingId) {
        const currentCsrf = (typeof window.getBrokerCsrfToken === 'function' ? window.getBrokerCsrfToken() : (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'));

        try {
            const response = await fetch(`/broker/bookings/${bookingId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': currentCsrf
                },
                body: JSON.stringify({
                    _token: currentCsrf
                })
            });

            if (response.status === 419) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Session Expired',
                        text: 'Your security session has expired. Refreshing the page...',
                        icon: 'warning',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => window.location.reload());
                } else {
                    alert('Session expired. Refreshing page...');
                    window.location.reload();
                }
                return;
            }

            const data = await response.json();
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Stay Completed! 🌟',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(data.message || 'Stay marked as completed.');
                    window.location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not complete booking.',
                        icon: 'error',
                        confirmButtonColor: '#4bb59d'
                    });
                } else {
                    alert(data.message || 'Could not complete booking.');
                }
            }
        } catch (err) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Network Error',
                    text: 'An error occurred while completing booking.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('An error occurred while completing booking.');
            }
        }
    }

    // Modal Details Handler with HTML5 data attributes
    function openBrokerBookingDetails(btn) {
        const ds = btn.dataset;
        document.getElementById('bModalId').textContent = `Booking #${ds.bookingId}`;
        document.getElementById('bModalTenantName').textContent = ds.tenantName;
        document.getElementById('bModalTenantPhone').textContent = ds.tenantPhone ? `+91 ${ds.tenantPhone}` : 'No phone';
        document.getElementById('bModalTenantEmail').textContent = ds.tenantEmail;
        document.getElementById('bModalPropName').textContent = ds.propName;
        document.getElementById('bModalRoomPlan').textContent = ds.roomType;
        document.getElementById('bModalCheckIn').textContent = ds.checkIn;
        document.getElementById('bModalDuration').textContent = ds.duration;
        document.getElementById('bModalRent').textContent = `₹${ds.rent}/mo`;
        document.getElementById('bModalDeposit').textContent = `₹${ds.deposit}`;
        document.getElementById('bModalTotal').textContent = ds.total;

        document.getElementById('bModalCallLink').href = `tel:${ds.tenantPhone}`;
        document.getElementById('bModalWhatsappLink').href = `https://wa.me/91${ds.tenantPhone}?text=${encodeURIComponent('Hi ' + ds.tenantName + ', regarding booking #' + ds.bookingId + ' for ' + ds.propName)}`;

        const specialBox = document.getElementById('bModalSpecialBox');
        const specialText = document.getElementById('bModalSpecialText');
        if (ds.specialRequests && ds.specialRequests.trim().length > 0) {
            specialText.textContent = ds.specialRequests;
            specialBox.classList.remove('hidden');
        } else {
            specialBox.classList.add('hidden');
        }

        // Action Buttons inside Modal
        const actionBox = document.getElementById('bModalActionBtns');
        actionBox.innerHTML = '';
        if (ds.isPending === '1') {
            actionBox.innerHTML = `
                <button onclick="approveBrokerBooking('${ds.id}', '${ds.tenantName.replace(/'/g, "\\'")}', '#${ds.bookingId}')" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold transition shadow-xs cursor-pointer">
                    <i class="fas fa-check"></i> Accept Stay
                </button>
                <button onclick="rejectBrokerBooking('${ds.id}', '${ds.tenantName.replace(/'/g, "\\'")}', '#${ds.bookingId}')" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition shadow-xs cursor-pointer">
                    <i class="fas fa-times"></i> Decline
                </button>
            `;
        } else if (ds.isConfirmed === '1') {
            actionBox.innerHTML = `
                <button onclick="completeBrokerBooking('${ds.id}', '${ds.tenantName.replace(/'/g, "\\'")}', '#${ds.bookingId}')" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-bold transition shadow-xs cursor-pointer">
                    <i class="fas fa-check-double"></i> Mark Completed
                </button>
            `;
        }

        const modal = document.getElementById('brokerBookingModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeBrokerBookingModal() {
        const modal = document.getElementById('brokerBookingModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openBrokerInvoice(btn) {
        const ds = btn.dataset;
        document.getElementById('bInvNumber').textContent = `#${ds.bookingId}`;
        document.getElementById('bInvTenant').textContent = ds.tenantName;
        document.getElementById('bInvProp').textContent = ds.propName;
        document.getElementById('bInvCheckIn').textContent = ds.checkIn;
        document.getElementById('bInvPlan').textContent = `${ds.roomType} (${ds.duration})`;
        document.getElementById('bInvRent').textContent = `₹${ds.rent}/mo`;
        document.getElementById('bInvDeposit').textContent = `₹${ds.deposit}`;
        document.getElementById('bInvTotal').textContent = ds.total;

        const modal = document.getElementById('brokerInvoiceModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeBrokerInvoiceModal() {
        const modal = document.getElementById('brokerInvoiceModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBrokerBookingModal();
            closeBrokerInvoiceModal();
        }
    });
</script>
@endpush
