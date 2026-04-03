@extends('layouts.app')

@section('title', 'Quản lý Phiếu đăng ký')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Quản lý Phiếu đăng ký
                    </h4>
                    <a href="{{ route('employee.requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tạo mới Phiếu đăng ký
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Thông tin bộ phận và kỳ -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Bộ phận:</strong> {{ auth()->user()->department ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                                <strong>Kỳ:</strong> {{ now()->format('F Y') }}
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Bảng danh sách yêu cầu -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Mã đơn</th>
                                    <th width="12%">Ngày tạo</th>
                                    <th width="12%">Ngày cần</th>
                                    <th width="10%">Ưu tiên</th>
                                    <th width="12%">Trạng thái</th>
                                    <th width="15%">Người phê duyệt</th>
                                    <th width="19%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $index => $request)
                                    <tr>
                                        <td>{{ $requests->firstItem() + $index }}</td>
                                        <td>
                                            <a href="{{ route('employee.requests.show', $request) }}" class="text-decoration-none">
                                                {{ $request->request_code }}
                                            </a>
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->needed_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($request->priority === 'Normal') bg-info
                                                @elseif($request->priority === 'High') bg-warning
                                                @else bg-danger
                                                @endif">
                                                {{ $request->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($request->status === 'pending') bg-secondary
                                                @elseif($request->status === 'forwarded') bg-warning
                                                @elseif($request->status === 'approved') bg-success
                                                @else bg-danger
                                                @endif">
                                                @switch($request->status)
                                                    @case('pending') Chờ xử lý @break
                                                    @case('forwarded') Đã chuyển @break
                                                    @case('approved') Đã duyệt @break
                                                    @case('rejected') Từ chối @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            {{ $request->approver->name ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('employee.requests.show', $request) }}" 
                                                   class="btn btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($request->status === 'pending')
                                                    <form action="{{ route('employee.requests.forward', $request) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-warning" 
                                                                title="Chuyển phê duyệt"
                                                                onclick="return confirm('Bạn có chắc muốn chuyển đơn này để phê duyệt?')">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('employee.requests.history', $request) }}" 
                                                   class="btn btn-secondary" title="Xem lịch sử">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <br>
                                            <span class="text-muted">Chưa có yêu cầu nào</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endpush