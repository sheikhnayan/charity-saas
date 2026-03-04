<?php

Route::get('/debug-analytics', function() {
    echo "<h1>Analytics Debug</h1>";
    
    // Check latest analytics event
    $latest = App\Models\AnalyticsEvent::latest()->first();
    if ($latest) {
        echo "<h2>Latest Analytics Event:</h2>";
        echo "<pre>";
        print_r($latest->toArray());
        echo "</pre>";
    } else {
        echo "<p>No analytics events found</p>";
    }
    
    // Test the specific queries that are failing
    $websiteId = 12; // pickpockets.com
    $startDate = now()->subDays(30);
    $endDate = now();
    
    echo "<h2>Device Breakdown Query Test:</h2>";
    $deviceBreakdown = App\Models\AnalyticsEvent::where('website_id', $websiteId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereNotNull('device_type')
        ->groupBy('device_type')
        ->selectRaw('device_type, count(*) as count')
        ->orderByDesc('count')
        ->get();
    echo "<pre>";
    print_r($deviceBreakdown->toArray());
    echo "</pre>";
    
    echo "<h2>Location Data Query Test:</h2>";
    $locationData = App\Models\AnalyticsEvent::whereNotNull('ip_address')
        ->where('website_id', $websiteId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('ip_address')
        ->selectRaw('ip_address as country, count(*) as count')
        ->orderByDesc('count')
        ->limit(10)
        ->get();
    echo "<pre>";
    print_r($locationData->toArray());
    echo "</pre>";
    
    echo "<h2>Recent Page Views Test:</h2>";
    $recentViews = App\Models\AnalyticsEvent::where('event_type', 'page_view')
        ->where('website_id', $websiteId)
        ->where('created_at', '>=', now()->subMinutes(60))
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();
    echo "<pre>";
    print_r($recentViews->toArray());
    echo "</pre>";
});