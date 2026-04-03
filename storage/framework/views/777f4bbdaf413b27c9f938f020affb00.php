<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-check-circle me-2"></i>Phê duyệt yêu cầu
            <?php if(auth()->user()->isApprover()): ?>
                <span class="badge bg-info ms-2"><?php echo e(auth()->user()->department); ?></span>
            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="approval-requests-table">
                <thead class="table-dark">
                    <tr>
                        <th>Mã yêu cầu</th>
                        <th>Người yêu cầu</th>
                        <th>Bộ phận</th>
                        <th>Ngày tạo</th>
                        <th>Mức độ</th>
                        <th>Số mặt hàng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">
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

<!-- Modal phê duyệt -->
<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Phê duyệt yêu cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="approvalContent">
                <!-- Content will be loaded by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" onclick="rejectRequest()">
                    <i class="fas fa-times me-1"></i>Từ chối
                </button>
                <button type="button" class="btn btn-success" onclick="approveRequest()">
                    <i class="fas fa-check me-1"></i>Phê duyệt
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal từ chối -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Từ chối yêu cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Lý do từ chối:</label>
                        <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="4" required placeholder="Nhập lý do từ chối yêu cầu..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" onclick="confirmReject()">
                    <i class="fas fa-times me-1"></i>Xác nhận từ chối
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentRequestId = null;

function updateApprovalRequestsTable(data) {
    let html = '';
    
    if (data.length === 0) {
        html = '<tr><td colspan="7" class="text-center text-muted">Không có yêu cầu nào cần phê duyệt</td></tr>';
    } else {
        data.forEach(request => {
            let priorityBadge = getPriorityBadge(request.priority);
            let totalItems = request.request_items.length;
            
            html += `
                <tr>
                    <td><strong>${request.request_code}</strong></td>
                    <td>${request.requester_name}</td>
                    <td>${request.requester_department}</td>
                    <td>${formatDate(request.created_at)}</td>
                    <td>${priorityBadge}</td>
                    <td>${totalItems} mặt hàng</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="reviewRequest(${request.id})">
                            <i class="fas fa-search"></i> Xem xét
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#approval-requests-table tbody').html(html);
}

function reviewRequest(requestId) {
    currentRequestId = requestId;
    
    $.get(`<?php echo e(url('supply-requests')); ?>/${requestId}`, function(request) {
        let content = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Mã yêu cầu:</strong> ${request.request_code}<br>
                    <strong>Người yêu cầu:</strong> ${request.requester_name}<br>
                    <strong>Email:</strong> ${request.requester_email}<br>
                    <strong>Bộ phận:</strong> ${request.requester_department}<br>
                    <strong>Chức vụ:</strong> ${request.requester_position}
                </div>
                <div class="col-md-6">
                    <strong>Ngày tạo:</strong> ${formatDate(request.created_at)}<br>
                    <strong>Mức độ:</strong> ${getPriorityBadge(request.priority)}<br>
                    <strong>Trạng thái:</strong> ${getStatusBadge(request.status)}
                </div>
            </div>
        `;

        if (request.notes) {
            content += `<div class="mb-3"><strong>Ghi chú:</strong> <em>${request.notes}</em></div>`;
        }

        content += `
            <div class="mb-3">
                <strong>Chi tiết văn phòng phẩm yêu cầu:</strong>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Văn phòng phẩm</th>
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
                                <th colspan="5">Tổng giá trị ước tính:</th>
                                <th>${formatCurrency(total)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        `;

        content += `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Lưu ý:</strong> Sau khi phê duyệt, số lượng tồn kho sẽ được trừ tương ứng. 
                Nếu từ chối, số lượng sẽ được hoàn lại kho.
            </div>
        `;

        $('#approvalContent').html(content);
        $('#approvalModal').modal('show');
    });
}

function approveRequest() {
    if (!currentRequestId) return;
    
    if (!confirm('Bạn có chắc chắn muốn phê duyệt yêu cầu này?')) {
        return;
    }

    $.ajax({
        url: `<?php echo e(url('supply-requests')); ?>/${currentRequestId}/approve`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('Đã phê duyệt yêu cầu thành công!');
                $('#approvalModal').modal('hide');
                loadApprovalRequests(); // Reload table
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

function rejectRequest() {
    $('#approvalModal').modal('hide');
    $('#rejectModal').modal('show');
}

function confirmReject() {
    if (!currentRequestId) return;
    
    let reason = $('#rejectionReason').val().trim();
    if (!reason) {
        alert('Vui lòng nhập lý do từ chối!');
        return;
    }

    $.ajax({
        url: `<?php echo e(url('supply-requests')); ?>/${currentRequestId}/reject`,
        method: 'POST',
        data: {
            rejection_reason: reason,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('Đã từ chối yêu cầu!');
                $('#rejectModal').modal('hide');
                $('#rejectionReason').val('');
                loadApprovalRequests(); // Reload table
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
</script><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\partials\approval-requests.blade.php ENDPATH**/ ?>