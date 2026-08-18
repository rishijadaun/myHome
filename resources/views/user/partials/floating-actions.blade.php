<!-- ===================== FIXED FLOATING ACTIONS (MAP VIEW & AI CHATBOT) ===================== -->
@php
    $realDatabaseProperties = \App\Models\Property::with(['city', 'area', 'images', 'primaryImage', 'amenities'])
        ->where('is_active', true)
        ->latest()
        ->limit(100)
        ->get()
        ->map(function($p) {
            $img = $p->display_image_url;
            $price = (int)($p->monthly_rent ?? 6500);
            $cityName = $p->city->name ?? 'Noida';
            $gender = strtoupper($p->gender_preference ?? 'CO-ED');
            if (in_array($gender, ['ALL', 'ANY', 'COED', 'UNISEX'])) $gender = 'CO-ED';
            elseif ($gender === 'MALE' || $gender === 'BOY' || $gender === 'BOYS') $gender = 'BOYS';
            elseif ($gender === 'FEMALE' || $gender === 'GIRL' || $gender === 'GIRLS') $gender = 'GIRLS';
            
            $amList = $p->amenities->pluck('name')->toArray();
            if (empty($amList)) $amList = ['WiFi', '3 Meals', 'Security'];

            return [
                'id' => $p->id,
                'title' => $p->name,
                'type' => $gender,
                'typeClass' => $gender === 'GIRLS' ? 'bg-pink-50 text-pink-600' : ($gender === 'BOYS' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600'),
                'city' => $cityName,
                'loc' => $p->address ? $p->address : (($p->area->name ?? $cityName) . ' Central'),
                'price' => '₹' . number_format($price),
                'raw_price' => $price,
                'rating' => ($p->rating ?? 4.8) . '★',
                'badge' => $p->featured ? 'Featured' : ($p->verification_status === 'verified' ? 'Verified' : 'Zero Brokerage'),
                'badgeBg' => $p->featured ? 'bg-amber-500' : 'bg-emerald-500',
                'img' => $img,
                'am' => array_slice($amList, 0, 3),
                'budget' => $price,
                'detail_url' => route('user.detail', $p->id)
            ];
        });
@endphp

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
    <div class="absolute bottom-0 md:bottom-6 right-0 md:right-6 w-full sm:w-[500px] md:w-[540px] lg:w-[580px] max-h-[94vh] md:max-h-[720px] h-[680px] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden z-10 animate-slide-up">
        
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
                        <span class="bg-yellow-400/25 text-yellow-300 text-[9px] font-extrabold px-1.5 py-0.2 rounded border border-yellow-400/30">SMART NLP v5</span>
                    </div>
                    <p class="text-[11px] text-purple-200/80">Intelligent Stay Concierge &amp; Direct Landlord Matching</p>
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
            <button type="button" onclick="askAiPrompt('Boys PG under 8000 in Noida Sector 62')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                👦 Boys Noida Sec 62
            </button>
            <button type="button" onclick="askAiPrompt('Girls PG with AC in Bangalore Koramangala')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                👧 Girls Bangalore AC
            </button>
            <button type="button" onclick="askAiPrompt('Co-Ed unisex coliving stays in Delhi with Gym')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                👥 Co-Living Delhi
            </button>
            <button type="button" onclick="askAiPrompt('Budget stays under 6000 with food')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                💰 Under ₹6K with Food
            </button>
            <button type="button" onclick="askAiPrompt('Stays in Orai and Jhansi')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                📍 Orai &amp; Jhansi
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
                    <p class="leading-relaxed text-gray-700">Ask me anything in English or Hindi! Tell me your <strong>City</strong> (Noida, Bangalore, Delhi, Pune, Hyderabad, Orai, Jhansi, etc.), <strong>Budget</strong>, <strong>Gender</strong>, or <strong>Amenities</strong>:</p>
                    <div class="pt-1 flex flex-wrap gap-1.5 text-[10px]">
                        <span class="bg-pink-50 text-pink-700 font-bold px-2.5 py-1 rounded-lg border border-pink-200/60">👧 Girls PGs</span>
                        <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200/60">👦 Boys PGs</span>
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
                    <button type="button" onclick="askAiPrompt('Mujhe Noida Sector 62 me boys pg chahiye under 8000 with food')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Boys PG Noida Sec 62 &lt; ₹8K
                    </button>
                    <button type="button" onclick="askAiPrompt('Looking for Girls PG in Bangalore with AC and WiFi under 9k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Girls PG Bangalore with AC
                    </button>
                    <button type="button" onclick="askAiPrompt('Best luxury Co-Living in Gurgaon with Gym & WiFi')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        🏢 Co-Living Gurgaon Cyber City
                    </button>
                    <button type="button" onclick="askAiPrompt('Budget single room stays in Orai or Jhansi under 5k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Orai &amp; Jhansi Stays &lt; ₹5k
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
                           placeholder="Ask: 'Boys PG in Noida under 8k' or 'Girls AC in Bangalore'..." 
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
                <span><i class="fas fa-shield-halved text-brand"></i> 100% Zero Brokerage Verified</span>
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
        flex: 0 0 230px;
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
// Live Database Properties Catalog
const STAYNEST_PROPERTY_CATALOG = @json($realDatabaseProperties ?? []);

// Comprehensive Indian Cities & Hubs List
const INDIAN_CITIES = [
    { name: 'Noida', aliases: ['noida', 'greater noida', 'sec 62', 'sector 62', 'sec 18', 'sector 18', 'electronic city noida'], hubs: 'Sector 62, Sector 18 & Knowledge Park' },
    { name: 'Bangalore', aliases: ['bangalore', 'bengaluru', 'koramangala', 'indiranagar', 'hsr layout', 'hsr', 'electronic city bangalore', 'electronic city', 'whitefield', 'bellandur', 'marathahalli'], hubs: 'Koramangala, Indiranagar, HSR & Electronic City' },
    { name: 'Delhi', aliases: ['delhi', 'new delhi', 'saket', 'laxmi nagar', 'north campus', 'south ex', 'hauz khas', 'dwarka', 'mukherjee nagar', 'cp'], hubs: 'Saket, North Campus, South Ex & Laxmi Nagar' },
    { name: 'Pune', aliases: ['pune', 'viman nagar', 'hinjewadi', 'kothrud', 'wakad', 'baner', 'hadapsar', 'kharadi', 'magarpatta'], hubs: 'Viman Nagar, Hinjewadi, Baner & Kharadi' },
    { name: 'Hyderabad', aliases: ['hyderabad', 'hitec city', 'madhapur', 'gachibowli', 'kondapur', 'kukatpally', 'ameerpet'], hubs: 'Hitec City, Gachibowli, Madhapur & Kondapur' },
    { name: 'Mumbai', aliases: ['mumbai', 'andheri', 'bandra', 'powai', 'navi mumbai', 'thane', 'dadar', 'juhu'], hubs: 'Andheri West, Powai, Bandra & Thane' },
    { name: 'Gurugram', aliases: ['gurugram', 'gurgaon', 'cyber city', 'dlf phase', 'sohna road', 'golf course road', 'sector 29'], hubs: 'Cyber City, DLF Phase 1-3 & Sohna Road' },
    { name: 'Jaipur', aliases: ['jaipur', 'malviya nagar', 'mansarovar', 'vaishali nagar', 'raja park', 'c scheme'], hubs: 'Malviya Nagar, Mansarovar & Raja Park' },
    { name: 'Orai', aliases: ['orai', 'jalaun', 'rath road', 'station road orai'], hubs: 'Station Road, Rath Road & Orai Central' },
    { name: 'Jhansi', aliases: ['jhansi', 'sadar bazar jhansi', 'sipri bazar', 'elite crossing jhansi'], hubs: 'Sadar Bazar, Sipri Bazar & Elite Crossing' },
    { name: 'Kanpur', aliases: ['kanpur', 'kakadeo', 'swaroop nagar', 'kalyanpur', 'civil lines kanpur'], hubs: 'Kakadeo, Swaroop Nagar & Kalyanpur' },
    { name: 'Lucknow', aliases: ['lucknow', 'gomti nagar', 'hazratganj', 'aliganj', 'indira nagar'], hubs: 'Gomti Nagar, Hazratganj & Aliganj' },
    { name: 'Greater Noida', aliases: ['greater noida', 'knowledge park', 'pari chowk', 'alpha 1', 'beta 1'], hubs: 'Knowledge Park I-III, Pari Chowk & Alpha' },
    { name: 'Kota', aliases: ['kota', 'landmark city', 'vigyan nagar', 'talwandi', 'mahaveer nagar', 'indira vihar'], hubs: 'Landmark City, Vigyan Nagar & Talwandi' },
    { name: 'Dehradun', aliases: ['dehradun', 'rajpur road', 'karanpur', 'ballupur', 'bidholi'], hubs: 'Rajpur Road, Karanpur & Bidholi' },
    { name: 'Ahmedabad', aliases: ['ahmedabad', 'sg highway', 'navrangpura', 'vastrapur', 'bodakdev'], hubs: 'SG Highway, Navrangpura & Vastrapur' },
    { name: 'Indore', aliases: ['indore', 'vijay nagar', 'bhanwarkuan', 'palasia', 'geeta bhawan'], hubs: 'Vijay Nagar, Bhanwarkuan & Bhawarkua' },
    { name: 'Chandigarh', aliases: ['chandigarh', 'mohali', 'panchkula', 'sector 17', 'sector 35'], hubs: 'Mohali Phase 7, Sector 35 & IT Park' },
    { name: 'Kolkata', aliases: ['kolkata', 'salt lake', 'new town', 'park street', 'ballygunge', 'jadavpur'], hubs: 'Salt Lake Sector 5, New Town & Park Street' },
    { name: 'Chennai', aliases: ['chennai', 'omr', 'velachery', 'guindy', 'anna nagar', 'thoraipakkam', 'adyar'], hubs: 'OMR Tech Corridor, Velachery & Anna Nagar' }
];

// Dynamically generate verified recommendations for any city
function generateCityProperties(cityName, genderType, budgetVal, amenities) {
    const isGirls = genderType === 'GIRLS';
    const isBoys = genderType === 'BOYS';
    const typeLabel = isGirls ? 'GIRLS' : (isBoys ? 'BOYS' : 'CO-ED');
    const typeClass = isGirls ? 'bg-pink-50 text-pink-600' : (isBoys ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600');
    
    let basePrice = budgetVal ? Math.min(budgetVal, 8500) : (isGirls ? 6800 : 6200);
    if (basePrice < 4500) basePrice = 4999;

    const dummyImgs = [
        'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
    ];

    const hubObj = INDIAN_CITIES.find(c => c.name.toLowerCase() === cityName.toLowerCase());
    const hubText = hubObj ? hubObj.hubs.split(',')[0].trim() : `${cityName} Central`;

    return [
        {
            id: 'dyn_1',
            title: `Aura Verified ${typeLabel} Stay`,
            type: typeLabel,
            typeClass: typeClass,
            city: cityName,
            loc: `${hubText}, ${cityName}`,
            price: `₹${basePrice.toLocaleString('en-IN')}`,
            rating: '4.8★',
            badge: 'Verified',
            badgeBg: 'bg-emerald-500',
            img: isGirls ? dummyImgs[1] : dummyImgs[0],
            am: ['WiFi', '3 Meals', 'Security'],
            budget: basePrice,
            detail_url: '{{ route("user.search") }}?city=' + encodeURIComponent(cityName)
        },
        {
            id: 'dyn_2',
            title: `Royal ${typeLabel} Comfort Living`,
            type: typeLabel,
            typeClass: typeClass,
            city: cityName,
            loc: `Near Metro & IT Hub, ${cityName}`,
            price: `₹${(basePrice + 1200).toLocaleString('en-IN')}`,
            rating: '4.9★',
            badge: 'Top Rated',
            badgeBg: 'bg-blue-600',
            img: isGirls ? dummyImgs[3] : dummyImgs[2],
            am: ['AC Room', 'WiFi', 'Gym'],
            budget: basePrice + 1200,
            detail_url: '{{ route("user.search") }}?city=' + encodeURIComponent(cityName)
        },
        {
            id: 'dyn_3',
            title: `Budget Pocket ${typeLabel} Residency`,
            type: typeLabel,
            typeClass: typeClass,
            city: cityName,
            loc: `Student Hub, ${cityName}`,
            price: `₹${Math.max(4500, basePrice - 800).toLocaleString('en-IN')}`,
            rating: '4.6★',
            badge: 'Budget Pick',
            badgeBg: 'bg-emerald-600',
            img: dummyImgs[0],
            am: ['Food Included', 'WiFi'],
            budget: Math.max(4500, basePrice - 800),
            detail_url: '{{ route("user.search") }}?city=' + encodeURIComponent(cityName)
        }
    ];
}

// ===================== ADVANCED NLP INTENT & CONVERSATIONAL ENGINE =====================
function processAiQuery(query) {
    const q = query.toLowerCase().trim();

    // 1. CONVERSATIONAL & SOCIAL GREETINGS
    const isExactHi = /^(hi|hello|hey|heyy|hii|namaste|hola|sup|yo|greetings)\b/i.test(q);
    if (isExactHi && q.split(' ').length <= 3) {
        return {
            type: 'info',
            title: 'Hello! Welcome to StayNest 👋',
            answer: `Hello there! I am your personal AI Stay Concierge. How can I help you today? You can ask me in English or Hindi for <strong>any city</strong> (Noida, Bangalore, Pune, Hyderabad, Delhi, Orai, Jhansi, etc.):`,
            steps: [
                '• <strong>Find Stays:</strong> "Safe Girls PG in Pune with food" or "Boys PG in Noida under 8k"',
                '• <strong>Zero Brokerage:</strong> How to rent directly with verified landlords',
                '• <strong>Schedule Visit:</strong> How to book free in-person or live video tours',
                '• <strong>List Property:</strong> How to list your PG for free as an owner'
            ],
            links: [
                { text: 'Browse Stays', url: '{{ route("user.search") }}', icon: 'search' },
                { text: 'Map View', url: '{{ route("user.location") }}', icon: 'map-location-dot' }
            ]
        };
    }

    // 2. FAREWELLS / GOODBYES
    const isBye = /^(bye|goodbye|cya|see you|tata|good night|take care|bye bye)\b/i.test(q);
    if (isBye && q.split(' ').length <= 4) {
        return {
            type: 'info',
            title: 'Goodbye! Have a Wonderful Day 👋',
            answer: 'Thank you for chatting with StayNest AI! Whenever you need to find your next home or have any stay questions, our 24/7 assistant is always here. Have a great day ahead!',
            links: [
                { text: 'Saved Stays', url: '{{ route("user.saved") }}', icon: 'heart' },
                { text: 'My Profile', url: '{{ route("user.profile") }}', icon: 'user' }
            ]
        };
    }

    // 3. GRATITUDE / THANKS
    const isThanks = /^(thanks|thank you|thank u|thx|appreciate|dhanyawad|shukriya)\b/i.test(q);
    if (isThanks && q.split(' ').length <= 4) {
        return {
            type: 'info',
            title: "You're Very Welcome! 😊",
            answer: "It's my absolute pleasure! If you'd like to explore specific areas, compare amenities, or schedule a free property visit, feel free to ask anytime!",
            links: [
                { text: 'Explore All Stays', url: '{{ route("user.search") }}', icon: 'search' },
                { text: 'Direct Support', url: '{{ route("user.contact") }}', icon: 'headset' }
            ]
        };
    }

    // 4. SMALL TALK / IDENTITY
    if (q.includes('how are you') || q.includes('how r u') || q.includes('how are u') || q.includes("how's it going")) {
        return {
            type: 'info',
            title: "Doing Great, Thank You! 🤖✨",
            answer: "I am doing fantastic! Ready to help you discover the best zero-brokerage PGs across India. What city or budget are you planning for?",
            links: [
                { text: 'Noida PGs', url: '{{ route("user.search") }}?city=noida', icon: 'city' },
                { text: 'Bangalore PGs', url: '{{ route("user.search") }}?city=bangalore', icon: 'city' },
                { text: 'Pune PGs', url: '{{ route("user.search") }}?city=pune', icon: 'city' }
            ]
        };
    }

    if (q.includes('who are you') || q.includes('what are you') || q.includes('what is staynest') || q.includes('who made you')) {
        return {
            type: 'info',
            title: "About StayNest AI 🌟",
            answer: "I am <strong>StayNest AI</strong> — India's smart accommodation assistant. We help students and working professionals find 100% verified, zero-brokerage PGs and co-living spaces with transparent pricing, home-cooked food, and high safety standards.",
            links: [
                { text: 'Learn More About Us', url: '{{ route("user.about") }}', icon: 'circle-info' },
                { text: 'Browse Stays', url: '{{ route("user.search") }}', icon: 'search' }
            ]
        };
    }

    // 5. DETAILED CITY, GENDER, BUDGET & AMENITIES PARSER
    let detectedFilters = [];

    // Gender / Type Detection
    let isGirls = /\b(girls?|girl|female|women|ladies|ladkiyo?|ladki|womens?)\b/i.test(q);
    let isBoys = /\b(boys?|boy|male|men|ladka|ladko?|gents?|bachelor|mens?)\b/i.test(q);
    let isColiving = /\b(coliving|co-living|coed|unisex|couple|couples|mixed|studio|1rk|flat)\b/i.test(q);

    let genderCategory = isGirls ? 'GIRLS' : (isBoys ? 'BOYS' : (isColiving ? 'CO-ED' : 'ALL'));

    if (isGirls) detectedFilters.push('👧 Girls Only');
    else if (isBoys) detectedFilters.push('👦 Boys Only');
    else if (isColiving) detectedFilters.push('👥 Co-Living');

    // Advanced City Detection
    let matchedCity = null;
    for (const c of INDIAN_CITIES) {
        const isMatched = c.aliases.some(alias => q.includes(alias));
        if (isMatched) {
            matchedCity = c.name;
            detectedFilters.push('📍 ' + c.name);
            break;
        }
    }

    // Dynamic Budget Extraction
    let parsedBudget = null;
    const kMatch = q.match(/(\d+(?:\.\d+)?)\s*k\b/i);
    if (kMatch) {
        parsedBudget = Math.round(parseFloat(kMatch[1]) * 1000);
    } else {
        const numMatch = q.match(/(?:under|below|max|upto|around|approx|se kam|k andar|andar|\b)\s*(\d{4,5})\b/i);
        if (numMatch) {
            parsedBudget = parseInt(numMatch[1]);
        }
    }

    if (parsedBudget) {
        detectedFilters.push('💰 Under ₹' + parsedBudget.toLocaleString('en-IN'));
    }

    // Amenities Detection
    if (/\b(ac|air conditioner|cooling)\b/i.test(q)) detectedFilters.push('❄️ AC');
    if (/\b(food|meal|meals|khana|mess|breakfast|dinner)\b/i.test(q)) detectedFilters.push('🍲 3 Meals');
    if (/\b(wifi|internet|broadband)\b/i.test(q)) detectedFilters.push('📶 Free WiFi');
    if (/\b(gym|fitness|workout)\b/i.test(q)) detectedFilters.push('🏋️ Gym');
    if (/\b(single|private room)\b/i.test(q)) detectedFilters.push('🚪 Single Room');
    if (/\b(bath|bathroom|washroom|attached)\b/i.test(q)) detectedFilters.push('🚿 Attached Bath');
    if (/\b(metro|station)\b/i.test(q)) detectedFilters.push('🚇 Near Metro');
    if (/\b(zero deposit|no deposit)\b/i.test(q)) detectedFilters.push('⚡ Zero Deposit');

    // Check for general stay intent
    const hasStayIntent = isBoys || isGirls || isColiving || matchedCity || parsedBudget || 
        /\b(pg|hostel|stay|stays|room|rooms|rent|coliving|flat|residency|accommodation|dorm|sharing|find|show|search|chahiye|dekho|list)\b/i.test(q);

    // 6. DYNAMIC & DATABASE PROPERTY MATCHING
    if (hasStayIntent) {
        let finalPgs = [];

        // Match against real database properties
        let pool = [...STAYNEST_PROPERTY_CATALOG];

        if (matchedCity) {
            let cityMatched = pool.filter(p => p.city.toLowerCase().includes(matchedCity.toLowerCase()) || p.loc.toLowerCase().includes(matchedCity.toLowerCase()));
            
            if (genderCategory !== 'ALL') {
                const gFiltered = cityMatched.filter(p => p.type === genderCategory);
                if (gFiltered.length > 0) cityMatched = gFiltered;
            }

            if (parsedBudget) {
                const bFiltered = cityMatched.filter(p => p.raw_price <= (parsedBudget + 500));
                if (bFiltered.length > 0) cityMatched = bFiltered;
            }

            if (cityMatched.length > 0) {
                finalPgs = cityMatched;
            } else {
                finalPgs = generateCityProperties(matchedCity, genderCategory, parsedBudget);
            }
        } else {
            // General query with gender or budget
            let filtered = [...pool];
            if (genderCategory !== 'ALL') {
                const gFiltered = filtered.filter(p => p.type === genderCategory);
                if (gFiltered.length > 0) filtered = gFiltered;
            }
            if (parsedBudget) {
                const bFiltered = filtered.filter(p => p.raw_price <= (parsedBudget + 500));
                if (bFiltered.length > 0) filtered = bFiltered;
            }
            finalPgs = filtered.length > 0 ? filtered : generateCityProperties('Noida', genderCategory, parsedBudget);
        }

        let cityNameDisplay = matchedCity || 'Your Desired Area';
        let genderNameDisplay = isGirls ? 'Girls' : (isBoys ? 'Boys' : (isColiving ? 'Co-Living' : 'Verified'));
        
        let humanIntro = `I've discovered top verified <strong>${genderNameDisplay}</strong> stays in <strong>${cityNameDisplay}</strong> for you! All listings include direct owner connectivity, 100% Zero Brokerage, and verified amenities:`;
        
        return {
            type: 'property_carousel',
            title: `Verified Stays in ${cityNameDisplay} ✨`,
            answer: humanIntro,
            filters: detectedFilters,
            pgs: finalPgs,
            searchUrl: '{{ route("user.search") }}?q=' + encodeURIComponent(query),
            mapUrl: '{{ route("user.location") }}'
        };
    }

    // 7. ZERO BROKERAGE INFO
    if (q.includes('brokerage') || q.includes('broker') || q.includes('commission') || q.includes('hidden') || q.includes('extra fee')) {
        return {
            type: 'info',
            title: '100% Zero Brokerage Guarantee 💰',
            answer: 'StayNest guarantees <strong>100% Zero Brokerage</strong>! You connect and deal directly with verified landlords without any agent commissions or platform surcharge.',
            steps: [
                '✔️ Direct deal with property owners',
                '✔️ Zero booking fees or commission',
                '✔️ Transparent security deposit with digital receipts'
            ],
            links: [
                { text: 'Browse Stays', url: '{{ route("user.search") }}', icon: 'search' },
                { text: 'About Us', url: '{{ route("user.about") }}', icon: 'circle-info' }
            ]
        };
    }

    // 8. LIST PROPERTY INFO
    if (q.includes('list') || q.includes('owner') || q.includes('landlord') || q.includes('host') || q.includes('add pg') || q.includes('register property')) {
        return {
            type: 'info',
            title: 'List Your PG / Property Free 🏠',
            answer: 'Are you a PG owner or property manager? You can register and list your property on StayNest for <strong>Free</strong> in 3 simple steps:',
            steps: [
                '1. Go to <strong>List Property</strong> page',
                '2. Enter room amenities, rent details, and photos',
                '3. Get instant tenant inquiries directly on Call/WhatsApp'
            ],
            links: [
                { text: 'List Property Free', url: '{{ route("user.list-property") }}', icon: 'plus-circle' },
                { text: 'Owner Pricing Plans', url: '{{ route("user.pricing") }}', icon: 'tags' }
            ]
        };
    }

    // 9. BOOKING & VISIT SCHEDULING
    if (q.includes('book') || q.includes('visit') || q.includes('schedule') || q.includes('token') || q.includes('tour')) {
        return {
            type: 'info',
            title: 'Booking & Free Visit Schedule 📅',
            answer: 'Booking a stay on StayNest is 100% digital and verified:',
            steps: [
                '1. Select your preferred verified PG',
                '2. Click "Schedule Visit" for a free in-person or live video tour',
                '3. Pay token amount directly to lock your bed with instant digital KYC receipt'
            ],
            links: [
                { text: 'Search Stays', url: '{{ route("user.search") }}', icon: 'search' },
                { text: 'My Bookings', url: '{{ route("user.bookings") }}', icon: 'calendar-check' }
            ]
        };
    }

    // 10. CONTACT & HELPLINE
    if (q.includes('contact') || q.includes('support') || q.includes('help') || q.includes('number') || q.includes('phone') || q.includes('email') || q.includes('address')) {
        return {
            type: 'info',
            title: '24/7 StayNest Help Desk 📞',
            answer: 'Our dedicated student and tenant support helpline is available 24/7:',
            steps: [
                '📞 <strong>Customer Helpline:</strong> +91 98765 43210',
                '💬 <strong>WhatsApp Desk:</strong> +91 98765 43211',
                '✉️ <strong>Official Email:</strong> support@staynest.com',
                '🏢 <strong>Corporate Office:</strong> Sector 62, Noida, Delhi NCR'
            ],
            links: [
                { text: 'Contact Us', url: '{{ route("user.contact") }}', icon: 'envelope' },
                { text: 'Terms & Policies', url: '{{ route("user.terms") }}', icon: 'file-contract' }
            ]
        };
    }

    // 11. UNMATCHED / NO RESULTS FALLBACK
    return {
        type: 'no_results_contact',
        title: `Finding Best Matches for "${query}" 🔍`,
        answer: `I could not locate an exact strict match for <strong>"${query}"</strong>. However, our concierge desk can arrange personalized options for you directly:`,
        contactInfo: {
            phone: '+91 98765 43210',
            whatsapp: '+91 98765 43210',
            email: 'support@staynest.com'
        },
        suggestedPgs: STAYNEST_PROPERTY_CATALOG.slice(0, 3),
        query: query
    };
}

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
    const chat = document.getElementById('aiChatMessages');
    if (!chat) return;
    chat.innerHTML = `
        <div class="flex items-start gap-2.5 max-w-[95%]">
            <div class="w-7 h-7 rounded-xl bg-brand flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
                <i class="fas fa-robot"></i>
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-gray-100 shadow-xs text-xs text-gray-800 space-y-2.5">
                <p class="font-bold text-gray-900 text-sm">Hello! I am your StayNest AI Concierge 👋</p>
                <p class="leading-relaxed text-gray-700">Ask me anything in English or Hindi! Tell me your <strong>City</strong> (Noida, Bangalore, Delhi, Pune, Hyderabad, Orai, Jhansi, etc.), <strong>Budget</strong>, <strong>Gender</strong>, or <strong>Amenities</strong>:</p>
                <div class="pt-1 flex flex-wrap gap-1.5 text-[10px]">
                    <span class="bg-pink-50 text-pink-700 font-bold px-2.5 py-1 rounded-lg border border-pink-200/60">👧 Girls PGs</span>
                    <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200/60">👦 Boys PGs</span>
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
                <button type="button" onclick="askAiPrompt('Mujhe Noida Sector 62 me boys pg chahiye under 8000 with food')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Boys PG Noida Sec 62 &lt; ₹8K
                </button>
                <button type="button" onclick="askAiPrompt('Looking for Girls PG in Bangalore with AC and WiFi under 9k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Girls PG Bangalore with AC
                </button>
                <button type="button" onclick="askAiPrompt('Best luxury Co-Living in Gurgaon with Gym & WiFi')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    🏢 Co-Living Gurgaon Cyber City
                </button>
                <button type="button" onclick="askAiPrompt('Budget single room stays in Orai or Jhansi under 5k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Orai &amp; Jhansi Stays &lt; ₹5k
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

function handleAiSubmit(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('aiChatInput');
    if (!input) return;
    const query = input.value.trim();
    if (!query) return;

    const chat = document.getElementById('aiChatMessages');
    if (!chat) return;

    // 1. Append user message bubble
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

    // 3. Process with AI Intelligence Engine
    setTimeout(() => {
        const el = document.getElementById(typingId);
        if (el) el.remove();

        const data = processAiQuery(query);

        // Build Filter Chips
        let filtersHtml = '';
        if (data.filters && data.filters.length) {
            filtersHtml = `
                <div class="flex flex-wrap gap-1.5 mb-2.5">
                    ${data.filters.map(f => `<span class="bg-purple-50 border border-purple-200 text-purple-700 text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-md">${f}</span>`).join('')}
                </div>
            `;
        }

        // Build Horizontal Multi-Card Slider HTML
        let carouselHtml = '';
        if (data.type === 'property_carousel' && data.pgs && data.pgs.length) {
            carouselHtml = `
                <div class="mt-3">
                    <p class="text-[10px] sm:text-[11px] font-extrabold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                        <span><i class="fas fa-sliders text-brand mr-1"></i> Swipe to explore (${data.pgs.length} stays)</span>
                        <a href="${data.searchUrl}" class="text-brand font-bold hover:underline">View all &rarr;</a>
                    </p>
                    <div class="ai-chat-slider">
                        ${data.pgs.map(p => `
                            <div class="ai-slider-card flex flex-col justify-between">
                                <div>
                                    <div class="relative h-28 overflow-hidden bg-gray-100">
                                        <img src="${p.img}" alt="${p.title}" class="w-full h-full object-cover">
                                        <span class="absolute top-2 left-2 ${p.badgeBg} text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                            ${p.badge}
                                        </span>
                                        <span class="absolute bottom-1.5 right-2 bg-black/75 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">
                                            ${p.rating}
                                        </span>
                                    </div>
                                    <div class="p-2.5">
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <p class="font-bold text-xs text-gray-900 truncate leading-tight">${p.title}</p>
                                            <span class="${p.typeClass} text-[8px] font-extrabold px-1.5 py-0.5 rounded flex-shrink-0">${p.type}</span>
                                        </div>
                                        <p class="text-[11px] text-gray-500 truncate mb-2">
                                            <i class="fas fa-map-marker-alt text-brand text-[9px]"></i> ${p.loc}
                                        </p>
                                        <div class="flex flex-wrap gap-1 mb-1">
                                            ${(p.am || []).slice(0, 2).map(a => `<span class="text-[9px] bg-gray-50 border border-gray-100 px-1.5 py-0.5 rounded text-gray-600">${a}</span>`).join('')}
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2.5 pt-1.5 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs sm:text-sm font-black text-gray-900">${p.price}<span class="text-[9px] font-normal text-gray-400">/m</span></span>
                                    <a href="${p.detail_url}" class="bg-brand hover:bg-brand-dark text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition shadow-xs">View</a>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Render No Results Direct Contact Widget
        if (data.type === 'no_results_contact') {
            const encodedQuery = encodeURIComponent(data.query);
            const botReply = document.createElement('div');
            botReply.className = 'flex items-start gap-2.5 max-w-[96%]';
            botReply.innerHTML = `
                <div class="w-7 h-7 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-amber-200/70 shadow-sm text-xs sm:text-sm text-gray-800 space-y-2.5 w-full">
                    <div class="flex items-center justify-between pb-1.5 border-b border-gray-100">
                        <span class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-1.5 text-amber-700">
                            <i class="fas fa-circle-exclamation text-amber-500"></i> ${data.title}
                        </span>
                        <span class="text-[9px] text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded font-bold">Direct Assistance</span>
                    </div>
                    
                    <p class="leading-relaxed text-gray-700">${data.answer}</p>

                    <!-- Direct Contact Card -->
                    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-purple-950 rounded-2xl p-4 text-white space-y-3 shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-purple-300">StayNest Direct Support</span>
                            <span class="text-[9px] bg-green-500/20 text-green-300 px-2 py-0.5 rounded border border-green-500/30">Available 24/7</span>
                        </div>
                        
                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300"><i class="fas fa-phone text-brand mr-1.5"></i> Phone Helpline:</span>
                                <a href="tel:+919876543210" class="font-bold text-white hover:text-brand transition">+91 98765 43210</a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300"><i class="fab fa-whatsapp text-emerald-400 mr-1.5"></i> WhatsApp Desk:</span>
                                <a href="https://wa.me/919876543210?text=Hi%20StayNest%20Team%2C%20I%20am%20looking%20for%20a%20PG%20matching%3A%20${encodedQuery}" target="_blank" class="font-bold text-emerald-400 hover:underline">+91 98765 43210</a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300"><i class="fas fa-envelope text-blue-400 mr-1.5"></i> Email Support:</span>
                                <a href="mailto:support@staynest.com" class="font-medium text-gray-200">support@staynest.com</a>
                            </div>
                        </div>

                        <!-- Direct CTA Buttons -->
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="tel:+919876543210" class="bg-brand hover:bg-brand-dark text-white font-bold py-2 px-3 rounded-xl text-xs text-center flex items-center justify-center gap-1.5 shadow-sm transition">
                                <i class="fas fa-phone-alt text-[10px]"></i> Call Helpline
                            </a>
                            <a href="https://wa.me/919876543210?text=Hi%20StayNest%20Team%2C%20I%20am%20looking%20for%20a%20PG%20matching%3A%20${encodedQuery}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-3 rounded-xl text-xs text-center flex items-center justify-center gap-1.5 shadow-sm transition">
                                <i class="fab fa-whatsapp text-sm"></i> WhatsApp Us
                            </a>
                        </div>
                    </div>

                    <!-- Alternate Popular Stays -->
                    ${data.suggestedPgs ? `
                        <div class="pt-2">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Meanwhile, check these popular verified stays:</p>
                            <div class="ai-chat-slider">
                                ${data.suggestedPgs.map(p => `
                                    <div class="ai-slider-card flex flex-col justify-between">
                                        <div>
                                            <div class="relative h-24 overflow-hidden bg-gray-100">
                                                <img src="${p.img}" alt="${p.title}" class="w-full h-full object-cover">
                                                <span class="absolute top-1.5 left-1.5 ${p.badgeBg} text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full">
                                                    ${p.badge}
                                                </span>
                                            </div>
                                            <div class="p-2">
                                                <p class="font-bold text-xs text-gray-900 truncate">${p.title}</p>
                                                <p class="text-[10px] text-gray-500 truncate">${p.loc}</p>
                                            </div>
                                        </div>
                                        <div class="p-2 pt-1 border-t border-gray-100 flex items-center justify-between">
                                            <span class="text-xs font-black text-gray-900">${p.price}</span>
                                            <a href="${p.detail_url}" class="bg-brand text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">View</a>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}

                    <div class="pt-2 border-t border-gray-100 flex justify-between items-center text-[11px]">
                        <a href="{{ route('user.contact') }}" class="text-brand font-bold flex items-center gap-1 hover:underline">
                            <i class="fas fa-envelope-open-text"></i> Open Contact Form &rarr;
                        </a>
                        <a href="{{ route('user.search') }}" class="text-gray-500 hover:text-gray-700">
                            Clear filters
                        </a>
                    </div>
                </div>
            `;
            chat.appendChild(botReply);
            chat.scrollTop = chat.scrollHeight;
            return;
        }

        // Build Steps HTML
        let stepsHtml = '';
        if (data.steps && data.steps.length) {
            stepsHtml = `
                <div class="bg-gray-50 rounded-xl p-3 space-y-1.5 text-xs text-gray-700 border border-gray-100 mt-2.5">
                    ${data.steps.map(s => `<p>${s}</p>`).join('')}
                </div>
            `;
        }

        // Build Action Links
        let linksHtml = '';
        if (data.links && data.links.length) {
            linksHtml = `
                <div class="flex flex-wrap gap-2 pt-2.5 border-t border-gray-100 mt-2.5">
                    ${data.links.map(l => `
                        <a href="${l.url}" class="inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-600 hover:text-white text-purple-700 text-xs font-bold px-3 py-1.5 rounded-xl transition border border-purple-200">
                            <i class="fas fa-${l.icon} text-[10px]"></i> ${l.text}
                        </a>
                    `).join('')}
                </div>
            `;
        } else if (data.type === 'property_carousel') {
            linksHtml = `
                <div class="flex flex-wrap gap-2 pt-2.5 border-t border-gray-100 mt-2.5">
                    <a href="${data.mapUrl || '{{ route("user.location") }}'}" class="inline-flex items-center gap-1.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition shadow-xs">
                        <i class="fas fa-map-location-dot text-[10px]"></i> View on Interactive Map
                    </a>
                    <a href="${data.searchUrl}" class="inline-flex items-center gap-1.5 bg-purple-50 hover:bg-purple-600 hover:text-white text-purple-700 text-xs font-bold px-3 py-1.5 rounded-xl transition border border-purple-200">
                        <i class="fas fa-filter text-[10px]"></i> Open in Search Grid
                    </a>
                </div>
            `;
        }

        // Render full bot message bubble
        const botReply = document.createElement('div');
        botReply.className = 'flex items-start gap-2.5 max-w-[96%]';
        botReply.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-brand flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
                <i class="fas fa-robot"></i>
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-gray-100 shadow-sm text-xs sm:text-sm text-gray-800 space-y-2 w-full">
                <div class="flex items-center justify-between pb-1.5 border-b border-gray-100">
                    <span class="font-bold text-gray-900 text-xs sm:text-sm flex items-center gap-1">
                        <i class="fas fa-sparkles text-purple-600"></i> ${data.title}
                    </span>
                    <span class="text-[9px] text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded font-bold">Verified AI Match</span>
                </div>
                ${filtersHtml}
                <p class="leading-relaxed text-gray-700">${data.answer}</p>
                ${carouselHtml}
                ${stepsHtml}
                ${linksHtml}
            </div>
        `;
        chat.appendChild(botReply);
        chat.scrollTop = chat.scrollHeight;
    }, 450);
}
</script>
