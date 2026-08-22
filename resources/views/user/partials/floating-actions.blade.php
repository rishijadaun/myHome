<!-- ===================== FIXED FLOATING ACTIONS (MAP VIEW & AI CHATBOT) ===================== -->
<div class="fixed bottom-20 md:bottom-8 right-4 md:right-8 z-40 flex flex-col items-end gap-3 pointer-events-none">

    <!-- 1. Map View Floating Pill Button (Desktop Only, hidden on location page) -->
    @if(!request()->routeIs('user.location'))
    <a href="{{ route('user.location') }}" 
       class="pointer-events-auto hidden md:flex items-center gap-2 bg-gray-900/90 hover:bg-gray-900 text-white px-4 py-2.5 rounded-full shadow-2xl backdrop-blur-md border border-white/20 tap-effect transition-all duration-300 hover:scale-105 hover:shadow-brand/20"
       title="Open Interactive Map">
        <span class="w-7 h-7 rounded-full bg-brand flex items-center justify-center text-white text-xs shadow-sm">
            <i class="fas fa-map-location-dot"></i>
        </span>
        <span class="text-xs font-bold tracking-wide pr-1">Map View</span>
    </a>
    @endif

    <!-- 2. AI Chatbot Floating Button with Glowing Ring -->
    <button type="button" 
            onclick="toggleAiChat()" 
            class="pointer-events-auto relative group flex items-center gap-2.5 bg-gradient-to-r from-purple-600 via-indigo-600 to-brand hover:from-purple-700 hover:to-teal-700 text-white px-4 py-2.5 rounded-full shadow-2xl border border-white/30 tap-effect transition-all duration-300 hover:scale-105 hover:shadow-purple-500/40"
            title="Ask StayNest Smart AI">
        <!-- Glowing Pulse Ping -->
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-yellow-400"></span>
        </span>
        <span class="w-7 h-7 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-xs">
            <i class="fas fa-wand-magic-sparkles text-yellow-300 animate-pulse"></i>
        </span>
        <span class="text-xs font-black tracking-wide pr-1">AI Assistant</span>
    </button>

</div>

<!-- ===================== AI CHATBOT ADVANCED KNOWLEDGE & EXPANDED MODAL ===================== -->
<div id="aiChatModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <!-- Backdrop overlay -->
    <div onclick="toggleAiChat()" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Chat Container -->
    <div class="absolute bottom-0 md:bottom-6 right-0 md:right-6 w-full sm:w-[520px] md:w-[560px] lg:w-[620px] max-h-[94vh] md:max-h-[740px] h-[700px] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden z-10 animate-slide-up">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-900 via-purple-950 to-brand-dark p-4 text-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-500 via-indigo-500 to-brand flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-robot text-base"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-gray-900 rounded-full animate-ping"></span>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-gray-900 rounded-full"></span>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <h3 class="font-bold text-sm sm:text-base leading-tight">StayNest AI Assistant</h3>
                        <!-- <span class="bg-yellow-400/25 text-yellow-300 text-[9px] font-extrabold px-1.5 py-0.2 rounded border border-yellow-400/30">MATCH ENGINE v5</span> -->
                    </div>
                    <p class="text-[11px] text-purple-200/80">Conversational Property Search &amp; Match Scoring</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button type="button" onclick="clearAiChat()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/70 hover:text-white transition" title="Clear Conversation">
                    <i class="fas fa-rotate-right text-xs"></i>
                </button>
                <button type="button" onclick="toggleAiChat()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/70 hover:text-white transition" title="Close">
                    <i class="fas fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Quick Category Selector Bar -->
        <div class="bg-gray-100/90 px-3 py-2 border-b border-gray-200/80 flex items-center gap-1.5 overflow-x-auto no-scrollbar flex-shrink-0">
            <button type="button" onclick="askAiPrompt('Noida sector 62 me boys PG 8k ke andar AC food ke saath')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                👨 Boys Noida Sec 62 &lt; 8k
            </button>
            <button type="button" onclick="askAiPrompt('Girls PG with AC in Bangalore Koramangala under 10k')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                👩 Girls Bangalore AC
            </button>
            <button type="button" onclick="askAiPrompt('Co-Ed unisex coliving stays in Delhi with Gym & WiFi')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                👥 Co-Living Delhi
            </button>
            <button type="button" onclick="askAiPrompt('Single room chahiye attached washroom ke saath')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                🚪 Single Room Attached Bath
            </button>
            <button type="button" onclick="askAiPrompt('Orai ya Jhansi me saste room with wifi')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                📍 Orai &amp; Jhansi Stays
            </button>
            <button type="button" onclick="askAiPrompt('How does Zero Brokerage work on StayNest?')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                🛡️ Zero Brokerage
            </button>
        </div>

        <!-- Chat Messages Scroll Area -->
        <div id="aiChatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/70">
            
            <!-- Bot Initial Greeting -->
            <div class="flex items-start gap-2.5 max-w-[95%]">
                <div class="w-7 h-7 rounded-xl bg-brand flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-gray-100 shadow-xs text-xs text-gray-800 space-y-2.5">
                    <p class="font-bold text-gray-900 text-sm">Hello! I am your StayNest AI Concierge 👋</p>
                    <p class="leading-relaxed text-gray-700">Tell me in English, Hindi, or Hinglish what you're looking for! For example: <em>"Noida sector 62 me boys PG 8k ke andar AC food ke saath"</em></p>
                    <div class="pt-1 flex flex-wrap gap-1.5 text-[10px]">
                        <span class="bg-pink-50 text-pink-700 font-bold px-2.5 py-1 rounded-lg border border-pink-200/60">👩 Girls PGs</span>
                        <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200/60">👨 Boys PGs</span>
                        <span class="bg-purple-50 text-purple-700 font-bold px-2.5 py-1 rounded-lg border border-purple-200/60">👥 Co-Ed / Unisex</span>
                        <span class="bg-emerald-50 text-emerald-700 font-bold px-2.5 py-1 rounded-lg border border-emerald-200/60">🛡️ 100% Zero Brokerage</span>
                    </div>
                </div>
            </div>

            <!-- Popular Prompt Pills -->
            <div id="aiQuickChips" class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-xs">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                    <i class="fas fa-bolt text-yellow-500"></i> Try Asking Natural Queries
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                    <button type="button" onclick="askAiPrompt('Noida sector 62 me boys PG 8k ke andar AC food ke saath')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Boys PG Noida Sec 62 &lt; ₹8K
                    </button>
                    <button type="button" onclick="askAiPrompt('girls pg in Bangalore with AC under 10k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Girls PG Bangalore with AC
                    </button>
                    <button type="button" onclick="askAiPrompt('sec 62 me ladko ke liye 8 hazar ke andar AC wala PG chahiye jisme khana bhi mile')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        🏢 Ladko ka PG with AC &amp; Food
                    </button>
                    <button type="button" onclick="askAiPrompt('single room chahiye attached washroom ke saath')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        🚪 Single Room + Attached Bath
                    </button>
                </div>
            </div>

        </div>

        <!-- Chat Input Form -->
        <div class="p-3.5 bg-white border-t border-gray-100 flex-shrink-0">
            <form onsubmit="handleAiSubmit(event)" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input id="aiChatInput" 
                           type="text" 
                           placeholder="Ask: 'Noida Sec 62 boys PG under 8k AC food' or 'Girls AC Bangalore'..." 
                           class="w-full bg-gray-100 focus:bg-white text-xs sm:text-sm text-gray-900 rounded-2xl pl-4 pr-9 py-3.5 border border-transparent focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition"
                           autocomplete="off">
                    <span class="absolute right-3.5 top-3.5 text-gray-400 text-sm">
                        <i class="fas fa-sparkles text-brand"></i>
                    </span>
                </div>
                <button type="submit" class="w-11 h-11 rounded-2xl bg-gradient-to-r from-brand to-teal-600 hover:from-brand-dark hover:to-teal-700 text-white flex items-center justify-center shadow-md shadow-brand/30 transition tap-effect flex-shrink-0">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
            <div class="flex items-center justify-between text-[10px] text-gray-400 mt-2 px-1">
                <span><i class="fas fa-shield-halved text-brand"></i> 100% Zero Brokerage Real Listings</span>
                <span>Powered by StayNest AI Intelligence</span>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes slideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up {
        animation: slideUp 0.28s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    #aiChatMessages {
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
    #aiChatMessages::-webkit-scrollbar {
        width: 5px;
    }
    #aiChatMessages::-webkit-scrollbar-track {
        background: transparent;
    }
    #aiChatMessages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    #aiChatMessages::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .ai-chat-slider {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 6px;
        padding-top: 4px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }
    .ai-chat-slider::-webkit-scrollbar {
        height: 4px;
    }
    .ai-chat-slider::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .ai-slider-card {
        flex: 0 0 250px;
        scroll-snap-align: start;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: transform 0.15s, border-color 0.15s;
    }
    .ai-slider-card:hover {
        transform: translateY(-2px);
        border-color: #4bb59d;
    }
</style>

<script>
let activeAiSessionQuery = '';
let currentAiFilters = {};

function toggleAiChat() {
    const modal = document.getElementById('aiChatModal');
    if (!modal) return;
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('aiChatInput')?.focus(), 150);
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function clearAiChat() {
    activeAiSessionQuery = '';
    currentAiFilters = {};
    const chat = document.getElementById('aiChatMessages');
    if (!chat) return;
    chat.innerHTML = `
        <div class="flex items-start gap-2.5 max-w-[95%]">
            <div class="w-7 h-7 rounded-xl bg-brand flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
                <i class="fas fa-robot"></i>
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-gray-100 shadow-xs text-xs text-gray-800 space-y-2.5">
                <p class="font-bold text-gray-900 text-sm">Hello! I am your StayNest AI Concierge 👋</p>
                <p class="leading-relaxed text-gray-700">Tell me in English, Hindi, or Hinglish what you're looking for! For example: <em>"Noida sector 62 me boys PG 8k ke andar AC food ke saath"</em></p>
                <div class="pt-1 flex flex-wrap gap-1.5 text-[10px]">
                    <span class="bg-pink-50 text-pink-700 font-bold px-2.5 py-1 rounded-lg border border-pink-200/60">👩 Girls PGs</span>
                    <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200/60">👨 Boys PGs</span>
                    <span class="bg-purple-50 text-purple-700 font-bold px-2.5 py-1 rounded-lg border border-purple-200/60">👥 Co-Ed / Unisex</span>
                    <span class="bg-emerald-50 text-emerald-700 font-bold px-2.5 py-1 rounded-lg border border-emerald-200/60">🛡️ 100% Zero Brokerage</span>
                </div>
            </div>
        </div>
        <div id="aiQuickChips" class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-xs">
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                <i class="fas fa-bolt text-yellow-500"></i> Try Asking Natural Queries
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                <button type="button" onclick="askAiPrompt('Noida sector 62 me boys PG 8k ke andar AC food ke saath')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Boys PG Noida Sec 62 &lt; ₹8K
                </button>
                <button type="button" onclick="askAiPrompt('girls pg in Bangalore with AC under 10k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Girls PG Bangalore with AC
                </button>
                <button type="button" onclick="askAiPrompt('sec 62 me ladko ke liye 8 hazar ke andar AC wala PG chahiye jisme khana bhi mile')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    🏢 Ladko ka PG with AC &amp; Food
                </button>
                <button type="button" onclick="askAiPrompt('single room chahiye attached washroom ke saath')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    🚪 Single Room + Attached Bath
                </button>
            </div>
        </div>
    `;
}

function askAiPrompt(prompt) {
    const input = document.getElementById('aiChatInput');
    if (input) {
        input.value = prompt;
        handleAiSubmit(new Event('submit'));
    }
}

async function handleAiSubmit(e, filterOverrides = null) {
    if (e) e.preventDefault();
    const input = document.getElementById('aiChatInput');
    if (!input) return;
    
    let query = input.value.trim();
    if (!query && !filterOverrides) {
        query = activeAiSessionQuery;
    }
    if (!query) return;

    activeAiSessionQuery = query;
    if (filterOverrides) {
        currentAiFilters = { ...currentAiFilters, ...filterOverrides };
    }

    const chat = document.getElementById('aiChatMessages');
    if (!chat) return;

    // 1. Append user message bubble if new typed submission
    if (!filterOverrides) {
        const userMsg = document.createElement('div');
        userMsg.className = 'flex justify-end';
        userMsg.innerHTML = `
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-3.5 rounded-2xl rounded-tr-sm max-w-[85%] shadow-sm text-xs sm:text-sm font-medium leading-relaxed">
                ${query}
            </div>
        `;
        chat.appendChild(userMsg);
        input.value = '';
        chat.scrollTop = chat.scrollHeight;
    }

    // 2. Typing indicator
    const typingId = 'typing_' + Date.now();
    const typing = document.createElement('div');
    typing.id = typingId;
    typing.className = 'flex items-start gap-2.5 max-w-[88%]';
    typing.innerHTML = `
        <div class="w-7 h-7 rounded-xl bg-brand flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
            <i class="fas fa-robot"></i>
        </div>
        <div class="bg-white px-4 py-3 rounded-2xl rounded-tl-sm border border-gray-100 shadow-sm flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 bg-purple-600 rounded-full animate-bounce"></span>
            <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-1.5 h-1.5 bg-brand rounded-full animate-bounce [animation-delay:0.4s]"></span>
        </div>
    `;
    chat.appendChild(typing);
    chat.scrollTop = chat.scrollHeight;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch('{{ url("/ai/search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                message: query,
                filters: currentAiFilters
            })
        });

        const json = await res.json();
        const el = document.getElementById(typingId);
        if (el) el.remove();

        if (json.success && json.data) {
            renderAiResponse(json.data);
        }
    } catch (err) {
        console.error("AI Search Error:", err);
        const el = document.getElementById(typingId);
        if (el) el.remove();
    }
}

function removeFilterChip(chipKey, amenityVal) {
    if (chipKey.startsWith('amenity_') || amenityVal) {
        const amName = amenityVal || chipKey.replace('amenity_', '');
        if (currentAiFilters.amenities) {
            currentAiFilters.amenities = currentAiFilters.amenities.filter(a => a !== amName);
        }
    } else {
        if (chipKey === 'gender') currentAiFilters.gender = null;
        if (chipKey === 'location' || chipKey === 'city') { currentAiFilters.city = null; currentAiFilters.area = null; }
        if (chipKey === 'area') currentAiFilters.area = null;
        if (chipKey === 'budget' || chipKey === 'max_budget') currentAiFilters.max_budget = null;
        if (chipKey === 'min_budget') currentAiFilters.min_budget = null;
        if (chipKey === 'budget_range') { currentAiFilters.min_budget = null; currentAiFilters.max_budget = null; }
    }

    handleAiSubmit(null, currentAiFilters);
}

function renderAiResponse(data) {
    const chat = document.getElementById('aiChatMessages');
    if (!chat) return;

    // Build Removable Filter Chips
    let filtersHtml = '';
    if (data.active_filters && data.active_filters.length) {
        filtersHtml = `
            <div class="flex flex-wrap gap-1.5 mb-2.5">
                ${data.active_filters.map(f => `
                    <span class="inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-800 text-[11px] font-bold px-2.5 py-1 rounded-lg transition shadow-xs">
                        <span>${f.label}</span>
                        <button type="button" onclick="removeFilterChip('${f.key}', '${f.amenity || ''}')" class="text-purple-400 hover:text-red-500 ml-0.5 text-xs font-black" title="Remove filter">&times;</button>
                    </span>
                `).join('')}
            </div>
        `;
    }

    // Build Horizontal Multi-Card Slider HTML or Full Property Detail Card or No Record Found Card
    let contentHtml = '';
    if (data.response_type === 'property_detail' && data.property) {
        const p = data.property;
        contentHtml = `
            <div class="mt-3 bg-gradient-to-br from-white via-purple-50/30 to-indigo-50/20 rounded-2xl border border-purple-100 shadow-sm overflow-hidden p-3.5 space-y-3">
                <!-- Header Banner with Image & Badges -->
                <div class="relative h-44 rounded-xl overflow-hidden shadow-xs bg-gray-100">
                    <img src="${p.image}" alt="${p.name}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                    <div class="absolute top-2 left-2 flex items-center gap-1.5 flex-wrap">
                        <span class="${p.tag_meta?.solid_badge || 'bg-emerald-500 text-white'} text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                            ${p.tag_meta?.label || 'Verified'}
                        </span>
                        <span class="${p.gender_class} text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm">
                            ${p.gender} PG
                        </span>
                    </div>
                    <div class="absolute top-2 right-2 bg-black/75 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                        ⭐ ${p.rating} (${p.total_reviews} reviews)
                    </div>
                    <div class="absolute bottom-2.5 left-3 right-3 text-white">
                        <h4 class="font-extrabold text-sm sm:text-base leading-tight drop-shadow-sm">${p.name}</h4>
                        <p class="text-xs text-gray-200 flex items-center gap-1 mt-0.5 truncate">
                            <i class="fas fa-map-marker-alt text-brand"></i> ${p.location}
                        </p>
                    </div>
                </div>

                <!-- Gallery Thumbnails (if multiple images) -->
                ${p.images && p.images.length > 1 ? `
                    <div class="flex gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                        ${p.images.slice(0, 6).map(img => `
                            <img src="${img}" alt="${p.name}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-purple-200/80 shadow-2xs">
                        `).join('')}
                    </div>
                ` : ''}

                <!-- Pricing & Occupancy Matrix -->
                <div class="grid grid-cols-3 gap-2 bg-white p-2.5 rounded-xl border border-gray-100 text-center shadow-2xs">
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Monthly Rent</span>
                        <span class="text-xs sm:text-sm font-black text-gray-900">${p.formatted_price}</span>
                    </div>
                    <div class="border-x border-gray-100">
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Deposit</span>
                        <span class="text-xs sm:text-sm font-bold text-gray-800">${p.formatted_deposit || p.formatted_price}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Availability</span>
                        <span class="text-xs sm:text-sm font-black ${p.available_beds > 0 ? 'text-emerald-600' : 'text-rose-600'}">
                            ${p.available_beds > 0 ? `${p.available_beds} Beds Left` : 'Sold Out'}
                        </span>
                    </div>
                </div>

                <!-- Description Snippet -->
                ${p.description ? `
                    <div class="bg-white/80 rounded-xl p-2.5 border border-gray-100 text-xs text-gray-600 leading-relaxed">
                        <p class="font-bold text-gray-800 text-[11px] mb-0.5"><i class="fas fa-info-circle text-brand mr-1"></i> About this PG:</p>
                        ${p.description}
                    </div>
                ` : ''}

                <!-- Amenities Pill Badges -->
                <div>
                    <p class="text-[11px] font-bold text-gray-700 mb-1.5 flex items-center gap-1">
                        <i class="fas fa-sparkles text-purple-600"></i> Included Amenities & Services:
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        ${(p.amenities || []).map(a => `
                            <span class="inline-flex items-center gap-1 bg-white border border-gray-200 text-gray-700 text-[11px] font-medium px-2 py-1 rounded-lg shadow-2xs">
                                <i class="fas fa-${a.icon || 'check'} text-brand text-[10px]"></i> ${a.name}
                            </span>
                        `).join('')}
                    </div>
                </div>

                <!-- House Rules -->
                ${p.rules && p.rules.length ? `
                    <div class="bg-amber-50/70 border border-amber-200/80 rounded-xl p-2.5 text-[11px] text-amber-900 space-y-1">
                        <p class="font-bold flex items-center gap-1 text-amber-950">
                            <i class="fas fa-clipboard-check text-amber-600"></i> House Rules & Guidelines:
                        </p>
                        <ul class="list-disc list-inside space-y-0.5 text-gray-700 pl-1 text-[11px]">
                            ${p.rules.slice(0, 4).map(r => `<li>${r}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}

                <!-- Direct CTA Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-2 pt-1">
                    <a href="${p.detail_url}" class="flex-1 bg-brand hover:bg-brand-dark text-white text-xs font-bold py-2.5 px-3 rounded-xl transition shadow-xs text-center no-underline flex items-center justify-center gap-1.5">
                        <i class="fas fa-external-link-alt text-[10px]"></i> View Full Listing Page
                    </a>
                    <button type="button" onclick="askAiPrompt('Search similar PGs like ${p.name.replace(/'/g, "\\'")}')" class="bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-bold py-2.5 px-3 rounded-xl transition text-center flex items-center justify-center gap-1">
                        <i class="fas fa-clone text-[10px]"></i> Similar PGs
                    </button>
                </div>
            </div>
        `;
    } else if (data.properties && data.properties.length) {
        contentHtml = `
            <div class="mt-3">
                <p class="text-[10px] sm:text-[11px] font-extrabold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                    <span><i class="fas fa-sliders text-brand mr-1"></i> Match Ranked Stays (${data.properties.length} active listings)</span>
                    <a href="{{ route('user.search') }}?q=${encodeURIComponent(data.intent?.raw_query || '')}" class="text-brand font-bold hover:underline">View all &rarr;</a>
                </p>
                <div class="ai-chat-slider">
                    ${data.properties.map(p => `
                        <div class="ai-slider-card flex flex-col justify-between">
                            <div>
                                <div class="relative h-28 overflow-hidden bg-gray-100">
                                    <img src="${p.image}" alt="${p.name}" class="w-full h-full object-cover">
                                    <span class="absolute top-2 left-2 ${p.tag_meta?.solid_badge || 'bg-emerald-500 text-white'} text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                        ${p.tag_meta?.label || 'Verified'}
                                    </span>
                                    <!-- StayNest Match % Badge -->
                                    <span class="absolute top-2 right-2 bg-emerald-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-sm flex items-center gap-1">
                                        <i class="fas fa-bolt text-yellow-300 text-[8px]"></i> ${p.match_score}% Match
                                    </span>
                                    <span class="absolute bottom-1.5 right-2 bg-black/75 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">
                                        ⭐ ${p.rating}
                                    </span>
                                </div>
                                <div class="p-2.5">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <p class="font-bold text-xs text-gray-900 truncate leading-tight">${p.name}</p>
                                        <span class="${p.gender_class} text-[8px] font-extrabold px-1.5 py-0.5 rounded flex-shrink-0">${p.gender}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 truncate mb-1.5">
                                        <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> ${p.location}
                                    </p>

                                    <!-- Match Breakdown Checklist -->
                                    <div class="bg-gray-50 rounded-lg p-1.5 mb-2 text-[10px] space-y-0.5 border border-gray-100">
                                        ${(p.match_breakdown || []).slice(0, 3).map(b => `
                                            <div class="flex items-center gap-1 ${b.matched ? 'text-emerald-700 font-semibold' : 'text-gray-400'} truncate">
                                                <i class="fas ${b.matched ? 'fa-check text-emerald-500' : 'fa-xmark text-gray-400'} text-[8px]"></i>
                                                <span class="truncate">${b.feature}</span>
                                            </div>
                                        `).join('')}
                                    </div>

                                    <div class="flex flex-wrap gap-1 mb-1">
                                        ${(p.amenities || []).slice(0, 2).map(a => `<span class="text-[9px] bg-gray-50 border border-gray-100 px-1.5 py-0.5 rounded text-gray-600">${a}</span>`).join('')}
                                    </div>
                                </div>
                            </div>
                            <div class="p-2.5 pt-1.5 border-t border-gray-100 flex items-center justify-between gap-1.5">
                                <span class="text-xs sm:text-sm font-black text-gray-900">${p.formatted_price}</span>
                                <div class="flex items-center gap-1">
                                    <button type="button" onclick="askAiPrompt('Give full details of ${p.name.replace(/'/g, "\\'")}')" class="bg-purple-100 hover:bg-purple-200 text-purple-700 text-[10px] font-bold px-2 py-1.5 rounded-xl transition flex items-center gap-1" title="Get full details in chat">
                                        <i class="fas fa-circle-info"></i> Detail
                                    </button>
                                    <a href="${p.detail_url}" class="bg-brand hover:bg-brand-dark text-white text-[11px] font-bold px-2.5 py-1.5 rounded-xl transition shadow-xs no-underline">View</a>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>

            <div class="flex flex-wrap gap-2 pt-2.5 border-t border-gray-100 mt-2.5">
                <a href="{{ route('user.search') }}?q=${encodeURIComponent(data.intent?.raw_query || '')}" class="inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-600 hover:text-white text-purple-700 text-xs font-bold px-3 py-1.5 rounded-xl transition border border-purple-200 no-underline">
                    <i class="fas fa-filter text-[10px]"></i> 🔍 Open in Search Grid
                </a>
            </div>
        `;
    } else {
        contentHtml = `
            <div class="mt-2.5 p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-2xl text-center space-y-2">
                <div class="w-10 h-10 mx-auto rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-base shadow-xs">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <p class="font-black text-xs sm:text-sm text-gray-900">No Record Found</p>
                    <p class="text-[11px] text-gray-600 leading-relaxed mt-0.5">
                        Database me is search ke liye koi verified listing nahi mili. Aap in popular cities me explore kar sakte hain:
                    </p>
                </div>
                <div class="flex flex-wrap justify-center gap-1.5 pt-1">
                    <button type="button" onclick="askAiPrompt('Noida Sector 62 boys PG under 8k')" class="text-[10px] font-bold bg-white text-gray-800 hover:text-brand px-2.5 py-1.5 rounded-xl border border-gray-200 shadow-xs transition tap-effect">
                        📍 Noida Sec 62
                    </button>
                    <button type="button" onclick="askAiPrompt('Bangalore Koramangala girls PG with AC')" class="text-[10px] font-bold bg-white text-gray-800 hover:text-brand px-2.5 py-1.5 rounded-xl border border-gray-200 shadow-xs transition tap-effect">
                        📍 Bangalore AC
                    </button>
                    <button type="button" onclick="askAiPrompt('Co-Ed stays in Delhi with wifi')" class="text-[10px] font-bold bg-white text-gray-800 hover:text-brand px-2.5 py-1.5 rounded-xl border border-gray-200 shadow-xs transition tap-effect">
                        📍 Delhi Stays
                    </button>
                    <a href="{{ route('user.search') }}" class="text-[10px] font-bold bg-brand text-white px-3 py-1.5 rounded-xl shadow-xs transition no-underline">
                        🔍 View All Available PGs
                    </a>
                </div>
            </div>
        `;
    }

    // Render bot bubble
    const botReply = document.createElement('div');
    botReply.className = 'flex items-start gap-2.5 max-w-[96%]';
    botReply.innerHTML = `
        <div class="w-7 h-7 rounded-xl bg-brand flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
            <i class="fas fa-robot"></i>
        </div>
        <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-gray-100 shadow-sm text-xs sm:text-sm text-gray-800 space-y-2 w-full">
            <div class="flex items-center justify-between pb-1.5 border-b border-gray-100">
                <span class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-1">
                    <i class="fas fa-wand-magic-sparkles text-purple-600"></i> ${data.total_matches > 0 ? 'Verified Matches' : 'Search Result'}
                </span>
                <span class="text-[9px] ${data.total_matches > 0 ? 'text-purple-700 bg-purple-50 border-purple-200' : 'text-amber-700 bg-amber-50 border-amber-200'} border px-2 py-0.5 rounded font-bold">
                    ${data.total_matches > 0 ? 'StayNest Match AI' : '0 Results'}
                </span>
            </div>
            ${filtersHtml}
            <p class="leading-relaxed text-gray-700">${data.message}</p>
            ${contentHtml}
        </div>
    `;
    chat.appendChild(botReply);
    chat.scrollTop = chat.scrollHeight;
}
</script>
