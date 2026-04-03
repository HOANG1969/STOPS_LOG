@extends('layouts.app')

@section('title', 'Chi tiết STOP')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px;">
        <h1>
            <i class="fas fa-eye text-info me-2"></i>
            Chi tiết STOP
        </h1>
        <div>
            @if(Auth::user()->isAdmin() || Auth::user()->isApprover())
            <a href="{{ route('stops.edit', $stop) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Chỉnh sửa
            </a>
            @endif
            <a href="{{ route('stops.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Thông tin quan sát
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Loại vấn đề -->
                    <div class="mb-4">
                        <label class="form-label text-muted">Loại vấn đề STOP</label>
                        <div>
                            <span class="badge bg-info fs-6 px-3 py-2">
                                <i class="fas fa-tag me-1"></i>{{ $stop->getCategoryLabel() }}
                            </span>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Người quan sát</label>
                            <p class="fw-bold">{{ $stop->observer_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Ca/kíp</label>
                            <p class="fw-bold">{{ $stop->observer_phone ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Ngày quan sát</label>
                            <p class="fw-bold">{{ $stop->observation_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Giờ quan sát</label>
                            <p class="fw-bold">{{ $stop->observation_time ? $stop->observation_time->format('H:i') : '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted">Vị trí</label>
                            <p class="fw-bold">{{ $stop->location }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Thiết bị</label>
                            <p class="fw-bold">{{ $stop->equipment_name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Vấn đề ghi nhận</label>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $stop->issue_description }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Hành động khắc phục</label>
                        <div class="alert alert-success">
                            <i class="fas fa-tools me-2"></i>
                            {{ $stop->corrective_action }}
                        </div>
                    </div>

                    @if($stop->notes)
                    <div class="mb-3">
                        <label class="form-label text-muted">Ghi chú</label>
                        <div class="alert alert-info">
                            <i class="fas fa-sticky-note me-2"></i>
                            {{ $stop->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-1"></i>Thông tin cập nhật
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Trạng thái hiện tại</label>
                        <div>
                            <span class="{{ $stop->getStatusBadgeClass() }} fs-6">
                                {{ $stop->getStatusLabel() }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Mức độ quan trọng</label>
                        <div>
                            @if($stop->priority_level !== null)
                                <span class="badge {{ $stop->getPriorityBadgeClass() }} fs-6">
                                    {{ $stop->getPriorityLabel() }}
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">Chưa chấm</span>
                            @endif
                        </div>
                    </div>

                    @if($stop->priority_scored_by && $stop->scorer)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Người chấm điểm mức</label>
                        <p class="mb-1">
                            <i class="fas fa-user-check me-1 text-primary"></i>{{ $stop->scorer->name }}
                        </p>
                        <p class="mb-0 text-muted small">
                            <i class="fas fa-clock me-1"></i>{{ $stop->priority_scored_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @endif

                    @if($stop->completion_date)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Ngày hoàn thành</label>
                        <p class="fw-bold mb-0">{{ $stop->completion_date->format('d/m/Y') }}</p>
                    </div>
                    @endif

                    <hr>

                    <div class="mb-2">
                        <label class="form-label text-muted small">Người ghi nhận</label>
                        <p class="mb-0">
                            <i class="fas fa-user me-1"></i>{{ $stop->user->full_name }}
                        </p>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-muted small">Ngày tạo</label>
                        <p class="mb-0">
                            <i class="fas fa-calendar me-1"></i>{{ $stop->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div>
                        <label class="form-label text-muted small">Cập nhật lần cuối</label>
                        <p class="mb-0">
                            <i class="fas fa-clock me-1"></i>{{ $stop->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin() || Auth::user()->isApprover())
            <!-- Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-1"></i>Thao tác
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('stops.edit', $stop) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-1"></i>Chỉnh sửa
                    </a>
                    <form action="{{ route('stops.destroy', $stop) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa ghi nhận STOP này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-1"></i>Xóa
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
