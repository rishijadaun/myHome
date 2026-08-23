<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\Review;
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

        // 2. Listing Type Tabs Aggregation
        $pgCount = Property::whereHas('propertyType', function ($q) {
            $q->whereIn('slug', ['pg-hostel', 'pg', 'hostel', 'co-living']);
        })->count();

        $flatCount = Property::whereHas('propertyType', function ($q) {
            $q->whereIn('slug', ['flat-apartment', 'flat', 'apartment', 'house', 'flat-house']);
        })->count();

        $commercialCount = Property::whereHas('propertyType', function ($q) {
            $q->whereIn('slug', ['commercial', 'commercial-property', 'office', 'shop']);
        })->count();

        $tabCounts = [
            'all' => $totalCount,
            'pg-hostel' => $pgCount,
            'flat-apartment' => $flatCount,
            'commercial' => $commercialCount,
        ];

        // 3. Query Builder with Eager Loading
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

        // Listing Type Filter (from Tabs or Dropdown)
        $currentType = $request->query('type') ?? $request->query('listing_type', 'all');
        if ($currentType && $currentType !== 'all') {
            if ($currentType === 'pg-hostel' || $currentType === 'pg') {
                $query->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['pg-hostel', 'pg', 'hostel', 'co-living']));
            } elseif ($currentType === 'flat-apartment' || $currentType === 'flat' || $currentType === 'flat-house') {
                $query->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['flat-apartment', 'flat', 'apartment', 'house', 'flat-house']));
            } elseif ($currentType === 'commercial') {
                $query->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['commercial', 'commercial-property', 'office', 'shop']));
            } elseif (in_array(strtoupper($currentType), ['BOYS', 'GIRLS', 'CO-ED', 'COED'])) {
                $gender = strtolower($currentType) === 'co-ed' || strtolower($currentType) === 'coed' ? 'co-ed' : strtolower($currentType);
                $query->where('gender_preference', $gender);
            } else {
                $query->whereHas('propertyType', function ($q) use ($currentType) {
                    $q->where('slug', $currentType)->orWhere('name', 'like', "%{$currentType}%");
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

        // Recommended / Featured Filter
        if ($request->filled('recommended')) {
            $recFilter = trim($request->query('recommended'));
            if ($recFilter === '1' || $recFilter === 'yes' || $recFilter === 'recommended') {
                $query->where(function ($q) {
                    $q->where('is_recommended', 1)->orWhere('featured', 1);
                });
            } elseif ($recFilter === '0' || $recFilter === 'no' || $recFilter === 'not_recommended') {
                $query->where(function ($q) {
                    $q->where('is_recommended', 0)->where('featured', 0);
                });
            }
        }

        // 4. Paginated Results
        $properties = $query->latest()->paginate(15)->withQueryString();

        $recommendedCount = Property::where(function ($q) {
            $q->where('is_recommended', 1)->orWhere('featured', 1);
        })->count();

        // 5. Dropdown Options
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
            'inactiveCount',
            'recommendedCount',
            'tabCounts',
            'currentType'
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
            'gender_preference' => ['nullable', 'string', 'in:boys,girls,co-ed,male,female,any,all,not_applicable'],
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

        // Auto resolve default area for city
        $area = Area::where('city_id', $validated['city_id'])->first();
        $areaId = $area ? $area->id : null;

        $isInstantApprove = $request->boolean('instant_approve', true);
        $isRecommended = $request->boolean('is_recommended', false);

        $property = Property::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'broker_id' => $validated['broker_id'],
            'city_id' => $validated['city_id'],
            'area_id' => $areaId,
            'property_type_id' => $propertyTypeId,
            'tag' => !empty($validated['tag']) && strtolower($validated['tag']) !== 'none' ? $validated['tag'] : null,
            'gender_preference' => $finalGender,
            'total_beds' => $validated['total_beds'],
            'available_beds' => $validated['total_beds'],
            'monthly_rent' => $validated['monthly_rent'],
            'security_deposit' => $validated['security_deposit'] ?? ($validated['monthly_rent'] * 2),
            'address' => $validated['address'] ?? ($validated['name'] . ', ' . ($area ? $area->name : 'City Center')),
            'landmark' => $validated['landmark'] ?? null,
            'description' => $validated['description'] ?? 'Modern, fully furnished stay with 24/7 power backup, high-speed WiFi, and security.',
            'verification_status' => $isInstantApprove ? 'verified' : 'pending',
            'status' => 'active',
            'is_recommended' => $isRecommended,
            'featured' => $isRecommended,
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
     * Toggle property Recommended / Not Recommended status.
     */
    public function toggleRecommended(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $newState = !$property->is_recommended;
        $property->is_recommended = $newState;
        $property->featured = $newState;
        $property->save();

        return response()->json([
            'success' => true,
            'is_recommended' => (bool) $property->is_recommended,
            'property_id' => $property->id,
            'property_name' => $property->name,
            'message' => $property->is_recommended
                ? "⭐ \"{$property->name}\" marked as Recommended!"
                : "Listing \"{$property->name}\" removed from Recommended.",
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
        $property = Property::where('id', $id)->orWhere('slug', $id)->first();
        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found or already deleted.',
            ], 404);
        }

        $name = $property->name;
        $property->delete();

        return response()->json([
            'success' => true,
            'message' => "Property \"{$name}\" was removed successfully.",
        ]);
    }

    /**
     * Approve a pending user review and recalculate property ratings.
     */
    public function approveReview(Request $request, $id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->status = 'approved';
        $review->is_active = true;
        $review->save();

        // Recalculate Property Rating
        $property = Property::find($review->property_id);
        if ($property) {
            $avg = \App\Models\Review::where('property_id', $property->id)->where('status', 'approved')->avg('rating');
            $count = \App\Models\Review::where('property_id', $property->id)->where('status', 'approved')->count();
            $property->rating = round($avg, 2);
            $property->total_reviews = $count;
            $property->save();
        }

        // Notify Reviewer
        if ($review->user_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $review->user_id,
                'user_type' => 'user',
                'title' => 'Review Published! ⭐',
                'message' => "Your review for \"{$property?->name}\" was approved and is now public.",
                'type' => 'review_approved',
                'is_read' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully and is now published!',
            'review' => $review,
        ]);
    }

    /**
     * Reject a user review.
     */
    public function rejectReview(Request $request, $id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->status = 'rejected';
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review rejected.',
            'review' => $review,
        ]);
    }

    /**
     * Delete a review.
     */
    public function destroyReview(Request $request, $id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $propertyId = $review->property_id;
        $review->delete();

        // Recalculate Property Rating
        $property = Property::find($propertyId);
        if ($property) {
            $avg = \App\Models\Review::where('property_id', $property->id)->where('status', 'approved')->avg('rating');
            $count = \App\Models\Review::where('property_id', $property->id)->where('status', 'approved')->count();
            $property->rating = $count > 0 ? round($avg, 2) : 0;
            $property->total_reviews = $count;
            $property->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.',
        ]);
    }

    /**
     * List all reviews for moderation in Admin Panel.
     */
    public function indexReviews(Request $request)
    {
        // 1. Live Statistics
        $totalReviews = Review::count();
        $pendingReviews = Review::where('status', 'pending')->count();
        $approvedReviews = Review::where('status', 'approved')->count();
        $rejectedReviews = Review::where('status', 'rejected')->count();
        $avgRating = round(Review::where('status', 'approved')->avg('rating') ?: 4.8, 1);

        // 2. Query with Filters
        $query = Review::with(['user.profile', 'property.city', 'property.area'])->latest();

        $statusFilter = $request->query('status', 'all');
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $ratingFilter = $request->query('rating');
        if ($ratingFilter) {
            $query->where('rating', '>=', (float) $ratingFilter);
        }

        $propertyFilter = $request->query('property_id');
        if ($propertyFilter) {
            $query->where('property_id', $propertyFilter);
        }

        $searchQuery = trim($request->query('search', ''));
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('comment', 'like', "%{$searchQuery}%")
                  ->orWhereHas('user.profile', function ($pq) use ($searchQuery) {
                      $pq->where('first_name', 'like', "%{$searchQuery}%")
                         ->orWhere('last_name', 'like', "%{$searchQuery}%")
                         ->orWhere('full_name', 'like', "%{$searchQuery}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($searchQuery) {
                      $uq->where('email', 'like', "%{$searchQuery}%")
                         ->orWhere('phone', 'like', "%{$searchQuery}%");
                  })
                  ->orWhereHas('property', function ($pq) use ($searchQuery) {
                      $pq->where('name', 'like', "%{$searchQuery}%");
                  });
            });
        }

        $reviews = $query->paginate(15)->withQueryString();
        $properties = Property::select('id', 'name')->orderBy('name')->get();

        return view('admin.reviews', compact(
            'reviews',
            'properties',
            'totalReviews',
            'pendingReviews',
            'approvedReviews',
            'rejectedReviews',
            'avgRating',
            'statusFilter',
            'ratingFilter',
            'searchQuery',
            'propertyFilter'
        ));
    }

    /**
     * Add or update host reply to a review.
     */
    public function replyReview(Request $request, $id)
    {
        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id);
        $review->broker_reply = $validated['reply'];
        $review->broker_reply_at = now();
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Official response saved successfully!',
            'reply' => $review->broker_reply,
        ]);
    }
}
