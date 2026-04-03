@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Hồ sơ cá nhân</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hồ sơ cá nhân</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row">
                <!-- Card thông tin cá nhân -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="avatar-circle bg-primary text-white mx-auto" style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 48px; font-weight: bold;">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <h5 class="mb-1">{{ $user->full_name ?? $user->name }}</h5>
                            <p class="text-muted mb-2">{{ $user->position ?? 'Nhân viên' }}</p>
                            <p class="text-muted mb-3">
                                <i class="fas fa-building me-1"></i>{{ $user->department ?? 'N/A' }}
                            </p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Chỉnh sửa thông tin
                                </a>
                                <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-key me-1"></i>Đổi mật khẩu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card chi tiết thông tin -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="fas fa-user me-2"></i>Thông tin chi tiết
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-id-badge me-2 text-primary"></i>Mã nhân viên:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->employee_code ?? 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <hr>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-user me-2 text-primary"></i>Tên đăng nhập:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->name }}
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-user-circle me-2 text-primary"></i>Họ và tên:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->full_name ?? 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-envelope me-2 text-primary"></i>Email:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-phone me-2 text-primary"></i>Số điện thoại:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->phone ?? 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-briefcase me-2 text-primary"></i>Chức vụ:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->position ?? 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-building me-2 text-primary"></i>Bộ phận:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->department ?? 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-user-tag me-2 text-primary"></i>Vai trò:</strong>
                                </div>
                                <div class="col-md-8">
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">Quản trị viên</span>
                                    @elseif($user->is_department_head)
                                        <span class="badge bg-success">Trưởng phòng</span>
                                    @elseif($user->is_tchc_manager)
                                        <span class="badge bg-info">Lãnh đạo TCHC</span>
                                    @elseif($user->is_tchc_checker)
                                        <span class="badge bg-warning">Nhân sự TCHC</span>
                                    @else
                                        <span class="badge bg-secondary">Nhân viên</span>
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-calendar-plus me-2 text-primary"></i>Ngày tạo tài khoản:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
