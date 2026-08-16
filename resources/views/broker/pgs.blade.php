@extends('broker.layouts.app')

@section('title', 'My PGs')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My PG Properties</h1>
        <p class="text-sm text-gray-500">Manage, edit and monitor your 12 listed PG properties.</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('addPgModal')" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i> Add New PG
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Mobile Add Button -->
    <div class="lg:hidden">
        <button onclick="openModal('addPgModal')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Add New PG Property
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-gray-900">12</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Properties</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-green-600">10</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active & Listed</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-brand">120</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Bed Capacity</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-yellow-600">26</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Available Beds</div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input id="pgSearchInput" onkeyup="filterPGs()" type="text" placeholder="Search by PG name, city, sector..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <select id="typeFilter" onchange="filterPGs()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All Types</option>
                <option value="BOYS">Boys PG</option>
                <option value="GIRLS">Girls PG</option>
                <option value="CO-ED">Co-living / Co-Ed</option>
            </select>
            <select id="statusFilter" onchange="filterPGs()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                <option value="">All Status</option>
                <option value="ACTIVE">Active</option>
                <option value="INACTIVE">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="pgTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">PG Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Rent / Mo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Beds</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Occupancy</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- PG Item 1 -->
                    <tr class="pg-row hover:bg-gray-50/70 transition" data-type="BOYS" data-status="ACTIVE">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" alt="PG" class="w-12 h-12 rounded-xl object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 pg-name">Sunrise Premium PG</div>
                                    <div class="text-xs text-gray-400 font-mono">#PG-1001</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 pg-location"><i class="fas fa-map-marker-alt text-brand mr-1"></i> Sector 62, Noida</td>
                        <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">BOYS</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹8,500</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">20 beds</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-700">85% (17/20)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">ACTIVE</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal('Sunrise Premium PG', 'Sector 62, Noida', 'Boys', '20', '8500')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                                <a href="{{ route('user.location') }}" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect" title="View Location Map"><i class="fas fa-map text-xs"></i></a>
                                <button onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Delete"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- PG Item 2 -->
                    <tr class="pg-row hover:bg-gray-50/70 transition" data-type="GIRLS" data-status="ACTIVE">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" alt="PG" class="w-12 h-12 rounded-xl object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 pg-name">Aura Women's Stay</div>
                                    <div class="text-xs text-gray-400 font-mono">#PG-1002</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 pg-location"><i class="fas fa-map-marker-alt text-brand mr-1"></i> Indiranagar, Bangalore</td>
                        <td class="px-6 py-4"><span class="bg-pink-50 text-pink-600 text-xs font-bold px-2.5 py-1 rounded-lg">GIRLS</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹9,999</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">15 beds</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand rounded-full" style="width: 93%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-700">93% (14/15)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">ACTIVE</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal('Aura Women\'s Stay', 'Indiranagar, Bangalore', 'Girls', '15', '9999')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                                <a href="{{ route('user.location') }}" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect" title="View Location Map"><i class="fas fa-map text-xs"></i></a>
                                <button onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Delete"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- PG Item 3 -->
                    <tr class="pg-row hover:bg-gray-50/70 transition" data-type="CO-ED" data-status="ACTIVE">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" alt="PG" class="w-12 h-12 rounded-xl object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 pg-name">Urban Nest Co-living</div>
                                    <div class="text-xs text-gray-400 font-mono">#PG-1003</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 pg-location"><i class="fas fa-map-marker-alt text-brand mr-1"></i> HSR Layout, Bangalore</td>
                        <td class="px-6 py-4"><span class="bg-purple-50 text-purple-600 text-xs font-bold px-2.5 py-1 rounded-lg">CO-ED</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹12,500</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">30 beds</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-orange-400 rounded-full" style="width: 60%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-700">60% (18/30)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">ACTIVE</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal('Urban Nest Co-living', 'HSR Layout, Bangalore', 'Co-living', '30', '12500')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                                <a href="{{ route('user.location') }}" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect" title="View Location Map"><i class="fas fa-map text-xs"></i></a>
                                <button onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Delete"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- PG Item 4 -->
                    <tr class="pg-row hover:bg-gray-50/70 transition" data-type="BOYS" data-status="ACTIVE">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" alt="PG" class="w-12 h-12 rounded-xl object-cover shadow-xs">
                                <div>
                                    <div class="font-bold text-gray-900 pg-name">Silicon Valley Boys Hostel</div>
                                    <div class="text-xs text-gray-400 font-mono">#PG-1004</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 pg-location"><i class="fas fa-map-marker-alt text-brand mr-1"></i> Whitefield, Bangalore</td>
                        <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">BOYS</span></td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">₹7,800</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">25 beds</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand rounded-full" style="width: 88%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-700">88% (22/25)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">ACTIVE</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal('Silicon Valley Boys Hostel', 'Whitefield, Bangalore', 'Boys', '25', '7800')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                                <a href="{{ route('user.location') }}" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect" title="View Location Map"><i class="fas fa-map text-xs"></i></a>
                                <button onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Delete"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards List -->
    <div class="md:hidden space-y-4" id="pgMobileList">
        <div class="pg-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm" data-type="BOYS" data-status="ACTIVE">
            <div class="flex gap-3 mb-3">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-20 h-20 rounded-xl object-cover">
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-900 text-sm truncate pg-name">Sunrise Premium PG</h3>
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">ACTIVE</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 truncate pg-location"><i class="fas fa-map-marker-alt text-brand"></i> Sector 62, Noida</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded">BOYS</span>
                        <span class="text-xs font-semibold text-gray-900">₹8,500/mo</span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="flex justify-between text-[11px] mb-1">
                    <span class="text-gray-500">Occupancy</span>
                    <span class="font-bold text-brand">85% (17/20 beds)</span>
                </div>
                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-brand rounded-full" style="width: 85%"></div>
                </div>
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <button onclick="openEditModal('Sunrise Premium PG', 'Sector 62, Noida', 'Boys', '20', '8500')" class="flex-1 bg-blue-50 text-blue-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-edit mr-1"></i> Edit</button>
                <a href="{{ route('user.location') }}" class="flex-1 bg-teal-50 text-teal-600 text-xs font-semibold py-2 rounded-lg tap-effect text-center"><i class="fas fa-map mr-1"></i> Map</a>
                <button onclick="confirmDelete(this)" class="flex-1 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-trash mr-1"></i> Delete</button>
            </div>
        </div>

        <div class="pg-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm" data-type="GIRLS" data-status="ACTIVE">
            <div class="flex gap-3 mb-3">
                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" class="w-20 h-20 rounded-xl object-cover">
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-900 text-sm truncate pg-name">Aura Women's Stay</h3>
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">ACTIVE</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 truncate pg-location"><i class="fas fa-map-marker-alt text-brand"></i> Indiranagar, Bangalore</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="bg-pink-50 text-pink-600 text-[10px] font-bold px-2 py-0.5 rounded">GIRLS</span>
                        <span class="text-xs font-semibold text-gray-900">₹9,999/mo</span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="flex justify-between text-[11px] mb-1">
                    <span class="text-gray-500">Occupancy</span>
                    <span class="font-bold text-brand">93% (14/15 beds)</span>
                </div>
                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-brand rounded-full" style="width: 93%"></div>
                </div>
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <button onclick="openEditModal('Aura Women\'s Stay', 'Indiranagar, Bangalore', 'Girls', '15', '9999')" class="flex-1 bg-blue-50 text-blue-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-edit mr-1"></i> Edit</button>
                <a href="{{ route('user.location') }}" class="flex-1 bg-teal-50 text-teal-600 text-xs font-semibold py-2 rounded-lg tap-effect text-center"><i class="fas fa-map mr-1"></i> Map</a>
                <button onclick="confirmDelete(this)" class="flex-1 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-trash mr-1"></i> Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add PG Modal -->
<div id="addPgModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-bold text-gray-900">List New PG Property</h3>
                <p class="text-xs text-gray-500">Fill in details to make your property live</p>
            </div>
            <button onclick="closeModal('addPgModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <form onsubmit="event.preventDefault(); alert('PG added successfully!'); closeModal('addPgModal');" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">PG Name *</label>
                <input type="text" placeholder="e.g. Royal Living Luxury PG" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">City / Region *</label>
                    <input type="text" placeholder="e.g. Bangalore" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">PG Type *</label>
                    <select required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="Boys">Boys PG</option>
                        <option value="Girls">Girls PG</option>
                        <option value="Co-living">Co-living / Co-Ed</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Address & Landmark *</label>
                <textarea rows="2" placeholder="e.g. Plot 42, 5th Cross, Near Metro Station" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Total Beds *</label>
                    <input type="number" placeholder="20" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Starting Rent (₹/mo) *</label>
                    <input type="number" placeholder="7500" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deposit (₹) *</label>
                    <input type="number" placeholder="15000" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Amenities Provided</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                    <label class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" checked class="accent-brand"> High-Speed WiFi
                    </label>
                    <label class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" checked class="accent-brand"> 3 Times Meals
                    </label>
                    <label class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" checked class="accent-brand"> AC Rooms
                    </label>
                    <label class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" checked class="accent-brand"> 24/7 CCTV & Security
                    </label>
                </div>
            </div>
            <div class="p-6 -mx-6 -mb-6 border-t border-gray-100 flex gap-3 sticky bottom-0 bg-white">
                <button type="button" onclick="closeModal('addPgModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect transition">Cancel</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition">Publish PG</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit PG Modal -->
<div id="editPgModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full shadow-2xl">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900">Edit Property Details</h3>
            <button onclick="closeModal('editPgModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <form onsubmit="event.preventDefault(); alert('Property updated successfully!'); closeModal('editPgModal');" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">PG Name</label>
                <input id="editPgName" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                <input id="editPgLoc" type="text" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Total Beds</label>
                    <input id="editPgBeds" type="number" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Monthly Rent (₹)</label>
                    <input id="editPgRent" type="number" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('editPgModal')" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl tap-effect">Cancel</button>
                <button type="submit" class="flex-1 bg-brand text-white font-bold py-3 rounded-xl tap-effect shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openEditModal(name, loc, type, beds, rent) {
        document.getElementById('editPgName').value = name;
        document.getElementById('editPgLoc').value = loc;
        document.getElementById('editPgBeds').value = beds;
        document.getElementById('editPgRent').value = rent;
        openModal('editPgModal');
    }

    function confirmDelete(btn) {
        if (confirm('Are you sure you want to remove this PG listing?')) {
            const row = btn.closest('.pg-row') || btn.closest('.pg-card');
            if (row) row.remove();
        }
    }

    function filterPGs() {
        const search = document.getElementById('pgSearchInput').value.toLowerCase();
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;

        document.querySelectorAll('.pg-row, .pg-card').forEach(el => {
            const name = el.querySelector('.pg-name').textContent.toLowerCase();
            const loc = el.querySelector('.pg-location').textContent.toLowerCase();
            const elType = el.getAttribute('data-type');
            const elStatus = el.getAttribute('data-status');

            const matchSearch = name.includes(search) || loc.includes(search);
            const matchType = !type || elType === type;
            const matchStatus = !status || elStatus === status;

            if (matchSearch && matchType && matchStatus) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endpush
