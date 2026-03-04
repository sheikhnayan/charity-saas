<?php

require_once 'vendor/autoload.php';

// Boot Laravel
$app = require_once 'bootstrap/app.php';

// Get all websites
$websites = App\Models\Website::all();

echo "Total websites: " . $websites->count() . "\n";

foreach ($websites as $website) {
    echo "Website ID: {$website->id}, Name: {$website->name}, Domain: {$website->domain}, Type: {$website->type}\n";
}

// If we have websites, update the first one to be investment type for testing
if ($websites->count() > 0) {
    $firstWebsite = $websites->first();
    $firstWebsite->type = 'investment';
    $firstWebsite->save();
    echo "\nUpdated website '{$firstWebsite->name}' to investment type for testing.\n";
}
