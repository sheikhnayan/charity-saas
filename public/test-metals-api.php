<?php
// Quick test of the metals API endpoint
echo "<h2>Metals API Test</h2>";

$url = 'http://localhost/api/metals/prices';
echo "<p>Testing: <code>$url</code></p>";

try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "<p style='color: red;'><strong>cURL Error:</strong> $error</p>";
    } else if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "<p style='color: green;'><strong>✓ API Response (HTTP 200):</strong></p>";
        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "</pre>";
        
        if (!empty($data['prices_per_ounce_usd'])) {
            echo "<h3>Metal Prices (per troy ounce USD):</h3>";
            echo "<ul>";
            foreach ($data['prices_per_ounce_usd'] as $metal => $price) {
                $display = $price ? number_format($price, 2) : 'N/A';
                echo "<li><strong>" . ucfirst($metal) . ":</strong> $$display</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p style='color: red;'><strong>HTTP Error:</strong> $httpCode</p>";
        echo "<pre>$response</pre>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Exception:</strong> " . $e->getMessage() . "</p>";
}
