<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$websites = App\Models\Website::where('type', 'investment')->get(['id', 'domain', 'type', 'share_price', 'min_investment']);
echo json_encode($websites->toArray(), JSON_PRETTY_PRINT);
?>