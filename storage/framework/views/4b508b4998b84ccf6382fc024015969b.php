

<?php $__env->startSection('title', 'Báo cáo STOP'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .report-page-header {
        margin-top: 20px;
        gap: 0.75rem;
    }

    .report-table {
        min-width: 760px;
    }

    .report-link-button {
        border: none;
        background: transparent;
        color: #0d6efd;
        font-weight: 600;
        padding: 0;
        text-decoration: none;
        cursor: pointer;
    }

    .report-link-button:hover {
        color: #0a58ca;
    }

    @media (max-width: 991.98px) {
        .report-page-header {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .report-page-header .btn {
            width: 100%;
        }

        .report-filter-submit .btn {
            width: 100%;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 report-page-header">
        <h1 style="font-size: 1.5rem; font-weight: 500; color: #333;">
            <i class="fas fa-chart-bar text-primary me-2"></i>
            Báo cáo STOP
        </h1>
        <!-- <a href="<?php echo e(route('stops.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a> -->
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Bộ lọc báo cáo
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('reports.index')); ?>" class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label">Kiểu kỳ báo cáo</label>
                    <select name="period_type" id="period_type" class="form-select">
                        <option value="month" <?php echo e($periodType === 'month' ? 'selected' : ''); ?>>Theo tháng</option>
                        <option value="quarter" <?php echo e($periodType === 'quarter' ? 'selected' : ''); ?>>Theo quý</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label">Năm</label>
                    <select name="year" class="form-select">
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($yearOption); ?>" <?php echo e((int) $year === (int) $yearOption ? 'selected' : ''); ?>><?php echo e($yearOption); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2" id="month_filter_wrapper">
                    <label class="form-label">Tháng</label>
                    <select name="month" class="form-select">
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo e($m); ?>" <?php echo e((int) $month === $m ? 'selected' : ''); ?>>Tháng <?php echo e($m); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2" id="quarter_filter_wrapper">
                    <label class="form-label">Quý</label>
                    <select name="quarter" class="form-select">
                        <option value="1" <?php echo e((int) $quarter === 1 ? 'selected' : ''); ?>>Quý 1 (1,2,3)</option>
                        <option value="2" <?php echo e((int) $quarter === 2 ? 'selected' : ''); ?>>Quý 2 (4,5,6)</option>
                        <option value="3" <?php echo e((int) $quarter === 3 ? 'selected' : ''); ?>>Quý 3 (7,8,9)</option>
                        <option value="4" <?php echo e((int) $quarter === 4 ? 'selected' : ''); ?>>Quý 4 (10,11,12)</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label">Ca/kíp</label>
                    <select name="shift" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="HTSX" <?php echo e($shift === 'HTSX' ? 'selected' : ''); ?>>HTSX</option>
                        <option value="VH01" <?php echo e($shift === 'VH01' ? 'selected' : ''); ?>>VH01</option>
                        <option value="VH02" <?php echo e($shift === 'VH02' ? 'selected' : ''); ?>>VH02</option>
                        <option value="VH03" <?php echo e($shift === 'VH03' ? 'selected' : ''); ?>>VH03</option>
                        <option value="VH04" <?php echo e($shift === 'VH04' ? 'selected' : ''); ?>>VH04</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label">Loại vấn đề</label>
                    <select name="issue_category" class="form-select">
                        <option value="">Tất cả loại vấn đề</option>
                        <?php $__currentLoopData = $issueLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issueKey => $issueLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($issueKey); ?>" <?php echo e($issueCategory === $issueKey ? 'selected' : ''); ?>><?php echo e($issueLabel); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-12 col-lg-2 report-filter-submit">
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-search me-1"></i>Tạo báo cáo
                    </button>
                </div>
            </form>
            <div class="mt-3 text-muted">
                <small><i class="fas fa-calendar-alt me-1"></i>Kỳ đang xem: <strong><?php echo e($periodLabel); ?></strong></small>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-clipboard-list me-2"></i>Tổng số STOP</h6>
                    <h2 class="mb-0"><?php echo e($totalStats['total_stops']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-exclamation-circle me-2"></i>Chưa xử lý</h6>
                    <h2 class="mb-0"><?php echo e($totalStats['open']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-spinner me-2"></i>Đang xử lý</h6>
                    <h2 class="mb-0"><?php echo e($totalStats['in_progress']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-check-circle me-2"></i>Hoàn thành</h6>
                    <h2 class="mb-0"><?php echo e($totalStats['completed']); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Tổng hợp theo mức độ thẻ STOP</h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Tổng hợp theo loại vấn đề</h5>
                </div>
                <div class="card-body">
                    <canvas id="issueChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Thống kê số lượng STOP theo cá nhân trong kỳ</h5>
                <a href="<?php echo e($exportAllPersonalUrl); ?>" class="btn btn-sm btn-success" target="_blank" rel="noopener">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if($personalStats->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered report-table">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Họ tên</th>
                                <th>Ca/kíp</th>
                                <?php $__currentLoopData = $monthColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthColumn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="text-center"><?php echo e($monthColumn['label']); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $personalStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <button type="button" class="report-link-button js-open-person-modal" data-person-key="<?php echo e($person['modal_key']); ?>">
                                            <?php echo e($person['name']); ?>

                                        </button>
                                    </td>
                                    <td><?php echo e($person['shift']); ?></td>
                                    <?php $__currentLoopData = $monthColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthColumn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center"><?php echo e($person['months'][$monthColumn['key']] ?? 0); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center"><strong><?php echo e($person['total']); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    Không có dữ liệu cá nhân trong kỳ đã chọn.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Thống kê số lượng STOP theo ca/kíp trong kỳ</h5>
                <a href="<?php echo e($exportAllShiftUrl); ?>" class="btn btn-sm btn-success" target="_blank" rel="noopener">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if($shiftStats->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered report-table">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Ca/kíp</th>
                                <?php $__currentLoopData = $monthColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthColumn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="text-center"><?php echo e($monthColumn['label']); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $shiftStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $shiftRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <button type="button" class="report-link-button js-open-shift-modal" data-shift-key="<?php echo e($shiftRow['shift']); ?>">
                                            <?php echo e($shiftRow['shift']); ?>

                                        </button>
                                    </td>
                                    <?php $__currentLoopData = $monthColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthColumn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center"><?php echo e($shiftRow['months'][$monthColumn['key']] ?? 0); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center"><strong><?php echo e($shiftRow['total']); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    Không có dữ liệu ca/kíp trong kỳ đã chọn.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Thống kê số lượng STOP theo loại vấn đề</h5>
                <a href="<?php echo e($exportAllIssueUrl); ?>" class="btn btn-sm btn-success" target="_blank" rel="noopener">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if($issueTypeStats->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Loại vấn đề</th>
                                <th class="text-center" width="20%">Số lượng STOP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $issueTypeStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $issueType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <button type="button" class="report-link-button js-open-issue-modal" data-issue-key="<?php echo e($issueType['modal_key']); ?>">
                                            <?php echo e($issueType['label']); ?>

                                        </button>
                                    </td>
                                    <td class="text-center"><strong><?php echo e($issueType['count']); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    Không có dữ liệu loại vấn đề trong kỳ đã chọn.
                </div>
            <?php endif; ?>

            <?php if($issueCategory): ?>
                <hr>
                <h6>
                    Danh sách theo ca/kíp cho loại vấn đề:
                    <span class="badge bg-info"><?php echo e($issueLabels[$issueCategory] ?? $issueCategory); ?></span>
                </h6>
                <?php if($selectedIssueShiftStats->count() > 0): ?>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Ca/kíp</th>
                                    <th class="text-center" width="20%">Số lượng STOP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $selectedIssueShiftStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($row['shift']); ?></td>
                                        <td class="text-center"><strong><?php echo e($row['count']); ?></strong></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">Không có dữ liệu theo ca/kíp cho loại vấn đề đã chọn.</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-signal me-2"></i>Thống kê mức độ thẻ STOP theo tháng/quý</h5>
                <a href="<?php echo e($exportAllPriorityUrl); ?>" class="btn btn-sm btn-success" target="_blank" rel="noopener">
                    <i class="fas fa-file-excel me-1"></i>Report tất cả
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if($priorityPeriodStats->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered report-table">
                        <thead class="table-light">
                            <tr>
                                <th>Mức độ</th>
                                <?php $__currentLoopData = $monthColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthColumn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="text-center"><?php echo e($monthColumn['label']); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center">Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $priorityPeriodStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <button type="button" class="report-link-button js-open-priority-modal" data-priority-key="<?php echo e($row['modal_key']); ?>">
                                            <strong><?php echo e($row['label']); ?></strong>
                                        </button>
                                    </td>
                                    <?php $__currentLoopData = $monthColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthColumn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center"><?php echo e($row['months'][$monthColumn['key']] ?? 0); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center"><strong><?php echo e($row['total']); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    Không có dữ liệu mức độ trong kỳ đã chọn.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="stopListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stopListModalTitle">Danh sách thẻ STOP</h5>
                    <a id="stopListModalExportBtn" href="#" class="btn btn-sm btn-success me-2" target="_blank" rel="noopener">
                        <i class="fas fa-file-excel me-1"></i>Report Excel
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Ngày</th>
                                    <th>Người ghi nhận</th>
                                    <th>Ca/kíp</th>
                                    <th>Loại vấn đề</th>
                                    <th>Mức độ</th>
                                    <th>Trạng thái</th>
                                    <th>Vị trí</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody id="stopListModalBody">
                            </tbody>
                        </table>
                    </div>
                    <div id="stopListModalEmpty" class="text-center text-muted py-4 d-none">
                        Không có thẻ STOP.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const periodTypeElement = document.getElementById('period_type');
    const monthWrapper = document.getElementById('month_filter_wrapper');
    const quarterWrapper = document.getElementById('quarter_filter_wrapper');

    function togglePeriodInputs() {
        const isQuarter = periodTypeElement.value === 'quarter';
        monthWrapper.style.display = isQuarter ? 'none' : '';
        quarterWrapper.style.display = isQuarter ? '' : 'none';
    }

    togglePeriodInputs();
    periodTypeElement.addEventListener('change', togglePeriodInputs);

    const priorityData = {
        level0: <?php echo e($priorityStats['level_0']); ?>,
        level1: <?php echo e($priorityStats['level_1']); ?>,
        level2: <?php echo e($priorityStats['level_2']); ?>,
        level3: <?php echo e($priorityStats['level_3']); ?>,
        unscored: <?php echo e($priorityStats['unscored']); ?>

    };

    const issueLabels = <?php echo json_encode($issueTypeStats->pluck('label')->values(), 15, 512) ?>;
    const issueCounts = <?php echo json_encode($issueTypeStats->pluck('count')->values(), 15, 512) ?>;
    const personStopMap = <?php echo json_encode($personStopMap, 15, 512) ?>;
    const shiftStopMap = <?php echo json_encode($shiftStopMap, 15, 512) ?>;
    const issueStopMap = <?php echo json_encode($issueStopMap, 15, 512) ?>;
    const priorityStopMap = <?php echo json_encode($priorityStopMap, 15, 512) ?>;

    const priorityCanvas = document.getElementById('priorityChart');
    if (priorityCanvas) {
        new Chart(priorityCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Mức 0', 'Mức 1', 'Mức 2', 'Mức 3', 'Chưa chấm'],
                datasets: [{
                    data: [priorityData.level0, priorityData.level1, priorityData.level2, priorityData.level3, priorityData.unscored],
                    backgroundColor: ['#dc3545', '#fd7e14', '#0dcaf0', '#198754', '#6c757d'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    const issueCanvas = document.getElementById('issueChart');
    if (issueCanvas) {
        new Chart(issueCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: issueLabels,
                datasets: [{
                    label: 'Số lượng STOP',
                    data: issueCounts,
                    backgroundColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    const stopListModalElement = document.getElementById('stopListModal');
    const stopListModalTitle = document.getElementById('stopListModalTitle');
    const stopListModalBody = document.getElementById('stopListModalBody');
    const stopListModalEmpty = document.getElementById('stopListModalEmpty');
    const stopListModalExportBtn = document.getElementById('stopListModalExportBtn');
    const stopListModal = new bootstrap.Modal(stopListModalElement);

    function renderStopRows(data) {
        stopListModalBody.innerHTML = '';

        if (!data || !Array.isArray(data.stops) || data.stops.length === 0) {
            stopListModalEmpty.classList.remove('d-none');
            return;
        }

        stopListModalEmpty.classList.add('d-none');

        data.stops.forEach(function (stop, index) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${stop.date}</td>
                <td>${stop.observer_name}</td>
                <td>${stop.shift}</td>
                <td>${stop.issue_category}</td>
                <td>${stop.priority_label}</td>
                <td>${stop.status_label}</td>
                <td>${stop.location ?? '-'}</td>
                <td><a href="${stop.detail_url}" class="btn btn-sm btn-outline-primary">Xem</a></td>
            `;
            stopListModalBody.appendChild(tr);
        });
    }

    function openStopModal(data, fallbackTitle) {
        stopListModalTitle.textContent = data ? data.title : fallbackTitle;
        if (data && data.export_url) {
            stopListModalExportBtn.href = data.export_url;
            stopListModalExportBtn.classList.remove('d-none');
        } else {
            stopListModalExportBtn.href = '#';
            stopListModalExportBtn.classList.add('d-none');
        }
        renderStopRows(data);
        stopListModal.show();
    }

    document.querySelectorAll('.js-open-person-modal').forEach(function (button) {
        button.addEventListener('click', function () {
            const key = button.getAttribute('data-person-key');
            const data = personStopMap[key];
            openStopModal(data, 'Danh sách thẻ STOP');
        });
    });

    document.querySelectorAll('.js-open-shift-modal').forEach(function (button) {
        button.addEventListener('click', function () {
            const key = button.getAttribute('data-shift-key');
            const data = shiftStopMap[key];
            openStopModal(data, 'Danh sách thẻ STOP');
        });
    });

    document.querySelectorAll('.js-open-issue-modal').forEach(function (button) {
        button.addEventListener('click', function () {
            const key = button.getAttribute('data-issue-key');
            const data = issueStopMap[key];
            openStopModal(data, 'Danh sách thẻ STOP theo loại vấn đề');
        });
    });

    document.querySelectorAll('.js-open-priority-modal').forEach(function (button) {
        button.addEventListener('click', function () {
            const key = button.getAttribute('data-priority-key');
            const data = priorityStopMap[key];
            openStopModal(data, 'Danh sách thẻ STOP theo mức độ');
        });
    });
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views/stops/report.blade.php ENDPATH**/ ?>