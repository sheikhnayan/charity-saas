<?php

use Illuminate\Support\Facades\Route;
use App\Models\Website;
use App\Models\PaymentFunnelEvent;

// Simple database connectivity test
Route::get('/test-db-connection', function () {
    try {
        // Test database connection
        $websiteCount = Website::count();
        $funnelEventCount = PaymentFunnelEvent::count();
        
        return response()->json([
            'success' => true,
            'message' => 'Database connection is working!',
            'stats' => [
                'websites_count' => $websiteCount,
                'funnel_events_count' => $funnelEventCount
            ],
            'tables_exist' => [
                'websites' => \Schema::hasTable('websites'),
                'payment_funnel_events' => \Schema::hasTable('payment_funnel_events')
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});