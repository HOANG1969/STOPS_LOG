@extends('layouts.app')

@section('title', 'TCHC Manager - Phê duyệt cuối VPP')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Phê duyệt cuối văn phòng phẩm</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">TCHC Manager</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-purple text-white fs-6 me-3" style="background-color: #6366f1;">
                <i class="fas fa-stamp me-1"></i>{{ $pendingRequests->total() }} phiếu chờ phê duyệt cuối
            </span>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-stamp me-2" style="color: #6366f1;"></i>
                Danh sách phiếu nhân sự TCHC đã kiểm tra - Chờ lãnh đạo TCHC phê duyệt cuối
            </h6>
        </div>
        <div class="card-body">
            @if($pendingRequests->count() == 0)
            <div class="text-center py-5">
                <i class="fas fa-stamp fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không có phiếu nào cần phê duyệt cuối</h5>
                <p class="text-muted">Tất cả phiếu đã được xử lý.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="8%">Mã phiếu</th>
                            <th width="12%">Người tạo</th>
                            <th width="10%">Bộ phận</th>
                            <th width="12%">Người duyệt</th>
                            <th width="12%">TCHC Checker</th>
                            <th width="10%">Ngày check</th>
                            <th width="8%">Ưu tiên</th>
                            <th width="20%">VPP yêu cầu</th>
                            <th width="8%">Thao tác</th>
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
                                <small class="text-muted">{{ $request->approved_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $request->tchcChecker->name }}</div>
                                @if($request->tchc_check_notes)
                                <small class="text-muted" title="{{ $request->tchc_check_notes }}">
                                    <i class="fas fa-comment"></i> Có ghi chú
                                </small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $request->tchc_checked_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $request->tchc_checked_at->format('H:i') }}</small>
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
                                            data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}" title="Phê duyệt">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}" title="Từ chối">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Phê duyệt -->
                        <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('tchc.manager.approve', $request->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">Phê duyệt cuối {{ $request->request_code ?? '#'.$request->id }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Phê duyệt cuối phiếu này? Đây là bước cuối cùng trong workflow.
                                            </div>
                                            @if($request->tchc_check_notes)
                                            <div class="alert alert-info">
                                                <strong>Ghi chú của TCHC Checker:</strong><br>
                                                {{ $request->tchc_check_notes }}
                                            </div>
                                            @endif
                                            <div class="mb-3">
                                                <label for="tchc_approval_notes{{ $request->id }}" class="form-label">Ghi chú phê duyệt (tùy chọn)</label>
                                                <textarea name="tchc_approval_notes" id="tchc_approval_notes{{ $request->id }}" 
                                                          class="form-control" rows="3" placeholder="Nhập ghi chú phê duyệt cuối..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i>Phê duyệt cuối
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Từ chối -->
                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('tchc.manager.reject', $request->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Từ chối phiếu {{ $request->request_code ?? '#'.$request->id }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Từ chối phiếu này? Workflow sẽ kết thúc.
                                            </div>
                                            @if($request->tchc_check_notes)
                                            <div class="alert alert-info">
                                                <strong>Ghi chú của TCHC Checker:</strong><br>
                                                {{ $request->tchc_check_notes }}
                                            </div>
                                            @endif
                                            <div class="mb-3">
                                                <label for="tchc_rejection_notes{{ $request->id }}" class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                                                <textarea name="tchc_approval_notes" id="tchc_rejection_notes{{ $request->id }}" 
                                                          class="form-control" rows="3" placeholder="Nhập lý do từ chối..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times me-1"></i>Từ chối
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