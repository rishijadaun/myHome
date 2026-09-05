<?php

$srcPath = 'C:/Users/tingu/.gemini/antigravity-ide/brain/d787fa12-8bfd-4141-a8e7-3b7d06b5e579/.user_uploaded/media_1788585883024.png';

$imgInfo = getimagesize($srcPath);
echo "Image Info: " . print_r($imgInfo, true) . "\n";

$src = imagecreatefrompng($srcPath);
$width = imagesx($src);
$height = imagesy($src);

echo "Width: $width, Height: $height\n";

// Let's create a transparent version by converting near-white background to transparent
$transparentImg = imagecreatetruecolor($width, $height);
imagealphablending($transparentImg, false);
imagesavealpha($transparentImg, true);
$transparentColor = imagecolorallocatealpha($transparentImg, 0, 0, 0, 127);
imagefilledrectangle($transparentImg, 0, 0, $width, $height, $transparentColor);

// Scan background color from corners
for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $rgb = imagecolorat($src, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        // If it's near white background
        if ($r > 240 && $g > 240 && $b > 240) {
            // make transparent
            // Anti-aliasing edge softening
            $avg = ($r + $g + $b) / 3;
            if ($avg > 250) {
                imagesetpixel($transparentImg, $x, $y, $transparentColor);
            } else {
                $alpha = (int)(($avg - 240) / 10 * 127);
                $col = imagecolorallocatealpha($transparentImg, $r, $g, $b, $alpha);
                imagesetpixel($transparentImg, $x, $y, $col);
            }
        } else {
            $col = imagecolorallocatealpha($transparentImg, $r, $g, $b, 0);
            imagesetpixel($transparentImg, $x, $y, $col);
        }
    }
}

// Find bounding box of the icon
$minX = $width; $minY = $height; $maxX = 0; $maxY = 0;
for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $color = imagecolorat($transparentImg, $x, $y);
        $alpha = ($color >> 24) & 0x7F;
        if ($alpha < 120) {
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}

echo "Icon BBox: minX=$minX, minY=$minY, maxX=$maxX, maxY=$maxY\n";
$iconW = $maxX - $minX + 1;
$iconH = $maxY - $minY + 1;
echo "Icon Dimensions: {$iconW}x{$iconH}\n";

// Function to generate square icon with padding
function generateSquareIcon($source, $minX, $minY, $iconW, $iconH, $targetSize, $paddingRatio, $destPath, $bg = null) {
    $canvas = imagecreatetruecolor($targetSize, $targetSize);
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);
    
    if ($bg === 'white') {
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $targetSize, $targetSize, $white);
    } else {
        $trans = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $targetSize, $targetSize, $trans);
    }
    imagealphablending($canvas, true);

    $availableSize = $targetSize * (1 - $paddingRatio * 2);
    $scale = min($availableSize / $iconW, $availableSize / $iconH);
    $destW = (int)($iconW * $scale);
    $destH = (int)($iconH * $scale);
    $destX = (int)(($targetSize - $destW) / 2);
    $destY = (int)(($targetSize - $destH) / 2);

    imagecopyresampled($canvas, $source, $destX, $destY, $minX, $minY, $destW, $destH, $iconW, $iconH);
    imagepng($canvas, $destPath, 9);
    imagedestroy($canvas);
    echo "Generated: $destPath ({$targetSize}x{$targetSize})\n";
}

// 1. Favicon (512x512, transparent, slight padding 5%)
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 512, 0.05, 'public/images/favicon.png');

// 2. SpaceSeeks App Icon (512x512, transparent, slight padding 5%)
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 512, 0.05, 'public/images/spaceseeks-icon.png');

// 3. PWA Icon 192 (192x192, transparent, slight padding 5%)
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 192, 0.05, 'public/images/icon-192.png');

// 4. PWA Icon 512 (512x512, transparent, slight padding 5%)
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 512, 0.05, 'public/images/icon-512.png');

// 5. Apple Touch Icon (180x180, on crisp white rounded / square canvas with 10% padding as iOS likes)
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 180, 0.08, 'public/images/apple-touch-icon.png', 'white');

// 6. Maskable 192 (with 35% safe zone margin and white background)
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 192, 0.20, 'public/images/icon-192-maskable.png', 'white');

// 7. Maskable 512 (with 35% safe zone margin and white background)
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 512, 0.20, 'public/images/icon-512-maskable.png', 'white');

// Also generate .ico file (or 32x32 / 16x16 / 48x48) if needed, and also save public/favicon.ico
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 64, 0.05, 'public/favicon.png');
generateSquareIcon($transparentImg, $minX, $minY, $iconW, $iconH, 32, 0.05, 'public/favicon.ico');

imagedestroy($transparentImg);
imagedestroy($src);

echo "\nAll favicons and icons successfully generated from second image!\n";
