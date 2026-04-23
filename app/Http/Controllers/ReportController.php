<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    private function getShiftPersonnelRoster(string $shift): array
    {
        if ($shift === '') {
            return [];
        }

        return User::query()
            ->where('phone', $shift)
            ->where('is_active', true)
            ->orderByRaw('COALESCE(NULLIF(full_name, \'\'), name)')
            ->get(['name', 'full_name'])
            ->map(function (User $user) {
                return trim((string) ($user->full_name ?: $user->name));
            })
            ->filter(function ($name) {
                return $name !== '';
            })
            ->unique(function ($name) {
                return mb_strtolower($name);
            })
            ->values()
            ->all();
    }

    private function resolveFilters(Request $request): array
    {
        $shift = $request->input('shift');
        $periodType = $request->input('period_type', 'month');
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $quarter = (int) $request->input('quarter', (int) ceil(now()->month / 3));
        $issueCategory = $request->input('issue_category');

        if (!in_array($periodType, ['month', 'quarter'], true)) {
            $periodType = 'month';
        }
        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        if ($quarter < 1 || $quarter > 4) {
            $quarter = (int) ceil(now()->month / 3);
        }

        $periodMonths = [];
        if ($periodType === 'quarter') {
            $startMonth = (($quarter - 1) * 3) + 1;
            $from = Carbon::create($year, $startMonth, 1)->startOfMonth();
            $to = (clone $from)->addMonths(2)->endOfMonth();
            $periodMonths = [$startMonth, $startMonth + 1, $startMonth + 2];
        } else {
            $from = Carbon::create($year, $month, 1)->startOfMonth();
            $to = (clone $from)->endOfMonth();
            $periodMonths = [$month];
        }

        return [
            'shift' => $shift,
            'periodType' => $periodType,
            'year' => $year,
            'month' => $month,
            'quarter' => $quarter,
            'issueCategory' => $issueCategory,
            'from' => $from,
            'to' => $to,
            'periodMonths' => $periodMonths,
        ];
    }

    private function baseStopQuery(array $filters)
    {
        $query = Stop::with(['user:id,name,full_name,email,department'])
            ->whereDate('observation_date', '>=', $filters['from']->toDateString())
            ->whereDate('observation_date', '<=', $filters['to']->toDateString());

        if (!empty($filters['shift'])) {
            $query->where('observer_phone', $filters['shift']);
        }

        if (!empty($filters['issueCategory'])) {
            $query->where('issue_category', $filters['issueCategory']);
        }

        return $query;
    }

    private function buildExportParams(array $filters): array
    {
        return array_filter([
            'period_type' => $filters['periodType'],
            'year' => $filters['year'],
            'month' => $filters['month'],
            'quarter' => $filters['quarter'],
            'shift' => $filters['shift'],
            'issue_category' => $filters['issueCategory'],
        ], function ($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * Display STOP reports with filters
     */
    public function index(Request $request)
    {
        $filters = $this->resolveFilters($request);
        $shift = $filters['shift'];
        $periodType = $filters['periodType'];
        $year = $filters['year'];
        $month = $filters['month'];
        $quarter = $filters['quarter'];
        $issueCategory = $filters['issueCategory'];
        $periodMonths = $filters['periodMonths'];
        $shiftPersonnelRoster = $this->getShiftPersonnelRoster((string) ($shift ?? ''));

        $stops = $this->baseStopQuery($filters)->get();
        $baseExportParams = $this->buildExportParams($filters);
        $exportAllPersonalUrl = route('reports.export-modal', array_merge($baseExportParams, [
            'scope' => 'all',
            'report_type' => 'personal',
        ]));
        $exportAllShiftUrl = route('reports.export-modal', array_merge($baseExportParams, [
            'scope' => 'all',
            'report_type' => 'shift',
        ]));
        $exportAllIssueUrl = route('reports.export-modal', array_merge($baseExportParams, [
            'scope' => 'all',
            'report_type' => 'issue',
        ]));
        $exportAllPriorityUrl = route('reports.export-modal', array_merge($baseExportParams, [
            'scope' => 'all',
            'report_type' => 'priority',
        ]));

        $monthColumns = collect($periodMonths)->map(function ($monthNumber) use ($year) {
            return [
                'key' => sprintf('%d-%02d', $year, $monthNumber),
                'label' => 'Th' . $monthNumber,
            ];
        })->values();

        // Thống kê theo mức độ
        $priorityStats = [
            'level_0' => $stops->where('priority_level', 0)->count(),
            'level_1' => $stops->where('priority_level', 1)->count(),
            'level_2' => $stops->where('priority_level', 2)->count(),
            'level_3' => $stops->where('priority_level', 3)->count(),
            'unscored' => $stops->whereNull('priority_level')->count(),
        ];

        $emptyMonths = $monthColumns->pluck('key')->mapWithKeys(function ($key) {
            return [$key => 0];
        })->all();

        $issueLabels = Stop::getIssueCategories();

        $personalStats = [];
        $shiftStats = [];
        $personStopMap = [];
        $shiftStopMap = [];
        $issueStopMap = [];
        $priorityStopMap = [];

        $formatStopRow = function (Stop $stop, string $shiftName): array {
            return [
                'id' => $stop->id,
                'date' => $stop->observation_date ? $stop->observation_date->format('d/m/Y') : '-',
                'observer_name' => $stop->observer_name,
                'shift' => $shiftName,
                'issue_category' => $stop->getCategoryLabel(),
                'priority_label' => $stop->priority_level === null ? 'Chưa chấm' : $stop->getPriorityLabel(),
                'status_label' => $stop->getStatusLabel(),
                'location' => $stop->location,
                'detail_url' => route('stops.show', $stop),
            ];
        };

        foreach ($stops as $stop) {
            $monthKey = $stop->observation_date->format('Y-m');
            $shiftName = trim((string) $stop->observer_phone);
            if ($shiftName === '') {
                $shiftName = 'Chưa khai báo';
            }

            $personKey = mb_strtolower(trim((string) $stop->observer_name)) . '|' . $shiftName;
            $personModalKey = md5($personKey);

            if (!isset($personalStats[$personKey])) {
                $personalStats[$personKey] = [
                    'name' => $stop->observer_name,
                    'shift' => $shiftName,
                    'modal_key' => $personModalKey,
                    'months' => $emptyMonths,
                    'total' => 0,
                ];
            }
            if (isset($personalStats[$personKey]['months'][$monthKey])) {
                $personalStats[$personKey]['months'][$monthKey]++;
            }
            $personalStats[$personKey]['total']++;

            if (!isset($personStopMap[$personModalKey])) {
                $personStopMap[$personModalKey] = [
                    'title' => $stop->observer_name . ' - Ca ' . $shiftName,
                    'export_url' => route('reports.export-modal', array_merge($baseExportParams, [
                        'scope' => 'person',
                        'observer_name' => $stop->observer_name,
                        'observer_shift' => $shiftName,
                    ])),
                    'stops' => [],
                ];
            }
            $personStopMap[$personModalKey]['stops'][] = $formatStopRow($stop, $shiftName);

            if (!isset($shiftStats[$shiftName])) {
                $shiftStats[$shiftName] = [
                    'shift' => $shiftName,
                    'months' => $emptyMonths,
                    'total' => 0,
                ];
            }
            if (isset($shiftStats[$shiftName]['months'][$monthKey])) {
                $shiftStats[$shiftName]['months'][$monthKey]++;
            }
            $shiftStats[$shiftName]['total']++;

            if (!isset($shiftStopMap[$shiftName])) {
                $shiftStopMap[$shiftName] = [
                    'title' => 'Ca ' . $shiftName,
                    'export_url' => route('reports.export-modal', array_merge($baseExportParams, [
                        'scope' => 'shift',
                        'observer_shift' => $shiftName,
                    ])),
                    'stops' => [],
                ];
            }
            $shiftStopMap[$shiftName]['stops'][] = $formatStopRow($stop, $shiftName);

            $issueKey = (string) $stop->issue_category;
            if (!isset($issueStopMap[$issueKey])) {
                $issueStopMap[$issueKey] = [
                    'title' => 'Loại vấn đề: ' . ($issueLabels[$issueKey] ?? $issueKey),
                    'export_url' => route('reports.export-modal', array_merge($baseExportParams, [
                        'scope' => 'issue',
                        'scope_issue' => $issueKey,
                    ])),
                    'stops' => [],
                ];
            }
            $issueStopMap[$issueKey]['stops'][] = $formatStopRow($stop, $shiftName);

            $priorityKey = $stop->priority_level === null ? 'unscored' : 'level_' . $stop->priority_level;
            if (!isset($priorityStopMap[$priorityKey])) {
                $priorityLabel = $stop->priority_level === null ? 'Chưa chấm' : $stop->getPriorityLabel();
                $priorityStopMap[$priorityKey] = [
                    'title' => 'Mức độ: ' . $priorityLabel,
                    'export_url' => route('reports.export-modal', array_merge($baseExportParams, [
                        'scope' => 'priority',
                        'scope_priority' => $priorityKey,
                    ])),
                    'stops' => [],
                ];
            }
            $priorityStopMap[$priorityKey]['stops'][] = $formatStopRow($stop, $shiftName);
        }

        if (!empty($shift) && !empty($shiftPersonnelRoster)) {
            foreach ($shiftPersonnelRoster as $observerName) {
                $personKey = mb_strtolower(trim((string) $observerName)) . '|' . $shift;
                $personModalKey = md5($personKey);

                if (!isset($personalStats[$personKey])) {
                    $personalStats[$personKey] = [
                        'name' => $observerName,
                        'shift' => $shift,
                        'modal_key' => $personModalKey,
                        'months' => $emptyMonths,
                        'total' => 0,
                    ];
                }

                if (!isset($personStopMap[$personModalKey])) {
                    $personStopMap[$personModalKey] = [
                        'title' => $observerName . ' - Ca ' . $shift,
                        'export_url' => route('reports.export-modal', array_merge($baseExportParams, [
                            'scope' => 'person',
                            'observer_name' => $observerName,
                            'observer_shift' => $shift,
                        ])),
                        'stops' => [],
                    ];
                }
            }
        }

        if (!empty($shift) && !isset($shiftStats[$shift])) {
            $shiftStats[$shift] = [
                'shift' => $shift,
                'months' => $emptyMonths,
                'total' => 0,
            ];
        }

        if (!empty($shift) && !isset($shiftStopMap[$shift])) {
            $shiftStopMap[$shift] = [
                'title' => 'Ca ' . $shift,
                'export_url' => route('reports.export-modal', array_merge($baseExportParams, [
                    'scope' => 'shift',
                    'observer_shift' => $shift,
                ])),
                'stops' => [],
            ];
        }

        $personalStats = collect(array_values($personalStats))->sortByDesc('total')->values();
        $shiftStats = collect(array_values($shiftStats))->sortByDesc('total')->values();

        $issueTypeStats = collect($issueLabels)->map(function ($label, $key) use ($stops) {
            return [
                'key' => $key,
                'label' => $label,
                'count' => $stops->where('issue_category', $key)->count(),
            ];
        });

        if (empty($shift)) {
            $issueTypeStats = $issueTypeStats->filter(function ($item) {
                return $item['count'] > 0;
            });
        }

        $issueTypeStats = $issueTypeStats->sortByDesc('count')->values();

        $selectedIssueShiftStats = collect();
        if ($issueCategory) {
            $selectedIssueShiftStats = $stops
                ->groupBy(function ($stop) {
                    $shiftName = trim((string) $stop->observer_phone);
                    return $shiftName !== '' ? $shiftName : 'Chưa khai báo';
                })
                ->map(function ($items, $shiftName) {
                    return [
                        'shift' => $shiftName,
                        'count' => $items->count(),
                    ];
                })
                ->sortByDesc('count')
                ->values();
        }

        $priorityLevelRows = [
            ['key' => 'level_0', 'label' => 'Mức 0'],
            ['key' => 'level_1', 'label' => 'Mức 1'],
            ['key' => 'level_2', 'label' => 'Mức 2'],
            ['key' => 'level_3', 'label' => 'Mức 3'],
            ['key' => 'unscored', 'label' => 'Chưa chấm'],
        ];

        $priorityPeriodStats = collect($priorityLevelRows)->map(function ($row) use ($emptyMonths) {
            return [
                'key' => $row['key'],
                'label' => $row['label'],
                'months' => $emptyMonths,
                'total' => 0,
            ];
        })->keyBy('key')->all();

        foreach ($stops as $stop) {
            $monthKey = $stop->observation_date->format('Y-m');
            $priorityKey = $stop->priority_level === null ? 'unscored' : 'level_' . $stop->priority_level;

            if (isset($priorityPeriodStats[$priorityKey]['months'][$monthKey])) {
                $priorityPeriodStats[$priorityKey]['months'][$monthKey]++;
            }
            $priorityPeriodStats[$priorityKey]['total']++;
        }

        $priorityPeriodStats = collect($priorityLevelRows)
            ->map(function ($row) use ($priorityPeriodStats) {
                return $priorityPeriodStats[$row['key']];
            })
            ->values();

        $issueTypeStats = $issueTypeStats->map(function ($row) {
            $row['modal_key'] = $row['key'];
            return $row;
        });

        $priorityPeriodStats = $priorityPeriodStats->map(function ($row) {
            $row['modal_key'] = $row['key'];
            return $row;
        });

        // Thống kê tổng quan
        $totalStats = [
            'total_stops' => $stops->count(),
            'open' => $stops->where('status', 'open')->count(),
            'in_progress' => $stops->where('status', 'in-progress')->count(),
            'completed' => $stops->where('status', 'completed')->count(),
        ];

        $periodLabel = $periodType === 'quarter'
            ? 'Quý ' . $quarter . '/' . $year . ' (Tháng ' . implode(', ', $periodMonths) . ')'
            : 'Tháng ' . $month . '/' . $year;

        $years = range((int) now()->year - 3, (int) now()->year + 1);

        return view('stops.report', compact(
            'shift',
            'periodType',
            'year',
            'month',
            'quarter',
            'years',
            'periodLabel',
            'issueCategory',
            'monthColumns',
            'priorityStats',
            'priorityPeriodStats',
            'issueTypeStats',
            'selectedIssueShiftStats',
            'personalStats',
            'shiftStats',
            'personStopMap',
            'shiftStopMap',
            'issueStopMap',
            'priorityStopMap',
            'exportAllPersonalUrl',
            'exportAllShiftUrl',
            'exportAllIssueUrl',
            'exportAllPriorityUrl',
            'totalStats',
            'issueLabels'
        ));
    }

    public function exportModalReport(Request $request)
    {
        $scope = $request->input('scope');
        if (!in_array($scope, ['all', 'person', 'shift', 'issue', 'priority'], true)) {
            abort(422, 'Phạm vi export không hợp lệ.');
        }

        $filters = $this->resolveFilters($request);
        $query = $this->baseStopQuery($filters);

        $reportType = (string) $request->input('report_type', 'all');
        $title = 'Danh sach STOP';
        if ($scope === 'all') {
            $typeLabels = [
                'personal' => 'Tong hop ca nhan',
                'shift' => 'Tong hop ca kip',
                'issue' => 'Tong hop loai van de',
                'priority' => 'Tong hop muc do',
                'all' => 'Tong hop STOP',
            ];
            $title = $typeLabels[$reportType] ?? $typeLabels['all'];
        }
        if ($scope === 'person') {
            $observerName = (string) $request->input('observer_name', '');
            $observerShift = (string) $request->input('observer_shift', '');
            if ($observerName === '') {
                abort(422, 'Thiếu tên nhân sự để export.');
            }
            $query->where('observer_name', $observerName);
            if ($observerShift !== '') {
                $query->where('observer_phone', $observerShift);
            }
            $title = 'Nhan su ' . $observerName . ' ca ' . ($observerShift !== '' ? $observerShift : 'khac');
        }

        if ($scope === 'shift') {
            $observerShift = (string) $request->input('observer_shift', '');
            if ($observerShift === '') {
                abort(422, 'Thiếu ca/kíp để export.');
            }
            $query->where('observer_phone', $observerShift);
            $title = 'Ca ' . $observerShift;
        }

        if ($scope === 'issue') {
            $scopeIssue = (string) $request->input('scope_issue', '');
            if ($scopeIssue === '') {
                abort(422, 'Thiếu loại vấn đề để export.');
            }
            $query->where('issue_category', $scopeIssue);
            $title = 'Loai van de ' . $scopeIssue;
        }

        if ($scope === 'priority') {
            $scopePriority = (string) $request->input('scope_priority', '');
            if ($scopePriority === 'unscored') {
                $query->whereNull('priority_level');
                $title = 'Muc do chua cham';
            } else {
                $priorityLevel = (int) str_replace('level_', '', $scopePriority);
                if (!in_array($priorityLevel, [0, 1, 2, 3], true)) {
                    abort(422, 'Thiếu mức độ để export.');
                }
                $query->where('priority_level', $priorityLevel);
                $title = 'Muc do ' . $priorityLevel;
            }
        }

        $stops = $query->orderBy('observation_date', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('STOP');

        $headers = ['STT', 'Ngay', 'Nguoi ghi nhan', 'Ca/kip', 'Loai van de', 'Muc do', 'Trang thai', 'Vi tri', 'Thiet bi', 'Noi dung', 'De xuat hanh dong'];
        foreach ($headers as $columnIndex => $header) {
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, 1, $header);
        }

        foreach ($stops as $index => $stop) {
            $row = $index + 2;
            $sheet->setCellValueByColumnAndRow(1, $row, $index + 1);
            $sheet->setCellValueByColumnAndRow(2, $row, $stop->observation_date ? $stop->observation_date->format('d/m/Y') : '-');
            $sheet->setCellValueByColumnAndRow(3, $row, $stop->observer_name);
            $sheet->setCellValueByColumnAndRow(4, $row, $stop->observer_phone ?: 'Chua khai bao');
            $sheet->setCellValueByColumnAndRow(5, $row, $stop->getCategoryLabel());
            $sheet->setCellValueByColumnAndRow(6, $row, $stop->priority_level === null ? 'Chua cham' : $stop->getPriorityLabel());
            $sheet->setCellValueByColumnAndRow(7, $row, $stop->getStatusLabel());
            $sheet->setCellValueByColumnAndRow(8, $row, $stop->location);
            $sheet->setCellValueByColumnAndRow(9, $row, $stop->equipment_name ?? '-');
            $sheet->setCellValueByColumnAndRow(10, $row, $stop->issue_description);
            $sheet->setCellValueByColumnAndRow(11, $row, $stop->corrective_action);
        }

        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $fileName = 'stop-report-' . preg_replace('/[^a-zA-Z0-9\-_]/', '-', strtolower($title)) . '-' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
