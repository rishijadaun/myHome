@extends('admin.layouts.app')

@section('title', 'Manage Partner Brokers')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Partner Broker Management</h1>
        <p class="text-xs text-gray-500">Monitor onboarding, verification, relationship managers and partner network</p>
    </div>
    <div class="flex items-center gap-3">
        <!-- Manage RM Team Button -->
        <button 
            onclick="openModal('rmTeamManagementModal')" 
            class="bg-brand-50 hover:bg-brand-100 text-brand-dark px-4 py-2.5 rounded-xl text-xs font-bold tap-effect flex items-center gap-2 border border-brand-200 transition cursor-pointer shadow-xs"
        >
            <i class="fas fa-users-cog text-brand"></i> RM Team ({{ $relationshipManagers->count() }})
        </button>

        <!-- KYC Document Verifications Center Link -->
        <a 
            href="{{ route('admin.broker-kyc.index') }}" 
            class="bg-teal-50 hover:bg-teal-100 text-teal-800 px-4 py-2.5 rounded-xl text-xs font-bold tap-effect flex items-center gap-2 border border-teal-200 transition cursor-pointer"
        >
            <i class="fas fa-id-card-clip text-teal-600"></i> KYC Verifications
        </a>

        <!-- Auto-Assign by Zone Button -->
        <button 
            onclick="triggerAutoAssignRm()" 
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold tap-effect flex items-center gap-2 border border-gray-200 transition cursor-pointer"
            title="Auto-assign unassigned brokers to regional RMs based on city"
        >
            <i class="fas fa-magic text-amber-500"></i> Auto-Assign RMs
        </button>

        <!-- Add Broker Button -->
        <button 
            onclick="openModal('adminAddBrokerModal')" 
            class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-teal-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs tap-effect flex items-center gap-2 shadow-sm cursor-pointer transition"
        >
            <i class="fas fa-plus"></i> Add New Broker
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ $totalCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Registered Brokers</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-amber-600">{{ $pendingCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Approvals</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ $approvedCount }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Verified Active Partners</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-brand">₹{{ number_format($totalCommission / 100000, 2) }}L</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Commission & Payouts</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        
        <!-- Tabs & Filter Header -->
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Status Tabs -->
            <div class="flex border-b md:border-b-0 border-gray-100 space-x-2 overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.brokers', ['tab' => 'ALL', 'search' => request('search'), 'rm_id' => request('rm_id')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($currentTab ?? 'ALL') === 'ALL' ? 'bg-brand-light text-brand border border-brand/20' : 'text-gray-600 hover:bg-gray-50' }}">
                    All Brokers ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.brokers', ['tab' => 'PENDING', 'search' => request('search'), 'rm_id' => request('rm_id')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($currentTab ?? '') === 'PENDING' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    Pending Approval ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.brokers', ['tab' => 'APPROVED', 'search' => request('search'), 'rm_id' => request('rm_id')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($currentTab ?? '') === 'APPROVED' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    Approved ({{ $approvedCount }})
                </a>
                <a href="{{ route('admin.brokers', ['tab' => 'REJECTED', 'search' => request('search'), 'rm_id' => request('rm_id')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ in_array(($currentTab ?? ''), ['REJECTED', 'SUSPENDED']) ? 'bg-red-50 text-red-700 border border-red-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    Suspended ({{ $rejectedCount }})
                </a>
            </div>

            <!-- RM & Search Filters -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Filter by Relationship Manager -->
                <form method="GET" action="{{ route('admin.brokers') }}" class="w-full sm:w-auto">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <select name="rm_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl py-2 px-3 text-xs font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/40 transition w-full sm:w-56 cursor-pointer">
                        <option value="">All Relationship Managers</option>
                        <option value="unassigned" {{ request('rm_id') === 'unassigned' ? 'selected' : '' }}>⚠️ Unassigned Brokers</option>
                        @foreach($relationshipManagers as $rm)
                            <option value="{{ $rm->id }}" {{ request('rm_id') === $rm->id ? 'selected' : '' }}>
                                {{ $rm->name }} ({{ $rm->zone }})
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.brokers') }}" class="relative w-full sm:w-72">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    @if(request('rm_id'))
                        <input type="hidden" name="rm_id" value="{{ request('rm_id') }}">
                    @endif
                    <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                    <input 
                        type="text" 
                        name="search" 
                        id="brokerSearchInput" 
                        value="{{ request('search') }}" 
                        placeholder="Search broker, email, phone..." 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-9 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition"
                    >
                    @if(request('search'))
                        <a href="{{ route('admin.brokers', ['tab' => $currentTab, 'rm_id' => request('rm_id')]) }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Floating Bulk Action Bar (Visible when rows selected) -->
        <div id="bulkActionBar" class="hidden bg-gray-900 text-white px-6 py-3 border-b border-gray-800 flex items-center justify-between transition-all duration-300">
            <div class="flex items-center gap-3 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span id="selectedBrokersCount">0 brokers selected</span>
            </div>
            <div class="flex items-center gap-3">
                <select id="bulkRmSelector" class="bg-gray-800 border border-gray-700 text-white text-xs rounded-xl px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-brand">
                    <option value="">Choose Relationship Manager...</option>
                    @foreach($relationshipManagers as $rm)
                        <option value="{{ $rm->id }}">{{ $rm->name }} ({{ $rm->zone }})</option>
                    @endforeach
                </select>
                <button onclick="handleBulkAssignRm()" class="bg-brand hover:bg-brand-dark text-white px-4 py-1.5 rounded-xl text-xs font-bold tap-effect transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fas fa-user-check"></i> Assign to Selected
                </button>
                <button onclick="deselectAllBrokers()" class="text-xs text-gray-400 hover:text-white underline ml-2 cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left" id="brokerTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-4 w-10 text-center">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="w-4 h-4 accent-brand cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Broker Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Operating Region</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Relationship Manager</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">PGs</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="brokerTableBody">
                    @forelse($brokers as $broker)
                        @php
                            $fullName = $broker->profile->full_name ?? ($broker->name ?? $broker->email);
                            $firstName = $broker->profile->first_name ?? $fullName;
                            $lastName = $broker->profile->last_name ?? '';
                            $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                            if (empty(trim($initials))) $initials = 'BR';
                            
                            $company = $broker->profile->company_name ?? 'Individual Partner';
                            $operatingCities = $broker->properties->pluck('city.name')->filter()->unique()->values()->implode(', ');
                            if (empty($operatingCities)) {
                                $operatingCities = $broker->profile?->preferences['operating_city'] ?? 'Delhi NCR / Regional';
                            }
                            
                            $isKycVerified = !empty($broker->kyc_verified_at);
                            $isActive = ($broker->status === 'active');
                            $isPending = ($broker->status === 'pending_verification' || !$isKycVerified);
                            $rm = $broker->relationshipManager;
                        @endphp
                        <tr id="broker-row-{{ $broker->id }}" class="broker-row hover:bg-gray-50/70 transition">
                            <!-- Checkbox -->
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" value="{{ $broker->id }}" onchange="handleBrokerRowCheckbox()" class="broker-checkbox w-4 h-4 accent-brand cursor-pointer">
                            </td>

                            <!-- Broker Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 truncate broker-name">{{ $fullName }}</div>
                                        <div class="text-xs text-gray-400 truncate">{{ $company }} • Joined {{ $broker->created_at ? $broker->created_at->format('M Y') : 'Recent' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-6 py-4 text-sm">
                                <div class="text-gray-900 font-medium broker-phone">{{ $broker->phone ?? 'No Phone' }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-[180px]">{{ $broker->email }}</div>
                            </td>

                            <!-- Operating Region -->
                            <td class="px-6 py-4 text-sm text-gray-600 broker-city">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-map-marker-alt text-brand text-xs"></i>
                                    <span class="truncate max-w-[150px]">{{ $operatingCities }}</span>
                                </div>
                            </td>

                            <!-- Assigned Relationship Manager -->
                            <td class="px-6 py-4">
                                <div id="rm-cell-{{ $broker->id }}" class="flex items-center gap-2">
                                    @if($rm)
                                        <button 
                                            onclick="openAssignRmModal('{{ $broker->id }}', '{{ addslashes($fullName) }}', '{{ $rm->id }}')" 
                                            class="flex items-center gap-2 text-left bg-gray-50 hover:bg-brand-50/60 p-1.5 pr-2.5 rounded-xl border border-gray-200 transition group cursor-pointer"
                                            title="Click to reassign Relationship Manager"
                                        >
                                            <div class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">
                                                {{ substr($rm->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-gray-900 group-hover:text-brand transition truncate">{{ $rm->name }}</div>
                                                <div class="text-[10px] text-gray-400 truncate">{{ $rm->zone }}</div>
                                            </div>
                                            <i class="fas fa-exchange-alt text-[10px] text-gray-400 group-hover:text-brand ml-1"></i>
                                        </button>
                                    @else
                                        <button 
                                            onclick="openAssignRmModal('{{ $broker->id }}', '{{ addslashes($fullName) }}', '')" 
                                            class="bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-xl text-xs font-bold flex items-center gap-1.5 transition cursor-pointer"
                                        >
                                            <i class="fas fa-user-plus text-[10px]"></i> Assign RM
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <!-- PGs Listed -->
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                <span class="bg-gray-100 text-gray-800 text-xs px-2.5 py-1 rounded-lg">
                                    {{ $broker->properties->count() }} PGs
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <span id="kyc-badge-{{ $broker->id }}" class="text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 w-fit {{ $isKycVerified ? 'text-emerald-700 bg-emerald-50' : 'text-amber-800 bg-amber-100' }}">
                                        <i class="fas {{ $isKycVerified ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                        {{ $isKycVerified ? 'KYC VERIFIED' : 'KYC PENDING' }}
                                    </span>
                                    <span id="status-badge-{{ $broker->id }}" class="status-badge text-[10px] font-bold px-2 py-0.5 rounded-md uppercase inline-block {{ $isActive ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                        {{ $isActive ? 'ACTIVE' : ($isPending ? 'PENDING' : strtoupper($broker->status)) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Details Modal -->
                                    <button onclick="viewBrokerDetails('{{ $broker->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-600 flex items-center justify-center tap-effect transition" title="View Full Details & KYC">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <!-- 1-Click Approve (if pending) -->
                                    @if(!$isKycVerified || $broker->status !== 'active')
                                        <button onclick="approveBrokerDirect('{{ $broker->id }}')" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center tap-effect transition" title="1-Click Approve KYC">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                    @endif

                                    <!-- Toggle Active / Suspend -->
                                    <button onclick="toggleBrokerStatusDirect('{{ $broker->id }}')" class="w-8 h-8 rounded-lg {{ $isActive ? 'bg-amber-50 hover:bg-amber-100 text-amber-600' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600' }} flex items-center justify-center tap-effect transition" title="{{ $isActive ? 'Suspend Broker' : 'Activate Broker' }}">
                                        <i class="fas {{ $isActive ? 'fa-ban' : 'fa-play' }} text-xs"></i>
                                    </button>

                                    <!-- Delete Broker -->
                                    <button onclick="deleteBrokerDirect('{{ $broker->id }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center tap-effect transition" title="Remove Broker">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-400 text-sm">
                                <i class="fas fa-user-slash text-3xl mb-3 text-gray-300 block"></i>
                                No partner brokers found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card Listing -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($brokers as $broker)
                @php
                    $fullName = $broker->profile->full_name ?? ($broker->name ?? $broker->email);
                    $company = $broker->profile->company_name ?? 'Individual Partner';
                    $isKycVerified = !empty($broker->kyc_verified_at);
                    $isActive = ($broker->status === 'active');
                    $rm = $broker->relationshipManager;
                @endphp
                <div id="broker-mobile-card-{{ $broker->id }}" class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($fullName, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">{{ $fullName }}</div>
                                <div class="text-xs text-gray-400">{{ $company }}</div>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $isActive ? 'ACTIVE' : 'PENDING' }}
                        </span>
                    </div>

                    <!-- Assigned RM Mobile Pill -->
                    <div class="bg-gray-50 p-2.5 rounded-xl flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-headset text-brand"></i>
                            <span class="text-gray-500 font-medium">Relationship Manager:</span>
                            <span class="font-bold text-gray-900">{{ $rm ? $rm->name : 'Unassigned' }}</span>
                        </div>
                        <button onclick="openAssignRmModal('{{ $broker->id }}', '{{ addslashes($fullName) }}', '{{ $rm?->id }}')" class="text-brand font-bold text-[11px] underline">
                            {{ $rm ? 'Change' : 'Assign' }}
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-xs">
                        <span class="text-gray-500">{{ $broker->phone ?? $broker->email }}</span>
                        <div class="flex items-center gap-2">
                            <button onclick="viewBrokerDetails('{{ $broker->id }}')" class="text-brand font-bold">Details</button>
                            <button onclick="toggleBrokerStatusDirect('{{ $broker->id }}')" class="text-gray-600 font-semibold">{{ $isActive ? 'Suspend' : 'Activate' }}</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm">No brokers found.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($brokers->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $brokers->links() }}
            </div>
        @endif
    </div>

</div>

<!-- 1-Click Assign Relationship Manager Modal -->
<div id="assignRmModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-gray-100 space-y-5 animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Assign Relationship Manager</h3>
                <p id="assignRmBrokerName" class="text-xs text-gray-500 font-semibold text-brand mt-0.5">Broker: Vikram Singh</p>
            </div>
            <button onclick="closeModal('assignRmModal')" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center tap-effect">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="assignRmForm" onsubmit="handleAssignRmSubmit(event)" class="space-y-4">
            <input type="hidden" id="assignRmBrokerId" value="">

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Select Dedicated Relationship Manager <span class="text-red-500">*</span></label>
                <select id="assignRmSelect" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                    <option value="">-- Choose Relationship Manager --</option>
                    @foreach($relationshipManagers as $rm)
                        <option value="{{ $rm->id }}">
                            {{ $rm->name }} • {{ $rm->zone }} ({{ $rm->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="p-3 bg-brand-50 rounded-2xl border border-brand-100 text-xs text-brand-dark space-y-1">
                <div class="font-bold flex items-center gap-1.5"><i class="fas fa-info-circle text-brand"></i> Real-time Portal Sync:</div>
                <p>The broker's portal and support desk card will immediately update to show this manager's direct WhatsApp chat, call link, and working hours.</p>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="notifyBrokerCheck" checked class="w-4 h-4 accent-brand cursor-pointer">
                <label for="notifyBrokerCheck" class="text-xs font-semibold text-gray-700 cursor-pointer">Send instant in-app notification to broker</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
                <button type="button" onclick="closeModal('assignRmModal')" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs tap-effect transition">
                    Cancel
                </button>
                <button type="submit" id="submitAssignRmBtn" class="px-6 py-2.5 bg-brand hover:bg-brand-dark text-white font-bold rounded-xl text-xs tap-effect shadow-md transition flex items-center gap-2">
                    <i class="fas fa-check"></i> <span>Assign Manager</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Relationship Managers Team Management Modal -->
<div id="rmTeamManagementModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-3xl w-full shadow-2xl border border-gray-100 space-y-6 max-h-[90vh] overflow-y-auto animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h3 class="font-bold text-gray-900 text-xl flex items-center gap-2">
                    <i class="fas fa-users text-brand"></i> Relationship Managers Directory
                </h3>
                <p class="text-xs text-gray-500">Manage regional partner success leads, working hours, and workload distribution</p>
            </div>
            <button onclick="closeModal('rmTeamManagementModal')" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center tap-effect">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <!-- RM Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="rmCardsGrid">
            @foreach($relationshipManagers as $rm)
                <div id="rm-card-{{ $rm->id }}" class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3 relative group hover:border-brand/40 transition">
                    <div class="flex items-start gap-3">
                        <div class="relative w-12 h-12 rounded-xl bg-gradient-to-br from-brand to-brand-dark text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0 overflow-hidden border border-white">
                            @if($rm->avatar_url)
                                <img id="rm-avatar-img-{{ $rm->id }}" src="{{ $rm->avatar_url }}" alt="{{ $rm->name }}" class="w-full h-full object-cover">
                            @else
                                <span id="rm-avatar-init-{{ $rm->id }}">{{ strtoupper(substr($rm->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <h4 id="rm-name-{{ $rm->id }}" class="font-bold text-gray-900 text-sm truncate">{{ $rm->name }}</h4>
                                @if($rm->is_default)
                                    <span class="text-[9px] font-black bg-brand text-white px-2 py-0.5 rounded-full">DEFAULT</span>
                                @endif
                            </div>
                            <div id="rm-desig-{{ $rm->id }}" class="text-xs text-brand-dark font-semibold truncate">{{ $rm->designation }}</div>
                            <div id="rm-zone-{{ $rm->id }}" class="text-[11px] text-gray-500 truncate">{{ $rm->zone }}</div>
                        </div>
                    </div>

                    <div class="space-y-1 text-xs text-gray-600 border-t border-gray-200/60 pt-2 font-mono">
                        <div><i class="fas fa-phone-alt text-brand text-[10px] mr-1.5"></i> <span id="rm-phone-{{ $rm->id }}">{{ $rm->phone }}</span></div>
                        <div><i class="fab fa-whatsapp text-emerald-600 text-[10px] mr-1.5"></i> <span id="rm-wa-{{ $rm->id }}">{{ $rm->whatsapp_number ?? $rm->phone }}</span></div>
                        <div class="truncate"><i class="fas fa-envelope text-gray-400 text-[10px] mr-1.5"></i> <span id="rm-email-{{ $rm->id }}">{{ $rm->email }}</span></div>
                    </div>

                    <div class="flex items-center justify-between pt-1 border-t border-gray-200/60 text-xs">
                        <span class="text-gray-500 font-semibold">
                            <strong class="text-gray-900 font-bold">{{ $rm->brokers()->count() }}</strong> Brokers
                        </span>
                        <div class="flex items-center gap-2">
                            <!-- Edit Button -->
                            <button onclick="openEditRmModal('{{ $rm->id }}')" class="px-2.5 py-1 bg-white hover:bg-brand-50 text-gray-700 hover:text-brand border border-gray-200 rounded-lg text-xs font-bold tap-effect transition flex items-center gap-1 cursor-pointer">
                                <i class="fas fa-edit text-xs"></i> Edit
                            </button>
                            <!-- Test WhatsApp Link -->
                            <a href="https://wa.me/{{ $rm->whatsapp_number ?? preg_replace('/[^0-9]/', '', $rm->phone) }}" target="_blank" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold tap-effect transition flex items-center gap-1">
                                <i class="fab fa-whatsapp"></i> WA
                            </a>
                            <!-- Delete RM -->
                            <button onclick="deleteRmDirect('{{ $rm->id }}', '{{ addslashes($rm->name) }}')" class="w-7 h-7 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs flex items-center justify-center tap-effect transition cursor-pointer" title="Remove Relationship Manager">
                                <i class="fas fa-trash-alt text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add New RM Form Toggle -->
        <div class="border-t border-gray-100 pt-4">
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer p-3 bg-brand-50/50 hover:bg-brand-50 rounded-2xl text-xs font-bold text-brand-dark border border-brand-100 transition">
                    <span><i class="fas fa-user-plus mr-1.5"></i> Add New Relationship Manager to Team</span>
                    <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                </summary>

                <form id="createRmForm" onsubmit="handleCreateRm(event)" enctype="multipart/form-data" class="mt-4 space-y-3 p-4 bg-gray-50 rounded-2xl border border-gray-200">
                    @csrf
                    
                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-4 bg-white p-3 rounded-xl border border-gray-200">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xl overflow-hidden border border-gray-200 shrink-0">
                            <img id="newRmAvatarPreview" src="" class="hidden w-full h-full object-cover">
                            <i id="newRmAvatarIcon" class="fas fa-camera text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Profile Photo (Optional)</label>
                            <input type="file" name="avatar" accept="image/*" onchange="previewRmAvatar(this, 'newRmAvatarPreview', 'newRmAvatarIcon')" class="text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand hover:file:bg-brand-100 cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Full Name *</label>
                            <input type="text" name="name" required placeholder="e.g. Sanya Kapoor" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Official Email *</label>
                            <input type="email" name="email" required placeholder="e.g. sanya.rm@staynest.com" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Phone Number *</label>
                            <input type="text" name="phone" required placeholder="+91 98765 00000" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" placeholder="919876500000" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Designation *</label>
                            <input type="text" name="designation" required placeholder="e.g. Key Account Lead" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Assigned Zone / Region *</label>
                            <input type="text" name="zone" required placeholder="e.g. Central Zone (Indore, Bhopal)" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" id="submitCreateRmBtn" class="bg-brand hover:bg-brand-dark text-white font-bold px-5 py-2 rounded-xl text-xs tap-effect shadow-sm transition">
                            Save Relationship Manager
                        </button>
                    </div>
                </form>
            </details>
        </div>
    </div>
</div>

<!-- Edit Relationship Manager Modal -->
<div id="editRmModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-lg w-full shadow-2xl border border-gray-100 space-y-5 max-h-[90vh] overflow-y-auto animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                    <i class="fas fa-user-edit text-brand"></i> Edit Relationship Manager
                </h3>
                <p class="text-xs text-gray-500">Update profile details, contact numbers, and photo</p>
            </div>
            <button onclick="closeModal('editRmModal')" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center tap-effect">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="editRmForm" onsubmit="handleEditRmSubmit(event)" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" id="editRmId" value="">

            <!-- Profile Photo Upload / Preview -->
            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-200">
                <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-brand to-brand-dark text-white flex items-center justify-center font-bold text-lg shadow-sm overflow-hidden border-2 border-white shrink-0">
                    <img id="editRmAvatarPreview" src="" class="hidden w-full h-full object-cover">
                    <span id="editRmAvatarInitials">RM</span>
                </div>
                <div class="flex-1 space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Profile Photo</label>
                    <input type="file" id="editRmAvatarInput" name="avatar" accept="image/*" onchange="previewRmAvatar(this, 'editRmAvatarPreview', 'editRmAvatarInitials')" class="text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand hover:file:bg-brand-100 cursor-pointer">
                    <div class="text-[10px] text-gray-400">Supports JPG, PNG, WEBP up to 5MB</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="editRmName" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Official Email <span class="text-red-500">*</span></label>
                    <input type="email" id="editRmEmail" name="email" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" id="editRmPhone" name="phone" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">WhatsApp Number</label>
                    <input type="text" id="editRmWhatsapp" name="whatsapp_number" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Designation <span class="text-red-500">*</span></label>
                    <input type="text" id="editRmDesignation" name="designation" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Assigned Zone <span class="text-red-500">*</span></label>
                    <input type="text" id="editRmZone" name="zone" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">City Coverage</label>
                <input type="text" id="editRmCityCoverage" name="city_coverage" placeholder="e.g. Noida, Delhi, Greater Noida" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Working Hours</label>
                <input type="text" id="editRmWorkingHours" name="working_hours" placeholder="Mon - Sat: 9:00 AM - 7:00 PM" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand focus:bg-white transition">
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                    <input type="checkbox" id="editRmIsDefault" name="is_default" value="1" class="w-4 h-4 accent-brand cursor-pointer">
                    <span>Set as Default RM (for new brokers)</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('editRmModal')" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs tap-effect transition">
                    Cancel
                </button>
                <button type="submit" id="submitEditRmBtn" class="px-6 py-2.5 bg-brand hover:bg-brand-dark text-white font-bold rounded-xl text-xs tap-effect shadow-md transition flex items-center gap-2">
                    <i class="fas fa-save"></i> <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
    </div>
</div>

<!-- Add New Broker Modal -->
<div id="adminAddBrokerModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-lg w-full shadow-2xl border border-gray-100 space-y-6 max-h-[90vh] overflow-y-auto animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h3 class="font-bold text-gray-900 text-xl">Onboard Partner Broker</h3>
                <p class="text-xs text-gray-500">Create new verified broker credentials and assign relationship manager</p>
            </div>
            <button onclick="closeModal('adminAddBrokerModal')" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center tap-effect">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="adminCreateBrokerForm" onsubmit="handleCreateBroker(event)" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" required placeholder="e.g. Vikram" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Last Name</label>
                    <input type="text" name="last_name" placeholder="e.g. Singh" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required placeholder="vikram@broker.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" required placeholder="+91 98765 00000" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Agency / Company Name</label>
                <input type="text" name="company_name" placeholder="e.g. Singh Real Estate & PG Management" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Operating City</label>
                    <input type="text" name="city_name" placeholder="e.g. Noida, Bangalore" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Dedicated Relationship Manager</label>
                    <select name="relationship_manager_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
                        @foreach($relationshipManagers as $rm)
                            <option value="{{ $rm->id }}" {{ $rm->is_default ? 'selected' : '' }}>
                                {{ $rm->name }} ({{ $rm->zone }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Initial Login Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="6" placeholder="•••••••• (Min 6 chars)" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white transition">
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="auto_verify_kyc" id="autoVerifyCheck" value="1" checked class="w-4 h-4 accent-brand cursor-pointer">
                <label for="autoVerifyCheck" class="text-xs font-semibold text-gray-700 cursor-pointer">Auto-verify KYC and grant full portal access immediately</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('adminAddBrokerModal')" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs tap-effect transition">
                    Cancel
                </button>
                <button type="submit" id="submitAddBrokerBtn" class="px-6 py-2.5 bg-brand hover:bg-brand-dark text-white font-bold rounded-xl text-xs tap-effect shadow-md transition">
                    Create & Onboard
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Broker Details & KYC Modal -->
<div id="brokerKycModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-gray-100 space-y-5 animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="font-bold text-gray-900 text-lg">Partner Broker Profile</h3>
            <button onclick="closeModal('brokerKycModal')" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center tap-effect">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <div id="brokerKycBody">
            <div class="flex justify-center py-8">
                <i class="fas fa-circle-notch fa-spin text-brand text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Floating Toast Notification Anchor -->
<div id="brokerToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none">
    <div id="brokerToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
        <span id="brokerToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
        <span id="brokerToastMessage">Action completed</span>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';

    function showBrokerToast(message, type = 'success') {
        const toast = document.getElementById('brokerToastNotification');
        const msgEl = document.getElementById('brokerToastMessage');
        const iconEl = document.getElementById('brokerToastIcon');

        msgEl.textContent = message;
        if (type === 'error') {
            iconEl.innerHTML = '<i class="fas fa-exclamation-circle text-red-400"></i>';
        } else {
            iconEl.innerHTML = '<i class="fas fa-check-circle text-emerald-400"></i>';
        }

        toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
        }, 3500);
    }

    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
    }

    // 1-Click Assign RM Modal Opener
    function openAssignRmModal(brokerId, brokerName, currentRmId) {
        document.getElementById('assignRmBrokerId').value = brokerId;
        document.getElementById('assignRmBrokerName').textContent = 'Broker: ' + brokerName;
        document.getElementById('assignRmSelect').value = currentRmId || '';
        openModal('assignRmModal');
    }

    // Handle Assign RM Form Submission
    async function handleAssignRmSubmit(e) {
        e.preventDefault();
        const brokerId = document.getElementById('assignRmBrokerId').value;
        const rmId = document.getElementById('assignRmSelect').value;
        const notify = document.getElementById('notifyBrokerCheck').checked;
        const btn = document.getElementById('submitAssignRmBtn');

        if (!rmId) {
            showBrokerToast('Please select a Relationship Manager', 'error');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Assigning...';

        try {
            const res = await fetch(`/admin/brokers/${brokerId}/assign-rm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    relationship_manager_id: rmId,
                    notify_broker: notify
                })
            });

            const data = await res.json();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> <span>Assign Manager</span>';

            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                closeModal('assignRmModal');

                // Update Table Cell dynamically
                const rmCell = document.getElementById(`rm-cell-${brokerId}`);
                if (rmCell && data.manager) {
                    rmCell.innerHTML = `
                        <button 
                            onclick="openAssignRmModal('${brokerId}', '', '${data.manager.id}')" 
                            class="flex items-center gap-2 text-left bg-gray-50 hover:bg-brand-50/60 p-1.5 pr-2.5 rounded-xl border border-gray-200 transition group cursor-pointer"
                            title="Click to reassign Relationship Manager"
                        >
                            <div class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">
                                ${data.manager.name.substring(0, 1)}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-gray-900 group-hover:text-brand transition truncate">${data.manager.name}</div>
                                <div class="text-[10px] text-gray-400 truncate">${data.manager.zone}</div>
                            </div>
                            <i class="fas fa-exchange-alt text-[10px] text-gray-400 group-hover:text-brand ml-1"></i>
                        </button>
                    `;
                }
            } else {
                showBrokerToast(data.message || 'Failed to assign manager', 'error');
            }
        } catch (err) {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> <span>Assign Manager</span>';
            showBrokerToast('Error communicating with server', 'error');
        }
    }

    // Checkbox & Bulk Actions
    function toggleSelectAll(master) {
        document.querySelectorAll('.broker-checkbox').forEach(cb => cb.checked = master.checked);
        updateBulkBar();
    }

    function handleBrokerRowCheckbox() {
        const total = document.querySelectorAll('.broker-checkbox').length;
        const checked = document.querySelectorAll('.broker-checkbox:checked').length;
        const master = document.getElementById('selectAllCheckbox');
        if (master) master.checked = (total > 0 && total === checked);
        updateBulkBar();
    }

    function updateBulkBar() {
        const checked = document.querySelectorAll('.broker-checkbox:checked');
        const bar = document.getElementById('bulkActionBar');
        const countText = document.getElementById('selectedBrokersCount');

        if (checked.length > 0) {
            countText.textContent = `${checked.length} broker(s) selected`;
            bar.classList.remove('hidden');
        } else {
            bar.classList.add('hidden');
        }
    }

    function deselectAllBrokers() {
        document.querySelectorAll('.broker-checkbox').forEach(cb => cb.checked = false);
        const master = document.getElementById('selectAllCheckbox');
        if (master) master.checked = false;
        updateBulkBar();
    }

    async function handleBulkAssignRm() {
        const rmId = document.getElementById('bulkRmSelector').value;
        if (!rmId) {
            showBrokerToast('Please choose a Relationship Manager from the dropdown.', 'error');
            return;
        }

        const checkedBoxes = Array.from(document.querySelectorAll('.broker-checkbox:checked'));
        const brokerIds = checkedBoxes.map(cb => cb.value);

        if (brokerIds.length === 0) {
            showBrokerToast('No brokers selected', 'error');
            return;
        }

        try {
            const res = await fetch('{{ route('admin.brokers.bulk-assign-rm') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    broker_ids: brokerIds,
                    relationship_manager_id: rmId,
                    notify_brokers: true
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                deselectAllBrokers();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showBrokerToast(data.message || 'Bulk assignment failed', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Server error during bulk assignment', 'error');
        }
    }

    // Auto-assign RMs by Zone
    async function triggerAutoAssignRm() {
        if (!confirm('Auto-assign all unassigned brokers to regional Relationship Managers based on city zones?')) return;

        try {
            const res = await fetch('{{ route('admin.brokers.auto-assign-rm') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await res.json();
            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showBrokerToast(data.message || 'Auto-assignment failed', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Error during auto-assignment', 'error');
        }
    }

    // Image preview helper
    function previewRmAvatar(input, previewImgId, placeholderId) {
        const previewImg = document.getElementById(previewImgId);
        const placeholder = document.getElementById(placeholderId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Open Edit RM Modal and populate fields
    async function openEditRmModal(rmId) {
        document.getElementById('editRmId').value = rmId;
        const btn = document.getElementById('submitEditRmBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';
        openModal('editRmModal');

        try {
            const res = await fetch(`/admin/relationship-managers/${rmId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> <span>Save Changes</span>';

            if (res.ok && data.success && data.manager) {
                const m = data.manager;
                document.getElementById('editRmName').value = m.name || '';
                document.getElementById('editRmEmail').value = m.email || '';
                document.getElementById('editRmPhone').value = m.phone || '';
                document.getElementById('editRmWhatsapp').value = m.whatsapp_number || '';
                document.getElementById('editRmDesignation').value = m.designation || '';
                document.getElementById('editRmZone').value = m.zone || '';
                document.getElementById('editRmCityCoverage').value = m.city_coverage || '';
                document.getElementById('editRmWorkingHours').value = m.working_hours || '';
                document.getElementById('editRmIsDefault').checked = !!m.is_default;

                // Photo preview
                const previewImg = document.getElementById('editRmAvatarPreview');
                const initials = document.getElementById('editRmAvatarInitials');
                if (m.avatar_url) {
                    previewImg.src = m.avatar_url;
                    previewImg.classList.remove('hidden');
                    initials.classList.add('hidden');
                } else {
                    previewImg.classList.add('hidden');
                    initials.textContent = (m.name || 'RM').substring(0, 2).toUpperCase();
                    initials.classList.remove('hidden');
                }
            } else {
                showBrokerToast(data.message || 'Failed to load Relationship Manager details', 'error');
            }
        } catch (err) {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> <span>Save Changes</span>';
            showBrokerToast('Network error while loading RM details', 'error');
        }
    }

    // Handle Edit RM Form Submission (Supports multipart FormData & image upload)
    async function handleEditRmSubmit(e) {
        e.preventDefault();
        const rmId = document.getElementById('editRmId').value;
        const form = document.getElementById('editRmForm');
        const btn = document.getElementById('submitEditRmBtn');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving Changes...';

        try {
            const res = await fetch(`/admin/relationship-managers/${rmId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await res.json();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> <span>Save Changes</span>';

            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                closeModal('editRmModal');

                // Dynamically update card in directory if present
                const m = data.manager;
                if (m) {
                    const nameEl = document.getElementById(`rm-name-${rmId}`);
                    const desigEl = document.getElementById(`rm-desig-${rmId}`);
                    const zoneEl = document.getElementById(`rm-zone-${rmId}`);
                    const phoneEl = document.getElementById(`rm-phone-${rmId}`);
                    const waEl = document.getElementById(`rm-wa-${rmId}`);
                    const emailEl = document.getElementById(`rm-email-${rmId}`);
                    const avatarImg = document.getElementById(`rm-avatar-img-${rmId}`);
                    const avatarInit = document.getElementById(`rm-avatar-init-${rmId}`);

                    if (nameEl) nameEl.textContent = m.name;
                    if (desigEl) desigEl.textContent = m.designation;
                    if (zoneEl) zoneEl.textContent = m.zone;
                    if (phoneEl) phoneEl.textContent = m.phone;
                    if (waEl) waEl.textContent = m.whatsapp_number || m.phone;
                    if (emailEl) emailEl.textContent = m.email;

                    if (m.avatar_url) {
                        if (avatarImg) {
                            avatarImg.src = m.avatar_url;
                            avatarImg.classList.remove('hidden');
                        }
                        if (avatarInit) avatarInit.classList.add('hidden');
                    }
                }

                setTimeout(() => window.location.reload(), 1000);
            } else {
                let errMsg = data.message || 'Failed to update Relationship Manager';
                if (data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    if (firstKey && data.errors[firstKey][0]) errMsg = data.errors[firstKey][0];
                }
                showBrokerToast(errMsg, 'error');
            }
        } catch (err) {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> <span>Save Changes</span>';
            showBrokerToast('Network error while updating Relationship Manager', 'error');
        }
    }

    // Delete RM Direct
    async function deleteRmDirect(rmId, rmName) {
        if (!confirm(`Are you sure you want to remove Relationship Manager "${rmName}"? Their assigned brokers will be reallocated.`)) return;

        try {
            const res = await fetch(`/admin/relationship-managers/${rmId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                const card = document.getElementById(`rm-card-${rmId}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
                }
            } else {
                showBrokerToast(data.message || 'Failed to delete manager', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Network error while deleting RM', 'error');
        }
    }

    // Create New RM Form
    async function handleCreateRm(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('submitCreateRmBtn');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';

        try {
            const res = await fetch('{{ route('admin.relationship-managers.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await res.json();
            btn.disabled = false;
            btn.textContent = 'Save Relationship Manager';

            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                form.reset();
                setTimeout(() => window.location.reload(), 900);
            } else {
                showBrokerToast(data.message || 'Failed to save Relationship Manager', 'error');
            }
        } catch (err) {
            console.error(err);
            btn.disabled = false;
            btn.textContent = 'Save Relationship Manager';
            showBrokerToast('Error creating Relationship Manager', 'error');
        }
    }

    // 1-Click Approve Broker
    async function approveBrokerDirect(brokerId) {
        try {
            const res = await fetch(`/admin/brokers/${brokerId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                const badge = document.getElementById(`kyc-badge-${brokerId}`);
                if (badge) {
                    badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 w-fit text-emerald-700 bg-emerald-50';
                    badge.innerHTML = '<i class="fas fa-check-circle"></i> KYC VERIFIED';
                }
                const statusBadge = document.getElementById(`status-badge-${brokerId}`);
                if (statusBadge) {
                    statusBadge.className = 'status-badge text-[10px] font-bold px-2 py-0.5 rounded-md uppercase bg-emerald-100 text-emerald-700';
                    statusBadge.textContent = 'ACTIVE';
                }
            } else {
                showBrokerToast(data.message || 'Failed to approve broker', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Network error while approving broker', 'error');
        }
    }

    // 1-Click Toggle Broker Status
    async function toggleBrokerStatusDirect(brokerId) {
        try {
            const res = await fetch(`/admin/brokers/${brokerId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                const statusBadge = document.getElementById(`status-badge-${brokerId}`);
                if (data.is_active) {
                    if (statusBadge) {
                        statusBadge.className = 'status-badge text-[10px] font-bold px-2 py-0.5 rounded-md uppercase bg-emerald-100 text-emerald-700';
                        statusBadge.textContent = 'ACTIVE';
                    }
                } else {
                    if (statusBadge) {
                        statusBadge.className = 'status-badge text-[10px] font-bold px-2 py-0.5 rounded-md uppercase bg-red-100 text-red-700';
                        statusBadge.textContent = 'SUSPENDED';
                    }
                }
            } else {
                showBrokerToast(data.message || 'Failed to update status', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Network error while toggling status', 'error');
        }
    }

    // 1-Click Delete Broker
    async function deleteBrokerDirect(brokerId) {
        if (!confirm('Are you sure you want to remove this broker account?')) return;

        try {
            const res = await fetch(`/admin/brokers/${brokerId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showBrokerToast(data.message, 'success');
                const row = document.getElementById(`broker-row-${brokerId}`);
                if (row) {
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.95)';
                    setTimeout(() => row.remove(), 250);
                }
            } else {
                showBrokerToast(data.message || 'Failed to delete broker', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Network error while deleting broker', 'error');
        }
    }

    // Handle Create Broker Form (AJAX)
    async function handleCreateBroker(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('submitAddBrokerBtn');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1"></i> Creating Broker...';

        try {
            const res = await fetch('{{ route('admin.brokers.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showBrokerToast(data.message || 'Broker created successfully!', 'success');
                closeModal('adminAddBrokerModal');
                form.reset();
                setTimeout(() => window.location.reload(), 800);
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create & Onboard';
                let errMsg = data.message || 'Failed to create broker';
                if (data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    if (firstKey && data.errors[firstKey][0]) {
                        errMsg = data.errors[firstKey][0];
                    }
                }
                showBrokerToast(errMsg, 'error');
            }
        } catch (err) {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create & Onboard';
            showBrokerToast('Connection error. Please try again.', 'error');
        }
    }

    // View Broker Details & KYC Modal
    async function viewBrokerDetails(brokerId) {
        openModal('brokerKycModal');
        const bodyEl = document.getElementById('brokerKycBody');

        bodyEl.innerHTML = `
            <div class="flex justify-center py-8">
                <i class="fas fa-circle-notch fa-spin text-brand text-2xl"></i>
            </div>
        `;

        try {
            const res = await fetch(`/admin/brokers/${brokerId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (res.ok && data.success && data.broker) {
                const b = data.broker;
                const rm = b.relationship_manager;
                bodyEl.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 bg-gray-50 p-3.5 rounded-2xl">
                            <div class="w-12 h-12 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-base shadow-xs">
                                ${b.name.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 text-base truncate">${b.name}</div>
                                <div class="text-xs text-gray-500 truncate">${b.company_name}</div>
                            </div>
                        </div>

                        <!-- Assigned RM Widget in Details Modal -->
                        <div class="p-3.5 bg-brand-50/70 border border-brand-100 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-brand-dark flex items-center gap-1.5">
                                    <i class="fas fa-headset text-brand"></i> Assigned Relationship Manager
                                </span>
                                <button onclick="closeModal('brokerKycModal'); openAssignRmModal('${b.id}', '${b.name}', '${rm ? rm.id : ''}')" class="text-brand font-bold text-[11px] hover:underline">
                                    ${rm ? 'Change' : 'Assign'}
                                </button>
                            </div>
                            ${rm ? `
                                <div class="text-xs font-bold text-gray-900">${rm.name} <span class="font-normal text-gray-500">(${rm.zone})</span></div>
                                <div class="text-[11px] text-gray-600 font-mono">Phone: ${rm.phone} • WhatsApp: ${rm.whatsapp}</div>
                            ` : `
                                <div class="text-xs text-amber-700 font-semibold">⚠️ No Relationship Manager assigned yet.</div>
                            `}
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Email</span>
                                <span class="font-bold text-gray-900 font-mono">${b.email}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Phone</span>
                                <span class="font-bold text-gray-900 font-mono">${b.phone}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Operating Region</span>
                                <span class="font-bold text-gray-900">${b.cities}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Active Properties</span>
                                <span class="font-bold text-brand">${b.properties_count} PGs Listed</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">KYC Verification</span>
                                <span class="font-bold ${b.kyc_status === 'VERIFIED' ? 'text-emerald-600' : 'text-amber-600'}">${b.kyc_status}</span>
                            </div>
                        </div>
                    </div>
                `;
            }
        } catch (err) {
            console.error(err);
            bodyEl.innerHTML = `<div class="text-center text-red-500 text-xs py-4">Failed to load broker profile.</div>`;
        }
    }
</script>
@endpush
@endsection
