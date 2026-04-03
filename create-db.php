<?php
// Script tạo database
$host = '127.0.0.1';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE DATABASE IF NOT EXISTS kdnvpp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    
    echo "Database 'kdnvpp' đã được tạo thành công!\n";
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
$conn = null;
