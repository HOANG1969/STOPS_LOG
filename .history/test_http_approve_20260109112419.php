<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing HTTP approval request:\n";
echo "===============================\n";

// Create session and login manager
Session::start();
$manager = App\Models\User::where('email', 'manager.it@company.com')->first();
Auth::login($manager);

echo "Logged in as: " . $manager->full_name . " (Role: " . $manager->role . ")\n";

// Get a forwarded request
$supplyRequest = App\Models\SupplyRequest::where('status', 'forwarded')->first();

if (!$supplyRequest) {
    echo "No forwarded request found!\n";
    exit;
}

echo "Testing request ID: " . $supplyRequest->id . "\n";
echo "Request code: " . $supplyRequest->request_code . "\n";
echo "Status: " . $supplyRequest->status . "\n\n";

// Create a proper HTTP request like the browser would send
$request = Request::create("/supply-requests/{$supplyRequest->id}/approve", 'POST', [
    '_token' => csrf_token()
]);

// Set headers
$request->headers->set('Accept', 'application/json');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');

try {
    echo "Sending HTTP request...\n";
    $response = app()->handle($request);
    
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response content: " . $response->getContent() . "\n";
    
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}