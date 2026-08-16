@extends('broker.layouts.app')

@section('title', 'Tenant Reviews')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tenant Reviews & Ratings</h1>
        <p class="text-sm text-gray-500">124 verified reviews from current and past tenants</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="bg-brand-light text-brand px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5">
            <i class="fas fa-certificate text-brand"></i> Top Rated Host Badge
        </span>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Rating Overview Banner -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            <div class="text-center md:text-left md:border-r md:border-gray-100 md:pr-8">
                <div class="text-5xl font-extrabold text-gray-900 leading-none">4.8</div>
                <div class="flex items-center justify-center md:justify-start gap-1 text-yellow-400 my-3 text-lg">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <div class="text-sm text-gray-500 font-medium">Based on 124 verified reviews</div>
                <div class="mt-3 inline-flex items-center gap-1.5 text-xs text-green-700 bg-green-50 px-2.5 py-1 rounded-full font-semibold">
                    <i class="fas fa-smile"></i> 96% positive feedback
                </div>
            </div>

            <!-- Breakdown Bars -->
            <div class="md:col-span-2 space-y-2.5">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-600 w-10">5 ★</span>
                    <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-brand rounded-full" style="width: 75%"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 w-12 text-right">93 (75%)</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-600 w-10">4 ★</span>
                    <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-brand rounded-full" style="width: 18%"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 w-12 text-right">22 (18%)</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-600 w-10">3 ★</span>
                    <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-400 rounded-full" style="width: 5%"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 w-12 text-right">6 (5%)</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-600 w-10">2 ★</span>
                    <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-orange-400 rounded-full" style="width: 1%"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 w-12 text-right">2 (1%)</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-600 w-10">1 ★</span>
                    <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-red-400 rounded-full" style="width: 1%"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 w-12 text-right">1 (1%)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
            <input id="reviewSearch" onkeyup="filterReviews()" type="text" placeholder="Search within reviews..." class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 shadow-xs">
        </div>
        <select id="reviewRatingFilter" onchange="filterReviews()" class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 shadow-xs">
            <option value="">All Ratings</option>
            <option value="5">5 Stars only</option>
            <option value="4">4 Stars only</option>
            <option value="3">3 Stars & Below</option>
        </select>
        <select id="reviewPgFilter" onchange="filterReviews()" class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 shadow-xs">
            <option value="">All Properties</option>
            <option value="Sunrise Premium PG">Sunrise Premium PG</option>
            <option value="Aura Women's Stay">Aura Women's Stay</option>
            <option value="Urban Nest Co-living">Urban Nest Co-living</option>
        </select>
    </div>

    <!-- Reviews List -->
    <div class="space-y-4" id="reviewList">
        <!-- Review 1 -->
        <div class="review-item bg-white rounded-3xl p-6 border border-gray-100 shadow-sm" data-rating="5" data-pg="Sunrise Premium PG">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">RS</div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                        <div>
                            <div class="font-bold text-gray-900 text-base tenant-name">Rahul Sharma</div>
                            <div class="text-xs text-gray-500 pg-name">Sunrise Premium PG • 2 weeks ago</div>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <span class="text-gray-700 ml-1 font-bold text-xs">5.0</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed mb-3 review-text">"Excellent PG! The food is hygienic and tasty, rooms are clean, and the caretakers are very responsive. High speed WiFi works without interruption which is essential for my remote job. Highly recommended for IT professionals."</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><i class="fas fa-thumbs-up text-brand"></i> 12 tenants found this helpful</span>
                        <button onclick="openReplyModal('Rahul Sharma')" class="text-brand font-bold hover:underline">Reply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review 2 (With Existing Reply) -->
        <div class="review-item bg-white rounded-3xl p-6 border border-gray-100 shadow-sm" data-rating="4" data-pg="Aura Women's Stay">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-pink-500 text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">PP</div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                        <div>
                            <div class="font-bold text-gray-900 text-base tenant-name">Priya Patel</div>
                            <div class="text-xs text-gray-500 pg-name">Aura Women's Stay • 1 month ago</div>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                            <span class="text-gray-700 ml-1 font-bold text-xs">4.0</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed mb-3 review-text">"Very clean and well-maintained property. Good location near Indiranagar metro station. Biometric security and female warden make it extremely safe for working women. Only suggestion is to add more dinner variety."</p>
                    
                    <!-- Reply Box -->
                    <div class="bg-brand-50/70 rounded-2xl p-4 border-l-4 border-brand space-y-1">
                        <div class="text-xs font-bold text-brand flex items-center gap-1.5">
                            <i class="fas fa-reply"></i> Your Host Reply
                        </div>
                        <p class="text-xs text-gray-700 leading-relaxed">"Thank you Priya for your lovely feedback! We have recently updated our weekday dinner menu with South and North Indian cuisines. Glad you feel secure here! 🙏"</p>
                    </div>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mt-3">
                        <span class="flex items-center gap-1.5"><i class="fas fa-thumbs-up text-brand"></i> 8 found helpful</span>
                        <button onclick="openReplyModal('Priya Patel')" class="text-brand font-bold hover:underline">Edit Reply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review 3 -->
        <div class="review-item bg-white rounded-3xl p-6 border border-gray-100 shadow-sm" data-rating="4" data-pg="Urban Nest Co-living">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">AK</div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                        <div>
                            <div class="font-bold text-gray-900 text-base tenant-name">Amit Kumar</div>
                            <div class="text-xs text-gray-500 pg-name">Urban Nest Co-living • 2 months ago</div>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            <span class="text-gray-700 ml-1 font-bold text-xs">4.5</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed mb-3 review-text">"Great co-living vibe! Made great friends in the common room and gym. Community events and game nights are fun. Overall fantastic value for money in HSR Layout."</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><i class="fas fa-thumbs-up text-brand"></i> 15 found helpful</span>
                        <button onclick="openReplyModal('Amit Kumar')" class="text-brand font-bold hover:underline">Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900">Reply to Review</h3>
            <button onclick="closeModal('replyModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect"><i class="fas fa-times text-gray-500 text-xs"></i></button>
        </div>
        <div>
            <div class="text-xs text-gray-500 mb-1">Replying publicly to <span id="replyUser" class="font-bold text-gray-900">Rahul Sharma</span>:</div>
            <textarea id="replyText" rows="4" placeholder="Write a professional and courteous reply..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50"></textarea>
        </div>
        <div class="flex gap-2">
            <button onclick="closeModal('replyModal')" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-xl tap-effect">Cancel</button>
            <button onclick="alert('Your reply has been published!'); closeModal('replyModal');" class="flex-1 bg-brand text-white font-bold py-2.5 rounded-xl tap-effect shadow-md">Post Reply</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openReplyModal(name) {
        document.getElementById('replyUser').textContent = name;
        document.getElementById('replyText').value = '';
        openModal('replyModal');
    }

    function filterReviews() {
        const search = document.getElementById('reviewSearch').value.toLowerCase();
        const rating = document.getElementById('reviewRatingFilter').value;
        const pg = document.getElementById('reviewPgFilter').value;

        document.querySelectorAll('.review-item').forEach(el => {
            const text = el.querySelector('.review-text').textContent.toLowerCase();
            const tenant = el.querySelector('.tenant-name').textContent.toLowerCase();
            const elRating = el.getAttribute('data-rating');
            const elPg = el.getAttribute('data-pg');

            const matchSearch = text.includes(search) || tenant.includes(search);
            const matchRating = !rating || (rating === '3' ? parseInt(elRating) <= 3 : elRating === rating);
            const matchPg = !pg || elPg === pg;

            if (matchSearch && matchRating && matchPg) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endpush
