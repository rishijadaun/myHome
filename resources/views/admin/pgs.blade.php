@extends('admin.layouts.app')

@section('title', 'Manage All PGs')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manage PG Properties</h1>
        <p class="text-sm text-gray-500">{{ $totalCount }} properties listed across {{ $cities->count() }} major cities</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('adminAddPgModal')" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2 cursor-pointer">
            <i class="fas fa-plus text-sm"></i> Add PG Listing
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Flash Alert / Toast Anchor -->
    <div id="pgToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="pgToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="pgToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="pgToastMessage">Action completed</span>
        </div>
    </div>

    <!-- Mobile Add Button -->
    <div class="lg:hidden">
        <button onclick="openModal('adminAddPgModal')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2 cursor-pointer">
            <i class="fas fa-plus"></i> Add New PG Property
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Listings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($approvedCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active & Approved</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-amber-600">{{ number_format($pendingCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Verification</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-red-600">{{ number_format($inactiveCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Suspended / Inactive</div>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <form method="GET" action="{{ route('admin.pgs') }}" id="adminPgFilterForm" class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Text -->
            <div class="lg:col-span-2 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                <input 
                    type="text" 
                    name="search" 
                    id="adminPgSearch" 
                    value="{{ request('search') }}" 
                    placeholder="Search by property, broker, city, landmark..." 
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition"
                >
            </div>

            <!-- Type Filter -->
            <div>
                <select name="type" id="adminPgType" onchange="document.getElementById('adminPgFilterForm').submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition">
                    <option value="">All Listing Types</option>
                    <option value="boys" {{ request('type') == 'boys' ? 'selected' : '' }}>Boys PG</option>
                    <option value="girls" {{ request('type') == 'girls' ? 'selected' : '' }}>Girls PG</option>
                    <option value="co-ed" {{ request('type') == 'co-ed' ? 'selected' : '' }}>Co-Living / Co-Ed</option>
                    @foreach($propertyTypes as $pt)
                        <option value="{{ $pt->slug }}" {{ request('type') == $pt->slug ? 'selected' : '' }}>{{ $pt->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- City Filter -->
            <div>
                <select name="city" id="adminPgCity" onchange="document.getElementById('adminPgFilterForm').submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter & Submit -->
            <div class="flex items-center gap-2">
                <select name="status" id="adminPgStatus" onchange="document.getElementById('adminPgFilterForm').submit()" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition">
                    <option value="">All Status</option>
                    <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending Review</option>
                    <option value="SUSPENDED" {{ request('status') == 'SUSPENDED' ? 'selected' : '' }}>Suspended / Inactive</option>
                </select>

                @if(request()->hasAny(['search', 'type', 'city', 'status']) && (request('search') || request('type') || request('city') || request('status')))
                    <a href="{{ route('admin.pgs') }}" class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center gap-1" title="Clear Filters">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="adminPgTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Broker / Owner</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">City / Area</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Price / Mo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="adminPgBody">
                    @forelse($properties as $property)
                        @php
                            $imgUrl = $property->primaryImage->image_url ?? ($property->images->first()->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80');
                            
                            $brokerName = $property->broker->profile->full_name ?? ($property->broker->name ?? ($property->broker->email ?? 'Direct / Admin'));
                            
                            $gender = strtolower($property->gender_preference ?? 'co-ed');
                            $typeClass = 'bg-purple-50 text-purple-600';
                            $typeLabel = 'CO-LIVING';
                            if ($gender === 'boys' || $gender === 'male') {
                                $typeClass = 'bg-blue-50 text-blue-600';
                                $typeLabel = 'BOYS';
                            } elseif ($gender === 'girls' || $gender === 'female') {
                                $typeClass = 'bg-pink-50 text-pink-600';
                                $typeLabel = 'GIRLS';
                            }
                            
                            $isVerified = ($property->verification_status === 'verified' && $property->status === 'active');
                            $isPending = ($property->verification_status === 'pending');
                        @endphp
                        <tr id="pg-row-{{ $property->id }}" class="admin-pg-row hover:bg-gray-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $imgUrl }}" alt="{{ $property->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-gray-100 shrink-0">
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 truncate max-w-xs pg-name">{{ $property->name }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $property->landmark ?? ($property->address ?? 'Verified Stay') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 broker-name">
                                {{ $brokerName }}
                                @if($property->broker && $property->broker->phone)
                                    <div class="text-[11px] text-gray-400 font-normal">{{ $property->broker->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 pg-city">
                                <div class="font-medium text-gray-900">{{ $property->city->name ?? 'City' }}</div>
                                <div class="text-xs text-gray-400">{{ $property->area->name ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $typeClass }} text-[11px] font-bold px-2.5 py-1 rounded-lg uppercase">
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $property->total_beds ?? 1 }} beds
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                ₹{{ number_format($property->monthly_rent) }}
                            </td>
                            <td class="px-6 py-4">
                                <span id="status-badge-{{ $property->id }}" class="status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase {{ $isVerified ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                    {{ $isVerified ? 'APPROVED' : ($isPending ? 'PENDING' : strtoupper($property->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Modal Action -->
                                    <button type="button" onclick="viewPropertyDetails('{{ $property->id }}')" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect cursor-pointer" title="View Property Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <!-- 1-Click Approve (if pending) -->
                                    @if($isPending)
                                        <button type="button" id="approve-btn-{{ $property->id }}" onclick="approvePgDirect('{{ $property->id }}')" class="px-2.5 py-1.5 bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs tap-effect flex items-center gap-1 cursor-pointer" title="Approve & Publish">
                                            <i class="fas fa-check text-[10px]"></i> Approve
                                        </button>
                                    @endif

                                    <!-- Status Toggle -->
                                    <button type="button" onclick="togglePgStatusDirect('{{ $property->id }}')" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center tap-effect cursor-pointer" title="Toggle Active/Inactive">
                                        <i class="fas fa-toggle-on text-xs"></i>
                                    </button>

                                    <!-- Delete -->
                                    <button type="button" onclick="deletePgDirect('{{ $property->id }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect cursor-pointer" title="Delete Property">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-2 text-xl">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="text-sm font-semibold text-gray-600">No properties found</div>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting your search criteria or add a new property listing.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Showing <span class="font-bold text-gray-900">{{ $properties->firstItem() }}</span> to <span class="font-bold text-gray-900">{{ $properties->lastItem() }}</span> of <span class="font-bold text-gray-900">{{ $properties->total() }}</span> properties
                </div>
                <div>
                    {{ $properties->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Mobile Cards View -->
    <div class="md:hidden space-y-4" id="adminPgMobileList">
        @forelse($properties as $property)
            @php
                $imgUrl = $property->primaryImage->image_url ?? ($property->images->first()->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80');
                $brokerName = $property->broker->profile->full_name ?? ($property->broker->name ?? ($property->broker->email ?? 'Direct / Admin'));
                $isVerified = ($property->verification_status === 'verified' && $property->status === 'active');
                $isPending = ($property->verification_status === 'pending');
            @endphp
            <div id="pg-mobile-card-{{ $property->id }}" class="admin-pg-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm space-y-3">
                <div class="flex gap-3">
                    <img src="{{ $imgUrl }}" alt="{{ $property->name }}" class="w-20 h-20 rounded-xl object-cover shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-1">
                            <h3 class="font-bold text-gray-900 text-sm truncate pg-name">{{ $property->name }}</h3>
                            <span id="mobile-status-badge-{{ $property->id }}" class="status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 {{ $isVerified ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                {{ $isVerified ? 'APPROVED' : ($isPending ? 'PENDING' : strtoupper($property->status)) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 pg-city">
                            <i class="fas fa-map-marker-alt text-brand"></i> {{ $property->city->name ?? 'City' }} {{ !empty($property->area->name) ? '• ' . $property->area->name : '' }}
                        </p>
                        <p class="text-xs text-gray-600 font-medium mt-0.5 truncate broker-name">Broker: {{ $brokerName }}</p>
                        <p class="text-xs font-bold text-gray-900 mt-1">₹{{ number_format($property->monthly_rent) }}/mo • {{ $property->total_beds }} beds</p>
                    </div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="viewPropertyDetails('{{ $property->id }}')" class="flex-1 bg-teal-50 text-teal-600 text-xs font-semibold py-2 rounded-lg text-center tap-effect">
                        <i class="fas fa-eye mr-1"></i> Details
                    </button>
                    @if($isPending)
                        <button type="button" id="mobile-approve-btn-{{ $property->id }}" onclick="approvePgDirect('{{ $property->id }}')" class="flex-1 bg-brand text-white text-xs font-semibold py-2 rounded-lg text-center tap-effect">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                    @endif
                    <button type="button" onclick="togglePgStatusDirect('{{ $property->id }}')" class="flex-1 bg-yellow-50 text-yellow-700 text-xs font-semibold py-2 rounded-lg tap-effect">
                        <i class="fas fa-toggle-on mr-1"></i> Status
                    </button>
                    <button type="button" onclick="deletePgDirect('{{ $property->id }}')" class="w-9 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect flex items-center justify-center">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center text-gray-400 border border-gray-100">
                <i class="fas fa-search text-2xl text-gray-300 mb-2"></i>
                <div class="text-sm font-semibold text-gray-600">No properties found</div>
            </div>
        @endforelse
    </div>

</div>

<!-- 1. Admin Add PG Modal -->
<div id="adminAddPgModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[92vh] overflow-y-auto shadow-2xl animate-scale-up">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Add New PG Property</h3>
                <p class="text-xs text-gray-500">List and publish verified accommodation directly to the catalog</p>
            </div>
            <button onclick="closeModal('adminAddPgModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200 cursor-pointer">
                <i class="fas fa-times text-gray-500 text-sm"></i>
            </button>
        </div>

        <form id="adminAddPgForm" onsubmit="handleCreatePg(event)" class="p-6 space-y-4">
            <!-- Name -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">PG / Property Name *</label>
                <input type="text" name="name" required placeholder="e.g. Royal Heights Luxury PG" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>

            <!-- Broker & City -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Assign Broker / Owner *</label>
                    <select name="broker_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-800">
                        @foreach($brokers as $b)
                            <option value="{{ $b->id }}">{{ $b->profile->full_name ?? $b->email }} ({{ $b->phone ?? 'No Phone' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">City *</label>
                    <select name="city_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-800">
                        @foreach($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Type, Beds & Rent -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Gender Type *</label>
                    <select name="gender_preference" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-800">
                        <option value="boys">Boys PG</option>
                        <option value="girls">Girls PG</option>
                        <option value="co-ed">Co-Living / Co-Ed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Total Beds *</label>
                    <input type="number" name="total_beds" value="20" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Rent / Mo (₹) *</label>
                    <input type="number" name="monthly_rent" value="8500" min="500" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <!-- Address & Landmark -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Street Address</label>
                    <input type="text" name="address" placeholder="e.g. Plot 45, Sector 62" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Landmark</label>
                    <input type="text" name="landmark" placeholder="e.g. Near Metro Station" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <!-- Image URL -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Primary Photo URL</label>
                <input type="url" name="image_url" value="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" placeholder="https://images.unsplash.com/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700">
            </div>

            <!-- Instant Publish Checkbox -->
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="instant_approve" value="1" checked id="instantApproveCheck" class="w-4 h-4 text-brand rounded border-gray-300 focus:ring-brand/50 accent-brand">
                <label for="instantApproveCheck" class="text-xs font-semibold text-gray-700 cursor-pointer select-none">
                    Instant Approve & Publish Live (Bypass pending review)
                </label>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('adminAddPgModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect cursor-pointer">Cancel</button>
                <button type="submit" id="submitAddPgBtn" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 cursor-pointer">Save & Publish</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Property Quick Details Modal -->
<div id="adminViewPgModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl animate-scale-up">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 id="modalPgName" class="text-xl font-bold text-gray-900">Property Details</h3>
                <p id="modalPgLocation" class="text-xs text-gray-500">Location</p>
            </div>
            <button onclick="closeModal('adminViewPgModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200 cursor-pointer">
                <i class="fas fa-times text-gray-500 text-sm"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 text-sm" id="modalPgBody">
            <div class="flex justify-center py-8">
                <i class="fas fa-circle-notch fa-spin text-brand text-2xl"></i>
            </div>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-3xl text-right">
            <button type="button" onclick="closeModal('adminViewPgModal')" class="px-5 py-2 bg-gray-900 text-white rounded-xl text-xs font-semibold tap-effect cursor-pointer">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Modal Helpers
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Dynamic Toast Messenger
    function showPgToast(message, type = 'success') {
        const toast = document.getElementById('pgToastNotification');
        const text = document.getElementById('pgToastMessage');
        const icon = document.getElementById('pgToastIcon');

        text.textContent = message;
        if (type === 'success') {
            icon.innerHTML = '<i class="fas fa-check-circle text-emerald-400 text-base"></i>';
        } else {
            icon.innerHTML = '<i class="fas fa-exclamation-circle text-red-400 text-base"></i>';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3200);
    }

    // 1-Click Approve PG
    async function approvePgDirect(propertyId) {
        if (!confirm('Verify and publish this property listing live?')) return;

        try {
            const res = await fetch(`/admin/pgs/${propertyId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showPgToast(data.message, 'success');

                // Update badge
                const badge = document.getElementById(`status-badge-${propertyId}`);
                if (badge) {
                    badge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-emerald-100 text-emerald-700';
                    badge.textContent = 'APPROVED';
                }
                const mobileBadge = document.getElementById(`mobile-status-badge-${propertyId}`);
                if (mobileBadge) {
                    mobileBadge.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-emerald-100 text-emerald-700';
                    mobileBadge.textContent = 'APPROVED';
                }

                // Remove approve buttons
                const btn = document.getElementById(`approve-btn-${propertyId}`);
                if (btn) btn.remove();
                const mBtn = document.getElementById(`mobile-approve-btn-${propertyId}`);
                if (mBtn) mBtn.remove();
            } else {
                showPgToast(data.message || 'Error approving property', 'error');
            }
        } catch (err) {
            console.error(err);
            showPgToast('Network error while approving property', 'error');
        }
    }

    // 1-Click Toggle Active / Inactive Status
    async function togglePgStatusDirect(propertyId) {
        try {
            const res = await fetch(`/admin/pgs/${propertyId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showPgToast(data.message, 'success');

                const badge = document.getElementById(`status-badge-${propertyId}`);
                const mobileBadge = document.getElementById(`mobile-status-badge-${propertyId}`);

                if (data.is_active) {
                    if (badge) {
                        badge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-emerald-100 text-emerald-700';
                        badge.textContent = 'APPROVED';
                    }
                    if (mobileBadge) {
                        mobileBadge.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-emerald-100 text-emerald-700';
                        mobileBadge.textContent = 'APPROVED';
                    }
                } else {
                    if (badge) {
                        badge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-red-100 text-red-700';
                        badge.textContent = 'INACTIVE';
                    }
                    if (mobileBadge) {
                        mobileBadge.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-red-100 text-red-700';
                        mobileBadge.textContent = 'INACTIVE';
                    }
                }
            } else {
                showPgToast(data.message || 'Failed to update status', 'error');
            }
        } catch (err) {
            console.error(err);
            showPgToast('Network error while updating status', 'error');
        }
    }

    // 1-Click Delete Property
    async function deletePgDirect(propertyId) {
        if (!confirm('Are you sure you want to permanently delete this property listing?')) return;

        try {
            const res = await fetch(`/admin/pgs/${propertyId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showPgToast(data.message, 'success');

                const row = document.getElementById(`pg-row-${propertyId}`);
                if (row) {
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.95)';
                    setTimeout(() => row.remove(), 250);
                }

                const card = document.getElementById(`pg-mobile-card-${propertyId}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
                }
            } else {
                showPgToast(data.message || 'Failed to delete property', 'error');
            }
        } catch (err) {
            console.error(err);
            showPgToast('Network error while deleting property', 'error');
        }
    }

    // Handle Add PG Form Submit (AJAX)
    async function handleCreatePg(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('submitAddPgBtn');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1"></i> Saving...';

        try {
            const res = await fetch('{{ route('admin.pgs.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showPgToast(data.message || 'Property added successfully!', 'success');
                closeModal('adminAddPgModal');
                form.reset();

                // Reload after short delay to reflect in list
                setTimeout(() => window.location.reload(), 800);
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save & Publish';
                showPgToast(data.message || 'Failed to create property', 'error');
            }
        } catch (err) {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save & Publish';
            showPgToast('Error connecting to server. Please try again.', 'error');
        }
    }

    // View Property Details in Modal
    async function viewPropertyDetails(propertyId) {
        openModal('adminViewPgModal');
        const nameEl = document.getElementById('modalPgName');
        const locEl = document.getElementById('modalPgLocation');
        const bodyEl = document.getElementById('modalPgBody');

        bodyEl.innerHTML = `
            <div class="flex justify-center py-8">
                <i class="fas fa-circle-notch fa-spin text-brand text-2xl"></i>
            </div>
        `;

        try {
            const res = await fetch(`/admin/pgs/${propertyId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (res.ok && data.success && data.property) {
                const p = data.property;
                nameEl.textContent = p.name;
                locEl.textContent = `${p.city ? p.city.name : 'City'} • ${p.address || ''}`;

                const imgUrl = p.primary_image ? p.primary_image.image_url : (p.images && p.images[0] ? p.images[0].image_url : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80');
                const brokerName = p.broker ? (p.broker.profile ? p.broker.profile.full_name : p.broker.name || p.broker.email) : 'Direct Partner';

                bodyEl.innerHTML = `
                    <div class="space-y-4">
                        <img src="${imgUrl}" alt="${p.name}" class="w-full h-48 rounded-2xl object-cover border border-gray-100 shadow-xs">
                        
                        <div class="grid grid-cols-2 gap-3 bg-gray-50 p-4 rounded-2xl">
                            <div>
                                <span class="text-xs text-gray-500 block font-medium">Monthly Rent</span>
                                <span class="text-base font-extrabold text-gray-900">₹${Number(p.monthly_rent).toLocaleString()}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block font-medium">Security Deposit</span>
                                <span class="text-base font-extrabold text-gray-900">₹${Number(p.security_deposit || (p.monthly_rent * 2)).toLocaleString()}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block font-medium">Total Capacity</span>
                                <span class="text-sm font-bold text-gray-900">${p.total_beds || 1} Beds</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block font-medium">Gender Preference</span>
                                <span class="text-sm font-bold uppercase text-brand">${p.gender_preference || 'Co-Ed'}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider block mb-1">Broker & Contact</span>
                            <div class="text-xs text-gray-800 font-semibold">${brokerName}</div>
                            <div class="text-xs text-gray-500">${p.broker ? (p.broker.email || '') + ' ' + (p.broker.phone || '') : ''}</div>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider block mb-1">Description</span>
                            <p class="text-xs text-gray-600 leading-relaxed">${p.description || 'No description provided.'}</p>
                        </div>
                    </div>
                `;
            }
        } catch (err) {
            console.error(err);
            bodyEl.innerHTML = `<div class="text-center text-red-500 text-xs py-4">Failed to load property details.</div>`;
        }
    }
</script>
@endpush
