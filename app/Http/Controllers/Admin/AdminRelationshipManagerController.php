<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\RelationshipManager;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminRelationshipManagerController extends Controller
{
    /**
     * List all relationship managers with their active broker workloads.
     */
    public function index(Request $request)
    {
        $managers = RelationshipManager::withCount(['brokers' => function ($q) {
            $q->where('status', 'active');
        }])->orderByDesc('is_default')->orderBy('name')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'managers' => $managers,
            ]);
        }

        return view('admin.relationship_managers', compact('managers'));
    }

    /**
     * Store a new relationship manager.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:relationship_managers,email'],
            'phone' => ['required', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'designation' => ['required', 'string', 'max:120'],
            'zone' => ['required', 'string', 'max:100'],
            'city_coverage' => ['nullable', 'string', 'max:255'],
            'working_hours' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'avatar_url' => ['nullable', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default', false);
        if ($isDefault) {
            RelationshipManager::where('is_default', 1)->update(['is_default' => 0]);
        }

        // Clean WhatsApp number (digits only)
        $whatsapp = !empty($validated['whatsapp_number']) 
            ? preg_replace('/[^0-9]/', '', $validated['whatsapp_number']) 
            : preg_replace('/[^0-9]/', '', $validated['phone']);

        $rmId = (string) Str::uuid();
        $avatarUrl = $validated['avatar_url'] ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80';

        // Handle Avatar File Upload with WebP conversion
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $prefix = 'rm_' . Str::slug($rmId) . '_';
            $processed = app(\App\Services\ImageProcessingService::class)->processUpload($file, 'rm_avatars', $prefix, [
                'max_width' => 500,
                'max_height' => 500,
                'quality' => 85,
            ]);
            if ($processed) {
                $avatarUrl = $processed['relative_url'];
            }
        }

        $manager = RelationshipManager::create([
            'id' => $rmId,
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'whatsapp_number' => $whatsapp,
            'designation' => trim($validated['designation']),
            'zone' => trim($validated['zone']),
            'city_coverage' => $validated['city_coverage'] ?? null,
            'working_hours' => $validated['working_hours'] ?? 'Mon - Sat: 9:00 AM - 7:00 PM',
            'avatar_url' => $avatarUrl,
            'is_active' => true,
            'is_default' => $isDefault,
            'version' => 1,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Relationship Manager \"{$manager->name}\" added successfully!",
                'manager' => $manager,
            ]);
        }

        return back()->with('success', "Relationship Manager \"{$manager->name}\" added successfully!");
    }

    /**
     * Show single Relationship Manager and their assigned brokers.
     */
    public function show($id)
    {
        $manager = RelationshipManager::with(['brokers.profile', 'brokers.properties.city'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'manager' => [
                'id' => $manager->id,
                'name' => $manager->name,
                'email' => $manager->email,
                'phone' => $manager->phone,
                'whatsapp_number' => $manager->whatsapp_number,
                'designation' => $manager->designation,
                'zone' => $manager->zone,
                'city_coverage' => $manager->city_coverage,
                'working_hours' => $manager->working_hours,
                'avatar_url' => $manager->avatar_url,
                'is_active' => (bool) $manager->is_active,
                'is_default' => (bool) $manager->is_default,
                'brokers_count' => $manager->brokers->count(),
                'brokers' => $manager->brokers->map(fn($b) => [
                    'id' => $b->id,
                    'name' => $b->profile ? $b->profile->full_name : $b->email,
                    'email' => $b->email,
                    'phone' => $b->phone,
                    'company' => $b->profile ? ($b->profile->company_name ?? 'Partner') : 'Partner',
                    'city' => $b->properties->pluck('city.name')->filter()->first() ?? 'Regional',
                    'status' => $b->status,
                ]),
            ]
        ]);
    }

    /**
     * Update an existing Relationship Manager.
     */
    public function update(Request $request, $id)
    {
        $manager = RelationshipManager::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:relationship_managers,email,' . $manager->id],
            'phone' => ['required', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'designation' => ['required', 'string', 'max:120'],
            'zone' => ['required', 'string', 'max:100'],
            'city_coverage' => ['nullable', 'string', 'max:255'],
            'working_hours' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'avatar_url' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default', false);
        if ($isDefault && !$manager->is_default) {
            RelationshipManager::where('is_default', 1)->update(['is_default' => 0]);
        }

        $whatsapp = !empty($validated['whatsapp_number']) 
            ? preg_replace('/[^0-9]/', '', $validated['whatsapp_number']) 
            : preg_replace('/[^0-9]/', '', $validated['phone']);

        $avatarUrl = $manager->avatar_url;

        // Handle Avatar File Upload with WebP conversion
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $prefix = 'rm_' . Str::slug($manager->id) . '_';
            $processed = app(\App\Services\ImageProcessingService::class)->processUpload($file, 'rm_avatars', $prefix, [
                'max_width' => 500,
                'max_height' => 500,
                'quality' => 85,
            ]);
            if ($processed) {
                $avatarUrl = $processed['relative_url'];
            }
        } elseif ($request->filled('avatar_url')) {
            $avatarUrl = $request->input('avatar_url');
        }

        $manager->update([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'whatsapp_number' => $whatsapp,
            'designation' => trim($validated['designation']),
            'zone' => trim($validated['zone']),
            'city_coverage' => $validated['city_coverage'] ?? $manager->city_coverage,
            'working_hours' => $validated['working_hours'] ?? $manager->working_hours,
            'avatar_url' => $avatarUrl,
            'is_active' => $request->boolean('is_active', true),
            'is_default' => $isDefault,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Relationship Manager \"{$manager->name}\" updated successfully! 🎉",
                'manager' => $manager,
            ]);
        }

        return back()->with('success', "Relationship Manager \"{$manager->name}\" updated successfully!");
    }

    /**
     * Delete or deactivate Relationship Manager.
     */
    public function destroy(Request $request, $id)
    {
        $manager = RelationshipManager::findOrFail($id);

        // Find fallback default RM
        $defaultRm = RelationshipManager::where('id', '!=', $id)->where('is_active', 1)->orderByDesc('is_default')->first();

        // Reassign brokers to default RM if exists
        if ($defaultRm) {
            User::where('relationship_manager_id', $manager->id)->update(['relationship_manager_id' => $defaultRm->id]);
        } else {
            User::where('relationship_manager_id', $manager->id)->update(['relationship_manager_id' => null]);
        }

        $name = $manager->name;
        $manager->delete();

        return response()->json([
            'success' => true,
            'message' => "Relationship Manager \"{$name}\" removed and assigned brokers were rebalanced.",
        ]);
    }

    /**
     * 1-Click Assign / Reassign a Relationship Manager to a specific Broker.
     */
    public function assignBroker(Request $request, $id)
    {
        $validated = $request->validate([
            'relationship_manager_id' => ['required', 'uuid', 'exists:relationship_managers,id'],
            'notify_broker' => ['nullable', 'boolean'],
        ]);

        $broker = User::with('profile')->findOrFail($id);
        $manager = RelationshipManager::findOrFail($validated['relationship_manager_id']);

        // Update broker user
        $broker->relationship_manager_id = $manager->id;
        $broker->save();

        // Also sync into profile preferences
        if ($broker->profile) {
            $preferences = $broker->profile->preferences ?? [];
            if (!is_array($preferences)) {
                $preferences = json_decode($preferences, true) ?? [];
            }
            $preferences['relationship_manager_id'] = $manager->id;
            $preferences['relationship_manager_name'] = $manager->name;
            $broker->profile->preferences = $preferences;
            $broker->profile->save();
        }

        // Send In-App Notification to Broker
        if ($request->boolean('notify_broker', true)) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $broker->id,
                'user_type' => 'broker',
                'title' => 'Dedicated Relationship Manager Assigned 🤝',
                'message' => "{$manager->name} ({$manager->zone}) has been assigned as your dedicated SpaceSeeks Relationship Manager.",
                'type' => 'rm_assigned',
                'is_read' => 0,
                'action_url' => '/broker/profile',
            ]);
        }

        $brokerName = $broker->profile ? $broker->profile->full_name : $broker->email;

        return response()->json([
            'success' => true,
            'message' => "Assigned {$manager->name} as Relationship Manager for \"{$brokerName}\"!",
            'broker_id' => $broker->id,
            'manager' => [
                'id' => $manager->id,
                'name' => $manager->name,
                'zone' => $manager->zone,
                'designation' => $manager->designation,
                'phone' => $manager->phone,
                'whatsapp' => $manager->whatsapp_number,
                'email' => $manager->email,
            ]
        ]);
    }

    /**
     * Bulk Assign multiple brokers to a Relationship Manager.
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'broker_ids' => ['required', 'array', 'min:1'],
            'broker_ids.*' => ['required', 'uuid', 'exists:users,id'],
            'relationship_manager_id' => ['required', 'uuid', 'exists:relationship_managers,id'],
            'notify_brokers' => ['nullable', 'boolean'],
        ]);

        $manager = RelationshipManager::findOrFail($validated['relationship_manager_id']);
        $brokerIds = $validated['broker_ids'];

        User::whereIn('id', $brokerIds)->update(['relationship_manager_id' => $manager->id]);

        // Sync preferences and send notifications
        $shouldNotify = $request->boolean('notify_brokers', true);
        foreach ($brokerIds as $bId) {
            $profile = UserProfile::where('user_id', $bId)->first();
            if ($profile) {
                $prefs = $profile->preferences ?? [];
                if (!is_array($prefs)) $prefs = json_decode($prefs, true) ?? [];
                $prefs['relationship_manager_id'] = $manager->id;
                $prefs['relationship_manager_name'] = $manager->name;
                $profile->preferences = $prefs;
                $profile->save();
            }

            if ($shouldNotify) {
                Notification::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $bId,
                    'user_type' => 'broker',
                    'title' => 'Dedicated Relationship Manager Assigned 🤝',
                    'message' => "{$manager->name} ({$manager->zone}) has been assigned as your dedicated SpaceSeeks Relationship Manager.",
                    'type' => 'rm_assigned',
                    'is_read' => 0,
                    'action_url' => '/broker/profile',
                ]);
            }
        }

        $count = count($brokerIds);

        return response()->json([
            'success' => true,
            'message' => "Successfully assigned {$count} partner broker(s) to {$manager->name} ({$manager->zone})! 🎉",
            'manager_id' => $manager->id,
            'manager_name' => $manager->name,
            'manager_zone' => $manager->zone,
            'assigned_count' => $count,
        ]);
    }

    /**
     * Smart auto-assign unassigned brokers to Relationship Managers based on their operating cities.
     */
    public function autoAssignByZone(Request $request)
    {
        $brokerRole = Role::where('slug', 'broker')->first();
        if (!$brokerRole) {
            return response()->json(['success' => false, 'message' => 'Broker role not found.'], 404);
        }

        $unassignedBrokers = $brokerRole->users()
            ->whereNull('relationship_manager_id')
            ->with(['profile', 'properties.city'])
            ->get();

        $allRMs = RelationshipManager::where('is_active', 1)->get();
        $defaultRM = $allRMs->where('is_default', 1)->first() ?? $allRMs->first();

        if ($allRMs->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No active Relationship Managers found.'], 422);
        }

        $assignedCount = 0;
        foreach ($unassignedBrokers as $broker) {
            $assignedRM = null;
            $operatingCities = $broker->properties->pluck('city.name')->filter()->unique()->values()->all();
            $cityStr = implode(' ', $operatingCities) . ' ' . ($broker->profile?->preferences['operating_city'] ?? '');

            // Match zone/city coverage
            foreach ($allRMs as $rm) {
                if (!empty($rm->city_coverage)) {
                    $covered = array_map('trim', explode(',', strtolower($rm->city_coverage)));
                    foreach ($covered as $cov) {
                        if (!empty($cov) && stripos($cityStr, $cov) !== false) {
                            $assignedRM = $rm;
                            break 2;
                        }
                    }
                }
            }

            if (!$assignedRM) {
                $assignedRM = $defaultRM;
            }

            $broker->relationship_manager_id = $assignedRM->id;
            $broker->save();
            $assignedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Auto-assigned {$assignedCount} partner broker(s) to regional Relationship Managers based on geographical zones! 🚀",
            'assigned_count' => $assignedCount,
        ]);
    }
}
