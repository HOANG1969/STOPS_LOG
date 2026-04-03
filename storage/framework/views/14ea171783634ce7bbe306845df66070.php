

<?php $__env->startSection('title', 'Ghi nhận STOP mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px;">
        <h1 style="font-size: 1.3rem;">
            <i class="fas fa-plus-circle text-primary me-2"></i>
            Ghi nhận STOP mới
        </h1>
        <a href="<?php echo e(route('stops.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Thông tin STOP (Safety Training Observation Program)
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('stops.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <!-- Loại vấn đề STOP -->
                        <div class="mb-4">
                            <label for="issue_category" class="form-label">
                                <strong>Loại vấn đề STOP</strong> <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?php $__errorArgs = ['issue_category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="issue_category" 
                                    name="issue_category" 
                                    required>
                                <option value="">-- Chọn loại vấn đề --</option>
                                <?php $__currentLoopData = \App\Models\Stop::getIssueCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('issue_category') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['issue_category'];
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

                        <hr class="mb-4">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="observer_name" class="form-label">
                                    Tên người ghi nhận <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['observer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="observer_name" 
                                       name="observer_name" 
                                       value="<?php echo e(old('observer_name', Auth::user()->full_name ?? Auth::user()->name)); ?>" 
                                       required>
                                <?php $__errorArgs = ['observer_name'];
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
                            
                            <div class="col-md-2">
                                <label for="observer_phone" class="form-label">
                                    Ca/kíp <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['observer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="observer_phone" 
                                        name="observer_phone" 
                                        required>
                                    <option value="">-- Chọn Ca/kíp --</option>
                                    <option value="HTSX" <?php echo e(old('observer_phone', Auth::user()->phone) == 'HTSX' ? 'selected' : ''); ?>>HTSX</option>
                                    <option value="VH01" <?php echo e(old('observer_phone', Auth::user()->phone) == 'VH01' ? 'selected' : ''); ?>>VH01</option>
                                    <option value="VH02" <?php echo e(old('observer_phone', Auth::user()->phone) == 'VH02' ? 'selected' : ''); ?>>VH02</option>
                                    <option value="VH03" <?php echo e(old('observer_phone', Auth::user()->phone) == 'VH03' ? 'selected' : ''); ?>>VH03</option>
                                    <option value="VH04" <?php echo e(old('observer_phone', Auth::user()->phone) == 'VH04' ? 'selected' : ''); ?>>VH04</option>
                                </select>
                                <?php $__errorArgs = ['observer_phone'];
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

                            <div class="col-md-3">
                                <label for="observation_date" class="form-label">
                                    Ngày tháng <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control <?php $__errorArgs = ['observation_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="observation_date" 
                                       name="observation_date" 
                                       value="<?php echo e(old('observation_date', date('Y-m-d'))); ?>" 
                                       required>
                                <?php $__errorArgs = ['observation_date'];
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

                            <div class="col-md-3">
                                <label for="observation_time" class="form-label">
                                    Giờ ghi nhận
                                </label>
                                <input type="time" 
                                       class="form-control <?php $__errorArgs = ['observation_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="observation_time" 
                                       name="observation_time" 
                                       value="<?php echo e(old('observation_time', date('H:i'))); ?>">
                                <?php $__errorArgs = ['observation_time'];
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

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="location" class="form-label">
                                    Vị trí <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="location" 
                                       name="location" 
                                       value="<?php echo e(old('location')); ?>" 
                                       placeholder="Ví dụ: Phân xưởng A, Khu vực sản xuất..."
                                       required>
                                <?php $__errorArgs = ['location'];
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

                            <div class="col-md-4">
                                <label for="equipment_name" class="form-label">
                                    Tên thiết bị
                                </label>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['equipment_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="equipment_name" 
                                       name="equipment_name" 
                                       value="<?php echo e(old('equipment_name')); ?>" 
                                       placeholder="Máy móc, công cụ...">
                                <?php $__errorArgs = ['equipment_name'];
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
                            <label for="issue_description" class="form-label">
                                Vấn đề ghi nhận <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['issue_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="issue_description" 
                                      name="issue_description" 
                                      rows="4" 
                                      placeholder="Mô tả chi tiết vấn đề về an toàn, sức khỏe, môi trường làm việc..."
                                      required><?php echo e(old('issue_description')); ?></textarea>
                            <?php $__errorArgs = ['issue_description'];
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
                            <label for="corrective_action" class="form-label">
                                Hành động khắc phục <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['corrective_action'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="corrective_action" 
                                      name="corrective_action" 
                                      rows="4" 
                                      placeholder="Đề xuất biện pháp khắc phục, cải thiện..."
                                      required><?php echo e(old('corrective_action')); ?></textarea>
                            <?php $__errorArgs = ['corrective_action'];
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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    Trạng thái <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="open" <?php echo e(old('status') == 'open' ? 'selected' : ''); ?>>Chưa xử lý</option>
                                    <option value="in-progress" <?php echo e(old('status') == 'in-progress' ? 'selected' : ''); ?>>Đang xử lý</option>
                                    <option value="completed" <?php echo e(old('status') == 'completed' ? 'selected' : ''); ?>>Hoàn thành</option>
                                </select>
                                <?php $__errorArgs = ['status'];
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

                            <div class="col-md-6">
                                <label for="completion_date" class="form-label">
                                    Ngày hoàn thành
                                </label>
                                <input type="date" 
                                       class="form-control <?php $__errorArgs = ['completion_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="completion_date" 
                                       name="completion_date" 
                                       value="<?php echo e(old('completion_date')); ?>">
                                <small class="text-muted">Chỉ cần nhập nếu trạng thái là "Hoàn thành"</small>
                                <?php $__errorArgs = ['completion_date'];
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

                        <!-- <div class="mb-3">
                            <label for="notes" class="form-label">
                                Ghi chú
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Thông tin bổ sung..."><?php echo e(old('notes')); ?></textarea>
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
                        </div> -->

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('stops.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Lưu ghi nhận
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="card" style="background-color: #6cd69c; color: white;">
                <div class="card-body">
                    <h6 class="card-title" style=" font-size: 1.25rem; color: blue;">
                        <i class="fas fa-info-circle me-1" style="color: Blue;" ></i>Hướng dẫn
                    </h6>
                    <small>
                        <p class="mb-2" style="font-size: 1rem; ">STOP là chương trình quan sát an toàn giúp:</p>
                        <ul class="small" style="font-size: 1rem; ">
                            <li>Phát hiện hành vi không an toàn</li>
                            <li>Cải thiện điều kiện làm việc</li>
                            <li>Tăng cường ý thức ATVSLĐ</li>
                            <li>- Các mức chấm điểm thẻ STOP:</li>
                            <li><strong>Mức 0</strong>: Nguy hiểm nghiêm trọng, cần xử lý ngay</li>
                            <li><strong>Mức 1</strong>: Nguy hiểm cao, cần xử lý ngay</li>
                            <li><strong>Mức 2</strong>: Nguy hiểm trung bình, cần theo dõi</li>
                            <li><strong>Mức 3</strong>: Nguy hiểm thấp, lưu ý</li>
                        </ul>

                    </small>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto enable/disable completion date based on status
document.getElementById('status').addEventListener('change', function() {
    const completionDate = document.getElementById('completion_date');
    if (this.value === 'completed') {
        completionDate.removeAttribute('disabled');
        if (!completionDate.value) {
            completionDate.value = new Date().toISOString().split('T')[0];
        }
    } else {
        completionDate.value = '';
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\stops\create.blade.php ENDPATH**/ ?>