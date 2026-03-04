<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentFunnelService;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;
use App\Http\Controllers\AuthorizeNetController;

class TestStripeTicketPurchase extends Command
{
    protected $signature = 'test:stripe-ticket';
    protected $description = 'Test Stripe ticket purchase payment funnel tracking';

    public function handle()
    {
        $this->info('🎫 Testing Stripe Ticket Purchase Tracking...');
        
        try {
            // Set up request simulation for Stripe payment
            request()->merge([
                'stripeToken' => 'tok_test_stripe',
                'amount' => 150,
                'type' => 'ticket',
                'donation_id' => 1 
            ]);
            
            // Set the host to pickpockets.com for proper website detection
            request()->headers->set('HOST', 'pickpockets.com');
            
            $this->info('Request setup completed');
            $this->info('- Payment Method: Stripe (stripeToken present)');
            $this->info('- Amount: $150');
            $this->info('- Type: ticket');
            $this->info('- Host: pickpockets.com');
            
            // Check website detection
            $website = Website::where('domain', 'pickpockets.com')->first();
            if ($website) {
                $this->info("✅ Website found: ID={$website->id}, Domain={$website->domain}");
            } else {
                $this->error('❌ Website pickpockets.com not found!');
                return 1;
            }
            
            // Test payment funnel service directly
            $this->info('Testing PaymentFunnelService directly...');
            $service = new PaymentFunnelService();
            
            $initialCount = PaymentFunnelEvent::count();
            
            $result = $service->trackPaymentCompleted(
                'ticket',           // form type  
                150,               // amount
                'stripe',          // payment method
                'stripe_test_123', // transaction id
                null               // user id
            );
            
            $directCount = PaymentFunnelEvent::count();
            
            if ($result && $directCount > $initialCount) {
                $this->info("✅ Direct service tracking worked! Event ID: {$result->id}");
            } else {
                $this->error('❌ Direct service tracking failed');
            }
            
            // Test via AuthorizeNetController method
            $this->info('Testing via AuthorizeNetController trackPaymentFunnel method...');
            
            $controller = new AuthorizeNetController();
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('trackPaymentFunnel');
            $method->setAccessible(true);
            
            $beforeControllerCount = PaymentFunnelEvent::count();
            
            // Call the exact method used in Stripe ticket completion
            $method->invoke($controller, 'completed', 'ticket', 150, 'stripe_controller_test', null, null);
            
            $afterControllerCount = PaymentFunnelEvent::count();
            
            if ($afterControllerCount > $beforeControllerCount) {
                $this->info('✅ Controller method tracking worked!');
            } else {
                $this->error('❌ Controller method tracking failed');
            }
            
            // Show all events
            $this->info('Recent payment funnel events:');
            $events = PaymentFunnelEvent::latest()->limit(10)->get();
            
            if ($events->count() > 0) {
                $headers = ['ID', 'Website ID', 'Step', 'Form Type', 'Payment Method', 'Amount', 'Transaction ID', 'Created'];
                $rows = [];
                
                foreach($events as $event) {
                    $rows[] = [
                        $event->id,
                        $event->website_id,
                        $event->funnel_step,
                        $event->form_type,
                        $event->payment_method ?? 'null',
                        $event->amount ?? 'null',
                        $event->transaction_id ?? 'null',
                        $event->created_at->format('Y-m-d H:i:s')
                    ];
                }
                
                $this->table($headers, $rows);
            }
            
            // Clean up test data
            $this->info('Cleaning up test data...');
            $deleted = PaymentFunnelEvent::where('transaction_id', 'LIKE', '%test%')->delete();
            $this->info("Deleted {$deleted} test events");
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}