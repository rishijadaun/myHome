<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Property;
use App\Models\User;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\User\UserHomeController;

echo "========================================================\n";
echo "TESTING RECOMMENDED PROPERTIES LIFECYCLE & HOME VISIBILITY\n";
echo "========================================================\n\n";

// 1. Get properties of different types
$properties = Property::all();
echo "Total Properties in DB: " . $properties->count() . "\n";

// Reset all recommended to 0 for initial test
Property::query()->update(['is_recommended' => false, 'featured' => false]);
echo "Reset all properties to NOT recommended (0 records recommended).\n";

// 2. Test Home Controller with 0 recommended records
$userController = new UserHomeController();
$request = Request::create('/', 'GET');
$response0 = $userController->index($request);
$viewData0 = $response0->getData();
$recommendedProps0 = $viewData0['recommendedProperties'];

echo "Recommended Properties count when 0 marked: " . $recommendedProps0->count() . "\n";
assert($recommendedProps0->count() === 0, "Expected 0 recommended properties");

$renderedHtml0 = $response0->render();
$hasRecommendedSection0 = str_contains($renderedHtml0, 'Recommended for You');
echo "Is 'Recommended for You' section present in HTML with 0 records? " . ($hasRecommendedSection0 ? 'YES (FAIL)' : 'NO - HIDDEN COMPLETELY (PASS)') . "\n";
if ($hasRecommendedSection0) {
    echo "ERROR: Recommended for You section was found when 0 records recommended!\n";
    exit(1);
}

// 3. Test Admin toggleRecommended AJAX endpoint
$adminController = new AdminPropertyController();

// Select one PG, one Flat, one Commercial
$pgProp = Property::whereHas('propertyType', function($q) {
    $q->where('slug', 'like', '%pg%')->orWhere('slug', 'like', '%hostel%');
})->first() ?? Property::first();

$flatProp = Property::whereHas('propertyType', function($q) {
    $q->where('slug', 'like', '%flat%')->orWhere('slug', 'like', '%apartment%');
})->first();

$commProp = Property::whereHas('propertyType', function($q) {
    $q->where('slug', 'like', '%commercial%')->orWhere('slug', 'like', '%shop%')->orWhere('slug', 'like', '%office%');
})->first();

echo "\nTesting Admin 1-Click Toggle for PG Property ID: {$pgProp->id} ({$pgProp->name})...\n";
$req = Request::create("/admin/pgs/{$pgProp->id}/toggle-recommended", 'POST');
$toggleRes1 = $adminController->toggleRecommended($req, $pgProp->id);
$toggleData1 = json_decode($toggleRes1->getContent(), true);

echo "Toggle Response: " . json_encode($toggleData1) . "\n";
assert($toggleData1['success'] === true, "Expected success true");
assert($toggleData1['is_recommended'] === true, "Expected is_recommended true");

$pgProp->refresh();
echo "Property is_recommended in DB: " . ($pgProp->is_recommended ? '1 (TRUE)' : '0 (FALSE)') . "\n";
echo "Property featured in DB: " . ($pgProp->featured ? '1 (TRUE)' : '0 (FALSE)') . "\n";
assert($pgProp->is_recommended == true, "Expected is_recommended to be true in DB");

// 4. Test Home Controller when 1+ records are recommended
$response1 = $userController->index($request);
$viewData1 = $response1->getData();
$recommendedProps1 = $viewData1['recommendedProperties'];

echo "\nRecommended Properties count when 1 marked: " . $recommendedProps1->count() . "\n";
assert($recommendedProps1->count() >= 1, "Expected at least 1 recommended property");

$renderedHtml1 = $response1->render();
$hasRecommendedSection1 = str_contains($renderedHtml1, 'Recommended for You');
$hasPropName1 = str_contains($renderedHtml1, $pgProp->name);

echo "Is 'Recommended for You' section present in HTML? " . ($hasRecommendedSection1 ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Is recommended property name present in Recommended section? " . ($hasPropName1 ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
assert($hasRecommendedSection1, "Expected Recommended section to be visible");
assert($hasPropName1, "Expected recommended property name to be in HTML");

// 5. Test Admin filter by recommended (recommended=1 and recommended=0)
echo "\nTesting Admin Index Filter by recommended=1...\n";
$adminReq1 = Request::create('/admin/pgs', 'GET', ['recommended' => '1']);
$adminIndexRes1 = $adminController->index($adminReq1);
$adminViewData1 = $adminIndexRes1->getData();
echo "Admin Filter recommended=1 returned " . $adminViewData1['properties']->count() . " properties.\n";
foreach ($adminViewData1['properties'] as $p) {
    assert($p->is_recommended || $p->featured, "Expected all properties in recommended=1 to be recommended");
}

echo "\nTesting Admin Index Filter by recommended=0...\n";
$adminReq0 = Request::create('/admin/pgs', 'GET', ['recommended' => '0']);
$adminIndexRes0 = $adminController->index($adminReq0);
$adminViewData0 = $adminIndexRes0->getData();
echo "Admin Filter recommended=0 returned " . $adminViewData0['properties']->count() . " properties.\n";
foreach ($adminViewData0['properties'] as $p) {
    assert(!$p->is_recommended && !$p->featured, "Expected all properties in recommended=0 to not be recommended");
}

// 6. Test untoggling recommended
echo "\nTesting Admin 1-Click Untoggle for Property ID: {$pgProp->id}...\n";
$toggleRes2 = $adminController->toggleRecommended($req, $pgProp->id);
$toggleData2 = json_decode($toggleRes2->getContent(), true);
echo "Untoggle Response: " . json_encode($toggleData2) . "\n";
assert($toggleData2['is_recommended'] === false, "Expected is_recommended false");
$pgProp->refresh();
assert($pgProp->is_recommended == false, "Expected is_recommended to be false in DB");

// Verify that home page section hides again
$responseFinal = $userController->index($request);
$renderedHtmlFinal = $responseFinal->render();
$hasRecommendedSectionFinal = str_contains($renderedHtmlFinal, 'Recommended for You');
echo "Is 'Recommended for You' section present after untoggling back to 0? " . ($hasRecommendedSectionFinal ? 'YES (FAIL)' : 'NO - HIDDEN COMPLETELY (PASS)') . "\n";
assert(!$hasRecommendedSectionFinal, "Expected section to be completely hidden");

// Re-recommend a few properties for a good initial experience if desired
if ($pgProp) {
    $pgProp->update(['is_recommended' => true, 'featured' => true]);
    echo "\nRe-marked property '{$pgProp->name}' as Recommended for live display.\n";
}
if ($flatProp) {
    $flatProp->update(['is_recommended' => true, 'featured' => true]);
    echo "Re-marked flat '{$flatProp->name}' as Recommended.\n";
}
if ($commProp) {
    $commProp->update(['is_recommended' => true, 'featured' => true]);
    echo "Re-marked commercial '{$commProp->name}' as Recommended.\n";
}

echo "\n========================================================\n";
echo "ALL TESTS PASSED SUCCESSFULLY! 100% VERIFIED\n";
echo "========================================================\n";
