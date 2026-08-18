<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;

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

        // 4. Popular Boys PG Section
        $boysProperties = (clone $approvedBase)
            ->where(function ($q) {
                $q->where('gender_preference', 'boys')
                  ->orWhere('gender_preference', 'male');
            })
            ->take(6)
            ->get();

        // If boys properties are few, fallback to any approved
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
            $property = (clone $query)->where('slug', $slugOrId)->orWhere('id', $slugOrId)->first();
        }

        if (!$property) {
            // Fallback to first approved property
            $property = (clone $query)->where('status', 'active')->where('verification_status', 'verified')->first() ?? Property::first();
        }

        // Similar Stays
        $similarProperties = collect();
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
        }

        return view('user.detail', compact('property', 'similarProperties'));
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
}
