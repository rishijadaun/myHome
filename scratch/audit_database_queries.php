<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Property;
use App\Models\City;
use App\Models\Area;
use App\Models\PropertyType;

echo "========================================================\n";
echo "       1. DATABASE SCHEMA & INDEXES AUDIT\n";
echo "========================================================\n\n";

$tables = ['properties', 'cities', 'areas', 'property_types', 'property_images', 'room_configurations', 'amenities', 'property_amenities', 'users'];

foreach ($tables as $table) {
    if (!Schema::hasTable($table)) {
        echo "⚠️ Table '{$table}' does NOT exist!\n";
        continue;
    }
    $indexes = DB::select("SHOW INDEX FROM `{$table}`");
    $indexedCols = array_unique(array_map(fn($idx) => $idx->Column_name, $indexes));
    echo "📋 Table: `{$table}` (Indexed Columns: " . implode(', ', $indexedCols) . ")\n";
}

echo "\n========================================================\n";
echo "       2. QUERY PROFILING (HOME, SEARCH, DETAIL, BROKER)\n";
echo "========================================================\n\n";

DB::enableQueryLog();

// Test 1: Home page featured & top properties
DB::flushQueryLog();
echo "[Test 1: Home Page Properties Query]\n";
$homeProperties = Property::with([
    'city', 'area', 'propertyType', 'primaryImage', 'images', 'room_configurations'
])
->where('is_active', 1)
->whereIn('status', ['active', 'verified'])
->orderByDesc('is_featured')
->orderByDesc('created_at')
->limit(12)
->get();

$homeQueries = DB::getQueryLog();
echo "  -> Retrieved " . count($homeProperties) . " properties in " . count($homeQueries) . " queries.\n";
foreach ($homeQueries as $i => $q) {
    echo "     Query " . ($i + 1) . " (" . $q['time'] . "ms): " . substr($q['query'], 0, 120) . "...\n";
}

// Test 2: Search Listing Query
DB::flushQueryLog();
echo "\n[Test 2: Search Page Query]\n";
$searchProps = Property::with(['city', 'area', 'propertyType', 'primaryImage', 'amenities'])
    ->where('is_active', 1)
    ->where('status', 'active')
    ->when('noida', fn($q) => $q->whereHas('city', fn($c) => $c->where('name', 'like', '%noida%')))
    ->limit(20)
    ->get();

$searchQueries = DB::getQueryLog();
echo "  -> Retrieved " . count($searchProps) . " search results in " . count($searchQueries) . " queries.\n";

// Test 3: Detail Page Query
DB::flushQueryLog();
echo "\n[Test 3: Detail Page Query]\n";
$sampleProp = Property::first();
if ($sampleProp) {
    $detailProp = Property::with([
        'city', 'area', 'propertyType', 'images', 'amenities', 'rules', 'room_configurations', 'broker.profile', 'reviews.user'
    ])->where('id', $sampleProp->id)->first();
    $detailQueries = DB::getQueryLog();
    echo "  -> Retrieved property detail in " . count($detailQueries) . " queries.\n";
}

// Test 4: Check for Missing Foreign Keys or Null Relationships
echo "\n========================================================\n";
echo "       3. DATA INTEGRITY & ORPHAN AUDIT\n";
echo "========================================================\n\n";

$orphanedPropsNoCity = Property::whereNotIn('city_id', City::pluck('id'))->count();
echo "  - Properties with invalid city_id: {$orphanedPropsNoCity}\n";

$propsNoCategory = Property::whereNull('property_category')->count();
echo "  - Properties with NULL property_category: {$propsNoCategory}\n";

$propsNoAdType = Property::whereNull('ad_type')->count();
echo "  - Properties with NULL ad_type: {$propsNoAdType}\n";

$inactiveImages = DB::table('property_images')->whereNotIn('property_id', Property::pluck('id'))->count();
echo "  - Orphaned property images: {$inactiveImages}\n";

$inactiveRooms = DB::table('rooms')->whereNotIn('floor_id', DB::table('floors')->pluck('id'))->count();
echo "  - Orphaned rooms: {$inactiveRooms}\n";

echo "\n=== QUERY AUDIT COMPLETE ===\n";
