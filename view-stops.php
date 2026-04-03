<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DANH SÁCH STOP (MỚI NHẤT) ===" . PHP_EOL . PHP_EOL;

$stops = DB::table('stops')
    ->leftJoin('users as creator', 'stops.user_id', '=', 'creator.id')
    ->leftJoin('users as scorer', 'stops.priority_scored_by', '=', 'scorer.id')
    ->select(
        'stops.id',
        'stops.observer_name',
        'stops.observer_phone as shift',
        'stops.issue_category',
        'stops.priority_level',
        'stops.status',
        'stops.created_at',
        'creator.name as created_by',
        'scorer.name as scored_by',
        'stops.priority_scored_at'
    )
    ->orderBy('stops.created_at', 'desc')
    ->limit(20)
    ->get();

foreach ($stops as $stop) {
    echo "ID: {$stop->id}" . PHP_EOL;
    echo "Người quan sát: {$stop->observer_name}" . PHP_EOL;
    echo "Ca/kíp: {$stop->shift}" . PHP_EOL;
    echo "Loại vấn đề: {$stop->issue_category}" . PHP_EOL;
    echo "Mức độ: " . ($stop->priority_level !== null ? "Mức {$stop->priority_level}" : "Chưa chấm") . PHP_EOL;
    echo "Trạng thái: {$stop->status}" . PHP_EOL;
    echo "Đăng ký lúc: {$stop->created_at}" . PHP_EOL;
    echo "Đăng ký bởi: {$stop->created_by}" . PHP_EOL;
    if ($stop->scored_by) {
        echo "Chấm điểm bởi: {$stop->scored_by} lúc {$stop->priority_scored_at}" . PHP_EOL;
    }
    echo str_repeat("-", 80) . PHP_EOL;
}

echo "\nTổng số STOP: " . DB::table('stops')->count() . PHP_EOL;
echo "Chưa chấm: " . DB::table('stops')->whereNull('priority_level')->count() . PHP_EOL;
echo "Đã chấm: " . DB::table('stops')->whereNotNull('priority_level')->count() . PHP_EOL;
