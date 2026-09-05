<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all registered users with filters and stats.
     */
    public function index(Request $request)
    {
        // 1. Live Counters
        $totalUsers = User::count();
        
        $activeTenants = User::whereHas('roles', function ($q) {
            $q->where('slug', 'tenant');
        })->where('status', 'active')->count();

        $newThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();

        $suspendedUsers = User::where(function ($q) {
            $q->where('status', 'suspended')
              ->orWhere('status', 'inactive')
              ->orWhere('status', 'blocked')
              ->orWhere('is_active', 0);
        })->count();

        // 2. Query Builder
        $query = User::with(['profile', 'roles'])->withCount(['bookings', 'properties']);

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($p) use ($search) {
                      $p->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                  });
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $roleSlug = strtolower(trim($request->query('role')));
            $query->whereHas('roles', function ($r) use ($roleSlug) {
                $r->where('slug', $roleSlug)
                  ->orWhere('name', 'like', "%{$roleSlug}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $status = strtolower(trim($request->query('status')));
            if ($status === 'active') {
                $query->where('status', 'active')->where('is_active', 1);
            } elseif ($status === 'blocked' || $status === 'suspended') {
                $query->where(function ($q) {
                    $q->where('status', 'suspended')
                      ->orWhere('status', 'blocked')
                      ->orWhere('is_active', 0);
                });
            } elseif ($status === 'pending') {
                $query->where('status', 'pending_verification');
            }
        }

        $users = $query->latest('created_at')->paginate(15)->withQueryString();
        $roles = Role::where('is_active', 1)->orderBy('level', 'desc')->get();

        return view('admin.users', compact(
            'users',
            'roles',
            'totalUsers',
            'activeTenants',
            'newThisMonth',
            'suspendedUsers'
        ));
    }

    /**
     * Store a newly created user from Admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'role_id' => ['required', 'string', 'exists:roles,id'],
            'status' => ['nullable', 'string', 'in:active,suspended,pending_verification'],
        ], [
            'email.unique' => 'A user with this email address already exists.',
            'phone.unique' => 'A user with this phone number already exists.',
        ]);

        $status = $validated['status'] ?? 'active';
        $isActive = ($status === 'active');

        // 1. Create User
        $user = User::create([
            'id' => (string) Str::uuid(),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'password_hash' => Hash::make($validated['password']),
            'status' => $status,
            'is_active' => $isActive,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'kyc_verified_at' => now(),
            'version' => 1,
        ]);

        // 2. Create User Profile
        $fullName = trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? ''));
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? '',
            'full_name' => $fullName,
            'is_active' => true,
            'version' => 1,
        ]);

        // 3. Assign Role
        UserRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'role_id' => $validated['role_id'],
            'is_primary' => true,
            'is_active' => true,
            'version' => 1,
        ]);

        // 4. Create Wallet
        Wallet::firstOrCreate(['user_id' => $user->id], [
            'id' => (string) Str::uuid(),
            'balance' => 0.00,
            'currency_code' => 'INR',
            'is_active' => true,
            'version' => 1,
        ]);

        // 5. Send Notification
        $role = Role::find($validated['role_id']);
        $notifUserType = 'user';
        if ($role && in_array($role->slug, ['broker', 'commercial_owner', 'landlord', 'owner'])) {
            $notifUserType = 'broker';
        } elseif ($role && in_array($role->slug, ['super_admin', 'admin'])) {
            $notifUserType = 'admin';
        }

        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'user_type' => $notifUserType,
            'title' => 'Welcome to SpaceSeeks 🎉',
            'message' => 'Your account has been created by SpaceSeeks administrator.',
            'type' => 'account_created',
            'is_read' => 0,
            'action_url' => '/',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "User \"{$fullName}\" created successfully!",
                'user' => $user->load(['profile', 'roles']),
            ]);
        }

        return redirect()->route('admin.users')->with('success', "User \"{$fullName}\" created successfully!");
    }

    /**
     * Show single user details and activity.
     */
    public function show($id)
    {
        $user = User::with(['profile', 'roles', 'bookings.property', 'properties', 'wallet'])
            ->findOrFail($id);

        $primaryRole = $user->roles->first();
        $roleSlug = $primaryRole ? $primaryRole->slug : 'tenant';
        $roleName = $primaryRole ? $primaryRole->name : 'Tenant';

        $activitySummary = 'No recent activity';
        if ($roleSlug === 'tenant') {
            $count = $user->bookings->count();
            $activitySummary = "{$count} Bookings";
        } elseif ($roleSlug === 'broker') {
            $count = $user->properties->count();
            $activitySummary = "{$count} Listed PGs";
        } elseif (in_array($roleSlug, ['admin', 'super_admin'])) {
            $activitySummary = 'Platform Administrator';
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->profile ? $user->profile->full_name : $user->email,
                'email' => $user->email,
                'phone' => $user->phone ?? 'Not provided',
                'role' => strtoupper($roleName),
                'role_slug' => $roleSlug,
                'activity' => $activitySummary,
                'wallet_balance' => $user->wallet ? $user->wallet->balance : 0,
                'status' => strtoupper($user->status),
                'is_active' => (bool) $user->is_active,
                'joined_at' => $user->created_at ? $user->created_at->format('M d, Y') : 'Recent',
                'bookings' => $user->bookings->take(5)->map(fn($b) => [
                    'id' => $b->id,
                    'booking_number' => $b->booking_number,
                    'property' => $b->property ? $b->property->name : 'Stay',
                    'amount' => $b->total_amount,
                    'status' => $b->status,
                ]),
                'properties' => $user->properties->take(5)->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'rent' => $p->monthly_rent,
                    'status' => $p->status,
                ]),
            ]
        ]);
    }

    /**
     * Toggle user status between Active and Blocked/Suspended.
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent self blocking
        if (Auth::id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot suspend your own active administrator account.',
            ], 422);
        }

        if ($user->status === 'active') {
            $user->status = 'suspended';
            $user->is_active = false;
        } else {
            $user->status = 'active';
            $user->is_active = true;
        }

        $user->save();

        $name = $user->profile ? $user->profile->full_name : $user->email;

        return response()->json([
            'success' => true,
            'status' => strtoupper($user->status),
            'is_active' => (bool) $user->is_active,
            'message' => "Account for \"{$name}\" is now {$user->status}.",
        ]);
    }

    /**
     * Reset / Update user password from admin console.
     */
    public function resetPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'new_password' => ['nullable', 'string', 'min:6', 'max:100'],
            'password' => ['nullable', 'string', 'min:6', 'max:100'],
        ]);

        $newPassword = $validated['new_password'] ?? $validated['password'] ?? null;
        if (!$newPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid new password with minimum 6 characters.',
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->password_hash = Hash::make($newPassword);
        $user->save();

        $name = $user->profile ? $user->profile->full_name : $user->email;

        return response()->json([
            'success' => true,
            'message' => "Password for \"{$name}\" has been updated successfully.",
        ]);
    }

    /**
     * Soft delete user account.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent self deletion
        if (Auth::id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own active administrator account.',
            ], 422);
        }

        $name = $user->profile ? $user->profile->full_name : $user->email;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "User account \"{$name}\" was removed successfully.",
        ]);
    }
}
