<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Sample 1x1 red pixel PNG base64
$base64Image = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';

$data = [
    'website_id' => 8,
    'page_url' => 'http://localhost/test',
    'page_path' => '/test',
    'screenshot_data' => 'data:image/png;base64,' . $base64Image,
    'viewport_width' => 1920,
    'viewport_height' => 1080,
    'device_type' => 'desktop'
];

$service = $app->make(App\Services\HeatmapService::class);
$controller = new App\Http\Controllers\HeatmapController($service);
$request = Illuminate\Http\Request::create('/api/heatmap/screenshot/capture', 'POST', $data);

try {
    $response = $controller->captureScreenshot($request);
    echo "Response: " . $response->getContent() . "\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    
    // Check logs
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        $screenshotLogs = array_filter(explode("\n", $logs), function($line) {
            return stripos($line, 'Screenshot') !== false;
        });
        echo "\n=== Recent Screenshot Logs ===\n";
        echo implode("\n", array_slice($screenshotLogs, -10));
        echo "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

// Check if screenshot was saved
$count = App\Models\PageScreenshot::where('website_id', 8)->where('page_path', '/test')->count();
echo "Screenshots in database: $count\n";
