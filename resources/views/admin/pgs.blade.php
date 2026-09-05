@extends('admin.layouts.app')

@section('title', 'Manage All Properties')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">All Properties Management</h1>
        <p class="text-sm text-gray-500">{{ $totalCount }} properties listed across {{ $cities->count() }} major cities</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('user.list-property') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2 cursor-pointer">
            <i class="fas fa-plus text-sm"></i> Add New Property
        </a>
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
        <a href="{{ route('user.list-property') }}" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2 cursor-pointer">
            <i class="fas fa-plus"></i> Add New Property
        </a>
    </div>

    <!-- ================= LISTING TYPE TABS BAR ================= -->
    <div class="bg-white rounded-2xl p-2.5 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <!-- Tab: All Properties -->
            <a href="{{ request()->fullUrlWithQuery(['type' => 'all', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ ($currentType === 'all' || empty($currentType)) ? 'bg-brand text-white shadow-md shadow-brand/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-layer-group text-xs sm:text-sm"></i>
                <span>All Properties</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ ($currentType === 'all' || empty($currentType)) ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($tabCounts['all'] ?? $totalCount) }}
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
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Listings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-amber-600">{{ number_format($recommendedCount ?? 0) }}</div>
            <div class="text-xs text-amber-700 mt-1 font-bold flex items-center gap-1">
                <i class="fas fa-star text-amber-500 text-[10px]"></i> Recommended
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($approvedCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active & Approved</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-amber-600">{{ number_format($pendingCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Verification</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm col-span-2 sm:col-span-1">
            <div class="text-2xl md:text-3xl font-extrabold text-red-600">{{ number_format($inactiveCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Suspended / Inactive</div>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <form method="GET" action="{{ route('admin.pgs') }}" id="adminPgFilterForm" class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        @if(request('type'))
            <input type="hidden" name="type" value="{{ request('type') }}">
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
            <!-- Search Text -->
            <div class="lg:col-span-2 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                <input 
                    type="text" 
                    name="search" 
                    id="adminPgSearch" 
                    value="{{ request('search') }}" 
                    placeholder="Search property, broker, city..." 
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition"
                >
            </div>

            <!-- Gender Preference / Sub-Type Filter -->
            <div>
                <select name="type" id="adminPgType" onchange="document.getElementById('adminPgFilterForm').submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition font-medium">
                    <option value="">All Types</option>
                    <option value="pg-hostel" {{ request('type') == 'pg-hostel' ? 'selected' : '' }}>🏠 PG / Hostel</option>
                    <option value="flat-apartment" {{ request('type') == 'flat-apartment' ? 'selected' : '' }}>🏢 Flat / House</option>
                    <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>🏪 Commercial</option>
                    <option value="boys" {{ request('type') == 'boys' ? 'selected' : '' }}>👦 Boys Stays</option>
                    <option value="girls" {{ request('type') == 'girls' ? 'selected' : '' }}>👧 Girls Stays</option>
                    <option value="co-ed" {{ request('type') == 'co-ed' ? 'selected' : '' }}>👫 Co-Living / Co-Ed</option>
                    @foreach($propertyTypes as $pt)
                        <option value="{{ $pt->slug }}" {{ request('type') == $pt->slug ? 'selected' : '' }}>{{ $pt->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Recommended Filter -->
            <div>
                <select name="recommended" id="adminPgRecommended" onchange="document.getElementById('adminPgFilterForm').submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition font-medium">
                    <option value="">All Recommendations</option>
                    <option value="1" {{ request('recommended') === '1' ? 'selected' : '' }}>⭐ Recommended</option>
                    <option value="0" {{ request('recommended') === '0' ? 'selected' : '' }}>☆ Not Recommended</option>
                </select>
            </div>

            <!-- Tag / Badge Filter -->
            <div>
                <select name="tag" id="adminPgTag" onchange="document.getElementById('adminPgFilterForm').submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition font-medium">
                    <option value="">All Badges</option>
                    <option value="Popular" {{ request('tag') == 'Popular' ? 'selected' : '' }}>🔥 Popular</option>
                    <option value="Verified" {{ request('tag') == 'Verified' ? 'selected' : '' }}>🛡️ Verified</option>
                    <option value="Guest Favourite" {{ request('tag') == 'Guest Favourite' ? 'selected' : '' }}>❤️ Guest Favourite</option>
                    <option value="Trending" {{ request('tag') == 'Trending' ? 'selected' : '' }}>⚡ Trending</option>
                    <option value="Top rated" {{ request('tag') == 'Top rated' ? 'selected' : '' }}>⭐ Top rated</option>
                    <option value="New" {{ request('tag') == 'New' ? 'selected' : '' }}>✨ New</option>
                    <option value="untagged" {{ request('tag') == 'untagged' ? 'selected' : '' }}>🚫 Untagged</option>
                </select>
            </div>

            <!-- City Filter -->
            <div>
                <select name="city" id="adminPgCity" onchange="document.getElementById('adminPgFilterForm').submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition font-medium">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter & Submit -->
            <div class="flex items-center gap-2">
                <select name="status" id="adminPgStatus" onchange="document.getElementById('adminPgFilterForm').submit()" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition font-medium">
                    <option value="">All Status</option>
                    <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending Review</option>
                    <option value="SUSPENDED" {{ request('status') == 'SUSPENDED' ? 'selected' : '' }}>Suspended / Inactive</option>
                </select>

                @if(request()->hasAny(['search', 'type', 'city', 'status', 'tag', 'recommended']) && (request('search') || request('type') || request('city') || request('status') || request('tag') || request('recommended') !== null))
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Recommended</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tag / Badge</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Price / Mo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Listed Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="adminPgBody">
                    @forelse($properties as $property)
                        @php
                            $imgUrl = $property->primaryImage->image_url ?? ($property->images->first()->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80');
                            
                            $brokerName = $property->broker->profile->full_name ?? ($property->broker->name ?? ($property->broker->email ?? 'Direct / Admin'));
                            
                            $typeSlug = strtolower($property->propertyType->slug ?? '');
                            $typeName = $property->propertyType->name ?? 'PG / Hostel';
                            $gender = strtolower($property->gender_preference ?? 'co-ed');
                            
                            $typeClass = 'bg-purple-50 text-purple-700 border border-purple-200';
                            $typeLabel = strtoupper($typeName);

                            if (str_contains($typeSlug, 'commercial')) {
                                $typeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                                $typeLabel = 'COMMERCIAL';
                            } elseif (str_contains($typeSlug, 'flat') || str_contains($typeSlug, 'apartment') || str_contains($typeSlug, 'house')) {
                                $typeClass = 'bg-teal-50 text-teal-700 border border-teal-200';
                                $typeLabel = 'FLAT / HOUSE';
                            } elseif ($gender === 'boys' || $gender === 'male') {
                                $typeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                                $typeLabel = 'BOYS PG';
                            } elseif ($gender === 'girls' || $gender === 'female') {
                                $typeClass = 'bg-pink-50 text-pink-700 border border-pink-200';
                                $typeLabel = 'GIRLS PG';
                            }
                            
                            $isVerified = ($property->verification_status === 'verified' && $property->status === 'active');
                            $isPending = ($property->verification_status === 'pending');
                            $tagMeta = $property->tag_meta;
                            $isComm = $property->property_category === 'commercial' || str_contains($typeSlug, 'commercial');
                            $isFlat = $property->property_category === 'residential' && (str_contains($typeSlug, 'flat') || str_contains($typeSlug, 'apartment') || str_contains($typeSlug, 'house') || str_contains($typeSlug, 'villa'));
                            $isLand = $property->property_category === 'land-plot' || str_contains($typeSlug, 'land') || str_contains($typeSlug, 'plot');
                            $isPg = !$isComm && !$isFlat && !$isLand;
                        @endphp
                        <tr id="pg-row-{{ $property->id }}" class="admin-pg-row hover:bg-gray-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $imgUrl }}" alt="{{ $property->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-gray-100 shrink-0">
                                    <div class="min-w-0">
                                        <a href="{{ url('/list-property?edit_id=' . $property->id) }}" class="font-bold text-gray-900 truncate max-w-xs pg-name hover:text-brand transition block">{{ $property->name }}</a>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $property->landmark ?? ($property->address ?? 'Verified Stay') }}</div>
                                        <div class="text-[10px] text-gray-400 flex items-center gap-1 mt-0.5">
                                            <i class="far fa-clock text-[9px]"></i> Listed: {{ $property->created_at ? $property->created_at->format('d M Y') : 'N/A' }}
                                        </div>
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
                            <td class="px-6 py-4">
                                @php
                                    $isRec = (bool) ($property->is_recommended || $property->featured);
                                @endphp
                                <button type="button" 
                                        onclick="togglePropertyRecommended('{{ $property->id }}')" 
                                        id="rec-btn-{{ $property->id }}" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer shadow-2xs {{ $isRec ? 'bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200' : 'bg-gray-50 text-gray-500 border border-gray-200 hover:bg-gray-100 hover:text-gray-700' }}"
                                        title="Click to toggle Recommended on Home Page">
                                    <i id="rec-icon-{{ $property->id }}" class="{{ $isRec ? 'fas fa-star text-amber-500' : 'far fa-star text-gray-400' }} text-[11px]"></i>
                                    <span id="rec-label-{{ $property->id }}">{{ $isRec ? 'Recommended' : 'Standard' }}</span>
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5 min-w-[145px]" id="tag-container-{{ $property->id }}">
                                    <span id="tag-badge-{{ $property->id }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border transition-all duration-200 w-fit {{ $tagMeta ? $tagMeta['bg_class'] : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                        <i class="fas fa-{{ $tagMeta ? $tagMeta['icon'] : 'tag' }} text-[10px]"></i>
                                        <span class="tag-label">{{ $tagMeta ? $tagMeta['label'] : 'No Tag' }}</span>
                                    </span>
                                    <select onchange="changePropertyTag('{{ $property->id }}', this.value)" 
                                            id="tag-select-{{ $property->id }}"
                                            class="text-[11px] font-medium bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 text-gray-700 hover:bg-white hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer transition">
                                        <option value="" {{ empty($property->tag) ? 'selected' : '' }}>Select Tag...</option>
                                        <option value="Popular" {{ strcasecmp($property->tag, 'Popular') === 0 ? 'selected' : '' }}>🔥 Popular</option>
                                        <option value="Verified" {{ strcasecmp($property->tag, 'Verified') === 0 ? 'selected' : '' }}>🛡️ Verified</option>
                                        <option value="Guest Favourite" {{ strcasecmp($property->tag, 'Guest Favourite') === 0 ? 'selected' : '' }}>❤️ Guest Favourite</option>
                                        <option value="Trending" {{ strcasecmp($property->tag, 'Trending') === 0 ? 'selected' : '' }}>⚡ Trending</option>
                                        <option value="Top rated" {{ strcasecmp($property->tag, 'Top rated') === 0 ? 'selected' : '' }}>⭐ Top rated</option>
                                        <option value="New" {{ strcasecmp($property->tag, 'New') === 0 ? 'selected' : '' }}>✨ New</option>
                                        <option value="none">❌ None (Clear Tag)</option>
                                    </select>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                @if($isComm)
                                    <span class="font-bold text-gray-900">Commercial</span>
                                    <div class="text-[11px] text-gray-500">{{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' sq ft' : 'Ready Space' }}</div>
                                @elseif($isFlat)
                                    <span class="font-bold text-gray-900">{{ $property->bhk_type ?: 'Apartment' }}</span>
                                    <div class="text-[11px] text-gray-500">{{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' sq ft' : 'Full House' }}</div>
                                @elseif($isLand)
                                    <span class="font-bold text-gray-900">Land / Plot</span>
                                    <div class="text-[11px] text-gray-500">{{ $property->carpet_area_sqft ? number_format($property->carpet_area_sqft) . ' Sq. Yds' : 'Plot Layout' }}</div>
                                @else
                                    {{ $property->total_beds ?? 1 }} beds
                                    <div class="text-[11px] {{ (int)($property->available_beds ?? 0) > 0 ? 'text-emerald-600 font-semibold' : 'text-rose-600 font-bold' }}">
                                        {{ (int)($property->available_beds ?? 0) > 0 ? ($property->available_beds . ' avail.') : '0 avail (Full)' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                @if($property->is_sale)
                                    <span class="text-amber-600">{{ $property->display_price_formatted }}</span>
                                    <div class="text-[10px] text-gray-400 font-semibold uppercase">For Sale</div>
                                @else
                                    ₹{{ number_format($property->monthly_rent) }}<span class="text-[10px] font-normal text-gray-500">/mo</span>
                                @endif
                            </td>
                            <!-- Listed Date Column -->
                            <td class="px-6 py-4 text-xs whitespace-nowrap">
                                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                    <i class="far fa-calendar-alt text-brand text-[11px]"></i>
                                    <span>{{ $property->created_at ? $property->created_at->format('d M Y') : 'N/A' }}</span>
                                </div>
                                <div class="text-[10px] text-gray-400 mt-0.5">
                                    {{ $property->created_at ? $property->created_at->diffForHumans() : '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span id="status-badge-{{ $property->id }}" class="status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase {{ $isVerified ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                    {{ $isVerified ? 'APPROVED' : ($isPending ? 'PENDING' : strtoupper($property->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Edit in List Property Wizard -->
                                    <a href="{{ url('/list-property?edit_id=' . $property->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect cursor-pointer" title="Edit in List Property Wizard">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>

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
                            <td colspan="11" class="px-6 py-12 text-center text-gray-400">
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

        <!-- Table Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                @if($properties->total() > 0)
                    <span>Showing <strong class="text-gray-900 font-bold">{{ $properties->firstItem() ?? 1 }}</strong> to <strong class="text-gray-900 font-bold">{{ $properties->lastItem() ?? $properties->total() }}</strong> of <strong class="text-gray-900 font-bold">{{ $properties->total() }}</strong> properties</span>
                @else
                    <span>Showing <strong class="text-gray-900 font-bold">0</strong> properties</span>
                @endif

                <div class="flex items-center gap-1.5 pl-2 border-l border-gray-200">
                    <span class="text-gray-400">Rows per page:</span>
                    <select onchange="changePerPage(this.value)" class="bg-white border border-gray-200 text-gray-700 text-xs font-semibold rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-brand cursor-pointer">
                        @foreach([10, 15, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{ $properties->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

    <!-- Mobile Cards View -->
    <div class="md:hidden space-y-4" id="adminPgMobileList">
        @forelse($properties as $property)
            @php
                $imgUrl = $property->primaryImage->image_url ?? ($property->images->first()->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80');
                $brokerName = $property->broker->profile->full_name ?? ($property->broker->name ?? ($property->broker->email ?? 'Direct / Admin'));
                
                $typeSlug = strtolower($property->propertyType->slug ?? '');
                $typeName = $property->propertyType->name ?? 'PG / Hostel';
                $gender = strtolower($property->gender_preference ?? 'co-ed');
                
                $typeClass = 'bg-purple-50 text-purple-700 border border-purple-200';
                $typeLabel = strtoupper($typeName);

                if (str_contains($typeSlug, 'commercial')) {
                    $typeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                    $typeLabel = 'COMMERCIAL';
                } elseif (str_contains($typeSlug, 'flat') || str_contains($typeSlug, 'apartment') || str_contains($typeSlug, 'house')) {
                    $typeClass = 'bg-teal-50 text-teal-700 border border-teal-200';
                    $typeLabel = 'FLAT / HOUSE';
                } elseif ($gender === 'boys' || $gender === 'male') {
                    $typeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                    $typeLabel = 'BOYS PG';
                } elseif ($gender === 'girls' || $gender === 'female') {
                    $typeClass = 'bg-pink-50 text-pink-700 border border-pink-200';
                    $typeLabel = 'GIRLS PG';
                }
                
                $isVerified = ($property->verification_status === 'verified' && $property->status === 'active');
                $isPending = ($property->verification_status === 'pending');
                $tagMeta = $property->tag_meta;
                $isRecMobile = (bool) ($property->is_recommended || $property->featured);
            @endphp
            <div id="pg-mobile-card-{{ $property->id }}" class="admin-pg-card bg-white rounded-2xl p-4 border border-gray-100 shadow-sm space-y-3">
                <div class="flex gap-3">
                    <img src="{{ $imgUrl }}" alt="{{ $property->name }}" class="w-20 h-20 rounded-xl object-cover shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-1">
                            <a href="{{ url('/list-property?edit_id=' . $property->id) }}" class="font-bold text-gray-900 text-sm truncate pg-name hover:text-brand transition block">{{ $property->name }}</a>
                            <span id="mobile-status-badge-{{ $property->id }}" class="status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 {{ $isVerified ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                {{ $isVerified ? 'APPROVED' : ($isPending ? 'PENDING' : strtoupper($property->status)) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="{{ $typeClass }} text-[9px] font-bold px-1.5 py-0.5 rounded uppercase">
                                {{ $typeLabel }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 pg-city">
                            <i class="fas fa-map-marker-alt text-brand"></i> {{ $property->city->name ?? 'City' }} {{ !empty($property->area->name) ? '• ' . $property->area->name : '' }}
                        </p>
                        <p class="text-xs text-gray-600 font-medium mt-0.5 truncate broker-name">Broker: {{ $brokerName }}</p>
                        <p class="text-xs font-bold text-gray-900 mt-1">₹{{ number_format($property->monthly_rent) }}/mo • {{ $property->total_beds }} beds</p>
                        <p class="text-[11px] text-gray-500 mt-1 flex items-center gap-1 font-medium">
                            <i class="far fa-calendar-alt text-brand text-[10px]"></i> 
                            <span>Listed: <strong class="text-gray-800">{{ $property->created_at ? $property->created_at->format('d M Y') : 'N/A' }}</strong> <span class="text-gray-400">({{ $property->created_at ? $property->created_at->diffForHumans() : '' }})</span></span>
                        </p>
                    </div>
                </div>

                <!-- Mobile Tag & Recommended Bar -->
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-gray-100">
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tag:</span>
                        <span id="mobile-tag-badge-{{ $property->id }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border {{ $tagMeta ? $tagMeta['bg_class'] : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                            <i class="fas fa-{{ $tagMeta ? $tagMeta['icon'] : 'tag' }} text-[9px]"></i>
                            <span>{{ $tagMeta ? $tagMeta['label'] : 'No Tag' }}</span>
                        </span>
                    </div>
                    <select onchange="changePropertyTag('{{ $property->id }}', this.value)" 
                            id="mobile-tag-select-{{ $property->id }}"
                            class="text-[11px] font-medium bg-gray-50 border border-gray-200 rounded px-2 py-1 text-gray-700">
                        <option value="" {{ empty($property->tag) ? 'selected' : '' }}>Select Tag...</option>
                        <option value="Popular" {{ strcasecmp($property->tag, 'Popular') === 0 ? 'selected' : '' }}>🔥 Popular</option>
                        <option value="Verified" {{ strcasecmp($property->tag, 'Verified') === 0 ? 'selected' : '' }}>🛡️ Verified</option>
                        <option value="Guest Favourite" {{ strcasecmp($property->tag, 'Guest Favourite') === 0 ? 'selected' : '' }}>❤️ Guest Favourite</option>
                        <option value="Trending" {{ strcasecmp($property->tag, 'Trending') === 0 ? 'selected' : '' }}>⚡ Trending</option>
                        <option value="Top rated" {{ strcasecmp($property->tag, 'Top rated') === 0 ? 'selected' : '' }}>⭐ Top rated</option>
                        <option value="New" {{ strcasecmp($property->tag, 'New') === 0 ? 'selected' : '' }}>✨ New</option>
                        <option value="none">❌ Clear</option>
                    </select>
                </div>

                <!-- Mobile Recommended Toggle -->
                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Home Showcase:</span>
                    <button type="button" 
                            onclick="togglePropertyRecommended('{{ $property->id }}')" 
                            id="mobile-rec-btn-{{ $property->id }}" 
                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1 {{ $isRecMobile ? 'bg-amber-100 text-amber-900 border-amber-300' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                        <i id="mobile-rec-icon-{{ $property->id }}" class="{{ $isRecMobile ? 'fas fa-star text-amber-500' : 'far fa-star text-gray-400' }} text-[9px]"></i>
                        <span id="mobile-rec-label-{{ $property->id }}">{{ $isRecMobile ? '⭐ Recommended' : '☆ Standard' }}</span>
                    </button>
                </div>

                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <a href="{{ url('/list-property?edit_id=' . $property->id) }}" class="flex-1 bg-blue-50 text-blue-600 text-xs font-semibold py-2 rounded-lg text-center tap-effect flex items-center justify-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
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

        <!-- Mobile Pagination Footer -->
        @if($properties->total() > 0)
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs space-y-3">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>Page <strong class="text-gray-900 font-bold">{{ $properties->currentPage() }}</strong> of <strong class="text-gray-900 font-bold">{{ $properties->lastPage() }}</strong> ({{ $properties->total() }} total)</span>
                    <div class="flex items-center gap-1.5">
                        <span class="text-gray-400">Rows:</span>
                        <select onchange="changePerPage(this.value)" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-lg px-2 py-1 cursor-pointer">
                            @foreach([10, 15, 25, 50] as $size)
                                <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100 flex justify-center">
                    {{ $properties->links('vendor.pagination.custom') }}
                </div>
            </div>
        @endif
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

            <!-- Tag & Image URL -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Property Tag / Badge</label>
                    <select name="tag" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-800 font-medium">
                        <option value="">No Tag (Default)</option>
                        <option value="Popular">🔥 Popular</option>
                        <option value="Verified">🛡️ Verified</option>
                        <option value="Guest Favourite">❤️ Guest Favourite</option>
                        <option value="Trending">⚡ Trending</option>
                        <option value="Top rated">⭐ Top rated</option>
                        <option value="New">✨ New</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Primary Photo URL</label>
                    <input type="url" name="image_url" value="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" placeholder="https://images.unsplash.com/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700">
                </div>
            </div>

            <!-- Instant Publish & Recommended Checkboxes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="instant_approve" value="1" checked id="instantApproveCheck" class="w-4 h-4 text-brand rounded border-gray-300 focus:ring-brand/50 accent-brand">
                    <label for="instantApproveCheck" class="text-xs font-semibold text-gray-700 cursor-pointer select-none">
                        Instant Approve & Publish Live
                    </label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_recommended" value="1" id="isRecommendedCheck" class="w-4 h-4 text-amber-500 rounded border-gray-300 focus:ring-amber-400 accent-amber-500">
                    <label for="isRecommendedCheck" class="text-xs font-bold text-amber-800 cursor-pointer select-none flex items-center gap-1">
                        <i class="fas fa-star text-amber-500 text-xs"></i> Mark as Recommended Listing
                    </label>
                </div>
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

    // 1-Click Toggle Property Recommended Status (AJAX)
    async function togglePropertyRecommended(propertyId) {
        try {
            const res = await fetch(`/admin/pgs/${propertyId}/toggle-recommended`, {
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

                const isRec = data.is_recommended;
                const btn = document.getElementById(`rec-btn-${propertyId}`);
                const icon = document.getElementById(`rec-icon-${propertyId}`);
                const label = document.getElementById(`rec-label-${propertyId}`);

                const mBtn = document.getElementById(`mobile-rec-btn-${propertyId}`);
                const mIcon = document.getElementById(`mobile-rec-icon-${propertyId}`);
                const mLabel = document.getElementById(`mobile-rec-label-${propertyId}`);

                if (btn) {
                    btn.className = `inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer shadow-2xs ${isRec ? 'bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200 animate-pulse' : 'bg-gray-50 text-gray-500 border border-gray-200 hover:bg-gray-100 hover:text-gray-700'}`;
                    if (icon) icon.className = (isRec ? 'fas fa-star text-amber-500' : 'far fa-star text-gray-400') + ' text-[11px]';
                    if (label) label.textContent = isRec ? 'Recommended' : 'Standard';
                    setTimeout(() => btn.classList.remove('animate-pulse'), 500);
                }

                if (mBtn) {
                    mBtn.className = `px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1 ${isRec ? 'bg-amber-100 text-amber-900 border-amber-300' : 'bg-gray-50 text-gray-500 border-gray-200'}`;
                    if (mIcon) mIcon.className = (isRec ? 'fas fa-star text-amber-500' : 'far fa-star text-gray-400') + ' text-[9px]';
                    if (mLabel) mLabel.textContent = isRec ? '⭐ Recommended' : '☆ Standard';
                }
            } else {
                showPgToast(data.message || 'Error updating recommended status', 'error');
            }
        } catch (err) {
            console.error(err);
            showPgToast('Network error while updating recommendation', 'error');
        }
    }

    // 1-Click Update Property Tag / Badge Direct (AJAX)
    async function changePropertyTag(propertyId, newTag) {
        try {
            const res = await fetch(`/admin/pgs/${propertyId}/update-tag`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ tag: newTag })
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showPgToast(data.message, 'success');

                const meta = data.tag_meta;
                const badge = document.getElementById(`tag-badge-${propertyId}`);
                const mBadge = document.getElementById(`mobile-tag-badge-${propertyId}`);
                const modalBadge = document.getElementById(`modal-tag-badge-${propertyId}`);

                const badgeClass = meta ? meta.bg_class : 'bg-gray-100 text-gray-500 border-gray-200';
                const badgeIcon = meta ? meta.icon : 'tag';
                const badgeLabel = meta ? meta.label : 'No Tag';

                if (badge) {
                    badge.className = `inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border transition-all duration-200 w-fit ${badgeClass} animate-pulse`;
                    badge.innerHTML = `<i class="fas fa-${badgeIcon} text-[10px]"></i> <span class="tag-label">${badgeLabel}</span>`;
                    setTimeout(() => badge.classList.remove('animate-pulse'), 500);
                }

                if (mBadge) {
                    mBadge.className = `inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border ${badgeClass}`;
                    mBadge.innerHTML = `<i class="fas fa-${badgeIcon} text-[9px]"></i> <span>${badgeLabel}</span>`;
                }

                if (modalBadge) {
                    modalBadge.className = `inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold border ${badgeClass}`;
                    modalBadge.innerHTML = `<i class="fas fa-${badgeIcon} text-xs"></i> <span>${badgeLabel}</span>`;
                }

                // Sync desktop & mobile dropdown selects
                const sel = document.getElementById(`tag-select-${propertyId}`);
                if (sel) sel.value = data.tag || '';
                const mSel = document.getElementById(`mobile-tag-select-${propertyId}`);
                if (mSel) mSel.value = data.tag || '';
                const modalSel = document.getElementById(`modal-tag-select-${propertyId}`);
                if (modalSel) modalSel.value = data.tag || '';

            } else {
                showPgToast(data.message || 'Failed to update tag', 'error');
            }
        } catch (err) {
            console.error(err);
            showPgToast('Network error while updating tag', 'error');
        }
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
                const tagMeta = p.tag_meta;
                const tagBadgeClass = tagMeta ? tagMeta.bg_class : 'bg-gray-100 text-gray-500 border-gray-200';
                const tagBadgeIcon = tagMeta ? tagMeta.icon : 'tag';
                const tagBadgeLabel = tagMeta ? tagMeta.label : 'No Tag';

                const isRecProperty = Boolean(p.is_recommended || p.featured);

                bodyEl.innerHTML = `
                    <div class="space-y-4">
                        <img src="${imgUrl}" alt="${p.name}" class="w-full h-48 rounded-2xl object-cover border border-gray-100 shadow-xs">
                        
                        <!-- Recommendation & Tag Modifier Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-gray-50 p-3.5 rounded-2xl flex items-center justify-between gap-2 border border-gray-100">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Recommended Showcase</span>
                                    <button type="button" onclick="togglePropertyRecommended('${p.id}'); this.querySelector('span').textContent = this.querySelector('span').textContent.includes('Recommended') ? '☆ Standard' : '⭐ Recommended';" class="px-2.5 py-1.5 rounded-xl text-xs font-bold border ${isRecProperty ? 'bg-amber-100 text-amber-900 border-amber-300' : 'bg-white text-gray-600 border-gray-200'}">
                                        <i class="fas fa-star text-amber-500 text-xs"></i> <span>${isRecProperty ? '⭐ Recommended' : '☆ Standard'}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-3.5 rounded-2xl flex items-center justify-between gap-3 border border-gray-100">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Property Tag</span>
                                    <span id="modal-tag-badge-${p.id}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border ${tagBadgeClass}">
                                        <i class="fas fa-${tagBadgeIcon} text-xs"></i> <span>${tagBadgeLabel}</span>
                                    </span>
                                </div>
                                <div>
                                    <select onchange="changePropertyTag('${p.id}', this.value)" 
                                            id="modal-tag-select-${p.id}"
                                            class="text-xs font-semibold bg-white border border-gray-200 rounded-xl px-2.5 py-1.5 text-gray-800 focus:outline-none focus:ring-2 focus:ring-brand/40 cursor-pointer shadow-xs">
                                        <option value="" ${!p.tag ? 'selected' : ''}>No Tag (None)</option>
                                        <option value="Popular" ${p.tag && p.tag.toLowerCase() === 'popular' ? 'selected' : ''}>🔥 Popular</option>
                                        <option value="Verified" ${p.tag && p.tag.toLowerCase() === 'verified' ? 'selected' : ''}>🛡️ Verified</option>
                                        <option value="Guest Favourite" ${p.tag && p.tag.toLowerCase() === 'guest favourite' ? 'selected' : ''}>❤️ Guest Favourite</option>
                                        <option value="Trending" ${p.tag && p.tag.toLowerCase() === 'trending' ? 'selected' : ''}>⚡ Trending</option>
                                        <option value="Top rated" ${p.tag && p.tag.toLowerCase() === 'top rated' ? 'selected' : ''}>⭐ Top rated</option>
                                        <option value="New" ${p.tag && p.tag.toLowerCase() === 'new' ? 'selected' : ''}>✨ New</option>
                                        <option value="none">❌ Clear Tag</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-2xl">
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
                            <div>
                                <span class="text-xs text-gray-500 block font-medium">Listed On</span>
                                <span class="text-xs font-bold text-gray-900 flex items-center gap-1 mt-0.5"><i class="far fa-calendar-alt text-brand"></i> ${p.created_at ? new Date(p.created_at).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A'}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block font-medium">Status</span>
                                <span class="text-xs font-bold uppercase text-emerald-600">${p.status || 'Active'}</span>
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

    // Dynamic Per Page Rows Handler
    function changePerPage(perPage) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
</script>
@endpush
