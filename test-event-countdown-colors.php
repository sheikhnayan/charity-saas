<?php
/**
 * Test Event Countdown Color Options
 * This script tests the new color customization features for event countdown components
 */

// Test different color configurations
$testConfigurations = [
    [
        'name' => 'Default Colors (Black)',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Event Starting Soon!',
                'date' => '2025-12-31 23:59:59',
                'fontWeight' => 'bold'
                // No color options - should use defaults (#000)
            ]
        ]
    ],
    [
        'name' => 'Custom Number Color (Red)',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'New Year Countdown',
                'date' => '2025-12-31 23:59:59',
                'fontWeight' => 'bold',
                'numberColor' => '#ff0000'
                // Text and verbiage should remain default
            ]
        ]
    ],
    [
        'name' => 'Custom Text Color (Blue)',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Blue Text Labels',
                'date' => '2025-12-31 23:59:59',
                'fontWeight' => 'bold',
                'textColor' => '#0066cc'
                // Numbers and verbiage should remain default
            ]
        ]
    ],
    [
        'name' => 'Custom Verbiage Color (Green)',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Green Bottom Text',
                'date' => '2025-12-31 23:59:59',
                'fontWeight' => 'bold',
                'remainingVerbiageColor' => '#00aa00'
                // Numbers and text should remain default
            ]
        ]
    ],
    [
        'name' => 'All Colors Custom',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Fully Customized Colors',
                'date' => '2025-12-31 23:59:59',
                'fontWeight' => 'bold',
                'numberColor' => '#ff6600',      // Orange numbers
                'textColor' => '#6600ff',        // Purple text labels
                'remainingVerbiageColor' => '#ff0066' // Pink verbiage
            ]
        ]
    ],
    [
        'name' => 'With Background and All Colors',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Complete Styling Test',
                'date' => '2025-12-31 23:59:59',
                'fontWeight' => 'bold',
                'numberColor' => '#ffffff',      // White numbers
                'textColor' => '#f0f0f0',        // Light gray text
                'remainingVerbiageColor' => '#ffff00' // Yellow verbiage
            ],
            'style' => [
                'backgroundColor' => '#333333'   // Dark background
            ]
        ]
    ]
];

echo "<!DOCTYPE html>\n";
echo "<html lang='en'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Event Countdown Color Options Test</title>\n";
echo "    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>\n";
echo "    <style>\n";
echo "        body { padding: 20px; background-color: #f8f9fa; }\n";
echo "        .test-section { margin-bottom: 40px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }\n";
echo "        .test-title { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }\n";
echo "        .color-info { background: #e9ecef; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-family: monospace; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";
echo "    <div class='container'>\n";
echo "        <h1 class='mb-4'>Event Countdown Color Options Test</h1>\n";
echo "        <p class='lead'>Testing different color configurations for event countdown components.</p>\n";

foreach ($testConfigurations as $index => $config) {
    echo "        <div class='test-section'>\n";
    echo "            <h3 class='test-title'>{$config['name']}</h3>\n";
    
    // Display color configuration info
    echo "            <div class='color-info'>\n";
    echo "                <strong>Color Configuration:</strong><br>\n";
    
    $countdownData = $config['component']['countdownData'];
    if (isset($countdownData['numberColor'])) {
        echo "                • Number Color: {$countdownData['numberColor']}<br>\n";
    } else {
        echo "                • Number Color: Default (#000)<br>\n";
    }
    
    if (isset($countdownData['textColor'])) {
        echo "                • Text Color: {$countdownData['textColor']}<br>\n";
    } else {
        echo "                • Text Color: Default (#000)<br>\n";
    }
    
    if (isset($countdownData['remainingVerbiageColor'])) {
        echo "                • Verbiage Color: {$countdownData['remainingVerbiageColor']}<br>\n";
    } else {
        echo "                • Verbiage Color: Default (#000)<br>\n";
    }
    
    if (isset($config['component']['style']['backgroundColor'])) {
        echo "                • Background Color: {$config['component']['style']['backgroundColor']}<br>\n";
    }
    
    echo "            </div>\n";
    
    // Render the component
    echo "            <div class='component-container'>\n";
    
    // Simulate the component rendering logic
    $component = $config['component'];
    $countdownData = $component['countdownData'];
    $style = $component['style'] ?? [];
    
    // Extract colors
    $numberColor = $countdownData['numberColor'] ?? '#000';
    $textColor = $countdownData['textColor'] ?? '#000';
    $remainingVerbiageColor = $countdownData['remainingVerbiageColor'] ?? '#000';
    
    // Extract styling
    $backgroundColor = $style['backgroundColor'] ?? '';
    $wrapperStyle = $backgroundColor ? "background-color: $backgroundColor;" : '';
    
    $uniqueId = 'countdown_' . uniqid();
    $label = $countdownData['label'];
    $date = $countdownData['date'];
    $fontWeight = $countdownData['fontWeight'] ?? 'bold';
    
    echo "                <div class='event-countdown' style='padding:24px 16px;border-radius:8px;text-align:center;margin-bottom:24px;$wrapperStyle'>\n";
    echo "                    <div class='timer text-center mt-5'>\n";
    echo "                        <div class='d-flex justify-content-center align-items-center flex-wrap'>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='months_$uniqueId' class='display-4' style='font-weight:" . ($fontWeight == 'normal' ? 400 : 600) . ";color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor'>Months</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='days_$uniqueId' class='display-4' style='font-weight:" . ($fontWeight == 'normal' ? 400 : 600) . ";color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor'>Days</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='hours_$uniqueId' class='display-4' style='font-weight:" . ($fontWeight == 'normal' ? 400 : 600) . ";color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor'>Hours</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='minutes_$uniqueId' class='display-4' style='font-weight:" . ($fontWeight == 'normal' ? 400 : 600) . ";color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor'>Minutes</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='seconds_$uniqueId' class='display-4' style='font-weight:" . ($fontWeight == 'normal' ? 400 : 600) . ";color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor'>Seconds</p>\n";
    echo "                            </div>\n";
    echo "                        </div>\n";
    if ($label) {
        echo "                        <p style='font-size: .8em; font-weight:" . ($fontWeight == 'normal' ? 400 : 600) . "; color:$remainingVerbiageColor'>$label</p>\n";
    }
    echo "                    </div>\n";
    echo "                    <input type='hidden' id='timer_$uniqueId' class='date-countdown' value='$date'>\n";
    echo "                </div>\n";
    
    // JavaScript for this countdown
    echo "<script>\n";
    echo "(function() {\n";
    echo "    const timerId = '$uniqueId';\n";
    echo "    const dateValue = document.getElementById('timer_' + timerId).value;\n";
    echo "    \n";
    echo "    if (!dateValue) return;\n";
    echo "    \n";
    echo "    const targetDate = new Date(dateValue).getTime();\n";
    echo "    \n";
    echo "    function updateCountdown() {\n";
    echo "        const now = new Date().getTime();\n";
    echo "        const timeLeft = targetDate - now;\n";
    echo "        \n";
    echo "        if (timeLeft <= 0) {\n";
    echo "            document.getElementById('months_' + timerId).textContent = 0;\n";
    echo "            document.getElementById('days_' + timerId).textContent = 0;\n";
    echo "            document.getElementById('hours_' + timerId).textContent = 0;\n";
    echo "            document.getElementById('minutes_' + timerId).textContent = 0;\n";
    echo "            document.getElementById('seconds_' + timerId).textContent = 0;\n";
    echo "            return;\n";
    echo "        }\n";
    echo "        \n";
    echo "        const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));\n";
    echo "        const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));\n";
    echo "        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));\n";
    echo "        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));\n";
    echo "        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);\n";
    echo "        \n";
    echo "        document.getElementById('months_' + timerId).textContent = months;\n";
    echo "        document.getElementById('days_' + timerId).textContent = days;\n";
    echo "        document.getElementById('hours_' + timerId).textContent = hours;\n";
    echo "        document.getElementById('minutes_' + timerId).textContent = minutes;\n";
    echo "        document.getElementById('seconds_' + timerId).textContent = seconds;\n";
    echo "    }\n";
    echo "    \n";
    echo "    updateCountdown();\n";
    echo "    setInterval(updateCountdown, 1000);\n";
    echo "})();\n";
    echo "</script>\n";
    
    echo "            </div>\n";
    echo "        </div>\n";
}

echo "        <div class='alert alert-info'>\n";
echo "            <h5>Color Options Available:</h5>\n";
echo "            <ul>\n";
echo "                <li><strong>numberColor:</strong> Sets the color of countdown numbers (0, 1, 2, etc.)</li>\n";
echo "                <li><strong>textColor:</strong> Sets the color of text labels (Months, Days, Hours, etc.)</li>\n";
echo "                <li><strong>remainingVerbiageColor:</strong> Sets the color of the custom label text at the bottom</li>\n";
echo "            </ul>\n";
echo "            <p><strong>Usage:</strong> Add these properties to your event countdown component's countdownData object.</p>\n";
echo "        </div>\n";
echo "    </div>\n";
echo "</body>\n";
echo "</html>\n";

// Validation tests
echo "\n\n<!-- VALIDATION TESTS -->\n";
echo "<!-- Testing PHP syntax and variable handling -->\n";

// Test 1: Check if color variables are properly set
$testComponent = [
    'type' => 'event-countdown',
    'countdownData' => [
        'label' => 'Test',
        'date' => '2025-12-31 23:59:59',
        'numberColor' => '#ff0000',
        'textColor' => '#00ff00',
        'remainingVerbiageColor' => '#0000ff'
    ]
];

$countdownData = $testComponent['countdownData'];
$numberColor = $countdownData['numberColor'] ?? '#000';
$textColor = $countdownData['textColor'] ?? '#000';  
$remainingVerbiageColor = $countdownData['remainingVerbiageColor'] ?? '#000';

echo "<!-- Test Results:\n";
echo "Number Color: $numberColor (Expected: #ff0000)\n";
echo "Text Color: $textColor (Expected: #00ff00)\n";
echo "Verbiage Color: $remainingVerbiageColor (Expected: #0000ff)\n";
echo "-->\n";

// Test 2: Check fallback behavior
$testComponentNoColors = [
    'type' => 'event-countdown',
    'countdownData' => [
        'label' => 'Test',
        'date' => '2025-12-31 23:59:59'
    ]
];

$countdownData2 = $testComponentNoColors['countdownData'];
$numberColor2 = $countdownData2['numberColor'] ?? '#000';
$textColor2 = $countdownData2['textColor'] ?? '#000';
$remainingVerbiageColor2 = $countdownData2['remainingVerbiageColor'] ?? '#000';

echo "<!-- Fallback Test Results:\n";
echo "Number Color: $numberColor2 (Expected: #000)\n";
echo "Text Color: $textColor2 (Expected: #000)\n";
echo "Verbiage Color: $remainingVerbiageColor2 (Expected: #000)\n";
echo "-->\n";

echo "\n✅ Event Countdown Color Options Test Complete!\n";
?>