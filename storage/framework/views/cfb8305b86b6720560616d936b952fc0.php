

<?php $__env->startSection('title', 'Tạo yêu cầu văn phòng phẩm'); ?>
<?php $__env->startSection('page-title', 'Tạo yêu cầu mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Tạo yêu cầu văn phòng phẩm mới
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Tiêu đề yêu cầu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Nhập tiêu đề yêu cầu">
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Chọn danh mục</option>
                                <option value="stationery">Văn phòng phẩm</option>
                                <option value="electronics">Thiết bị điện tử</option>
                                <option value="furniture">Nội thất văn phòng</option>
                                <option value="consumables">Vật tư tiêu hao</option>
                                <option value="books">Sách và tài liệu</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Mức độ ưu tiên</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low">Thấp</option>
                                <option value="medium" selected>Trung bình</option>
                                <option value="high">Cao</option>
                                <option value="urgent">Khẩn cấp</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="expected_date" class="form-label">Ngày mong muốn nhận hàng</label>
                            <input type="date" class="form-control" id="expected_date" name="expected_date">
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-boxes me-2"></i>
                                Chi tiết sản phẩm
                            </h6>
                        </div>
                    </div>

                    <!-- Dynamic Product List -->
                    <div id="product-list">
                        <div class="product-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="products[0][name]" placeholder="Nhập tên sản phẩm" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="products[0][quantity]" min="1" value="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Đơn vị</label>
                                    <select class="form-select" name="products[0][unit]">
                                        <option value="cái">Cái</option>
                                        <option value="chiếc">Chiếc</option>
                                        <option value="bộ">Bộ</option>
                                        <option value="thùng">Thùng</option>
                                        <option value="gói">Gói</option>
                                        <option value="kg">Kg</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Giá ước tính (VNĐ)</label>
                                    <input type="number" class="form-control" name="products[0][estimated_price]" min="0" placeholder="0">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-outline-danger w-100 remove-product" title="Xóa sản phẩm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-label">Mô tả chi tiết</label>
                                    <textarea class="form-control" name="products[0][description]" rows="2" placeholder="Mô tả thêm về sản phẩm (màu sắc, kích thước, thương hiệu...)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="add-product" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>
                            Thêm sản phẩm
                        </button>
                    </div>

                    <!-- Justification -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-comment-alt me-2"></i>
                                Lý do yêu cầu
                            </h6>
                            <label for="justification" class="form-label">Giải trình chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="justification" name="justification" rows="4" placeholder="Vui lòng giải thích lý do cần mua những vật phẩm này, mục đích sử dụng và tính cấp thiết..." required></textarea>
                        </div>
                    </div>

                    <!-- Attachment -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-paperclip me-2"></i>
                                Tài liệu đính kèm
                            </h6>
                            <label for="attachments" class="form-label">File đính kèm (nếu có)</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls">
                            <div class="form-text">Hỗ trợ file PDF, Word, Excel, hình ảnh. Tối đa 10MB mỗi file.</div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo e(route('office-supplies.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại
                                </a>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-save me-2"></i>
                                        Lưu nháp
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Gửi yêu cầu
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productCount = 1;

    // Add product
    document.getElementById('add-product').addEventListener('click', function() {
        const productList = document.getElementById('product-list');
        const newProduct = document.querySelector('.product-item').cloneNode(true);
        
        // Update input names
        const inputs = newProduct.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[0]', `[${productCount}]`));
                input.value = input.type === 'number' ? '1' : '';
            }
        });
        
        productList.appendChild(newProduct);
        productCount++;
        updateRemoveButtons();
    });

    // Remove product
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-product');
        removeButtons.forEach(button => {
            button.onclick = function() {
                if (document.querySelectorAll('.product-item').length > 1) {
                    this.closest('.product-item').remove();
                } else {
                    alert('Phải có ít nhất một sản phẩm!');
                }
            };
        });
    }

    updateRemoveButtons();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\office-supplies\create.blade.php ENDPATH**/ ?>