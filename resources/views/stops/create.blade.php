@extends('layouts.app')

@section('title', 'Ghi nhận STOP mới')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px;">
        <h1 style="font-size: 1.3rem;">
            <i class="fas fa-plus-circle text-primary me-2"></i>
            Ghi nhận STOP mới
        </h1>
        <a href="{{ route('stops.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Thông tin STOP (Safety Training Observation Program)
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stops.store') }}" method="POST">
                        @csrf

                        <!-- Loại vấn đề STOP -->
                        <div class="mb-4">
                            <label for="issue_category" class="form-label">
                                <strong>Loại vấn đề STOP</strong> <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('issue_category') is-invalid @enderror" 
                                    id="issue_category" 
                                    name="issue_category" 
                                    required>
                                <option value="">-- Chọn loại vấn đề --</option>
                                @foreach(\App\Models\Stop::getIssueCategories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('issue_category') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('issue_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="mb-4">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="observer_name" class="form-label">
                                    Tên người ghi nhận <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('observer_name') is-invalid @enderror" 
                                       id="observer_name" 
                                       name="observer_name" 
                                       value="{{ old('observer_name', Auth::user()->full_name ?? Auth::user()->name) }}" 
                                       required>
                                @error('observer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2">
                                <label for="observer_phone" class="form-label">
                                    Ca/kíp <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('observer_phone') is-invalid @enderror" 
                                        id="observer_phone" 
                                        name="observer_phone" 
                                        required>
                                    <option value="">-- Chọn Ca/kíp --</option>
                                    <option value="HTSX" {{ old('observer_phone', Auth::user()->phone) == 'HTSX' ? 'selected' : '' }}>HTSX</option>
                                    <option value="VH01" {{ old('observer_phone', Auth::user()->phone) == 'VH01' ? 'selected' : '' }}>VH01</option>
                                    <option value="VH02" {{ old('observer_phone', Auth::user()->phone) == 'VH02' ? 'selected' : '' }}>VH02</option>
                                    <option value="VH03" {{ old('observer_phone', Auth::user()->phone) == 'VH03' ? 'selected' : '' }}>VH03</option>
                                    <option value="VH04" {{ old('observer_phone', Auth::user()->phone) == 'VH04' ? 'selected' : '' }}>VH04</option>
                                </select>
                                @error('observer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="observation_date" class="form-label">
                                    Ngày tháng <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('observation_date') is-invalid @enderror" 
                                       id="observation_date" 
                                       name="observation_date" 
                                       value="{{ old('observation_date', date('Y-m-d')) }}" 
                                       required>
                                @error('observation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="observation_time" class="form-label">
                                    Giờ ghi nhận
                                </label>
                                <input type="time" 
                                       class="form-control @error('observation_time') is-invalid @enderror" 
                                       id="observation_time" 
                                       name="observation_time" 
                                       value="{{ old('observation_time', date('H:i')) }}">
                                @error('observation_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="location" class="form-label">
                                    Vị trí <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}" 
                                       placeholder="Ví dụ: Phân xưởng A, Khu vực sản xuất..."
                                       required>
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="equipment_name" class="form-label">
                                    Tên thiết bị
                                </label>
                                <input type="text" 
                                       class="form-control @error('equipment_name') is-invalid @enderror" 
                                       id="equipment_name" 
                                       name="equipment_name" 
                                       value="{{ old('equipment_name') }}" 
                                       placeholder="Máy móc, công cụ...">
                                @error('equipment_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="issue_description" class="form-label">
                                Vấn đề ghi nhận <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('issue_description') is-invalid @enderror" 
                                      id="issue_description" 
                                      name="issue_description" 
                                      rows="4" 
                                      placeholder="Mô tả chi tiết vấn đề về an toàn, sức khỏe, môi trường làm việc..."
                                      required>{{ old('issue_description') }}</textarea>
                            @error('issue_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="corrective_action" class="form-label">
                                Hành động khắc phục <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('corrective_action') is-invalid @enderror" 
                                      id="corrective_action" 
                                      name="corrective_action" 
                                      rows="4" 
                                      placeholder="Đề xuất biện pháp khắc phục, cải thiện..."
                                      required>{{ old('corrective_action') }}</textarea>
                            @error('corrective_action')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Trạng thái
                                </label>
                                <input type="text" class="form-control" value="Chưa xử lý" readonly>
                                <small class="text-muted">Trạng thái được hệ thống đặt mặc định khi tạo mới.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="completion_date" class="form-label">
                                    Ngày hoàn thành
                                </label>
                                <input type="date" 
                                       class="form-control @error('completion_date') is-invalid @enderror" 
                                       id="completion_date" 
                                       name="completion_date" 
                                        value="{{ old('completion_date') }}"
                                        disabled>
                                    <small class="text-muted">Sẽ được cập nhật khi thẻ STOP chuyển sang "Hoàn thành".</small>
                                @error('completion_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="mb-3">
                            <label for="notes" class="form-label">
                                Ghi chú
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Thông tin bổ sung...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('stops.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Lưu ghi nhận
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="card" style="background-color: #6cd69c; color: white;">
                <div class="card-body">
                    <h6 class="card-title" style=" font-size: 1.25rem; color: blue;">
                        <i class="fas fa-info-circle me-1" style="color: Blue;" ></i>Hướng dẫn
                    </h6>
                    <small>
                        <p class="mb-2" style="font-size: 1rem; ">STOP là chương trình quan sát an toàn giúp:</p>
                        <ul class="small" style="font-size: 1rem; ">
                            <li>Phát hiện hành vi không an toàn</li>
                            <li>Cải thiện điều kiện làm việc</li>
                            <li>Tăng cường ý thức ATVSLĐ</li>
                            <li>- Các mức chấm điểm thẻ STOP:</li>
                            <li><strong>Mức 0</strong>: Nguy hiểm nghiêm trọng, cần xử lý ngay</li>
                            <li><strong>Mức 1</strong>: Nguy hiểm cao, cần xử lý ngay</li>
                            <li><strong>Mức 2</strong>: Nguy hiểm trung bình, cần theo dõi</li>
                            <li><strong>Mức 3</strong>: Nguy hiểm thấp, lưu ý</li>
                        </ul>

                    </small>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const completionDate = document.getElementById('completion_date');
if (completionDate) {
    completionDate.value = '';
}
</script>
@endpush
@endsection
