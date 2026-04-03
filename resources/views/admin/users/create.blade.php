@extends('layouts.app')

@section('title', 'Thêm nhân sự')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user-plus"></i> Thêm nhân sự mới</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <!-- <option value="">Chọn vai trò</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="approver" {{ old('role') === 'approver' ? 'selected' : '' }}>Phê duyệt</option>
                                    <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Nhân viên</option> -->
                                    <option value="">Chọn vai trò</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="approver" {{ old('role') === 'approver' ? 'selected' : '' }}>Phê duyệt</option>
                                    <option value="tchc_checker" {{ old('role') === 'tchc_checker' ? 'selected' : '' }}>TCHC Kiểm tra</option>
                                    <option value="tchc_manager" {{ old('role') === 'tchc_manager' ? 'selected' : '' }}>TCHC Quản lý</option>
                                    <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Nhân viên</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Ca/kíp</label>
                                <select class="form-select @error('phone') is-invalid @enderror" id="phone" name="phone">
                                    <option value="">-- Chọn ca/kíp --</option>
                                    <option value="HTSX" {{ old('phone') == 'HTSX' ? 'selected' : '' }}>HTSX</option>
                                    <option value="VH01" {{ old('phone') == 'VH01' ? 'selected' : '' }}>VH01</option>
                                    <option value="VH02" {{ old('phone') == 'VH02' ? 'selected' : '' }}>VH02</option>
                                    <option value="VH03" {{ old('phone') == 'VH03' ? 'selected' : '' }}>VH03</option>
                                    <option value="VH04" {{ old('phone') == 'VH04' ? 'selected' : '' }}>VH04</option>
                                </select>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Phòng ban</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                       id="department" name="department" value="{{ old('department') }}" 
                                       placeholder="VD: Nhân sự, IT, Marketing">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Chức vụ</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="position" name="position" value="{{ old('position') }}" 
                                       placeholder="VD: Trưởng phòng, Nhân viên">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection