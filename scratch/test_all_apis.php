<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "========================================================\n";
echo "       STAYNEST API ENDPOINT COMPREHENSIVE TEST         \n";
echo "========================================================\n\n";

$results = [];

function makeRequest($kernel, $method, $uri, $data = [], $headers = []) {
    $server = [
        'HTTP_ACCEPT' => 'application/json',
        'HTTP_USER_AGENT' => 'StayNestApiTester/1.0',
    ];
    foreach ($headers as $key => $val) {
        $server['HTTP_' . strtoupper(str_replace('-', '_', $key))] = $val;
    }

    $request = Illuminate\Http\Request::create($uri, $method, $data, [], [], $server);
    if (!empty($data) && $method !== 'GET') {
        $request->headers->set('Content-Type', 'application/json');
        $request->initialize($request->query->all(), $request->request->all(), $request->attributes->all(), $request->cookies->all(), $request->files->all(), $server, json_encode($data));
    }

    \Illuminate\Support\Facades\Auth::forgetGuards();
    \Illuminate\Support\Facades\Auth::setDefaultDriver('sanctum');

    $start = microtime(true);
    $response = $kernel->handle($request);
    $duration = round((microtime(true) - $start) * 1000, 1);
    $kernel->terminate($request, $response);

    return [
        'status' => $response->getStatusCode(),
        'duration_ms' => $duration,
        'body' => $response->getContent(),
        'json' => json_decode($response->getContent(), true),
    ];
}

// 1. App discovery endpoints
$tests = [
    ['GET', '/api/v1/app/check-update', []],
    ['GET', '/api/v1/app/properties', ['limit' => 5]],
    ['GET', '/api/v1/app/locations', []],
    ['GET', '/api/v1/property-types', []],
    ['POST', '/api/v1/ai/search', ['query' => 'boys pg in noida under 8000']],
    ['POST', '/api/v1/contact', ['name' => 'API Test User', 'email' => 'apitest@example.com', 'phone' => '9876543210', 'message' => 'API test message']],
];

echo "1. Testing Public & App Discovery APIs...\n";
foreach ($tests as $t) {
    [$method, $uri, $params] = $t;
    $res = makeRequest($kernel, $method, $uri, $params);
    $isOk = $res['status'] >= 200 && $res['status'] < 300;
    $statusText = $isOk ? "✅ PASS [{$res['status']}]" : "❌ FAIL [{$res['status']}]";
    echo sprintf("  %-6s %-32s -> %s (%sms)\n", $method, $uri, $statusText, $res['duration_ms']);
    if (!$isOk) {
        echo "     Error Body: " . substr($res['body'], 0, 150) . "\n";
    }
}

// 2. Auth APIs Test
echo "\n2. Testing Authentication APIs...\n";
$user = \App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'user'))->first();
if (!$user) {
    $user = \App\Models\User::first();
}

$token = null;
if ($user) {
    $token = $user->createToken('api_test_token')->plainTextToken;
    echo "  Authenticated as User: {$user->email} (ID: {$user->id})\n";
}

$authHeaders = $token ? ['Authorization' => 'Bearer ' . $token] : [];

$authTests = [
    ['GET', '/api/v1/auth/me', [], $authHeaders],
    ['GET', '/api/v1/user/dashboard', [], $authHeaders],
    ['GET', '/api/v1/user/profile', [], $authHeaders],
    ['GET', '/api/v1/user/bookings', [], $authHeaders],
    ['GET', '/api/v1/user/visits', [], $authHeaders],
    ['POST', '/api/v1/user/saved/toggle', ['property_id' => \App\Models\Property::first()?->id ?? 'dummy'], $authHeaders],
];

foreach ($authTests as $t) {
    [$method, $uri, $params, $hdrs] = $t;
    $res = makeRequest($kernel, $method, $uri, $params, $hdrs);
    $isOk = $res['status'] >= 200 && $res['status'] < 300;
    $statusText = $isOk ? "✅ PASS [{$res['status']}]" : "❌ FAIL [{$res['status']}]";
    echo sprintf("  %-6s %-32s -> %s (%sms)\n", $method, $uri, $statusText, $res['duration_ms']);
    if (!$isOk) {
        echo "     Error Body: " . substr($res['body'], 0, 150) . "\n";
    }
}

// 3. Broker APIs Test
echo "\n3. Testing Broker Portal APIs...\n";
$broker = \App\Models\User::whereHas('roles', fn($q) => $q->where('slug', 'broker'))->first();
$brokerToken = $broker ? $broker->createToken('broker_test_token')->plainTextToken : null;
$brokerHeaders = $brokerToken ? ['Authorization' => 'Bearer ' . $brokerToken] : [];

if ($broker) {
    echo "  Authenticated as Broker: {$broker->email} (ID: {$broker->id})\n";
}

$brokerTests = [
    ['GET', '/api/v1/broker/dashboard', [], $brokerHeaders],
    ['GET', '/api/v1/broker/listings', [], $brokerHeaders],
    ['GET', '/api/v1/broker/bookings', [], $brokerHeaders],
    ['GET', '/api/v1/broker/visits', [], $brokerHeaders],
];

foreach ($brokerTests as $t) {
    [$method, $uri, $params, $hdrs] = $t;
    $res = makeRequest($kernel, $method, $uri, $params, $hdrs);
    $isOk = $res['status'] >= 200 && $res['status'] < 300;
    $statusText = $isOk ? "✅ PASS [{$res['status']}]" : "❌ FAIL [{$res['status']}]";
    echo sprintf("  %-6s %-32s -> %s (%sms)\n", $method, $uri, $statusText, $res['duration_ms']);
    if (!$isOk) {
        echo "     Error Body: " . substr($res['body'], 0, 150) . "\n";
    }
}

// 4. Admin APIs Test
echo "\n4. Testing Admin Portal APIs...\n";
$admin = \App\Models\User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin', 'super_admin']))->first();
$adminToken = $admin ? $admin->createToken('admin_test_token')->plainTextToken : null;
$adminHeaders = $adminToken ? ['Authorization' => 'Bearer ' . $adminToken] : [];

if ($admin) {
    echo "  Authenticated as Admin: {$admin->email} (ID: {$admin->id}, Status: '{$admin->status}', is_active: '{$admin->is_active}')\n";
    $pivotRoles = \DB::table('user_roles')->where('user_id', $admin->id)->get();
    echo "  user_roles rows in DB: " . json_encode($pivotRoles) . "\n";
    echo "  roles()->whereIn exists: " . ($admin->roles()->whereIn('slug', ['super_admin', 'admin'])->exists() ? 'true' : 'false') . "\n";
}

$adminTests = [
    ['GET', '/api/v1/admin/dashboard', [], $adminHeaders],
    ['GET', '/api/v1/admin/users', [], $adminHeaders],
    ['GET', '/api/v1/admin/properties', [], $adminHeaders],
];

foreach ($adminTests as $t) {
    [$method, $uri, $params, $hdrs] = $t;
    $res = makeRequest($kernel, $method, $uri, $params, $hdrs);
    $isOk = $res['status'] >= 200 && $res['status'] < 300;
    $statusText = $isOk ? "✅ PASS [{$res['status']}]" : "❌ FAIL [{$res['status']}]";
    echo sprintf("  %-6s %-32s -> %s (%sms)\n", $method, $uri, $statusText, $res['duration_ms']);
    if (!$isOk) {
        echo "     Error Body: " . substr($res['body'], 0, 150) . "\n";
    }
}

// 5. Property Details API
echo "\n5. Testing Property Details & Submission APIs...\n";
$firstProp = \App\Models\Property::first();
if ($firstProp) {
    $res = makeRequest($kernel, 'GET', '/api/v1/properties/details/' . $firstProp->id);
    $isOk = $res['status'] >= 200 && $res['status'] < 300;
    $statusText = $isOk ? "✅ PASS [{$res['status']}]" : "❌ FAIL [{$res['status']}]";
    echo sprintf("  %-6s %-32s -> %s (%sms)\n", 'GET', '/api/v1/properties/details/{id}', $statusText, $res['duration_ms']);
    if (!$isOk) {
        echo "     Error Body: " . substr($res['body'], 0, 150) . "\n";
    }
}

// 6. Web Frontend Pages Render Test
echo "\n6. Testing Core Web Pages (HTML Rendering)...\n";
$webTests = [
    ['GET', '/'],
    ['GET', '/search'],
    ['GET', '/explore-near-me'],
    ['GET', '/find-roommate'],
    ['GET', '/detail/' . ($firstProp->slug ?? '')],
];

foreach ($webTests as $wt) {
    [$method, $uri] = $wt;
    $res = makeRequest($kernel, $method, $uri, [], ['Accept' => 'text/html']);
    $isOk = $res['status'] >= 200 && $res['status'] < 300;
    $statusText = $isOk ? "✅ PASS [{$res['status']}]" : "❌ FAIL [{$res['status']}]";
    echo sprintf("  %-6s %-32s -> %s (%sms)\n", $method, $uri, $statusText, $res['duration_ms']);
    if (!$isOk) {
        echo "     Error Body: " . substr($res['body'], 0, 150) . "\n";
    }
}

echo "\n========================================================\n";
echo "                  API TESTING COMPLETE                  \n";
echo "========================================================\n";
