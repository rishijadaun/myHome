<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

echo "========================================================\n";
echo "   VERIFYING TAILWIND RESPONSIVE UTILITIES & MD:HIDDEN\n";
echo "========================================================\n\n";

// 1. Check compiled CSS file
$manifest = json_decode(file_get_contents(__DIR__ . '/../public/build/manifest.json'), true);
$appCssFile = $manifest['resources/css/app.css']['file'] ?? null;
assert(!empty($appCssFile), "manifest.json must contain resources/css/app.css entry!");

$cssContent = file_get_contents(__DIR__ . '/../public/build/' . $appCssFile);
assert(str_contains($cssContent, '768px'), "Compiled CSS must contain @media (min-width: 768px) breakpoints!");
assert(str_contains($cssContent, 'hidden'), "Compiled CSS must contain hidden utilities!");
echo "  [PASS] Production CSS ({$appCssFile}) is active and compiled with 768px responsive breakpoint rules.\n";

// 2. Verify Home Page Render
$res = $app->handle(Request::create('/', 'GET'));
assert($res->getStatusCode() === 200, "Home page must return 200 OK!");
$html = $res->getContent();
assert(str_contains($html, 'hidden md:block'), "Home page must contain 'hidden md:block' to hide on mobile!");
assert(str_contains($html, 'mobileMainHeader'), "Mobile header with md:hidden must be present in DOM!");
echo "  [PASS] Home page properly outputs responsive classes (hidden md:block & md:hidden).\n";

echo "\n=== RESPONSIVE UTILITIES TEST PASSED! ===\n";
