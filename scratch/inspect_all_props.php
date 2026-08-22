<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$props = App\Models\Property::with('propertyType')->get();
foreach ($props as $p) {
    echo "Name: {$p->name} | Type: " . ($p->propertyType->name ?? 'None') . " (Slug: " . ($p->propertyType->slug ?? '') . ") | Status: {$p->status} | Verif: {$p->verification_status} | Active: {$p->is_active}\n";
}
