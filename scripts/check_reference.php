<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Donation;
use App\Models\EventTicket;
use App\Models\AuctionBid;
use App\Models\Investment;

$id = $argv[1] ?? null;
if (!$id) {
    echo "Usage: php scripts/check_reference.php <id>\n";
    exit(1);
}

function checkModel($class, $id) {
    $found = null;
    // Try normal find
    try {
        $found = $class::find($id);
    } catch (\Throwable $e) {
        echo "Error checking $class: " . $e->getMessage() . "\n";
        return;
    }

    if ($found) {
        echo "$class: FOUND (id={$found->id})\n";
        // Print some helpful fields
        $attrs = ['id'];
        foreach ($attrs as $a) {
            if (isset($found->$a)) {
                echo "  $a: " . $found->$a . "\n";
            }
        }
    } else {
        // Try withTrashed if available
        if (method_exists($class, 'withTrashed')) {
            $found = $class::withTrashed()->find($id);
            if ($found) {
                echo "$class: FOUND (trashed) (id={$found->id})\n";
                return;
            }
        }
        echo "$class: NOT FOUND\n";
    }
}

checkModel(Donation::class, $id);
checkModel(EventTicket::class, $id);
checkModel(AuctionBid::class, $id);
checkModel(Investment::class, $id);

// Also check crypto_payments referencing this id
use App\Models\CryptoPayment;
$cp = CryptoPayment::where('reference_id', $id)->get();
if ($cp->count()) {
    echo "CryptoPayment(s) referencing id $id: found count " . $cp->count() . "\n";
    foreach ($cp as $c) {
        echo "  charge_code: " . ($c->charge_code ?? 'N/A') . " status: " . ($c->status ?? 'N/A') . "\n";
    }
} else {
    echo "CryptoPayment referencing id $id: none found\n";
}

