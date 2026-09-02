{{-- ============================================================================== --}}
{{-- STAYNEST ROOMMATE DIRECT 1-TO-1 HUMAN CHAT MODAL (WHATSAPP THEME)             --}}
{{-- ============================================================================== --}}
<div id="whatsappChatModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    {{-- Backdrop overlay --}}
    <div onclick="closeWhatsAppChat()" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity cursor-pointer"></div>

    {{-- Chat Container (Slide-up modal with responsive dimensions) --}}
    <div class="absolute bottom-0 md:bottom-6 right-0 md:right-6 w-full sm:w-[520px] md:w-[560px] lg:w-[600px] max-h-[94vh] md:max-h-[740px] h-[700px] bg-white rounded-t-3xl md:rounded-3xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden z-10 animate-slide-up">

        {{-- ── 1. MODAL HEADER (DEEP FOREST GREEN / WHATSAPP TEAL GRADIENT) ────── --}}
        <div class="p-4 text-white flex items-center justify-between flex-shrink-0 shadow-sm"
             style="background: linear-gradient(135deg, #075e54 0%, #128c7e 100%); color: #ffffff;">
            <div class="flex items-center gap-3 min-w-0">
                {{-- Back button on mobile --}}
                <button type="button" onclick="closeWhatsAppChat()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition sm:hidden flex-shrink-0 cursor-pointer" title="Go back">
                    <i class="fas fa-arrow-left text-sm"></i>
                </button>

                {{-- Glowing Avatar Container --}}
                <div class="relative flex-shrink-0">
                    <div id="waHeaderAvatar" class="w-10 h-10 rounded-2xl bg-white/20 text-white font-black flex items-center justify-center text-lg shadow-sm border border-white/30 overflow-hidden">
                        <span id="waHeaderAvatarText">👤</span>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-[#075e54] rounded-full"></span>
                </div>

                {{-- Name & Status --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 id="waHeaderName" class="font-extrabold text-sm sm:text-base text-white truncate leading-tight">Flatmate Discussion</h3>
                        <span class="bg-emerald-400/20 text-emerald-200 text-[10px] font-black px-2 py-0.5 rounded-md border border-emerald-400/30 flex items-center gap-1 flex-shrink-0">
                            <i class="fas fa-shield-check text-[9px]"></i> Verified
                        </span>
                    </div>
                    <p id="waHeaderSub" class="text-xs text-emerald-100/90 truncate flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 inline-block animate-pulse"></span>
                        <span id="waHeaderSubText">Online </span>
                    </p>
                </div>
            </div>

            {{-- Action Buttons: Prominent Close Button --}}
            <div class="flex items-center gap-2 flex-shrink-0 text-white">
                <button type="button" 
                        onclick="closeWhatsAppChat()" 
                        class="w-9 h-9 rounded-full bg-white/15 hover:bg-red-500 hover:text-white text-white flex items-center justify-center transition shadow-xs cursor-pointer" 
                        title="Close Chat" 
                        aria-label="Close Chat">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        {{-- ── 2. OWNER CONTACT THREADS RIBBON (FOR POST OWNER REPLYING) ───────── --}}
        <div id="waThreadsBar" class="hidden bg-slate-900 text-white px-3 py-2 flex items-center gap-2 overflow-x-auto no-scrollbar flex-shrink-0 border-b border-gray-800 shadow-xs">
            <span class="text-[10px] font-black uppercase text-emerald-400 whitespace-nowrap flex items-center gap-1">
                <i class="fas fa-users text-[9px]"></i> Threads:
            </span>
            <div id="waThreadsList" class="flex items-center gap-1.5 flex-nowrap"></div>
        </div>

        {{-- ── 3. QUICK TOPIC SHORTCUTS ────────────────────────────────────────── --}}
        <div id="waQuickQuestions" class="bg-gray-100 px-3 py-2 border-b border-gray-200 flex items-center gap-1.5 overflow-x-auto no-scrollbar flex-shrink-0">
            <span class="text-[10px] font-black text-gray-500 uppercase tracking-wider whitespace-nowrap flex items-center gap-1">
                <i class="fas fa-bolt text-amber-500 text-xs"></i> Quick:
            </span>
            <button type="button" onclick="askWaPrompt('Hi, is this room still available? 🔑')"
                    class="text-xs font-bold bg-white text-emerald-800 hover:text-emerald-900 hover:border-emerald-400 px-3 py-1 rounded-xl border border-emerald-200 shadow-xs whitespace-nowrap tap-effect flex items-center gap-1 cursor-pointer">
                <span>🔑 Available?</span>
            </button>
            <button type="button" onclick="askWaPrompt('What is the rent, deposit & maintenance breakdown? 💰')"
                    class="text-xs font-bold bg-white text-gray-700 hover:text-[#3a9a85] hover:border-[#4bb59d] px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap tap-effect flex items-center gap-1 cursor-pointer">
                <span>💰 Rent &amp; Deposit</span>
            </button>
            <button type="button" onclick="askWaPrompt('What amenities are provided in the flat (Fridge, AC, WiFi)? 🧊')"
                    class="text-xs font-bold bg-white text-gray-700 hover:text-[#3a9a85] hover:border-[#4bb59d] px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap tap-effect flex items-center gap-1 cursor-pointer">
                <span>🧊 Fridge &amp; WiFi</span>
            </button>
            <button type="button" onclick="askWaPrompt('What are the house rules and food preferences? 📋')"
                    class="text-xs font-bold bg-white text-gray-700 hover:text-[#3a9a85] hover:border-[#4bb59d] px-3 py-1 rounded-xl border border-gray-200 shadow-xs whitespace-nowrap tap-effect flex items-center gap-1 cursor-pointer">
                <span>📋 Rules &amp; Food</span>
            </button>
            <button type="button" onclick="askWaPrompt('Can I schedule a visit to see the room this weekend? 🗓️')"
                    class="text-xs font-bold bg-white text-purple-700 hover:text-purple-900 hover:border-purple-300 px-3 py-1 rounded-xl border border-purple-200 shadow-xs whitespace-nowrap tap-effect flex items-center gap-1 cursor-pointer">
                <span>🗓️ Schedule Visit</span>
            </button>
        </div>

        {{-- ── 4. CHAT MESSAGES SCROLL AREA (CLEAN 1-TO-1 WHATSAPP THEME) ────── --}}
        <div id="waChatBody" class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#eef2f5] relative custom-scrollbar">

            {{-- Trust & Safety Pill --}}
            <div class="text-center my-1">
                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-900 border border-amber-200 rounded-xl px-3 py-1.5 text-[11px] font-bold shadow-xs max-w-[92%] leading-tight text-center">
                    <i class="fas fa-shield-halved text-amber-600 text-xs"></i>
                    Direct 1-to-1 flatmate messaging &bull; Zero Brokerage
                </span>
            </div>

            {{-- Dynamic Message Stream Container --}}
            <div id="waMessagesStream" class="space-y-3">
                {{-- Loaded dynamically via JS for 1-to-1 chat --}}
            </div>

            {{-- Sending / Delivering Indicator (Hidden by default) --}}
            <div id="waTypingIndicator" class="hidden flex items-end gap-2 text-left">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#075e54] to-[#128c7e] text-white flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                    <i class="fas fa-paper-plane text-[10px]"></i>
                </div>
                <div class="bg-white text-gray-700 rounded-2xl rounded-tl-xs px-4 py-2.5 shadow-sm border border-gray-200 text-xs flex items-center gap-1.5 w-fit">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4bb59d] animate-bounce"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4bb59d] animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4bb59d] animate-bounce [animation-delay:0.4s]"></span>
                    <span class="text-xs font-bold text-gray-600 ml-1" id="waTypingText">Sending message...</span>
                </div>
            </div>
        </div>

        {{-- ── 5. CHAT INPUT BAR ──────────────────────────────────────────────── --}}
        <div class="p-3.5 bg-white border-t border-gray-200 flex-shrink-0">
            <form onsubmit="event.preventDefault(); sendWhatsAppMessage();" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input id="waChatInput" 
                           type="text" 
                           placeholder="Type a direct message to the flatmate..." 
                           class="w-full bg-gray-100 focus:bg-white text-xs sm:text-sm text-gray-900 rounded-2xl pl-4 pr-10 py-3.5 border border-transparent focus:border-[#4bb59d] focus:ring-2 focus:ring-[#4bb59d]/20 outline-none transition font-medium shadow-xs"
                           autocomplete="off">
                    <span class="absolute right-3.5 top-3.5 text-gray-400 text-sm">
                        <i class="fas fa-message text-[#4bb59d]"></i>
                    </span>
                </div>
                <button type="button" id="waSendBtn" onclick="sendWhatsAppMessage()" 
                        class="w-12 h-12 rounded-2xl bg-gradient-to-r from-[#4bb59d] to-teal-600 hover:from-[#3a9a85] hover:to-teal-700 text-white flex items-center justify-center shadow-md shadow-[#4bb59d]/30 transition tap-effect flex-shrink-0 cursor-pointer"
                        title="Send Message">
                    <i class="fas fa-paper-plane text-base"></i>
                </button>
            </form>
            <div class="flex items-center justify-between text-[11px] text-gray-400 mt-2 px-1 font-medium">
                <span class="flex items-center gap-1"><i class="fas fa-lock text-[#4bb59d]"></i> Direct message protection</span>
                <span>Direct 1-to-1 Flatmate Chat</span>
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
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<script>
// Global 1-to-1 Roommate Chat State
let waActiveSlug = null;
let waActivePost = null;
let waPollTimer = null;
let waIsOwner = false;
let waCurrentPeerId = null;
let waConversationStream = []; // In-memory 1-to-1 message stream

function openWhatsAppChat(slug, fallbackData = {}) {
    @guest
        window.location.href = "{{ route('user.login') }}";
        return;
    @endguest

    const modal = document.getElementById('whatsappChatModal');
    if (!modal) return;

    const isDifferentPost = (waActiveSlug !== slug);
    waActiveSlug = slug;

    // Reset input
    const input = document.getElementById('waChatInput');
    if (input) {
        input.value = '';
    }

    // Set fallback headers while loading
    if (fallbackData.poster_name) {
        document.getElementById('waHeaderName').innerText = fallbackData.poster_name;
        document.getElementById('waHeaderAvatarText').innerText = fallbackData.poster_gender === 'female' ? '👩' : (fallbackData.poster_gender === 'male' ? '👨' : '🧑');
        document.getElementById('waHeaderSubText').innerText = 'Online';
    }

    if (isDifferentPost || waConversationStream.length === 0) {
        waConversationStream = [];
        waCurrentPeerId = null;
        renderWaConversation(true);
    }

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    // Fetch latest 1-to-1 messages from DB immediately
    fetchWaMessages(true);

    // Start 4-second polling while chat is open
    if (waPollTimer) clearInterval(waPollTimer);
    waPollTimer = setInterval(() => {
        fetchWaMessages(false);
    }, 4000);

    setTimeout(() => {
        if (input) input.focus();
    }, 200);

    // Trigger unread check refresh after opening chat
    if (typeof checkGlobalUnreadMessages === 'function') {
        setTimeout(checkGlobalUnreadMessages, 1200);
    }
}

function closeWhatsAppChat() {
    const modal = document.getElementById('whatsappChatModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    document.body.classList.remove('overflow-hidden');
    if (waPollTimer) {
        clearInterval(waPollTimer);
        waPollTimer = null;
    }
}

async function fetchWaMessages(isFirstLoad = false) {
    if (!waActiveSlug) return;

    try {
        const url = `/find-roommate/${waActiveSlug}/messages` + (waCurrentPeerId ? `?peer_id=${waCurrentPeerId}` : '');
        const res = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!res.ok) return;
        const data = await res.json();

        if (data.success) {
            waActivePost = data.post;
            waIsOwner = data.is_owner;
            waCurrentPeerId = data.active_peer_id || null;

            // Render Threads bar for owner
            renderWaThreads(data.threads, data.active_peer_id);

            // Update Header & Avatar
            if (waIsOwner && data.active_peer) {
                document.getElementById('waHeaderName').innerText = data.active_peer.name;
                document.getElementById('waHeaderAvatarText').innerText = data.active_peer.gender === 'female' ? '👩' : (data.active_peer.gender === 'male' ? '👨' : '🧑');
                document.getElementById('waHeaderSubText').innerText = 'Prospective Flatmate';
            } else if (data.post) {
                document.getElementById('waHeaderName').innerText = data.post.poster_name;
                document.getElementById('waHeaderAvatarText').innerText = data.post.poster_gender === 'female' ? '👩' : (data.post.poster_gender === 'male' ? '👨' : '🧑');
                document.getElementById('waHeaderSubText').innerText = 'Online';
            }
            // Hide quick questions bar for owner/partner since they answer inquiries
            const quickBar = document.getElementById('waQuickQuestions');
            if (quickBar) {
                if (waIsOwner) {
                    quickBar.classList.add('hidden');
                } else {
                    quickBar.classList.remove('hidden');
                }
            }

            // Sync DB messages into 1-to-1 conversation stream
            syncDbMessagesToStream(data.messages, waIsOwner);

            renderWaConversation(isFirstLoad);
        }
    } catch (err) {
        console.error('Error fetching WA messages:', err);
    }
}

function syncDbMessagesToStream(dbMessages, isOwner) {
    if (!dbMessages) dbMessages = [];

    // Filter out temporary messages that have been confirmed in DB
    const existingMsgIds = new Set(
        waConversationStream
            .filter(item => item.type === 'msg' && !item.is_temp)
            .map(item => item.id)
    );

    // Merge incoming 1-to-1 messages
    dbMessages.forEach(m => {
        if (!existingMsgIds.has(m.id)) {
            // Check if there is a temp message bubble from user that matches this message
            const tempIdx = waConversationStream.findIndex(item => 
                item.type === 'msg' && item.is_temp && item.message === m.message
            );

            if (tempIdx !== -1) {
                waConversationStream[tempIdx] = {
                    type: 'msg',
                    id: m.id,
                    sender_id: m.sender_id,
                    sender_name: m.sender_name,
                    message: m.message,
                    is_me: m.is_me,
                    time: m.time,
                    is_read: m.is_read
                };
            } else {
                waConversationStream.push({
                    type: 'msg',
                    id: m.id,
                    sender_id: m.sender_id,
                    sender_name: m.sender_name,
                    message: m.message,
                    is_me: m.is_me,
                    time: m.time,
                    is_read: m.is_read
                });
            }
            existingMsgIds.add(m.id);
        }
    });
}

function renderWaThreads(threads, activePeerId) {
    const bar = document.getElementById('waThreadsBar');
    const list = document.getElementById('waThreadsList');
    if (!bar || !list) return;

    if (waIsOwner && threads && threads.length > 0) {
        bar.classList.remove('hidden');
        list.innerHTML = threads.map(t => {
            const isActive = (t.user_id === activePeerId);
            return `
                <button type="button" onclick="switchWaPeer('${t.user_id}')"
                        class="px-3 py-1 rounded-xl text-xs font-bold transition tap-effect flex items-center gap-1.5 whitespace-nowrap cursor-pointer ${isActive ? 'bg-[#4bb59d] text-white shadow-xs' : 'bg-white/10 text-gray-300 hover:bg-white/20'}">
                    <span>${t.name}</span>
                    ${t.unread_count > 0 ? `<span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.2 rounded-full">${t.unread_count}</span>` : ''}
                </button>
            `;
        }).join('');
    } else {
        bar.classList.add('hidden');
        list.innerHTML = '';
    }
}

function switchWaPeer(peerId) {
    waCurrentPeerId = peerId;
    waConversationStream = [];
    fetchWaMessages(true);
}

function renderWaConversation(forceScrollBottom = false) {
    const stream = document.getElementById('waMessagesStream');
    if (!stream) return;

    const body = document.getElementById('waChatBody');
    const isNearBottom = body ? (body.scrollHeight - body.scrollTop - body.clientHeight < 120) : true;

    let html = '';

    // If no messages yet in this 1-to-1 conversation, show clean direct conversation prompt
    if (waConversationStream.length === 0) {
        const posterName = waActivePost ? waActivePost.poster_name : 'the flatmate';
        const bhk = waActivePost ? waActivePost.bhk_type : 'Flatmate';
        const locality = waActivePost ? waActivePost.locality : 'Listing';

        if (waIsOwner) {
            html += `
                <div class="text-center my-8 p-5 bg-white rounded-3xl border border-gray-200 shadow-sm text-xs text-gray-600 space-y-2 max-w-sm mx-auto">
                    <div class="w-12 h-12 bg-[#e6f7f3] text-[#3a9a85] rounded-2xl flex items-center justify-center text-xl mx-auto mb-1">💬</div>
                    <p class="font-black text-gray-900 text-sm">No Inquiries on This Listing Yet</p>
                    <p class="text-xs text-gray-500 leading-relaxed">When prospective roommates message you, their threads will appear here and you can chat directly 1-to-1!</p>
                </div>
            `;
        } else {
            html += `
                <div class="text-center my-6 p-5 bg-white rounded-3xl border border-gray-200 shadow-sm text-xs text-gray-600 space-y-2 max-w-sm mx-auto">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl mx-auto mb-1">💬</div>
                    <p class="font-black text-gray-900 text-sm">Direct 1-to-1 Chat with ${escapeWaHtml(posterName)}</p>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Start your direct conversation regarding <strong>${escapeWaHtml(bhk)}</strong> in <strong>${escapeWaHtml(locality)}</strong>. Send a message or tap quick topics above!
                    </p>
                </div>
            `;
        }
    } else {
        waConversationStream.forEach(item => {
            if (item.type === 'msg') {
                if (item.is_me) {
                    html += `
                        <div class="flex justify-end mb-2">
                            <div class="max-w-[85%] sm:max-w-[80%] text-white p-3.5 rounded-2xl rounded-tr-xs shadow-sm text-xs space-y-1"
                                 style="background: linear-gradient(135deg, #4bb59d 0%, #3a9a85 100%); color: #ffffff;">
                                <p class="leading-relaxed whitespace-pre-line">${escapeWaHtml(item.message)}</p>
                                <div class="flex items-center justify-end gap-1 text-[10px] text-teal-100 font-medium">
                                    <span>${item.time}</span>
                                    <i class="fas ${item.is_temp ? 'fa-check' : 'fa-check-double'} text-teal-200 text-[10px]" title="${item.is_temp ? 'Sending...' : 'Delivered'}"></i>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="flex items-start gap-2.5 mb-2 max-w-[90%]">
                            <div class="w-8 h-8 rounded-2xl bg-gradient-to-br from-slate-800 to-gray-900 text-white flex items-center justify-center text-xs font-black flex-shrink-0 shadow-xs mt-0.5">
                                ${item.sender_name ? item.sender_name.charAt(0).toUpperCase() : '👤'}
                            </div>
                            <div class="bg-white text-gray-800 p-3.5 rounded-2xl rounded-tl-xs border border-gray-200 shadow-sm text-xs space-y-1">
                                <div class="text-[11px] font-black text-[#3a9a85] uppercase tracking-wide">${escapeWaHtml(item.sender_name)}</div>
                                <p class="leading-relaxed whitespace-pre-line text-gray-900 font-medium">${escapeWaHtml(item.message)}</p>
                                <div class="text-[10px] text-gray-400 text-right mt-1 font-medium">${item.time}</div>
                            </div>
                        </div>
                    `;
                }
            }
        });
    }

    stream.innerHTML = html;

    if (forceScrollBottom || isNearBottom) {
        if (body) {
            body.scrollTop = body.scrollHeight;
        }
    }
}

function askWaPrompt(promptText) {
    const input = document.getElementById('waChatInput');
    if (input) {
        input.value = promptText;
        input.focus();
    }
}

async function sendWhatsAppMessage() {
    const input = document.getElementById('waChatInput');
    if (!input || !waActiveSlug) return;

    const text = input.value.trim();
    if (!text || text.length < 2) {
        input.focus();
        return;
    }

    const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    // Append temporary user message to 1-to-1 conversation stream
    const tempId = 'temp_' + Date.now();
    waConversationStream.push({
        type: 'msg',
        id: tempId,
        is_temp: true,
        is_me: true,
        sender_name: 'You',
        message: text,
        time: nowTime
    });

    renderWaConversation(true);

    // Reset input
    input.value = '';

    // Show sending status
    showWaTyping(true, 'Sending message...');

    try {
        const payload = {
            message: text,
            receiver_id: waCurrentPeerId || (waActivePost ? waActivePost.user_id : null)
        };

        const res = await fetch(`/find-roommate/${waActiveSlug}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        showWaTyping(false);

        if (res.ok && data.success) {
            // Update temp message status to confirmed in conversation stream
            const tempItem = waConversationStream.find(i => i.id === tempId);
            if (tempItem) {
                tempItem.is_temp = false;
                if (data.data && data.data.id) {
                    tempItem.id = data.data.id;
                }
            }
            renderWaConversation(true);
        } else {
            // Display error alert inside chat stream
            const stream = document.getElementById('waMessagesStream');
            const errDiv = document.createElement('div');
            errDiv.className = 'bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl p-3 my-2 text-center font-semibold';
            errDiv.innerText = data.message || 'Could not send message. Please try again.';
            if (stream) stream.appendChild(errDiv);
            const body = document.getElementById('waChatBody');
            if (body) body.scrollTop = body.scrollHeight;
        }
    } catch (err) {
        showWaTyping(false);
        console.error('Error sending message:', err);
    }
}

function showWaTyping(show, text = 'Sending...') {
    const el = document.getElementById('waTypingIndicator');
    const txtEl = document.getElementById('waTypingText');
    if (el) {
        if (show) {
            if (txtEl) txtEl.innerText = text;
            el.classList.remove('hidden');
            const body = document.getElementById('waChatBody');
            if (body) body.scrollTop = body.scrollHeight;
        } else {
            el.classList.add('hidden');
        }
    }
}

function escapeWaHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>
