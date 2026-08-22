<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\User\UserHomeController;
use Illuminate\Support\Facades\DB;

DB::enableQueryLog();
$start = microtime(true);

$request = Request::create('/location', 'GET');
$controller = new UserHomeController();
$response = $controller->location($request);

$content = $response->render();

$end = microtime(true);
$duration = ($end - $start) * 1000;
$queries = DB::getQueryLog();

echo "Total Time: " . round($duration, 2) . " ms\n";
echo "Total Queries: " . count($queries) . "\n";
echo "Rendered HTML length: " . strlen($content) . " bytes\n\n";

foreach ($queries as $i => $q) {
    echo "Query " . ($i + 1) . " (" . $q['time'] . "ms): " . substr($q['query'], 0, 100) . "...\n";
}
