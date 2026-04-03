<?php
// Kiểm tra dữ liệu trong database kdnvpp
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'kdnvpp';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Kiểm tra database: $database ===\n\n";
    
    // Lấy danh sách bảng
    $result = $conn->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Số bảng: " . count($tables) . "\n\n";
    
    if (count($tables) > 0) {
        echo "Chi tiết các bảng:\n";
        foreach($tables as $table) {
            $count = $conn->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "  - $table: $count records\n";
        }
        
        // Kiểm tra users
        echo "\n=== Thông tin users ===\n";
        $users = $conn->query("SELECT id, name, email, department FROM users LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        foreach($users as $user) {
            echo "  - ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Dept: {$user['department']}\n";
        }
    } else {
        echo "Database rỗng - không có bảng nào!\n";
    }
    
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
