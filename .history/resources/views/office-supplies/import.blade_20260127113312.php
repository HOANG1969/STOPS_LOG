@extends('layouts.app')

@section('title', 'Import văn phòng phẩm')
@section('page-title', 'Import văn phòng phẩm')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-upload me-2"></i>
                    Import văn phòng phẩm từ file Excel/CSV
                </h5>
                <a href="{{ route('office-supplies.admin') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>
            <div class="card-body">
                <!-- Instructions -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Hướng dẫn sử dụng:</h6>
                    <ul class="mb-0">
                        <li>File cần có định dạng CSV hoặc Excel (.xlsx)</li>
                        <li>Dòng đầu tiên là tiêu đề (sẽ được bỏ qua)</li>
                        <li>Các cột theo thứ tự: <strong>Tên, Mô tả, Đơn vị, Giá, Số lượng tồn, Danh mục</strong></li>
                        <li>Giá phải là số (có thể có số thập phân)</li>
                        <li>Số lượng tồn phải là số nguyên</li>
                    </ul>
                </div>

                <!-- Sample Template -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-file-download me-2"></i>
                            File mẫu
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">Tải xuống file mẫu để sử dụng làm template:</p>
                        <button class="btn btn-outline-success" onclick="downloadTemplate()">
                            <i class="fas fa-download me-2"></i>
                            Tải xuống template CSV
                        </button>
                    </div>
                </div>

                <!-- Upload Form -->
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="fileInput" class="form-label">Chọn file để import *</label>
                        <input type="file" class="form-control" id="fileInput" name="file" 
                               accept=".csv,.txt,.xlsx" required>
                        <div class="form-text">Chỉ chấp nhận file .csv, .txt, .xlsx</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="history.back()">
                            <i class="fas fa-times me-2"></i>
                            Hủy
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-upload me-2"></i>
                            Import văn phòng phẩm
                        </button>
                    </div>
                </form>

                <!-- Progress -->
                <div id="progressSection" class="mt-4" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%" id="progressBar"></div>
                    </div>
                    <div class="text-center mt-2">
                        <small id="progressText">Đang xử lý...</small>
                    </div>
                </div>

                <!-- Results -->
                <div id="resultSection" class="mt-4" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Kết quả import
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <h4 id="successCount" class="mt-2">0</h4>
                                        <p class="text-muted">Thành công</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-circle fa-2x"></i>
                                        <h4 id="errorCount" class="mt-2">0</h4>
                                        <p class="text-muted">Lỗi</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-info">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                        <h4 id="totalCount" class="mt-2">0</h4>
                                        <p class="text-muted">Tổng dòng</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Details -->
                            <div id="errorDetails" class="mt-3" style="display: none;">
                                <h6 class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Chi tiết lỗi:
                                </h6>
                                <div class="alert alert-danger">
                                    <ul id="errorList" class="mb-0"></ul>
                                </div>
                            </div>

                            <!-- Success Actions -->
                            <div id="successActions" class="mt-3 text-center" style="display: none;">
                                <a href="{{ route('office-supplies.admin') }}" class="btn btn-success">
                                    <i class="fas fa-eye me-2"></i>
                                    Xem danh sách văn phòng phẩm
                                </a>
                                <button class="btn btn-outline-primary ms-2" onclick="resetForm()">
                                    <i class="fas fa-redo me-2"></i>
                                    Import file khác
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Download template
function downloadTemplate() {
    const csvContent = "Tên,Mô tả,Đơn vị,Giá,Số lượng tồn,Danh mục\n" +
                      "Bút bi xanh,Bút bi viết văn bản,Cây,5000,100,Văn phòng phẩm\n" +
                      "Giấy A4,Giấy in A4 70gsm,Ream,120000,50,Văn phòng phẩm\n" +
                      "Bóng đèn LED,Bóng đèn tiết kiệm điện,Bóng,150000,20,Thiết bị điện\n" +
                      "Máy tính bảng,Máy tính cầm tay 12 số,Cái,350000,15,Điện tử\n" +
                      "Ghế xoay,Ghế văn phòng có tay vịn,Cái,2500000,5,Nội thất\n" +
                      "Cà phê sữa,Cà phê sữa cho CBCNV,Hộp,8000,50,Đồ uống";
    
    // Add BOM for UTF-8 to ensure proper encoding
    const BOM = '\uFEFF';
    const csvWithBOM = BOM + csvContent;
    
    const blob = new Blob([csvWithBOM], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'template_van_phong_pham.csv');
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Reset form
function resetForm() {
    document.getElementById('importForm').reset();
    document.getElementById('progressSection').style.display = 'none';
    document.getElementById('resultSection').style.display = 'none';
    document.getElementById('submitBtn').disabled = false;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-upload me-2"></i>Import văn phòng phẩm';
}

// Show progress
function showProgress() {
    document.getElementById('progressSection').style.display = 'block';
    document.getElementById('resultSection').style.display = 'none';
    
    let width = 0;
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    const interval = setInterval(() => {
        width += Math.random() * 10;
        if (width >= 90) {
            clearInterval(interval);
            return;
        }
        
        progressBar.style.width = width + '%';
        progressText.textContent = `Đang xử lý... ${Math.round(width)}%`;
    }, 100);
}

// Show results
function showResults(data) {
    document.getElementById('progressSection').style.display = 'none';
    document.getElementById('resultSection').style.display = 'block';
    
    document.getElementById('successCount').textContent = data.total_success || 0;
    document.getElementById('errorCount').textContent = data.total_errors || 0;
    document.getElementById('totalCount').textContent = (data.total_success || 0) + (data.total_errors || 0);
    
    // Show errors if any
    if (data.errors && data.errors.length > 0) {
        document.getElementById('errorDetails').style.display = 'block';
        const errorList = document.getElementById('errorList');
        errorList.innerHTML = data.errors.map(error => `<li>${error}</li>`).join('');
    }
    
    // Show success actions if any successful imports
    if (data.total_success > 0) {
        document.getElementById('successActions').style.display = 'block';
    }
    
    // Reset progress bar
    document.getElementById('progressBar').style.width = '100%';
    document.getElementById('progressText').textContent = 'Hoàn thành!';
}

// Show alert
function showAlert(message, type) {
    const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHTML);
    
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}

// Form submission
document.getElementById('importForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('fileInput');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!fileInput.files[0]) {
        showAlert('Vui lòng chọn file để import', 'warning');
        return;
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
    
    // Show progress
    showProgress();
    
    try {
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        console.log('Sending file:', fileInput.files[0]);
        console.log('FormData:', formData);
        
        const response = await fetch('{{ route('office-supplies.import') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Result:', result);
        
        if (result.success) {
            showResults(result);
            showAlert(result.message, 'success');
        } else {
            showAlert('Có lỗi xảy ra khi import: ' + (result.message || 'Unknown error'), 'danger');
            document.getElementById('progressSection').style.display = 'none';
            
            // Show error details if available
            if (result.errors && result.errors.length > 0) {
                showResults(result);
            }
        }
        
    } catch (error) {
        console.error('Import error:', error);
        showAlert('Có lỗi xảy ra khi import file: ' + error.message, 'danger');
        document.getElementById('progressSection').style.display = 'none';
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import văn phòng phẩm';
    }
});

// File input validation
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    const allowedExtensions = ['.csv', '.txt', '.xlsx'];
    
    const isValidType = allowedTypes.includes(file.type) || 
                       allowedExtensions.some(ext => file.name.toLowerCase().endsWith(ext));
    
    if (!isValidType) {
        showAlert('File không đúng định dạng. Chỉ chấp nhận file CSV hoặc Excel (.xlsx)', 'warning');
        e.target.value = '';
        return;
    }
    
    // Check file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        showAlert('File quá lớn. Vui lòng chọn file nhỏ hơn 5MB', 'warning');
        e.target.value = '';
        return;
    }
});
</script>
@endpush