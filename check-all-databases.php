<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== KIỂM TRA TẤT CẢ DATABASES ===\n\n";

try {
    $databases = DB::select('SHOW DATABASES');
    
    echo "📦 Tất cả databases:\n";
    echo str_repeat("=", 50) . "\n";
    
    foreach ($databases as $db) {
        $dbName = $db->Database;
        echo "  - $dbName\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "📊 Kiểm tra từng database có tables không:\n\n";
    
    foreach ($databases as $db) {
        $dbName = $db->Database;
        
        if (in_array($dbName, ['information_schema', 'performance_schema', 'mysql', 'sys'])) {
            continue;
        }
        
        try {
            $tables = DB::select("SHOW TABLES FROM `$dbName`");
            $tableCount = count($tables);
            
            if ($tableCount > 0) {
                echo "✅ $dbName: $tableCount tables\n";
                
                // Nếu có table users, đếm số users
                foreach ($tables as $table) {
                    $tableName = array_values((array)$table)[0];
                    if ($tableName === 'users') {
                        $userCount = DB::connection('mysql')->select("SELECT COUNT(*) as count FROM `$dbName`.`users`")[0]->count;
                        echo "   👥 users: $userCount records\n";
                    }
                }
            } else {
                echo "❌ $dbName: RỖNG (không có tables)\n";
            }
        } catch (Exception $e) {
            echo "⚠️  $dbName: Lỗi - " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
}
