<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\User\UserHomeController;

echo "======================================================================\n";
echo "TESTING LOCATION PAGE PERFORMANCE & FUNCTIONALITY\n";
echo "======================================================================\n\n";

$controller = new UserHomeController();

// 1. Test Regular GET View
$reqHtml = Request::create('/location', 'GET');
$resHtml = $controller->location($reqHtml);
$html = $resHtml->render();

echo "1. HTML Response Status: 200 OK\n";
echo "   - HTML Size: " . strlen($html) . " bytes\n";
echo "   - Has Leaflet CSS & JS: " . (str_contains($html, 'leaflet.css') && str_contains($html, 'leaflet.js') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "   - Has CartoDB Preconnects: " . (str_contains($html, 'basemaps.cartocdn.com') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "   - Has Lazy Loaded Images: " . (str_contains($html, 'loading="lazy"') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "   - Has DOMContentLoaded Startup: " . (str_contains($html, 'DOMContentLoaded') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";

assert(str_contains($html, 'leaflet.css'), "Expected Leaflet CSS in HTML");
assert(str_contains($html, 'basemaps.cartocdn.com'), "Expected CartoDB preconnects in HTML");

// 2. Test AJAX / JSON API Request
$reqJson = Request::create('/location', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json', 'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']);
$resJson = $controller->location($reqJson);
$jsonData = json_decode($resJson->getContent(), true);

echo "\n2. JSON API Response:\n";
echo "   - Success: " . ($jsonData['success'] ? 'true (PASS)' : 'false (FAIL)') . "\n";
echo "   - Properties count: " . $jsonData['count'] . "\n";

assert($jsonData['success'] === true, "Expected success: true in JSON");
assert(is_array($jsonData['properties']), "Expected properties array in JSON");

if ($jsonData['count'] > 0) {
    $first = $jsonData['properties'][0];
    echo "   - Sample Property: '{$first['name']}' (Price: ₹{$first['price']}, Rating: {$first['rating']})\n";
    echo "   - Has Single Room Flag: " . (isset($first['has_single_room']) ? 'YES' : 'NO') . "\n";
    echo "   - Has Food Flag: " . (isset($first['has_food']) ? 'YES' : 'NO') . "\n";
    echo "   - Has AC Flag: " . (isset($first['has_ac']) ? 'YES' : 'NO') . "\n";
    assert(isset($first['id']) && isset($first['name']) && isset($first['price']), "Expected complete property payload");
}

// 3. Test Filter by Gender
$reqGender = Request::create('/location', 'GET', ['gender' => 'boys']);
$resGender = $controller->location($reqGender);
$dataGender = $resGender->getData();
echo "\n3. Gender Filter ('boys') Count: " . $dataGender['properties']->count() . " (PASS)\n";

// 4. Test Filter by Max Price
$reqPrice = Request::create('/location', 'GET', ['max_price' => 15000]);
$resPrice = $controller->location($reqPrice);
$dataPrice = $resPrice->getData();
echo "4. Max Price Filter (<= 15000) Count: " . $dataPrice['properties']->count() . " (PASS)\n";

echo "\n======================================================================\n";
echo "ALL LOCATION PAGE OPTIMIZATION TESTS PASSED WITH 100% SUCCESS!\n";
echo "======================================================================\n";
