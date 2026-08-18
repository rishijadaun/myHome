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

        // 2. Query ONLY active & admin-verified database properties
        $query = Property::query()
            ->with(['city', 'area', 'images', 'primaryImage', 'amenities', 'propertyType'])
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('is_active', 1)
            ->whereNull('deleted_at');

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

        // Apply Budget Filter
        if (!empty($intent['max_budget'])) {
            $maxBudget = (int) $intent['max_budget'];
            $query->where('monthly_rent', '<=', $maxBudget + 800); // 800 tolerance for close matches
        }

        $results = $query->limit(25)->get();

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
                'message' => $summaryMessage,
                'intent' => $intent,
                'active_filters' => $activeFilters,
                'total_matches' => count($rankedProperties),
                'properties' => $rankedProperties,
            ]
        ];
    }

    /**
     * Build active filter chips for removable UI badges.
     */
    protected function buildActiveFilterChips(array $intent): array
    {
        $chips = [];

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

        if (!empty($intent['max_budget'])) {
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
            $loc = $intent['area'] ?? $intent['city'] ?? null;
            if ($loc) {
                return "No approved verified properties found in {$loc}. We are actively expanding to this location soon!";
            }
            if (!empty($intent['max_budget'])) {
                return "No approved properties found matching ₹" . number_format($intent['max_budget']) . " budget. Try increasing your budget filter.";
            }
            return "No matching verified properties found in database. Try modifying your search criteria or keywords.";
        }

        $parts = [];
        if (!empty($intent['gender'])) {
            $parts[] = ucfirst($intent['gender']) . ' PG';
        } else {
            $parts[] = 'verified stays';
        }

        if (!empty($intent['area'])) {
            $parts[] = 'in ' . $intent['area'];
        } elseif (!empty($intent['city'])) {
            $parts[] = 'in ' . $intent['city'];
        }

        if (!empty($intent['max_budget'])) {
            $parts[] = 'under ₹' . number_format($intent['max_budget']);
        }

        $queryDesc = implode(' ', $parts);
        if (empty($queryDesc)) $queryDesc = 'stays';

        return "I found {$totalMatches} verified {$queryDesc} with high StayNest match scores:";
    }
}
