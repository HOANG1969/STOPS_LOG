

<?php $__env->startSection('title', 'Đăng ký văn phòng phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px; font-size: 20px; text-color: blue;">
        <h1>Đăng ký văn phòng phẩm</h1>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('supply-requests.index')); ?>" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Năm</label>
                    <select name="year" class="form-select">
                        <option value="2026" <?php echo e(request('year', '2026') == '2026' ? 'selected' : ''); ?>>2026</option>
                        <option value="2025" <?php echo e(request('year') == '2025' ? 'selected' : ''); ?>>2025</option>
                        <option value="2024" <?php echo e(request('year') == '2024' ? 'selected' : ''); ?>>2024</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kỳ</label>
                    <select name="period" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="1" <?php echo e(request('period') == '1' ? 'selected' : ''); ?>>Tháng 1</option>
                        <option value="2" <?php echo e(request('period') == '2' ? 'selected' : ''); ?>>Tháng 2</option>
                        <option value="3" <?php echo e(request('period') == '3' ? 'selected' : ''); ?>>Tháng 3</option>
                        <option value="4" <?php echo e(request('period') == '4' ? 'selected' : ''); ?>>Tháng 4</option>
                        <option value="5" <?php echo e(request('period') == '5' ? 'selected' : ''); ?>>Tháng 5</option>
                        <option value="6" <?php echo e(request('period') == '6' ? 'selected' : ''); ?>>Tháng 6</option>
                        <option value="7" <?php echo e(request('period') == '7' ? 'selected' : ''); ?>>Tháng 7</option>
                        <option value="8" <?php echo e(request('period') == '8' ? 'selected' : ''); ?>>Tháng 8</option>
                        <option value="9" <?php echo e(request('period') == '9' ? 'selected' : ''); ?>>Tháng 9</option>
                        <option value="10" <?php echo e(request('period') == '10' ? 'selected' : ''); ?>>Tháng 10</option>
                        <option value="11" <?php echo e(request('period') == '11' ? 'selected' : ''); ?>>Tháng 11</option>
                        <option value="12" <?php echo e(request('period') == '12' ? 'selected' : ''); ?>>Tháng 12</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-info d-block">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách phiếu đăng ký -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách Phiếu đăng ký</h5>
            <button type="button" class="btn btn-primary" onclick="createNewRequest()">
                <i class="fas fa-plus me-1"></i>Tạo mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="12%">Kỳ</th>
                            <th width="15%">Bộ phận</th>
                            <th width="12%">Khu vực</th>
                            <th width="35%">VPP</th>
                            <th width="12%">Trạng thái</th>
                            <th width="9%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($requests->firstItem() + $index); ?></td>
                            <td>
                                <?php echo e($request->period_display); ?>

                            </td>
                            <td><?php echo e($request->requester_department); ?></td>
                            <td><?php echo e($request->area ?? 'TCKT'); ?></td>
                            <td>
                                <?php if($request->requestItems->count() > 0): ?>
                                <ul class="list-unstyled mb-0">
                                    <?php $__currentLoopData = $request->requestItems->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>• <?php echo e($item->officeSupply->name); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($request->requestItems->count() > 3): ?>
                                    <li class="text-muted">và <?php echo e($request->requestItems->count() - 3); ?> văn phòng phẩm khác</li>
                                    <?php endif; ?>
                                </ul>
                                <?php else: ?>
                                <span class="text-muted">Chưa có VPP</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusConfig = [
                                    'draft' => ['class' => 'bg-secondary', 'text' => 'Bản nháp'],
                                    'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ Trưởng bộ duyệt'],
                                    'approved' => ['class' => 'bg-success', 'text' => 'Trưởng bộ phận phê duyệt'],
                                    'tchc_checked' => ['class' => 'bg-info', 'text' => 'Nhân sự TCHC đã kiểm tra'],
                                    'tchc_approved' => ['class' => 'bg-success', 'text' => 'Lãnh đạo TCHC phê duyệt'],
                                    'tchc_rejected' => ['class' => 'bg-danger', 'text' => 'TCHC từ chối'],
                                    'rejected' => ['class' => 'bg-danger', 'text' => 'Từ chối'],
                                    'completed' => ['class' => 'bg-dark', 'text' => 'Hoàn thành']
                                ];
                                $config = $statusConfig[$request->status] ?? $statusConfig['draft'];
                                ?>
                                <span class="badge <?php echo e($config['class']); ?>">
                                    <?php echo e($config['text']); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <?php if($request->status == 'draft'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editRequest(<?php echo e($request->id); ?>)" title="Sửa phiếu">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php elseif(in_array($request->status, ['rejected', 'tchc_rejected'])): ?>
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            onclick="viewRequest(<?php echo e($request->id); ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editRequest(<?php echo e($request->id); ?>)" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            onclick="resubmitRequestFromTable(<?php echo e($request->id); ?>)" title="Gửi lại">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteRequestFromTable(<?php echo e($request->id); ?>)" title="Hủy phiếu">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            onclick="viewRequest(<?php echo e($request->id); ?>)" title="Theo dõi đơn đăng ký">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <div>Chưa có phiếu đăng ký nào</div>
                                <small class="text-muted">Tạo phiếu đăng ký văn phòng phẩm đầu tiên</small>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" onclick="createNewRequest()">
                                        <i class="fas fa-plus me-1"></i>Tạo phiếu đăng ký
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($requests->hasPages()): ?>
            <div class="d-flex justify-content-center">
                <?php echo e($requests->appends(request()->query())->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Tạo/Sửa phiếu đăng ký -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo Phiếu đăng ký văn phòng phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="requestForm">
                    <input type="hidden" id="request_id" name="request_id" value="">
                    
                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Bộ phận:</label>
                            <input type="text" class="form-control" value="<?php echo e(Auth::user()->department); ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Người tạo:</label>
                            <input type="text" class="form-control" value="<?php echo e(Auth::user()->name); ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kỳ:</label>
                            <select name="period" class="form-select" required>
                                <option value="">Chọn kỳ</option>
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e(date('n') == $i ? 'selected' : ''); ?>>Tháng <?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ngày:</label>
                            <input type="text" class="form-control" value="<?php echo e(date('d/m/Y')); ?>" readonly>
                        </div>
                    </div>

                    <!-- Mức độ ưu tiên -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Mức độ ưu tiên: <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select" required>
                                <option value="low">Thấp</option>
                                <option value="normal" selected>Bình thường</option>
                                <option value="high">Cao</option>
                                <option value="urgent">Khẩn cấp</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày cần sử dụng:</label>
                            <input type="date" name="needed_date" class="form-control" value="<?php echo e(date('Y-m-d', strtotime('+7 days'))); ?>">
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="mb-4">
                        <label class="form-label">Ghi chú:</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Nhập ghi chú (nếu có)"></textarea>
                    </div>

                    <!-- Danh sách văn phòng phẩm -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Danh sách văn phòng phẩm</h5>
                        <button type="button" class="btn btn-success" onclick="showSupplySelector()">
                            <i class="fas fa-plus me-1"></i>Thêm VPP
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="selected-supplies-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">Tên VPP</th>
                                    <th width="15%">Số lượng</th>
                                    <th width="35%">Mục đích</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="empty-row">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <div>Chưa có văn phòng phẩm nào được chọn</div>
                                        <small>Click "Thêm VPP" để thêm văn phòng phẩm vào danh sách</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-warning" onclick="saveDraft()">
                    <i class="fas fa-save me-1"></i>Lưu
                </button>
                <button type="button" class="btn btn-primary" onclick="submitForApproval()">
                    <i class="fas fa-paper-plane me-1"></i>Chuyển phê duyệt
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal chọn văn phòng phẩm -->
<div class="modal fade" id="supplyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn văn phòng phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="supply-filter" class="form-control" placeholder="Tìm kiếm văn phòng phẩm...">
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover" id="supply-list-table">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Tên VPP</th>
                                <th width="15%">Danh mục</th>
                                <th width="10%">ĐVT</th>
                                <th width="15%">Tồn kho</th>
                                <th width="20%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data sẽ được load bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedSupplies = [];
let allSupplies = [];
let isEditMode = false;

$(document).ready(function() {
    console.log('Document ready - loading office supplies...');
    loadOfficeSuppliesForRequest();
    
    // Search filter trong modal
    $('#supply-filter').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterSuppliesInModal(searchTerm);
    });
});

function createNewRequest() {
    console.log('Creating new request...');
    isEditMode = false;
    $('#request_id').val('');
    $('#requestForm')[0].reset();
    selectedSupplies = [];
    renderSelectedSupplies();
    $('.modal-title').text('Tạo Phiếu đăng ký văn phòng phẩm');
    $('#requestModal').modal('show');
}

function editRequest(requestId) {
    console.log('Editing request:', requestId);
    isEditMode = true;
    $('#request_id').val(requestId);
    $('.modal-title').text('Sửa Phiếu đăng ký văn phòng phẩm');
    
    // Load dữ liệu request để edit
    $.ajax({
        url: `/supply-requests/${requestId}`,
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(request) {
            console.log('Loaded request data:', request);
            
            // Fill form data
            $('[name="period"]').val(request.period || '');
            $('[name="priority"]').val(request.priority || 'normal');
            $('[name="needed_date"]').val(request.needed_date || '');
            $('[name="notes"]').val(request.notes || '');
            
            // Load request items
            selectedSupplies = request.request_items.map(item => ({
                id: item.office_supply.id,
                name: item.office_supply.name,
                description: item.office_supply.description,
                unit: item.office_supply.unit,
                price: item.office_supply.price,
                stock_quantity: item.office_supply.stock_quantity,
                quantity: item.quantity,
                purpose: item.purpose
            }));
            
            renderSelectedSupplies();
            $('#requestModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error loading request:', xhr);
            showAlert('danger', 'Không thể tải thông tin phiếu đăng ký!');
        }
    });
}

function viewRequest(requestId) {
    window.location.href = `/supply-requests/${requestId}`;
}

function showSupplySelector() {
    console.log('Show supply selector...');
    loadSuppliesIntoModal();
    $('#supplyModal').modal('show');
}

function loadSuppliesIntoModal() {
    const tbody = $('#supply-list-table tbody');
    tbody.html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Đang tải...</td></tr>');
    
    if (allSupplies.length === 0) {
        loadOfficeSuppliesForRequest(() => {
            renderSuppliesInModal();
        });
    } else {
        renderSuppliesInModal();
    }
}

function renderSuppliesInModal() {
    const tbody = $('#supply-list-table tbody');
    tbody.empty();

    if (allSupplies.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <div>Không có văn phòng phẩm nào khả dụng</div>
                </td>
            </tr>
        `);
        return;
    }

    allSupplies.forEach(function(supply, index) {
        if (!supply.is_active || supply.stock_quantity <= 0) return;
        
        const isAlreadySelected = selectedSupplies.find(s => s.id == supply.id);
        const stockClass = supply.stock_quantity <= 10 ? 'text-danger' : 'text-success';
        const stockIcon = supply.stock_quantity <= 10 ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
        
        const row = `
            <tr data-supply-id="${supply.id}">
                <td class="text-center">${index + 1}</td>
                <td>
                    <div class="fw-bold">${supply.name}</div>
                    <small class="text-muted">${supply.description || 'Không có mô tả'}</small>
                </td>
                <td><span class="badge bg-info">${supply.category}</span></td>
                <td class="text-center"><span class="badge bg-light text-dark">${supply.unit}</span></td>
                <td class="text-center">
                    <span class="${stockClass}">
                        <i class="${stockIcon} me-1"></i>${supply.stock_quantity}
                    </span>
                </td>
                <td class="text-center">
                    <button type="button" 
                            class="btn ${isAlreadySelected ? 'btn-secondary' : 'btn-success'} btn-sm" 
                            onclick="selectSupply(${supply.id})"
                            ${isAlreadySelected ? 'disabled' : ''}>
                        <i class="fas ${isAlreadySelected ? 'fa-check' : 'fa-plus'} me-1"></i>
                        ${isAlreadySelected ? 'Đã chọn' : 'Chọn'}
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function filterSuppliesInModal(searchTerm) {
    $('#supply-list-table tbody tr').each(function() {
        const row = $(this);
        const text = row.text().toLowerCase();
        
        if (text.includes(searchTerm) || searchTerm === '') {
            row.show();
        } else {
            row.hide();
        }
    });
}

function selectSupply(supplyId) {
    console.log('Selecting supply:', supplyId);
    addSupplyToList(supplyId);
    $('#supplyModal').modal('hide');
    renderSuppliesInModal();
}

function addSupplyToList(supplyId) {
    const supply = allSupplies.find(s => s.id == supplyId);
    if (!supply) return;
    
    if (selectedSupplies.find(s => s.id == supplyId)) {
        showAlert('warning', 'Văn phòng phẩm này đã được thêm vào danh sách!');
        return;
    }
    
    selectedSupplies.push({
        id: supply.id,
        name: supply.name,
        description: supply.description,
        unit: supply.unit,
        price: supply.price,
        stock_quantity: supply.stock_quantity,
        quantity: 1,
        purpose: ''
    });
    
    renderSelectedSupplies();
}

function renderSelectedSupplies() {
    const tbody = $('#selected-supplies-table tbody');
    tbody.empty();

    if (selectedSupplies.length === 0) {
        tbody.append(`
            <tr id="empty-row">
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <div>Chưa có văn phòng phẩm nào được chọn</div>
                    <small>Click "Thêm VPP" để thêm văn phòng phẩm vào danh sách</small>
                </td>
            </tr>
        `);
        return;
    }

    selectedSupplies.forEach(function(item, index) {
        const row = `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>
                    <div class="fw-bold">${item.name}</div>
                    <small class="text-muted">${item.description || 'Không có mô tả'}</small>
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm" 
                           value="${item.quantity}" min="1" max="${item.stock_quantity}"
                           onchange="updateQuantity(${index}, this.value)">
                    <small class="text-muted">Max: ${item.stock_quantity}</small>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" 
                           value="${item.purpose}" 
                           placeholder="Nhập mục đích sử dụng"
                           onchange="updatePurpose(${index}, this.value)">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeSupply(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function updateQuantity(index, quantity) {
    if (selectedSupplies[index]) {
        selectedSupplies[index].quantity = parseInt(quantity) || 1;
        console.log('Updated quantity for item', index, ':', quantity);
    }
}

function updatePurpose(index, purpose) {
    if (selectedSupplies[index]) {
        selectedSupplies[index].purpose = purpose;
        console.log('Updated purpose for item', index, ':', purpose);
    }
}

function removeSupply(index) {
    selectedSupplies.splice(index, 1);
    renderSelectedSupplies();
    renderSuppliesInModal(); // Update modal để hiển thị lại nút "Chọn"
}

function loadOfficeSuppliesForRequest(callback = null) {
    console.log('Loading office supplies from API...');
    
    $.ajax({
        url: '<?php echo e(route("office-supplies.api.for-request", [], false)); ?>',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(supplies) {
            allSupplies = supplies;
            console.log('Loaded supplies successfully:', supplies.length, 'items');
            
            if (callback) {
                callback();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            
            showAlert('danger', 'Không thể tải danh sách văn phòng phẩm. Vui lòng thử lại sau.');
        }
    });
}

function saveDraft() {
    console.log('Saving draft...');
    submitRequest('draft');
}

function submitForApproval() {
    console.log('Submitting for approval...');
    submitRequest('pending');
}

function submitRequest(status = 'pending') {
    console.log('Submit request called with status:', status);
    console.log('Selected supplies:', selectedSupplies);
    
    if (selectedSupplies.length === 0) {
        showAlert('warning', 'Vui lòng chọn ít nhất một văn phòng phẩm!');
        return;
    }

    const invalidItems = selectedSupplies.filter(item => !item.purpose || !item.purpose.trim());
    if (invalidItems.length > 0 && status === 'pending') {
        showAlert('warning', 'Vui lòng nhập mục đích sử dụng cho tất cả văn phòng phẩm!');
        return;
    }

    const items = selectedSupplies.map(item => ({
        supply_id: item.id,
        quantity: parseInt(item.quantity) || 1,
        purpose: (item.purpose && item.purpose.trim()) ? item.purpose.trim() : 'Chưa xác định'
    }));

    const formData = {
        items: items,
        priority: $('[name="priority"]').val(),
        notes: $('[name="notes"]').val(),
        needed_date: $('[name="needed_date"]').val(),
        period: $('[name="period"]').val(),
        status: status,
        _token: $('meta[name="csrf-token"]').attr('content')
    };
    
    // Nếu là edit mode, thêm request_id
    if (isEditMode) {
        formData.request_id = $('#request_id').val();
    }
    
    console.log('Form data to submit:', formData);

    const submitText = status === 'draft' ? 'Đang lưu phiếu...' : 'Đang gửi phê duyệt...';
    const button = status === 'draft' ? $('button[onclick="saveDraft()"]') : $('button[onclick="submitForApproval()"]');
    const originalText = button.html();
    
    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>' + submitText);

    $.ajax({
        url: isEditMode ? `/supply-requests/${formData.request_id}` : '<?php echo e(route("supply-requests.store", [], false)); ?>',
        method: isEditMode ? 'PUT' : 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            console.log('Submit response:', response);
            
            if (response.success) {
                const action = isEditMode ? 'cập nhật' : 'tạo';
                const message = status === 'draft' ? `Đã ${action} và lưu phiếu thành công!` : `Đã ${action} và gửi phê duyệt thành công!`;
                showAlert('success', message);
                
                $('#requestModal').modal('hide');
                
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showAlert('danger', 'Có lỗi: ' + (response.message || 'Không xác định'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Submit Error:', xhr);
            
            let message = 'Có lỗi xảy ra!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                message = errors.join(', ');
            }
            showAlert('danger', message);
        },
        complete: function() {
            button.prop('disabled', false).html(originalText);
        }
    });
}

function resubmitRequestFromTable(requestId) {
    if (!confirm('Bạn có chắc muốn gửi lại đơn yêu cầu này? Đơn sẽ được reset về trạng thái chờ phê duyệt.')) {
        return;
    }
    
    fetch(`/supply-requests/${requestId}/resubmit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('danger', 'Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Có lỗi xảy ra khi gửi lại đơn');
    });
}

function deleteRequestFromTable(requestId) {
    if (!confirm('Bạn có chắc chắn muốn hủy phiếu này? Hành động này không thể hoàn tác!')) {
        return;
    }
    
    fetch(`/supply-requests/${requestId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('danger', 'Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Có lỗi xảy ra khi hủy phiếu');
    });
}

function showAlert(type, message) {
    $('.alert').remove();
    
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $('.container-fluid').prepend(alertHtml);
    
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
    
    $('html, body').animate({ scrollTop: 0 }, 300);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\supply-requests\index.blade.php ENDPATH**/ ?>