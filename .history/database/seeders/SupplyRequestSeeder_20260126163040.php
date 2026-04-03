<?php

namespace Database\Seeders;

use App\Models\SupplyRequest;
use App\Models\RequestItem;
use App\Models\User;
use App\Models\OfficeSupply;
use Illuminate\Database\Seeder;

class SupplyRequestSeeder extends Seeder
{
    public function run()
    {
        $employee = User::where('email', 'employee.it@company.com')->first();
        $approver = User::where('role', 'approver')->first();
        $supplies = OfficeSupply::take(5)->get();
        
        if ($employee && $supplies->count() > 0) {
            // Tạo yêu cầu chờ phê duyệt
            $request1 = SupplyRequest::create([
                'request_code' => 'VP001',
                'user_id' => $employee->id,
                'requester_name' => $employee->full_name ?? $employee->name,
                'requester_email' => $employee->email,
                'department' => $employee->department,
                'requester_department' => $employee->department,
                'priority' => 'Normal',
                'needed_date' => now()->addDays(7),
                'notes' => 'Văn phòng phẩm cho dự án mới Q1/2026',
                'status' => 'pending',
                'created_at' => now()->subDays(2),
            ]);

            foreach ($supplies->take(3) as $supply) {
                RequestItem::create([
                    'supply_request_id' => $request1->id,
                    'office_supply_id' => $supply->id,
                    'quantity' => rand(5, 20),
                    'purpose' => 'Dùng cho công việc hàng ngày',
                ]);
            }

            // Tạo yêu cầu đã phê duyệt
            $request2 = SupplyRequest::create([
                'request_code' => 'VP002',
                'user_id' => $employee->id,
                'requester_name' => $employee->full_name ?? $employee->name,
                'requester_email' => $employee->email,
                'department' => $employee->department,
                'requester_department' => $employee->department,
                'priority' => 'High',
                'needed_date' => now()->addDays(3),
                'notes' => 'Bổ sung văn phòng phẩm tháng 12',
                'status' => 'approved',
                'approved_by' => $approver?->id,
                'approved_at' => now()->subDays(1),
                'approval_notes' => 'Đã phê duyệt',
                'created_at' => now()->subDays(5),
            ]);

            foreach ($supplies->take(2) as $supply) {
                RequestItem::create([
                    'supply_request_id' => $request2->id,
                    'office_supply_id' => $supply->id,
                    'quantity' => rand(10, 30),
                    'purpose' => 'Dùng cho báo cáo cuối năm',
                ]);
            }

            // Tạo thêm một yêu cầu urgency
            $request3 = SupplyRequest::create([
                'request_code' => 'VP003',
                'user_id' => $employee->id,
                'requester_name' => $employee->full_name ?? $employee->name,
                'requester_email' => $employee->email,
                'department' => $employee->department,
                'requester_department' => $employee->department,
                'priority' => 'Urgent',
                'needed_date' => now()->addDays(1),
                'notes' => 'Cần gấp cho báo cáo cuối tháng',
                'status' => 'pending',
                'created_at' => now()->subHours(6),
            ]);

            foreach ($supplies->take(2) as $supply) {
                RequestItem::create([
                    'supply_request_id' => $request3->id,
                    'office_supply_id' => $supply->id,
                    'quantity' => rand(5, 15),
                    'purpose' => 'Cần gấp làm báo cáo',
                ]);
            }
        }
    }
}