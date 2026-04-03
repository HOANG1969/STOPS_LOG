@extends('layouts.app')

@section('title', 'Lịch sử phê duyệt')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Lịch sử phê duyệt - {{ $request->request_code }}
                    </h4>
                </div>
                
                <div class="card-body">
                    <!-- Thông tin tóm tắt -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Mã yêu cầu:</strong><br>
                                        <span class="text-primary">{{ $request->request_code }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Người yêu cầu:</strong><br>
                                        {{ $request->requester_name }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Ngày tạo:</strong><br>
                                        {{ $request->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Trạng thái hiện tại:</strong><br>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline lịch sử -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-4">Chi tiết lịch sử xử lý</h5>
                            
                            <div class="timeline">
                                @foreach($timeline as $index => $event)
                                <div class="timeline-item {{ $event['status'] }}">
                                    <div class="timeline-marker">
                                        <i class="fas 
                                            @if($event['action'] === 'created') fa-plus-circle
                                            @elseif($event['action'] === 'forwarded') fa-paper-plane
                                            @elseif($event['action'] === 'approved') fa-check-circle
                                            @elseif($event['action'] === 'rejected') fa-times-circle
                                            @else fa-info-circle
                                            @endif"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <h6 class="timeline-title mb-1">{{ $event['title'] }}</h6>
                                            <small class="text-muted">
                                                {{ $event['timestamp']->format('d/m/Y H:i:s') }}
                                            </small>
                                        </div>
                                        <div class="timeline-body">
                                            <p class="mb-2">{{ $event['description'] }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $event['user'] }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết văn phòng phẩm -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3">Danh sách văn phòng phẩm</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="30%">Tên văn phòng phẩm</th>
                                            <th width="20%">Quy cách</th>
                                            <th width="8%">ĐVT</th>
                                            <th width="12%">Số lượng</th>
                                            <th width="25%">Mục đích sử dụng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($request->requestItems as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->officeSupply->name }}</td>
                                                <td>{{ $item->officeSupply->specification ?? '-' }}</td>
                                                <td>{{ $item->officeSupply->unit }}</td>
                                                <td class="text-center">{{ number_format($item->quantity) }}</td>
                                                <td>{{ $item->purpose }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('employee.requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Về danh sách
                        </a>
                        
                        <div class="btn-group">
                            <a href="{{ route('employee.requests.show', $request) }}" class="btn btn-info">
                                <i class="fas fa-eye me-1"></i>
                                Xem chi tiết
                            </a>
                            
                            @if($request->status === 'pending')
                                <form action="{{ route('employee.requests.forward', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning" 
                                            onclick="return confirm('Bạn có chắc muốn chuyển đơn này để phê duyệt?')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Chuyển phê duyệt
                                    </button>
                                </form>
                            @endif
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
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-left: 40px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -40px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
    z-index: 1;
}

.timeline-item.info .timeline-marker {
    background: #17a2b8;
}

.timeline-item.warning .timeline-marker {
    background: #ffc107;
}

.timeline-item.success .timeline-marker {
    background: #28a745;
}

.timeline-item.danger .timeline-marker {
    background: #dc3545;
}

.timeline-content {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-title {
    color: #495057;
    font-weight: 600;
}

.timeline-body p {
    color: #6c757d;
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 768px) {
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-item {
        padding-left: 30px;
    }
    
    .timeline-marker {
        left: -30px;
        width: 25px;
        height: 25px;
        font-size: 12px;
    }
    
    .timeline::before {
        left: 12px;
    }
}
</style>
@endpush