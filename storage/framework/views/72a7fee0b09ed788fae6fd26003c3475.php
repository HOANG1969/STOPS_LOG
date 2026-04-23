

<?php $__env->startSection('title', 'Sửa thông tin nhân sự'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user-edit"></i> Sửa thông tin nhân sự: <?php echo e($user->name); ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('users.update', $user->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
                                <?php $__errorArgs = ['name'];
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

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                                <?php $__errorArgs = ['email'];
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
                            <!-- <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">full_name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="full_name" name="full_name" value="<?php echo e(old('full_name', $user->full_name)); ?>" required>
                                <?php $__errorArgs = ['full_name'];
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
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="password" name="password">
                                <?php $__errorArgs = ['password'];
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

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="role" name="role" required>
                                    <option value="">Chọn vai trò</option>
                                    <option value="admin" <?php echo e(old('role', $user->role) === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                    <option value="approver" <?php echo e(old('role', $user->role) === 'approver' ? 'selected' : ''); ?>>Phê duyệt</option>
                                    <option value="tchc_checker" <?php echo e(old('role', $user->role) === 'tchc_checker' ? 'selected' : ''); ?>>TCHC Kiểm tra</option>
                                    <option value="tchc_manager" <?php echo e(old('role', $user->role) === 'tchc_manager' ? 'selected' : ''); ?>>TCHC Quản lý</option>
                                    <option value="employee" <?php echo e(old('role', $user->role) === 'employee' ? 'selected' : ''); ?>>Nhân viên</option>
                                </select>
                                <?php $__errorArgs = ['role'];
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

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Ca/kíp</label>
                                <select class="form-select <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone" name="phone">
                                    <option value="">-- Chọn ca/kíp --</option>
                                    <option value="HTSX" <?php echo e(old('phone', $user->phone) == 'HTSX' ? 'selected' : ''); ?>>HTSX</option>
                                    <option value="VH01" <?php echo e(old('phone', $user->phone) == 'VH01' ? 'selected' : ''); ?>>VH01</option>
                                    <option value="VH02" <?php echo e(old('phone', $user->phone) == 'VH02' ? 'selected' : ''); ?>>VH02</option>
                                    <option value="VH03" <?php echo e(old('phone', $user->phone) == 'VH03' ? 'selected' : ''); ?>>VH03</option>
                                    <option value="VH04" <?php echo e(old('phone', $user->phone) == 'VH04' ? 'selected' : ''); ?>>VH04</option>
                                </select>
                                <?php $__errorArgs = ['phone'];
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Phòng ban</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="department" name="department" value="<?php echo e(old('department', $user->department)); ?>" 
                                       placeholder="VD: Nhân sự, IT, Marketing">
                                <?php $__errorArgs = ['department'];
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

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Chức vụ</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="position" name="position" value="<?php echo e(old('position', $user->position)); ?>" 
                                       placeholder="VD: Trưởng phòng, Nhân viên">
                                <?php $__errorArgs = ['position'];
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       <?php echo e(old('is_active', $user->is_active) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">
                                    Tài khoản hoạt động
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>