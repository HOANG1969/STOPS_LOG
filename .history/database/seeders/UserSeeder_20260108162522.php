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
        
        // Admin user
        User::create([
            'name' => 'admin',
            'full_name' => 'Quản trị viên hệ thống',
            'email' => 'admin@company.com',
            'password' => bcrypt('password'),
            'department' => 'Quản trị',
            'position' => 'Quản trị viên hệ thống',
            'role' => 'admin',
            'phone' => '0901234567',
            'is_active' => true,
        ]);

        // HR Approver
        User::create([
            'name' => 'hr_approve',
            'full_name' => 'Nguyễn Thị Hạnh',
            'email' => 'hr.approve@company.com',
            'password' => bcrypt('password'),
            'department' => 'Nhân sự',
            'position' => 'Trưởng phòng Nhân sự',
            'role' => 'approver',
            'phone' => '0901234568',
            'is_active' => true,
        ]);

        // IT Approver
        User::create([
            'name' => 'it_approve',
            'full_name' => 'Trần Văn Nam',
            'email' => 'it.approve@company.com',
            'password' => bcrypt('password'),
            'department' => 'IT',
            'position' => 'Trưởng phòng IT',
            'role' => 'approver',
            'phone' => '0901234569',
            'is_active' => true,
        ]);

        // Marketing Approver
        User::create([
            'name' => 'marketing_approve',
            'full_name' => 'Phạm Thị Lan',
            'email' => 'marketing.approve@company.com',
            'password' => bcrypt('password'),
            'department' => 'Marketing',
            'position' => 'Trưởng phòng Marketing',
            'role' => 'approver',
            'phone' => '0901234570',
            'is_active' => true,
        ]);

        // HR Employee
        User::create([
            'name' => 'hr_emp',
            'full_name' => 'Lê Văn Hoàng',
            'email' => 'hr.employee@company.com',
            'password' => bcrypt('password'),
            'department' => 'Nhân sự',
            'position' => 'Nhân viên Nhân sự',
            'role' => 'employee',
            'phone' => '0901234571',
            'is_active' => true,
        ]);

        // IT Employee
        User::create([
            'name' => 'it_emp',
            'full_name' => 'Vũ Minh Tuấn',
            'email' => 'it.employee@company.com',
            'password' => bcrypt('password'),
            'department' => 'IT',
            'position' => 'Lập trình viên',
            'role' => 'employee',
            'phone' => '0901234572',
            'is_active' => true,
        ]);

        // Marketing Employee
        User::create([
            'name' => 'marketing_emp',
            'full_name' => 'Nguyễn Thị Mai',
            'email' => 'marketing.employee@company.com',
            'password' => bcrypt('password'),
            'department' => 'Marketing',
            'position' => 'Nhân viên Marketing',
            'role' => 'employee',
            'phone' => '0901234573',
            'is_active' => true,
        ]);
    }
}
