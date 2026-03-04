<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;
use App\Http\Controllers\Admin\PaymentMethodAnalyticsController;
use Carbon\Carbon;

class DebugAnalyticsController extends Command
{
    protected $signature = 'debug:analytics-controller';
    protected $description = 'Debug what the analytics controller sees from payment funnel events';

    public function handle()
    {
        $this->info('🔍 Debugging Analytics Controller Data...');
        
        // Use website ID 12 (pickpockets.com)
        $websiteId = 12;
        $website = Website::find($websiteId);
        
        if (!$website) {
            $this->error('Website ID 12 not found!');
            return 1;
        }
        
        $this->info("Website: {$website->domain} (ID: {$website->id})");
        
        // Use date range that includes today
        $dateFrom = Carbon::now()->subDays(7)->startOfDay();
        $dateTo = Carbon::now()->endOfDay();
        
        $this->info("Date range: {$dateFrom->format('Y-m-d H:i:s')} to {$dateTo->format('Y-m-d H:i:s')}");
        
        // 1. Check raw payment funnel events
        $this->info('1. Raw Payment Funnel Events:');
        $events = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();
            
        if ($events->count() > 0) {
            $this->info("Found {$events->count()} events:");
            foreach ($events as $event) {
                $this->line("- ID: {$event->id}, Step: {$event->funnel_step}, Type: {$event->form_type}, Method: {$event->payment_method}, Amount: {$event->amount}, Created: {$event->created_at}");
            }
        } else {
            $this->warn('No events found for this date range');
        }
        
        // 2. Test payment method performance query
        $this->info('2. Payment Method Performance Query:');
        $paymentMethodStats = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                payment_method,
                funnel_step,
                COUNT(*) as event_count,
                COUNT(DISTINCT session_id) as unique_sessions,
                AVG(amount) as avg_amount,
                SUM(amount) as total_amount
            ')
            ->whereNotNull('payment_method')
            ->groupBy('payment_method', 'funnel_step')
            ->get()
            ->groupBy('payment_method');
            
        if ($paymentMethodStats->count() > 0) {
            foreach ($paymentMethodStats as $method => $steps) {
                $this->info("Payment Method: {$method}");
                foreach ($steps as $step) {
                    $this->line("  - Step: {$step->funnel_step}, Count: {$step->event_count}, Amount: {$step->total_amount}");
                }
            }
        } else {
            $this->warn('No payment method stats found');
        }
        
        // 3. Test conversion by payment method query
        $this->info('3. Conversion by Payment Method Query:');
        $methods = ['authorize_net', 'stripe', 'crypto'];
        
        foreach ($methods as $method) {
            $events = PaymentFunnelEvent::where('website_id', $websiteId)
                ->where('payment_method', $method)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->selectRaw('
                    funnel_step,
                    COUNT(*) as count,
                    COUNT(DISTINCT session_id) as unique_sessions,
                    SUM(amount) as revenue
                ')
                ->groupBy('funnel_step')
                ->get();
                
            if ($events->count() > 0) {
                $this->info("Method: {$method}");
                foreach ($events as $event) {
                    $this->line("  - Step: {$event->funnel_step}, Count: {$event->count}, Revenue: {$event->revenue}");
                }
            } else {
                $this->line("Method: {$method} - No data");
            }
        }
        
        // 4. Test form type by payment method query
        $this->info('4. Form Type by Payment Method Query:');
        $formTypeByMethod = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereIn('funnel_step', [PaymentFunnelEvent::PAYMENT_COMPLETED])
            ->selectRaw('
                form_type,
                payment_method,
                COUNT(*) as completions,
                AVG(amount) as avg_amount,
                SUM(amount) as total_revenue
            ')
            ->whereNotNull('payment_method')
            ->groupBy('form_type', 'payment_method')
            ->get()
            ->groupBy('form_type');
            
        if ($formTypeByMethod->count() > 0) {
            foreach ($formTypeByMethod as $formType => $methods) {
                $this->info("Form Type: {$formType}");
                foreach ($methods as $methodData) {
                    $this->line("  - Method: {$methodData->payment_method}, Completions: {$methodData->completions}, Revenue: {$methodData->total_revenue}");
                }
            }
        } else {
            $this->warn('No form type data found');
        }
        
        return 0;
    }
}