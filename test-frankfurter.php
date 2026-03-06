<?php
// Direct test of Frankfurter API

echo "Testing Frankfurter API...\n\n";

// Test 1: cURL
echo "=== Test 1: cURL Request ===\n";
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.frankfurter.dev/latest?base=USD&symbols=XAU,XAG,XPT,XPD',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => array('Accept: application/json'),
    CURLOPT_VERBOSE => true,
    CURLOPT_STDERR => fopen('php://stderr', 'w')
));

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Status: $status\n";
if ($error) {
    echo "cURL Error: $error\n";
} else {
    echo "Response:\n" . $response . "\n";
    $data = json_decode($response, true);
    if ($data && isset($data['rates'])) {
        echo "\n✅ Frankfurter API works!\n";
        echo "Rates received:\n";
        foreach ($data['rates'] as $code => $rate) {
            $price = 1 / $rate;
            echo "  $code: 1 USD = " . number_format($rate, 8) . " | 1 oz = \$" . number_format($price, 2) . "\n";
        }
    }
}

// Test 2: Laravel Http Facade
echo "\n\n=== Test 2: Laravel Http Facade ===\n";
try {
    $response = \Illuminate\Support\Facades\Http::timeout(10)
        ->acceptJson()
        ->get('https://api.frankfurter.dev/latest', [
            'base' => 'USD',
            'symbols' => 'XAU,XAG,XPT,XPD'
        ]);
    
    echo "Status: " . $response->status() . "\n";
    echo "Response:\n" . $response->body() . "\n";
    
    if ($response->ok()) {
        echo "\n✅ Laravel Http works!\n";
        $data = $response->json();
        if (isset($data['rates'])) {
            foreach ($data['rates'] as $code => $metal) {
                $price = 1 / $metal;
                echo "  $code: \$" . number_format($price, 2) . "/oz\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

// Test 3: file_get_contents with stream context
echo "\n\n=== Test 3: file_get_contents with stream context ===\n";
$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);

$json = @file_get_contents('https://api.frankfurter.dev/latest?base=USD&symbols=XAU,XAG,XPT,XPD', false, $context);
if ($json) {
    echo "✅ file_get_contents works!\n";
    echo "Response:\n" . $json . "\n";
} else {
    echo "❌ file_get_contents failed\n";
}

echo "\n=== Test Complete ===\n";
