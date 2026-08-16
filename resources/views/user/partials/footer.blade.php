<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 pb-safe shadow-lg">
    <div class="grid grid-cols-5 h-16">
        <a href="{{ route('user.home') }}" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-home text-xl text-brand"></i>
            <span class="text-[10px] font-semibold text-brand">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-search text-xl text-gray-400"></i>
            <span class="text-[10px] font-medium text-gray-500">Search</span>
        </a>
        <a href="{{ route('user.location') }}" class="flex flex-col items-center justify-center gap-1 tap-effect -translate-y-4">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center text-white shadow-lg shadow-yellow-500/40">
                <i class="fas fa-map-marker  text-xl text-gray-400"></i>
            </div>
        </a>
        <a href="#" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-heart text-xl text-gray-400"></i>
            <span class="text-[10px] font-medium text-gray-500">Saved</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center gap-1 tap-effect">
            <i class="fas fa-user text-xl text-gray-400"></i>
            <span class="text-[10px] font-medium text-gray-500">Profile</span>
        </a>
    </div>
</nav>
