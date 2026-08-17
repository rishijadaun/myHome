<?php

namespace App\Services;

class ContentModerationService
{
    /**
     * Whitelist of acceptable real estate / PG terms that should NOT trigger false positives.
     */
    protected static array $whitelist = [
        'master bedroom',
        'couple friendly',
        'bachelors allowed',
        'bachelor allowed',
        'family allowed',
        'girls only',
        'boys only',
        'smoking zone',
        'non-smoking',
        'no smoking',
        'non smoking',
        'attached washroom',
        'private bathroom',
        'co-living',
        'pet friendly',
        'security deposit',
        'wifi included',
        'power backup',
        'maid service',
        'sharing room',
        'single room',
        'double sharing',
        'triple sharing',
        'gate closing time',
        'visitor policy',
        'notice period'
    ];

    /**
     * Prohibited categories and patterns.
     */
    protected static array $prohibitedPatterns = [
        'Vulgarity / Profanity' => [
            '/\b(fuck|fucking|fucked|fucker|fck|f\*ck|f\s*u\s*c\s*k|motherfucker|mf)\b/i',
            '/\b(bitch|bitches|b\*tch|b\s*i\s*t\s*c\s*h|bastard|bastards|asshole|assholes|arsehole|dickhead)\b/i',
            '/\b(chutiya|chootiya|chutya|choot|bhenchod|behenchod|bc|madarchod|mc|gaand|gandu|harami|harampaye|lavde|lauda|loda|lodu|bhosadi|bhosdike|randi|kutiya|kamina|kameena)\b/i',
            '/\b(shit|bullshit|cunt|cunts|pussy|dick|penis|vagina|boobs|tits)\b/i',
        ],
        'Sexual Content & Escorts' => [
            '/\b(sex|sexy|sexual|intercourse|sax\s*sux|sax|sux)\b/i',
            '/\b(call\s*girl|call\s*girls|call\s*boy|call\s*boys|escort\s*service|escort|escorts|russian\s*girl|paid\s*service|paid\s*sex)\b/i',
            '/\b(adult\s*service|adult|adults|sex\s*service|nude|nudes|nudity|naked|porn|xxx|erotic|sensual\s*massage|massage\s*with\s*extra|happy\s*ending)\b/i',
            '/\b(onlyfans|night\s*service|female\s*companion|gigolo)\b/i',
            '/\b(sugar\s*daddy|sugar\s*baby|hookup|fwa|casual\s*sex)\b/i',
        ],
        'Substances & Illicit Drugs' => [
            '/\b(cocaine|heroin|charas|ganja|weed|weed\s*available|weed\s*seller|buy\s*weed|cannabis|cannabis\s*sale)\b/i',
            '/\b(meth|crystal\s*meth|mdma|ecstasy|lsd|smack|brown\s*sugar|narcotics|drug\s*party)\b/i',
            '/\b(opium|afim|afeem|bhang\s*available|dealer\s*contact)\b/i',
        ],
        'Abuse, Harassment & Hate Speech' => [
            '/\b(kill\s*you|murder|rape|threat|assault|beat\s*up|violence|die\s*bitch)\b/i',
            '/\b(terrorist|jihad|hate\s*all|nazi|hitler)\b/i',
            '/\b(chamar|bhangi|katue|mulle|anti-national)\b/i',
        ],
        'Scams, Phishing & Fraud' => [
            '/\b(send\s*otp|share\s*otp|lottery\s*winner|crypto\s*investment|double\s*money|instant\s*loan\s*scam)\b/i',
            '/\b(click\s*here\s*http|free\s*recharge|hack\s*account)\b/i',
            '/https?:\/\/(?:bit\.ly|tinyurl\.com|t\.co|goo\.gl|rb\.gy|is\.gd)\/\w+/i',
        ]
    ];

    /**
     * Scan text content for violations across Title, Description, House Rules, Landmark, Address, Area.
     *
     * @param array|string $input
     * @param string|null $description
     * @param string|null $houseRules
     * @param string|null $landmark
     * @param string|null $address
     * @param string|null $area
     * @return array ['passed' => bool, 'category' => string|null, 'reason' => string|null, 'flagged_field' => string|null, 'flagged_term' => string|null]
     */
    public static function validateContent(
        array|string $input,
        ?string $description = '',
        ?string $houseRules = '',
        ?string $landmark = '',
        ?string $address = '',
        ?string $area = ''
    ): array {
        if (is_array($input)) {
            $fields = [
                'Title' => $input['name'] ?? '',
                'Description' => $input['description'] ?? '',
                'House Rules' => $input['house_rules'] ?? '',
                'Landmark' => $input['landmark'] ?? '',
                'Address' => $input['address'] ?? '',
                'Area' => $input['area'] ?? '',
            ];
        } else {
            $fields = [
                'Title' => $input ?? '',
                'Description' => $description ?? '',
                'House Rules' => $houseRules ?? '',
                'Landmark' => $landmark ?? '',
                'Address' => $address ?? '',
                'Area' => $area ?? '',
            ];
        }

        foreach ($fields as $fieldName => $text) {
            if (empty(trim((string)$text))) continue;

            $normalized = self::normalizeText((string)$text);

            foreach (self::$prohibitedPatterns as $category => $patterns) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $normalized, $matches) || preg_match($pattern, (string)$text, $matches)) {
                        $matchedTerm = $matches[0] ?? 'prohibited word';
                        
                        // Check if matched term is part of a whitelisted phrase
                        if (self::isWhitelisted((string)$text, $matchedTerm)) {
                            continue;
                        }

                        return [
                            'passed' => false,
                            'category' => $category,
                            'flagged_field' => $fieldName,
                            'reason' => "The {$fieldName} contains prohibited content in category: [{$category}]. Please remove inappropriate term ('{$matchedTerm}') to proceed.",
                            'flagged_term' => $matchedTerm
                        ];
                    }
                }
            }
        }

        return [
            'passed' => true,
            'category' => null,
            'reason' => null,
            'flagged_field' => null,
            'flagged_term' => null
        ];
    }

    /**
     * Check if a term occurs inside an allowed whitelisted phrase.
     */
    protected static function isWhitelisted(string $fullText, string $term): bool
    {
        $fullTextLower = strtolower($fullText);
        $termLower = strtolower($term);

        foreach (self::$whitelist as $allowed) {
            if (str_contains($fullTextLower, $allowed) && str_contains($allowed, $termLower)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Normalize text for anti-evasion obfuscation (leetspeak, spaced characters).
     */
    protected static function normalizeText(string $text): string
    {
        $text = strtolower($text);
        
        // Replace common leetspeak substitutions
        $replacements = [
            '@' => 'a',
            '$' => 's',
            '0' => 'o',
            '1' => 'i',
            '3' => 'e',
            '5' => 's',
            '7' => 't',
            '!' => 'i',
            '*' => '',
            '_' => '',
            '-' => '',
        ];
        $text = strtr($text, $replacements);

        // Collapse multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return $text;
    }
}
