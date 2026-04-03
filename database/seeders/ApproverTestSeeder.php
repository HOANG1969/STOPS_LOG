<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SupplyRequest;
use App\Models\OfficeSupply;
use App\Models\RequestItem;

class ApproverTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Creating approver test data...\n";
        
        // Tạo approver user
        $approver = User::firstOrCreate([
            'email' => 'approver@test.com'
        ], [
            'name' => 'Tran Van Nam',
            'password' => bcrypt('123456'),
            'department' => 'IT',
            'position' => 'Trưởng phòng IT',
            'is_approver' => true
        ]);
        
        echo "Approver created: {$approver->email}\n";
        
        // Tạo employee user
        $employee = User::firstOrCreate([
            'email' => 'it.employee@test.com'
        ], [
            'name' => 'IT Employee',
            'password' => bcrypt('123456'),
            'department' => 'IT',
            'position' => 'Lập trình viên'
        ]);
        
        echo "Employee created: {$employee->email}\n";
        
        // Tạo office supply
        $supply = OfficeSupply::firstOrCreate([
            'name' => 'Bút bi'
        ], [
            'category' => 'Văn phòng phẩm',
            'description' => 'Bút bi xanh', 
            'unit' => 'cái',
            'price' => 5000,
            'stock_quantity' => 100,
            'is_active' => true
        ]);
        
        // Tạo pending request
        $request = SupplyRequest::create([
            'request_code' => 'REQ' . time(),
            'user_id' => $employee->id,
            'requester_name' => $employee->name,
            'requester_email' => $employee->email,
            'requester_department' => 'IT',
            'status' => 'pending',
            'priority' => 'normal',
            'request_date' => now(),
            'needed_date' => now()->addDays(7)
        ]);
        
        // Tạo request item
        RequestItem::create([
            'supply_request_id' => $request->id,
            'office_supply_id' => $supply->id,
            'quantity' => 5,
            'purpose' => 'Dùng cho văn phòng'
        ]);
        
        echo "Created pending request ID: {$request->id}\n";
        echo "Approver isApprover: " . ($approver->isApprover() ? 'Yes' : 'No') . "\n";
        echo "Test data created successfully!\n";
    }
}