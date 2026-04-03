<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Supplies Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 15px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            margin: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }
        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        .features {
            margin: 30px 0;
            text-align: left;
        }
        .feature {
            padding: 8px 0;
            color: #555;
        }
        .feature:before {
            content: "✓";
            color: #667eea;
            font-weight: bold;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🏢 Office Supplies Management</h1>
        <p class="subtitle">Hệ thống quản lý văn phòng phẩm</p>
        
        <div class="features">
            <div class="feature">Quản lý sản phẩm và danh mục</div>
            <div class="feature">Quản lý yêu cầu mua sắm</div>
            <div class="feature">Phê duyệt và theo dõi</div>
            <div class="feature">Báo cáo thống kê</div>
        </div>
        
        <div>
            <a href="/dashboard" class="btn">Dashboard</a>
            <a href="/test" class="btn">Test Page</a>
            <a href="/clean" class="btn">Clean Test</a>
        </div>
    </div>
</body>
</html>