<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Yêu cầu của tôi
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="my-requests-table">
                <thead class="table-dark">
                    <tr>
                        <th>Mã yêu cầu</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Mức độ</th>
                        <th>Số lượng mặt hàng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center">
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

<!-- Modal xem chi tiết yêu cầu -->
<div class="modal fade" id="requestDetailModal" tabindex="-1" aria-labelledby="requestDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestDetailModalLabel">Chi tiết yêu cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="requestDetailContent">
                <!-- Content will be loaded by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
function updateMyRequestsTable(data) {
    let html = '';
    
    if (data.length === 0) {
        html = '<tr><td colspan="6" class="text-center text-muted">Chưa có yêu cầu nào</td></tr>';
    } else {
        data.forEach(request => {
            let statusBadge = getStatusBadge(request.status);
            let totalItems = request.request_items.length;
            let priorityBadge = getPriorityBadge(request.priority);
            
            html += `
                <tr>
                    <td><strong>${request.request_code}</strong></td>
                    <td>${formatDate(request.created_at)}</td>
                    <td>${statusBadge}</td>
                    <td>${priorityBadge}</td>
                    <td>${totalItems} mặt hàng</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" onclick="viewRequestDetails(${request.id})">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#my-requests-table tbody').html(html);
}

function getStatusBadge(status) {
    switch(status) {
        case 'pending':
            return '<span class="badge bg-warning">Chờ duyệt</span>';
        case 'approved':
            return '<span class="badge bg-success">Đã duyệt</span>';
        case 'rejected':
            return '<span class="badge bg-danger">Từ chối</span>';
        default:
            return '<span class="badge bg-secondary">Không xác định</span>';
    }
}

function getPriorityBadge(priority) {
    switch(priority) {
        case 'normal':
            return '<span class="badge bg-info">Bình thường</span>';
        case 'urgent':
            return '<span class="badge bg-warning">Khẩn cấp</span>';
        case 'emergency':
            return '<span class="badge bg-danger">Rất khẩn cấp</span>';
        default:
            return '<span class="badge bg-secondary">Không xác định</span>';
    }
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function viewRequestDetails(requestId) {
    $.get(`{{ url('supply-requests') }}/${requestId}`, function(request) {
        let content = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Mã yêu cầu:</strong> ${request.request_code}<br>
                    <strong>Ngày tạo:</strong> ${formatDate(request.created_at)}<br>
                    <strong>Mức độ:</strong> ${getPriorityBadge(request.priority)}
                </div>
                <div class="col-md-6">
                    <strong>Trạng thái:</strong> ${getStatusBadge(request.status)}<br>
                    <strong>Người yêu cầu:</strong> ${request.requester_name}<br>
                    <strong>Bộ phận:</strong> ${request.requester_department}
                </div>
            </div>
        `;

        if (request.notes) {
            content += `<div class="mb-3"><strong>Ghi chú:</strong> ${request.notes}</div>`;
        }

        content += `
            <div class="mb-3">
                <strong>Danh sách văn phòng phẩm:</strong>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tên văn phòng phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn vị</th>
                                <th>Mục đích</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        let total = 0;
        request.request_items.forEach(item => {
            let itemTotal = item.quantity * item.office_supply.price;
            total += itemTotal;
            
            content += `
                <tr>
                    <td>${item.office_supply.name}</td>
                    <td>${item.quantity}</td>
                    <td>${item.office_supply.unit}</td>
                    <td>${item.purpose}</td>
                    <td>${formatCurrency(item.office_supply.price)}</td>
                    <td>${formatCurrency(itemTotal)}</td>
                </tr>
            `;
        });

        content += `
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5">Tổng cộng:</th>
                                <th>${formatCurrency(total)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        `;

        if (request.status === 'approved' && request.approver) {
            content += `
                <div class="alert alert-success">
                    <strong>Đã được phê duyệt bởi:</strong> ${request.approver.full_name || request.approver.name}<br>
                    <strong>Ngày phê duyệt:</strong> ${formatDate(request.approved_at)}
                </div>
            `;
        } else if (request.status === 'rejected') {
            content += `
                <div class="alert alert-danger">
                    <strong>Đã bị từ chối bởi:</strong> ${request.approver.full_name || request.approver.name}<br>
                    <strong>Ngày từ chối:</strong> ${formatDate(request.approved_at)}<br>
                    <strong>Lý do:</strong> ${request.rejection_reason}
                </div>
            `;
        }

        $('#requestDetailContent').html(content);
        $('#requestDetailModal').modal('show');
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}
</script>