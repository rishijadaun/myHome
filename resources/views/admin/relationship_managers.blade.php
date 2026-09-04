@extends('admin.layouts.app')

@section('title', 'Relationship Managers Directory')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Relationship Managers Team</h1>
        <p class="text-xs text-gray-500">Dedicated RM directory, regional zone assignments & broker workload allocation</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.brokers') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold tap-effect flex items-center gap-2 border border-gray-200 transition">
            <i class="fas fa-arrow-left"></i> Back to Brokers
        </a>
        <button 
            onclick="openAddRmModal()" 
            class="bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-teal-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs tap-effect flex items-center gap-2 shadow-sm cursor-pointer transition"
        >
            <i class="fas fa-user-plus"></i> Add New RM
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ $managers->count() }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Active RMs</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-brand-dark">{{ $managers->sum('brokers_count') }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Assigned Partner Brokers</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-indigo-600">{{ $managers->where('is_default', 1)->count() }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Default System RM</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-teal-600">{{ $managers->unique('zone')->count() }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Regional Zones Covered</div>
        </div>
    </div>

    <!-- RM Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($managers as $rm)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $rm->avatar_url ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80' }}" alt="{{ $rm->name }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-100 shadow-xs flex-shrink-0">
                            <div>
                                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                    {{ $rm->name }}
                                    @if($rm->is_default)
                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Default</span>
                                    @endif
                                </h3>
                                <p class="text-xs text-brand font-semibold">{{ $rm->designation ?? 'Relationship Manager' }}</p>
                                <p class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-location-dot text-gray-400"></i> Zone: {{ $rm->zone ?? 'All Regions' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button onclick="editRmDirect('{{ $rm->id }}')" class="w-8 h-8 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs flex items-center justify-center transition cursor-pointer" title="Edit RM">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button onclick="deleteRmDirect('{{ $rm->id }}', '{{ addslashes($rm->name) }}')" class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs flex items-center justify-center transition cursor-pointer" title="Delete RM">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="space-y-2 py-3 border-y border-gray-50 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-gray-400 w-4 text-center"></i>
                            <span class="truncate">{{ $rm->email }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone text-gray-400 w-4 text-center"></i>
                            <span>{{ $rm->phone }}</span>
                        </div>
                        @if($rm->whatsapp_number)
                            <div class="flex items-center gap-2 text-emerald-600 font-medium">
                                <i class="fab fa-whatsapp text-emerald-500 w-4 text-center"></i>
                                <span>WhatsApp: +{{ $rm->whatsapp_number }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-4 mt-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-gray-400 font-semibold uppercase block">Assigned Brokers</span>
                        <span class="text-sm font-black text-gray-900">{{ $rm->brokers_count ?? 0 }} Partners</span>
                    </div>
                    <button onclick="viewRmBrokers('{{ $rm->id }}')" class="bg-brand-50 hover:bg-brand-100 text-brand-dark px-3 py-1.5 rounded-xl text-xs font-bold tap-effect flex items-center gap-1.5 border border-brand-200 transition cursor-pointer">
                        <i class="fas fa-list-check text-brand"></i> View Details
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                <i class="fas fa-users-slash text-4xl text-gray-300 mb-3"></i>
                <h3 class="font-bold text-gray-900 text-base mb-1">No Relationship Managers Configured</h3>
                <p class="text-xs text-gray-500 mb-4">Add your first RM to start allocating broker partner portfolios.</p>
                <button onclick="openAddRmModal()" class="bg-brand hover:bg-brand-dark text-white font-bold px-4 py-2 rounded-xl text-xs tap-effect inline-flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add Relationship Manager
                </button>
            </div>
        @endforelse
    </div>

</div>

<!-- Add RM Modal -->
<div id="addRmModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('addRmModal')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 text-lg">
            <i class="fas fa-times"></i>
        </button>
        <h2 class="text-xl font-bold text-gray-900 mb-1">Add Relationship Manager</h2>
        <p class="text-xs text-gray-500 mb-6">Enter new RM details to assign to partner brokers.</p>

        <form action="{{ route('admin.relationship-managers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white" placeholder="e.g. Priya Sharma">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white" placeholder="priya@staynest.com">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white" placeholder="+91 98765 43210">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Designation <span class="text-red-500">*</span></label>
                    <input type="text" name="designation" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white" placeholder="Senior Relationship Manager">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Regional Zone <span class="text-red-500">*</span></label>
                    <input type="text" name="zone" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white" placeholder="Noida / Delhi NCR">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/50 focus:bg-white" placeholder="919876543210">
            </div>
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_default_add" name="is_default" value="1" class="rounded text-brand focus:ring-brand w-4 h-4">
                <label for="is_default_add" class="text-xs text-gray-700 font-medium">Set as Default RM for newly registered brokers</label>
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="closeModal('addRmModal')" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold">Cancel</button>
                <button type="submit" class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-2.5 rounded-xl text-xs shadow-sm">Save RM</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddRmModal() {
    document.getElementById('addRmModal').classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function editRmDirect(rmId) {
    window.location.href = "{{ route('admin.brokers') }}";
}
function viewRmBrokers(rmId) {
    window.location.href = "{{ route('admin.brokers') }}";
}
function deleteRmDirect(rmId, name) {
    if (!confirm(`Are you sure you want to remove Relationship Manager "${name}"?`)) return;
    fetch(`/admin/relationship-managers/${rmId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(res => res.json()).then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error deleting RM');
        }
    });
}
</script>
@endsection
