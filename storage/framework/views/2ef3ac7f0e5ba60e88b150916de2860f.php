

<?php $__env->startSection('title', 'Quản lý nhân sự'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.filter-card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #e3e6f0;
}

.filter-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: 1px solid #e3e6f0;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
}

.table th {
    background-color: #f8f9fc;
    border-top: 1px solid #e3e6f0;
}

.badge {
    font-size: 0.75em;
}

.alert-info {
    background-color: #e7f3ff;
    border-color: #b8daff;
    color: #0c5460;
}

.results-summary {
    border-left: 4px solid #667eea;
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(102, 126, 234, 0.02) 100%);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users"></i> Quản lý nhân sự</h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('users.import.form')); ?>" class="btn btn-success">
                <i class="fas fa-file-import"></i> Import Excel
            </a>
            <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm nhân sự mới
            </a>
        </div>
    </div>

    <!-- cập nhật thông tin nhân sự -->
    <!-- <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?> -->

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="card mb-4 filter-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('users.index')); ?>" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="name" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo e(request('name')); ?>" placeholder="Tìm theo tên...">
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo e(request('email')); ?>" placeholder="Tìm theo email...">
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label">Phòng ban</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">-- Tất cả phòng ban --</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept); ?>" <?php echo e(request('department') == $dept ? 'selected' : ''); ?>>
                                    <?php echo e($dept); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="role" class="form-label">Vai trò</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">-- Tất cả vai trò --</option>
                            <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="approver" <?php echo e(request('role') == 'approver' ? 'selected' : ''); ?>>Phê duyệt</option>
                            <option value="tchc_checker" <?php echo e(request('role') == 'tchc_checker' ? 'selected' : ''); ?>>TCHC Kiểm tra</option>
                            <option value="tchc_manager" <?php echo e(request('role') == 'tchc_manager' ? 'selected' : ''); ?>>TCHC Quản lý</option>
                            <option value="employee" <?php echo e(request('role') == 'employee' ? 'selected' : ''); ?>>Nhân viên</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Hoạt động</option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Vô hiệu hóa</option>
                        </select>
                    </div>
                    <div class="col-md-9 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <?php if(request()->hasAny(['name', 'email', 'department', 'role', 'status'])): ?>
    <div class="alert alert-info results-summary">
        <i class="fas fa-info-circle"></i>
        Tìm thấy <strong><?php echo e($users->total()); ?></strong> nhân sự
        <?php if(request('name')): ?>
            với tên chứa "<strong><?php echo e(request('name')); ?></strong>"
        <?php endif; ?>
        <?php if(request('email')): ?>
            với email chứa "<strong><?php echo e(request('email')); ?></strong>"
        <?php endif; ?>
        <?php if(request('department')): ?>
            thuộc phòng ban "<strong><?php echo e(request('department')); ?></strong>"
        <?php endif; ?>
        <?php if(request('role')): ?>
            có vai trò "<strong><?php echo e(ucfirst(request('role'))); ?></strong>"
        <?php endif; ?>
        <?php if(request('status')): ?>
            trạng thái "<strong><?php echo e(request('status') == 'active' ? 'Hoạt động' : 'Vô hiệu hóa'); ?></strong>"
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Phòng ban</th>
                            <th>Chức vụ</th>
                            <th>Ca/kíp</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($user->id); ?></td>
                            <td>
                                <strong><?php echo e($user->name); ?></strong>
                                <?php if($user->id === auth()->id()): ?>
                                    <span class="badge bg-info ms-1">Bạn</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <?php switch($user->role):
                                    case ('admin'): ?>
                                        <span class="badge bg-danger">Admin</span>
                                        <?php break; ?>
                                    <?php case ('approver'): ?>
                                        <span class="badge bg-warning">Phê duyệt</span>
                                        <?php break; ?>
                                    <?php case ('tchc_checker'): ?>
                                        <span class="badge bg-info">TCHC Kiểm tra</span>
                                        <?php break; ?>
                                    <?php case ('tchc_manager'): ?>
                                        <span class="badge bg-primary">TCHC Quản lý</span>
                                        <?php break; ?>
                                    <?php case ('employee'): ?>
                                        <span class="badge bg-success">Nhân viên</span>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <span class="badge bg-secondary"><?php echo e($user->role); ?></span>
                                <?php endswitch; ?>
                            </td>
                            <td><?php echo e($user->department ?? '-'); ?></td>
                            <td><?php echo e($user->position ?? '-'); ?></td>
                            <td><?php echo e($user->phone ?? '-'); ?></td>
                            <td>
                                <?php if($user->is_active): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Vô hiệu hóa</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('users.show', $user->id)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if($user->id !== auth()->id()): ?>
                                        <button type="button" class="btn btn-sm <?php echo e($user->is_active ? 'btn-secondary' : 'btn-success'); ?>" 
                                                onclick="toggleStatus(<?php echo e($user->id); ?>, '<?php echo e($user->is_active ? 'vô hiệu hóa' : 'kích hoạt'); ?>')">
                                            <?php if($user->is_active): ?>
                                                <i class="fas fa-ban"></i>
                                            <?php else: ?>
                                                <i class="fas fa-check"></i>
                                            <?php endif; ?>
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center">Không có dữ liệu nhân sự</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa nhân sự <strong id="userName"></strong>?
                <br><small class="text-danger">Hành động này không thể hoàn tác!</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận thay đổi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn <strong id="actionText"></strong> tài khoản này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a id="toggleLink" href="#" class="btn btn-warning">Xác nhận</a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/users/' + userId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function toggleStatus(userId, action) {
    document.getElementById('actionText').textContent = action;
    document.getElementById('toggleLink').href = '/admin/users/' + userId + '/toggle-status';
    new bootstrap.Modal(document.getElementById('toggleModal')).show();
}

// Auto-submit form when filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input');
    
    filterInputs.forEach(input => {
        if (input.type === 'text' || input.type === 'email') {
            // For text inputs, submit after user stops typing (debounce)
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500); // Wait 500ms after user stops typing
            });
        } else {
            // For select elements, submit immediately
            input.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });

    // Highlight current filters
    const urlParams = new URLSearchParams(window.location.search);
    filterInputs.forEach(input => {
        if (input.value && input.value !== '') {
            input.style.borderColor = '#0d6efd';
            input.style.borderWidth = '2px';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\admin\users\index.blade.php ENDPATH**/ ?>