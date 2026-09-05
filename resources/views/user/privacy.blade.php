@extends('user.layouts.app')

@section('title', 'Privacy Policy - Zero Brokerage PG & Roommate Network | SpaceSeeks')
@section('meta_description', 'Learn how SpaceSeeks protects your personal data, contact information, roommate matchmaking preferences, broker partner KYC documents, and bank records.')
@section('meta_keywords', 'SpaceSeeks privacy policy, data security, student PG privacy, tenant KYC protection, broker KYC security')
@section('canonical', route('user.privacy'))
@section('robots', 'index, follow, max-snippet:-1, max-image-preview:large')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Privacy Policy - SpaceSeeks",
  "description": "Learn how SpaceSeeks protects your personal data, contact information, roommate matchmaking preferences, broker partner KYC documents, and bank records.",
  "url": "{{ route('user.privacy') }}"
}
</script>
@endpush

@section('content')
<div class="pt-20 md:pt-10 pb-20 max-w-4xl mx-auto px-4 md:px-6">
    
    <!-- Page Header -->
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-brand-light rounded-2xl flex items-center justify-center text-brand text-2xl mx-auto mb-4 shadow-sm">
            <i class="fas fa-shield-halved"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-2">Privacy Policy</h1>
        <p class="text-slate-500 text-xs md:text-sm">Last updated: September 2026 • We respect & safeguard your personal data</p>
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
            <p class="text-[11px] text-slate-500 mt-0.5">Never sold to telemarketers</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-center card-hover">
            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base mx-auto mb-2">
                <i class="fas fa-id-card-clip"></i>
            </div>
            <div class="font-bold text-xs text-slate-900">KYC Vault</div>
            <p class="text-[11px] text-slate-500 mt-0.5">Encrypted partner records</p>
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
                At <strong>SpaceSeeks</strong>, accessible from our official domain and mobile web applications across India (including Noida, Delhi NCR, and Bangalore), one of our core commitments is the complete confidentiality and security of our visitors, tenants, flatmates, and partner brokers. This Privacy Policy details what information is collected, how it is processed, and the measures we take to protect your privacy.
            </p>
        </section>

        <!-- Section 2 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">2</span>
                Information We Collect
            </h2>
            <p class="text-slate-600">
                We collect only the essential information needed to facilitate verified accommodation bookings, roommate matchmaking, and partner broker payouts:
            </p>
            <ul class="space-y-2.5 text-slate-600 pl-2">
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">User & Tenant Account Data:</strong> Full name, verified mobile number, email address, gender, occupation/student status, and profile photo.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Roommate & Accommodation Preferences:</strong> Preferred target localities, budget boundaries, room sharing types (Single, Double, Triple), dietary habits (Veg/Non-Veg), smoking/drinking preferences, and sleep schedules.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Partner Broker & Host Verification (KYC Documents):</strong> Government ID proofs (Aadhar / PAN cards), State RERA registration certificates, cancelled cheques / bank passbook details, and business GSTIN records for regulatory compliance and verified partner badge issuance.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Location Coordinates (GPS):</strong> Approximate or precise GPS location when you choose to use the interactive "Explore Near Me" map or route calculators.
                    </div>
                </li>
                <li class="flex items-start gap-2.5">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <div>
                        <strong class="text-slate-900">Banking & Settlement Data:</strong> Bank account numbers, IFSC codes, and UPI IDs for direct daily automated rental collections and broker commissions.
                    </div>
                </li>
            </ul>
        </section>

        <!-- Section 3 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">3</span>
                Broker Partner KYC Document Protection & Locking Policy
            </h2>
            <div class="p-4 bg-teal-50 border border-teal-100 rounded-2xl text-teal-950 space-y-2">
                <div class="flex items-center gap-2 font-bold">
                    <i class="fas fa-shield-halved text-teal-700"></i> Encrypted KYC Vault & Anti-Tamper Locking
                </div>
                <p class="text-xs text-teal-900 leading-relaxed">
                    All partner KYC documents uploaded to SpaceSeeks are stored in restricted server directories protected by firewall authentication. Once an administrator verifies and approves a document, it is <strong>strictly locked</strong> to prevent unauthorized alterations or identity substitution. Re-upload is only enabled when explicitly authorized by an administrator for compliance updates.
                </p>
            </div>
        </section>

        <!-- Section 4 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">4</span>
                How We Use Your Information
            </h2>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Connecting prospective tenants directly with verified PG owners and brokers without intermediary brokerage fees.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Matching compatible flatmates based on shared habits, gender preferences, and college/office proximity.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Processing automated daily rent settlements and payout transfers to verified partner bank accounts.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Sending instant booking confirmations, visit alerts, and WhatsApp customer service updates.</span>
                </li>
            </ul>
        </section>

        <!-- Section 5 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">5</span>
                Zero Spam & Privacy Guarantees
            </h2>
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-950">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fas fa-shield-check text-emerald-600"></i> Strict Anti-Telemarketing Rule
                </div>
                <p class="text-xs text-emerald-800 leading-relaxed">
                    SpaceSeeks does <strong>not</strong> sell, rent, or trade your phone number, email, or roommate listings to third-party telemarketers or external advertisers. Your contact details are only shared with a property host when you explicitly schedule a visit or initiate direct booking.
                </p>
            </div>
        </section>

        <!-- Section 6 -->
        <section class="space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2.5 pb-2 border-b border-gray-100">
                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs font-bold">6</span>
                Your Data Rights & Deletion Requests
            </h2>
            <p class="text-slate-600">
                You retain complete authority over your personal information. You can:
            </p>
            <ul class="space-y-2 text-slate-600 pl-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>View, update, or edit your personal profile and preferences anytime from your Dashboard.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Pause or deactivate active roommate search listings when a match is finalized.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1 text-xs flex-shrink-0"></i>
                    <span>Request permanent deletion of your account and records by writing to our grievance team at <strong>privacy@spaceseeks.com</strong>.</span>
                </li>
            </ul>
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
