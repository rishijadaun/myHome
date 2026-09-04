<?php

namespace App\Http\Controllers\Broker;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrokerPropertyController extends Controller
{
    /**
     * Display a dynamic listing of the broker's PG properties.
     */
    public function index(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return redirect()->route('broker.login');
        }

        $brokerId = $broker->id;

        // Base query for properties belonging to this broker
        $baseQuery = Property::where(function ($q) use ($brokerId) {
            $q->where('broker_id', $brokerId);
            // Fallback for demo: if broker has no properties yet, show properties or empty
        });

        // 1. Live Counters & Metrics
        $totalProperties = (clone $baseQuery)->count();
        $activeProperties = (clone $baseQuery)->where('status', 'active')->where('is_active', 1)->count();
        $inactiveProperties = (clone $baseQuery)->where(function ($q) {
            $q->where('status', '!=', 'active')->orWhere('is_active', 0);
        })->count();

        $totalBeds = (int) (clone $baseQuery)->sum('total_beds');
        $availableBeds = (int) (clone $baseQuery)->sum('available_beds');
        $occupiedBeds = max(0, $totalBeds - $availableBeds);
        $occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;

        // 2. Listing Type Tabs Aggregation
        $pgCount = (clone $baseQuery)->where(function($q) {
            $q->where(function($sq) {
                $sq->where('property_category', 'residential')
                   ->whereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['pg-hostel', 'pg', 'hostel', 'co-living']));
            })->orWhere(function($sq) {
                $sq->whereNull('property_category')
                   ->whereDoesntHave('propertyType', fn($pt) => $pt->whereIn('slug', ['commercial', 'office', 'shop', 'flat-apartment', 'flat', 'apartment', 'house-villa', 'land-plot', 'plot']));
            });
        })->count();

        $flatCount = (clone $baseQuery)->where(function($q) {
            $q->where('property_category', 'residential')
              ->whereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['flat-apartment', 'flat', 'apartment', 'house', 'house-villa', 'villa', 'builder-floor']));
        })->count();

        $commercialCount = (clone $baseQuery)->where(function($q) {
            $q->where('property_category', 'commercial')
              ->orWhereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['commercial', 'commercial-property', 'office', 'shop', 'warehouse']));
        })->count();

        $landCount = (clone $baseQuery)->where(function($q) {
            $q->where('property_category', 'land-plot')
              ->orWhereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['land-plot', 'land', 'plot']));
        })->count();

        $tabCounts = [
            'all' => $totalProperties,
            'pg-hostel' => $pgCount,
            'flat-apartment' => $flatCount,
            'commercial' => $commercialCount,
            'land-plot' => $landCount,
        ];

        // 3. Query Builder with Search and Filters
        $query = (clone $baseQuery)->with([
            'city',
            'area',
            'propertyType',
            'primaryImage',
            'images',
            'amenities',
        ]);

        // Listing Type Tab Filter
        $currentType = strtolower($request->query('type', 'all'));
        if ($currentType === 'pg-hostel' || $currentType === 'pg') {
            $query->where(function($q) {
                $q->where(function($sq) {
                    $sq->where('property_category', 'residential')
                       ->whereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['pg-hostel', 'pg', 'hostel', 'co-living']));
                })->orWhere(function($sq) {
                    $sq->whereNull('property_category')
                       ->whereDoesntHave('propertyType', fn($pt) => $pt->whereIn('slug', ['commercial', 'office', 'shop', 'flat-apartment', 'flat', 'apartment', 'house-villa', 'land-plot', 'plot']));
                });
            });
        } elseif ($currentType === 'flat-apartment' || $currentType === 'flat') {
            $query->where(function($q) {
                $q->where('property_category', 'residential')
                  ->whereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['flat-apartment', 'flat', 'apartment', 'house', 'house-villa', 'villa', 'builder-floor']));
            });
        } elseif ($currentType === 'commercial') {
            $query->where(function($q) {
                $q->where('property_category', 'commercial')
                  ->orWhereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['commercial', 'commercial-property', 'office', 'shop', 'warehouse']));
            });
        } elseif ($currentType === 'land-plot' || $currentType === 'land' || $currentType === 'plot') {
            $query->where(function($q) {
                $q->where('property_category', 'land-plot')
                  ->orWhereHas('propertyType', fn($pt) => $pt->whereIn('slug', ['land-plot', 'land', 'plot']));
            });
        } elseif (in_array(strtoupper($currentType), ['BOYS', 'GIRLS', 'CO-ED', 'COED'])) {
            $gender = in_array(strtoupper($currentType), ['CO-ED', 'COED']) ? 'co-ed' : strtolower($currentType);
            $query->where('gender_preference', $gender);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('landmark', 'like', "%{$search}%")
                  ->orWhereHas('city', fn($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('area', fn($a) => $a->where('name', 'like', "%{$search}%"));
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $status = strtolower($request->query('status'));
            if ($status === 'active') {
                $query->where('status', 'active')->where('is_active', 1);
            } elseif (in_array($status, ['inactive', 'draft', 'disabled'])) {
                $query->where(function ($q) use ($status) {
                    $q->where('status', $status)
                      ->orWhere('status', 'draft')
                      ->orWhere('status', 'inactive')
                      ->orWhere('is_active', 0);
                });
            }
        }

        $properties = $query->latest('created_at')->get();

        // 4. Dropdown Options for Modals
        $cities = City::where('is_active', 1)->orderBy('name')->get();
        $propertyTypes = PropertyType::where('is_active', 1)->orderBy('name')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'properties' => $properties,
                'stats' => [
                    'total' => $totalProperties,
                    'active' => $activeProperties,
                    'inactive' => $inactiveProperties,
                    'total_beds' => $totalBeds,
                    'available_beds' => $availableBeds,
                    'occupied_beds' => $occupiedBeds,
                    'occupancy_rate' => $occupancyRate,
                ],
                'tab_counts' => $tabCounts
            ]);
        }

        return view('broker.pgs', compact(
            'properties',
            'totalProperties',
            'activeProperties',
            'inactiveProperties',
            'totalBeds',
            'availableBeds',
            'occupiedBeds',
            'occupancyRate',
            'cities',
            'propertyTypes',
            'tabCounts',
            'currentType'
        ));
    }

    /**
     * Get single property details for view/edit.
     */
    public function show($id)
    {
        $broker = Auth::user();
        $property = Property::with(['city', 'area', 'propertyType', 'images', 'primaryImage', 'amenities'])
            ->where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'property' => $property,
        ]);
    }

    /**
     * 1-Click Toggle Listing Status (Active <-> Draft/Inactive).
     */
    public function toggleStatus(Request $request, $id)
    {
        $broker = Auth::user();
        $property = Property::where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        if ($request->has('status')) {
            $newStatus = strtolower($request->input('status'));
            if (!in_array($newStatus, ['active', 'draft', 'inactive'])) {
                $newStatus = 'active';
            }
            $property->status = $newStatus;
            $property->is_active = ($newStatus === 'active' ? 1 : 0);
        } else {
            // Toggle
            if ($property->status === 'active' && $property->is_active) {
                $property->status = 'draft';
                $property->is_active = 0;
            } else {
                $property->status = 'active';
                $property->is_active = 1;
            }
        }

        $property->save();

        return response()->json([
            'success' => true,
            'property_id' => $property->id,
            'status' => strtoupper($property->status),
            'is_active' => (bool) $property->is_active,
            'message' => "Listing status for \"{$property->name}\" changed to " . strtoupper($property->status) . "!",
        ]);
    }

    /**
     * Store a newly created PG property.
     */
    public function store(Request $request)
    {
        $broker = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'area_name' => 'nullable|string|max:100',
            'property_type_id' => 'nullable|exists:property_types,id',
            'gender_preference' => 'nullable|string|in:boys,girls,co-ed,all,any,not_applicable',
            'monthly_rent' => 'required|numeric|min:500|max:1000000',
            'security_deposit' => 'nullable|numeric|min:0|max:2000000',
            'total_beds' => 'required|integer|min:1|max:5000',
            'available_beds' => 'required|integer|min:0|max:5000',
            'address' => 'required|string|max:500',
            'landmark' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,draft,inactive',
            'image' => 'nullable|image|max:5120',
            'image_url' => 'nullable|url',
        ]);

        // Resolve or create area
        $areaId = null;
        if (!empty($validated['area_name'])) {
            $area = Area::firstOrCreate(
                ['city_id' => $validated['city_id'], 'name' => trim($validated['area_name'])],
                [
                    'id' => (string) Str::uuid(),
                    'slug' => Str::slug(trim($validated['area_name'])) . '-' . Str::random(4),
                    'is_active' => 1,
                    'version' => 1,
                ]
            );
            $areaId = $area->id;
        }

        $propertyTypeId = $validated['property_type_id'] ?? PropertyType::where('slug', 'pg')->value('id') ?? PropertyType::first()?->id;
        $pt = PropertyType::find($propertyTypeId);
        $ptSlug = strtolower($pt?->slug ?? '');
        $finalGender = !empty($validated['gender_preference']) ? strtolower($validated['gender_preference']) : null;
        if (in_array($ptSlug, ['commercial', 'shop', 'office', 'retail', 'commercial-space'])) {
            $finalGender = null;
        } elseif (in_array($ptSlug, ['flat', 'flat-apartment', 'house', 'apartment', 'villa'])) {
            $finalGender = $finalGender ?: 'all';
        } else {
            $finalGender = $finalGender ?: 'co-ed';
        }

        $property = Property::create([
            'id' => (string) Str::uuid(),
            'broker_id' => $broker->id,
            'organization_id' => $broker->organization_id ?? null,
            'city_id' => $validated['city_id'],
            'area_id' => $areaId,
            'property_type_id' => $propertyTypeId,
            'name' => $validated['name'],
            'gender_preference' => $finalGender,
            'monthly_rent' => $validated['monthly_rent'],
            'security_deposit' => $validated['security_deposit'] ?? ($validated['monthly_rent'] * 2),
            'total_beds' => $validated['total_beds'],
            'available_beds' => $validated['available_beds'],
            'address' => $validated['address'],
            'landmark' => $validated['landmark'] ?? null,
            'description' => $validated['description'] ?? "Premium PG accommodation with modern amenities in prime location.",
            'verification_status' => 'verified',
            'status' => $validated['status'] ?? 'active',
            'is_active' => ($validated['status'] ?? 'active') === 'active' ? 1 : 0,
            'rating' => 4.8,
            'total_reviews' => 0,
        ]);

        // Handle Image Upload with WebP Conversion & Thumbnail Generation
        $imageUrl = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        if ($request->hasFile('image')) {
            $processed = app(ImageProcessingService::class)->processUpload($request->file('image'), 'property_images', 'pg_');
            if ($processed) {
                $imageUrl = $processed['relative_url'];
            }
        } elseif (!empty($validated['image_url'])) {
            $imageUrl = $validated['image_url'];
        }

        PropertyImage::create([
            'id' => (string) Str::uuid(),
            'property_id' => $property->id,
            'image_url' => $imageUrl,
            'is_primary' => 1,
            'sort_order' => 1,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "PG Property \"{$property->name}\" listed successfully!",
                'property' => $property->load(['city', 'primaryImage']),
            ]);
        }

        return redirect()->route('broker.pgs')->with('success', "PG \"{$property->name}\" added successfully!");
    }

    /**
     * Update an existing PG property.
     */
    public function update(Request $request, $id)
    {
        $broker = Auth::user();
        $property = Property::where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'monthly_rent' => 'required|numeric|min:500|max:1000000',
            'security_deposit' => 'nullable|numeric|min:0|max:2000000',
            'total_beds' => 'required|integer|min:1|max:5000',
            'available_beds' => 'required|integer|min:0|max:5000',
            'gender_preference' => 'nullable|string|in:boys,girls,co-ed,all,any,not_applicable',
            'address' => 'required|string|max:500',
            'landmark' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,draft,inactive',
        ]);

        $ptSlug = strtolower($property->propertyType?->slug ?? '');
        $finalGender = !empty($validated['gender_preference']) ? strtolower($validated['gender_preference']) : null;
        if (in_array($ptSlug, ['commercial', 'shop', 'office', 'retail', 'commercial-space'])) {
            $finalGender = null;
        } elseif (in_array($ptSlug, ['flat', 'flat-apartment', 'house', 'apartment', 'villa'])) {
            $finalGender = $finalGender ?: 'all';
        } else {
            $finalGender = $finalGender ?: ($property->gender_preference ?: 'co-ed');
        }

        $property->name = $validated['name'];
        $property->monthly_rent = $validated['monthly_rent'];
        $property->security_deposit = $validated['security_deposit'] ?? $property->security_deposit;
        $property->total_beds = $validated['total_beds'];
        $property->available_beds = $validated['available_beds'];
        $property->gender_preference = $finalGender;
        $property->address = $validated['address'];
        $property->landmark = $validated['landmark'] ?? $property->landmark;

        if (!empty($validated['status'])) {
            $property->status = $validated['status'];
            $property->is_active = ($validated['status'] === 'active' ? 1 : 0);
        }

        $property->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Property \"{$property->name}\" updated successfully!",
                'property' => $property,
            ]);
        }

        return redirect()->route('broker.pgs')->with('success', "Property \"{$property->name}\" updated successfully!");
    }

    /**
     * Remove the specified PG property.
     */
    public function destroy(Request $request, $id)
    {
        $broker = Auth::user();
        $property = Property::where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        $name = $property->name;
        $property->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Property \"{$name}\" deleted successfully.",
            ]);
        }

        return redirect()->route('broker.pgs')->with('success', "Property \"{$name}\" deleted successfully.");
    }
}
