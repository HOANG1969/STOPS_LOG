<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Debugging approval permissions:\n";
echo "=================================\n";

// Check current manager
$manager = App\Models\User::where('email', 'manager.it@company.com')->first();
echo "Manager Info:\n";
echo "Name: " . $manager->full_name . "\n";
echo "Department: " . $manager->department . "\n";
echo "Role: " . $manager->role . "\n\n";

// Check supply requests
$requests = App\Models\SupplyRequest::whereIn('status', ['pending', 'forwarded'])->get();

echo "Supply Requests:\n";
foreach($requests as $request) {
    echo "ID: " . $request->id . "\n";
    echo "Request Code: " . $request->request_code . "\n";
    echo "Requester Department: " . $request->requester_department . "\n";
    echo "Status: " . $request->status . "\n";
    echo "Can be approved by manager: " . ($manager->canApprove($request) ? 'YES' : 'NO') . "\n";
    echo "canBeApprovedBy check: " . ($request->canBeApprovedBy($manager) ? 'YES' : 'NO') . "\n";
    echo "-------------------\n";
}