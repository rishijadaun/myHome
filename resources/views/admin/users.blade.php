@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
<!-- Header -->
<header class="hidden lg:flex bg-white border-b border-gray-100 px-8 py-4 items-center justify-between sticky top-0 z-30 shadow-xs">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Registered Users</h1>
        <p class="text-sm text-gray-500">{{ number_format($totalUsers) }} registered accounts across tenants, brokers and administrators</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('adminAddUserModal')" class="bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-2.5 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 hover:shadow-xl transition flex items-center gap-2 cursor-pointer">
            <i class="fas fa-user-plus text-sm"></i> Add New User
        </button>
    </div>
</header>

<div class="p-4 md:p-8 space-y-6">

    <!-- Flash Alert / Toast Anchor -->
    <div id="userToastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="userToastInner" class="flex items-center gap-3 px-5 py-3.5 bg-gray-900 text-white rounded-2xl shadow-2xl border border-gray-800 text-sm font-medium">
            <span id="userToastIcon"><i class="fas fa-check-circle text-emerald-400"></i></span>
            <span id="userToastMessage">Action completed</span>
        </div>
    </div>

    <!-- Mobile Add Button -->
    <div class="lg:hidden">
        <button onclick="openModal('adminAddUserModal')" class="w-full bg-gradient-to-r from-brand to-brand-dark text-white px-5 py-3 rounded-xl font-semibold tap-effect shadow-lg shadow-brand/30 flex items-center justify-center gap-2 cursor-pointer">
            <i class="fas fa-user-plus"></i> Add New User
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.users') }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-brand/40 transition">
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900">{{ number_format($totalUsers) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Total Registered Users</div>
        </a>
        <a href="{{ route('admin.users', ['role' => 'tenant', 'status' => 'active']) }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-emerald-300 transition">
            <div class="text-2xl md:text-3xl font-extrabold text-emerald-600">{{ number_format($activeTenants) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Active Tenants</div>
        </a>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="text-2xl md:text-3xl font-extrabold text-brand">{{ number_format($newThisMonth) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">New This Month</div>
        </div>
        <a href="{{ route('admin.users', ['status' => 'blocked']) }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm cursor-pointer hover:border-red-300 transition">
            <div class="text-2xl md:text-3xl font-extrabold text-red-600">{{ number_format($suspendedUsers) }}</div>
            <div class="text-xs text-gray-500 mt-1 font-medium">Suspended / Blocked</div>
        </a>
    </div>

    <!-- Search & Filter Form -->
    <form method="GET" action="{{ route('admin.users') }}" id="adminUserFilterForm" class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                <input 
                    type="text" 
                    name="search" 
                    id="userSearch" 
                    value="{{ request('search') }}" 
                    placeholder="Search users by name, email, phone..." 
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:bg-white transition"
                >
            </div>
            
            <!-- Role Selector -->
            <select name="role" id="userRoleFilter" onchange="document.getElementById('adminUserFilterForm').submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition">
                <option value="">All Account Roles</option>
                <option value="tenant" {{ request('role') == 'tenant' ? 'selected' : '' }}>Tenants</option>
                <option value="broker" {{ request('role') == 'broker' ? 'selected' : '' }}>Brokers</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admins</option>
            </select>

            <!-- Status Selector -->
            <select name="status" id="userStatusFilter" onchange="document.getElementById('adminUserFilterForm').submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-700 transition">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked / Suspended</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Verification</option>
            </select>

            @if(request()->hasAny(['search', 'role', 'status']) && (request('search') || request('role') || request('status')))
                <a href="{{ route('admin.users') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5" title="Clear Filters">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </div>
    </form>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="userTable">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">User Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Email Address</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Joined Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="userBody">
                    @forelse($users as $user)
                        @php
                            $fullName = $user->profile?->full_name ?? ($user->name ?? $user->email);
                            $firstName = $user->profile?->first_name ?? $fullName;
                            $lastName = $user->profile?->last_name ?? '';
                            $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                            if (empty(trim($initials))) $initials = 'US';

                            $role = $user->roles->first();
                            $roleSlug = $role ? $role->slug : 'tenant';
                            $roleName = $role ? $role->name : 'Tenant';

                            $roleBadgeClass = 'bg-blue-50 text-blue-600';
                            if ($roleSlug === 'broker') {
                                $roleBadgeClass = 'bg-purple-50 text-purple-600';
                            } elseif ($roleSlug === 'admin') {
                                $roleBadgeClass = 'bg-emerald-50 text-emerald-700';
                            } elseif ($roleSlug === 'super_admin') {
                                $roleBadgeClass = 'bg-gray-900 text-white';
                            }

                            $activity = '0 Bookings';
                            if ($roleSlug === 'tenant') {
                                $count = $user->bookings->count();
                                $activity = "{$count} " . ($count === 1 ? 'Booking' : 'Bookings');
                            } elseif ($roleSlug === 'broker') {
                                $count = $user->properties->count();
                                $activity = "{$count} Listed PGs";
                            } elseif (in_array($roleSlug, ['admin', 'super_admin'])) {
                                $activity = 'Platform Admin';
                            }

                            $isActive = ($user->status === 'active' && $user->is_active);
                            $isPending = ($user->status === 'pending_verification');
                        @endphp
                        <tr id="user-row-{{ $user->id }}" class="user-row hover:bg-gray-50/70 transition">
                            <!-- Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 truncate user-name">{{ $fullName }}</div>
                                        <div class="text-xs text-gray-400 user-phone">{{ $user->phone ?? 'No Phone' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 text-sm text-gray-600 user-email font-mono">
                                {{ $user->email }}
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4">
                                <span class="{{ $roleBadgeClass }} text-[11px] font-bold px-2.5 py-1 rounded-lg uppercase">
                                    {{ $roleName }}
                                </span>
                            </td>

                            <!-- Joined Date -->
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Recent' }}
                            </td>

                            <!-- Activity -->
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ $activity }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <span id="status-badge-{{ $user->id }}" class="status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase {{ $isActive ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                    {{ $isActive ? 'ACTIVE' : ($isPending ? 'PENDING' : 'BLOCKED') }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Profile -->
                                    <button type="button" onclick="viewUserDetails('{{ $user->id }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center tap-effect cursor-pointer" title="View Profile">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    <!-- Reset Password -->
                                    <button type="button" onclick="openResetPasswordModal('{{ $user->id }}', '{{ addslashes($fullName) }}')" class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center tap-effect cursor-pointer" title="Reset Password">
                                        <i class="fas fa-key text-xs"></i>
                                    </button>

                                    <!-- Toggle Block / Active -->
                                    <button type="button" onclick="toggleUserBlockDirect('{{ $user->id }}', '{{ addslashes($fullName) }}')" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100 flex items-center justify-center tap-effect cursor-pointer" title="Toggle Block / Active">
                                        <i class="fas fa-ban text-xs"></i>
                                    </button>

                                    <!-- Delete -->
                                    <button type="button" onclick="deleteUserDirect('{{ $user->id }}', '{{ addslashes($fullName) }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center tap-effect cursor-pointer" title="Delete User">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-2 text-xl">
                                    <i class="fas fa-users-slash"></i>
                                </div>
                                <div class="text-sm font-semibold text-gray-600">No users found</div>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting your search criteria or add a new user.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Showing <span class="font-bold text-gray-900">{{ $users->firstItem() }}</span> to <span class="font-bold text-gray-900">{{ $users->lastItem() }}</span> of <span class="font-bold text-gray-900">{{ $users->total() }}</span> registered users
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Mobile Cards View -->
    <div class="md:hidden space-y-3" id="userMobileList">
        @forelse($users as $user)
            @php
                $fullName = $user->profile?->full_name ?? ($user->name ?? $user->email);
                $firstName = $user->profile?->first_name ?? $fullName;
                $lastName = $user->profile?->last_name ?? '';
                $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                if (empty(trim($initials))) $initials = 'US';

                $role = $user->roles->first();
                $roleSlug = $role ? $role->slug : 'tenant';
                $roleName = $role ? $role->name : 'Tenant';

                $isActive = ($user->status === 'active' && $user->is_active);
                $isPending = ($user->status === 'pending_verification');
            @endphp
            <div id="user-mobile-card-{{ $user->id }}" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm user-card space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                        {{ $initials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-1">
                            <h3 class="font-bold text-gray-900 user-name text-sm truncate">{{ $fullName }}</h3>
                            <span id="mobile-status-badge-{{ $user->id }}" class="status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 {{ $isActive ? 'bg-emerald-100 text-emerald-700' : ($isPending ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                                {{ $isActive ? 'ACTIVE' : ($isPending ? 'PENDING' : 'BLOCKED') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 user-email font-mono truncate">{{ $user->email }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">{{ $roleName }}</span>
                            <span class="text-xs text-gray-400">{{ $user->phone ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="viewUserDetails('{{ $user->id }}')" class="flex-1 bg-blue-50 text-blue-600 text-xs font-semibold py-2 rounded-lg tap-effect text-center">
                        <i class="fas fa-eye mr-1"></i> Profile
                    </button>
                    <button type="button" onclick="openResetPasswordModal('{{ $user->id }}', '{{ addslashes($fullName) }}')" class="flex-1 bg-purple-50 text-purple-600 text-xs font-semibold py-2 rounded-lg tap-effect text-center">
                        <i class="fas fa-key mr-1"></i> Pass
                    </button>
                    <button type="button" onclick="toggleUserBlockDirect('{{ $user->id }}', '{{ addslashes($fullName) }}')" class="flex-1 bg-yellow-50 text-yellow-700 text-xs font-semibold py-2 rounded-lg tap-effect text-center">
                        <i class="fas fa-ban mr-1"></i> Block
                    </button>
                    <button type="button" onclick="deleteUserDirect('{{ $user->id }}', '{{ addslashes($fullName) }}')" class="w-9 bg-red-50 text-red-600 text-xs font-semibold py-2 rounded-lg tap-effect flex items-center justify-center">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-400 bg-white rounded-2xl border border-gray-100">
                <i class="fas fa-users-slash text-2xl text-gray-300 mb-2"></i>
                <div class="text-sm font-semibold text-gray-600">No users found</div>
            </div>
        @endforelse
    </div>

</div>

<!-- 1. Add New User Modal -->
<div id="adminAddUserModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full max-h-[92vh] overflow-y-auto shadow-2xl animate-scale-up">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Add New User</h3>
                <p class="text-xs text-gray-500">Create a new tenant, broker or administrator account</p>
            </div>
            <button onclick="closeModal('adminAddUserModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200 cursor-pointer">
                <i class="fas fa-times text-gray-500 text-sm"></i>
            </button>
        </div>

        <form id="adminAddUserForm" onsubmit="handleCreateUser(event)" class="p-6 space-y-4">
            <!-- First & Last Name -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">First Name *</label>
                    <input type="text" name="first_name" required placeholder="e.g. Rahul" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Last Name</label>
                    <input type="text" name="last_name" placeholder="e.g. Sharma" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <!-- Email & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address *</label>
                    <input type="email" name="email" required placeholder="user@gmail.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number *</label>
                    <input type="text" name="phone" required placeholder="+91 98765 43210" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40">
                </div>
            </div>

            <!-- Role & Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Account Role *</label>
                    <select name="role_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-800">
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->slug }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Initial Password *</label>
                    <input type="text" name="password" value="User@123" required minlength="6" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 font-mono">
                </div>
            </div>

            <!-- Status Selector -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Initial Status</label>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 text-gray-800">
                    <option value="active">Active (Verified)</option>
                    <option value="suspended">Suspended / Blocked</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('adminAddUserModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl tap-effect cursor-pointer">Cancel</button>
                <button type="submit" id="submitAddUserBtn" class="flex-1 bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-3 rounded-xl tap-effect shadow-lg shadow-brand/30 cursor-pointer">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. User Profile & Activity Modal -->
<div id="userDetailModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden animate-scale-up">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">User Account Profile</h3>
            <button onclick="closeModal('userDetailModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200 cursor-pointer">
                <i class="fas fa-times text-gray-500 text-xs"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 text-sm" id="userProfileModalBody">
            <div class="flex justify-center py-8">
                <i class="fas fa-circle-notch fa-spin text-brand text-2xl"></i>
            </div>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-3xl text-right">
            <button onclick="closeModal('userDetailModal')" class="px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs rounded-xl tap-effect cursor-pointer">Close</button>
        </div>
    </div>
</div>

<!-- 3. Reset Password Modal -->
<div id="adminResetPasswordModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl p-6 space-y-4 animate-scale-up">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Set New Password</h3>
                <p id="resetPasswordUserName" class="text-xs text-gray-500">For user account</p>
            </div>
            <button onclick="closeModal('adminResetPasswordModal')" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center tap-effect hover:bg-gray-200 cursor-pointer">
                <i class="fas fa-times text-gray-500 text-xs"></i>
            </button>
        </div>

        <form id="adminResetPasswordForm" onsubmit="handleResetPassword(event)" class="space-y-4">
            <input type="hidden" id="resetPasswordUserId" name="user_id">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">New Password *</label>
                <input type="text" id="resetNewPasswordInput" name="new_password" required minlength="6" value="StayNest@2026" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 font-mono">
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('adminResetPasswordModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-xs tap-effect cursor-pointer">Cancel</button>
                <button type="submit" id="submitResetPassBtn" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded-xl text-xs tap-effect shadow-md shadow-purple-500/20 cursor-pointer">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    // Dynamic Toast Messenger
    function showUserToast(message, type = 'success') {
        const toast = document.getElementById('userToastNotification');
        const text = document.getElementById('userToastMessage');
        const icon = document.getElementById('userToastIcon');

        text.textContent = message;
        if (type === 'success') {
            icon.innerHTML = '<i class="fas fa-check-circle text-emerald-400 text-base"></i>';
        } else {
            icon.innerHTML = '<i class="fas fa-exclamation-circle text-red-400 text-base"></i>';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3200);
    }

    // View User Details (AJAX)
    async function viewUserDetails(userId) {
        openModal('userDetailModal');
        const bodyEl = document.getElementById('userProfileModalBody');

        bodyEl.innerHTML = `
            <div class="flex justify-center py-8">
                <i class="fas fa-circle-notch fa-spin text-brand text-2xl"></i>
            </div>
        `;

        try {
            const res = await fetch(`/admin/users/${userId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (res.ok && data.success && data.user) {
                const u = data.user;
                bodyEl.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 bg-gray-50 p-3.5 rounded-2xl">
                            <div class="w-12 h-12 bg-gradient-to-br from-brand to-brand-dark text-white rounded-full flex items-center justify-center font-bold text-base shadow-xs">
                                ${u.name.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 text-base truncate">${u.name}</div>
                                <div class="text-xs text-gray-500 font-mono truncate">${u.email}</div>
                            </div>
                        </div>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Phone</span>
                                <span class="font-bold text-gray-900 font-mono">${u.phone}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Account Role</span>
                                <span class="font-bold text-brand">${u.role}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Platform Activity</span>
                                <span class="font-bold text-gray-900">${u.activity}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Wallet Balance</span>
                                <span class="font-bold text-gray-900 font-mono">₹${Number(u.wallet_balance).toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Member Since</span>
                                <span class="font-medium text-gray-700">${u.joined_at}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500 font-medium">Account Status</span>
                                <span class="font-bold ${u.is_active ? 'text-emerald-600' : 'text-red-600'}">${u.status}</span>
                            </div>
                        </div>
                    </div>
                `;
            }
        } catch (err) {
            console.error(err);
            bodyEl.innerHTML = `<div class="text-center text-red-500 text-xs py-4">Failed to load user profile.</div>`;
        }
    }

    // Toggle Block / Suspend
    async function toggleUserBlockDirect(userId, userName) {
        if (!confirm(`Switch account status for ${userName}?`)) return;

        try {
            const res = await fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showUserToast(data.message, 'success');

                const statusBadge = document.getElementById(`status-badge-${userId}`);
                const mobileStatus = document.getElementById(`mobile-status-badge-${userId}`);

                if (data.is_active) {
                    if (statusBadge) {
                        statusBadge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-emerald-100 text-emerald-700';
                        statusBadge.textContent = 'ACTIVE';
                    }
                    if (mobileStatus) {
                        mobileStatus.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-emerald-100 text-emerald-700';
                        mobileStatus.textContent = 'ACTIVE';
                    }
                } else {
                    if (statusBadge) {
                        statusBadge.className = 'status-badge text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase bg-red-100 text-red-700';
                        statusBadge.textContent = 'BLOCKED';
                    }
                    if (mobileStatus) {
                        mobileStatus.className = 'status-badge text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0 bg-red-100 text-red-700';
                        mobileStatus.textContent = 'BLOCKED';
                    }
                }
            } else {
                showUserToast(data.message || 'Failed to update user status', 'error');
            }
        } catch (err) {
            console.error(err);
            showUserToast('Network error while updating status', 'error');
        }
    }

    // Open Reset Password Modal
    function openResetPasswordModal(userId, userName) {
        document.getElementById('resetPasswordUserId').value = userId;
        document.getElementById('resetPasswordUserName').textContent = `For ${userName}`;
        document.getElementById('resetNewPasswordInput').value = 'StayNest@2026';
        openModal('adminResetPasswordModal');
    }

    // Handle Reset Password Submit
    async function handleResetPassword(e) {
        e.preventDefault();
        const userId = document.getElementById('resetPasswordUserId').value;
        const newPassword = document.getElementById('resetNewPasswordInput').value;
        const submitBtn = document.getElementById('submitResetPassBtn');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1"></i> Saving...';

        try {
            const res = await fetch(`/admin/users/${userId}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ new_password: newPassword })
            });
            const data = await res.json();

            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Password';

            if (res.ok && data.success) {
                showUserToast(data.message, 'success');
                closeModal('adminResetPasswordModal');
            } else {
                showUserToast(data.message || 'Failed to reset password', 'error');
            }
        } catch (err) {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Password';
            showUserToast('Connection error. Please try again.', 'error');
        }
    }

    // Delete User
    async function deleteUserDirect(userId, userName) {
        if (!confirm(`Are you sure you want to permanently remove user account for "${userName}"?`)) return;

        try {
            const res = await fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showUserToast(data.message, 'success');

                const row = document.getElementById(`user-row-${userId}`);
                if (row) {
                    row.style.opacity = '0';
                    row.style.transform = 'scale(0.95)';
                    setTimeout(() => row.remove(), 250);
                }

                const card = document.getElementById(`user-mobile-card-${userId}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 250);
                }
            } else {
                showUserToast(data.message || 'Failed to delete user', 'error');
            }
        } catch (err) {
            console.error(err);
            showUserToast('Network error while deleting user', 'error');
        }
    }

    // Handle Create User Form Submit (AJAX)
    async function handleCreateUser(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('submitAddUserBtn');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1"></i> Creating User...';

        try {
            const res = await fetch('{{ route('admin.users.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showUserToast(data.message || 'User created successfully!', 'success');
                closeModal('adminAddUserModal');
                form.reset();

                setTimeout(() => window.location.reload(), 800);
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create User';
                let errMsg = data.message || 'Failed to create user';
                if (data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    if (firstKey && data.errors[firstKey][0]) {
                        errMsg = data.errors[firstKey][0];
                    }
                }
                showUserToast(errMsg, 'error');
            }
        } catch (err) {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create User';
            showUserToast('Connection error. Please try again.', 'error');
        }
    }
</script>
@endpush
