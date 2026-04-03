<!DOCTYPE html>
<html>
<head>
    <title>Login Test - KDNVPP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .test-box { background: #f0f0f0; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔐 Test Login Functionality</h1>
    
    <?php
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $users = App\Models\User::all();
    ?>
    
    <div class="test-box success">
        <h3>✓ Database Connection: OK</h3>
        <p>Tổng số users: <strong><?php echo $users->count(); ?></strong></p>
    </div>
    
    <h2>📋 Danh sách tài khoản có sẵn:</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Phòng ban</th>
                <th>Chức vụ</th>
                <th>Role</th>
                <th>Password Test</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): 
                $passwordCheck = Illuminate\Support\Facades\Hash::check('123456', $user->password);
            ?>
            <tr>
                <td><?php echo $user->id; ?></td>
                <td><?php echo $user->name; ?></td>
                <td><strong><?php echo $user->email; ?></strong></td>
                <td><?php echo $user->full_name; ?></td>
                <td><?php echo $user->department; ?></td>
                <td><?php echo $user->position; ?></td>
                <td><?php echo $user->role; ?></td>
                <td><?php echo $passwordCheck ? '✓ OK' : '✗ FAIL'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="test-box">
        <h3>🔑 Thông tin đăng nhập:</h3>
        <p><strong>Mật khẩu cho TẤT CẢ tài khoản:</strong> <code>123456</code></p>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="/login" class="btn">🔐 Đi đến trang Login</a>
        <a href="/dashboard" class="btn">📊 Đi đến Dashboard</a>
    </div>
    
    <div class="test-box">
        <h3>📝 Hướng dẫn:</h3>
        <ol>
            <li>Chọn bất kỳ email nào từ bảng trên</li>
            <li>Nhấn nút "Đi đến trang Login"</li>
            <li>Nhập email và mật khẩu <code>123456</code></li>
            <li>Nhấn "Đăng nhập"</li>
        </ol>
    </div>
</body>
</html>
