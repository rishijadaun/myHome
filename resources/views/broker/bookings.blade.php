@extends('broker.layouts.app')

@section('title', 'My Bookings')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tenant Bookings</h1>
        <p class="text-sm text-gray-500">48 total bookings • 5 pending verification & approval</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="alert('Exporting booking CSV report...')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold tap-effect flex items-center gap-2 transition">
            <i class="fas fa-file-export text-xs"></i> Export CSV
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-brand/40 transition" onclick="filterByTab('ALL')">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">48</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Bookings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-green-300 transition" onclick="filterByTab('CONFIRMED')">
            <div class="text-2xl md:text-3xl font-bold text-green-600">38</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Confirmed & Active</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-yellow-300 transition" onclick="filterByTab('PENDING')">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600">5</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Requests</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-red-300 transition" onclick="filterByTab('CANCELLED')">
            <div class="text-2xl md:text-3xl font-bold text-red-600">5</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Cancelled / Rejected</div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Tabs & Search Header -->
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex border-b md:border-b-0 border-gray-100 space-x-2 overflow-x-auto no-scrollbar" id="bookingTabContainer">
                <button onclick="setTab(this, 'PENDING')" class="tab-btn px-5 py-2.5 text-sm font-semibold rounded-xl bg-brand-light text-brand border border-brand/20 transition whitespace-nowrap">
                    Pending (5)
                </button>
                <button onclick="setTab(this, 'CONFIRMED')" class="tab-btn px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">
                    Confirmed (38)
                </button>
                <button onclick="setTab(this, 'CANCELLED')" class="tab-btn px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">
                    Cancelled (5)
                </button>
                <button onclick="setTab(this, 'ALL')" class="tab-btn px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">
                    All (48)
                </button>
            </div>
            <div class="relative w-full md:w-72">
                <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                <input id="bookingSearchInput" onkeyup="filterBookings()" type="text" placeholder="Search tenant, PG or ID..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-9 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left" id="bookingTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">PG Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Room Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Check-in</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="bookingTableBody">
                    <!-- Pending Row 1 -->
                    <tr class="booking-row hover:bg-gray-50/70 transition bg-yellow-50/20" data-status="PENDING">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-brand booking-id">#BK-2045</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-brand text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">RS</div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 tenant-name">Rahul Sharma</div>
                                    <div class="text-xs text-gray-500">+91 98765 43210</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-medium pg-title">Sunrise Premium PG</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Twin Sharing</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Aug 20, 2026</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹17,000</td>
                        <td class="px-6 py-4"><span class="status-badge bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">PENDING</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 action-buttons">
                                <button onclick="approveBooking(this, 'Rahul Sharma', '#BK-2045')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect shadow-xs" title="Approve"><i class="fas fa-check text-xs"></i></button>
                                <button onclick="rejectBooking(this, 'Rahul Sharma', '#BK-2045')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect shadow-xs" title="Reject"><i class="fas fa-times text-xs"></i></button>
                                <button onclick="viewBookingDetails('#BK-2045', 'Rahul Sharma', '+91 98765 43210', 'Sunrise Premium PG', 'Twin Sharing', 'Aug 20, 2026', '₹17,000', 'PENDING')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="View"><i class="fas fa-eye text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Pending Row 2 -->
                    <tr class="booking-row hover:bg-gray-50/70 transition bg-yellow-50/20" data-status="PENDING">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-brand booking-id">#BK-2046</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">PP</div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 tenant-name">Priya Patel</div>
                                    <div class="text-xs text-gray-500">+91 98765 11111</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-medium pg-title">Aura Women's Stay</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Single Private Room</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Aug 22, 2026</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹9,999</td>
                        <td class="px-6 py-4"><span class="status-badge bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">PENDING</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 action-buttons">
                                <button onclick="approveBooking(this, 'Priya Patel', '#BK-2046')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect shadow-xs" title="Approve"><i class="fas fa-check text-xs"></i></button>
                                <button onclick="rejectBooking(this, 'Priya Patel', '#BK-2046')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect shadow-xs" title="Reject"><i class="fas fa-times text-xs"></i></button>
                                <button onclick="viewBookingDetails('#BK-2046', 'Priya Patel', '+91 98765 11111', 'Aura Women\'s Stay', 'Single Private Room', 'Aug 22, 2026', '₹9,999', 'PENDING')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="View"><i class="fas fa-eye text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Confirmed Row 1 -->
                    <tr class="booking-row hover:bg-gray-50/70 transition" data-status="CONFIRMED">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-brand booking-id">#BK-2044</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">AK</div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 tenant-name">Amit Kumar</div>
                                    <div class="text-xs text-gray-500">+91 98765 22222</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-medium pg-title">Urban Nest Co-living</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Triple Sharing</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Aug 10, 2026</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹12,500</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">CONFIRMED</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 action-buttons">
                                <button onclick="viewBookingDetails('#BK-2044', 'Amit Kumar', '+91 98765 22222', 'Urban Nest Co-living', 'Triple Sharing', 'Aug 10, 2026', '₹12,500', 'CONFIRMED')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect"><i class="fas fa-eye text-xs"></i></button>
                                <a href="https://wa.me/919876522222" target="_blank" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center tap-effect"><i class="fab fa-whatsapp text-xs"></i></a>
                            </div>
                        </td>
                    </tr>

                    <!-- Confirmed Row 2 -->
                    <tr class="booking-row hover:bg-gray-50/70 transition" data-status="CONFIRMED">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-brand booking-id">#BK-2043</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-teal-500 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">SM</div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 tenant-name">Sneha Mishra</div>
                                    <div class="text-xs text-gray-500">+91 98765 33333</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-medium pg-title">Aura Women's Stay</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Twin Sharing</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Aug 01, 2026</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹8,000</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">CONFIRMED</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 action-buttons">
                                <button onclick="viewBookingDetails('#BK-2043', 'Sneha Mishra', '+91 98765 33333', 'Aura Women\'s Stay', 'Twin Sharing', 'Aug 01, 2026', '₹8,000', 'CONFIRMED')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect"><i class="fas fa-eye text-xs"></i></button>
                                <a href="https://wa.me/919876533333" target="_blank" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center tap-effect"><i class="fab fa-whatsapp text-xs"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards List -->
        <div class="md:hidden divide-y divide-gray-100" id="bookingMobileList">
            <div class="p-4 bg-yellow-50/20 booking-card" data-status="PENDING">
                <div class="flex justify-between items-start mb-2">
                    <div class="text-xs font-mono font-bold text-brand booking-id">#BK-2045</div>
                    <span class="status-badge bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded">PENDING</span>
                </div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm">RS</div>
                    <div>
                        <div class="font-bold text-gray-900 text-sm tenant-name">Rahul Sharma</div>
                        <div class="text-xs text-gray-500 pg-title">Sunrise Premium PG • Twin Sharing</div>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-3 text-xs">
                    <div><span class="text-gray-400">Check-in:</span> <span class="font-semibold text-gray-700">Aug 20, 2026</span></div>
                    <div class="text-right"><span class="text-gray-400">Amount:</span> <span class="font-bold text-gray-900">₹17,000</span></div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <button onclick="approveBooking(this, 'Rahul Sharma', '#BK-2045')" class="flex-1 bg-green-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-check mr-1"></i> Approve</button>
                    <button onclick="rejectBooking(this, 'Rahul Sharma', '#BK-2045')" class="flex-1 bg-red-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-times mr-1"></i> Reject</button>
                </div>
            </div>

            <div class="p-4 bg-yellow-50/20 booking-card" data-status="PENDING">
                <div class="flex justify-between items-start mb-2">
                    <div class="text-xs font-mono font-bold text-brand booking-id">#BK-2046</div>
                    <span class="status-badge bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded">PENDING</span>
                </div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-sm">PP</div>
                    <div>
                        <div class="font-bold text-gray-900 text-sm tenant-name">Priya Patel</div>
                        <div class="text-xs text-gray-500 pg-title">Aura Women's Stay • Single Room</div>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-3 text-xs">
                    <div><span class="text-gray-400">Check-in:</span> <span class="font-semibold text-gray-700">Aug 22, 2026</span></div>
                    <div class="text-right"><span class="text-gray-400">Amount:</span> <span class="font-bold text-gray-900">₹9,999</span></div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <button onclick="approveBooking(this, 'Priya Patel', '#BK-2046')" class="flex-1 bg-green-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-check mr-1"></i> Approve</button>
                    <button onclick="rejectBooking(this, 'Priya Patel', '#BK-2046')" class="flex-1 bg-red-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-times mr-1"></i> Reject</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center font-bold"><i class="fas fa-receipt text-sm"></i></div>
                <h3 class="text-lg font-bold text-gray-900">Booking Summary</h3>
            </div>
            <button onclick="closeModal('bookingDetailsModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200">
                <i class="fas fa-times text-gray-500 text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 text-sm">
            <div class="flex justify-between items-center bg-gray-50 p-3.5 rounded-xl">
                <span class="text-gray-500">Booking Reference</span>
                <span id="modalBkId" class="font-mono font-bold text-brand">#BK-2045</span>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between"><span class="text-gray-500">Tenant Name</span><span id="modalTenant" class="font-bold text-gray-900">Rahul Sharma</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Phone</span><span id="modalPhone" class="font-medium text-gray-700">+91 98765 43210</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Property</span><span id="modalPg" class="font-semibold text-gray-900">Sunrise Premium PG</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Room Type</span><span id="modalRoom" class="text-gray-700">Twin Sharing</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Move-in Date</span><span id="modalCheckin" class="font-medium text-gray-900">Aug 20, 2026</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Status</span><span id="modalStatus" class="font-bold text-yellow-600">PENDING</span></div>
            </div>
            <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                <span class="font-semibold text-gray-900">Total Booking Paid</span>
                <span id="modalAmount" class="text-xl font-bold text-brand">₹17,000</span>
            </div>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-2">
            <button onclick="closeModal('bookingDetailsModal')" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 rounded-xl tap-effect">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentFilter = 'PENDING';

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function setTab(btn, status) {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-brand-light', 'text-brand', 'border', 'border-brand/20', 'font-semibold');
            b.classList.add('text-gray-600', 'font-medium');
        });
        btn.classList.add('bg-brand-light', 'text-brand', 'border', 'border-brand/20', 'font-semibold');
        btn.classList.remove('text-gray-600', 'font-medium');
        currentFilter = status;
        filterBookings();
    }

    function filterByTab(status) {
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(t => {
            if (t.textContent.toUpperCase().includes(status)) {
                setTab(t, status);
            }
        });
    }

    function filterBookings() {
        const search = document.getElementById('bookingSearchInput').value.toLowerCase();
        document.querySelectorAll('.booking-row, .booking-card').forEach(el => {
            const id = el.querySelector('.booking-id').textContent.toLowerCase();
            const tenant = el.querySelector('.tenant-name').textContent.toLowerCase();
            const pg = el.querySelector('.pg-title').textContent.toLowerCase();
            const status = el.getAttribute('data-status');

            const matchTab = (currentFilter === 'ALL') || (status === currentFilter);
            const matchSearch = id.includes(search) || tenant.includes(search) || pg.includes(search);

            if (matchTab && matchSearch) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }

    // Default trigger filter to show pending
    filterBookings();

    function approveBooking(btn, name, id) {
        if (confirm(`Approve booking ${id} for ${name}?`)) {
            const row = btn.closest('.booking-row') || btn.closest('.booking-card');
            if (row) {
                row.setAttribute('data-status', 'CONFIRMED');
                const badge = row.querySelector('.status-badge');
                if (badge) {
                    badge.className = 'status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                    badge.textContent = 'CONFIRMED';
                }
                filterBookings();
                alert(`Booking ${id} approved successfully!`);
            }
        }
    }

    function rejectBooking(btn, name, id) {
        if (confirm(`Reject booking ${id} for ${name}?`)) {
            const row = btn.closest('.booking-row') || btn.closest('.booking-card');
            if (row) {
                row.setAttribute('data-status', 'CANCELLED');
                const badge = row.querySelector('.status-badge');
                if (badge) {
                    badge.className = 'status-badge bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                    badge.textContent = 'CANCELLED';
                }
                filterBookings();
                alert(`Booking ${id} rejected.`);
            }
        }
    }

    function viewBookingDetails(id, name, phone, pg, room, checkin, amount, status) {
        document.getElementById('modalBkId').textContent = id;
        document.getElementById('modalTenant').textContent = name;
        document.getElementById('modalPhone').textContent = phone;
        document.getElementById('modalPg').textContent = pg;
        document.getElementById('modalRoom').textContent = room;
        document.getElementById('modalCheckin').textContent = checkin;
        document.getElementById('modalAmount').textContent = amount;
        document.getElementById('modalStatus').textContent = status;
        openModal('bookingDetailsModal');
    }
</script>
@endpush
