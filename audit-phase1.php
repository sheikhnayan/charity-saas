<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PHASE 1 IMPLEMENTATION AUDIT ===\n\n";

// 1. Analytics System Check
echo "1. ANALYTICS DASHBOARD\n";
echo "   Routes exist: ";
echo Route::has('analytics.dashboard') ? "✅ YES\n" : "❌ NO\n";

echo "   Analytics events: " . DB::table('analytics_events')->count() . "\n";
echo "   Recent event: ";
$recent = DB::table('analytics_events')->orderBy('created_at', 'desc')->first();
if ($recent) {
    echo date('Y-m-d H:i:s', strtotime($recent->created_at)) . "\n";
} else {
    echo "None\n";
}

// 2. UTM Tracking Check
echo "\n2. UTM TRACKING\n";
$utmCount = DB::table('analytics_events')->whereNotNull('utm_source')->count();
echo "   Events with UTM: " . $utmCount . "\n";
echo "   UTM implementation: " . ($utmCount > 0 ? "✅ WORKING" : "⚠️ NO DATA") . "\n";

if ($utmCount > 0) {
    $utmSample = DB::table('analytics_events')->whereNotNull('utm_source')->first();
    echo "   Sample UTM:\n";
    echo "     - Source: " . ($utmSample->utm_source ?? 'N/A') . "\n";
    echo "     - Medium: " . ($utmSample->utm_medium ?? 'N/A') . "\n";
    echo "     - Campaign: " . ($utmSample->utm_campaign ?? 'N/A') . "\n";
}

// 3. Geographic Data Check
echo "\n3. GEOGRAPHIC DATA\n";
$geoCount = DB::table('analytics_events')->whereNotNull('country')->count();
echo "   Events with location: " . $geoCount . "\n";
echo "   Geographic tracking: " . ($geoCount > 0 ? "✅ WORKING" : "⚠️ NO DATA") . "\n";

// 4. Payment Funnel Check
echo "\n4. PAYMENT FUNNEL TRACKING\n";
echo "   Transactions: " . DB::table('transactions')->count() . "\n";
echo "   Donations: " . DB::table('donations')->count() . "\n";
echo "   Conversion funnel API: ";
echo Route::has('analytics.api.funnel') ? "✅ EXISTS\n" : "❌ MISSING\n";

// Check if funnel stages are tracked
$funnelStages = [
    'page_view' => DB::table('analytics_events')->where('event_type', 'page_view')->count(),
    'donation_started' => DB::table('analytics_events')->where('event_type', 'donation_started')->count(),
    'payment_initiated' => DB::table('analytics_events')->where('event_type', 'payment_initiated')->count(),
    'donation_completed' => DB::table('donations')->count(),
];

echo "   Funnel stages:\n";
foreach ($funnelStages as $stage => $count) {
    echo "     - " . ucfirst(str_replace('_', ' ', $stage)) . ": $count\n";
}

// 5. Device/Browser Tracking
echo "\n5. DEVICE & BROWSER TRACKING\n";
$deviceCount = DB::table('analytics_events')->whereNotNull('device')->count();
$browserCount = DB::table('analytics_events')->whereNotNull('browser')->count();
echo "   Events with device: " . $deviceCount . "\n";
echo "   Events with browser: " . $browserCount . "\n";
echo "   Device tracking: " . ($deviceCount > 0 ? "✅ WORKING" : "⚠️ NO DATA") . "\n";

// 6. Conversion Rates
echo "\n6. CONVERSION TRACKING\n";
$pageViews = DB::table('analytics_events')->where('event_type', 'page_view')->count();
$donations = DB::table('donations')->count();
$conversionRate = $pageViews > 0 ? round(($donations / $pageViews) * 100, 2) : 0;
echo "   Page views: $pageViews\n";
echo "   Conversions: $donations\n";
echo "   Conversion rate: {$conversionRate}%\n";
echo "   Conversion calculation: " . ($pageViews > 0 ? "✅ WORKING" : "⚠️ NO DATA") . "\n";

// 7. Scheduled Reporting
echo "\n7. SCHEDULED REPORTING\n";
$hasReportTable = Schema::hasTable('scheduled_reports');
echo "   Report table exists: " . ($hasReportTable ? "✅ YES" : "❌ NO") . "\n";
if ($hasReportTable) {
    echo "   Scheduled reports: " . DB::table('scheduled_reports')->count() . "\n";
}

// 8. Export Functionality
echo "\n8. EXPORT FUNCTIONALITY\n";
$exportRoutes = ['analytics.export.csv', 'analytics.export.excel', 'analytics.export.pdf'];
$hasExport = false;
foreach ($exportRoutes as $route) {
    if (Route::has($route)) {
        echo "   Export route ({$route}): ✅ EXISTS\n";
        $hasExport = true;
    }
}
if (!$hasExport) {
    echo "   Export functionality: ❌ NOT IMPLEMENTED\n";
}

// 9. Fraud Detection
echo "\n9. FRAUD DETECTION\n";
$hasFraudTable = Schema::hasTable('fraud_rules') || Schema::hasTable('fraud_detections');
echo "   Fraud tables exist: " . ($hasFraudTable ? "✅ YES" : "❌ NO") . "\n";

// 10. Controllers Check
echo "\n10. CONTROLLERS STATUS\n";
$controllers = [
    'DashboardController' => 'App\Http\Controllers\Analytics\DashboardController',
    'PaymentAnalyticsController' => 'App\Http\Controllers\Admin\PaymentMethodAnalyticsController',
];

foreach ($controllers as $name => $class) {
    echo "   {$name}: " . (class_exists($class) ? "✅ EXISTS" : "❌ MISSING") . "\n";
}

// Summary
echo "\n=== SUMMARY ===\n";
echo "✅ COMPLETED:\n";
$completed = [];
$incomplete = [];

if (Route::has('analytics.dashboard')) $completed[] = "Analytics dashboard routes";
if ($utmCount > 0) $completed[] = "UTM tracking (has data)";
if ($geoCount > 0) $completed[] = "Geographic tracking (has data)";
if ($deviceCount > 0) $completed[] = "Device/Browser tracking (has data)";
if (Route::has('analytics.api.funnel')) $completed[] = "Conversion funnel API";
if ($pageViews > 0) $completed[] = "Conversion rate calculation";

if (!$hasReportTable) $incomplete[] = "Scheduled reporting system";
if (!$hasExport) $incomplete[] = "Export functionality (CSV/Excel)";
if (!$hasFraudTable) $incomplete[] = "Fraud detection system";
if ($utmCount == 0) $incomplete[] = "UTM tracking (no data yet)";

foreach ($completed as $item) {
    echo "  - $item\n";
}

echo "\n⚠️ NEEDS WORK:\n";
foreach ($incomplete as $item) {
    echo "  - $item\n";
}

echo "\n";
