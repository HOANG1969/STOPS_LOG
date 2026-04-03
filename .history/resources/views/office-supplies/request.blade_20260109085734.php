@extends('layouts.app')

@section('title', 'Tạo Phiếu đăng ký văn phòng phẩm')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Tạo Phiếu đăng ký văn phòng phẩm</h1>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
            </a>
        </div>
    </div>

    <!-- Alert container for messages -->
    <div id="alert-container"></div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form id="request-form">
                <!-- Thông tin cơ bản -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label"><strong>Bộ phận:</strong></label>
                        <div class="form-control-plaintext">{{ Auth::user()->department }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><strong>Người tạo:</strong></label>
                        <div class="form-control-plaintext">{{ Auth::user()->full_name ?? Auth::user()->name }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><strong>Ngày:</strong></label>
                        <div class="form-control-plaintext">{{ now()->format('d/m/Y') }}</div>
                    </div>
                </div>

                <!-- Mức độ ưu tiên và Ngày cần sử dụng -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Mức độ ưu tiên: <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select" required>
                            <option value="low">Thấp</option>
                            <option value="normal" selected>Bình thường</option>
                            <option value="high">Cao</option>
                            <option value="urgent">Khẩn cấp</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ngày cần sử dụng: <span class="text-danger">*</span></label>
                        <input type="date" name="needed_date" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                    </div>
                </div>

                <!-- Ghi chú -->
                <div class="mb-4">
                    <label class="form-label">Ghi chú:</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Nhập ghi chú (nếu có)"></textarea>
                </div>

                <!-- Danh sách văn phòng phẩm -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Danh sách văn phòng phẩm</h5>
                    <button type="button" class="btn btn-success" onclick="showSupplySelector()">
                        <i class="fas fa-plus me-1"></i>Thêm VPP
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="selected-supplies-table">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Tên VPP</th>
                                <th width="15%">Số lượng</th>
                                <th width="35%">Mục đích</th>
                                <th width="10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="empty-row">
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <div>Chưa có văn phòng phẩm nào được chọn</div>
                                    <small>Click "Thêm VPP" để thêm văn phòng phẩm vào danh sách</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Nút hành động -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" class="btn btn-secondary" onclick="clearForm()">
                        <i class="fas fa-undo me-1"></i>Quay lại
                    </button>
                    
                    <div class="btn-group">
                        <button type="button" class="btn btn-warning" onclick="saveDraft()">
                            <i class="fas fa-save me-1"></i>Lưu phiếu
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Gửi phê duyệt
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal chọn văn phòng phẩm -->
<div class="modal fade" id="supplyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn văn phòng phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="supply-filter" placeholder="Tìm kiếm văn phòng phẩm...">
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover" id="supply-list-table">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Tên VPP</th>
                                <th width="15%">Danh mục</th>
                                <th width="10%">ĐVT</th>
                                <th width="15%">Tồn kho</th>
                                <th width="20%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedSupplies = [];
let allSupplies = [];

$(document).ready(function() {
    loadOfficeSuppliesForRequest();
    
    // Search filter in modal
    $('#supply-filter').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterSuppliesInModal(searchTerm);
    });

    // Submit form
    $('#request-form').on('submit', function(e) {
        e.preventDefault();
        submitRequest('pending');
    });
});

function showSupplySelector() {
    loadSuppliesIntoModal();
    $('#supplyModal').modal('show');
}

function loadSuppliesIntoModal() {
    const tbody = $('#supply-list-table tbody');
    tbody.html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Đang tải...</td></tr>');
    
    if (allSupplies.length === 0) {
        loadOfficeSuppliesForRequest(() => {
            renderSuppliesInModal();
        });
    } else {
        renderSuppliesInModal();
    }
}

function renderSuppliesInModal() {
    const tbody = $('#supply-list-table tbody');
    tbody.empty();

    if (allSupplies.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <div>Không có văn phòng phẩm nào khả dụng</div>
                </td>
            </tr>
        `);
        return;
    }

    allSupplies.forEach(function(supply, index) {
        if (!supply.is_active || supply.stock_quantity <= 0) return;
        
        const isAlreadySelected = selectedSupplies.find(s => s.id == supply.id);
        const stockClass = supply.stock_quantity <= 10 ? 'text-danger' : 'text-success';
        const stockIcon = supply.stock_quantity <= 10 ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
        
        const row = `
            <tr data-supply-id="${supply.id}">
                <td class="text-center">${index + 1}</td>
                <td>
                    <div class="fw-bold">${supply.name}</div>
                    <small class="text-muted">${supply.description || 'Không có mô tả'}</small>
                </td>
                <td><span class="badge bg-info">${supply.category}</span></td>
                <td class="text-center"><span class="badge bg-light text-dark">${supply.unit}</span></td>
                <td class="text-center">
                    <span class="${stockClass}">
                        <i class="${stockIcon} me-1"></i>${supply.stock_quantity}
                    </span>
                </td>
                <td class="text-center">
                    <button type="button" 
                            class="btn ${isAlreadySelected ? 'btn-secondary' : 'btn-success'} btn-sm" 
                            onclick="selectSupply(${supply.id})"
                            ${isAlreadySelected ? 'disabled' : ''}>
                        <i class="fas ${isAlreadySelected ? 'fa-check' : 'fa-plus'} me-1"></i>
                        ${isAlreadySelected ? 'Đã chọn' : 'Chọn'}
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function filterSuppliesInModal(searchTerm) {
    $('#supply-list-table tbody tr').each(function() {
        const row = $(this);
        const text = row.text().toLowerCase();
        
        if (text.includes(searchTerm) || searchTerm === '') {
            row.show();
        } else {
            row.hide();
        }
    });
}

function selectSupply(supplyId) {
    addSupplyToList(supplyId);
    $('#supplyModal').modal('hide');
    renderSuppliesInModal(); // Update modal to show "Đã chọn"
}

function addSupplyToList(supplyId) {
    const supply = allSupplies.find(s => s.id == supplyId);
    if (!supply) return;
    
    // Check if already added
    if (selectedSupplies.find(s => s.id == supplyId)) {
        showAlert('warning', 'Văn phòng phẩm này đã được thêm vào danh sách!');
        return;
    }
    
    selectedSupplies.push({
        id: supply.id,
        name: supply.name,
        description: supply.description,
        unit: supply.unit,
        price: supply.price,
        stock_quantity: supply.stock_quantity,
        quantity: 1,
        purpose: ''
    });
    
    renderSelectedSupplies();
}

function renderSelectedSupplies() {
    const tbody = $('#selected-supplies-table tbody');
    tbody.empty();
    
    if (selectedSupplies.length === 0) {
        tbody.append(`
            <tr id="empty-row">
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <div>Chưa có văn phòng phẩm nào được chọn</div>
                    <small>Click "Thêm VPP" để thêm văn phòng phẩm vào danh sách</small>
                </td>
            </tr>
        `);
        return;
    }

    selectedSupplies.forEach(function(item, index) {
        const row = `
            <tr data-index="${index}">
                <td class="text-center">${index + 1}</td>
                <td>
                    <div class="fw-bold">${item.name}</div>
                    <small class="text-muted">${item.description || ''}</small>
                </td>
                <td>
                    <input type="number" 
                           class="form-control quantity-input" 
                           value="${item.quantity}"
                           min="1" 
                           max="${item.stock_quantity}" 
                           data-index="${index}">
                </td>
                <td>
                    <input type="text" 
                           class="form-control purpose-input" 
                           value="${item.purpose}"
                           data-index="${index}"
                           placeholder="Nhập mục đích sử dụng">
                </td>
                <td class="text-center">
                    <button type="button" 
                            class="btn btn-danger btn-sm" 
                            onclick="removeSupply(${index})"
                            title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
    
    // Bind events for inputs
    bindInputEvents();
}

function bindInputEvents() {
    $(document).off('input', '.quantity-input, .purpose-input');
    $(document).on('input', '.quantity-input, .purpose-input', function() {
        const index = $(this).data('index');
        const field = $(this).hasClass('quantity-input') ? 'quantity' : 'purpose';
        
        if (selectedSupplies[index]) {
            selectedSupplies[index][field] = $(this).val();
        }
    });
}

function removeSupply(index) {
    selectedSupplies.splice(index, 1);
    renderSelectedSupplies();
}

function loadOfficeSuppliesForRequest(callback = null) {
    $.ajax({
        url: '{{ route("office-supplies.api.for-request") }}',
        method: 'GET',
        success: function(supplies) {
            allSupplies = supplies;
            console.log('Loaded supplies:', supplies.length);
            
            if (callback) {
                callback();
            }
        },
        error: function(xhr) {
            console.error('Error loading office supplies:', xhr);
            showAlert('danger', 'Không thể tải danh sách văn phòng phẩm. Vui lòng thử lại sau.');
        }
    });
}

function saveDraft() {
    submitRequest('draft');
}

function submitRequest(status = 'pending') {
    if (selectedSupplies.length === 0) {
        showAlert('warning', 'Vui lòng chọn ít nhất một văn phòng phẩm!');
        return;
    }

    // Validate all items have purpose for pending requests
    const invalidItems = selectedSupplies.filter(item => !item.purpose.trim());
    if (invalidItems.length > 0 && status === 'pending') {
        showAlert('warning', 'Vui lòng nhập mục đích sử dụng cho tất cả văn phòng phẩm!');
        return;
    }

    const items = selectedSupplies.map(item => ({
        supply_id: item.id,
        quantity: parseInt(item.quantity) || 1,
        purpose: item.purpose.trim() || 'Chưa xác định'
    }));

    const formData = {
        items: items,
        priority: $('[name="priority"]').val(),
        notes: $('[name="notes"]').val(),
        needed_date: $('[name="needed_date"]').val(),
        status: status,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    const submitText = status === 'draft' ? 'Đang lưu phiếu...' : 'Đang gửi phê duyệt...';
    const button = status === 'draft' ? $('button[onclick="saveDraft()"]') : $('button[type="submit"]');
    const originalText = button.html();
    
    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>' + submitText);

    $.ajax({
        url: '{{ route("supply-requests.store") }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                const message = status === 'draft' ? 'Đã lưu phiếu thành công!' : 'Đã gửi phê duyệt thành công!';
                showAlert('success', message);
                clearForm();
                
                // Redirect after success
                setTimeout(() => {
                    window.location.href = '{{ route("supply-requests.my-requests") }}';
                }, 2000);
            } else {
                showAlert('danger', 'Có lỗi: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Có lỗi xảy ra!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showAlert('danger', message);
        },
        complete: function() {
            button.prop('disabled', false).html(originalText);
        }
    });
}

function clearForm() {
    $('#request-form')[0].reset();
    selectedSupplies = [];
    renderSelectedSupplies();
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $('#alert-container').html(alertHtml);
    
    // Auto hide after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
}
</script>
@endsection