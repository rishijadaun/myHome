@extends('admin.layouts.app')

@section('title', 'Broker KYC & Document Verifications - SpaceSeeks Admin')

@section('content')
<style>
    /* Custom High-Contrast & Premium Modal Styling */
    .kyc-modal-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #134e4a 100%) !important;
        color: #ffffff !important;
    }
    .kyc-btn-approve-all {
        background: #0d9488 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35) !important;
    }
    .kyc-btn-approve-all:hover {
        background: #0f766e !important;
    }
    .kyc-btn-reject-all {
        background: #fff1f2 !important;
        color: #be123c !important;
        border: 1px solid #fecdd3 !important;
    }
    .kyc-btn-reject-all:hover {
        background: #ffe4e6 !important;
        color: #9f1239 !important;
    }
    .kyc-btn-action-view {
        background: #ffffff !important;
        color: #0f766e !important;
        border: 1px solid #99f6e4 !important;
    }
    .kyc-btn-action-view:hover {
        background: #f0fdfa !important;
        border-color: #5eead4 !important;
    }
    .kyc-btn-action-verify {
        background: #0d9488 !important;
        color: #ffffff !important;
    }
    .kyc-btn-action-verify:hover {
        background: #0f766e !important;
    }
    .kyc-btn-action-reject {
        background: #fff1f2 !important;
        color: #e11d48 !important;
        border: 1px solid #fecdd3 !important;
    }
    .kyc-btn-action-reject:hover {
        background: #ffe4e6 !important;
        color: #be123c !important;
    }
    .animate-scaleUp {
        animation: kycScaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes kycScaleUp {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

<div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto">

    <!-- Header Section with Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition">Dashboard</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('admin.brokers') }}" class="hover:text-brand transition">Brokers</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-brand">KYC Verifications</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2.5">
                <i class="fas fa-id-card-clip text-teal-600"></i> Broker KYC Verifications
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Review government ID proofs, RERA certificates, and bank account details for partner brokers</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="button" onclick="window.location.reload()" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-xs font-semibold tap-effect flex items-center gap-2 shadow-xs transition cursor-pointer">
                <i class="fas fa-rotate text-xs text-gray-400"></i> Refresh
            </button>
            <a href="{{ route('admin.brokers') }}" class="px-4 py-2.5 bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-teal-800 text-white rounded-xl text-xs font-bold tap-effect flex items-center gap-2 shadow-sm transition cursor-pointer">
                <i class="fas fa-user-tie text-xs"></i> All Brokers Directory
            </a>
        </div>
    </div>

    <!-- Live Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- 1. Total Brokers -->
        <a href="{{ route('admin.broker-kyc.index', ['tab' => 'ALL']) }}" class="bg-white rounded-3xl p-5 border {{ $currentTab === 'ALL' ? 'border-brand ring-2 ring-brand/20 shadow-md' : 'border-gray-100 shadow-xs' }} hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Brokers</div>
                    <div class="text-2xl font-black text-gray-900 mt-1">{{ $totalBrokers }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-700 text-xl">
                    <i class="fas fa-users-viewfinder"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 text-[11px] text-gray-500 font-medium">
                Registered partner accounts
            </div>
        </a>

        <!-- 2. Pending Verification (Highlighted) -->
        <a href="{{ route('admin.broker-kyc.index', ['tab' => 'PENDING']) }}" class="bg-white rounded-3xl p-5 border {{ $currentTab === 'PENDING' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-gray-100 shadow-xs' }} hover:shadow-md transition relative overflow-hidden group">
            @if($pendingKycCount > 0)
                <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/10 rounded-bl-full pointer-events-none"></div>
            @endif
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-amber-600 uppercase tracking-wider flex items-center gap-1.5">
                        <span>Awaiting Review</span>
                        @if($pendingKycCount > 0)
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        @endif
                    </div>
                    <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingKycCount }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 text-xl group-hover:scale-105 transition">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 text-[11px] text-amber-700 font-semibold">
                {{ $pendingKycCount > 0 ? 'Requires administrative action' : 'All submissions up to date' }}
            </div>
        </a>

        <!-- 3. Fully Verified -->
        <a href="{{ route('admin.broker-kyc.index', ['tab' => 'VERIFIED']) }}" class="bg-white rounded-3xl p-5 border {{ $currentTab === 'VERIFIED' ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-md' : 'border-gray-100 shadow-xs' }} hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Fully Verified</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">{{ $verifiedKycCount }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 text-xl">
                    <i class="fas fa-badge-check"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 text-[11px] text-emerald-700 font-medium">
                Active certified badge partners
            </div>
        </a>

        <!-- 4. Action Needed / Missing -->
        <a href="{{ route('admin.broker-kyc.index', ['tab' => 'REJECTED']) }}" class="bg-white rounded-3xl p-5 border {{ $currentTab === 'REJECTED' ? 'border-rose-500 ring-2 ring-rose-500/20 shadow-md' : 'border-gray-100 shadow-xs' }} hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-rose-600 uppercase tracking-wider">Rejected / Incomplete</div>
                    <div class="text-2xl font-black text-rose-600 mt-1">{{ $rejectedKycCount }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 text-xl">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 text-[11px] text-gray-500 font-medium">
                {{ $missingKycCount }} brokers without uploaded files
            </div>
        </a>

    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-xs space-y-4">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Status Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1">
                @php
                    $tabs = [
                        'ALL' => ['label' => 'All Brokers', 'icon' => 'fa-list', 'count' => $totalBrokers],
                        'PENDING' => ['label' => 'Pending Review', 'icon' => 'fa-hourglass-half', 'count' => $pendingKycCount],
                        'VERIFIED' => ['label' => 'Verified Partners', 'icon' => 'fa-shield-check', 'count' => $verifiedKycCount],
                        'REJECTED' => ['label' => 'Rejected', 'icon' => 'fa-times-circle', 'count' => $rejectedKycCount],
                        'MISSING' => ['label' => 'No Uploads', 'icon' => 'fa-file-slash', 'count' => $missingKycCount],
                    ];
                @endphp

                @foreach($tabs as $key => $tabInfo)
                    <a href="{{ route('admin.broker-kyc.index', ['tab' => $key, 'search' => $search]) }}" 
                       class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap tap-effect {{ $currentTab === $key ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 bg-gray-50 border border-gray-100' }}">
                        <i class="fas {{ $tabInfo['icon'] }} text-[11px]"></i>
                        <span>{{ $tabInfo['label'] }}</span>
                        <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $currentTab === $key ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">
                            {{ $tabInfo['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.broker-kyc.index') }}" method="GET" class="flex items-center gap-2 w-full lg:w-80">
                <input type="hidden" name="tab" value="{{ $currentTab }}">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Search name, email, phone..." 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-8 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white text-gray-800 transition"
                    >
                    @if(!empty($search))
                        <a href="{{ route('admin.broker-kyc.index', ['tab' => $currentTab]) }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-3 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-semibold tap-effect shrink-0">
                    Filter
                </button>
            </form>

        </div>

        <!-- Bulk Action Toolbar (appears when items are selected) -->
        <div id="bulkToolbar" class="hidden p-3 bg-teal-50 border border-teal-200 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs animate-fade-in">
            <div class="flex items-center gap-2 font-bold text-teal-900">
                <i class="fas fa-check-double text-teal-600"></i>
                <span id="bulkSelectedCount">0</span> brokers selected
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="handleBulkAction('approve')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl tap-effect flex items-center gap-1.5 shadow-xs">
                    <i class="fas fa-check"></i> Bulk Approve KYC
                </button>
                <button type="button" onclick="handleBulkAction('reject')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl tap-effect flex items-center gap-1.5 shadow-xs">
                    <i class="fas fa-times"></i> Bulk Reject
                </button>
                <button type="button" onclick="clearBulkSelection()" class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl tap-effect">
                    Deselect All
                </button>
            </div>
        </div>

    </div>

    <!-- Brokers KYC Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        
        @if($brokers->isEmpty())
            <div class="p-12 text-center space-y-4">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-2xl border border-gray-100">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">No Broker Submissions Found</h3>
                    <p class="text-xs text-gray-500 mt-1">There are no partner brokers matching this tab filter or search query.</p>
                </div>
                @if(!empty($search) || $currentTab !== 'ALL')
                    <a href="{{ route('admin.broker-kyc.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold tap-effect transition">
                        <i class="fas fa-undo text-[10px]"></i> Reset All Filters
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                            <th class="p-4 w-10 text-center">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500 cursor-pointer">
                            </th>
                            <th class="p-4">Partner Broker</th>
                            <th class="p-4">Uploaded Documents Checklist</th>
                            <th class="p-4">Overall KYC Status</th>
                            <th class="p-4">Assigned RM</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($brokers as $broker)
                            @php
                                $profile = $broker->profile;
                                $fullName = $profile ? $profile->full_name : ($broker->email ?? 'Partner Broker');
                                $company = $profile->company_name ?? 'Individual Broker';
                                $city = $profile->preferences['operating_city'] ?? 'Pan-India';
                                $initials = strtoupper(substr($profile->first_name ?? 'B', 0, 1) . substr($profile->last_name ?? 'R', 0, 1));
                                if (empty(trim($initials))) $initials = 'BR';
                                
                                $docs = $broker->kyc_docs;
                                $idDoc = $docs['id_proof'] ?? null;
                                $licDoc = $docs['license_proof'] ?? null;
                                $bankDoc = $docs['bank_proof'] ?? null;
                                $overallStatus = $broker->computed_kyc_status;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                
                                <!-- Checkbox -->
                                <td class="p-4 text-center">
                                    <input type="checkbox" name="broker_ids[]" value="{{ $broker->id }}" onchange="updateBulkToolbar()" class="broker-select-checkbox w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500 cursor-pointer">
                                </td>

                                <!-- Broker Info -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-600 to-teal-800 text-white font-bold flex items-center justify-center text-xs shadow-xs shrink-0 overflow-hidden">
                                            @if(!empty($profile->avatar_url))
                                                <img src="{{ $profile->avatar_url }}" alt="{{ $fullName }}" class="w-full h-full object-cover">
                                            @else
                                                <span>{{ $initials }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                                                <span>{{ $fullName }}</span>
                                                @if($overallStatus === 'verified')
                                                    <i class="fas fa-circle-check text-emerald-500 text-xs" title="Verified Partner"></i>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-gray-500 font-medium">{{ $company }} • <span class="text-teal-700 font-semibold">{{ $city }}</span></div>
                                            <div class="text-[10px] text-gray-400 mt-0.5 flex items-center gap-2">
                                                <span><i class="fas fa-envelope text-[9px] text-gray-400 mr-0.5"></i> {{ $broker->email }}</span>
                                                <span><i class="fas fa-phone text-[9px] text-gray-400 mr-0.5"></i> {{ $broker->phone ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Uploaded Documents Checklist -->
                                <td class="p-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        
                                        <!-- 1. ID Proof Badge -->
                                        @if($idDoc && $idDoc['exists'])
                                            @if($idDoc['status'] === 'verified')
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold flex items-center gap-1 hover:bg-emerald-100 transition tap-effect" title="ID Proof Verified - Click to View">
                                                    <i class="fas fa-check-circle text-[9px]"></i> ID Proof
                                                </button>
                                            @elseif($idDoc['status'] === 'rejected')
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold flex items-center gap-1 hover:bg-rose-100 transition tap-effect" title="ID Proof Rejected: {{ $idDoc['rejection_reason'] ?? 'Invalid' }}">
                                                    <i class="fas fa-times-circle text-[9px]"></i> ID (Rejected)
                                                </button>
                                            @else
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold flex items-center gap-1 hover:bg-amber-100 transition tap-effect" title="ID Proof Awaiting Review">
                                                    <i class="fas fa-clock text-[9px]"></i> ID Proof
                                                </button>
                                            @endif
                                        @else
                                            <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-400 text-[10px] font-medium flex items-center gap-1">
                                                <i class="fas fa-minus text-[8px]"></i> ID Missing
                                            </span>
                                        @endif

                                        <!-- 2. RERA License Badge -->
                                        @if($licDoc && $licDoc['exists'])
                                            @if($licDoc['status'] === 'verified')
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold flex items-center gap-1 hover:bg-emerald-100 transition tap-effect" title="RERA License Verified">
                                                    <i class="fas fa-check-circle text-[9px]"></i> RERA
                                                </button>
                                            @elseif($licDoc['status'] === 'rejected')
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold flex items-center gap-1 hover:bg-rose-100 transition tap-effect" title="RERA License Rejected: {{ $licDoc['rejection_reason'] ?? 'Invalid' }}">
                                                    <i class="fas fa-times-circle text-[9px]"></i> RERA (Rejected)
                                                </button>
                                            @else
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold flex items-center gap-1 hover:bg-amber-100 transition tap-effect" title="RERA License Awaiting Review">
                                                    <i class="fas fa-clock text-[9px]"></i> RERA
                                                </button>
                                            @endif
                                        @else
                                            <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-400 text-[10px] font-medium flex items-center gap-1">
                                                <i class="fas fa-minus text-[8px]"></i> RERA Missing
                                            </span>
                                        @endif

                                        <!-- 3. Bank Cheque Badge -->
                                        @if($bankDoc && $bankDoc['exists'])
                                            @if($bankDoc['status'] === 'verified')
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold flex items-center gap-1 hover:bg-emerald-100 transition tap-effect" title="Bank Passbook Verified">
                                                    <i class="fas fa-check-circle text-[9px]"></i> Bank Passbook
                                                </button>
                                            @elseif($bankDoc['status'] === 'rejected')
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold flex items-center gap-1 hover:bg-rose-100 transition tap-effect" title="Bank Proof Rejected: {{ $bankDoc['rejection_reason'] ?? 'Invalid' }}">
                                                    <i class="fas fa-times-circle text-[9px]"></i> Bank (Rejected)
                                                </button>
                                            @else
                                                <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-2 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold flex items-center gap-1 hover:bg-amber-100 transition tap-effect" title="Bank Passbook Awaiting Review">
                                                    <i class="fas fa-clock text-[9px]"></i> Bank Passbook
                                                </button>
                                            @endif
                                        @else
                                            <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-400 text-[10px] font-medium flex items-center gap-1">
                                                <i class="fas fa-minus text-[8px]"></i> Bank Missing
                                            </span>
                                        @endif

                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        Joined {{ $broker->created_at ? $broker->created_at->format('M d, Y') : 'Recent' }}
                                    </div>
                                </td>

                                <!-- Overall Status Badge -->
                                <td class="p-4">
                                    @if($overallStatus === 'verified')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="fas fa-badge-check text-emerald-600"></i> VERIFIED
                                        </span>
                                    @elseif($overallStatus === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <i class="fas fa-circle-xmark text-rose-600"></i> REJECTED
                                        </span>
                                    @elseif($overallStatus === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                                            <i class="fas fa-hourglass-half text-amber-600"></i> PENDING REVIEW
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                            <i class="fas fa-file-circle-question text-gray-400"></i> NOT UPLOADED
                                        </span>
                                    @endif
                                </td>

                                <!-- Assigned RM -->
                                <td class="p-4">
                                    @if($broker->relationshipManager)
                                        <div class="font-bold text-gray-800 text-[11px] flex items-center gap-1">
                                            <i class="fas fa-user-check text-teal-600 text-[10px]"></i>
                                            <span>{{ $broker->relationshipManager->name }}</span>
                                        </div>
                                        <div class="text-[10px] text-gray-400">{{ $broker->relationshipManager->zone }}</div>
                                    @else
                                        <span class="text-gray-400 text-[11px]">Unassigned</span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" onclick="openReviewModal('{{ $broker->id }}')" class="px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-800 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 border border-teal-200 transition cursor-pointer">
                                            <i class="fas fa-file-magnifying-glass"></i> Review
                                        </button>

                                        @if($overallStatus !== 'verified')
                                            <button type="button" onclick="quickApproveBroker('{{ $broker->id }}', '{{ addslashes($fullName) }}')" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs tap-effect border border-emerald-200 transition cursor-pointer" title="Quick Approve KYC">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        @if($overallStatus !== 'rejected')
                                            <button type="button" onclick="quickRejectBroker('{{ $broker->id }}', '{{ addslashes($fullName) }}')" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs tap-effect border border-rose-200 transition cursor-pointer" title="Reject KYC">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($brokers->hasPages())
                <div class="p-4 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-xs text-gray-500 font-medium">
                        Showing {{ $brokers->firstItem() }} to {{ $brokers->lastItem() }} of {{ $brokers->total() }} partner brokers
                    </div>
                    <div>
                        {{ $brokers->links() }}
                    </div>
                </div>
            @endif
        @endif

    </div>

</div>

<!-- ================= INTERACTIVE KYC REVIEW MODAL ================= -->
<div id="kycReviewModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-3 sm:p-4 md:p-6 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-4xl w-full my-auto shadow-2xl border border-gray-200 overflow-hidden relative animate-scaleUp">
        
        <!-- Modal Header (High-contrast slate/navy with teal accents) -->
        <div class="kyc-modal-header p-5 sm:p-6 flex items-center justify-between relative border-b border-slate-700">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-teal-300 text-2xl shrink-0" style="background: rgba(13, 148, 136, 0.25); border: 1px solid rgba(45, 212, 191, 0.4);">
                    <i class="fas fa-id-card-clip"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-lg sm:text-xl font-extrabold text-white tracking-tight truncate" id="mBrokerName">Partner Broker KYC</h3>
                        <span id="mBrokerHeaderStatusBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold" style="background: rgba(255,255,255,0.15); color: #fff;">Review</span>
                    </div>
                    <p class="text-xs text-teal-200/80 font-medium mt-0.5 truncate" id="mBrokerCompany">Regulatory compliance and bank verification details</p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="w-10 h-10 rounded-2xl flex items-center justify-center tap-effect transition cursor-pointer shrink-0 ml-3" style="background: rgba(255, 255, 255, 0.15); color: #ffffff;" hover="background: rgba(255,255,255,0.25);">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <!-- Modal Content Body -->
        <div class="p-5 sm:p-7 space-y-6 max-h-[72vh] overflow-y-auto custom-scrollbar bg-white">
            
            <!-- Broker Summary Quick Card -->
            <div class="rounded-2xl p-4 border grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs" style="background: #f8fafc; border-color: #e2e8f0;">
                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Email Address</div>
                    <div class="font-bold text-gray-900 mt-0.5 break-all" id="mBrokerEmail">Loading...</div>
                </div>
                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Mobile Number</div>
                    <div class="font-bold text-gray-900 mt-0.5" id="mBrokerPhone">Loading...</div>
                </div>
                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Operating City</div>
                    <div class="font-bold text-gray-900 mt-0.5" id="mBrokerCity">Loading...</div>
                </div>
                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">KYC Verification</div>
                    <div class="font-extrabold mt-0.5" id="mBrokerKycBadge">Loading...</div>
                </div>
            </div>

            <!-- Regulatory & Bank Details Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                
                <!-- Business Reg Details -->
                <div class="p-4 bg-white rounded-2xl border space-y-2.5 shadow-xs" style="border-color: #e2e8f0;">
                    <div class="font-bold text-gray-900 flex items-center gap-2 border-b pb-2.5" style="border-color: #f1f5f9;">
                        <i class="fas fa-building-circle-check text-teal-600 text-sm"></i> 
                        <span class="text-sm font-extrabold">Regulatory Business Info</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b" style="border-color: #f8fafc;">
                        <span class="text-gray-500 font-medium">GSTIN Number:</span>
                        <span class="font-mono font-bold text-gray-900" id="mBrokerGstin">-</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b" style="border-color: #f8fafc;">
                        <span class="text-gray-500 font-medium">RERA Agent ID:</span>
                        <span class="font-mono font-bold text-gray-900" id="mBrokerRera">-</span>
                    </div>
                    <div class="flex justify-between items-start py-1">
                        <span class="text-gray-500 font-medium shrink-0 mr-2">Office Address:</span>
                        <span class="font-semibold text-gray-800 text-right leading-tight" id="mBrokerAddress">-</span>
                    </div>
                </div>

                <!-- Bank Account Details -->
                <div class="p-4 bg-white rounded-2xl border space-y-2.5 shadow-xs" style="border-color: #e2e8f0;">
                    <div class="font-bold text-gray-900 flex items-center gap-2 border-b pb-2.5" style="border-color: #f1f5f9;">
                        <i class="fas fa-university text-teal-600 text-sm"></i> 
                        <span class="text-sm font-extrabold">Payout Bank Account</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b" style="border-color: #f8fafc;">
                        <span class="text-gray-500 font-medium">Account Holder:</span>
                        <span class="font-bold text-gray-900" id="mBankHolder">-</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b" style="border-color: #f8fafc;">
                        <span class="text-gray-500 font-medium">Bank & IFSC:</span>
                        <span class="font-semibold text-gray-800" id="mBankNameIfsc">-</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-500 font-medium">Account Number:</span>
                        <span class="font-mono font-bold text-gray-900 text-sm" id="mBankAccountNo">-</span>
                    </div>
                </div>

            </div>

            <!-- Uploaded Document Cards Section -->
            <div class="space-y-3.5">
                <div class="flex items-center justify-between border-b pb-2.5" style="border-color: #f1f5f9;">
                    <div class="font-extrabold text-gray-900 text-sm flex items-center gap-2">
                        <i class="fas fa-file-shield text-teal-600"></i> Submitted Verification Documents
                    </div>
                    <span class="text-xs text-gray-400 font-medium">Review each uploaded file</span>
                </div>

                <div id="modalDocsContainer" class="space-y-3.5">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

        </div>

        <!-- Modal Action Footer (High-contrast buttons) -->
        <div class="p-4 sm:p-5 border-t flex flex-col sm:flex-row items-center justify-between gap-3.5" style="background: #f8fafc; border-color: #e2e8f0;">
            <div class="text-xs text-gray-500 font-medium flex items-center gap-1.5 text-center sm:text-left">
                <i class="fas fa-shield-halved text-teal-600 text-sm"></i>
                <span>Approval activates verified badge & public listings on SpaceSeeks.</span>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto justify-end">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-bold rounded-xl text-xs tap-effect transition cursor-pointer">
                    Close
                </button>
                <button type="button" id="mModalRejectBtn" onclick="submitModalReject()" class="kyc-btn-reject-all px-4 py-2.5 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 transition cursor-pointer">
                    <i class="fas fa-times"></i> Reject KYC
                </button>
                <button type="button" id="mModalApproveBtn" onclick="submitModalApprove()" class="kyc-btn-approve-all px-6 py-2.5 font-extrabold rounded-xl text-xs tap-effect flex items-center gap-2 transition cursor-pointer">
                    <i class="fas fa-badge-check text-sm"></i> <span>Approve All & Verify Partner</span>
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    let currentBrokerIdInModal = null;

    // ================= SELECTION & BULK ACTIONS =================
    function toggleSelectAll(masterCheckbox) {
        document.querySelectorAll('.broker-select-checkbox').forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
        updateBulkToolbar();
    }

    function updateBulkToolbar() {
        const selected = document.querySelectorAll('.broker-select-checkbox:checked');
        const count = selected.length;
        const toolbar = document.getElementById('bulkToolbar');
        const countEl = document.getElementById('bulkSelectedCount');

        if (count > 0) {
            countEl.textContent = count;
            toolbar.classList.remove('hidden');
        } else {
            toolbar.classList.add('hidden');
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) selectAll.checked = false;
        }
    }

    function clearBulkSelection() {
        document.querySelectorAll('.broker-select-checkbox').forEach(cb => cb.checked = false);
        const selectAll = document.getElementById('selectAllCheckbox');
        if (selectAll) selectAll.checked = false;
        updateBulkToolbar();
    }

    function handleBulkAction(action) {
        const checked = Array.from(document.querySelectorAll('.broker-select-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) return;

        if (action === 'approve') {
            Swal.fire({
                title: `Approve KYC for ${checked.length} Broker(s)?`,
                text: 'This will verify all uploaded documents and activate the verified partner status for these brokers.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d9488',
                confirmButtonText: 'Yes, Approve All'
            }).then((res) => {
                if (res.isConfirmed) {
                    performBulkRequest('approve', checked);
                }
            });
        } else {
            Swal.fire({
                title: `Reject KYC for ${checked.length} Broker(s)`,
                input: 'textarea',
                inputLabel: 'Rejection Reason / Remarks for Brokers',
                inputPlaceholder: 'e.g. Uploaded documents are unclear or invalid...',
                inputValidator: (value) => {
                    if (!value || value.trim().length < 3) {
                        return 'Please provide a clear rejection reason.';
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Reject Submissions'
            }).then((res) => {
                if (res.isConfirmed) {
                    performBulkRequest('reject', checked, res.value);
                }
            });
        }
    }

    async function performBulkRequest(action, brokerIds, reason = '') {
        try {
            Swal.showLoading();
            const res = await fetch('{{ route("admin.broker-kyc.bulk-action") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    _token: csrfToken,
                    broker_ids: brokerIds,
                    action: action,
                    reason: reason
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                Swal.fire('Completed!', data.message, 'success').then(() => window.location.reload());
            } else {
                Swal.fire('Error', data.message || 'Operation failed.', 'error');
            }
        } catch (err) {
            Swal.fire('Network Error', 'Could not process request.', 'error');
        }
    }

    // ================= QUICK 1-CLICK ACTIONS =================
    function quickApproveBroker(brokerId, brokerName) {
        Swal.fire({
            title: `Approve KYC for "${brokerName}"?`,
            text: 'All uploaded documents will be marked as verified and the partner broker badge will be activated.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d9488',
            confirmButtonText: 'Yes, Approve & Verify'
        }).then(async (res) => {
            if (res.isConfirmed) {
                try {
                    Swal.showLoading();
                    const response = await fetch(`/admin/broker-kyc/${brokerId}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ _token: csrfToken })
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        Swal.fire('Verified!', data.message, 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Approval failed.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Network Error', 'Please try again.', 'error');
                }
            }
        });
    }

    function quickRejectBroker(brokerId, brokerName) {
        Swal.fire({
            title: `Reject KYC for "${brokerName}"`,
            input: 'textarea',
            inputLabel: 'Reason for Rejection',
            inputPlaceholder: 'State why the KYC documents are rejected...',
            inputValidator: (val) => {
                if (!val || val.trim().length < 3) return 'Please specify a rejection reason.';
            },
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Decline Submission'
        }).then(async (res) => {
            if (res.isConfirmed) {
                try {
                    Swal.showLoading();
                    const response = await fetch(`/admin/broker-kyc/${brokerId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            _token: csrfToken,
                            reason: res.value
                        })
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        Swal.fire('Declined', data.message, 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Rejection failed.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Network Error', 'Please try again.', 'error');
                }
            }
        });
    }

    // ================= MODAL FULL KYC REVIEW =================
    async function openReviewModal(brokerId) {
        currentBrokerIdInModal = brokerId;
        const modal = document.getElementById('kycReviewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Loading state
        document.getElementById('mBrokerName').textContent = 'Loading details...';
        document.getElementById('mBrokerCompany').textContent = 'Fetching verification documents...';
        document.getElementById('modalDocsContainer').innerHTML = `
            <div class="p-8 text-center" style="color: #0d9488;">
                <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                <div class="text-xs font-semibold">Loading document files...</div>
            </div>
        `;

        try {
            const res = await fetch(`/admin/broker-kyc/${brokerId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const result = await res.json();
            if (!res.ok || !result.success) {
                Swal.fire('Error', 'Unable to load broker KYC details.', 'error');
                closeReviewModal();
                return;
            }

            const b = result.broker;
            document.getElementById('mBrokerName').textContent = b.name;
            document.getElementById('mBrokerCompany').textContent = `${b.company_name} • Joined ${b.joined_at}`;
            document.getElementById('mBrokerEmail').textContent = b.email;
            document.getElementById('mBrokerPhone').textContent = b.phone;
            document.getElementById('mBrokerCity').textContent = b.operating_city;
            document.getElementById('mBrokerGstin').textContent = b.gstin || 'Not provided';
            document.getElementById('mBrokerRera').textContent = b.rera_number || 'Not provided';
            document.getElementById('mBrokerAddress').textContent = b.office_address || 'Not specified';

            document.getElementById('mBankHolder').textContent = b.bank_details.account_holder_name || b.name;
            document.getElementById('mBankNameIfsc').textContent = b.bank_details.bank_name ? `${b.bank_details.bank_name} (${b.bank_details.ifsc_code})` : 'Not provided';
            document.getElementById('mBankAccountNo').textContent = b.bank_details.account_number || 'Not provided';

            // Status Badges
            const kycBadge = document.getElementById('mBrokerKycBadge');
            const headerStatusBadge = document.getElementById('mBrokerHeaderStatusBadge');

            if (b.kyc_status === 'verified') {
                kycBadge.className = 'font-bold text-emerald-600 flex items-center gap-1';
                kycBadge.innerHTML = '<i class="fas fa-badge-check"></i> Verified Partner';
                headerStatusBadge.style.background = '#059669';
                headerStatusBadge.style.color = '#ffffff';
                headerStatusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> VERIFIED';
            } else if (b.kyc_status === 'rejected') {
                kycBadge.className = 'font-bold text-rose-600 flex items-center gap-1';
                kycBadge.innerHTML = '<i class="fas fa-circle-xmark"></i> Rejected / Action Needed';
                headerStatusBadge.style.background = '#e11d48';
                headerStatusBadge.style.color = '#ffffff';
                headerStatusBadge.innerHTML = '<i class="fas fa-times-circle mr-1"></i> REJECTED';
            } else if (b.kyc_status === 'pending') {
                kycBadge.className = 'font-bold text-amber-600 flex items-center gap-1';
                kycBadge.innerHTML = '<i class="fas fa-hourglass-half"></i> Pending Review';
                headerStatusBadge.style.background = '#d97706';
                headerStatusBadge.style.color = '#ffffff';
                headerStatusBadge.innerHTML = '<i class="fas fa-clock mr-1"></i> PENDING REVIEW';
            } else {
                kycBadge.className = 'font-bold text-gray-500 flex items-center gap-1';
                kycBadge.innerHTML = '<i class="fas fa-file-slash"></i> Not Uploaded';
                headerStatusBadge.style.background = '#475569';
                headerStatusBadge.style.color = '#ffffff';
                headerStatusBadge.innerHTML = 'NOT UPLOADED';
            }

            // Render Document Cards
            renderDocumentCards(b.documents, brokerId);

        } catch (err) {
            Swal.fire('Error', 'Connection error while fetching broker documents.', 'error');
            closeReviewModal();
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function renderDocumentCards(docs, brokerId) {
        const container = document.getElementById('modalDocsContainer');
        container.innerHTML = '';

        const docTypes = ['id_proof', 'license_proof', 'bank_proof'];
        const icons = {
            id_proof: 'fa-id-card',
            license_proof: 'fa-file-contract',
            bank_proof: 'fa-money-check-dollar'
        };

        docTypes.forEach((type, idx) => {
            const doc = docs[type];
            if (!doc) return;

            const isUploaded = doc.is_uploaded;
            const status = doc.status;
            const allowReupload = !!doc.allow_reupload;
            let statusPill = '';
            let cardBorder = '#e2e8f0';
            let cardBg = '#ffffff';

            if (!isUploaded) {
                statusPill = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold" style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1;">NOT UPLOADED</span>`;
                cardBg = '#f8fafc';
            } else if (status === 'verified') {
                if (allowReupload) {
                    statusPill = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold" style="background: #fef3c7; color: #b45309; border: 1px solid #fcd34d;"><i class="fas fa-unlock text-xs mr-1"></i> RE-UPLOAD ENABLED</span>`;
                    cardBorder = '#fcd34d';
                    cardBg = '#fffbeb';
                } else {
                    statusPill = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold" style="background: #dcfce7; color: #15803d; border: 1px solid #86efac;"><i class="fas fa-shield-check mr-1"></i> VERIFIED & LOCKED</span>`;
                    cardBorder = '#86efac';
                    cardBg = '#f0fdf4';
                }
            } else if (status === 'rejected') {
                statusPill = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold" style="background: #ffe4e6; color: #be123c; border: 1px solid #fda4af;"><i class="fas fa-circle-xmark mr-1"></i> REJECTED</span>`;
                cardBorder = '#fda4af';
                cardBg = '#fff1f2';
            } else {
                statusPill = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold" style="background: #fef3c7; color: #b45309; border: 1px solid #fcd34d;"><i class="fas fa-clock mr-1"></i> PENDING REVIEW</span>`;
                cardBorder = '#fcd34d';
                cardBg = '#fffbeb';
            }

            const isImage = isUploaded && (doc.file_path.endsWith('.jpg') || doc.file_path.endsWith('.jpeg') || doc.file_path.endsWith('.png') || doc.file_path.endsWith('.webp'));
            const previewId = `docPreview_${type}_${idx}`;

            const card = document.createElement('div');
            card.className = `p-4 sm:p-5 rounded-2xl border transition shadow-xs`;
            card.style.borderColor = cardBorder;
            card.style.background = cardBg;

            // Action Buttons calculation
            let actionButtonsHtml = '';
            if (isUploaded) {
                actionButtonsHtml += `
                    <a href="${doc.file_path}" target="_blank" class="kyc-btn-action-view px-3.5 py-2 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 transition cursor-pointer shadow-xs">
                        <i class="fas fa-arrow-up-right-from-square text-xs"></i> View File
                    </a>
                `;
                if (isImage) {
                    actionButtonsHtml += `
                        <button type="button" onclick="toggleInlinePreview('${previewId}')" class="px-3 py-2 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-bold rounded-xl text-xs tap-effect flex items-center gap-1 transition cursor-pointer" title="Toggle Thumbnail">
                            <i class="fas fa-image text-xs text-teal-600"></i> Preview
                        </button>
                    `;
                }

                if (status === 'verified') {
                    if (allowReupload) {
                        actionButtonsHtml += `
                            <button type="button" onclick="toggleDocReupload('${brokerId}', '${type}', false)" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 border border-gray-300 transition cursor-pointer shadow-xs" title="Lock re-upload permission">
                                <i class="fas fa-lock text-xs"></i> Lock Re-upload
                            </button>
                        `;
                    } else {
                        actionButtonsHtml += `
                            <button type="button" onclick="promptAllowReupload('${brokerId}', '${type}', '${escapeHtml(doc.label)}')" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 border border-amber-300 transition cursor-pointer shadow-xs" title="Allow broker to upload new version of this verified document">
                                <i class="fas fa-rotate-right text-xs"></i> Enable Re-upload
                            </button>
                        `;
                    }
                    actionButtonsHtml += `
                        <button type="button" onclick="verifySingleDocument('${brokerId}', '${type}', 'reject')" class="kyc-btn-action-reject px-3.5 py-2 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 transition cursor-pointer">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    `;
                } else if (status === 'rejected') {
                    actionButtonsHtml += `
                        <button type="button" onclick="verifySingleDocument('${brokerId}', '${type}', 'verify')" class="kyc-btn-action-verify px-3.5 py-2 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                            <i class="fas fa-check"></i> Verify
                        </button>
                    `;
                } else {
                    // Pending review
                    actionButtonsHtml += `
                        <button type="button" onclick="verifySingleDocument('${brokerId}', '${type}', 'verify')" class="kyc-btn-action-verify px-3.5 py-2 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 shadow-xs transition cursor-pointer">
                            <i class="fas fa-check"></i> Verify
                        </button>
                        <button type="button" onclick="verifySingleDocument('${brokerId}', '${type}', 'reject')" class="kyc-btn-action-reject px-3.5 py-2 font-bold rounded-xl text-xs tap-effect flex items-center gap-1.5 transition cursor-pointer">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    `;
                }
            } else {
                actionButtonsHtml = `<span class="text-xs text-gray-400 font-medium italic">Upload pending by broker</span>`;
            }

            card.innerHTML = `
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5 min-w-0">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0" style="${isUploaded ? 'background: #ffffff; color: #0d9488; border: 1px solid #ccfbf1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);' : 'background: #e2e8f0; color: #94a3b8;'}">
                            <i class="fas ${icons[type] || 'fa-file'}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-extrabold text-gray-900 text-sm">${doc.label}</h4>
                                ${statusPill}
                            </div>
                            <div class="text-xs text-gray-600 mt-1 font-medium">
                                ${isUploaded 
                                    ? `File: <span class="font-bold text-gray-800">${doc.name || 'document.pdf'}</span> • Uploaded: <span class="text-gray-700">${doc.uploaded_at || 'Recently'}</span>`
                                    : 'Partner has not uploaded this document yet.'
                                }
                            </div>
                            ${doc.doc_number ? `<div class="text-[11px] text-gray-600 mt-1 font-mono">Document No: <span class="font-bold text-gray-900 bg-white px-2 py-0.5 rounded border border-gray-200">${doc.doc_number}</span></div>` : ''}
                            ${doc.rejection_reason ? `<div class="text-xs text-rose-700 font-semibold mt-2 bg-white p-2.5 rounded-xl border border-rose-300 flex items-start gap-2 shadow-xs"><i class="fas fa-circle-exclamation text-rose-500 mt-0.5 shrink-0"></i> <span><strong>Rejection Reason:</strong> ${doc.rejection_reason}</span></div>` : ''}
                            ${doc.reupload_note ? `<div class="text-xs text-amber-800 font-semibold mt-2 bg-amber-50/80 p-2.5 rounded-xl border border-amber-300 flex items-start gap-2 shadow-xs"><i class="fas fa-unlock-alt text-amber-600 mt-0.5 shrink-0"></i> <span><strong>Re-upload Admin Note:</strong> ${doc.reupload_note}</span></div>` : ''}
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 self-end lg:self-center flex-wrap">
                        ${actionButtonsHtml}
                    </div>
                </div>

                ${isImage ? `
                    <div id="${previewId}" class="hidden mt-3 pt-3 border-t border-gray-200/80">
                        <div class="relative inline-block max-w-sm rounded-xl overflow-hidden border border-gray-200 bg-white shadow-xs">
                            <img src="${doc.file_path}" alt="${doc.name}" class="w-full max-h-56 object-contain">
                        </div>
                    </div>
                ` : ''}
            `;

            container.appendChild(card);
        });
    }

    function toggleInlinePreview(previewId) {
        const el = document.getElementById(previewId);
        if (el) {
            el.classList.toggle('hidden');
        }
    }

    function closeReviewModal() {
        const modal = document.getElementById('kycReviewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentBrokerIdInModal = null;
    }

    async function promptAllowReupload(brokerId, docType, docLabel) {
        const { value: reason, isConfirmed } = await Swal.fire({
            title: `Enable Re-upload?`,
            html: `<div class="text-sm text-gray-600 mb-2">Allow broker to re-upload their <b>${escapeHtml(docLabel)}</b>. You may provide optional instructions:</div>`,
            input: 'textarea',
            inputLabel: 'Instructions / Remarks for Broker (Optional)',
            inputPlaceholder: 'e.g. Please upload a clearer scan with valid expiry date...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            confirmButtonText: 'Yes, Enable Re-upload'
        });

        if (isConfirmed) {
            toggleDocReupload(brokerId, docType, true, reason || '');
        }
    }

    async function toggleDocReupload(brokerId, docType, allow, reason = '') {
        try {
            Swal.showLoading();
            const res = await fetch(`/admin/broker-kyc/${brokerId}/toggle-reupload`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    _token: csrfToken,
                    doc_type: docType,
                    allow: allow ? 1 : 0,
                    reason: reason
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: allow ? 'Re-upload Enabled!' : 'Document Locked!',
                    text: data.message,
                    timer: 1600,
                    showConfirmButton: false
                });
                // Reload modal data
                openReviewModal(brokerId);
            } else {
                Swal.fire('Error', data.message || 'Failed to update re-upload permission.', 'error');
            }
        } catch (err) {
            Swal.fire('Network Error', 'Please check connection.', 'error');
        }
    }

    async function verifySingleDocument(brokerId, docType, action) {
        if (action === 'reject') {
            const { value: reason } = await Swal.fire({
                title: 'Reject Document',
                input: 'textarea',
                inputLabel: 'Rejection Reason for Broker',
                inputPlaceholder: 'Explain why this document was rejected (e.g. Unclear image, expired certificate)...',
                inputValidator: (value) => {
                    if (!value || value.trim().length < 3) return 'Please specify a rejection reason.';
                },
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Confirm Rejection'
            });

            if (!reason) return;

            sendSingleDocVerification(brokerId, docType, 'reject', reason);
        } else {
            sendSingleDocVerification(brokerId, docType, 'verify', '');
        }
    }

    async function sendSingleDocVerification(brokerId, docType, action, reason) {
        try {
            Swal.showLoading();
            const res = await fetch(`/admin/broker-kyc/${brokerId}/verify-doc`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    _token: csrfToken,
                    doc_type: docType,
                    action: action,
                    reason: reason
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                // Reload modal data
                openReviewModal(brokerId);
            } else {
                Swal.fire('Error', data.message || 'Failed to update document.', 'error');
            }
        } catch (err) {
            Swal.fire('Network Error', 'Please check connection.', 'error');
        }
    }

    function submitModalApprove() {
        if (!currentBrokerIdInModal) return;
        const brokerId = currentBrokerIdInModal;
        const brokerName = document.getElementById('mBrokerName')?.textContent?.trim() || 'Selected Partner Broker';
        closeReviewModal();
        quickApproveBroker(brokerId, brokerName);
    }

    function submitModalReject() {
        if (!currentBrokerIdInModal) return;
        const brokerId = currentBrokerIdInModal;
        const brokerName = document.getElementById('mBrokerName')?.textContent?.trim() || 'Selected Partner Broker';
        closeReviewModal();
        quickRejectBroker(brokerId, brokerName);
    }
</script>
@endpush
@endsection
