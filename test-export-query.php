<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test Export Query ===\n\n";

// Test 1: All request items
$allItems = App\Models\RequestItem::with(['officeSupply', 'supplyRequest.user'])->get();
echo "Total all items: " . $allItems->count() . "\n";

// Test 2: KTSX items
$ktsxItems = App\Models\RequestItem::with(['officeSupply', 'supplyRequest.user'])
    ->whereHas('supplyRequest', function($q) {
        $q->where('requester_department', 'KTSX');
    })->get();
echo "Total KTSX items: " . $ktsxItems->count() . "\n";

// Test 3: KTSX tháng 2/2026
$ktsxFeb2026 = App\Models\RequestItem::with(['officeSupply', 'supplyRequest.user'])
    ->whereHas('supplyRequest', function($q) {
        $q->where('requester_department', 'KTSX')
          ->whereYear('created_at', 2026)
          ->whereMonth('created_at', 2);
    })->get();
echo "Total KTSX Feb 2026: " . $ktsxFeb2026->count() . "\n\n";

if ($ktsxFeb2026->count() > 0) {
    echo "Details:\n";
    foreach($ktsxFeb2026 as $item) {
        echo "- Item ID: " . $item->id . "\n";
        echo "  Office Supply: " . ($item->officeSupply ? $item->officeSupply->name : 'NULL') . "\n";
        echo "  Department: " . ($item->supplyRequest ? $item->supplyRequest->requester_department : 'NULL') . "\n";
        echo "  Created: " . ($item->supplyRequest ? $item->supplyRequest->created_at : 'NULL') . "\n";
        echo "  Quantity: " . $item->quantity . "\n\n";
    }
}

// Test Export class
echo "=== Test Export Class ===\n";
$export = new App\Exports\SupplyItemsExport($ktsxFeb2026);
$collection = $export->collection();
echo "Collection count: " . $collection->count() . "\n";
echo "Headings: " . implode(', ', $export->headings()) . "\n";

if ($collection->count() > 0) {
    echo "\nFirst row data:\n";
    $firstRow = $export->map($collection->first());
    print_r($firstRow);
}
