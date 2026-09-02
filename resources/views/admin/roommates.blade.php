@extends('admin.layouts.app')

@section('title', 'All Flatmates & Roommates - Admin Panel')

@section('content')
<!-- Top Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                <i class="fas fa-handshake"></i>
            </span>
            All Flatmates & Roommates Management
        </h1>
        <p class="text-sm text-gray-500">{{ number_format($totalCount) }} direct flatmate & room-sharing postings across {{ $cities->count() }} cities</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('user.roommate.create') }}" target="_blank" class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-emerald-600/30 hover:shadow-xl transition flex items-center gap-2 cursor-pointer no-underline text-xs sm:text-sm">
            <i class="fas fa-plus text-xs"></i> Post Flatmate Listing
        </a>
        <a href="{{ route('user.roommate.index') }}" target="_blank" class="bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 px-4 py-2.5 rounded-xl font-semibold tap-effect transition flex items-center gap-2 no-underline text-xs sm:text-sm">
            <i class="fas fa-external-link-alt text-xs text-gray-400"></i> Public Finder
        </a>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Flash Alert / Toast Anchor -->
    <div id="roommateToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="roommateToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="roommateToastIcon"><i class="fas fa-check-circle text-emerald-400 text-base"></i></span>
            <span id="roommateToastMessage">Action completed successfully</span>
        </div>
    </div>

    <!-- Mobile Top Actions -->
    <div class="lg:hidden flex items-center gap-2">
        <a href="{{ route('user.roommate.create') }}" target="_blank" class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-700 text-white py-3 rounded-xl font-semibold text-center tap-effect shadow-md flex items-center justify-center gap-2 text-xs">
            <i class="fas fa-plus"></i> Post Flatmate
        </a>
        <a href="{{ route('user.roommate.index') }}" target="_blank" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center justify-center gap-1.5 shadow-xs">
            <i class="fas fa-external-link-alt text-xs"></i> Finder
        </a>
    </div>

    <!-- ================= LISTING TYPE TABS BAR ================= -->
    <div class="bg-white rounded-2xl p-2.5 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <!-- All Listings -->
            <a href="{{ request()->fullUrlWithQuery(['post_type' => null, 'status' => null, 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ (!request()->filled('post_type') && !request()->filled('status')) ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-layer-group text-xs"></i>
                <span>All Listings</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ (!request()->filled('post_type') && !request()->filled('status')) ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($totalCount) }}
                </span>
            </a>

            <!-- Have Room -->
            <a href="{{ request()->fullUrlWithQuery(['post_type' => 'have_room', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ (request('post_type') === 'have_room') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-door-open text-xs"></i>
                <span>Have a Room (Offering)</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ (request('post_type') === 'have_room') ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($haveRoomCount) }}
                </span>
            </a>

            <!-- Need Room -->
            <a href="{{ request()->fullUrlWithQuery(['post_type' => 'need_room', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ (request('post_type') === 'need_room') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-search-location text-xs"></i>
                <span>Need a Room (Seeking)</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ (request('post_type') === 'need_room') ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ number_format($needRoomCount) }}
                </span>
            </a>

            <!-- Active Only -->
            <a href="{{ request()->fullUrlWithQuery(['status' => 'active', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ (request('status') === 'active') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-check-circle text-xs"></i>
                <span>Active Listings</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ (request('status') === 'active') ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-700' }}">
                    {{ number_format($activeCount) }}
                </span>
            </a>

            <!-- Filled / Closed -->
            <a href="{{ request()->fullUrlWithQuery(['status' => 'filled', 'page' => null]) }}" 
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap {{ (request('status') === 'filled') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-user-check text-xs"></i>
                <span>Filled / Matched</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ (request('status') === 'filled') ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-700' }}">
                    {{ number_format($filledCount) }}
                </span>
            </a>
        </div>
    </div>

    <!-- ================= STATS CARDS ================= -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Listings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($activeCount) }}</div>
            <div class="text-xs text-emerald-700 mt-1 font-bold flex items-center gap-1">
                <i class="fas fa-bolt text-[10px]"></i> Live & Active
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-blue-600">{{ number_format($haveRoomCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Rooms Available</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-purple-600">{{ number_format($needRoomCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Room Seekers</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-teal-600">{{ number_format($totalViews) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Page Views</div>
        </div>
    </div>

    <!-- ================= COMPREHENSIVE FILTER BAR ================= -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.roommates') }}" class="space-y-3">
            @if(request('post_type'))
                <input type="hidden" name="post_type" value="{{ request('post_type') }}">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <!-- Keyword Search -->
                <div class="lg:col-span-2 relative">
                    <i class="fas fa-search absolute left-3.5 top-3.5 text-xs text-gray-400"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search poster, title, locality, phone..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                </div>

                <!-- City Filter -->
                <div>
                    <select name="city" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                        <option value="all">All Cities ({{ $cities->count() }})</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender Preference -->
                <div>
                    <select name="gender_pref" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                        <option value="all">All Preferences</option>
                        <option value="any" {{ request('gender_pref') === 'any' ? 'selected' : '' }}>Any Gender</option>
                        <option value="female" {{ request('gender_pref') === 'female' ? 'selected' : '' }}>Girls Only</option>
                        <option value="male" {{ request('gender_pref') === 'male' ? 'selected' : '' }}>Boys Only</option>
                    </select>
                </div>

                <!-- BHK / Room Type -->
                <div>
                    <select name="bhk_type" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                        <option value="all">All BHK Types</option>
                        @foreach($bhkOptions as $key => $label)
                            <option value="{{ $key }}" {{ request('bhk_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition">
                        <option value="all">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="filled" {{ request('status') === 'filled' ? 'selected' : '' }}>Filled</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <!-- Bottom Row: Sort & Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 font-medium">Sort by:</span>
                    <select name="sort" onchange="this.form.submit()" class="py-1.5 px-3 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="rent_high" {{ request('sort') === 'rent_high' ? 'selected' : '' }}>Rent: High to Low</option>
                        <option value="rent_low" {{ request('sort') === 'rent_low' ? 'selected' : '' }}>Rent: Low to High</option>
                        <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Most Viewed</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'city', 'gender_pref', 'bhk_type', 'status', 'post_type', 'sort']))
                        <a href="{{ route('admin.roommates') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-gray-500 hover:text-red-600 hover:bg-red-50 transition">
                            <i class="fas fa-times mr-1"></i> Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-bold tap-effect transition flex items-center gap-1.5 shadow-sm">
                        <i class="fas fa-filter text-[10px]"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================= BULK ACTIONS BAR (HIDDEN BY DEFAULT) ================= -->
    <div id="bulkActionsBar" class="hidden bg-gray-900 text-white p-3.5 rounded-2xl shadow-xl flex items-center justify-between transition-all duration-300">
        <div class="flex items-center gap-3">
            <span class="w-6 h-6 rounded-full bg-emerald-500 text-white font-black text-xs flex items-center justify-center" id="selectedCount">0</span>
            <span class="text-xs font-semibold">listing(s) selected</span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="bulkDeleteRoommates()" class="bg-red-600 hover:bg-red-700 text-white px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fas fa-trash-alt text-xs"></i> Delete Selected
            </button>
            <button type="button" onclick="clearBulkSelection()" class="bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-xl text-xs font-medium transition">
                Cancel
            </button>
        </div>
    </div>

    <!-- ================= MAIN DATA TABLE ================= -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] font-extrabold uppercase text-gray-400 tracking-wider bg-gray-50/70">
                        <th class="py-3.5 px-4 w-10">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </th>
                        <th class="py-3.5 px-4">Poster & Contact</th>
                        <th class="py-3.5 px-4">Room & Type</th>
                        <th class="py-3.5 px-4">Location</th>
                        <th class="py-3.5 px-4">Rent / Mo</th>
                        <th class="py-3.5 px-4">Preference</th>
                        <th class="py-3.5 px-4">Views & Date</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($roommates as $roommate)
                        @php
                            $bhkName = $bhkOptions[$roommate->bhk_type] ?? $roommate->bhk_type;
                            $pGender = strtolower($roommate->poster_gender ?? 'male');
                            $genderPref = strtolower($roommate->gender_preference ?? 'any');
                            
                            $statusBadge = match($roommate->status) {
                                'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'filled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'expired' => 'bg-gray-100 text-gray-600 border-gray-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <tr id="roommate-row-{{ $roommate->id }}" class="hover:bg-gray-50/80 transition-colors">
                            <!-- Select Checkbox -->
                            <td class="py-4 px-4">
                                <input type="checkbox" 
                                       value="{{ $roommate->id }}" 
                                       onchange="updateBulkBar()" 
                                       class="row-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            </td>

                            <!-- Poster Details -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-700 to-gray-900 text-white font-bold flex items-center justify-center text-sm shrink-0 overflow-hidden shadow-xs">
                                        @if(!empty($roommate->poster_avatar_url))
                                            <img src="{{ $roommate->poster_avatar_url }}" alt="{{ $roommate->poster_name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $pGender === 'female' ? '👩' : ($pGender === 'male' ? '👨' : '🧑') }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 truncate flex items-center gap-1.5">
                                            <span>{{ $roommate->poster_name }}</span>
                                            @if($roommate->poster_age)
                                                <span class="text-[10px] text-gray-400">({{ $roommate->poster_age }}y)</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-500 truncate">
                                            {{ $roommate->profession ?: 'Flatmate Host' }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 truncate">
                                            {{ $roommate->user->phone ?? ($roommate->user->email ?? 'Direct Post') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Room & BHK -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-gray-900 truncate max-w-[240px]" title="{{ $roommate->title }}">
                                    {{ $roommate->title }}
                                </div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    @if($roommate->post_type === 'have_room')
                                        <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold px-1.5 py-0.2 rounded">Have Room</span>
                                    @else
                                        <span class="text-[10px] bg-purple-50 text-purple-700 border border-purple-200 font-bold px-1.5 py-0.2 rounded">Need Room</span>
                                    @endif
                                    <span class="text-[10px] bg-gray-100 text-gray-700 font-bold px-1.5 py-0.2 rounded">{{ $bhkName }}</span>
                                    <span class="text-[10px] text-gray-400 capitalize">• {{ str_replace('_', ' ', $roommate->furnishing) }}</span>
                                </div>
                            </td>

                            <!-- Location -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-gray-800 flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-emerald-600 text-[11px]"></i>
                                    <span>{{ $roommate->city }}</span>
                                </div>
                                @if($roommate->locality)
                                    <div class="text-[11px] text-gray-500 truncate max-w-[160px]">{{ $roommate->locality }}</div>
                                @endif
                            </td>

                            <!-- Rent & Move-in -->
                            <td class="py-4 px-4">
                                <div class="font-black text-gray-900 text-sm">
                                    {{ $roommate->budget_range }}
                                </div>
                                @if($roommate->move_in_date)
                                    <div class="text-[10px] text-gray-400">Move-in: {{ \Carbon\Carbon::parse($roommate->move_in_date)->format('d M Y') }}</div>
                                @else
                                    <div class="text-[10px] text-emerald-600 font-semibold">Immediate</div>
                                @endif
                            </td>

                            <!-- Gender Preference -->
                            <td class="py-4 px-4">
                                @if($genderPref === 'female' || $genderPref === 'girls')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-pink-50 text-pink-700 border border-pink-200">
                                        <i class="fas fa-venus text-[9px]"></i> Girls Only
                                    </span>
                                @elseif($genderPref === 'male' || $genderPref === 'boys')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="fas fa-mars text-[9px]"></i> Boys Only
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        <i class="fas fa-users text-[9px]"></i> Any Gender
                                    </span>
                                @endif
                            </td>

                            <!-- Views & Date -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-1 text-gray-700 font-bold">
                                    <i class="fas fa-eye text-gray-400 text-[10px]"></i>
                                    <span>{{ number_format($roommate->view_count) }} views</span>
                                </div>
                                <div class="text-[10px] text-gray-400">{{ $roommate->created_at ? $roommate->created_at->diffForHumans() : 'Recently' }}</div>
                            </td>

                            <!-- Status Dropdown -->
                            <td class="py-4 px-4">
                                <select onchange="updateRoommateStatus('{{ $roommate->id }}', this.value)" class="text-[11px] font-bold px-2 py-1 rounded-lg border {{ $statusBadge }} focus:outline-none cursor-pointer">
                                    <option value="active" {{ $roommate->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="filled" {{ $roommate->status === 'filled' ? 'selected' : '' }}>Filled</option>
                                    <option value="expired" {{ $roommate->status === 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="rejected" {{ $roommate->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </td>

                            <!-- Actions (View + Delete Button) -->
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('user.roommate.show', $roommate->slug) }}" target="_blank" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-emerald-600 hover:text-white text-gray-600 flex items-center justify-center transition tap-effect" title="View Public Listing">
                                        <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="deleteRoommatePost('{{ $roommate->id }}', '{{ addslashes($roommate->poster_name) }} ({{ addslashes($roommate->city) }})')" 
                                            class="w-8 h-8 rounded-xl bg-red-50 hover:bg-red-600 hover:text-white text-red-600 flex items-center justify-center transition tap-effect cursor-pointer" 
                                            title="Delete Flatmate Listing">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center text-gray-400">
                                <div class="w-14 h-14 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fas fa-handshake-slash"></i>
                                </div>
                                <div class="text-sm font-bold text-gray-700">No flatmate listings found</div>
                                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">Try adjusting your filters or keyword search, or create a new flatmate listing.</p>
                                <a href="{{ route('admin.roommates') }}" class="inline-block mt-3 text-xs font-bold text-emerald-600 hover:underline">Reset all filters</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($roommates->hasPages())
            <div class="p-4 md:p-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500">
                    Showing <span class="font-bold text-gray-900">{{ $roommates->firstItem() }}</span> to <span class="font-bold text-gray-900">{{ $roommates->lastItem() }}</span> of <span class="font-bold text-gray-900">{{ $roommates->total() }}</span> listings
                </div>
                <div>
                    {{ $roommates->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Dynamic Toast Messenger
    function showToast(message, type = 'success') {
        const toast = document.getElementById('roommateToastNotification');
        const text = document.getElementById('roommateToastMessage');
        const icon = document.getElementById('roommateToastIcon');

        if (!toast || !text || !icon) return;

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

    // 1-Click Delete Flatmate / Roommate Listing via AJAX
    async function deleteRoommatePost(postId, postTitle) {
        if (!confirm(`Are you sure you want to permanently delete the flatmate listing "${postTitle}"?\n\nThis will remove the listing from public search and clear all active chat threads.`)) return;

        const row = document.getElementById(`roommate-row-${postId}`);
        try {
            const res = await fetch(`/admin/roommates/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message || 'Flatmate listing deleted successfully', 'success');
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.95)';
                    setTimeout(() => row.remove(), 300);
                }
            } else {
                showToast(data.message || 'Failed to delete listing', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while deleting listing', 'error');
        }
    }

    // Dynamic Flatmate Status Updater
    async function updateRoommateStatus(postId, newStatus) {
        try {
            const res = await fetch(`/admin/roommates/${postId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: newStatus })
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message || 'Status updated', 'success');
            } else {
                showToast(data.message || 'Failed to update status', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while updating status', 'error');
        }
    }

    // Bulk Select & Delete
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        updateBulkBar();
    }

    function updateBulkBar() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        const bar = document.getElementById('bulkActionsBar');
        const countSpan = document.getElementById('selectedCount');

        if (selected.length > 0) {
            bar.classList.remove('hidden');
            countSpan.textContent = selected.length;
        } else {
            bar.classList.add('hidden');
        }
    }

    function clearBulkSelection() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        const master = document.getElementById('selectAllCheckbox');
        if (master) master.checked = false;
        updateBulkBar();
    }

    async function bulkDeleteRoommates() {
        const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;

        if (!confirm(`Are you sure you want to permanently delete ${selected.length} selected flatmate listing(s)?`)) return;

        try {
            const res = await fetch(`/admin/roommates/bulk-delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ ids: selected })
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message || 'Listings deleted successfully', 'success');
                selected.forEach(id => {
                    const row = document.getElementById(`roommate-row-${id}`);
                    if (row) row.remove();
                });
                clearBulkSelection();
            } else {
                showToast(data.message || 'Failed to delete selected listings', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error during bulk delete', 'error');
        }
    }
</script>
@endpush
