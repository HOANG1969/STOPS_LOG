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

if ($_SERVER['REQUEST_URI'] === '/logout') {
    session_destroy();
    header('Location: /');
    exit;
}

// Default 404
http_response_code(404);
echo "Page not found";
?>
