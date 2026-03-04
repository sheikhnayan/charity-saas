<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Website;
use App\Models\WebsitePaymentSetting;

// Get the first website
$website = Website::first();

if (!$website) {
    echo "❌ No websites found in database\n";
    exit;
}

echo "📋 Website: " . $website->name . " (ID: " . $website->id . ")\n";
echo "📋 Domain: " . $website->domain . "\n\n";

// Check payment settings
echo "=== WEBSITE PAYMENT SETTINGS ===\n";
$paymentSetting = $website->paymentSettings;

if (!$paymentSetting) {
    echo "❌ No WebsitePaymentSetting record found for this website\n";
    echo "📌 Creating a default one...\n";
    $paymentSetting = WebsitePaymentSetting::create([
        'website_id' => $website->id,
        'payment_method' => 'authorize',
        'is_active' => true,
    ]);
    echo "✅ Created default payment setting with ID: " . $paymentSetting->id . "\n";
} else {
    echo "✅ Found WebsitePaymentSetting (ID: " . $paymentSetting->id . ")\n";
    echo "   Payment Method: " . $paymentSetting->payment_method . "\n";
    echo "   Is Active: " . ($paymentSetting->is_active ? 'YES' : 'NO') . "\n";
    echo "   Authorize Login ID: " . ($paymentSetting->authorize_login_id ? '✅ SET' : '❌ NOT SET') . "\n";
    echo "   Authorize Transaction Key: " . ($paymentSetting->authorize_transaction_key ? '✅ SET' : '❌ NOT SET') . "\n";
    echo "   Authorize Sandbox: " . ($paymentSetting->authorize_sandbox ? 'YES' : 'NO') . "\n";
}

// Check fallback settings
echo "\n=== FALLBACK SETTINGS (Settings Table) ===\n";
$setting = $website->setting;

if (!$setting) {
    echo "❌ No Setting record found for this website\n";
} else {
    echo "✅ Found Setting record\n";
    echo "   Payment Method: " . $setting->payment_method . "\n";
    echo "   Authorize Login ID: " . ($setting->authorize_login_id ? '✅ SET' : '❌ NOT SET') . "\n";
    echo "   Authorize Transaction Key: " . ($setting->authorize_transaction_key ? '✅ SET' : '❌ NOT SET') . "\n";
}

// Check what getPaymentConfig returns
echo "\n=== ACTUAL CONFIG BEING RETURNED ===\n";
$config = $website->getPaymentConfig();
$method = $website->getPaymentMethod();

echo "Payment Method: " . $method . "\n";
echo "Config returned:\n";
echo json_encode($config, JSON_PRETTY_PRINT) . "\n";

// Check validation
echo "\n=== VALIDATION CHECK ===\n";
$errors = [];
if (empty($config['login_id'])) {
    $errors[] = 'Authorize.net login ID is required';
}
if (empty($config['transaction_key'])) {
    $errors[] = 'Authorize.net transaction key is required';
}

if (!empty($errors)) {
    echo "❌ VALIDATION ERRORS:\n";
    foreach ($errors as $error) {
        echo "   - " . $error . "\n";
    }
} else {
    echo "✅ All validations passed!\n";
}
