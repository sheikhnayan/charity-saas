<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AnalyticsEvent;
use DB;

class DebugAnalytics extends Command
{
    protected $signature = 'debug:analytics';
    protected $description = 'Debug analytics tracking issues';

    public function handle()
    {
        $this->info('=== Analytics Debug ===');
        
        // Check table structure
        $this->info('1. Checking analytics_events table structure:');
        $columns = DB::select('DESCRIBE analytics_events');
        foreach($columns as $col) {
            $this->line("   {$col->Field} ({$col->Type})");
        }
        
        // Check latest events
        $this->info('2. Latest 3 analytics events:');
        $events = AnalyticsEvent::latest()->take(3)->get();
        if ($events->count() > 0) {
            foreach($events as $event) {
                $this->line("   Event ID: {$event->id}");
                $this->line("   Event Type: {$event->event_type}");
                $this->line("   URL: {$event->url}");
                $this->line("   UTM Source: {$event->utm_source}");
                $this->line("   Device Type: {$event->device_type}");
                $this->line("   Browser: {$event->browser}");
                $this->line("   Created: {$event->created_at}");
                $this->line("   ---");
            }
        } else {
            $this->error('   No analytics events found!');
        }
        
        // Create test event
        $this->info('3. Creating test event:');
        try {
            $event = new AnalyticsEvent();
            $event->event_type = 'debug_test';
            $event->website_id = 8; // Using fundably.org
            $event->session_id = 'debug-' . time();
            $event->url = 'https://fundably.org/debug';
            $event->user_agent = 'Debug Agent';
            $event->ip_address = '127.0.0.1';
            $event->method = 'GET';
            $event->utm_source = 'debug_source';
            $event->utm_medium = 'debug_medium';
            $event->utm_campaign = 'debug_campaign';
            $event->device_type = 'desktop';
            $event->browser = 'Chrome';
            $event->os = 'Windows';
            $event->platform = 'Windows';
            $event->save();
            
            $this->info("   Test event created with ID: {$event->id}");
            
            // Check if data was saved correctly
            $saved = AnalyticsEvent::find($event->id);
            $this->line("   Saved URL: {$saved->url}");
            $this->line("   Saved UTM Source: {$saved->utm_source}");
            $this->line("   Saved Device Type: {$saved->device_type}");
            $this->line("   Saved Browser: {$saved->browser}");
            
        } catch (\Exception $e) {
            $this->error("   Error: " . $e->getMessage());
        }
        
        return 0;
    }
}