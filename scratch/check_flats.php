<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$flats = App\Models\Property::where('status', 'active')->where('verification_status', 'verified')->where('is_active', 1)
    ->whereHas('propertyType', fn($q) => $q->whereIn('slug', ['flat', 'apartment', 'house', 'villa', 'flat-apartment']))->get();
echo "Flats count: " . $flats->count() . "\n";
foreach ($flats as $f) {
    echo "ID: {$f->id} | Name: {$f->name} | DeletedAt: {$f->deleted_at}\n";
}
