@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
        <div class="btn-group" role="group">
            <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-users"></i> Quản lý nhân sự
            </a>
            <a href="{{ route('admin.import.office-supplies') }}" class="btn btn-outline-success">
                <i class="fas fa-file-import"></i> Import VPP
            </a>
            <a href="{{ route('office-supplies.admin') }}" class="btn btn-outline-info">
                <i class="fas fa-boxes"></i> Quản lý VPP
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                            <p class="mb-0">Tổng nhân sự</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary bg-opacity-10">
                    <small>{{ $stats['active_users'] }} đang hoạt động</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $stats['total_supplies'] }}</h4>
                            <p class="mb-0">Tổng VPP</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success bg-opacity-10">
                    <small>{{ $stats['active_supplies'] }} đang hoạt động</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $stats['pending_requests'] }}</h4>
                            <p class="mb-0">Yêu cầu chờ</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-warning bg-opacity-10">
                    <small>Cần xem xét</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $stats['low_stock_supplies'] }}</h4>
                            <p class="mb-0">VPP sắp hết</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-danger bg-opacity-10">
                    <small>≤ 10 sản phẩm</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Requests -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-list"></i> Yêu cầu gần nhất</h5>
                </div>
                <div class="card-body">
                    @if($recentRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Người yêu cầu</th>
                                        <th>Số lượng VPP</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                    <tr>
                                        <td>#{{ $request->id }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->requestItems->count() }} sản phẩm</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge bg-success">Đã duyệt</span>
                                            @elseif($request->status == 'rejected')
                                                <span class="badge bg-danger">Từ chối</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('supply-requests.show', $request->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Chưa có yêu cầu nào.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-exclamation-triangle text-warning"></i> VPP sắp hết hàng</h5>
                </div>
                <div class="card-body">
                    @if($lowStockSupplies->count() > 0)
                        @foreach($lowStockSupplies as $supply)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $supply->name }}</strong>
                                <small class="text-muted d-block">{{ $supply->category }}</small>
                            </div>
                            <span class="badge bg-{{ $supply->stock_quantity <= 5 ? 'danger' : 'warning' }}">
                                {{ $supply->stock_quantity }} {{ $supply->unit }}
                            </span>
                        </div>
                        <hr class="my-2">
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Tất cả VPP đều đủ hàng.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line"></i> Thống kê 6 tháng gần nhất</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyStats = @json($monthlyStats);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyStats.map(stat => stat.month),
            datasets: [
                {
                    label: 'Yêu cầu VPP',
                    data: monthlyStats.map(stat => stat.requests),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                },
                {
                    label: 'Nhân sự mới',
                    data: monthlyStats.map(stat => stat.users),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Xu hướng 6 tháng gần nhất'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection