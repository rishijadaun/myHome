{{-- ============================================================================== --}}
{{-- GLOBAL STAYNEST ROOMMATE REAL-TIME MESSAGE NOTIFIER & FLOATING TOAST           --}}
{{-- ============================================================================== --}}

{{-- Floating Real-Time Message Notification Toast (Slide-in alert on every page) --}}
<div id="roommateIncomingToast" 
     class="fixed bottom-20 md:bottom-8 left-4 md:left-8 z-50 max-w-sm sm:max-w-md w-[calc(100%-2rem)] sm:w-auto hidden transform transition-all duration-300 pointer-events-auto">
    <div class="bg-white text-gray-900 p-4 rounded-3xl shadow-2xl border-2 border-[#4bb59d]/40 flex items-start gap-3.5 relative overflow-hidden backdrop-blur-md">
        
        {{-- Ambient top stripe --}}
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#25D366] via-[#4bb59d] to-teal-600"></div>

        {{-- Glowing Sender Avatar Container --}}
        <div class="relative flex-shrink-0 mt-0.5">
            <div id="roommateToastAvatar" 
                 class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#075e54] to-[#128c7e] text-white font-black flex items-center justify-center text-base shadow-md shadow-emerald-900/20 border border-white">
                👤
            </div>
            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
        </div>

        {{-- Message Content & CTA --}}
        <div class="flex-1 min-w-0 pr-6">
            <div class="flex items-center gap-1.5 mb-0.5">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60">
                    <i class="fab fa-whatsapp text-emerald-600 mr-0.5"></i> New Message
                </span>
                <span id="roommateToastTime" class="text-[10px] text-gray-400 font-medium">Just now</span>
            </div>

            <h4 id="roommateToastSender" class="font-extrabold text-sm text-gray-900 truncate leading-tight mt-1">
                Prospective Flatmate
            </h4>

            <p id="roommateToastSnippet" class="text-xs text-gray-600 line-clamp-2 mt-0.5 leading-relaxed">
                Sent you a message regarding roommate stay.
            </p>

            <div class="flex items-center gap-2 mt-3">
                <button type="button" 
                        id="roommateToastOpenBtn" 
                        class="bg-gradient-to-r from-[#25D366] to-[#075e54] hover:from-[#20bd5a] hover:to-[#064c44] text-white text-xs font-extrabold px-4 py-2 rounded-xl shadow-md shadow-emerald-600/30 transition tap-effect flex items-center gap-1.5 cursor-pointer">
                    <i class="fab fa-whatsapp text-sm"></i>
                    <span>Open Chat &amp; Reply</span>
                </button>
                <button type="button" 
                        onclick="dismissRoommateToast()" 
                        class="text-xs font-bold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-xl transition tap-effect cursor-pointer">
                    Later
                </button>
            </div>
        </div>

        {{-- Quick Dismiss Button --}}
        <button type="button" 
                onclick="dismissRoommateToast()" 
                class="absolute top-3 right-3 w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition tap-effect cursor-pointer"
                title="Dismiss">
            <i class="fas fa-xmark text-xs"></i>
        </button>

    </div>
</div>

<script>
// Global Roommate Notification & Unread Checker
let lastSeenRoommateMsgId = null;
let roommateUnreadInterval = null;
let currentLatestRoommateData = null;

// Pleasant Soft Audio Chime using Web Audio API (Synthesized Bell)
function playRoommateChime() {
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;
        const ctx = new AudioContext();
        if (ctx.state === 'suspended') {
            ctx.resume();
        }

        const now = ctx.currentTime;
        // First tone
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(587.33, now); // D5
        osc1.frequency.exponentialRampToValueAtTime(880.00, now + 0.15); // A5
        gain1.gain.setValueAtTime(0.12, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.5);

        // Second harmonic tone
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'triangle';
        osc2.frequency.setValueAtTime(880.00, now + 0.08); // A5
        osc2.frequency.exponentialRampToValueAtTime(1174.66, now + 0.22); // D6
        gain2.gain.setValueAtTime(0.08, now + 0.08);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.55);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.08);
        osc2.stop(now + 0.55);
    } catch (e) {
        // Audio error ignored safely
    }
}

async function checkGlobalUnreadMessages() {
    @guest
        return;
    @endguest

    try {
        const res = await fetch('{{ route("user.roommate.unreadStats") }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!res.ok) return;
        const data = await res.json();

        if (data.success) {
            updateUnreadBadges(data.unread_count);

            if (data.unread_count > 0 && data.latest) {
                currentLatestRoommateData = data.latest;

                // If this is a new message we haven't alerted yet in this session
                const storedLastId = sessionStorage.getItem('staynest_last_notif_msg_id');
                if (data.latest.id && data.latest.id !== storedLastId && data.latest.id !== lastSeenRoommateMsgId) {
                    lastSeenRoommateMsgId = data.latest.id;
                    sessionStorage.setItem('staynest_last_notif_msg_id', data.latest.id);

                    // Show incoming message toast with audio feedback
                    showIncomingRoommateToast(data.latest);
                    playRoommateChime();
                }
            } else {
                dismissRoommateToast();
            }
        }
    } catch (e) {
        // Network errors silently handled
    }
}

function updateUnreadBadges(count) {
    const deskBadge = document.getElementById('deskWaUnreadBadge');
    const mobBadge = document.getElementById('mobWaUnreadBadge');
    const bottomNavBadge = document.getElementById('bottomNavProfileBadge');

    const displayCount = count > 99 ? '99+' : count;

    [deskBadge, mobBadge].forEach(badge => {
        if (badge) {
            if (count > 0) {
                badge.innerText = displayCount;
                badge.classList.remove('hidden');
                badge.classList.add('animate-pulse');
            } else {
                badge.innerText = '0';
                badge.classList.add('hidden');
                badge.classList.remove('animate-pulse');
            }
        }
    });

    if (bottomNavBadge) {
        if (count > 0) {
            bottomNavBadge.classList.remove('hidden');
        } else {
            bottomNavBadge.classList.add('hidden');
        }
    }
}

function showIncomingRoommateToast(latest) {
    const toast = document.getElementById('roommateIncomingToast');
    if (!toast) return;

    // Check if chat modal is currently open for this slug
    const modal = document.getElementById('whatsappChatModal');
    if (modal && !modal.classList.contains('hidden') && typeof waActiveSlug !== 'undefined' && waActiveSlug === latest.post_slug) {
        return; // Already viewing chat
    }

    const senderEl = document.getElementById('roommateToastSender');
    const snippetEl = document.getElementById('roommateToastSnippet');
    const timeEl = document.getElementById('roommateToastTime');
    const avatarEl = document.getElementById('roommateToastAvatar');
    const openBtn = document.getElementById('roommateToastOpenBtn');

    if (senderEl) senderEl.innerText = latest.sender_name + (latest.post_title ? ` (${latest.post_title})` : '');
    if (snippetEl) snippetEl.innerText = `"${latest.message}"`;
    if (timeEl) timeEl.innerText = latest.time || 'Just now';
    if (avatarEl) {
        avatarEl.innerText = latest.sender_gender === 'female' ? '👩' : (latest.sender_gender === 'male' ? '👨' : '🧑');
    }

    if (openBtn) {
        openBtn.onclick = function() {
            dismissRoommateToast();
            if (typeof openWhatsAppChat === 'function') {
                openWhatsAppChat(latest.post_slug, {
                    poster_name: latest.sender_name,
                    poster_gender: latest.sender_gender,
                    bhk_type: latest.bhk_type,
                    budget_range: latest.budget_range,
                    locality: latest.locality
                });
            } else {
                window.location.href = `/find-roommate/${latest.post_slug}`;
            }
        };
    }

    toast.classList.remove('hidden');
    toast.classList.add('animate-slide-up');

    // Auto dismiss toast after 12 seconds
    setTimeout(() => {
        dismissRoommateToast();
    }, 12000);
}

function dismissRoommateToast() {
    const toast = document.getElementById('roommateIncomingToast');
    if (toast) {
        toast.classList.add('hidden');
    }
}

function handleGlobalChatClick() {
    if (currentLatestRoommateData && currentLatestRoommateData.post_slug) {
        if (typeof openWhatsAppChat === 'function') {
            openWhatsAppChat(currentLatestRoommateData.post_slug, {
                poster_name: currentLatestRoommateData.sender_name,
                poster_gender: currentLatestRoommateData.sender_gender,
                bhk_type: currentLatestRoommateData.bhk_type,
                budget_range: currentLatestRoommateData.budget_range,
                locality: currentLatestRoommateData.locality
            });
            return;
        }
    }
    window.location.href = "{{ route('user.roommate.index') }}";
}

// Start periodic unread checks on page load
document.addEventListener('DOMContentLoaded', function() {
    @auth
        // Initial check after 800ms
        setTimeout(checkGlobalUnreadMessages, 800);

        // Periodic check every 15s
        if (roommateUnreadInterval) clearInterval(roommateUnreadInterval);
        roommateUnreadInterval = setInterval(checkGlobalUnreadMessages, 15000);
    @endauth
});
</script>
