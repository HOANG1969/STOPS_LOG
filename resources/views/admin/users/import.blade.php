@extends('layouts.app')

@section('title', 'Import nhân sự')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-excel"></i> Import nhân sự từ Excel</h2>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Hướng dẫn nhanh</h5>
                        </div>
                        <div class="card-body">
                            <ol class="mb-0">
                                <li>Tải template Excel mẫu.</li>
                                <li>Điền dữ liệu theo đúng cột.</li>
                                <li>Upload file và bấm Import.</li>
                                <li>Hệ thống chỉ tạo mới theo email, email đã tồn tại sẽ bỏ qua.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Lưu ý</h5>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li>Định dạng hỗ trợ: .xlsx, .xls, .csv</li>
                                <li>Dung lượng tối đa: 4MB</li>
                                <li>Cột bắt buộc: ho_ten, email</li>
                                <li>Nếu không có mat_khau, hệ thống dùng mặc định: 12345678</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-download"></i> Template import</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Template đã gồm đúng các thông tin như form nhân sự: Họ tên, Email, Mật khẩu, Xác nhận mật khẩu, Vai trò, Ca/kíp, Phòng ban, Chức vụ, Trạng thái.</p>
                    <a href="{{ route('users.import.template') }}" class="btn btn-success">
                        <i class="fas fa-file-download"></i> Tải template Excel
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-upload"></i> Upload file import</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.import.process') }}" method="POST" enctype="multipart/form-data" id="importUserForm">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Chọn file</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">File cần đúng template để import ổn định.</div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmImport" required>
                            <label class="form-check-label" for="confirmImport">
                                Tôi đã kiểm tra dữ liệu trước khi import
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" id="importBtn">
                            <i class="fas fa-file-import"></i> Import nhân sự
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-table"></i> Cấu trúc cột template</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Cột</th>
                                    <th>Ý nghĩa</th>
                                    <th>Bắt buộc</th>
                                    <th>Ví dụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>ho_ten</td><td>Họ và tên</td><td><span class="badge bg-danger">Có</span></td><td>Thái Việt Hùng</td></tr>
                                <tr><td>email</td><td>Email đăng nhập</td><td><span class="badge bg-danger">Có</span></td><td>hung.tv2@pvgas.com.vn</td></tr>
                                <tr><td>mat_khau</td><td>Mật khẩu</td><td><span class="badge bg-secondary">Không</span></td><td>12345678</td></tr>
                                <tr><td>xac_nhan_mat_khau</td><td>Xác nhận mật khẩu (nếu có sẽ kiểm tra trùng)</td><td><span class="badge bg-secondary">Không</span></td><td>12345678</td></tr>
                                <tr><td>vai_tro</td><td>admin/approver/employee/tchc_checker/tchc_manager</td><td><span class="badge bg-secondary">Không</span></td><td>employee</td></tr>
                                <tr><td>ca_kip</td><td>Ca/kíp</td><td><span class="badge bg-secondary">Không</span></td><td>VH01</td></tr>
                                <tr><td>phong_ban</td><td>Phòng ban</td><td><span class="badge bg-secondary">Không</span></td><td>KCTV</td></tr>
                                <tr><td>chuc_vu</td><td>Chức vụ</td><td><span class="badge bg-secondary">Không</span></td><td>Công nhân VH</td></tr>
                                <tr><td>trang_thai</td><td>1/0 hoặc true/false</td><td><span class="badge bg-secondary">Không</span></td><td>1</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('importUserForm').addEventListener('submit', function () {
    const btn = document.getElementById('importBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang import...';
});
</script>
@endpush
