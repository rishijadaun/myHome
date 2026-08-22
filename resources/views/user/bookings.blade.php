@extends('user.layouts.app')

@section('title', 'My Bookings - India\'s Trusted Zero Brokerage PG Network | StayNest')
@section('meta_description', 'View and manage your PG and co-living stay reservations on StayNest with direct property owner confirmation and official receipts.')
@section('canonical', route('user.bookings'))

@push('styles')
<style>
    /* Android-like App Styling */
    .android-header {
        position: sticky;
        top: 0;
        z-index: 40;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }
    
    .android-chip {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .android-chip:active {
        transform: scale(0.95);
    }

    /* Android Bottom Sheet Styles */
    @media (max-width: 640px) {
        .android-bottom-sheet {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            margin: 0 !important;
            max-height: 90vh;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-top-left-radius: 1.75rem !important;
            border-top-right-radius: 1.75rem !important;
            transform: translateY(0);
            animation: slideUpBottom 0.25s cubic-bezier(0, 0, 0.2, 1);
        }
    }
    
    @keyframes slideUpBottom {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
</style>
@endpush

@section('content')

<!-- ================= MOBILE ANDROID TOP APP BAR ================= -->
<!-- <div class="md:hidden android-header px-4 py-3 shadow-xs">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('user.home') }}'" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-100 active:bg-gray-200 transition tap-effect cursor-pointer" title="Go Back">
                <i class="fas fa-arrow-left text-base"></i>
            </button>
            <div>
                <h1 class="text-lg font-black text-gray-900 leading-none">My Bookings</h1>
                <span class="text-[11px] font-semibold text-brand mt-0.5 inline-block">
                    {{ $upcomingCount }} active • {{ $totalBookings ?? ($upcomingCount + $completedCount + $cancelledCount) }} total
                </span>
            </div>
        </div>

        <div class="flex items-center gap-1.5">
            <a href="{{ route('user.search') }}" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition tap-effect" title="Search PGs">
                <i class="fas fa-search text-sm"></i>
            </a>
            <button type="button" onclick="window.location.reload()" class="w-9 h-9 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition tap-effect cursor-pointer" title="Refresh">
                <i class="fas fa-rotate-right text-sm"></i>
            </button>
        </div>
    </div>
</div> -->

<!-- ================= MOBILE ANDROID CHIP TABS (Sticky Below App Bar) ================= -->
<div class="md:hidden sticky top-[58px] z-30 bg-white/95 backdrop-blur-md px-4 py-2.5 border-b border-gray-100 shadow-2xs">
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar" id="mobileTabContainer">
        <button type="button" onclick="switchBookingTab('UPCOMING', this)" id="mob-tab-UPCOMING" class="android-chip mob-booking-tab flex-1 py-2 px-3 rounded-2xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center justify-center gap-1.5 bg-brand text-white shadow-xs">
            <i class="fas fa-clock text-[10px]"></i>
            <span>Upcoming</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-white/25 text-white font-extrabold">{{ $upcomingCount }}</span>
        </button>

        <button type="button" onclick="switchBookingTab('COMPLETED', this)" id="mob-tab-COMPLETED" class="android-chip mob-booking-tab flex-1 py-2 px-3 rounded-2xl text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition whitespace-nowrap cursor-pointer flex items-center justify-center gap-1.5">
            <i class="fas fa-check-circle text-[10px]"></i>
            <span>Completed</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-gray-200 text-gray-700 font-bold">{{ $completedCount }}</span>
        </button>

        <button type="button" onclick="switchBookingTab('CANCELLED', this)" id="mob-tab-CANCELLED" class="android-chip mob-booking-tab flex-1 py-2 px-3 rounded-2xl text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition whitespace-nowrap cursor-pointer flex items-center justify-center gap-1.5">
            <i class="fas fa-ban text-[10px]"></i>
            <span>Cancelled</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-gray-200 text-gray-700 font-bold">{{ $cancelledCount }}</span>
        </button>
    </div>
</div>

<!-- ================= MAIN CONTENT WRAPPER ================= -->
<div class="pt-20 md:pt-10 pb-20 max-w-7xl mx-auto px-4 md:px-6 space-y-16">

    <!-- 1. Desktop Hero Header (Matching About Us Layout Format) -->
    <div class="hidden md:block text-center max-w-3xl mx-auto space-y-2">
        <!-- <span class="bg-brand-light text-brand text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Stay Reservation Portal</span> -->
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">
            My Stay <br><span class="gradient-text">Bookings &amp; Reservations</span>
        </h1>
     
    </div>

    <!-- 2. Metrics (Matching About Us 4-Col Stat Cards Format) -->
    <!-- <div class="hidden sm:grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-3xl p-5 md:p-8 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="text-2xl md:text-4xl font-extrabold text-brand mb-1">{{ $upcomingCount }}</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Upcoming Bookings</div>
        </div>
        <div class="bg-white rounded-3xl p-5 md:p-8 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1">{{ $completedCount }}</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Completed Stays</div>
        </div>
        <div class="bg-white rounded-3xl p-5 md:p-8 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="text-2xl md:text-4xl font-extrabold text-rose-500 mb-1">{{ $cancelledCount }}</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Cancelled Requests</div>
        </div>
        <div class="bg-white rounded-3xl p-5 md:p-8 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="text-2xl md:text-4xl font-extrabold text-emerald-600 mb-1">₹0</div>
            <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Zero Booking Fee</div>
        </div>
    </div> -->

    <!-- 3. Core Bookings Section Container -->
    <div class="bg-transparent md:bg-white rounded-none md:rounded-3xl p-0 md:p-8 lg:p-10 border-0 md:border md:border-gray-100 shadow-none md:shadow-sm space-y-5 sm:space-y-8">
        
        <!-- Desktop Segmented Filter Tabs -->
        <div class="hidden md:flex items-center gap-2 sm:gap-6 border-b border-gray-200 pb-1 overflow-x-auto no-scrollbar" id="desktopBookingTabNav">
            <button type="button" onclick="switchBookingTab('UPCOMING', this)" id="tab-btn-UPCOMING" class="desk-booking-tab px-6 py-3 text-sm font-bold text-brand border-b-2 border-brand transition whitespace-nowrap cursor-pointer flex items-center gap-2">
                <span>Upcoming Reservations</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-brand/10 text-brand font-extrabold">{{ $upcomingCount }}</span>
            </button>

            <button type="button" onclick="switchBookingTab('COMPLETED', this)" id="tab-btn-COMPLETED" class="desk-booking-tab px-6 py-3 text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap cursor-pointer flex items-center gap-2">
                <span>Completed Stays</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600 font-bold">{{ $completedCount }}</span>
            </button>

            <button type="button" onclick="switchBookingTab('CANCELLED', this)" id="tab-btn-CANCELLED" class="desk-booking-tab px-6 py-3 text-sm font-semibold text-gray-500 hover:text-gray-900 border-b-2 border-transparent transition whitespace-nowrap cursor-pointer flex items-center gap-2">
                <span>Cancelled / Declined</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600 font-bold">{{ $cancelledCount }}</span>
            </button>
        </div>

        <!-- Bookings Cards Container -->
        <div id="bookingsContainer" class="space-y-4 sm:space-y-6">
            
            <!-- ================= UPCOMING LIST ================= -->
            <div id="section-UPCOMING" class="booking-section space-y-4 sm:space-y-5">
                @forelse($upcoming as $bk)
                    @php
                        $prop = $bk->property;
                        $propImg = $prop?->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=300&q=80';
                        $propName = $prop?->name ?? 'Verified Stay';
                        $propLocation = ($prop?->address ? $prop->address . ', ' : '') . ($prop?->area?->name ? $prop->area->name . ', ' : '') . ($prop?->city?->name ?? 'India');
                        $statusMeta = $bk->display_status;
                        $ownerPhone = preg_replace('/[^0-9]/', '', $bk->broker?->phone ?? '9876543210');
                        if (strlen($ownerPhone) > 10) $ownerPhone = substr($ownerPhone, -10);
                        $ownerName = $bk->broker?->name ?? 'Property Owner';
                        $checkInFormatted = $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'Immediate';
                        $durationText = ($bk->duration_months ?: 11) . ' ' . Str::plural('Month', (int)$bk->duration_months);
                        $amountText = '₹' . number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit));
                        $detailUrl = $prop ? route('user.detail', ['slug' => $prop->slug ?: \Illuminate\Support\Str::slug($prop->name)]) : '#';
                        $isConfirmed = ($bk->broker_approval === 'approved' || $bk->booking_status === 'confirmed');
                    @endphp

                    <!-- Responsive Booking Card (Android-Optimized Mobile + Desktop Grid) -->
                    <div class="bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 sm:border-gray-200/80 shadow-xs sm:shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row items-stretch md:items-center gap-3.5 sm:gap-6 group">
                        
                        <!-- Top Row on Mobile / Left Image on Desktop -->
                        <div class="flex items-center md:items-start gap-3.5 sm:gap-4 md:w-44 flex-shrink-0">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 md:w-44 md:h-36 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-900 relative shadow-2xs">
                                <img src="{{ $propImg }}" alt="{{ $propName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute bottom-1.5 left-1.5 bg-black/75 backdrop-blur text-white text-[8px] sm:text-[9px] font-mono px-1.5 py-0.5 rounded-md">
                                    #{{ $bk->booking_id }}
                                </span>
                            </div>

                            <!-- Mobile-Only Info Stack Next to Photo -->
                            <div class="flex-1 min-w-0 md:hidden space-y-1">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="inline-block {{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                                        {{ $statusMeta['label'] }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-black text-gray-900 truncate leading-snug">
                                    <a href="{{ $detailUrl }}">{{ $propName }}</a>
                                </h3>
                                <p class="text-[11px] text-gray-500 truncate flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-brand text-[10px]"></i>
                                    <span class="truncate">{{ $propLocation }}</span>
                                </p>
                                <div class="pt-0.5">
                                    <span class="text-brand font-black text-sm">{{ $amountText }}</span>
                                    <span class="text-[10px] text-gray-400 font-normal">/ {{ $durationText }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body (Desktop layout + Mobile metrics & Actions) -->
                        <div class="flex-1 min-w-0 w-full space-y-3">
                            <!-- Desktop Header: Title & Status Badge -->
                            <div class="hidden md:flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 truncate">
                                        <a href="{{ $detailUrl }}" class="hover:text-brand transition">{{ $propName }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                        <i class="fas fa-map-marker-alt text-brand text-xs flex-shrink-0"></i>
                                        <span class="truncate">{{ $propLocation }}</span>
                                    </p>
                                </div>
                                <span class="{{ $statusMeta['bg'] }} {{ $statusMeta['text'] }} text-xs font-black px-3.5 py-1.5 rounded-xl uppercase tracking-wider shadow-2xs">
                                    {{ $statusMeta['label'] }}
                                </span>
                            </div>

                            <!-- 4-Column Metric Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 p-2.5 sm:p-3 bg-gray-50/80 rounded-2xl text-xs border border-gray-100">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">CHECK-IN</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-1">
                                        <i class="fas fa-calendar-day text-brand text-[11px] hidden sm:inline"></i>
                                        {{ $checkInFormatted }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">ROOM TYPE</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm truncate block">{{ $bk->room_type_name ?: 'Standard Stay' }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">DURATION</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $durationText }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">
                                        {{ $isConfirmed ? 'TOTAL ESTIMATE' : 'AMOUNT DUE' }}
                                    </span>
                                    <span class="font-black text-xs sm:text-sm text-brand">{{ $amountText }}</span>
                                </div>
                            </div>

                            <!-- Android Action Button Row -->
                            <div class="flex items-center gap-2 pt-1 flex-wrap">
                                <!-- View Details -->
                                <button type="button" onclick="showBookingDetailsModal({{ json_encode($bk) }}, '{{ addslashes($propName) }}', '{{ addslashes($propLocation) }}', '{{ $propImg }}', '{{ addslashes($ownerName) }}', '{{ $ownerPhone }}')" class="flex-1 sm:flex-initial px-4 py-2.5 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-800 text-xs font-bold rounded-2xl transition tap-effect flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs">
                                    <i class="fas fa-eye text-xs text-gray-600"></i>
                                    <span>Details</span>
                                </button>

                                <!-- WhatsApp Chat with Host -->
                                <a href="https://wa.me/91{{ $ownerPhone }}?text={{ urlencode('Hi ' . $ownerName . ', regarding my booking #' . $bk->booking_id . ' for ' . $propName . ' on StayNest. Please confirm check-in details.') }}" target="_blank" class="flex-1 sm:flex-initial px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold rounded-2xl transition tap-effect shadow-xs flex items-center justify-center gap-1.5">
                                    <i class="fab fa-whatsapp text-sm"></i>
                                    <span>Chat Host</span>
                                </a>

                                <!-- Invoice / Receipt -->
                                <button type="button" onclick="showInvoiceModal('{{ $bk->booking_id }}', '{{ addslashes($propName) }}', '{{ addslashes($bk->effective_tenant_name) }}', '{{ $checkInFormatted }}', '{{ $bk->room_type_name ?: 'Standard Room' }}', '{{ $durationText }}', '{{ number_format($bk->base_rent) }}', '{{ number_format($bk->security_deposit) }}', '{{ $amountText }}', '{{ $statusMeta['label'] }}')" class="p-2.5 sm:px-4 sm:py-2.5 bg-white hover:bg-gray-50 active:bg-gray-100 border border-gray-200 text-gray-700 text-xs font-bold rounded-2xl transition tap-effect shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer" title="Download Invoice">
                                    <i class="fas fa-file-invoice text-xs text-gray-500"></i>
                                    <span class="hidden sm:inline">Receipt</span>
                                </button>

                                <!-- Cancel Booking (if pending) -->
                                @if($bk->broker_approval === 'pending' && $bk->booking_status === 'pending')
                                    <button type="button" onclick="cancelUserBooking('{{ $bk->id }}', '{{ $bk->booking_id }}')" class="p-2.5 sm:px-4 sm:py-2.5 bg-white hover:bg-rose-50 active:bg-rose-100 border border-rose-200 text-rose-600 text-xs font-bold rounded-2xl transition tap-effect cursor-pointer ml-auto" title="Cancel Booking Request">
                                        <i class="fas fa-times sm:mr-1"></i>
                                        <span class="hidden sm:inline">Cancel</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl p-8 sm:p-12 text-center border border-dashed border-gray-200 bg-white space-y-4 shadow-xs">
                        <div class="w-16 h-16 bg-brand/10 text-brand rounded-3xl flex items-center justify-center mx-auto text-2xl font-bold shadow-xs">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base sm:text-lg font-black text-gray-900">No Upcoming Bookings Found</h3>
                            <p class="text-xs text-gray-500 max-w-md mx-auto">You do not have any pending or active reservations right now. Discover 100% verified student and professional PGs with zero brokerage.</p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('user.search') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-dark active:scale-95 text-white text-xs font-bold rounded-2xl transition tap-effect shadow-md shadow-brand/20">
                                <i class="fas fa-search"></i> Find a Stay Now
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- ================= COMPLETED LIST ================= -->
            <div id="section-COMPLETED" class="booking-section space-y-4 sm:space-y-5 hidden">
                @forelse($completed as $bk)
                    @php
                        $prop = $bk->property;
                        $propImg = $prop?->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=300&q=80';
                        $propName = $prop?->name ?? 'Verified Stay';
                        $propLocation = ($prop?->address ? $prop->address . ', ' : '') . ($prop?->area?->name ? $prop->area->name . ', ' : '') . ($prop?->city?->name ?? 'India');
                        $statusMeta = $bk->display_status;
                        $ownerPhone = preg_replace('/[^0-9]/', '', $bk->broker?->phone ?? '9876543210');
                        if (strlen($ownerPhone) > 10) $ownerPhone = substr($ownerPhone, -10);
                        $ownerName = $bk->broker?->name ?? 'Property Owner';
                        $checkInFormatted = $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'N/A';
                        $durationText = ($bk->duration_months ?: 11) . ' ' . Str::plural('Month', (int)$bk->duration_months);
                        $amountText = '₹' . number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit));
                        $detailUrl = $prop ? route('user.detail', ['slug' => $prop->slug ?: \Illuminate\Support\Str::slug($prop->name)]) : '#';
                    @endphp
                    <div class="bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 sm:border-gray-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center gap-3.5 sm:gap-6 group">
                        <div class="flex items-center md:items-start gap-3.5 sm:gap-4 md:w-44 flex-shrink-0">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 md:w-44 md:h-36 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-900 relative">
                                <img src="{{ $propImg }}" alt="{{ $propName }}" class="w-full h-full object-cover">
                                <span class="absolute bottom-1.5 left-1.5 bg-black/75 backdrop-blur text-white text-[8px] sm:text-[9px] font-mono px-1.5 py-0.5 rounded-md">
                                    #{{ $bk->booking_id }}
                                </span>
                            </div>

                            <div class="flex-1 min-w-0 md:hidden space-y-1">
                                <span class="inline-block bg-blue-100 text-blue-700 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    COMPLETED
                                </span>
                                <h3 class="text-sm font-black text-gray-900 truncate leading-snug">
                                    <a href="{{ $detailUrl }}">{{ $propName }}</a>
                                </h3>
                                <p class="text-[11px] text-gray-500 truncate flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-brand text-[10px]"></i>
                                    <span class="truncate">{{ $propLocation }}</span>
                                </p>
                                <div class="pt-0.5">
                                    <span class="text-brand font-black text-sm">{{ $amountText }}</span>
                                    <span class="text-[10px] text-gray-400 font-normal">/ {{ $durationText }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0 w-full space-y-3">
                            <div class="hidden md:flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 truncate">
                                        <a href="{{ $detailUrl }}" class="hover:text-brand transition">{{ $propName }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                        <i class="fas fa-map-marker-alt text-brand text-xs flex-shrink-0"></i>
                                        <span class="truncate">{{ $propLocation }}</span>
                                    </p>
                                </div>
                                <span class="bg-blue-100 text-blue-700 text-xs font-black px-3.5 py-1.5 rounded-xl uppercase tracking-wider">
                                    COMPLETED
                                </span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 p-2.5 sm:p-3 bg-gray-50/80 rounded-2xl text-xs border border-gray-100">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">CHECK-IN</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $checkInFormatted }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">ROOM TYPE</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm truncate block">{{ $bk->room_type_name ?: 'Standard Stay' }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">DURATION</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $durationText }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">TOTAL PAID</span>
                                    <span class="font-black text-xs sm:text-sm text-brand">{{ $amountText }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-1 flex-wrap">
                                <button type="button" onclick="showBookingDetailsModal({{ json_encode($bk) }}, '{{ addslashes($propName) }}', '{{ addslashes($propLocation) }}', '{{ $propImg }}', '{{ addslashes($ownerName) }}', '{{ $ownerPhone }}')" class="flex-1 sm:flex-initial px-4 py-2.5 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-800 text-xs font-bold rounded-2xl transition tap-effect flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span>Details</span>
                                </button>
                                <button type="button" onclick="showInvoiceModal('{{ $bk->booking_id }}', '{{ addslashes($propName) }}', '{{ addslashes($bk->effective_tenant_name) }}', '{{ $checkInFormatted }}', '{{ $bk->room_type_name ?: 'Standard Room' }}', '{{ $durationText }}', '{{ number_format($bk->base_rent) }}', '{{ number_format($bk->security_deposit) }}', '{{ $amountText }}', 'COMPLETED')" class="flex-1 sm:flex-initial px-4 py-2.5 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-bold rounded-2xl transition tap-effect shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="fas fa-file-invoice text-xs"></i>
                                    <span>Receipt</span>
                                </button>
                                <a href="{{ $detailUrl }}#sec-reviews" class="flex-1 sm:flex-initial px-4 py-2.5 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 text-amber-800 text-xs font-bold rounded-2xl transition tap-effect flex items-center justify-center gap-1.5">
                                    <i class="fas fa-star text-amber-500"></i>
                                    <span>Review</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl p-8 sm:p-12 text-center border border-dashed border-gray-200 bg-white space-y-4 shadow-xs">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto text-2xl font-bold shadow-xs">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-gray-900">No Past Completed Stays</h3>
                        <p class="text-xs text-gray-500 max-w-md mx-auto">When your stay duration completes or you check out, your records, invoices, and history will be saved here.</p>
                    </div>
                @endforelse
            </div>

            <!-- ================= CANCELLED LIST ================= -->
            <div id="section-CANCELLED" class="booking-section space-y-4 sm:space-y-5 hidden">
                @forelse($cancelled as $bk)
                    @php
                        $prop = $bk->property;
                        $propImg = $prop?->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=300&q=80';
                        $propName = $prop?->name ?? 'Verified Stay';
                        $propLocation = ($prop?->address ? $prop->address . ', ' : '') . ($prop?->area?->name ? $prop->area->name . ', ' : '') . ($prop?->city?->name ?? 'India');
                        $statusMeta = $bk->display_status;
                        $ownerPhone = preg_replace('/[^0-9]/', '', $bk->broker?->phone ?? '9876543210');
                        if (strlen($ownerPhone) > 10) $ownerPhone = substr($ownerPhone, -10);
                        $ownerName = $bk->broker?->name ?? 'Property Owner';
                        $checkInFormatted = $bk->check_in_date ? $bk->check_in_date->format('M d, Y') : 'N/A';
                        $durationText = ($bk->duration_months ?: 11) . ' ' . Str::plural('Month', (int)$bk->duration_months);
                        $amountText = '₹' . number_format($bk->total_amount ?: ($bk->base_rent + $bk->security_deposit));
                        $detailUrl = $prop ? route('user.detail', ['slug' => $prop->slug ?: \Illuminate\Support\Str::slug($prop->name)]) : '#';
                    @endphp
                    <div class="bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 sm:border-gray-200/80 shadow-xs opacity-90 hover:opacity-100 transition-all flex flex-col md:flex-row items-stretch md:items-center gap-3.5 sm:gap-6 group">
                        <div class="flex items-center md:items-start gap-3.5 sm:gap-4 md:w-44 flex-shrink-0">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 md:w-44 md:h-36 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-900 relative grayscale group-hover:grayscale-0 transition-all">
                                <img src="{{ $propImg }}" alt="{{ $propName }}" class="w-full h-full object-cover">
                                <span class="absolute bottom-1.5 left-1.5 bg-black/75 backdrop-blur text-white text-[8px] sm:text-[9px] font-mono px-1.5 py-0.5 rounded-md">
                                    #{{ $bk->booking_id }}
                                </span>
                            </div>

                            <div class="flex-1 min-w-0 md:hidden space-y-1">
                                <span class="inline-block bg-rose-100 text-rose-700 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    {{ $bk->broker_approval === 'rejected' ? 'REJECTED' : 'CANCELLED' }}
                                </span>
                                <h3 class="text-sm font-black text-gray-900 truncate leading-snug">
                                    <a href="{{ $detailUrl }}">{{ $propName }}</a>
                                </h3>
                                <p class="text-[11px] text-gray-500 truncate flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-brand text-[10px]"></i>
                                    <span class="truncate">{{ $propLocation }}</span>
                                </p>
                                <div class="pt-0.5">
                                    <span class="text-gray-700 font-bold text-sm">{{ $amountText }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0 w-full space-y-3">
                            <div class="hidden md:flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 truncate">
                                        <a href="{{ $detailUrl }}" class="hover:text-brand transition">{{ $propName }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                        <i class="fas fa-map-marker-alt text-brand text-xs flex-shrink-0"></i>
                                        <span class="truncate">{{ $propLocation }}</span>
                                    </p>
                                </div>
                                <span class="bg-rose-100 text-rose-700 text-xs font-black px-3.5 py-1.5 rounded-xl uppercase tracking-wider">
                                    {{ $bk->broker_approval === 'rejected' ? 'REJECTED' : 'CANCELLED' }}
                                </span>
                            </div>

                            @if($bk->cancellation_reason)
                                <div class="p-2.5 bg-rose-50 rounded-2xl border border-rose-100 text-xs text-rose-800 flex items-start gap-2">
                                    <i class="fas fa-info-circle text-rose-500 mt-0.5 flex-shrink-0"></i>
                                    <span><strong>Reason:</strong> {{ $bk->cancellation_reason }}</span>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 p-2.5 sm:p-3 bg-gray-50/80 rounded-2xl text-xs border border-gray-100">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">REQUESTED DATE</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $checkInFormatted }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">ROOM TYPE</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm truncate block">{{ $bk->room_type_name ?: 'Standard Stay' }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">DURATION</span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $durationText }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">AMOUNT</span>
                                    <span class="font-black text-xs sm:text-sm text-gray-700">{{ $amountText }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-1 flex-wrap">
                                <button type="button" onclick="showBookingDetailsModal({{ json_encode($bk) }}, '{{ addslashes($propName) }}', '{{ addslashes($propLocation) }}', '{{ $propImg }}', '{{ addslashes($ownerName) }}', '{{ $ownerPhone }}')" class="flex-1 sm:flex-initial px-4 py-2.5 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-800 text-xs font-bold rounded-2xl transition tap-effect flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span>Details</span>
                                </button>
                                <a href="{{ route('user.search') }}" class="flex-1 sm:flex-initial px-4 py-2.5 bg-brand text-white text-xs font-bold rounded-2xl transition tap-effect shadow-xs flex items-center justify-center gap-1.5">
                                    <i class="fas fa-redo text-xs"></i>
                                    <span>Find Alternate PG</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl p-8 sm:p-12 text-center border border-dashed border-gray-200 bg-white space-y-4 shadow-xs">
                        <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-3xl flex items-center justify-center mx-auto text-2xl font-bold shadow-xs">
                            <i class="fas fa-ban"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-gray-900">No Cancelled Requests</h3>
                        <p class="text-xs text-gray-500 max-w-md mx-auto">You have no cancelled or declined booking requests on StayNest.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 4. Call to Action Banner (Matching About Us CTA Layout) -->
    <div class="bg-gradient-to-r from-brand to-brand-dark rounded-3xl p-6 sm:p-10 md:p-12 text-white text-center space-y-5 sm:space-y-6 shadow-xl shadow-brand/20">
        <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold">Looking for another verified stay?</h2>
        <p class="text-white/80 max-w-xl mx-auto text-xs sm:text-sm leading-relaxed">
            Discover 1,200+ top-rated student &amp; professional PGs, hostels, and premium co-living spaces with zero brokerage and instant owner confirmation.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 pt-1">
            <a href="{{ route('user.search') }}" class="bg-white text-brand font-bold px-7 py-3 rounded-2xl tap-effect shadow-md hover:bg-gray-50 transition text-xs sm:text-sm flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> Explore All PGs
            </a>
            <a href="{{ route('user.contact') }}" class="bg-white/20 hover:bg-white/30 active:bg-white/40 backdrop-blur-sm text-white font-semibold px-7 py-3 rounded-2xl tap-effect transition text-xs sm:text-sm border border-white/30 flex items-center justify-center gap-2">
                <i class="fas fa-headset"></i> StayNest Support
            </a>
        </div>
    </div>
</div>

<!-- ================= ANDROID BOTTOM SHEET / BOOKING DETAILS MODAL ================= -->
<div id="bookingDetailsModal" class="fixed inset-0 z-[3000] hidden items-end sm:items-center justify-center p-0 sm:p-4 overflow-y-auto">
    <!-- Backdrop -->
    <div onclick="closeBookingDetailsModal()" class="fixed inset-0 bg-slate-900/65 backdrop-blur-xs transition-opacity"></div>
    
    <!-- Dialog / Bottom Sheet Container -->
    <div class="android-bottom-sheet relative bg-white w-full sm:max-w-lg p-5 sm:p-7 z-10 border-t sm:border border-gray-100 shadow-2xl overflow-y-auto max-h-[90vh] sm:max-h-[85vh] my-0 sm:my-auto">
        <!-- Android Handle Bar (Mobile Only) -->
        <div class="sm:hidden w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-4"></div>

        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-base sm:text-lg font-black text-gray-900" id="modalBookingIdText">Booking Details</h3>
                <p class="text-[11px] text-gray-500">Official StayNest Booking Confirmation Record</p>
            </div>
            <button onclick="closeBookingDetailsModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition cursor-pointer" title="Close">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <div class="space-y-3.5 text-xs">
            <!-- Property Header Mini Card -->
            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-3">
                <img id="modalPropImg" src="" class="w-14 h-14 rounded-2xl object-cover shadow-xs flex-shrink-0">
                <div class="min-w-0 flex-1">
                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 truncate" id="modalPropName"></h4>
                    <p class="text-gray-500 text-[11px] truncate flex items-center gap-1 mt-0.5">
                        <i class="fas fa-map-marker-alt text-brand text-[10px]"></i>
                        <span id="modalPropLocation" class="truncate"></span>
                    </p>
                </div>
            </div>

            <!-- Booking Info 2x2 Grid -->
            <div class="grid grid-cols-2 gap-2.5 p-3 bg-gray-50/70 rounded-2xl border border-gray-100">
                <div>
                    <span class="text-[9px] text-gray-400 font-bold uppercase block mb-0.5">Check-in Date</span>
                    <span class="font-bold text-gray-900 text-xs" id="modalCheckIn"></span>
                </div>
                <div>
                    <span class="text-[9px] text-gray-400 font-bold uppercase block mb-0.5">Duration</span>
                    <span class="font-bold text-gray-900 text-xs" id="modalDuration"></span>
                </div>
                <div>
                    <span class="text-[9px] text-gray-400 font-bold uppercase block mb-0.5">Room Type</span>
                    <span class="font-bold text-gray-900 text-xs truncate block" id="modalRoomType"></span>
                </div>
                <div>
                    <span class="text-[9px] text-gray-400 font-bold uppercase block mb-0.5">Approval Status</span>
                    <span class="font-bold text-emerald-600 text-xs" id="modalStatus"></span>
                </div>
            </div>

            <!-- Host Contact Box (with 1-Tap WhatsApp & Call) -->
            <div class="p-3.5 bg-emerald-50/80 rounded-2xl border border-emerald-100 flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <span class="text-[9px] text-emerald-800 font-bold uppercase block">Property Host</span>
                    <span class="font-extrabold text-gray-900 text-xs truncate block" id="modalHostName"></span>
                    <p class="text-[11px] text-gray-600 truncate" id="modalHostPhone"></p>
                </div>
                <div class="flex items-center gap-1.5">
                    <a id="modalCallLink" href="#" class="w-9 h-9 rounded-xl bg-white text-emerald-700 border border-emerald-200 flex items-center justify-center font-bold tap-effect" title="Call Host">
                        <i class="fas fa-phone-alt text-xs"></i>
                    </a>
                    <a id="modalWhatsappLink" href="#" target="_blank" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-bold transition flex items-center gap-1.5 shadow-xs">
                        <i class="fab fa-whatsapp text-sm"></i>
                        <span>Chat</span>
                    </a>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 space-y-1.5">
                <div class="flex justify-between text-gray-600">
                    <span>Monthly Rent:</span>
                    <span class="font-bold text-gray-900" id="modalRent"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Security Deposit (Refundable):</span>
                    <span class="font-bold text-gray-900" id="modalDeposit"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>StayNest Booking Fee:</span>
                    <span class="font-bold text-emerald-600">FREE (₹0)</span>
                </div>
                <div class="border-t border-gray-200 pt-1.5 flex justify-between font-black text-xs sm:text-sm text-brand-dark">
                    <span>Total Move-in Payable:</span>
                    <span id="modalTotal"></span>
                </div>
            </div>

            <!-- Special Requests if any -->
            <div id="modalSpecialReqBox" class="p-3 bg-amber-50/80 rounded-2xl border border-amber-100 hidden">
                <span class="text-[9px] text-amber-800 font-bold uppercase block mb-0.5">Special Requests / Notes</span>
                <p class="text-gray-700" id="modalSpecialReqText"></p>
            </div>
        </div>

        <div class="mt-5 pt-3 border-t border-gray-100 flex items-center justify-end gap-2 pb-safe">
            <button onclick="closeBookingDetailsModal()" class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-800 text-xs font-bold rounded-2xl transition cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<!-- ================= INVOICE / RECEIPT MODAL ================= -->
<div id="invoiceModal" class="fixed inset-0 z-[3000] hidden items-end sm:items-center justify-center p-0 sm:p-4 overflow-y-auto">
    <div onclick="closeInvoiceModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="android-bottom-sheet relative bg-white w-full sm:max-w-md p-6 sm:p-8 z-10 border-t sm:border border-gray-100 shadow-2xl overflow-y-auto max-h-[90vh] sm:max-h-[85vh] my-0 sm:my-auto printable-receipt">
        <!-- Android Handle Bar (Mobile Only) -->
        <div class="sm:hidden w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-4"></div>

        <!-- Printable Invoice Header -->
        <div class="flex items-start justify-between pb-4 border-b border-gray-200 mb-4">
            <div>
                <div class="flex items-center gap-1.5 text-brand font-black text-xl mb-1">
                    <i class="fas fa-house-chimney"></i> StayNest
                </div>
                <p class="text-[10px] text-gray-500">Booking Confirmation &amp; Invoice</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-mono text-gray-400 block leading-none">INVOICE NO.</span>
                <span class="text-xs font-mono font-bold text-gray-900" id="invNumber"></span>
            </div>
        </div>

        <!-- Receipt Body -->
        <div class="space-y-3.5 text-xs">
            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-gray-500 text-[11px]">Guest Name:</span>
                    <span class="font-bold text-gray-900" id="invTenantName"></span>
                </div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-gray-500 text-[11px]">Stay / Property:</span>
                    <span class="font-bold text-gray-900" id="invPropName"></span>
                </div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-gray-500 text-[11px]">Check-in Date:</span>
                    <span class="font-bold text-gray-900" id="invCheckIn"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-[11px]">Stay Plan:</span>
                    <span class="font-bold text-gray-900" id="invPlan"></span>
                </div>
            </div>

            <div class="space-y-2 py-2">
                <div class="flex justify-between text-gray-600">
                    <span>Monthly Accommodation Rent:</span>
                    <span class="font-bold text-gray-900" id="invRent"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Refundable Security Deposit:</span>
                    <span class="font-bold text-gray-900" id="invDeposit"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>StayNest Service Charges:</span>
                    <span class="font-bold text-emerald-600">₹0 (Free)</span>
                </div>
                <div class="border-t border-gray-200 pt-2 flex justify-between font-black text-sm text-gray-900">
                    <span>Total Amount:</span>
                    <span class="text-brand font-black" id="invTotal"></span>
                </div>
            </div>

            <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-800 text-[10px] font-semibold flex items-center gap-1.5">
                <i class="fas fa-check-circle text-emerald-600"></i>
                <span>Direct Verification • Zero Online Advance Payment Required</span>
            </div>
        </div>

        <!-- Invoice Action Buttons -->
        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5 no-print pb-safe">
            <button onclick="closeInvoiceModal()" class="px-4 py-2.5 rounded-2xl border border-gray-200 text-gray-600 text-xs font-bold hover:bg-gray-50 transition cursor-pointer">
                Close
            </button>
            <button onclick="window.print()" class="px-5 py-2.5 rounded-2xl bg-brand hover:bg-brand-dark active:scale-95 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                <i class="fas fa-print"></i> Print Invoice
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab Switcher for both Desktop & Android Mobile Pill Chips
    function switchBookingTab(status, btn) {
        // Desktop Tabs
        document.querySelectorAll('.desk-booking-tab').forEach(b => {
            b.classList.remove('text-brand', 'border-brand', 'font-bold');
            b.classList.add('text-gray-500', 'border-transparent', 'font-semibold');
        });
        const activeDeskBtn = document.getElementById(`tab-btn-${status}`);
        if (activeDeskBtn) {
            activeDeskBtn.classList.remove('text-gray-500', 'border-transparent', 'font-semibold');
            activeDeskBtn.classList.add('text-brand', 'border-brand', 'font-bold');
        }

        // Mobile Chips
        document.querySelectorAll('.mob-booking-tab').forEach(b => {
            b.classList.remove('bg-brand', 'text-white', 'shadow-xs', 'font-bold');
            b.classList.add('bg-gray-100', 'text-gray-600', 'font-semibold');
        });
        const activeMobBtn = document.getElementById(`mob-tab-${status}`);
        if (activeMobBtn) {
            activeMobBtn.classList.remove('bg-gray-100', 'text-gray-600', 'font-semibold');
            activeMobBtn.classList.add('bg-brand', 'text-white', 'shadow-xs', 'font-bold');
        }

        // Switch List Sections
        document.querySelectorAll('.booking-section').forEach(sec => sec.classList.add('hidden'));
        const activeSec = document.getElementById(`section-${status}`);
        if (activeSec) {
            activeSec.classList.remove('hidden');
        }
    }

    // Details Modal
    function showBookingDetailsModal(bk, propName, propLocation, propImg, ownerName, ownerPhone) {
        document.getElementById('modalBookingIdText').textContent = `Booking #${bk.booking_id}`;
        document.getElementById('modalPropName').textContent = propName;
        document.getElementById('modalPropLocation').textContent = propLocation;
        document.getElementById('modalPropImg').src = propImg;
        document.getElementById('modalCheckIn').textContent = bk.check_in_date ? new Date(bk.check_in_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Immediate';
        document.getElementById('modalDuration').textContent = (bk.duration_months || 11) + ' Months';
        document.getElementById('modalRoomType').textContent = bk.room_type_name || 'Standard Stay';
        
        let statusStr = bk.booking_status ? bk.booking_status.toUpperCase() : 'PENDING';
        if (bk.broker_approval === 'approved') statusStr = 'CONFIRMED';
        if (bk.broker_approval === 'rejected' || bk.booking_status === 'cancelled') statusStr = 'CANCELLED';
        document.getElementById('modalStatus').textContent = statusStr;

        document.getElementById('modalHostName').textContent = ownerName;
        document.getElementById('modalHostPhone').textContent = ownerPhone ? `+91 ${ownerPhone}` : 'Contact via StayNest';
        document.getElementById('modalCallLink').href = `tel:${ownerPhone}`;
        document.getElementById('modalWhatsappLink').href = `https://wa.me/91${ownerPhone}?text=${encodeURIComponent('Hi ' . $ownerName . ', regarding booking #' + bk.booking_id + ' for ' + propName)}`;

        document.getElementById('modalRent').textContent = '₹' + Number(bk.base_rent).toLocaleString('en-IN') + '/mo';
        document.getElementById('modalDeposit').textContent = '₹' + Number(bk.security_deposit || 0).toLocaleString('en-IN');
        document.getElementById('modalTotal').textContent = '₹' + Number(bk.total_amount || (Number(bk.base_rent) + Number(bk.security_deposit || 0))).toLocaleString('en-IN');

        const reqBox = document.getElementById('modalSpecialReqBox');
        const reqText = document.getElementById('modalSpecialReqText');
        if (bk.special_requests) {
            reqText.textContent = bk.special_requests;
            reqBox.classList.remove('hidden');
        } else {
            reqBox.classList.add('hidden');
        }

        const modal = document.getElementById('bookingDetailsModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeBookingDetailsModal() {
        const modal = document.getElementById('bookingDetailsModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Invoice Modal
    function showInvoiceModal(bkId, propName, tenantName, checkIn, roomType, duration, rent, deposit, total, status) {
        document.getElementById('invNumber').textContent = `#${bkId}`;
        document.getElementById('invTenantName').textContent = tenantName;
        document.getElementById('invPropName').textContent = propName;
        document.getElementById('invCheckIn').textContent = checkIn;
        document.getElementById('invPlan').textContent = `${roomType} (${duration})`;
        document.getElementById('invRent').textContent = `₹${rent}/mo`;
        document.getElementById('invDeposit').textContent = `₹${deposit}`;
        document.getElementById('invTotal').textContent = total;

        const modal = document.getElementById('invoiceModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeInvoiceModal() {
        const modal = document.getElementById('invoiceModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Cancel Booking
    async function cancelUserBooking(bkId, bkCode) {
        if (typeof Swal === 'undefined') {
            if (confirm(`Are you sure you want to cancel booking #${bkCode}?`)) {
                submitCancelBooking(bkId, '');
            }
            return;
        }

        const { value: reason } = await Swal.fire({
            title: `Cancel Booking #${bkCode}?`,
            text: 'Are you sure you want to cancel this booking request?',
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'Reason for cancellation (optional)',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Cancel It',
            cancelButtonText: 'Keep Booking'
        });

        if (reason !== undefined) {
            submitCancelBooking(bkId, reason);
        }
    }

    async function submitCancelBooking(bkId, reason) {
        try {
            const response = await fetch(`/bookings/${bkId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ reason: reason || 'Cancelled by tenant' })
            });

            const data = await response.json();
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Booking Cancelled',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4bb59d'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(data.message);
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
                    title: 'Error',
                    text: 'An error occurred while communicating with the server.',
                    icon: 'error',
                    confirmButtonColor: '#4bb59d'
                });
            } else {
                alert('An error occurred while communicating with the server.');
            }
        }
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBookingDetailsModal();
            closeInvoiceModal();
        }
    });
</script>
@endpush
