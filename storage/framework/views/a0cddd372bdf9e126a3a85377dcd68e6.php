

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle me-2"></i>Tạo yêu cầu VPP mới</h2>
        <a href="<?php echo e(route('requests.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <form action="<?php echo e(route('requests.store')); ?>" method="POST" id="requestForm">
        <?php echo csrf_field(); ?>
        
        <div class="row">
            <!-- Request Info -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin yêu cầu</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Tiêu đề yêu cầu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="title" name="title" value="<?php echo e(old('title')); ?>" required>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="needed_date" class="form-label">Ngày cần <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['needed_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="needed_date" name="needed_date" value="<?php echo e(old('needed_date')); ?>" 
                                       min="<?php echo e(date('Y-m-d')); ?>" required>
                                <?php $__errorArgs = ['needed_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="priority" class="form-label">Độ ưu tiên <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="priority" name="priority" required>
                                    <option value="low" <?php echo e(old('priority') === 'low' ? 'selected' : ''); ?>>Thấp</option>
                                    <option value="medium" <?php echo e(old('priority', 'medium') === 'medium' ? 'selected' : ''); ?>>Trung bình</option>
                                    <option value="high" <?php echo e(old('priority') === 'high' ? 'selected' : ''); ?>>Cao</option>
                                    <option value="urgent" <?php echo e(old('priority') === 'urgent' ? 'selected' : ''); ?>>Khẩn cấp</option>
                                </select>
                                <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Mục đích sử dụng</label>
                            <textarea class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="purpose" name="purpose" rows="3"><?php echo e(old('purpose')); ?></textarea>
                            <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="notes" name="notes" rows="2"><?php echo e(old('notes')); ?></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Products Selection -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Danh sách sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button type="button" class="btn btn-success" onclick="addProductRow()">
                                <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="productsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Sản phẩm <span class="text-danger">*</span></th>
                                        <th width="15%">Đơn vị</th>
                                        <th width="15%">Số lượng <span class="text-danger">*</span></th>
                                        <th width="20%">Đơn giá <span class="text-danger">*</span></th>
                                        <th width="15%">Thành tiền</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <!-- Product rows will be added here -->
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th colspan="4" class="text-end">Tổng cộng:</th>
                                        <th id="totalAmount">0 VND</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary & Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Tóm tắt</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Tổng số sản phẩm:</strong> <span id="totalProducts">0</span>
                        </div>
                        <div class="mb-3">
                            <strong>Tổng tiền:</strong> <span id="summaryTotal" class="text-success">0 VND</span>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>Gửi yêu cầu
                            </button>
                            <button type="submit" name="save_draft" class="btn btn-outline-primary">
                                <i class="fas fa-save me-2"></i>Lưu nháp
                            </button>
                            <a href="<?php echo e(route('requests.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Product selection template -->
<template id="productRowTemplate">
    <tr class="product-row">
        <td>
            <select name="products[INDEX][product_id]" class="form-select product-select" required>
                <option value="">Chọn sản phẩm...</option>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName => $categoryProducts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup label="<?php echo e($categoryName); ?>">
                        <?php $__currentLoopData = $categoryProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>" 
                                    data-unit="<?php echo e($product->unit); ?>" 
                                    data-price="<?php echo e($product->price); ?>">
                                <?php echo e($product->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
        <td class="unit-display">-</td>
        <td>
            <input type="number" name="products[INDEX][quantity]" class="form-control quantity-input" 
                   min="1" value="1" required>
        </td>
        <td>
            <input type="number" name="products[INDEX][unit_price]" class="form-control price-input" 
                   min="0" step="0.01" required>
        </td>
        <td class="total-display">0 VND</td>
        <td>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeProductRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let rowIndex = 0;

function addProductRow() {
    const template = document.getElementById('productRowTemplate');
    const tableBody = document.getElementById('productsTableBody');
    
    const clone = template.content.cloneNode(true);
    const row = clone.querySelector('.product-row');
    
    // Replace INDEX with actual index
    row.innerHTML = row.innerHTML.replace(/INDEX/g, rowIndex);
    
    tableBody.appendChild(row);
    
    // Attach event listeners
    attachEventListeners(row);
    
    rowIndex++;
    updateSummary();
}

function removeProductRow(button) {
    button.closest('tr').remove();
    updateSummary();
}

function attachEventListeners(row) {
    const productSelect = row.querySelector('.product-select');
    const quantityInput = row.querySelector('.quantity-input');
    const priceInput = row.querySelector('.price-input');
    
    productSelect.addEventListener('change', function() {
        const option = this.selectedOptions[0];
        const unitDisplay = row.querySelector('.unit-display');
        const priceInput = row.querySelector('.price-input');
        
        if (option.value) {
            unitDisplay.textContent = option.dataset.unit || '-';
            priceInput.value = option.dataset.price || 0;
        } else {
            unitDisplay.textContent = '-';
            priceInput.value = 0;
        }
        
        updateRowTotal(row);
    });
    
    quantityInput.addEventListener('input', () => updateRowTotal(row));
    priceInput.addEventListener('input', () => updateRowTotal(row));
}

function updateRowTotal(row) {
    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = quantity * price;
    
    row.querySelector('.total-display').textContent = formatCurrency(total);
    updateSummary();
}

function updateSummary() {
    const rows = document.querySelectorAll('.product-row');
    let totalAmount = 0;
    let totalProducts = 0;
    
    rows.forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        
        if (quantity > 0 && price >= 0) {
            totalAmount += quantity * price;
            totalProducts++;
        }
    });
    
    document.getElementById('totalAmount').textContent = formatCurrency(totalAmount);
    document.getElementById('summaryTotal').textContent = formatCurrency(totalAmount);
    document.getElementById('totalProducts').textContent = totalProducts;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Form validation
document.getElementById('requestForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('.product-row');
    
    if (rows.length === 0) {
        e.preventDefault();
        alert('Vui lòng thêm ít nhất một sản phẩm.');
        return false;
    }
    
    let hasValidProduct = false;
    rows.forEach(row => {
        const productSelect = row.querySelector('.product-select');
        const quantity = row.querySelector('.quantity-input');
        const price = row.querySelector('.price-input');
        
        if (productSelect.value && quantity.value > 0 && price.value >= 0) {
            hasValidProduct = true;
        }
    });
    
    if (!hasValidProduct) {
        e.preventDefault();
        alert('Vui lòng chọn ít nhất một sản phẩm hợp lệ.');
        return false;
    }
});

// Add first product row on page load
document.addEventListener('DOMContentLoaded', function() {
    addProductRow();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\requests\create.blade.php ENDPATH**/ ?>