@extends('user.layouts.app')

@section('title', 'Terms & Conditions of Service - Zero Brokerage PG Network | StayNest')
@section('meta_description', 'Read StayNest Terms and Conditions, platform usage policies, verified PG listing rules, tenant safety guidelines and cancellation policies.')
@section('meta_keywords', 'StayNest terms of service, tenant agreement, PG booking policies, host cancellation policy')
@section('canonical', route('user.terms'))
@section('robots', 'index, follow, max-snippet:-1, max-image-preview:large')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Terms of Service - StayNest",
  "description": "Read StayNest Terms and Conditions, platform usage policies, verified PG listing rules, tenant safety guidelines and cancellation policies.",
  "url": "{{ route('user.terms') }}"
}
</script>
@endpush

@section('content')
<div class="pt-20 md:pt-10 pb-20 max-w-4xl mx-auto px-4 md:px-6">
    
    <!-- Page Header -->
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-brand-light rounded-2xl flex items-center justify-center text-brand text-2xl mx-auto mb-4 shadow-sm">
            <i class="fas fa-file-contract"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">Terms & Conditions</h1>
        <p class="text-slate-500 text-xs md:text-sm">Last updated: August 2026 • Effective immediately</p>
        
        <div class="flex flex-wrap items-center justify-center gap-2 mt-4 text-xs font-semibold">
            <span class="bg-brand-50 text-brand px-3 py-1 rounded-full border border-brand-100"><i class="fas fa-check-circle mr-1"></i> Zero Brokerage</span>
            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100"><i class="fas fa-shield-alt mr-1"></i> Verified Stays</span>
            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full border border-purple-100"><i class="fas fa-lock mr-1"></i> Secure Deposits</span>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-3xl p-6 md:p-10 shadow-sm border border-gray-100 space-y-8 text-sm leading-relaxed text-slate-700">
        
        <!-- Section 1 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">1</span>
                Agreement to Terms & Platform Scope
            </h2>
            <p class="text-slate-600">
                By accessing, browsing, or using the <strong>StayNest</strong> web application, mobile interfaces, or related services, you confirm that you have read, understood, and agreed to be legally bound by these Terms and Conditions and our Privacy Policy.
            </p>
            <p class="text-slate-600">
                StayNest operates as a technology discovery and facilitation platform connecting paying guest (PG) seekers, hostel residents, and co-living tenants directly with verified property owners and caretakers.
            </p>
        </section>

        <!-- Section 2 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">2</span>
                User Eligibility & Account Security
            </h2>
            <p class="text-slate-600">
                To register an account or initiate bookings on StayNest, you must be at least 18 years old or have parental/guardian authorization. When registering, you agree to:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Provide authentic, updated, and accurate personal identification information.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Maintain the confidentiality of your credentials and restrict unauthorized access to your account.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Promptly report any suspected security breaches or unauthorized use to StayNest support.</span>
                </li>
            </ul>
        </section>

        <!-- Section 3 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">3</span>
                PG Listings, In-Person Verification & Accuracy
            </h2>
            <p class="text-slate-600">
                StayNest makes rigorous efforts to verify listed PG amenities, photo galleries, room configurations, and rent amounts. However:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Property owners are solely responsible for ensuring real-time bed availability and honoring posted rental tariffs.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Tenants are encouraged to schedule a physical or virtual visit prior to paying any move-in tokens or full deposits.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Photographs marked with "Verified by StayNest" represent inspections conducted at the time of cataloging.</span>
                </li>
            </ul>
        </section>

        <!-- Section 4 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">4</span>
                Zero Brokerage Policy & Direct Interactions
            </h2>
            <div class="p-4 bg-brand-50 border border-brand-100 rounded-2xl text-slate-700">
                <div class="flex items-center gap-2 font-bold text-slate-900 mb-1">
                    <i class="fas fa-handshake text-brand"></i> Zero Brokerage Guarantee
                </div>
                <p class="text-xs text-slate-600">
                    StayNest does not charge any brokerage fees, commissions, or tenant placement charges from tenants. All inquiries and contracts are direct between the resident and the property host.
                </p>
            </div>
        </section>

        <!-- Section 5 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">5</span>
                Security Deposits, Rent Schedules & Refunds
            </h2>
            <p class="text-slate-600">
                Rental payments and security deposits are governed by the mutual tenancy agreement signed between the resident and the PG owner:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Security deposits must be refunded upon move-out subject to notice period compliance and inspection for property damage.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Digital payments transacted via StayNest are routed through encrypted, RBI-compliant payment gateways.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Cancellation policies specific to each property are shown clearly prior to booking confirmation.</span>
                </li>
            </ul>
        </section>

        <!-- Section 6 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">6</span>
                House Rules, Curfews & Tenant Conduct
            </h2>
            <p class="text-slate-600">
                Each PG accommodation has community guidelines regarding gate timings, visitors, smoking/alcohol policies, and quiet hours. Residents agree to abide by the respective property’s house rules to ensure harmony for all roommates.
            </p>
        </section>

        <!-- Section 7 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">7</span>
                Prohibited Activities
            </h2>
            <div class="space-y-2 text-slate-600 pl-2">
                <div class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-rose-500 mt-1 text-xs flex-shrink-0"></i>
                    <span>Subletting accommodation to unauthorized third parties without owner consent.</span>
                </div>
                <div class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-rose-500 mt-1 text-xs flex-shrink-0"></i>
                    <span>Posting fraudulent property listings, deceptive rent prices, or fake reviews.</span>
                </div>
                <div class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-rose-500 mt-1 text-xs flex-shrink-0"></i>
                    <span>Harassing other platform members, property hosts, or support personnel.</span>
                </div>
            </div>
        </section>

        <!-- Section 8 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">8</span>
                Limitation of Liability
            </h2>
            <p class="text-slate-600">
                To the maximum extent permitted by applicable Indian law, StayNest shall not be liable for any direct, indirect, incidental, or consequential damages arising from disputes between residents and property hosts, property damage, loss of personal belongings, or temporary amenity disruptions at the PG premises.
            </p>
        </section>

        <!-- Section 9 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">9</span>
                Contact & Legal Inquiries
            </h2>
            <p class="text-slate-600">
                For any legal inquiries, copyright notices, or questions regarding these Terms, please contact our grievance team at:
            </p>
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="mailto:legal@staynest.com" class="inline-flex items-center gap-2 text-brand font-bold hover:underline">
                    <i class="fas fa-envelope"></i> legal@staynest.com
                </a>
                <span class="text-gray-300">•</span>
                <a href="{{ route('user.contact') }}" class="inline-flex items-center gap-2 text-slate-700 font-semibold hover:text-brand transition">
                    <i class="fas fa-headset"></i> Support Desk
                </a>
            </div>
        </section>

    </div>

    <!-- Quick Links Row -->
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 p-5 bg-white rounded-2xl border border-gray-100">
        <div class="text-xs text-slate-500 text-center sm:text-left">
            Have questions about how your personal data is handled?
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('user.privacy') }}" class="px-4 py-2 rounded-xl bg-brand-50 text-brand font-bold text-xs hover:bg-brand-100 transition tap-effect">
                <i class="fas fa-shield-alt mr-1"></i> Read Privacy Policy
            </a>
            <a href="{{ route('user.contact') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition tap-effect">
                <i class="fas fa-envelope mr-1"></i> Contact Us
            </a>
        </div>
    </div>

</div>
@endsection
