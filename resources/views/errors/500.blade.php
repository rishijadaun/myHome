@extends('user.layouts.app')

@section('title', '500 - Server Error | StayNest')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="flex-1 flex items-center justify-center pt-20 md:pt-10 pb-20 px-4 md:px-6">
    <div class="max-w-2xl mx-auto text-center space-y-8 my-auto py-6">
        
        <!-- 500 Illustration Badge -->
        <div class="relative inline-block">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-3xl bg-gradient-to-tr from-amber-100 via-amber-50 to-orange-50 flex items-center justify-center mx-auto shadow-inner border border-amber-200 floating-bubble">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center text-4xl md:text-5xl shadow-xl shadow-amber-500/30">
                    <i class="fas fa-server"></i>
                </div>
            </div>
            
            <span class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs md:text-sm font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md">
                Error 500
            </span>
        </div>

        <!-- Error Headings -->
        <div class="space-y-3">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                Server Encountered an Issue
            </h1>
            <p class="text-slate-600 text-sm md:text-base max-w-md mx-auto leading-relaxed">
                Our team has been automatically alerted and is working to resolve it promptly. Please refresh or return to the home page.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <a href="{{ route('user.home') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-brand to-brand-dark text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-brand/30 hover:opacity-95 tap-effect">
                <i class="fas fa-home"></i> Return to Home
            </a>
            <a href="javascript:location.reload()" class="inline-flex items-center gap-2 bg-white text-slate-700 border border-slate-200 text-sm font-bold px-6 py-3 rounded-xl hover:bg-slate-50 tap-effect">
                <i class="fas fa-rotate-right"></i> Try Again
            </a>
        </div>

    </div>
</div>
@endsection
