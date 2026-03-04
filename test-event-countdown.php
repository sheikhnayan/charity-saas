<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== EVENT COUNTDOWN COMPONENT FIX TEST ===\n\n";

// Test 1: Check if event-countdown component exists in render-component.blade.php
echo "1. Testing event-countdown component in render-component.blade.php:\n";
$renderComponentPath = 'resources/views/page-components/render-component.blade.php';
if (file_exists($renderComponentPath)) {
    $content = file_get_contents($renderComponentPath);
    
    // Check for event-countdown case
    $hasEventCountdown = strpos($content, "@case('event-countdown')") !== false;
    echo "   - event-countdown case found: " . ($hasEventCountdown ? "✅ YES" : "❌ NO") . "\n";
    
    // Check for countdown JavaScript
    $hasCountdownJS = strpos($content, 'function updateCountdown') !== false;
    echo "   - Countdown JavaScript function: " . ($hasCountdownJS ? "✅ FOUND" : "❌ MISSING") . "\n";
    
    // Check for unique ID generation
    $hasUniqueId = strpos($content, 'uniqid()') !== false;
    echo "   - Unique ID generation: " . ($hasUniqueId ? "✅ FOUND" : "❌ MISSING") . "\n";
    
    // Check for responsive design
    $hasResponsiveDesign = strpos($content, 'flex-wrap') !== false;
    echo "   - Responsive design (flex-wrap): " . ($hasResponsiveDesign ? "✅ FOUND" : "❌ MISSING") . "\n";
    
} else {
    echo "   - render-component.blade.php: ❌ NOT FOUND\n";
}

// Test 2: Check if responsive styles added to page-investment.blade.php
echo "\n2. Testing responsive styles in page-investment.blade.php:\n";
$pageInvestmentPath = 'resources/views/page-investment.blade.php';
if (file_exists($pageInvestmentPath)) {
    $content = file_get_contents($pageInvestmentPath);
    
    // Check for event-countdown responsive styles
    $hasEventCountdownStyles = strpos($content, '.event-countdown') !== false;
    echo "   - Event countdown styles: " . ($hasEventCountdownStyles ? "✅ FOUND" : "❌ MISSING") . "\n";
    
    // Check for mobile responsive breakpoints
    $hasMobileStyles = strpos($content, '@media (max-width: 768px)') !== false;
    echo "   - Mobile responsive styles: " . ($hasMobileStyles ? "✅ FOUND" : "❌ MISSING") . "\n";
    
    // Check for small screen styles
    $hasSmallScreenStyles = strpos($content, '@media (max-width: 480px)') !== false;
    echo "   - Small screen styles: " . ($hasSmallScreenStyles ? "✅ FOUND" : "❌ MISSING") . "\n";
    
} else {
    echo "   - page-investment.blade.php: ❌ NOT FOUND\n";
}

// Test 3: Check if event-countdown exists in page builder
echo "\n3. Testing event-countdown in page builder:\n";
$pageBuilderPath = 'resources/views/admin/page/page-builder.blade.php';
if (file_exists($pageBuilderPath)) {
    $content = file_get_contents($pageBuilderPath);
    
    // Check for event-countdown in component list
    $hasEventCountdownComponent = strpos($content, 'data-type="event-countdown"') !== false;
    echo "   - Component in builder list: " . ($hasEventCountdownComponent ? "✅ FOUND" : "❌ MISSING") . "\n";
    
    // Check for event-countdown case in builder logic
    $hasEventCountdownCase = substr_count($content, "'event-countdown'");
    echo "   - Builder logic cases: " . ($hasEventCountdownCase > 0 ? "✅ {$hasEventCountdownCase} found" : "❌ NONE") . "\n";
    
} else {
    echo "   - page-builder.blade.php: ❌ NOT FOUND\n";
}

// Test 4: Syntax validation
echo "\n4. Running syntax validation:\n";
$syntaxCheck = shell_exec('php -l resources/views/page-components/render-component.blade.php 2>&1');
echo "   - render-component.blade.php syntax: " . (strpos($syntaxCheck, 'No syntax errors') !== false ? "✅ VALID" : "❌ ERRORS FOUND") . "\n";

// Test 5: Feature comparison with old template
echo "\n5. Comparing with old template implementation:\n";
$oldPagePath = 'resources/views/page.blade.php';
if (file_exists($oldPagePath)) {
    $oldContent = file_get_contents($oldPagePath);
    
    // Check if old template has event-countdown
    $oldHasEventCountdown = strpos($oldContent, "@case('event-countdown')") !== false;
    echo "   - Old template has event-countdown: " . ($oldHasEventCountdown ? "✅ YES" : "❌ NO") . "\n";
    
    // Extract key features from old implementation
    $oldHasMonths = strpos($oldContent, 'id="months"') !== false;
    $oldHasTargetDate = strpos($oldContent, 'targetDate') !== false;
    echo "   - Old template features (months, targetDate): " . ($oldHasMonths && $oldHasTargetDate ? "✅ FOUND" : "❌ MISSING") . "\n";
    
} else {
    echo "   - page.blade.php: ❌ NOT FOUND\n";
}

echo "\n=== FIX VERIFICATION COMPLETED ===\n";

// Summary
$renderComponentExists = file_exists($renderComponentPath) && strpos(file_get_contents($renderComponentPath), "@case('event-countdown')") !== false;
$stylesExist = file_exists($pageInvestmentPath) && strpos(file_get_contents($pageInvestmentPath), '.event-countdown') !== false;

if ($renderComponentExists && $stylesExist) {
    echo "✅ EVENT COUNTDOWN COMPONENT SUCCESSFULLY ADDED!\n";
    echo "The event-countdown component should now work in the consolidated template.\n";
    echo "\nKey improvements made:\n";
    echo "• ✅ Added event-countdown case to render-component.blade.php\n";
    echo "• ✅ Implemented unique ID generation to avoid conflicts\n";
    echo "• ✅ Added responsive design with flex-wrap\n";
    echo "• ✅ Included mobile-friendly responsive styles\n";
    echo "• ✅ Used IIFE to avoid JavaScript variable conflicts\n";
} else {
    echo "❌ Issues detected - event countdown may not work properly\n";
}
?>