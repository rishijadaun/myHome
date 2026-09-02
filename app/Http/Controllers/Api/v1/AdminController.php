<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Notification;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponse;

    /**
     * Ensure the authenticated user has active administrator privileges.
     */
    protected function authorizeAdmin(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->roles()->whereIn('slug', ['super_admin', 'admin'])->exists() || $user->status !== 'active') {
            return $this->error('Forbidden. Administrator privileges required.', [], 403);
        }
        return null;
    }

    public function dashboard(Request $request)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $totalProperties = Property::count();
        $approvedProperties = Property::where('verification_status', 'verified')->count();
        $pendingProperties = Property::where('verification_status', 'pending')->count();
        $totalUsers = User::count();

        return $this->success('Admin dashboard loaded', [
            'stats' => [
                'total_properties' => $totalProperties,
                'approved_properties' => $approvedProperties,
                'pending_properties' => $pendingProperties,
                'total_users' => $totalUsers,
            ],
            'message' => 'Admin stats loaded successfully.',
        ]);
    }

    public function users(Request $request)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $users = User::with('profile', 'roles')->latest()->paginate(20);
        return $this->success('User list fetched', $users);
    }

    /**
     * Get All Properties (with filters for listing_type, status, verification_status, city)
     */
    public function properties(Request $request)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $query = Property::with(['propertyType', 'city', 'area', 'broker.profile', 'primaryImage']);

        if ($request->filled('type')) {
            $typeSlug = $request->query('type');
            $query->whereHas('propertyType', function ($q) use ($typeSlug) {
                $q->where('slug', $typeSlug)->orWhere('name', 'like', "%{$typeSlug}%");
            });
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->query('verification_status'));
        }

        if ($request->filled('city')) {
            $city = $request->query('city');
            $query->whereHas('city', function ($q) use ($city) {
                $q->where('slug', $city)->orWhere('name', 'like', "%{$city}%");
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('broker.profile', function ($b) use ($search) {
                      $b->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $properties = $query->latest()->paginate(15);
        return $this->success('Properties fetched for admin moderation', $properties);
    }

    /**
     * Approve Property (1-Click Verification)
     */
    public function approveProperty(Request $request, $id)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $property = Property::findOrFail($id);
        $property->verification_status = 'verified';
        $property->status = 'active';
        $property->is_active = 1;
        $property->save();

        // Notify Broker / Owner
        if ($property->broker_id) {
            Notification::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $property->broker_id,
                'user_type' => 'broker',
                'title' => 'Property Approved & Published 🎉',
                'message' => "Your listing \"{$property->name}\" has been approved and is now live on StayNest.",
                'type' => 'property_approved',
                'is_read' => 0,
                'action_url' => '/broker/pgs',
            ]);
        }

        return $this->success('Property approved and published successfully!', $property);
    }

    /**
     * Reject Property
     */
    public function rejectProperty(Request $request, $id)
    {
        if ($deny = $this->authorizeAdmin($request)) return $deny;

        $property = Property::findOrFail($id);
        $property->verification_status = 'rejected';
        $property->status = 'inactive';
        $property->save();

        if ($property->broker_id) {
            Notification::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $property->broker_id,
                'user_type' => 'broker',
                'title' => 'Property Verification Update',
                'message' => "Your listing \"{$property->name}\" requires corrections before it can be published.",
                'type' => 'property_rejected',
                'is_read' => 0,
                'action_url' => '/broker/pgs',
            ]);
        }

        return $this->success('Property marked as rejected.', $property);
    }
}
