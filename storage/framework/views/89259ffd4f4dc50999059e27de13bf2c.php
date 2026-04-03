<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Office Supplies Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .features {
            text-align: left;
            margin: 30px 0;
        }
        .features ul {
            list-style-type: none;
            padding: 0;
        }
        .features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .features li:before {
            content: "✓ ";
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏢 Office Supplies Management System</h1>
        <p>Hệ thống quản lý văn phòng phẩm chuyên nghiệp</p>
        
        <div class="features">
            <h3>Tính năng chính:</h3>
            <ul>
                <li>Quản lý danh mục sản phẩm</li>
                <li>Quản lý yêu cầu mua sắm</li>
                <li>Workflow phê duyệt</li>
                <li>Quản lý người dùng và phân quyền</li>
                <li>Báo cáo và thống kê</li>
            </ul>
        </div>
        
        <a href="/dashboard" class="btn">Vào Dashboard</a>
        <a href="/test" class="btn">Test Page</a>
    </div>
</body>
</html><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\simple-welcome.blade.php ENDPATH**/ ?>