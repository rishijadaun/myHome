@extends('user.layouts.app')

@section('title', 'Pricing Plans - Zero Brokerage for Tenants & Transparent Landlord Tiers | SpaceSeeks')
@section('meta_description', 'Explore SpaceSeeks pricing plans. 100% free with zero brokerage forever for tenants. Affordable verified listing plans and lead management for PG landlords.')
@section('meta_keywords', 'SpaceSeeks pricing, PG listing subscription, Zero brokerage student PG, Landlord listing plans')
@section('canonical', route('user.pricing'))

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "SpaceSeeks Pricing Plans",
  "url": "{{ route('user.pricing') }}",
  "description": "Zero brokerage for tenants, flexible plans for PG owners.",
  "offers": [
    {
      "@type": "Offer",
      "name": "Tenant Plan",
      "price": "0",
      "priceCurrency": "INR",
      "description": "100% free with zero brokerage forever for students & working professionals."
    },
    {
      "@type": "Offer",
      "name": "Single PG Host Plan",
      "price": "999",
      "priceCurrency": "INR",
      "description": "Monthly verified listing with tenant lead management."
    },
    {
      "@type": "Offer",
      "name": "Pro Broker Network Plan",
      "price": "2499",
      "priceCurrency": "INR",
      "description": "Unlimited listings with priority match engine placement."
    }
  ]
}
</script>
@endpush

@section('content')
<div class="pt-20 md:pt-10 pb-20 max-w-7xl mx-auto px-4 md:px-6 space-y-16">
    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span class="bg-brand-light text-brand text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Transparent & Fair</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight">Simple Pricing, <br><span class="gradient-text">Zero Hidden Charges</span></h1>
        <p class="text-gray-500 text-sm md:text-base">For tenants, browsing and booking is always 100% free with zero brokerage. For property owners, flexible plans to grow occupancy.</p>
    </div>

    <!-- Pricing Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Tenant Card -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col justify-between space-y-6 card-hover">
            <div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase">For Tenants</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-4">100% Free</h3>
                <p class="text-xs text-gray-500 mt-1">Zero brokerage forever for all students & professionals</p>
                <div class="text-4xl font-extrabold text-gray-900 my-6">₹0<span class="text-sm font-normal text-gray-400"> / forever</span></div>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Search unlimited verified PGs</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Direct owner & caretaker contact</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Free live location map routing</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Verified photos & transparent pricing</li>
                </ul>
            </div>
            <a href="{{ route('user.search') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 rounded-xl text-center tap-effect transition text-sm">Explore PGs Now</a>
        </div>

        <!-- Owner Starter Card (Popular) -->
        <div class="bg-white rounded-3xl p-8 border-2 border-brand shadow-xl relative flex flex-col justify-between space-y-6 card-hover">
            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand text-white text-xs font-extrabold px-4 py-1 rounded-full uppercase tracking-wider shadow-sm">Most Popular</span>
            <div>
                <span class="text-xs font-bold text-brand bg-brand-light px-3 py-1 rounded-full uppercase">Host Starter</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-4">Growth Partner</h3>
                <p class="text-xs text-gray-500 mt-1">For single and multi-property PG owners</p>
                <div class="text-4xl font-extrabold text-gray-900 my-6">₹0<span class="text-sm font-normal text-gray-400"> / listing fee</span></div>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Up to 3 PG property listings</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Unlimited tenant leads & inquiries</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Broker management dashboard access</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Rent payment tracking & automated receipts</li>
                </ul>
            </div>
            <a href="{{ route('user.list-property') }}" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3.5 rounded-xl text-center tap-effect shadow-lg shadow-brand/30 hover:opacity-95 transition text-sm">List Your PG Free</a>
        </div>

        <!-- Enterprise Card -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col justify-between space-y-6 card-hover">
            <div>
                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full uppercase">Hostel Chains</span>
                <h3 class="text-2xl font-bold text-gray-900 mt-4">Enterprise</h3>
                <p class="text-xs text-gray-500 mt-1">For co-living operators with 10+ locations</p>
                <div class="text-4xl font-extrabold text-gray-900 my-6">Custom<span class="text-sm font-normal text-gray-400"> / solution</span></div>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Unlimited properties & beds</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Priority top-ranking search placement</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Dedicated account manager</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check text-brand text-xs"></i> Custom API & ERP integrations</li>
                </ul>
            </div>
            <a href="mailto:imrishikrishna@gmail.com" target="_blank" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3.5 rounded-xl text-center tap-effect transition text-sm">Contact Sales Team</a>
        </div>
    </div>
</div>
@endsection
