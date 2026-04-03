

<?php $__env->startSection('title', 'TCHC Checker - Kiểm tra phiếu VPP'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 30px;">
        <div>
            <h2 class="mb-1">Kiểm tra phiếu văn phòng phẩm</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Văn phòng phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">TCHC Checker</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-clipboard-check me-2" style="color: #0d6efd;"></i>
                    Danh sách phiếu đăng ký văn phòng phẩm - Chờ TCHC kiểm tra
                </h6>
                <div>
                    <a href="<?php echo e(route('supply-requests.export', request()->all())); ?>" class="btn btn-success btn-sm me-2">
                        <i class="fas fa-file-excel me-1"></i>Export Tổng quan
                    </a>
                    <a href="<?php echo e(route('supply-requests.export-items', request()->all())); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-file-excel me-1"></i>Export Chi tiết VPP
                    </a>
                    <span class="badge bg-info fs-6 ms-2">
                        <i class="fas fa-list me-1"></i><?php echo e($requests->total()); ?> tổng phiếu
                    </span>
                </div>
            </div>
            
            <!-- Form tìm kiếm -->
            <form method="GET" action="<?php echo e(route('tchc.checker.dashboard')); ?>" class="row g-3">
                <div class="col-md-2">
                    <label for="year" class="form-label">Năm</label>
                    <select name="year" id="year" class="form-select">
                        <option value="">Tất cả</option>
                        <?php for($y = 2024; $y <= 2026; $y++): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="period" class="form-label">Tháng</label>
                    <select name="period" id="period" class="form-select">
                        <option value="">Tất cả</option>
                        <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php echo e(request('period') == $m ? 'selected' : ''); ?>>Tháng <?php echo e($m); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="department" class="form-label">Bộ phận</label>
                    <select name="department" id="department" class="form-select">
                        <option value="">Tất cả</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dept); ?>" <?php echo e(request('department') == $dept ? 'selected' : ''); ?>>
                            <?php echo e($dept); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="all" <?php echo e(request('status', 'all') == 'all' ? 'selected' : ''); ?>>Tất cả</option>
                        <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Chờ kiểm tra</option>
                        <option value="tchc_checked" <?php echo e(request('status') == 'tchc_checked' ? 'selected' : ''); ?>>Đã kiểm tra</option>
                        <option value="tchc_approved" <?php echo e(request('status') == 'tchc_approved' ? 'selected' : ''); ?>>Đã phê duyệt</option>
                        <option value="tchc_rejected" <?php echo e(request('status') == 'tchc_rejected' ? 'selected' : ''); ?>>Đã từ chối</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="area" class="form-label">Khu vực</label>
                    <select name="area" id="area" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="HCM" <?php echo e(request('area') == 'HCM' ? 'selected' : ''); ?>>TP.HCM</option>
                        <option value="HN" <?php echo e(request('area') == 'HN' ? 'selected' : ''); ?>>Hà Nội</option>
                        <option value="DN" <?php echo e(request('area') == 'DN' ? 'selected' : ''); ?>>Đà Nẵng</option>
                        <option value="TCKT" <?php echo e(request('area') == 'TCKT' ? 'selected' : ''); ?>>TCKT</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Tìm
                    </button>
                    <a href="<?php echo e(route('tchc.checker.dashboard')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php if($requests->count() == 0): ?>
            <div class="text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Không có phiếu nào</h5>
                <p class="text-muted">Không tìm thấy phiếu nào phù hợp với bộ lọc.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="8%">Mã phiếu</th>
                            <th width="15%">Người tạo</th>
                            <th width="12%">Bộ phận</th>
                            <th width="12%">Người duyệt</th>
                            <th width="10%">Ngày duyệt</th>
                            <th width="8%">Ưu tiên</th>
                            <th width="12%">Trạng thái</th>
                            <th width="13%">VPP yêu cầu</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo e($request->request_code ?? '#'.$request->id); ?></span>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($request->user->name); ?></div>
                                <small class="text-muted"><?php echo e($request->user->position); ?></small>
                            </td>
                            <td><?php echo e($request->requester_department); ?></td>
                            <td>
                                <div class="fw-bold"><?php echo e($request->approver->name); ?></div>
                                <small class="text-muted"><?php echo e($request->approver->department); ?></small>
                            </td>
                            <td>
                                <div><?php echo e($request->approved_at->format('d/m/Y')); ?></div>
                                <small class="text-muted"><?php echo e($request->approved_at->format('H:i')); ?></small>
                            </td>
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
                            <td>
                                <?php
                                $statusConfig = [
                                    'approved' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ kiểm tra'],
                                    'tchc_checked' => ['class' => 'bg-info', 'text' => 'Đã kiểm tra'],
                                    'tchc_approved' => ['class' => 'bg-success', 'text' => 'Đã phê duyệt'],
                                    'tchc_rejected' => ['class' => 'bg-danger', 'text' => 'Đã từ chối']
                                ];
                                $statusCfg = $statusConfig[$request->status] ?? $statusConfig['approved'];
                                ?>
                                <span class="badge <?php echo e($statusCfg['class']); ?>"><?php echo e($statusCfg['text']); ?></span>
                            </td>
                            <td>
                                <?php if($request->requestItems->count() > 0): ?>
                                <ul class="list-unstyled mb-0">
                                    <?php $__currentLoopData = $request->requestItems->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="small">• <?php echo e($item->officeSupply->name); ?> (<?php echo e($item->quantity); ?>)</li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($request->requestItems->count() > 2): ?>
                                    <li class="text-muted small">và <?php echo e($request->requestItems->count() - 2); ?> VPP khác</li>
                                    <?php endif; ?>
                                </ul>
                                <?php else: ?>
                                <span class="text-muted">Chưa có VPP</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('supply-requests.show', $request->id)); ?>" 
                                       class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($request->status === 'approved'): ?>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" data-bs-target="#checkModal<?php echo e($request->id); ?>" title="Check phiếu">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <?php if($request->status === 'approved'): ?>
                        <!-- Modal Check -->
                        <div class="modal fade" id="checkModal<?php echo e($request->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="<?php echo e(route('tchc.checker.check', $request->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Check phiếu <?php echo e($request->request_code ?? '#'.$request->id); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Xác nhận check phiếu này và chuyển tới TCHC Manager để phê duyệt cuối?
                                            </div>
                                            <div class="mb-3">
                                                <label for="tchc_check_notes<?php echo e($request->id); ?>" class="form-label">Ghi chú check (tùy chọn)</label>
                                                <textarea name="tchc_check_notes" id="tchc_check_notes<?php echo e($request->id); ?>" 
                                                          class="form-control" rows="3" placeholder="Nhập ghi chú về quá trình check..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i>Xác nhận check
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($requests->appends(request()->query())->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Auto refresh every 30 seconds
    setInterval(function() {
        if (!$('.modal.show').length) { // Only refresh if no modal is open
            location.reload();
        }
    }, 30000);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\tchc\checker\dashboard.blade.php ENDPATH**/ ?>