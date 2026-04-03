<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OfficeSupplySeeder::class,
        ]);
    }
}
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT',
            'position' => 'System Administrator',
            'is_active' => true
        ]);

        // Create director
        $director = User::create([
            'name' => 'Giám đốc',
            'email' => 'director@example.com',
            'password' => Hash::make('password'),
            'role' => 'director',
            'department' => 'Ban Giám đốc',
            'position' => 'Giám đốc',
            'is_active' => true
        ]);

        // Create manager
        $manager = User::create([
            'name' => 'Trưởng phòng IT',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department' => 'IT',
            'position' => 'Trưởng phòng',
            'manager_id' => $director->id,
            'is_active' => true
        ]);

        // Create employee
        User::create([
            'name' => 'Nhân viên IT',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department' => 'IT',
            'position' => 'Nhân viên',
            'manager_id' => $manager->id,
            'is_active' => true
        ]);

        // Create categories
        $categories = [
            ['name' => 'Văn phòng phẩm cơ bản', 'description' => 'Bút, giấy, thước kẻ, v.v.'],
            ['name' => 'Thiết bị văn phòng', 'description' => 'Máy tính, máy in, scanner, v.v.'],
            ['name' => 'Đồ dùng học tập', 'description' => 'Sách, vở, bảng, v.v.'],
            ['name' => 'Vật tư tiêu hao', 'description' => 'Mực in, giấy photocopy, v.v.'],
            ['name' => 'Đồ nội thất', 'description' => 'Bàn, ghế, tủ, v.v.']
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create products
        $products = [
            // Văn phòng phẩm cơ bản
            ['name' => 'Bút bi Thiên Long', 'category_id' => 1, 'unit' => 'cái', 'price' => 5000, 'supplier' => 'Thiên Long'],
            ['name' => 'Giấy A4 Double A', 'category_id' => 1, 'unit' => 'ream', 'price' => 80000, 'supplier' => 'Double A'],
            ['name' => 'Thước kẻ 30cm', 'category_id' => 1, 'unit' => 'cái', 'price' => 15000, 'supplier' => 'Thiên Long'],
            
            // Thiết bị văn phòng
            ['name' => 'Máy tính để bàn Dell', 'category_id' => 2, 'unit' => 'bộ', 'price' => 15000000, 'supplier' => 'Dell'],
            ['name' => 'Máy in HP LaserJet', 'category_id' => 2, 'unit' => 'chiếc', 'price' => 8000000, 'supplier' => 'HP'],
            
            // Vật tư tiêu hao
            ['name' => 'Mực in đen HP 79A', 'category_id' => 4, 'unit' => 'hộp', 'price' => 2500000, 'supplier' => 'HP'],
            ['name' => 'Giấy photocopy A4', 'category_id' => 4, 'unit' => 'ream', 'price' => 70000, 'supplier' => 'IK Plus'],
            
            // Đồ nội thất
            ['name' => 'Bàn làm việc', 'category_id' => 5, 'unit' => 'cái', 'price' => 3500000, 'supplier' => 'Hòa Phát'],
            ['name' => 'Ghế xoay văn phòng', 'category_id' => 5, 'unit' => 'cái', 'price' => 2800000, 'supplier' => 'Hòa Phát'],
            ['name' => 'Tủ hồ sơ 4 ngăn', 'category_id' => 5, 'unit' => 'cái', 'price' => 4200000, 'supplier' => 'Hòa Phát'],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}