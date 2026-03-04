<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Website;
use App\Models\PaymentFunnelEvent;

class CheckWebsiteId extends Command
{
    protected $signature = 'check:website-id';
    protected $description = 'Check website ID for pickpockets.com and payment funnel events';

    public function handle()
    {
        $this->info('Checking website data...');
        
        // Check pickpockets.com website
        $website = Website::where('domain', 'pickpockets.com')->first();
        if ($website) {
            $this->info("pickpockets.com found with ID: {$website->id}");
        } else {
            $this->warn("pickpockets.com NOT found in websites table");
        }
        
        // Check all websites
        $this->info("\nAll websites:");
        $websites = Website::all();
        foreach ($websites as $site) {
            $this->line("ID: {$site->id} - Domain: {$site->domain}");
        }
        
        // Check payment funnel events
        $eventCount = PaymentFunnelEvent::count();
        $this->info("\nPayment funnel events count: $eventCount");
        
        if ($eventCount > 0) {
            $this->info("Recent payment funnel events:");
            $events = PaymentFunnelEvent::orderBy('created_at', 'desc')->take(5)->get();
            foreach ($events as $event) {
                $this->line("ID: {$event->id} - Website ID: {$event->website_id} - Event: {$event->event} - Type: {$event->form_type} - Amount: {$event->amount}");
            }
        }
        
        return 0;
    }
}