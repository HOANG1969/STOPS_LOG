<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SupplyRequest;
use App\Models\User;

echo "=== Testing Approval Workflow ===\n";

// Check users
$approver = User::where('email', 'approver@test.com')->first();
if ($approver) {
    echo "✓ Found approver: " . $approver->name . "\n";
} else {
    echo "✗ Approver not found\n";
}

// Check requests
$requests = SupplyRequest::where('status', 'pending')->get();
echo "Found " . $requests->count() . " pending requests\n";

foreach ($requests as $request) {
    echo "Request ID: {$request->id}, Status: {$request->status}, User: {$request->user->name}\n";
    
    // Test canBeApprovedBy method
    if ($approver && $request->canBeApprovedBy($approver)) {
        echo "✓ Approver can approve this request\n";
    } else {
        echo "✗ Approver cannot approve this request\n";
    }
}

echo "\n=== Checking already approved requests ===\n";
$approved = SupplyRequest::where('status', 'approved')->get();
echo "Found " . $approved->count() . " approved requests\n";

foreach ($approved as $request) {
    echo "Request ID: {$request->id}, Status: {$request->status}, Approved by: ";
    echo $request->approver ? $request->approver->name : 'N/A';
    echo ", Approved at: " . ($request->approved_at ? $request->approved_at->format('Y-m-d H:i') : 'N/A') . "\n";
}