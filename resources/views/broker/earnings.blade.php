@extends('broker.layouts.app')

@section('title', 'Earnings & Payouts')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Earnings & Payouts</h1>
        <p class="text-sm text-gray-500">Track revenue, rent collections, payouts, and invoices</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('payoutModal')" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2">
            <i class="fas fa-wallet text-sm"></i> Request Payout
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Mobile Request Button -->
    <div class="lg:hidden">
        <button onclick="openModal('payoutModal')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2">
            <i class="fas fa-wallet"></i> Request Payout (₹42,000)
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 font-bold text-lg"><i class="fas fa-rupee-sign"></i></div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+18% MoM</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">₹2,48,500</div>
            <div class="text-xs text-gray-500 font-medium mt-1">Gross Earnings (August)</div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600 font-bold text-lg"><i class="fas fa-clock"></i></div>
                <span class="text-xs font-semibold text-yellow-700 bg-yellow-100 px-2.5 py-1 rounded-full">Ready</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">₹42,000</div>
            <div class="text-xs text-gray-500 font-medium mt-1">Available for Payout</div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-bold text-lg"><i class="fas fa-coins"></i></div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Total</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">₹24.8L</div>
            <div class="text-xs text-gray-500 font-medium mt-1">Lifetime Collections</div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center text-brand font-bold text-lg"><i class="fas fa-percentage"></i></div>
                <span class="text-xs font-semibold text-brand bg-brand-light px-2.5 py-1 rounded-full">Active Promo</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">0%</div>
            <div class="text-xs text-gray-500 font-medium mt-1">Brokerage Commission</div>
        </div>
    </div>

    <!-- Chart & Bank Account Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Monthly Revenue Breakdown</h3>
                    <p class="text-xs text-gray-500">Rent collected and payouts transferred in 2026</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center text-xs text-gray-500"><span class="w-3 h-3 rounded-full bg-brand mr-1"></span> Revenue</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Bank Account Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900 text-lg">Settlement Bank</h3>
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">VERIFIED</span>
                </div>
                <div class="p-4 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl text-white shadow-lg space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-white/70 font-semibold tracking-wider uppercase">HDFC BANK</span>
                        <i class="fas fa-shield-alt text-brand"></i>
                    </div>
                    <div class="font-mono text-lg tracking-widest">•••• •••• 8842</div>
                    <div class="flex justify-between items-end text-xs text-white/80">
                        <div>
                            <div class="text-[10px] text-white/50">ACCOUNT HOLDER</div>
                            <div class="font-bold uppercase text-white">Vikram Singh</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-white/50">IFSC</div>
                            <div class="font-mono font-bold text-white">HDFC0001248</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                <div class="text-xs text-gray-500">Next automatic payout scheduled for: <span class="font-bold text-gray-900">Aug 25, 2026</span></div>
                <button onclick="alert('Bank modification request sent to admin.');" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 rounded-xl tap-effect transition">Change Bank Details</button>
            </div>
        </div>
    </div>

    <!-- Payout History Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-lg">Payout & Transaction History</h3>
            <span class="text-xs text-gray-500">Showing last 4 transactions</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-brand">#TXN-99824</td>
                        <td class="px-6 py-4 text-gray-600">Aug 15, 2026</td>
                        <td class="px-6 py-4 font-medium text-gray-900">Direct Bank Payout</td>
                        <td class="px-6 py-4 font-bold text-gray-900">₹85,000</td>
                        <td class="px-6 py-4 text-gray-600">NEFT / IMPS</td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">COMPLETED</span></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="alert('Downloading Invoice #TXN-99824.pdf')" class="text-brand hover:underline font-semibold text-xs"><i class="fas fa-download mr-1"></i> PDF</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-brand">#TXN-98412</td>
                        <td class="px-6 py-4 text-gray-600">Aug 01, 2026</td>
                        <td class="px-6 py-4 font-medium text-gray-900">Monthly Rent Payout</td>
                        <td class="px-6 py-4 font-bold text-gray-900">₹1,21,500</td>
                        <td class="px-6 py-4 text-gray-600">Direct Transfer</td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">COMPLETED</span></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="alert('Downloading Invoice #TXN-98412.pdf')" class="text-brand hover:underline font-semibold text-xs"><i class="fas fa-download mr-1"></i> PDF</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-brand">#TXN-96210</td>
                        <td class="px-6 py-4 text-gray-600">Jul 15, 2026</td>
                        <td class="px-6 py-4 font-medium text-gray-900">Direct Bank Payout</td>
                        <td class="px-6 py-4 font-bold text-gray-900">₹72,000</td>
                        <td class="px-6 py-4 text-gray-600">NEFT / IMPS</td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">COMPLETED</span></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="alert('Downloading Invoice #TXN-96210.pdf')" class="text-brand hover:underline font-semibold text-xs"><i class="fas fa-download mr-1"></i> PDF</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Request Payout Modal -->
<div id="payoutModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold text-gray-900">Request Instant Payout</h3>
            <button onclick="closeModal('payoutModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect"><i class="fas fa-times text-gray-500 text-xs"></i></button>
        </div>
        <div class="space-y-3">
            <div class="bg-brand-50 p-4 rounded-2xl border border-brand-100">
                <div class="text-xs text-brand font-semibold">Available Balance</div>
                <div class="text-2xl font-extrabold text-gray-900">₹42,000</div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Withdrawal Amount (₹)</label>
                <input type="number" value="42000" max="42000" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 font-bold text-gray-900">
            </div>
            <div class="text-xs text-gray-500">
                Funds will be deposited to your verified HDFC Bank Account (•••• 8842) within 24-48 business hours.
            </div>
        </div>
        <div class="pt-3 flex gap-3">
            <button onclick="closeModal('payoutModal')" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl tap-effect">Cancel</button>
            <button onclick="alert('Payout request of ₹42,000 submitted successfully!'); closeModal('payoutModal');" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-md">Confirm</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    const ctxRev = document.getElementById('revenueChart');
    if (ctxRev) {
        new Chart(ctxRev, {
            type: 'bar',
            data: {
                labels: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug (Est)'],
                datasets: [{
                    label: 'Earnings (₹)',
                    data: [140000, 165000, 190000, 210000, 225000, 248500],
                    backgroundColor: '#4bb59d',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '₹' + (v / 1000) + 'k' },
                        grid: { color: '#f3f4f6' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>
@endpush
