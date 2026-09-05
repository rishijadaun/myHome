<?php

namespace App\Console\Commands;

use App\Models\PropertyImage;
use App\Services\ImageProcessingService;
use Illuminate\Console\Command;

class OptimizePropertyImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:optimize-images {--dry-run : Only scan and display without modifying}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch converts existing property photos to .webp format, generates responsive thumbnails, and optimizes image files with Spatie ImageOptimizer';

    /**
     * Execute the console command.
     */
    public function handle(ImageProcessingService $imageService): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info("🚀 Starting SpaceSeeks Property Image Optimization Engine...");
        if ($isDryRun) {
            $this->warn("⚠️ DRY-RUN MODE: Files will not be modified or converted.");
        }

        $directories = [
            'properties' => public_path('uploads/properties'),
            'property_images' => public_path('uploads/property_images'),
            'avatars' => public_path('uploads/avatars'),
            'rm_avatars' => public_path('uploads/rm_avatars'),
        ];

        $totalConverted = 0;
        $totalSavedBytes = 0;
        $tableRows = [];

        foreach ($directories as $label => $dirPath) {
            if (!file_exists($dirPath)) {
                continue;
            }

            $files = glob($dirPath . '/*.*');
            foreach ($files as $filePath) {
                $fileInfo = pathinfo($filePath);
                $ext = strtolower($fileInfo['extension'] ?? '');
                $fileName = $fileInfo['basename'];

                // Skip thumbnails and already converted webp files if thumb exists
                if (str_starts_with($fileName, 'thumb_')) {
                    continue;
                }

                $origSize = filesize($filePath);

                // If non-webp file, convert to webp + create thumbnail
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                    $this->line("Processing [{$label}] {$fileName} (" . round($origSize / 1024, 1) . " KB)...");

                    if (!$isDryRun) {
                        $result = $imageService->processExistingFile($filePath, $label, false);
                        if ($result) {
                            $newSize = $result['size_bytes'];
                            $thumbSize = $result['thumb_size_bytes'];
                            $saved = max(0, $origSize - $newSize);
                            $totalSavedBytes += $saved;
                            $totalConverted++;

                            // Update corresponding database image URLs
                            $oldUrlPath = '/uploads/' . $label . '/' . $fileName;
                            $newUrlPath = $result['relative_url'];

                            PropertyImage::where('image_url', 'LIKE', '%' . $fileName)->update([
                                'image_url' => $newUrlPath,
                            ]);

                            $tableRows[] = [
                                $label,
                                $fileName,
                                $result['file_name'],
                                round($origSize / 1024, 1) . ' KB',
                                round($newSize / 1024, 1) . ' KB',
                                round($thumbSize / 1024, 1) . ' KB',
                                round(($saved / max(1, $origSize)) * 100, 1) . '%',
                            ];
                        }
                    } else {
                        $tableRows[] = [
                            $label,
                            $fileName,
                            $fileInfo['filename'] . '.webp (simulated)',
                            round($origSize / 1024, 1) . ' KB',
                            '-- (Dry Run)',
                            '-- (Dry Run)',
                            '-- (Dry Run)',
                        ];
                    }
                } elseif ($ext === 'webp') {
                    // If already webp, check if thumbnail exists, create if missing
                    $thumbPath = $dirPath . DIRECTORY_SEPARATOR . 'thumb_' . $fileName;
                    if (!file_exists($thumbPath) && !$isDryRun) {
                        $imageService->processExistingFile($filePath, $label, false);
                    }
                }
            }
        }

        if (!empty($tableRows)) {
            $this->table(
                ['Folder', 'Original File', 'New WebP File', 'Orig Size', 'WebP Size', 'Thumb Size', 'Savings %'],
                $tableRows
            );
        }

        $this->info("✅ Optimization completed!");
        $this->info("✨ Total Converted: {$totalConverted} images");
        $this->info("💾 Total Storage Saved: " . round($totalSavedBytes / 1024, 2) . " KB (~" . round($totalSavedBytes / (1024 * 1024), 2) . " MB)");

        return Command::SUCCESS;
    }
}
