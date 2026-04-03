@extends('layouts.app')

@section('title', 'Quản lý Phiếu đăng ký')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản lý Phiếu đăng ký</h1>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('supply-requests.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Năm</label>
                    <select name="year" class="form-select">
                        <option value="2026" {{ request('year', '2026') == '2026' ? 'selected' : '' }}>2026</option>
                        <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                        <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kỳ</label>
                    <select name="period" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('period') == '1' ? 'selected' : '' }}>Tháng 1</option>
                        <option value="2" {{ request('period') == '2' ? 'selected' : '' }}>Tháng 2</option>
                        <option value="3" {{ request('period') == '3' ? 'selected' : '' }}>Tháng 3</option>
                        <option value="4" {{ request('period') == '4' ? 'selected' : '' }}>Tháng 4</option>
                        <option value="5" {{ request('period') == '5' ? 'selected' : '' }}>Tháng 5</option>
                        <option value="6" {{ request('period') == '6' ? 'selected' : '' }}>Tháng 6</option>
                        <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Tháng 7</option>
                        <option value="8" {{ request('period') == '8' ? 'selected' : '' }}>Tháng 8</option>
                        <option value="9" {{ request('period') == '9' ? 'selected' : '' }}>Tháng 9</option>
                        <option value="10" {{ request('period') == '10' ? 'selected' : '' }}>Tháng 10</option>
                        <option value="11" {{ request('period') == '11' ? 'selected' : '' }}>Tháng 11</option>
                        <option value="12" {{ request('period') == '12' ? 'selected' : '' }}>Tháng 12</option>
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
            @php
                $hasDraftRequests = $requests->where('status', 'draft')->count() > 0;
                $hasAnyRequests = $requests->count() > 0;
            @endphp
            @if(!$hasAnyRequests || $hasDraftRequests)
            <button type="button" class="btn btn-primary" onclick="createNewRequest()">
                <i class="fas fa-plus me-1"></i>Tạo mới
            </button>
            @endif
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
                        @forelse($requests as $index => $request)
                        <tr>
                            <td>{{ $requests->firstItem() + $index }}</td>
                            <td>
                                {{ $request->period_display }}
                            </td>
                            <td>{{ $request->requester_department }}</td>
                            <td>{{ $request->area ?? 'TCKT' }}</td>
                            <td>
                                @if($request->requestItems->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($request->requestItems->take(3) as $item)
                                    <li>• {{ $item->officeSupply->name }}</li>
                                    @endforeach
                                    @if($request->requestItems->count() > 3)
                                    <li class="text-muted">và {{ $request->requestItems->count() - 3 }} văn phòng phẩm khác</li>
                                    @endif
                                </ul>
                                @else
                                <span class="text-muted">Chưa có VPP</span>
                                @endif
                            </td>
                            <td>
                                @php
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
                                @endphp
                                <span class="badge {{ $config['class'] }}">
                                    {{ $config['text'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($request->status == 'draft')
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editRequest({{ $request->id }})" title="Sửa phiếu">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            onclick="viewRequest({{ $request->id }})" title="Theo dõi đơn đăng ký">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
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
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($requests->hasPages())
            <div class="d-flex justify-content-center">
                {{ $requests->appends(request()->query())->links() }}
            </div>
            @endif
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
                            <input type="text" class="form-control" value="{{ Auth::user()->department }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Người tạo:</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kỳ:</label>
                            <select name="period" class="form-select" required>
                                <option value="">Chọn kỳ</option>
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ngày:</label>
                            <input type="text" class="form-control" value="{{ date('d/m/Y') }}" readonly>
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
                            <input type="date" name="needed_date" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
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
        url: '{{ route("office-supplies.api.for-request") }}',
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
        url: isEditMode ? `/supply-requests/${formData.request_id}` : '{{ route("supply-requests.store") }}',
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
@endsection