@extends('layouts.app')

@section('title', 'Lịch sử yêu cầu')

@section('content')
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-800">
                Lịch sử yêu cầu - {{ $request->request_code }}
            </h4>
        </div>
        
        <div class="p-6">
            <!-- Thông tin tóm tắt -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded">
                <div>
                    <span class="font-medium">Mã yêu cầu:</span><br>
                    <span class="text-blue-600">{{ $request->request_code }}</span>
                </div>
                <div>
                    <span class="font-medium">Người yêu cầu:</span><br>
                    {{ $request->requester_name }}
                </div>
                <div>
                    <span class="font-medium">Ngày tạo:</span><br>
                    {{ $request->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <span class="font-medium">Trạng thái hiện tại:</span><br>
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

            <!-- Timeline lịch sử -->
            <div class="mb-6">
                <h5 class="font-semibold mb-4">Lịch sử xử lý:</h5>
                
                <div class="space-y-4">
                    @foreach($timeline as $event)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                @if($event['action'] === 'created') bg-blue-100
                                @elseif($event['action'] === 'forwarded') bg-yellow-100
                                @elseif($event['action'] === 'approved') bg-green-100
                                @else bg-red-100
                                @endif">
                                <i class="fas 
                                    @if($event['action'] === 'created') fa-plus text-blue-600
                                    @elseif($event['action'] === 'forwarded') fa-paper-plane text-yellow-600
                                    @elseif($event['action'] === 'approved') fa-check text-green-600
                                    @else fa-times text-red-600
                                    @endif"></i>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <div class="bg-gray-50 p-4 rounded">
                                <h6 class="font-medium">{{ $event['title'] }}</h6>
                                <p class="text-sm text-gray-600 mt-1">{{ $event['description'] }}</p>
                                <div class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $event['timestamp']->format('d/m/Y H:i:s') }}
                                    <span class="ml-3"><i class="fas fa-user mr-1"></i>{{ $event['user'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

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
                    Về danh sách
                </a>
                
                <a href="{{ route('employee.requests.show', $request) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
</div>
@endsection