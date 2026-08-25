<?php
$manifest = json_decode(file_get_contents(__DIR__ . '/../public/build/manifest.json'), true);
$appCssFile = $manifest['resources/css/app.css']['file'] ?? null;
$css = file_get_contents(__DIR__ . '/../public/build/' . $appCssFile);

echo "CSS File: " . $appCssFile . "\n";
echo "Size: " . strlen($css) . " bytes\n";

if (str_contains($css, '768px')) {
    echo "  [FOUND] @media (min-width: 768px) is in production CSS!\n";
} else {
    echo "  [FAIL] 768px not found!\n";
}

if (str_contains($css, 'display:none!important') || str_contains($css, 'display: none !important')) {
    echo "  [FOUND] display: none !important is in production CSS!\n";
} else {
    echo "  [FAIL] display: none !important not found!\n";
}
