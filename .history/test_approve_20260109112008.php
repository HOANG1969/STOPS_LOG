<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing approve API directly:\n";
echo "==============================\n";

// Login as manager
$manager = App\Models\User::where('email', 'manager.it@company.com')->first();
Auth::login($manager);
echo "Logged in as: " . $manager->full_name . "\n\n";

// Get a request to approve
$request = App\Models\SupplyRequest::where('status', 'forwarded')->first();

if (!$request) {
    echo "No forwarded request found!\n";
    exit;
}

echo "Testing request: " . $request->request_code . "\n";
echo "Status: " . $request->status . "\n";
echo "Requester Department: " . $request->requester_department . "\n";

// Test the approve method directly
try {
    $controller = new App\Http\Controllers\SupplyRequestController();
    
    // Create a mock request
    $httpRequest = new Illuminate\Http\Request();
    $httpRequest->merge(['_token' => csrf_token()]);
    
    $result = $controller->approve($httpRequest, $request);
    
    echo "Result: " . $result->getContent() . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}