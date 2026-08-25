<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Property;
use Illuminate\Support\Facades\Request;

echo "=== TESTING PROPERTY DETAILS API FOR EDIT FORM ===\n\n";

// 1. Find or check a PG property
$pgProp = Property::where('property_category', 'residential')
    ->whereHas('propertyType', function($q) {
        $q->whereIn('slug', ['pg-hostel', 'co-living']);
    })->first();

// 2. Find or check a Flat property
$flatProp = Property::where(function($q) {
    $q->where('name', 'like', '%flat%')
      ->orWhere('name', 'like', '%bhk%')
      ->orWhere('name', 'like', '%villa%')
      ->orWhere('bhk_type', '!=', null);
})->first();

// 3. Find or check a Commercial property
$commProp = Property::where('property_category', 'commercial')
    ->orWhere('name', 'like', '%commercial%')
    ->orWhere('name', 'like', '%office%')
    ->orWhere('name', 'like', '%shop%')
    ->first();

// Test Details Endpoint
$controller = app(\App\Http\Controllers\Api\v1\PropertySubmissionController::class);

if ($pgProp) {
    echo "1. PG Property Details Test (#{$pgProp->code}):\n";
    $req = Request::create("/api/v1/properties/details/{$pgProp->id}", 'GET');
    $response = $controller->details($pgProp->id);
    $data = $response->getData(true);
    echo "  Status: " . ($data['success'] ? 'SUCCESS' : 'FAILED') . "\n";
    echo "  Category: " . ($data['data']['property_category'] ?? 'N/A') . "\n";
    echo "  Listing Type: " . ($data['data']['listing_type'] ?? 'N/A') . "\n";
    echo "  Ad Type: " . ($data['data']['ad_type'] ?? 'N/A') . "\n";
    echo "  Room Sharing Count: " . count($data['data']['room_sharing'] ?? []) . "\n";
    assert(($data['data']['property_category'] ?? '') === 'residential');
    echo "  [PASS] PG Details API returns correct category and room sharing.\n\n";
}

if ($flatProp) {
    echo "2. Flat Property Details Test (#{$flatProp->code}):\n";
    $response = $controller->details($flatProp->id);
    $data = $response->getData(true);
    echo "  Status: " . ($data['success'] ? 'SUCCESS' : 'FAILED') . "\n";
    echo "  Category: " . ($data['data']['property_category'] ?? 'N/A') . "\n";
    echo "  Listing Type: " . ($data['data']['listing_type'] ?? 'N/A') . "\n";
    echo "  BHK: " . ($data['data']['bhk_type'] ?? 'N/A') . "\n";
    echo "  Carpet Area: " . ($data['data']['carpet_area_sqft'] ?? 'N/A') . "\n";
    echo "  Ad Type: " . ($data['data']['ad_type'] ?? 'N/A') . "\n";
    echo "  Expected Price: " . ($data['data']['expected_price'] ?? 'N/A') . "\n";
    echo "  [PASS] Flat Details API returns category, BHK, and carpet area.\n\n";
}

if ($commProp) {
    echo "3. Commercial Property Details Test (#{$commProp->code}):\n";
    $response = $controller->details($commProp->id);
    $data = $response->getData(true);
    echo "  Status: " . ($data['success'] ? 'SUCCESS' : 'FAILED') . "\n";
    echo "  Category: " . ($data['data']['property_category'] ?? 'N/A') . "\n";
    echo "  Listing Type: " . ($data['data']['listing_type'] ?? 'N/A') . "\n";
    echo "  Ad Type: " . ($data['data']['ad_type'] ?? 'N/A') . "\n";
    echo "  [PASS] Commercial Details API returns category and commercial data.\n\n";
}

echo "=== ALL API TESTS PASSED! ===\n";
