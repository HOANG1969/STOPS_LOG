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
        $user = Auth::user();
        
        $query = SupplyRequest::with(['requestItems.officeSupply', 'user']);
        
        // Lọc theo role
        if ($user->isApprover()) {
            // Approver thấy tất cả yêu cầu của bộ phận mình
            $query->where('requester_department', $user->department);
        } else {
            // Employee chỉ thấy yêu cầu của mình
            $query->where('user_id', $user->id);
        }
        
        $query->whereYear('created_at', $year);
        
        if ($period) {
            $query->whereMonth('created_at', $period);
        }
        
        $requests = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Add period display
        $requests->getCollection()->transform(function ($request) {
            $request->period_display = 'Tháng ' . $request->created_at->format('n') . ' - ' . $request->created_at->format('Y');
            return $request;
        });
        
        // Return JSON for AJAX requests
        if (request()->ajax()) {
            return response()->json($requests);
        }
        
        return view('supply-requests.index', compact('requests'));
    }

    /**
     * Get all requests for dashboard filtering
     */
    public function allRequests(): JsonResponse
    {
        $user = Auth::user();
        
        $query = SupplyRequest::with([
            'user:id,name,department,position',
            'requestItems:id,supply_request_id,office_supply_id,quantity',
            'requestItems.officeSupply:id,name,price',
            'approver:id,name,department'
        ]);
        
        // Filter based on user role
        if ($user->isApprover()) {
            // Approvers can see all requests from their department
            $query->where('requester_department', $user->department);
        } elseif ($user->isAdmin()) {
            // Admin can see all requests
            // No additional filter needed
        } else {
            // Regular users can only see their own requests
            $query->where('user_id', $user->id);
        }
        
        $requests = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($requests);
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
            // Admin can see all pending/forwarded requests
            $requests = SupplyRequest::with(['user', 'requestItems.officeSupply'])
                                    ->whereIn('status', ['pending', 'forwarded'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        } elseif ($user->isApprover()) {
            // Approvers can only see requests from their department
            $requests = SupplyRequest::with(['user', 'requestItems.officeSupply'])
                                    ->whereIn('status', ['pending', 'forwarded'])
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
        $user = Auth::user();
        $canView = ($request->user_id === $user->id) || 
                   $user->isAdmin() || 
                   $user->isApprover() ||
                   $user->isTchcChecker() ||
                   $user->isTchcManager();
        
        if (!$canView) {
            abort(403, 'Bạn không có quyền xem yêu cầu này');
        }
        
        $request->load(['user', 'requestItems.officeSupply', 'approver', 'tchcChecker', 'tchcManager']);
        
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
        $user = Auth::user();
        
        // Check if user can update this request
        if ($supplyRequest->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Không có quyền sửa yêu cầu này');
        }
        
        // Allow updating rejected requests or drafts
        if (!in_array($supplyRequest->status, ['draft', 'rejected', 'tchc_rejected'])) {
            return redirect()->back()->with('error', 'Chỉ có thể sửa yêu cầu ở trạng thái bản nháp hoặc bị từ chối');
        }
        
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:office_supplies,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purpose' => 'nullable|string|max:255',
            'priority' => 'required|in:normal,urgent,emergency',
            'notes' => 'nullable|string',
            'needed_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            // Update the request
            $supplyRequest->update([
                'priority' => $validated['priority'],
                'needed_date' => $validated['needed_date'],
                'notes' => $validated['notes'],
                // Reset status back to pending for resubmission
                'status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
                'rejection_reason' => null,
                'tchc_checker_id' => null,
                'tchc_checked_at' => null,
                'tchc_check_notes' => null,
                'tchc_manager_id' => null,
                'tchc_approved_at' => null,
                'tchc_approval_notes' => null
            ]);
            
            // Delete existing items
            $supplyRequest->requestItems()->delete();
            
            // Add new items
            foreach ($validated['items'] as $itemData) {
                $supplyRequest->requestItems()->create([
                    'office_supply_id' => $itemData['supply_id'],
                    'quantity' => $itemData['quantity'],
                    'purpose' => $itemData['purpose'] ?? ''
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('dashboard.employee')->with('success', 'Đã cập nhật và gửi lại đơn yêu cầu thành công');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn yêu cầu');
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
    public function approve(Request $httpRequest, SupplyRequest $request)
    {
        $user = Auth::user();
        
        if (!$request->canBeApprovedBy($user)) {
            return back()->with('error', 'Bạn không có quyền phê duyệt yêu cầu này');
        }

        $approvalNotes = $httpRequest->input('approval_notes', 'Đã phê duyệt');

        $request->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'approval_notes' => $approvalNotes
        ]);

        return redirect()->route('supply-requests.show', $request->id)
            ->with('success', 'Đã phê duyệt yêu cầu thành công');
    }

    /**
     * Reject a supply request
     */
    public function reject(Request $httpRequest, SupplyRequest $request)
    {
        $validated = $httpRequest->validate([
            'approval_notes' => 'required|string|max:500'
        ]);

        $user = Auth::user();
        
        if (!$request->canBeApprovedBy($user)) {
            return back()->with('error', 'Bạn không có quyền từ chối yêu cầu này');
        }

        DB::beginTransaction();
        try {
            // Return stock quantity
            foreach ($request->requestItems as $item) {
                $item->officeSupply->increment('stock_quantity', $item->quantity);
            }

            $request->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'approval_notes' => $validated['approval_notes']
            ]);

            DB::commit();

            return redirect()->route('supply-requests.show', $request->id)
                ->with('success', 'Đã từ chối yêu cầu thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi từ chối yêu cầu');
        }
    }

    /**
     * Show the form for editing a rejected supply request
     */
    public function edit(SupplyRequest $supplyRequest)
    {
        $user = Auth::user();
        
        // Chỉ cho phép user sở hữu đơn chỉnh sửa
        if ($supplyRequest->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền chỉnh sửa đơn này');
        }
        
        // Chỉ cho phép chỉnh sửa đơn bị từ chối
        if (!in_array($supplyRequest->status, ['rejected', 'tchc_rejected'])) {
            return redirect()->route('dashboard.employee')
                ->with('error', 'Chỉ có thể chỉnh sửa đơn đã bị từ chối');
        }
        
        $officeSupplies = OfficeSupply::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();
            
        return view('supply-requests.edit', compact('supplyRequest', 'officeSupplies'));
    }

    /**
     * Resubmit a rejected supply request
     */
    public function resubmit(Request $request, SupplyRequest $supplyRequest)
    {
        $user = Auth::user();
        
        // Chỉ cho phép user sở hữu đơn resubmit
        if ($supplyRequest->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền resubmit đơn này'], 403);
        }
        
        // Chỉ cho phép resubmit đơn bị từ chối
        if (!in_array($supplyRequest->status, ['rejected', 'tchc_rejected'])) {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể resubmit đơn đã bị từ chối'], 400);
        }
        
        DB::beginTransaction();
        try {
            // Reset về trạng thái pending để phê duyệt lại
            $supplyRequest->update([
                'status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
                'rejection_reason' => null,
                'notes' => null,
                'tchc_checker_id' => null,
                'tchc_checked_at' => null,
                'tchc_check_notes' => null,
                'tchc_manager_id' => null,
                'tchc_approved_at' => null,
                'tchc_approval_notes' => null
            ]);
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Đã gửi lại đơn yêu cầu thành công']);
            }
            
            return redirect()->route('dashboard.employee')
                ->with('success', 'Đã gửi lại đơn yêu cầu thành công');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi lại đơn'], 500);
            }
            
            return back()->with('error', 'Có lỗi xảy ra khi gửi lại đơn');
        }
    }
}
