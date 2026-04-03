@extends('layouts.app')

@section('title', 'Chỉnh sửa STOP')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px;">
        <h1>
            <i class="fas fa-edit text-warning me-2"></i>
            Chi tiết thẻ STOP
        </h1>
        <a href="{{ route('stops.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-10">
            @if($stop->status === 'completed')
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Thẻ STOP đã hoàn thành!</strong> Không thể chỉnh sửa nội dung.
            </div>
            @endif

            <!-- @if ($errors->has('status'))
            <div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i>
                {{ $errors->first('status') }}
            </div>
            @endif -->
            
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Cập nhật thông tin STOP
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stops.update', $stop) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Loại vấn đề STOP -->
                        <div class="mb-4">
                            <label for="issue_category" class="form-label">
                                <strong>Loại vấn đề STOP</strong> <span class="text-danger">*</span>
                            </select>
                            </label>
                            
                            <select class="form-select @error('issue_category') is-invalid @enderror" 
                                    id="issue_category" 
                                    name="issue_category" 
                                    required>
                                <option value="">-- Chọn loại vấn đề --</option>
                                @foreach(\App\Models\Stop::getIssueCategories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('issue_category', $stop->issue_category) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('issue_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                         <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="priority_level" class="form-label">
                                    <strong>Mức độ STOP</strong> <span class="text-danger">*</span>
                                </label>
                                    <select class="form-select @error('priority_level') is-invalid @enderror" 
                                            id="priority_level" 
                                            name="priority_level">
                                        <option value="">Chưa chấm</option>
                                        <option value="0" {{ old('priority_level', $stop->priority_level) == '0' ? 'selected' : '' }}>Mức 0: Nguy hiểm nghiêm trọng, cần xử lý ngay</option>
                                        <option value="1" {{ old('priority_level', $stop->priority_level) == '1' ? 'selected' : '' }}>Mức 1: Nguy hiểm cao, cần xử lý ngay</option>
                                        <option value="2" {{ old('priority_level', $stop->priority_level) == '2' ? 'selected' : '' }}>Mức 2: Nguy hiểm trung bình, cần theo dõi</option>
                                        <option value="3" {{ old('priority_level', $stop->priority_level) == '3' ? 'selected' : '' }}>Mức 3: Nguy hiểm thấp, cần cải thiện</option>
                                    </select>
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="notes" class="form-label">
                                Ghi chú
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                        id="notes" 
                                        name="notes" 
                                        rows="2">{{ old('notes', $stop->notes) }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                            </div> -->

                            @if(Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTchcManager())
                            <div class="col-md-6">
                                <label for="score_note" class="form-label">
                                    <strong>Ghi chú:</strong>
                                    <!-- Chú thích nếu CBAT bắt buộc -->
                                    <!-- @if(Auth::user()->isAdmin() || Auth::user()->isTchcManager())
                                    <span class="text-danger">*</span>
                                    @endif -->
                                </label>
                                <textarea class="form-control @error('score_note') is-invalid @enderror"
                                        id="score_note"
                                        name="score_note"
                                        rows="3"
                                        placeholder="">{{ old('score_note') }}</textarea>
                                @error('score_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <!-- <small class="text-muted">Ghi chú này sẽ được lưu vào lịch sử chấm điểm của Trưởng ca hoặc CBAT.</small> -->
                            </div>
                            @endif
                        
                        </div>

                        

                        <hr class="mb-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="observer_name" class="form-label">
                                    <strong>Tên người quan sát</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('observer_name') is-invalid @enderror" 
                                       id="observer_name" 
                                       name="observer_name" 
                                       value="{{ old('observer_name', $stop->observer_name) }}" 
                                       required>
                                @error('observer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="observer_phone" class="form-label">
                                    <strong>Ca/kíp</strong> <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('observer_phone') is-invalid @enderror" 
                                        id="observer_phone" 
                                        name="observer_phone" 
                                        required>
                                    <option value="">-- Chọn Ca/kíp --</option>
                                    <option value="HTSX" {{ old('observer_phone', $stop->observer_phone) == 'HTSX' ? 'selected' : '' }}>HTSX</option>
                                    <option value="VH01" {{ old('observer_phone', $stop->observer_phone) == 'VH01' ? 'selected' : '' }}>VH01</option>
                                    <option value="VH02" {{ old('observer_phone', $stop->observer_phone) == 'VH02' ? 'selected' : '' }}>VH02</option>
                                    <option value="VH03" {{ old('observer_phone', $stop->observer_phone) == 'VH03' ? 'selected' : '' }}>VH03</option>
                                    <option value="VH04" {{ old('observer_phone', $stop->observer_phone) == 'VH04' ? 'selected' : '' }}>VH04</option>
                                </select>
                                @error('observer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="observation_date" class="form-label">
                                    <strong>Ngày quan sát</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('observation_date') is-invalid @enderror" 
                                       id="observation_date" 
                                       name="observation_date" 
                                       value="{{ old('observation_date', $stop->observation_date->format('Y-m-d')) }}" 
                                       required>
                                @error('observation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label for="observation_time" class="form-label">
                                    <strong>Giờ quan sát</strong>
                                </label>
                                <input type="time" 
                                       class="form-control @error('observation_time') is-invalid @enderror" 
                                       id="observation_time" 
                                       name="observation_time" 
                                       value="{{ old('observation_time', $stop->observation_time ? $stop->observation_time->format('H:i') : '') }}">
                                @error('observation_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="location" class="form-label">
                                    <strong>Vị trí</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location', $stop->location) }}" 
                                       required>
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="equipment_name" class="form-label">
                                    <strong>Tên thiết bị</strong>
                                </label>
                                <input type="text" 
                                       class="form-control @error('equipment_name') is-invalid @enderror" 
                                       id="equipment_name" 
                                       name="equipment_name" 
                                       value="{{ old('equipment_name', $stop->equipment_name) }}">
                                @error('equipment_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="issue_description" class="form-label">
                                <strong>Vấn đề ghi nhận</strong> <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('issue_description') is-invalid @enderror" 
                                      id="issue_description" 
                                      name="issue_description" 
                                      rows="4" 
                                      required>{{ old('issue_description', $stop->issue_description) }}</textarea>
                            @error('issue_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="corrective_action" class="form-label">
                                <strong>Hành động khắc phục</strong> <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('corrective_action') is-invalid @enderror" 
                                      id="corrective_action" 
                                      name="corrective_action" 
                                      rows="4" 
                                      required>{{ old('corrective_action', $stop->corrective_action) }}</textarea>
                            @error('corrective_action')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <strong>Trạng thái</strong> <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="open" {{ old('status', $stop->status) == 'open' ? 'selected' : '' }}>Chưa xử lý</option>
                                    <option value="in-progress" {{ old('status', $stop->status) == 'in-progress' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="completed" {{ old('status', $stop->status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="completion_date" class="form-label">
                                    <strong>Ngày hoàn thành</strong>
                                </label>
                                <input type="date" 
                                       class="form-control @error('completion_date') is-invalid @enderror" 
                                       id="completion_date" 
                                       name="completion_date" 
                                       value="{{ old('completion_date', $stop->completion_date ? $stop->completion_date->format('Y-m-d') : '') }}">
                                <small class="text-muted">Chỉ cần nhập nếu trạng thái là "Hoàn thành"</small>
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
                                      rows="3">{{ old('notes', $stop->notes) }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('stops.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            @if($stop->status !== 'completed')
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>Cập nhật
                            </button>
                            @else
                            <button type="button" class="btn btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i>Đã hoàn thành
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
              <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-1"></i>Thông tin cập nhật
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $latestShiftLeaderUpdate = $stop->scoreHistories->firstWhere('scorer_type', 'shift_leader');
                        $latestSafetyOfficerUpdate = $stop->scoreHistories->firstWhere('scorer_type', 'safety_officer');

                        if ($stop->relationLoaded('shiftLeaderScorer') && $stop->shift_leader_scored_at) {
                            $latestShiftLeaderUpdate = (object) [
                                'scorer' => $stop->shiftLeaderScorer,
                                'scored_at' => $stop->shift_leader_scored_at,
                                'priority_level' => $stop->shift_leader_priority_level,
                                'note' => $stop->shift_leader_note,
                            ];
                        }

                        if ($stop->relationLoaded('safetyOfficerScorer') && $stop->safety_officer_scored_at) {
                            $latestSafetyOfficerUpdate = (object) [
                                'scorer' => $stop->safetyOfficerScorer,
                                'scored_at' => $stop->safety_officer_scored_at,
                                'priority_level' => $stop->safety_officer_priority_level,
                                'note' => $stop->safety_officer_note,
                            ];
                        }

                        $legacyUpdate = null;
                        if ($stop->scorer && $stop->priority_scored_at) {
                            $legacyUpdate = (object) [
                                'scorer' => $stop->scorer,
                                'scored_at' => $stop->priority_scored_at,
                                'priority_level' => $stop->priority_level,
                                'note' => $stop->notes,
                            ];
                        }

                        if (!$latestShiftLeaderUpdate && $legacyUpdate && $stop->scorer->isApprover() && !$stop->scorer->isTchcManager()) {
                            $latestShiftLeaderUpdate = $legacyUpdate;
                        }

                        if (!$latestSafetyOfficerUpdate && $legacyUpdate && (!$stop->scorer->isApprover() || $stop->scorer->isTchcManager())) {
                            $latestSafetyOfficerUpdate = $legacyUpdate;
                        }
                    @endphp

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

                    <!-- <div class="mb-3">
                        <label class="form-label text-muted small">Trưởng ca cập nhật</label>
                        @if($latestShiftLeaderUpdate)
                        <p class="mb-1">
                            <i class="fas fa-user-check me-1 text-primary"></i>{{ $latestShiftLeaderUpdate->scorer->full_name ?? $latestShiftLeaderUpdate->scorer->name ?? 'N/A' }}
                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="fas fa-clock me-1"></i>{{ $latestShiftLeaderUpdate->scored_at?->format('d/m/Y H:i') }}
                        </p>
                        <p class="mb-1 small">
                            <strong>Mức:</strong> {{ $latestShiftLeaderUpdate->priority_level !== null ? 'Mức ' . $latestShiftLeaderUpdate->priority_level : 'Chưa chấm' }}
                        </p>
                        <p class="mb-0 small"><strong>Ghi chú:</strong> {{ $latestShiftLeaderUpdate->note ?: 'Không có' }}</p>
                        @else
                        <p class="mb-0 text-muted">
                            <i class="fas fa-minus-circle me-1"></i>Chưa có cập nhật
                        </p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">CBAT cập nhật</label>
                        @if($latestSafetyOfficerUpdate)
                        <p class="mb-1">
                            <i class="fas fa-user-shield me-1 text-primary"></i>{{ $latestSafetyOfficerUpdate->scorer->full_name ?? $latestSafetyOfficerUpdate->scorer->name ?? 'N/A' }}
                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="fas fa-clock me-1"></i>{{ $latestSafetyOfficerUpdate->scored_at?->format('d/m/Y H:i') }}
                        </p>
                        <p class="mb-1 small">
                            <strong>Mức:</strong> {{ $latestSafetyOfficerUpdate->priority_level !== null ? 'Mức ' . $latestSafetyOfficerUpdate->priority_level : 'Chưa chấm' }}
                        </p>
                        <p class="mb-0 small"><strong>Ghi chú:</strong> {{ $latestSafetyOfficerUpdate->note ?: 'Không có' }}</p>
                        @else
                        <p class="mb-0 text-muted">
                            <i class="fas fa-minus-circle me-1"></i>Chưa có cập nhật
                        </p>
                        @endif
                    </div> -->

                    @if($stop->scoreHistories->isNotEmpty())
                    <hr>
                    <div>
                        <label class="form-label text-muted small">Lịch sử</label>
                        <div class="small" style="max-height: 320px; overflow-y: auto;">
                            @foreach($stop->scoreHistories as $history)
                            <div class="border rounded p-2 mb-2">
                                <div class="fw-semibold">{{ $history->getScorerTypeLabel() }}: {{ $history->scorer->full_name ?? $history->scorer->name ?? 'N/A' }}</div>
                                <div class="text-muted">{{ $history->scored_at?->format('d/m/Y H:i') }}</div>
                                <div>
                                    @if($history->previous_priority_level !== null)
                                    <span>Mức: {{ $history->previous_priority_level }}</span>
                                    <i class="fas fa-arrow-right mx-1"></i>
                                    @endif
                                    <span class="fw-semibold">{{ $history->priority_level !== null ? 'Mức: ' . $history->priority_level : 'Chưa chấm' }}</span>
                                </div>
                                @if($history->note)
                                <div class="mt-1"><strong>Ghi chú:</strong> {{ $history->note }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
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
        </div>
    </div>
</div>

@push('scripts')
<script>
// Disable form khi STOP đã hoàn thành
@if($stop->status === 'completed')
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        const inputs = form.querySelectorAll('input, select, textarea, button[type="submit"]');
        inputs.forEach(input => {
            input.disabled = true;
        });
    }
});
@endif

// Auto enable/disable completion date based on status
document.getElementById('status')?.addEventListener('change', function() {
    const completionDate = document.getElementById('completion_date');
    if (this.value === 'completed') {
        completionDate?.removeAttribute('disabled');
        if (!completionDate.value) {
            completionDate.value = new Date().toISOString().split('T')[0];
        }
    } else {
        if (completionDate) {
            completionDate.value = '';
        }
    }
});
</script>
@endpush
@endsection
