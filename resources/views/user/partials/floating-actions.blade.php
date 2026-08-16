<!-- ===================== FIXED FLOATING ACTIONS (MAP VIEW & AI CHATBOT) ===================== -->
@if(request()->routeIs('user.home') || request()->is('/'))
<div class="fixed bottom-20 md:bottom-8 right-4 md:right-8 z-40 flex flex-col items-end gap-3 pointer-events-none">

    <!-- 1. Map View Floating Pill Button (Desktop Only) -->
    <a href="{{ route('user.location') }}" 
       class="pointer-events-auto hidden md:flex items-center gap-2 bg-gray-900/90 hover:bg-gray-900 text-white px-4 py-2.5 rounded-full shadow-2xl backdrop-blur-md border border-white/20 tap-effect transition-all duration-300 hover:scale-105 hover:shadow-brand/20"
       title="Open Interactive Map">
        <span class="w-7 h-7 rounded-full bg-brand flex items-center justify-center text-white text-xs shadow-sm">
            <i class="fas fa-map-location-dot"></i>
        </span>
        <span class="text-xs font-bold tracking-wide pr-1">Map View</span>
    </a>

    <!-- 2. AI Chatbot Floating Button with Glowing Ring -->
    <button type="button" 
            onclick="toggleAiChat()" 
            class="pointer-events-auto relative group flex items-center gap-2.5 bg-gradient-to-r from-brand to-teal-600 hover:from-brand-dark hover:to-teal-700 text-white px-4 py-2.5 rounded-full shadow-2xl border border-white/30 tap-effect transition-all duration-300 hover:scale-105 hover:shadow-brand/40"
            title="Ask StayNest Smart AI">
        <!-- Glowing Pulse Ping -->
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-yellow-400"></span>
        </span>
        <span class="w-7 h-7 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-xs">
            <i class="fas fa-wand-magic-sparkles text-yellow-300"></i>
        </span>
        <span class="text-xs font-black tracking-wide pr-1">AI Assistant</span>
    </button>

</div>

<!-- ===================== AI CHATBOT ADVANCED KNOWLEDGE & EXPANDED MODAL ===================== -->
<div id="aiChatModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <!-- Backdrop overlay -->
    <div onclick="toggleAiChat()" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Chat Container (Spacious Width: 560px on desktop) -->
    <div class="absolute bottom-0 md:bottom-6 right-0 md:right-6 w-full sm:w-[500px] md:w-[540px] lg:w-[580px] max-h-[94vh] md:max-h-[720px] h-[680px] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden z-10 animate-slide-up">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-900 via-teal-950 to-brand-dark p-4 text-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-brand to-teal-400 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-robot text-base"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-gray-900 rounded-full"></span>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <h3 class="font-bold text-sm sm:text-base leading-tight">StayNest AI Assistant</h3>
                        <span class="bg-yellow-400/25 text-yellow-300 text-[9px] font-extrabold px-1.5 py-0.2 rounded border border-yellow-400/30">HUMAN NLP v4</span>
                    </div>
                    <p class="text-[11px] text-teal-200/80">Conversational Multi-City & Stay Matcher</p>
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
            <button type="button" onclick="askAiPrompt('Girls PG in Noida under 5k with Food')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                👧 Girls PG Noida
            </button>
            <button type="button" onclick="askAiPrompt('Safe Girls PG in Pune near Viman Nagar with Food')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                📍 Pune Stays
            </button>
            <button type="button" onclick="askAiPrompt('Boys PG in Hyderabad Hitec City under 8k')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                📍 Hyderabad Stays
            </button>
            <button type="button" onclick="askAiPrompt('Co-Living in Bangalore Koramangala with Gym')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                🏢 Co-Living
            </button>
            <button type="button" onclick="askAiPrompt('How does Zero Brokerage work on StayNest?')" class="text-[11px] font-bold bg-white text-gray-700 hover:text-brand hover:border-brand px-2.5 py-1 rounded-lg border border-gray-200 shadow-xs whitespace-nowrap tap-effect">
                💰 Zero Brokerage
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
                    <p class="font-bold text-gray-900 text-sm">Hello! I am your StayNest AI Assistant 👋</p>
                    <p class="leading-relaxed text-gray-700">You can ask me in natural human language about <strong>any city in India</strong> (Noida, Bangalore, Pune, Hyderabad, Delhi, Jaipur, Mumbai, Kota, etc.) and <strong>any type of accommodation</strong>!</p>
                    <div class="pt-1 flex flex-wrap gap-1.5 text-[10px]">
                        <span class="bg-pink-50 text-pink-700 font-bold px-2.5 py-1 rounded-lg border border-pink-200/60">👧 Girls PGs</span>
                        <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200/60">👦 Boys PGs</span>
                        <span class="bg-purple-50 text-purple-700 font-bold px-2.5 py-1 rounded-lg border border-purple-200/60">🏢 Co-Living & Studios</span>
                    </div>
                </div>
            </div>

            <!-- Popular Prompt Pills -->
            <div id="aiQuickChips" class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-xs">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                    <i class="fas fa-bolt text-yellow-500"></i> Try Asking Natural Queries
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                    <button type="button" onclick="askAiPrompt('Can you find safe Girls PG in Pune with food under 7k?')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Girls PG in Pune under ₹7k
                    </button>
                    <button type="button" onclick="askAiPrompt('Looking for Boys PG in Hyderabad near Hitec City with AC')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Boys PG in Hyderabad (Hitec)
                    </button>
                    <button type="button" onclick="askAiPrompt('Best luxury Co-Living in Mumbai with Gym & WiFi')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        🏢 Co-Living in Mumbai
                    </button>
                    <button type="button" onclick="askAiPrompt('Affordable student stays in Jaipur under 6k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                        📍 Stays in Jaipur under ₹6k
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
                           placeholder="Ask: 'Girls PG in Pune with food' or 'Boys room Hyderabad'..." 
                           class="w-full bg-gray-100 focus:bg-white text-xs sm:text-sm text-gray-900 rounded-2xl pl-4 pr-9 py-3.5 border border-transparent focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition"
                           autocomplete="off">
                    <span class="absolute right-3.5 top-3.5 text-gray-400 text-sm">
                        <i class="fas fa-sparkles text-brand"></i>
                    </span>
                </div>
                <button type="submit" class="w-11 h-11 rounded-2xl bg-brand hover:bg-brand-dark text-white flex items-center justify-center shadow-md shadow-brand/30 transition tap-effect flex-shrink-0">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </form>
            <div class="flex items-center justify-between text-[10px] text-gray-400 mt-2 px-1">
                <span><i class="fas fa-shield-halved text-brand"></i> 100% Zero Brokerage Verified</span>
                <span>Powered by StayNest Human NLP</span>
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
    /* Single Clean Vertical Scrollbar for Chat */
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

    /* Horizontal Slider inside Chatbot */
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
        flex: 0 0 220px;
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
// ===================== STAYNEST EXTENSIVE PROPERTY DATABASE =====================
const STAYNEST_PROPERTY_CATALOG = [
    // Girls PGs
    { id: 1, title: 'Tulip Budget Girls PG', type: 'GIRLS', typeClass: 'bg-pink-50 text-pink-600', city: 'Noida', loc: 'Sector 62, Noida', price: '₹4,999', rating: '4.8★', badge: 'Under 5K', badgeBg: 'bg-emerald-500', img: 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['WiFi', 'Food', 'Security'], budget: 4999 },
    { id: 2, title: 'Sector 22 Girls Home', type: 'GIRLS', typeClass: 'bg-pink-50 text-pink-600', city: 'Noida', loc: 'Sector 22, Noida', price: '₹4,800', rating: '4.7★', badge: 'Budget Pick', badgeBg: 'bg-emerald-600', img: 'https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Food Included', 'WiFi'], budget: 4800 },
    { id: 3, title: 'Green Valley Girls Living', type: 'GIRLS', typeClass: 'bg-pink-50 text-pink-600', city: 'Noida', loc: 'Sector 62, Noida', price: '₹7,500', rating: '4.8★', badge: 'Verified', badgeBg: 'bg-emerald-500', img: 'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Food', 'WiFi', 'AC'], budget: 7500 },
    { id: 4, title: 'Aura Women\'s Stay', type: 'GIRLS', typeClass: 'bg-pink-50 text-pink-600', city: 'Bangalore', loc: 'Indiranagar, BLR', price: '₹9,999', rating: '4.9★', badge: '100% Safe', badgeBg: 'bg-emerald-500', img: 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['CCTV', '3 Meals', 'Security'], budget: 9999 },
    { id: 5, title: 'Koramangala Hub Girls PG', type: 'GIRLS', typeClass: 'bg-pink-50 text-pink-600', city: 'Bangalore', loc: 'Koramangala, BLR', price: '₹8,500', rating: '4.8★', badge: 'Guest Fav', badgeBg: 'bg-amber-500', img: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Cleaning', 'Food', 'WiFi'], budget: 8500 },
    { id: 6, title: 'Saket Safe Girls Stay', type: 'GIRLS', typeClass: 'bg-pink-50 text-pink-600', city: 'Delhi', loc: 'Saket, Delhi', price: '₹5,800', rating: '4.7★', badge: 'Near Metro', badgeBg: 'bg-indigo-600', img: 'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Metro 0.3km', 'Security'], budget: 5800 },
    { id: 7, title: 'Laxmi Nagar Budget Girls PG', type: 'GIRLS', typeClass: 'bg-pink-50 text-pink-600', city: 'Delhi', loc: 'Laxmi Nagar, Delhi', price: '₹4,500', rating: '4.6★', badge: 'Under 5K', badgeBg: 'bg-emerald-500', img: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Meals', 'WiFi'], budget: 4500 },

    // Boys PGs
    { id: 8, title: 'Sector 62 Budget Boys PG', type: 'BOYS', typeClass: 'bg-blue-50 text-blue-600', city: 'Noida', loc: 'Sector 62, Noida', price: '₹4,900', rating: '4.6★', badge: 'Under 5K', badgeBg: 'bg-emerald-500', img: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['WiFi', '3 Meals'], budget: 4900 },
    { id: 9, title: 'Sunrise Premium Boys PG', type: 'BOYS', typeClass: 'bg-blue-50 text-blue-600', city: 'Noida', loc: 'Sector 62, Noida', price: '₹7,500', rating: '4.8★', badge: 'Verified', badgeBg: 'bg-emerald-500', img: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['WiFi', '3 Meals', 'AC'], budget: 7500 },
    { id: 10, title: 'Elite Boys Residency', type: 'BOYS', typeClass: 'bg-blue-50 text-blue-600', city: 'Noida', loc: 'Sector 18, Noida', price: '₹8,500', rating: '4.8★', badge: 'Trending', badgeBg: 'bg-blue-600', img: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Gym', 'AC', 'Food'], budget: 8500 },
    { id: 11, title: 'Pocket Friendly Boys Stay', type: 'BOYS', typeClass: 'bg-blue-50 text-blue-600', city: 'Delhi', loc: 'Laxmi Nagar, Delhi', price: '₹4,500', rating: '4.5★', badge: 'Under 5K', badgeBg: 'bg-emerald-500', img: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['WiFi', 'Meals'], budget: 4500 },
    { id: 12, title: 'Metro Heights Boys PG', type: 'BOYS', typeClass: 'bg-blue-50 text-blue-600', city: 'Delhi', loc: 'Saket, Delhi', price: '₹8,500', rating: '4.7★', badge: 'Near Metro', badgeBg: 'bg-indigo-600', img: 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['WiFi', 'AC', 'Gym'], budget: 8500 },
    { id: 13, title: 'Tech Park Boys PG', type: 'BOYS', typeClass: 'bg-blue-50 text-blue-600', city: 'Bangalore', loc: 'Electronic City, BLR', price: '₹9,500', rating: '4.9★', badge: 'Top Rated', badgeBg: 'bg-amber-500', img: 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Gaming', 'Gym', 'Meals'], budget: 9500 },

    // Co-Living Stays
    { id: 14, title: 'Urban Nest Co-Living', type: 'CO-ED', typeClass: 'bg-purple-50 text-purple-600', city: 'Bangalore', loc: 'HSR Layout, BLR', price: '₹11,500', rating: '4.7★', badge: 'Community', badgeBg: 'bg-purple-600', img: 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Gym', 'WiFi', 'Gaming'], budget: 11500 },
    { id: 15, title: 'Royal Suites Co-Living', type: 'CO-ED', typeClass: 'bg-purple-50 text-purple-600', city: 'Mumbai', loc: 'Andheri West, Mumbai', price: '₹13,500', rating: '4.8★', badge: 'Luxury', badgeBg: 'bg-amber-500', img: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['AC', 'Gym', 'WiFi'], budget: 13500 },
    { id: 16, title: 'Cyber City Co-Living', type: 'CO-ED', typeClass: 'bg-purple-50 text-purple-600', city: 'Gurugram', loc: 'Cyber City, HR', price: '₹12,000', rating: '4.9★', badge: 'Top Rated', badgeBg: 'bg-blue-600', img: 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', am: ['Gym', 'Work Desk', 'WiFi'], budget: 12000 }
];

// Comprehensive Indian Cities List
const INDIAN_CITIES = [
    { name: 'Noida', aliases: ['noida', 'greater noida', 'sec 62', 'sector 62', 'sec 18'], hubs: 'Sector 62, Sector 18 & Knowledge Park' },
    { name: 'Bangalore', aliases: ['bangalore', 'bengaluru', 'koramangala', 'indiranagar', 'hsr', 'electronic city', 'whitefield', 'bellandur', 'marathahalli'], hubs: 'Koramangala, Indiranagar, HSR & Electronic City' },
    { name: 'Delhi', aliases: ['delhi', 'new delhi', 'saket', 'laxmi nagar', 'north campus', 'south ex', 'hauz khas', 'dwarka'], hubs: 'Saket, North Campus, South Ex & Laxmi Nagar' },
    { name: 'Pune', aliases: ['pune', 'viman nagar', 'hinjewadi', 'kothrud', 'wakad', 'baner', 'hadapsar', 'kharadi', 'magarpatta'], hubs: 'Viman Nagar, Hinjewadi, Baner & Kharadi' },
    { name: 'Hyderabad', aliases: ['hyderabad', 'hitec city', 'madhapur', 'gachibowli', 'kondapur', 'kukatpally', 'ameerpet'], hubs: 'Hitec City, Gachibowli, Madhapur & Kondapur' },
    { name: 'Mumbai', aliases: ['mumbai', 'andheri', 'bandra', 'powai', 'navi mumbai', 'thane', 'dadar', 'juhu'], hubs: 'Andheri West, Powai, Bandra & Thane' },
    { name: 'Gurugram', aliases: ['gurugram', 'gurgaon', 'cyber city', 'dlf phase', 'sohna road', 'golf course road', 'sector 29'], hubs: 'Cyber City, DLF Phase 1-3 & Sohna Road' },
    { name: 'Jaipur', aliases: ['jaipur', 'malviya nagar', 'mansarovar', 'vaishali nagar', 'raja park', 'c scheme'], hubs: 'Malviya Nagar, Mansarovar & Raja Park' },
    { name: 'Chennai', aliases: ['chennai', 'omr', 'velachery', 'guindy', 'anna nagar', 'thoraipakkam', 'adyar'], hubs: 'OMR Tech Corridor, Velachery & Anna Nagar' },
    { name: 'Kolkata', aliases: ['kolkata', 'salt lake', 'new town', 'park street', 'ballygunge', 'jadavpur'], hubs: 'Salt Lake Sector 5, New Town & Park Street' },
    { name: 'Chandigarh', aliases: ['chandigarh', 'mohali', 'panchkula', 'sector 17', 'sector 35'], hubs: 'Mohali Phase 7, Sector 35 & IT Park' },
    { name: 'Indore', aliases: ['indore', 'vijay nagar', 'bhanwarkuan', 'palasia', 'geeta bhawan'], hubs: 'Vijay Nagar, Bhanwarkuan & Bhawarkua' },
    { name: 'Kota', aliases: ['kota', 'landmark city', 'vigyan nagar', 'talwandi', 'mahaveer nagar', 'indira vihar'], hubs: 'Landmark City, Vigyan Nagar & Talwandi' },
    { name: 'Lucknow', aliases: ['lucknow', 'gomti nagar', 'hazratganj', 'aliganj', 'indira nagar'], hubs: 'Gomti Nagar, Hazratganj & Aliganj' },
    { name: 'Dehradun', aliases: ['dehradun', 'rajpur road', 'karanpur', 'ballupur', 'bidholi'], hubs: 'Rajpur Road, Karanpur & Bidholi Uni Area' },
    { name: 'Ahmedabad', aliases: ['ahmedabad', 'sg highway', 'navrangpura', 'vastrapur', 'bodakdev'], hubs: 'SG Highway, Navrangpura & Vastrapur' }
];

// Helper to generate verified cards for ANY Indian city
function generateCityProperties(cityName, genderType, budgetVal, amenities) {
    const isGirls = genderType === 'GIRLS';
    const isBoys = genderType === 'BOYS';
    const typeLabel = isGirls ? 'GIRLS' : (isBoys ? 'BOYS' : 'CO-ED');
    const typeClass = isGirls ? 'bg-pink-50 text-pink-600' : (isBoys ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600');
    
    let basePrice = budgetVal ? Math.min(budgetVal, 8500) : (isGirls ? 6800 : 6200);
    if (basePrice < 4500) basePrice = 4999;

    const dummyImgs = [
        'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
        'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
        'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
        'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
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
            badge: 'Verified Choice',
            badgeBg: 'bg-emerald-500',
            img: isGirls ? dummyImgs[1] : dummyImgs[0],
            am: ['WiFi', '3 Meals', 'Security'],
            budget: basePrice
        },
        {
            id: 'dyn_2',
            title: `Royal ${typeLabel} Comfort Living`,
            type: typeLabel,
            typeClass: typeClass,
            city: cityName,
            loc: `Near Metro / IT Hub, ${cityName}`,
            price: `₹${(basePrice + 1200).toLocaleString('en-IN')}`,
            rating: '4.9★',
            badge: 'Top Rated',
            badgeBg: 'bg-blue-600',
            img: isGirls ? dummyImgs[3] : dummyImgs[2],
            am: ['AC Room', 'WiFi', 'Gym'],
            budget: basePrice + 1200
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
            budget: Math.max(4500, basePrice - 800)
        }
    ];
}

// ===================== ADVANCED NLP INTENT & CONVERSATIONAL ENGINE =====================
function processAiQuery(query) {
    const q = query.toLowerCase().trim();

    // ----------------- 1. CONVERSATIONAL & SOCIAL GREETINGS -----------------
    const isExactHi = /^(hi|hello|hey|heyy|hii|namaste|hola|sup|yo|greetings)\b/i.test(q);
    if (isExactHi && q.split(' ').length <= 3) {
        return {
            type: 'info',
            title: 'Hello! Welcome to StayNest 👋',
            answer: `Hello there! I am your personal AI Stay Concierge. How can I help you today? You can ask me for <strong>any city in India</strong> (Noida, Bangalore, Pune, Hyderabad, Delhi, Jaipur, etc.) or specific requirements:`,
            steps: [
                '• <strong>Find Stays:</strong> "Safe Girls PG in Pune with food" or "Boys PG in Hyderabad under 8k"',
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

    // ----------------- 2. FAREWELLS / GOODBYES -----------------
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

    // ----------------- 3. GRATITUDE / THANKS -----------------
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

    // ----------------- 4. SMALL TALK / STATUS / BOT IDENTITY -----------------
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
            answer: "I am <strong>StayNest AI</strong> — India's smart accommodation assistant. We help students and professionals find 100% verified, zero-brokerage PGs and co-living spaces with transparent pricing, home-cooked food, and high safety standards.",
            links: [
                { text: 'Learn More About Us', url: '{{ route("user.about") }}', icon: 'circle-info' },
                { text: 'Browse Stays', url: '{{ route("user.search") }}', icon: 'search' }
            ]
        };
    }

    if (/^(ok|okay|alright|cool|sure|got it|done)\b/i.test(q) && q.split(' ').length <= 2) {
        return {
            type: 'info',
            title: "Great! Let's Find Your Stay 🚀",
            answer: "Tell me your requirements whenever you're ready! For example: <em>'Girls PG in Pune under 7k'</em> or <em>'Boys PG in Hyderabad near Hitec City'</em>.",
            links: [
                { text: 'Search Stays', url: '{{ route("user.search") }}', icon: 'search' }
            ]
        };
    }

    // ----------------- 5. DETAILED CITY, GENDER & BUDGET PARSER -----------------
    let detectedFilters = [];

    // Gender / Type Detection
    let isGirls = q.includes('girl') || q.includes('female') || q.includes('women') || q.includes('ladki') || q.includes('ladies') || q.includes('women\'s');
    let isBoys = q.includes('boy') || q.includes('male') || q.includes('men') || q.includes('ladka') || q.includes('gent') || q.includes('bachelor');
    let isColiving = q.includes('coliving') || q.includes('co-living') || q.includes('coed') || q.includes('unisex') || q.includes('couple') || q.includes('studio') || q.includes('1rk') || q.includes('flat');

    let genderCategory = isGirls ? 'GIRLS' : (isBoys ? 'BOYS' : (isColiving ? 'CO-ED' : 'ALL'));

    if (isGirls) detectedFilters.push('👧 Girls Only');
    else if (isBoys) detectedFilters.push('👦 Boys Only');
    else if (isColiving) detectedFilters.push('🏢 Co-Living');

    // Advanced City Detection across All Indian Metros & Student Hubs
    let matchedCity = null;
    for (const c of INDIAN_CITIES) {
        const isMatched = c.aliases.some(alias => q.includes(alias));
        if (isMatched) {
            matchedCity = c.name;
            detectedFilters.push('📍 ' + c.name);
            break;
        }
    }

    // Dynamic Budget Extraction (5k, 5000, under 5k, below 8000, 10k, 12k, etc.)
    let parsedBudget = null;
    const kMatch = q.match(/(\d+(?:\.\d+)?)\s*k\b/i);
    if (kMatch) {
        parsedBudget = Math.round(parseFloat(kMatch[1]) * 1000);
    } else {
        const numMatch = q.match(/(?:under|below|max|upto|around|approx|\b)\s*(\d{4,5})\b/i);
        if (numMatch) {
            parsedBudget = parseInt(numMatch[1]);
        }
    }

    if (parsedBudget) {
        detectedFilters.push('💰 Under ₹' + parsedBudget.toLocaleString('en-IN'));
    }

    // Amenities Detection
    if (q.includes('ac') || q.includes('air conditioner')) detectedFilters.push('❄️ AC');
    if (q.includes('food') || q.includes('meal') || q.includes('khana')) detectedFilters.push('🍽️ 3 Meals');
    if (q.includes('wifi') || q.includes('internet')) detectedFilters.push('📶 Free WiFi');
    if (q.includes('gym') || q.includes('fitness')) detectedFilters.push('🏋️ Gym');

    // Check for general stay keywords
    const hasStayIntent = isBoys || isGirls || isColiving || matchedCity || parsedBudget || 
        q.includes('pg') || q.includes('hostel') || q.includes('stay') || q.includes('room') || 
        q.includes('rent') || q.includes('coliving') || q.includes('flat') || q.includes('residency') || 
        q.includes('accommodation') || q.includes('dorm') || q.includes('sharing') || q.includes('find') || q.includes('show');

    // ----------------- 6. DYNAMIC & CATALOG PROPERTY MATCHING -----------------
    if (hasStayIntent) {
        let finalPgs = [];

        // If City is in our catalog (Noida, Bangalore, Delhi, Mumbai, Gurugram)
        if (matchedCity) {
            let filteredCatalog = STAYNEST_PROPERTY_CATALOG.filter(p => p.city.toLowerCase() === matchedCity.toLowerCase());
            
            if (genderCategory !== 'ALL') {
                filteredCatalog = filteredCatalog.filter(p => p.type === genderCategory);
            }

            if (parsedBudget && filteredCatalog.length > 0) {
                const budgetFiltered = filteredCatalog.filter(p => p.budget <= (parsedBudget + 300));
                if (budgetFiltered.length > 0) filteredCatalog = budgetFiltered;
            }

            if (filteredCatalog.length > 0) {
                finalPgs = filteredCatalog;
            } else {
                // Dynamically generate verified properties tailored to that exact city, gender, and budget!
                finalPgs = generateCityProperties(matchedCity, genderCategory, parsedBudget);
            }
        } else {
            // General query with gender or budget
            let filteredCatalog = [...STAYNEST_PROPERTY_CATALOG];
            if (genderCategory !== 'ALL') {
                filteredCatalog = filteredCatalog.filter(p => p.type === genderCategory);
            }
            if (parsedBudget) {
                const budgetFiltered = filteredCatalog.filter(p => p.budget <= (parsedBudget + 300));
                if (budgetFiltered.length > 0) filteredCatalog = budgetFiltered;
            }
            finalPgs = filteredCatalog.length > 0 ? filteredCatalog : STAYNEST_PROPERTY_CATALOG.slice(0, 4);
        }

        // Build Natural Conversational Human-like Answer
        let cityNameDisplay = matchedCity || 'your requested location';
        let genderNameDisplay = isGirls ? 'Female / Girls' : (isBoys ? 'Boys' : (isColiving ? 'Co-Living' : 'Verified'));
        
        let humanIntro = `I've found great <strong>${genderNameDisplay}</strong> stays in <strong>${cityNameDisplay}</strong> for you! All properties below feature direct landlord connections, 100% Zero Brokerage, and verified amenities:`;
        
        return {
            type: 'property_carousel',
            title: `Verified Stays in ${cityNameDisplay} ✨`,
            answer: humanIntro,
            filters: detectedFilters,
            pgs: finalPgs,
            searchUrl: '{{ route("user.search") }}?q=' + encodeURIComponent(query)
        };
    }

    // ----------------- 7. ZERO BROKERAGE INFO -----------------
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

    // ----------------- 8. LIST PROPERTY INFO -----------------
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
                { text: 'Owner Pricing Plans', url: '{{ route("user.pricing") }}', icon: 'tags' },
                { text: 'Broker Portal', url: '{{ route("broker.login") }}', icon: 'user-tie' }
            ]
        };
    }

    // ----------------- 9. BOOKING & VISIT SCHEDULING -----------------
    if (q.includes('book') || q.includes('visit') || q.includes('schedule') || q.includes('token') || q.includes('tour') || q.includes('how to')) {
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

    // ----------------- 10. CONTACT & HELPLINE -----------------
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

    // ----------------- 11. UNMATCHED / NO RESULTS FALLBACK -----------------
    return {
        type: 'no_results_contact',
        title: `No Results Found for "${query}" 🔍`,
        answer: `We could not find any matching verified stays or answers for <strong>"${query}"</strong>. Please check your spelling or contact our 24/7 support desk directly for personalized assistance:`,
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
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('aiChatInput').focus(), 150);
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function clearAiChat() {
    const chat = document.getElementById('aiChatMessages');
    chat.innerHTML = `
        <div class="flex items-start gap-2.5 max-w-[95%]">
            <div class="w-7 h-7 rounded-xl bg-brand flex items-center justify-center text-white text-xs flex-shrink-0 shadow-sm mt-0.5">
                <i class="fas fa-robot"></i>
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-gray-100 shadow-xs text-xs text-gray-800 space-y-2.5">
                <p class="font-bold text-gray-900 text-sm">Hello! I am your StayNest AI Assistant 👋</p>
                <p class="leading-relaxed text-gray-700">You can ask me in natural human language about <strong>any city in India</strong> (Noida, Bangalore, Pune, Hyderabad, Delhi, Jaipur, etc.) and <strong>any type of accommodation</strong>!</p>
                <div class="pt-1 flex flex-wrap gap-1.5 text-[10px]">
                    <span class="bg-pink-50 text-pink-700 font-bold px-2.5 py-1 rounded-lg border border-pink-200/60">👧 Girls PGs</span>
                    <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200/60">👦 Boys PGs</span>
                    <span class="bg-purple-50 text-purple-700 font-bold px-2.5 py-1 rounded-lg border border-purple-200/60">🏢 Co-Living & Studios</span>
                    <span class="bg-emerald-50 text-emerald-700 font-bold px-2.5 py-1 rounded-lg border border-emerald-200/60">🛡️ 100% Zero Brokerage</span>
                </div>
            </div>
        </div>
        <div id="aiQuickChips" class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-xs">
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                <i class="fas fa-bolt text-yellow-500"></i> Try Asking Natural Queries
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                <button type="button" onclick="askAiPrompt('Can you find safe Girls PG in Pune with food under 7k?')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Girls PG in Pune under ₹7k
                </button>
                <button type="button" onclick="askAiPrompt('Looking for Boys PG in Hyderabad near Hitec City with AC')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Boys PG in Hyderabad (Hitec)
                </button>
                <button type="button" onclick="askAiPrompt('Best luxury Co-Living in Mumbai with Gym & WiFi')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    🏢 Co-Living in Mumbai
                </button>
                <button type="button" onclick="askAiPrompt('Affordable student stays in Jaipur under 6k')" class="text-left bg-gray-50 hover:bg-brand-light hover:text-brand p-2.5 rounded-xl border border-gray-200/80 text-[11px] font-semibold text-gray-700 transition">
                    📍 Stays in Jaipur under ₹6k
                </button>
            </div>
        </div>
    `;
}

function askAiPrompt(prompt) {
    document.getElementById('aiChatInput').value = prompt;
    handleAiSubmit(new Event('submit'));
}

function handleAiSubmit(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('aiChatInput');
    const query = input.value.trim();
    if (!query) return;

    const chat = document.getElementById('aiChatMessages');

    // 1. Append user message bubble
    const userMsg = document.createElement('div');
    userMsg.className = 'flex justify-end';
    userMsg.innerHTML = `
        <div class="bg-gradient-to-r from-brand to-teal-600 text-white p-3.5 rounded-2xl rounded-tr-sm max-w-[85%] shadow-sm text-xs sm:text-sm font-medium leading-relaxed">
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
            <span class="w-1.5 h-1.5 bg-brand rounded-full animate-bounce"></span>
            <span class="w-1.5 h-1.5 bg-brand rounded-full animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-1.5 h-1.5 bg-brand rounded-full animate-bounce [animation-delay:0.4s]"></span>
        </div>
    `;
    chat.appendChild(typing);
    chat.scrollTop = chat.scrollHeight;

    // 3. Process with AI Knowledge & Multiple-PG Slider Response
    setTimeout(() => {
        const el = document.getElementById(typingId);
        if (el) el.remove();

        const data = processAiQuery(query);

        // Build Filter Chips
        let filtersHtml = '';
        if (data.filters && data.filters.length) {
            filtersHtml = `
                <div class="flex flex-wrap gap-1.5 mb-2.5">
                    ${data.filters.map(f => `<span class="bg-teal-50 border border-brand/20 text-brand text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-md">${f}</span>`).join('')}
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
                                    <div class="relative h-28 overflow-hidden">
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
                                            ${p.am.slice(0, 2).map(a => `<span class="text-[9px] bg-gray-50 border border-gray-100 px-1.5 py-0.5 rounded text-gray-600">${a}</span>`).join('')}
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2.5 pt-1.5 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs sm:text-sm font-black text-gray-900">${p.price}<span class="text-[9px] font-normal text-gray-400">/m</span></span>
                                    <a href="{{ route('user.detail') }}" class="bg-brand hover:bg-brand-dark text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition shadow-xs">View</a>
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
                    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-teal-950 rounded-2xl p-4 text-white space-y-3 shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-teal-300">StayNest Direct Support</span>
                            <span class="text-[9px] bg-green-500/20 text-green-300 px-2 py-0.5 rounded border border-green-500/30">Available Now</span>
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
                                <i class="fas fa-phone-alt text-[10px]"></i> Call Hotline
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
                                            <div class="relative h-24 overflow-hidden">
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
                                            <a href="{{ route('user.detail') }}" class="bg-brand text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">View</a>
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
                        <a href="${l.url}" class="inline-flex items-center gap-1.5 bg-brand-light hover:bg-brand hover:text-white text-brand text-xs font-bold px-3 py-1.5 rounded-xl transition border border-brand/20">
                            <i class="fas fa-${l.icon} text-[10px]"></i> ${l.text}
                        </a>
                    `).join('')}
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
                    <span class="font-bold text-gray-900 text-xs sm:text-sm">${data.title}</span>
                    <span class="text-[9px] text-brand bg-brand-light px-2 py-0.5 rounded font-bold">Verified Info</span>
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
    }, 550);
}
</script>
@endif
