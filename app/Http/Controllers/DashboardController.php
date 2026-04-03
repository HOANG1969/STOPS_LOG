<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard chính - chuyển hướng theo role
     */
    public function index()
    {
        $user = Auth::user();

        // Admin vao man hinh phe duyet tong quan.
        if ($user->isAdmin()) {
            return redirect()->route('dashboard.approval');
        }
        
        // TCHC Manager mac dinh vao quan ly STOP, menu co them bao cao STOP.
        if ($user->isTchcManager()) {
            return redirect()->route('stops.index');
        }
        
        // TCHC Checker chỉ có quyền kiểm tra VPP
        if ($user->isTchcChecker()) {
            return redirect()->route('tchc.checker.dashboard');
        }
        
        // Employee mac dinh vao quan ly STOP.
        if ($user->isEmployee()) {
            return redirect()->route('stops.index');
        }
        
        if ($user->isApprover()) {
            // return redirect()->route('dashboard.approval');
            return redirect()->route('stops.index');
        }
        
        return redirect()->route('dashboard.employee');
    }

    /**
     * Dashboard cho employee - hiển thị đơn của mình và bộ phận
     */
    public function employee()
    {
        $user = Auth::user();
        
        // Đơn của mình
        $myRequests = SupplyRequest::where('user_id', $user->id)
            ->with(['requestItems.officeSupply', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'my');
        
        // Đơn của bộ phận
        $departmentRequests = SupplyRequest::where('requester_department', $user->department)
            ->where('user_id', '!=', $user->id) // Loại trừ đơn của mình
            ->with(['requestItems.officeSupply', 'user', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'dept');
        
        // Thống kê
        $stats = [
            'my_pending' => SupplyRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
            'my_approved' => SupplyRequest::where('user_id', $user->id)->whereIn('status', ['approved', 'tchc_checked', 'tchc_approved'])->count(),
            'my_rejected' => SupplyRequest::where('user_id', $user->id)->whereIn('status', ['rejected', 'tchc_rejected'])->count(),
            'dept_pending' => SupplyRequest::where('requester_department', $user->department)->where('status', 'pending')->count(),
            'dept_total' => SupplyRequest::where('requester_department', $user->department)->count(),
        ];
        
        return view('dashboard.employee', compact('myRequests', 'departmentRequests', 'stats', 'user'));
    }

    /**
     * Dashboard cho approver - hiển thị đơn cần phê duyệt
     */
    public function approval()
    {
        $user = Auth::user();
        
        // Đơn cần phê duyệt (theo bộ phận nếu là approver, tất cả nếu là admin)
        $requestsQuery = SupplyRequest::with(['requestItems.officeSupply', 'user']);
        
        if ($user->isApprover()) {
            $requestsQuery->where('requester_department', $user->department);
        }
        
        // Đơn chờ phê duyệt (chỉ pending)
        $pendingRequests = (clone $requestsQuery)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10, ['*'], 'pending');
        
        // Đơn đã xử lý
        $processedRequests = (clone $requestsQuery)
            ->whereIn('status', ['approved', 'rejected', 'tchc_checked', 'tchc_approved', 'tchc_rejected'])
            ->orderBy('approved_at', 'desc')
            ->paginate(10, ['*'], 'processed');
        
        // Thống kê
        $stats = [
            'pending_count' => (clone $requestsQuery)->where('status', 'pending')->count(),
            'approved_today' => (clone $requestsQuery)
                ->where('status', 'approved')
                ->whereDate('approved_at', today())
                ->count(),
            'approved_this_month' => (clone $requestsQuery)
                ->where('status', 'approved')
                ->whereMonth('approved_at', now()->month)
                ->whereYear('approved_at', now()->year)
                ->count(),
            'total_requests' => (clone $requestsQuery)->count(),
        ];
        
        return view('dashboard.approval-new', compact('pendingRequests', 'processedRequests', 'stats', 'user'));
    }

    /**
     * Phê duyệt đơn yêu cầu
     */
    public function approve(Request $request, SupplyRequest $supplyRequest)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền phê duyệt
        if (!$supplyRequest->canBeApprovedBy($user)) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền phê duyệt đơn này.'], 403);
        }
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'required_if:action,reject|string|max:500'
        ]);
        
        if ($request->action === 'approve') {
            // Phê duyệt và chuyển sang TCHC
            $supplyRequest->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'notes' => $request->notes
            ]);
        } else {
            // Từ chối
            $supplyRequest->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => $request->notes,
                'notes' => $request->notes
            ]);
        }
        
        $message = $request->action === 'approve' ? 'Đơn yêu cầu đã được phê duyệt và chuyển tiếp đến TCHC.' : 'Đơn yêu cầu đã bị từ chối.';
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        return redirect()->back()->with('success', $message);
    }
}