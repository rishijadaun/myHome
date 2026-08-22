<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$types = App\Models\PropertyType::all();
foreach ($types as $t) {
    $count = App\Models\Property::where('property_type_id', $t->id)->count();
    $activeCount = App\Models\Property::where('property_type_id', $t->id)->where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)->count();
    echo "ID: {$t->id} | Name: {$t->name} | Slug: {$t->slug} | Total Props: {$count} | Active Props: {$activeCount}\n";
}

$allPropsCount = App\Models\Property::count();
echo "Total Properties in DB: {$allPropsCount}\n";
