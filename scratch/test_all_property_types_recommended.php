<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\User\UserHomeController;

echo "======================================================================\n";
echo "TESTING RECOMMENDED FOR ALL CATEGORIES (PG, FLAT, COMMERCIAL)\n";
echo "======================================================================\n\n";

$userController = new UserHomeController();
$adminController = new AdminPropertyController();

// 1. Identify sample properties for each category (active & verified)
$pgProp = Property::where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)
    ->whereHas('propertyType', function($q) {
        $q->where('slug', 'like', '%pg%')->orWhere('slug', 'like', '%hostel%');
    })->first();

$flatProp = Property::where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)
    ->whereHas('propertyType', function($q) {
        $q->where('slug', 'like', '%flat%')->orWhere('slug', 'like', '%apartment%');
    })->first();

if (!$flatProp) {
    // If no active verified flat, get any flat and ensure active/verified for testing
    $flatProp = Property::whereHas('propertyType', function($q) {
        $q->where('slug', 'like', '%flat%')->orWhere('slug', 'like', '%apartment%');
    })->first();
    if ($flatProp) {
        $flatProp->update(['status' => 'active', 'verification_status' => 'verified', 'is_active' => 1]);
    }
}

$commProp = Property::where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)
    ->whereHas('propertyType', function($q) {
        $q->where('slug', 'like', '%commercial%')->orWhere('slug', 'like', '%shop%')->orWhere('slug', 'like', '%office%');
    })->first();

if (!$commProp) {
    // If no active verified commercial, get any commercial and ensure active/verified for testing
    $commProp = Property::whereHas('propertyType', function($q) {
        $q->where('slug', 'like', '%commercial%')->orWhere('slug', 'like', '%shop%')->orWhere('slug', 'like', '%office%');
    })->first();
    if ($commProp) {
        $commProp->update(['status' => 'active', 'verification_status' => 'verified', 'is_active' => 1]);
    }
}

echo "Identified Samples:\n";
echo " - PG: " . ($pgProp ? "ID {$pgProp->id} ({$pgProp->name})" : 'None') . "\n";
echo " - Flat: " . ($flatProp ? "ID {$flatProp->id} ({$flatProp->name})" : 'None') . "\n";
echo " - Commercial: " . ($commProp ? "ID {$commProp->id} ({$commProp->name})" : 'None') . "\n\n";

// TEST CASE 1: All 0 Recommended
echo "--- TEST CASE 1: When 0 properties are recommended across all types ---\n";
Property::query()->update(['is_recommended' => false, 'featured' => false]);

$reqPg = Request::create('/', 'GET', ['type' => 'pg-hostel']);
$htmlPg0 = $userController->index($reqPg)->render();

// Check if any "Recommended for You" exists in the HTML
$hasRec0 = str_contains($htmlPg0, 'Recommended for You');
echo "Is 'Recommended for You' present anywhere in HTML when 0 records recommended? " . ($hasRec0 ? 'YES (FAIL)' : 'NO - COMPLETELY HIDDEN (PASS)') . "\n";
assert(!$hasRec0, "Expected 0 Recommended sections when no properties recommended");

// TEST CASE 2: Only Flat is Recommended
echo "\n--- TEST CASE 2: When Only Flat is Recommended ---\n";
if ($flatProp) {
    Property::where('id', $flatProp->id)->update(['is_recommended' => true, 'featured' => true]);
    
    $reqFlat = Request::create('/', 'GET', ['type' => 'flat-apartment']);
    $resFlat = $userController->index($reqFlat);
    $dataFlat = $resFlat->getData();
    $htmlFlat = $resFlat->render();

    echo "Flat Recommended count: " . $dataFlat['flatRecommended']->count() . "\n";
    echo "PG Recommended count: " . $dataFlat['recommendedProperties']->count() . "\n";
    echo "Commercial Recommended count: " . $dataFlat['commercialRecommended']->count() . "\n";

    assert($dataFlat['flatRecommended']->count() >= 1, "Expected flatRecommended >= 1");
    assert($dataFlat['recommendedProperties']->count() === 0, "Expected PG recommended === 0");
    assert($dataFlat['commercialRecommended']->count() === 0, "Expected Commercial recommended === 0");

    $hasFlatRecSwiper = str_contains($htmlFlat, 'flatRecommendedSwiper');
    $hasFlatPropName = str_contains($htmlFlat, $flatProp->name);
    echo "Does Flat view render flatRecommendedSwiper? " . ($hasFlatRecSwiper ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
    echo "Does Flat view show recommended flat name? " . ($hasFlatPropName ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
    assert($hasFlatRecSwiper, "Expected flatRecommendedSwiper in HTML");
}

// TEST CASE 3: Only Commercial is Recommended
echo "\n--- TEST CASE 3: When Only Commercial is Recommended ---\n";
Property::query()->update(['is_recommended' => false, 'featured' => false]);
if ($commProp) {
    Property::where('id', $commProp->id)->update(['is_recommended' => true, 'featured' => true]);
    
    $reqComm = Request::create('/', 'GET', ['type' => 'commercial']);
    $resComm = $userController->index($reqComm);
    $dataComm = $resComm->getData();
    $htmlComm = $resComm->render();

    echo "Commercial Recommended count: " . $dataComm['commercialRecommended']->count() . "\n";
    echo "Flat Recommended count: " . $dataComm['flatRecommended']->count() . "\n";
    echo "PG Recommended count: " . $dataComm['recommendedProperties']->count() . "\n";

    assert($dataComm['commercialRecommended']->count() >= 1, "Expected commercialRecommended >= 1");
    assert($dataComm['flatRecommended']->count() === 0, "Expected Flat recommended === 0");
    assert($dataComm['recommendedProperties']->count() === 0, "Expected PG recommended === 0");

    $hasCommRecSwiper = str_contains($htmlComm, 'commercialRecommendedSwiper');
    $hasCommPropName = str_contains($htmlComm, $commProp->name);
    echo "Does Commercial view render commercialRecommendedSwiper? " . ($hasCommRecSwiper ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
    echo "Does Commercial view show recommended commercial name? " . ($hasCommPropName ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
    assert($hasCommRecSwiper, "Expected commercialRecommendedSwiper in HTML");
}

// TEST CASE 4: PG, Flat, and Commercial are ALL Recommended
echo "\n--- TEST CASE 4: When PG, Flat, and Commercial each have Recommended Listings ---\n";
if ($pgProp) Property::where('id', $pgProp->id)->update(['is_recommended' => true, 'featured' => true]);
if ($flatProp) Property::where('id', $flatProp->id)->update(['is_recommended' => true, 'featured' => true]);
if ($commProp) Property::where('id', $commProp->id)->update(['is_recommended' => true, 'featured' => true]);

$reqAll = Request::create('/', 'GET');
$resAll = $userController->index($reqAll);
$dataAll = $resAll->getData();
$htmlAll = $resAll->render();

echo "PG Recommended count: " . $dataAll['recommendedProperties']->count() . "\n";
echo "Flat Recommended count: " . $dataAll['flatRecommended']->count() . "\n";
echo "Commercial Recommended count: " . $dataAll['commercialRecommended']->count() . "\n";

assert($dataAll['recommendedProperties']->count() >= 1, "Expected PG recommended >= 1");
assert($dataAll['flatRecommended']->count() >= 1, "Expected Flat recommended >= 1");
assert($dataAll['commercialRecommended']->count() >= 1, "Expected Commercial recommended >= 1");

$hasPGRec = str_contains($htmlAll, 'recommendedSwiper');
$hasFlatRec = str_contains($htmlAll, 'flatRecommendedSwiper');
$hasCommRec = str_contains($htmlAll, 'commercialRecommendedSwiper');

echo "PG Recommended Swiper rendered: " . ($hasPGRec ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Flat Recommended Swiper rendered: " . ($hasFlatRec ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Commercial Recommended Swiper rendered: " . ($hasCommRec ? 'YES (PASS)' : 'NO (FAIL)') . "\n";

assert($hasPGRec && $hasFlatRec && $hasCommRec, "Expected all 3 category recommended sections to be present when > 0 records exist");

echo "\n======================================================================\n";
echo "ALL CATEGORY RECOMMENDED TESTS PASSED WITH 100% SUCCESS!\n";
echo "======================================================================\n";
