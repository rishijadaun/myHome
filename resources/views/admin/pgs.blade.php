@extends('admin.layouts.app')

@section('title', 'Manage All PGs')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manage PG Properties</h1>
        <p class="text-sm text-gray-500">124 properties listed across 18 major cities</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('adminAddPgModal')" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i> Add PG Listing
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Mobile Add Button -->
    <div class="lg:hidden">
        <button onclick="openModal('adminAddPgModal')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Add New PG Property
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">124</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Listings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-green-600">110</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active & Approved</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600">10</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Verification</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-red-600">4</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Suspended / Inactive</div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input id="adminPgSearch" onkeyup="filterAdminPgs()" type="text" placeholder="Search by PG name, broker name, city..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <select id="adminPgType" onchange="filterAdminPgs()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All Categories</option>
                <option value="BOYS">Boys PG</option>
                <option value="GIRLS">Girls PG</option>
                <option value="CO-ED">Co-living / Co-Ed</option>
            </select>
            <select id="adminPgCity" onchange="filterAdminPgs()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All Cities</option>
                <option value="Noida">Noida</option>
                <option value="Bangalore">Bangalore</option>
                <option value="Delhi">Delhi</option>
                <option value="Mumbai">Mumbai</option>
            </select>
            <select id="adminPgStatus" onchange="filterAdminPgs()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All Status</option>
                <option value="APPROVED">Approved</option>
                <option value="PENDING">Pending</option>
                <option value="SUSPENDED">Suspended</option>
            </select>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="adminPgTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Broker / Owner</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">City</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Price / Mo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="adminPgBody">
                    <tr class="admin-pg-row hover:bg-gray-50/70 transition" data-type="BOYS" data-city="Noida" data-status="APPROVED">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-12 h-12 rounded-xl object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 pg-name">Sunrise Premium PG</div>
                                    <div class="text-xs text-gray-500">Sector 62</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 broker-name">Vikram Singh</td>
                        <td class="px-6 py-4 text-sm text-gray-600 pg-city">Noida</td>
                        <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">BOYS</span></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">20 beds</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹8,500</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">APPROVED</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('user.location') }}" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect" title="Map"><i class="fas fa-map text-xs"></i></a>
                                <button onclick="togglePgStatus(this)" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center tap-effect" title="Toggle Status"><i class="fas fa-toggle-on text-xs"></i></button>
                                <button onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Delete"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="admin-pg-row hover:bg-gray-50/70 transition" data-type="GIRLS" data-city="Bangalore" data-status="APPROVED">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-12 h-12 rounded-xl object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 pg-name">Aura Women's Stay</div>
                                    <div class="text-xs text-gray-500">Indiranagar</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 broker-name">Vikram Singh</td>
                        <td class="px-6 py-4 text-sm text-gray-600 pg-city">Bangalore</td>
                        <td class="px-6 py-4"><span class="bg-pink-50 text-pink-600 text-xs font-bold px-2.5 py-1 rounded-lg">GIRLS</span></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">15 beds</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹9,999</td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">APPROVED</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('user.location') }}" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect" title="Map"><i class="fas fa-map text-xs"></i></a>
                                <button onclick="togglePgStatus(this)" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center tap-effect" title="Toggle Status"><i class="fas fa-toggle-on text-xs"></i></button>
                                <button onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Delete"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="admin-pg-row hover:bg-gray-50/70 transition bg-yellow-50/20" data-type="CO-ED" data-city="Bangalore" data-status="PENDING">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" class="w-12 h-12 rounded-xl object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 pg-name">Urban Nest Co-living</div>
                                    <div class="text-xs text-gray-500">HSR Layout</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 broker-name">Neha Patel</td>
                        <td class="px-6 py-4 text-sm text-gray-600 pg-city">Bangalore</td>
                        <td class="px-6 py-4"><span class="bg-purple-50 text-purple-600 text-xs font-bold px-2.5 py-1 rounded-lg">CO-ED</span></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">30 beds</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹12,500</td>
                        <td class="px-6 py-4"><span class="status-badge bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">PENDING</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="approvePg(this)" class="px-2.5 py-1 bg-green-500 text-white font-bold text-xs rounded-lg tap-effect">Approve</button>
                                <button onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4" id="adminPgMobileList">
        <div class="admin-pg-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm" data-type="BOYS" data-city="Noida" data-status="APPROVED">
            <div class="flex gap-3 mb-3">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-20 h-20 rounded-xl object-cover">
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-900 text-sm truncate pg-name">Sunrise Premium PG</h3>
                        <span class="status-badge bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">APPROVED</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 pg-city"><i class="fas fa-map-marker-alt text-brand"></i> Noida • Sector 62</p>
                    <p class="text-xs text-gray-600 font-medium broker-name">Broker: Vikram Singh</p>
                </div>
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('user.location') }}" class="flex-1 bg-teal-50 text-teal-600 text-xs font-semibold py-2 rounded-lg text-center tap-effect"><i class="fas fa-map mr-1"></i> Map</a>
                <button onclick="togglePgStatus(this)" class="flex-1 bg-yellow-50 text-yellow-700 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-toggle-on mr-1"></i> Toggle</button>
                <button onclick="confirmDelete(this)" class="flex-1 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-trash mr-1"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Admin Add PG Modal -->
<div id="adminAddPgModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Admin - Add PG Property</h3>
                <p class="text-xs text-gray-500">List and instantly approve property</p>
            </div>
            <button onclick="closeModal('adminAddPgModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <form onsubmit="event.preventDefault(); alert('Property added and published by Admin!'); closeModal('adminAddPgModal');" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">PG Name *</label>
                <input type="text" placeholder="e.g. Grand Residency PG" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Assign Broker / Owner *</label>
                    <select required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="Vikram Singh">Vikram Singh (+91 98765 43210)</option>
                        <option value="Neha Patel">Neha Patel (+91 98765 11111)</option>
                        <option value="Rajesh Sharma">Rajesh Sharma (+91 98765 22222)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">City *</label>
                    <select required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="Noida">Noida</option>
                        <option value="Bangalore">Bangalore</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Mumbai">Mumbai</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Type *</label>
                    <select required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="BOYS">Boys</option>
                        <option value="GIRLS">Girls</option>
                        <option value="CO-ED">Co-living</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Total Beds *</label>
                    <input type="number" value="25" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Rent / Mo (₹) *</label>
                    <input type="number" value="8500" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('adminAddPgModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect">Cancel</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30">Save & Publish</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function approvePg(btn) {
        const row = btn.closest('.admin-pg-row');
        if (row) {
            row.setAttribute('data-status', 'APPROVED');
            const badge = row.querySelector('.status-badge');
            if (badge) {
                badge.className = 'status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'APPROVED';
            }
            btn.remove();
            alert('PG approved successfully!');
        }
    }

    function togglePgStatus(btn) {
        const row = btn.closest('.admin-pg-row') || btn.closest('.admin-pg-card');
        if (row) {
            const current = row.getAttribute('data-status');
            const badge = row.querySelector('.status-badge');
            if (current === 'APPROVED') {
                row.setAttribute('data-status', 'SUSPENDED');
                badge.className = 'status-badge bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'SUSPENDED';
            } else {
                row.setAttribute('data-status', 'APPROVED');
                badge.className = 'status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'APPROVED';
            }
        }
    }

    function confirmDelete(btn) {
        if (confirm('Are you sure you want to remove this property?')) {
            const row = btn.closest('.admin-pg-row') || btn.closest('.admin-pg-card');
            if (row) row.remove();
        }
    }

    function filterAdminPgs() {
        const search = document.getElementById('adminPgSearch').value.toLowerCase();
        const type = document.getElementById('adminPgType').value;
        const city = document.getElementById('adminPgCity').value;
        const status = document.getElementById('adminPgStatus').value;

        document.querySelectorAll('.admin-pg-row, .admin-pg-card').forEach(el => {
            const name = el.querySelector('.pg-name').textContent.toLowerCase();
            const broker = el.querySelector('.broker-name').textContent.toLowerCase();
            const elCity = el.getAttribute('data-city');
            const elType = el.getAttribute('data-type');
            const elStatus = el.getAttribute('data-status');

            const matchSearch = name.includes(search) || broker.includes(search);
            const matchType = !type || elType === type;
            const matchCity = !city || elCity.toLowerCase() === city.toLowerCase();
            const matchStatus = !status || elStatus === status;

            if (matchSearch && matchType && matchCity && matchStatus) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endpush
