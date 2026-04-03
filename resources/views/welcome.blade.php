<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
        }
        
        .hero-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 3rem;
            margin: 2rem 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
        }
        
        .text-white { color: white !important; }
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-3">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold fs-3" href="#">
                    <i class="fas fa-box me-2"></i>
                    Office Supplies
                </a>
                
                <div class="ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero-section text-center text-white">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Hệ thống Quản lý <br>
                        <span class="text-warning">Văn phòng phẩm</span>
                    </h1>
                    <p class="lead mb-4">
                        Giải pháp toàn diện cho việc đăng ký, phê duyệt và quản lý văn phòng phẩm 
                        với quy trình phê duyệt đa cấp hiện đại và hiệu quả.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Vào Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Bắt đầu sử dụng
                            </a>
                        @endauth
                        
                        <button class="btn btn-outline-light" onclick="document.getElementById('features').scrollIntoView()">
                            <i class="fas fa-arrow-down me-2"></i>
                            Tìm hiểu thêm
                        </button>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-clipboard-list" style="font-size: 12rem; opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <i class="fas fa-users fa-2x text-gradient mb-2"></i>
                    <h4 class="text-gradient">Multi-Role</h4>
                    <p class="mb-0">4 cấp phân quyền</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <i class="fas fa-check-circle fa-2x text-gradient mb-2"></i>
                    <h4 class="text-gradient">Workflow</h4>
                    <p class="mb-0">Phê duyệt đa cấp</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <i class="fas fa-chart-line fa-2x text-gradient mb-2"></i>
                    <h4 class="text-gradient">Reports</h4>
                    <p class="mb-0">Báo cáo chi tiết</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <i class="fas fa-mobile-alt fa-2x text-gradient mb-2"></i>
                    <h4 class="text-gradient">Responsive</h4>
                    <p class="mb-0">Giao diện thân thiện</p>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="row mb-5">
            <div class="col-12">
                <h2 class="text-center text-white mb-5 fw-bold">
                    <i class="fas fa-star me-2"></i>
                    Tính năng nổi bật
                </h2>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-file-alt fa-3x text-gradient mb-3"></i>
                    <h5 class="text-gradient mb-3">Quản lý yêu cầu</h5>
                    <p>Tạo và quản lý yêu cầu văn phòng phẩm dễ dàng với giao diện trực quan. 
                    Lưu nháp và gửi yêu cầu linh hoạt.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-sitemap fa-3x text-gradient mb-3"></i>
                    <h5 class="text-gradient mb-3">Phê duyệt đa cấp</h5>
                    <p>Quy trình phê duyệt tự động theo cấp bậc: Nhân viên → Manager → Director 
                    với khả năng tùy chỉnh linh hoạt.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-boxes fa-3x text-gradient mb-3"></i>
                    <h5 class="text-gradient mb-3">Quản lý sản phẩm</h5>
                    <p>Quản lý danh mục và sản phẩm văn phòng phẩm với thông tin chi tiết: 
                    giá cả, nhà cung cấp, đơn vị tính.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-chart-bar fa-3x text-gradient mb-3"></i>
                    <h5 class="text-gradient mb-3">Dashboard & Báo cáo</h5>
                    <p>Dashboard tổng quan và báo cáo chi tiết theo thời gian, 
                    phòng ban, loại sản phẩm với biểu đồ trực quan.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-shield-alt fa-3x text-gradient mb-3"></i>
                    <h5 class="text-gradient mb-3">Bảo mật cao</h5>
                    <p>Hệ thống phân quyền chi tiết, mã hóa dữ liệu và 
                    bảo vệ chống các cuộc tấn công web phổ biến.</p>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <i class="fas fa-cogs fa-3x text-gradient mb-3"></i>
                    <h5 class="text-gradient mb-3">Dễ mở rộng</h5>
                    <p>Kiến trúc modular cho phép tùy chỉnh và mở rộng 
                    dễ dàng theo nhu cầu cụ thể của tổ chức.</p>
                </div>
            </div>
        </div>

        <!-- User Roles Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center text-white mb-5 fw-bold">
                    <i class="fas fa-users me-2"></i>
                    Phân quyền người dùng
                </h2>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <i class="fas fa-user fa-3x text-primary mb-3"></i>
                    <h5 class="text-primary mb-3">Employee</h5>
                    <p>Tạo yêu cầu VPP<br>
                    Theo dõi trạng thái<br>
                    Chỉnh sửa bản nháp</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <i class="fas fa-user-tie fa-3x text-warning mb-3"></i>
                    <h5 class="text-warning mb-3">Manager</h5>
                    <p>Phê duyệt yêu cầu<br>
                    Quản lý nhân viên<br>
                    Xem báo cáo phòng ban</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <i class="fas fa-crown fa-3x text-success mb-3"></i>
                    <h5 class="text-success mb-3">Director</h5>
                    <p>Phê duyệt cao cấp<br>
                    Báo cáo toàn công ty<br>
                    Quản lý ngân sách</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <i class="fas fa-cog fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger mb-3">Admin</h5>
                    <p>Quản lý hệ thống<br>
                    Cấu hình sản phẩm<br>
                    Quản lý người dùng</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="hero-section text-center text-white mb-5">
            <h2 class="mb-4">Sẵn sàng bắt đầu?</h2>
            <p class="lead mb-4">
                Tham gia cùng hàng nghìn doanh nghiệp đã tin tưởng sử dụng hệ thống của chúng tôi.
            </p>
            
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket me-2"></i>
                    Vào Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket me-2"></i>
                    Đăng nhập ngay
                </a>
            @endauth
        </div>

        <!-- Footer -->
        <footer class="text-center text-white py-4">
            <div class="row">
                <div class="col-12">
                    <p class="mb-2">© 2024 Office Supplies Management System</p>
                    <p class="mb-0">
                        <i class="fas fa-heart text-danger"></i> 
                        Được xây dựng với Laravel Framework
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>