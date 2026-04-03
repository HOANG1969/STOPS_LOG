

<?php $__env->startSection('title', 'Quản lý Phiếu đăng ký'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Quản lý Phiếu đăng ký
                    </h4>
                    <a href="<?php echo e(route('employee.requests.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tạo mới Phiếu đăng ký
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Thông tin bộ phận và kỳ -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Bộ phận:</strong> <?php echo e(auth()->user()->department ?? 'N/A'); ?>

                        </div>
                        <div class="col-md-6">
                                <strong>Kỳ:</strong> <?php echo e(now()->format('F Y')); ?>

                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Bảng danh sách yêu cầu -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Mã đơn</th>
                                    <th width="12%">Ngày tạo</th>
                                    <th width="12%">Ngày cần</th>
                                    <th width="10%">Ưu tiên</th>
                                    <th width="12%">Trạng thái</th>
                                    <th width="15%">Người phê duyệt</th>
                                    <th width="19%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($requests->firstItem() + $index); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('employee.requests.show', $request)); ?>" class="text-decoration-none">
                                                <?php echo e($request->request_code); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($request->created_at->format('d/m/Y')); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($request->needed_date)->format('d/m/Y')); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php if($request->priority === 'Normal'): ?> bg-info
                                                <?php elseif($request->priority === 'High'): ?> bg-warning
                                                <?php else: ?> bg-danger
                                                <?php endif; ?>">
                                                <?php echo e($request->priority); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                <?php if($request->status === 'pending'): ?> bg-secondary
                                                <?php elseif($request->status === 'forwarded'): ?> bg-warning
                                                <?php elseif($request->status === 'approved'): ?> bg-success
                                                <?php else: ?> bg-danger
                                                <?php endif; ?>">
                                                <?php switch($request->status):
                                                    case ('pending'): ?> Chờ xử lý <?php break; ?>
                                                    <?php case ('forwarded'): ?> Đã chuyển <?php break; ?>
                                                    <?php case ('approved'): ?> Đã duyệt <?php break; ?>
                                                    <?php case ('rejected'): ?> Từ chối <?php break; ?>
                                                <?php endswitch; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo e($request->approver->name ?? '-'); ?>

                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('employee.requests.show', $request)); ?>" 
                                                   class="btn btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if($request->status === 'pending'): ?>
                                                    <form action="<?php echo e(route('employee.requests.forward', $request)); ?>" 
                                                          method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-warning" 
                                                                title="Chuyển phê duyệt"
                                                                onclick="return confirm('Bạn có chắc muốn chuyển đơn này để phê duyệt?')">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <a href="<?php echo e(route('employee.requests.history', $request)); ?>" 
                                                   class="btn btn-secondary" title="Xem lịch sử">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <br>
                                            <span class="text-muted">Chưa có yêu cầu nào</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php echo e($requests->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\employee\requests\index.blade.php ENDPATH**/ ?>