@extends('user.layouts.app')

@section('title', 'Terms & Conditions of Service - Zero Brokerage PG & Roommate Network | SpaceSeeks')
@section('meta_description', 'Read SpaceSeeks Terms and Conditions, platform usage policies, verified PG listing rules, roommate community safety guidelines, and broker partner settlement policies.')
@section('meta_keywords', 'SpaceSeeks terms of service, tenant agreement, PG booking policies, host cancellation policy, roommate safety rules, broker KYC terms')
@section('canonical', route('user.terms'))
@section('robots', 'index, follow, max-snippet:-1, max-image-preview:large')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Terms of Service - SpaceSeeks",
  "description": "Read SpaceSeeks Terms and Conditions, platform usage policies, verified PG listing rules, roommate community safety guidelines, and broker partner settlement policies.",
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
        <p class="text-slate-500 text-xs md:text-sm">Last updated: September 2026 • Effective immediately across all SpaceSeeks services</p>
        
        <div class="flex flex-wrap items-center justify-center gap-2 mt-4 text-xs font-semibold">
            <span class="bg-brand-50 text-brand px-3 py-1 rounded-full border border-brand-100"><i class="fas fa-check-circle mr-1"></i> Zero Brokerage</span>
            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100"><i class="fas fa-shield-alt mr-1"></i> Verified Stays</span>
            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full border border-purple-100"><i class="fas fa-users mr-1"></i> Safe Roommate Finder</span>
            <span class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full border border-teal-100"><i class="fas fa-id-card mr-1"></i> Partner KYC Compliance</span>
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
                By accessing, browsing, or using the <strong>SpaceSeeks</strong> web application, mobile interfaces, or related services, you confirm that you have read, understood, and agreed to be legally bound by these Terms and Conditions and our Privacy Policy.
            </p>
            <p class="text-slate-600">
                SpaceSeeks operates as a technology discovery and facilitation platform connecting paying guest (PG) seekers, hostel residents, and co-living tenants directly with verified property owners, caretakers, and verified broker partners across India (including Noida, Delhi NCR, and Bangalore).
            </p>
        </section>

        <!-- Section 2 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">2</span>
                User & Tenant Account Eligibility
            </h2>
            <p class="text-slate-600">
                To register an account or initiate bookings on SpaceSeeks, you must be at least 18 years old or have parental/guardian authorization. When registering, you agree to:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Provide authentic, updated, and accurate personal identification information.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Maintain the confidentiality of your account credentials and restrict unauthorized access to your devices.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Promptly report any suspected security breaches or unauthorized use to SpaceSeeks support.</span>
                </li>
            </ul>
        </section>

        <!-- Section 3 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">3</span>
                Zero Brokerage Policy & Direct Interactions
            </h2>
            <div class="p-4 bg-brand-50 border border-brand-100 rounded-2xl text-slate-700">
                <div class="flex items-center gap-2 font-bold text-slate-900 mb-1">
                    <i class="fas fa-handshake text-brand"></i> Zero Brokerage Guarantee for Tenants
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    SpaceSeeks does <strong>not</strong> charge any brokerage fees, commissions, or placement surcharges from prospective tenants. Inquiries, visits, and tenancy contracts are arranged directly between the resident and the verified property host/owner.
                </p>
            </div>
        </section>

        <!-- Section 4 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">4</span>
                Partner Broker & Host KYC Compliance Rules
            </h2>
            <p class="text-slate-600">
                Property brokers and PG managers listing inventory on SpaceSeeks must adhere to regulatory compliance:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span><strong>Mandatory Verification:</strong> Hosts must provide legitimate Government ID Proof (Aadhar/PAN), State RERA Registration or Property Deed, and Bank Account Proof.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span><strong>Anti-Tamper Document Locking:</strong> Once verified by admin, documents are locked to maintain authentic audit trails. Modifications require administrative re-upload authorization.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span><strong>Accurate Inventories:</strong> Property partners must maintain real-time room/bed occupancies and honor published rental tariffs without hidden move-in surcharges.</span>
                </li>
            </ul>
        </section>

        <!-- Section 5 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">5</span>
                Roommate & Flatmate Finder Safety Rules
            </h2>
            <p class="text-slate-600">
                Our Roommate Finder connects students and working executives looking for shared living spaces. All participants agree to:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Honor stated gender preferences (e.g. Male only, Female only, or Any) and respect roommate lifestyle requirements.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Strictly zero tolerance for harassment, offensive communication, misrepresentation, or discriminatory behavior.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Conduct in-person or video alignment before entering into shared lease or deposit agreements.</span>
                </li>
            </ul>
        </section>

        <!-- Section 6 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">6</span>
                Rent Collections, Daily Settlements & Deposits
            </h2>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span><strong>Daily Automated Settlements:</strong> Rent collected digitally via SpaceSeeks is deposited directly into the verified broker’s bank account within 24–48 hours of receipt.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span><strong>Deposit Refund:</strong> Security deposits are refundable upon move-out subject to standard notice period compliance and inspection of property premises.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span><strong>Secure Gateways:</strong> All digital transactions are processed through encrypted, RBI-licensed payment gateways.</span>
                </li>
            </ul>
        </section>

        <!-- Section 7 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">7</span>
                Prohibited Activities & Account Termination
            </h2>
            <div class="space-y-2 text-slate-600 pl-2">
                <div class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-rose-500 mt-1 text-xs flex-shrink-0"></i>
                    <span>Unauthorized subletting of PG accommodations without written host consent.</span>
                </div>
                <div class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-rose-500 mt-1 text-xs flex-shrink-0"></i>
                    <span>Publishing deceptive photos, fraudulent pricing, or fake reviews.</span>
                </div>
                <div class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-rose-500 mt-1 text-xs flex-shrink-0"></i>
                    <span>Using the platform for any unlawful commercial spam or telemarketing.</span>
                </div>
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
