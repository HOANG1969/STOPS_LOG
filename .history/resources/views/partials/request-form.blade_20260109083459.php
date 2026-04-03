<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Tạo mới Phiếu đăng ký
        </h5>
        <small class="text-muted">Bộ phận: {{ Auth::user()->department }} | Kỳ: {{ now()->format('m - Y') }}</small>
    </div>
    <div class="card-body">
        <form id="request-form">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Mức độ ưu tiên:</label>
                    <select name="priority" class="form-select" required>
                        <option value="normal">Bình thường</option>
                        <option value="urgent">Khẩn cấp</option>
                        <option value="emergency">Rất khẩn cấp</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Ghi chú:</label>
                    <input type="text" name="notes" class="form-control" placeholder="Ghi chú thêm (nếu có)">
                </div>
            </div>

            <!-- Danh sách văn phóng phẩm đã chọn -->
            <div class="mb-3">
                <label class="form-label"><strong>Danh sách văn phòng phẩm đăng ký:</strong></label>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="selected-supplies-table">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Tên VPP</th>
                                <th width="20%">Quy cách, xuất xứ</th>
                                <th width="8%">ĐVT</th>
                                <th width="8%">SL tồn</th>
                                <th width="10%">SL đề xuất</th>
                                <th width="8%">Định mức</th>
                                <th width="11%">Ghi chú</th>
                                <th width="5%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="add-item-row">
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="showSupplySelector()" title="Thêm văn phòng phẩm">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                                <td colspan="8" class="text-center text-muted py-3">
                                    <i class="fas fa-mouse-pointer me-2"></i>
                                    <span>Click nút "+" để thêm văn phòng phẩm vào danh sách</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div id="request-summary" class="alert alert-info">
                        Chưa có văn phòng phẩm nào được chọn
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary" onclick="clearForm()">
                            <i class="fas fa-times me-1"></i>Hủy
                        </button>
                        <button type="button" class="btn btn-warning" onclick="saveDraft()">
                            <i class="fas fa-save me-1"></i>Lưu lại
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Gửi phê duyệt
                        </button>
                    </div>
                </div>
            </div>
        </form>
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
                                <th width="30%">Tên VPP</th>
                                <th width="20%">Mô tả</th>
                                <th width="15%">Danh mục</th>
                                <th width="10%">ĐVT</th>
                                <th width="10%">Tồn kho</th>
                                <th width="10%">Thao tác</th>
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
    
    // Initialize select2 for supply search
    $('#supply-search').on('change', function() {
        const supplyId = $(this).val();
        if (supplyId) {
            addSupplyToList(supplyId);
            $(this).val('').trigger('change');
        }
    });

    // Submit form
    $('#request-form').on('submit', function(e) {
        e.preventDefault();
        submitRequest('pending');
    });
});

function loadOfficeSuppliesForRequest() {
    $.ajax({
        url: '{{ route("office-supplies.api.for-request") }}',
        method: 'GET',
        success: function(supplies) {
            allSupplies = supplies;
            
            // Populate dropdown
            let options = '<option value="">-- Chọn văn phòng phẩm --</option>';
            supplies.forEach(function(supply) {
                if (supply.stock_quantity > 0 && supply.is_active) {
                    options += `<option value="${supply.id}" data-supply='${JSON.stringify(supply)}'>
                        ${supply.name} - ${supply.category} (Tồn: ${supply.stock_quantity} ${supply.unit})
                    </option>`;
                }
            });
            
            $('#supply-search').html(options);
        },
        error: function(xhr) {
            console.error('Error loading office supplies:', xhr);
            showAlert('danger', 'Không thể tải danh sách văn phòng phẩm. Vui lòng thử lại sau.');
        }
    });
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
        purpose: '',
        quota: 0
    });
    
    renderSelectedSupplies();
    updateRequestSummary();
}

function renderSelectedSupplies() {
    const tbody = $('#selected-supplies-table tbody');
    tbody.empty();
    
    selectedSupplies.forEach(function(item, index) {
        const stockClass = item.stock_quantity <= 10 ? 'text-danger' : 'text-success';
        const stockIcon = item.stock_quantity <= 10 ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
        
        const row = `
            <tr data-index="${index}">
                <td class="text-center">${index + 1}</td>
                <td>
                    <div class="fw-bold">${item.name}</div>
                    <small class="text-muted">${item.category || ''}</small>
                </td>
                <td>
                    <small>${item.description || 'Không có thông tin'}</small>
                </td>
                <td class="text-center">
                    <span class="badge bg-light text-dark">${item.unit}</span>
                </td>
                <td class="text-center">
                    <span class="${stockClass}">
                        <i class="${stockIcon} me-1"></i>${item.stock_quantity}
                    </span>
                </td>
                <td>
                    <input type="number" 
                           class="form-control form-control-sm quantity-input text-center" 
                           value="${item.quantity}"
                           min="1" 
                           max="${item.stock_quantity}" 
                           data-index="${index}">
                </td>
                <td>
                    <input type="number" 
                           class="form-control form-control-sm quota-input text-center" 
                           value="${item.quota}"
                           min="0" 
                           data-index="${index}"
                           placeholder="0">
                </td>
                <td>
                    <input type="text" 
                           class="form-control form-control-sm purpose-input" 
                           value="${item.purpose}"
                           data-index="${index}"
                           placeholder="Mục đích sử dụng">
                </td>
                <td class="text-center">
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            onclick="removeSupply(${index})"
                            title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
    
    // Add the "add new item" row
    tbody.append(`
        <tr id="add-item-row">
            <td class="text-center">
                <span class="badge bg-secondary">+</span>
            </td>
            <td>
                <select class="form-select form-select-sm" id="supply-search" style="width: 100%;">
                    <option value="">-- Chọn văn phòng phẩm --</option>
                </select>
            </td>
            <td colspan="7" class="text-center text-muted">
                <small>Chọn văn phòng phẩm để thêm vào danh sách</small>
            </td>
        </tr>
    `);
    
    // Re-populate the dropdown
    loadOfficeSuppliesForRequest();
    
    // Bind events for inputs
    bindInputEvents();
}

function bindInputEvents() {
    $(document).off('input', '.quantity-input, .purpose-input, .quota-input');
    $(document).on('input', '.quantity-input, .purpose-input, .quota-input', function() {
        const index = $(this).data('index');
        const field = $(this).hasClass('quantity-input') ? 'quantity' : 
                     $(this).hasClass('quota-input') ? 'quota' : 'purpose';
        
        if (selectedSupplies[index]) {
            selectedSupplies[index][field] = $(this).val();
            updateRequestSummary();
        }
    });
}

function removeSupply(index) {
    selectedSupplies.splice(index, 1);
    renderSelectedSupplies();
    updateRequestSummary();
}

function updateRequestSummary() {
    const totalItems = selectedSupplies.length;
    let totalValue = 0;
    
    selectedSupplies.forEach(item => {
        totalValue += (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0);
    });
    
    let summaryHtml = '';
    if (totalItems > 0) {
        summaryHtml = `
            <div class="row">
                <div class="col-md-8">
                    <strong>Đã chọn ${totalItems} loại văn phòng phẩm</strong>
                    <div class="mt-2">
                        ${selectedSupplies.map(item => 
                            `<span class="badge bg-primary me-1">${item.name} x${item.quantity}</span>`
                        ).join('')}
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <strong>Tổng ước tính: ${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(totalValue)}</strong>
                </div>
            </div>
        `;
    } else {
        summaryHtml = 'Chưa có văn phòng phẩm nào được chọn';
    }

    $('#request-summary').html(summaryHtml);
}

function saveDraft() {
    submitRequest('draft');
}

function submitRequest(status = 'pending') {
    if (selectedSupplies.length === 0) {
        showAlert('warning', 'Vui lòng chọn ít nhất một văn phòng phẩm!');
        return;
    }

    // Validate all items have purpose
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
        status: status,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    const submitText = status === 'draft' ? 'Đang lưu nháp...' : 'Đang gửi yêu cầu...';
    const button = status === 'draft' ? $('button[onclick="saveDraft()"]') : $('button[type="submit"]');
    const originalText = button.html();
    
    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>' + submitText);

    $.ajax({
        url: '{{ route("supply-requests.store") }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                const message = status === 'draft' ? 'Đã lưu nháp thành công!' : 'Đã gửi yêu cầu phê duyệt thành công!';
                showAlert('success', message);
                clearForm();
                
                // Scroll to top to see the success message
                $('html, body').animate({scrollTop: 0}, 500);
                
                // Redirect to dashboard after 2 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard") }}';
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
    updateRequestSummary();
}
</script>