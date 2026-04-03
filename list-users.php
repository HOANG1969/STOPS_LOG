<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DANH SÁCH TÀI KHOẢN TRONG HỆ THỐNG ===\n\n";

$users = App\Models\User::all();

foreach($users as $user) {
    echo "┌─────────────────────────────────────────\n";
    echo "│ ID: " . $user->id . "\n";
    echo "│ Email: " . $user->email . "\n";
    echo "│ Tên: " . $user->name . "\n";
    echo "│ Họ tên: " . ($user->full_name ?? 'N/A') . "\n";
    echo "│ Bộ phận: " . ($user->department ?? 'N/A') . "\n";
    
    if ($user->role === 'admin') {
        echo "│ VAI TRÒ: ⭐ ADMIN ⭐\n";
    } elseif ($user->is_tchc_manager) {
        echo "│ Vai trò: Lãnh đạo TCHC\n";
    } elseif ($user->is_tchc_checker) {
        echo "│ Vai trò: Nhân sự TCHC\n";
    } elseif ($user->is_department_head) {
        echo "│ Vai trò: Trưởng phòng\n";
    } else {
        echo "│ Vai trò: Nhân viên\n";
    }
    
    echo "└─────────────────────────────────────────\n\n";
}

echo "\n=== HƯỚNG DẪN ĐĂNG NHẬP ===\n";
echo "Mật khẩu mặc định cho tất cả user: 123456\n";
echo "Riêng admin: password\n\n";

$admin = App\Models\User::where('role', 'admin')->first();
if ($admin) {
    echo "👑 ACCOUNT ADMIN:\n";
    echo "   Email: " . $admin->email . "\n";
    echo "   Password: password\n";
}
