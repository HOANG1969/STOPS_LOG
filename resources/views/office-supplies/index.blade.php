@extends('layouts.app')

@section('title', 'Danh sách văn phòng phẩm')
@section('page-title', 'Quản lý văn phòng phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Danh sách yêu cầu văn phòng phẩm</h4>
        <small class="text-muted">Quản lý tất cả yêu cầu mua sắm văn phòng phẩm</small>
    </div>
    <a href="{{ route('office-supplies.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Tạo yêu cầu mới
    </a>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending">Chờ duyệt</option>
                    <option value="approved">Đã duyệt</option>
                    <option value="rejected">Từ chối</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Danh mục</label>
                <select class="form-select">
                    <option value="">Tất cả danh mục</option>
                    <option value="stationery">Văn phòng phẩm</option>
                    <option value="electronics">Thiết bị điện tử</option>
                    <option value="furniture">Nội thất</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" placeholder="Nhập từ khóa tìm kiếm...">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-outline-primary w-100">
                    <i class="fas fa-search me-2"></i>Lọc
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Requests Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Người yêu cầu</th>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>Nguyễn Văn A</td>
                        <td>Yêu cầu mua bút bi</td>
                        <td><span class="badge bg-info">Văn phòng phẩm</span></td>
                        <td>50</td>
                        <td><span class="badge bg-warning">Chờ duyệt</span></td>
                        <td>08/01/2026</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>Trần Thị B</td>
                        <td>Yêu cầu mua giấy A4</td>
                        <td><span class="badge bg-info">Văn phòng phẩm</span></td>
                        <td>10</td>
                        <td><span class="badge bg-success">Đã duyệt</span></td>
                        <td>07/01/2026</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#003</td>
                        <td>Lê Văn C</td>
                        <td>Yêu cầu mua máy tính</td>
                        <td><span class="badge bg-secondary">Thiết bị điện tử</span></td>
                        <td>2</td>
                        <td><span class="badge bg-danger">Từ chối</span></td>
                        <td>06/01/2026</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Hiển thị 1-3 của 3 kết quả
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Trước</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection