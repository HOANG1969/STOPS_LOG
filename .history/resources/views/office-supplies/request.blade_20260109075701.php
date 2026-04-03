@extends('layouts.app')

@section('title', 'Tạo yêu cầu văn phòng phẩm')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Tạo yêu cầu văn phòng phẩm</h1>
                    <p class="text-muted mb-0">Chọn và đăng ký các văn phòng phẩm cần thiết</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
                </a>
            </div>

            <!-- Alert container for messages -->
            <div id="alert-container"></div>

            @include('partials.request-form')
        </div>
    </div>
</div>

<script>
// Load office supplies when page loads
$(document).ready(function() {
    loadOfficeSuppliesForRequest();
});

function loadOfficeSuppliesForRequest() {
    $.ajax({
        url: '{{ route("office-supplies.api.for-request") }}',
        method: 'GET',
        success: function(supplies) {
            let tableBody = $('#supplies-table tbody');
            tableBody.empty();

            if (supplies.length === 0) {
                tableBody.append(`
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <div>Không có văn phòng phẩm nào khả dụng</div>
                        </td>
                    </tr>
                `);
                return;
            }

            supplies.forEach(function(supply) {
                let stockClass = supply.stock_quantity <= 10 ? 'text-danger' : 'text-success';
                let stockIcon = supply.stock_quantity <= 10 ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
                
                let row = `
                    <tr data-supply-id="${supply.id}">
                        <td>
                            <div class="fw-bold">${supply.name}</div>
                            <small class="text-muted">${supply.category}</small>
                        </td>
                        <td>
                            <small>${supply.description || 'Không có mô tả'}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">${supply.unit}</span>
                        </td>
                        <td>
                            ${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(supply.price)}
                        </td>
                        <td>
                            <span class="${stockClass}">
                                <i class="${stockIcon} me-1"></i>${supply.stock_quantity}
                            </span>
                        </td>
                        <td>
                            <input type="number" 
                                   class="form-control form-control-sm quantity-input" 
                                   min="0" 
                                   max="${supply.stock_quantity}" 
                                   placeholder="0"
                                   ${supply.stock_quantity <= 0 ? 'disabled' : ''}>
                        </td>
                        <td>
                            <input type="text" 
                                   class="form-control form-control-sm purpose-input" 
                                   placeholder="Mục đích sử dụng..."
                                   ${supply.stock_quantity <= 0 ? 'disabled' : ''}>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            // Update summary after loading
            updateRequestSummary();
        },
        error: function(xhr) {
            console.error('Error loading office supplies:', xhr);
            showAlert('danger', 'Không thể tải danh sách văn phòng phẩm. Vui lòng thử lại sau.');
            
            $('#supplies-table tbody').html(`
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <div>Có lỗi xảy ra khi tải dữ liệu</div>
                    </td>
                </tr>
            `);
        }
    });
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