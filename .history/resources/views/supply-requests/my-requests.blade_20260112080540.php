@extends('layouts.app')

@section('title', 'Yêu cầu của tôi')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Yêu cầu của tôi</h1>
        <a href="{{ route('office-supplies.index') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tạo yêu cầu mới
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách yêu cầu văn phòng phẩm</h5>
                </div>
                <div class="card-body">
                    @if($requests->count() == 0)
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Bạn chưa có yêu cầu nào</h4>
                        <p class="text-muted">Nhấn "Tạo yêu cầu mới" để đăng ký văn phòng phẩm</p>
                        <a href="{{ route('office-supplies.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Tạo yêu cầu mới
                        </a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th width="25%">Ngày tạo</th>
                                    <th width="15%">Trạng thái</th>
                                    <th width="12%">Ưu tiên</th>
                                    <th width="20%">Ngày cần</th>
                                    <th width="20%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $index => $request)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div>{{ $request->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @php
                                        $statusConfig = [
                                            'draft' => ['class' => 'bg-secondary', 'text' => 'Bản nháp', 'icon' => 'fas fa-edit'],
                                            'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ duyệt', 'icon' => 'fas fa-clock'],
                                            'approved' => ['class' => 'bg-success', 'text' => 'Đã duyệt', 'icon' => 'fas fa-check'],
                                            'partially_approved' => ['class' => 'bg-info', 'text' => 'Duyệt một phần', 'icon' => 'fas fa-check-double'],
                                            'rejected' => ['class' => 'bg-danger', 'text' => 'Từ chối', 'icon' => 'fas fa-times'],
                                            'completed' => ['class' => 'bg-dark', 'text' => 'Hoàn thành', 'icon' => 'fas fa-flag-checkered']
                                        ];
                                        $config = $statusConfig[$request->status] ?? $statusConfig['draft'];
                                        @endphp
                                        <span class="badge {{ $config['class'] }}">
                                            <i class="{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                        </span>
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
                                        @if($request->needed_date)
                                        <div>{{ \Carbon\Carbon::parse($request->needed_date)->format('d/m/Y') }}</div>
                                        @else
                                        <span class="text-muted">Chưa xác định</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="viewRequestDetails({{ $request->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            @if($request->status == 'draft')
                                            <a href="{{ route('office-supplies.index') }}?edit={{ $request->id }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            
                                            @if(in_array($request->status, ['draft', 'rejected']))
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteRequest({{ $request->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $requests->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết yêu cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detail-content">
                <div class="text-center py-3">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa yêu cầu này không?</p>
                <p class="text-muted mb-0">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentRequestId = null;

function viewRequestDetails(requestId) {
    window.location.href = `/supply-requests/${requestId}`;
}

function deleteRequest(requestId) {
    currentRequestId = requestId;
    $('#deleteModal').modal('show');
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (currentRequestId) {
        $.ajax({
            url: `/supply-requests/${currentRequestId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra!');
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra!');
            }
        });
    }
});

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $('.container').prepend(alertHtml);
}
</script>
@endsection