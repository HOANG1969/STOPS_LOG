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
                                <td>
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

<script>
$(document).ready(function() {
    // Update summary when quantities change
    $(document).on('input', '.quantity-input, .purpose-input', function() {
        updateRequestSummary();
    });

    // Submit form
    $('#request-form').on('submit', function(e) {
        e.preventDefault();
        submitRequest();
    });
});

function updateRequestSummary() {
    let items = [];
    let total = 0;

    $('#supplies-table tbody tr').each(function() {
        let $row = $(this);
        let quantity = parseInt($row.find('.quantity-input').val()) || 0;
        let purpose = $row.find('.purpose-input').val().trim();

        if (quantity > 0 && purpose) {
            let supplyId = $row.data('supply-id');
            let name = $row.find('td:first').text();
            let price = parseFloat($row.find('td:eq(3)').text().replace(/[^\d.-]/g, ''));
            let itemTotal = quantity * price;
            
            items.push({
                supply_id: supplyId,
                name: name,
                quantity: quantity,
                purpose: purpose,
                price: price,
                total: itemTotal
            });
            
            total += itemTotal;
        }
    });

    let summaryHtml = '';
    if (items.length > 0) {
        summaryHtml = `
            <div class="row">
                <div class="col-md-8">
                    <strong>Đã chọn ${items.length} mặt hàng:</strong>
                    <ul class="mb-0 mt-2">
        `;
        
        items.forEach(item => {
            summaryHtml += `<li>${item.name}: ${item.quantity} ${item.purpose ? '(' + item.purpose + ')' : ''}</li>`;
        });
        
        summaryHtml += `
                    </ul>
                </div>
                <div class="col-md-4 text-end">
                    <strong>Tổng ước tính: ${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(total)}</strong>
                </div>
            </div>
        `;
    } else {
        summaryHtml = 'Chọn văn phòng phẩm để xem tổng quan';
    }

    $('#request-summary').html(summaryHtml);
}

function submitRequest() {
    let items = [];
    
    $('#supplies-table tbody tr').each(function() {
        let $row = $(this);
        let quantity = parseInt($row.find('.quantity-input').val()) || 0;
        let purpose = $row.find('.purpose-input').val().trim();

        if (quantity > 0 && purpose) {
            items.push({
                supply_id: $row.data('supply-id'),
                quantity: quantity,
                purpose: purpose
            });
        }
    });

    if (items.length === 0) {
        showAlert('warning', 'Vui lòng chọn ít nhất một văn phòng phẩm!');
        return;
    }

    let formData = {
        items: items,
        priority: $('[name="priority"]').val(),
        notes: $('[name="notes"]').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: '{{ route("supply-requests.store") }}',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showAlert('success', 'Đã gửi yêu cầu thành công!');
                clearForm();
                loadOfficeSuppliesForRequest(); // Reload to update stock
                
                // Scroll to top to see the success message
                $('html, body').animate({scrollTop: 0}, 500);
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
        }
    });
}

function clearForm() {
    $('#request-form')[0].reset();
    $('.quantity-input').val('');
    $('.purpose-input').val('');
    updateRequestSummary();
}
</script>