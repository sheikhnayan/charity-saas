<?php
/**
 * Test Event Countdown Enhanced Font Weight and Visibility Options
 * This script tests the new font weight customization and show/hide features
 */

// Test different font weight and visibility configurations
$testConfigurations = [
    [
        'name' => 'Default Settings',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Default Configuration Test',
                'date' => '2025-12-31 23:59:59'
                // All defaults: numbers bold, labels normal, remaining text normal, show remaining text true
            ]
        ]
    ],
    [
        'name' => 'All Bold Text',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Everything Bold Test',
                'date' => '2025-12-31 23:59:59',
                'numberFontWeight' => 'bold',
                'textFontWeight' => 'bold',
                'remainingFontWeight' => 'bold',
                'showRemainingText' => true
            ]
        ]
    ],
    [
        'name' => 'All Normal Text',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Everything Normal Weight Test',
                'date' => '2025-12-31 23:59:59',
                'numberFontWeight' => 'normal',
                'textFontWeight' => 'normal',
                'remainingFontWeight' => 'normal',
                'showRemainingText' => true
            ]
        ]
    ],
    [
        'name' => 'Mixed Font Weights',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Mixed Font Weights Test',
                'date' => '2025-12-31 23:59:59',
                'numberFontWeight' => 'normal',     // Numbers normal
                'textFontWeight' => 'bold',         // Labels bold
                'remainingFontWeight' => 'normal',  // Remaining text normal
                'showRemainingText' => true
            ]
        ]
    ],
    [
        'name' => 'Hide Remaining Text',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'This text should not appear',
                'date' => '2025-12-31 23:59:59',
                'numberFontWeight' => 'bold',
                'textFontWeight' => 'normal',
                'remainingFontWeight' => 'bold',
                'showRemainingText' => false  // Hide remaining text
            ]
        ]
    ],
    [
        'name' => 'Colors + Font Weights + Show Text',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Comprehensive Styling Test',
                'date' => '2025-12-31 23:59:59',
                'numberColor' => '#ff0000',         // Red numbers
                'textColor' => '#0000ff',           // Blue labels
                'remainingVerbiageColor' => '#00aa00', // Green remaining text
                'numberFontWeight' => 'bold',       // Bold numbers
                'textFontWeight' => 'normal',       // Normal labels
                'remainingFontWeight' => 'bold',    // Bold remaining text
                'showRemainingText' => true
            ]
        ]
    ],
    [
        'name' => 'Colors + Font Weights + Hide Text',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Hidden label text',
                'date' => '2025-12-31 23:59:59',
                'numberColor' => '#ff6600',         // Orange numbers
                'textColor' => '#6600ff',           // Purple labels
                'remainingVerbiageColor' => '#ff0066', // Pink remaining (hidden)
                'numberFontWeight' => 'normal',     // Normal numbers
                'textFontWeight' => 'bold',         // Bold labels
                'remainingFontWeight' => 'bold',    // Bold remaining (hidden)
                'showRemainingText' => false        // Hide remaining text
            ]
        ]
    ],
    [
        'name' => 'Legacy Support Test',
        'component' => [
            'type' => 'event-countdown',
            'countdownData' => [
                'label' => 'Legacy fontWeight Support',
                'date' => '2025-12-31 23:59:59',
                'fontWeight' => 'normal',  // Legacy setting
                'numberColor' => '#333333',
                'textColor' => '#666666',
                'remainingVerbiageColor' => '#999999'
                // Should use legacy fontWeight for numbers and remaining text
            ]
        ]
    ]
];

echo "<!DOCTYPE html>\n";
echo "<html lang='en'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Event Countdown Enhanced Options Test</title>\n";
echo "    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>\n";
echo "    <style>\n";
echo "        body { padding: 20px; background-color: #f8f9fa; }\n";
echo "        .test-section { margin-bottom: 40px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }\n";
echo "        .test-title { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }\n";
echo "        .config-info { background: #e9ecef; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-family: monospace; font-size: 12px; }\n";
echo "        .feature-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; }\n";
echo "        .feature-item { padding: 5px; background: #f8f9fa; border-radius: 4px; }\n";
echo "        .label-bold { font-weight: bold; }\n";
echo "        .label-normal { font-weight: normal; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";
echo "    <div class='container'>\n";
echo "        <h1 class='mb-4'>Event Countdown Enhanced Options Test</h1>\n";
echo "        <p class='lead'>Testing font weight customization and show/hide functionality for event countdown components.</p>\n";

foreach ($testConfigurations as $index => $config) {
    echo "        <div class='test-section'>\n";
    echo "            <h3 class='test-title'>{$config['name']}</h3>\n";
    
    // Display configuration info
    echo "            <div class='config-info'>\n";
    echo "                <strong>Configuration:</strong><br>\n";
    
    $countdownData = $config['component']['countdownData'];
    
    // Font weight settings
    $numberWeight = $countdownData['numberFontWeight'] ?? ($countdownData['fontWeight'] ?? 'bold');
    $textWeight = $countdownData['textFontWeight'] ?? 'normal';
    $remainingWeight = $countdownData['remainingFontWeight'] ?? ($countdownData['fontWeight'] ?? 'normal');
    $showRemaining = $countdownData['showRemainingText'] ?? true;
    
    echo "                • Number Font Weight: {$numberWeight}<br>\n";
    echo "                • Label Font Weight: {$textWeight}<br>\n";
    echo "                • Remaining Text Font Weight: {$remainingWeight}<br>\n";
    echo "                • Show Remaining Text: " . ($showRemaining ? 'Yes' : 'No') . "<br>\n";
    
    // Color settings
    if (isset($countdownData['numberColor'])) {
        echo "                • Number Color: {$countdownData['numberColor']}<br>\n";
    }
    if (isset($countdownData['textColor'])) {
        echo "                • Label Color: {$countdownData['textColor']}<br>\n";
    }
    if (isset($countdownData['remainingVerbiageColor'])) {
        echo "                • Remaining Text Color: {$countdownData['remainingVerbiageColor']}<br>\n";
    }
    
    // Legacy support
    if (isset($countdownData['fontWeight'])) {
        echo "                • Legacy fontWeight: {$countdownData['fontWeight']} (for backward compatibility)<br>\n";
    }
    
    echo "            </div>\n";
    
    // Expected behavior
    echo "            <div class='feature-grid'>\n";
    echo "                <div class='feature-item'>\n";
    echo "                    <strong>Numbers:</strong> <span class='" . ($numberWeight === 'bold' ? 'label-bold' : 'label-normal') . "'>{$numberWeight}</span>\n";
    echo "                </div>\n";
    echo "                <div class='feature-item'>\n";
    echo "                    <strong>Labels:</strong> <span class='" . ($textWeight === 'bold' ? 'label-bold' : 'label-normal') . "'>{$textWeight}</span>\n";
    echo "                </div>\n";
    echo "                <div class='feature-item'>\n";
    echo "                    <strong>Remaining Text:</strong> <span class='" . ($remainingWeight === 'bold' ? 'label-bold' : 'label-normal') . "'>{$remainingWeight}</span>\n";
    echo "                </div>\n";
    echo "                <div class='feature-item'>\n";
    echo "                    <strong>Text Visibility:</strong> " . ($showRemaining ? 'Visible' : 'Hidden') . "\n";
    echo "                </div>\n";
    echo "            </div>\n";
    
    // Render the component
    echo "            <div class='component-container'>\n";
    
    // Simulate the component rendering logic
    $component = $config['component'];
    $countdownData = $component['countdownData'];
    
    // Extract settings
    $label = $countdownData['label'];
    $date = $countdownData['date'];
    
    // Colors
    $numberColor = $countdownData['numberColor'] ?? '#000000';
    $textColor = $countdownData['textColor'] ?? '#000000';
    $remainingVerbiageColor = $countdownData['remainingVerbiageColor'] ?? '#000000';
    
    // Font weights
    $numberFontWeight = $countdownData['numberFontWeight'] ?? ($countdownData['fontWeight'] ?? 'bold');
    $textFontWeight = $countdownData['textFontWeight'] ?? 'normal';
    $remainingFontWeight = $countdownData['remainingFontWeight'] ?? ($countdownData['fontWeight'] ?? 'normal');
    
    // Show/hide
    $showRemainingText = $countdownData['showRemainingText'] ?? true;
    
    // Convert to CSS weights
    $numberWeight = $numberFontWeight === 'bold' ? 600 : 400;
    $textWeight = $textFontWeight === 'bold' ? 600 : 400;
    $remainingWeight = $remainingFontWeight === 'bold' ? 600 : 400;
    
    $uniqueId = 'countdown_' . uniqid();
    
    echo "                <div class='event-countdown' style='padding:24px 16px;border-radius:8px;text-align:center;margin-bottom:24px;'>\n";
    echo "                    <div class='timer text-center mt-5'>\n";
    echo "                        <div class='d-flex justify-content-center align-items-center flex-wrap'>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='months_$uniqueId' class='display-4' style='font-weight:$numberWeight !important;color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor;font-weight:$textWeight !important'>Months</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='days_$uniqueId' class='display-4' style='font-weight:$numberWeight !important;color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor;font-weight:$textWeight !important'>Days</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='hours_$uniqueId' class='display-4' style='font-weight:$numberWeight !important;color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor;font-weight:$textWeight !important'>Hours</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='minutes_$uniqueId' class='display-4' style='font-weight:$numberWeight !important;color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor;font-weight:$textWeight !important'>Minutes</p>\n";
    echo "                            </div>\n";
    echo "                            <div class='mx-2 counters'>\n";
    echo "                                <h1 id='seconds_$uniqueId' class='display-4' style='font-weight:$numberWeight !important;color:$numberColor'>0</h1>\n";
    echo "                                <p style='color:$textColor;font-weight:$textWeight !important'>Seconds</p>\n";
    echo "                            </div>\n";
    echo "                        </div>\n";
    if ($showRemainingText && $label) {
        echo "                        <p style='font-size: .8em; font-weight:$remainingWeight !important; color:$remainingVerbiageColor'>$label</p>\n";
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
echo "            <h5>Enhanced Options Available:</h5>\n";
echo "            <ul>\n";
echo "                <li><strong>numberFontWeight:</strong> 'normal' or 'bold' for countdown numbers</li>\n";
echo "                <li><strong>textFontWeight:</strong> 'normal' or 'bold' for time unit labels</li>\n";
echo "                <li><strong>remainingFontWeight:</strong> 'normal' or 'bold' for custom label text</li>\n";
echo "                <li><strong>showRemainingText:</strong> true/false to show/hide the custom label</li>\n";
echo "                <li><strong>Legacy Support:</strong> Existing 'fontWeight' setting still works</li>\n";
echo "            </ul>\n";
echo "            <p><strong>Usage in Page Builder:</strong> Each font weight can be controlled independently with dropdown menus. The show/hide toggle controls visibility of the custom label text.</p>\n";
echo "        </div>\n";
echo "    </div>\n";
echo "</body>\n";
echo "</html>\n";

echo "\n✅ Event Countdown Enhanced Options Test Complete!\n";
echo "📋 Test Features:\n";
echo "   • Individual font weight controls for numbers, labels, and remaining text\n";
echo "   • Show/hide toggle for remaining text\n";
echo "   • Color customization integration\n";
echo "   • Legacy fontWeight backward compatibility\n";
echo "   • Real-time countdown functionality\n";
?>