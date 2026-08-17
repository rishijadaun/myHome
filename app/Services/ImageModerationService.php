<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageModerationService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $endpoint;
    protected bool $failOpen;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? env('OPENAI_API_KEY');
        $this->model = config('services.openai.moderation_model') ?? env('OPENAI_MODERATION_MODEL', 'omni-moderation-latest');
        $this->endpoint = 'https://api.openai.com/v1/moderations';
        $this->failOpen = (bool)(config('services.openai.fail_open') ?? env('OPENAI_MODERATION_FAIL_OPEN', false));
    }

    /**
     * Check if an image is safe according to OpenAI Omnimodels Moderation (/v1/moderations).
     * Supports:
     * - Base64 data URL string (e.g. data:image/jpeg;base64,...)
     * - Absolute local file path (e.g. /uploads/properties/photo.jpg)
     * - Public / storage path (e.g. uploads/properties/...)
     * - Remote URL (http://... or https://...)
     *
     * @param string $imageSource
     * @return array ['is_safe' => bool, 'reason' => string|null, 'flagged_categories' => array, 'scores' => array]
     */
    public function scanImage(string $imageSource): array
    {
        try {
            $formattedUrl = $this->formatImageUrl($imageSource);

            if (!$formattedUrl) {
                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            if (empty($this->apiKey)) {
                Log::warning('OpenAI Moderation: API key is not configured.');
                if (!$this->failOpen) {
                    return [
                        'is_safe' => false,
                        'reason' => 'Image moderation failed: OpenAI API key is not configured in .env file.',
                        'flagged_categories' => ['Configuration Error'],
                        'scores' => []
                    ];
                }
                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            $response = Http::withToken($this->apiKey)
                ->timeout(15)
                ->post($this->endpoint, [
                    'model' => $this->model,
                    'input' => [
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $formattedUrl
                            ]
                        ]
                    ]
                ]);

            if (!$response->successful()) {
                $status = $response->status();
                $errBody = $response->json('error.message') ?? $response->body();
                Log::error("OpenAI Moderation API Error ({$status}): {$errBody}");

                if (!$this->failOpen) {
                    $hint = $status === 429 ? ' (OpenAI returned 429 Too Many Requests - please check your OpenAI account billing balance or project quota)' : '';
                    return [
                        'is_safe' => false,
                        'reason' => "AI Image Moderation Error (HTTP {$status}): {$errBody}{$hint}",
                        'flagged_categories' => ['API Error'],
                        'scores' => []
                    ];
                }

                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            $result = $response->json('results.0');

            if (!$result) {
                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            $isFlagged = $result['flagged'] ?? false;
            $categories = $result['categories'] ?? [];
            $scores = $result['category_scores'] ?? [];

            if ($isFlagged) {
                $flaggedCategories = [];
                foreach ($categories as $category => $flagged) {
                    if ($flagged) {
                        $flaggedCategories[] = $this->formatCategoryName((string)$category);
                    }
                }

                $categoryListStr = !empty($flaggedCategories) ? implode(', ', $flaggedCategories) : 'Safety Policy Violation';

                return [
                    'is_safe' => false,
                    'reason' => "Image rejected: OpenAI Moderation detected prohibited content [{$categoryListStr}].",
                    'flagged_categories' => $flaggedCategories,
                    'categories' => $categories,
                    'scores' => $scores
                ];
            }

            return [
                'is_safe' => true,
                'reason' => null,
                'flagged_categories' => [],
                'categories' => $categories,
                'scores' => $scores
            ];

        } catch (\Exception $e) {
            Log::error('ImageModerationService Exception: ' . $e->getMessage());
            // Fail open gracefully so standard uploads aren't interrupted if network drops
            return [
                'is_safe' => true,
                'reason' => null,
                'flagged_categories' => [],
                'scores' => []
            ];
        }
    }

    /**
     * Check if a list of images are all safe.
     *
     * @param array $images
     * @return array ['is_safe' => bool, 'reason' => string|null, 'flagged_index' => int|null]
     */
    public function scanImages(array $images): array
    {
        foreach ($images as $index => $img) {
            if (empty($img)) continue;
            $result = $this->scanImage($img);
            if (!$result['is_safe']) {
                return [
                    'is_safe' => false,
                    'reason' => 'Photo #' . ($index + 1) . ' violates safety policies: ' . $result['reason'],
                    'flagged_index' => $index
                ];
            }
        }

        return [
            'is_safe' => true,
            'reason' => null,
            'flagged_index' => null
        ];
    }

    /**
     * Check text safety using OpenAI Omnimodels Moderation Endpoint (/v1/moderations).
     *
     * @param string $text
     * @return array ['is_safe' => bool, 'reason' => string|null, 'flagged_categories' => array, 'scores' => array]
     */
    public function scanText(string $text): array
    {
        try {
            if (empty(trim($text))) {
                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            if (empty($this->apiKey)) {
                Log::warning('OpenAI Moderation: API key is not configured.');
                if (!$this->failOpen) {
                    return [
                        'is_safe' => false,
                        'reason' => 'Text moderation failed: OpenAI API key is not configured in .env file.',
                        'flagged_categories' => ['Configuration Error'],
                        'scores' => []
                    ];
                }
                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            $response = Http::withToken($this->apiKey)
                ->timeout(10)
                ->post($this->endpoint, [
                    'model' => $this->model,
                    'input' => $text
                ]);

            if (!$response->successful()) {
                $status = $response->status();
                $errBody = $response->json('error.message') ?? $response->body();
                Log::error("OpenAI Text Moderation API Error ({$status}): {$errBody}");

                if (!$this->failOpen) {
                    $hint = $status === 429 ? ' (OpenAI returned 429 Too Many Requests - please check your OpenAI account billing balance or project quota)' : '';
                    return [
                        'is_safe' => false,
                        'reason' => "AI Text Moderation Error (HTTP {$status}): {$errBody}{$hint}",
                        'flagged_categories' => ['API Error'],
                        'scores' => []
                    ];
                }

                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            $result = $response->json('results.0');
            if (!$result) {
                return [
                    'is_safe' => true,
                    'reason' => null,
                    'flagged_categories' => [],
                    'scores' => []
                ];
            }

            $isFlagged = $result['flagged'] ?? false;
            $categories = $result['categories'] ?? [];
            $scores = $result['category_scores'] ?? [];

            if ($isFlagged) {
                $flaggedCategories = [];
                foreach ($categories as $category => $flagged) {
                    if ($flagged) {
                        $flaggedCategories[] = $this->formatCategoryName((string)$category);
                    }
                }

                $categoryListStr = !empty($flaggedCategories) ? implode(', ', $flaggedCategories) : 'Safety Policy Violation';

                return [
                    'is_safe' => false,
                    'reason' => "Text content rejected: OpenAI Moderation detected prohibited content [{$categoryListStr}].",
                    'flagged_categories' => $flaggedCategories,
                    'categories' => $categories,
                    'scores' => $scores
                ];
            }

            return [
                'is_safe' => true,
                'reason' => null,
                'flagged_categories' => [],
                'categories' => $categories,
                'scores' => $scores
            ];

        } catch (\Exception $e) {
            Log::error('ImageModerationService scanText Exception: ' . $e->getMessage());
            return [
                'is_safe' => true,
                'reason' => null,
                'flagged_categories' => [],
                'scores' => []
            ];
        }
    }

    /**
     * Multi-modal moderation supporting mixed text and image items in a single request.
     *
     * @param array $inputs Array of items, e.g. [['type' => 'text', 'text' => '...'], ['type' => 'image_url', 'image_url' => ['url' => '...']]]
     * @return array
     */
    public function scanMultiModal(array $inputs): array
    {
        try {
            if (empty($inputs)) {
                return ['is_safe' => true, 'reason' => null, 'results' => []];
            }

            if (empty($this->apiKey)) {
                Log::warning('OpenAI Moderation: API key is not configured.');
                if (!$this->failOpen) {
                    return [
                        'is_safe' => false,
                        'reason' => 'Multi-modal moderation failed: OpenAI API key is not configured in .env file.',
                        'results' => []
                    ];
                }
                return ['is_safe' => true, 'reason' => null, 'results' => []];
            }

            $response = Http::withToken($this->apiKey)
                ->timeout(20)
                ->post($this->endpoint, [
                    'model' => $this->model,
                    'input' => $inputs
                ]);

            if (!$response->successful()) {
                $status = $response->status();
                $errBody = $response->json('error.message') ?? $response->body();
                Log::error("OpenAI MultiModal Moderation API Error ({$status}): {$errBody}");

                if (!$this->failOpen) {
                    $hint = $status === 429 ? ' (OpenAI returned 429 Too Many Requests - please check your OpenAI account billing balance or project quota)' : '';
                    return [
                        'is_safe' => false,
                        'reason' => "AI Multi-modal Moderation Error (HTTP {$status}): {$errBody}{$hint}",
                        'results' => []
                    ];
                }

                return ['is_safe' => true, 'reason' => null, 'results' => []];
            }

            $results = $response->json('results') ?? [];
            foreach ($results as $index => $res) {
                if ($res['flagged'] ?? false) {
                    $flaggedCats = [];
                    foreach (($res['categories'] ?? []) as $cat => $val) {
                        if ($val) $flaggedCats[] = $this->formatCategoryName((string)$cat);
                    }
                    $catStr = !empty($flaggedCats) ? implode(', ', $flaggedCats) : 'Policy Violation';
                    return [
                        'is_safe' => false,
                        'reason' => "Multi-modal input item #{$index} rejected: [{$catStr}].",
                        'flagged_index' => $index,
                        'results' => $results
                    ];
                }
            }

            return ['is_safe' => true, 'reason' => null, 'results' => $results];

        } catch (\Exception $e) {
            Log::error('ImageModerationService scanMultiModal Exception: ' . $e->getMessage());
            return ['is_safe' => true, 'reason' => null, 'results' => []];
        }
    }

    /**
     * Convert various image sources (file path, raw base64, URL) into an OpenAI-compatible URL or Data URL.
     */
    protected function formatImageUrl(string $source): ?string
    {
        $source = trim($source);

        // 1. If it's already a base64 data URL
        if (preg_match('/^data:image\/[a-zA-Z0-9\+\-\.]+;base64,/i', $source)) {
            return $source;
        }

        // 2. If it's a raw base64 string without data prefix
        if (preg_match('/^[a-zA-Z0-9\/\r\n+]+={0,2}$/', $source) && strlen($source) > 100) {
            return 'data:image/jpeg;base64,' . str_replace(["\r", "\n"], '', $source);
        }

        // 3. If it's an absolute local file path
        if (file_exists($source) && is_file($source)) {
            $data = file_get_contents($source);
            if ($data !== false) {
                $mime = $this->detectMimeType($source, $data);
                return "data:{$mime};base64," . base64_encode($data);
            }
        }

        // 4. If it's a relative path in public directory (e.g. /uploads/properties/...)
        $publicPath = public_path(ltrim($source, '/\\'));
        if (file_exists($publicPath) && is_file($publicPath)) {
            $data = file_get_contents($publicPath);
            if ($data !== false) {
                $mime = $this->detectMimeType($publicPath, $data);
                return "data:{$mime};base64," . base64_encode($data);
            }
        }

        // 5. If it's a relative path in storage/app directory
        $storagePath = storage_path('app/' . ltrim($source, '/\\'));
        if (file_exists($storagePath) && is_file($storagePath)) {
            $data = file_get_contents($storagePath);
            if ($data !== false) {
                $mime = $this->detectMimeType($storagePath, $data);
                return "data:{$mime};base64," . base64_encode($data);
            }
        }

        // 6. If it's a remote URL
        if (filter_var($source, FILTER_VALIDATE_URL)) {
            // For remote URLs, we attempt to download and encode as base64 so local dev / internal URLs work seamlessly
            try {
                $response = Http::timeout(8)->get($source);
                if ($response->successful()) {
                    $body = $response->body();
                    $mime = $response->header('Content-Type') ?: 'image/jpeg';
                    if (str_contains($mime, ';')) {
                        $mime = explode(';', $mime)[0];
                    }
                    return "data:{$mime};base64," . base64_encode($body);
                }
            } catch (\Exception $e) {
                // If downloading fails, fallback to passing the raw URL directly to OpenAI
                return $source;
            }

            return $source;
        }

        return null;
    }

    /**
     * Detect MIME type for an image file.
     */
    protected function detectMimeType(string $filePath, string $content): string
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_buffer($finfo, $content);
            finfo_close($finfo);
            if ($mime && str_starts_with($mime, 'image/')) {
                return $mime;
            }
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return match ($extension) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'image/jpeg',
        };
    }

    /**
     * Format OpenAI category slug to human-readable label.
     */
    protected function formatCategoryName(string $category): string
    {
        return match ($category) {
            'sexual' => 'Sexual Content',
            'sexual/minors' => 'Sexual Content Involving Minors',
            'hate' => 'Hate Speech',
            'hate/threatening' => 'Hate Speech & Threatening',
            'harassment' => 'Harassment',
            'harassment/threatening' => 'Harassment & Threatening',
            'self-harm', 'self-harm/intent', 'self-harm/instructions' => 'Self-Harm / Suicide',
            'violence' => 'Violence',
            'violence/graphic' => 'Graphic Violence',
            'illicit' => 'Illicit / Illegal Activity',
            'illicit/violent' => 'Illicit Violent Activity',
            default => ucwords(str_replace(['/', '_', '-'], ' ', $category)),
        };
    }
}
