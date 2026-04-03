@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-alt me-2"></i>Danh sách yêu cầu VPP</h2>
        <a href="{{ route('requests.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Tạo yêu cầu mới
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('requests.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Đã gửi</option>
                            <option value="manager_approved" {{ request('status') === 'manager_approved' ? 'selected' : '' }}>Manager đã duyệt</option>
                            <option value="director_approved" {{ request('status') === 'director_approved' ? 'selected' : '' }}>Director đã duyệt</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                               placeholder="Tìm theo mã hoặc tiêu đề yêu cầu...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Xóa bộ lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card">
        <div class="card-body">
            @if($requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã yêu cầu</th>
                            <th>Tiêu đề</th>
                            <th>Người yêu cầu</th>
                            <th>Ngày tạo</th>
                            <th>Ngày cần</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                        <tr>
                            <td>
                                <strong>{{ $request->request_number }}</strong>
                                @if($request->priority === 'urgent')
                                    <i class="fas fa-exclamation-triangle text-danger ms-1" title="Khẩn cấp"></i>
                                @elseif($request->priority === 'high')
                                    <i class="fas fa-arrow-up text-warning ms-1" title="Ưu tiên cao"></i>
                                @endif
                            </td>
                            <td>{{ Str::limit($request->title, 30) }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $request->needed_date->format('d/m/Y') }}</td>
                            <td>{{ $request->formatted_total_amount }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status_color }} status-badge">
                                    {{ $request->status_name }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('requests.show', $request) }}" class="btn btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($request->isDraft() || ($request->isRejected() && $request->user_id === Auth::id()))
                                    <a href="{{ route('requests.edit', $request) }}" class="btn btn-outline-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    
                                    @if($request->isDraft() && ($request->user_id === Auth::id() || Auth::user()->isAdmin()))
                                    <form action="{{ route('requests.destroy', $request) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Hiển thị {{ $requests->firstItem() ?? 0 }} đến {{ $requests->lastItem() ?? 0 }} 
                    trong tổng số {{ $requests->total() }} yêu cầu
                </div>
                {{ $requests->appends(request()->query())->links() }}
            </div>

            @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt text-muted fa-3x mb-3"></i>
                <h5 class="text-muted">Không có yêu cầu nào</h5>
                <p class="text-muted">Bạn chưa có yêu cầu VPP nào hoặc không có kết quả phù hợp với bộ lọc.</p>
                <a href="{{ route('requests.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Tạo yêu cầu đầu tiên
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection