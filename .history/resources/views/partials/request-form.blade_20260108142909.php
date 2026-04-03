<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Đăng ký văn phóng phẩm
        </h5>
    </div>
    <div class="card-body">
        <form id="request-form">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Mức độ ưu tiên:</label>
                    <select name="priority" class="form-select" required>
                        <option value="normal">Bình thường</option>
                        <option value="urgent">Khẩn cấp</option>
                        <option value="emergency">Rất khẩn cấp</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ghi chú:</label>
                    <input type="text" name="notes" class="form-control" placeholder="Ghi chú thêm (nếu có)">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Danh sách văn phòng phẩm:</label>
                <div class="table-responsive">
                    <table class="table table-striped" id="supplies-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Tên văn phòng phẩm</th>
                                <th>Mô tả</th>
                                <th>Đơn vị</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Số lượng</th>
                                <th>Mục đích sử dụng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-3">
                <h6>Tổng quan yêu cầu:</h6>
                <div id="request-summary" class="alert alert-info">
                    Chọn văn phòng phẩm để xem tổng quan
                </div>
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-secondary me-2" onclick="clearForm()">
                    <i class="fas fa-times me-1"></i>Hủy
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i>Gửi yêu cầu
                </button>
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
        alert('Vui lòng chọn ít nhất một văn phòng phẩm!');
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
                alert('Đã gửi yêu cầu thành công!');
                clearForm();
                loadOfficeSuppliesForRequest(); // Reload to update stock
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

function clearForm() {
    $('#request-form')[0].reset();
    $('.quantity-input').val('');
    $('.purpose-input').val('');
    updateRequestSummary();
}
</script>