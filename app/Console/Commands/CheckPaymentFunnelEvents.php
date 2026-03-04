<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentFunnelEvent;
use Carbon\Carbon;

class CheckPaymentFunnelEvents extends Command
{
    protected $signature = 'debug:payment-funnel-events';
    protected $description = 'Debug payment funnel events in database';

    public function handle()
    {
        $this->info('🔍 Checking Payment Funnel Events...');
        
        $events = PaymentFunnelEvent::all();
        
        $this->info("Total events: " . $events->count());
        
        foreach ($events as $event) {
            $this->line("ID: {$event->id} | Website: {$event->website_id} | Step: {$event->funnel_step} | Amount: $" . number_format($event->amount / 100, 2) . " | Date: {$event->created_at}");
        }
        
        // Check specifically for payment_completed events
        $completedEvents = PaymentFunnelEvent::where('funnel_step', 'payment_completed')->get();
        $this->info("\nPayment Completed Events: " . $completedEvents->count());
        
        // Check events in last 7 days
        $recentEvents = PaymentFunnelEvent::where('created_at', '>=', Carbon::now()->subDays(7))->get();
        $this->info("Events in last 7 days: " . $recentEvents->count());
        
        return 0;
    }
}