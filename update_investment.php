<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$website = App\Models\Website::find(1);
$website->share_price = 10.00;
$website->min_investment = 100.00;
$website->investment_tiers = json_encode([
    ['amount' => 100, 'label' => '$100'],
    ['amount' => 500, 'label' => '$500'],
    ['amount' => 1000, 'label' => '$1,000'],
    ['amount' => 5000, 'label' => '$5,000']
]);
$website->save();

echo "Updated website with share price: $10.00, min investment: $100.00\n";
echo "Investment tiers: " . $website->investment_tiers . "\n";
?>