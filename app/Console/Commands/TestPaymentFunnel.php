<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentFunnelService;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;

class TestPaymentFunnel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:payment-funnel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test payment funnel tracking functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Payment Funnel Tracking...');
        
        try {
            // Test 1: Check website detection
            $this->info('1. Testing website detection...');
            $websites = Website::all();
            $this->info("   Available websites: {$websites->count()}");
            foreach($websites as $website) {
                $this->info("   - ID: {$website->id}, Domain: {$website->domain}");
            }
            
            // Test 2: Try to create PaymentFunnelService manually
            $this->info('2. Testing PaymentFunnelService creation...');
            
            // Manually set website in session for testing
            session(['website_id' => $websites->first()->id]);
            
            $service = new PaymentFunnelService();
            $this->info('   ✅ PaymentFunnelService created successfully');
            
            // Test 3: Track a test payment completion
            $this->info('3. Testing payment completion tracking...');
            $initialCount = PaymentFunnelEvent::count();
            $this->info("   Initial event count: {$initialCount}");
            
            $result = $service->trackPaymentCompleted(
                'ticket',      // form type
                100,           // amount
                'stripe',      // payment method
                'test_cmd_123',// transaction id
                null           // user id
            );
            
            $newCount = PaymentFunnelEvent::count();
            $this->info("   Event count after tracking: {$newCount}");
            
            if ($result && $newCount > $initialCount) {
                $this->info("   ✅ Payment completion tracked successfully! Event ID: {$result->id}");
            } else {
                $this->error('   ❌ Failed to track payment completion');
                if (!$result) {
                    $this->error('   Service returned false/null');
                }
            }
            
            // Test 4: Show recent events
            $this->info('4. Recent payment funnel events:');
            $events = PaymentFunnelEvent::latest()->limit(5)->get();
            
            if ($events->count() > 0) {
                $headers = ['ID', 'Website ID', 'Step', 'Form Type', 'Payment Method', 'Amount', 'Created'];
                $rows = [];
                
                foreach($events as $event) {
                    $rows[] = [
                        $event->id,
                        $event->website_id,
                        $event->funnel_step,
                        $event->form_type,
                        $event->payment_method ?? 'null',
                        $event->amount ?? 'null',
                        $event->created_at->format('Y-m-d H:i:s')
                    ];
                }
                
                $this->table($headers, $rows);
            } else {
                $this->warn('   No payment funnel events found');
            }
            
            // Test 5: Clean up test data
            $this->info('5. Cleaning up test data...');
            $deleted = PaymentFunnelEvent::where('transaction_id', 'test_cmd_123')->delete();
            $this->info("   Deleted {$deleted} test events");
            
            $this->info('🎉 Payment funnel test completed!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error during testing: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}
