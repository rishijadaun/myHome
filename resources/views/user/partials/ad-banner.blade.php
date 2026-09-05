{{-- DevPluss App, Website & SEO Compact Ad Banner --}}
<div id="devpluss-promo-banner" class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white border border-indigo-500/20 shadow-sm transition-all duration-300 mb-6">
    {{-- Decorative subtle background glow --}}
    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-500/15 rounded-full blur-2xl pointer-events-none"></div>
    <div class="absolute -left-8 -top-8 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 px-3.5 py-2.5 sm:py-3 sm:px-5 flex flex-col sm:flex-row items-center justify-between gap-3">
        
        {{-- Left / Middle: Service icon + Headline --}}
        <div class="flex items-center gap-3 w-full sm:w-auto min-w-0 pr-6 sm:pr-0">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-emerald-400 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-indigo-500/20">
                <i class="fas fa-code text-sm"></i>
            </div>
            
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                        DevPluss
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-white truncate">
                        App &amp; Website Design, Development &amp; SEO
                    </span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-300 truncate mt-0.5">
                    Build high-performance Website Design & Development, Mobile Application &amp; boost Google rankings with DevPluss.
                </p>
            </div>
        </div>

        {{-- Right: CTA Button + Dismiss Button --}}
        <div class="flex items-center gap-2 w-full sm:w-auto justify-end flex-shrink-0">
            <a href="https://devpluss.com/contact#contact-form" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 sm:py-2 text-xs font-bold text-white bg-gradient-to-r from-indigo-500 to-emerald-500 hover:from-indigo-600 hover:to-emerald-600 rounded-xl shadow-sm hover:shadow transition-all duration-200 whitespace-nowrap">
                <span>Contact Now</span>
                <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
            </a>

            {{-- Close / Dismiss Button --}}
            <button type="button" 
                    onclick="dismissDevplussAdBanner()" 
                    aria-label="Close Advertisement" 
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors text-xs flex-shrink-0 cursor-pointer" 
                    title="Hide advertisement">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
    function dismissDevplussAdBanner() {
        const banner = document.getElementById('devpluss-promo-banner');
        if (banner) {
            banner.style.opacity = '0';
            banner.style.transform = 'scale(0.98)';
            setTimeout(function() {
                banner.style.display = 'none';
            }, 200);
            try {
                sessionStorage.setItem('devpluss_ad_banner_hidden', '1');
            } catch(e) {}
        }
    }

    // Auto-hide if user already dismissed in this session
    (function() {
        try {
            if (sessionStorage.getItem('devpluss_ad_banner_hidden') === '1') {
                const banner = document.getElementById('devpluss-promo-banner');
                if (banner) banner.style.display = 'none';
            }
        } catch(e) {}
    })();
</script>
