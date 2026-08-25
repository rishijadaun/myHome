<!-- ================= PWA APP INSTALL & DOWNLOAD MODAL ================= -->
<div id="pwaInstallModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 bg-black/70 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-gray-100 relative transform transition-all animate-in fade-in zoom-in duration-200">
        
        <!-- Close Button -->
        <button type="button" onclick="closePwaModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center tap-effect transition cursor-pointer" aria-label="Close">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- App Identity Card -->
        <div class="flex items-center gap-3.5 pb-5 border-b border-gray-100">
            <img src="/images/favicon.png" alt="StayNest App" class="w-14 h-14 rounded-2xl shadow-md border border-gray-100 p-1 bg-white object-contain flex-shrink-0">
            <div>
                <div class="flex items-center gap-1.5">
                    <h3 class="text-lg font-black text-gray-900">StayNest</h3>
                    <span class="bg-brand-50 text-brand text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-brand/20">Official PWA</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Zero Brokerage PG &amp; Flat Discovery App</p>
                <div class="flex items-center gap-2 mt-1 text-[11px] font-bold text-gray-600">
                    <span class="text-yellow-500 flex items-center gap-0.5"><i class="fas fa-star text-[10px]"></i> 4.9★</span>
                    <span class="text-gray-300">•</span>
                    <span class="text-emerald-600 font-semibold"><i class="fas fa-bolt text-[10px]"></i> Instant Install (&lt; 2MB)</span>
                </div>
            </div>
        </div>

        <!-- Dynamic Platform Content: Auto / Android / iOS / Desktop -->
        <div class="py-5 space-y-4">
            
            <!-- Standard One-Click PWA Trigger (When supported) -->
            <div id="pwaDirectTriggerBox" class="space-y-3">
                <p class="text-xs text-gray-600 leading-relaxed">
                    Install StayNest directly on your device. Enjoy fast booking, offline access, instant notifications, and zero storage hassle.
                </p>
                <button type="button" id="pwaModalInstallBtn" onclick="triggerPwaPromptDirectly()" class="w-full bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white font-extrabold text-sm py-3.5 px-5 rounded-2xl shadow-lg shadow-brand/30 hover:shadow-xl transition tap-effect flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fas fa-download text-base"></i>
                    <span id="pwaModalBtnText">Install StayNest App Now</span>
                </button>
            </div>

            <!-- iOS Safari Instructions (Shown on Apple Devices) -->
            <div id="pwaIosGuideBox" class="hidden space-y-3 bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4">
                <div class="flex items-center gap-2 text-amber-900 font-black text-xs uppercase tracking-wider">
                    <i class="fab fa-apple text-base"></i> Install on iPhone / iPad (iOS)
                </div>
                <ol class="space-y-2.5 text-xs text-gray-700">
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-900 font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <span>Tap the <strong>Share</strong> button <i class="fas fa-arrow-up-from-bracket text-blue-600 mx-1 text-sm"></i> at the bottom of Safari.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-900 font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        <span>Scroll down and select <strong>"Add to Home Screen"</strong> <i class="fas fa-plus-square text-gray-800 mx-1"></i>.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-900 font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                        <span>Tap <strong>"Add"</strong> in the top-right corner to install.</span>
                    </li>
                </ol>
            </div>

            <!-- Android / Chrome fallback instructions if prompt is already dismissed or suppressed -->
            <div id="pwaAndroidFallbackBox" class="hidden space-y-3 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4">
                <div class="flex items-center gap-2 text-emerald-900 font-black text-xs uppercase tracking-wider">
                    <i class="fab fa-android text-base"></i> Quick Android Browser Install
                </div>
                <ol class="space-y-2 text-xs text-gray-700">
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-emerald-200 text-emerald-900 font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <span>Tap the <strong>three dots (⋮)</strong> in Chrome or your browser menu.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-emerald-200 text-emerald-900 font-black text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        <span>Select <strong>"Install app"</strong> or <strong>"Add to Home screen"</strong>.</span>
                    </li>
                </ol>
            </div>

            <!-- Features Highlights -->
            <div class="grid grid-cols-3 gap-2 pt-2 text-center text-[10px] font-bold text-gray-600">
                <div class="bg-gray-50 rounded-xl p-2 border border-gray-100">
                    <i class="fas fa-bolt text-brand text-sm mb-1 block"></i>
                    <span>Super Fast</span>
                </div>
                <div class="bg-gray-50 rounded-xl p-2 border border-gray-100">
                    <i class="fas fa-hand-holding-dollar text-emerald-600 text-sm mb-1 block"></i>
                    <span>0% Brokerage</span>
                </div>
                <div class="bg-gray-50 rounded-xl p-2 border border-gray-100">
                    <i class="fas fa-shield-halved text-blue-600 text-sm mb-1 block"></i>
                    <span>100% Safe</span>
                </div>
            </div>
        </div>

        <div class="pt-2 text-center">
            <button type="button" onclick="closePwaModal()" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition cursor-pointer">
                Continue browsing on web
            </button>
        </div>
    </div>
</div>
