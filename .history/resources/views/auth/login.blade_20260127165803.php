<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Office Supplies Management</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .login-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 2rem 4rem rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            position: relative;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: #dd2020ff;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            width: 100%;
            padding: 0.75rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        .demo-accounts {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #dee2e6;
        }
        .demo-account {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-boxes fa-2x text-primary mb-3"></i>
                <h1>PVGAS LOGISTICS</h1>
                <p>Đăng nhập vào hệ thống quản lý văn phòng phẩm</p>
            </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            @foreach($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            <label for="email">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Mật khẩu
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" checked>
                            <label class="form-check-label" for="remember">
                                <i class="fas fa-clock me-1"></i>
                                Ghi nhớ đăng nhập (30 ngày)
                            </label>
                            <small class="form-text text-muted d-block">
                                Bạn sẽ không cần đăng nhập lại trên thiết bị này
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Đăng nhập
                        </button>
                    </form>

                    <!-- <div class="demo-accounts">
                        <h6 class="text-muted mb-2">Demo Accounts:</h6>
                        <div class="demo-account">
                            <strong>Admin:</strong> admin@test.com / admin123
                        </div>
                        <div class="demo-account">
                            <strong>HR Manager:</strong> hr@test.com / hr123
                        </div>
                        <div class="demo-account">
                            <strong>IT Manager:</strong> it@test.com / it123
                        </div>
                        <div class="demo-account">
                            <strong>Employee 1:</strong> user1@test.com / user123
                        </div>
                        <div class="demo-account">
                            <strong>Employee 2:</strong> user2@test.com / user123
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide password
        const passwordInput = document.getElementById('password');
        const togglePassword = document.createElement('button');
        togglePassword.type = 'button';
        togglePassword.classList.add('btn', 'btn-outline-secondary', 'position-absolute', 'end-0', 'top-0', 'h-100');
        togglePassword.style.zIndex = '10';
        togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
        
        passwordInput.parentNode.style.position = 'relative';
        passwordInput.parentNode.appendChild(togglePassword);
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

        // Auto-focus email field
        document.getElementById('email').focus();

        // Remember me tooltip
        const rememberCheckbox = document.getElementById('remember');
        rememberCheckbox.title = 'Với tính năng này, bạn sẽ tự động đăng nhập trong vòng 30 ngày mà không cần nhập lại thông tin đăng nhập';

        // Form submission loading state
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
        });

        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    });
    </script>
</body>
</html>