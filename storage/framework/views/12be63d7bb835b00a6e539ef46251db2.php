

<?php $__env->startSection('title', 'Chi tiết yêu cầu'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="margin-top: 30px;">Chi tiết yêu cầu văn phòng phẩm</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <?php if(Auth::user()->isTchcChecker()): ?>
                        <!-- <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Kiểm tra</a></li> -->
                        <li class="breadcrumb-item"><a href="<?php echo e(route('tchc.checker.dashboard')); ?>">Kiểm tra phiếu VPP</a></li>
                    <?php elseif(Auth::user()->isTchcManager()): ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('tchc.manager.dashboard')); ?>">Phê duyệt đăng ký VPP</a></li>
                    <?php elseif(Auth::user()->isApprover()): ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.approval')); ?>">Phê duyệt đăng ký VPP</a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Văn phòng phẩm</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('supply-requests.index')); ?>">Yêu cầu VPP</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết #<?php echo e($request->id); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if(Auth::user()->isTchcChecker()): ?>
                <a href="<?php echo e(route('tchc.checker.dashboard')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            <?php elseif(Auth::user()->isTchcManager()): ?>
                <a href="<?php echo e(route('tchc.manager.dashboard')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            <?php elseif(Auth::user()->isApprover()): ?>
                <a href="<?php echo e(route('dashboard.approval')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('supply-requests.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            <?php endif; ?>
            <?php if(Auth::id() == $request->user_id && in_array($request->status, ['draft'])): ?>
                <a href="<?php echo e(route('supply-requests.edit', $request->id)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            <?php endif; ?>
            <?php if(Auth::user()->isApprover() && in_array($request->status, ['pending', 'forwarded'])): ?>
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="fas fa-check me-2"></i> Phê duyệt
                </button>
                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times me-2"></i> Từ chối
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Request Details Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Yêu cầu #<?php echo e($request->id); ?>

            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-2">Thông tin chung</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="text-muted">Người tạo:</td>
                            <td><?php echo e($request->user->name); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Phòng ban:</td>
                            <td><?php echo e($request->requester_department); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày tạo:</td>
                            <td><?php echo e($request->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày cần:</td>
                            <td>
                                <?php if($request->needed_date): ?>
                                <?php echo e(\Carbon\Carbon::parse($request->needed_date)->format('d/m/Y')); ?>

                                <?php else: ?>
                                <span class="text-muted">Chưa xác định</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Độ ưu tiên:</td>
                            <td>
                                <?php
                                $priorityConfig = [
                                    'low' => ['class' => 'bg-light text-dark', 'text' => 'Thấp'],
                                    'Low' => ['class' => 'bg-light text-dark', 'text' => 'Thấp'],
                                    'normal' => ['class' => 'bg-primary', 'text' => 'Bình thường'],
                                    'Normal' => ['class' => 'bg-primary', 'text' => 'Bình thường'],
                                    'high' => ['class' => 'bg-warning text-dark', 'text' => 'Cao'],
                                    'High' => ['class' => 'bg-warning text-dark', 'text' => 'Cao'],
                                    'urgent' => ['class' => 'bg-danger', 'text' => 'Khẩn cấp'],
                                    'Urgent' => ['class' => 'bg-danger', 'text' => 'Khẩn cấp']
                                ];
                                $config = $priorityConfig[$request->priority] ?? $priorityConfig['normal'];
                                $priorityClass = $config['class'];
                                $priorityText = $config['text'];
                                ?>
                                <span class="badge <?php echo e($priorityClass); ?>"><?php echo e($priorityText); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái:</td>
                            <td>
                                <?php
                                $statusConfig = [
                                    'draft' => ['class' => 'bg-secondary', 'text' => 'Bản nháp', 'icon' => 'fas fa-edit'],
                                    'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ duyệt', 'icon' => 'fas fa-clock'],
                                    'approved' => ['class' => 'bg-success', 'text' => 'Trưởng bộ phận phê duyệt', 'icon' => 'fas fa-check'],
                                    'tchc_checked' => ['class' => 'bg-info', 'text' => 'Nhân sự TCHC đã kiểm tra', 'icon' => 'fas fa-search'],
                                    'tchc_approved' => ['class' => 'bg-success', 'text' => 'Lãnh đạo TCHC phê duyệt', 'icon' => 'fas fa-stamp'],
                                    'tchc_rejected' => ['class' => 'bg-danger', 'text' => 'TCHC từ chối', 'icon' => 'fas fa-ban'],
                                    'partially_approved' => ['class' => 'bg-info', 'text' => 'Duyệt một phần', 'icon' => 'fas fa-check-double'],
                                    'rejected' => ['class' => 'bg-danger', 'text' => 'Từ chối', 'icon' => 'fas fa-times'],
                                    'completed' => ['class' => 'bg-dark', 'text' => 'Hoàn thành', 'icon' => 'fas fa-flag-checkered']
                                ];
                                $config = $statusConfig[$request->status] ?? $statusConfig['draft'];
                                ?>
                                <span class="badge <?php echo e($config['class']); ?>">
                                    <i class="<?php echo e($config['icon']); ?> me-1"></i><?php echo e($config['text']); ?>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bước tiếp theo:</td>
                            <td>
                                <?php
                                $nextSteps = [
                                    'draft' => ['text' => 'Hoàn thiện thông tin và gửi phê duyệt', 'icon' => 'fas fa-paper-plane', 'class' => 'text-primary'],
                                    'pending' => ['text' => 'Chờ Trưởng bộ phận xem xét và phê duyệt', 'icon' => 'fas fa-user-tie', 'class' => 'text-warning'],
                                    'approved' => ['text' => 'Chờ Nhân sự TCHC kiểm tra hồ sơ', 'icon' => 'fas fa-search', 'class' => 'text-info'],
                                    'tchc_checked' => ['text' => 'Chờ Lãnh đạo TCHC phê duyệt cuối cùng', 'icon' => 'fas fa-stamp', 'class' => 'text-purple'],
                                    'tchc_approved' => ['text' => 'Đã hoàn thành - chờ cấp phát VPP', 'icon' => 'fas fa-check-circle', 'class' => 'text-success'],
                                    'tchc_rejected' => ['text' => 'Đã từ chối - vui lòng xem lại yêu cầu', 'icon' => 'fas fa-exclamation-triangle', 'class' => 'text-danger'],
                                    'rejected' => ['text' => 'Đã từ chối - có thể tạo yêu cầu mới', 'icon' => 'fas fa-exclamation-triangle', 'class' => 'text-danger'],
                                    'completed' => ['text' => 'Quy trình đã hoàn tất', 'icon' => 'fas fa-flag-checkered', 'class' => 'text-success']
                                ];
                                $nextStep = $nextSteps[$request->status] ?? $nextSteps['draft'];
                                ?>
                                <span class="<?php echo e($nextStep['class']); ?>">
                                    <i class="<?php echo e($nextStep['icon']); ?> me-1"></i><?php echo e($nextStep['text']); ?>

                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <?php if($request->notes): ?>
                    <h6 class="fw-bold mb-2">Ghi chú</h6>
                    <div class="border rounded p-3 mb-3 bg-light">
                        <?php echo e($request->notes); ?>

                    </div>
                    <?php endif; ?>
                    
                    <?php if($request->approver): ?>
                    <h6 class="fw-bold mb-2">Thông tin phê duyệt</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="text-muted">Người duyệt:</td>
                            <td><?php echo e($request->approver->name); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày duyệt:</td>
                            <td><?php echo e($request->approved_at ? $request->approved_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'Chưa duyệt'); ?></td>
                        </tr>
                        <?php if($request->approval_notes): ?>
                        <tr>
                            <td class="text-muted">Ghi chú duyệt:</td>
                            <td><?php echo e($request->approval_notes); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    <?php endif; ?>

                    
                    <?php if($request->tchcChecker): ?>
                    <h6 class="fw-bold mb-2">Thông tin TCHC Check</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="text-muted">TCHC Checker:</td>
                            <td><?php echo e($request->tchcChecker->name); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày check:</td>
                            <td><?php echo e($request->tchc_checked_at ? \Carbon\Carbon::parse($request->tchc_checked_at)->format('d/m/Y H:i') : 'Chưa check'); ?></td>
                        </tr>
                        <?php if($request->tchc_check_notes): ?>
                        <tr>
                            <td class="text-muted">Ghi chú check:</td>
                            <td><?php echo e($request->tchc_check_notes); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    <?php endif; ?>

                    
                    <?php if($request->tchcManager): ?>
                    <h6 class="fw-bold mb-2">Thông tin phê duyệt cuối</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="text-muted">TCHC Manager:</td>
                            <td><?php echo e($request->tchcManager->name); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày phê duyệt cuối:</td>
                            <td><?php echo e($request->tchc_approved_at ? $request->tchc_approved_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'Chưa phê duyệt'); ?></td>
                        </tr>
                        <?php if($request->tchc_approval_notes): ?>
                        <tr>
                            <td class="text-muted">Ghi chú phê duyệt cuối:</td>
                            <td><?php echo e($request->tchc_approval_notes); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="fw-bold mb-3">Danh sách văn phòng phẩm</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th width="25%">Tên VPP</th>
                                    <th width="12%">Đơn vị</th>
                                    <th width="10%">Số lượng</th>
                                    <th width="15%">Đơn giá</th>
                                    <th width="15%">Thành tiền</th>
                                    <th width="15%">Mục đích</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                <?php $__currentLoopData = $request->requestItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo e($item->officeSupply->name); ?></div>
                                        <?php if($item->officeSupply->description): ?>
                                        <small class="text-muted"><?php echo e($item->officeSupply->description); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark"><?php echo e($item->officeSupply->unit); ?></span>
                                    </td>
                                    <td class="text-center"><?php echo e(number_format($item->quantity)); ?></td>
                                    <td class="text-end"><?php echo e(number_format($item->officeSupply->price, 0, ',', '.')); ?>đ</td>
                                    <td class="text-end">
                                        <?php 
                                        $itemTotal = $item->quantity * $item->officeSupply->price;
                                        $total += $itemTotal;
                                        ?>
                                        <?php echo e(number_format($itemTotal, 0, ',', '.')); ?>đ
                                    </td>
                                    <td>
                                        <div class="text-truncate" title="<?php echo e($item->purpose); ?>">
                                            <?php echo e($item->purpose); ?>

                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="5" class="text-end">Tổng cộng:</th>
                                    <th class="text-end"><?php echo e(number_format($total, 0, ',', '.')); ?>đ</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<?php if(Auth::user()->isApprover() && in_array($request->status, ['pending', 'forwarded'])): ?>
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('supply-requests.approve', $request->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Phê duyệt yêu cầu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn phê duyệt yêu cầu này?</p>
                    <div class="mb-3">
                        <label for="approval_notes" class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea name="approval_notes" id="approval_notes" class="form-control" rows="3" placeholder="Nhập ghi chú..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Phê duyệt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('supply-requests.reject', $request->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Từ chối yêu cầu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn từ chối yêu cầu này?</p>
                    <div class="mb-3">
                        <label for="reject_notes" class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea name="approval_notes" id="reject_notes" class="form-control" rows="3" placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.text-purple {
    color: #6f42c1 !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\supply-requests\show.blade.php ENDPATH**/ ?>