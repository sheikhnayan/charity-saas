<?php

use Illuminate\Support\Facades\Route;
use App\Services\PaymentFunnelService;
use App\Models\PaymentFunnelEvent;

/*
|--------------------------------------------------------------------------
| Payment Funnel Testing Routes
|--------------------------------------------------------------------------
*/

Route::get('/test-payment-funnel', function() {
    try {
        echo "<h2>Payment Funnel Tracking Test</h2>";
        
        echo "<h3>1. Testing PaymentFunnelService initialization</h3>";
        $service = new PaymentFunnelService();
        echo "✅ PaymentFunnelService created successfully<br>";
        
        // Use reflection to check website
        $reflection = new ReflectionClass($service);
        $websiteProperty = $reflection->getProperty('website');
        $websiteProperty->setAccessible(true);
        $website = $websiteProperty->getValue($service);
        
        if ($website) {
            echo "✅ Website detected: ID={$website->id}, Domain={$website->domain}<br>";
        } else {
            echo "❌ No website detected!<br>";
            echo "Host: " . request()->getHost() . "<br>";
            echo "Available websites:<br>";
            foreach(\App\Models\Website::all() as $w) {
                echo "- ID: {$w->id}, Domain: {$w->domain}<br>";
            }
        }
        
        echo "<h3>2. Testing payment completion tracking</h3>";
        $result = $service->trackPaymentCompleted(
            'ticket',      // form type
            100,           // amount
            'stripe',      // payment method
            'test_tx_123', // transaction id
            null           // user id
        );
        
        if ($result) {
            echo "✅ Payment completion tracked successfully! Event ID: {$result->id}<br>";
        } else {
            echo "❌ Failed to track payment completion<br>";
        }
        
        echo "<h3>3. Checking PaymentFunnelEvent count</h3>";
        $count = PaymentFunnelEvent::count();
        echo "Total payment funnel events: {$count}<br>";
        
        if ($count > 0) {
            echo "<h4>Latest 5 events:</h4>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Website ID</th><th>Step</th><th>Form Type</th><th>Payment Method</th><th>Amount</th><th>Created</th></tr>";
            
            $events = PaymentFunnelEvent::latest()->limit(5)->get();
            foreach($events as $event) {
                echo "<tr>";
                echo "<td>{$event->id}</td>";
                echo "<td>{$event->website_id}</td>";
                echo "<td>{$event->funnel_step}</td>";
                echo "<td>{$event->form_type}</td>";
                echo "<td>{$event->payment_method}</td>";
                echo "<td>{$event->amount}</td>";
                echo "<td>{$event->created_at}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "<h3>4. Testing AuthorizeNetController tracking method</h3>";
        
        // Simulate a request
        request()->merge([
            'stripeToken' => 'tok_test_123',
            'amount' => 150,
            'type' => 'ticket'
        ]);
        
        $controller = new \App\Http\Controllers\AuthorizeNetController();
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('trackPaymentFunnel');
        $method->setAccessible(true);
        
        echo "Calling trackPaymentFunnel method...<br>";
        $method->invoke($controller, 'completed', 'ticket', 150, 'test_tx_456');
        
        $newCount = PaymentFunnelEvent::count();
        echo "Events after controller test: {$newCount}<br>";
        
        if ($newCount > $count) {
            echo "✅ Controller tracking working!<br>";
        } else {
            echo "❌ Controller tracking failed<br>";
        }
        
    } catch (Exception $e) {
        echo "<h3 style='color: red;'>Error: " . $e->getMessage() . "</h3>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});

Route::get('/test-payment-funnel-clean', function() {
    // Clean up test data
    PaymentFunnelEvent::where('transaction_id', 'LIKE', 'test_tx_%')->delete();
    echo "Test payment funnel events cleaned up!";
});