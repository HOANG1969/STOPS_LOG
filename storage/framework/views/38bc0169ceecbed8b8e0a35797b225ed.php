

<?php $__env->startSection('title', 'Chi tiết Phiếu đăng ký'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Chi tiết Phiếu đăng ký - <?php echo e($request->request_code); ?>

                    </h4>
                    <div class="btn-group">
                        <?php if($request->status === 'pending'): ?>
                            <form action="<?php echo e(route('employee.requests.forward', $request)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-warning" 
                                        onclick="return confirm('Bạn có chắc muốn chuyển đơn này để phê duyệt?')">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    Chuyển phê duyệt
                                </button>
                            </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('employee.requests.history', $request)); ?>" class="btn btn-info">
                            <i class="fas fa-history me-1"></i>
                            Lịch sử
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Flash messages -->
                    <?php if(session('success')): ?>
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

                    <!-- Thông tin chung -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3">Thông tin chung</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="fw-bold">Mã yêu cầu:</label>
                                <span class="ms-2"><?php echo e($request->request_code); ?></span>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold">Người yêu cầu:</label>
                                <span class="ms-2"><?php echo e($request->requester_name); ?></span>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold">Email:</label>
                                <span class="ms-2"><?php echo e($request->requester_email); ?></span>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold">Bộ phận:</label>
                                <span class="ms-2"><?php echo e($request->department); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="fw-bold">Ngày tạo:</label>
                                <span class="ms-2"><?php echo e($request->created_at->format('d/m/Y H:i')); ?></span>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold">Ngày cần sử dụng:</label>
                                <span class="ms-2"><?php echo e(\Carbon\Carbon::parse($request->needed_date)->format('d/m/Y')); ?></span>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold">Mức độ ưu tiên:</label>
                                <span class="ms-2">
                                    <span class="badge 
                                        <?php if($request->priority === 'Normal'): ?> bg-info
                                        <?php elseif($request->priority === 'High'): ?> bg-warning
                                        <?php else: ?> bg-danger
                                        <?php endif; ?>">
                                        <?php echo e($request->priority); ?>

                                    </span>
                                </span>
                            </div>
                            <div class="info-group mb-3">
                                <label class="fw-bold">Trạng thái:</label>
                                <span class="ms-2">
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
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <?php if($request->notes): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3">Ghi chú</h5>
                            <div class="bg-light p-3 rounded">
                                <?php echo e($request->notes); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Thông tin phê duyệt -->
                    <?php if($request->status !== 'pending'): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3">Thông tin phê duyệt</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group mb-3">
                                <label class="fw-bold">Người phê duyệt:</label>
                                <span class="ms-2"><?php echo e($request->approver->name ?? '-'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php if($request->approved_at): ?>
                            <div class="info-group mb-3">
                                <label class="fw-bold">Thời gian phê duyệt:</label>
                                <span class="ms-2"><?php echo e($request->approved_at->format('d/m/Y H:i')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if($request->approval_notes): ?>
                        <div class="col-md-12">
                            <div class="info-group mb-3">
                                <label class="fw-bold">Ghi chú phê duyệt:</label>
                                <div class="bg-light p-3 rounded mt-2">
                                    <?php echo e($request->approval_notes); ?>

                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Danh sách văn phòng phẩm -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3">Danh sách văn phòng phẩm</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Tên văn phòng phẩm</th>
                                            <th width="20%">Quy cách, xuất xứ</th>
                                            <th width="8%">ĐVT</th>
                                            <th width="12%">Số lượng</th>
                                            <th width="30%">Mục đích sử dụng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $request->requestItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($index + 1); ?></td>
                                                <td><?php echo e($item->officeSupply->name); ?></td>
                                                <td><?php echo e($item->officeSupply->specification ?? '-'); ?></td>
                                                <td><?php echo e($item->officeSupply->unit); ?></td>
                                                <td><?php echo e(number_format($item->quantity)); ?></td>
                                                <td><?php echo e($item->purpose); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-3">
                                                    <span class="text-muted">Không có văn phòng phẩm nào</span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo e(route('employee.requests.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Quay lại danh sách
                        </a>
                        
                        <div class="btn-group">
                            <?php if($request->status === 'pending'): ?>
                                <form action="<?php echo e(route('employee.requests.forward', $request)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-warning" 
                                            onclick="return confirm('Bạn có chắc muốn chuyển đơn này để phê duyệt?')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Chuyển phê duyệt
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('employee.requests.history', $request)); ?>" class="btn btn-info">
                                <i class="fas fa-history me-1"></i>
                                Xem lịch sử
                            </a>
                        </div>
                    </div>
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

<?php $__env->startPush('styles'); ?>
<style>
.info-group {
    display: flex;
    align-items: flex-start;
}
.info-group label {
    min-width: 150px;
    margin-bottom: 0;
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\employee\requests\show.blade.php ENDPATH**/ ?>