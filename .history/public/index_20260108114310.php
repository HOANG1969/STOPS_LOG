<?php

// Simple login check without Laravel complexity
session_start();

if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($email === 'admin@example.com' && $password === 'password') {
            $_SESSION['logged_in'] = true;
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
                                    <strong>Demo Account:</strong><br>
                                    Email: admin@example.com<br>
                                    Password: password
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

if ($_SERVER['REQUEST_URI'] === '/logout') {
    session_destroy();
    header('Location: /');
    exit;
}

// Default 404
http_response_code(404);
echo "Page not found";
?>
