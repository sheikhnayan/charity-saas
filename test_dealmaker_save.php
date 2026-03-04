<?php

// Include the Laravel autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Boot the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DealmakerConfig;

echo "Testing DealMaker Config Save...\n";

try {
    // Get current instance
    $config = DealmakerConfig::getInstance();
    echo "Current config ID: " . $config->id . "\n";
    
    // Test data
    $testData = [
        'meta_title' => 'Test Save - ' . date('Y-m-d H:i:s'),
        'hero_title' => 'Hero Test - ' . date('Y-m-d H:i:s'),
        'hero_subtitle' => 'Testing subtitle save functionality'
    ];
    
    echo "Attempting to save: " . json_encode($testData) . "\n";
    
    // Try to save
    $result = $config->update($testData);
    
    echo "Save result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    
    // Verify the data was saved
    $fresh = $config->fresh();
    echo "New meta_title: " . $fresh->meta_title . "\n";
    echo "New hero_title: " . $fresh->hero_title . "\n";
    echo "New hero_subtitle: " . $fresh->hero_subtitle . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}