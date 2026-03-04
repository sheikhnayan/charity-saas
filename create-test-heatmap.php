<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$website = App\Models\Website::first();

if (!$website) {
    echo "No website found\n";
    exit(1);
}

echo "Creating test heatmap data for website: {$website->name}\n";

// Create click events
for ($i = 0; $i < 20; $i++) {
    App\Models\HeatmapData::create([
        'website_id' => $website->id,
        'page_url' => 'http://localhost/page/home',
        'page_path' => '/page/home',
        'event_type' => 'click',
        'x' => rand(100, 1200),
        'y' => rand(100, 800),
        'viewport_width' => 1920,
        'viewport_height' => 1080,
        'element_selector' => 'button.test-btn-' . rand(1, 3),
        'device_type' => 'desktop',
        'session_id' => 'test_session_' . $i,
        'visitor_id' => 'test_visitor_' . rand(1, 5)
    ]);
}

echo "Created 20 click events\n";

// Create move events
for ($i = 0; $i < 30; $i++) {
    App\Models\HeatmapData::create([
        'website_id' => $website->id,
        'page_url' => 'http://localhost/page/home',
        'page_path' => '/page/home',
        'event_type' => 'move',
        'x' => rand(50, 1400),
        'y' => rand(50, 900),
        'viewport_width' => 1920,
        'viewport_height' => 1080,
        'duration_ms' => rand(100, 5000),
        'device_type' => 'desktop',
        'session_id' => 'test_session_' . rand(0, 9),
        'visitor_id' => 'test_visitor_' . rand(1, 5)
    ]);
}

echo "Created 30 move events\n";

// Create scroll events
for ($i = 0; $i < 15; $i++) {
    App\Models\HeatmapData::create([
        'website_id' => $website->id,
        'page_url' => 'http://localhost/page/home',
        'page_path' => '/page/home',
        'event_type' => 'scroll',
        'scroll_depth' => rand(10, 100),
        'y' => rand(0, 2000),
        'viewport_width' => 1920,
        'viewport_height' => 1080,
        'max_scroll' => 2500,
        'device_type' => 'desktop',
        'session_id' => 'test_session_' . rand(0, 9),
        'visitor_id' => 'test_visitor_' . rand(1, 5)
    ]);
}

echo "Created 15 scroll events\n";
echo "Total heatmap data: " . App\Models\HeatmapData::count() . "\n";
