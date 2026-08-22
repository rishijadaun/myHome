@extends('broker.layouts.app')

@section('title', 'Tenant Bookings - StayNest Broker Portal')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tenant Bookings</h1>
        <p class="text-sm text-gray-500">{{ $totalCount }} total reservations • {{ $pendingCount }} pending owner confirmation</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('broker.pgs') }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2.5 rounded-xl text-sm font-semibold tap-effect flex items-center gap-2 transition shadow-xs">
            <i class="fas fa-plus text-xs"></i> Manage Properties
        </a>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('broker.bookings', ['status' => 'ALL']) }}" class="bg-white rounded-2xl p-5 border {{ ($activeTab ?? 'ALL') === 'ALL' ? 'border-brand ring-2 ring-brand/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-brand/40 transition">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">{{ $totalCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Bookings</div>
        </a>
        <a href="{{ route('broker.bookings', ['status' => 'CONFIRMED']) }}" class="bg-white rounded-2xl p-5 border {{ ($activeTab ?? '') === 'CONFIRMED' ? 'border-green-500 ring-2 ring-green-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-green-300 transition">
            <div class="text-2xl md:text-3xl font-bold text-green-600">{{ $confirmedCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Confirmed & Active</div>
        </a>
        <a href="{{ route('broker.bookings', ['status' => 'PENDING']) }}" class="bg-white rounded-2xl p-5 border {{ ($activeTab ?? '') === 'PENDING' ? 'border-yellow-500 ring-2 ring-yellow-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-yellow-300 transition">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600">{{ $pendingCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Requests</div>
        </a>
        <a href="{{ route('broker.bookings', ['status' => 'CANCELLED']) }}" class="bg-white rounded-2xl p-5 border {{ ($activeTab ?? '') === 'CANCELLED' ? 'border-red-500 ring-2 ring-red-500/20' : 'border-gray-100' }} shadow-sm cursor-pointer hover:border-red-300 transition">
            <div class="text-2xl md:text-3xl font-bold text-red-600">{{ $cancelledCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Cancelled / Rejected</div>
        </a>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Tabs & Search Header -->
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex border-b md:border-b-0 border-gray-100 space-x-2 overflow-x-auto no-scrollbar">
                <a href="{{ route('broker.bookings', ['status' => 'PENDING']) }}" class="px-5 py-2.5 text-sm font-semibold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? '') === 'PENDING' ? 'bg-brand text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                    Pending ({{ $pendingCount }})
                </a>
                <a href="{{ route('broker.bookings', ['status' => 'CONFIRMED']) }}" class="px-5 py-2.5 text-sm font-semibold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? '') === 'CONFIRMED' ? 'bg-brand text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                    Confirmed ({{ $confirmedCount }})
                </a>
                <a href="{{ route('broker.bookings', ['status' => 'CANCELLED']) }}" class="px-5 py-2.5 text-sm font-semibold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? '') === 'CANCELLED' ? 'bg-brand text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                    Cancelled ({{ $cancelledCount }})
                </a>
                <a href="{{ route('broker.bookings', ['status' => 'ALL']) }}" class="px-5 py-2.5 text-sm font-semibold rounded-xl transition whitespace-nowrap {{ ($activeTab ?? 'ALL') === 'ALL' ? 'bg-brand text-white shadow-xs' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                    All ({{ $totalCount }})
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('broker.bookings') }}" class="relative w-full md:w-80 flex items-center">
                <input type="hidden" name="status" value="{{ $activeTab ?? 'ALL' }}">
                <i class="fas fa-search absolute left-3.5 text-gray-400 text-xs"></i>
                <input name="search" value="{{ request('search') }}" type="text" placeholder="Search tenant, PG, or Booking ID..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-9 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-brand/50">
                @if(request('search'))
                    <a href="{{ route('broker.bookings', ['status' => $activeTab ?? 'ALL']) }}" class="absolute right-2.5 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

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
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            @if($tenantPhone)
                                                <a href="tel:{{ $tenantPhone }}" class="hover:text-brand transition">+91 {{ $tenantPhone }}</a>
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
                                <span class="text-gray-500">{{ $bk->duration_months ?: 11 }} Months</span>
                            </td>

                            <!-- Check-in Date -->
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                {{ $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'Immediate' }}
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 text-sm font-black text-gray-900">
                                ₹{{ number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit)) }}
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
                                        <button onclick="approveBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect shadow-xs cursor-pointer" title="Accept & Confirm Booking">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                        <button onclick="rejectBrokerBooking('{{ $bk->id }}', '{{ addslashes($tenantName) }}', '#{{ $bk->booking_id }}')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect shadow-xs cursor-pointer" title="Decline Request">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    @endif

                                    @if($tenantPhone)
                                        <a href="https://wa.me/91{{ $tenantPhone }}?text={{ urlencode('Hi ' . $tenantName . ', regarding your booking #' . $bk->booking_id . ' for ' . $propName . ' on StayNest.') }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center tap-effect" title="WhatsApp Tenant">
                                            <i class="fab fa-whatsapp text-sm"></i>
                                        </a>
                                    @endif

                                    <button onclick="viewBrokerBookingModal({{ json_encode($bk) }}, '{{ addslashes($tenantName) }}', '{{ $tenantPhone }}', '{{ $tenantEmail }}', '{{ addslashes($propName) }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect cursor-pointer" title="View Full Details">
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
                    $tenantName = $bk->effective_tenant_name;
                    $tenantPhone = preg_replace('/[^0-9]/', '', $bk->effective_tenant_phone);
                    if (strlen($tenantPhone) > 10) $tenantPhone = substr($tenantPhone, -10);
                    $tenantEmail = $bk->tenant_email ?: ($bk->user?->email ?? 'N/A');
                    $statusMeta = $bk->display_status;
                    $isPending = ($bk->broker_approval === 'pending' && $bk->booking_status === 'pending');
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
                            <span class="font-bold text-gray-800">{{ $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'Immediate' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] uppercase font-bold block">Total Amount</span>
                            <span class="font-black text-brand">₹{{ number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit)) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 gap-2">
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
                            @endif
                            <button onclick="viewBrokerBookingModal({{ json_encode($bk) }}, '{{ addslashes($tenantName) }}', '{{ $tenantPhone }}', '{{ $tenantEmail }}', '{{ addslashes($propName) }}')" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg cursor-pointer">
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 text-xs">
                    No booking requests found.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ================= BROKER VIEW BOOKING MODAL ================= -->
<div id="brokerBookingModal" class="fixed inset-0 z-[3000] hidden items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div onclick="closeBrokerBookingModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 z-10 border border-gray-100 animate-in fade-in zoom-in-95 duration-200 my-8">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-black text-gray-900" id="bModalId">Booking Information</h3>
                <p class="text-xs text-gray-500">Tenant request and reservation details</p>
            </div>
            <button onclick="closeBrokerBookingModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition cursor-pointer">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <div class="space-y-4 text-xs">
            <!-- Tenant Card -->
            <div class="p-3.5 bg-brand-light/30 rounded-2xl border border-brand/20 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-brand uppercase font-bold block">Tenant</span>
                    <h4 class="text-sm font-black text-gray-900" id="bModalTenantName"></h4>
                    <p class="text-gray-600" id="bModalTenantPhone"></p>
                    <p class="text-gray-500" id="bModalTenantEmail"></p>
                </div>
                <a id="bModalWhatsappBtn" href="#" target="_blank" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition flex items-center gap-1.5 shadow-xs">
                    <i class="fab fa-whatsapp"></i> Chat Tenant
                </a>
            </div>

            <!-- Booking Specs -->
            <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
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

            <!-- Financials -->
            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 space-y-1.5">
                <div class="flex justify-between text-gray-600">
                    <span>Monthly Rent:</span>
                    <span class="font-bold text-gray-900" id="bModalRent"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Security Deposit:</span>
                    <span class="font-bold text-gray-900" id="bModalDeposit"></span>
                </div>
                <div class="border-t border-gray-200 pt-1.5 flex justify-between font-black text-sm text-gray-900">
                    <span>Total Amount:</span>
                    <span class="text-brand font-black" id="bModalTotal"></span>
                </div>
            </div>

            <!-- Special Requests -->
            <div id="bModalSpecialBox" class="p-3 bg-amber-50 rounded-2xl border border-amber-100 hidden">
                <span class="text-[10px] text-amber-800 font-bold uppercase block mb-0.5">Tenant Special Requests / Notes</span>
                <p class="text-gray-700" id="bModalSpecialText"></p>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
            <button onclick="closeBrokerBookingModal()" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-xl transition cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function approveBrokerBooking(bookingId, tenantName, bookingCode) {
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
            try {
                const response = await fetch(`/broker/bookings/${bookingId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        title: 'Booking Accepted! 🎉',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not approve booking.',
                        icon: 'error',
                        confirmButtonColor: '#4bb59d'
                    });
                }
            } catch (err) {
                Swal.fire({
                    title: 'Network Error',
                    text: 'An error occurred while updating booking status.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            }
        }
    }

    async function rejectBrokerBooking(bookingId, tenantName, bookingCode) {
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
            try {
                const response = await fetch(`/broker/bookings/${bookingId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ reason: reason || 'Declined due to unavailability.' })
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        title: 'Booking Declined',
                        text: data.message,
                        icon: 'info',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not decline booking.',
                        icon: 'error',
                        confirmButtonColor: '#4bb59d'
                    });
                }
            } catch (err) {
                Swal.fire({
                    title: 'Network Error',
                    text: 'An error occurred while communicating with the server.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            }
        }
    }

    function viewBrokerBookingModal(bk, tenantName, tenantPhone, tenantEmail, propName) {
        document.getElementById('bModalId').textContent = `Booking #${bk.booking_id}`;
        document.getElementById('bModalTenantName').textContent = tenantName;
        document.getElementById('bModalTenantPhone').textContent = tenantPhone ? `+91 ${tenantPhone}` : 'No phone provided';
        document.getElementById('bModalTenantEmail').textContent = tenantEmail;
        document.getElementById('bModalPropName').textContent = propName;
        document.getElementById('bModalRoomPlan').textContent = bk.room_type_name || 'Standard Stay';
        document.getElementById('bModalCheckIn').textContent = bk.check_in_date ? new Date(bk.check_in_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Immediate';
        document.getElementById('bModalDuration').textContent = (bk.duration_months || 11) + ' Months';
        document.getElementById('bModalRent').textContent = '₹' + Number(bk.base_rent).toLocaleString('en-IN') + '/mo';
        document.getElementById('bModalDeposit').textContent = '₹' + Number(bk.security_deposit || 0).toLocaleString('en-IN');
        document.getElementById('bModalTotal').textContent = '₹' + Number(bk.total_amount || (Number(bk.base_rent) + Number(bk.security_deposit || 0))).toLocaleString('en-IN');

        if (tenantPhone) {
            document.getElementById('bModalWhatsappBtn').href = `https://wa.me/91${tenantPhone}?text=${encodeURIComponent('Hi ' + tenantName + ', regarding booking #' + bk.booking_id + ' for ' + propName)}`;
            document.getElementById('bModalWhatsappBtn').classList.remove('hidden');
        } else {
            document.getElementById('bModalWhatsappBtn').classList.add('hidden');
        }

        const specialBox = document.getElementById('bModalSpecialBox');
        const specialText = document.getElementById('bModalSpecialText');
        if (bk.special_requests) {
            specialText.textContent = bk.special_requests;
            specialBox.classList.remove('hidden');
        } else {
            specialBox.classList.add('hidden');
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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBrokerBookingModal();
        }
    });
</script>
@endpush
