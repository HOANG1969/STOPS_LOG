@extends('layouts.app')

@section('title', 'Trang chủ - Hệ thống quản lý văn phòng phẩm')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Hệ thống quản lý văn phòng phẩm
                </h2>
                <div class="d-flex align-items-center">
                    <span class="me-3">
                        Xin chào, <strong>{{ auth()->user()->full_name ?? auth()->user()->name }}</strong>
                        <br>
                        <small class="text-muted">{{ auth()->user()->department }} - {{ auth()->user()->position }}</small>
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="request-tab" data-bs-toggle="tab" data-bs-target="#request" type="button" role="tab">
                <i class="fas fa-plus-circle me-2"></i>Đăng ký văn phòng phẩm
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="my-requests-tab" data-bs-toggle="tab" data-bs-target="#my-requests" type="button" role="tab">
                <i class="fas fa-file-alt me-2"></i>Yêu cầu của tôi
            </button>
        </li>
        @if(auth()->user()->isApprover() || auth()->user()->isAdmin())
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approval-tab" data-bs-toggle="tab" data-bs-target="#approval" type="button" role="tab">
                <i class="fas fa-check-circle me-2"></i>Phê duyệt yêu cầu
            </button>
        </li>
        @endif
        @if(auth()->user()->isAdmin())
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="supplies-tab" data-bs-toggle="tab" data-bs-target="#supplies" type="button" role="tab">
                <i class="fas fa-box me-2"></i>Quản lý văn phòng phẩm
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="fas fa-users me-2"></i>Quản lý nhân sự
            </button>
        </li>
        @endif
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="mainTabsContent">
        
        <!-- Đăng ký văn phòng phẩm -->
        <div class="tab-pane fade show active" id="request" role="tabpanel">
            @include('partials.request-form')
        </div>

        <!-- Yêu cầu của tôi -->
        <div class="tab-pane fade" id="my-requests" role="tabpanel">
            @include('partials.my-requests')
        </div>

        <!-- Phê duyệt yêu cầu -->
        @if(auth()->user()->isApprover() || auth()->user()->isAdmin())
        <div class="tab-pane fade" id="approval" role="tabpanel">
            @include('partials.approval-requests')
        </div>
        @endif

        <!-- Quản lý văn phòng phẩm -->
        @if(auth()->user()->isAdmin())
        <div class="tab-pane fade" id="supplies" role="tabpanel">
            @include('partials.manage-supplies')
        </div>
        @endif

    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load data when tabs are shown
    $('#my-requests-tab').on('shown.bs.tab', function() {
        loadMyRequests();
    });

    @if(auth()->user()->isApprover() || auth()->user()->isAdmin())
    $('#approval-tab').on('shown.bs.tab', function() {
        loadApprovalRequests();
    });
    @endif

    @if(auth()->user()->isAdmin())
    $('#supplies-tab').on('shown.bs.tab', function() {
        loadOfficeSupplies();
    });
    @endif

    // Load initial data
    loadOfficeSuppliesForRequest();
});

function loadOfficeSuppliesForRequest() {
    $.get('{{ route("office-supplies.index") }}', function(data) {
        let suppliesHtml = '';
        data.forEach(supply => {
            suppliesHtml += `
                <tr data-supply-id="${supply.id}">
                    <td>${supply.name}</td>
                    <td>${supply.description}</td>
                    <td>${supply.unit}</td>
                    <td>${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(supply.price)}</td>
                    <td>${supply.stock_quantity}</td>
                    <td>
                        <input type="number" class="form-control quantity-input" min="1" max="${supply.stock_quantity}" placeholder="0">
                    </td>
                    <td>
                        <input type="text" class="form-control purpose-input" placeholder="Mục đích sử dụng">
                    </td>
                </tr>
            `;
        });
        $('#supplies-table tbody').html(suppliesHtml);
    });
}

function loadMyRequests() {
    $.get('{{ route("supply-requests.my-requests") }}', function(data) {
        updateMyRequestsTable(data);
    });
}

function loadApprovalRequests() {
    $.get('{{ route("supply-requests.for-approval") }}', function(data) {
        updateApprovalRequestsTable(data);
    });
}

function loadOfficeSupplies() {
    $.get('{{ route("office-supplies.admin") }}', function(data) {
        updateOfficeSuppliesTable(data);
    });
}

function updateMyRequestsTable(data) {
    let html = '';
    data.forEach(request => {
        let statusBadge = getStatusBadge(request.status);
        let totalItems = request.request_items.length;
        
        html += `
            <tr>
                <td>${request.request_code}</td>
                <td>${new Date(request.created_at).toLocaleDateString('vi-VN')}</td>
                <td>${statusBadge}</td>
                <td>${request.priority}</td>
                <td>${totalItems} mặt hàng</td>
                <td>
                    <button class="btn btn-sm btn-outline-info" onclick="viewRequestDetails(${request.id})">
                        <i class="fas fa-eye"></i> Xem
                    </button>
                </td>
            </tr>
        `;
    });
    $('#my-requests-table tbody').html(html);
}

function getStatusBadge(status) {
    switch(status) {
        case 'pending':
            return '<span class="badge bg-warning">Chờ duyệt</span>';
        case 'approved':
            return '<span class="badge bg-success">Đã duyệt</span>';
        case 'rejected':
            return '<span class="badge bg-danger">Từ chối</span>';
        default:
            return '<span class="badge bg-secondary">Không xác định</span>';
    }
}
</script>
@endsection

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