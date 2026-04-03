<?php
// Kiểm tra và tạo database LogDB
$host = '127.0.0.1';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Kiểm tra database nào đang tồn tại
    $result = $conn->query("SHOW DATABASES");
    echo "=== Danh sách database hiện có ===\n";
    while($row = $result->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }
    
    echo "\n";
    
    // Tạo database LogDB nếu chưa tồn tại
    $sql = "CREATE DATABASE IF NOT EXISTS LogDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    echo "✓ Database 'LogDB' đã được tạo/kiểm tra thành công!\n";
    
    // Tạo database kdnvpp nếu chưa tồn tại  
    $sql2 = "CREATE DATABASE IF NOT EXISTS kdnvpp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql2);
    echo "✓ Database 'kdnvpp' đã được tạo/kiểm tra thành công!\n";
    
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
$conn = null;
