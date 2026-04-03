<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Debugging canBeApprovedBy method:\n";
echo "=================================\n";

// Get manager and request
$manager = App\Models\User::where('email', 'manager.it@company.com')->first();
$request = App\Models\SupplyRequest::where('status', 'forwarded')->first();

echo "Manager: " . $manager->full_name . "\n";
echo "Manager department: " . $manager->department . "\n";
echo "Manager role: " . $manager->role . "\n\n";

echo "Request: " . $request->request_code . "\n";
echo "Request status: " . $request->status . "\n";
echo "Request department: " . $request->requester_department . "\n\n";

// Test step by step
echo "Testing canApprove:\n";
$canApprove = $manager->canApprove($request);
echo "Manager canApprove: " . ($canApprove ? 'YES' : 'NO') . "\n";

echo "\nTesting isApprover:\n";
echo "Manager isApprover: " . ($manager->isApprover() ? 'YES' : 'NO') . "\n";

echo "\nTesting department match:\n";
echo "Department match: " . ($manager->department === $request->requester_department ? 'YES' : 'NO') . "\n";

echo "\nTesting status:\n";
echo "Status is pending or forwarded: " . (in_array($request->status, ['pending', 'forwarded']) ? 'YES' : 'NO') . "\n";

echo "\nTesting canBeApprovedBy:\n";
$canBeApproved = $request->canBeApprovedBy($manager);
echo "Can be approved: " . ($canBeApproved ? 'YES' : 'NO') . "\n";