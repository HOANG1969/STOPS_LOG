@extends('layouts.app')

@section('title', 'Dashboard - Nhân viên')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard - {{ $user->full_name }}</h1>
        <p class="text-gray-600">Bộ phận: {{ $user->department }} | Chức vụ: {{ $user->position }}</p>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="text-blue-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Đơn chờ duyệt</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['my_pending'] }}</p>
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
                    <p class="text-sm text-gray-600">Đơn đã duyệt</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['my_approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 p-6 rounded-lg border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="text-red-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Đơn bị từ chối</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['my_rejected'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 p-6 rounded-lg border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="text-yellow-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Đơn bộ phận chờ</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['dept_pending'] }}</p>
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
                    <p class="text-sm text-gray-600">Tổng đơn bộ phận</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['dept_total'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Nút tạo đơn mới -->
    <div class="mb-6">
        <a href="{{ route('office-supplies.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Tạo đơn yêu cầu mới
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Đơn yêu cầu của tôi -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Đơn yêu cầu của tôi</h3>
            </div>
            
            <div class="p-6">
                @if($myRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach($myRequests as $request)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $request->request_code }}</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif(in_array($request->status, ['approved', 'tchc_checked', 'tchc_approved'])) bg-green-100 text-green-800
                                        @elseif(in_array($request->status, ['rejected', 'tchc_rejected'])) bg-red-100 text-red-800
                                        @endif">
                                        @if($request->status === 'pending') Chờ duyệt
                                        @elseif($request->status === 'approved') Chờ TCHC kiểm tra
                                        @elseif($request->status === 'tchc_checked') TCHC đã kiểm tra
                                        @elseif($request->status === 'tchc_approved') Đã hoàn thành
                                        @elseif($request->status === 'rejected') Từ chối
                                        @elseif($request->status === 'tchc_rejected') TCHC từ chối
                                        @endif
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $request->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-2">
                                <div class="mb-1">Mức ưu tiên: 
                                    <span class="font-medium
                                        @if($request->priority === 'high') text-red-600
                                        @elseif($request->priority === 'medium') text-yellow-600
                                        @else text-green-600
                                        @endif">
                                        @if($request->priority === 'high') Cao
                                        @elseif($request->priority === 'medium') Trung bình
                                        @else Thấp
                                        @endif
                                    </span>
                                </div>
                                <div>Số lượng vật phẩm: {{ $request->requestItems->count() }}</div>
                            </div>

                            @if($request->approver)
                            <div class="text-xs text-gray-500 mt-2">
                                Phê duyệt bởi: {{ $request->approver->full_name }} 
                                @if($request->approved_at)
                                    - {{ $request->approved_at->format('d/m/Y H:i') }}
                                @endif
                            </div>
                            @endif

                            @if($request->status === 'rejected' && $request->rejection_reason)
                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-sm text-red-600">
                                <strong>Lý do từ chối:</strong> {{ $request->rejection_reason }}
                            </div>
                            @endif

                            <!-- Nút thao tác cho đơn bị từ chối -->
                            @if(in_array($request->status, ['rejected', 'tchc_rejected']))
                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('supply-requests.edit', $request) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                    Chỉnh sửa
                                </a>
                                <button onclick="resubmitRequest({{ $request->id }})"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                    </svg>
                                    Gửi lại
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $myRequests->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Bạn chưa có đơn yêu cầu nào</p>
                        <a href="{{ route('office-supplies.index') }}" class="mt-2 text-blue-600 hover:text-blue-800 font-medium">Tạo đơn đầu tiên →</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Đơn yêu cầu bộ phận -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Đơn yêu cầu bộ phận {{ $user->department }}</h3>
            </div>
            
            <div class="p-6">
                @if($departmentRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach($departmentRequests as $request)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $request->request_code }}</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'approved') bg-green-100 text-green-800
                                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                        @endif">
                                        @if($request->status === 'pending') Chờ duyệt
                                        @elseif($request->status === 'approved') Đã duyệt
                                        @elseif($request->status === 'rejected') Từ chối
                                        @endif
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-2">
                                <div class="mb-1">
                                    <span class="font-medium">{{ $request->user->full_name }}</span> 
                                    ({{ $request->user->position }})
                                </div>
                                <div class="mb-1">Mức ưu tiên: 
                                    <span class="font-medium
                                        @if($request->priority === 'high') text-red-600
                                        @elseif($request->priority === 'medium') text-yellow-600
                                        @else text-green-600
                                        @endif">
                                        @if($request->priority === 'high') Cao
                                        @elseif($request->priority === 'medium') Trung bình
                                        @else Thấp
                                        @endif
                                    </span>
                                </div>
                                <div>Số lượng vật phẩm: {{ $request->requestItems->count() }}</div>
                            </div>

                            @if($request->approver)
                            <div class="text-xs text-gray-500 mt-2">
                                Phê duyệt bởi: {{ $request->approver->full_name }} 
                                @if($request->approved_at)
                                    - {{ $request->approved_at->format('d/m/Y H:i') }}
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $departmentRequests->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p>Bộ phận chưa có đơn yêu cầu khác</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function resubmitRequest(requestId) {
    if (!confirm('Bạn có chắc muốn gửi lại đơn yêu cầu này? Đơn sẽ được reset về trạng thái chờ phê duyệt.')) {
        return;
    }
    
    fetch(`/supply-requests/${requestId}/resubmit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert(data.message);
            // Reload page to update status
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi gửi lại đơn');
    });
}
</script>
@endsection
