<?php

namespace App\Services;

use App\Models\City;
use App\Models\Area;
use App\Models\Amenity;

class AiIntentParserService
{
    /**
     * Common City & Hub Aliases for Indian Metros and Educational/Tech Hubs
     */
    protected array $cityAliases = [
        'Noida' => ['noida', 'greater noida', 'sec 62', 'sector 62', 'sec 18', 'sector 18', 'electronic city noida', 'knowledge park'],
        'Bangalore' => ['bangalore', 'bengaluru', 'koramangala', 'indiranagar', 'hsr layout', 'hsr', 'electronic city', 'whitefield', 'bellandur', 'marathahalli', 'btm'],
        'Delhi' => ['delhi', 'new delhi', 'saket', 'laxmi nagar', 'north campus', 'south ex', 'hauz khas', 'dwarka', 'mukherjee nagar', 'cp', 'rohini', 'pitampura', 'janakpuri'],
        'Pune' => ['pune', 'viman nagar', 'hinjewadi', 'kothrud', 'wakad', 'baner', 'hadapsar', 'kharadi', 'magarpatta', 'shivaji nagar'],
        'Hyderabad' => ['hyderabad', 'hitec city', 'madhapur', 'gachibowli', 'kondapur', 'kukatpally', 'ameerpet', 'secunderabad'],
        'Mumbai' => ['mumbai', 'andheri', 'bandra', 'powai', 'navi mumbai', 'thane', 'dadar', 'juhu', 'borivali', 'goregaon', 'malad'],
        'Gurugram' => ['gurugram', 'gurgaon', 'cyber city', 'dlf phase', 'sohna road', 'golf course road', 'sector 29', 'sector 14'],
        'Jaipur' => ['jaipur', 'malviya nagar', 'mansarovar', 'vaishali nagar', 'raja park', 'c scheme', 'gopalpura'],
        'Orai' => ['orai', 'jalaun', 'rath road', 'station road orai', 'konch'],
        'Jhansi' => ['jhansi', 'sadar bazar jhansi', 'sipri bazar', 'elite crossing jhansi'],
        'Kanpur' => ['kanpur', 'kakadeo', 'swaroop nagar', 'kalyanpur', 'civil lines kanpur'],
        'Lucknow' => ['lucknow', 'gomti nagar', 'hazratganj', 'aliganj', 'indira nagar', 'charbagh'],
        'Kota' => ['kota', 'landmark city', 'vigyan nagar', 'talwandi', 'mahaveer nagar', 'indira vihar', 'kunhari'],
        'Dehradun' => ['dehradun', 'rajpur road', 'karanpur', 'ballupur', 'bidholi'],
        'Ahmedabad' => ['ahmedabad', 'sg highway', 'navrangpura', 'vastrapur', 'bodakdev'],
        'Indore' => ['indore', 'vijay nagar', 'bhanwarkuan', 'palasia', 'geeta bhawan'],
        'Chandigarh' => ['chandigarh', 'mohali', 'panchkula', 'sector 17', 'sector 35'],
        'Kolkata' => ['kolkata', 'salt lake', 'new town', 'park street', 'ballygunge', 'jadavpur'],
        'Chennai' => ['chennai', 'omr', 'velachery', 'guindy', 'anna nagar', 'thoraipakkam', 'adyar']
    ];

    /**
     * Parse natural language message into a structured search intent.
     *
     * @param string $message
     * @param array $overrideFilters
     * @return array
     */
    public function parse(string $message, array $overrideFilters = []): array
    {
        $text = mb_strtolower(trim($message));

        // 1. Gender Intent
        $gender = null;
        if (preg_match('/\b(girls?|girl|female|women|ladies|ladkiyo?|ladki|womens?)\b/iu', $text)) {
            $gender = 'girls';
        } elseif (preg_match('/\b(boys?|boy|male|men|ladka|ladko?|gents?|bachelor|mens?)\b/iu', $text)) {
            $gender = 'boys';
        } elseif (preg_match('/\b(coliving|co-living|coed|unisex|couple|couples|mixed|family)\b/iu', $text)) {
            $gender = 'co-ed';
        }

        // 2. City & Area Intent
        $city = null;
        $area = null;

        // Check configured aliases
        foreach ($this->cityAliases as $cityName => $aliases) {
            foreach ($aliases as $alias) {
                if (str_contains($text, mb_strtolower($alias))) {
                    $city = $cityName;
                    if ($alias !== mb_strtolower($cityName)) {
                        $area = ucwords($alias);
                    }
                    break 2;
                }
            }
        }

        // Specific area extraction patterns (e.g., "sector 62", "sec 18", "hitec city", "koramangala")
        if (preg_match('/\b(sec(?:tor)?\s*\d+[a-z]?|knowledge park(?:\s*[1-3]| i+)?|indiranagar|koramangala|hsr(?: layout)?|whitefield|saket|laxmi nagar|hinjewadi|viman nagar|madhapur|gachibowli|andheri|cyber city|kakadeo|sadar bazar)\b/iu', $text, $areaMatch)) {
            $area = ucwords($areaMatch[1]);
        }

        // Dynamic Location extraction for unlisted cities/towns/places (e.g. "Paris pg", "Shimla me", "in London")
        if (!$city && !$area) {
            if (preg_match('/\b(?:in|at|near|around)\s+([a-z]{3,20})\b/iu', $text, $locMatch)) {
                $cleaned = trim($locMatch[1]);
                if (!in_array($cleaned, ['boys', 'girls', 'coed', 'room', 'hostel', 'coliving', 'sharing', 'food', 'good', 'best'])) {
                    $city = ucwords($cleaned);
                }
            } elseif (preg_match('/\b([a-z]{3,20})\s+(?:me|ke paas|mein|city)\b/iu', $text, $locMatch2)) {
                $cleaned2 = trim($locMatch2[1]);
                if (!in_array($cleaned2, ['ladko', 'ladkiyo', 'budget', 'room', 'hostel', 'pg', 'khana'])) {
                    $city = ucwords($cleaned2);
                }
            } elseif (preg_match('/^([a-z]{3,20})\s+(?:pg|hostel|room|stays?)\b/iu', $text, $locMatch3)) {
                $cleaned3 = trim($locMatch3[1]);
                if (!in_array($cleaned3, ['boys', 'girls', 'coed', 'luxury', 'cheap', 'budget', 'best', 'find', 'show'])) {
                    $city = ucwords($cleaned3);
                }
            }
        }

        // 3. Budget Intent (e.g., "8k", "8000", "8 hazar", "under 8k", "8k ke andar", "5k se kam", "under 500 rs")
        $maxBudget = null;
        $minBudget = null;

        if (preg_match('/(\d+(?:\.\d+)?)\s*k\b/i', $text, $kMatch)) {
            $maxBudget = (int) round(((float) $kMatch[1]) * 1000);
        } elseif (preg_match('/(\d+)\s*(?:hazar|hazaar|thousand)\b/iu', $text, $hazarMatch)) {
            $maxBudget = (int) $hazarMatch[1] * 1000;
        } elseif (preg_match('/(?:under|below|max|upto|approx|se kam|k andar|ke andar|tak|<|<=|₹|rs\.?|inr)\s*(\d{2,6})\b/iu', $text, $numMatch)) {
            $maxBudget = (int) $numMatch[1];
        } elseif (preg_match('/\b(\d{2,6})\s*(?:rs|rupees|inr|tak|k andar|ke andar|se kam)\b/iu', $text, $numMatch2)) {
            $maxBudget = (int) $numMatch2[1];
        } elseif (preg_match('/\b(\d{4,5})\b/', $text, $standAloneNum)) {
            $maxBudget = (int) $standAloneNum[1];
        }

        // 4. Amenities Intent
        $amenities = [];
        if (preg_match('/\b(ac|air conditioner|cooling|ac wala|ac room)\b/iu', $text)) {
            $amenities[] = 'ac';
        }
        if (preg_match('/\b(food|meal|meals|khana|mess|breakfast|dinner|lunch|khana pina)\b/iu', $text)) {
            $amenities[] = 'food';
        }
        if (preg_match('/\b(wifi|wi-fi|internet|broadband)\b/iu', $text)) {
            $amenities[] = 'wifi';
        }
        if (preg_match('/\b(gym|fitness|workout)\b/iu', $text)) {
            $amenities[] = 'gym';
        }
        if (preg_match('/\b(attached|attached washroom|attached bathroom|private bath)\b/iu', $text)) {
            $amenities[] = 'attached_bath';
        }
        if (preg_match('/\b(metro|metro station|near metro|metro ke paas)\b/iu', $text)) {
            $amenities[] = 'near_metro';
        }
        if (preg_match('/\b(zero deposit|no deposit|bina deposit)\b/iu', $text)) {
            $amenities[] = 'zero_deposit';
        }
        if (preg_match('/\b(power backup|generator|inverter)\b/iu', $text)) {
            $amenities[] = 'power_backup';
        }

        // 5. Room Type Intent
        $roomType = null;
        if (preg_match('/\b(single|private room|single room|1 sharing|single sharing)\b/iu', $text)) {
            $roomType = 'single';
        } elseif (preg_match('/\b(double|2 sharing|double sharing|twin)\b/iu', $text)) {
            $roomType = 'double';
        } elseif (preg_match('/\b(triple|3 sharing|triple sharing|3 bed)\b/iu', $text)) {
            $roomType = 'triple';
        }

        // 6. Apply any manual filter overrides from UI chips
        if (isset($overrideFilters['gender'])) $gender = $overrideFilters['gender'];
        if (isset($overrideFilters['city'])) $city = $overrideFilters['city'];
        if (isset($overrideFilters['area'])) $area = $overrideFilters['area'];
        if (isset($overrideFilters['max_budget'])) $maxBudget = (int) $overrideFilters['max_budget'];
        if (isset($overrideFilters['amenities']) && is_array($overrideFilters['amenities'])) {
            $amenities = $overrideFilters['amenities'];
        }

        return [
            'raw_query' => $message,
            'city' => $city,
            'area' => $area,
            'gender' => $gender,
            'max_budget' => $maxBudget,
            'min_budget' => $minBudget,
            'amenities' => array_values(array_unique($amenities)),
            'room_type' => $roomType,
            'has_stay_intent' => !empty($gender) || !empty($city) || !empty($area) || !empty($maxBudget) || !empty($amenities) || preg_match('/\b(pg|hostel|stay|stays|room|rooms|rent|coliving|flat|residency|accommodation|dorm|sharing|find|show|search|chahiye|dekho|list)\b/iu', $text)
        ];
    }
}
