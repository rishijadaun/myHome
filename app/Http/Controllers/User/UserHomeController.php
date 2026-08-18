<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyReport;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHomeController extends Controller
{
    /**
     * Display the dynamic Home Page.
     */
    public function index()
    {
        // 1. Base Query: Only Approved & Active listings
        $approvedBase = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->with(['primaryImage', 'images', 'city', 'area', 'amenities', 'propertyType']);

        // 2. PG Near Me Section (Properties with GPS Coordinates preferred, max 8)
        $nearMeProperties = (clone $approvedBase)
            ->latest()
            ->take(8)
            ->get();

        // 3. Recommended for You Section (Properties with high tags or top rated, max 4)
        $recommendedProperties = (clone $approvedBase)
            ->where(function ($q) {
                $q->whereIn('tag', ['Guest Favourite', 'Popular', 'Top rated', 'Trending'])
                  ->orWhere('featured', 1);
            })
            ->take(4)
            ->get();

        // If recommended is less than 4, fallback to include approved properties
        if ($recommendedProperties->count() < 4) {
            $recommendedProperties = (clone $approvedBase)->take(4)->get();
        }

        // 4. Popular Boys PG Section (max 4)
        $boysProperties = (clone $approvedBase)
            ->where(function ($q) {
                $q->where('gender_preference', 'boys')
                  ->orWhere('gender_preference', 'male');
            })
            ->take(4)
            ->get();

        // If boys properties are few, fallback to any approved (max 4)
        if ($boysProperties->isEmpty()) {
            $boysProperties = (clone $approvedBase)->take(4)->get();
        }

        // 5. Recently Added Section (max 4)
        $recentProperties = (clone $approvedBase)
            ->latest('created_at')
            ->take(4)
            ->get();

        // 6. Top Cities with active property count (distinct top 6)
        $rawCities = City::where('is_active', 1)
            ->withCount(['properties' => function ($q) {
                $q->where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1);
            }])
            ->orderByDesc('properties_count')
            ->orderByDesc('is_metro')
            ->orderByDesc('is_tier1')
            ->get();

        $seenCityNames = [];
        $topCities = $rawCities->filter(function ($city) use (&$seenCityNames) {
            $normalized = strtolower(trim(preg_replace('/\s*\(.*?\)\s*/', '', $city->name)));
            if (in_array($normalized, $seenCityNames)) {
                return false;
            }
            $seenCityNames[] = $normalized;
            return true;
        })->take(6);

        return view('user.home', compact(
            'nearMeProperties',
            'recommendedProperties',
            'boysProperties',
            'recentProperties',
            'topCities'
        ));
    }

    /**
     * Display Property Detail Page dynamically with SEO friendly slug.
     */
    public function show(Request $request, $slug = null)
    {
        $slugOrId = $slug ?? $request->query('slug') ?? $request->query('id');

        $query = Property::with(['primaryImage', 'images', 'city', 'area', 'amenities', 'rules', 'broker.profile', 'propertyType']);

        $property = null;
        if ($slugOrId) {
            $property = (clone $query)->where(function ($q) use ($slugOrId) {
                $q->where('slug', $slugOrId)->orWhere('id', $slugOrId);
            })->first();

            if ($property) {
                // Check if viewer has preview permissions (Admin or Broker Owner)
                $canPreview = false;
                if (Auth::check()) {
                    $authUser = Auth::user();
                    if ($authUser->roles()->whereIn('slug', ['super_admin', 'admin'])->exists() || $property->broker_id === $authUser->id) {
                        $canPreview = true;
                    }
                }

                // If not authorized to preview and listing is not approved/active, block public access
                if (!$canPreview && ($property->status !== 'active' || $property->verification_status !== 'verified' || !$property->is_active)) {
                    abort(404, 'This property listing is currently under review by admin and is not publicly accessible.');
                }
            } else {
                abort(404, 'Property not found.');
            }
        }

        if (!$property) {
            // Fallback to first approved & verified active property
            $property = (clone $query)->where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_active', 1)
                ->first();
        }

        if (!$property) {
            abort(404, 'No active property found.');
        }

        // Similar Stays
        $similarProperties = collect();
        $approvedReviews = collect();
        $userPendingReview = null;

        if ($property) {
            $similarProperties = Property::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('id', '!=', $property->id)
                ->where(function ($q) use ($property) {
                    if ($property->city_id) {
                        $q->where('city_id', $property->city_id);
                    }
                })
                ->with(['primaryImage', 'images', 'city', 'area', 'amenities'])
                ->take(4)
                ->get();

            // Approved Reviews for this property
            $approvedReviews = Review::where('property_id', $property->id)
                ->where('status', 'approved')
                ->where('is_active', 1)
                ->with('user.profile')
                ->latest()
                ->get();

            // Check if authenticated user has a pending review
            if (Auth::check()) {
                $userPendingReview = Review::where('property_id', $property->id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
            }
        }

        $totalReviewsCount = $approvedReviews->count();
        $avgRating = $totalReviewsCount > 0
            ? round($approvedReviews->avg('rating'), 1)
            : ($property && $property->rating ? number_format($property->rating, 1) : '4.8');

        return view('user.detail', compact('property', 'similarProperties', 'approvedReviews', 'userPendingReview', 'avgRating', 'totalReviewsCount'));
    }

    /**
     * Display Dynamic Search Page with Filters and Explore by Budget support.
     */
    public function search(Request $request)
    {
        $query = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->with(['primaryImage', 'images', 'city', 'area', 'amenities', 'propertyType']);

        // 1. Text Query (name, address, landmark, description, city, area)
        $searchQuery = trim($request->query('q') ?? $request->query('search') ?? '');
        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('address', 'like', "%{$searchQuery}%")
                  ->orWhere('landmark', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhereHas('city', function ($cq) use ($searchQuery) {
                      $cq->where('name', 'like', "%{$searchQuery}%");
                  })
                  ->orWhereHas('area', function ($aq) use ($searchQuery) {
                      $aq->where('name', 'like', "%{$searchQuery}%");
                  });
            });
        }

        // 2. City Filter
        $selectedCity = trim($request->query('city') ?? '');
        if ($selectedCity !== '') {
            $query->whereHas('city', function ($q) use ($selectedCity) {
                $q->where('name', 'like', "%{$selectedCity}%")->orWhere('slug', 'like', "%{$selectedCity}%");
            });
        }

        // 3. Gender / Room For Filter
        $selectedGender = strtoupper(trim($request->query('gender') ?? $request->query('type') ?? ''));
        if (in_array($selectedGender, ['BOYS', 'MALE'])) {
            $query->whereIn('gender_preference', ['boys', 'male']);
        } elseif (in_array($selectedGender, ['GIRLS', 'FEMALE'])) {
            $query->whereIn('gender_preference', ['girls', 'female']);
        } elseif (in_array($selectedGender, ['CO-ED', 'COED', 'UNISEX'])) {
            $query->whereIn('gender_preference', ['co-ed', 'coed', 'unisex', 'both', 'any']);
        }

        // 4. Budget Range & Price Limits Filter
        $budget = trim($request->query('budget') ?? '');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');

        if ($minPrice !== null && $maxPrice !== null && $minPrice !== '' && $maxPrice !== '') {
            $query->whereBetween('monthly_rent', [(float)$minPrice, (float)$maxPrice]);
        } elseif ($minPrice !== null && $minPrice !== '') {
            $query->where('monthly_rent', '>=', (float)$minPrice);
        } elseif ($maxPrice !== null && $maxPrice !== '') {
            $query->where('monthly_rent', '<=', (float)$maxPrice);
        } elseif ($budget !== '') {
            if ($budget === '0-6000' || $budget === '6000' || $budget === 'under-6k') {
                $query->where('monthly_rent', '<=', 6000);
            } elseif ($budget === '6000-10000' || $budget === '10000') {
                $query->whereBetween('monthly_rent', [6000, 10000]);
            } elseif ($budget === '10000-15000' || $budget === '15000') {
                $query->whereBetween('monthly_rent', [10000, 15000]);
            } elseif ($budget === '15000-plus' || $budget === '20000' || $budget === '15000+') {
                $query->where('monthly_rent', '>=', 15000);
            } elseif (is_numeric($budget)) {
                $query->where('monthly_rent', '<=', (float)$budget);
            }
        }

        // 5. Amenities Filters (AC, Food, WiFi, Security)
        if ($request->boolean('ac')) {
            $query->whereHas('amenities', fn($q) => $q->where('name', 'like', '%ac%')->orWhere('slug', 'like', '%ac%'));
        }
        if ($request->boolean('food')) {
            $query->whereHas('amenities', fn($q) => $q->where('name', 'like', '%food%')->orWhere('name', 'like', '%meal%')->orWhere('slug', 'like', '%food%'));
        }
        if ($request->boolean('wifi')) {
            $query->whereHas('amenities', fn($q) => $q->where('name', 'like', '%wifi%')->orWhere('slug', 'like', '%wifi%'));
        }

        // 6. Sort
        $sort = $request->query('sort', 'recommended');
        if ($sort === 'price-asc') {
            $query->orderBy('monthly_rent', 'asc');
        } elseif ($sort === 'price-desc') {
            $query->orderBy('monthly_rent', 'desc');
        } elseif ($sort === 'rating') {
            $query->orderByDesc('rating')->orderByDesc('total_reviews');
        } else {
            $query->orderByDesc('featured')->latest('created_at');
        }

        $properties = $query->get();

        // 7. Distinct Cities for Dropdowns and Mobile Drawer
        $rawCities = City::where('is_active', 1)
            ->withCount(['properties' => function ($q) {
                $q->where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1);
            }])
            ->orderByDesc('properties_count')
            ->orderByDesc('is_metro')
            ->get();

        $seenCityNames = [];
        $cities = $rawCities->filter(function ($city) use (&$seenCityNames) {
            $normalized = strtolower(trim(preg_replace('/\s*\(.*?\)\s*/', '', $city->name)));
            if (in_array($normalized, $seenCityNames)) {
                return false;
            }
            $seenCityNames[] = $normalized;
            return true;
        });

        return view('user.search', compact(
            'properties',
            'cities',
            'selectedCity',
            'selectedGender',
            'budget',
            'minPrice',
            'maxPrice',
            'searchQuery',
            'sort'
        ));
    }

    /**
     * Submit user feedback or abuse report for a listing.
     */
    public function report(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'reporter_name' => 'nullable|string|max:100',
            'reporter_email' => 'nullable|email|max:150',
            'reporter_phone' => ['nullable', 'string', 'regex:/^[0-9]{10}$/'],
        ], [
            'reporter_phone.regex' => 'The phone number must be exactly 10 digits.',
        ]);

        $userId = Auth::id() ?? null;
        $userName = Auth::user()?->name ?? $validated['reporter_name'] ?? 'Guest User';
        $userEmail = Auth::user()?->email ?? $validated['reporter_email'] ?? null;
        $userPhone = Auth::user()?->phone ?? $validated['reporter_phone'] ?? null;

        $report = PropertyReport::create([
            'property_id' => $property->id,
            'user_id' => $userId,
            'reporter_name' => $userName,
            'reporter_email' => $userEmail,
            'reporter_phone' => $userPhone,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        // Alert administrators
        try {
            $admins = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['super_admin', 'admin']))->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'user_type' => 'admin',
                    'title' => 'Property Reported ⚠️',
                    'message' => "Listing \"{$property->name}\" was reported for: {$validated['reason']}.",
                    'type' => 'property_report',
                    'data' => json_encode(['property_id' => $property->id, 'report_id' => $report->id]),
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            // Ignore notification failure
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback. Our Trust & Safety team has received your report and will investigate this listing promptly.'
            ]);
        }

        return back()->with('success', 'Thank you for your feedback. Our team will review this listing shortly.');
    }

    /**
     * Submit user review for a property (Pending admin approval).
     */
    public function submitReview(Request $request, $id)
    {
        $user = Auth::user();

        // If not logged in via web session, check Bearer token from request or Sanctum
        if (!$user && $request->bearerToken()) {
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());
            if ($tokenModel) {
                $user = $tokenModel->tokenable;
            }
        }

        // Also check if auth_user_id was passed in form / payload
        if (!$user && $request->filled('auth_user_id')) {
            $user = User::find($request->input('auth_user_id'));
        }

        if ($user) {
            // Keep web session active
            Auth::login($user);
        } else {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to submit a review.',
                    'redirect' => route('user.login')
                ], 401);
            }
            return redirect()->route('user.login')->with('error', 'Please login to submit a review.');
        }

        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        $review = Review::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'property_id' => $property->id,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?: 'Verified Resident Review',
            'comment' => $validated['comment'],
            'status' => 'pending',
            'is_verified' => 1,
            'is_active' => 1,
        ]);

        // Notify Admins
        try {
            $admins = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['super_admin', 'admin']))->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'user_id' => $admin->id,
                    'user_type' => 'admin',
                    'title' => 'New Review Pending Approval ⭐',
                    'message' => 'User "' . (Auth::user()->name ?? 'User') . "\" submitted a {$validated['rating']}★ review for \"{$property->name}\".",
                    'type' => 'property_review_pending',
                    'data' => json_encode(['property_id' => $property->id, 'review_id' => $review->id]),
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            // Ignore notification failure
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your review has been submitted and is pending moderation. It will be published once approved by admin.',
                'review' => $review
            ]);
        }

        return back()->with('success', 'Thank you! Your review has been submitted and is pending moderation. It will be published once approved by admin.');
    }

    /**
     * Display Dynamic Interactive Map View (/location).
     */
    public function location(Request $request)
    {
        $query = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->with(['primaryImage', 'images', 'city', 'area', 'amenities', 'propertyType']);

        if ($request->filled('city')) {
            $cityParam = $request->query('city');
            $query->whereHas('city', function ($q) use ($cityParam) {
                $q->where('slug', $cityParam)->orWhere('name', 'like', "%{$cityParam}%");
            });
        }

        if ($request->filled('gender')) {
            $gender = $request->query('gender');
            if ($gender === 'boys' || $gender === 'girls' || $gender === 'co-ed' || $gender === 'unisex') {
                $query->where('gender_preference', $gender);
            }
        }

        if ($request->filled('max_price')) {
            $query->where('monthly_rent', '<=', (float)$request->query('max_price'));
        }

        $properties = $query->latest()->get()->map(function ($p) {
            $primaryImg = $p->primaryImage->image_url ?? ($p->images->first()?->image_url ?? $p->display_image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80');
            $lat = $p->map_latitude;
            $lng = $p->map_longitude;
            $tags = [];
            if ($p->gender_preference) {
                $tags[] = ucfirst($p->gender_preference === 'co-ed' ? 'Unisex' : $p->gender_preference);
            }
            if ($p->amenities->count() > 0) {
                foreach ($p->amenities->take(2) as $am) {
                    $tags[] = $am->name;
                }
            } else {
                $tags[] = 'WiFi';
                $tags[] = 'AC';
            }

            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug ?: \Illuminate\Support\Str::slug($p->name),
                'price' => number_format($p->monthly_rent),
                'raw_price' => (float) $p->monthly_rent,
                'rating' => $p->rating ? number_format($p->rating, 1) : '4.8',
                'reviews_count' => $p->total_reviews ?: 12,
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'image' => $primaryImg,
                'gender' => $p->gender_preference ?? 'boys',
                'gender_label' => strtoupper($p->gender_preference === 'co-ed' ? 'UNISEX' : ($p->gender_preference ?: 'BOYS')),
                'location_text' => ($p->area ? $p->area->name . ', ' : '') . ($p->city->name ?? 'City Center'),
                'address' => $p->address ?: (($p->area->name ?? '') . ', ' . ($p->city->name ?? 'Noida')),
                'city' => $p->city->name ?? 'Noida',
                'tags' => $tags,
                'detail_url' => route('user.detail', ['slug' => $p->slug ?: \Illuminate\Support\Str::slug($p->name)]),
            ];
        });

        $cities = City::where('is_active', 1)->orderBy('name')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $properties->count(),
                'properties' => $properties
            ]);
        }

        return view('user.location', compact('properties', 'cities'));
    }
}
