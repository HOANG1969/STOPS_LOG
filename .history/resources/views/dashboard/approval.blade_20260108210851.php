@extends('layouts.app')

@section('title', 'Dashboard - Phê duyệt')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard - Phê duyệt đơn yêu cầu</h1>
        <p class="text-gray-600">{{ $user->full_name }} - {{ $user->position }} | Bộ phận: {{ $user->department }}</p>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-yellow-50 p-6 rounded-lg border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="text-yellow-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Chờ phê duyệt</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_count'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 p-6 rounded-lg border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="text-green-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Đã duyệt hôm nay</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_today'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="text-blue-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Đã duyệt tháng này</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_this_month'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 p-6 rounded-lg border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="text-purple-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Tổng đơn</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_requests'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Đơn chờ phê duyệt -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Đơn chờ phê duyệt ({{ $stats['pending_count'] }})
                </h3>
            </div>
            
            <div class="p-6 max-h-96 overflow-y-auto">
                @if($pendingRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingRequests as $request)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-bold text-blue-600">{{ $request->request_code }}</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        Chờ duyệt
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($request->priority === 'high') bg-red-100 text-red-800
                                        @elseif($request->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        @if($request->priority === 'high') Ưu tiên cao
                                        @elseif($request->priority === 'medium') Ưu tiên TB
                                        @else Ưu tiên thấp
                                        @endif
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <div class="font-medium text-gray-900">{{ $request->user->full_name }}</div>
                                <div class="text-sm text-gray-600">{{ $request->user->position }} - {{ $request->user->department }}</div>
                                <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                            </div>

                            <div class="text-sm text-gray-600 mb-3">
                                <div class="flex items-center space-x-4">
                                    <span>📦 {{ $request->requestItems->count() }} vật phẩm</span>
                                    <span>📅 {{ $request->request_date->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            @if($request->notes)
                            <div class="text-sm text-gray-600 mb-3 p-2 bg-gray-50 rounded border-l-4 border-blue-300">
                                <strong>Ghi chú:</strong> {{ $request->notes }}
                            </div>
                            @endif

                            <!-- Chi tiết vật phẩm -->
                            <div class="mb-3">
                                <div class="text-xs text-gray-500 mb-2">Danh sách vật phẩm yêu cầu:</div>
                                <div class="space-y-1 max-h-20 overflow-y-auto">
                                    @foreach($request->requestItems as $item)
                                    <div class="text-xs text-gray-600 flex justify-between">
                                        <span>{{ $item->officeSupply->name ?? 'N/A' }}</span>
                                        <span>x{{ $item->quantity }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Nút hành động -->
                            <div class="flex space-x-2">
                                <button onclick="approveRequest({{ $request->id }}, 'approve')" 
                                        class="flex-1 px-3 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 transition-colors">
                                    ✓ Phê duyệt
                                </button>
                                <button onclick="showRejectModal({{ $request->id }})" 
                                        class="flex-1 px-3 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition-colors">
                                    ✗ Từ chối
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $pendingRequests->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-medium">Không có đơn nào chờ phê duyệt</p>
                        <p class="text-sm mt-1">Tất cả các đơn yêu cầu đã được xử lý</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Đơn đã xử lý -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Đơn đã xử lý
                </h3>
            </div>
            
            <div class="p-6 max-h-96 overflow-y-auto">
                @if($processedRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach($processedRequests as $request)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $request->request_code }}</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($request->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($request->status === 'approved') ✓ Đã duyệt
                                        @else ✗ Từ chối
                                        @endif
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $request->approved_date->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-2">
                                <div class="font-medium">{{ $request->user->full_name }}</div>
                                <div class="text-xs">{{ $request->user->position }} - {{ $request->user->department }}</div>
                                <div class="text-xs">📦 {{ $request->requestItems->count() }} vật phẩm</div>
                            </div>

                            @if($request->status === 'rejected' && $request->rejection_reason)
                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-sm text-red-600">
                                <strong>Lý do từ chối:</strong> {{ $request->rejection_reason }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $processedRequests->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Chưa có đơn nào được xử lý</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal từ chối -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Từ chối đơn yêu cầu</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="rejectForm">
                <div class="mb-4">
                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Lý do từ chối <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rejectionReason" name="notes" rows="4" required 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Vui lòng nhập lý do từ chối đơn yêu cầu này..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Từ chối đơn
                    </button>
                    <button type="button" onclick="closeRejectModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 font-medium rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

@push('scripts')
<script>
let currentRequestId = null;

function approveRequest(requestId, action) {
    if (!confirm('Bạn có chắc chắn muốn phê duyệt đơn yêu cầu này?')) {
        return;
    }
    
    fetch(`/dashboard/approve/${requestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showMessage(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Có lỗi xảy ra khi xử lý yêu cầu', 'error');
    });
}

function showRejectModal(requestId) {
    currentRequestId = requestId;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectionReason').focus();
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectionReason').value = '';
    currentRequestId = null;
}

document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const notes = document.getElementById('rejectionReason').value.trim();
    if (!notes) {
        showMessage('Vui lòng nhập lý do từ chối', 'error');
        return;
    }
    
    fetch(`/dashboard/approve/${currentRequestId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: 'reject',
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            closeRejectModal();
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showMessage(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Có lỗi xảy ra khi xử lý yêu cầu', 'error');
    });
});

function showMessage(message, type) {
    const container = document.getElementById('messageContainer');
    const div = document.createElement('div');
    
    div.className = `px-4 py-3 rounded-md shadow-md ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 
        'bg-red-100 text-red-800 border border-red-300'
    }`;
    
    div.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2">${type === 'success' ? '✓' : '✗'}</span>
            <span>${message}</span>
        </div>
    `;
    
    container.appendChild(div);
    
    setTimeout(() => {
        div.remove();
    }, 5000);
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endpush
@endsection