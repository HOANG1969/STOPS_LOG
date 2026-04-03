@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu')

@section('content')
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-800">
                Chi tiết yêu cầu - {{ $request->request_code }}
            </h4>
        </div>
        
        <div class="p-6">
            <!-- Thông tin chung -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-3">
                    <div><span class="font-medium">Mã yêu cầu:</span> {{ $request->request_code }}</div>
                    <div><span class="font-medium">Người yêu cầu:</span> {{ $request->requester_name }}</div>
                    <div><span class="font-medium">Email:</span> {{ $request->requester_email }}</div>
                    <div><span class="font-medium">Bộ phận:</span> {{ $request->department }}</div>
                </div>
                <div class="space-y-3">
                    <div><span class="font-medium">Ngày tạo:</span> {{ $request->created_at->format('d/m/Y H:i') }}</div>
                    <div><span class="font-medium">Ngày cần:</span> {{ \Carbon\Carbon::parse($request->needed_date)->format('d/m/Y') }}</div>
                    <div><span class="font-medium">Ưu tiên:</span> 
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                            @if($request->priority === 'Normal') bg-blue-100 text-blue-800
                            @elseif($request->priority === 'High') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $request->priority }}
                        </span>
                    </div>
                    <div><span class="font-medium">Trạng thái:</span>
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                            @if($request->status === 'pending') bg-gray-100 text-gray-800
                            @elseif($request->status === 'forwarded') bg-yellow-100 text-yellow-800
                            @elseif($request->status === 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @switch($request->status)
                                @case('pending') Chờ xử lý @break
                                @case('forwarded') Đã chuyển @break
                                @case('approved') Đã duyệt @break
                                @case('rejected') Từ chối @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            @if($request->notes)
            <div class="mb-6">
                <h5 class="font-semibold mb-2">Ghi chú:</h5>
                <div class="bg-gray-50 p-3 rounded">{{ $request->notes }}</div>
            </div>
            @endif

            <!-- Danh sách văn phòng phẩm -->
            <div class="mb-6">
                <h5 class="font-semibold mb-3">Danh sách văn phòng phẩm:</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border text-left">STT</th>
                                <th class="px-4 py-2 border text-left">Tên VPP</th>
                                <th class="px-4 py-2 border text-left">ĐVT</th>
                                <th class="px-4 py-2 border text-left">Số lượng</th>
                                <th class="px-4 py-2 border text-left">Mục đích</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request->requestItems as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border">{{ $item->officeSupply->name }}</td>
                                    <td class="px-4 py-2 border">{{ $item->officeSupply->unit }}</td>
                                    <td class="px-4 py-2 border">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 border">{{ $item->purpose }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="flex justify-between">
                <a href="{{ route('employee.requests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Quay lại
                </a>
                
                <div class="space-x-2">
                    @if($request->status === 'pending')
                        <form action="{{ route('employee.requests.forward', $request) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded" 
                                    onclick="return confirm('Chuyển đơn này để phê duyệt?')">
                                Chuyển phê duyệt
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('employee.requests.history', $request) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Xem lịch sử
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection