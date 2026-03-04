<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentFunnelEvent;

class CheckFunnelEvents extends Command
{
    protected $signature = 'debug:funnel-events';
    protected $description = 'Check payment funnel events in database';

    public function handle()
    {
        $this->info('🔍 Checking Payment Funnel Events...');
        
        $websiteId = 12;
        
        $totalEvents = PaymentFunnelEvent::where('website_id', $websiteId)->count();
        $this->info("Total PaymentFunnelEvents for website {$websiteId}: {$totalEvents}");
        
        if ($totalEvents > 0) {
            $this->info('Funnel step breakdown:');
            $breakdown = PaymentFunnelEvent::where('website_id', $websiteId)
                ->groupBy('funnel_step')
                ->selectRaw('funnel_step, COUNT(*) as count')
                ->orderByDesc('count')
                ->get();
                
            foreach ($breakdown as $step) {
                $this->line("  {$step->funnel_step}: {$step->count}");
            }
            
            $this->info('Recent funnel events:');
            $recent = PaymentFunnelEvent::where('website_id', $websiteId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(['funnel_step', 'form_type', 'amount', 'created_at']);
                
            foreach ($recent as $event) {
                $amount = $event->amount ? '$' . number_format($event->amount / 100, 2) : 'N/A';
                $this->line("  {$event->created_at}: {$event->funnel_step} ({$event->form_type}) - {$amount}");
            }
        } else {
            $this->warn('No payment funnel events found. This explains why conversions are 0.');
            
            // Check if we have the test data that was supposed to be created
            $this->info('Checking other related data...');
            
            $uniqueVisitors = \App\Models\UniqueVisitor::where('website_id', $websiteId)->count();
            $pageViews = \App\Models\PageView::where('website_id', $websiteId)->count();
            
            $this->line("UniqueVisitors: {$uniqueVisitors}");
            $this->line("PageViews: {$pageViews}");
        }
        
        return 0;
    }
}