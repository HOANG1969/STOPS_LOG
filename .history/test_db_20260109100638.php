<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing database connection...\n";

try {
    $users = App\Models\User::count();
    echo "Users count: $users\n";
    
    echo "Checking SupplyRequest table columns...\n";
    $columns = Illuminate\Support\Facades\Schema::getColumnListing('supply_requests');
    echo "Columns: " . implode(', ', $columns) . "\n";
    
    echo "Testing SupplyRequest code generation...\n";
    $code = App\Models\SupplyRequest::generateRequestCode();
    echo "Generated code: $code\n";
    
    echo "Testing create SupplyRequest...\n";
    $firstUser = App\Models\User::first();
    if ($firstUser) {
        $data = [
            'request_code' => $code,
            'user_id' => $firstUser->id,
            'requester_name' => $firstUser->name,
            'requester_email' => $firstUser->email,
            'requester_department' => $firstUser->department ?? 'IT',
            'requester_position' => $firstUser->position ?? 'Employee',
            'request_date' => now()->toDateString(),
            'priority' => 'normal',
            'status' => 'draft',
            'notes' => 'Test request'
        ];
        
        $request = App\Models\SupplyRequest::create($data);
        echo "Created SupplyRequest ID: " . $request->id . "\n";
        
        // Delete test request
        $request->delete();
        echo "Test request deleted\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}