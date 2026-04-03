<?php

namespace Database\Seeders;

use App\Models\OfficeSupply;
use Illuminate\Database\Seeder;

class OfficeSupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplies = [
            // Văn phòng phẩm cơ bản
            [
                'name' => 'Bút bi xanh',
                'description' => 'Bút bi ngòi 1.0mm, mực xanh',
                'unit' => 'cái',
                'price' => 5000,
                'stock_quantity' => 500,
                'category' => 'Dụng cụ viết',
                'is_active' => true
            ],
            [
                'name' => 'Bút bi đỏ',
                'description' => 'Bút bi ngòi 1.0mm, mực đỏ',
                'unit' => 'cái',
                'price' => 5000,
                'stock_quantity' => 300,
                'category' => 'Dụng cụ viết',
                'is_active' => true
            ],
            [
                'name' => 'Bút chì 2B',
                'description' => 'Bút chì gỗ độ cứng 2B',
                'unit' => 'cái',
                'price' => 3000,
                'stock_quantity' => 200,
                'category' => 'Dụng cụ viết',
                'is_active' => true
            ],
            [
                'name' => 'Giấy A4',
                'description' => 'Giấy A4 80gsm (1 ream = 500 tờ)',
                'unit' => 'ream',
                'price' => 95000,
                'stock_quantity' => 100,
                'category' => 'Giấy in',
                'is_active' => true
            ],
            [
                'name' => 'Giấy A3',
                'description' => 'Giấy A3 80gsm (1 ream = 500 tờ)',
                'unit' => 'ream',
                'price' => 180000,
                'stock_quantity' => 50,
                'category' => 'Giấy in',
                'is_active' => true
            ],
            [
                'name' => 'Kẹp giấy',
                'description' => 'Kẹp giấy kim loại cỡ nhỏ',
                'unit' => 'hộp',
                'price' => 15000,
                'stock_quantity' => 80,
                'category' => 'Văn phòng phẩm',
                'is_active' => true
            ],
            [
                'name' => 'Thước kẻ 30cm',
                'description' => 'Thước kẻ nhựa trong suốt 30cm',
                'unit' => 'cái',
                'price' => 8000,
                'stock_quantity' => 150,
                'category' => 'Dụng cụ đo',
                'is_active' => true
            ],
            [
                'name' => 'Keo dán UHU',
                'description' => 'Keo dán đa năng UHU 40g',
                'unit' => 'tuýp',
                'price' => 25000,
                'stock_quantity' => 60,
                'category' => 'Văn phòng phẩm',
                'is_active' => true
            ],
            [
                'name' => 'Băng dính trong',
                'description' => 'Băng dính trong suốt 2cm x 30m',
                'unit' => 'cuộn',
                'price' => 12000,
                'stock_quantity' => 120,
                'category' => 'Văn phòng phẩm',
                'is_active' => true
            ],
            [
                'name' => 'Tẩy trắng',
                'description' => 'Tẩy trắng bút chì',
                'unit' => 'cái',
                'price' => 4000,
                'stock_quantity' => 200,
                'category' => 'Dụng cụ viết',
                'is_active' => true
            ],
            [
                'name' => 'Bìa lưu trữ',
                'description' => 'Bìa lưu trữ hồ sơ A4',
                'unit' => 'cái',
                'price' => 35000,
                'stock_quantity' => 80,
                'category' => 'Lưu trữ',
                'is_active' => true
            ],
            [
                'name' => 'Kéo văn phòng',
                'description' => 'Kéo văn phòng cỡ trung',
                'unit' => 'cái',
                'price' => 45000,
                'stock_quantity' => 40,
                'category' => 'Dụng cụ cắt',
                'is_active' => true
            ],
            [
                'name' => 'Ghim bấm',
                'description' => 'Ghim bấm kim loại cỡ nhỏ',
                'unit' => 'hộp',
                'price' => 8000,
                'stock_quantity' => 100,
                'category' => 'Văn phòng phẩm',
                'is_active' => true
            ],
            [
                'name' => 'Máy bấm ghim',
                'description' => 'Máy bấm ghim cỡ trung',
                'unit' => 'cái',
                'price' => 120000,
                'stock_quantity' => 25,
                'category' => 'Thiết bị văn phòng',
                'is_active' => true
            ],
            [
                'name' => 'Bút highlight vàng',
                'description' => 'Bút đánh dấu màu vàng',
                'unit' => 'cái',
                'price' => 12000,
                'stock_quantity' => 80,
                'category' => 'Dụng cụ viết',
                'is_active' => true
            ]
        ];

        foreach ($supplies as $supply) {
            OfficeSupply::create($supply);
        }
    }
}
