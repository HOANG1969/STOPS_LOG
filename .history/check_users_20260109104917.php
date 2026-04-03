<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Users được tạo trong database:\n";
echo "==========================================\n";

$users = App\Models\User::all();

foreach($users as $user) {
    echo "Tên: " . $user->full_name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Phòng ban: " . $user->department . "\n";
    echo "Chức vụ: " . $user->position . "\n";
    echo "==========================================\n";
}