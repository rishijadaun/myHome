@extends('broker.layouts.app')

@section('title', 'Tenants Directory')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tenants Directory</h1>
        <p class="text-sm text-gray-500">36 active tenants currently residing in your properties</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('addTenantModal')" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
            <i class="fas fa-user-plus text-sm"></i> Add Tenant
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Mobile Add Button -->
    <div class="lg:hidden">
        <button onclick="openModal('addTenantModal')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
            <i class="fas fa-user-plus"></i> Add New Tenant
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">36</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active Tenants</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-green-600">32</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Rent Paid (August)</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600">4</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Rent Pending</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-bold text-blue-600">2</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Notice Period</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input id="tenantSearchInput" onkeyup="filterTenants()" type="text" placeholder="Search tenant name, phone, room or PG..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <select id="tenantPgFilter" onchange="filterTenants()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All PGs</option>
                <option value="Sunrise Premium PG">Sunrise Premium PG</option>
                <option value="Aura Women's Stay">Aura Women's Stay</option>
                <option value="Urban Nest Co-living">Urban Nest Co-living</option>
            </select>
            <select id="tenantRentFilter" onchange="filterTenants()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">Rent Status: All</option>
                <option value="PAID">Paid</option>
                <option value="PENDING">Pending</option>
            </select>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="tenantTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Property & Room</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Join Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Monthly Rent</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">August Rent</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Deposit</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="tenantTableBody">
                    <!-- Tenant 1 -->
                    <tr class="tenant-row hover:bg-gray-50/70 transition" data-pg="Sunrise Premium PG" data-rent="PAID">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm shadow-xs">RS</div>
                                <div>
                                    <div class="font-bold text-gray-900 tenant-name">Rahul Sharma</div>
                                    <div class="text-xs text-gray-500 tenant-contact">+91 98765 43210</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900 tenant-pg">Sunrise Premium PG</div>
                            <div class="text-xs text-gray-500 tenant-room">Room 204 • Bed A (Twin)</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jan 15, 2026</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹8,500</td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">PAID</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">₹17,000</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="https://wa.me/919876543210" target="_blank" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center tap-effect" title="WhatsApp"><i class="fab fa-whatsapp text-xs"></i></a>
                                <button onclick="alert('Sending rent receipt SMS/Email to Rahul Sharma')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="Send Receipt"><i class="fas fa-receipt text-xs"></i></button>
                                <button onclick="confirmRemoveTenant(this, 'Rahul Sharma')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Mark Move-out"><i class="fas fa-sign-out-alt text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Tenant 2 -->
                    <tr class="tenant-row hover:bg-gray-50/70 transition" data-pg="Aura Women's Stay" data-rent="PAID">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-xs">PP</div>
                                <div>
                                    <div class="font-bold text-gray-900 tenant-name">Priya Patel</div>
                                    <div class="text-xs text-gray-500 tenant-contact">+91 98765 11111</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900 tenant-pg">Aura Women's Stay</div>
                            <div class="text-xs text-gray-500 tenant-room">Room 102 • Bed 1 (Single)</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">Mar 01, 2026</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹9,999</td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">PAID</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">₹20,000</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="https://wa.me/919876511111" target="_blank" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center tap-effect" title="WhatsApp"><i class="fab fa-whatsapp text-xs"></i></a>
                                <button onclick="alert('Sending rent receipt SMS/Email to Priya Patel')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="Send Receipt"><i class="fas fa-receipt text-xs"></i></button>
                                <button onclick="confirmRemoveTenant(this, 'Priya Patel')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Mark Move-out"><i class="fas fa-sign-out-alt text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Tenant 3 -->
                    <tr class="tenant-row hover:bg-gray-50/70 transition" data-pg="Urban Nest Co-living" data-rent="PENDING">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-xs">AK</div>
                                <div>
                                    <div class="font-bold text-gray-900 tenant-name">Amit Kumar</div>
                                    <div class="text-xs text-gray-500 tenant-contact">+91 98765 22222</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900 tenant-pg">Urban Nest Co-living</div>
                            <div class="text-xs text-gray-500 tenant-room">Room 305 • Bed C (Triple)</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">Feb 10, 2026</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹12,500</td>
                        <td class="px-6 py-4"><span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">PENDING</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">₹25,000</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="alert('Sent rent payment reminder to Amit Kumar')" class="px-2.5 py-1 bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs font-bold rounded-lg tap-effect">Remind</button>
                                <a href="https://wa.me/919876522222" target="_blank" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center tap-effect"><i class="fab fa-whatsapp text-xs"></i></a>
                                <button onclick="confirmRemoveTenant(this, 'Amit Kumar')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect"><i class="fas fa-sign-out-alt text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards List -->
    <div class="md:hidden space-y-4" id="tenantMobileList">
        <div class="tenant-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm" data-pg="Sunrise Premium PG" data-rent="PAID">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm">RS</div>
                    <div>
                        <div class="font-bold text-gray-900 text-sm tenant-name">Rahul Sharma</div>
                        <div class="text-xs text-gray-500 tenant-contact">+91 98765 43210</div>
                    </div>
                </div>
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">PAID</span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl mb-3 text-xs space-y-1">
                <div class="flex justify-between"><span class="text-gray-500">Property:</span> <span class="font-semibold text-gray-800 tenant-pg">Sunrise Premium PG</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Room:</span> <span class="font-medium text-gray-700 tenant-room">Room 204 • Bed A</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Monthly Rent:</span> <span class="font-bold text-gray-900">₹8,500</span></div>
            </div>
            <div class="flex gap-2">
                <a href="https://wa.me/919876543210" target="_blank" class="flex-1 bg-green-50 text-green-600 text-xs font-semibold py-2 rounded-lg text-center tap-effect"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
                <button onclick="confirmRemoveTenant(this, 'Rahul Sharma')" class="flex-1 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-sign-out-alt mr-1"></i> Move-out</button>
            </div>
        </div>

        <div class="tenant-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm" data-pg="Aura Women's Stay" data-rent="PAID">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-sm">PP</div>
                    <div>
                        <div class="font-bold text-gray-900 text-sm tenant-name">Priya Patel</div>
                        <div class="text-xs text-gray-500 tenant-contact">+91 98765 11111</div>
                    </div>
                </div>
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">PAID</span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl mb-3 text-xs space-y-1">
                <div class="flex justify-between"><span class="text-gray-500">Property:</span> <span class="font-semibold text-gray-800 tenant-pg">Aura Women's Stay</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Room:</span> <span class="font-medium text-gray-700 tenant-room">Room 102 • Bed 1</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Monthly Rent:</span> <span class="font-bold text-gray-900">₹9,999</span></div>
            </div>
            <div class="flex gap-2">
                <a href="https://wa.me/919876511111" target="_blank" class="flex-1 bg-green-50 text-green-600 text-xs font-semibold py-2 rounded-lg text-center tap-effect"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
                <button onclick="confirmRemoveTenant(this, 'Priya Patel')" class="flex-1 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-sign-out-alt mr-1"></i> Move-out</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Tenant Modal -->
<div id="addTenantModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-gray-900">Add New Tenant</h3>
            <button onclick="closeModal('addTenantModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <form onsubmit="event.preventDefault(); alert('Tenant added successfully!'); closeModal('addTenantModal');" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name *</label>
                <input type="text" placeholder="e.g. Ankit Verma" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number *</label>
                    <input type="tel" placeholder="+91 9876543210" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email ID</label>
                    <input type="email" placeholder="tenant@gmail.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Select PG *</label>
                    <select required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="Sunrise Premium PG">Sunrise Premium PG</option>
                        <option value="Aura Women's Stay">Aura Women's Stay</option>
                        <option value="Urban Nest Co-living">Urban Nest Co-living</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Room & Bed *</label>
                    <input type="text" placeholder="e.g. Room 104, Bed B" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Monthly Rent (₹) *</label>
                    <input type="number" placeholder="8500" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Security Deposit (₹)</label>
                    <input type="number" placeholder="17000" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('addTenantModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect">Cancel</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30">Save Tenant</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function confirmRemoveTenant(btn, name) {
        if (confirm(`Are you sure you want to mark ${name} as moved out?`)) {
            const row = btn.closest('.tenant-row') || btn.closest('.tenant-card');
            if (row) row.remove();
        }
    }

    function filterTenants() {
        const search = document.getElementById('tenantSearchInput').value.toLowerCase();
        const pg = document.getElementById('tenantPgFilter').value;
        const rent = document.getElementById('tenantRentFilter').value;

        document.querySelectorAll('.tenant-row, .tenant-card').forEach(el => {
            const name = el.querySelector('.tenant-name').textContent.toLowerCase();
            const contact = el.querySelector('.tenant-contact').textContent.toLowerCase();
            const elPg = el.getAttribute('data-pg');
            const elRent = el.getAttribute('data-rent');

            const matchSearch = name.includes(search) || contact.includes(search) || elPg.toLowerCase().includes(search);
            const matchPg = !pg || elPg === pg;
            const matchRent = !rent || elRent === rent;

            if (matchSearch && matchPg && matchRent) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endpush
