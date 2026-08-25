<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

echo "========================================================\n";
echo "   TESTING TOP MOBILE APP BANNER & MOBILE HIDE LOGIC\n";
echo "========================================================\n\n";

// 1. Verify Home Page contains hidden md:block for bulky section
$homeHtml = file_get_contents(__DIR__ . '/../resources/views/user/home.blade.php');
assert(str_contains($homeHtml, 'hidden md:block max-w-7xl mx-auto px-4 sm:px-6 mb-12 sm:mb-16'), "Bulky Get the StayNest App section must be hidden on mobile (hidden md:block)!");
echo "  [PASS] Bulky 'Get the StayNest App' section is hidden on mobile screens (visible only on desktop/tablet).\n";

// 2. Verify Top App Download Banner in Header
$headerHtml = file_get_contents(__DIR__ . '/../resources/views/user/partials/header.blade.php');
assert(str_contains($headerHtml, 'id="topAppDownloadBanner"'), "Header must contain topAppDownloadBanner!");
assert(str_contains($headerHtml, 'dismissTopAppBanner'), "Header must contain dismissTopAppBanner function call!");
assert(str_contains($headerHtml, 'staynest_top_app_banner_dismissed_until'), "Header must contain 30-day dismissal storage key!");
assert(str_contains($headerHtml, '30 * 24 * 60 * 60 * 1000'), "Header must use 30 days (1 month) in milliseconds!");
echo "  [PASS] Top App Download Banner is placed at the top of the mobile header with 30-day (1 Month) close memory.\n";

// 3. Test HTTP Response for Home Page
$response = $app->handle(Request::create('/', 'GET'));
assert($response->getStatusCode() === 200, "Home page must return 200 OK!");
$rendered = $response->getContent();
assert(str_contains($rendered, 'topAppDownloadBanner'), "Rendered Home HTML must contain topAppDownloadBanner!");
echo "  [PASS] Rendered Home Page contains Top Smart App Download Banner successfully.\n";

echo "\n=== ALL APP BANNER TESTS PASSED! ===\n";
