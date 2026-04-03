@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Chi tiết yêu cầu văn phòng phẩm</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supply-requests.index') }}">Yêu cầu VPP</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết #{{ $request->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('supply-requests.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            @if(Auth::id() == $request->user_id && in_array($request->status, ['draft']))
                <a href="{{ route('supply-requests.edit', $request->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            @endif
            @if(Auth::user()->isApprover() && $request->status == 'pending')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="fas fa-check me-1"></i> Phê duyệt
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times me-1"></i> Từ chối
                </button>
            @endif
        </div>
    </div>

    <!-- Request Details Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Yêu cầu #{{ $request->id }}
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-2">Thông tin chung</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="text-muted">Người tạo:</td>
                            <td>{{ $request->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Phòng ban:</td>
                            <td>{{ $request->requester_department }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày tạo:</td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày cần:</td>
                            <td>
                                @if($request->needed_date)
                                {{ \Carbon\Carbon::parse($request->needed_date)->format('d/m/Y') }}
                                @else
                                <span class="text-muted">Chưa xác định</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Độ ưu tiên:</td>
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
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái:</td>
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
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    @if($request->notes)
                    <h6 class="fw-bold mb-2">Ghi chú</h6>
                    <div class="border rounded p-3 mb-3 bg-light">
                        {{ $request->notes }}
                    </div>
                    @endif
                    
                    @if($request->approver)
                    <h6 class="fw-bold mb-2">Thông tin phê duyệt</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="text-muted">Người duyệt:</td>
                            <td>{{ $request->approver->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày duyệt:</td>
                            <td>{{ $request->approved_at ? \Carbon\Carbon::parse($request->approved_at)->format('d/m/Y H:i') : 'Chưa duyệt' }}</td>
                        </tr>
                        @if($request->approval_notes)
                        <tr>
                            <td class="text-muted">Ghi chú duyệt:</td>
                            <td>{{ $request->approval_notes }}</td>
                        </tr>
                        @endif
                    </table>
                    @endif
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="fw-bold mb-3">Danh sách văn phòng phẩm</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th width="25%">Tên VPP</th>
                                    <th width="12%">Đơn vị</th>
                                    <th width="10%">Số lượng</th>
                                    <th width="15%">Đơn giá</th>
                                    <th width="15%">Thành tiền</th>
                                    <th width="15%">Mục đích</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($request->requestItems as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $item->officeSupply->name }}</div>
                                        @if($item->officeSupply->description)
                                        <small class="text-muted">{{ $item->officeSupply->description }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $item->officeSupply->unit }}</span>
                                    </td>
                                    <td class="text-center">{{ number_format($item->quantity) }}</td>
                                    <td class="text-end">{{ number_format($item->officeSupply->price, 0, ',', '.') }}đ</td>
                                    <td class="text-end">
                                        @php 
                                        $itemTotal = $item->quantity * $item->officeSupply->price;
                                        $total += $itemTotal;
                                        @endphp
                                        {{ number_format($itemTotal, 0, ',', '.') }}đ
                                    </td>
                                    <td>
                                        <div class="text-truncate" title="{{ $item->purpose }}">
                                            {{ $item->purpose }}
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="5" class="text-end">Tổng cộng:</th>
                                    <th class="text-end">{{ number_format($total, 0, ',', '.') }}đ</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@if(Auth::user()->isApprover() && $request->status == 'pending')
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('supply-requests.approve', $request->id) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Phê duyệt yêu cầu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn phê duyệt yêu cầu này?</p>
                    <div class="mb-3">
                        <label for="approval_notes" class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea name="approval_notes" id="approval_notes" class="form-control" rows="3" placeholder="Nhập ghi chú..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Phê duyệt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('supply-requests.reject', $request->id) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Từ chối yêu cầu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn từ chối yêu cầu này?</p>
                    <div class="mb-3">
                        <label for="reject_notes" class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea name="approval_notes" id="reject_notes" class="form-control" rows="3" placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection