@extends('layouts.app')

@section('title', 'Chỉnh sửa đơn yêu cầu')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Chỉnh sửa đơn yêu cầu</h1>
            <p class="text-muted mb-0">Mã đơn: {{ $supplyRequest->request_code }}</p>
        </div>
        <a href="{{ route('dashboard.employee') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    @if($supplyRequest->rejection_reason)
    <div class="alert alert-danger">
        <h6><strong>Lý do từ chối:</strong></h6>
        <p class="mb-0">{{ $supplyRequest->rejection_reason }}</p>
    </div>
    @endif

    <form action="{{ route('supply-requests.update', $supplyRequest) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin đơn yêu cầu</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Người yêu cầu</label>
                                    <input type="text" class="form-control" value="{{ $supplyRequest->requester_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Bộ phận</label>
                                    <input type="text" class="form-control" value="{{ $supplyRequest->requester_department }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mức ưu tiên <span class="text-danger">*</span></label>
                                    <select class="form-select" name="priority" required>
                                        <option value="normal" {{ $supplyRequest->priority === 'normal' ? 'selected' : '' }}>Bình thường</option>
                                        <option value="urgent" {{ $supplyRequest->priority === 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                                        <option value="emergency" {{ $supplyRequest->priority === 'emergency' ? 'selected' : '' }}>Rất khẩn</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày cần</label>
                                    <input type="date" class="form-control" name="needed_date" value="{{ $supplyRequest->needed_date ? $supplyRequest->needed_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea class="form-control" name="notes" rows="3" placeholder="Nhập ghi chú (không bắt buộc)">{{ $supplyRequest->notes }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách văn phòng phẩm</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                            <i class="fas fa-plus"></i> Thêm mục
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="items-container">
                            @foreach($supplyRequest->requestItems as $index => $item)
                            <div class="item-row border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Văn phòng phẩm <span class="text-danger">*</span></label>
                                        <select class="form-select" name="items[{{ $index }}][office_supply_id]" required>
                                            <option value="">Chọn văn phòng phẩm...</option>
                                            @foreach($officeSupplies as $supply)
                                                <option value="{{ $supply->id }}" 
                                                        data-price="{{ $supply->price }}" 
                                                        data-unit="{{ $supply->unit }}"
                                                        {{ $item->office_supply_id == $supply->id ? 'selected' : '' }}>
                                                    {{ $supply->name }} ({{ number_format($supply->price) }} VND/{{ $supply->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="items[{{ $index }}][quantity]" 
                                               value="{{ $item->quantity }}" min="1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mục đích sử dụng</label>
                                        <input type="text" class="form-control" name="items[{{ $index }}][purpose]" 
                                               value="{{ $item->purpose }}" placeholder="Nhập mục đích...">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-danger w-100" onclick="removeItem(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card position-sticky" style="top: 1rem;">
                    <div class="card-header">
                        <h5 class="mb-0">Thao tác</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Lưu thay đổi
                            </button>
                            <a href="{{ route('dashboard.employee') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Hủy bỏ
                            </a>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <small class="text-muted">Sau khi lưu, đơn sẽ được gửi lại để phê duyệt</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let itemIndex = {{ count($supplyRequest->requestItems) }};

function addItem() {
    const container = document.getElementById('items-container');
    const itemHtml = `
        <div class="item-row border rounded p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Văn phòng phẩm <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[${itemIndex}][office_supply_id]" required>
                        <option value="">Chọn văn phòng phẩm...</option>
                        @foreach($officeSupplies as $supply)
                            <option value="{{ $supply->id }}" data-price="{{ $supply->price }}" data-unit="{{ $supply->unit }}">
                                {{ $supply->name }} ({{ number_format($supply->price) }} VND/{{ $supply->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="items[${itemIndex}][quantity]" min="1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mục đích sử dụng</label>
                    <input type="text" class="form-control" name="items[${itemIndex}][purpose]" placeholder="Nhập mục đích...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removeItem(button) {
    const itemRow = button.closest('.item-row');
    if (document.querySelectorAll('.item-row').length > 1) {
        itemRow.remove();
    } else {
        alert('Phải có ít nhất một mục văn phòng phẩm');
    }
}
</script>
@endsection