@extends('broker.layouts.app')

@section('title', 'My Properties')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Properties Management</h1>
        <p class="text-sm text-gray-500">Manage, edit, and toggle status for your listed properties (PGs, Flats, Commercial, Plots).</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i> Add New Property
        </a>
    </div>
</header>

<!-- Floating Toast Notification -->
<div id="pgToast" class="fixed top-20 right-4 md:right-8 z-50 hidden transition-all duration-300 transform translate-y-2">
    <div class="bg-gray-900/95 text-white px-4 py-3 rounded-2xl shadow-2xl backdrop-blur-md flex items-center gap-3 border border-white/10 min-w-[280px]">
        <div id="toastIconBox" class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
            <i id="toastIcon" class="fas fa-check"></i>
        </div>
        <div>
            <p id="toastTitle" class="text-xs font-bold leading-tight">Status Updated</p>
            <p id="toastMessage" class="text-[11px] text-gray-300 leading-tight mt-0.5">Listing status has been changed.</p>
        </div>
    </div>
</div>

<div class="p-4 md:p-8 space-y-6">
    <!-- Mobile Add Button -->
    <div class="lg:hidden">
        <a href="{{ route('user.list-property') }}" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Add New Property
        </a>
    </div>

    <!-- Live Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 font-medium">Total Properties</div>
                <div id="statTotalProps" class="text-2xl font-bold text-gray-900 mt-1">{{ $totalProperties }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand flex items-center justify-center text-lg">
                <i class="fas fa-building"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 font-medium">Active Listings</div>
                <div id="statActiveProps" class="text-2xl font-bold text-emerald-600 mt-1">{{ $activeProperties }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 font-medium">Total Bed Capacity</div>
                <div class="text-2xl font-bold text-brand mt-1">{{ $totalBeds }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                <i class="fas fa-bed"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 font-medium">Available Beds</div>
                <div class="text-2xl font-bold text-amber-600 mt-1">{{ $availableBeds }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
    </div>

    <!-- ================= LISTING TYPE TABS BAR (All, PG, Flat, Commercial, Land) ================= -->
    <div class="bg-white rounded-2xl p-2.5 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <!-- Tab: All Properties -->
            <a href="{{ request()->fullUrlWithQuery(['type' => 'all', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ ($currentType === 'all' || empty($currentType)) ? 'bg-brand text-white shadow-md shadow-brand/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-layer-group text-xs sm:text-sm"></i>
                <span>All Properties</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ ($currentType === 'all' || empty($currentType)) ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($tabCounts['all'] ?? $totalProperties) }}
                </span>
            </a>

            <!-- Tab: PG / Hostel -->
            <a href="{{ request()->fullUrlWithQuery(['type' => 'pg-hostel', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ ($currentType === 'pg-hostel' || $currentType === 'pg') ? 'bg-brand text-white shadow-md shadow-brand/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-bed text-xs sm:text-sm"></i>
                <span>PG / Hostel</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ ($currentType === 'pg-hostel' || $currentType === 'pg') ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($tabCounts['pg-hostel'] ?? 0) }}
                </span>
            </a>

            <!-- Tab: Flat / House -->
            <a href="{{ request()->fullUrlWithQuery(['type' => 'flat-apartment', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ ($currentType === 'flat-apartment' || $currentType === 'flat') ? 'bg-brand text-white shadow-md shadow-brand/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-building text-xs sm:text-sm"></i>
                <span>Flat / House</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ ($currentType === 'flat-apartment' || $currentType === 'flat') ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($tabCounts['flat-apartment'] ?? 0) }}
                </span>
            </a>

            <!-- Tab: Commercial -->
            <a href="{{ request()->fullUrlWithQuery(['type' => 'commercial', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ ($currentType === 'commercial') ? 'bg-brand text-white shadow-md shadow-brand/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-store text-xs sm:text-sm"></i>
                <span>Commercial</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ ($currentType === 'commercial') ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($tabCounts['commercial'] ?? 0) }}
                </span>
            </a>

            <!-- Tab: Land / Plot (if exists or active) -->
            @if(($tabCounts['land-plot'] ?? 0) > 0 || $currentType === 'land-plot')
            <a href="{{ request()->fullUrlWithQuery(['type' => 'land-plot', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ ($currentType === 'land-plot') ? 'bg-brand text-white shadow-md shadow-brand/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-mountain text-xs sm:text-sm"></i>
                <span>Land / Plot</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ ($currentType === 'land-plot') ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($tabCounts['land-plot'] ?? 0) }}
                </span>
            </a>
            @endif
        </div>
    </div>

    <!-- Search & Real-Time Filters -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input id="pgSearchInput" onkeyup="filterPGs()" type="text" placeholder="Search by property name, city, sector, address..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
            </div>
            <select id="typeFilter" onchange="filterPGs()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                <option value="">All Genders / Sub-types</option>
                <option value="BOYS">Boys PG</option>
                <option value="GIRLS">Girls PG</option>
                <option value="CO-ED">Co-living / Co-Ed</option>
            </select>
            <select id="statusFilter" onchange="filterPGs()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 transition">
                <option value="">All Statuses</option>
                <option value="ACTIVE">Active & Listed</option>
                <option value="DRAFT">Draft / Inactive</option>
            </select>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="pgTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Property Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Price / Rent</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Capacity / Space</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status / Occupancy</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Listing Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="pgTableBody">
                    @forelse($properties as $property)
                        @php
                            $gender = strtolower($property->gender_preference ?? 'boys');
                            $genderType = in_array($gender, ['co-ed', 'coed', 'unisex']) ? 'CO-ED' : ($gender === 'girls' ? 'GIRLS' : 'BOYS');
                            $genderBadgeClass = $genderType === 'GIRLS' ? 'bg-pink-50 text-pink-600' : ($genderType === 'CO-ED' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600');
                            
                            $isActive = ($property->status === 'active' && $property->is_active);
                            $statusType = $isActive ? 'ACTIVE' : 'DRAFT';
                            $statusBadgeClass = $isActive ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200';
                            
                            $ptSlug = strtolower($property->propertyType?->slug ?? '');
                            $isComm = $property->property_category === 'commercial' || in_array($ptSlug, ['commercial', 'office', 'shop', 'warehouse']);
                            $isFlat = $property->property_category === 'residential' && in_array($ptSlug, ['flat-apartment', 'flat', 'apartment', 'house-villa', 'house', 'villa', 'builder-floor']);
                            $isLand = $property->property_category === 'land-plot' || in_array($ptSlug, ['land-plot', 'plot', 'land']);
                            $isPg = !$isComm && !$isFlat && !$isLand;

                            $prefix = $isComm ? 'COM' : ($isFlat ? 'FLAT' : ($isLand ? 'PLOT' : 'PG'));

                            $tBeds = max(1, (int) $property->total_beds);
                            $aBeds = (int) $property->available_beds;
                            $occBeds = max(0, $tBeds - $aBeds);
                            $occPct = min(100, round(($occBeds / $tBeds) * 100));

                            $imgSrc = $property->primaryImage?->image_url ?? $property->images->first()?->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80';
                        @endphp
                        <tr class="pg-row hover:bg-gray-50/70 transition" id="pg-row-{{ $property->id }}" data-type="{{ $genderType }}" data-status="{{ $statusType }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $imgSrc }}" alt="{{ $property->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-gray-100">
                                    <div>
                                        <a href="{{ url('/list-property?edit_id=' . $property->id) }}" class="font-bold text-gray-900 pg-name hover:text-brand transition block">{{ $property->name }}</a>
                                        <div class="text-xs text-gray-400 font-mono">#{{ $prefix }}-{{ substr($property->id, 0, 8) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 pg-location">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-map-marker-alt text-brand text-xs"></i>
                                    <span class="truncate max-w-[200px]">{{ $property->area?->name ?? $property->landmark ?? 'Prime Zone' }}, {{ $property->city?->name ?? 'Delhi NCR' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($isComm)
                                    <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-2.5 py-1 rounded-lg">Commercial</span>
                                @elseif($isFlat)
                                    <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-bold px-2.5 py-1 rounded-lg">Flat / House</span>
                                @elseif($isLand)
                                    <span class="bg-teal-50 text-teal-700 border border-teal-200 text-xs font-bold px-2.5 py-1 rounded-lg">Land / Plot</span>
                                @else
                                    <span class="{{ $genderBadgeClass }} text-xs font-bold px-2.5 py-1 rounded-lg">{{ $genderType }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                @if($property->is_sale)
                                    <span class="text-amber-600">{{ $property->display_price_formatted }}</span>
                                    <div class="text-[10px] text-gray-400 font-semibold uppercase">For Sale</div>
                                @else
                                    ₹{{ number_format($property->monthly_rent, 0) }}<span class="text-[11px] font-normal text-gray-500">/mo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                @if($isComm)
                                    <span class="font-bold text-gray-900">Commercial Space</span>
                                    <div class="text-[11px] text-gray-500">{{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' sq ft' : 'Ready Office / Shop' }}</div>
                                @elseif($isFlat)
                                    <span class="font-bold text-gray-900">{{ $property->bhk_type ?: 'Apartment / House' }}</span>
                                    <div class="text-[11px] text-gray-500">{{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' sq ft' : 'Full House' }}</div>
                                @elseif($isLand)
                                    <span class="font-bold text-gray-900">Land / Plot</span>
                                    <div class="text-[11px] text-gray-500">{{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' Sq. Yards' : 'Plot Layout' }}</div>
                                @else
                                    {{ $property->total_beds }} beds
                                    <div class="text-[11px] {{ (int)$property->available_beds > 0 ? 'text-emerald-600 font-semibold' : 'text-rose-600 font-bold' }}">
                                        {{ (int)$property->available_beds > 0 ? $property->available_beds . ' available' : '0 available (Full)' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($isComm || $isFlat || $isLand)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fas fa-check-circle text-[10px]"></i> Active Listing
                                    </span>
                                @else
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-bold text-gray-700">{{ $occPct }}%</span>
                                            <span class="text-[10px] text-gray-400">({{ $occBeds }}/{{ $tBeds }})</span>
                                        </div>
                                        <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-brand rounded-full transition-all duration-300" style="width: {{ $occPct }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <!-- 1-Click Status Change Toggle Switch -->
                                <div class="inline-flex flex-col items-center gap-1.5">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               id="statusToggle-{{ $property->id }}" 
                                               onchange="toggleListingStatus('{{ $property->id }}', this)" 
                                               class="sr-only peer" 
                                               {{ $isActive ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>
                                    <span id="statusBadge-{{ $property->id }}" class="{{ $statusBadgeClass }} text-[10px] font-bold px-2 py-0.5 rounded-md border tracking-wide">
                                        {{ $statusType }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ url('/list-property?edit_id=' . $property->id) }}" 
                                       class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect transition" 
                                       title="Edit on List Property Page">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <a href="{{ route('user.location') }}" 
                                       class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 flex items-center justify-center tap-effect transition" 
                                       title="View on Map">
                                        <i class="fas fa-map text-xs"></i>
                                    </a>
                                    <button onclick="deleteProperty('{{ $property->id }}', '{{ addslashes($property->name) }}')" 
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect transition" 
                                            title="Delete Property">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fas fa-building-circle-xmark"></i>
                                </div>
                                <p class="font-bold text-gray-700">No PG Properties Listed Yet</p>
                                <p class="text-xs text-gray-400 mt-1">Click "Add New PG" above to publish your first PG stay.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards List -->
    <div class="md:hidden space-y-4" id="pgMobileCards">
        @forelse($properties as $property)
            @php
                $gender = strtolower($property->gender_preference ?? 'boys');
                $genderType = in_array($gender, ['co-ed', 'coed', 'unisex']) ? 'CO-ED' : ($gender === 'girls' ? 'GIRLS' : 'BOYS');
                $genderBadgeClass = $genderType === 'GIRLS' ? 'bg-pink-50 text-pink-600' : ($genderType === 'CO-ED' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600');
                
                $isActive = ($property->status === 'active' && $property->is_active);
                $statusType = $isActive ? 'ACTIVE' : 'DRAFT';
                $statusBadgeClass = $isActive ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200';
                
                $ptSlug = strtolower($property->propertyType?->slug ?? '');
                $isComm = $property->property_category === 'commercial' || in_array($ptSlug, ['commercial', 'office', 'shop', 'warehouse']);
                $isFlat = $property->property_category === 'residential' && in_array($ptSlug, ['flat-apartment', 'flat', 'apartment', 'house-villa', 'house', 'villa', 'builder-floor']);
                $isLand = $property->property_category === 'land-plot' || in_array($ptSlug, ['land-plot', 'plot', 'land']);
                $isPg = !$isComm && !$isFlat && !$isLand;

                $prefix = $isComm ? 'COM' : ($isFlat ? 'FLAT' : ($isLand ? 'PLOT' : 'PG'));

                $tBeds = max(1, (int) $property->total_beds);
                $aBeds = (int) $property->available_beds;
                $occBeds = max(0, $tBeds - $aBeds);
                $occPct = min(100, round(($occBeds / $tBeds) * 100));

                $imgSrc = $property->primaryImage?->image_url ?? $property->images->first()?->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80';
            @endphp
            <div class="pg-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm" id="pg-card-{{ $property->id }}" data-type="{{ $genderType }}" data-status="{{ $statusType }}">
                <div class="flex gap-3 mb-3">
                    <img src="{{ $imgSrc }}" class="w-20 h-20 rounded-xl object-cover shrink-0 border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <a href="{{ url('/list-property?edit_id=' . $property->id) }}" class="font-bold text-gray-900 text-sm truncate pg-name hover:text-brand transition block">{{ $property->name }}</a>
                            <button type="button" 
                                    onclick="toggleListingStatus('{{ $property->id }}')" 
                                    id="mobStatusBadge-{{ $property->id }}" 
                                    class="{{ $statusBadgeClass }} text-[10px] font-bold px-2 py-0.5 rounded border tap-effect">
                                {{ $statusType }}
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 truncate pg-location"><i class="fas fa-map-marker-alt text-brand"></i> {{ $property->area?->name ?? 'Zone' }}, {{ $property->city?->name ?? 'City' }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            @if($isComm)
                                <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded">Commercial</span>
                            @elseif($isFlat)
                                <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded">Flat / House</span>
                            @elseif($isLand)
                                <span class="bg-teal-50 text-teal-700 text-[10px] font-bold px-2 py-0.5 rounded">Land / Plot</span>
                            @else
                                <span class="{{ $genderBadgeClass }} text-[10px] font-bold px-2 py-0.5 rounded">{{ $genderType }}</span>
                            @endif
                            <span class="text-xs font-bold text-gray-900">
                                @if($property->is_sale)
                                    <span class="text-amber-600">{{ $property->display_price_formatted }}</span>
                                @else
                                    ₹{{ number_format($property->monthly_rent, 0) }}/mo
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    @if($isComm || $isFlat || $isLand)
                        <div class="flex justify-between text-[11px] py-1 bg-gray-50 px-2.5 rounded-lg border border-gray-100">
                            <span class="text-gray-500 font-medium">Specification</span>
                            <span class="font-bold text-gray-900">
                                @if($isComm)
                                    {{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' sq ft' : 'Ready Commercial Space' }}
                                @elseif($isFlat)
                                    {{ $property->bhk_type ?: 'Apartment / House' }} ({{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' sq ft' : 'Full Unit' }})
                                @else
                                    {{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' Sq. Yds' : 'Plot Land' }}
                                @endif
                            </span>
                        </div>
                    @else
                        <div class="flex justify-between text-[11px] mb-1">
                            <span class="text-gray-500">Occupancy</span>
                            <span class="font-bold text-brand">{{ $occPct }}% ({{ $occBeds }}/{{ $tBeds }} beds)</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-brand rounded-full" style="width: {{ $occPct }}%"></div>
                        </div>
                    @endif
                </div>
                <div class="flex gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ url('/list-property?edit_id=' . $property->id) }}" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold py-2 rounded-lg tap-effect text-center flex items-center justify-center gap-1"><i class="fas fa-edit"></i> Edit</a>
                    <button onclick="toggleListingStatus('{{ $property->id }}')" class="flex-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-toggle-on mr-1"></i> Status</button>
                    <button onclick="deleteProperty('{{ $property->id }}', '{{ addslashes($property->name) }}')" class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-trash mr-1"></i> Delete</button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-500">
                <p class="font-bold">No Properties Listed</p>
                <p class="text-xs text-gray-400 mt-1">Tap "Add New Property" to get started.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- ===================== ADD PG MODAL ===================== -->
<div id="addPgModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-bold text-gray-900">List New PG Property</h3>
                <p class="text-xs text-gray-500">Fill in details to publish your PG property live</p>
            </div>
            <button onclick="closeModal('addPgModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <form id="addPgForm" onsubmit="handleAddPgSubmit(event)" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">PG Name *</label>
                <input type="text" name="name" placeholder="e.g. Royal Living Luxury PG" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">City *</label>
                    <select name="city_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Area / Locality *</label>
                    <input type="text" name="area_name" placeholder="e.g. Sector 62 or Indiranagar" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Gender Type *</label>
                    <select name="gender_preference" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="boys">Boys PG</option>
                        <option value="girls">Girls PG</option>
                        <option value="co-ed">Co-Living / Co-Ed</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Monthly Rent (₹) *</label>
                    <input type="number" name="monthly_rent" placeholder="8500" min="500" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Security Deposit (₹)</label>
                    <input type="number" name="security_deposit" placeholder="17000" min="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Total Beds *</label>
                    <input type="number" name="total_beds" placeholder="20" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Available Beds Now *</label>
                    <input type="number" name="available_beds" placeholder="5" min="0" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Initial Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="active">Active (Publish Live)</option>
                        <option value="draft">Draft (Private)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Full Address *</label>
                <textarea name="address" rows="2" placeholder="e.g. Plot 42, 5th Cross, Near Metro Station" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Photo URL / Image File</label>
                <input type="text" name="image_url" placeholder="https://images.unsplash.com/photo-..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>

            <div class="p-6 -mx-6 -mb-6 border-t border-gray-100 flex gap-3 sticky bottom-0 bg-white">
                <button type="button" onclick="closeModal('addPgModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect transition">Cancel</button>
                <button type="submit" id="saveAddPgBtn" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition flex items-center justify-center gap-2">
                    <span>Publish PG</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== EDIT PG MODAL ===================== -->
<div id="editPgModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Edit Property Details</h3>
                <p class="text-xs text-gray-500">Update listing information or status</p>
            </div>
            <button onclick="closeModal('editPgModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <form id="editPgForm" onsubmit="handleEditPgSubmit(event)" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="editPgId" name="property_id">
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">PG Name *</label>
                <input id="editPgName" type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Monthly Rent (₹) *</label>
                    <input id="editPgRent" type="number" name="monthly_rent" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Deposit (₹)</label>
                    <input id="editPgDeposit" type="number" name="security_deposit" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Total Beds *</label>
                    <input id="editPgBeds" type="number" name="total_beds" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Available Beds *</label>
                    <input id="editPgAvailBeds" type="number" name="available_beds" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Gender Type *</label>
                    <select id="editPgGender" name="gender_preference" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="boys">Boys PG</option>
                        <option value="girls">Girls PG</option>
                        <option value="co-ed">Co-living / Co-Ed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Listing Status *</label>
                    <select id="editPgStatus" name="status" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50">
                        <option value="active">Active & Listed</option>
                        <option value="draft">Draft / Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Address *</label>
                <textarea id="editPgAddress" name="address" rows="2" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('editPgModal')" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl tap-effect">Cancel</button>
                <button type="submit" id="saveEditPgBtn" class="flex-1 bg-brand text-white font-bold py-3 rounded-xl tap-effect shadow-md hover:bg-brand-dark transition">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = (typeof window.getBrokerCsrfToken === 'function' ? window.getBrokerCsrfToken() : (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'));

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    // Toast Helper
    function showToast(title, message, isSuccess = true) {
        const toast = document.getElementById('pgToast');
        const tTitle = document.getElementById('toastTitle');
        const tMsg = document.getElementById('toastMessage');
        const tIcon = document.getElementById('toastIcon');
        const tIconBox = document.getElementById('toastIconBox');

        if (!toast) return;

        tTitle.innerText = title;
        tMsg.innerText = message;

        if (isSuccess) {
            tIconBox.className = 'w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0';
            tIcon.className = 'fas fa-check';
        } else {
            tIconBox.className = 'w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0';
            tIcon.className = 'fas fa-exclamation';
        }

        toast.classList.remove('hidden', 'translate-y-4', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }, 3500);
    }

    // 1-Click Listing Status Toggle Handler
    async function toggleListingStatus(propertyId, checkboxElem = null) {
        const token = (typeof window.getBrokerCsrfToken === 'function' ? window.getBrokerCsrfToken() : csrfToken);

        try {
            const resp = await fetch(`/broker/pgs/${propertyId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    _token: token
                })
            });

            const data = await resp.json();
            if (data.success) {
                const isNowActive = data.is_active;
                const statusStr = data.status; // 'ACTIVE' or 'DRAFT'

                // Update Desktop Row Data and Badges
                const row = document.getElementById(`pg-row-${propertyId}`);
                if (row) {
                    row.setAttribute('data-status', statusStr);
                    const badge = document.getElementById(`statusBadge-${propertyId}`);
                    if (badge) {
                        badge.innerText = statusStr;
                        badge.className = isNowActive 
                            ? 'bg-emerald-100 text-emerald-800 border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-md border tracking-wide'
                            : 'bg-amber-100 text-amber-800 border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-md border tracking-wide';
                    }
                    const toggleInput = document.getElementById(`statusToggle-${propertyId}`);
                    if (toggleInput) {
                        toggleInput.checked = isNowActive;
                    }
                }

                // Update Mobile Card
                const card = document.getElementById(`pg-card-${propertyId}`);
                if (card) {
                    card.setAttribute('data-status', statusStr);
                    const mobBadge = document.getElementById(`mobStatusBadge-${propertyId}`);
                    if (mobBadge) {
                        mobBadge.innerText = statusStr;
                        mobBadge.className = isNowActive 
                            ? 'bg-emerald-100 text-emerald-800 border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded border tap-effect'
                            : 'bg-amber-100 text-amber-800 border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded border tap-effect';
                    }
                }

                // Update Live Stat Counter
                recalculateActiveCounter();

                showToast('Listing Status Changed', data.message || `Property is now ${statusStr}`, true);
            } else {
                if (checkboxElem) checkboxElem.checked = !checkboxElem.checked;
                showToast('Update Failed', data.message || 'Could not change listing status.', false);
            }
        } catch (err) {
            if (checkboxElem) checkboxElem.checked = !checkboxElem.checked;
            showToast('Network Error', 'Failed to communicate with server.', false);
        }
    }

    function recalculateActiveCounter() {
        const activeRows = document.querySelectorAll('.pg-row[data-status="ACTIVE"]');
        const activeStat = document.getElementById('statActiveProps');
        if (activeStat) {
            activeStat.innerText = activeRows.length;
        }
    }

    // Open Edit Modal with Real Data
    async function openEditModalFromRow(propertyId) {
        try {
            const resp = await fetch(`/broker/pgs/${propertyId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await resp.json();
            if (data.success && data.property) {
                const p = data.property;
                document.getElementById('editPgId').value = p.id;
                document.getElementById('editPgName').value = p.name;
                document.getElementById('editPgRent').value = Math.round(p.monthly_rent);
                document.getElementById('editPgDeposit').value = Math.round(p.security_deposit || 0);
                document.getElementById('editPgBeds').value = p.total_beds;
                document.getElementById('editPgAvailBeds').value = p.available_beds;
                document.getElementById('editPgGender').value = p.gender_preference || 'boys';
                document.getElementById('editPgStatus').value = p.status || (p.is_active ? 'active' : 'draft');
                document.getElementById('editPgAddress').value = p.address || '';
                
                openModal('editPgModal');
            }
        } catch (err) {
            showToast('Error', 'Failed to load property details.', false);
        }
    }

    // Handle Edit PG Submit
    async function handleEditPgSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const propertyId = document.getElementById('editPgId').value;
        const btn = document.getElementById('saveEditPgBtn');
        const originalText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i> Saving...`;

        try {
            const formData = new FormData(form);
            const resp = await fetch(`/broker/pgs/${propertyId}/update`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await resp.json();
            if (data.success) {
                showToast('Success', data.message || 'Property updated successfully!');
                closeModal('editPgModal');
                setTimeout(() => window.location.reload(), 600);
            } else {
                showToast('Error', data.message || 'Validation failed.', false);
            }
        } catch (err) {
            showToast('Error', 'Failed to update property.', false);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // Handle Add PG Submit
    async function handleAddPgSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('saveAddPgBtn');
        const originalText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i> Publishing...`;

        try {
            const formData = new FormData(form);
            const resp = await fetch(`{{ route('broker.pgs.store') }}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await resp.json();
            if (data.success) {
                showToast('Published!', data.message || 'PG added successfully!');
                closeModal('addPgModal');
                setTimeout(() => window.location.reload(), 600);
            } else {
                showToast('Error', data.message || 'Could not add PG.', false);
            }
        } catch (err) {
            showToast('Error', 'Failed to submit PG property.', false);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // Delete Property Handler
    async function deleteProperty(propertyId, propertyName) {
        if (!confirm(`Are you sure you want to delete "${propertyName}"? This will archive the listing.`)) {
            return;
        }

        try {
            const resp = await fetch(`/broker/pgs/${propertyId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await resp.json();
            if (data.success) {
                const row = document.getElementById(`pg-row-${propertyId}`);
                if (row) row.remove();
                const card = document.getElementById(`pg-card-${propertyId}`);
                if (card) card.remove();

                showToast('Deleted', data.message || 'Property removed.', true);
                recalculateActiveCounter();
            } else {
                showToast('Error', data.message || 'Failed to delete property.', false);
            }
        } catch (err) {
            showToast('Error', 'Could not delete property.', false);
        }
    }

    // Search and Filters
    function filterPGs() {
        const search = (document.getElementById('pgSearchInput')?.value || '').toLowerCase().trim();
        const type = document.getElementById('typeFilter')?.value || '';
        const status = document.getElementById('statusFilter')?.value || '';

        document.querySelectorAll('.pg-row, .pg-card').forEach(el => {
            const name = el.querySelector('.pg-name')?.textContent.toLowerCase() || '';
            const loc = el.querySelector('.pg-location')?.textContent.toLowerCase() || '';
            const elType = el.getAttribute('data-type') || '';
            const elStatus = el.getAttribute('data-status') || '';

            const matchSearch = !search || name.includes(search) || loc.includes(search);
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
