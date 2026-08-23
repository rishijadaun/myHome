@extends('admin.layouts.app')

@section('title', 'Reported Listings & Moderation')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shadow-xs">
                <i class="fas fa-flag"></i>
            </span>
            <span>Reported Listings & Moderation</span>
        </h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ number_format($totalReports) }} total reports &middot; {{ number_format($pendingReports) }} pending review &middot; {{ number_format($reportedPropertiesCount) }} flagged properties</p>
    </div>
    <div class="flex items-center gap-3">
        @if($pendingReports > 0)
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-2 animate-pulse">
                <i class="fas fa-triangle-exclamation text-rose-600"></i> {{ $pendingReports }} Report{{ $pendingReports > 1 ? 's' : '' }} Require Urgent Action
            </div>
        @else
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i class="fas fa-shield-check text-emerald-600"></i> All Reports Reviewed
            </div>
        @endif
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Toast Notification -->
    <div id="reportToast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="reportToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="reportToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="reportToastMessage">Action completed successfully</span>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Reports -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between hover:border-gray-200 transition">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalReports) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Total Reports Submitted</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center text-lg">
                <i class="fas fa-flag"></i>
            </div>
        </div>

        <!-- Pending Moderation -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between hover:border-rose-100 transition">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-rose-600">{{ number_format($pendingReports) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Pending Moderation</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <!-- Under Investigation -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between hover:border-amber-100 transition">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-amber-600">{{ number_format($investigatingReports) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Under Investigation</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fas fa-magnifying-glass-chart"></i>
            </div>
        </div>

        <!-- Resolved / Action Taken -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between hover:border-emerald-100 transition">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($resolvedReports) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Resolved / Action Taken</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fas fa-shield-halved"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.reports') }}" class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm space-y-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2 border-b border-gray-100">
            <a href="{{ route('admin.reports', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ ($statusFilter === 'all' || !$statusFilter) ? 'bg-brand text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <span>All Reports</span>
                <span class="text-[10px] opacity-80">({{ $totalReports }})</span>
            </a>
            <a href="{{ route('admin.reports', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $statusFilter === 'pending' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                <i class="fas fa-clock text-[10px]"></i>
                <span>Pending</span>
                <span class="text-[10px] opacity-80">({{ $pendingReports }})</span>
            </a>
            <a href="{{ route('admin.reports', array_merge(request()->except('status', 'page'), ['status' => 'investigating'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $statusFilter === 'investigating' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                <i class="fas fa-magnifying-glass text-[10px]"></i>
                <span>Investigating</span>
                <span class="text-[10px] opacity-80">({{ $investigatingReports }})</span>
            </a>
            <a href="{{ route('admin.reports', array_merge(request()->except('status', 'page'), ['status' => 'resolved'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $statusFilter === 'resolved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                <i class="fas fa-check-circle text-[10px]"></i>
                <span>Resolved</span>
                <span class="text-[10px] opacity-80">({{ $resolvedReports }})</span>
            </a>
            <a href="{{ route('admin.reports', array_merge(request()->except('status', 'page'), ['status' => 'dismissed'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $statusFilter === 'dismissed' ? 'bg-gray-700 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="fas fa-times-circle text-[10px]"></i>
                <span>Dismissed</span>
                <span class="text-[10px] opacity-80">({{ $dismissedReports }})</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-1">
            <!-- Search Text -->
            <div class="lg:col-span-2 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Search by property, reporter, reason, phone, or notes..." class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium">
            </div>

            <!-- Reason Filter -->
            <div>
                <select name="reason" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium text-gray-700">
                    <option value="all" {{ $reasonFilter === 'all' ? 'selected' : '' }}>All Violation Reasons</option>
                    @foreach($reasonsList as $r)
                        <option value="{{ $r }}" {{ $reasonFilter === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Flagged Property Dropdown -->
            <div>
                <select name="property_id" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium text-gray-700">
                    <option value="">All Flagged Properties ({{ $flaggedProperties->count() }})</option>
                    @foreach($flaggedProperties as $fp)
                        <option value="{{ $fp->id }}" {{ request('property_id') === $fp->id ? 'selected' : '' }}>{{ Str::limit($fp->name, 25) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sorting & Reset -->
            <div class="flex items-center gap-2">
                <select name="sort" onchange="this.form.submit()" class="flex-1 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium text-gray-700">
                    <option value="latest" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Oldest First</option>
                </select>
                @if($searchQuery || $reasonFilter !== 'all' || request('property_id') || $statusFilter !== 'all')
                    <a href="{{ route('admin.reports') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center justify-center shrink-0" title="Reset Filters">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Reports Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 md:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-gray-900 text-base">Flagged Property Reports</h3>
                <p class="text-xs text-gray-500">Showing {{ $reports->firstItem() ?? 0 }}-{{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} reports</p>
            </div>
        </div>

        @if($reports->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-xs">
                    <i class="fas fa-shield-heart"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-base">No Reports Found</h4>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">
                    @if($searchQuery || $reasonFilter !== 'all' || $statusFilter !== 'all' || request('property_id'))
                        No reports match your selected filters. Try clearing some filters.
                    @else
                        No properties have been reported by users. All active listings are clean!
                    @endif
                </p>
                @if($searchQuery || $reasonFilter !== 'all' || $statusFilter !== 'all' || request('property_id'))
                    <a href="{{ route('admin.reports') }}" class="inline-flex items-center gap-1.5 bg-brand text-white text-xs font-bold px-4 py-2 rounded-xl shadow-xs hover:bg-brand-dark transition">
                        <i class="fas fa-rotate-left"></i> Reset All Filters
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                            <th class="py-3.5 px-4">Flagged Property</th>
                            <th class="py-3.5 px-4">Reporter Details</th>
                            <th class="py-3.5 px-4">Reason & Description</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Reported On</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @foreach($reports as $report)
                            @php
                                $property = $report->property;
                                $statusClasses = [
                                    'pending' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'investigating' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'action_taken' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'dismissed' => 'bg-gray-100 text-gray-600 border-gray-200',
                                ];
                                $statusBadgeClass = $statusClasses[$report->status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition" id="report-row-{{ $report->id }}">
                                <!-- Flagged Property Column -->
                                <td class="py-4 px-4 align-top min-w-[220px]">
                                    @if($property)
                                        <div class="flex items-start gap-3">
                                            <div class="relative w-12 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-gray-200 shadow-2xs">
                                                <img src="{{ $property->display_image_url }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
                                                @if($property->status === 'inactive' || !$property->is_active)
                                                    <span class="absolute inset-0 bg-red-900/60 flex items-center justify-center text-[9px] font-black text-white uppercase">Suspended</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('user.detail', ['slug' => $property->slug ?: \Illuminate\Support\Str::slug($property->name)]) }}" target="_blank" class="font-bold text-gray-900 hover:text-brand truncate block leading-tight text-xs" title="{{ $property->name }}">
                                                    {{ $property->name }} <i class="fas fa-external-link-alt text-[9px] text-gray-400"></i>
                                                </a>
                                                <p class="text-[11px] text-gray-500 truncate mt-0.5">
                                                    <i class="fas fa-map-marker-alt text-brand text-[10px]"></i> {{ $property->area->name ?? $property->city->name ?? 'Noida' }}
                                                </p>
                                                <div class="flex items-center gap-1.5 mt-1">
                                                    <span class="text-[10px] font-black text-gray-800">₹{{ number_format($property->monthly_rent) }}/mo</span>
                                                    <span class="text-[10px] font-extrabold px-1.5 py-0.2 rounded {{ $property->status === 'active' && $property->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                                        {{ $property->status === 'active' && $property->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-gray-400 italic">Property Deleted (ID: {{ Str::limit($report->property_id, 8) }})</div>
                                    @endif
                                </td>

                                <!-- Reporter Details Column -->
                                <td class="py-4 px-4 align-top min-w-[180px]">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-gray-900 truncate">{{ $report->reporter_name ?? ($report->user->name ?? 'Guest User') }}</span>
                                            @if($report->user_id)
                                                <span class="bg-blue-50 text-blue-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded border border-blue-200">Tenant</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 text-[9px] font-extrabold px-1.5 py-0.5 rounded">Guest</span>
                                            @endif
                                        </div>
                                        @if($report->reporter_email || $report->user?->email)
                                            <p class="text-[11px] text-gray-500 truncate">
                                                <i class="fas fa-envelope text-[10px] text-gray-400"></i> {{ $report->reporter_email ?? $report->user->email }}
                                            </p>
                                        @endif
                                        @if($report->reporter_phone || $report->user?->phone)
                                            <p class="text-[11px] text-gray-500 truncate">
                                                <i class="fas fa-phone text-[10px] text-gray-400"></i> {{ $report->reporter_phone ?? $report->user->phone }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Reason & Description Column -->
                                <td class="py-4 px-4 align-top max-w-xs min-w-[220px]">
                                    <div class="space-y-1">
                                        <span class="inline-block bg-rose-50 text-rose-800 border border-rose-200 text-[10px] font-extrabold px-2 py-0.5 rounded-lg shadow-2xs">
                                            <i class="fas fa-circle-exclamation text-rose-500 mr-1"></i> {{ $report->reason }}
                                        </span>
                                        @if($report->description)
                                            <p class="text-[11px] text-gray-600 line-clamp-2 leading-relaxed bg-gray-50 p-1.5 rounded-lg border border-gray-100" title="{{ $report->description }}">
                                                {{ $report->description }}
                                            </p>
                                        @endif
                                        @if($report->admin_notes)
                                            <p class="text-[10px] text-purple-700 flex items-center gap-1 font-semibold truncate" title="{{ $report->admin_notes }}">
                                                <i class="fas fa-notes-medical"></i> {{ Str::limit($report->admin_notes, 35) }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="py-4 px-4 align-top">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $statusBadgeClass }}" id="status-badge-{{ $report->id }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $report->status === 'pending' ? 'bg-rose-500 animate-pulse' : ($report->status === 'investigating' ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </td>

                                <!-- Date Column -->
                                <td class="py-4 px-4 align-top whitespace-nowrap text-gray-500">
                                    <div class="font-medium text-gray-900 text-xs">{{ $report->created_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $report->created_at->diffForHumans() }}</div>
                                </td>

                                <!-- Actions Column -->
                                <td class="py-4 px-4 align-top text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Review & Investigate Modal Button -->
                                        <button type="button" onclick="openReportModal('{{ $report->id }}')" class="bg-brand hover:bg-brand-dark text-white text-[11px] font-bold px-2.5 py-1.5 rounded-xl transition shadow-xs flex items-center gap-1" title="View Full Report & Moderate">
                                            <i class="fas fa-eye"></i> <span>Review</span>
                                        </button>

                                        <!-- Quick Status Dropdown -->
                                        <div class="relative inline-block text-left">
                                            <button type="button" onclick="toggleReportMenu('{{ $report->id }}', event)" class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition" title="Change Status">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div id="report-menu-{{ $report->id }}" class="report-action-menu hidden absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-40 text-left">
                                                <button type="button" onclick="quickUpdateStatus('{{ $report->id }}', 'pending')" class="w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Mark Pending
                                                </button>
                                                <button type="button" onclick="quickUpdateStatus('{{ $report->id }}', 'investigating')" class="w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Mark Investigating
                                                </button>
                                                <button type="button" onclick="quickUpdateStatus('{{ $report->id }}', 'resolved')" class="w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Mark Resolved
                                                </button>
                                                <button type="button" onclick="quickUpdateStatus('{{ $report->id }}', 'dismissed')" class="w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span> Dismiss Report
                                                </button>
                                                <div class="border-t border-gray-100 my-1"></div>
                                                <button type="button" onclick="deleteReport('{{ $report->id }}')" class="w-full px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2 font-bold">
                                                    <i class="fas fa-trash text-[10px]"></i> Delete Report
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reports->hasPages())
                <div class="p-4 border-t border-gray-100 flex items-center justify-between">
                    {{ $reports->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Complete Report & Moderation Modal -->
<div id="reportModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <div onclick="closeReportModal()" class="absolute inset-0 bg-black/60 backdrop-blur-xs"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]">
            
            <!-- Modal Header -->
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-base shadow-xs">
                        <i class="fas fa-shield-virus"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-gray-900 leading-tight">Property Report Investigation</h3>
                        <p class="text-xs text-gray-500" id="modalReportSubtitle">Report Details & Listing Moderation</p>
                    </div>
                </div>
                <button type="button" onclick="closeReportModal()" class="w-8 h-8 rounded-full bg-gray-200/70 hover:bg-gray-300 text-gray-600 flex items-center justify-center transition" title="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-6 flex-1 text-xs">
                
                <!-- 2-Column Grid: Report Details vs Flagged Property Card -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Left Column: Report Reason & Reporter Info -->
                    <div class="space-y-4">
                        <!-- Reason Box -->
                        <div class="bg-rose-50/70 border border-rose-200/80 rounded-2xl p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-wider text-rose-600">Reported Violation Reason</span>
                                <span id="modalReportStatusBadge" class="px-2 py-0.5 rounded-full font-extrabold text-[10px] bg-rose-100 text-rose-800">Pending</span>
                            </div>
                            <h4 id="modalReportReason" class="font-extrabold text-sm text-gray-900">Fake Photos & Hidden Charges</h4>
                            <p id="modalReportDescription" class="text-gray-700 leading-relaxed bg-white p-3 rounded-xl border border-rose-100 shadow-2xs font-normal">
                                The host demanded extra deposit not mentioned in the listing.
                            </p>
                        </div>

                        <!-- Reporter Info Card -->
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 space-y-2.5">
                            <p class="font-bold text-gray-900 flex items-center gap-1.5">
                                <i class="fas fa-user-circle text-brand"></i> Reporter Information
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-[11px]">
                                <div>
                                    <span class="text-gray-400 block font-medium">Name</span>
                                    <span id="modalReporterName" class="font-bold text-gray-900">John Doe</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block font-medium">User Type</span>
                                    <span id="modalReporterType" class="font-bold text-blue-600">Registered Tenant</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block font-medium">Email</span>
                                    <span id="modalReporterEmail" class="font-bold text-gray-800 truncate block">john@example.com</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block font-medium">Phone</span>
                                    <span id="modalReporterPhone" class="font-bold text-gray-800">+91 9876543210</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-400 block font-medium">IP Address & Time</span>
                                    <span id="modalReporterIpTime" class="text-gray-600 font-mono text-[10px]">192.168.1.1 &middot; 2 hours ago</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Flagged Property Card & Host Info -->
                    <div class="space-y-4">
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-4 border border-gray-200/80 shadow-xs space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-500">Flagged Property Details</span>
                                <span id="modalPropertyLiveBadge" class="px-2 py-0.5 rounded-full font-extrabold text-[10px] bg-emerald-100 text-emerald-800">Active</span>
                            </div>

                            <div class="flex items-start gap-3">
                                <img id="modalPropertyImage" src="" alt="Property" class="w-16 h-16 rounded-xl object-cover border border-gray-200 shrink-0">
                                <div class="min-w-0 flex-1">
                                    <h4 id="modalPropertyName" class="font-extrabold text-sm text-gray-900 truncate">Royal Luxury PG</h4>
                                    <p id="modalPropertyLocation" class="text-gray-500 text-[11px] truncate mt-0.5">Sector 62, Noida</p>
                                    <p id="modalPropertyPrice" class="text-brand font-black text-xs mt-1">₹8,500/month</p>
                                </div>
                            </div>

                            <!-- Host / Broker Info -->
                            <div class="bg-white p-3 rounded-xl border border-gray-100 text-[11px] space-y-1">
                                <span class="text-gray-400 block font-bold uppercase text-[9px]">Property Host / Owner</span>
                                <p id="modalBrokerInfo" class="font-bold text-gray-800">Rahul Sharma &middot; +91 9898989898</p>
                            </div>

                            <!-- Direct Property Action Buttons -->
                            <div class="flex items-center gap-2 pt-1">
                                <a id="modalPropertyDetailLink" href="" target="_blank" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-3 rounded-xl text-center transition flex items-center justify-center gap-1.5 no-underline">
                                    <i class="fas fa-external-link-alt text-[10px]"></i> View Public Listing
                                </a>
                            </div>
                        </div>

                        <!-- Moderation Quick Action Buttons -->
                        <div class="bg-rose-50/50 rounded-2xl p-3.5 border border-rose-100 space-y-2">
                            <p class="font-bold text-gray-900 text-xs flex items-center gap-1">
                                <i class="fas fa-gavel text-rose-600"></i> Property Moderation Control:
                            </p>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="executePropertyModeration('suspend')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-3 rounded-xl transition shadow-xs flex items-center justify-center gap-1">
                                    <i class="fas fa-ban"></i> Suspend Property
                                </button>
                                <button type="button" onclick="executePropertyModeration('verify')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-3 rounded-xl transition shadow-xs flex items-center justify-center gap-1">
                                    <i class="fas fa-check-circle"></i> Keep Active / Safe
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Investigation Notes & Status Update Form -->
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 space-y-3">
                    <label class="block font-bold text-gray-900 text-xs flex items-center justify-between">
                        <span><i class="fas fa-clipboard-check text-purple-600 mr-1"></i> Admin Investigation Log & Internal Notes</span>
                        <span class="text-[10px] font-normal text-gray-500">Visible to administrators only</span>
                    </label>
                    <textarea id="modalAdminNotes" rows="3" placeholder="Add investigation notes, verification findings, phone calls with host, or resolution comments..." class="w-full p-3 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium"></textarea>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-700 text-xs">Set Status:</span>
                            <select id="modalStatusSelect" class="px-3 py-2 bg-white border border-gray-300 rounded-xl text-xs font-bold text-gray-800 focus:ring-2 focus:ring-brand/50">
                                <option value="pending">⏳ Pending Review</option>
                                <option value="investigating">🔍 Under Investigation</option>
                                <option value="action_taken">⚡ Action Taken / Penalized</option>
                                <option value="resolved">✅ Resolved / Fixed</option>
                                <option value="dismissed">❌ Dismissed / Invalid</option>
                            </select>
                        </div>

                        <button type="button" onclick="saveReportDetails()" class="bg-brand hover:bg-brand-dark text-white font-bold py-2 px-5 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5 text-xs">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentActiveReportId = null;

function toggleReportMenu(id, event) {
    if (event) {
        event.stopPropagation();
    }
    const menu = document.getElementById(`report-menu-${id}`);
    if (!menu) return;
    const isHidden = menu.classList.contains('hidden');

    // Close any other open menus
    document.querySelectorAll('.report-action-menu').forEach(m => m.classList.add('hidden'));

    if (isHidden) {
        menu.classList.remove('hidden');
    }
}

// Close dropdowns when clicking anywhere outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.report-action-menu')) {
        document.querySelectorAll('.report-action-menu').forEach(m => m.classList.add('hidden'));
    }
});

function showReportToast(message, type = 'success') {
    const toast = document.getElementById('reportToast');
    const toastMessage = document.getElementById('reportToastMessage');
    const toastIcon = document.getElementById('reportToastIcon');
    if (!toast) return;

    toastMessage.innerText = message;
    if (type === 'success') {
        toastIcon.innerHTML = '<i class="fas fa-check-circle text-emerald-400"></i>';
    } else if (type === 'error') {
        toastIcon.innerHTML = '<i class="fas fa-times-circle text-rose-400"></i>';
    } else {
        toastIcon.innerHTML = '<i class="fas fa-info-circle text-blue-400"></i>';
    }

    toast.classList.remove('opacity-0', 'translate-y-20', 'pointer-events-none');
    toast.classList.add('opacity-100', 'translate-y-0');

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-20', 'pointer-events-none');
        toast.classList.remove('opacity-100', 'translate-y-0');
    }, 3500);
}

function openReportModal(id) {
    currentActiveReportId = id;
    const modal = document.getElementById('reportModal');
    modal.classList.remove('hidden');

    // Fetch report data
    fetch(`/admin/reports/${id}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success) {
            alert('Failed to load report data.');
            return;
        }
        const d = res.data;

        document.getElementById('modalReportSubtitle').innerText = `Report #${d.id.substring(0, 8)} &middot; Submitted ${d.created_at_diff}`;
        document.getElementById('modalReportReason').innerText = d.reason;
        document.getElementById('modalReportDescription').innerText = d.description;
        document.getElementById('modalReportStatusBadge').innerText = d.status_label;

        // Reporter
        document.getElementById('modalReporterName').innerText = d.reporter.name;
        document.getElementById('modalReporterType').innerText = d.reporter.is_registered ? 'Registered Tenant' : 'Guest User';
        document.getElementById('modalReporterEmail').innerText = d.reporter.email;
        document.getElementById('modalReporterPhone').innerText = d.reporter.phone;
        document.getElementById('modalReporterIpTime').innerText = `IP: ${d.ip_address} · ${d.created_at_formatted}`;

        // Property
        if (d.property) {
            document.getElementById('modalPropertyImage').src = d.property.image;
            document.getElementById('modalPropertyName').innerText = d.property.name;
            document.getElementById('modalPropertyLocation').innerText = `${d.property.area}, ${d.property.city}`;
            document.getElementById('modalPropertyPrice').innerText = d.property.formatted_price;
            document.getElementById('modalPropertyDetailLink').href = d.property.detail_url;

            const isActive = d.property.is_active && d.property.status === 'active';
            document.getElementById('modalPropertyLiveBadge').innerText = isActive ? 'Active Listing' : 'Suspended / Inactive';
            document.getElementById('modalPropertyLiveBadge').className = `px-2 py-0.5 rounded-full font-extrabold text-[10px] ${isActive ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}`;

            if (d.property.broker) {
                document.getElementById('modalBrokerInfo').innerText = `${d.property.broker.name} · ${d.property.broker.phone} (${d.property.broker.email})`;
            } else {
                document.getElementById('modalBrokerInfo').innerText = 'No Broker Assigned';
            }
        }

        // Notes & Status select
        document.getElementById('modalAdminNotes').value = d.admin_notes || '';
        document.getElementById('modalStatusSelect').value = d.status;
    })
    .catch(err => {
        console.error(err);
        showReportToast('Failed to fetch report details.', 'error');
    });
}

function closeReportModal() {
    const modal = document.getElementById('reportModal');
    modal.classList.add('hidden');
    currentActiveReportId = null;
}

function saveReportDetails() {
    if (!currentActiveReportId) return;

    const status = document.getElementById('modalStatusSelect').value;
    const adminNotes = document.getElementById('modalAdminNotes').value;

    fetch(`/admin/reports/${currentActiveReportId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status, admin_notes: adminNotes })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showReportToast(res.message, 'success');
            closeReportModal();
            setTimeout(() => window.location.reload(), 600);
        } else {
            showReportToast('Failed to update report status.', 'error');
        }
    })
    .catch(err => {
        showReportToast('Error occurred while updating report.', 'error');
    });
}

function quickUpdateStatus(id, status) {
    fetch(`/admin/reports/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showReportToast(res.message, 'success');
            setTimeout(() => window.location.reload(), 500);
        } else {
            showReportToast('Failed to update status.', 'error');
        }
    });
}

function executePropertyModeration(action) {
    if (!currentActiveReportId) return;

    const confirmMsg = action === 'suspend' 
        ? 'Are you sure you want to suspend this property listing immediately? It will be unpublished from search.' 
        : 'Are you sure you want to approve and keep this property active?';

    if (!confirm(confirmMsg)) return;

    const reason = prompt('Add an optional moderation note for this action:', '');

    fetch(`/admin/reports/${currentActiveReportId}/property-action`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action, reason })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showReportToast(res.message, 'success');
            closeReportModal();
            setTimeout(() => window.location.reload(), 600);
        } else {
            showReportToast(res.message || 'Action failed.', 'error');
        }
    })
    .catch(err => {
        showReportToast('Error executing property moderation.', 'error');
    });
}

function deleteReport(id) {
    if (!confirm('Are you sure you want to permanently delete this report from database? This action cannot be undone.')) return;

    fetch(`/admin/reports/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showReportToast(res.message, 'success');
            const row = document.getElementById(`report-row-${id}`);
            if (row) row.remove();
        } else {
            showReportToast('Failed to delete report.', 'error');
        }
    });
}
</script>
@endpush
@endsection
