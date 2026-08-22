<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$searchService = app(\App\Services\PropertySearchService::class);

$queries = [
    'Commercial properties ke baare me batao',
    'Flats and houses ki detail do',
    'PG and hostels me kya kya facility hai',
    'Database me konsi listing types available hain',
    'Office space for rent',
    '2 BHK Flat chahiye'
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
    echo "========================================================\n\n";
}
