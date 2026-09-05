<!-- Global Wishlist Login Prompt Modal -->
<div id="wishlistAuthPromptModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <div onclick="closeWishlistPrompt()" class="absolute inset-0 bg-black/60 backdrop-blur-xs"></div>
    
    <div class="absolute bottom-0 md:bottom-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 w-full sm:w-[420px] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl p-6 z-10 text-center animate-slide-up">
        <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 border border-red-100 flex items-center justify-center text-2xl mx-auto mb-4 shadow-sm">
            <i class="fas fa-heart animate-bounce"></i>
        </div>
        
        <h3 class="text-lg font-black text-gray-900 mb-1.5">Login to Save Stays</h3>
        <p class="text-xs text-gray-500 leading-relaxed max-w-xs mx-auto mb-6">
            Please sign in to save your favorite PGs and access your personal wishlist across all devices anytime.
        </p>

        <div class="space-y-2.5">
            <a href="{{ route('user.login') }}" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white font-bold py-3.5 px-4 rounded-2xl text-xs flex items-center justify-center gap-2 transition tap-effect">
                <i class="fas fa-arrow-right-to-bracket"></i>
                <span>Log In / Create Account</span>
            </a>
            <button type="button" onclick="closeWishlistPrompt()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-2xl text-xs transition tap-effect">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Global Wishlist Toast Notification -->
<div id="wishlistGlobalToast" class="fixed bottom-20 md:bottom-8 right-4 md:right-8 z-50 hidden transition-all duration-300 transform translate-y-2">
    <div class="bg-gray-900/95 backdrop-blur-md text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-white/20">
        <div id="wishlistToastIcon" class="w-7 h-7 rounded-full bg-red-500 flex items-center justify-center text-white text-xs shadow-md">
            <i class="fas fa-heart"></i>
        </div>
        <div>
            <p id="wishlistToastTitle" class="text-xs font-bold leading-tight">Wishlist Updated</p>
            <p id="wishlistToastMsg" class="text-[11px] text-gray-300">Property added to your saved collection.</p>
        </div>
    </div>
</div>

<script>
    // Global Auth Status Detection
    window.isUserLoggedIn = function() {
        @auth
            return true;
        @else
            return false;
        @endauth
    };

    window.getUserWishlistStorageKey = function() {
        let userKey = 'guest';
        @auth
            userKey = 'user_{{ Auth::id() }}';
        @else
            const userStr = localStorage.getItem('staynest_user');
            if (userStr) {
                try {
                    const u = JSON.parse(userStr);
                    userKey = u.id ? ('user_' + u.id) : (u.email || 'guest');
                } catch(e) {}
            }
        @endauth
        return 'staynest_saved_stays_' + userKey;
    };

    window.getSavedProperties = function() {
        const key = getUserWishlistStorageKey();
        try {
            let list = JSON.parse(localStorage.getItem(key)) || [];
            if (Array.isArray(list)) {
                const cleaned = list.filter(item => {
                    if (!item) return false;
                    const id = String(item.id || '');
                    if (id.startsWith('pg_sunrise') || id.startsWith('pg_aura') || item.title === 'Sunrise Premium PG' || item.title === "Aura Women's Stay") {
                        return false;
                    }
                    return true;
                });
                if (cleaned.length !== list.length) {
                    localStorage.setItem(key, JSON.stringify(cleaned));
                    list = cleaned;
                }
                return list;
            }
            return [];
        } catch(e) {
            return [];
        }
    };

    window.savePropertiesToStorage = function(list) {
        const key = getUserWishlistStorageKey();
        localStorage.setItem(key, JSON.stringify(list || []));
        updateWishlistBadgeCounts();
    };

    window.openWishlistPrompt = function() {
        const modal = document.getElementById('wishlistAuthPromptModal');
        if (modal) modal.classList.remove('hidden');
    };

    window.closeWishlistPrompt = function() {
        const modal = document.getElementById('wishlistAuthPromptModal');
        if (modal) modal.classList.add('hidden');
    };

    window.showWishlistToast = function(title, msg, isAdded) {
        const toast = document.getElementById('wishlistGlobalToast');
        const iconBox = document.getElementById('wishlistToastIcon');
        const titleEl = document.getElementById('wishlistToastTitle');
        const msgEl = document.getElementById('wishlistToastMsg');
        
        if (toast && titleEl && msgEl) {
            titleEl.innerText = title;
            msgEl.innerText = msg;
            if (isAdded) {
                iconBox.className = 'w-7 h-7 rounded-full bg-red-500 flex items-center justify-center text-white text-xs shadow-md';
                iconBox.innerHTML = '<i class="fas fa-heart"></i>';
            } else {
                iconBox.className = 'w-7 h-7 rounded-full bg-gray-600 flex items-center justify-center text-white text-xs shadow-md';
                iconBox.innerHTML = '<i class="fas fa-trash-can"></i>';
            }
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    };

    window.updateWishlistBadgeCounts = function() {
        const list = getSavedProperties();
        const count = list.length;
        
        // Update header & mobile badges
        document.querySelectorAll('.saved-wishlist-count-badge').forEach(el => {
            el.innerText = count;
            if (count > 0) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    };

    // Universal Heart Click Handler across all property cards
    window.heartToggle = function(btn, propertyData) {
        // 1. Strict Auth Check
        if (!isUserLoggedIn()) {
            openWishlistPrompt();
            return false;
        }

        // 2. Extract or use property details
        let prop = propertyData;
        if (!prop || !prop.id) {
            const card = btn.closest('.pg-card') || btn.closest('[data-property-id]') || btn.closest('.property-card');
            const id = card?.getAttribute('data-property-id') || btn.getAttribute('data-id') || (btn.title || btn.id || 'pg_' + Date.now());
            const title = card?.querySelector('.font-bold, .prop-title')?.innerText || 'Verified Premium Stay';
            const price = card?.querySelector('.text-base, .text-xs, .text-xl, .text-lg')?.innerText || '₹8,500';
            const img = card?.querySelector('img')?.src || 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600';
            const location = card?.querySelector('.fa-map-marker-alt, .prop-loc')?.parentNode?.innerText?.trim() || 'Sector 62, Noida';
            const type = card?.querySelector('[class*="bg-blue-50"], [class*="bg-pink-50"], [class*="bg-purple-50"]')?.innerText?.trim() || 'BOYS';
            const href = card?.getAttribute('href') || card?.querySelector('a[href*="/detail/"]')?.getAttribute('href');
            let slug = '';
            if (href && href.includes('/detail/')) {
                slug = href.split('/detail/')[1].split('?')[0];
            } else if (title) {
                slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            } else {
                slug = id;
            }

            prop = {
                id: id,
                slug: slug,
                title: title,
                price: price,
                image: img,
                location: location,
                type: type,
                rating: '4.8'
            };
        } else if (!prop.slug) {
            if (prop.title) {
                prop.slug = prop.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            } else {
                prop.slug = prop.id;
            }
        }

        let savedList = getSavedProperties();
        const existingIndex = savedList.findIndex(item => item.id === prop.id || item.title === prop.title);
        const icon = btn.querySelector('i');

        if (existingIndex > -1) {
            // Remove from saved
            savedList.splice(existingIndex, 1);
            savePropertiesToStorage(savedList);

            if (icon) {
                icon.className = 'far fa-heart text-gray-400 text-xs';
            }
            btn.classList.remove('text-red-500');
            showWishlistToast('Removed from Saved', `${prop.title} was removed from your wishlist.`, false);
        } else {
            // Add to saved
            savedList.push(prop);
            savePropertiesToStorage(savedList);

            if (icon) {
                icon.className = 'fas fa-heart text-red-500 text-xs animate-pulse';
            }
            btn.classList.add('text-red-500');
            showWishlistToast('Added to Saved Stays ❤️', `${prop.title} added to your wishlist!`, true);
        }

        // Sync with API if user is authenticated with Sanctum token
        const token = localStorage.getItem('staynest_token');
        if (token && prop.id) {
            try {
                fetch('/api/v1/user/saved/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ property_id: prop.id, property_data: prop })
                }).catch(()=>{});
            } catch(e){}
        }

        return true;
    };

    // Auto-sync heart icons on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (isUserLoggedIn()) {
            const savedList = getSavedProperties();
            document.querySelectorAll('button[data-prop-id], button[onclick*="heartToggle"]').forEach(btn => {
                const card = btn.closest('.pg-card');
                const title = card?.querySelector('.font-bold')?.innerText;
                const propId = btn.getAttribute('data-prop-id') || title;
                
                if (propId && savedList.some(item => item.id === propId || (title && item.title === title))) {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.className = 'fas fa-heart text-red-500 text-xs';
                    }
                    btn.classList.add('text-red-500');
                }
            });
            updateWishlistBadgeCounts();
        }
    });
</script>
