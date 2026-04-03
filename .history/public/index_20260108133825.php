<?php

// Simple login check without Laravel complexity
session_start();

// Initialize sample data if not exists
if (!isset($_SESSION['requests'])) {
    $_SESSION['requests'] = [
        'VP001' => [
            'id' => 'VP001',
            'requester' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@company.com',
            'department' => 'IT',
            'priority' => 'High',
            'needed_date' => '2026-01-10',
            'notes' => 'Cần gấp cho dự án mới',
            'status' => 'pending',
            'created_at' => '2026-01-08',
            'items' => [
                ['name' => 'Bút bi', 'quantity' => 10, 'unit' => 'cái', 'purpose' => 'Ghi chú công việc'],
                ['name' => 'Giấy A4', 'quantity' => 2, 'unit' => 'thùng', 'purpose' => 'In tài liệu dự án']
            ],
            'approval_history' => []
        ],
        'VP002' => [
            'id' => 'VP002',
            'requester' => 'Trần Thị B',
            'email' => 'tranthib@company.com',
            'department' => 'HR',
            'priority' => 'Normal',
            'needed_date' => '2026-01-15',
            'notes' => 'Trang bị cho nhân viên mới',
            'status' => 'approved',
            'created_at' => '2026-01-07',
            'items' => [
                ['name' => 'Máy tính', 'quantity' => 1, 'unit' => 'cái', 'purpose' => 'Làm việc'],
                ['name' => 'Bàn phím', 'quantity' => 1, 'unit' => 'cái', 'purpose' => 'Phụ kiện máy tính']
            ],
            'approval_history' => [
                ['action' => 'approved', 'by' => 'Trần Văn B (Lãnh đạo IT)', 'date' => '2026-01-08', 'note' => 'Phê duyệt đầy đủ']
            ]
        ]
    ];
}

if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($email === 'admin@example.com' && $password === 'password') {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_role'] = 'employee';
            $_SESSION['user_name'] = 'Nhân viên';
            header('Location: /dashboard');
            exit;
        } elseif ($email === 'leader@example.com' && $password === 'leader123') {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_role'] = 'leader';
            $_SESSION['user_name'] = 'Lãnh đạo';
            header('Location: /dashboard');
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    }
    
    if (!isset($_SESSION['logged_in'])) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container">
                <div class="row justify-content-center mt-5">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Office Supplies Management - Login</h4>
                            </div>
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>
                                
                                <div class="alert alert-info">
                                    <strong>Demo Accounts:</strong><br>
                                    <strong>Nhân viên:</strong> admin@example.com / password<br>
                                    <strong>Lãnh đạo:</strong> leader@example.com / leader123
                                </div>
                                
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    } else {
        header('Location: /dashboard');
        exit;
    }
}

if ($_SERVER['REQUEST_URI'] === '/dashboard') {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Office Supplies Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .sidebar { min-height: 100vh; background: #343a40; }
            .content { min-height: 100vh; background: #f8f9fa; }
            .status-badge { font-size: 0.8em; }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 sidebar text-white p-0">
                    <div class="p-3">
                        <h4><i class="fas fa-building"></i> Office Supplies</h4>
                        <hr>
                        <nav class="nav flex-column">
                            <a class="nav-link text-white active" href="/dashboard">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                            <a class="nav-link text-white" href="/register">
                                <i class="fas fa-plus-circle"></i> Đăng ký văn phòng phẩm
                            </a>
                            <a class="nav-link text-white" href="/requests">
                                <i class="fas fa-list"></i> Danh sách đăng ký
                            </a>
                            <a class="nav-link text-white" href="/approval">
                                <i class="fas fa-check-circle"></i> Phê duyệt
                            </a>
                            <hr>
                            <a class="nav-link text-white" href="/logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="col-md-9 content p-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h2>Hệ thống quản lý văn phòng phẩm</h2>
                            <p class="text-muted">Chào mừng bạn đến với hệ thống đăng ký và quản lý văn phòng phẩm</p>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Tổng đơn đăng ký</h4>
                                            <h2>25</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clipboard-list fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Chờ phê duyệt</h4>
                                            <h2>8</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Đã phê duyệt</h4>
                                            <h2>15</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Từ chối</h4>
                                            <h2>2</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-bolt"></i> Thao tác nhanh</h5>
                                </div>
                                <div class="card-body">
                                    <a href="/register" class="btn btn-primary me-2">
                                        <i class="fas fa-plus"></i> Đăng ký văn phòng phẩm mới
                                    </a>
                                    <a href="/requests" class="btn btn-info me-2">
                                        <i class="fas fa-list"></i> Xem danh sách đăng ký
                                    </a>
                                    <a href="/approval" class="btn btn-success">
                                        <i class="fas fa-check-circle"></i> Phê duyệt đơn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Requests -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-history"></i> Đơn đăng ký gần đây</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Mã đơn</th>
                                                    <th>Người đăng ký</th>
                                                    <th>Văn phòng phẩm</th>
                                                    <th>Ngày đăng ký</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>VP001</td>
                                                    <td>Nguyễn Văn A</td>
                                                    <td>Bút bi, Giấy A4</td>
                                                    <td>08/01/2026</td>
                                                    <td><span class="badge bg-warning status-badge">Chờ duyệt</span></td>
                                                    <td>
                                                        <a href="/detail/VP001" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>VP002</td>
                                                    <td>Trần Thị B</td>
                                                    <td>Máy tính, Bàn phím</td>
                                                    <td>07/01/2026</td>
                                                    <td><span class="badge bg-success status-badge">Đã duyệt</span></td>
                                                    <td>
                                                        <a href="/detail/VP002" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>VP003</td>
                                                    <td>Lê Văn C</td>
                                                    <td>Thước kẻ, Xóa</td>
                                                    <td>06/01/2026</td>
                                                    <td><span class="badge bg-danger status-badge">Từ chối</span></td>
                                                    <td>
                                                        <a href="/detail/VP003" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/register') {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Simulate saving request
        $_SESSION['message'] = 'Đăng ký văn phòng phẩm thành công! Mã đơn: VP' . rand(100, 999);
        header('Location: /requests');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Đăng ký văn phòng phẩm</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .sidebar { min-height: 100vh; background: #343a40; }
            .content { min-height: 100vh; background: #f8f9fa; }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 sidebar text-white p-0">
                    <div class="p-3">
                        <h4><i class="fas fa-building"></i> Office Supplies</h4>
                        <hr>
                        <nav class="nav flex-column">
                            <a class="nav-link text-white" href="/dashboard">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                            <a class="nav-link text-white active" href="/register">
                                <i class="fas fa-plus-circle"></i> Đăng ký văn phòng phẩm
                            </a>
                            <a class="nav-link text-white" href="/requests">
                                <i class="fas fa-list"></i> Danh sách đăng ký
                            </a>
                            <a class="nav-link text-white" href="/approval">
                                <i class="fas fa-check-circle"></i> Phê duyệt
                            </a>
                            <hr>
                            <a class="nav-link text-white" href="/logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="col-md-9 content p-4">
                    <h2><i class="fas fa-plus-circle"></i> Đăng ký văn phòng phẩm</h2>
                    
                    <div class="card">
                        <div class="card-body">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Người đăng ký *</label>
                                            <input type="text" class="form-control" name="requester" placeholder="Họ và tên" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Email *</label>
                                            <input type="email" class="form-control" name="email" placeholder="email@example.com" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Phòng ban *</label>
                                            <select class="form-select" name="department" required>
                                                <option value="">Chọn phòng ban</option>
                                                <option value="IT">Phòng IT</option>
                                                <option value="HR">Phòng Nhân sự</option>
                                                <option value="Finance">Phòng Tài chính</option>
                                                <option value="Marketing">Phòng Marketing</option>
                                                <option value="Sales">Phòng Kinh doanh</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Mức độ ưu tiên</label>
                                            <select class="form-select" name="priority">
                                                <option value="Normal">Bình thường</option>
                                                <option value="High">Cao</option>
                                                <option value="Urgent">Khẩn cấp</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Ngày cần có *</label>
                                            <input type="date" class="form-control" name="needed_date" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Ghi chú</label>
                                            <textarea class="form-control" name="notes" rows="3" placeholder="Ghi chú thêm (nếu có)"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <h5><i class="fas fa-boxes"></i> Danh sách văn phòng phẩm</h5>
                                
                                <div id="items-container">
                                    <div class="row mb-2 item-row">
                                        <div class="col-md-4">
                                            <select class="form-select" name="items[]" required>
                                                <option value="">Chọn văn phòng phẩm</option>
                                                <option value="Bút bi">Bút bi</option>
                                                <option value="Bút chì">Bút chì</option>
                                                <option value="Giấy A4">Giấy A4</option>
                                                <option value="Kẹp giấy">Kẹp giấy</option>
                                                <option value="Thước kẻ">Thước kẻ</option>
                                                <option value="Tẩy">Tẩy</option>
                                                <option value="Máy tính">Máy tính</option>
                                                <option value="Bàn phím">Bàn phím</option>
                                                <option value="Chuột">Chuột</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="quantities[]" placeholder="Số lượng" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select" name="units[]" required>
                                                <option value="cái">cái</option>
                                                <option value="bộ">bộ</option>
                                                <option value="thùng">thùng</option>
                                                <option value="gói">gói</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="purposes[]" placeholder="Mục đích sử dụng">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-success btn-sm" onclick="addItem()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Gửi đăng ký
                                    </button>
                                    <a href="/dashboard" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function addItem() {
                const container = document.getElementById('items-container');
                const newRow = container.querySelector('.item-row').cloneNode(true);
                
                // Clear values
                newRow.querySelectorAll('input, select').forEach(input => {
                    if (input.type !== 'number') input.value = '';
                    else input.value = '';
                });
                
                // Change add button to remove button
                const button = newRow.querySelector('button');
                button.className = 'btn btn-danger btn-sm';
                button.innerHTML = '<i class="fas fa-minus"></i>';
                button.onclick = function() { removeItem(this); };
                
                container.appendChild(newRow);
            }
            
            function removeItem(button) {
                button.closest('.item-row').remove();
            }
        </script>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/requests') {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Danh sách đăng ký</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .sidebar { min-height: 100vh; background: #343a40; }
            .content { min-height: 100vh; background: #f8f9fa; }
            .status-badge { font-size: 0.8em; }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 sidebar text-white p-0">
                    <div class="p-3">
                        <h4><i class="fas fa-building"></i> Office Supplies</h4>
                        <hr>
                        <nav class="nav flex-column">
                            <a class="nav-link text-white" href="/dashboard">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                            <a class="nav-link text-white" href="/register">
                                <i class="fas fa-plus-circle"></i> Đăng ký văn phòng phẩm
                            </a>
                            <a class="nav-link text-white active" href="/requests">
                                <i class="fas fa-list"></i> Danh sách đăng ký
                            </a>
                            <a class="nav-link text-white" href="/approval">
                                <i class="fas fa-check-circle"></i> Phê duyệt
                            </a>
                            <hr>
                            <a class="nav-link text-white" href="/logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="col-md-9 content p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-list"></i> Danh sách đăng ký văn phòng phẩm</h2>
                        <a href="/register" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Đăng ký mới
                        </a>
                    </div>
                    
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $_SESSION['message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                    
                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="pending">Chờ duyệt</option>
                                        <option value="approved">Đã duyệt</option>
                                        <option value="rejected">Từ chối</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Tất cả phòng ban</option>
                                        <option value="IT">Phòng IT</option>
                                        <option value="HR">Phòng Nhân sự</option>
                                        <option value="Finance">Phòng Tài chính</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm theo tên, mã đơn...">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-primary w-100">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Requests Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Người đăng ký</th>
                                            <th>Phòng ban</th>
                                            <th>Văn phòng phẩm</th>
                                            <th>Ngày đăng ký</th>
                                            <th>Ngày cần có</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>VP001</strong></td>
                                            <td>Nguyễn Văn A</td>
                                            <td>Phòng IT</td>
                                            <td>Bút bi (10 cái), Giấy A4 (2 thùng)</td>
                                            <td>08/01/2026</td>
                                            <td>10/01/2026</td>
                                            <td><span class="badge bg-warning status-badge">Chờ duyệt</span></td>
                                            <td>
                                                <a href="/detail/VP001" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                                <a href="/edit/VP001" class="btn btn-sm btn-outline-warning">Sửa</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>VP002</strong></td>
                                            <td>Trần Thị B</td>
                                            <td>Phòng HR</td>
                                            <td>Máy tính (1 cái), Bàn phím (1 cái)</td>
                                            <td>07/01/2026</td>
                                            <td>15/01/2026</td>
                                            <td><span class="badge bg-success status-badge">Đã duyệt</span></td>
                                            <td>
                                                <a href="/detail/VP002" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>VP003</strong></td>
                                            <td>Lê Văn C</td>
                                            <td>Phòng Tài chính</td>
                                            <td>Thước kẻ (5 cái), Tẩy (10 cái)</td>
                                            <td>06/01/2026</td>
                                            <td>12/01/2026</td>
                                            <td><span class="badge bg-danger status-badge">Từ chối</span></td>
                                            <td>
                                                <a href="/detail/VP003" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

if (preg_match('/^\/detail\/(.+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    }
    
    $requestId = $matches[1];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Chi tiết đơn đăng ký</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .sidebar { min-height: 100vh; background: #343a40; }
            .content { min-height: 100vh; background: #f8f9fa; }
            .status-badge { font-size: 0.9em; }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 sidebar text-white p-0">
                    <div class="p-3">
                        <h4><i class="fas fa-building"></i> Office Supplies</h4>
                        <hr>
                        <nav class="nav flex-column">
                            <a class="nav-link text-white" href="/dashboard">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                            <a class="nav-link text-white" href="/register">
                                <i class="fas fa-plus-circle"></i> Đăng ký văn phòng phẩm
                            </a>
                            <a class="nav-link text-white" href="/requests">
                                <i class="fas fa-list"></i> Danh sách đăng ký
                            </a>
                            <a class="nav-link text-white" href="/approval">
                                <i class="fas fa-check-circle"></i> Phê duyệt
                            </a>
                            <hr>
                            <a class="nav-link text-white" href="/logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="col-md-9 content p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-file-alt"></i> Chi tiết đơn đăng ký: <?= htmlspecialchars($requestId) ?></h2>
                        <a href="/requests" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                    
                    <!-- Request Info -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><i class="fas fa-info-circle"></i> Thông tin đơn đăng ký</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Mã đơn:</strong> <?= htmlspecialchars($requestId) ?></p>
                                            <p><strong>Người đăng ký:</strong> Nguyễn Văn A</p>
                                            <p><strong>Email:</strong> nguyenvana@company.com</p>
                                            <p><strong>Phòng ban:</strong> Phòng IT</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Ngày đăng ký:</strong> 08/01/2026</p>
                                            <p><strong>Ngày cần có:</strong> 10/01/2026</p>
                                            <p><strong>Mức độ ưu tiên:</strong> <span class="badge bg-warning">Cao</span></p>
                                            <p><strong>Trạng thái:</strong> 
                                                <?php if ($requestId === 'VP001'): ?>
                                                    <span class="badge bg-warning status-badge">Chờ phê duyệt</span>
                                                <?php elseif ($requestId === 'VP002'): ?>
                                                    <span class="badge bg-success status-badge">Đã phê duyệt</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger status-badge">Từ chối</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Ghi chú:</strong> Cần gấp cho dự án mới</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Items List -->
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-boxes"></i> Danh sách văn phòng phẩm</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên văn phòng phẩm</th>
                                                    <th>Số lượng</th>
                                                    <th>Đơn vị</th>
                                                    <th>Mục đích sử dụng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Bút bi</td>
                                                    <td>10</td>
                                                    <td>cái</td>
                                                    <td>Ghi chú công việc</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Giấy A4</td>
                                                    <td>2</td>
                                                    <td>thùng</td>
                                                    <td>In tài liệu dự án</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="col-md-4">
                            <?php if ($requestId === 'VP001'): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="fas fa-cogs"></i> Thao tác phê duyệt</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="/approval-action">
                                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($requestId) ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Quyết định</label>
                                                <select class="form-select" name="decision" required>
                                                    <option value="">Chọn quyết định</option>
                                                    <option value="approve">Phê duyệt</option>
                                                    <option value="reject">Từ chối</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ghi chú phê duyệt</label>
                                                <textarea class="form-control" name="approval_note" rows="3" placeholder="Lý do phê duyệt/từ chối..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-check"></i> Xác nhận
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="fas fa-history"></i> Lịch sử phê duyệt</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline">
                                            <div class="mb-3">
                                                <small class="text-muted">08/01/2026 09:00</small>
                                                <p class="mb-1"><strong>Đơn được tạo</strong></p>
                                                <p class="text-muted">Bởi: Nguyễn Văn A</p>
                                            </div>
                                            <?php if ($requestId === 'VP002'): ?>
                                                <div class="mb-3">
                                                    <small class="text-muted">08/01/2026 14:30</small>
                                                    <p class="mb-1 text-success"><strong>Đã phê duyệt</strong></p>
                                                    <p class="text-muted">Bởi: Trần Văn B (Quản lý)</p>
                                                    <p class="text-muted">Ghi chú: Phê duyệt đầy đủ</p>
                                                </div>
                                            <?php else: ?>
                                                <div class="mb-3">
                                                    <small class="text-muted">08/01/2026 16:00</small>
                                                    <p class="mb-1 text-danger"><strong>Từ chối</strong></p>
                                                    <p class="text-muted">Bởi: Lê Thị C (Quản lý)</p>
                                                    <p class="text-muted">Ghi chú: Không đủ ngân sách</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5><i class="fas fa-tools"></i> Thao tác khác</h5>
                                </div>
                                <div class="card-body">
                                    <?php if ($requestId === 'VP001'): ?>
                                        <a href="/edit/<?= $requestId ?>" class="btn btn-warning w-100 mb-2">
                                            <i class="fas fa-edit"></i> Chỉnh sửa đơn
                                        </a>
                                    <?php endif; ?>
                                    <button class="btn btn-info w-100 mb-2">
                                        <i class="fas fa-print"></i> In đơn đăng ký
                                    </button>
                                    <button class="btn btn-secondary w-100">
                                        <i class="fas fa-download"></i> Xuất PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/approval') {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: /');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Phê duyệt đơn đăng ký</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .sidebar { min-height: 100vh; background: #343a40; }
            .content { min-height: 100vh; background: #f8f9fa; }
            .status-badge { font-size: 0.8em; }
            .priority-high { border-left: 4px solid #dc3545; }
            .priority-urgent { border-left: 4px solid #fd7e14; }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 sidebar text-white p-0">
                    <div class="p-3">
                        <h4><i class="fas fa-building"></i> Office Supplies</h4>
                        <hr>
                        <nav class="nav flex-column">
                            <a class="nav-link text-white" href="/dashboard">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                            <a class="nav-link text-white" href="/register">
                                <i class="fas fa-plus-circle"></i> Đăng ký văn phòng phẩm
                            </a>
                            <a class="nav-link text-white" href="/requests">
                                <i class="fas fa-list"></i> Danh sách đăng ký
                            </a>
                            <a class="nav-link text-white active" href="/approval">
                                <i class="fas fa-check-circle"></i> Phê duyệt
                            </a>
                            <hr>
                            <a class="nav-link text-white" href="/logout">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="col-md-9 content p-4">
                    <h2><i class="fas fa-check-circle"></i> Phê duyệt đơn đăng ký</h2>
                    
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h4>Chờ phê duyệt</h4>
                                    <h2>8</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger">
                                <div class="card-body">
                                    <h4>Quá hạn</h4>
                                    <h2>2</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h4>Ưu tiên cao</h4>
                                    <h2>3</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h4>Đã duyệt hôm nay</h4>
                                    <h2>5</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Approvals -->
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-clock"></i> Đơn chờ phê duyệt</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" placeholder="Tìm kiếm đơn...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Tất cả mức độ ưu tiên</option>
                                        <option value="urgent">Khẩn cấp</option>
                                        <option value="high">Cao</option>
                                        <option value="normal">Bình thường</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Tất cả phòng ban</option>
                                        <option value="IT">Phòng IT</option>
                                        <option value="HR">Phòng Nhân sự</option>
                                        <option value="Finance">Phòng Tài chính</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="list-group">
                                <div class="list-group-item priority-high">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <h5 class="mb-1">VP001 - Nguyễn Văn A (Phòng IT)</h5>
                                            <p class="mb-1">Bút bi (10 cái), Giấy A4 (2 thùng)</p>
                                            <small>Đăng ký: 08/01/2026 | Cần có: 10/01/2026</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-warning mb-2">Ưu tiên cao</span><br>
                                            <a href="/detail/VP001" class="btn btn-sm btn-outline-primary me-1">Chi tiết</a>
                                            <button class="btn btn-sm btn-success me-1" onclick="quickApprove('VP001')">Duyệt</button>
                                            <button class="btn btn-sm btn-danger" onclick="quickReject('VP001')">Từ chối</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <h5 class="mb-1">VP004 - Hoàng Văn D (Phòng Marketing)</h5>
                                            <p class="mb-1">Máy in (1 cái), Mực in (3 hộp)</p>
                                            <small>Đăng ký: 07/01/2026 | Cần có: 12/01/2026</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-secondary mb-2">Bình thường</span><br>
                                            <a href="/detail/VP004" class="btn btn-sm btn-outline-primary me-1">Chi tiết</a>
                                            <button class="btn btn-sm btn-success me-1" onclick="quickApprove('VP004')">Duyệt</button>
                                            <button class="btn btn-sm btn-danger" onclick="quickReject('VP004')">Từ chối</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="list-group-item priority-urgent">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <h5 class="mb-1">VP005 - Phạm Thị E (Phòng Tài chính)</h5>
                                            <p class="mb-1">Máy tính xách tay (1 cái), Chuột (1 cái)</p>
                                            <small class="text-danger">Đăng ký: 05/01/2026 | Cần có: 09/01/2026 (Quá hạn)</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger mb-2">Khẩn cấp</span><br>
                                            <a href="/detail/VP005" class="btn btn-sm btn-outline-primary me-1">Chi tiết</a>
                                            <button class="btn btn-sm btn-success me-1" onclick="quickApprove('VP005')">Duyệt</button>
                                            <button class="btn btn-sm btn-danger" onclick="quickReject('VP005')">Từ chối</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function quickApprove(requestId) {
                if (confirm('Bạn có chắc chắn muốn phê duyệt đơn ' + requestId + '?')) {
                    alert('Đã phê duyệt đơn ' + requestId);
                    location.reload();
                }
            }
            
            function quickReject(requestId) {
                const reason = prompt('Nhập lý do từ chối:');
                if (reason) {
                    alert('Đã từ chối đơn ' + requestId + ' với lý do: ' + reason);
                    location.reload();
                }
            }
        </script>
    </body>
    </html>
    <?php
    exit;
}

if ($_SERVER['REQUEST_URI'] === '/logout') {
    session_destroy();
    header('Location: /');
    exit;
}

// Default 404
http_response_code(404);
echo "Page not found";
?>
