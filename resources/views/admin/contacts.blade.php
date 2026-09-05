@extends('admin.layouts.app')

@section('title', 'Contact Inquiries & Leads')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Contact Inquiries & Support Leads</h1>
        <p class="text-sm text-gray-500">{{ $totalInquiries }} total inquiries &middot; {{ $newInquiries }} new / unread</p>
    </div>
    <div class="flex items-center gap-3">
        @if($newInquiries > 0)
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-2 animate-pulse">
                <i class="fas fa-envelope text-rose-600"></i> {{ $newInquiries }} New Lead{{ $newInquiries > 1 ? 's' : '' }}
            </div>
        @endif
        <a href="{{ route('admin.contacts.export', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm tap-effect flex items-center gap-2">
            <i class="fas fa-file-excel text-sm"></i> Export to Excel
        </a>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Toast Notification -->
    <div id="contactToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="contactToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="contactToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="contactToastMessage">Action completed successfully</span>
        </div>
    </div>

    <!-- Mobile Top Action Bar -->
    <div class="lg:hidden flex items-center justify-between bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h2 class="font-bold text-gray-900 text-base">Contact Inquiries</h2>
            <p class="text-xs text-gray-500">{{ $totalInquiries }} total ({{ $newInquiries }} new)</p>
        </div>
        <a href="{{ route('admin.contacts.export', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-3 rounded-xl text-xs transition flex items-center gap-1.5 shadow-sm">
            <i class="fas fa-file-excel"></i> Export
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalInquiries) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Total Inquiries</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center text-lg">
                <i class="fas fa-inbox"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-rose-600">{{ number_format($newInquiries) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">New / Unread</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fas fa-envelope-open-text"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-blue-600">{{ number_format($inProgressInquiries) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">In Progress</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fas fa-spinner"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($resolvedInquiries) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Resolved</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.contacts') }}" class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm space-y-3">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2 border-b border-gray-100">
            <a href="{{ route('admin.contacts', array_merge(request()->query(), ['status' => 'all'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ ($statusFilter === 'all' || !$statusFilter) ? 'bg-brand text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                All ({{ $totalInquiries }})
            </a>
            <a href="{{ route('admin.contacts', array_merge(request()->query(), ['status' => 'new'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ $statusFilter === 'new' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                <i class="fas fa-envelope text-[10px]"></i> New / Unread ({{ $newInquiries }})
            </a>
            <a href="{{ route('admin.contacts', array_merge(request()->query(), ['status' => 'in_progress'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ $statusFilter === 'in_progress' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                <i class="fas fa-spinner text-[10px]"></i> In Progress ({{ $inProgressInquiries }})
            </a>
            <a href="{{ route('admin.contacts', array_merge(request()->query(), ['status' => 'resolved'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ $statusFilter === 'resolved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                <i class="fas fa-check text-[10px]"></i> Resolved ({{ $resolvedInquiries }})
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 pt-1">
            <!-- Search Text -->
            <div class="lg:col-span-5 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Search by name, email, phone, city, or message..." class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium">
            </div>

            <!-- User Type Filter -->
            <div class="lg:col-span-3">
                <select name="user_type" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium cursor-pointer">
                    <option value="all">All Inquirer Categories</option>
                    @foreach($userTypes as $key => $label)
                        <option value="{{ $key }}" {{ $typeFilter == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort Order -->
            <div class="lg:col-span-2">
                <select name="sort" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium cursor-pointer">
                    <option value="latest" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 bg-brand hover:bg-brand-dark text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm tap-effect flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter text-[11px]"></i> Filter
                </button>
                @if($searchQuery || ($statusFilter && $statusFilter !== 'all') || ($typeFilter && $typeFilter !== 'all') || $sortOrder === 'asc')
                    <a href="{{ route('admin.contacts') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-3 rounded-xl text-xs transition tap-effect" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Contacts Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-5">Lead / Inquirer</th>
                        <th class="py-4 px-3">Category</th>
                        <th class="py-4 px-3">City / Location</th>
                        <th class="py-4 px-4 min-w-[240px]">Message Preview</th>
                        <th class="py-4 px-3 text-center">Status</th>
                        <th class="py-4 px-3">Submitted</th>
                        <th class="py-4 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs font-normal">
                    @forelse($inquiries as $inquiry)
                        <tr id="inquiry-row-{{ $inquiry->id }}" class="hover:bg-gray-50/60 transition group {{ $inquiry->status === 'new' ? 'bg-rose-50/20' : '' }}">
                            <!-- Lead / Inquirer -->
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $inquiry->status === 'new' ? 'bg-rose-100 text-rose-700' : 'bg-brand-50 text-brand' }} flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($inquiry->name, 0, 1)) }}
                                    </div>
                                    <div class="overflow-hidden min-w-0">
                                        <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                            <span>{{ $inquiry->name }}</span>
                                            @if($inquiry->status === 'new')
                                                <span class="inline-block w-2 h-2 rounded-full bg-rose-500" title="New unread lead"></span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-500 truncate flex items-center gap-2 mt-0.5">
                                            <a href="mailto:{{ $inquiry->email }}" class="hover:text-brand transition flex items-center gap-1">
                                                <i class="fas fa-envelope text-[10px]"></i> {{ $inquiry->email }}
                                            </a>
                                            <span>&middot;</span>
                                            <a href="tel:{{ $inquiry->phone }}" class="hover:text-brand transition flex items-center gap-1">
                                                <i class="fas fa-phone text-[10px]"></i> {{ $inquiry->phone }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3.5 px-3">
                                @php
                                    $typeBadgeStyles = [
                                        'tenant' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'owner' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'partner' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'support' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        'other' => 'bg-gray-50 text-gray-700 border-gray-200',
                                    ];
                                    $badgeClass = $typeBadgeStyles[$inquiry->user_type] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $badgeClass }}">
                                    {{ $inquiry->user_type_label }}
                                </span>
                            </td>

                            <!-- City -->
                            <td class="py-3.5 px-3 text-gray-600">
                                @if($inquiry->city)
                                    <span class="flex items-center gap-1 text-gray-800 font-medium">
                                        <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> {{ Str::limit($inquiry->city, 20) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Not provided</span>
                                @endif
                            </td>

                            <!-- Message Preview -->
                            <td class="py-3.5 px-4 text-gray-700">
                                <div class="max-w-xs md:max-w-md line-clamp-2 leading-relaxed font-normal">
                                    {{ $inquiry->message }}
                                </div>
                                @if($inquiry->admin_notes)
                                    <div class="mt-1 text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 inline-flex items-center gap-1">
                                        <i class="fas fa-sticky-note"></i> Note: {{ Str::limit($inquiry->admin_notes, 35) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-3 text-center" id="status-cell-{{ $inquiry->id }}">
                                @if($inquiry->status === 'new')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> New
                                    </span>
                                @elseif($inquiry->status === 'in_progress')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> In Progress
                                    </span>
                                @elseif($inquiry->status === 'resolved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Resolved
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                        {{ ucfirst($inquiry->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Submitted Date -->
                            <td class="py-3.5 px-3 text-gray-500 whitespace-nowrap">
                                <div class="font-medium text-gray-800">{{ $inquiry->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $inquiry->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Details Button -->
                                    <button onclick="openViewModal({{ $inquiry->id }})" class="p-2 rounded-xl bg-gray-100 hover:bg-brand-50 hover:text-brand text-gray-600 transition tap-effect" title="View Full Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <!-- Quick WhatsApp Direct Link -->
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $inquiry->phone);
                                        if (strlen($cleanPhone) === 10) { $cleanPhone = '91' . $cleanPhone; }
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello ' . $inquiry->name . ', thank you for contacting SpaceSeeks.') }}" target="_blank" class="p-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition tap-effect" title="Chat on WhatsApp">
                                        <i class="fab fa-whatsapp text-xs"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <button onclick="confirmDelete({{ $inquiry->id }}, '{{ addslashes($inquiry->name) }}')" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition tap-effect" title="Delete Inquiry">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-gray-400">
                                <div class="w-16 h-16 rounded-3xl bg-gray-50 text-gray-300 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p class="font-bold text-gray-700 text-sm">No Contact Inquiries Found</p>
                                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">There are currently no customer inquiries matching the selected filters or search keywords.</p>
                                @if($searchQuery || ($statusFilter && $statusFilter !== 'all') || ($typeFilter && $typeFilter !== 'all'))
                                    <a href="{{ route('admin.contacts') }}" class="inline-block mt-4 bg-brand text-white font-bold py-2 px-4 rounded-xl text-xs tap-effect transition">
                                        Clear All Filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        @if($inquiries->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500 font-medium">
                    Showing <span class="font-bold text-gray-800">{{ $inquiries->firstItem() ?? 0 }}</span> to <span class="font-bold text-gray-800">{{ $inquiries->lastItem() ?? 0 }}</span> of <span class="font-bold text-gray-800">{{ $inquiries->total() }}</span> inquiries
                </div>
                <div>
                    {{ $inquiries->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- VIEW DETAILS MODAL -->
<div id="viewModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-fadeIn">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-brand-50 to-white px-6 py-5 border-b border-brand-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand text-white flex items-center justify-center text-lg font-bold shadow-md shadow-brand/20">
                    <i class="fas fa-id-card"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base" id="modalInquirerName">Contact Inquiry Details</h3>
                    <p class="text-xs text-brand-dark font-medium" id="modalInquiryMeta">Inquiry ID #</p>
                </div>
            </div>
            <button onclick="closeViewModal()" class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center tap-effect">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
            <!-- Inquirer Contact Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100 text-xs">
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Email Address</span>
                    <a id="modalEmail" href="#" class="font-bold text-brand hover:underline mt-0.5 inline-block text-sm"></a>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Phone Number</span>
                    <a id="modalPhone" href="#" class="font-bold text-brand hover:underline mt-0.5 inline-block text-sm"></a>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Inquirer Role</span>
                    <span id="modalUserType" class="font-bold text-gray-800 mt-0.5 inline-block"></span>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">City / Location</span>
                    <span id="modalCity" class="font-bold text-gray-800 mt-0.5 inline-block"></span>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Submission Time</span>
                    <span id="modalCreatedAt" class="font-semibold text-gray-600 mt-0.5 inline-block"></span>
                </div>
                <div>
                    <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">IP Address</span>
                    <span id="modalIpAddress" class="font-mono text-gray-600 mt-0.5 inline-block"></span>
                </div>
            </div>

            <!-- Quick Reach Out Action Buttons -->
            <div class="flex flex-wrap gap-2.5">
                <a id="modalCallBtn" href="#" class="flex-1 min-w-[130px] bg-brand hover:bg-brand-dark text-white font-bold py-2.5 px-4 rounded-xl text-xs transition tap-effect flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-phone-alt"></i> Call Now
                </a>
                <a id="modalWhatsAppBtn" href="#" target="_blank" class="flex-1 min-w-[130px] bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition tap-effect flex items-center justify-center gap-2 shadow-sm">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a id="modalMailBtn" href="#" class="flex-1 min-w-[130px] bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition tap-effect flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-envelope"></i> Send Email
                </a>
            </div>

            <!-- Full Inquiry Message -->
            <div>
                <label class="block text-xs font-bold text-gray-800 uppercase tracking-wide mb-2">Inquiry Message</label>
                <div id="modalMessage" class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-xs text-gray-800 leading-relaxed font-normal whitespace-pre-wrap"></div>
            </div>

            <!-- Status & Internal Notes Form -->
            <form id="updateStatusForm" onsubmit="handleStatusUpdate(event)" class="space-y-4 pt-3 border-t border-gray-100">
                <input type="hidden" id="modalInquiryId" name="inquiry_id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Update Status</label>
                        <select id="modalStatusSelect" name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand/30">
                            <option value="new">🔴 New / Unread</option>
                            <option value="in_progress">🔵 In Progress</option>
                            <option value="resolved">🟢 Resolved</option>
                            <option value="archived">⚪ Archived</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Internal Admin Notes</label>
                    <textarea id="modalAdminNotes" name="admin_notes" rows="3" placeholder="Add follow-up notes, assigned representative, or customer response details..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeViewModal()" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-bold text-xs hover:bg-gray-100 transition tap-effect">
                        Close
                    </button>
                    <button type="submit" id="saveNotesBtn" class="px-5 py-2.5 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-xs transition tap-effect shadow-md shadow-brand/20 flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Notes & Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-fadeIn p-6 text-center space-y-5">
        <div class="w-14 h-14 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto shadow-inner">
            <i class="fas fa-trash-alt"></i>
        </div>
        <div>
            <h3 class="font-bold text-gray-900 text-lg">Delete Contact Inquiry?</h3>
            <p class="text-xs text-gray-500 mt-1">Are you sure you want to permanently delete the inquiry from <span id="deleteInquirerName" class="font-bold text-gray-800"></span>? This action cannot be undone.</p>
        </div>
        <div class="flex items-center gap-3 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-xl text-xs transition tap-effect">
                Cancel
            </button>
            <button type="button" id="confirmDeleteBtn" onclick="executeDelete()" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 rounded-xl text-xs transition tap-effect shadow-md shadow-rose-600/30 flex items-center justify-center gap-2">
                <i class="fas fa-trash"></i> Yes, Delete
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeInquiryIdToDelete = null;

    // Toast helper
    function showToast(message, isError = false) {
        const toast = document.getElementById('contactToastNotification');
        const msgEl = document.getElementById('contactToastMessage');
        const iconEl = document.getElementById('contactToastIcon');

        msgEl.innerText = message;
        if (isError) {
            iconEl.innerHTML = '<i class="fas fa-exclamation-circle text-rose-400"></i>';
        } else {
            iconEl.innerHTML = '<i class="fas fa-check-circle text-emerald-400"></i>';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3500);
    }

    // Open view inquiry modal
    async function openViewModal(id) {
        try {
            const res = await fetch(`{{ url('/admin/contacts') }}/${id}`);
            const json = await res.json();

            if (!json.success || !json.data) {
                showToast('Failed to load inquiry details.', true);
                return;
            }

            const d = json.data;
            document.getElementById('modalInquiryId').value = d.id;
            document.getElementById('modalInquirerName').innerText = d.name;
            document.getElementById('modalInquiryMeta').innerText = `Inquiry ID #${d.id} · Submitted ${d.created_at_diff}`;
            
            const emailEl = document.getElementById('modalEmail');
            emailEl.innerText = d.email;
            emailEl.href = `mailto:${d.email}`;
            
            const phoneEl = document.getElementById('modalPhone');
            phoneEl.innerText = d.phone;
            phoneEl.href = `tel:${d.phone}`;
            
            document.getElementById('modalUserType').innerText = d.user_type_label;
            document.getElementById('modalCity').innerText = d.city;
            document.getElementById('modalCreatedAt').innerText = d.created_at_formatted;
            document.getElementById('modalIpAddress').innerText = d.ip_address;
            document.getElementById('modalMessage').innerText = d.message;
            document.getElementById('modalStatusSelect').value = d.status;
            document.getElementById('modalAdminNotes').value = d.admin_notes || '';

            // Update Reach out buttons
            document.getElementById('modalCallBtn').href = `tel:${d.phone}`;
            document.getElementById('modalMailBtn').href = `mailto:${d.email}?subject=SpaceSeeks%20Support%20Follow-up%20(Inquiry%20%23${d.id})`;
            
            let cleanNum = d.phone.replace(/[^0-9]/g, '');
            if (cleanNum.length === 10) cleanNum = '91' + cleanNum;
            document.getElementById('modalWhatsAppBtn').href = `https://wa.me/${cleanNum}?text=Hello%20${encodeURIComponent(d.name)},%20thank%20you%20for%20contacting%20SpaceSeeks.%20We%20received%20your%20inquiry%20regarding:%20${encodeURIComponent(d.message.substring(0, 50))}...`;

            document.getElementById('viewModal').classList.remove('hidden');
        } catch (e) {
            console.error(e);
            showToast('An error occurred while fetching details.', true);
        }
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
    }

    // Handle Status and Notes Update
    async function handleStatusUpdate(e) {
        e.preventDefault();
        const id = document.getElementById('modalInquiryId').value;
        const status = document.getElementById('modalStatusSelect').value;
        const notes = document.getElementById('modalAdminNotes').value;
        const saveBtn = document.getElementById('saveNotesBtn');

        const originalHtml = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveBtn.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`{{ url('/admin/contacts') }}/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: status, admin_notes: notes })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message);
                
                // Update table row badge
                const cell = document.getElementById(`status-cell-${id}`);
                if (cell) {
                    if (status === 'new') {
                        cell.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> New</span>';
                    } else if (status === 'in_progress') {
                        cell.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> In Progress</span>';
                    } else if (status === 'resolved') {
                        cell.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Resolved</span>';
                    } else {
                        cell.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">${status.toUpperCase()}</span>`;
                    }
                }

                closeViewModal();
            } else {
                showToast(data.message || 'Failed to update status.', true);
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while saving notes.', true);
        } finally {
            saveBtn.innerHTML = originalHtml;
            saveBtn.disabled = false;
        }
    }

    // Delete flow
    function confirmDelete(id, name) {
        activeInquiryIdToDelete = id;
        document.getElementById('deleteInquirerName').innerText = name;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        activeInquiryIdToDelete = null;
        document.getElementById('deleteModal').classList.add('hidden');
    }

    async function executeDelete() {
        if (!activeInquiryIdToDelete) return;
        const id = activeInquiryIdToDelete;
        const btn = document.getElementById('confirmDeleteBtn');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        btn.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`{{ url('/admin/contacts') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await res.json();

            if (res.ok && data.success) {
                showToast(data.message);
                const row = document.getElementById(`inquiry-row-${id}`);
                if (row) {
                    row.classList.add('opacity-0', 'scale-95', 'transition-all', 'duration-300');
                    setTimeout(() => row.remove(), 300);
                }
                closeDeleteModal();
            } else {
                showToast(data.message || 'Failed to delete inquiry.', true);
            }
        } catch (err) {
            console.error(err);
            showToast('Network error while deleting.', true);
        } finally {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    }
</script>
@endpush
