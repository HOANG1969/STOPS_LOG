<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TchcUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo TCHC Checker
        User::create([
            'name' => 'Nguyễn Thị Checker',
            'full_name' => 'Nguyễn Thị Checker TCHC',
            'email' => 'tchc.checker@pvgas.com.vn',
            'password' => Hash::make('123456'),
            'department' => 'TCHC',
            'position' => 'Nhân viên TCHC',
            'role' => 'employee',
            'phone' => '0987654321',
            'is_active' => true,
            'is_tchc_checker' => true,
            'is_tchc_manager' => false
        ]);

        // Tạo TCHC Manager
        User::create([
            'name' => 'Lê Văn Manager',
            'full_name' => 'Lê Văn Manager TCHC',
            'email' => 'tchc.manager@pvgas.com.vn',
            'password' => Hash::make('123456'),
            'department' => 'TCHC',
            'position' => 'Trưởng phòng TCHC',
            'role' => 'approver',
            'phone' => '0987654322',
            'is_active' => true,
            'is_tchc_checker' => false,
            'is_tchc_manager' => true
        ]);

        echo "Đã tạo user TCHC:\n";
        echo "- TCHC Checker: tchc.checker@pvgas.com.vn / 123456\n";
        echo "- TCHC Manager: tchc.manager@pvgas.com.vn / 123456\n";
    }
}
