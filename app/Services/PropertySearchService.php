<?php

namespace App\Services;

use App\Models\Property;
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

        // Check if user is asking for specific PG details (e.g. "tell me more about Royal PG")
        if (!empty($intent['is_detail_query']) && !empty($intent['target_property_name'])) {
            return $this->getPropertyDetails($intent['target_property_name'], $intent);
        }

        // 2. Query ONLY active & admin-verified database properties
        $query = Property::query()
            ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'rules', 'propertyType'])
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->whereNull('deleted_at');

        // Apply PG Name / Keyword Filter
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
            $query->where('monthly_rent', '>=', max(0, $minBudget - 500)); // tolerance for close matches
        } elseif (!empty($intent['max_budget'])) {
            $maxBudget = (int) $intent['max_budget'];
            $query->where('monthly_rent', '<=', $maxBudget + 800); // 800 tolerance for close matches
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

        $amenitySummary = implode(', ', array_slice(array_column($amenities, 'name'), 0, 5));
        $rulesSummary = implode(' • ', array_slice($rules, 0, 2));

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
        if ($totalMatches === 0) {
            if (!empty($intent['property_name'])) {
                return "No approved verified property found matching '{$intent['property_name']}'. Try searching by area or city to find available stays!";
            }
            $loc = $intent['area'] ?? $intent['city'] ?? null;
            if ($loc) {
                return "No approved verified properties found in {$loc}. We are actively expanding to this location soon!";
            }
            if (!empty($intent['min_budget']) && !empty($intent['max_budget'])) {
                return "No approved properties found in ₹" . number_format($intent['min_budget']) . " - ₹" . number_format($intent['max_budget']) . " range. Try broadening your budget filter.";
            } elseif (!empty($intent['min_budget'])) {
                return "No approved properties found above ₹" . number_format($intent['min_budget']) . ". Try adjusting your price criteria.";
            } elseif (!empty($intent['max_budget'])) {
                return "No approved properties found matching ₹" . number_format($intent['max_budget']) . " budget. Try increasing your budget filter.";
            }
            return "No matching verified properties found in database. Try modifying your search criteria or keywords.";
        }

        if (!empty($intent['property_name'])) {
            return "I found {$totalMatches} verified property matching '**{$intent['property_name']}**':";
        }

        $parts = [];
        if (!empty($intent['gender'])) {
            $parts[] = ucfirst($intent['gender']) . ' PG';
        } else {
            $parts[] = 'stays';
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
        if (empty($queryDesc)) $queryDesc = 'stays';

        return "I found {$totalMatches} verified {$queryDesc} with high StayNest match scores:";
    }
}
