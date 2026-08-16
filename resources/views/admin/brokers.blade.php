@extends('admin.layouts.app')

@section('title', 'Manage Brokers')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manage Partner Brokers</h1>
        <p class="text-sm text-gray-500">45 verified brokers • 8 pending identity & property verification</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="alert('Exporting brokers report...')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold tap-effect flex items-center gap-2 transition">
            <i class="fas fa-file-export text-xs"></i> Export List
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-brand/40 transition" onclick="filterByBrokerTab('ALL')">
            <div class="text-2xl md:text-3xl font-bold text-gray-900">45</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Brokers</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-yellow-300 transition" onclick="filterByBrokerTab('PENDING')">
            <div class="text-2xl md:text-3xl font-bold text-yellow-600">8</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Pending Approval</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-green-300 transition" onclick="filterByBrokerTab('APPROVED')">
            <div class="text-2xl md:text-3xl font-bold text-green-600">34</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Approved & Active</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-brand/30 transition">
            <div class="text-2xl md:text-3xl font-bold text-brand">₹2.48L</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Commission Paid</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Tabs & Search -->
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex border-b md:border-b-0 border-gray-100 space-x-2 overflow-x-auto no-scrollbar">
                <button onclick="setBrokerTab(this, 'ALL')" class="broker-tab-btn px-5 py-2.5 text-sm font-semibold rounded-xl bg-brand-light text-brand border border-brand/20 transition whitespace-nowrap">
                    All Brokers (45)
                </button>
                <button onclick="setBrokerTab(this, 'PENDING')" class="broker-tab-btn px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">
                    Pending Approval (8)
                </button>
                <button onclick="setBrokerTab(this, 'APPROVED')" class="broker-tab-btn px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">
                    Approved (34)
                </button>
                <button onclick="setBrokerTab(this, 'REJECTED')" class="broker-tab-btn px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-50 transition whitespace-nowrap">
                    Rejected (3)
                </button>
            </div>
            <div class="relative w-full md:w-72">
                <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                <input id="brokerSearchInput" onkeyup="filterBrokers()" type="text" placeholder="Search broker, phone or city..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-9 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-brand/50">
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left" id="brokerTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Broker Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Operating City</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">PGs Listed</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">KYC Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Approval Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="brokerTableBody">
                    <!-- Approved Broker -->
                    <tr class="broker-row hover:bg-gray-50/70 transition" data-status="APPROVED">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm shadow-xs">VS</div>
                                <div>
                                    <div class="font-bold text-gray-900 broker-name">Vikram Singh</div>
                                    <div class="text-xs text-gray-400">ID: #BR-8821 • Joined Jan 2026</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-900 font-medium broker-phone">+91 98765 43210</div>
                            <div class="text-xs text-gray-500">vikram@broker.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 broker-city">Delhi / Noida</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">12 PGs</td>
                        <td class="px-6 py-4"><span class="text-xs font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded flex items-center gap-1 w-fit"><i class="fas fa-check-circle"></i> VERIFIED</span></td>
                        <td class="px-6 py-4"><span class="status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">APPROVED</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="viewBrokerKyc('Vikram Singh', '+91 98765 43210', 'vikram@broker.com', 'Delhi / Noida', '12 PGs', 'VERIFIED')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect" title="View Profile"><i class="fas fa-eye text-xs"></i></button>
                                <button onclick="suspendBroker(this, 'Vikram Singh')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect" title="Suspend"><i class="fas fa-ban text-xs"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Pending Broker 1 -->
                    <tr class="broker-row hover:bg-gray-50/70 transition bg-yellow-50/20" data-status="PENDING">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-xs">NP</div>
                                <div>
                                    <div class="font-bold text-gray-900 broker-name">Neha Patel</div>
                                    <div class="text-xs text-gray-400">ID: #BR-8822 • Applied 2 days ago</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-900 font-medium broker-phone">+91 98765 11111</div>
                            <div class="text-xs text-gray-500">neha@broker.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 broker-city">Mumbai</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">8 PGs</td>
                        <td class="px-6 py-4"><span class="text-xs font-semibold text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded flex items-center gap-1 w-fit"><i class="fas fa-clock"></i> PENDING</span></td>
                        <td class="px-6 py-4"><span class="status-badge bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">PENDING</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="approveBroker(this, 'Neha Patel')" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white font-bold text-xs rounded-xl tap-effect shadow-xs" title="Approve"><i class="fas fa-check mr-1"></i> Approve</button>
                                <button onclick="rejectBroker(this, 'Neha Patel')" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-xl tap-effect shadow-xs" title="Reject"><i class="fas fa-times mr-1"></i> Reject</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Pending Broker 2 -->
                    <tr class="broker-row hover:bg-gray-50/70 transition bg-yellow-50/20" data-status="PENDING">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-xs">RS</div>
                                <div>
                                    <div class="font-bold text-gray-900 broker-name">Rajesh Sharma</div>
                                    <div class="text-xs text-gray-400">ID: #BR-8823 • Applied 4 days ago</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-900 font-medium broker-phone">+91 98765 22222</div>
                            <div class="text-xs text-gray-500">rajesh@broker.com</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 broker-city">Bangalore</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">5 PGs</td>
                        <td class="px-6 py-4"><span class="text-xs font-semibold text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded flex items-center gap-1 w-fit"><i class="fas fa-clock"></i> PENDING</span></td>
                        <td class="px-6 py-4"><span class="status-badge bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-lg">PENDING</span></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="approveBroker(this, 'Rajesh Sharma')" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white font-bold text-xs rounded-xl tap-effect shadow-xs" title="Approve"><i class="fas fa-check mr-1"></i> Approve</button>
                                <button onclick="rejectBroker(this, 'Rajesh Sharma')" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-xl tap-effect shadow-xs" title="Reject"><i class="fas fa-times mr-1"></i> Reject</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-gray-100" id="brokerMobileList">
            <div class="p-4 broker-card" data-status="APPROVED">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 bg-brand text-white rounded-full flex items-center justify-center font-bold text-sm">VS</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-900 broker-name">Vikram Singh</h3>
                            <span class="status-badge bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded">APPROVED</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 broker-phone"><i class="fas fa-phone text-brand"></i> +91 98765 43210</p>
                        <p class="text-xs text-gray-500 broker-city"><i class="fas fa-map-marker-alt text-brand"></i> Delhi / Noida • 12 PGs</p>
                    </div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <button onclick="viewBrokerKyc('Vikram Singh', '+91 98765 43210', 'vikram@broker.com', 'Delhi / Noida', '12 PGs', 'VERIFIED')" class="flex-1 bg-blue-50 text-blue-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-eye mr-1"></i> View KYC</button>
                    <button onclick="suspendBroker(this, 'Vikram Singh')" class="flex-1 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-ban mr-1"></i> Suspend</button>
                </div>
            </div>

            <div class="p-4 bg-yellow-50/20 broker-card" data-status="PENDING">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-sm">NP</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-900 broker-name">Neha Patel</h3>
                            <span class="status-badge bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded">PENDING</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 broker-phone"><i class="fas fa-phone text-brand"></i> +91 98765 11111</p>
                        <p class="text-xs text-gray-500 broker-city"><i class="fas fa-map-marker-alt text-brand"></i> Mumbai • 8 PGs</p>
                    </div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <button onclick="approveBroker(this, 'Neha Patel')" class="flex-1 bg-green-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-check mr-1"></i> Approve</button>
                    <button onclick="rejectBroker(this, 'Neha Patel')" class="flex-1 bg-red-500 text-white text-xs font-semibold py-2 rounded-lg tap-effect"><i class="fas fa-times mr-1"></i> Reject</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Broker KYC Modal -->
<div id="brokerKycModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900">Broker KYC & Agency Profile</h3>
            <button onclick="closeModal('brokerKycModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect"><i class="fas fa-times text-gray-500 text-xs"></i></button>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Name</span><span id="kycName" class="font-bold text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Phone</span><span id="kycPhone" class="font-medium text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Email</span><span id="kycEmail" class="font-medium text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">City / Operational Region</span><span id="kycCity" class="font-semibold text-gray-900"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Properties</span><span id="kycPgs" class="font-bold text-brand"></span></div>
            <div class="flex justify-between"><span class="text-gray-500">KYC Status</span><span id="kycStatus" class="font-bold text-green-600"></span></div>
        </div>
        <div class="p-3 bg-gray-50 rounded-xl text-xs space-y-1">
            <div class="font-bold text-gray-700">Verified Documents:</div>
            <div>✓ Government ID (Aadhar / PAN) - Verified</div>
            <div>✓ Agency Commercial Registration - Verified</div>
            <div>✓ Bank Settlement Account - Active</div>
        </div>
        <div class="pt-2">
            <button onclick="closeModal('brokerKycModal')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2.5 rounded-xl tap-effect">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentBrokerFilter = 'ALL';

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function setBrokerTab(btn, status) {
        document.querySelectorAll('.broker-tab-btn').forEach(b => {
            b.classList.remove('bg-brand-light', 'text-brand', 'border', 'border-brand/20', 'font-semibold');
            b.classList.add('text-gray-600', 'font-medium');
        });
        btn.classList.add('bg-brand-light', 'text-brand', 'border', 'border-brand/20', 'font-semibold');
        btn.classList.remove('text-gray-600', 'font-medium');
        currentBrokerFilter = status;
        filterBrokers();
    }

    function filterByBrokerTab(status) {
        const tabs = document.querySelectorAll('.broker-tab-btn');
        tabs.forEach(t => {
            if (t.textContent.toUpperCase().includes(status)) {
                setBrokerTab(t, status);
            }
        });
    }

    function filterBrokers() {
        const search = document.getElementById('brokerSearchInput').value.toLowerCase();
        document.querySelectorAll('.broker-row, .broker-card').forEach(el => {
            const name = el.querySelector('.broker-name').textContent.toLowerCase();
            const phone = el.querySelector('.broker-phone').textContent.toLowerCase();
            const city = el.querySelector('.broker-city').textContent.toLowerCase();
            const status = el.getAttribute('data-status');

            const matchTab = (currentBrokerFilter === 'ALL') || (status === currentBrokerFilter);
            const matchSearch = name.includes(search) || phone.includes(search) || city.includes(search);

            if (matchTab && matchSearch) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }

    function approveBroker(btn, name) {
        if (confirm(`Approve broker ${name} application?`)) {
            const row = btn.closest('.broker-row') || btn.closest('.broker-card');
            if (row) {
                row.setAttribute('data-status', 'APPROVED');
                const badge = row.querySelector('.status-badge');
                badge.className = 'status-badge bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'APPROVED';
                filterBrokers();
                alert(`Broker ${name} approved and activated!`);
            }
        }
    }

    function rejectBroker(btn, name) {
        if (confirm(`Decline application for broker ${name}?`)) {
            const row = btn.closest('.broker-row') || btn.closest('.broker-card');
            if (row) {
                row.setAttribute('data-status', 'REJECTED');
                const badge = row.querySelector('.status-badge');
                badge.className = 'status-badge bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'REJECTED';
                filterBrokers();
                alert(`Broker ${name} rejected.`);
            }
        }
    }

    function suspendBroker(btn, name) {
        if (confirm(`Suspend broker account for ${name}?`)) {
            const row = btn.closest('.broker-row') || btn.closest('.broker-card');
            if (row) {
                row.setAttribute('data-status', 'REJECTED');
                const badge = row.querySelector('.status-badge');
                badge.className = 'status-badge bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg';
                badge.textContent = 'SUSPENDED';
                alert(`Broker ${name} has been suspended.`);
            }
        }
    }

    function viewBrokerKyc(name, phone, email, city, pgs, status) {
        document.getElementById('kycName').textContent = name;
        document.getElementById('kycPhone').textContent = phone;
        document.getElementById('kycEmail').textContent = email;
        document.getElementById('kycCity').textContent = city;
        document.getElementById('kycPgs').textContent = pgs;
        document.getElementById('kycStatus').textContent = status;
        openModal('brokerKycModal');
    }
</script>
@endpush
