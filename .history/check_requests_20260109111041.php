<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking supply requests in database:\n";
echo "=====================================\n";

$requests = App\Models\SupplyRequest::all();

if($requests->count() == 0) {
    echo "No supply requests found in database!\n";
} else {
    echo "Total requests: " . $requests->count() . "\n\n";
    
    foreach($requests as $request) {
        echo "ID: " . $request->id . "\n";
        echo "Requester Department: " . $request->requester_department . "\n";
        echo "Status: " . $request->status . "\n";
        echo "Created: " . $request->created_at . "\n";
        echo "-------------------\n";
    }
}

echo "\nUsers in IT department:\n";
echo "======================\n";

$itUsers = App\Models\User::where('department', 'IT')->get();
foreach($itUsers as $user) {
    echo "Name: " . $user->full_name . " - Role: " . $user->role . " - Department: " . $user->department . "\n";
}