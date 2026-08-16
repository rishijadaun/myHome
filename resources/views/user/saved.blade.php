@extends('user.layouts.app')

@section('title', 'Saved Properties - StayNest')

@section('content')
<!-- Mobile Content -->
<div class="md:hidden pt-[80px] pb-24 px-4">
    <h1 class="text-xl font-bold text-gray-900 mb-4">Saved Properties</h1>
    <div class="space-y-4" id="mobileSavedList">
        <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 tap-effect relative">
            <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 tap-effect z-10"><i class="fas fa-heart"></i></button>
            <div class="relative h-40"><img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover"></div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-1.5"><h3 class="font-bold text-gray-900">Sunrise Premium PG</h3><span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded">BOYS</span></div>
                <p class="text-gray-500 text-xs mb-3 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand text-[10px]"></i> Sector 62, Noida • 1.2 km</p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="text-base font-bold text-gray-900">₹8,500<span class="text-[10px] font-normal text-gray-500">/mo</span></div>
                    <a href="{{ route('user.detail') }}" class="bg-brand text-white text-xs font-semibold px-4 py-2 rounded-lg tap-effect">View</a>
                </div>
            </div>
        </div>

        <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 tap-effect relative">
            <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 tap-effect z-10"><i class="fas fa-heart"></i></button>
            <div class="relative h-40"><img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover"></div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-1.5"><h3 class="font-bold text-gray-900">Aura Women's Stay</h3><span class="bg-pink-50 text-pink-600 text-[10px] font-bold px-2 py-0.5 rounded">GIRLS</span></div>
                <p class="text-gray-500 text-xs mb-3 flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand text-[10px]"></i> Indiranagar, Bangalore • 0.5 km</p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="text-base font-bold text-gray-900">₹9,999<span class="text-[10px] font-normal text-gray-500">/mo</span></div>
                    <a href="{{ route('user.detail') }}" class="bg-brand text-white text-xs font-semibold px-4 py-2 rounded-lg tap-effect">View</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Content -->
<div class="hidden md:block max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Saved Properties</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="desktopSavedList">
        <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm card-hover border border-gray-100 group relative">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 transition tap-effect shadow-lg"><i class="fas fa-heart text-lg"></i></button>
                <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-2 rounded-lg flex items-center gap-2"><i class="fas fa-star text-yellow-400"></i><span class="font-bold">4.8</span><span class="text-gray-300">(120 Reviews)</span></div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2"><h3 class="font-bold text-lg text-gray-900">Sunrise Premium PG</h3><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">BOYS</span></div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-brand"></i> Sector 62, Noida • 1.2 km</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div><span class="text-xs text-gray-500">Starts from</span><div class="text-xl font-bold text-gray-900">₹8,500<span class="text-sm font-normal text-gray-500">/month</span></div></div>
                    <a href="{{ route('user.detail') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-5 py-2.5 rounded-xl font-semibold transition tap-effect">View Details</a>
                </div>
            </div>
        </div>

        <div class="saved-item bg-white rounded-2xl overflow-hidden shadow-sm card-hover border border-gray-100 group relative">
            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <button onclick="removeSaved(this)" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 transition tap-effect shadow-lg"><i class="fas fa-heart text-lg"></i></button>
                <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur text-white text-xs px-3 py-2 rounded-lg flex items-center gap-2"><i class="fas fa-star text-yellow-400"></i><span class="font-bold">4.9</span><span class="text-gray-300">(98 Reviews)</span></div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-2"><h3 class="font-bold text-lg text-gray-900">Aura Women's Stay</h3><span class="bg-pink-50 text-pink-600 text-xs font-bold px-2.5 py-1 rounded-lg">GIRLS</span></div>
                <p class="text-gray-500 text-sm mb-3 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-brand"></i> Indiranagar, Bangalore • 0.5 km</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div><span class="text-xs text-gray-500">Starts from</span><div class="text-xl font-bold text-gray-900">₹9,999<span class="text-sm font-normal text-gray-500">/month</span></div></div>
                    <a href="{{ route('user.detail') }}" class="bg-gradient-to-r from-brand to-brand-dark hover:shadow-lg hover:shadow-brand/30 text-white px-5 py-2.5 rounded-xl font-semibold transition tap-effect">View Details</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function removeSaved(btn) {
        const item = btn.closest('.saved-item');
        if (item) {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.9)';
            setTimeout(() => item.remove(), 200);
        }
    }
</script>
@endpush
