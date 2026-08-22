<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$searchService = app(\App\Services\PropertySearchService::class);

$queries = [
    'Tell me about all listing types on StayNest',
    'Show me verified PG & Hostels',
    'Show me Flats & Houses',
    'Show me Commercial spaces',
    'Details of Commercial spaces',
    'Tell me more about flats & houses',
    'Noida me boys PG 8k ke andar'
];

foreach ($queries as $q) {
    echo "========================================================\n";
    echo "QUERY: {$q}\n";
    $res = $searchService->search($q);
    echo "SUCCESS: " . ($res['success'] ? 'YES' : 'NO') . "\n";
    echo "RESPONSE TYPE: " . ($res['data']['response_type'] ?? 'unknown') . "\n";
    echo "MESSAGE:\n" . ($res['data']['message'] ?? '') . "\n";
    if (!empty($res['data']['total_matches'])) {
        echo "TOTAL MATCHES: {$res['data']['total_matches']}\n";
    }
    if (!empty($res['data']['listing_types'])) {
        echo "LISTING TYPES COUNT: " . count($res['data']['listing_types']) . "\n";
    }
    echo "========================================================\n\n";
}
