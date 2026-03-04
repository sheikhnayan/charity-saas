<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;
use Carbon\Carbon;

class CreateTestPaymentFunnelEvent extends Command
{
    protected $signature = 'test:create-payment-funnel-event';
    protected $description = 'Create a test payment funnel event to verify analytics dashboard';

    public function handle()
    {
        $this->info('🧪 Creating test payment funnel event...');
        
        // Get pickpockets website
        $website = Website::where('domain', 'pickpockets.com')->first();
        
        if (!$website) {
            $this->error('❌ pickpockets.com website not found in database');
            return;
        }
        
        // Create a test payment funnel event
        $event = PaymentFunnelEvent::create([
            'website_id' => $website->id,
            'user_id' => auth()->id() ?? 1, // Use current user or admin
            'session_id' => 'test_session_' . time(),
            'funnel_step' => 'payment_completed',
            'form_type' => 'ticket',
            'payment_method' => 'stripe',
            'amount' => 5000, // $50.00
            'transaction_id' => 'test_txn_' . time(),
            'user_agent' => 'Mozilla/5.0 (Test Agent)',
            'ip_address' => '127.0.0.1',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'operating_system' => 'Windows',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        $this->info("✅ Created test payment funnel event:");
        $this->line("   ID: {$event->id}");
        $this->line("   Website: {$website->domain} (ID: {$website->id})");
        $this->line("   Amount: $" . number_format($event->amount / 100, 2));
        $this->line("   Payment Method: {$event->payment_method}");
        $this->line("   Form Type: {$event->form_type}");
        $this->line("   Date: {$event->created_at}");
        
        return 0;
    }
}