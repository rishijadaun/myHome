<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/broker/bookings', 'GET')
);

echo "Broker bookings GET status: " . $response->getStatusCode() . "\n";

// Check if CSRF token meta is present in compiled views
$html = file_get_contents('http://127.0.0.1:8000/broker/login');
$hasCsrf = strpos($html, 'name="csrf-token"') !== false;
echo "Broker login has csrf-token meta: " . ($hasCsrf ? "YES" : "NO") . "\n";
