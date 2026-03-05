<?php
// Test the metals API with debug output
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Call the route directly
$response = app()->make('router')->dispatch(
    \Illuminate\Http\Request::create('/api/metals/prices', 'GET')
);

$content = $response->getContent();
$data = json_decode($content, true);

echo "=== METALS API DEBUG TEST ===\n\n";
echo "Status: " . $response->getStatusCode() . "\n\n";

if ($data) {
    echo "Source: " . ($data['source'] ?? 'unknown') . "\n";
    echo "Success: " . ($data['success'] ? 'YES' : 'NO') . "\n\n";
    
    if (isset($data['prices_per_ounce_usd'])) {
        echo "Prices per ounce (USD):\n";
        foreach ($data['prices_per_ounce_usd'] as $metal => $price) {
            echo "  - " . ucfirst($metal) . ": $" . ($price ?? 'N/A') . "\n";
        }
        echo "\n";
    }
    
    if (isset($data['prices_per_gram_usd'])) {
        echo "Prices per gram (USD):\n";
        foreach ($data['prices_per_gram_usd'] as $metal => $price) {
            echo "  - " . ucfirst($metal) . ": $" . ($price ?? 'N/A') . "\n";
        }
        echo "\n";
    }
    
    if (isset($data['debug']) && is_array($data['debug'])) {
        echo "=== DEBUG LOG ===\n";
        foreach ($data['debug'] as $log) {
            echo $log . "\n";
        }
        echo "\n";
    }
} else {
    echo "Failed to parse response\n";
    echo $content . "\n";
}
