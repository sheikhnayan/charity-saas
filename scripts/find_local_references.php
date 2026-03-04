<?php

// Bootstrap Laravel to run model queries outside artisan
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function idOrNone($class)
{
    if (!class_exists($class)) {
        return 'none (class missing)';
    }

    try {
        $rec = $class::latest()->first();
        return $rec ? $rec->id : 'none';
    } catch (\Throwable $e) {
        return 'none (error)';
    }
}

echo "donation:" . idOrNone(\App\Models\Donation::class) . PHP_EOL;
echo "event_ticket:" . idOrNone(\App\Models\EventTicket::class) . PHP_EOL;
echo "auction_bid:" . idOrNone(\App\Models\AuctionBid::class) . PHP_EOL;
echo "investment:" . idOrNone(\App\Models\Investment::class) . PHP_EOL;

// Print some helpful SQL examples for testing
echo PHP_EOL;
echo "SQL to select record by id (replace <table> and <id>):\n";
echo "SELECT * FROM <table> WHERE id = <id>;\n";
