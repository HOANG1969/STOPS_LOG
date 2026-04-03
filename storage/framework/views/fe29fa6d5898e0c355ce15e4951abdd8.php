<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-box me-2"></i>Quản lý văn phòng phẩm
        </h5>
        <button class="btn btn-success btn-sm" onclick="addNewSupply()">
            <i class="fas fa-plus me-1"></i>Thêm mới
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="admin-supplies-table">
                <thead class="table-dark">
                    <tr>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Danh mục</th>
                        <th>Đơn vị</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal thêm/sửa văn phòng phẩm -->
<div class="modal fade" id="supplyModal" tabindex="-1" aria-labelledby="supplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplyModalLabel">Thêm văn phòng phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="supplyForm">
                    <input type="hidden" id="supplyId" name="id">
                    
                    <div class="mb-3">
                        <label for="supplyName" class="form-label">Tên văn phòng phẩm:</label>
                        <input type="text" class="form-control" id="supplyName" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplyDescription" class="form-label">Mô tả:</label>
                        <textarea class="form-control" id="supplyDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplyCategory" class="form-label">Danh mục:</label>
                                <input type="text" class="form-control" id="supplyCategory" name="category" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplyUnit" class="form-label">Đơn vị:</label>
                                <input type="text" class="form-control" id="supplyUnit" name="unit" required placeholder="cái, hộp, ream...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplyPrice" class="form-label">Giá (VNĐ):</label>
                                <input type="number" class="form-control" id="supplyPrice" name="price" min="0" step="1000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplyStock" class="form-label">Số lượng tồn kho:</label>
                                <input type="number" class="form-control" id="supplyStock" name="stock_quantity" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="supplyActive" name="is_active" checked>
                            <label class="form-check-label" for="supplyActive">
                                Kích hoạt (hiển thị cho người dùng)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="saveSupply()">
                    <i class="fas fa-save me-1"></i>Lưu
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const supplyBaseUrl = '<?php echo e(route('office-supplies.store', [], false)); ?>';

function updateOfficeSuppliesTable(data) {
    let html = '';
    
    if (data.length === 0) {
        html = '<tr><td colspan="8" class="text-center text-muted">Chưa có văn phòng phẩm nào</td></tr>';
    } else {
        data.forEach(supply => {
            let statusBadge = supply.is_active 
                ? '<span class="badge bg-success">Hoạt động</span>' 
                : '<span class="badge bg-secondary">Tạm dừng</span>';
            
            html += `
                <tr>
                    <td><strong>${supply.name}</strong></td>
                    <td>${supply.description || ''}</td>
                    <td>${supply.category}</td>
                    <td>${supply.unit}</td>
                    <td>${formatCurrency(supply.price)}</td>
                    <td>
                        <span class="badge ${supply.stock_quantity < 10 ? 'bg-warning' : 'bg-info'}">
                            ${supply.stock_quantity}
                        </span>
                    </td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editSupply(${supply.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteSupply(${supply.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#admin-supplies-table tbody').html(html);
}

function addNewSupply() {
    $('#supplyModalLabel').text('Thêm văn phòng phẩm');
    $('#supplyForm')[0].reset();
    $('#supplyId').val('');
    $('#supplyActive').prop('checked', true);
    $('#supplyModal').modal('show');
}

function editSupply(supplyId) {
    $('#supplyModalLabel').text('Sửa văn phòng phẩm');
    
    // Get supply data from table or make API call
    $.get(`<?php echo e(route('office-supplies.admin', [], false)); ?>`, function(supplies) {
        const supply = supplies.find(s => s.id === supplyId);
        if (supply) {
            $('#supplyId').val(supply.id);
            $('#supplyName').val(supply.name);
            $('#supplyDescription').val(supply.description || '');
            $('#supplyCategory').val(supply.category);
            $('#supplyUnit').val(supply.unit);
            $('#supplyPrice').val(supply.price);
            $('#supplyStock').val(supply.stock_quantity);
            $('#supplyActive').prop('checked', supply.is_active);
            
            $('#supplyModal').modal('show');
        }
    });
}

function saveSupply() {
    const formData = new FormData(document.getElementById('supplyForm'));
    const supplyId = $('#supplyId').val();
    const isEdit = supplyId !== '';
    
    // Convert FormData to object
    const data = {};
    formData.forEach((value, key) => {
        if (key === 'is_active') {
            data[key] = $('#supplyActive').prop('checked');
        } else {
            data[key] = value;
        }
    });
    data._token = $('meta[name="csrf-token"]').attr('content');

    const url = isEdit ? `${supplyBaseUrl}/${supplyId}` : supplyBaseUrl;
    const method = isEdit ? 'PUT' : 'POST';
    
    if (isEdit) {
        data._method = 'PUT';
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                alert(isEdit ? 'Đã cập nhật văn phòng phẩm!' : 'Đã thêm văn phòng phẩm mới!');
                $('#supplyModal').modal('hide');
                loadOfficeSupplies(); // Reload table
            } else {
                alert('Có lỗi: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Có lỗi xảy ra!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                message = errors.join(', ');
            }
            alert(message);
        }
    });
}

function deleteSupply(supplyId) {
    if (!confirm('Bạn có chắc chắn muốn xóa văn phòng phẩm này?')) {
        return;
    }

    $.ajax({
        url: `${supplyBaseUrl}/${supplyId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('Đã xóa văn phòng phẩm!');
                loadOfficeSupplies(); // Reload table
            } else {
                alert('Có lỗi: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Có lỗi xảy ra!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}
</script><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\partials\manage-supplies.blade.php ENDPATH**/ ?>