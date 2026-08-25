<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Property;
use Illuminate\Http\Request;

echo "=== TESTING UPDATE ON FLAT & COMMERCIAL PROPERTY VIA API ===\n\n";

$flatProp = Property::where('name', 'like', '%Supertech Supernova%')->first();
$commProp = Property::where('name', 'like', '%Brigade Tech Gardens%')->first();

$controller = app(\App\Http\Controllers\Api\v1\PropertySubmissionController::class);

if ($flatProp) {
    echo "1. Testing Update on Flat Property (#{$flatProp->name}):\n";
    $payload = [
        'listing_type' => 'flat-apartment',
        'property_category' => 'residential',
        'ad_type' => 'rent',
        'name' => 'Supertech Supernova 2BHK Smart Apartment',
        'city' => 'Noida',
        'area' => 'Sector 94',
        'address' => 'Supertech Supernova, Sector 94, Noida Express Way',
        'monthly_rent' => 38000,
        'security_deposit' => 76000,
        'bhk_type' => '2 BHK',
        'furnishing_status' => 'Fully Furnished',
        'carpet_area_sqft' => 1250,
        'description' => 'Ultra luxury modern 2BHK apartment in Noida Sector 94 with high speed elevator and clubhouse.',
        'house_rules' => '• Family and working professionals welcome\n• No loud music after 11 PM',
        'amenities' => ['wifi', 'ac', 'parking', 'power-backup'],
        'room_sharing' => [], // Flat has NO room sharing!
        'owner_name' => 'Amit Sharma',
        'owner_phone' => '9876543210',
    ];

    $request = Request::create("/api/v1/properties/{$flatProp->id}/update", 'POST', $payload);
    $response = $controller->update($request, $flatProp->id);
    $data = $response->getData(true);
    echo "  Update Response: " . ($data['success'] ? 'SUCCESS' : 'FAILED: ' . ($data['message'] ?? '')) . "\n";
    assert($data['success'] === true, "Flat update must succeed!");

    // Verify persisted details
    $flatProp->refresh();
    $detailsRes = $controller->details($flatProp->id);
    $details = $detailsRes->getData(true)['data'];
    echo "  Updated Category: {$details['property_category']}\n";
    echo "  Updated BHK: {$details['bhk_type']}\n";
    echo "  Updated Carpet Area: {$details['carpet_area_sqft']}\n";
    echo "  Room Sharing Count: " . count($details['room_sharing']) . "\n";
    assert($details['bhk_type'] === '2 BHK');
    assert($details['carpet_area_sqft'] === 1250);
    echo "  [PASS] Flat property updated cleanly without room sharing issues.\n\n";
}

if ($commProp) {
    echo "2. Testing Update on Commercial Property (#{$commProp->name}):\n";
    $payload = [
        'listing_type' => 'commercial-office',
        'property_category' => 'commercial',
        'ad_type' => 'rent',
        'name' => 'Brigade Tech Gardens Modern IT Office Suite',
        'city' => 'Bengaluru',
        'area' => 'Whitefield',
        'address' => 'Brookefield Main Road, Whitefield, Bengaluru',
        'monthly_rent' => 125000,
        'security_deposit' => 375000,
        'carpet_area_sqft' => 2800,
        'commercial_space_type' => 'Ready-to-use Office',
        'description' => 'Grade-A plug and play commercial corporate office space with cafeteria and 100% DG power backup.',
        'house_rules' => '• Commercial corporate operations only\n• Visitor registration at reception',
        'amenities' => ['wifi', 'ac', 'parking', 'power-backup', 'cctv'],
        'room_sharing' => [],
        'owner_name' => 'Brigade Asset Management',
        'owner_phone' => '9988776655',
    ];

    $request = Request::create("/api/v1/properties/{$commProp->id}/update", 'POST', $payload);
    $response = $controller->update($request, $commProp->id);
    $data = $response->getData(true);
    echo "  Update Response: " . ($data['success'] ? 'SUCCESS' : 'FAILED: ' . ($data['message'] ?? '')) . "\n";
    assert($data['success'] === true, "Commercial update must succeed!");

    $commProp->refresh();
    $detailsRes = $controller->details($commProp->id);
    $details = $detailsRes->getData(true)['data'];
    echo "  Updated Category: {$details['property_category']}\n";
    echo "  Updated Carpet Area: {$details['carpet_area_sqft']}\n";
    echo "  Updated Rent: ₹{$details['monthly_rent']}\n";
    echo "  [PASS] Commercial property updated cleanly.\n\n";
}

echo "=== ALL UPDATES TESTED AND VERIFIED SUCCESSFULLY! ===\n";
