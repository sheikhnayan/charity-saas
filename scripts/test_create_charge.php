<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

// Params to test - change as needed
$params = [
    'type' => 'donation',
    'reference_id' => 62,
    'amount' => '10.00',
    'currency' => 'USD',
    'website_id' => null,
];

$request = Request::create('/coinbase/create-charge', 'POST', $params);

// Resolve controller and call method
$controller = app()->make(\App\Http\Controllers\CoinbaseController::class);
$response = null;

// For local testing disable SSL verification if not already configured
if (config('coinbase.verify_ssl') === null) {
    config(['coinbase.verify_ssl' => false]);
} else {
    // Force disable for test script to avoid local cURL cert issues
    config(['coinbase.verify_ssl' => false]);
}

$response = $controller->createCharge($request);

if (is_object($response) && method_exists($response, 'getContent')) {
    echo $response->getContent() . PHP_EOL;
} else {
    var_dump($response);
}
