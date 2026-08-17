@extends('admin.layouts.app')

@section('title', 'Manage Brokers')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manage Partner Brokers</h1>
        <p class="text-sm text-gray-500">{{ $approvedCount }} verified brokers • {{ $pendingCount }} pending identity & property verification</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('adminAddBrokerModal')" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2 cursor-pointer">
            <i class="fas fa-user-plus text-sm"></i> Add Partner Broker
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Flash Alert / Toast Anchor -->
    <div id="brokerToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="brokerToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="brokerToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="brokerToastMessage">Action completed</span>
        </div>
    </div>

    <!-- Mobile Add Button -->
    <div class="lg:hidden">
        <button onclick="openModal('adminAddBrokerModal')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2 cursor-pointer">
            <i class="fas fa-user-plus"></i> Add New Partner Broker
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.brokers', ['tab' => 'ALL']) }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-brand/40 transition">
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Brokers</div>
        </a>
        <a href="{{ route('admin.brokers', ['tab' => 'PENDING']) }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-yellow-300 transition">
            <div class="text-2xl md:text-3xl font-extrabold text-amber-600">{{ number_format($pendingCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Approval / KYC</div>
        </a>
        <a href="{{ route('admin.brokers', ['tab' => 'APPROVED']) }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-emerald-300 transition">
            <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($approvedCount) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Approved & Active</div>
        </a>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-brand">₹{{ number_format($totalCommission / 100000, 2) }}L</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Commission & Payouts</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        
        <!-- Tabs & Search Header -->
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Tabs -->
            <div class="flex border-b md:border-b-0 border-gray-100 space-x-2 overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.brokers', ['tab' => 'ALL', 'search' => request('search')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($currentTab ?? 'ALL') === 'ALL' ? 'bg-brand-light text-brand border border-brand/20' : 'text-gray-600 hover:bg-gray-50' }}">
                    All Brokers ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.brokers', ['tab' => 'PENDING', 'search' => request('search')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($currentTab ?? '') === 'PENDING' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    Pending Approval ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.brokers', ['tab' => 'APPROVED', 'search' => request('search')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ ($currentTab ?? '') === 'APPROVED' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    Approved ({{ $approvedCount }})
                </a>
                <a href="{{ route('admin.brokers', ['tab' => 'REJECTED', 'search' => request('search')]) }}" class="px-4 py-2.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ in_array(($currentTab ?? ''), ['REJECTED', 'SUSPENDED']) ? 'bg-red-50 text-red-700 border border-red-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    Suspended ({{ $rejectedCount }})
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.brokers') }}" class="relative w-full md:w-80">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                <input 
                    type="text" 
                    name="search" 
                    id="brokerSearchInput" 
                    value="{{ request('search') }}" 
                    placeholder="Search broker, email, phone or city..." 
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-9 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition"
                >
                @if(request('search'))
                    <a href="{{ route('admin.brokers', ['tab' => $currentTab]) }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left" id="brokerTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Broker Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Operating Region</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">PGs Listed</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">KYC Status</th>
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
                                $operatingCities = 'Delhi NCR / Regional';
                            }
                            
                            $isKycVerified = !empty($broker->kyc_verified_at);
                            $isActive = ($broker->status === 'active');
                            $isPending = ($broker->status === 'pending_verification' || !$isKycVerified);
                        @endphp
                        <tr id="broker-row-{{ $broker->id }}" class="broker-row hover:bg-gray-50/70 transition">
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

                            <!-- PGs Listed -->
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                <span class="bg-gray-100 text-gray-800 text-xs px-2.5 py-1 rounded-lg">
                                    {{ $broker->properties->count() }} PGs
                                </span>
                            </td>

                            <!-- KYC Status -->
                            <td class="px-6 py-4">
                                <span id="kyc-badge-{{ $broker->id }}" class="text-xs font-semibold px-2.5 py-1 rounded-lg flex items-center gap-1 w-fit {{ $isKycVerified ? 'text-emerald-700 bg-emerald-50' : 'text-amber-800 bg-amber-100' }}">
                                    <i class="fas {{ $isKycVerified ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                    {{ $isKycVerified ? 'VERIFIED' : 'PENDING' }}
                                </span>
                            </td>

                            <!-- Approval Status -->
                            <td class="px-6 py-4">
                                <span id="status-badge-{{ $broker->id }}" class="status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase {{ $isActive ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                    {{ $isActive ? 'APPROVED' : ($isPending ? 'PENDING' : strtoupper($broker->status)) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Profile & KYC -->
                                    <button type="button" onclick="viewBrokerDetails('{{ $broker->id }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect cursor-pointer" title="View Profile & KYC">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <!-- 1-Click Approve KYC (if pending) -->
                                    @if(!$isKycVerified || $broker->status === 'pending_verification')
                                        <button type="button" id="approve-btn-{{ $broker->id }}" onclick="approveBrokerDirect('{{ $broker->id }}')" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl tap-effect shadow-xs flex items-center gap-1 cursor-pointer" title="Verify KYC & Approve">
                                            <i class="fas fa-check text-[10px]"></i> Approve
                                        </button>
                                    @endif

                                    <!-- Status Toggle (Active / Suspend) -->
                                    <button type="button" onclick="toggleBrokerStatusDirect('{{ $broker->id }}')" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100 flex items-center justify-center tap-effect cursor-pointer" title="Toggle Status">
                                        <i class="fas fa-ban text-xs"></i>
                                    </button>

                                    <!-- Delete -->
                                    <button type="button" onclick="deleteBrokerDirect('{{ $broker->id }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect cursor-pointer" title="Remove Broker">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-2 text-xl">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <div class="text-sm font-semibold text-gray-600">No partner brokers found</div>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting the filter tab or add a new partner broker.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards View -->
        <div class="md:hidden divide-y divide-gray-100" id="brokerMobileList">
            @forelse($brokers as $broker)
                @php
                    $fullName = $broker->profile->full_name ?? ($broker->name ?? $broker->email);
                    $firstName = $broker->profile->first_name ?? $fullName;
                    $lastName = $broker->profile->last_name ?? '';
                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                    if (empty(trim($initials))) $initials = 'BR';
                    
                    $isKycVerified = !empty($broker->kyc_verified_at);
                    $isActive = ($broker->status === 'active');
                    $isPending = ($broker->status === 'pending_verification' || !$isKycVerified);
                @endphp
                <div id="broker-mobile-card-{{ $broker->id }}" class="p-4 broker-card space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                            {{ $initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-1">
                                <h3 class="font-bold text-gray-900 broker-name text-sm truncate">{{ $fullName }}</h3>
                                <span id="mobile-status-badge-{{ $broker->id }}" class="status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 {{ $isActive ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                    {{ $isActive ? 'APPROVED' : ($isPending ? 'PENDING' : strtoupper($broker->status)) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 broker-phone"><i class="fas fa-phone text-brand text-[10px]"></i> {{ $broker->phone ?? 'No Phone' }}</p>
                            <p class="text-xs text-gray-500 broker-city mt-0.5"><i class="fas fa-building text-brand text-[10px]"></i> {{ $broker->properties->count() }} Properties Listed</p>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-2 border-t border-gray-100">
                        <button type="button" onclick="viewBrokerDetails('{{ $broker->id }}')" class="flex-1 bg-blue-50 text-blue-600 text-xs font-semibold py-2 rounded-lg tap-effect">
                            <i class="fas fa-eye mr-1"></i> Profile
                        </button>
                        @if(!$isKycVerified || $broker->status === 'pending_verification')
                            <button type="button" id="mobile-approve-btn-{{ $broker->id }}" onclick="approveBrokerDirect('{{ $broker->id }}')" class="flex-1 bg-emerald-600 text-white text-xs font-semibold py-2 rounded-lg tap-effect">
                                <i class="fas fa-check mr-1"></i> Approve
                            </button>
                        @endif
                        <button type="button" onclick="toggleBrokerStatusDirect('{{ $broker->id }}')" class="flex-1 bg-yellow-50 text-yellow-700 text-xs font-semibold py-2 rounded-lg tap-effect">
                            <i class="fas fa-ban mr-1"></i> Status
                        </button>
                        <button type="button" onclick="deleteBrokerDirect('{{ $broker->id }}')" class="w-9 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect flex items-center justify-center">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-user-slash text-2xl text-gray-300 mb-2"></i>
                    <div class="text-sm font-semibold text-gray-600">No brokers found in this category</div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($brokers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Showing <span class="font-bold text-gray-900">{{ $brokers->firstItem() }}</span> to <span class="font-bold text-gray-900">{{ $brokers->lastItem() }}</span> of <span class="font-bold text-gray-900">{{ $brokers->total() }}</span> brokers
                </div>
                <div>
                    {{ $brokers->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- 1. Add New Broker Modal -->
<div id="adminAddBrokerModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full max-h-[92vh] overflow-y-auto shadow-2xl animate-scale-up">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Add Partner Broker</h3>
                <p class="text-xs text-gray-500">Onboard a verified property broker to the StayNest network</p>
            </div>
            <button onclick="closeModal('adminAddBrokerModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200 cursor-pointer">
                <i class="fas fa-times text-gray-500 text-sm"></i>
            </button>
        </div>

        <form id="adminAddBrokerForm" onsubmit="handleCreateBroker(event)" class="p-6 space-y-4">
            <!-- First Name & Last Name -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">First Name *</label>
                    <input type="text" name="first_name" required placeholder="e.g. Ramesh" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Last Name</label>
                    <input type="text" name="last_name" placeholder="e.g. Gupta" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <!-- Email & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address *</label>
                    <input type="email" name="email" required placeholder="ramesh@broker.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number *</label>
                    <input type="text" name="phone" required placeholder="+91 98765 00000" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <!-- Agency / Company Name -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Agency / Company Name</label>
                <input type="text" name="company_name" placeholder="e.g. Gupta Prime Properties & Stays" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
            </div>

            <!-- Password & Operating City -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Initial Password *</label>
                    <input type="text" name="password" value="broker123" required minlength="6" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Primary Operating City *</label>
                    <select name="city_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-800">
                        <option value="Pan-India / Regional">Pan-India / Regional</option>
                        @foreach($cities as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Auto Verify KYC Checkbox -->
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="auto_verify_kyc" value="1" checked id="autoVerifyKycCheck" class="w-4 h-4 text-brand rounded border-gray-300 focus:ring-brand/50 accent-brand">
                <label for="autoVerifyKycCheck" class="text-xs font-semibold text-gray-700 cursor-pointer select-none">
                    Instant KYC Verify & Approve (Grants direct portal access)
                </label>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('adminAddBrokerModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect cursor-pointer">Cancel</button>
                <button type="submit" id="submitAddBrokerBtn" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 cursor-pointer">Create & Onboard</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Broker KYC & Profile Modal -->
<div id="brokerKycModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden animate-scale-up">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Broker KYC & Agency Profile</h3>
            <button onclick="closeModal('brokerKycModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200 cursor-pointer">
                <i class="fas fa-times text-gray-500 text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 text-sm" id="brokerKycBody">
            <div class="flex justify-center py-8">
                <i class="fas fa-circle-notch fa-spin text-brand text-2xl"></i>
            </div>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-3xl text-right">
            <button onclick="closeModal('brokerKycModal')" class="px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs rounded-xl tap-effect cursor-pointer">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    // Dynamic Toast Messenger
    function showBrokerToast(message, type = 'success') {
        const toast = document.getElementById('brokerToastNotification');
        const text = document.getElementById('brokerToastMessage');
        const icon = document.getElementById('brokerToastIcon');

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

    // 1-Click Approve Broker KYC
    async function approveBrokerDirect(brokerId) {
        if (!confirm('Approve and verify this partner broker KYC?')) return;

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

                // Update KYC & Status badges
                const kycBadge = document.getElementById(`kyc-badge-${brokerId}`);
                if (kycBadge) {
                    kycBadge.className = 'text-xs font-semibold px-2.5 py-1 rounded-lg flex items-center gap-1 w-fit text-emerald-700 bg-emerald-50';
                    kycBadge.innerHTML = '<i class="fas fa-check-circle"></i> VERIFIED';
                }

                const statusBadge = document.getElementById(`status-badge-${brokerId}`);
                if (statusBadge) {
                    statusBadge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-emerald-100 text-emerald-700';
                    statusBadge.textContent = 'APPROVED';
                }

                const mobileStatus = document.getElementById(`mobile-status-badge-${brokerId}`);
                if (mobileStatus) {
                    mobileStatus.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-emerald-100 text-emerald-700';
                    mobileStatus.textContent = 'APPROVED';
                }

                // Remove approve button
                const btn = document.getElementById(`approve-btn-${brokerId}`);
                if (btn) btn.remove();
                const mBtn = document.getElementById(`mobile-approve-btn-${brokerId}`);
                if (mBtn) mBtn.remove();
            } else {
                showBrokerToast(data.message || 'Error approving broker', 'error');
            }
        } catch (err) {
            console.error(err);
            showBrokerToast('Network error while approving broker', 'error');
        }
    }

    // 1-Click Toggle Active / Suspended
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
                const mobileStatus = document.getElementById(`mobile-status-badge-${brokerId}`);

                if (data.is_active) {
                    if (statusBadge) {
                        statusBadge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-emerald-100 text-emerald-700';
                        statusBadge.textContent = 'APPROVED';
                    }
                    if (mobileStatus) {
                        mobileStatus.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-emerald-100 text-emerald-700';
                        mobileStatus.textContent = 'APPROVED';
                    }
                } else {
                    if (statusBadge) {
                        statusBadge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-red-100 text-red-700';
                        statusBadge.textContent = 'SUSPENDED';
                    }
                    if (mobileStatus) {
                        mobileStatus.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-red-100 text-red-700';
                        mobileStatus.textContent = 'SUSPENDED';
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

                const card = document.getElementById(`broker-mobile-card-${brokerId}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
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

                        <div class="space-y-2.5 text-xs">
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
                                <span class="text-gray-500 font-medium">Wallet Balance</span>
                                <span class="font-bold text-gray-900 font-mono">₹${Number(b.wallet_balance).toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">KYC Verification</span>
                                <span class="font-bold ${b.kyc_status === 'VERIFIED' ? 'text-emerald-600' : 'text-amber-600'}">${b.kyc_status}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-xl text-xs text-emerald-900 space-y-1">
                            <div class="font-bold text-emerald-800">Verified Credentials Checklist:</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-600 text-[10px]"></i> Government Photo ID & Verification</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-600 text-[10px]"></i> Bank Settlement & Payout Account</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-600 text-[10px]"></i> Partner Service Agreement</div>
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
