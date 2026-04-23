

<?php $__env->startSection('title', 'Chỉnh sửa STOP'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px;">
        <h1>
            <i class="fas fa-edit text-warning me-2"></i>
            Chi tiết thẻ STOP
        </h1>
        <a href="<?php echo e(route('stops.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <?php if($stop->status === 'completed'): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Thẻ STOP đã hoàn thành!</strong> Không thể chỉnh sửa nội dung.
            </div>
            <?php endif; ?>

            <!-- <?php if($errors->has('status')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i>
                <?php echo e($errors->first('status')); ?>

            </div>
            <?php endif; ?> -->
            
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Cập nhật thông tin STOP
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                        $canScoreStop = Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTchcManager();
                    ?>

                    <form action="<?php echo e(route('stops.update', $stop)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Loại vấn đề STOP -->
                        <div class="mb-4">
                            <label for="issue_category" class="form-label">
                                <strong>Loại vấn đề STOP</strong> <span class="text-danger">*</span>
                            </select>
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
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('issue_category', $stop->issue_category) == $key ? 'selected' : ''); ?>>
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
                        
                         <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="priority_level" class="form-label">
                                    <strong>Mức độ STOP</strong>
                                </label>
                                    <?php if($canScoreStop): ?>
                                    <select class="form-select <?php $__errorArgs = ['priority_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="priority_level" 
                                            name="priority_level">
                                        <option value="">Chưa chấm</option>
                                        <option value="0" <?php echo e(old('priority_level', $stop->priority_level) == '0' ? 'selected' : ''); ?>>Mức 0: Nguy hiểm nghiêm trọng, cần xử lý ngay</option>
                                        <option value="1" <?php echo e(old('priority_level', $stop->priority_level) == '1' ? 'selected' : ''); ?>>Mức 1: Nguy hiểm cao, cần xử lý ngay</option>
                                        <option value="2" <?php echo e(old('priority_level', $stop->priority_level) == '2' ? 'selected' : ''); ?>>Mức 2: Nguy hiểm trung bình, cần theo dõi</option>
                                        <option value="3" <?php echo e(old('priority_level', $stop->priority_level) == '3' ? 'selected' : ''); ?>>Mức 3: Nguy hiểm thấp, cần cải thiện</option>
                                    </select>
                                    <?php else: ?>
                                    <input type="text" class="form-control" value="<?php echo e($stop->priority_level !== null ? $stop->getPriorityLabel() : 'Chưa chấm'); ?>" readonly>
                                    <small class="text-muted">Bạn chỉ có quyền chỉnh nội dung, không được thay đổi mức độ chấm điểm.</small>
                                    <?php endif; ?>
                                    <?php $__errorArgs = ['priority_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <!-- <div class="col-md-6">
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
                                        rows="2"><?php echo e(old('notes', $stop->notes)); ?></textarea>
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

                            <?php if($canScoreStop): ?>
                            <div class="col-md-6">
                                <label for="score_note" class="form-label">
                                    <strong>Ghi chú:</strong>
                                    <!-- Chú thích nếu CBAT bắt buộc -->
                                    <!-- <?php if(Auth::user()->isAdmin() || Auth::user()->isTchcManager()): ?>
                                    <span class="text-danger">*</span>
                                    <?php endif; ?> -->
                                </label>
                                <textarea class="form-control <?php $__errorArgs = ['score_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="score_note"
                                        name="score_note"
                                        rows="3"
                                        placeholder=""><?php echo e(old('score_note')); ?></textarea>
                                <?php $__errorArgs = ['score_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <!-- <small class="text-muted">Ghi chú này sẽ được lưu vào lịch sử chấm điểm của Trưởng ca hoặc CBAT.</small> -->
                            </div>
                            <?php endif; ?>
                        
                        </div>

                        

                        <hr class="mb-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="observer_name" class="form-label">
                                    <strong>Tên người quan sát</strong> <span class="text-danger">*</span>
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
                                       value="<?php echo e(old('observer_name', $stop->observer_name)); ?>" 
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
                                    <strong>Ca/kíp</strong> <span class="text-danger">*</span>
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
                                    <option value="HTSX" <?php echo e(old('observer_phone', $stop->observer_phone) == 'HTSX' ? 'selected' : ''); ?>>HTSX</option>
                                    <option value="VH01" <?php echo e(old('observer_phone', $stop->observer_phone) == 'VH01' ? 'selected' : ''); ?>>VH01</option>
                                    <option value="VH02" <?php echo e(old('observer_phone', $stop->observer_phone) == 'VH02' ? 'selected' : ''); ?>>VH02</option>
                                    <option value="VH03" <?php echo e(old('observer_phone', $stop->observer_phone) == 'VH03' ? 'selected' : ''); ?>>VH03</option>
                                    <option value="VH04" <?php echo e(old('observer_phone', $stop->observer_phone) == 'VH04' ? 'selected' : ''); ?>>VH04</option>
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

                            <div class="col-md-2">
                                <label for="observation_date" class="form-label">
                                    <strong>Ngày quan sát</strong> <span class="text-danger">*</span>
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
                                       value="<?php echo e(old('observation_date', $stop->observation_date->format('Y-m-d'))); ?>" 
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

                            <div class="col-md-2">
                                <label for="observation_time" class="form-label">
                                    <strong>Giờ quan sát</strong>
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
                                       value="<?php echo e(old('observation_time', $stop->observation_time ? $stop->observation_time->format('H:i') : '')); ?>">
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
                                    <strong>Vị trí</strong> <span class="text-danger">*</span>
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
                                       value="<?php echo e(old('location', $stop->location)); ?>" 
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
                                    <strong>Tên thiết bị</strong>
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
                                       value="<?php echo e(old('equipment_name', $stop->equipment_name)); ?>">
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
                                <strong>Vấn đề ghi nhận</strong> <span class="text-danger">*</span>
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
                                      required><?php echo e(old('issue_description', $stop->issue_description)); ?></textarea>
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
                                <strong>Hành động khắc phục</strong> <span class="text-danger">*</span>
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
                                      required><?php echo e(old('corrective_action', $stop->corrective_action)); ?></textarea>
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
                                <label class="form-label">
                                    <strong>Trạng thái</strong>
                                </label>
                                <input type="text" class="form-control" value="<?php echo e($stop->getStatusLabel()); ?>" readonly>
                                <small class="text-muted">Trạng thái chỉ hiển thị, hệ thống tự cập nhật theo quy trình chấm điểm.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="completion_date" class="form-label">
                                    <strong>Ngày hoàn thành</strong>
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
                                       value="<?php echo e(old('completion_date', $stop->completion_date ? $stop->completion_date->format('Y-m-d') : '')); ?>">
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
                                      rows="3"><?php echo e(old('notes', $stop->notes)); ?></textarea>
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
                            <?php if($stop->status !== 'completed'): ?>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Cập nhật
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i>Đã hoàn thành
                            </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
              <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-1"></i>Thông tin cập nhật
                    </h6>
                </div>
                <div class="card-body">
                    <?php
                        $latestShiftLeaderUpdate = $stop->scoreHistories->firstWhere('scorer_type', 'shift_leader');
                        $latestSafetyOfficerUpdate = $stop->scoreHistories->firstWhere('scorer_type', 'safety_officer');

                        if ($stop->relationLoaded('shiftLeaderScorer') && $stop->shift_leader_scored_at) {
                            $latestShiftLeaderUpdate = (object) [
                                'scorer' => $stop->shiftLeaderScorer,
                                'scored_at' => $stop->shift_leader_scored_at,
                                'priority_level' => $stop->shift_leader_priority_level,
                                'note' => $stop->shift_leader_note,
                            ];
                        }

                        if ($stop->relationLoaded('safetyOfficerScorer') && $stop->safety_officer_scored_at) {
                            $latestSafetyOfficerUpdate = (object) [
                                'scorer' => $stop->safetyOfficerScorer,
                                'scored_at' => $stop->safety_officer_scored_at,
                                'priority_level' => $stop->safety_officer_priority_level,
                                'note' => $stop->safety_officer_note,
                            ];
                        }

                        $legacyUpdate = null;
                        if ($stop->scorer && $stop->priority_scored_at) {
                            $legacyUpdate = (object) [
                                'scorer' => $stop->scorer,
                                'scored_at' => $stop->priority_scored_at,
                                'priority_level' => $stop->priority_level,
                                'note' => $stop->notes,
                            ];
                        }

                        if (!$latestShiftLeaderUpdate && $legacyUpdate && $stop->scorer->isApprover() && !$stop->scorer->isTchcManager()) {
                            $latestShiftLeaderUpdate = $legacyUpdate;
                        }

                        if (!$latestSafetyOfficerUpdate && $legacyUpdate && (!$stop->scorer->isApprover() || $stop->scorer->isTchcManager())) {
                            $latestSafetyOfficerUpdate = $legacyUpdate;
                        }
                    ?>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Trạng thái hiện tại</label>
                        <div>
                            <span class="<?php echo e($stop->getStatusBadgeClass()); ?> fs-6">
                                <?php echo e($stop->getStatusLabel()); ?>

                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Mức độ quan trọng</label>
                        <div>
                            <?php if($stop->priority_level !== null): ?>
                                <span class="badge <?php echo e($stop->getPriorityBadgeClass()); ?> fs-6">
                                    <?php echo e($stop->getPriorityLabel()); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary fs-6">Chưa chấm</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- <div class="mb-3">
                        <label class="form-label text-muted small">Trưởng ca cập nhật</label>
                        <?php if($latestShiftLeaderUpdate): ?>
                        <p class="mb-1">
                            <i class="fas fa-user-check me-1 text-primary"></i><?php echo e($latestShiftLeaderUpdate->scorer->full_name ?? $latestShiftLeaderUpdate->scorer->name ?? 'N/A'); ?>

                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="fas fa-clock me-1"></i><?php echo e($latestShiftLeaderUpdate->scored_at?->format('d/m/Y H:i')); ?>

                        </p>
                        <p class="mb-1 small">
                            <strong>Mức:</strong> <?php echo e($latestShiftLeaderUpdate->priority_level !== null ? 'Mức ' . $latestShiftLeaderUpdate->priority_level : 'Chưa chấm'); ?>

                        </p>
                        <p class="mb-0 small"><strong>Ghi chú:</strong> <?php echo e($latestShiftLeaderUpdate->note ?: 'Không có'); ?></p>
                        <?php else: ?>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-minus-circle me-1"></i>Chưa có cập nhật
                        </p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">CBAT cập nhật</label>
                        <?php if($latestSafetyOfficerUpdate): ?>
                        <p class="mb-1">
                            <i class="fas fa-user-shield me-1 text-primary"></i><?php echo e($latestSafetyOfficerUpdate->scorer->full_name ?? $latestSafetyOfficerUpdate->scorer->name ?? 'N/A'); ?>

                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="fas fa-clock me-1"></i><?php echo e($latestSafetyOfficerUpdate->scored_at?->format('d/m/Y H:i')); ?>

                        </p>
                        <p class="mb-1 small">
                            <strong>Mức:</strong> <?php echo e($latestSafetyOfficerUpdate->priority_level !== null ? 'Mức ' . $latestSafetyOfficerUpdate->priority_level : 'Chưa chấm'); ?>

                        </p>
                        <p class="mb-0 small"><strong>Ghi chú:</strong> <?php echo e($latestSafetyOfficerUpdate->note ?: 'Không có'); ?></p>
                        <?php else: ?>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-minus-circle me-1"></i>Chưa có cập nhật
                        </p>
                        <?php endif; ?>
                    </div> -->

                    <?php if($stop->scoreHistories->isNotEmpty()): ?>
                    <hr>
                    <div>
                        <label class="form-label text-muted small">Lịch sử</label>
                        <div class="small" style="max-height: 320px; overflow-y: auto;">
                            <?php $__currentLoopData = $stop->scoreHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded p-2 mb-2">
                                <div class="fw-semibold"><?php echo e($history->getScorerTypeLabel()); ?>: <?php echo e($history->scorer->full_name ?? $history->scorer->name ?? 'N/A'); ?></div>
                                <div class="text-muted"><?php echo e($history->scored_at?->format('d/m/Y H:i')); ?></div>
                                <div>
                                    <?php if($history->previous_priority_level !== null): ?>
                                    <span>Mức: <?php echo e($history->previous_priority_level); ?></span>
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    <?php endif; ?>
                                    <span class="fw-semibold"><?php echo e($history->priority_level !== null ? 'Mức: ' . $history->priority_level : 'Chưa chấm'); ?></span>
                                </div>
                                <?php if($history->note): ?>
                                <div class="mt-1"><strong>Ghi chú:</strong> <?php echo e($history->note); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($stop->completion_date): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Ngày hoàn thành</label>
                        <p class="fw-bold mb-0"><?php echo e($stop->completion_date->format('d/m/Y')); ?></p>
                    </div>
                    <?php endif; ?>

                    <hr>

                    <div class="mb-2">
                        <label class="form-label text-muted small">Người ghi nhận</label>
                        <p class="mb-0">
                            <i class="fas fa-user me-1"></i><?php echo e($stop->user->full_name); ?>

                        </p>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-muted small">Ngày tạo</label>
                        <p class="mb-0">
                            <i class="fas fa-calendar me-1"></i><?php echo e($stop->created_at->format('d/m/Y H:i')); ?>

                        </p>
                    </div>

                    <div>
                        <label class="form-label text-muted small">Cập nhật lần cuối</label>
                        <p class="mb-0">
                            <i class="fas fa-clock me-1"></i><?php echo e($stop->updated_at->format('d/m/Y H:i')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Disable form khi STOP đã hoàn thành
<?php if($stop->status === 'completed'): ?>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        const inputs = form.querySelectorAll('input, select, textarea, button[type="submit"]');
        inputs.forEach(input => {
            input.disabled = true;
        });
    }
});
<?php endif; ?>

// Auto enable/disable completion date based on status
document.getElementById('status')?.addEventListener('change', function() {
    const completionDate = document.getElementById('completion_date');
    if (this.value === 'completed') {
        completionDate?.removeAttribute('disabled');
        if (!completionDate.value) {
            completionDate.value = new Date().toISOString().split('T')[0];
        }
    } else {
        if (completionDate) {
            completionDate.value = '';
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views/stops/edit.blade.php ENDPATH**/ ?>