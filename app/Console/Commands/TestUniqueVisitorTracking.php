<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UniqueVisitorService;
use Illuminate\Http\Request;
use App\Models\UniqueVisitor;
use App\Models\PageView;

class TestUniqueVisitorTracking extends Command
{
    protected $signature = 'test:unique-visitor-tracking';
    protected $description = 'Test the unique visitor tracking system (Shopify approach)';

    public function handle()
    {
        $this->info('🧪 Testing Unique Visitor Tracking (Shopify Approach)...');
        
        // Clear existing test data
        UniqueVisitor::where('visitor_id', 'like', 'test_%')->delete();
        PageView::where('visitor_id', 'like', 'test_%')->delete();
        
        $service = new UniqueVisitorService();
        
        // Simulate first visit
        $this->info('1. Testing First Visit:');
        $request1 = $this->createMockRequest('https://pickpockets.com/tickets', null);
        $visitorId1 = $service->getUniqueVisitorId($request1);
        $service->trackVisitor($request1, 12); // pickpockets.com website_id
        
        $this->line("   Generated Visitor ID: {$visitorId1}");
        $this->line("   Is Returning Visitor: " . ($service->isReturningVisitor($request1) ? 'Yes' : 'No'));
        
        // Simulate return visit with same cookie
        $this->info('2. Testing Return Visit (Same Cookie):');
        $request2 = $this->createMockRequestWithCookie('https://pickpockets.com/invest', $visitorId1);
        $visitorId2 = $service->getUniqueVisitorId($request2);
        $service->trackVisitor($request2, 12);
        
        $this->line("   Visitor ID: {$visitorId2}");
        $this->line("   Same as first visit: " . ($visitorId1 === $visitorId2 ? 'Yes' : 'No'));
        $this->line("   Is Returning Visitor: " . ($service->isReturningVisitor($request2) ? 'Yes' : 'No'));
        
        // Simulate new browser (no cookie)
        $this->info('3. Testing New Browser (No Cookie):');
        $request3 = $this->createMockRequest('https://pickpockets.com/', null);
        $visitorId3 = $service->getUniqueVisitorId($request3);
        $service->trackVisitor($request3, 12);
        
        $this->line("   Visitor ID: {$visitorId3}");
        $this->line("   Different from first: " . ($visitorId1 !== $visitorId3 ? 'Yes' : 'No'));
        $this->line("   Is Returning Visitor: " . ($service->isReturningVisitor($request3) ? 'Yes' : 'No'));
        
        // Check database results
        $this->info('4. Database Results:');
        $uniqueVisitors = UniqueVisitor::where('website_id', 12)->where('visitor_id', 'like', '%' . substr($visitorId1, -8))->count();
        $pageViews = PageView::where('website_id', 12)->count();
        
        $this->line("   Unique Visitors Created: {$uniqueVisitors}");
        $this->line("   Page Views Created: {$pageViews}");
        
        // Test analytics
        $this->info('5. Analytics Summary:');
        $stats = $service->getVisitorStats(12, now()->subDay(), now()->addDay());
        $this->line("   Total Unique Visitors: {$stats['unique_visitors']}");
        $this->line("   Total Sessions: {$stats['total_sessions']}");
        $this->line("   Total Page Views: {$stats['total_page_views']}");
        
        $this->info('✅ Unique visitor tracking test completed!');
        
        return 0;
    }
    
    /**
     * Create a mock request for testing
     */
    protected function createMockRequest($url, $cookie)
    {
        $request = Request::create($url, 'GET', [], $cookie ? ['_charity_visitor_id' => $cookie] : []);
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        $request->headers->set('referer', 'https://google.com/search?q=pickpockets');
        
        // Mock session
        $sessionId = 'test_session_' . uniqid();
        $request->setLaravelSession(app('session')->driver());
        $request->session()->setId($sessionId);
        
        return $request;
    }
    
    /**
     * Create mock request with existing cookie
     */
    protected function createMockRequestWithCookie($url, $visitorId)
    {
        return $this->createMockRequest($url, $visitorId);
    }
}