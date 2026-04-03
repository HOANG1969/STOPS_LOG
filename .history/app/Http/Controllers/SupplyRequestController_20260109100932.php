<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\RequestItem;
use App\Models\OfficeSupply;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplyRequestController extends Controller
{
    /**
     * Display a listing of supply requests
     */
    public function index()
    {
        $year = request('year', date('Y'));
        $period = request('period');
        
        $query = SupplyRequest::with(['requestItems.officeSupply'])
                            ->where('user_id', Auth::id())
                            ->whereYear('created_at', $year);
        
        if ($period) {
            $query->whereMonth('created_at', $period);
        }
        
        $requests = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Add period display
        $requests->getCollection()->transform(function ($request) {
            $request->period_display = 'Tháng ' . $request->created_at->format('n') . ' - ' . $request->created_at->format('Y');
            return $request;
        });
        
        return view('supply-requests.index', compact('requests'));
    }

    /**
     * Store a newly created supply request
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:office_supplies,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purpose' => 'required|string|max:255',
            'priority' => 'required|in:low,normal,high,urgent',
            'notes' => 'nullable|string',
            'needed_date' => 'nullable|date|after:today',
            'period' => 'nullable|integer|min:1|max:12',
            'status' => 'in:draft,pending'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $status = $validated['status'] ?? 'pending';
            
            // For draft, don't check stock
            if ($status === 'pending') {
                // Check stock availability for pending requests
                foreach ($validated['items'] as $item) {
                    $supply = OfficeSupply::find($item['supply_id']);
                    
                    if ($supply->stock_quantity < $item['quantity']) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Không đủ tồn kho cho {$supply->name}. Còn lại: {$supply->stock_quantity}"
                        ], 400);
                    }
                }
            }
            
            // Create supply request
            $supplyRequest = SupplyRequest::create([
                'request_code' => SupplyRequest::generateRequestCode(),
                'user_id' => $user->id,
                'requester_name' => $user->full_name ?? $user->name,
                'requester_email' => $user->email,
                'department' => $user->department,
                'requester_department' => $user->department,
                'requester_position' => $user->position,
                'request_date' => now()->toDateString(),
                'needed_date' => $validated['needed_date'] ?? null,
                'period' => $validated['period'] ?? null,
                'priority' => $validated['priority'],
                'status' => $status,
                'notes' => $validated['notes'] ?? null
            ]);

            // Create request items
            foreach ($validated['items'] as $item) {
                RequestItem::create([
                    'supply_request_id' => $supplyRequest->id,
                    'office_supply_id' => $item['supply_id'],
                    'quantity' => $item['quantity'],
                    'purpose' => $item['purpose']
                ]);

                // Only update stock for pending requests
                if ($status === 'pending') {
                    $supply = OfficeSupply::find($item['supply_id']);
                    $supply->decrement('stock_quantity', $item['quantity']);
                }
            }

            DB::commit();

            $message = $status === 'draft' ? 'Đã lưu nháp thành công' : 'Đã gửi yêu cầu phê duyệt thành công';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $supplyRequest->load('requestItems.officeSupply')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo yêu cầu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user's requests
     */
    public function myRequests()
    {
        $requests = SupplyRequest::with(['requestItems.officeSupply'])
                                ->where('user_id', Auth::id())
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('supply-requests.my-requests', compact('requests'));
    }

    /**
     * Get requests for approval
     */
    public function forApproval(): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin can see all pending requests
            $requests = SupplyRequest::with(['user', 'requestItems.officeSupply'])
                                    ->where('status', 'pending')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        } elseif ($user->isApprover()) {
            // Approvers can only see requests from their department
            $requests = SupplyRequest::with(['user', 'requestItems.officeSupply'])
                                    ->where('status', 'pending')
                                    ->where('requester_department', $user->department)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        } else {
            $requests = collect();
        }

        return response()->json($requests);
    }

    /**
     * Show specific request details
     */
    public function show(SupplyRequest $request)
    {
        // Check if user can view this request
        if ($request->user_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isApprover()) {
            abort(403);
        }
        
        $request->load(['user', 'requestItems.officeSupply', 'approver']);
        
        if (request()->ajax()) {
            return response()->json($request);
        }
        
        return view('supply-requests.show', compact('request'));
    }

    /**
     * Update a supply request
     */
    public function update(Request $request, SupplyRequest $supplyRequest)
    {
        // Check if user can update this request
        if ($supplyRequest->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Không có quyền sửa yêu cầu này'], 403);
        }
        
        // Only allow updating draft requests
        if ($supplyRequest->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể sửa yêu cầu ở trạng thái bản nháp'], 400);
        }
        
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:office_supplies,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purpose' => 'required|string|max:255',
            'priority' => 'required|in:low,normal,high,urgent',
            'notes' => 'nullable|string',
            'needed_date' => 'nullable|date|after:today',
            'period' => 'nullable|integer|min:1|max:12',
            'status' => 'in:draft,pending'
        ]);

        DB::beginTransaction();
        try {
            $status = $validated['status'] ?? 'pending';
            
            // For pending status, check stock
            if ($status === 'pending') {
                foreach ($validated['items'] as $item) {
                    $supply = OfficeSupply::find($item['supply_id']);
                    
                    if ($supply->stock_quantity < $item['quantity']) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Không đủ tồn kho cho {$supply->name}. Còn lại: {$supply->stock_quantity}"
                        ], 400);
                    }
                }
            }
            
            // Update supply request
            $supplyRequest->update([
                'priority' => $validated['priority'],
                'notes' => $validated['notes'],
                'needed_date' => $validated['needed_date'],
                'period' => $validated['period'],
                'status' => $status
            ]);
            
            // Delete existing items
            $supplyRequest->requestItems()->delete();
            
            // Create new items
            foreach ($validated['items'] as $item) {
                RequestItem::create([
                    'supply_request_id' => $supplyRequest->id,
                    'office_supply_id' => $item['supply_id'],
                    'quantity' => $item['quantity'],
                    'purpose' => $item['purpose']
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật phiếu đăng ký thành công!',
                'request_id' => $supplyRequest->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a request
     */
    public function destroy(SupplyRequest $request)
    {
        // Check if user can delete this request
        if ($request->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Không có quyền xóa yêu cầu này'], 403);
        }
        
        // Only allow deleting draft and rejected requests
        if (!in_array($request->status, ['draft', 'rejected'])) {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể xóa yêu cầu ở trạng thái bản nháp hoặc bị từ chối'], 400);
        }
        
        DB::beginTransaction();
        try {
            // Delete related request items first
            $request->requestItems()->delete();
            
            // Delete the request
            $request->delete();
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Đã xóa yêu cầu thành công']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa yêu cầu'], 500);
        }
    }

    /**
     * Approve a supply request
     */
    public function approve(Request $request, SupplyRequest $supplyRequest): JsonResponse
    {
        $user = Auth::user();
        
        if (!$supplyRequest->canBeApprovedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền phê duyệt yêu cầu này'
            ], 403);
        }

        $supplyRequest->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'approved_date' => now(),
            'rejection_reason' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã phê duyệt yêu cầu thành công',
            'data' => $supplyRequest->load('approver')
        ]);
    }

    /**
     * Reject a supply request
     */
    public function reject(Request $request, SupplyRequest $supplyRequest): JsonResponse
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $user = Auth::user();
        
        if (!$supplyRequest->canBeApprovedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền từ chối yêu cầu này'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Return stock quantity
            foreach ($supplyRequest->requestItems as $item) {
                $item->officeSupply->increment('stock_quantity', $item->quantity);
            }

            $supplyRequest->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'approved_date' => now(),
                'rejection_reason' => $validated['rejection_reason']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối yêu cầu thành công',
                'data' => $supplyRequest->load('approver')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi từ chối yêu cầu'
            ], 500);
        }
    }
}
