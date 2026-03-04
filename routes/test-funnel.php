<?php

use Illuminate\Support\Facades\Route;
use App\Services\PaymentFunnelService;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;

// Test route for payment funnel tracking
Route::get('/test-funnel-tracking', function () {
    try {
        // Create a test website or use existing one for testing
        $website = Website::first();
        if (!$website) {
            return response()->json([
                'success' => false,
                'error' => 'No websites found in database. Please create a website first.',
                'help' => 'You can create a website through the admin panel.'
            ]);
        }
        
        // Test direct model creation to bypass service issues
        $testEvent = PaymentFunnelEvent::create([
            'website_id' => $website->id,
            'session_id' => 'test-session-' . time(),
            'funnel_step' => 'form_view',
            'form_type' => 'general',
            'completed_at' => now(),
            'device_type' => 'desktop',
            'browser' => 'test-browser',
            'ip_address' => '127.0.0.1'
        ]);
        
        // Get recent events to verify database connectivity
        $recentEvents = PaymentFunnelEvent::latest()->take(10)->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment funnel tracking database is working!',
            'website_info' => [
                'id' => $website->id,
                'name' => $website->name,
                'domain' => $website->domain
            ],
            'test_event_created' => [
                'id' => $testEvent->id,
                'funnel_step' => $testEvent->funnel_step,
                'form_type' => $testEvent->form_type,
                'created_at' => $testEvent->created_at
            ],
            'recent_events_count' => $recentEvents->count(),
            'recent_events' => $recentEvents->map(function($event) {
                return [
                    'id' => $event->id,
                    'funnel_step' => $event->funnel_step,
                    'form_type' => $event->form_type,
                    'amount' => $event->amount,
                    'website_id' => $event->website_id,
                    'created_at' => $event->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});