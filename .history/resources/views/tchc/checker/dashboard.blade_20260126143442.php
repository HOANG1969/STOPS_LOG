@extends('layouts.app')

@section('title', 'TCHC Checker - Kiểm tra phiếu VPP')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Kiểm tra phiếu văn phòng phẩm</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">TCHC Checker</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-info fs-6 me-3">
                <i class="fas fa-list me-1"></i>{{ $requests->total() }} tổng phiếu
            </span>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-search me-2 text-orange-500"></i>
                    Tất cả phiếu đăng ký văn phòng phẩm - TCHC
                </h6>
            </div>
            
            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('tchc.checker.dashboard') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Tất cả</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Chờ kiểm tra</option>
                        <option value="tchc_checked" {{ request('status') == 'tchc_checked' ? 'selected' : '' }}>Đã kiểm tra</option>
                        <option value="tchc_approved" {{ request('status') == 'tchc_approved' ? 'selected' : '' }}>Đã phê duyệt</option>
                        <option value="tchc_rejected" {{ request('status') == 'tchc_rejected' ? 'selected' : '' }}>Đã từ chối</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="department" class="form-label">Bộ phận</label>
                    <select name="department" id="department" class="form-select">
                        <option value="">Tất cả bộ phận</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="area" class="form-label">Khu vực</label>
                    <select name="area" id="area" class="form-select">
                        <option value="">Tất cả khu vực</option>
                        <option value="HCM" {{ request('area') == 'HCM' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                        <option value="HN" {{ request('area') == 'HN' ? 'selected' : '' }}>Hà Nội</option>
                        <option value="DN" {{ request('area') == 'DN' ? 'selected' : '' }}>Đà Nẵng</option>
                        <option value="TCKT" {{ request('area') == 'TCKT' ? 'selected' : '' }}>TCKT</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('tchc.checker.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($requests->count() == 0)
            <div class="text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không có phiếu nào</h5>
                <p class="text-muted">Không tìm thấy phiếu nào phù hợp với bộ lọc.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="8%">Mã phiếu</th>
                            <th width="15%">Người tạo</th>
                            <th width="12%">Bộ phận</th>
                            <th width="12%">Người duyệt</th>
                            <th width="10%">Ngày duyệt</th>
                            <th width="8%">Ưu tiên</th>
                            <th width="12%">Trạng thái</th>
                            <th width="13%">VPP yêu cầu</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $requestData)
                        @php $request = $requestData->original; @endphp
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ $requestData->request_code }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $requestData->user['name'] }}</div>
                                <small class="text-muted">{{ $requestData->user['position'] }}</small>
                            </td>
                            <td>{{ $requestData->requester_department }}</td>
                            <td>
                                <div class="fw-bold">{{ $requestData->approver['name'] }}</div>
                                <small class="text-muted">{{ $requestData->approver['department'] }}</small>
                            </td>
                            <td>
                                <div>{{ $requestData->approved_at['date'] }}</div>
                                <small class="text-muted">{{ $requestData->approved_at['time'] }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $requestData->priority['class'] }}">{{ $requestData->priority['text'] }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $requestData->status['class'] }}">{{ $requestData->status['text'] }}</span>
                            </td>
                            <td>
                                @if($requestData->items_display['total_count'] > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($requestData->items_display['items'] as $item)
                                    <li class="small">• {{ $item->officeSupply->name }} ({{ $item->quantity }})</li>
                                    @endforeach
                                    @if($requestData->items_display['has_more'])
                                    <li class="text-muted small">và {{ $requestData->items_display['more_count'] }} VPP khác</li>
                                    @endif
                                </ul>
                                @else
                                <span class="text-muted">Chưa có VPP</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('supply-requests.show', $request->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($request->status === 'approved')
                                    <button type="button" class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" data-bs-target="#checkModal{{ $request->id }}" title="Check phiếu">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @if($request->status === 'approved')
                        <!-- Modal Check -->
                        <div class="modal fade" id="checkModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('tchc.checker.check', $request->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Check phiếu {{ $request->request_code ?? '#'.$request->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Xác nhận check phiếu này và chuyển tới TCHC Manager để phê duyệt cuối?
                                            </div>
                                            <div class="mb-3">
                                                <label for="tchc_check_notes{{ $request->id }}" class="form-label">Ghi chú check (tùy chọn)</label>
                                                <textarea name="tchc_check_notes" id="tchc_check_notes{{ $request->id }}" 
                                                          class="form-control" rows="3" placeholder="Nhập ghi chú về quá trình check..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i>Xác nhận check
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $requests->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Auto refresh every 30 seconds
    setInterval(function() {
        if (!$('.modal.show').length) { // Only refresh if no modal is open
            location.reload();
        }
    }, 30000);
});
</script>
@endsection