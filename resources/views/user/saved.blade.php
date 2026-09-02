@extends('user.layouts.app')

@section('title', 'Saved Properties - StayNest')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="min-h-screen bg-gray-50/70 pb-24 md:pb-12 pt-6 md:pt-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header Title Bar -->
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 flex items-center gap-2.5">
                    <span>Saved Wishlist</span>
                    <span id="savedCountBadge" class="text-xs bg-red-100 text-red-600 font-extrabold px-2.5 py-0.5 rounded-full">
                        0 Stays
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Properties you've bookmarked for quick booking & visits</p>
            </div>
            <a href="{{ route('user.search') }}" class="hidden sm:inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white text-xs font-bold px-4 py-2.5 rounded-xl transition tap-effect shadow-xs">
                <i class="fas fa-search"></i> Explore More PGs
            </a>
        </div>

        <!-- ================= 1. GUEST LOCKED STATE (IF NOT LOGGED IN) ================= -->
        <div id="savedGuestLockedState" class="hidden">
            <div class="bg-white rounded-3xl p-8 sm:p-12 border border-gray-100 shadow-sm text-center max-w-lg mx-auto my-8">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-sm">
                    <i class="fas fa-heart-circle-bolt"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 mb-2">Log in to view Saved Stays</h2>
                <p class="text-xs sm:text-sm text-gray-500 mb-6 leading-relaxed">
                    Your saved properties are safely stored in your account. Log in or create a free account to access your shortlist anywhere.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('user.login') }}" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 px-6 rounded-2xl text-sm shadow-lg shadow-brand/30 hover:shadow-brand/40 transition tap-effect">
                        <i class="fas fa-arrow-right-to-bracket"></i>
                        <span>Log In / Sign Up</span>
                    </a>
                    <a href="{{ route('user.home') }}" class="w-full inline-flex items-center justify-center py-2.5 text-xs text-gray-500 hover:text-gray-800 font-semibold transition">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= 2. EMPTY WISHLIST STATE (LOGGED IN BUT NO ITEMS) ================= -->
        <div id="savedEmptyState" class="hidden">
            <div class="bg-white rounded-3xl p-8 sm:p-12 border border-gray-100 shadow-sm text-center max-w-lg mx-auto my-8">
                <div class="w-20 h-20 bg-gray-100 text-gray-400 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-5">
                    <i class="far fa-heart"></i>
                </div>
                <h2 class="text-xl font-black text-gray-900 mb-2">No Saved Properties Yet</h2>
                <p class="text-xs sm:text-sm text-gray-500 mb-6 leading-relaxed">
                    You haven't added any PGs to your wishlist. Tap the heart icon on any property card to save it here!
                </p>
                <a href="{{ route('user.search') }}" class="inline-flex items-center gap-2 bg-brand hover:bg-brand-dark text-white font-bold py-3 px-6 rounded-2xl text-xs transition tap-effect shadow-md shadow-brand/20">
                    <i class="fas fa-compass"></i>
                    <span>Browse Verified Stays</span>
                </a>
            </div>
        </div>

        <!-- ================= SKELETON PLACEHOLDER GRID ================= -->
        <div id="savedSkeletonGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
            @for ($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-3xl p-3 sm:p-4 border border-gray-100 shadow-xs space-y-3">
                <div class="h-44 sm:h-48 w-full bg-gray-200 rounded-2xl skeleton-shimmer"></div>
                <div class="space-y-2">
                    <div class="h-3 w-1/3 bg-gray-200 rounded skeleton-shimmer"></div>
                    <div class="h-5 w-3/4 bg-gray-200 rounded-lg skeleton-shimmer"></div>
                    <div class="h-3 w-1/2 bg-gray-100 rounded skeleton-shimmer"></div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="h-6 w-20 bg-gray-200 rounded-md skeleton-shimmer"></div>
                    <div class="h-8 w-24 bg-gray-200 rounded-xl skeleton-shimmer"></div>
                </div>
            </div>
            @endfor
        </div>

        <!-- ================= 3. POPULATED SAVED PROPERTIES GRID ================= -->
        <div id="savedListContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
            <!-- Dynamically injected via JavaScript -->
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(renderSavedPage, 60);
    });

    function renderSavedPage() {
        const skeletonBox = document.getElementById('savedSkeletonGrid');
        const lockedBox = document.getElementById('savedGuestLockedState');
        const emptyBox = document.getElementById('savedEmptyState');
        const listContainer = document.getElementById('savedListContainer');
        const countBadge = document.getElementById('savedCountBadge');

        if (skeletonBox) skeletonBox.classList.add('hidden');

        // Check if user is logged in
        if (!isUserLoggedIn()) {
            lockedBox.classList.remove('hidden');
            emptyBox.classList.add('hidden');
            listContainer.classList.add('hidden');
            countBadge.innerText = 'Locked';
            return;
        }

        lockedBox.classList.add('hidden');
        let savedList = getSavedProperties();

        // Fallback default sample wishlist if freshly logged in
        if (!savedList || savedList.length === 0) {
            savedList = [
                {
                    id: 'pg_sunrise_1',
                    title: 'Sunrise Premium PG',
                    type: 'BOYS',
                    typeColor: 'bg-blue-50 text-blue-600',
                    location: 'Sector 62, Noida',
                    dist: '0.4 km away',
                    price: '₹8,500',
                    rating: '4.8',
                    reviews: '120',
                    badge: 'Verified',
                    image: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
                },
                {
                    id: 'pg_aura_2',
                    title: "Aura Women's Stay",
                    type: 'GIRLS',
                    typeColor: 'bg-pink-50 text-pink-600',
                    location: 'Indiranagar, Bangalore',
                    dist: '0.5 km away',
                    price: '₹9,999',
                    rating: '4.9',
                    reviews: '98',
                    badge: 'Verified',
                    image: 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
                }
            ];
            savePropertiesToStorage(savedList);
        }

        countBadge.innerText = `${savedList.length} Stays`;

        if (savedList.length === 0) {
            emptyBox.classList.remove('hidden');
            listContainer.classList.add('hidden');
            return;
        }

        emptyBox.classList.add('hidden');
        listContainer.classList.remove('hidden');
        listContainer.innerHTML = '';

        savedList.forEach(pg => {
            const card = document.createElement('div');
            card.className = 'saved-item bg-white rounded-3xl overflow-hidden shadow-xs hover:shadow-lg border border-gray-100 flex flex-col transition-all duration-300 transform group relative';
            card.id = `savedCard_${pg.id}`;

            const slugVal = pg.slug || (pg.title ? pg.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '') : pg.id);
            const detailUrl = `/detail/${slugVal}`;
            const isSale = (pg.type && pg.type.toLowerCase().includes('sale')) || (pg.price && (pg.price.includes('Cr') || pg.price.includes('Lac') || pg.price.includes('Lakh')));
            
            card.innerHTML = `
                <div class="relative h-44 overflow-hidden">
                    <img src="${pg.image || 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=600&q=80'}" alt="${pg.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-2.5 left-2.5 ${isSale ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black uppercase' : 'bg-brand text-white font-bold'} text-[10px] px-2.5 py-0.5 rounded-full shadow-sm">
                        ${isSale ? '<i class="fas fa-tag mr-1 text-[9px]"></i> For Sale' : (pg.type || 'StayNest')}
                    </span>
                    <button type="button" onclick="removeSavedItem('${pg.id}', this)" class="absolute top-2.5 right-2.5 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 hover:text-gray-400 shadow-sm transition tap-effect" title="Remove from wishlist">
                        <i class="fas fa-heart text-xs"></i>
                    </button>
                    <div class="absolute bottom-2 left-2.5 bg-black/75 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                        <i class="fas fa-star text-yellow-400 text-[9px]"></i> ${pg.rating || '4.8'}
                    </div>
                </div>

                <div class="p-4 flex flex-col flex-1 justify-between">
                    <div>
                        <div class="flex justify-between items-center gap-1 mb-1">
                            <h3 class="font-bold text-base text-gray-900 truncate">${pg.title}</h3>
                            <span class="bg-emerald-50 text-emerald-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded flex items-center gap-1 flex-shrink-0">
                                <i class="fas fa-check-circle text-[8px]"></i> Verified
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1 mb-3 truncate">
                            <i class="fas fa-map-marker-alt text-brand text-[11px]"></i> ${pg.location || 'Noida, India'}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                        <div>
                            <span class="text-[9px] text-gray-400 block font-semibold leading-none">${isSale ? 'Price' : 'Starting from'}</span>
                            <span class="text-base font-black text-gray-900">${pg.price || '₹8,500'}${isSale ? '' : '<span class="text-[10px] font-normal text-gray-500">/mo</span>'}</span>
                        </div>
                        <a href="${detailUrl}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-md hover:shadow-brand/20 text-white text-xs font-bold px-4 py-2 rounded-xl transition tap-effect no-underline">
                            View Details
                        </a>
                    </div>
                </div>
            `;
            listContainer.appendChild(card);
        });
    }

    function removeSavedItem(id, btn) {
        let savedList = getSavedProperties();
        const card = document.getElementById(`savedCard_${id}`) || btn.closest('.saved-item');
        
        savedList = savedList.filter(item => item.id !== id);
        savePropertiesToStorage(savedList);

        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.85)';
            setTimeout(() => {
                card.remove();
                const countBadge = document.getElementById('savedCountBadge');
                countBadge.innerText = `${savedList.length} Stays`;

                if (savedList.length === 0) {
                    document.getElementById('savedEmptyState').classList.remove('hidden');
                    document.getElementById('savedListContainer').classList.add('hidden');
                }
            }, 250);
        }

        showWishlistToast('Removed from Wishlist', 'Property removed from your saved stays.', false);
    }
</script>
@endpush
