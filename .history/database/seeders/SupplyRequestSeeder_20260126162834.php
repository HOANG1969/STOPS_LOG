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
        $supplies = OfficeSupply::take(5)->get();
        
        if ($employee && $supplies->count() > 0) {
            // Tạo yêu cầu chờ phê duyệt
            $request1 = SupplyRequest::create([
                'user_id' => $employee->id,
                'requester_department' => $employee->department,
                'requester_position' => $employee->position,
                'reason' => 'Văn phòng phẩm cho dự án mới Q1/2026',
                'period' => 'Q1/2026',
                'status' => 'pending',
                'created_at' => now()->subDays(2),
            ]);

            foreach ($supplies->take(3) as $supply) {
                RequestItem::create([
                    'supply_request_id' => $request1->id,
                    'office_supply_id' => $supply->id,
                    'quantity' => rand(5, 20),
                    'unit_price' => $supply->price,
                ]);
            }

            // Tạo yêu cầu đã phê duyệt
            $request2 = SupplyRequest::create([
                'user_id' => $employee->id,
                'requester_department' => $employee->department,
                'requester_position' => $employee->position,
                'reason' => 'Bổ sung văn phòng phẩm tháng 12',
                'period' => 'Q4/2025',
                'status' => 'approved',
                'approved_by' => User::where('role', 'approver')->first()->id ?? null,
                'approved_at' => now()->subDays(1),
                'created_at' => now()->subDays(5),
            ]);

            foreach ($supplies->take(2) as $supply) {
                RequestItem::create([
                    'supply_request_id' => $request2->id,
                    'office_supply_id' => $supply->id,
                    'quantity' => rand(10, 30),
                    'unit_price' => $supply->price,
                ]);
            }

            // Tạo yêu cầu đã kiểm tra TCHC
            $request3 = SupplyRequest::create([
                'user_id' => $employee->id,
                'requester_department' => $employee->department,
                'requester_position' => $employee->position,
                'reason' => 'Văn phòng phẩm cho phòng IT',
                'period' => 'Q1/2026',
                'status' => 'tchc_checked',
                'approved_by' => User::where('role', 'approver')->first()->id ?? null,
                'approved_at' => now()->subDays(3),
                'tchc_checked_by' => User::where('is_tchc_checker', true)->first()->id ?? null,
                'tchc_checked_at' => now()->subDays(1),
                'created_at' => now()->subDays(7),
            ]);

            foreach ($supplies->take(4) as $supply) {
                RequestItem::create([
                    'supply_request_id' => $request3->id,
                    'office_supply_id' => $supply->id,
                    'quantity' => rand(5, 15),
                    'unit_price' => $supply->price,
                ]);
            }
        }
    }
}