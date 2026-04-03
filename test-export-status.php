<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test with status='all' ===\n\n";

$status = 'all';

$items = App\Models\RequestItem::with(['officeSupply', 'supplyRequest.user'])
    ->whereHas('supplyRequest', function($q) use ($status) {
        $q->where('requester_department', 'KTSX')
          ->whereYear('created_at', 2026)
          ->whereMonth('created_at', 2);
        
        // Test với status = 'all'
        if ($status && $status !== 'all') {
            $q->where('status', $status);
            echo "Filtering by status: $status\n";
        } else {
            echo "NOT filtering by status (status='all')\n";
        }
    })->get();

echo "Total items: " . $items->count() . "\n";

if ($items->count() > 0) {
    echo "\nDetails:\n";
    foreach($items as $item) {
        echo "- " . $item->officeSupply->name . " (Qty: " . $item->quantity . ") - Status: " . $item->supplyRequest->status . "\n";
    }
}
