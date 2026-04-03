<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplyRequest;
use App\Models\RequestItem;
use App\Models\OfficeSupply;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo employee user nếu chưa có
        $employee = User::firstOrCreate([
            'email' => 'employee@test.com'
        ], [
            'name' => 'Nguyễn Văn A',
            'full_name' => 'Nguyễn Văn A',
            'password' => Hash::make('123456'),
            'department' => 'IT',
            'position' => 'Nhân viên',
            'role' => 'employee',
            'phone' => '0123456789',
            'is_active' => true,
            'is_tchc_checker' => false,
            'is_tchc_manager' => false
        ]);

        // Tạo approver user nếu chưa có  
        $approver = User::firstOrCreate([
            'email' => 'approver@test.com'
        ], [
            'name' => 'Trần Thị B',
            'full_name' => 'Trần Thị B',
            'password' => Hash::make('123456'),
            'department' => 'IT',
            'position' => 'Trưởng phòng IT',
            'role' => 'approver',
            'phone' => '0123456790',
            'is_active' => true,
            'is_tchc_checker' => false,
            'is_tchc_manager' => false
        ]);

        // Tạo office supply nếu chưa có
        $supply = OfficeSupply::firstOrCreate([
            'name' => 'Bút bi'
        ], [
            'description' => 'Bút bi xanh',
            'unit' => 'cái',
            'price' => 5000,
            'stock_quantity' => 100,
            'category' => 'Văn phòng phẩm',
            'is_active' => true
        ]);

        // Tạo supply request với status approved
        $request1 = SupplyRequest::create([
            'request_code' => 'REQ' . date('YmdHis') . '001',
            'user_id' => $employee->id,
            'requester_name' => $employee->name,
            'requester_email' => $employee->email,
            'requester_position' => $employee->position,
            'request_date' => now(),
            'department' => $employee->department,
            'requester_department' => $employee->department,
            'priority' => 'normal',
            'needed_date' => now()->addDays(7),
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'notes' => 'Test request for TCHC workflow'
        ]);

        // Tạo supply request với status tchc_checked để test TCHC Manager
        $tchcChecker = User::where('is_tchc_checker', true)->first();
        $request2 = SupplyRequest::create([
            'request_code' => 'REQ' . date('YmdHis') . '002',
            'user_id' => $employee->id,
            'requester_name' => $employee->name,
            'requester_email' => $employee->email,
            'requester_position' => $employee->position,
            'request_date' => now(),
            'department' => $employee->department,
            'requester_department' => $employee->department,
            'priority' => 'high',
            'needed_date' => now()->addDays(5),
            'status' => 'tchc_checked',
            'approved_by' => $approver->id,
            'approved_at' => now()->subHour(),
            'tchc_checker_id' => $tchcChecker ? $tchcChecker->id : null,
            'tchc_checked_at' => now(),
            'tchc_check_notes' => 'Đã kiểm tra, phiếu hợp lệ',
            'notes' => 'Test request for TCHC Manager workflow'
        ]);

        // Tạo request items cho request1
        RequestItem::create([
            'supply_request_id' => $request1->id,
            'office_supply_id' => $supply->id,
            'quantity' => 10,
            'purpose' => 'Dùng cho văn phòng'
        ]);

        // Tạo request items cho request2
        RequestItem::create([
            'supply_request_id' => $request2->id,
            'office_supply_id' => $supply->id,
            'quantity' => 5,
            'purpose' => 'Dùng cho họp'
        ]);

        echo "Tạo test data thành công:\n";
        echo "- Employee: employee@test.com / 123456\n";
        echo "- Approver: approver@test.com / 123456\n";
        echo "- Request 1 ID: {$request1->id} (status: approved)\n";
        echo "- Request 2 ID: {$request2->id} (status: tchc_checked)\n";
    }
}