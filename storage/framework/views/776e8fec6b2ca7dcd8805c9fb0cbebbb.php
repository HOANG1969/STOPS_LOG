

<?php $__env->startSection('title', 'Tạo Phiếu đăng ký văn phòng phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6">
    <form action="<?php echo e(route('employee.requests.store')); ?>" method="POST" id="requestForm">
        <?php echo csrf_field(); ?>
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-plus-circle mr-2 text-blue-500"></i>
                    Tạo mới Phiếu đăng ký
                </h4>
            </div>
            
            <div class="p-6">
                <!-- Thông tin chung -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <span class="font-medium text-gray-700">Bộ phận:</span> 
                        <span class="text-gray-600"><?php echo e(auth()->user()->department ?? 'N/A'); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Kỳ:</span> 
                        <span class="text-gray-600"><?php echo e(now()->format('F Y')); ?></span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Người tạo:</span> 
                        <span class="text-gray-600"><?php echo e(auth()->user()->name); ?></span>
                    </div>
                </div>

                <!-- Flash messages -->
                <?php if($errors->any()): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Form thông tin yêu cầu -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mức độ ưu tiên <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Chọn mức độ ưu tiên</option>
                            <option value="Normal" <?php echo e(old('priority') === 'Normal' ? 'selected' : ''); ?>>Bình thường</option>
                            <option value="High" <?php echo e(old('priority') === 'High' ? 'selected' : ''); ?>>Cao</option>
                            <option value="Urgent" <?php echo e(old('priority') === 'Urgent' ? 'selected' : ''); ?>>Khẩn cấp</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày cần sử dụng <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="needed_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="<?php echo e(old('needed_date', now()->addDay()->format('Y-m-d'))); ?>" 
                               min="<?php echo e(today()->format('Y-m-d')); ?>"
                               required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              rows="3" placeholder="Nhập ghi chú (nếu có)"><?php echo e(old('notes')); ?></textarea>
                </div>

                <!-- Danh sách văn phòng phẩm -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-lg font-semibold text-gray-800">Danh sách văn phòng phẩm yêu cầu</h5>
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm" onclick="addSupplyRow()">
                            <i class="fas fa-plus mr-1"></i>
                            Thêm VPP
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200" id="supplyTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">#</th>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Tên VPP <span class="text-red-500">*</span></th>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Quy cách</th>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">ĐVT</th>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Tồn kho</th>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Số lượng <span class="text-red-500">*</span></th>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Mục đích <span class="text-red-500">*</span></th>
                                    <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="supplyTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Rows sẽ được thêm bằng JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="flex justify-between">
                    <a href="<?php echo e(route('employee.requests.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Quay lại
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-save mr-1"></i>
                        Lưu lại
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal chọn văn phòng phẩm -->
<div id="selectSupplyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-96">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800">Chọn văn phòng phẩm</h5>
                <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="searchSupply" class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập tên văn phòng phẩm để tìm kiếm...">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <button type="button" id="clearSearch" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 hidden" onclick="clearSearchInput()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="overflow-y-auto max-h-64">
                    <div id="noResults" class="text-center text-gray-500 py-8 hidden">
                        <i class="fas fa-search text-3xl mb-2"></i>
                        <p>Không tìm thấy văn phòng phẩm nào</p>
                    </div>
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Tên VPP</th>
                                <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Quy cách</th>
                                <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">ĐVT</th>
                                <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Tồn kho</th>
                                <th class="px-4 py-2 border border-gray-200 text-left text-sm font-medium text-gray-700">Chọn</th>
                            </tr>
                        </thead>
                        <tbody id="supplyListBody">
                            <?php $__currentLoopData = $officeSupplies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-supply-id="<?php echo e($supply->id); ?>" class="hover:bg-gray-50">
                                <td class="px-4 py-2 border border-gray-200 text-sm"><?php echo e($supply->name); ?></td>
                                <td class="px-4 py-2 border border-gray-200 text-sm"><?php echo e($supply->specification ?? '-'); ?></td>
                                <td class="px-4 py-2 border border-gray-200 text-sm"><?php echo e($supply->unit); ?></td>
                                <td class="px-4 py-2 border border-gray-200 text-sm"><?php echo e($supply->stock_quantity); ?></td>
                                <td class="px-4 py-2 border border-gray-200 text-sm">
                                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm select-supply"
                                            data-supply-id="<?php echo e($supply->id); ?>"
                                            data-supply-name="<?php echo e($supply->name); ?>"
                                            data-supply-specification="<?php echo e($supply->specification); ?>"
                                            data-supply-unit="<?php echo e($supply->unit); ?>"
                                            data-supply-stock="<?php echo e($supply->stock_quantity); ?>">
                                        Chọn
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let rowIndex = 0;

function addSupplyRow() {
    document.getElementById('selectSupplyModal').classList.remove('hidden');
    // Focus vào search input khi modal mở
    setTimeout(() => {
        document.getElementById('searchSupply').focus();
    }, 100);
}

function closeModal() {
    document.getElementById('selectSupplyModal').classList.add('hidden');
    // Clear search khi đóng modal
    clearSearchInput();
}

// Chọn văn phòng phẩm từ modal
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('select-supply')) {
        const button = e.target;
        const supplyId = button.dataset.supplyId;
        const supplyName = button.dataset.supplyName;
        const specification = button.dataset.supplySpecification || '-';
        const unit = button.dataset.supplyUnit;
        const stock = button.dataset.supplyStock;

        // Kiểm tra xem văn phòng phẩm đã được chọn chưa
        const existingRow = document.querySelector(`#supplyTableBody tr[data-supply-id="${supplyId}"]`);
        if (existingRow) {
            alert('Văn phòng phẩm này đã được chọn!');
            return;
        }

        const newRow = `
            <tr data-supply-id="${supplyId}" class="bg-white">
                <td class="px-4 py-2 border border-gray-200 text-sm">${rowIndex + 1}</td>
                <td class="px-4 py-2 border border-gray-200 text-sm">
                    ${supplyName}
                    <input type="hidden" name="items[${rowIndex}][supply_id]" value="${supplyId}">
                </td>
                <td class="px-4 py-2 border border-gray-200 text-sm">${specification}</td>
                <td class="px-4 py-2 border border-gray-200 text-sm">${unit}</td>
                <td class="px-4 py-2 border border-gray-200 text-sm">${stock}</td>
                <td class="px-4 py-2 border border-gray-200 text-sm">
                    <input type="number" name="items[${rowIndex}][quantity]" 
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           min="1" max="${stock}" required>
                </td>
                <td class="px-4 py-2 border border-gray-200 text-sm">
                    <input type="text" name="items[${rowIndex}][purpose]" 
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           placeholder="Mục đích sử dụng" required>
                </td>
                <td class="px-4 py-2 border border-gray-200 text-sm">
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm" onclick="removeSupplyRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        document.getElementById('supplyTableBody').insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
        
        closeModal();
        updateRowNumbers();
    }
});

function removeSupplyRow(btn) {
    btn.closest('tr').remove();
    updateRowNumbers();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#supplyTableBody tr');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

// Tìm kiếm văn phòng phẩm với cải tiến
document.getElementById('searchSupply').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#supplyListBody tr');
    const clearBtn = document.getElementById('clearSearch');
    const noResults = document.getElementById('noResults');
    let visibleCount = 0;

    // Hiển thị/ẩn nút clear
    clearBtn.classList.toggle('hidden', searchTerm === '');

    rows.forEach(row => {
        const supplyName = row.querySelector('td:first-child').textContent.toLowerCase();
        const specification = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        // Tìm kiếm trong tên và quy cách
        const isMatch = supplyName.includes(searchTerm) || specification.includes(searchTerm);
        
        if (isMatch) {
            row.style.display = '';
            visibleCount++;
            
            // Highlight từ khóa tìm kiếm
            highlightSearchTerm(row, searchTerm);
        } else {
            row.style.display = 'none';
        }
    });

    // Hiển thị thông báo không tìm thấy
    noResults.classList.toggle('hidden', visibleCount > 0 || searchTerm === '');
});

// Clear search input
function clearSearchInput() {
    const searchInput = document.getElementById('searchSupply');
    searchInput.value = '';
    searchInput.dispatchEvent(new Event('keyup'));
    searchInput.focus();
}

// Highlight từ khóa tìm kiếm
function highlightSearchTerm(row, searchTerm) {
    if (searchTerm === '') return;
    
    const cells = row.querySelectorAll('td:nth-child(1), td:nth-child(2)');
    cells.forEach(cell => {
        const originalText = cell.dataset.originalText || cell.textContent;
        cell.dataset.originalText = originalText;
        
        if (searchTerm.length > 0) {
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            cell.innerHTML = originalText.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
        } else {
            cell.textContent = originalText;
        }
    });
}

// Validation form
document.getElementById('requestForm').addEventListener('submit', function(e) {
    const rowCount = document.querySelectorAll('#supplyTableBody tr').length;
    if (rowCount === 0) {
        e.preventDefault();
        alert('Vui lòng thêm ít nhất một văn phòng phẩm!');
        return false;
    }
});

// Close modal khi click outside hoặc nhấn ESC
document.getElementById('selectSupplyModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('selectSupplyModal');
    const isModalOpen = !modal.classList.contains('hidden');
    
    if (isModalOpen) {
        // ESC để đóng modal
        if (e.key === 'Escape') {
            closeModal();
        }
        // Enter để chọn item đầu tiên visible
        else if (e.key === 'Enter' && e.target.id === 'searchSupply') {
            e.preventDefault();
            const firstVisibleBtn = document.querySelector('#supplyListBody tr[style=""] .select-supply, #supplyListBody tr:not([style*="none"]) .select-supply');
            if (firstVisibleBtn) {
                firstVisibleBtn.click();
            }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\employee\requests\create.blade.php ENDPATH**/ ?>