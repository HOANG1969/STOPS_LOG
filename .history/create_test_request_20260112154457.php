<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SupplyRequest;
use App\Models\RequestItem;
use App\Models\User;
use App\Models\OfficeSupply;

echo "=== Creating New Pending Request ===\n";

// Get employee and supplies
$employee = User::where('email', 'employee@test.com')->first();
$supply = OfficeSupply::first();

if (!$employee) {
    echo "✗ Employee not found\n";
    exit;
}

if (!$supply) {
    echo "✗ Office supply not found\n";
    exit;
}

// Create new request
$request = SupplyRequest::create([
    'request_code' => SupplyRequest::generateRequestCode(),
    'user_id' => $employee->id,
    'requester_name' => $employee->name,
    'requester_email' => $employee->email,
    'requester_position' => $employee->position ?? 'Nhân viên',
    'requester_department' => $employee->department ?? 'IT',
    'priority' => 'normal',
    'status' => 'pending',
    'needed_date' => now()->addDays(7),
    'notes' => 'Test request for approval workflow',
]);

// Add request item
RequestItem::create([
    'supply_request_id' => $request->id,
    'office_supply_id' => $supply->id,
    'quantity' => 5,
    'purpose' => 'Testing approval workflow'
]);

echo "✓ Created new request:\n";
echo "   ID: {$request->id}\n";
echo "   Status: {$request->status}\n";
echo "   User: {$request->requester_name}\n";
echo "   Department: {$request->requester_department}\n";
echo "\n";
echo "Now you can test approval at: /supply-requests/{$request->id}\n";
echo "Login as approver: approver@test.com / 123456\n";