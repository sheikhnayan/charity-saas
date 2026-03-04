<?php
// Test script to verify all API endpoints are working
echo "Testing Analytics API Endpoints\n";
echo "================================\n\n";

$baseUrl = 'http://127.0.0.1:8000/analytics';
$websiteId = 1; // Test website ID
$startDate = '2025-01-01';
$endDate = '2025-01-31';

$endpoints = [
    'conversion-funnel' => "$baseUrl/conversion-funnel?website_id=$websiteId&start_date=$startDate&end_date=$endDate",
    'device-data' => "$baseUrl/device-data?website_id=$websiteId&start_date=$startDate&end_date=$endDate",
    'location-chart' => "$baseUrl/location-chart?website_id=$websiteId&start_date=$startDate&end_date=$endDate",
    'geomap-data' => "$baseUrl/geomap-data?website_id=$websiteId&start_date=$startDate&end_date=$endDate"
];

foreach ($endpoints as $name => $url) {
    echo "Testing $name endpoint:\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HEADER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "CURL Error: $error\n";
    } else {
        echo "HTTP Code: $httpCode\n";
        
        if ($httpCode == 200) {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                echo "✅ JSON Response Valid\n";
                echo "Response Keys: " . implode(', ', array_keys($decoded)) . "\n";
                if (isset($decoded['data'])) {
                    echo "Data Count: " . (is_array($decoded['data']) ? count($decoded['data']) : 'N/A') . "\n";
                }
            } else {
                echo "❌ JSON Parse Error: " . json_last_error_msg() . "\n";
                echo "Raw Response: " . substr($response, 0, 200) . "...\n";
            }
        } else {
            echo "❌ HTTP Error\n";
            echo "Response: " . substr($response, 0, 200) . "...\n";
        }
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}

echo "Testing complete!\n";
?>