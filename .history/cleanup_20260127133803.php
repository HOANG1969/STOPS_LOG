<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OfficeSupply;

// Xóa tất cả records trống
$emptyRecords = OfficeSupply::whereNull('name')
    ->orWhere('name', '')
    ->orWhere('name', '0')
    ->orWhere('price', 0)
    ->get();

echo "Found " . $emptyRecords->count() . " empty records\n";

foreach ($emptyRecords as $record) {
    echo "Deleting record ID: " . $record->id . " - name: '" . $record->name . "'\n";
    $record->delete();
}

echo "Cleanup completed!\n";

// Hiển thị số lượng còn lại
echo "Remaining records: " . OfficeSupply::count() . "\n";