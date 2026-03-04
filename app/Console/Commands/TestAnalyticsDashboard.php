<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Http\Controllers\Analytics\DashboardController;
use Illuminate\Http\Request;

class TestAnalyticsDashboard extends Command
{
    protected $signature = 'test:analytics-dashboard';
    protected $description = 'Test the analytics dashboard with current data';

    public function handle()
    {
        $this->info('🧪 Testing Analytics Dashboard...');
        
        // Mock a request with the same parameters that would come from the UI
        $request = new Request([
            'website_id' => 12, // pickpockets.com
            'date_from' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'date_to' => Carbon::now()->format('Y-m-d')
        ]);
        
        // Create the dashboard controller
        $controller = new DashboardController();
        
        // Call the protected methods using reflection
        $reflection = new \ReflectionClass($controller);
        
        $getConversionsMethod = $reflection->getMethod('getConversions');
        $getConversionsMethod->setAccessible(true);
        
        $getRevenueMethod = $reflection->getMethod('getRevenue');
        $getRevenueMethod->setAccessible(true);
        
        $websiteId = 12;
        $startDate = Carbon::now()->subDays(7)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $conversions = $getConversionsMethod->invoke($controller, $websiteId, $startDate, $endDate);
        $revenue = $getRevenueMethod->invoke($controller, $websiteId, $startDate, $endDate);
        
        $this->info("📊 Analytics Dashboard Results:");
        $this->line("   Website ID: {$websiteId}");
        $this->line("   Date Range: {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
        $this->line("   🔢 Total Conversions: {$conversions}");
        $this->line("   💰 Total Revenue: $" . number_format($revenue / 100, 2));
        
        if ($conversions > 0) {
            $this->info("✅ SUCCESS: Analytics dashboard is now showing conversions and revenue!");
        } else {
            $this->error("❌ FAILED: Analytics dashboard still shows 0 conversions");
        }
        
        return 0;
    }
}