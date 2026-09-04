<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageProcessingService
{
    /**
     * Default options for property images
     */
    protected array $defaultOptions = [
        'max_width' => 1600,
        'max_height' => 1200,
        'quality' => 82,
        'thumb_width' => 600,
        'thumb_height' => 450,
        'thumb_quality' => 80,
    ];

    /**
     * Process an uploaded file (convert to WebP, resize, generate thumbnail, optimize).
     *
     * @param UploadedFile|string $file
     * @param string $subDirectory
     * @param string $prefix
     * @param array $options
     * @return array|null
     */
    public function processUpload($file, string $subDirectory = 'properties', string $prefix = 'prop_', array $options = []): ?array
    {
        $opts = array_merge($this->defaultOptions, $options);
        $targetDir = public_path('uploads/' . trim($subDirectory, '/'));

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $sourcePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        if (!file_exists($sourcePath)) {
            return null;
        }

        $baseName = $prefix . time() . '_' . Str::random(8);
        return $this->createWebpAndThumbnail($sourcePath, $targetDir, $baseName, $subDirectory, $opts);
    }

    /**
     * Process a Base64 data string (convert to WebP, resize, generate thumbnail, optimize).
     *
     * @param string $base64String
     * @param string $subDirectory
     * @param string $prefix
     * @param array $options
     * @return array|null
     */
    public function processBase64(string $base64String, string $subDirectory = 'properties', string $prefix = 'prop_', array $options = []): ?array
    {
        $opts = array_merge($this->defaultOptions, $options);
        $targetDir = public_path('uploads/' . trim($subDirectory, '/'));

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Clean Base64 header if present
        $cleanBase64 = $base64String;
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String)) {
            $cleanBase64 = substr($base64String, strpos($base64String, ',') + 1);
        }

        $decodedData = base64_decode($cleanBase64);
        if ($decodedData === false) {
            return null;
        }

        // Create temporary source image from binary data
        $tempSource = tempnam(sys_get_temp_dir(), 'staynest_img_');
        file_put_contents($tempSource, $decodedData);

        $baseName = $prefix . time() . '_' . Str::random(8);
        $result = $this->createWebpAndThumbnail($tempSource, $targetDir, $baseName, $subDirectory, $opts);

        if (file_exists($tempSource)) {
            @unlink($tempSource);
        }

        return $result;
    }

    /**
     * Process an existing file on disk and convert to WebP + Thumbnail.
     *
     * @param string $filePath Absolute path to existing image
     * @param string $subDirectory
     * @param bool $deleteOriginal
     * @param array $options
     * @return array|null
     */
    public function processExistingFile(string $filePath, string $subDirectory = 'properties', bool $deleteOriginal = false, array $options = []): ?array
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $opts = array_merge($this->defaultOptions, $options);
        $targetDir = public_path('uploads/' . trim($subDirectory, '/'));
        $fileInfo = pathinfo($filePath);
        $baseName = $fileInfo['filename'];

        $result = $this->createWebpAndThumbnail($filePath, $targetDir, $baseName, $subDirectory, $opts);

        if ($result && $deleteOriginal && strtolower($fileInfo['extension']) !== 'webp') {
            @unlink($filePath);
        }

        return $result;
    }

    /**
     * Core conversion engine: Uses GD to read any format, auto-orient, resize, create WebP & Thumbnail, then optimize.
     */
    protected function createWebpAndThumbnail(string $sourcePath, string $targetDir, string $baseName, string $subDirectory, array $opts): ?array
    {
        $imageResource = $this->createImageResource($sourcePath);
        if (!$imageResource) {
            return null;
        }

        // 1. Auto-rotate according to EXIF if applicable
        $imageResource = $this->autoRotateImage($sourcePath, $imageResource);

        $origWidth = imagesx($imageResource);
        $origHeight = imagesy($imageResource);

        // 2. Build High-Res Main WebP Image
        $mainFileName = $baseName . '.webp';
        $mainFilePath = $targetDir . DIRECTORY_SEPARATOR . $mainFileName;

        $mainDimensions = $this->calculateDimensions($origWidth, $origHeight, $opts['max_width'], $opts['max_height']);
        $mainImage = $this->resizeImage($imageResource, $origWidth, $origHeight, $mainDimensions['width'], $mainDimensions['height']);

        // Save Main WebP
        imagewebp($mainImage, $mainFilePath, $opts['quality']);
        imagedestroy($mainImage);

        // 3. Build Responsive Thumbnail WebP Image
        $thumbFileName = 'thumb_' . $baseName . '.webp';
        $thumbFilePath = $targetDir . DIRECTORY_SEPARATOR . $thumbFileName;

        $thumbDimensions = $this->calculateDimensions($origWidth, $origHeight, $opts['thumb_width'], $opts['thumb_height']);
        $thumbImage = $this->resizeImage($imageResource, $origWidth, $origHeight, $thumbDimensions['width'], $thumbDimensions['height']);

        // Save Thumbnail WebP
        imagewebp($thumbImage, $thumbFilePath, $opts['thumb_quality']);
        imagedestroy($thumbImage);

        imagedestroy($imageResource);

        // 4. Run Spatie ImageOptimizer on both generated WebP files
        $this->optimizeFile($mainFilePath);
        $this->optimizeFile($thumbFilePath);

        $cleanSubDir = trim($subDirectory, '/');
        $mainRelativeUrl = '/uploads/' . $cleanSubDir . '/' . $mainFileName;
        $thumbRelativeUrl = '/uploads/' . $cleanSubDir . '/' . $thumbFileName;

        return [
            'file_name' => $mainFileName,
            'file_path' => $mainFilePath,
            'url' => asset('uploads/' . $cleanSubDir . '/' . $mainFileName),
            'relative_url' => $mainRelativeUrl,
            'thumbnail_file_name' => $thumbFileName,
            'thumbnail_file_path' => $thumbFilePath,
            'thumbnail_url' => asset('uploads/' . $cleanSubDir . '/' . $thumbFileName),
            'thumbnail_relative_url' => $thumbRelativeUrl,
            'width' => $mainDimensions['width'],
            'height' => $mainDimensions['height'],
            'size_bytes' => file_exists($mainFilePath) ? filesize($mainFilePath) : 0,
            'thumb_size_bytes' => file_exists($thumbFilePath) ? filesize($thumbFilePath) : 0,
            'format' => 'webp',
        ];
    }

    /**
     * Create GD image resource from file path safely
     */
    protected function createImageResource(string $path)
    {
        if (!file_exists($path) || filesize($path) === 0) {
            return null;
        }

        $imageInfo = @getimagesize($path);
        if (!$imageInfo) {
            // Attempt to load from binary string if getimagesize fails
            $data = @file_get_contents($path);
            return $data ? @imagecreatefromstring($data) : null;
        }

        $mime = $imageInfo['mime'] ?? '';

        return match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            'image/gif' => @imagecreatefromgif($path),
            'image/bmp', 'image/x-ms-bmp' => function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($path) : null,
            default => @imagecreatefromstring(file_get_contents($path)),
        };
    }

    /**
     * Auto-rotate image according to EXIF Orientation tag
     */
    protected function autoRotateImage(string $path, $resource)
    {
        if (!function_exists('exif_read_data')) {
            return $resource;
        }

        try {
            $exif = @exif_read_data($path);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $rotated = imagerotate($resource, 180, 0);
                        imagedestroy($resource);
                        return $rotated;
                    case 6:
                        $rotated = imagerotate($resource, -90, 0);
                        imagedestroy($resource);
                        return $rotated;
                    case 8:
                        $rotated = imagerotate($resource, 90, 0);
                        imagedestroy($resource);
                        return $rotated;
                }
            }
        } catch (\Throwable $e) {
            // Ignore EXIF parsing errors
        }

        return $resource;
    }

    /**
     * Calculate proportional dimensions keeping aspect ratio
     */
    protected function calculateDimensions(int $origWidth, int $origHeight, int $maxWidth, int $maxHeight): array
    {
        if ($origWidth <= $maxWidth && $origHeight <= $maxHeight) {
            return ['width' => $origWidth, 'height' => $origHeight];
        }

        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        return [
            'width' => max(1, (int) round($origWidth * $ratio)),
            'height' => max(1, (int) round($origHeight * $ratio)),
        ];
    }

    /**
     * Resize image with truecolor and alpha transparency preserved
     */
    protected function resizeImage($sourceResource, int $origWidth, int $origHeight, int $newWidth, int $newHeight)
    {
        $targetResource = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve alpha transparency for PNG / WebP transparency
        imagealphablending($targetResource, false);
        imagesavealpha($targetResource, true);
        $transparent = imagecolorallocatealpha($targetResource, 255, 255, 255, 127);
        imagefilledrectangle($targetResource, 0, 0, $newWidth, $newHeight, $transparent);

        imagecopyresampled(
            $targetResource,
            $sourceResource,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $origWidth, $origHeight
        );

        return $targetResource;
    }

    /**
     * Run Spatie ImageOptimizer safely without crashing if CLI binaries are absent
     */
    public function optimizeFile(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        try {
            if (class_exists(ImageOptimizer::class)) {
                ImageOptimizer::optimize($filePath);
                return true;
            }
        } catch (\Throwable $e) {
            Log::debug("ImageOptimizer notice: " . $e->getMessage());
        }

        return false;
    }
}
