<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo tài khoản admin
        User::create([
            'name' => 'admin',
            'full_name' => 'System Administrator',
            'email' => 'admin@company.com',
            'password' => Hash::make('admin123'),
            'department' => 'IT',
            'position' => 'System Administrator',
            'role' => 'admin',
            'phone' => '0901234000',
            'is_active' => true,
            'is_tchc_checker' => false,
            'is_tchc_manager' => false
        ]);

        echo "Đã tạo tài khoản admin:\n";
        echo "- Email: admin@company.com\n";
        echo "- Password: admin123\n";
    }
}