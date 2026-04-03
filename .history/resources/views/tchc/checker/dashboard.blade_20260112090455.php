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
            <span class="badge bg-warning text-dark fs-6 me-3">
                <i class="fas fa-clock me-1"></i>{{ $pendingRequests->total() }} phiếu chờ check
            </span>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-search me-2 text-orange-500"></i>
                Danh sách phiếu trưởng bộ phận đã phê duyệt - Chờ TCHC kiểm tra
            </h6>
        </div>
        <div class="card-body">
            @if($pendingRequests->count() == 0)
            <div class="text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không có phiếu nào cần check</h5>
                <p class="text-muted">Tất cả phiếu đã được xử lý.</p>
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
                            <th width="25%">VPP yêu cầu</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ $request->request_code ?? '#'.$request->id }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $request->user->name }}</div>
                                <small class="text-muted">{{ $request->user->position }}</small>
                            </td>
                            <td>{{ $request->requester_department }}</td>
                            <td>
                                <div class="fw-bold">{{ $request->approver->name }}</div>
                                <small class="text-muted">{{ $request->approver->department }}</small>
                            </td>
                            <td>
                                <div>{{ $request->approved_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $request->approved_at->format('H:i') }}</small>
                            </td>
                            <td>
                                @php
                                $priorityConfig = [
                                    'low' => ['class' => 'bg-light text-dark', 'text' => 'Thấp'],
                                    'normal' => ['class' => 'bg-primary', 'text' => 'Bình thường'],
                                    'high' => ['class' => 'bg-warning text-dark', 'text' => 'Cao'],
                                    'urgent' => ['class' => 'bg-danger', 'text' => 'Khẩn cấp']
                                ];
                                $priorityClass = $priorityConfig[$request->priority]['class'];
                                $priorityText = $priorityConfig[$request->priority]['text'];
                                @endphp
                                <span class="badge {{ $priorityClass }}">{{ $priorityText }}</span>
                            </td>
                            <td>
                                @if($request->requestItems->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($request->requestItems->take(2) as $item)
                                    <li class="small">• {{ $item->officeSupply->name }} ({{ $item->quantity }})</li>
                                    @endforeach
                                    @if($request->requestItems->count() > 2)
                                    <li class="text-muted small">và {{ $request->requestItems->count() - 2 }} VPP khác</li>
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
                                    <button type="button" class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" data-bs-target="#checkModal{{ $request->id }}" title="Check phiếu">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

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
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $pendingRequests->links() }}
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