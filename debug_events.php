<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Payment Funnel Events Debug ===\n\n";

$total = DB::table('payment_funnel_events')->count();
echo "Total events: $total\n";

$withLocation = DB::table('payment_funnel_events')->whereNotNull('country_code')->count();
echo "Events with location: $withLocation\n\n";

echo "Funnel steps breakdown:\n";
$steps = DB::table('payment_funnel_events')
    ->select('funnel_step', DB::raw('COUNT(*) as count'))
    ->groupBy('funnel_step')
    ->get();

foreach ($steps as $step) {
    echo "  - {$step->funnel_step}: {$step->count}\n";
}

echo "\nRecent 10 events:\n";
$recent = DB::table('payment_funnel_events')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get(['id', 'funnel_step', 'form_type', 'country', 'state', 'amount', 'created_at']);

foreach ($recent as $event) {
    $location = $event->country ? ($event->state ? "{$event->state}, {$event->country}" : $event->country) : 'No location';
    $amount = $event->amount ? "\${$event->amount}" : 'N/A';
    echo "  ID: {$event->id} | {$event->funnel_step} | {$event->form_type} | {$amount} | {$location} | {$event->created_at}\n";
}

echo "\n=== API Test ===\n";
$websiteId = DB::table('payment_funnel_events')->first()->website_id ?? null;
if ($websiteId) {
    echo "Testing with website_id: $websiteId\n";
    
    // Test location API
    $locations = DB::table('payment_funnel_events')
        ->where('website_id', $websiteId)
        ->whereNotNull('country_code')
        ->select('country_code', 'country', 'state', DB::raw('COUNT(DISTINCT COALESCE(visitor_id, session_id)) as visitors'))
        ->groupBy('country_code', 'country', 'state')
        ->limit(5)
        ->get();
    
    echo "\nLocation data (top 5):\n";
    foreach ($locations as $loc) {
        echo "  - {$loc->state}, {$loc->country} ({$loc->country_code}): {$loc->visitors} visitors\n";
    }
}

echo "\nDone!\n";
