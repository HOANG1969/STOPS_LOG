

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-alt me-2"></i>Danh sách yêu cầu VPP</h2>
        <a href="<?php echo e(route('requests.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Tạo yêu cầu mới
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('requests.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Bản nháp</option>
                            <option value="submitted" <?php echo e(request('status') === 'submitted' ? 'selected' : ''); ?>>Đã gửi</option>
                            <option value="manager_approved" <?php echo e(request('status') === 'manager_approved' ? 'selected' : ''); ?>>Manager đã duyệt</option>
                            <option value="director_approved" <?php echo e(request('status') === 'director_approved' ? 'selected' : ''); ?>>Director đã duyệt</option>
                            <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Đã duyệt</option>
                            <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Từ chối</option>
                            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Hoàn thành</option>
                            <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" 
                               placeholder="Tìm theo mã hoặc tiêu đề yêu cầu...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('requests.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Xóa bộ lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card">
        <div class="card-body">
            <?php if($requests->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã yêu cầu</th>
                            <th>Tiêu đề</th>
                            <th>Người yêu cầu</th>
                            <th>Ngày tạo</th>
                            <th>Ngày cần</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($request->request_number); ?></strong>
                                <?php if($request->priority === 'urgent'): ?>
                                    <i class="fas fa-exclamation-triangle text-danger ms-1" title="Khẩn cấp"></i>
                                <?php elseif($request->priority === 'high'): ?>
                                    <i class="fas fa-arrow-up text-warning ms-1" title="Ưu tiên cao"></i>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(Str::limit($request->title, 30)); ?></td>
                            <td><?php echo e($request->user->name); ?></td>
                            <td><?php echo e($request->created_at->format('d/m/Y H:i')); ?></td>
                            <td><?php echo e($request->needed_date->format('d/m/Y')); ?></td>
                            <td><?php echo e($request->formatted_total_amount); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($request->status_color); ?> status-badge">
                                    <?php echo e($request->status_name); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('requests.show', $request)); ?>" class="btn btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if($request->isDraft() || ($request->isRejected() && $request->user_id === Auth::id())): ?>
                                    <a href="<?php echo e(route('requests.edit', $request)); ?>" class="btn btn-outline-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if($request->isDraft() && ($request->user_id === Auth::id() || Auth::user()->isAdmin())): ?>
                                    <form action="<?php echo e(route('requests.destroy', $request)); ?>" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Hiển thị <?php echo e($requests->firstItem() ?? 0); ?> đến <?php echo e($requests->lastItem() ?? 0); ?> 
                    trong tổng số <?php echo e($requests->total()); ?> yêu cầu
                </div>
                <?php echo e($requests->appends(request()->query())->links()); ?>

            </div>

            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt text-muted fa-3x mb-3"></i>
                <h5 class="text-muted">Không có yêu cầu nào</h5>
                <p class="text-muted">Bạn chưa có yêu cầu VPP nào hoặc không có kết quả phù hợp với bộ lọc.</p>
                <a href="<?php echo e(route('requests.create')); ?>" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Tạo yêu cầu đầu tiên
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\requests\index.blade.php ENDPATH**/ ?>