<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PvgasTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo user test với domain @pvgas.com.vn để test chức năng login
        $testUsers = [
            [
                'name' => 'dang.hv',
                'full_name' => 'Hoàng Văn Đăng',
                'email' => 'dang.hv@pvgas.com.vn',
                'password' => Hash::make('123456'),
                'department' => 'IT',
                'position' => 'Lập trình viên',
                'role' => 'employee',
                'phone' => '0901234100',
                'is_active' => true,
                'is_tchc_checker' => false,
                'is_tchc_manager' => false
            ],
            [
                'name' => 'nguyen.nt',
                'full_name' => 'Nguyễn Thị Nhi',
                'email' => 'nguyen.nt@pvgas.com.vn',
                'password' => Hash::make('123456'),
                'department' => 'Kế toán',
                'position' => 'Nhân viên kế toán',
                'role' => 'employee',
                'phone' => '0901234101',
                'is_active' => true,
                'is_tchc_checker' => false,
                'is_tchc_manager' => false
            ],
            [
                'name' => 'admin.test',
                'full_name' => 'Admin Test',
                'email' => 'admin.test@pvgas.com.vn',
                'password' => Hash::make('123456'),
                'department' => 'IT',
                'position' => 'Quản trị viên',
                'role' => 'admin',
                'phone' => '0901234102',
                'is_active' => true,
                'is_tchc_checker' => false,
                'is_tchc_manager' => false
            ]
        ];

        foreach ($testUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']], 
                $userData
            );
        }

        echo "Đã tạo user test với domain @pvgas.com.vn:\n";
        echo "- dang.hv@pvgas.com.vn / 123456\n";
        echo "- nguyen.nt@pvgas.com.vn / 123456\n";
        echo "- admin.test@pvgas.com.vn / 123456\n";
        echo "\nBây giờ bạn có thể test login chỉ với username:\n";
        echo "- dang.hv\n";
        echo "- nguyen.nt\n";
        echo "- admin.test\n";
    }
}