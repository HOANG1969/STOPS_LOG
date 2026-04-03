<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks for MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing users first
        User::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // IT Approver (Trưởng phòng IT để phê duyệt)
        User::create([
            'name' => 'it_manager',
            'full_name' => 'Trần Văn Nam',
            'email' => 'manager.it@company.com',
            'password' => bcrypt('123456'),
            'department' => 'IT',
            'position' => 'Trưởng phòng IT',
            'role' => 'approver',
            'phone' => '0901234001',
            'is_active' => true,
        ]);

        // IT Employee (Nhân viên để tạo yêu cầu)
        User::create([
            'name' => 'it_employee',
            'full_name' => 'Vũ Minh Tuấn',
            'email' => 'employee.it@company.com',
            'password' => bcrypt('123456'),
            'department' => 'IT',
            'position' => 'Lập trình viên',
            'role' => 'employee',
            'phone' => '0901234002',
            'is_active' => true,
        ]);

        // TCHC Checker (Nhân sự TCHC kiểm tra)
        User::create([
            'name' => 'tchc_checker',
            'full_name' => 'Nguyễn Thị Hoa',
            'email' => 'checker.tchc@company.com',
            'password' => bcrypt('123456'),
            'department' => 'TCHC',
            'position' => 'Chuyên viên TCHC',
            'role' => 'employee',
            'phone' => '0901234003',
            'is_active' => true,
            'is_tchc_checker' => true,
        ]);

        // TCHC Manager (Lãnh đạo phòng TCHC phê duyệt)
        User::create([
            'name' => 'tchc_manager',
            'full_name' => 'Lê Văn Dũng',
            'email' => 'manager.tchc@company.com',
            'password' => bcrypt('123456'),
            'department' => 'TCHC',
            'position' => 'Trưởng phòng TCHC',
            'role' => 'approver',
            'phone' => '0901234004',
            'is_active' => true,
            'is_tchc_manager' => true,
        ]);
    }
}
