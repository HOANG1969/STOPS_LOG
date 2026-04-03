@extends('layouts.app')

@section('title', 'Tạo Phiếu đăng ký văn phòng phẩm')

@section('content')
<div class="container-fluid">
    <form action="{{ route('employee.requests.store') }}" method="POST" id="requestForm">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Tạo mới Phiếu đăng ký
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <!-- Thông tin chung -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <strong>Bộ phận:</strong> {{ auth()->user()->department ?? 'N/A' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Kỳ:</strong> {{ \Carbon\Carbon::now()->format('F Y') }}
                            </div>
                            <div class="col-md-4">
                                <strong>Người tạo:</strong> {{ auth()->user()->name }}
                            </div>
                        </div>

                        <!-- Form thông tin yêu cầu -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Mức độ ưu tiên <span class="text-danger">*</span></label>
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="">Chọn mức độ ưu tiên</option>
                                    <option value="Normal" {{ old('priority') === 'Normal' ? 'selected' : '' }}>Bình thường</option>
                                    <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>Cao</option>
                                    <option value="Urgent" {{ old('priority') === 'Urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày cần sử dụng <span class="text-danger">*</span></label>
                                <input type="date" name="needed_date" 
                                       class="form-control @error('needed_date') is-invalid @enderror"
                                       value="{{ old('needed_date', \Carbon\Carbon::tomorrow()->format('Y-m-d')) }}" 
                                       min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                       required>
                                @error('needed_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" placeholder="Nhập ghi chú (nếu có)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Danh sách văn phòng phẩm -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Danh sách văn phòng phẩm yêu cầu</h5>
                                <button type="button" class="btn btn-success btn-sm" onclick="addSupplyRow()">
                                    <i class="fas fa-plus me-1"></i>
                                    Thêm VPP
                                </button>
                            </div>

                            @if($errors->has('items'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('items') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered" id="supplyTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Tên VPP <span class="text-danger">*</span></th>
                                            <th width="15%">Quy cách, xuất xử</th>
                                            <th width="8%">ĐVT</th>
                                            <th width="10%">SL tồn</th>
                                            <th width="12%">SL đề xuất <span class="text-danger">*</span></th>
                                            <th width="10%">Định mức</th>
                                            <th width="20%">Mục đích sử dụng <span class="text-danger">*</span></th>
                                            <th width="5%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="supplyTableBody">
                                        <!-- Rows sẽ được thêm bằng JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Nút hành động -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employee.requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Lưu lại
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal chọn văn phòng phẩm -->
<div class="modal fade" id="selectSupplyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn văn phòng phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchSupply" placeholder="Tìm kiếm văn phòng phẩm...">
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>Tên VPP</th>
                                <th>Quy cách</th>
                                <th>ĐVT</th>
                                <th>Tồn kho</th>
                                <th>Định mức</th>
                                <th>Chọn</th>
                            </tr>
                        </thead>
                        <tbody id="supplyListBody">
                            @foreach($officeSupplies as $supply)
                            <tr data-supply-id="{{ $supply->id }}">
                                <td>{{ $supply->name }}</td>
                                <td>{{ $supply->specification ?? '-' }}</td>
                                <td>{{ $supply->unit }}</td>
                                <td>{{ $supply->stock_quantity }}</td>
                                <td>{{ $supply->standard_quantity ?? '-' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary select-supply"
                                            data-supply-id="{{ $supply->id }}"
                                            data-supply-name="{{ $supply->name }}"
                                            data-supply-specification="{{ $supply->specification }}"
                                            data-supply-unit="{{ $supply->unit }}"
                                            data-supply-stock="{{ $supply->stock_quantity }}"
                                            data-supply-standard="{{ $supply->standard_quantity }}">
                                        Chọn
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let rowIndex = 0;
let currentRowIndex = null;

// Thêm dòng văn phòng phẩm
function addSupplyRow() {
    currentRowIndex = rowIndex;
    $('#selectSupplyModal').modal('show');
}

// Chọn văn phòng phẩm từ modal
$(document).on('click', '.select-supply', function() {
    const supplyId = $(this).data('supply-id');
    const supplyName = $(this).data('supply-name');
    const specification = $(this).data('supply-specification') || '-';
    const unit = $(this).data('supply-unit');
    const stock = $(this).data('supply-stock');
    const standard = $(this).data('supply-standard') || '-';

    // Kiểm tra xem văn phòng phẩm đã được chọn chưa
    const existingRow = $(`#supplyTableBody tr[data-supply-id="${supplyId}"]`);
    if (existingRow.length > 0) {
        alert('Văn phòng phẩm này đã được chọn!');
        return;
    }

    const newRow = `
        <tr data-supply-id="${supplyId}">
            <td>${rowIndex + 1}</td>
            <td>
                ${supplyName}
                <input type="hidden" name="items[${rowIndex}][supply_id]" value="${supplyId}">
            </td>
            <td>${specification}</td>
            <td>${unit}</td>
            <td>${stock}</td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" 
                       class="form-control form-control-sm" 
                       min="1" max="${stock}" required>
            </td>
            <td>${standard}</td>
            <td>
                <input type="text" name="items[${rowIndex}][purpose]" 
                       class="form-control form-control-sm" 
                       placeholder="Mục đích sử dụng" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeSupplyRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#supplyTableBody').append(newRow);
    rowIndex++;
    
    $('#selectSupplyModal').modal('hide');
    updateRowNumbers();
});

// Xóa dòng văn phòng phẩm
function removeSupplyRow(btn) {
    $(btn).closest('tr').remove();
    updateRowNumbers();
}

// Cập nhật số thứ tự
function updateRowNumbers() {
    $('#supplyTableBody tr').each(function(index) {
        $(this).find('td:first').text(index + 1);
    });
}

// Tìm kiếm văn phòng phẩm
$('#searchSupply').on('keyup', function() {
    const searchTerm = $(this).val().toLowerCase();
    $('#supplyListBody tr').each(function() {
        const text = $(this).text().toLowerCase();
        $(this).toggle(text.includes(searchTerm));
    });
});

// Validation form
$('#requestForm').on('submit', function(e) {
    const rowCount = $('#supplyTableBody tr').length;
    if (rowCount === 0) {
        e.preventDefault();
        alert('Vui lòng thêm ít nhất một văn phòng phẩm!');
        return false;
    }
});

// Thêm dòng đầu tiên khi load trang
$(document).ready(function() {
    @if(old('items'))
        @foreach(old('items') as $index => $item)
            // Khôi phục dữ liệu cũ nếu có lỗi validation
        @endforeach
    @endif
});
</script>
@endpush