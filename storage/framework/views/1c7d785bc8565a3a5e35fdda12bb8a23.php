

<?php $__env->startSection('title', 'Quản lý STOP'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    
    .stop-page-header {
        margin-top: 20px;
        gap: 0.75rem;
    }

    .stop-table {
        min-width: 1200px;
    }

    @media (max-width: 991.98px) {
        .stop-page-header {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .stop-page-header .btn {
            width: 100%;
        }

        .stop-filter-actions .btn {
            width: 100% !important;
        }

        .stop-table {
            font-size: 0.85rem;
        }
    }
    .card-info .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: 1px solid #e3e6f0;
    }
    .card-header{
    background: linear-gradient(135deg, #9ba19a 0%, #ecebee 100%);
    color: white;
    border-bottom: 1px solid #e3e6f0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 stop-page-header">
        <h1 style="font-size: 1.5rem; font-weight: 500; color: #333;">
            <i class="fas fa-exclamation-triangle text-warning me-2" ></i>
            Quản lý STOP
        </h1>
        <a href="<?php echo e(route('stops.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Ghi nhận mới
        </a>
    </div>

    <!-- <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div> -->
    <?php endif; ?>

    <!-- Bộ lọc -->
    <div class="card mb-4 card-info" >
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('stops.index')); ?>" class="row g-3">
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="observer_name" class="form-control" value="<?php echo e(request('observer_name')); ?>" placeholder="Tên người ghi nhận">
                </div> 
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" value="<?php echo e(request('email')); ?>" placeholder="Email">
                </div>
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label">Ca/kíp</label>
                        <select name="shift" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="HTSX" <?php echo e(request('shift') == 'HTSX' ? 'selected' : ''); ?>>HTSX</option>
                            <option value="VH01" <?php echo e(request('shift') == 'VH01' ? 'selected' : ''); ?>>VH01</option>
                            <option value="VH02" <?php echo e(request('shift') == 'VH02' ? 'selected' : ''); ?>>VH02</option>
                            <option value="VH03" <?php echo e(request('shift') == 'VH03' ? 'selected' : ''); ?>>VH03</option>
                            <option value="VH04" <?php echo e(request('shift') == 'VH04' ? 'selected' : ''); ?>>VH04</option>
                        </select>
                </div>  
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label">Loại vấn đề</label>
                     <select name="issue_category" class="form-select">
                            <option value="">Tất cả</option>
                            <?php $__currentLoopData = \App\Models\Stop::getIssueCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('issue_category') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                </div>  
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="open" <?php echo e(request('status') == 'open' ? 'selected' : ''); ?>>Chưa xử lý</option>
                        <option value="in-progress" <?php echo e(request('status') == 'in-progress' ? 'selected' : ''); ?>>Đang xử lý</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Hoàn thành</option>
                    </select>
                </div>
                
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                </div>
                <div class="col-12 col-md-6 col-lg-3 col-xl-2 d-flex align-items-end stop-filter-actions">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-info d-block w-100">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách STOP -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Danh sách ghi nhận STOP
            </h5>
        </div>
        <div class="card-body">
            <!-- Thanh công cụ chấm điểm hàng loạt -->
            <?php if(Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTchcChecker() || Auth::user()->isTchcManager()): ?>
            <div class="alert alert-info d-none" id="bulkActionToolbar">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="selectedCount">0</span> STOP đã chọn
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <select id="bulkPrioritySelect" class="form-select w-auto">
                                <option value="">Chọn mức độ...</option>
                                <option value="0">Mức 0</option>
                                <option value="1">Mức 1</option>
                                <option value="2">Mức 2</option>
                                <option value="3">Mức 3</option>
                            </select>
                            <input type="text" id="bulkScoreNote" class="form-control" style="max-width: 280px;" placeholder="Ghi chú chấm điểm">
                            <button type="button" class="btn btn-primary" id="btnBulkUpdate">
                                <i class="fas fa-save me-1"></i>Cập nhật
                            </button>
                            <button type="button" class="btn btn-secondary" id="btnCancelBulk">
                                <i class="fas fa-times me-1"></i>Hủy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered stop-table">
                    <thead class="table-light">
                        <tr>
                            <?php if(Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTchcChecker() || Auth::user()->isTchcManager()): ?>
                            <th width="2%">
                                <input type="checkbox" id="selectAll" class="form-check-input" title="Chọn tất cả">
                            </th>
                            <?php endif; ?>
                            <!-- <th width="2%">#</th> -->
                            <th width="5%">Thời gian đăng ký</th>
                            <th width="12%">Người ghi nhận</th>
                            <!-- <th width="6%">Email</th> -->
                            <th width="5%">Ca/kíp</th>
                            <th width="15%">Loại vấn đề</th>
                            <th width="6%">Vị trí</th>
                            <th width="5%">Thiết bị</th>
                            <th width="20%">Nội dung</th>
                            <th width="20%">Đề xuất hành động</th>
                            <th width="9%" class="text-center">Mức độ</th>
                            <th width="10%">Trạng thái</th>
                            <th width="6%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $stops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <?php if(Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTchcChecker() || Auth::user()->isTchcManager()): ?>
                            <td>
                                <?php if($stop->status !== 'completed'): ?>
                                <input type="checkbox" class="form-check-input stop-checkbox" value="<?php echo e($stop->id); ?>" data-stop-id="<?php echo e($stop->id); ?>">
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                            <!-- <td><?php echo e($stops->firstItem() + $index); ?></td> -->
                            <td>
                                <?php echo e($stop->created_at->format('d/m/Y')); ?>

                                <br><small class="text-muted"><?php echo e($stop->created_at->format('H:i')); ?></small>
                            </td>
                            <td><?php echo e($stop->observer_name); ?></td>
                            <!-- <td><?php echo e(explode('@',$stop->user->email)[0]); ?></td> -->
                            <td><?php echo e($stop->observer_phone); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e($stop->getCategoryLabel()); ?></span>
                            </td>
                            <td><?php echo e(Str::limit($stop->location,10,'...')); ?></td>
                            <td><?php echo e(Str::limit($stop->equipment_name ?? '-',10,'...')); ?></td>
                            <td>
                                <small><?php echo e(Str::words($stop->issue_description, 10,'...')); ?></small>
                            </td>
                            <td>
                                <small><?php echo e(Str::words($stop->corrective_action, 10, '...')); ?></small>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <?php
                                        $canInlineScore = (Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTchcChecker() || Auth::user()->isTchcManager())
                                            && $stop->status !== 'completed';
                                    ?>

                                    <?php if($canInlineScore && $stop->priority_level === null): ?>
                                        <select class="form-select form-select-sm priority-inline-select"
                                                data-stop-id="<?php echo e($stop->id); ?>"
                                            style="min-width: 110px; max-width: 120px;">
                                            <option value="" <?php echo e($stop->priority_level === null ? 'selected' : ''); ?>>Chưa chấm</option>
                                            <option value="0" <?php echo e((string)$stop->priority_level === '0' ? 'selected' : ''); ?>>Mức 0</option>
                                            <option value="1" <?php echo e((string)$stop->priority_level === '1' ? 'selected' : ''); ?>>Mức 1</option>
                                            <option value="2" <?php echo e((string)$stop->priority_level === '2' ? 'selected' : ''); ?>>Mức 2</option>
                                            <option value="3" <?php echo e((string)$stop->priority_level === '3' ? 'selected' : ''); ?>>Mức 3</option>
                                        </select>
                                    <?php elseif($stop->priority_level !== null): ?>
                                        <span class="badge <?php echo e($stop->getPriorityBadgeClass()); ?>">
                                            <?php echo e($stop->getPriorityLabel()); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Chưa chấm</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="<?php echo e($stop->getStatusBadgeClass()); ?>">
                                    <?php echo e($stop->getStatusLabel()); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <?php
                                        $isPrivilegedUser = Auth::user()->isApprover() || Auth::user()->isTchcManager();
                                        $canEditOwnStop = $stop->canBeEditedByCreator(Auth::id(), 1);
                                        $isScoredByReviewer = $stop->isScoredByShiftLeaderOrSafetyOfficer();
                                        $canEditStop = $stop->status !== 'completed' && (
                                            $isPrivilegedUser || ($canEditOwnStop && !$isScoredByReviewer)
                                        );
                                    ?>

                                    <?php if(Auth::user()->isEmployee()): ?>
                                    <a href="<?php echo e(route('stops.show', $stop)); ?>" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if($canEditStop): ?>
                                        <a href="<?php echo e(route('stops.edit', $stop)); ?>" class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if(Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTCHCManager()): ?>
                                        <?php if($stop->status === 'completed'): ?>
                                            <!-- STOP đã hoàn thành - Chỉ xem -->
                                            <a href="<?php echo e(route('stops.show', $stop)); ?>" class="btn btn-sm btn-secondary" title="Xem chi tiết (Đã hoàn thành)">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php else: ?>
                                            <!-- STOP chưa hoàn thành -->
                                            <form action="<?php echo e(route('stops.destroy', $stop)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Chưa có ghi nhận STOP nào
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                <?php echo e($stops->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận chấm mức độ quan trọng -->
<div class="modal fade" id="priorityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>Xác nhận chấm mức độ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Chọn mức độ quan trọng:</label>
                    <select id="priorityLevelSelect" class="form-select">
                        <option value="">Chưa chấm</option>
                        <option value="0">Mức 0</option>
                        <option value="1">Mức 1</option>
                        <option value="2">Mức 2</option>
                        <option value="3">Mức 3</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ghi chú chấm điểm (không bắt buộc):</label>
                    <textarea id="priorityScoreNote" class="form-control" rows="3" placeholder="Có thể để trống hoặc nhập ghi chú cho lịch sử chấm điểm"></textarea>
                </div>
                <input type="hidden" id="currentStopId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-primary" id="btnSavePriority">
                    <i class="fas fa-check me-1"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Bắt sự kiện khi chọn Ca/kíp
document.querySelector('select[name="shift"]').addEventListener('change', function() {
    console.log('Ca/kíp được chọn:', this.value);
    
    // Auto submit form khi chọn Ca/kíp
    this.closest('form').submit();
});

// Khởi tạo modal
const priorityModalElement = document.getElementById('priorityModal');
const priorityModal = new bootstrap.Modal(priorityModalElement);
let currentStopId = null;
let inlinePendingSelect = null;

// Bắt sự kiện click nút Edit mức độ
document.querySelectorAll('.btn-edit-priority').forEach(function(btn) {
    btn.addEventListener('click', function() {
        currentStopId = this.dataset.stopId;
        const currentPriority = this.dataset.currentPriority;
        
        // Set giá trị hiện tại vào select
        document.getElementById('priorityLevelSelect').value = currentPriority;
        document.getElementById('currentStopId').value = currentStopId;
        
        // Hiển thị modal
        priorityModal.show();
    });
});

// Chấm mức độ trực tiếp bằng dropdown (Trưởng ca/CBAT/Admin)
document.querySelectorAll('.priority-inline-select').forEach(function(select) {
    select.addEventListener('change', function() {
        const stopId = this.dataset.stopId;
        const priorityLevel = this.value;
        const originalValue = this.dataset.originalValue ?? '';

        inlinePendingSelect = this;
        this.dataset.originalValue = originalValue;

        currentStopId = stopId;
        document.getElementById('currentStopId').value = stopId;
        document.getElementById('priorityLevelSelect').value = priorityLevel;
        document.getElementById('priorityScoreNote').value = '';

        priorityModal.show();
    });

    select.dataset.originalValue = select.value;
});

priorityModalElement.addEventListener('hidden.bs.modal', function() {
    if (inlinePendingSelect) {
        inlinePendingSelect.value = inlinePendingSelect.dataset.originalValue ?? '';
        inlinePendingSelect = null;
    }
});

// Bắt sự kiện click nút Lưu trong modal
document.getElementById('btnSavePriority').addEventListener('click', function() {
    const stopId = document.getElementById('currentStopId').value;
    const priorityLevel = document.getElementById('priorityLevelSelect').value;
    const scoreNote = document.getElementById('priorityScoreNote').value.trim();
    const btnSave = this;
    
    console.log('=== Saving Priority ===');
    console.log('Stop ID:', stopId);
    console.log('Priority Level:', priorityLevel);
    
    // Disable button while processing
    btnSave.disabled = true;
    btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang lưu...';

    if (inlinePendingSelect) {
        inlinePendingSelect.disabled = true;
    }
    
    fetch(`/stops/${stopId}/priority`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            priority_level: priorityLevel === '' ? null : parseInt(priorityLevel),
            score_note: scoreNote
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
                if (inlinePendingSelect) {
                    inlinePendingSelect.dataset.originalValue = priorityLevel;
                }

            // Đóng modal và reload trang
            priorityModal.hide();
            window.location.reload();
        } else {
            alert('Lỗi: ' + data.message);
            btnSave.disabled = false;
                btnSave.innerHTML = '<i class="fas fa-check me-1"></i>OK';
                if (inlinePendingSelect) {
                    inlinePendingSelect.disabled = false;
                }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra: ' + error.message);
        btnSave.disabled = false;
        btnSave.innerHTML = '<i class="fas fa-check me-1"></i>OK';
        if (inlinePendingSelect) {
            inlinePendingSelect.disabled = false;
        }
    });
});

// ============================================
// BULK UPDATE PRIORITY FUNCTIONALITY
// ============================================

// Hàm cập nhật số lượng đã chọn và hiển thị toolbar
function updateBulkToolbar() {
    const checkboxes = document.querySelectorAll('.stop-checkbox:checked');
    const count = checkboxes.length;
    const toolbar = document.getElementById('bulkActionToolbar');
    const selectedCountSpan = document.getElementById('selectedCount');
    
    if (count > 0) {
        toolbar.classList.remove('d-none');
        selectedCountSpan.textContent = count;
    } else {
        toolbar.classList.add('d-none');
        document.getElementById('bulkPrioritySelect').value = '';
    }
}

// Checkbox "Chọn tất cả"
const selectAllCheckbox = document.getElementById('selectAll');
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.stop-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateBulkToolbar();
    });
}

// Các checkbox riêng lẻ
document.querySelectorAll('.stop-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        updateBulkToolbar();
        
        // Cập nhật trạng thái checkbox "Chọn tất cả"
        const allCheckboxes = document.querySelectorAll('.stop-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.stop-checkbox:checked');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length && allCheckboxes.length > 0;
        }
    });
});

// Nút hủy bulk action
const btnCancelBulk = document.getElementById('btnCancelBulk');
if (btnCancelBulk) {
    btnCancelBulk.addEventListener('click', function() {
        // Bỏ chọn tất cả checkbox
        document.querySelectorAll('.stop-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
        }
        updateBulkToolbar();
    });
}

// Nút cập nhật hàng loạt
const btnBulkUpdate = document.getElementById('btnBulkUpdate');
if (btnBulkUpdate) {
    btnBulkUpdate.addEventListener('click', function() {
        const priorityLevel = document.getElementById('bulkPrioritySelect').value;
        const scoreNote = document.getElementById('bulkScoreNote').value;
        
        if (!priorityLevel) {
            alert('Vui lòng chọn mức độ trước khi cập nhật!');
            return;
        }
        
        // Lấy danh sách ID đã chọn
        const selectedIds = Array.from(document.querySelectorAll('.stop-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedIds.length === 0) {
            alert('Vui lòng chọn ít nhất một STOP!');
            return;
        }
        
        if (!confirm(`Bạn có chắc muốn cập nhật mức độ cho ${selectedIds.length} STOP?`)) {
            return;
        }
        
        // Disable button
        btnBulkUpdate.disabled = true;
        btnBulkUpdate.innerHTML = '<i class=\"fas fa-spinner fa-spin me-1\"></i>Đang cập nhật...';
        
        // Gửi request
        fetch('/stops/bulk-priority', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                stop_ids: selectedIds,
                priority_level: parseInt(priorityLevel),
                score_note: scoreNote
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Lỗi: ' + data.message);
                btnBulkUpdate.disabled = false;
                btnBulkUpdate.innerHTML = '<i class=\"fas fa-save me-1\"></i>Cập nhật';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra: ' + error.message);
            btnBulkUpdate.disabled = false;
            btnBulkUpdate.innerHTML = '<i class=\"fas fa-save me-1\"></i>Cập nhật';
        });
    });
}

</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views/stops/index.blade.php ENDPATH**/ ?>