@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Registered Users</h1>
        <p class="text-sm text-gray-500">1,248 registered accounts across tenants, brokers and administrators</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="alert('Exporting full users list...')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-semibold tap-effect flex items-center gap-2 transition">
            <i class="fas fa-file-export text-xs"></i> Export Users
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">1,248</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Users</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-green-600">892</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active Tenants</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-brand">156</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">New This Month</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-red-600">23</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Suspended Accounts</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Search & Role Filter -->
        <div class="p-4 md:p-5 border-b border-gray-100">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input id="userSearch" onkeyup="filterUsers()" type="text" placeholder="Search users by name, email, phone..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <select id="userRoleFilter" onchange="filterUsers()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                    <option value="">All Account Roles</option>
                    <option value="TENANT">Tenants</option>
                    <option value="BROKER">Brokers</option>
                    <option value="ADMIN">Admins</option>
                </select>
                <select id="userStatusFilter" onchange="filterUsers()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                    <option value="">All Status</option>
                    <option value="ACTIVE">Active</option>
                    <option value="BLOCKED">Blocked</option>
                </select>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left" id="userTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">User Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Email Address</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Joined Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="userBody">
                    <tr class="user-row hover:bg-gray-50/70 transition" data-role="TENANT" data-status="ACTIVE">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-10 h-10 rounded-full object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 user-name">Rahul Sharma</div>
                                    <div class="text-xs text-gray-500 user-phone">+91 98765 43210</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 user-email">rahul.sharma@gmail.com</td>
                        <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">TENANT</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jan 15, 2026</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">3 Bookings</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">ACTIVE</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="viewUserDetails('Rahul Sharma', '+91 98765 43210', 'rahul.sharma@gmail.com', 'TENANT', '3 Bookings', 'Jan 15, 2026', 'ACTIVE')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="View"><i class="fas fa-eye text-xs"></i></button>
                                <button onclick="toggleUserBlock(this, 'Rahul Sharma')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Block"><i class="fas fa-ban text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="user-row hover:bg-gray-50/70 transition" data-role="BROKER" data-status="ACTIVE">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm shadow-xs">VS</div>
                                <div>
                                    <div class="font-bold text-gray-900 user-name">Vikram Singh</div>
                                    <div class="text-xs text-gray-500 user-phone">+91 98765 00000</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 user-email">vikram@broker.com</td>
                        <td class="px-6 py-4"><span class="bg-purple-50 text-purple-600 text-xs font-bold px-2.5 py-1 rounded-lg">BROKER</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600">Mar 22, 2025</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">12 Listed PGs</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">ACTIVE</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="viewUserDetails('Vikram Singh', '+91 98765 00000', 'vikram@broker.com', 'BROKER', '12 Listed PGs', 'Mar 22, 2025', 'ACTIVE')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="View"><i class="fas fa-eye text-xs"></i></button>
                                <button onclick="toggleUserBlock(this, 'Vikram Singh')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Block"><i class="fas fa-ban text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="user-row hover:bg-gray-50/70 transition" data-role="TENANT" data-status="ACTIVE">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-10 h-10 rounded-full object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 user-name">Priya Patel</div>
                                    <div class="text-xs text-gray-500 user-phone">+91 98765 11111</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 user-email">priya.patel@gmail.com</td>
                        <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">TENANT</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600">Apr 10, 2026</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">1 Booking</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">ACTIVE</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="viewUserDetails('Priya Patel', '+91 98765 11111', 'priya.patel@gmail.com', 'TENANT', '1 Booking', 'Apr 10, 2026', 'ACTIVE')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="View"><i class="fas fa-eye text-xs"></i></button>
                                <button onclick="toggleUserBlock(this, 'Priya Patel')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Block"><i class="fas fa-ban text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-gray-100" id="userMobileList">
            <div class="p-4 user-card" data-role="TENANT" data-status="ACTIVE">
                <div class="flex items-center gap-3 mb-3">
                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-900 user-name">Rahul Sharma</h3>
                            <span class="status-badge bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">ACTIVE</span>
                        </div>
                        <p class="text-xs text-gray-500 user-email">rahul.sharma@gmail.com</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded">TENANT</span>
                            <span class="text-xs text-gray-500">3 bookings</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <button onclick="viewUserDetails('Rahul Sharma', '+91 98765 43210', 'rahul.sharma@gmail.com', 'TENANT', '3 Bookings', 'Jan 15, 2026', 'ACTIVE')" class="flex-1 bg-blue-50 text-blue-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-eye mr-1"></i> View</button>
                    <button onclick="toggleUserBlock(this, 'Rahul Sharma')" class="flex-1 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-ban mr-1"></i> Block</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div id="userDetailModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900">User Account Profile</h3>
            <button onclick="closeModal('userDetailModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect"><i class="fas fa-times text-gray-500 text-xs"></i></button>
        </div>
        <div class="space-y-2.5 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Name</span><span id="usrModalName" class="font-bold text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Phone</span><span id="usrModalPhone" class="font-medium text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Email</span><span id="usrModalEmail" class="font-medium text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Role</span><span id="usrModalRole" class="font-bold text-brand"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Activity</span><span id="usrModalActivity" class="font-semibold text-gray-800"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Member Since</span><span id="usrModalJoined" class="text-gray-600"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Account Status</span><span id="usrModalStatus" class="font-bold text-green-600"></span></div>
        </div>
        <div class="pt-3">
            <button onclick="closeModal('userDetailModal')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2.5 rounded-xl tap-effect">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function viewUserDetails(name, phone, email, role, activity, joined, status) {
        document.getElementById('usrModalName').textContent = name;
        document.getElementById('usrModalPhone').textContent = phone;
        document.getElementById('usrModalEmail').textContent = email;
        document.getElementById('usrModalRole').textContent = role;
        document.getElementById('usrModalActivity').textContent = activity;
        document.getElementById('usrModalJoined').textContent = joined;
        document.getElementById('usrModalStatus').textContent = status;
        openModal('userDetailModal');
    }

    function toggleUserBlock(btn, name) {
        const row = btn.closest('.user-row') || btn.closest('.user-card');
        if (row) {
            const current = row.getAttribute('data-status');
            const badge = row.querySelector('.status-badge');
            if (current === 'ACTIVE') {
                if (confirm(`Block user ${name}?`)) {
                    row.setAttribute('data-status', 'BLOCKED');
                    badge.className = 'status-badge bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                    badge.textContent = 'BLOCKED';
                    alert(`User ${name} has been blocked.`);
                }
            } else {
                row.setAttribute('data-status', 'ACTIVE');
                badge.className = 'status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'ACTIVE';
                alert(`User ${name} unblocked.`);
            }
        }
    }

    function filterUsers() {
        const search = document.getElementById('userSearch').value.toLowerCase();
        const role = document.getElementById('userRoleFilter').value;
        const status = document.getElementById('userStatusFilter').value;

        document.querySelectorAll('.user-row, .user-card').forEach(el => {
            const name = el.querySelector('.user-name').textContent.toLowerCase();
            const email = el.querySelector('.user-email').textContent.toLowerCase();
            const elRole = el.getAttribute('data-role');
            const elStatus = el.getAttribute('data-status');

            const matchSearch = name.includes(search) || email.includes(search);
            const matchRole = !role || elRole === role;
            const matchStatus = !status || elStatus === status;

            if (matchSearch && matchRole && matchStatus) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endpush
