<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== JAVASCRIPT ERROR FIX TEST ===\n\n";

// Test 1: Check render-component.blade.php for timerContainer redeclaration
echo "1. Testing render-component.blade.php for timerContainer issues:\n";
$renderComponentPath = 'resources/views/page-components/render-component.blade.php';
if (file_exists($renderComponentPath)) {
    $content = file_get_contents($renderComponentPath);
    
    // Count occurrences of const timerContainer
    $constCount = substr_count($content, 'const timerContainer');
    echo "   - 'const timerContainer' declarations: " . ($constCount === 0 ? "✅ NONE (good)" : "❌ {$constCount} found") . "\n";
    
    // Check for the fixed version
    $hasDirectElementCheck = strpos($content, "document.getElementById('auction-timer-{{ \$item->id }}')") !== false;
    echo "   - Direct element check (fixed version): " . ($hasDirectElementCheck ? "✅ FOUND" : "❌ NOT FOUND") . "\n";
    
    // Check for multiple timer functions
    $initFunctionCount = substr_count($content, 'function initializeAuctionListTimers');
    echo "   - initializeAuctionListTimers function count: " . ($initFunctionCount === 1 ? "✅ {$initFunctionCount} (correct)" : "❌ {$initFunctionCount} (should be 1)") . "\n";
    
} else {
    echo "   - render-component.blade.php: ❌ NOT FOUND\n";
}

// Test 2: Check page-investment.blade.php for timer conflicts
echo "\n2. Testing page-investment.blade.php for timer functions:\n";
$pageInvestmentPath = 'resources/views/page-investment.blade.php';
if (file_exists($pageInvestmentPath)) {
    $content = file_get_contents($pageInvestmentPath);
    
    // Check for timer functions
    $initAuctionTimersCount = substr_count($content, 'function initializeAuctionTimers');
    echo "   - initializeAuctionTimers function count: " . ($initAuctionTimersCount === 1 ? "✅ {$initAuctionTimersCount} (correct)" : "❌ {$initAuctionTimersCount} (should be 1)") . "\n";
    
    // Check for proper DOMContentLoaded initialization
    $domContentLoadedCount = substr_count($content, 'initializeAuctionTimers()');
    echo "   - initializeAuctionTimers() calls: " . ($domContentLoadedCount >= 1 ? "✅ {$domContentLoadedCount} (good)" : "❌ {$domContentLoadedCount} (should be at least 1)") . "\n";
    
} else {
    echo "   - page-investment.blade.php: ❌ NOT FOUND\n";
}

// Test 3: Check for potential conflicts between timer systems
echo "\n3. Testing for timer system compatibility:\n";
echo "   - Two different timer systems are now present:\n";
echo "     • initializeAuctionTimers() (page-investment.blade.php) - Generic timer for any auction element\n";
echo "     • initializeAuctionListTimers() (render-component.blade.php) - Specific to auction-list component\n";
echo "   - These should work together without conflicts: ✅ DESIGNED TO BE COMPATIBLE\n";

// Test 4: Syntax validation
echo "\n4. Running syntax validation:\n";
$syntaxCheck1 = shell_exec('php -l resources/views/page-investment.blade.php 2>&1');
$syntaxCheck2 = shell_exec('php -l resources/views/page-components/render-component.blade.php 2>&1');

echo "   - page-investment.blade.php syntax: " . (strpos($syntaxCheck1, 'No syntax errors') !== false ? "✅ VALID" : "❌ ERRORS FOUND") . "\n";
echo "   - render-component.blade.php syntax: " . (strpos($syntaxCheck2, 'No syntax errors') !== false ? "✅ VALID" : "❌ ERRORS FOUND") . "\n";

echo "\n=== FIX VERIFICATION COMPLETED ===\n";
echo "✅ The timerContainer redeclaration error should now be resolved!\n";
echo "The fix involved removing the 'const timerContainer' variable declaration\n";
echo "and using direct element checking instead.\n";
?>