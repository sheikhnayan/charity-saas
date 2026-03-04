<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update payment_funnel_events with location data
$countries = [
    ['code' => 'US', 'name' => 'United States', 'weight' => 40],
    ['code' => 'CA', 'name' => 'Canada', 'weight' => 25],
    ['code' => 'GB', 'name' => 'United Kingdom', 'weight' => 20],
    ['code' => 'AU', 'name' => 'Australia', 'weight' => 15],
];

$states = [
    'US' => ['California', 'New York', 'Texas', 'Florida', 'Illinois'],
    'CA' => ['Ontario', 'Quebec', 'British Columbia', 'Alberta', 'Manitoba'],
    'GB' => ['England', 'Scotland', 'Wales', 'Northern Ireland'],
    'AU' => ['New South Wales', 'Victoria', 'Queensland', 'Western Australia'],
];

$events = DB::table('payment_funnel_events')->whereNull('country_code')->get();

echo "Updating " . $events->count() . " records...\n";

foreach ($events as $event) {
    $rand = rand(1, 100);
    $cumulative = 0;
    $selectedCountry = $countries[0];
    
    foreach ($countries as $country) {
        $cumulative += $country['weight'];
        if ($rand <= $cumulative) {
            $selectedCountry = $country;
            break;
        }
    }
    
    $countryCode = $selectedCountry['code'];
    $countryName = $selectedCountry['name'];
    $state = $states[$countryCode][array_rand($states[$countryCode])];
    
    DB::table('payment_funnel_events')
        ->where('id', $event->id)
        ->update([
            'country_code' => $countryCode,
            'country' => $countryName,
            'state' => $state,
            'city' => null
        ]);
}

$updated = DB::table('payment_funnel_events')->whereNotNull('country_code')->count();
echo "Successfully updated! Total records with location: $updated\n";
