

<?php $__env->startSection('title', 'Import Văn phòng phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-import"></i> Import Văn phòng phẩm</h2>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Import Instructions -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5><i class="fas fa-info-circle"></i> Hướng dẫn Import</h5>
                        </div>
                        <div class="card-body">
                            <ol class="mb-0">
                                <li>Tải file mẫu CSV hoặc Excel</li>
                                <li>Điền thông tin văn phòng phẩm theo đúng định dạng</li>
                                <li>Lưu file và upload để import</li>
                                <li>Hệ thống sẽ tự động xử lý và thông báo kết quả</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5><i class="fas fa-exclamation-triangle"></i> Lưu ý quan trọng</h5>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li>File import phải là định dạng CSV, XLS, hoặc XLSX</li>
                                <li>Dung lượng file tối đa 2MB</li>
                                <li>Các cột bắt buộc: Tên sản phẩm, Đơn vị</li>
                                <li>Sản phẩm trùng tên sẽ được cập nhật thông tin</li>
                                <li>Dữ liệu không hợp lệ sẽ bị bỏ qua</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Template -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-download"></i> Tải file mẫu</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Tải file CSV mẫu để import văn phòng phẩm với định dạng đúng chuẩn.</p>
                    <a href="<?php echo e(route('admin.import.template')); ?>" class="btn btn-success">
                        <i class="fas fa-download"></i> Tải file CSV mẫu
                    </a>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-upload"></i> Upload file Import</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.import.office-supplies.process')); ?>" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          id="importForm">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">Chọn file để import</label>
                            <input type="file" 
                                   class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="file" 
                                   name="file" 
                                   accept=".csv,.xlsx,.xls"
                                   required>
                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                Chấp nhận các file: CSV, Excel (XLS, XLSX). Dung lượng tối đa: 2MB.
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmImport" required>
                                <label class="form-check-label" for="confirmImport">
                                    Tôi đã kiểm tra dữ liệu và đồng ý import văn phòng phẩm
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-upload"></i> Bắt đầu Import
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Đặt lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Supported Columns -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="fas fa-table"></i> Cấu trúc dữ liệu hỗ trợ</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên cột (tiếng Anh)</th>
                                    <th>Tên cột (tiếng Việt)</th>
                                    <th>Bắt buộc</th>
                                    <th>Kiểu dữ liệu</th>
                                    <th>Ví dụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>name</code></td>
                                    <td>Tên sản phẩm</td>
                                    <td><span class="badge bg-danger">Có</span></td>
                                    <td>Văn bản</td>
                                    <td>Bút bi xanh</td>
                                </tr>
                                <tr>
                                    <td><code>description</code></td>
                                    <td>Mô tả</td>
                                    <td><span class="badge bg-secondary">Không</span></td>
                                    <td>Văn bản</td>
                                    <td>Bút bi màu xanh chất lượng cao</td>
                                </tr>
                                <tr>
                                    <td><code>unit</code></td>
                                    <td>Đơn vị</td>
                                    <td><span class="badge bg-danger">Có</span></td>
                                    <td>Văn bản</td>
                                    <td>cái, hộp, ream</td>
                                </tr>
                                <tr>
                                    <td><code>price</code></td>
                                    <td>Giá</td>
                                    <td><span class="badge bg-secondary">Không</span></td>
                                    <td>Số</td>
                                    <td>5000, 15.500</td>
                                </tr>
                                <tr>
                                    <td><code>stock_quantity</code></td>
                                    <td>Số lượng tồn kho</td>
                                    <td><span class="badge bg-secondary">Không</span></td>
                                    <td>Số nguyên</td>
                                    <td>100, 50</td>
                                </tr>
                                <tr>
                                    <td><code>category</code></td>
                                    <td>Danh mục</td>
                                    <td><span class="badge bg-secondary">Không</span></td>
                                    <td>Văn bản</td>
                                    <td>Văn phòng phẩm, Giấy tờ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Lưu ý:</strong> Hệ thống hỗ trợ nhiều tên cột khác nhau cho cùng một trường dữ liệu. 
                        Ví dụ: cột "name" có thể là "ten_san_pham", "tên sản phẩm", hoặc "product_name".
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.getElementById('importForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
});

document.getElementById('file').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const maxSize = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSize) {
            alert('File quá lớn! Vui lòng chọn file nhỏ hơn 2MB.');
            this.value = '';
            return;
        }
        
        const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if (!allowedTypes.includes(file.type)) {
            alert('Định dạng file không được hỗ trợ! Vui lòng chọn file CSV hoặc Excel.');
            this.value = '';
            return;
        }
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\admin\import\office-supplies.blade.php ENDPATH**/ ?>