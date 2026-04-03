<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update tất cả STOP đang có priority_level = 3 về NULL (Chưa chấm)
// Vì 3 là default cũ, không phải do trưởng ca chấm điểm
$updated = DB::table('stops')
    ->where('priority_level', 3)
    ->update(['priority_level' => null]);

echo "Đã cập nhật {$updated} STOP records: priority_level = 3 → NULL (Chưa chấm)" . PHP_EOL;

// Hiển thị trạng thái
$stops = DB::table('stops')->select('id', 'observer_name', 'priority_level')->get();
echo "\n=== DANH SÁCH STOP VÀ MỨC ĐỘ ===" . PHP_EOL;
foreach ($stops as $stop) {
    $priority = $stop->priority_level === null ? 'Chưa chấm' : "Mức {$stop->priority_level}";
    echo "ID: {$stop->id} | Observer: {$stop->observer_name} | Priority: {$priority}" . PHP_EOL;
}
