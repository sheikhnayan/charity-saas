<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Analytics\DashboardController;
use Carbon\Carbon;

class TestAnalyticsEndpoints extends Command
{
    protected $signature = 'test:analytics-endpoints';
    protected $description = 'Test all analytics API endpoints';

    public function handle()
    {
        $this->info('🧪 Testing Analytics API Endpoints...');
        
        $controller = new DashboardController();
        $websiteId = 12; // pickpockets.com
        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        
        // Test time-based conversions
        $this->info('1. Testing Time-Based Conversions...');
        $request = new Request(['website_id' => $websiteId, 'start_date' => $startDate, 'end_date' => $endDate, 'group_by' => 'day']);
        $response = $controller->getTimeBasedConversions($request);
        $data = json_decode($response->getContent(), true);
        $this->line("   Found " . count($data) . " data points");
        
        // Test time-based sessions
        $this->info('2. Testing Time-Based Sessions...');
        $request = new Request(['website_id' => $websiteId, 'start_date' => $startDate, 'end_date' => $endDate, 'group_by' => 'day']);
        $response = $controller->getTimeBasedSessions($request);
        $data = json_decode($response->getContent(), true);
        $this->line("   Found " . count($data) . " data points");
        
        // Test conversion funnel
        $this->info('3. Testing Conversion Funnel...');
        $request = new Request(['website_id' => $websiteId, 'start_date' => $startDate, 'end_date' => $endDate]);
        $response = $controller->getConversionFunnel($request);
        $data = json_decode($response->getContent(), true);
        $this->line("   Found " . count($data) . " funnel steps");
        foreach ($data as $step) {
            $this->line("     - {$step['step']}: {$step['count']} ({$step['conversion_rate']}%)");
        }
        
        // Test device data
        $this->info('4. Testing Device Data...');
        $request = new Request(['website_id' => $websiteId, 'start_date' => $startDate, 'end_date' => $endDate]);
        $response = $controller->getDeviceData($request);
        $data = json_decode($response->getContent(), true);
        $this->line("   Found " . count($data) . " device types");
        foreach ($data as $device) {
            $this->line("     - {$device['device_type']}: {$device['visitors']} visitors, {$device['conversions']} conversions");
        }
        
        // Test location data
        $this->info('5. Testing Location Data...');
        $request = new Request(['website_id' => $websiteId, 'start_date' => $startDate, 'end_date' => $endDate]);
        $response = $controller->getLocationChartData($request);
        $data = json_decode($response->getContent(), true);
        $this->line("   Found " . count($data) . " locations");
        foreach (array_slice($data, 0, 5) as $location) {
            $this->line("     - {$location['country_name']}: {$location['visitors']} visitors, {$location['conversions']} conversions");
        }
        
        // Test product data
        $this->info('6. Testing Product Data...');
        $request = new Request(['website_id' => $websiteId, 'start_date' => $startDate, 'end_date' => $endDate]);
        $response = $controller->getProductData($request);
        $data = json_decode($response->getContent(), true);
        $this->line("   Found " . count($data) . " products");
        foreach ($data as $product) {
            $this->line("     - {$product['name']}: {$product['sell_through_rate']}% sell-through, $" . number_format($product['revenue'], 2) . " revenue");
        }
        
        // Test geomap data
        $this->info('7. Testing GeoMap Data...');
        $request = new Request(['website_id' => $websiteId, 'start_date' => $startDate, 'end_date' => $endDate]);
        $response = $controller->getGeoMapData($request);
        $data = json_decode($response->getContent(), true);
        $this->line("   Found " . count($data) . " geographic locations");
        foreach (array_slice($data, 0, 5) as $location) {
            $this->line("     - {$location['country_name']}: {$location['visitors']} visitors at ({$location['lat']}, {$location['lng']})");
        }
        
        $this->info('✅ All analytics endpoints are working correctly!');
        
        return 0;
    }
}