<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPropertyController extends Controller
{
    /**
     * Display a listing of all properties with filters and metrics.
     */
    public function index(Request $request)
    {
        // 1. Live Statistics
        $totalCount = Property::count();
        $approvedCount = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->count();
        $pendingCount = Property::where('verification_status', 'pending')->count();
        $inactiveCount = Property::where(function ($q) {
            $q->where('status', 'inactive')
              ->orWhere('verification_status', 'rejected')
              ->orWhere('status', 'draft');
        })->count();

        // 2. Query Builder with Eager Loading
        $query = Property::with([
            'broker.profile',
            'city',
            'area',
            'propertyType',
            'primaryImage',
            'images',
        ]);

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('landmark', 'like', "%{$search}%")
                  ->orWhereHas('city', fn($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('area', fn($a) => $a->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('broker.profile', function ($b) use ($search) {
                      $b->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('broker', fn($u) => $u->where('email', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
            });
        }

        // Type / Gender Filter
        if ($request->filled('type')) {
            $type = $request->query('type');
            if (in_array(strtoupper($type), ['BOYS', 'GIRLS', 'CO-ED', 'COED'])) {
                $gender = strtolower($type) === 'co-ed' || strtolower($type) === 'coed' ? 'co-ed' : strtolower($type);
                $query->where('gender_preference', $gender);
            } else {
                $query->where(function ($q) use ($type) {
                    $q->whereHas('propertyType', function ($pt) use ($type) {
                        $pt->where('slug', $type)
                           ->orWhere('name', 'like', "%{$type}%");
                    })->orWhere('gender_preference', strtolower($type));
                });
            }
        }

        // City Filter
        if ($request->filled('city')) {
            $city = $request->query('city');
            $query->where(function ($q) use ($city) {
                $q->where('city_id', $city)
                  ->orWhereHas('city', function ($c) use ($city) {
                      $c->where('slug', $city)->orWhere('name', $city);
                  });
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $status = strtoupper($request->query('status'));
            if ($status === 'APPROVED' || $status === 'VERIFIED') {
                $query->where('verification_status', 'verified')->where('status', 'active');
            } elseif ($status === 'PENDING') {
                $query->where('verification_status', 'pending');
            } elseif ($status === 'SUSPENDED' || $status === 'INACTIVE' || $status === 'REJECTED') {
                $query->where(function ($q) {
                    $q->where('status', 'inactive')
                      ->orWhere('verification_status', 'rejected')
                      ->orWhere('status', 'draft');
                });
            }
        }

        // Tag / Badge Filter
        if ($request->filled('tag')) {
            $tagFilter = trim($request->query('tag'));
            if (strtolower($tagFilter) === 'untagged' || strtolower($tagFilter) === 'none') {
                $query->where(function ($q) {
                    $q->whereNull('tag')->orWhere('tag', '');
                });
            } else {
                $query->where('tag', 'like', "%{$tagFilter}%");
            }
        }

        // 3. Paginated Results
        $properties = $query->latest()->paginate(15)->withQueryString();

        // 4. Dropdown Options
        $cities = City::where('is_active', 1)->orderBy('name')->get();
        $propertyTypes = PropertyType::where('is_active', 1)->orderBy('name')->get();
        $allowedTags = Property::ALLOWED_TAGS;

        $brokerRole = Role::where('slug', 'broker')->first();
        $brokers = $brokerRole ? $brokerRole->users()->with('profile')->orderBy('email')->get() : collect();

        return view('admin.pgs', compact(
            'properties',
            'cities',
            'propertyTypes',
            'brokers',
            'allowedTags',
            'totalCount',
            'approvedCount',
            'pendingCount',
            'inactiveCount'
        ));
    }

    /**
     * Store a newly created property from Admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'broker_id' => ['required', 'string'],
            'city_id' => ['required', 'string'],
            'property_type_id' => ['nullable', 'string'],
            'tag' => ['nullable', 'string', 'max:50'],
            'gender_preference' => ['required', 'string', 'in:boys,girls,co-ed,male,female,any'],
            'total_beds' => ['required', 'integer', 'min:1', 'max:500'],
            'monthly_rent' => ['required', 'numeric', 'min:500'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'address' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'instant_approve' => ['nullable', 'boolean'],
            'image_url' => ['nullable', 'string', 'url'],
        ]);

        // Auto resolve default property type if not passed
        $propertyTypeId = $validated['property_type_id'] ?? null;
        if (!$propertyTypeId) {
            $defaultType = PropertyType::where('slug', 'pg')->orWhere('slug', 'co-living')->first();
            $propertyTypeId = $defaultType ? $defaultType->id : null;
        }

        // Auto resolve default area for city
        $area = Area::where('city_id', $validated['city_id'])->first();
        $areaId = $area ? $area->id : null;

        $isInstantApprove = $request->boolean('instant_approve', true);

        $property = Property::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'broker_id' => $validated['broker_id'],
            'city_id' => $validated['city_id'],
            'area_id' => $areaId,
            'property_type_id' => $propertyTypeId,
            'tag' => !empty($validated['tag']) && strtolower($validated['tag']) !== 'none' ? $validated['tag'] : null,
            'gender_preference' => strtolower($validated['gender_preference']),
            'total_beds' => $validated['total_beds'],
            'available_beds' => $validated['total_beds'],
            'monthly_rent' => $validated['monthly_rent'],
            'security_deposit' => $validated['security_deposit'] ?? ($validated['monthly_rent'] * 2),
            'address' => $validated['address'] ?? ($validated['name'] . ', ' . ($area ? $area->name : 'City Center')),
            'landmark' => $validated['landmark'] ?? null,
            'description' => $validated['description'] ?? 'Modern, fully furnished stay with 24/7 power backup, high-speed WiFi, and security.',
            'verification_status' => $isInstantApprove ? 'verified' : 'pending',
            'status' => 'active',
            'is_active' => true,
            'version' => 1,
        ]);

        // Add primary image if URL provided or use default modern stock
        $imageUrl = $validated['image_url'] ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        PropertyImage::create([
            'id' => (string) Str::uuid(),
            'property_id' => $property->id,
            'image_url' => $imageUrl,
            'caption' => $property->name . ' Front View',
            'is_primary' => true,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Send alert to broker
        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $validated['broker_id'],
            'user_type' => 'broker',
            'title' => 'Property Added by Admin 🎉',
            'message' => "Your listing \"{$property->name}\" has been published on StayNest.",
            'type' => 'property_added',
            'is_read' => 0,
            'action_url' => '/broker/pgs',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Property \"{$property->name}\" created and published successfully!",
                'property' => $property,
            ]);
        }

        return redirect()->route('admin.pgs')->with('success', "Property \"{$property->name}\" added successfully!");
    }

    /**
     * Get single property details for view/edit modal.
     */
    public function show($id)
    {
        $property = Property::with(['broker.profile', 'city', 'area', 'propertyType', 'images', 'amenities'])
            ->findOrFail($id);

        $propertyData = $property->toArray();
        $propertyData['tag_meta'] = $property->tag_meta;

        return response()->json([
            'success' => true,
            'property' => $propertyData,
            'allowed_tags' => Property::ALLOWED_TAGS,
        ]);
    }

    /**
     * Update Property Tag / Badge (Popular, Verified, Guest Favourite, Trending, Top rated, New, or None).
     */
    public function updateTag(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'tag' => ['nullable', 'string', 'max:50'],
        ]);

        $rawTag = trim($validated['tag'] ?? '');
        $selectedTag = null;

        if (!empty($rawTag) && strtolower($rawTag) !== 'none' && strtolower($rawTag) !== 'null' && strtolower($rawTag) !== 'untagged') {
            foreach (Property::ALLOWED_TAGS as $key => $meta) {
                if (strcasecmp($rawTag, $key) === 0) {
                    $selectedTag = $key;
                    break;
                }
            }
            if (!$selectedTag) {
                $selectedTag = $rawTag;
            }
        }

        $property->tag = $selectedTag;
        $property->save();

        $tagMeta = $property->tag_meta;

        return response()->json([
            'success' => true,
            'message' => $selectedTag 
                ? "Tag updated to \"{$selectedTag}\" for {$property->name}!" 
                : "Tag removed from {$property->name}.",
            'tag' => $selectedTag,
            'tag_meta' => $tagMeta,
            'property_id' => $property->id,
        ]);
    }

    /**
     * Toggle property active/inactive status.
     */
    public function toggleStatus(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        if ($property->status === 'active') {
            $property->status = 'inactive';
            $property->is_active = false;
        } else {
            $property->status = 'active';
            $property->is_active = true;
        }

        $property->save();

        return response()->json([
            'success' => true,
            'status' => strtoupper($property->status),
            'is_active' => (bool) $property->is_active,
            'message' => "Status for \"{$property->name}\" updated to {$property->status}.",
        ]);
    }

    /**
     * 1-Click Approve Property.
     */
    public function approve(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $property->verification_status = 'verified';
        $property->status = 'active';
        $property->is_active = true;
        $property->save();

        if ($property->broker_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $property->broker_id,
                'user_type' => 'broker',
                'title' => 'Property Approved 🎉',
                'message' => "Your listing \"{$property->name}\" has been approved and published.",
                'type' => 'property_approved',
                'is_read' => 0,
                'action_url' => '/broker/pgs',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Listing \"{$property->name}\" approved and is now live!",
            'property' => $property,
        ]);
    }

    /**
     * Soft delete property.
     */
    public function destroy(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $name = $property->name;
        $property->delete();

        return response()->json([
            'success' => true,
            'message' => "Property \"{$name}\" was removed successfully.",
        ]);
    }
}
