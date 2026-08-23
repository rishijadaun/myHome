<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\City;
use App\Models\Area;

class PropertySearchService
{
    public function __construct(
        protected AiIntentParserService $intentParser,
        protected PropertyRankingService $rankingService
    ) {}

    /**
     * Search and rank active database properties using AI intent and filters.
     *
     * @param string $message
     * @param array $overrideFilters
     * @return array
     */
    public function search(string $message, array $overrideFilters = []): array
    {
        // 1. Parse natural language into structured intent
        $intent = $this->intentParser->parse($message, $overrideFilters);

        // Check if user is asking for specific Listing Type Details (e.g. "Tell me about Commercial spaces" or "Learn about Flats & Houses")
        if (!empty($intent['specific_listing_type_detail'])) {
            return $this->getListingTypeDetails($intent['specific_listing_type_detail'], $intent);
        }

        // Check if user is asking for Trending Searches / Stays
        if (!empty($intent['is_trending_inquiry'])) {
            return $this->getTrendingOverview($intent);
        }

        // Check if user is asking about general Listing Types / Categories on StayNest
        if (!empty($intent['is_listing_types_inquiry'])) {
            return $this->getListingTypesOverview($intent);
        }

        // Check if user is asking for a specific named property detail (e.g. "tell me more about Urban Nest")
        if (!empty($intent['is_detail_query']) && !empty($intent['target_property_name'])) {
            return $this->getPropertyDetails($intent['target_property_name'], $intent);
        }

        // 2. Query ONLY active & admin-verified database properties
        $query = Property::query()
            ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType'])
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg(['approvedReviews as dynamic_rating' => fn($q) => $q->where('status', 'approved')], 'rating')
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->whereNull('deleted_at');

        // Apply Property Type Filter (PG & Hostels / Flats & Houses / Commercial)
        if (!empty($intent['property_type'])) {
            $pt = $intent['property_type'];
            if ($pt === 'commercial') {
                $query->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['commercial', 'office', 'shop', 'commercial-space']));
            } elseif ($pt === 'flat_house') {
                $query->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['flat', 'apartment', 'house', 'villa', 'flat-apartment']));
            } elseif ($pt === 'pg_hostel') {
                $query->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['pg-hostel', 'co-living', 'pg', 'hostel']));
            }
        }

        // Apply Property Name / Keyword Filter
        $searchTerms = [];
        if (!empty($intent['property_name'])) {
            $searchTerms[] = $intent['property_name'];
            $cleanNameOnly = trim(preg_replace('/\b(pg|hostel|stay|stays|residency|rooms?|living|house)\b/iu', '', $intent['property_name']));
            if (!empty($cleanNameOnly) && $cleanNameOnly !== $intent['property_name']) {
                $searchTerms[] = $cleanNameOnly;
            }
        }
        if (!empty($intent['keywords'])) {
            $searchTerms = array_merge($searchTerms, $intent['keywords']);
        }
        $searchTerms = array_values(array_unique(array_filter($searchTerms)));

        if (!empty($searchTerms)) {
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('slug', 'LIKE', "%{$term}%")
                      ->orWhere('landmark', 'LIKE', "%{$term}%")
                      ->orWhere('address', 'LIKE', "%{$term}%")
                      ->orWhere('description', 'LIKE', "%{$term}%");
                }
            });
        }

        // Apply Location Filter
        if (!empty($intent['city'])) {
            $cityStr = $intent['city'];
            $query->where(function ($q) use ($cityStr) {
                $q->whereHas('city', function ($cq) use ($cityStr) {
                    $cq->where('name', 'LIKE', "%{$cityStr}%")
                       ->orWhere('slug', 'LIKE', "%{$cityStr}%");
                })->orWhere('address', 'LIKE', "%{$cityStr}%");
            });
        }

        if (!empty($intent['area'])) {
            $areaStr = $intent['area'];
            $query->where(function ($q) use ($areaStr) {
                $q->whereHas('area', function ($aq) use ($areaStr) {
                    $aq->where('name', 'LIKE', "%{$areaStr}%")
                       ->orWhere('slug', 'LIKE', "%{$areaStr}%");
                })->orWhere('address', 'LIKE', "%{$areaStr}%")
                  ->orWhere('landmark', 'LIKE', "%{$areaStr}%");
            });
        }

        // Apply Gender Filter
        if (!empty($intent['gender'])) {
            $gender = strtolower($intent['gender']);
            if ($gender === 'boys') {
                $query->whereIn('gender_preference', ['boys', 'male', 'co-ed', 'unisex', 'all']);
            } elseif ($gender === 'girls') {
                $query->whereIn('gender_preference', ['girls', 'female', 'co-ed', 'unisex', 'all']);
            } elseif ($gender === 'co-ed') {
                $query->whereIn('gender_preference', ['co-ed', 'unisex', 'all']);
            }
        }

        // Apply Budget Filter (Min, Max, or Range)
        if (!empty($intent['min_budget']) && !empty($intent['max_budget'])) {
            $minBudget = (int) $intent['min_budget'];
            $maxBudget = (int) $intent['max_budget'];
            $query->whereBetween('monthly_rent', [max(0, $minBudget - 500), $maxBudget + 800]);
        } elseif (!empty($intent['min_budget'])) {
            $minBudget = (int) $intent['min_budget'];
            $query->where('monthly_rent', '>=', max(0, $minBudget - 500));
        } elseif (!empty($intent['max_budget'])) {
            $maxBudget = (int) $intent['max_budget'];
            $query->where('monthly_rent', '<=', $maxBudget + 800);
        }

        $results = $query->limit(25)->get();

        // Fallback: If no results found with combined filters and a specific PG name was requested,
        // search across all active properties by name only so user always finds the matching PG
        if ($results->isEmpty() && !empty($searchTerms)) {
            $fallbackQuery = Property::query()
                ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType'])
                ->where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_active', 1)
                ->whereNull('deleted_at')
                ->where(function ($q) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $q->orWhere('name', 'LIKE', "%{$term}%")
                          ->orWhere('slug', 'LIKE', "%{$term}%")
                          ->orWhere('landmark', 'LIKE', "%{$term}%")
                          ->orWhere('address', 'LIKE', "%{$term}%");
                    }
                });
            $results = $fallbackQuery->limit(10)->get();
        }

        // 3. Rank and compute Match Scores
        if ($results->isNotEmpty()) {
            $rankedProperties = $this->rankingService->rank($results, $intent);
        } else {
            $rankedProperties = [];
        }

        // 4. Build Active Filter Chips for UI
        $activeFilters = $this->buildActiveFilterChips($intent);

        // 5. Generate conversational summary response
        $summaryMessage = $this->generateSummaryMessage($intent, count($rankedProperties));

        return [
            'success' => true,
            'data' => [
                'response_type' => 'property_list',
                'message' => $summaryMessage,
                'intent' => $intent,
                'active_filters' => $activeFilters,
                'total_matches' => count($rankedProperties),
                'properties' => $rankedProperties,
            ]
        ];
    }

    /**
     * Provide comprehensive knowledge overview for all 3 StayNest Listing Types.
     */
    public function getListingTypesOverview(array $intent = []): array
    {
        // Live Database Counts
        $pgCount = Property::where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)
            ->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['pg-hostel', 'co-living', 'pg', 'hostel']))->count();

        $flatCount = Property::where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)
            ->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['flat', 'apartment', 'house', 'villa', 'flat-apartment']))->count();

        $commercialCount = Property::where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)
            ->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['commercial', 'office', 'shop', 'commercial-space']))->count();

        $listingTypes = [
            [
                'type_key' => 'pg_hostel',
                'title' => 'PG & Hostels',
                'badge' => "{$pgCount}+ Verified Stays",
                'icon' => 'building-user',
                'color' => 'brand',
                'bg_gradient' => 'from-emerald-500 to-teal-700',
                'price_range' => 'Starting from ₹4,500/mo',
                'tagline' => 'Fully managed shared & private rooms with all amenities included',
                'key_features' => [
                    '3 Times Daily Hygienic Food / Meals',
                    'High-Speed WiFi & Air Conditioning (AC)',
                    'Daily Professional Housekeeping & Laundry',
                    '24x7 Power Backup & CCTV Biometric Security',
                    'Zero Brokerage & Flexible 1-Month to 11-Month Stay'
                ],
                'target_audience' => 'Students, Interns, Working Professionals, Singles',
                'explore_prompt' => 'Show me verified PG & Hostels'
            ],
            [
                'type_key' => 'flat_house',
                'title' => 'Flats & Houses',
                'badge' => "{$flatCount}+ Verified Properties",
                'icon' => 'city',
                'color' => 'blue',
                'bg_gradient' => 'from-blue-600 to-indigo-700',
                'price_range' => 'Starting from ₹10,000/mo',
                'tagline' => '1 BHK, 2 BHK, 3 BHK, Furnished Apartments & Independent Houses',
                'key_features' => [
                    'Fully Furnished & Semi-Furnished Options',
                    'Gated Society Security & Dedicated Parking',
                    'Lift, Power Backup & Modular Kitchen',
                    'No Gate Timings / 100% Privacy for Families & Groups',
                    'Direct Landlord Lease with Zero Hidden Charges'
                ],
                'target_audience' => 'Families, Working Couples, Executive Groups',
                'explore_prompt' => 'Show me Flats & Houses'
            ],
            [
                'type_key' => 'commercial',
                'title' => 'Commercial',
                'badge' => "{$commercialCount}+ Verified Spaces",
                'icon' => 'shop',
                'color' => 'purple',
                'bg_gradient' => 'from-purple-600 to-violet-800',
                'price_range' => 'Starting from ₹15,000/mo',
                'tagline' => 'Office Spaces, Retail Shops, Showrooms & Co-working Desks',
                'key_features' => [
                    'Prime High-Footfall Commercial Locations',
                    'Main Road Visibility & Customer Parking',
                    'Plug & Play Office Cabins & Workstations',
                    'High Electric Power Load & Fire Safety Approved',
                    'Direct Owner Connect with Zero Brokerage'
                ],
                'target_audience' => 'Startups, IT Companies, Retail Brands, Clinics, Freelancers',
                'explore_prompt' => 'Show me Commercial spaces'
            ]
        ];

        $summaryMessage = "StayNest currently features **3 verified listing types** in our database with **100% Zero Brokerage**:\n\n" .
            "1. **PG & Hostels** ({$pgCount}+ Stays): Managed rooms with Food, WiFi, AC & Housekeeping for students & professionals.\n" .
            "2. **Flats & Houses** ({$flatCount}+ Properties): 1/2/3 BHK Furnished & Semi-Furnished Apartments & Villas.\n" .
            "3. **Commercial** ({$commercialCount}+ Spaces): Prime Office Cabins, Retail Shops, Showrooms & Coworking spaces.\n\n" .
            "Click any category below to explore available verified spaces in our database!";

        return [
            'success' => true,
            'data' => [
                'response_type' => 'listing_types_overview',
                'message' => $summaryMessage,
                'intent' => $intent,
                'active_filters' => [
                    [
                        'key' => 'listing_types',
                        'label' => '🏷️ All Listing Types',
                        'value' => 'all_types',
                        'icon' => 'layer-group'
                    ]
                ],
                'listing_types' => $listingTypes,
                'total_types' => count($listingTypes)
            ]
        ];
    }

    /**
     * Provide deep knowledge details and database listings for a single specific listing type.
     */
    public function getListingTypeDetails(string $typeKey, array $intent = []): array
    {
        // Determine category meta
        if ($typeKey === 'commercial') {
            $title = 'Commercial Spaces';
            $icon = 'shop';
            $slugs = ['commercial', 'office', 'shop', 'commercial-space'];
            $description = "StayNest offers verified **Commercial Spaces** including Corporate Office Cabins, Retail Ground-Floor Shops, Main Road Showrooms, and Co-working Desks. All commercial listings are 100% verified with direct owner contacts and zero brokerage fees.";
            $features = [
                '🏢 Private Office Cabins & Ready-to-use Workstations',
                '🏪 Prime Ground-Floor Retail Shops & Showrooms',
                '🚗 Customer & Dedicated Staff Parking Facilities',
                '⚡ High Power Backup, Central AC & Fire Safety Approvals',
                '🤝 Direct Landlord Agreement with 0% Broker Commission'
            ];
            $bestFor = 'Startups, Corporate IT Offices, Doctors & Clinics, Retail Outlets, and Businesses';
            $pricing = '₹15,000/mo – ₹85,000/mo';
        } elseif ($typeKey === 'flat_house') {
            $title = 'Flats & Houses';
            $icon = 'city';
            $slugs = ['flat', 'apartment', 'house', 'villa', 'flat-apartment'];
            $description = "StayNest features verified **Flats, Apartments & Independent Houses** ranging from compact 1 BHK studios to spacious 2 BHK, 3 BHK, and luxurious villas with complete privacy, gated society security, and direct landlord terms.";
            $features = [
                '🛋️ Fully Furnished, Semi-Furnished & Unfurnished Units',
                '🔒 24x7 Gated Society Security, CCTV & Lift Access',
                '🅿️ Reserved Car & Two-Wheeler Parking',
                '🍳 Modern Modular Kitchen with Chimney & Piped Gas',
                '🕒 100% Independent Living with No Gate Closing Timings'
            ];
            $bestFor = 'Families, Working Couples, Executive Groups, and Long-Term Tenants';
            $pricing = '₹10,000/mo – ₹35,000/mo';
        } else {
            $title = 'PG & Hostels';
            $icon = 'building-user';
            $slugs = ['pg-hostel', 'co-living', 'pg', 'hostel'];
            $description = "StayNest's **PG & Hostels** provide fully managed co-living and student accommodations equipped with 3 daily meals, high-speed WiFi, air conditioning, daily housekeeping, power backup, and CCTV security.";
            $features = [
                '🍱 3 Times Daily Nutritious Food & Clean RO Water',
                '📶 High-Speed WiFi & Air Conditioned (AC) Rooms',
                '🧹 Daily Professional Housekeeping & Clean Washrooms',
                '⚡ 24x7 Power Backup, Geyser & Biometric Security Entry',
                '🛏️ Single, Double & Triple Sharing Room Options Available'
            ];
            $bestFor = 'College Students, Interns, Working Professionals, and Singles';
            $pricing = '₹4,500/mo – ₹14,000/mo';
        }

        // Query active properties in database for this type
        $query = Property::query()
            ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType'])
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereHas('propertyType', fn($q) => $q->whereIn('slug', $slugs));

        // Apply city / area filter if mentioned
        if (!empty($intent['city'])) {
            $cityStr = $intent['city'];
            $query->where(function ($q) use ($cityStr) {
                $q->whereHas('city', fn($cq) => $cq->where('name', 'LIKE', "%{$cityStr}%"))
                  ->orWhere('address', 'LIKE', "%{$cityStr}%");
            });
        }

        $results = $query->limit(15)->get();
        $rankedProperties = $results->isNotEmpty() ? $this->rankingService->rank($results, $intent) : [];

        $count = count($rankedProperties);
        $summaryMessage = "Here is everything you need to know about **{$title}** on StayNest:\n\n" .
            "{$description}\n\n" .
            "✨ **Key Highlights & Amenities:**\n" . implode("\n", array_map(fn($f) => "• {$f}", $features)) . "\n\n" .
            "👥 **Best Suited For:** {$bestFor}\n" .
            "💰 **Typical Rent:** {$pricing}\n\n" .
            "Found **{$count} active verified {$title}** in our database:";

        return [
            'success' => true,
            'data' => [
                'response_type' => 'listing_type_detail',
                'message' => $summaryMessage,
                'intent' => $intent,
                'active_filters' => [
                    [
                        'key' => 'property_type',
                        'label' => "🏷️ {$title}",
                        'value' => $typeKey,
                        'icon' => $icon
                    ]
                ],
                'listing_type_info' => [
                    'title' => $title,
                    'type_key' => $typeKey,
                    'icon' => $icon,
                    'description' => $description,
                    'features' => $features,
                    'best_for' => $bestFor,
                    'price_range' => $pricing,
                    'total_available' => $count
                ],
                'total_matches' => $count,
                'properties' => $rankedProperties,
            ]
        ];
    }

    /**
     * Provide Trending Searches & Curated Popular Hot Queries and Verified Properties.
     */
    public function getTrendingOverview(array $intent = []): array
    {
        // 1. Fetch top trending, featured, recommended, or highest-rated verified properties
        $trendingProperties = Property::query()
            ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType'])
            ->withCount(['approvedReviews as approved_reviews_count'])
            ->withAvg(['approvedReviews as dynamic_rating' => fn($q) => $q->where('status', 'approved')], 'rating')
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->where('tag', 'Trending')
                  ->orWhere('tag', 'Popular')
                  ->orWhere('tag', 'Guest Favourite')
                  ->orWhere('tag', 'Top rated')
                  ->orWhere('is_recommended', 1)
                  ->orWhere('featured', 1);
            })
            ->orderByDesc('rating')
            ->orderByDesc('total_reviews')
            ->limit(12)
            ->get();

        if ($trendingProperties->isEmpty()) {
            $trendingProperties = Property::query()
                ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType'])
                ->withCount(['approvedReviews as approved_reviews_count'])
                ->withAvg(['approvedReviews as dynamic_rating' => fn($q) => $q->where('status', 'approved')], 'rating')
                ->where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_active', 1)
                ->whereNull('deleted_at')
                ->orderByDesc('rating')
                ->limit(12)
                ->get();
        }

        $rankedProperties = $this->rankingService->rank($trendingProperties, $intent);

        $trendingSearchCategories = [
            [
                'category_name' => '🔥 Top Trending Searches Right Now',
                'category_icon' => 'fire',
                'color' => 'orange',
                'queries' => [
                    [
                        'title' => 'Noida Sector 62 Boys PG < ₹8k AC + Food',
                        'prompt' => 'Noida sector 62 me boys PG 8k ke andar AC food ke saath',
                        'badge' => '#1 Trending',
                        'icon' => 'bolt',
                        'badge_color' => 'bg-orange-500 text-white'
                    ],
                    [
                        'title' => 'Girls AC PG in Bangalore Koramangala < ₹10k',
                        'prompt' => 'Girls PG with AC in Bangalore Koramangala under 10k',
                        'badge' => '⚡ High Demand',
                        'icon' => 'sparkles',
                        'badge_color' => 'bg-pink-500 text-white'
                    ],
                    [
                        'title' => 'Single Room + Attached Washroom (Zero Deposit)',
                        'prompt' => 'single room chahiye attached washroom ke saath zero deposit',
                        'badge' => '🎯 Most Saved',
                        'icon' => 'door-open',
                        'badge_color' => 'bg-purple-600 text-white'
                    ],
                    [
                        'title' => '2 BHK Fully Furnished Flat Near Metro',
                        'prompt' => '2 BHK furnished flat near metro station zero brokerage',
                        'badge' => '🏢 Family / Coliving',
                        'icon' => 'building',
                        'badge_color' => 'bg-blue-600 text-white'
                    ],
                ]
            ],
            [
                'category_name' => '🚀 Hot Tech & Student Hubs',
                'category_icon' => 'graduation-cap',
                'color' => 'emerald',
                'queries' => [
                    [
                        'title' => 'Boys PG near Knowledge Park Greater Noida',
                        'prompt' => 'Boys PG near Knowledge Park with wifi and meals',
                        'badge' => '🎓 Students',
                        'icon' => 'user-graduate',
                        'badge_color' => 'bg-emerald-600 text-white'
                    ],
                    [
                        'title' => 'Co-ed / Unisex Stays in Pune Hinjewadi IT Park',
                        'prompt' => 'Co-ed stays in Pune Hinjewadi IT park with high speed wifi',
                        'badge' => '💻 IT Techies',
                        'icon' => 'laptop-code',
                        'badge_color' => 'bg-indigo-600 text-white'
                    ],
                    [
                        'title' => 'Budget Friendly Stays Under ₹6,000 / month',
                        'prompt' => 'budget PG under 6000 with food and wifi',
                        'badge' => '💰 Value Pick',
                        'icon' => 'tags',
                        'badge_color' => 'bg-teal-600 text-white'
                    ],
                ]
            ],
            [
                'category_name' => '🏪 Commercial & Office Spaces',
                'category_icon' => 'shop',
                'color' => 'amber',
                'queries' => [
                    [
                        'title' => 'Ready-to-Move Commercial Office Cabins',
                        'prompt' => 'Commercial office space ready to move with parking',
                        'badge' => '💼 Business Hub',
                        'icon' => 'briefcase',
                        'badge_color' => 'bg-amber-600 text-white'
                    ],
                    [
                        'title' => 'Ground Floor Retail Shop / Showroom Space',
                        'prompt' => 'Ground floor retail shop main road commercial space',
                        'badge' => '🛍️ High Footfall',
                        'icon' => 'store',
                        'badge_color' => 'bg-rose-600 text-white'
                    ],
                ]
            ]
        ];

        $summaryMessage = "Here are today's **🔥 Top Trending Searches & Verified Stays** on StayNest! Tap any trending query below to see live verified matches:";

        return [
            'success' => true,
            'data' => [
                'response_type' => 'trending_overview',
                'message' => $summaryMessage,
                'intent' => $intent,
                'active_filters' => [
                    [
                        'key' => 'trending',
                        'label' => '🔥 Live Trending Searches',
                        'value' => 'trending',
                        'icon' => 'fire'
                    ]
                ],
                'trending_categories' => $trendingSearchCategories,
                'total_matches' => count($rankedProperties),
                'properties' => $rankedProperties,
            ]
        ];
    }

    /**
     * Handle detailed property inquiry.
     *
     * @param string $targetName
     * @param array $intent
     * @return array
     */
    public function getPropertyDetails(string $targetName, array $intent = []): array
    {
        $cleanTarget = trim($targetName);

        // 1. Exact name, slug, or ID match
        $property = Property::query()
            ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType', 'broker.profile'])
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->where(function ($q) use ($cleanTarget) {
                $q->where('name', $cleanTarget)
                  ->orWhere('slug', $cleanTarget)
                  ->orWhere('id', $cleanTarget);
            })
            ->first();

        // 2. Fuzzy / LIKE Match
        if (!$property) {
            $coreName = trim(preg_replace('/\b(pg|hostel|stay|stays|residency|rooms?|living|house|the)\b/iu', '', $cleanTarget));
            $searchTerm = !empty($coreName) ? $coreName : $cleanTarget;

            $property = Property::query()
                ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType', 'broker.profile'])
                ->where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_active', 1)
                ->whereNull('deleted_at')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('slug', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('address', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('landmark', 'LIKE', "%{$searchTerm}%");
                })
                ->orderByRaw("CASE WHEN name LIKE ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END", ["{$searchTerm}%", "%{$searchTerm}%"])
                ->first();
        }

        // 3. Fallback: If no single property found, fallback to general search with this term
        if (!$property) {
            return $this->search($targetName);
        }

        // Format Property Detail Payload
        $gender = strtoupper($property->gender_preference ?? 'CO-ED');
        if (in_array($gender, ['ALL', 'ANY', 'COED', 'UNISEX'])) $gender = 'CO-ED';
        elseif ($gender === 'MALE' || $gender === 'BOY') $gender = 'BOYS';
        elseif ($gender === 'FEMALE' || $gender === 'GIRL') $gender = 'GIRLS';

        $cityName = $property->city->name ?? $property->city ?? 'Noida';
        $areaName = $property->area->name ?? null;
        $locationDisplay = $property->address ?: ($areaName ? "$areaName, $cityName" : "$cityName Central");

        // Format amenities
        $amenities = [];
        if ($property->amenities && $property->amenities->isNotEmpty()) {
            foreach ($property->amenities as $am) {
                $amenities[] = [
                    'name' => $am->name,
                    'icon' => $am->icon ?? 'check',
                    'category' => $am->category ?? 'General'
                ];
            }
        } else {
            $amenities = [
                ['name' => 'High-Speed WiFi', 'icon' => 'wifi', 'category' => 'Connectivity'],
                ['name' => 'Air Conditioning', 'icon' => 'snowflake', 'category' => 'Comfort'],
                ['name' => 'Daily Meals (3 Times)', 'icon' => 'utensils', 'category' => 'Food'],
                ['name' => 'Power Backup 24x7', 'icon' => 'bolt', 'category' => 'Utility'],
                ['name' => 'CCTV Security', 'icon' => 'shield-alt', 'category' => 'Safety'],
                ['name' => 'Attached Washroom', 'icon' => 'bath', 'category' => 'Room'],
            ];
        }

        // Format House Rules
        $rules = [];
        if ($property->rules && $property->rules->isNotEmpty()) {
            foreach ($property->rules as $r) {
                $rules[] = $r->rule_text;
            }
        } else {
            $rules = [
                'Gate Closing Time: 10:30 PM',
                'Visitors allowed in common areas till 8:00 PM',
                'Smoking & Alcohol strictly prohibited inside premises',
                'Quiet hours from 11:00 PM to 6:00 AM'
            ];
        }

        // Format Image Gallery
        $images = [];
        if ($property->images && $property->images->isNotEmpty()) {
            foreach ($property->images as $img) {
                if (!empty($img->image_url)) {
                    $images[] = $img->image_url;
                }
            }
        }
        if (empty($images)) {
            $images[] = $property->display_image_url;
        }

        $monthlyRent = (int) ($property->monthly_rent ?? 6500);
        $deposit = (int) ($property->security_deposit ?? $monthlyRent);
        $totalBeds = (int) ($property->total_beds ?? 20);
        $availBeds = (int) ($property->available_beds ?? 4);
        $noticePeriod = (int) ($property->notice_period_days ?? 30);

        $summaryMessage = "Here are the complete verified details for **{$property->name}** in {$cityName}:";

        return [
            'success' => true,
            'data' => [
                'response_type' => 'property_detail',
                'message' => $summaryMessage,
                'intent' => $intent,
                'active_filters' => [
                    [
                        'key' => 'property_name',
                        'label' => '🏠 ' . $property->name,
                        'value' => $property->name,
                        'icon' => 'building'
                    ]
                ],
                'property' => [
                    'id' => $property->id,
                    'name' => $property->name,
                    'slug' => $property->slug ?? $property->id,
                    'image' => $property->display_image_url,
                    'images' => $images,
                    'rating' => (float) ($property->dynamic_rating > 0 ? $property->dynamic_rating : ($property->rating ?? 0)),
                    'total_reviews' => (int) $property->dynamic_reviews_count,
                    'verified' => $property->verification_status === 'verified',
                    'featured' => (bool) $property->featured,
                    'tag' => $property->tag ?? 'Popular',
                    'tag_meta' => $property->display_tag_meta ?? [
                        'label' => 'Verified',
                        'icon' => 'check-circle',
                        'bg_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'solid_badge' => 'bg-emerald-500 text-white',
                        'dot_color' => 'bg-emerald-500',
                    ],
                    'city' => $cityName,
                    'area' => $areaName,
                    'location' => $locationDisplay,
                    'address' => $property->address,
                    'landmark' => $property->landmark,
                    'price' => $monthlyRent,
                    'formatted_price' => '₹' . number_format($monthlyRent) . '/mo',
                    'security_deposit' => $deposit,
                    'formatted_deposit' => '₹' . number_format($deposit),
                    'notice_period_days' => $noticePeriod,
                    'total_beds' => $totalBeds,
                    'available_beds' => $availBeds,
                    'gender' => $gender,
                    'gender_class' => $gender === 'GIRLS' ? 'bg-pink-50 text-pink-600' : ($gender === 'BOYS' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600'),
                    'description' => $property->description ?: "Experience premium {$gender} co-living at {$property->name}, equipped with high-speed WiFi, daily meals, 24x7 security, and professional housekeeping.",
                    'amenities' => $amenities,
                    'rules' => $rules,
                    'match_score' => 100,
                    'detail_url' => route('user.detail', $property->id),
                    'latitude' => (float) ($property->latitude ?? 28.6280),
                    'longitude' => (float) ($property->longitude ?? 77.3649),
                ]
            ]
        ];
    }

    /**
     * Build active filter chips for removable UI badges.
     */
    protected function buildActiveFilterChips(array $intent): array
    {
        $chips = [];

        if (!empty($intent['property_type'])) {
            $pt = $intent['property_type'];
            $label = $pt === 'commercial' ? '🏪 Commercial' : ($pt === 'flat_house' ? '🏢 Flats & Houses' : '🏠 PG & Hostels');
            $chips[] = [
                'key' => 'property_type',
                'label' => $label,
                'value' => $pt,
                'icon' => $pt === 'commercial' ? 'shop' : ($pt === 'flat_house' ? 'city' : 'building-user')
            ];
        }

        if (!empty($intent['property_name'])) {
            $chips[] = [
                'key' => 'property_name',
                'label' => '🔍 ' . $intent['property_name'],
                'value' => $intent['property_name'],
                'icon' => 'magnifying-glass'
            ];
        }

        if (!empty($intent['gender'])) {
            $g = $intent['gender'];
            $label = $g === 'boys' ? '👨 Boys Only' : ($g === 'girls' ? '👩 Girls Only' : '👥 Co-Ed / Unisex');
            $chips[] = [
                'key' => 'gender',
                'label' => $label,
                'value' => $g,
                'icon' => $g === 'boys' ? 'male' : ($g === 'girls' ? 'female' : 'users')
            ];
        }

        if (!empty($intent['city'])) {
            $loc = $intent['city'];
            if (!empty($intent['area'])) {
                $loc .= ' • ' . $intent['area'];
            }
            $chips[] = [
                'key' => 'location',
                'label' => '📍 ' . $loc,
                'value' => $intent['city'],
                'icon' => 'location-dot'
            ];
        } elseif (!empty($intent['area'])) {
            $chips[] = [
                'key' => 'area',
                'label' => '📍 ' . $intent['area'],
                'value' => $intent['area'],
                'icon' => 'location-dot'
            ];
        }

        if (!empty($intent['min_budget']) && !empty($intent['max_budget'])) {
            $chips[] = [
                'key' => 'budget_range',
                'label' => '💰 ₹' . number_format($intent['min_budget']) . ' - ₹' . number_format($intent['max_budget']),
                'value' => $intent['min_budget'] . '-' . $intent['max_budget'],
                'icon' => 'tag'
            ];
        } elseif (!empty($intent['min_budget'])) {
            $chips[] = [
                'key' => 'min_budget',
                'label' => '💰 Above ₹' . number_format($intent['min_budget']),
                'value' => $intent['min_budget'],
                'icon' => 'tag'
            ];
        } elseif (!empty($intent['max_budget'])) {
            $chips[] = [
                'key' => 'budget',
                'label' => '💰 Under ₹' . number_format($intent['max_budget']),
                'value' => $intent['max_budget'],
                'icon' => 'tag'
            ];
        }

        if (!empty($intent['amenities'])) {
            foreach ($intent['amenities'] as $am) {
                $icon = 'check';
                $label = ucfirst($am);
                if ($am === 'ac') { $icon = 'snowflake'; $label = '❄️ AC'; }
                elseif ($am === 'food') { $icon = 'utensils'; $label = '🍱 Food'; }
                elseif ($am === 'wifi') { $icon = 'wifi'; $label = '📶 WiFi'; }
                elseif ($am === 'gym') { $icon = 'dumbbell'; $label = '🏋️ Gym'; }
                elseif ($am === 'attached_bath') { $icon = 'bath'; $label = '🚿 Attached Bath'; }
                elseif ($am === 'near_metro') { $icon = 'train'; $label = '🚇 Near Metro'; }
                elseif ($am === 'zero_deposit') { $icon = 'shield-halved'; $label = '⚡ Zero Deposit'; }

                $chips[] = [
                    'key' => 'amenity_' . $am,
                    'amenity' => $am,
                    'label' => $label,
                    'value' => $am,
                    'icon' => $icon
                ];
            }
        }

        return $chips;
    }

    /**
     * Generate conversational summary message.
     */
    protected function generateSummaryMessage(array $intent, int $totalMatches): string
    {
        $typeLabel = 'stays';
        if (!empty($intent['property_type'])) {
            if ($intent['property_type'] === 'commercial') $typeLabel = 'commercial spaces';
            elseif ($intent['property_type'] === 'flat_house') $typeLabel = 'flats & apartments';
            elseif ($intent['property_type'] === 'pg_hostel') $typeLabel = 'PG & hostels';
        }

        if ($totalMatches === 0) {
            if (!empty($intent['property_name'])) {
                return "No approved verified property found matching '{$intent['property_name']}'. Try searching by area or city to find available {$typeLabel}!";
            }
            $loc = $intent['area'] ?? $intent['city'] ?? null;
            if ($loc) {
                return "No approved verified {$typeLabel} found in {$loc}. We are actively expanding to this location soon!";
            }
            if (!empty($intent['min_budget']) && !empty($intent['max_budget'])) {
                return "No approved {$typeLabel} found in ₹" . number_format($intent['min_budget']) . " - ₹" . number_format($intent['max_budget']) . " range. Try broadening your budget filter.";
            } elseif (!empty($intent['min_budget'])) {
                return "No approved {$typeLabel} found above ₹" . number_format($intent['min_budget']) . ". Try adjusting your price criteria.";
            } elseif (!empty($intent['max_budget'])) {
                return "No approved {$typeLabel} found matching ₹" . number_format($intent['max_budget']) . " budget. Try increasing your budget filter.";
            }
            return "No matching verified {$typeLabel} found in database. Try modifying your search criteria or keywords.";
        }

        if (!empty($intent['property_name'])) {
            return "I found {$totalMatches} verified property matching '**{$intent['property_name']}**':";
        }

        $parts = [];
        if (!empty($intent['gender'])) {
            $parts[] = ucfirst($intent['gender']) . ' PG';
        } else {
            $parts[] = $typeLabel;
        }

        if (!empty($intent['area'])) {
            $parts[] = 'in ' . $intent['area'];
        } elseif (!empty($intent['city'])) {
            $parts[] = 'in ' . $intent['city'];
        }

        if (!empty($intent['min_budget']) && !empty($intent['max_budget'])) {
            $parts[] = 'between ₹' . number_format($intent['min_budget']) . ' - ₹' . number_format($intent['max_budget']);
        } elseif (!empty($intent['min_budget'])) {
            $parts[] = 'above ₹' . number_format($intent['min_budget']);
        } elseif (!empty($intent['max_budget'])) {
            $parts[] = 'under ₹' . number_format($intent['max_budget']);
        }

        $queryDesc = implode(' ', $parts);
        if (empty($queryDesc)) $queryDesc = $typeLabel;

        return "I found {$totalMatches} verified {$queryDesc} with high StayNest match scores in our database:";
    }
}
