@extends('user.layouts.app')

@section('title', '404 - Page Not Found | StayNest')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="flex-1 flex items-center justify-center pt-20 md:pt-10 pb-20 px-4 md:px-6">
    <div class="max-w-2xl mx-auto text-center space-y-8 my-auto py-6">
        
        <!-- 404 Illustration Badge -->
        <div class="relative inline-block">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-3xl bg-gradient-to-tr from-brand-100 via-brand-50 to-emerald-50 flex items-center justify-center mx-auto shadow-inner border border-brand-100 floating-bubble">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-brand to-brand-dark text-white flex items-center justify-center text-4xl md:text-5xl shadow-xl shadow-brand/30">
                    <i class="fas fa-map-location-dot"></i>
                </div>
            </div>
            
            <span class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs md:text-sm font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md">
                Error 404
            </span>
        </div>

        <!-- Error Headings -->
        <div class="space-y-3">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ !empty($exception) && $exception->getMessage() ? $exception->getMessage() : "Oops! Property Not Found" }}
            </h1>
            <p class="text-slate-600 text-sm md:text-base max-w-md mx-auto leading-relaxed">
                The property you are looking for is currently unapproved by admin, under review, or no longer publicly accessible.
            </p>
        </div>

        <!-- Quick Search Bar -->
        <form action="{{ route('user.search') }}" method="GET" class="max-w-lg mx-auto">
            <div class="relative flex items-center bg-white rounded-2xl p-1.5 shadow-sm border border-gray-200 focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20 transition">
                <span class="pl-4 text-gray-400 text-sm"><i class="fas fa-search"></i></span>
                <input type="text" name="q" placeholder="Search verified PGs by city or locality..." class="w-full bg-transparent px-3 py-2.5 text-sm focus:outline-none text-slate-800 placeholder-gray-400">
                <button type="submit" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold text-xs tap-effect shadow-md shadow-brand/30 flex-shrink-0">
                    Search
                </button>
            </div>
        </form>

        <!-- Popular Quick Link Cards -->
        <div class="pt-4">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Or try these popular sections</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-xl mx-auto">
                <a href="{{ route('user.home') }}" class="bg-white p-3.5 rounded-2xl border border-gray-100 card-hover text-center tap-effect flex flex-col items-center gap-1.5">
                    <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-home"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-900">Home</span>
                </a>

                <a href="{{ route('user.search') }}" class="bg-white p-3.5 rounded-2xl border border-gray-100 card-hover text-center tap-effect flex flex-col items-center gap-1.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-bed"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-900">Find Listing</span>
                </a>

                <a href="{{ route('user.location') }}" class="bg-white p-3.5 rounded-2xl border border-gray-100 card-hover text-center tap-effect flex flex-col items-center gap-1.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-900">Route Map</span>
                </a>

                <a href="{{ route('user.contact') }}" class="bg-white p-3.5 rounded-2xl border border-gray-100 card-hover text-center tap-effect flex flex-col items-center gap-1.5">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-headset"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-900">Contact</span>
                </a>
            </div>
        </div>

        <!-- Primary Action Buttons -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('user.home') }}" class="w-full sm:w-auto bg-gradient-to-r from-brand to-brand-dark text-white font-bold px-8 py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition text-sm flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Back to Homepage
            </a>
            <a href="{{ route('user.contact') }}" class="w-full sm:w-auto bg-white border border-gray-200 text-slate-700 font-semibold px-6 py-3 rounded-xl tap-effect hover:bg-gray-50 transition text-sm flex items-center justify-center gap-2">
                <i class="fas fa-circle-question text-brand"></i> Report Broken Link
            </a>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .floating-bubble {
        animation: float 4s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(3deg); }
    }
</style>
@endpush
