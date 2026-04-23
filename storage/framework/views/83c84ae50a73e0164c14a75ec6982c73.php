

<?php $__env->startSection('title', 'Chi tiết nhân sự'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-user"></i> Chi tiết nhân sự</h5>
                    <div>
                        <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td><?php echo e($user->id); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Họ và tên:</strong></td>
                                    <td><?php echo e($user->name); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?php echo e($user->email); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Vai trò:</strong></td>
                                    <td>
                                        <?php switch($user->role):
                                            case ('admin'): ?>
                                                <span class="badge bg-danger">Admin</span>
                                                <?php break; ?>
                                            <?php case ('approver'): ?>
                                                <span class="badge bg-warning">Phê duyệt</span>
                                                <?php break; ?>
                                            <?php case ('employee'): ?>
                                                <span class="badge bg-success">Nhân viên</span>
                                                <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        <?php if($user->is_active): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Vô hiệu hóa</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Phòng ban:</strong></td>
                                    <td><?php echo e($user->department ?? 'Chưa xác định'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Chức vụ:</strong></td>
                                    <td><?php echo e($user->position ?? 'Chưa xác định'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Ca/kíp:</strong></td>
                                    <td><?php echo e($user->phone ?? 'Chưa có'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td><?php echo e($user->created_at->format('d/m/Y H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cập nhật cuối:</strong></td>
                                    <td><?php echo e($user->updated_at->format('d/m/Y H:i:s')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($user->role === 'employee' || $user->role === 'approver'): ?>
                    <hr>
                    <h6><i class="fas fa-chart-bar"></i> Thống kê hoạt động</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-primary">Yêu cầu đã tạo</h5>
                                    <h3 class="text-primary"><?php echo e($user->supplyRequests()->count()); ?></h3>
                                </div>
                            </div>
                        </div>
                        <?php if($user->role === 'approver'): ?>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-success">Yêu cầu đã phê duyệt</h5>
                                    <h3 class="text-success"><?php echo e($user->approvedRequests()->count()); ?></h3>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views/admin/users/show.blade.php ENDPATH**/ ?>