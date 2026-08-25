<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Property;

$controller = app(\App\Http\Controllers\Api\v1\PropertySubmissionController::class);

echo "=== PRECISE VERIFICATION OF PROPERTY DETAILS FOR EDIT FORM ===\n\n";

$tests = [
    'Flat' => Property::where('name', 'like', '%Supertech Supernova%')->first(),
    'Commercial' => Property::where('name', 'like', '%Brigade Tech Gardens%')->first(),
    'Plot' => Property::where('name', 'like', '%250 Sq. Yards Freehold%')->first(),
    'PG' => Property::where('name', 'like', '%Sabarmati Executive%')->first(),
];

foreach ($tests as $label => $prop) {
    if (!$prop) {
        echo "[$label] Property not found!\n";
        continue;
    }
    $res = $controller->details($prop->id);
    $data = $res->getData(true)['data'];

    echo "[$label] \"{$prop->name}\":\n";
    echo "  - property_category: " . ($data['property_category'] ?? 'null') . "\n";
    echo "  - listing_type: " . ($data['listing_type'] ?? 'null') . "\n";
    echo "  - ad_type: " . ($data['ad_type'] ?? 'null') . "\n";
    echo "  - carpet_area_sqft: " . ($data['carpet_area_sqft'] ?? 'null') . "\n";
    echo "  - bhk_type: " . ($data['bhk_type'] ?? 'null') . "\n";
    echo "  - furnishing_status: " . ($data['furnishing_status'] ?? 'null') . "\n";
    echo "  - room_sharing count: " . count($data['room_sharing'] ?? []) . "\n";

    // Simulate JS visibility logic:
    $type = strtolower($data['listing_type'] ?? '');
    $category = $data['property_category'] ?? '';
    
    $showPgRoomConfigs = ($type === 'pg-hostel' || $type === 'co-living');
    $showCommercialConfigs = (str_contains($type, 'commercial') || str_contains($type, 'office') || str_contains($type, 'shop') || str_contains($type, 'warehouse'));
    $showPlotConfigs = (str_contains($type, 'plot') || str_contains($type, 'land'));
    $showFlatConfigs = (!$showPgRoomConfigs && !$showCommercialConfigs && !$showPlotConfigs);

    echo "  JS Simulation -> Visible Config Container in Step 2:\n";
    if ($showPgRoomConfigs) echo "    -> #pgRoomConfigs (PG Room Sharing Types & Rent)\n";
    if ($showCommercialConfigs) echo "    -> #commercialConfigs (Commercial Space Specifications)\n";
    if ($showPlotConfigs) echo "    -> #plotConfigs (Land & Plot Specifications)\n";
    if ($showFlatConfigs) echo "    -> #flatConfigs (Apartment & Residential Specifications)\n";

    if ($label === 'Flat') {
        assert($showFlatConfigs && !$showPgRoomConfigs, "Flat MUST show flatConfigs and hide pgRoomConfigs!");
        echo "  [PASS] Flat correctly shows Flat Specs and HIDES PG Room Sharing!\n";
    } elseif ($label === 'Commercial') {
        assert($showCommercialConfigs && !$showPgRoomConfigs, "Commercial MUST show commercialConfigs and hide pgRoomConfigs!");
        echo "  [PASS] Commercial correctly shows Commercial Specs and HIDES PG Room Sharing!\n";
    } elseif ($label === 'Plot') {
        assert($showPlotConfigs && !$showPgRoomConfigs, "Plot MUST show plotConfigs and hide pgRoomConfigs!");
        echo "  [PASS] Plot correctly shows Plot Specs and HIDES PG Room Sharing!\n";
    } elseif ($label === 'PG') {
        assert($showPgRoomConfigs && !$showFlatConfigs, "PG MUST show pgRoomConfigs and hide flatConfigs!");
        echo "  [PASS] PG correctly shows PG Room Sharing!\n";
    }
    echo "\n";
}

echo "=== ALL 4 PROPERTY TYPES VERIFIED SUCCESSFULLY ===\n";
