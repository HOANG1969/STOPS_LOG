@extends('layouts.app')

@section('title', 'Quản lý nhân sự')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users"></i> Quản lý nhân sự</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm nhân sự mới
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="name" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ request('name') }}" placeholder="Tìm theo tên...">
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ request('email') }}" placeholder="Tìm theo email...">
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label">Phòng ban</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">-- Tất cả phòng ban --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="role" class="form-label">Vai trò</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">-- Tất cả vai trò --</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="approver" {{ request('role') == 'approver' ? 'selected' : '' }}>Phê duyệt</option>
                            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Nhân viên</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Vô hiệu hóa</option>
                        </select>
                    </div>
                    <div class="col-md-9 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    @if(request()->hasAny(['name', 'email', 'department', 'role', 'status']))
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        Tìm thấy <strong>{{ $users->total() }}</strong> nhân sự
        @if(request('name'))
            với tên chứa "<strong>{{ request('name') }}</strong>"
        @endif
        @if(request('email'))
            với email chứa "<strong>{{ request('email') }}</strong>"
        @endif
        @if(request('department'))
            thuộc phòng ban "<strong>{{ request('department') }}</strong>"
        @endif
        @if(request('role'))
            có vai trò "<strong>{{ ucfirst(request('role')) }}</strong>"
        @endif
        @if(request('status'))
            trạng thái "<strong>{{ request('status') == 'active' ? 'Hoạt động' : 'Vô hiệu hóa' }}</strong>"
        @endif
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Phòng ban</th>
                            <th>Chức vụ</th>
                            <th>Điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-info ms-1">Bạn</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Admin</span>
                                        @break
                                    @case('approver')
                                        <span class="badge bg-warning">Phê duyệt</span>
                                        @break
                                    @case('employee')
                                        <span class="badge bg-success">Nhân viên</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $user->department ?? '-' }}</td>
                            <td>{{ $user->position ?? '-' }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Vô hiệu hóa</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-sm {{ $user->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                onclick="toggleStatus({{ $user->id }}, '{{ $user->is_active ? 'vô hiệu hóa' : 'kích hoạt' }}')">
                                            @if($user->is_active)
                                                <i class="fas fa-ban"></i>
                                            @else
                                                <i class="fas fa-check"></i>
                                            @endif
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Không có dữ liệu nhân sự</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa nhân sự <strong id="userName"></strong>?
                <br><small class="text-danger">Hành động này không thể hoàn tác!</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận thay đổi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn <strong id="actionText"></strong> tài khoản này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a id="toggleLink" href="#" class="btn btn-warning">Xác nhận</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/users/' + userId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function toggleStatus(userId, action) {
    document.getElementById('actionText').textContent = action;
    document.getElementById('toggleLink').href = '/admin/users/' + userId + '/toggle-status';
    new bootstrap.Modal(document.getElementById('toggleModal')).show();
}
</script>
@endpush
@endsection