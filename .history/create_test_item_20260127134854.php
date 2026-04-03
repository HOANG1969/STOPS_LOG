<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OfficeSupply;

try {
    $item = OfficeSupply::create([
        'name' => 'Test Delete Item - ' . date('Y-m-d H:i:s'),
        'description' => 'This is for testing delete functionality',
        'unit' => 'Cái',
        'price' => 1000,
        'stock_quantity' => 1,
        'category' => 'Test',
        'is_active' => true
    ]);
    
    echo "Test item created successfully with ID: " . $item->id . "\n";
    echo "Name: " . $item->name . "\n";
} catch (Exception $e) {
    echo "Error creating test item: " . $e->getMessage() . "\n";
}