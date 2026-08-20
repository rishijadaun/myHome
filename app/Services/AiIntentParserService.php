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

        // Specific area extraction patterns (e.g., "sector 62", "sec 62", "sec-62", "hitec city", "koramangala")
        if (preg_match('/\b(sec(?:tor)?[\s\-_]*\d+[a-z]?|knowledge park(?:\s*[1-3]| i+)?|indiranagar|koramangala|hsr(?: layout)?|whitefield|saket|laxmi nagar|hinjewadi|viman nagar|madhapur|gachibowli|andheri|cyber city|kakadeo|sadar bazar|electronic city)\b/iu', $text, $areaMatch)) {
            $rawArea = $areaMatch[1];
            // Normalize "sec 62" / "sec-62" to "Sector 62"
            $normalizedArea = preg_replace('/^sec(?!tor)[\s\-_]*/i', 'Sector ', $rawArea);
            $normalizedArea = preg_replace('/^sector[\s\-_]*/i', 'Sector ', $normalizedArea);
            $area = ucwords($normalizedArea);
        }

        // Dynamic Location extraction for unlisted cities/towns/places (e.g. "Paris pg", "Shimla me", "in London")
        if (!$city && !$area) {
            if (preg_match('/\b(?:in|at|near|around)\s+([a-z0-9\s]{3,25})\b/iu', $text, $locMatch)) {
                $cleaned = trim($locMatch[1]);
                $stopWords = ['boys', 'girls', 'coed', 'room', 'hostel', 'coliving', 'sharing', 'food', 'good', 'best', 'ac', 'wifi', 'pg', 'stays', 'stay', 'luxury', 'budget', 'cheap'];
                if (!in_array($cleaned, $stopWords)) {
                    $city = ucwords($cleaned);
                }
            } elseif (preg_match('/\b([a-z0-9]{3,20})\s+(?:me|mein|ke paas|city)\b/iu', $text, $locMatch2)) {
                $cleaned2 = trim($locMatch2[1]);
                $stopWords2 = ['ladko', 'ladkiyo', 'budget', 'room', 'hostel', 'pg', 'khana', 'andar', 'saath', 'paas'];
                if (!in_array($cleaned2, $stopWords2)) {
                    $city = ucwords($cleaned2);
                }
            } elseif (preg_match('/^([a-z0-9]{3,20})\s+(?:pg|hostel|room|stays?)\b/iu', $text, $locMatch3)) {
                $cleaned3 = trim($locMatch3[1]);
                $stopWords3 = ['boys', 'girls', 'coed', 'luxury', 'cheap', 'budget', 'best', 'find', 'show', 'top'];
                if (!in_array($cleaned3, $stopWords3)) {
                    $city = ucwords($cleaned3);
                }
            }
        }

        // 3. Budget Intent (e.g. "less then 5k", "less than 5000", "more then 5k", "above 5000", "5k to 8k", "between 5k and 8k")
        $maxBudget = null;
        $minBudget = null;

        $parseAmount = function($num, $unit = '') {
            $val = (float) $num;
            $u = strtolower($unit ?? '');
            if (($u === 'k' || $u === 'thousand' || $u === 'hazar' || $u === 'hazaar') && $val < 1000) {
                return (int) round($val * 1000);
            }
            if ($val > 0 && $val <= 50) {
                return (int) round($val * 1000);
            }
            return (int) round($val);
        };

        // 3A. Range Detection: "between 5k and 8k", "5k to 8k", "5000 - 8000", "5000 se 8000"
        if (preg_match('/(?:between|range|from)\s+(?:(?:rs\.?|inr|₹)\s*)?(\d+(?:\.\d+)?)\s*(k|hazar|thousand)?\s*(?:and|to|-|se)\s*(?:(?:rs\.?|inr|₹)\s*)?(\d+(?:\.\d+)?)\s*(k|hazar|thousand)?\b/iu', $text, $rangeMatch1)) {
            $minBudget = $parseAmount($rangeMatch1[1], $rangeMatch1[2] ?? ($rangeMatch1[4] ?? ''));
            $maxBudget = $parseAmount($rangeMatch1[3], $rangeMatch1[4] ?? ($rangeMatch1[2] ?? ''));
        } elseif (preg_match('/(\d+(?:\.\d+)?)\s*(k|hazar|thousand)?\s*(?:to|-|se)\s*(\d+(?:\.\d+)?)\s*(k|hazar|thousand)?\b/iu', $text, $rangeMatch2)) {
            if (!str_contains($text, 'sector') && !str_contains($text, 'sec')) {
                $minBudget = $parseAmount($rangeMatch2[1], $rangeMatch2[2] ?? ($rangeMatch2[4] ?? ''));
                $maxBudget = $parseAmount($rangeMatch2[3], $rangeMatch2[4] ?? ($rangeMatch2[2] ?? ''));
            }
        }

        if (!$minBudget && !$maxBudget) {
            // 3B. "More than", "More then", "Greater than/then", "Above", "Higher than/then", "Starting from", "Minimum", "se jyada", ">"
            if (preg_match('/(?:more\s+(?:than|then)|greater\s+(?:than|then)|higher\s+(?:than|then)|above|over|exceeding|starting\s+from|starts?\s+from|from|minimum|min|at\s+least|se\s+(?:jyada|zyada|upar|bada|adhik)|>|>=)\s*(?:price|rent|budget|cost)?\s*(?:(?:rs\.?|inr|₹)\s*)?(\d+(?:\.\d+)?)\s*(k|hazar|thousand|rs|rupees|inr)?\b/iu', $text, $moreMatch)) {
                $minBudget = $parseAmount($moreMatch[1], $moreMatch[2] ?? '');
            } elseif (preg_match('/\b(\d+(?:\.\d+)?)\s*(k|hazar|thousand|rs|rupees|inr)?\s*(?:se\s+(?:jyada|zyada|upar|bada|adhik|start)|and\s+above|se\s+start|\+)\b/iu', $text, $moreMatch2)) {
                $minBudget = $parseAmount($moreMatch2[1], $moreMatch2[2] ?? '');
            }

            // 3C. "Less than", "Less then", "Under", "Below", "Upto", "Within", "Cheaper than/then", "Maximum", "se kam", "<"
            elseif (preg_match('/(?:less\s+(?:than|then)|cheaper\s+(?:than|then)|lower\s+(?:than|then)|under|below|upto|up\s+to|within|maximum|max|at\s+most|budget\s+under|budget\s+below|se\s+kam|ke\s+andar|<|<=)\s*(?:price|rent|budget|cost)?\s*(?:(?:rs\.?|inr|₹)\s*)?(\d+(?:\.\d+)?)\s*(k|hazar|thousand|rs|rupees|inr)?\b/iu', $text, $lessMatch)) {
                $maxBudget = $parseAmount($lessMatch[1], $lessMatch[2] ?? '');
            } elseif (preg_match('/\b(\d+(?:\.\d+)?)\s*(k|hazar|thousand|rs|rupees|inr)?\s*(?:se\s+kam|ke\s+andar|tak|k\s+andar)\b/iu', $text, $lessMatch2)) {
                $maxBudget = $parseAmount($lessMatch2[1], $lessMatch2[2] ?? '');
            }

            // 3D. Generic "pg price 5k", "budget 5k", "rent 5000", "5k pg", "under 500 rs"
            elseif (preg_match('/(?:price|rent|budget|cost)\s*(?:is|of|for|around|approx|me)?\s*(?:(?:rs\.?|inr|₹)\s*)?(\d+(?:\.\d+)?)\s*(k|hazar|thousand|rs|rupees|inr)?\b/iu', $text, $priceMatch)) {
                $maxBudget = $parseAmount($priceMatch[1], $priceMatch[2] ?? '');
            } elseif (preg_match('/(\d+(?:\.\d+)?)\s*k\b/iu', $text, $kOnlyMatch)) {
                $maxBudget = (int) round(((float) $kOnlyMatch[1]) * 1000);
            } elseif (preg_match('/(\d+)\s*(?:hazar|hazaar|thousand)\b/iu', $text, $hazarOnlyMatch)) {
                $maxBudget = (int) $hazarOnlyMatch[1] * 1000;
            } elseif (preg_match('/\b(\d{4,5})\b/', $text, $standAloneNum)) {
                $maxBudget = (int) $standAloneNum[1];
            }
        }

        // 4. Amenities Intent
        $amenities = [];
        if (preg_match('/\b(ac|air conditioner|airconditioner|cooling|ac wala|ac room|ac ke saath)\b/iu', $text)) {
            $amenities[] = 'ac';
        }
        if (preg_match('/\b(food|meal|meals|khana|mess|breakfast|dinner|lunch|khana pina|food ke saath|khana ke saath|3 time food)\b/iu', $text)) {
            $amenities[] = 'food';
        }
        if (preg_match('/\b(wifi|wi-fi|internet|broadband|high speed wifi)\b/iu', $text)) {
            $amenities[] = 'wifi';
        }
        if (preg_match('/\b(gym|fitness|workout)\b/iu', $text)) {
            $amenities[] = 'gym';
        }
        if (preg_match('/\b(attached|attached washroom|attached bathroom|private bath|personal washroom)\b/iu', $text)) {
            $amenities[] = 'attached_bath';
        }
        if (preg_match('/\b(metro|metro station|near metro|metro ke paas|walking distance from metro)\b/iu', $text)) {
            $amenities[] = 'near_metro';
        }
        if (preg_match('/\b(security|cctv|guard|24x7 security|safe)\b/iu', $text)) {
            $amenities[] = 'security';
        }
        if (preg_match('/\b(zero deposit|no deposit|bina deposit|0 deposit)\b/iu', $text)) {
            $amenities[] = 'zero_deposit';
        }
        if (preg_match('/\b(power backup|generator|inverter|24hr power)\b/iu', $text)) {
            $amenities[] = 'power_backup';
        }
        if (preg_match('/\b(parking|bike parking|car parking)\b/iu', $text)) {
            $amenities[] = 'parking';
        }

        // 5. Room Type Intent
        $roomType = null;
        if (preg_match('/\b(single|private room|single room|1 sharing|single sharing|1 bed)\b/iu', $text)) {
            $roomType = 'single';
        } elseif (preg_match('/\b(double|2 sharing|double sharing|twin|2 bed)\b/iu', $text)) {
            $roomType = 'double';
        } elseif (preg_match('/\b(triple|3 sharing|triple sharing|3 bed)\b/iu', $text)) {
            $roomType = 'triple';
        }

        // 6. Property Detail Inquiry Intent & Target Property Extraction
        $isDetailQuery = false;
        $targetPropertyName = null;

        // Matches queries like: "tell me more about Royal PG", "give full details of Royal PG", "show detail of Stanza", "details about X", "X details", "X ki detail", "X ke baare me"
        if (preg_match('/\b(?:tell\s+me\s+more\s+about|give\s+(?:full\s+)?details?\s+(?:of|about|for)|show\s+(?:more\s+)?details?\s+(?:of|about|for)|details?\s+(?:of|about|for)|more\s+(?:info|details?)\s+(?:on|about|of)|all\s+details?\s+of)\s+([a-z0-9\s\-_.,\']+)/iu', $message, $detailMatch)) {
            $isDetailQuery = true;
            $targetPropertyName = trim($detailMatch[1]);
        } elseif (preg_match('/\b([a-z0-9\s\-_.,\']+?)\s+(?:details?|full\s+detail|full\s+info|ki\s+details?|ke\s+baare\s+me|kaisa\s+hai|kaise\s+hai|ke\s+details)\b/iu', $message, $detailMatch2)) {
            $isDetailQuery = true;
            $targetPropertyName = trim($detailMatch2[1]);
        }

        if ($targetPropertyName) {
            $targetPropertyName = trim(preg_replace('/[?!.,;:]+$/', '', $targetPropertyName));
        }

        // 7. Extract Direct Property Name (e.g. "Royal PG", "Stanza Living Amsterdam", "search Sai Krupa", "Greenview Residency")
        $propertyName = null;
        if ($targetPropertyName) {
            $propertyName = $targetPropertyName;
        } else {
            if (preg_match('/\b(?:search|find|show|look\s+for)\s+(?:for\s+)?([a-z0-9\s\-_.,\']+?\s+(?:pg|hostel|stay|stays|residency|living|house|home|suites|villa))\b/iu', $message, $nameMatch1)) {
                $propertyName = trim($nameMatch1[1]);
            } elseif (preg_match('/\b([a-z0-9\s\-_.,\']+?\s+(?:pg|hostel|residency|living|suites|inn|bhawan|villa))\b/iu', $message, $nameMatch2)) {
                $candidate = trim($nameMatch2[1]);
                if (!preg_match('/^(?:boys?|girls?|coed|unisex|luxury|cheap|budget|best|top|near|in|at)\s+(?:pg|hostel|residency)$/i', $candidate)) {
                    $propertyName = $candidate;
                }
            }
        }

        // 8. Keywords / Residual Tokens
        // Remove common stop words and extracted tokens to find residual property names or specific traits
        $stopWordsRegex = '/\b(pg|hostel|stay|stays|room|rooms|rent|coliving|flat|residency|accommodation|dorm|sharing|find|show|search|chahiye|dekho|list|me|mein|ke|ki|ka|hai|ho|andar|paas|saath|bhi|wala|wali|with|and|or|in|at|near|the|for|under|below|rs|inr|rupees|hazar|thousand|approx|upto|boys|boy|male|men|ladka|ladko|girls|girl|female|women|ladies|ladkiyo|ladki|coed|unisex|coliving|ac|food|khana|meals|wifi|internet|gym|sec|sector|\d+k?)\b/iu';
        $cleanedKeywords = trim(preg_replace('/\s+/', ' ', preg_replace($stopWordsRegex, ' ', $text)));
        $keywords = !empty($cleanedKeywords) ? array_values(array_filter(explode(' ', $cleanedKeywords), fn($w) => mb_strlen($w) >= 3)) : [];

        // If propertyName was found, add its parts to keywords as well
        if ($propertyName) {
            $nameTokens = array_filter(explode(' ', strtolower($propertyName)), fn($w) => mb_strlen($w) >= 3 && !in_array($w, ['the', 'for', 'and', 'with', 'in', 'at']));
            $keywords = array_values(array_unique(array_merge($keywords, $nameTokens)));
        }

        // 9. Apply manual filter overrides from UI chips if provided
        if (isset($overrideFilters['gender'])) $gender = $overrideFilters['gender'];
        if (isset($overrideFilters['city'])) $city = $overrideFilters['city'];
        if (isset($overrideFilters['area'])) $area = $overrideFilters['area'];
        if (isset($overrideFilters['max_budget'])) $maxBudget = (int) $overrideFilters['max_budget'];
        if (isset($overrideFilters['min_budget'])) $minBudget = (int) $overrideFilters['min_budget'];
        if (isset($overrideFilters['amenities']) && is_array($overrideFilters['amenities'])) {
            $amenities = $overrideFilters['amenities'];
        }

        return [
            'raw_query' => $message,
            'is_detail_query' => $isDetailQuery,
            'target_property_name' => $targetPropertyName,
            'property_name' => $propertyName,
            'city' => $city,
            'area' => $area,
            'gender' => $gender,
            'max_budget' => $maxBudget,
            'min_budget' => $minBudget,
            'amenities' => array_values(array_unique($amenities)),
            'room_type' => $roomType,
            'keywords' => $keywords,
            'has_stay_intent' => $isDetailQuery || !empty($propertyName) || !empty($gender) || !empty($city) || !empty($area) || !empty($maxBudget) || !empty($amenities) || !empty($roomType) || preg_match('/\b(pg|hostel|stay|stays|room|rooms|rent|coliving|flat|residency|accommodation|dorm|sharing|find|show|search|chahiye|dekho|list)\b/iu', $text)
        ];
    }
}
