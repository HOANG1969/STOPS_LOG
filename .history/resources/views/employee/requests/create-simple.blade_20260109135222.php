@extends('layouts.app')

@section('title', 'Tạo Phiếu đăng ký văn phòng phẩm')

@section('content')
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-xl font-semibold mb-6">Tạo Phiếu đăng ký văn phòng phẩm</h1>
        
        <form action="{{ route('employee.requests.store') }}" method="POST" id="requestForm">
            @csrf
            
            <!-- Thông tin cơ bản -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded">
                <div>
                    <span class="font-medium text-gray-700">Bộ phận:</span> 
                    <span class="text-gray-600">{{ auth()->user()->department ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Người tạo:</span> 
                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Ngày:</span> 
                    <span class="text-gray-600">{{ now()->format('d/m/Y') }}</span>
                </div>
            </div>

            <!-- Form fields -->
            <div class="grid grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mức độ ưu tiên <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        <option value="">Chọn mức độ ưu tiên</option>
                        <option value="Normal">Bình thường</option>
                        <option value="High">Cao</option>
                        <option value="Urgent">Khẩn cấp</option>
                    </select>
                </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                          rows="3" placeholder="Nhập ghi chú (nếu có)"></textarea>
            </div>

            <!-- Danh sách văn phòng phẩm -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Danh sách văn phòng phẩm</h3>
                    <button type="button" id="addSupplyBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                        Thêm VPP
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200" id="supplyTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border text-left">#</th>
                                <th class="px-4 py-2 border text-left">Tên VPP</th>
                                <th class="px-4 py-2 border text-left">Đơn vị tính</th>
                                <th class="px-4 py-2 border text-left">Số lượng</th>
                                <th class="px-4 py-2 border text-left">Ghi chú</th>
                                <th class="px-4 py-2 border text-left">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="supplyTableBody">
                            <!-- Rows will be added here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between">
                <a href="{{ route('employee.requests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Quay lại
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Lưu phiếu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal chọn VPP đơn giản -->
<div id="selectSupplyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b">
                <h5 class="text-lg font-semibold">Chọn văn phòng phẩm</h5>
            </div>
            <div class="px-6 py-3 border-b">
                <div class="relative">
                    <input type="text" id="searchSupply" class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập tên văn phòng phẩm để tìm kiếm...">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    <button type="button" id="clearSearch" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 hidden" onclick="clearSearchInput()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                <div id="noResults" class="text-center text-gray-500 py-8 hidden">
                    <i class="fas fa-search text-3xl mb-2"></i>
                    <p>Không tìm thấy văn phòng phẩm nào</p>
                </div>
                <div id="supplyList">
                @foreach($officeSupplies as $supply)
                <div class="supply-item flex justify-between items-center p-2 border-b hover:bg-gray-50" data-name="{{ strtolower($supply->name) }}">
                    <div>
                        <div class="font-medium supply-name">{{ $supply->name }}</div>
                        <div class="text-sm text-gray-500 supply-specs">{{ $supply->unit }} - Tồn kho: {{ $supply->stock_quantity }}</div>
                    </div>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm select-supply"
                            data-id="{{ $supply->id }}"
                            data-name="{{ $supply->name }}"
                            data-unit="{{ $supply->unit }}"
                            data-stock="{{ $supply->stock_quantity }}">
                        Chọn
                    </button>
                </div>
                @endforeach
                </div>
            </div>
            <div class="px-6 py-4 border-t">
                <button type="button" id="closeModalBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let rowIndex = 0;

document.getElementById('addSupplyBtn').addEventListener('click', function() {
    document.getElementById('selectSupplyModal').classList.remove('hidden');
});

document.getElementById('closeModalBtn').addEventListener('click', function() {
    document.getElementById('selectSupplyModal').classList.add('hidden');
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('select-supply')) {
        const btn = e.target;
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const unit = btn.dataset.unit;
        const stock = btn.dataset.stock;

        // Check if already added
        if (document.querySelector(`tr[data-supply-id="${id}"]`)) {
            alert('Văn phòng phẩm này đã được chọn!');
            return;
        }

        const newRow = `
            <tr data-supply-id="${id}">
                <td class="px-4 py-2 border">${rowIndex + 1}</td>
                <td class="px-4 py-2 border">
                    ${name}
                    <input type="hidden" name="items[${rowIndex}][supply_id]" value="${id}">
                </td>
                <td class="px-4 py-2 border">${unit}</td>
                <td class="px-4 py-2 border">
                    <input type="number" name="items[${rowIndex}][quantity]" 
                           class="w-full px-2 py-1 border rounded" 
                           min="1" max="${stock}" required>
                </td>
                <td class="px-4 py-2 border">
                    <input type="text" name="items[${rowIndex}][purpose]" 
                           class="w-full px-2 py-1 border rounded" 
                           placeholder="Ghi chú (tùy chọn)">
                </td>
                </td>
                <td class="px-4 py-2 border">
                    <button type="button" class="bg-red-500 text-white px-2 py-1 rounded text-sm" 
                            onclick="this.closest('tr').remove()">
                        Xóa
                    </button>
                </td>
            </tr>
        `;

        document.getElementById('supplyTableBody').insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
        
        document.getElementById('selectSupplyModal').classList.add('hidden');
    }
});

// Tìm kiếm văn phòng phẩm
document.getElementById('searchSupply').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const items = document.querySelectorAll('.supply-item');
    const clearBtn = document.getElementById('clearSearch');
    const noResults = document.getElementById('noResults');
    let visibleCount = 0;

    // Hiển thị/ẩn nút clear
    clearBtn.classList.toggle('hidden', searchTerm === '');

    items.forEach(item => {
        const supplyName = item.querySelector('.supply-name').textContent.toLowerCase();
        const supplySpecs = item.querySelector('.supply-specs').textContent.toLowerCase();
        
        // Tìm kiếm trong tên và thông số
        const isMatch = supplyName.includes(searchTerm) || supplySpecs.includes(searchTerm);
        
        if (isMatch) {
            item.style.display = '';
            visibleCount++;
            
            // Highlight từ khóa tìm kiếm
            highlightSearchTerm(item, searchTerm);
        } else {
            item.style.display = 'none';
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
function highlightSearchTerm(item, searchTerm) {
    if (searchTerm === '') {
        // Restore original text
        const nameEl = item.querySelector('.supply-name');
        const specsEl = item.querySelector('.supply-specs');
        if (nameEl.dataset.originalText) nameEl.textContent = nameEl.dataset.originalText;
        if (specsEl.dataset.originalText) specsEl.textContent = specsEl.dataset.originalText;
        return;
    }
    
    const nameEl = item.querySelector('.supply-name');
    const specsEl = item.querySelector('.supply-specs');
    
    // Store original text
    if (!nameEl.dataset.originalText) nameEl.dataset.originalText = nameEl.textContent;
    if (!specsEl.dataset.originalText) specsEl.dataset.originalText = specsEl.textContent;
    
    // Highlight matches
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    nameEl.innerHTML = nameEl.dataset.originalText.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
    specsEl.innerHTML = specsEl.dataset.originalText.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
}

// Modal controls
document.getElementById('addSupplyBtn').addEventListener('click', function() {
    document.getElementById('selectSupplyModal').classList.remove('hidden');
    // Focus vào search input
    setTimeout(() => {
        document.getElementById('searchSupply').focus();
    }, 100);
});

document.getElementById('closeModalBtn').addEventListener('click', function() {
    document.getElementById('selectSupplyModal').classList.add('hidden');
    clearSearchInput();
});

// Close modal với ESC hoặc click outside
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('selectSupplyModal').classList.contains('hidden')) {
        document.getElementById('closeModalBtn').click();
    }
});

document.getElementById('selectSupplyModal').addEventListener('click', function(e) {
    if (e.target === this) {
        document.getElementById('closeModalBtn').click();
    }
});

// Form validation
document.getElementById('requestForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('#supplyTableBody tr');
    if (rows.length === 0) {
        e.preventDefault();
        alert('Vui lòng thêm ít nhất một văn phòng phẩm!');
    }
});
</script>
@endsection