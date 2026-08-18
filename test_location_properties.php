 <?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$initReq = Illuminate\Http\Request::create('/', 'GET');
$kernel->handle($initReq);

use App\Models\City;
use App\Models\Property;

$properties = Property::where('status', 'active')
    ->where('verification_status', 'verified')
    ->with(['city', 'area', 'primaryImage', 'amenities', 'propertyType'])
    ->get();

echo "Active verified properties count: " . $properties->count() . PHP_EOL;
foreach ($properties as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Slug: {$p->slug} | City: " . ($p->city->name ?? 'N/A') . " | Lat: {$p->latitude} | Lng: {$p->longitude} | Rent: {$p->monthly_rent} | Gender: {$p->gender_preference}" . PHP_EOL;
}

$cities = City::with('areas')->get();
echo "Cities count: " . $cities->count() . PHP_EOL;
foreach ($cities as $c) {
    echo "City: {$c->name} (Lat: {$c->latitude}, Lng: {$c->longitude}) - Areas: " . $c->areas->count() . PHP_EOL;
}
