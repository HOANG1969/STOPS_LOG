@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-user me-2 text-primary"></i>
                    Chào mừng, {{ Auth::user()->name }}!
                </h5>
                <p class="card-text text-muted">
                    Vai trò: <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span> - 
                    Phòng ban: {{ Auth::user()->department }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">12</h4>
                        <p class="card-text">Yêu cầu chờ duyệt</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">45</h4>
                        <p class="card-text">Yêu cầu đã duyệt</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">126</h4>
                        <p class="card-text">Sản phẩm</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">8</h4>
                        <p class="card-text">Danh mục</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tags fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('office-supplies.create') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>
                            Tạo yêu cầu mới
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('office-supplies.index') }}" class="btn btn-outline-success btn-lg w-100">
                            <i class="fas fa-list me-2"></i>
                            Xem tất cả yêu cầu
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="#" class="btn btn-outline-info btn-lg w-100">
                            <i class="fas fa-chart-bar me-2"></i>
                            Báo cáo thống kê
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="#" class="btn btn-outline-warning btn-lg w-100">
                            <i class="fas fa-cog me-2"></i>
                            Cài đặt hệ thống
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Hoạt động gần đây
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Yêu cầu mới được tạo</h6>
                            <small class="text-muted">2 giờ trước</small>
                        </div>
                    </div>
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Yêu cầu #123 đã được duyệt</h6>
                            <small class="text-muted">5 giờ trước</small>
                        </div>
                    </div>
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Cập nhật danh mục sản phẩm</h6>
                            <small class="text-muted">1 ngày trước</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
}
.timeline-item {
    position: relative;
    padding-left: 1.5rem;
}
.timeline-marker {
    position: absolute;
    left: 0;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
}
.opacity-50 {
    opacity: 0.5;
}
</style>
@endpush