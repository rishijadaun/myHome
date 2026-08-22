@extends('admin.layouts.app')

@section('title', 'Manage All Bookings')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">All System Bookings</h1>
        <p class="text-sm text-gray-500">300 total platform bookings • 12 awaiting admin/broker confirmation</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="alert('Exporting system booking records to CSV...')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-semibold tap-effect flex items-center gap-2 transition">
            <i class="fas fa-download text-xs"></i> Export CSV
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">389</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Bookings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-green-600">324</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Confirmed & Active</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600">12</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Review</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-red-600">53</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Cancelled / Refunded</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input id="adminBookingSearch" onkeyup="filterAdminBookings()" type="text" placeholder="Search by booking ID, user name, PG property..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <select id="adminBookingStatus" onchange="filterAdminBookings()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All Status</option>
                <option value="CONFIRMED">Confirmed</option>
                <option value="PENDING">Pending</option>
                <option value="CANCELLED">Cancelled</option>
            </select>
            <select id="adminBookingDate" onchange="filterAdminBookings()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="adminBookingTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">User / Tenant</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">PG Property</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Check-in Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount Paid</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="adminBookingBody">
                    <tr class="admin-bk-row hover:bg-gray-50/70 transition" data-status="CONFIRMED">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-brand bk-id">#SN-8392014</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-9 h-9 rounded-full object-cover shadow-xs">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 user-name">Rahul Sharma</div>
                                    <div class="text-xs text-gray-500">+91 98765 43210</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 pg-name">Sunrise Premium PG</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Aug 20, 2026</td>
                        <td class="px-6 py-4 text-sm font-extrabold text-gray-900">₹17,000</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">CONFIRMED</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="openBookingModal('#SN-8392014', 'Rahul Sharma', 'Sunrise Premium PG', 'Aug 20, 2026', '₹17,000', 'CONFIRMED')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="View"><i class="fas fa-eye text-xs"></i></button>
                                <button onclick="alert('Downloading Official Receipt #SN-8392014.pdf')" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center tap-effect" title="Invoice"><i class="fas fa-file-invoice text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="admin-bk-row hover:bg-gray-50/70 transition bg-yellow-50/20" data-status="PENDING">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-brand bk-id">#SN-8392015</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-9 h-9 rounded-full object-cover shadow-xs">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 user-name">Priya Patel</div>
                                    <div class="text-xs text-gray-500">+91 98765 11111</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 pg-name">Aura Women's Stay</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Aug 22, 2026</td>
                        <td class="px-6 py-4 text-sm font-extrabold text-gray-900">₹9,999</td>
                        <td class="px-6 py-4"><span class="status-badge bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">PENDING</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="approveAdminBooking(this, 'Priya Patel', '#SN-8392015')" class="w-8 h-8 rounded-lg bg-green-500 hover:bg-green-600 text-white flex items-center justify-center tap-effect shadow-xs" title="Approve"><i class="fas fa-check text-xs"></i></button>
                                <button onclick="cancelAdminBooking(this, 'Priya Patel', '#SN-8392015')" class="w-8 h-8 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center justify-center tap-effect shadow-xs" title="Reject"><i class="fas fa-times text-xs"></i></button>
                                <button onclick="openBookingModal('#SN-8392015', 'Priya Patel', 'Aura Women\'s Stay', 'Aug 22, 2026', '₹9,999', 'PENDING')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect"><i class="fas fa-eye text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="admin-bk-row hover:bg-gray-50/70 transition" data-status="CANCELLED">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-brand bk-id">#SN-8392016</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-9 h-9 rounded-full object-cover shadow-xs">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 user-name">Amit Kumar</div>
                                    <div class="text-xs text-gray-500">+91 98765 22222</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 pg-name">Urban Nest Co-living</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jul 28, 2026</td>
                        <td class="px-6 py-4 text-sm font-extrabold text-gray-900">₹12,500</td>
                        <td class="px-6 py-4"><span class="status-badge bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg">CANCELLED</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="openBookingModal('#SN-8392016', 'Amit Kumar', 'Urban Nest Co-living', 'Jul 28, 2026', '₹12,500', 'CANCELLED')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect"><i class="fas fa-eye text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-3" id="adminBkMobileList">
        <div class="admin-bk-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm" data-status="CONFIRMED">
            <div class="flex justify-between items-start mb-3">
                <div class="text-xs font-mono font-bold text-brand bk-id">#SN-8392014</div>
                <span class="status-badge bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">CONFIRMED</span>
            </div>
            <div class="flex items-center gap-3 mb-3">
                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-900 text-sm user-name">Rahul Sharma</div>
                    <div class="text-xs text-gray-500 truncate pg-name">Sunrise Premium PG</div>
                </div>
            </div>
            <div class="flex justify-between items-center pt-3 border-t border-gray-100 text-xs">
                <div><span class="text-gray-400">Check-in:</span> <span class="font-semibold text-gray-700">Aug 20, 2026</span></div>
                <div class="text-right"><span class="text-gray-400">Paid:</span> <span class="font-extrabold text-gray-900">₹17,000</span></div>
            </div>
        </div>

        <div class="admin-bk-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm bg-yellow-50/20" data-status="PENDING">
            <div class="flex justify-between items-start mb-3">
                <div class="text-xs font-mono font-bold text-brand bk-id">#SN-8392015</div>
                <span class="status-badge bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded">PENDING</span>
            </div>
            <div class="flex items-center gap-3 mb-3">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-900 text-sm user-name">Priya Patel</div>
                    <div class="text-xs text-gray-500 truncate pg-name">Aura Women's Stay</div>
                </div>
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <button onclick="approveAdminBooking(this, 'Priya Patel', '#SN-8392015')" class="flex-1 bg-green-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-check mr-1"></i> Approve</button>
                <button onclick="cancelAdminBooking(this, 'Priya Patel', '#SN-8392015')" class="flex-1 bg-red-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-times mr-1"></i> Reject</button>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="adminBookingModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900">System Booking Details</h3>
            <button onclick="closeModal('adminBookingModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect"><i class="fas fa-times text-gray-500 text-xs"></i></button>
        </div>
        <div class="space-y-2.5 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Booking Reference</span><span id="adminModalId" class="font-mono font-bold text-brand"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">User / Tenant</span><span id="adminModalUser" class="font-bold text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">PG Property</span><span id="adminModalPg" class="font-semibold text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Check-in</span><span id="adminModalDate" class="text-gray-700"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Total Amount</span><span id="adminModalAmount" class="font-extrabold text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Status</span><span id="adminModalStatus" class="font-bold text-green-600"></span></div>
        </div>
        <div class="pt-3">
            <button onclick="closeModal('adminBookingModal')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2.5 rounded-xl tap-effect">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openBookingModal(id, user, pg, date, amount, status) {
        document.getElementById('adminModalId').textContent = id;
        document.getElementById('adminModalUser').textContent = user;
        document.getElementById('adminModalPg').textContent = pg;
        document.getElementById('adminModalDate').textContent = date;
        document.getElementById('adminModalAmount').textContent = amount;
        document.getElementById('adminModalStatus').textContent = status;
        openModal('adminBookingModal');
    }

    function approveAdminBooking(btn, user, id) {
        if (confirm(`Approve booking ${id} for ${user}?`)) {
            const row = btn.closest('.admin-bk-row') || btn.closest('.admin-bk-card');
            if (row) {
                row.setAttribute('data-status', 'CONFIRMED');
                const badge = row.querySelector('.status-badge');
                badge.className = 'status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'CONFIRMED';
                alert(`Booking ${id} approved successfully!`);
            }
        }
    }

    function cancelAdminBooking(btn, user, id) {
        if (confirm(`Cancel and refund booking ${id} for ${user}?`)) {
            const row = btn.closest('.admin-bk-row') || btn.closest('.admin-bk-card');
            if (row) {
                row.setAttribute('data-status', 'CANCELLED');
                const badge = row.querySelector('.status-badge');
                badge.className = 'status-badge bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'CANCELLED';
                alert(`Booking ${id} cancelled and refunded.`);
            }
        }
    }

    function filterAdminBookings() {
        const search = document.getElementById('adminBookingSearch').value.toLowerCase();
        const status = document.getElementById('adminBookingStatus').value;

        document.querySelectorAll('.admin-bk-row, .admin-bk-card').forEach(el => {
            const id = el.querySelector('.bk-id').textContent.toLowerCase();
            const user = el.querySelector('.user-name').textContent.toLowerCase();
            const pg = el.querySelector('.pg-name').textContent.toLowerCase();
            const elStatus = el.getAttribute('data-status');

            const matchSearch = id.includes(search) || user.includes(search) || pg.includes(search);
            const matchStatus = !status || elStatus === status;

            if (matchSearch && matchStatus) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endpush
