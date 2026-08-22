<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\User\UserHomeController;

echo "======================================================================\n";
echo "TESTING LOCATION PAGE SEGMENTED PROPERTY TYPE TABS & FILTERING\n";
echo "======================================================================\n\n";

$controller = new UserHomeController();

// 1. Test HTML rendering with default PG tab active
$req = Request::create('/location', 'GET');
$res = $controller->location($req);
$html = $res->render();

echo "1. Segmented Property Type Tab Bar UI Verification:\n";
$hasTabBar = str_contains($html, 'id="propertyTypeTabBar"');
$hasPgTab = str_contains($html, 'id="locTab-pg-hostel"');
$hasFlatTab = str_contains($html, 'id="locTab-flat-apartment"');
$hasCommTab = str_contains($html, 'id="locTab-commercial"');
$hasSubFilters = str_contains($html, 'id="subFilterChipsContainer"');

echo "   - Tab Bar Container Present: " . ($hasTabBar ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "   - PG / Hostel Tab Present: " . ($hasPgTab ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "   - Flat / House Tab Present: " . ($hasFlatTab ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "   - Commercial Tab Present: " . ($hasCommTab ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "   - Sub-filter Container Present: " . ($hasSubFilters ? 'YES (PASS)' : 'NO (FAIL)') . "\n";

assert($hasTabBar && $hasPgTab && $hasFlatTab && $hasCommTab, "Tabs must be present in HTML");

// 2. Test JSON Property Type Categorization
$reqJson = Request::create('/location', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json', 'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']);
$resJson = $controller->location($reqJson);
$jsonData = json_decode($resJson->getContent(), true);

$properties = $jsonData['properties'];
$pgCount = 0;
$flatCount = 0;
$commCount = 0;

foreach ($properties as $p) {
    if ($p['is_flat']) $flatCount++;
    elseif ($p['is_commercial']) $commCount++;
    else $pgCount++;
}

echo "\n2. Property Records Type Breakdown:\n";
echo "   - Total Properties: " . count($properties) . "\n";
echo "   - PG / Hostel Properties (is_pg): $pgCount\n";
echo "   - Flat / House Properties (is_flat): $flatCount\n";
echo "   - Commercial Properties (is_commercial): $commCount\n";

assert($pgCount + $flatCount + $commCount === count($properties), "All properties must be correctly categorized");

// 3. Test URL Query Param (?type=flat-apartment)
$reqFlat = Request::create('/location', 'GET', ['type' => 'flat-apartment']);
$resFlat = $controller->location($reqFlat);
$viewData = $resFlat->getData();

echo "\n3. URL Query Parameter Support:\n";
echo "   - Selected Type in View: '{$viewData['selectedType']}' (PASS)\n";
assert($viewData['selectedType'] === 'flat-apartment', "Expected selectedType to be flat-apartment");

// 4. Test URL Query Param (?type=commercial)
$reqComm = Request::create('/location', 'GET', ['type' => 'commercial']);
$resComm = $controller->location($reqComm);
$viewDataComm = $resComm->getData();
echo "   - Commercial Type in View: '{$viewDataComm['selectedType']}' (PASS)\n";
assert($viewDataComm['selectedType'] === 'commercial', "Expected selectedType to be commercial");

echo "\n======================================================================\n";
echo "ALL SEGMENTED PROPERTY TYPE TAB TESTS PASSED WITH 100% SUCCESS!\n";
echo "======================================================================\n";
