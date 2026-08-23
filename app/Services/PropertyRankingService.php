<?php

namespace App\Services;

use App\Models\Property;

class PropertyRankingService
{
    /**
     * Rank a collection of properties and calculate match scores and breakdowns.
     *
     * @param iterable $properties
     * @param array $intent
     * @return array
     */
    public function rank($properties, array $intent): array
    {
        $ranked = [];

        foreach ($properties as $property) {
            $analysis = $this->evaluateMatch($property, $intent);
            
            $gender = strtoupper($property->gender_preference ?? 'CO-ED');
            if (in_array($gender, ['ALL', 'ANY', 'COED', 'UNISEX'])) $gender = 'CO-ED';
            elseif ($gender === 'MALE' || $gender === 'BOY') $gender = 'BOYS';
            elseif ($gender === 'FEMALE' || $gender === 'GIRL') $gender = 'GIRLS';

            $cityName = $property->city->name ?? $property->city ?? 'Noida';
            $areaName = $property->area->name ?? null;
            $locationDisplay = $property->address ?: ($areaName ? "$areaName, $cityName" : "$cityName Central");

            $amenitiesList = $property->amenities ? $property->amenities->pluck('name')->toArray() : [];
            if (empty($amenitiesList) && is_array($property->amenities_list)) {
                $amenitiesList = $property->amenities_list;
            }
            if (empty($amenitiesList)) {
                $amenitiesList = ['WiFi', '3 Meals', 'Security'];
            }

            $monthlyRent = (int) ($property->monthly_rent ?? 6500);

            $propSlug = $property->slug ?: \Illuminate\Support\Str::slug($property->name);

            $ranked[] = [
                'id' => $property->id,
                'name' => $property->name,
                'slug' => $propSlug,
                'image' => $property->display_image_url,
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
                'price' => $monthlyRent,
                'formatted_price' => '₹' . number_format($monthlyRent) . '/mo',
                'gender' => $gender,
                'gender_class' => $gender === 'GIRLS' ? 'bg-pink-50 text-pink-600' : ($gender === 'BOYS' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600'),
                'amenities' => array_slice($amenitiesList, 0, 4),
                'match_score' => $analysis['score'],
                'match_breakdown' => $analysis['breakdown'],
                'detail_url' => route('user.detail', ['slug' => $propSlug]),
                'latitude' => (float) ($property->latitude ?? 28.6280),
                'longitude' => (float) ($property->longitude ?? 77.3649),
            ];
        }

        // Sort by match_score DESC, then rating DESC, then featured DESC
        usort($ranked, function ($a, $b) {
            if ($b['match_score'] !== $a['match_score']) {
                return $b['match_score'] <=> $a['match_score'];
            }
            if ($b['rating'] !== $a['rating']) {
                return $b['rating'] <=> $a['rating'];
            }
            return ($b['featured'] ? 1 : 0) <=> ($a['featured'] ? 1 : 0);
        });

        return $ranked;
    }

    /**
     * Rank Eloquent Property collection and attach match_score and match_breakdown directly.
     *
     * @param \Illuminate\Support\Collection|iterable $properties
     * @param array $intent
     * @return \Illuminate\Support\Collection
     */
    public function rankModels($properties, array $intent): \Illuminate\Support\Collection
    {
        $collection = collect($properties);

        $collection->each(function (Property $property) use ($intent) {
            $analysis = $this->evaluateMatch($property, $intent);
            $property->match_score = $analysis['score'];
            $property->match_breakdown = $analysis['breakdown'];
        });

        return $collection->sort(function ($a, $b) {
            if ($b->match_score !== $a->match_score) {
                return $b->match_score <=> $a->match_score;
            }
            if ($b->rating !== $a->rating) {
                return $b->rating <=> $a->rating;
            }
            return ($b->featured ? 1 : 0) <=> ($a->featured ? 1 : 0);
        })->values();
    }

    /**
     * Calculate match score (0 - 100) and detailed breakdown for a property.
     *
     * @param Property $property
     * @param array $intent
     * @return array
     */
    protected function evaluateMatch(Property $property, array $intent): array
    {
        $score = 60; // Base score
        $breakdown = [];

        $propPrice = (int) ($property->monthly_rent ?? 6500);
        $propGender = strtolower($property->gender_preference ?? 'co-ed');
        $propCity = strtolower($property->city->name ?? '');
        $propArea = strtolower($property->area->name ?? '');
        $propAddress = strtolower($property->address ?? '');
        $propAmenities = $property->amenities ? $property->amenities->pluck('name')->map(fn($n) => strtolower($n))->toArray() : [];

        // 1. Budget Evaluation
        if (!empty($intent['min_budget']) && !empty($intent['max_budget'])) {
            $bMin = (int) $intent['min_budget'];
            $bMax = (int) $intent['max_budget'];
            if ($propPrice >= $bMin && $propPrice <= $bMax) {
                $score += 20;
                $breakdown[] = [
                    'feature' => 'Rent ₹' . number_format($propPrice) . '/mo (Within ₹' . number_format($bMin) . ' - ₹' . number_format($bMax) . ')',
                    'matched' => true,
                ];
            } else {
                $score -= 10;
                $breakdown[] = [
                    'feature' => 'Rent: ₹' . number_format($propPrice) . '/mo (Target: ₹' . number_format($bMin) . ' - ₹' . number_format($bMax) . ')',
                    'matched' => false,
                ];
            }
        } elseif (!empty($intent['min_budget'])) {
            $budgetMin = (int) $intent['min_budget'];
            if ($propPrice >= $budgetMin) {
                $score += 15;
                $breakdown[] = [
                    'feature' => 'Rent ₹' . number_format($propPrice) . '/mo (Above ₹' . number_format($budgetMin) . ')',
                    'matched' => true,
                ];
            } else {
                $score -= 15;
                $breakdown[] = [
                    'feature' => 'Rent ₹' . number_format($propPrice) . '/mo (Below minimum ₹' . number_format($budgetMin) . ')',
                    'matched' => false,
                ];
            }
        } elseif (!empty($intent['max_budget'])) {
            $budgetMax = (int) $intent['max_budget'];
            if ($propPrice <= $budgetMax) {
                $score += 15;
                $diff = $budgetMax - $propPrice;
                if ($diff >= 1000) $score += 5; // Savings bonus
                $breakdown[] = [
                    'feature' => 'Budget under ₹' . number_format($budgetMax) . ' (₹' . number_format($propPrice) . '/mo)',
                    'matched' => true,
                ];
            } else {
                $score -= 15;
                $breakdown[] = [
                    'feature' => 'Exceeds budget of ₹' . number_format($budgetMax) . ' (₹' . number_format($propPrice) . '/mo)',
                    'matched' => false,
                ];
            }
        } else {
            $breakdown[] = [
                'feature' => 'Rent: ₹' . number_format($propPrice) . '/mo',
                'matched' => true,
            ];
        }

        // 2. Gender Evaluation
        if (!empty($intent['gender'])) {
            $reqGender = strtolower($intent['gender']);
            $isExact = ($reqGender === 'boys' && in_array($propGender, ['boys', 'male'])) ||
                       ($reqGender === 'girls' && in_array($propGender, ['girls', 'female'])) ||
                       ($reqGender === 'co-ed' && in_array($propGender, ['co-ed', 'unisex', 'all']));

            if ($isExact) {
                $score += 15;
                $breakdown[] = [
                    'feature' => ucfirst($reqGender) . ' Stay',
                    'matched' => true,
                ];
            } elseif (in_array($propGender, ['co-ed', 'unisex', 'all'])) {
                $score += 8;
                $breakdown[] = [
                    'feature' => 'Co-Ed / Unisex Accommodation',
                    'matched' => true,
                ];
            } else {
                $score -= 20;
                $breakdown[] = [
                    'feature' => ucfirst($propGender) . ' Only',
                    'matched' => false,
                ];
            }
        }

        // 3. Location Evaluation (City & Area)
        if (!empty($intent['area'])) {
            $reqArea = strtolower($intent['area']);
            if (str_contains($propArea, $reqArea) || str_contains($propAddress, $reqArea)) {
                $score += 15;
                $breakdown[] = [
                    'feature' => $intent['area'] . ' Location',
                    'matched' => true,
                ];
            } else {
                $score += 5;
                $breakdown[] = [
                    'feature' => ($property->area->name ?? $property->city->name ?? 'Nearby') . ' Location',
                    'matched' => true,
                ];
            }
        } elseif (!empty($intent['city'])) {
            $reqCity = strtolower($intent['city']);
            if (str_contains($propCity, $reqCity) || str_contains($propAddress, $reqCity)) {
                $score += 12;
                $breakdown[] = [
                    'feature' => $intent['city'] . ' Region',
                    'matched' => true,
                ];
            }
        }

        // 4. Amenities Evaluation
        if (!empty($intent['amenities']) && is_array($intent['amenities'])) {
            foreach ($intent['amenities'] as $am) {
                $hasAm = false;
                $label = ucfirst($am);

                if ($am === 'ac') {
                    $label = 'AC Cooling';
                    $hasAm = $this->checkAmenityPresence($propAmenities, ['ac', 'air conditioner']);
                // } elseif ($am === 'food') {
                //     $label = 'Food & Meals';
                //     $hasAm = $this->checkAmenityPresence($propAmenities, ['food', 'meal', 'mess', 'breakfast', 'dinner']);
                } elseif ($am === 'wifi') {
                    $label = 'High-Speed WiFi';
                    $hasAm = $this->checkAmenityPresence($propAmenities, ['wifi', 'wi-fi', 'internet']);
                } elseif ($am === 'gym') {
                    $label = 'Gym / Fitness';
                    $hasAm = $this->checkAmenityPresence($propAmenities, ['gym', 'fitness']);
                } elseif ($am === 'attached_bath') {
                    $label = 'Attached Washroom';
                    $hasAm = $this->checkAmenityPresence($propAmenities, ['attached', 'bath', 'washroom']);
                } elseif ($am === 'near_metro') {
                    $label = 'Near Metro Station';
                    $hasAm = str_contains($propAddress, 'metro') || $this->checkAmenityPresence($propAmenities, ['metro']);
                } elseif ($am === 'zero_deposit') {
                    $label = 'Zero Deposit Available';
                    $hasAm = true;
                }

                if ($hasAm) {
                    $score += 4;
                    $breakdown[] = [
                        'feature' => $label,
                        'matched' => true,
                    ];
                } else {
                    $breakdown[] = [
                        'feature' => $label,
                        'matched' => false,
                    ];
                }
            }
        }

        // 5. PG Name & Keyword Evaluation
        if (!empty($intent['property_name']) || !empty($intent['keywords'])) {
            $propNameLower = strtolower($property->name ?? '');
            $searchTerms = array_filter(array_merge(
                !empty($intent['property_name']) ? [$intent['property_name']] : [],
                $intent['keywords'] ?? []
            ));

            $hasExactNameMatch = false;
            foreach ($searchTerms as $term) {
                $t = strtolower(trim($term));
                if (mb_strlen($t) >= 3 && str_contains($propNameLower, $t)) {
                    $hasExactNameMatch = true;
                    break;
                }
            }

            if ($hasExactNameMatch) {
                $score += 35;
                $breakdown[] = [
                    'feature' => 'Name Matched: ' . $property->name,
                    'matched' => true,
                ];
            }
        }

        // Verification bonus
        if ($property->verification_status === 'verified') {
            $score += 4;
        }
        if ($property->rating >= 4.7) {
            $score += 3;
        }

        // Clamp between 65% and 99%
        $finalScore = min(99, max(65, $score));

        return [
            'score' => $finalScore,
            'breakdown' => $breakdown,
        ];
    }

    protected function checkAmenityPresence(array $propAmenities, array $needles): bool
    {
        foreach ($propAmenities as $am) {
            foreach ($needles as $needle) {
                if (str_contains($am, $needle)) return true;
            }
        }
        return false;
    }
}
