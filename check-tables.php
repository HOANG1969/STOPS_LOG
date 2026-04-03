<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== KIỂM TRA DATABASE ===\n\n";

$dbName = config('database.connections.mysql.database');
echo "📊 Database: $dbName\n\n";

try {
    $tables = DB::select('SHOW TABLES');
    
    if (empty($tables)) {
        echo "❌ KHÔNG CÓ TABLE NÀO TRONG DATABASE!\n";
        exit(1);
    }
    
    echo "✅ Tổng số tables: " . count($tables) . "\n";
    echo str_repeat("=", 50) . "\n\n";
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        
        try {
            $count = DB::table($tableName)->count();
            echo "📋 $tableName: $count records\n";
        } catch (Exception $e) {
            echo "⚠️  $tableName: Lỗi đọc - " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ DATABASE HOẠT ĐỘNG BÌNH THƯỜNG!\n";
    
} catch (Exception $e) {
    echo "❌ LỖI KẾT NỐI DATABASE!\n";
    echo "Chi tiết: " . $e->getMessage() . "\n";
}
