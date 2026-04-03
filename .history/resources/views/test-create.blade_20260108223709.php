@extends('layouts.app')

@section('title', 'Test Create')

@section('content')
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-xl font-semibold mb-4">Test Create Page</h1>
        <p>Đây là trang test. Nếu bạn thấy được nội dung này, nghĩa là view đã hoạt động.</p>
        
        <p><strong>Số lượng văn phòng phẩm:</strong> {{ $officeSupplies->count() }}</p>
        
        @if($officeSupplies->count() > 0)
        <div class="mt-4">
            <h3 class="font-semibold">Danh sách văn phòng phẩm (5 item đầu tiên):</h3>
            <ul class="mt-2">
                @foreach($officeSupplies->take(5) as $supply)
                    <li>{{ $supply->name }} - {{ $supply->unit }} ({{ $supply->stock_quantity }})</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="mt-4">
            <a href="{{ route('employee.requests.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Về danh sách
            </a>
        </div>
    </div>
</div>
@endsection