@extends('admin.layouts.app')

@section('title', 'Manage Reviews & Ratings')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manage Guest Reviews & Moderation</h1>
        <p class="text-sm text-gray-500">{{ $totalReviews }} total resident reviews &middot; {{ $pendingReviews }} pending approval</p>
    </div>
    <div class="flex items-center gap-3">
        @if($pendingReviews > 0)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-2 animate-pulse">
                <i class="fas fa-exclamation-circle text-amber-600"></i> {{ $pendingReviews }} Review{{ $pendingReviews > 1 ? 's' : '' }} Require Moderation
            </div>
        @endif
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Toast Notification -->
    <div id="reviewToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="reviewToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="reviewToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="reviewToastMessage">Action completed</span>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalReviews) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Total Reviews</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center text-lg">
                <i class="fas fa-comments"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-amber-600">{{ number_format($pendingReviews) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Pending Moderation</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($approvedReviews) }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Approved & Live</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-yellow-500 flex items-center gap-1.5">
                    <span>{{ $avgRating }}</span>
                    <span class="text-base text-yellow-400">★</span>
                </div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Avg Platform Rating</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-lg">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.reviews') }}" class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm space-y-3">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2 border-b border-gray-100">
            <a href="{{ route('admin.reviews', array_merge(request()->query(), ['status' => 'all'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ ($statusFilter === 'all' || !$statusFilter) ? 'bg-brand text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                All ({{ $totalReviews }})
            </a>
            <a href="{{ route('admin.reviews', array_merge(request()->query(), ['status' => 'pending'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                <i class="fas fa-clock text-[10px]"></i> Pending Approval ({{ $pendingReviews }})
            </a>
            <a href="{{ route('admin.reviews', array_merge(request()->query(), ['status' => 'approved'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $statusFilter === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                <i class="fas fa-check text-[10px]"></i> Approved Live ({{ $approvedReviews }})
            </a>
            <a href="{{ route('admin.reviews', array_merge(request()->query(), ['status' => 'rejected'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $statusFilter === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                <i class="fas fa-ban text-[10px]"></i> Rejected ({{ $rejectedReviews }})
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-1">
            <!-- Search Text -->
            <div class="lg:col-span-2 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Search by reviewer, property, title or comment..." class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium">
            </div>

            <!-- Property Filter -->
            <div>
                <select name="property_id" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium cursor-pointer">
                    <option value="">All Properties</option>
                    @foreach($properties as $p)
                        <option value="{{ $p->id }}" {{ $propertyFilter == $p->id ? 'selected' : '' }}>{{ Str::limit($p->name, 25) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Rating Filter -->
            <div>
                <select name="rating" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand/50 font-medium cursor-pointer">
                    <option value="">All Ratings (1★ - 5★)</option>
                    <option value="5" {{ $ratingFilter == '5' ? 'selected' : '' }}>5 Stars Only</option>
                    <option value="4" {{ $ratingFilter == '4' ? 'selected' : '' }}>4 Stars & Above</option>
                    <option value="3" {{ $ratingFilter == '3' ? 'selected' : '' }}>3 Stars & Above</option>
                    <option value="2" {{ $ratingFilter == '2' ? 'selected' : '' }}>2 Stars & Above</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-brand hover:bg-brand-dark text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm tap-effect flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter text-[11px]"></i> Filter
                </button>
                @if($searchQuery || ($statusFilter && $statusFilter !== 'all') || $ratingFilter || $propertyFilter)
                    <a href="{{ route('admin.reviews') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-3 rounded-xl text-xs transition tap-effect" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Reviews Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-5">Reviewer</th>
                        <th class="py-4 px-4">Property</th>
                        <th class="py-4 px-3 text-center">Rating</th>
                        <th class="py-4 px-4">Review Content</th>
                        <th class="py-4 px-3 text-center">Status</th>
                        <th class="py-4 px-3">Date</th>
                        <th class="py-4 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($reviews as $rev)
                        @php
                            $userName = $rev->user ? ($rev->user->name ?: ($rev->user->profile ? $rev->user->profile->first_name . ' ' . $rev->user->profile->last_name : 'Verified Resident')) : 'Anonymous Resident';
                            $userEmail = $rev->user->email ?? 'No email';
                            $userInitial = strtoupper(substr($userName, 0, 1));
                            $propName = $rev->property->name ?? 'Property Removed';
                            $propSlug = $rev->property ? ($rev->property->slug ?: \Illuminate\Support\Str::slug($rev->property->name)) : '';
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition" id="reviewRow-{{ $rev->id }}">
                            <!-- Reviewer -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand to-brand-dark text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                        {{ $userInitial }}
                                    </div>
                                    <div class="min-w-0 max-w-[160px]">
                                        <p class="font-bold text-gray-900 truncate">{{ $userName }}</p>
                                        <p class="text-[10px] text-gray-400 truncate">{{ $userEmail }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Property -->
                            <td class="py-4 px-4">
                                <div class="min-w-0 max-w-[180px]">
                                    @if($rev->property)
                                        <a href="{{ route('user.detail', ['slug' => $propSlug]) }}" target="_blank" class="font-bold text-brand hover:underline truncate block">
                                            {{ $propName }}
                                        </a>
                                        <p class="text-[10px] text-gray-400 truncate">
                                            {{ $rev->property->city->name ?? '' }}
                                        </p>
                                    @else
                                        <span class="text-gray-400 italic">Deleted Property</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Rating -->
                            <td class="py-4 px-3 text-center">
                                <div class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-800 border border-yellow-200 px-2.5 py-1 rounded-xl text-xs font-black shadow-xs">
                                    <i class="fas fa-star text-yellow-500 text-[10px]"></i>
                                    <span>{{ number_format($rev->rating, 1) }}</span>
                                </div>
                            </td>

                            <!-- Content -->
                            <td class="py-4 px-4 max-w-xs">
                                <div>
                                    @if($rev->title && $rev->title !== 'Verified Resident Review')
                                        <p class="font-bold text-gray-900 text-xs mb-0.5 truncate">{{ $rev->title }}</p>
                                    @endif
                                    <p class="text-gray-600 text-xs line-clamp-2 leading-relaxed">{{ $rev->comment }}</p>
                                    @if($rev->broker_reply)
                                        <div class="mt-1 text-[10px] bg-emerald-50 text-emerald-800 p-1.5 rounded-lg border border-emerald-100">
                                            <span class="font-bold">Reply:</span> {{ Str::limit($rev->broker_reply, 45) }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-3 text-center" id="reviewStatusCell-{{ $rev->id }}">
                                @if($rev->status === 'approved')
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                        <i class="fas fa-check-circle text-[9px]"></i> Approved
                                    </span>
                                @elseif($rev->status === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-full text-[10px] font-bold animate-pulse">
                                        <i class="fas fa-clock text-[9px]"></i> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                        <i class="fas fa-times-circle text-[9px]"></i> Rejected
                                    </span>
                                @endif
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-3 text-[11px] text-gray-500 whitespace-nowrap">
                                <div>{{ $rev->created_at ? $rev->created_at->format('d M, Y') : 'N/A' }}</div>
                                <div class="text-[9px] text-gray-400">{{ $rev->created_at ? $rev->created_at->diffForHumans() : '' }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($rev->status !== 'approved')
                                        <button type="button" onclick="moderateReview('{{ $rev->id }}', 'approve')" class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white transition flex items-center justify-center text-xs shadow-xs" title="Approve & Publish Live">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif

                                    @if($rev->status !== 'rejected')
                                        <button type="button" onclick="moderateReview('{{ $rev->id }}', 'reject')" class="w-8 h-8 rounded-xl bg-amber-50 hover:bg-amber-600 text-amber-600 hover:text-white transition flex items-center justify-center text-xs shadow-xs" title="Reject Review">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif

                                    <!-- Reply Button -->
                                    <button type="button" onclick="openReplyModal('{{ $rev->id }}', '{{ addslashes($rev->broker_reply ?? '') }}', '{{ addslashes($userName) }}')" class="w-8 h-8 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white transition flex items-center justify-center text-xs shadow-xs" title="Add / Edit Official Reply">
                                        <i class="fas fa-reply"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" onclick="deleteReview('{{ $rev->id }}')" class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white transition flex items-center justify-center text-xs shadow-xs" title="Delete Review">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <div class="w-14 h-14 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <p class="font-bold text-sm text-gray-700">No Reviews Found</p>
                                <p class="text-xs text-gray-400 mt-1">There are no reviews matching your current filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Official Response / Host Reply Modal -->
<div id="reviewReplyModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div onclick="closeReplyModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 z-10 border border-gray-100">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fas fa-reply"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Official Host Reply</h3>
                    <p class="text-[11px] text-gray-500" id="replyModalSubtitle">Replying to review</p>
                </div>
            </div>
            <button onclick="closeReplyModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <form id="replyForm" onsubmit="submitReply(event)">
            @csrf
            <input type="hidden" id="replyReviewId" value="">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Official Response / Host Reply</label>
                    <textarea id="replyText" name="reply" rows="4" required placeholder="Thank the resident for their feedback or address their concerns professionally..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
                </div>
            </div>

            <div class="mt-5 pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeReplyModal()" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-xs">Cancel</button>
                <button type="submit" id="replySubmitBtn" class="px-5 py-2 rounded-xl bg-brand hover:bg-brand-dark text-white font-bold text-xs shadow-md shadow-brand/20">Save Reply</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('reviewToastNotification');
        const msgEl = document.getElementById('reviewToastMessage');
        const iconEl = document.getElementById('reviewToastIcon');

        if (!toast || !msgEl || !iconEl) return;

        msgEl.innerText = message;
        if (type === 'success') {
            iconEl.innerHTML = '<i class="fas fa-check-circle text-emerald-400"></i>';
        } else if (type === 'error') {
            iconEl.innerHTML = '<i class="fas fa-times-circle text-rose-400"></i>';
        } else {
            iconEl.innerHTML = '<i class="fas fa-info-circle text-blue-400"></i>';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3500);
    }

    async function moderateReview(reviewId, action) {
        const url = action === 'approve'
            ? `/admin/reviews/${reviewId}/approve`
            : `/admin/reviews/${reviewId}/reject`;

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                const statusCell = document.getElementById(`reviewStatusCell-${reviewId}`);
                if (statusCell) {
                    if (action === 'approve') {
                        statusCell.innerHTML = `<span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-[10px] font-bold"><i class="fas fa-check-circle text-[9px]"></i> Approved</span>`;
                    } else {
                        statusCell.innerHTML = `<span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-1 rounded-full text-[10px] font-bold"><i class="fas fa-times-circle text-[9px]"></i> Rejected</span>`;
                    }
                }
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Operation failed', 'error');
            }
        } catch (err) {
            showToast('Failed to update review status', 'error');
        }
    }

    async function deleteReview(reviewId) {
        if (!confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            return;
        }

        try {
            const res = await fetch(`/admin/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                const row = document.getElementById(`reviewRow-${reviewId}`);
                if (row) row.remove();
            } else {
                showToast(data.message || 'Could not delete review', 'error');
            }
        } catch (err) {
            showToast('Delete request failed', 'error');
        }
    }

    function openReplyModal(reviewId, currentReply, reviewerName) {
        document.getElementById('replyReviewId').value = reviewId;
        document.getElementById('replyText').value = currentReply || '';
        document.getElementById('replyModalSubtitle').innerText = `Replying to review by ${reviewerName}`;
        const modal = document.getElementById('reviewReplyModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeReplyModal() {
        const modal = document.getElementById('reviewReplyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function submitReply(e) {
        e.preventDefault();
        const reviewId = document.getElementById('replyReviewId').value;
        const replyText = document.getElementById('replyText').value.trim();
        const submitBtn = document.getElementById('replySubmitBtn');

        if (!replyText) {
            alert('Please enter reply text');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerText = 'Saving...';

        try {
            const res = await fetch(`/admin/reviews/${reviewId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reply: replyText })
            });

            const data = await res.json();
            if (data.success) {
                showToast('Host reply saved successfully!', 'success');
                closeReplyModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Failed to save reply', 'error');
            }
        } catch (err) {
            showToast('Request failed', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Save Reply';
        }
    }
</script>
@endpush
@endsection
