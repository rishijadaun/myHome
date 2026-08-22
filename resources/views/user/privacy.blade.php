@extends('user.layouts.app')

@section('title', 'Privacy Policy - StayNest')
@section('meta_description', 'Learn how StayNest protects your personal data, contact information, accommodation preferences, and KYC verification records.')
@section('canonical', route('user.privacy'))
@section('robots', 'noindex, follow')

@section('content')
<div class="pt-20 md:pt-10 pb-20 max-w-4xl mx-auto px-4 md:px-6">
    
    <!-- Page Header -->
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-brand-light rounded-2xl flex items-center justify-center text-brand text-2xl mx-auto mb-4 shadow-sm">
            <i class="fas fa-shield-halved"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">Privacy Policy</h1>
        <p class="text-slate-500 text-xs md:text-sm">Last updated: August 2026 • We respect your personal data</p>
    </div>

    <!-- Privacy Highlights Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center card-hover">
            <div class="w-9 h-9 rounded-xl bg-brand-light text-brand flex items-center justify-center text-base mx-auto mb-2">
                <i class="fas fa-lock"></i>
            </div>
            <div class="font-bold text-xs text-slate-900">SSL Encrypted</div>
            <p class="text-[11px] text-slate-500 mt-0.5">256-bit safe transmission</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center card-hover">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base mx-auto mb-2">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="font-bold text-xs text-slate-900">Zero Spam</div>
            <p class="text-[11px] text-slate-500 mt-0.5">Never sold to marketers</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center card-hover">
            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base mx-auto mb-2">
                <i class="fas fa-map-pin"></i>
            </div>
            <div class="font-bold text-xs text-slate-900">Safe GPS</div>
            <p class="text-[11px] text-slate-500 mt-0.5">Used only for PG search</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center card-hover">
            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base mx-auto mb-2">
                <i class="fas fa-trash-can"></i>
            </div>
            <div class="font-bold text-xs text-slate-900">Easy Erasure</div>
            <p class="text-[11px] text-slate-500 mt-0.5">Delete data anytime</p>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-3xl p-6 md:p-10 shadow-sm border border-gray-100 space-y-8 text-sm leading-relaxed text-slate-700">
        
        <!-- Section 1 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">1</span>
                Our Commitment to Your Privacy
            </h2>
            <p class="text-slate-600">
                At <strong>StayNest</strong>, accessible from staynest.in and our mobile application, one of our main priorities is the privacy of our visitors and registered users. This Privacy Policy document describes what information is collected and recorded by StayNest and how we use it responsibly.
            </p>
        </section>

        <!-- Section 2 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">2</span>
                Information We Collect
            </h2>
            <p class="text-slate-600">
                We collect only the essential information needed to help you discover suitable accommodation or list your property:
            </p>
            <ul class="space-y-2.5 text-slate-600 pl-2">
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Account Details:</strong> Full name, verified mobile number, email address, and profile photo when signing up.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Search & Preference Data:</strong> Preferred cities (e.g. Bangalore, Noida, Pune), budget constraints, room sharing preferences, and food choices.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Location Coordinates (GPS):</strong> Approximate or precise GPS location when you choose to use the "Near Me" or interactive PG Route Map feature.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Property Listing Data:</strong> For hosts—property address, rent pricing, photographs, amenities checklist, and proof of property ownership.
                    </div>
                </li>
            </ul>
        </section>

        <!-- Section 3 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">3</span>
                How We Use Your Information
            </h2>
            <p class="text-slate-600">
                We process your data for clear, legitimate purposes including:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Enabling direct communication between verified tenants and property owners.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Calculating walking or driving travel times from your target college or office to shortlisted PGs.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Sending booking status notifications, rent reminders, and customer care updates.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Preventing duplicate listings, fraudulent hosts, or malicious activities.</span>
                </li>
            </ul>
        </section>

        <!-- Section 4 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">4</span>
                Data Protection & Security
            </h2>
            <p class="text-slate-600">
                StayNest implements industry-standard technical and organizational security measures, including HTTPS encryption, restricted database access, and regular security audits. While no electronic transmission is 100% immune from risks, we continuously upgrade our defenses to safeguard your personal credentials.
            </p>
        </section>

        <!-- Section 5 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">5</span>
                Zero Spam & Third-Party Sharing Rules
            </h2>
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-950">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fas fa-shield-check text-emerald-600"></i> Strict Anti-Spam Policy
                </div>
                <p class="text-xs text-emerald-800 leading-relaxed">
                    StayNest does not sell, rent, or trade your phone number or email to third-party telemarketers or external advertisers. Your contact information is only shared with the host of a specific property when you explicitly tap "Contact Host" or schedule an inspection.
                </p>
            </div>
        </section>

        <!-- Section 6 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">6</span>
                Your Rights & Data Control
            </h2>
            <p class="text-slate-600">
                You have complete authority over your personal information. You can:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>View and edit your personal profile anytime from the Profile dashboard.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Remove saved properties or delete search history from your device.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Request permanent deletion of your account and associated listings by writing to privacy@staynest.com.</span>
                </li>
            </ul>
        </section>

        <!-- Section 7 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">7</span>
                Grievance Officer & Contact
            </h2>
            <p class="text-slate-600">
                In accordance with the Information Technology Act (India) and applicable data protection norms, if you have any questions or grievances regarding data processing, please contact our Data Protection Officer:
            </p>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-xs space-y-1.5">
                <p class="font-bold text-slate-900">Data Protection Officer - StayNest</p>
                <p class="text-slate-600">Email: <a href="mailto:privacy@staynest.com" class="text-brand font-semibold hover:underline">privacy@staynest.com</a></p>
                <p class="text-slate-600">Address: StayNest Tech Park, Koramangala 4th Block, Bangalore, Karnataka 560034</p>
            </div>
        </section>

    </div>

    <!-- Quick Links Row -->
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 p-5 bg-white rounded-2xl border border-gray-100">
        <div class="text-xs text-slate-500 text-center sm:text-left">
            Looking for our rental guidelines and platform terms?
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('user.terms') }}" class="px-4 py-2 rounded-xl bg-brand-50 text-brand font-bold text-xs hover:bg-brand-100 transition tap-effect">
                <i class="fas fa-file-contract mr-1"></i> Terms & Conditions
            </a>
            <a href="{{ route('user.contact') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition tap-effect">
                <i class="fas fa-envelope mr-1"></i> Contact Us
            </a>
        </div>
    </div>

</div>
@endsection
