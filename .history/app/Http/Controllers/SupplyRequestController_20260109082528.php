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
     * Store a newly created supply request
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:office_supplies,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purpose' => 'required|string|max:255',
            'priority' => 'required|in:normal,urgent,emergency',
            'notes' => 'nullable|string',
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
                'requester_department' => $user->department,
                'requester_position' => $user->position,
                'request_date' => now()->toDateString(),
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
    public function myRequests(): JsonResponse
    {
        $requests = SupplyRequest::with(['requestItems.officeSupply'])
                                ->where('user_id', Auth::id())
                                ->orderBy('created_at', 'desc')
                                ->get();

        return response()->json($requests);
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
    public function show(SupplyRequest $request): JsonResponse
    {
        $request->load(['user', 'requestItems.officeSupply', 'approver']);
        
        return response()->json($request);
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
