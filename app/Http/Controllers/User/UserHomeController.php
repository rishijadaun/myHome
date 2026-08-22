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
        // 1. Single-pass query for approved listings
        $allApproved = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->with(['primaryImage', 'images', 'city', 'area', 'amenities', 'propertyType'])
            ->latest('created_at')
            ->take(30)
            ->get();

        // 2. PG Near Me Section (take 20)
        $nearMeProperties = $allApproved->take(20);

        // 3. Recommended for You Section (Properties with high tags or top rated, max 4)
        $recommendedProperties = $allApproved->filter(function ($p) {
            return in_array($p->tag, ['Guest Favourite', 'Popular', 'Top rated', 'Trending']) || $p->featured;
        })->take(4);
        if ($recommendedProperties->count() < 4) {
            $recommendedProperties = $allApproved->take(4);
        }

        // 4. Popular Boys PG Section (max 4)
        $boysProperties = $allApproved->filter(function ($p) {
            $pref = strtolower($p->gender_preference ?? '');
            return $pref === 'boys' || $pref === 'male';
        })->take(4);
        if ($boysProperties->isEmpty()) {
            $boysProperties = $allApproved->take(4);
        }

        // 5. Recently Added Section (max 4)
        $recentProperties = $allApproved->take(4);

        // 6. Top Cities with active property count (cached for ultra-fast TTFB)
        $topCities = \Illuminate\Support\Facades\Cache::remember('home_top_cities_v2', 300, function () {
            $rawCities = City::where('is_active', 1)
                ->withCount(['properties' => function ($q) {
                    $q->where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1);
                }])
                ->orderByDesc('properties_count')
                ->orderByDesc('is_metro')
                ->orderByDesc('is_tier1')
                ->get();

            $seenCityNames = [];
            return $rawCities->filter(function ($city) use (&$seenCityNames) {
                $normalized = strtolower(trim(preg_replace('/\s*\(.*?\)\s*/', '', $city->name)));
                if (in_array($normalized, $seenCityNames)) {
                    return false;
                }
                $seenCityNames[] = $normalized;
                return true;
            })->take(6);
        });

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
     * Display Dynamic Search Page with AI Natural Language / Hinglish Intent Parsing, Filters, and Match Scoring.
     */
    public function search(
        Request $request,
        \App\Services\AiIntentParserService $intentParser,
        \App\Services\PropertyRankingService $rankingService
    ) {
        $searchQuery = trim($request->query('q') ?? $request->query('search') ?? '');

        // 1. Parse natural language / Hinglish query if provided
        $intent = $searchQuery !== '' ? $intentParser->parse($searchQuery) : [
            'raw_query' => '',
            'city' => null,
            'area' => null,
            'gender' => null,
            'max_budget' => null,
            'min_budget' => null,
            'amenities' => [],
            'room_type' => null,
            'keywords' => [],
            'has_stay_intent' => false
        ];

        // 2. Read explicit URL parameters
        $explicitCity = trim($request->query('city') ?? '');
        $explicitGender = strtoupper(trim($request->query('gender') ?? $request->query('type') ?? ''));
        $explicitBudget = trim($request->query('budget') ?? '');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sort = $request->query('sort', 'recommended');

        // Checkbox states (if explicitly passed in request, override parsed intent)
        $reqHasAC = $request->has('ac');
        $reqHasFood = $request->has('food');
        $reqHasWifi = $request->has('wifi');
        $reqHasSecurity = $request->has('security');

        $filterAC = $reqHasAC ? $request->boolean('ac') : in_array('ac', $intent['amenities'] ?? []);
        $filterFood = $reqHasFood ? $request->boolean('food') : in_array('food', $intent['amenities'] ?? []);
        $filterWifi = $reqHasWifi ? $request->boolean('wifi') : in_array('wifi', $intent['amenities'] ?? []);
        $filterSecurity = $reqHasSecurity ? $request->boolean('security') : in_array('security', $intent['amenities'] ?? []);

        // 3. Resolve effective filter values (combining explicit URL params & parsed intent)
        $selectedCity = $explicitCity !== '' ? $explicitCity : ($intent['city'] ?? '');
        $selectedArea = $intent['area'] ?? '';
        
        $selectedGender = '';
        if ($explicitGender !== '') {
            $selectedGender = $explicitGender;
        } elseif (!empty($intent['gender'])) {
            $g = strtolower($intent['gender']);
            $selectedGender = ($g === 'boys' || $g === 'male') ? 'BOYS' : (($g === 'girls' || $g === 'female') ? 'GIRLS' : 'CO-ED');
        }

        $budget = $explicitBudget !== '' ? $explicitBudget : ($maxPrice !== null ? (string)$maxPrice : (!empty($intent['max_budget']) ? (string)$intent['max_budget'] : ''));

        // 4. Build Eloquent Query for Approved & Verified Active Properties
        $query = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->with(['primaryImage', 'images', 'city', 'area', 'amenities', 'propertyType']);

        // A. City Filter
        if ($selectedCity !== '') {
            $query->where(function ($q) use ($selectedCity) {
                $q->whereHas('city', function ($cq) use ($selectedCity) {
                    $cq->where('name', 'like', "%{$selectedCity}%")->orWhere('slug', 'like', "%{$selectedCity}%");
                })->orWhere('address', 'like', "%{$selectedCity}%");
            });
        }

        // B. Area Filter (e.g. Sector 62, Koramangala, Saket, etc.)
        if ($selectedArea !== '') {
            $query->where(function ($q) use ($selectedArea) {
                $q->whereHas('area', function ($aq) use ($selectedArea) {
                    $aq->where('name', 'like', "%{$selectedArea}%")->orWhere('slug', 'like', "%{$selectedArea}%");
                })->orWhere('address', 'like', "%{$selectedArea}%")
                  ->orWhere('landmark', 'like', "%{$selectedArea}%");
            });
        }

        // C. Gender Filter
        if ($selectedGender === 'BOYS' || $selectedGender === 'MALE') {
            $query->whereIn('gender_preference', ['boys', 'male', 'co-ed', 'unisex', 'both', 'any', 'all']);
        } elseif ($selectedGender === 'GIRLS' || $selectedGender === 'FEMALE') {
            $query->whereIn('gender_preference', ['girls', 'female', 'co-ed', 'unisex', 'both', 'any', 'all']);
        } elseif (in_array($selectedGender, ['CO-ED', 'COED', 'UNISEX'])) {
            $query->whereIn('gender_preference', ['co-ed', 'coed', 'unisex', 'both', 'any', 'all']);
        }

        // D. Budget Range & Price Limits Filter
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
                // If budget was parsed from query or entered, allow tolerance (+1000) so close matches are included and scored
                $query->where('monthly_rent', '<=', ((float)$budget) + 1000);
            }
        }

        // E. Amenities Filters (AC, Food, WiFi, Security)
        // If explicit checkboxes checked, apply strict filter
        if ($reqHasAC && $filterAC) {
            $query->whereHas('amenities', fn($q) => $q->where('name', 'like', '%ac%')->orWhere('slug', 'like', '%ac%'));
        }
        if ($reqHasFood && $filterFood) {
            $query->whereHas('amenities', fn($q) => $q->where('name', 'like', '%food%')->orWhere('name', 'like', '%meal%')->orWhere('slug', 'like', '%food%'));
        }
        if ($reqHasWifi && $filterWifi) {
            $query->whereHas('amenities', fn($q) => $q->where('name', 'like', '%wifi%')->orWhere('slug', 'like', '%wifi%'));
        }
        if ($reqHasSecurity && $filterSecurity) {
            $query->whereHas('amenities', fn($q) => $q->where('name', 'like', '%security%')->orWhere('name', 'like', '%cctv%')->orWhere('slug', 'like', '%security%'));
        }

        // F. Residual Keyword Search (if user searched specific PG name like "Royal", "Elite", "GPS", etc.)
        if (!empty($intent['keywords'])) {
            foreach ($intent['keywords'] as $kw) {
                // Skip if keyword is the city or area name already filtered
                if (strcasecmp($kw, $selectedCity) === 0 || strcasecmp($kw, $selectedArea) === 0) continue;
                $query->where(function ($q) use ($kw) {
                    $q->where('name', 'like', "%{$kw}%")
                      ->orWhere('address', 'like', "%{$kw}%")
                      ->orWhere('landmark', 'like', "%{$kw}%")
                      ->orWhere('description', 'like', "%{$kw}%");
                });
            }
        }

        // 5. Fetch & Rank properties
        $rawResults = $query->get();
        $isFallback = false;

        // If no results found with strict area/budget, fallback gracefully to broaden search in the same city/region
        if ($rawResults->isEmpty() && ($selectedArea !== '' || $selectedCity !== '' || $selectedGender !== '')) {
            $fallbackQuery = Property::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_active', 1)
                ->with(['primaryImage', 'images', 'city', 'area', 'amenities', 'propertyType']);

            if ($selectedCity !== '') {
                $fallbackQuery->where(function ($q) use ($selectedCity) {
                    $q->whereHas('city', function ($cq) use ($selectedCity) {
                        $cq->where('name', 'like', "%{$selectedCity}%")->orWhere('slug', 'like', "%{$selectedCity}%");
                    })->orWhere('address', 'like', "%{$selectedCity}%");
                });
            }

            if ($selectedGender === 'BOYS' || $selectedGender === 'MALE') {
                $fallbackQuery->whereIn('gender_preference', ['boys', 'male', 'co-ed', 'unisex', 'all']);
            } elseif ($selectedGender === 'GIRLS' || $selectedGender === 'FEMALE') {
                $fallbackQuery->whereIn('gender_preference', ['girls', 'female', 'co-ed', 'unisex', 'all']);
            }

            $rawResults = $fallbackQuery->take(12)->get();
            if ($rawResults->isNotEmpty()) {
                $isFallback = true;
            }
        }

        // Apply dynamic AI match ranking
        if ($rawResults->isNotEmpty()) {
            $properties = $rankingService->rankModels($rawResults, $intent);
        } else {
            $properties = collect();
        }

        // 6. Manual Sort override if requested
        if ($sort === 'distance-asc' || $request->has('near_me')) {
            $userLat = (float)($request->query('lat') ?? 28.6280);
            $userLng = (float)($request->query('lng') ?? 77.3649);
            $properties = $properties->sortBy(function ($p) use ($userLat, $userLng) {
                $pLat = (float)($p->map_latitude ?? 28.6280);
                $pLng = (float)($p->map_longitude ?? 77.3649);
                $dLat = deg2rad($pLat - $userLat);
                $dLon = deg2rad($pLng - $userLng);
                $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($userLat)) * cos(deg2rad($pLat)) * sin($dLon / 2) * sin($dLon / 2);
                return 6371 * (2 * atan2(sqrt($a), sqrt(1 - $a)));
            })->values();
        } elseif ($sort === 'price-asc') {
            $properties = $properties->sortBy('monthly_rent')->values();
        } elseif ($sort === 'price-desc') {
            $properties = $properties->sortByDesc('monthly_rent')->values();
        } elseif ($sort === 'rating') {
            $properties = $properties->sortByDesc('rating')->values();
        }

        // 7. Distinct Active Cities for Dropdowns and Mobile Drawer
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

        // 8. Build Interactive Active Filter Chips for UI
        $activeFilterChips = $this->buildFilterChips($intent, $selectedCity, $selectedArea, $selectedGender, $budget, $filterAC, $filterFood, $filterWifi, $filterSecurity);

        // 9. Conversational Summary Message
        $summaryMessage = $this->buildSummaryMessage($intent, $selectedCity, $selectedArea, $selectedGender, $budget, $properties->count(), $isFallback);

        return view('user.search', compact(
            'properties',
            'cities',
            'selectedCity',
            'selectedArea',
            'selectedGender',
            'budget',
            'minPrice',
            'maxPrice',
            'filterAC',
            'filterFood',
            'filterWifi',
            'filterSecurity',
            'searchQuery',
            'sort',
            'intent',
            'activeFilterChips',
            'summaryMessage',
            'isFallback'
        ));
    }

    /**
     * Helper to build removable UI filter badge chips.
     */
    protected function buildFilterChips(
        array $intent,
        string $selectedCity,
        string $selectedArea,
        string $selectedGender,
        string $budget,
        bool $filterAC,
        bool $filterFood,
        bool $filterWifi,
        bool $filterSecurity
    ): array {
        $chips = [];

        // Location Chip
        if ($selectedCity !== '' || $selectedArea !== '') {
            $locLabel = $selectedCity;
            if ($selectedArea !== '') {
                $locLabel = $selectedCity !== '' ? "{$selectedCity} ({$selectedArea})" : $selectedArea;
            }
            $chips[] = [
                'type' => 'location',
                'label' => "📍 {$locLabel}",
                'icon' => 'map-marker-alt',
                'value' => $selectedCity,
                'clear_param' => 'city'
            ];
        }

        // Gender Chip
        if ($selectedGender !== '') {
            $gLabel = $selectedGender === 'BOYS' ? '👨 Boys PG' : ($selectedGender === 'GIRLS' ? '👩 Girls PG' : '👥 Co-Ed / Unisex');
            $chips[] = [
                'type' => 'gender',
                'label' => $gLabel,
                'icon' => $selectedGender === 'BOYS' ? 'mars' : ($selectedGender === 'GIRLS' ? 'venus' : 'users'),
                'value' => $selectedGender,
                'clear_param' => 'gender'
            ];
        }

        // Budget Chip
        if ($budget !== '') {
            $budgetFormatted = is_numeric($budget) ? '₹' . number_format((float)$budget) : $budget;
            $chips[] = [
                'type' => 'budget',
                'label' => "💰 Under {$budgetFormatted}",
                'icon' => 'tag',
                'value' => $budget,
                'clear_param' => 'budget'
            ];
        }

        // Amenities Chips
        if ($filterAC) {
            $chips[] = [
                'type' => 'amenity',
                'label' => '❄️ AC',
                'icon' => 'snowflake',
                'value' => 'ac',
                'clear_param' => 'ac'
            ];
        }
        if ($filterFood) {
            $chips[] = [
                'type' => 'amenity',
                'label' => '🍱 Food Included',
                'icon' => 'utensils',
                'value' => 'food',
                'clear_param' => 'food'
            ];
        }
        if ($filterWifi) {
            $chips[] = [
                'type' => 'amenity',
                'label' => '📶 Free WiFi',
                'icon' => 'wifi',
                'value' => 'wifi',
                'clear_param' => 'wifi'
            ];
        }
        if ($filterSecurity) {
            $chips[] = [
                'type' => 'amenity',
                'label' => '🛡️ 24x7 Security',
                'icon' => 'shield-halved',
                'value' => 'security',
                'clear_param' => 'security'
            ];
        }

        return $chips;
    }

    /**
     * Helper to build conversational search summary message.
     */
    protected function buildSummaryMessage(
        array $intent,
        string $selectedCity,
        string $selectedArea,
        string $selectedGender,
        string $budget,
        int $count,
        bool $isFallback
    ): string {
        if ($count === 0) {
            $loc = $selectedArea ?: $selectedCity;
            if ($loc) {
                return "No approved verified properties found matching all your criteria in {$loc}. Try clearing some filters or expanding your budget.";
            }
            return "No matching verified properties found. Try searching with different keywords or clearing active filters.";
        }

        $parts = [];
        if ($selectedGender === 'BOYS') $parts[] = 'Boys PGs';
        elseif ($selectedGender === 'GIRLS') $parts[] = 'Girls PGs';
        elseif ($selectedGender === 'CO-ED') $parts[] = 'Co-Living Stays';
        else $parts[] = 'verified stays';

        if ($selectedArea) $parts[] = "in {$selectedArea}";
        elseif ($selectedCity) $parts[] = "in {$selectedCity}";

        if ($budget) {
            $parts[] = 'under ₹' . (is_numeric($budget) ? number_format((float)$budget) : $budget);
        }

        $desc = implode(' ', $parts);

        if ($isFallback) {
            return "Exact match not found for all strict constraints. Showing {$count} closest matching verified {$desc}:";
        }

        return "Found {$count} verified {$desc} ranked by StayNest Match Score:";
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
