<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use App\Models\StopScoreHistory;
use App\Models\User;
use App\Services\StopNotificationService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class StopController extends Controller
{
    /**
     * Display a listing of STOP observations
     */
    public function index()
    {
        $shouldLogPerformance = request()->boolean('perf');
        $requestStart = microtime(true);

        if ($shouldLogPerformance) {
            DB::flushQueryLog();
            DB::enableQueryLog();
        }

        $query = Stop::with([
                        'user:id,name,full_name,email',
                        'scorer:id,name,full_name',
                    ])
                     ->orderBy('created_at', 'desc');  // Sắp xếp theo thời gian đăng ký mới nhất


        //  Lọc theo loại vấn đề nếu có
        // Filter by observer name if provided
        
        if (request('observer_name')) {
            $query->where('observer_name', 'like', '%' . request('observer_name') . '%');
        }
        
        // Filter by email if provided
        if (request('email')) {
            $query->whereHas('user', function($q) {
                $q->where('email', 'like', '%' . request('email') . '%');
            });
        }
        
        // Filter by shift (Ca/kíp) if provided
        if (request('shift')) {
            $query->where('observer_phone', request('shift'));
        }

        if (request('issue_category')) {
            $query->where('issue_category', request('issue_category'));
        }

        // Filter by status if provided
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by date range if provided
        if (request('from_date')) {
            $query->whereDate('observation_date', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $query->whereDate('observation_date', '<=', request('to_date'));
        }

        $stops = $query->paginate(15);

        if ($shouldLogPerformance) {
            $queryLog = DB::getQueryLog();
            $totalDbTimeMs = array_sum(array_column($queryLog, 'time'));
            $totalRequestTimeMs = (microtime(true) - $requestStart) * 1000;

            Log::info('STOP index performance', [
                'url' => request()->fullUrl(),
                'query_count' => count($queryLog),
                'db_time_ms' => round($totalDbTimeMs, 2),
                'total_time_ms' => round($totalRequestTimeMs, 2),
                'peak_memory_mb' => round(memory_get_peak_usage(true) / 1048576, 2),
                'result_count' => $stops->count(),
                'page' => $stops->currentPage(),
            ]);

            DB::disableQueryLog();
        }

        return view('stops.index', compact('stops'));
    }

    /**
     * Show the form for creating a new STOP observation
     */
    public function create()
    {
        return view('stops.create');
    }

    /**
     * Store a newly created STOP observation
     */
    public function store(Request $request, StopNotificationService $stopNotificationService)
    {
        $validated = $request->validate([
            'issue_category' => 'required|string',
            'priority_level' => 'nullable|integer|in:0,1,2,3',
            'observer_name' => 'required|string|max:255',
            'observer_phone' => 'required|string',
            'observation_date' => 'required|date',
            'observation_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'equipment_name' => 'nullable|string|max:255',
            'issue_description' => 'required|string',
            'corrective_action' => 'required|string',
            'status' => 'required|in:open,in-progress,completed',
            'completion_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        
        // Nhân viên tạo mới thì mức độ để NULL (Chưa chấm)
        $validated['priority_level'] = null;

        $stop = Stop::create($validated);

        try {
            $stopNotificationService->sendNewStopCreatedNotification($stop);
        } catch (\Throwable $exception) {
            // Do not block STOP creation if email delivery fails.
            Log::error('Failed to send new STOP notification email.', [
                'stop_id' => $stop->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()->route('stops.index')->with('success', 'Ghi nhận STOP thành công!');
    }

    /**
     * Display the specified STOP observation
     */
    public function show(Stop $stop)
    {   
        $relations = ['user', 'scorer'];

        if (Schema::hasColumn('stops', 'shift_leader_scored_by')) {
            $relations[] = 'shiftLeaderScorer';
            $relations[] = 'safetyOfficerScorer';
        }

        if (Schema::hasTable('stop_score_histories')) {
            $relations[] = 'scoreHistories.scorer';
        }

        $stop->load($relations);

        if (!Schema::hasTable('stop_score_histories')) {
            $stop->setRelation('scoreHistories', new EloquentCollection());
        }

        return view('stops.show', compact('stop'));
    }

    /**
     * Show the form for editing the specified STOP observation
     */
    public function edit(Stop $stop)
    {
        $relations = ['user', 'scorer'];

        if (Schema::hasColumn('stops', 'shift_leader_scored_by')) {
            $relations[] = 'shiftLeaderScorer';
            $relations[] = 'safetyOfficerScorer';
        }

        if (Schema::hasTable('stop_score_histories')) {
            $relations[] = 'scoreHistories.scorer';
        }

        $stop->load($relations);

        if (!Schema::hasTable('stop_score_histories')) {
            $stop->setRelation('scoreHistories', new EloquentCollection());
        }

        return view('stops.edit', compact('stop'));
    }

    /**
     * Update the specified STOP observation
     */
    public function update(Request $request, Stop $stop, StopNotificationService $stopNotificationService)
    {
        // Ngăn chỉnh sửa STOP đã hoàn thành
        if ($stop->status === 'completed') {
            return redirect()->route('stops.index')->with('error', 'STOP đã hoàn thành, không thể chỉnh sửa!');
        }

        $validated = $request->validate([
            'issue_category' => 'required|string',
            'priority_level' => 'nullable|integer|in:0,1,2,3',
            'score_note' => 'nullable|string|max:1000',
            'observer_name' => 'required|string|max:255',
            'observer_phone' => 'required|string',
            'observation_date' => 'required|date',
            'observation_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'equipment_name' => 'nullable|string|max:255',
            'issue_description' => 'required|string',
            'corrective_action' => 'required|string',
            'status' => 'required|in:open,in-progress,completed',
            'completion_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $previousPriorityLevel = $stop->priority_level;
        $scoreNote = $validated['score_note'] ?? null;

        // Ngăn đặt trạng thái "Hoàn thành" khi chưa có điểm mức độ (priority_level)
        $effectivePriorityLevel = $validated['priority_level'] ?? $stop->priority_level;
        if ($validated['status'] === 'completed' && $effectivePriorityLevel === null) {
            return back()->withErrors([
                'status' => 'Không thể đặt trạng thái "Hoàn thành" khi thẻ STOP chưa được chấm điểm mức độ.'
            ])->withInput();
        }

        // CHỈ khi priority_level thay đổi → Tự động chuyển trạng thái
        if (isset($validated['priority_level']) && $validated['priority_level'] != $stop->priority_level) {
            // Cập nhật người chấm và thời gian
            $validated['priority_scored_by'] = Auth::id();
            $validated['priority_scored_at'] = now();
            
            // Chỉ chuyển trạng thái khi priority_level là số thực (0,1,2,3), không phải NULL
            if ($validated['priority_level'] !== null && in_array($validated['priority_level'], [0, 1, 2, 3])) {
                // Nếu là Trưởng ca/Approver chấm điểm → Đang xử lý
                if ($user->isApprover() && !$user->isTchcManager()) {
                    $validated['status'] = 'in-progress';
                }
                
                // Nếu là TCHC Manager chấm điểm → Hoàn thành
                if ($user->isTchcManager()) {
                    $validated['status'] = 'completed';
                    $validated['completion_date'] = now();
                }
            }
        }

        unset($validated['score_note']);

        $this->preserveLegacyShiftLeaderScoreIfNeeded($stop, $user, $previousPriorityLevel);

        $stop->update($validated);

        $this->updateRoleBasedLatestScore($stop, $user, $previousPriorityLevel, $validated['priority_level'] ?? null, $scoreNote);

        $this->recordScoreHistoryIfNeeded($stop, $user, $previousPriorityLevel, $validated['priority_level'] ?? null, $scoreNote);

        if ($this->shouldNotifyPriorityZero($previousPriorityLevel, $stop->priority_level)) {
            $this->notifyPriorityZeroSafely($stop->fresh(['user', 'scorer']), $stopNotificationService);
        }

        return redirect()->route('stops.index')->with('success', 'Cập nhật STOP thành công!');
    }

    /**
     * Update priority level (for approvers/admins only)
     */
    public function updatePriority(Request $request, Stop $stop, StopNotificationService $stopNotificationService)
    {
        // Log for debugging
        \Log::info('updatePriority called', [
            'stop_id' => $stop->id,
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role ?? 'null',
            'request_data' => $request->all()
        ]);
        
        // Check if user is admin or approver
        $user = Auth::user();
        if (!$user) {
            \Log::error('No authenticated user');
            return response()->json([
                'success' => false,
                'message' => 'Chưa đăng nhập'
            ], 401);
        }
        
        if (!$user->isAdmin() && !$user->isApprover() && !$user->isTchcManager()) {
            \Log::warning('User not authorized', ['user_id' => $user->id, 'role' => $user->role]);
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền chấm điểm STOP'
            ], 403);
        }

        $validated = $request->validate([
            'priority_level' => 'nullable|integer|in:0,1,2,3',
            'score_note' => 'nullable|string|max:1000',
        ]);

        $previousPriorityLevel = $stop->priority_level;
        $scoreNote = $validated['score_note'] ?? null;

        // Cập nhật mức độ, người chấm điểm và thời gian chấm
        $updateData = [
            'priority_level' => $validated['priority_level'],
            'priority_scored_by' => Auth::id(),
            'priority_scored_at' => now()
        ];

        $this->preserveLegacyShiftLeaderScoreIfNeeded($stop, $user, $previousPriorityLevel);
        
        // Nếu là TCHC Manager chấm điểm với mức độ hợp lệ (0,1,2,3) → Tự động chuyển sang Hoàn thành
        // Không hoàn thành nếu chưa chấm (NULL)
        if (Auth::user()->isTchcManager() && $validated['priority_level'] !== null && in_array($validated['priority_level'], [0, 1, 2, 3])) {
            $updateData['status'] = 'completed';
            $updateData['completion_date'] = now();
        }
        
        $stop->update($updateData);

        $this->updateRoleBasedLatestScore($stop, $user, $previousPriorityLevel, $validated['priority_level'] ?? null, $scoreNote);

        $this->recordScoreHistoryIfNeeded($stop, $user, $previousPriorityLevel, $validated['priority_level'] ?? null, $scoreNote);

        if ($this->shouldNotifyPriorityZero($previousPriorityLevel, $stop->priority_level)) {
            $this->notifyPriorityZeroSafely($stop->fresh(['user', 'scorer']), $stopNotificationService);
        }
        
        \Log::info('Priority updated successfully', [
            'stop_id' => $stop->id,
            'priority_level' => $stop->priority_level,
            'scored_by' => Auth::user()->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật mức độ quan trọng',
            'priority_level' => $stop->priority_level,
            'priority_label' => $stop->getPriorityLabel(),
            'priority_badge_class' => $stop->getPriorityBadgeClass()
        ]);
    }

    /**
     * Bulk update priority level for multiple STOPs
     */
    public function bulkUpdatePriority(Request $request, StopNotificationService $stopNotificationService)
    {
        // Kiểm tra quyền
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isApprover() && !$user->isTchcManager())) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền chấm điểm STOP'
            ], 403);
        }

        $validated = $request->validate([
            'stop_ids' => 'required|array|min:1',
            'stop_ids.*' => 'exists:stops,id',
            'priority_level' => 'nullable|integer|in:0,1,2,3',
            'score_note' => 'nullable|string|max:1000',
        ]);

        $stopIds = $validated['stop_ids'];
        $priorityLevel = $validated['priority_level'];
        $scoreNote = $validated['score_note'] ?? null;

        // Chuẩn bị dữ liệu cập nhật
        $updateData = [
            'priority_level' => $priorityLevel,
            'priority_scored_by' => Auth::id(),
            'priority_scored_at' => now()
        ];
        
        // Nếu là TCHC Manager chấm điểm với mức độ hợp lệ (0,1,2,3) → Tự động chuyển sang Hoàn thành
        if ($user->isTchcManager() && $priorityLevel !== null && in_array($priorityLevel, [0, 1, 2, 3])) {
            $updateData['status'] = 'completed';
            $updateData['completion_date'] = now();
        }
        
        // Nếu là Approver (không phải TCHC Manager) chấm điểm với mức độ hợp lệ → Chuyển sang Đang xử lý
        if ($user->isApprover() && !$user->isTchcManager() && $priorityLevel !== null && in_array($priorityLevel, [0, 1, 2, 3])) {
            $updateData['status'] = 'in-progress';
        }
        
        $notifyStopIds = [];
        if ((int) $priorityLevel === 0) {
            $notifyStopIds = Stop::whereIn('id', $stopIds)
                ->where('status', '!=', 'completed')
                ->where(function ($query) {
                    $query->whereNull('priority_level')
                        ->orWhere('priority_level', '!=', 0);
                })
                ->pluck('id')
                ->all();
        }

        $historyStops = Stop::whereIn('id', $stopIds)
            ->where('status', '!=', 'completed')
            ->get(['id', 'priority_level', 'priority_scored_by', 'priority_scored_at', 'notes']);

        foreach ($historyStops as $historyStop) {
            $this->preserveLegacyShiftLeaderScoreIfNeeded($historyStop, $user, $historyStop->priority_level);
        }

        // Cập nhật hàng loạt
        $updatedCount = Stop::whereIn('id', $stopIds)
            ->where('status', '!=', 'completed') // Không cập nhật các STOP đã hoàn thành
            ->update($updateData);

        foreach ($historyStops as $historyStop) {
            $this->updateRoleBasedLatestScore($historyStop, $user, $historyStop->priority_level, $priorityLevel, $scoreNote);
        }

        foreach ($historyStops as $historyStop) {
            $this->recordScoreHistoryIfNeeded($historyStop, $user, $historyStop->priority_level, $priorityLevel, $scoreNote);
        }

        if (!empty($notifyStopIds)) {
            Stop::with(['user', 'scorer'])
                ->whereIn('id', $notifyStopIds)
                ->get()
                ->each(function (Stop $stop) use ($stopNotificationService) {
                    $this->notifyPriorityZeroSafely($stop, $stopNotificationService);
                });
        }

        \Log::info('Bulk priority update', [
            'stop_ids' => $stopIds,
            'priority_level' => $priorityLevel,
            'updated_count' => $updatedCount,
            'scored_by' => $user->name
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đã cập nhật mức độ cho {$updatedCount} STOP",
            'updated_count' => $updatedCount
        ]);
    }

    /**
     * Remove the specified STOP observation
     */
    public function destroy(Stop $stop)
    {
        $stop->delete();

        return redirect()->route('stops.index')->with('success', 'Xóa STOP thành công!');
    }

    private function shouldNotifyPriorityZero($previousPriorityLevel, $currentPriorityLevel): bool
    {
        return (int) $currentPriorityLevel === 0 && (string) $previousPriorityLevel !== '0';
    }

    private function notifyPriorityZeroSafely(Stop $stop, StopNotificationService $stopNotificationService): void
    {
        try {
            $stopNotificationService->sendPriorityZeroNotification($stop);
        } catch (\Throwable $exception) {
            Log::error('Failed to send STOP level-0 notification.', [
                'stop_id' => $stop->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function recordScoreHistoryIfNeeded(Stop $stop, $user, $previousPriorityLevel, $currentPriorityLevel, ?string $scoreNote): void
    {
        if (!Schema::hasTable('stop_score_histories')) {
            return;
        }

        if (!$this->isScoringActor($user)) {
            return;
        }

        if ((string) $previousPriorityLevel === (string) $currentPriorityLevel && blank($scoreNote)) {
            return;
        }

        StopScoreHistory::create([
            'stop_id' => $stop->id,
            'scored_by' => $user?->id,
            'scorer_type' => $this->resolveScorerType($user),
            'scorer_role' => $user?->role,
            'previous_priority_level' => $previousPriorityLevel,
            'priority_level' => $currentPriorityLevel,
            'note' => $scoreNote,
            'scored_at' => now(),
        ]);
    }

    private function updateRoleBasedLatestScore(Stop $stop, $user, $previousPriorityLevel, $currentPriorityLevel, ?string $scoreNote): void
    {
        if (!$this->isScoringActor($user)) {
            return;
        }

        $isScoringAction = (string) $previousPriorityLevel !== (string) $currentPriorityLevel || filled($scoreNote);
        if (!$isScoringAction) {
            return;
        }

        if (!Schema::hasColumn('stops', 'shift_leader_scored_by') || !Schema::hasColumn('stops', 'safety_officer_scored_by')) {
            return;
        }

        $now = now();
        if ($this->resolveScorerType($user) === 'shift_leader') {
            $stop->forceFill([
                'shift_leader_scored_by' => $user?->id,
                'shift_leader_scored_at' => $now,
                'shift_leader_priority_level' => $currentPriorityLevel,
                'shift_leader_note' => $scoreNote,
            ])->save();

            return;
        }

        $stop->forceFill([
            'safety_officer_scored_by' => $user?->id,
            'safety_officer_scored_at' => $now,
            'safety_officer_priority_level' => $currentPriorityLevel,
            'safety_officer_note' => $scoreNote,
        ])->save();
    }

    private function preserveLegacyShiftLeaderScoreIfNeeded(Stop $stop, $currentUser, $previousPriorityLevel): void
    {
        if (!$currentUser || !$currentUser->isTchcManager()) {
            return;
        }

        if (!Schema::hasColumn('stops', 'shift_leader_scored_by')) {
            return;
        }

        if (filled($stop->shift_leader_scored_by)) {
            return;
        }

        if (blank($stop->priority_scored_by)) {
            return;
        }

        $legacyScorer = User::find($stop->priority_scored_by);
        if (!$legacyScorer || !$legacyScorer->isApprover() || $legacyScorer->isTchcManager()) {
            return;
        }

        $stop->forceFill([
            'shift_leader_scored_by' => $stop->priority_scored_by,
            'shift_leader_scored_at' => $stop->priority_scored_at,
            'shift_leader_priority_level' => $previousPriorityLevel,
            'shift_leader_note' => $stop->notes,
        ])->save();
    }

    private function resolveScorerType($user): string
    {
        if ($user && $user->isApprover() && !$user->isTchcManager()) {
            return 'shift_leader';
        }

        return 'safety_officer';
    }

    private function isScoringActor($user): bool
    {
        return $user && ($user->isAdmin() || $user->isApprover() || $user->isTchcManager());
    }

    private function requiresSafetyOfficerNote($user, $previousPriorityLevel, $currentPriorityLevel, ?string $scoreNote): bool
    {
        $isSafetyOfficer = $user && !$user->isApprover();
        $isScoringAction = (string) $previousPriorityLevel !== (string) $currentPriorityLevel;

        return $isSafetyOfficer && $isScoringAction && blank($scoreNote);
    }
}