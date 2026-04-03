@extends('layouts.app')

@section('title', 'Dashboard - Phê duyệt')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Phê duyệt đơn yêu cầu</h1>
            <p class="text-muted mb-0">{{ $user->full_name }} - {{ $user->position }} | Bộ phận: {{ $user->department }}</p>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" onclick="refreshRequests()">
                <i class="fas fa-sync-alt me-1"></i>Làm mới
            </button>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-warning" style="cursor: pointer;" onclick="filterByStatus('pending')">
                <div class="card-body text-center">
                    <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                    <h4 class="text-warning">{{ $stats['pending_count'] }}</h4>
                    <p class="text-muted mb-0">Chờ phê duyệt</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success" style="cursor: pointer;" onclick="filterByStatus('approved_today')">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    <h4 class="text-success">{{ $stats['approved_today'] }}</h4>
                    <p class="text-muted mb-0">Đã duyệt hôm nay</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info" style="cursor: pointer;" onclick="filterByStatus('approved_month')">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line text-info fa-2x mb-2"></i>
                    <h4 class="text-info">{{ $stats['approved_this_month'] }}</h4>
                    <p class="text-muted mb-0">Đã duyệt tháng này</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-primary" style="cursor: pointer;" onclick="filterByStatus('all')">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-list text-primary fa-2x mb-2"></i>
                    <h4 class="text-primary">{{ $stats['total_requests'] }}</h4>
                    <p class="text-muted mb-0">Tổng đơn</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert container -->
    <div id="alert-container"></div>

    <!-- Danh sách yêu cầu chờ phê duyệt -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Danh sách yêu cầu chờ phê duyệt
            </h5>
            <small class="text-muted">Click vào thống kê để lọc theo trạng thái</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="approval-requests-table">
                    <thead class="table-grey-50">
                        <tr>
                            <th width="12%">Mã phiếu</th>
                            <th width="15%">Người yêu cầu</th>
                            <th width="12%">Bộ phận</th>
                            <th width="10%">Mức độ</th>
                            <th width="18%">Văn phòng phẩm</th>
                            <th width="10%">Ngày tạo</th>
                            <th width="13%">Trạng thái</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal phê duyệt -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết yêu cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="request-details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger me-2" onclick="showRejectForm()">
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
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Từ chối yêu cầu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reject-form">
                    <div class="mb-3">
                        <label class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" rows="4" 
                                  placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" onclick="submitRejectRequest()">
                    <i class="fas fa-times me-1"></i>Từ chối
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentRequestId = null;
let allRequestsData = []; // Global variable to store all requests data

$(document).ready(function() {
    loadApprovalRequests();
});

function loadApprovalRequests() {
    console.log('Loading approval requests...');
    
    // Load all requests for complete view
    $.ajax({
        url: '{{ route("supply-requests.all") }}',
        method: 'GET',
        success: function(allRequests) {
            console.log('Loaded all requests:', allRequests);
            allRequestsData = allRequests; // Store globally
            
            // Show all requests by default
            renderTable(allRequests);
        },
        error: function(xhr) {
            console.error('Error loading approval requests:', xhr);
            showAlert('danger', 'Không thể tải danh sách yêu cầu phê duyệt.');
        }
    });
}

// New function to load all requests for filtering
function loadAllRequests(filterType = null) {
    console.log('Loading all requests for filtering...');
    
    $.ajax({
        url: '{{ route("supply-requests.all") }}',
        method: 'GET',
        success: function(allRequests) {
            console.log('Loaded all requests:', allRequests);
            allRequestsData = allRequests;
            
            if (filterType) {
                filterByStatus(filterType);
            } else {
                renderTable(allRequests);
            }
        },
        error: function(xhr) {
            console.error('Error loading all requests:', xhr);
            showAlert('danger', 'Không thể tải danh sách tất cả yêu cầu.');
        }
    });
}

function renderTable(requests) {
    const tbody = $('#approval-requests-table tbody');
    tbody.empty();

    if (requests.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <div>Không có yêu cầu nào</div>
                </td>
            </tr>
        `);
        return;
    }

    requests.forEach(function(request) {
        const priorityBadge = getPriorityBadge(request.priority);
        const statusBadge = getStatusBadge(request.status);
        const itemsPreview = getItemsPreview(request.requestItems || []);
        const isCompleted = request.status === 'approved' || request.status === 'rejected';
        
        const actionButtons = isCompleted ? 
            `<button class="btn btn-outline-primary btn-sm" 
                    onclick="viewRequestDetails(${request.id})" 
                    title="Xem chi tiết">
                <i class="fas fa-eye"></i>
            </button>` :
            `<div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" 
                        onclick="viewRequestDetails(${request.id})" 
                        title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-success" 
                        onclick="quickApprove(${request.id})" 
                        title="Phê duyệt nhanh">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn btn-outline-danger" 
                        onclick="quickReject(${request.id})" 
                        title="Từ chối">
                    <i class="fas fa-times"></i>
                </button>
            </div>`;
        
        const row = `
            <tr>
                <td>
                    <div class="fw-bold text-primary">${request.request_code}</div>
                    <small class="text-muted">${formatDateTime(request.created_at)}</small>
                </td>
                <td>
                    <div class="fw-bold">${request.requester_name}</div>
                    <small class="text-muted">${request.requester_position || ''}</small>
                </td>
                <td>
                    <span class="badge bg-info">${request.requester_department}</span>
                </td>
                <td>
                    ${priorityBadge}
                </td>
                <td>
                    <div class="small">${itemsPreview}</div>
                </td>
                <td>
                    ${formatDate(request.request_date || request.created_at)}
                </td>
                <td>
                    ${statusBadge}
                </td>
                <td>
                    ${actionButtons}
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function getPriorityBadge(priority) {
    switch(priority) {
        case 'emergency':
            return '<span class="badge bg-danger">Rất khẩn</span>';
        case 'urgent':
            return '<span class="badge bg-warning">Khẩn cấp</span>';
        default:
            return '<span class="badge bg-success">Bình thường</span>';
    }
}

function getStatusBadge(status) {
    switch(status) {
        case 'pending':
            return '<span class="badge bg-warning">Chờ phê duyệt</span>';
        case 'forwarded':
            return '<span class="badge bg-info">Đã chuyển tiếp</span>';
        case 'approved':
            return '<span class="badge bg-success">Đã phê duyệt</span>';
        case 'rejected':
            return '<span class="badge bg-danger">Đã từ chối</span>';
        case 'completed':
            return '<span class="badge bg-primary">Đã hoàn thành</span>';
        default:
            return '<span class="badge bg-secondary">Không xác định</span>';
    }
}

function calculateTotalValue(items) {
    return items.reduce((total, item) => {
        return total + (parseFloat(item.office_supply?.price || 0) * parseInt(item.quantity || 0));
    }, 0);
}

function getItemsPreview(items) {
    const preview = items.slice(0, 2).map(item => 
        `${item.office_supply?.name || 'Unknown'} (${item.quantity})`
    ).join(', ');
    
    return items.length > 2 ? preview + ` và ${items.length - 2} mục khác...` : preview;
}

function viewRequestDetails(requestId) {
    // Redirect to detail page instead of showing modal
    window.location.href = `/supply-requests/${requestId}`;
}

function renderRequestDetails(request) {
    const priorityBadge = getPriorityBadge(request.priority);
    const totalValue = calculateTotalValue(request.requestItems);
    
    let itemsTable = '';
    request.requestItems.forEach((item, index) => {
        const itemTotal = parseFloat(item.office_supply.price) * parseInt(item.quantity);
        itemsTable += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.office_supply.name}</td>
                <td class="text-center">${item.office_supply.unit}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-end">${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(item.office_supply.price)}</td>
                <td class="text-end fw-bold">${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(itemTotal)}</td>
                <td>${item.purpose}</td>
            </tr>
        `;
    });
    
    const detailsHtml = `
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Mã phiếu:</strong> ${request.request_code}<br>
                <strong>Người yêu cầu:</strong> ${request.requester_name}<br>
                <strong>Bộ phận:</strong> ${request.requester_department}<br>
                <strong>Chức vụ:</strong> ${request.requester_position || 'Không có thông tin'}
            </div>
            <div class="col-md-6">
                <strong>Mức độ ưu tiên:</strong> ${priorityBadge}<br>
                <strong>Ngày tạo:</strong> ${formatDateTime(request.created_at)}<br>
                <strong>Email:</strong> ${request.requester_email}<br>
                <strong>Ghi chú:</strong> ${request.notes || 'Không có'}
            </div>
        </div>
        
        <h6><strong>Danh sách văn phòng phẩm:</strong></h6>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tên VPP</th>
                        <th>ĐVT</th>
                        <th>SL</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                        <th>Mục đích</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsTable}
                </tbody>
                <tfoot>
                    <tr class="table-warning">
                        <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td class="text-end fw-bold">${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(totalValue)}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    `;
    
    $('#request-details').html(detailsHtml);
}

function quickApprove(requestId) {
    if (!confirm('Bạn có chắc chắn muốn phê duyệt yêu cầu này?')) {
        return;
    }
    
    approveRequestById(requestId);
}

function quickReject(requestId) {
    currentRequestId = requestId;
    $('#rejectModal').modal('show');
}

function approveRequest() {
    if (!currentRequestId) return;
    
    approveRequestById(currentRequestId);
}

function approveRequestById(requestId) {
    $.ajax({
        url: `/supply-requests/${requestId}/approve`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', 'Đã phê duyệt yêu cầu thành công!');
                $('#approvalModal').modal('hide');
                loadApprovalRequests(); // Reload table
                location.reload(); // Reload to update stats
            } else {
                showAlert('danger', 'Có lỗi: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Có lỗi xảy ra khi phê duyệt!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showAlert('danger', message);
        }
    });
}

function showRejectForm() {
    $('#approvalModal').modal('hide');
    $('#rejectModal').modal('show');
}

function submitRejectRequest() {
    const reason = $('[name="rejection_reason"]').val().trim();
    if (!reason) {
        showAlert('warning', 'Vui lòng nhập lý do từ chối!');
        return;
    }
    
    $.ajax({
        url: `/supply-requests/${currentRequestId}/reject`,
        method: 'POST',
        data: {
            rejection_reason: reason,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', 'Đã từ chối yêu cầu thành công!');
                $('#rejectModal').modal('hide');
                loadApprovalRequests(); // Reload table
                location.reload(); // Reload to update stats
            } else {
                showAlert('danger', 'Có lỗi: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Có lỗi xảy ra khi từ chối!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showAlert('danger', message);
        }
    });
}

function refreshRequests() {
    loadApprovalRequests();
    showAlert('info', 'Đã làm mới danh sách!');
}

function filterByStatus(status) {
    console.log('Filtering by status:', status);
    console.log('Available data:', allRequestsData);
    
    // For 'all' status, we need to load all data first  
    if (status === 'all') {
        loadAllRequests();
        return;
    }
    
    if (!allRequestsData || allRequestsData.length === 0) {
        showAlert('warning', 'Không có dữ liệu để lọc. Vui lòng làm mới trang.');
        return;
    }
    
    let filteredData = [];
    
    switch(status) {
        case 'pending':
            filteredData = allRequestsData.filter(req => req.status === 'pending' || req.status === 'forwarded');
            break;
        case 'approved_today':
            filteredData = allRequestsData.filter(req => {
                if (req.status !== 'approved') return false;
                const today = new Date().toDateString();
                const approvedDate = new Date(req.approved_at).toDateString();
                return today === approvedDate;
            });
            break;
        case 'approved_month':
            filteredData = allRequestsData.filter(req => {
                if (req.status !== 'approved') return false;
                const now = new Date();
                const approvedDate = new Date(req.approved_at);
                return approvedDate.getMonth() === now.getMonth() && 
                       approvedDate.getFullYear() === now.getFullYear();
            });
            break;
        default:
            filteredData = allRequestsData;
    }
    
    console.log('Filtered data:', filteredData);
    renderTable(filteredData);
    
    // Update UI feedback
    const statusNames = {
        'pending': 'Chờ phê duyệt',
        'approved_today': 'Đã duyệt hôm nay', 
        'approved_month': 'Đã duyệt tháng này',
        'all': 'Tất cả đơn'
    };
    showAlert('info', `Hiển thị: ${statusNames[status]} (${filteredData.length} đơn)`);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('vi-VN');
}

function formatDateTime(dateString) {
    return new Date(dateString).toLocaleString('vi-VN');
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