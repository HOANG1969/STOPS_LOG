@extends('layouts.app')

@section('title', 'Thêm văn phòng phẩm')
@section('page-title', 'Thêm văn phòng phẩm mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Thêm văn phòng phẩm mới
                </h5>
                <a href="{{ route('office-supplies.admin.manage') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>
            <div class="card-body">
                <form id="createSupplyForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Tên văn phòng phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required 
                                   placeholder="Nhập tên văn phòng phẩm">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Chọn danh mục</option>
                                <option value="Văn phòng phẩm">Văn phòng phẩm</option>
                                <option value="Thiết bị điện tử">Thiết bị điện tử</option>
                                <option value="Nội thất">Nội thất văn phòng</option>
                                <option value="Vật tư tiêu hao">Vật tư tiêu hao</option>
                                <option value="Sách và tài liệu">Sách và tài liệu</option>
                                <option value="Khác">Khác</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Nhập mô tả chi tiết về văn phòng phẩm"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="unit" class="form-label">Đơn vị tính <span class="text-danger">*</span></label>
                            <select class="form-select" id="unit" name="unit" required>
                                <option value="">Chọn đơn vị</option>
                                <option value="Cái">Cái</option>
                                <option value="Hộp">Hộp</option>
                                <option value="Cây">Cây</option>
                                <option value="Bộ">Bộ</option>
                                <option value="Ream">Ream</option>
                                <option value="Cuốn">Cuốn</option>
                                <option value="Kg">Kg</option>
                                <option value="Lít">Lít</option>
                                <option value="Gói">Gói</option>
                                <option value="Chiếc">Chiếc</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="price" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" required 
                                   min="0" step="0.01" placeholder="0">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_quantity" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                   required min="0" placeholder="0">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Đang hoạt động
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class="fas fa-times me-2"></i>
                                Hủy
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                Thêm văn phòng phẩm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Section -->
<div class="row justify-content-center mt-4" id="previewSection" style="display: none;">
    <div class="col-lg-8">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Xem trước thông tin
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Tên:</strong> <span id="preview_name">-</span><br>
                        <strong>Danh mục:</strong> <span id="preview_category">-</span><br>
                        <strong>Đơn vị:</strong> <span id="preview_unit">-</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Giá:</strong> <span id="preview_price">-</span> VNĐ<br>
                        <strong>Tồn kho:</strong> <span id="preview_stock">-</span><br>
                        <strong>Trạng thái:</strong> <span id="preview_status">-</span>
                    </div>
                    <div class="col-12 mt-2">
                        <strong>Mô tả:</strong> <span id="preview_description">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Form validation and submission
document.getElementById('createSupplyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
    
    try {
        const formData = new FormData(this);
        
        // Convert form data to JSON
        const data = {};
        for (let [key, value] of formData.entries()) {
            if (key === 'is_active') {
                data[key] = document.getElementById('is_active').checked;
            } else {
                data[key] = value;
            }
        }
        
        const response = await fetch('{{ route('office-supplies.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, 'success');
            // Redirect to admin page after 2 seconds
            setTimeout(() => {
                window.location.href = '{{ route('office-supplies.admin.manage') }}';
            }, 2000);
        } else {
            // Handle validation errors
            if (result.errors) {
                displayValidationErrors(result.errors);
            } else {
                showAlert('Có lỗi xảy ra khi thêm văn phòng phẩm', 'danger');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi gửi dữ liệu', 'danger');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Display validation errors
function displayValidationErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    
    // Display new errors
    for (let field in errors) {
        const input = document.getElementById(field);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = input.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = errors[field][0];
            }
        }
    }
}

// Real-time preview
function updatePreview() {
    document.getElementById('preview_name').textContent = document.getElementById('name').value || '-';
    document.getElementById('preview_category').textContent = document.getElementById('category').value || '-';
    document.getElementById('preview_unit').textContent = document.getElementById('unit').value || '-';
    
    const price = document.getElementById('price').value;
    document.getElementById('preview_price').textContent = price ? 
        new Intl.NumberFormat('vi-VN').format(price) : '-';
    
    document.getElementById('preview_stock').textContent = document.getElementById('stock_quantity').value || '-';
    document.getElementById('preview_status').textContent = document.getElementById('is_active').checked ? 
        'Đang hoạt động' : 'Ngưng hoạt động';
    document.getElementById('preview_description').textContent = document.getElementById('description').value || '-';
}

// Show preview when user starts typing
document.querySelectorAll('#createSupplyForm input, #createSupplyForm select, #createSupplyForm textarea').forEach(input => {
    input.addEventListener('input', function() {
        updatePreview();
        document.getElementById('previewSection').style.display = 'block';
    });
    
    input.addEventListener('change', updatePreview);
});

// Format price input
document.getElementById('price').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d.]/g, '');
    if (value) {
        e.target.value = value;
    }
});

// Show alert function
function showAlert(message, type) {
    const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHTML);
    
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush