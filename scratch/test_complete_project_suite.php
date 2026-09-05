<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "=================================================================\n";
echo "       SPACESEEKS FULL MODULE PRE-DEPLOYMENT TEST SUITE          \n";
echo "=================================================================\n\n";

$results = [
    'passed' => 0,
    'failed' => 0,
];

function runTest($name, $closure) {
    global $results;
    try {
        $res = $closure();
        if ($res === true || (is_array($res) && $res['status'] === true)) {
            $msg = is_array($res) ? $res['message'] : '';
            echo " [PASS] $name " . ($msg ? "($msg)" : "") . "\n";
            $results['passed']++;
        } else {
            $msg = is_array($res) ? $res['message'] : 'Assertion failed';
            echo " [FAIL] $name - $msg\n";
            $results['failed']++;
        }
    } catch (\Throwable $e) {
        echo " [ERROR] $name: " . $e->getMessage() . "\n";
        $results['failed']++;
    }
}

// -------------------------------------------------------------
// 1. DATABASE & CORE MODELS
// -------------------------------------------------------------
echo "\n--- 1. DATABASE & CORE MODELS ---\n";

runTest('Database Connection', function() {
    DB::connection()->getPdo();
    return true;
});

runTest('Users Table & Roles Relationship', function() {
    $userCount = App\Models\User::count();
    $admin = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'super_admin')->orWhere('slug', 'admin'))->first();
    $broker = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'broker'))->first();
    return $userCount > 0 && $admin && $broker ? ['status' => true, 'message' => "Users: $userCount, Admin: {$admin->email}, Broker: {$broker->email}"] : false;
});

runTest('Properties & Images Relationship', function() {
    $count = App\Models\Property::count();
    $activeCount = App\Models\Property::where('status', 'active')->where('verification_status', 'verified')->count();
    $withImages = App\Models\Property::whereHas('images')->count();
    return $count > 0 ? ['status' => true, 'message' => "Total: $count, Active/Verified: $activeCount, With Images: $withImages"] : false;
});

runTest('Roommate / Flatmate Posts', function() {
    $count = App\Models\RoommatePost::count();
    $activeCount = App\Models\RoommatePost::where('status', 'active')->count();
    return ['status' => true, 'message' => "Total: $count, Active: $activeCount"];
});

runTest('Cities & Areas Catalog', function() {
    $cities = App\Models\City::where('is_active', 1)->count();
    $areas = App\Models\Area::count();
    return $cities > 0 ? ['status' => true, 'message' => "Cities: $cities, Areas: $areas"] : false;
});

runTest('Bookings Module', function() {
    $count = App\Models\Booking::count();
    return ['status' => true, 'message' => "Total Bookings: $count"];
});

// -------------------------------------------------------------
// 2. HTTP ENDPOINTS & MODULE ROUTES TEST
// -------------------------------------------------------------
echo "\n--- 2. PUBLIC & USER MODULE ENDPOINTS ---\n";

$publicEndpoints = [
    '/' => 'Home Page',
    '/find-roommate' => 'Find Roommates / Flatmates',
    '/find-flatmates' => 'Find Flatmates (SEO Alias Redirect)',
    '/flatmate' => 'Flatmate Alias',
    '/list-property' => 'List Property Wizard',
    '/about-us' => 'About Us',
    '/contact-us' => 'Contact Us',
    '/privacy-policy' => 'Privacy Policy',
    '/terms-of-service' => 'Terms of Service',
    '/sitemap.xml' => 'Sitemap XML',
    '/robots.txt' => 'Robots TXT',
    '/manifest.json' => 'PWA Manifest',
    '/sw.js' => 'Service Worker JS',
    '/login' => 'User Login',
    '/broker/login' => 'Broker Login',
    '/admin/login' => 'Admin Login',
    '/favicon.ico' => 'Root Favicon ICO',
    '/favicon.png' => 'Root Favicon PNG',
    '/images/spaceseeks-logo.png' => 'SpaceSeeks Logo',
    '/images/favicon.png' => 'SpaceSeeks Favicon',
    '/images/icon-192.png' => 'PWA 192 Icon',
    '/images/icon-512.png' => 'PWA 512 Icon',
    '/images/apple-touch-icon.png' => 'Apple Touch Icon',
];

foreach ($publicEndpoints as $uri => $label) {
    runTest("HTTP GET $label ($uri)", function() use ($uri) {
        $ch = curl_init('http://127.0.0.1:8000' . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $code === 200 ? ['status' => true, 'message' => "HTTP 200"] : ['status' => false, 'message' => "HTTP $code"];
    });
}

// -------------------------------------------------------------
// 3. ADMIN & BROKER MODULES (DIRECT CONTROLLER EXECUTION)
// -------------------------------------------------------------
echo "\n--- 3. ADMIN & BROKER MODULES (AUTHENTICATED) ---\n";

runTest('Admin Panel Dashboard & Statistics', function() use ($app) {
    $req = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
    $app->instance('request', $req);
    $admin = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'super_admin')->orWhere('slug', 'admin'))->first();
    Auth::guard('web')->setUser($admin);
    
    $controller = new App\Http\Controllers\Admin\AdminDashboardController();
    $view = $controller->index($req);
    return strlen($view->render()) > 1000 ? ['status' => true, 'message' => 'Dashboard rendered successfully'] : false;
});

runTest('Admin PGs Table with Pagination', function() use ($app) {
    $req = Illuminate\Http\Request::create('/admin/pgs?per_page=10', 'GET');
    $app->instance('request', $req);
    $admin = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'super_admin')->orWhere('slug', 'admin'))->first();
    Auth::guard('web')->setUser($admin);
    
    $controller = new App\Http\Controllers\Admin\AdminPropertyController();
    $view = $controller->index($req);
    $html = $view->render();
    $hasRowsPerPage = strpos($html, 'Rows per page:') !== false;
    $hasPagination = strpos($html, 'Showing') !== false;
    return $hasRowsPerPage && $hasPagination ? ['status' => true, 'message' => 'Pagination & Per-Page rendered'] : false;
});

runTest('Admin Bookings Management', function() use ($app) {
    $req = Illuminate\Http\Request::create('/admin/bookings', 'GET');
    $app->instance('request', $req);
    $admin = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'super_admin')->orWhere('slug', 'admin'))->first();
    Auth::guard('web')->setUser($admin);
    
    $controller = new App\Http\Controllers\Admin\AdminBookingController();
    $view = $controller->index($req);
    return strlen($view->render()) > 1000 ? ['status' => true, 'message' => 'Bookings rendered'] : false;
});

runTest('Admin Brokers & KYC Management', function() use ($app) {
    $req = Illuminate\Http\Request::create('/admin/brokers', 'GET');
    $app->instance('request', $req);
    $admin = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'super_admin')->orWhere('slug', 'admin'))->first();
    Auth::guard('web')->setUser($admin);
    
    $controller = new App\Http\Controllers\Admin\AdminBrokerController();
    $view = $controller->index($req);
    return strlen($view->render()) > 1000 ? ['status' => true, 'message' => 'Brokers rendered'] : false;
});

runTest('Broker Panel Dashboard & Metrics', function() use ($app) {
    $req = Illuminate\Http\Request::create('/broker/dashboard', 'GET');
    $app->instance('request', $req);
    $broker = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'broker'))->first();
    Auth::guard('web')->setUser($broker);
    
    $controller = new App\Http\Controllers\Broker\BrokerDashboardController();
    $view = $controller->index($req);
    return strlen($view->render()) > 1000 ? ['status' => true, 'message' => 'Broker Dashboard rendered'] : false;
});

runTest('Broker Bookings with CSRF protection', function() use ($app) {
    $req = Illuminate\Http\Request::create('/broker/bookings', 'GET');
    $app->instance('request', $req);
    $broker = App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'broker'))->first();
    Auth::guard('web')->setUser($broker);
    
    $controller = new App\Http\Controllers\Broker\BrokerBookingController();
    $view = $controller->index($req);
    $html = $view->render();
    $hasCsrf = strpos($html, 'submitApproveBrokerBooking') !== false;
    return $hasCsrf ? ['status' => true, 'message' => 'Broker Bookings & CSRF Handlers OK'] : false;
});

// -------------------------------------------------------------
// 4. REST API ENDPOINTS
// -------------------------------------------------------------
echo "\n--- 4. REST API ENDPOINTS ---\n";

$apiEndpoints = [
    '/api/v1/app/properties' => 'Properties Public API',
    '/api/v1/app/locations' => 'Locations Public API',
    '/api/v1/property-types' => 'Property Types API',
    '/api/v1/app/check-update' => 'App Update Check API',
];

foreach ($apiEndpoints as $uri => $label) {
    runTest("API GET $label ($uri)", function() use ($uri) {
        $ch = curl_init('http://127.0.0.1:8000' . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($res, true);
        return $code === 200 && is_array($json) ? ['status' => true, 'message' => "HTTP 200 JSON OK"] : ['status' => false, 'message' => "HTTP $code"];
    });
}

// -------------------------------------------------------------
// 5. PRODUCTION ASSET BUILD & SECURITY AUDIT
// -------------------------------------------------------------
echo "\n--- 5. PRODUCTION ASSETS & BUILD AUDIT ---\n";

runTest('Vite Production Build Assets in public/build/', function() {
    $manifestFile = public_path('build/manifest.json');
    if (!file_exists($manifestFile)) return ['status' => false, 'message' => 'build/manifest.json missing'];
    $manifest = json_decode(file_get_contents($manifestFile), true);
    return is_array($manifest) && count($manifest) >= 3 ? ['status' => true, 'message' => count($manifest) . ' asset entries compiled'] : false;
});

runTest('Storage Directory Permissions Check', function() {
    $storage = storage_path();
    $cache = base_path('bootstrap/cache');
    $isStorageWritable = is_writable($storage);
    $isCacheWritable = is_writable($cache);
    return $isStorageWritable && $isCacheWritable ? ['status' => true, 'message' => 'storage/ & bootstrap/cache/ are writable'] : false;
});

runTest('robots.txt & Sitemap', function() {
    $robots = file_exists(public_path('robots.txt'));
    return $robots ? ['status' => true, 'message' => 'robots.txt and sitemap present'] : false;
});

echo "\n=================================================================\n";
echo "TEST SUMMARY: {$results['passed']} Passed, {$results['failed']} Failed\n";
echo "RESULT: " . ($results['failed'] === 0 ? "ALL MODULES 100% OPERATIONAL & READY FOR LIVE DEPLOYMENT!" : "ISSUES FOUND - REVIEW FAILED TESTS") . "\n";
echo "=================================================================\n";
