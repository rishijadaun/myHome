<?php

$baseUrl = 'http://127.0.0.1:8000';

$endpoints = [
    '/' => 'Home Page',
    '/manifest.json' => 'PWA Manifest',
    '/sw.js' => 'PWA Service Worker',
    '/favicon.ico' => 'Root Favicon ICO',
    '/favicon.png' => 'Root Favicon PNG',
    '/images/spaceseeks-logo.png' => 'SpaceSeeks Full Logo',
    '/images/favicon.png' => 'SpaceSeeks Favicon',
    '/images/spaceseeks-icon.png' => 'SpaceSeeks App Icon',
    '/images/icon-192.png' => 'PWA Icon 192',
    '/images/icon-192-maskable.png' => 'PWA Maskable Icon 192',
    '/images/icon-512.png' => 'PWA Icon 512',
    '/images/icon-512-maskable.png' => 'PWA Maskable Icon 512',
    '/images/apple-touch-icon.png' => 'Apple Touch Icon',
    '/login' => 'User Login',
    '/broker/login' => 'Broker Login',
    '/admin/login' => 'Admin Login',
    '/find-flatmates' => 'Find Flatmates',
    '/about-us' => 'About Us',
    '/contact-us' => 'Contact Us',
    '/list-your-property' => 'List Property'
];

echo "=== VERIFYING PWA ASSETS & ALL LOGO ENDPOINTS ===\n\n";

$allPassed = true;
foreach ($endpoints as $uri => $label) {
    $url = $baseUrl . $uri;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo " [OK] $label ($uri) -> HTTP 200 ($contentType)\n";
    } else {
        echo " [FAIL] $label ($uri) -> HTTP $httpCode\n";
        $allPassed = false;
    }
}

// Verify manifest content
$manifestContent = file_get_contents('public/manifest.json');
$manifest = json_decode($manifestContent, true);
if ($manifest && isset($manifest['icons']) && count($manifest['icons']) >= 4) {
    echo "\n [OK] Manifest JSON is valid with " . count($manifest['icons']) . " icons registered.\n";
} else {
    echo "\n [FAIL] Manifest JSON has issues.\n";
    $allPassed = false;
}

echo "\nResult: " . ($allPassed ? "ALL CHECKS PASSED!" : "SOME CHECKS FAILED!") . "\n";
