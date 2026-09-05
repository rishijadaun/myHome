<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/pgs', 'GET');
$app->instance('request', $request);

$adminUser = App\Models\User::whereHas('roles', function($q) {
    $q->where('slug', 'super_admin')->orWhere('slug', 'admin');
})->first();

Auth::guard('web')->setUser($adminUser);

$controller = new App\Http\Controllers\Admin\AdminPropertyController();
$view = $controller->index($request);
$rendered = $view->render();

echo "Rendered Admin PGs View Length: " . strlen($rendered) . " bytes\n";

if (strpos($rendered, 'Rows per page:') !== false) {
    echo " [PASS] Table pagination 'Rows per page:' selector found in rendered HTML.\n";
} else {
    echo " [FAIL] 'Rows per page:' selector not found.\n";
}

if (strpos($rendered, 'Showing') !== false) {
    echo " [PASS] Table pagination 'Showing ... properties' summary found in rendered HTML.\n";
} else {
    echo " [FAIL] 'Showing ... properties' summary not found.\n";
}

if (strpos($rendered, 'changePerPage') !== false) {
    echo " [PASS] JS changePerPage handler found in rendered HTML.\n";
} else {
    echo " [FAIL] JS changePerPage handler not found.\n";
}
