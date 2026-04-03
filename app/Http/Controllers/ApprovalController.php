<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Approval;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Display pending approvals for current user
     */
    public function index(HttpRequest $request)
    {
        $user = Auth::user();
        
        // Chỉ manager, director, admin mới có thể approve
        if ($user->isEmployee()) {
            abort(403, 'Bạn không có quyền phê duyệt.');
        }
        
        $query = Approval::with(['request.user', 'request.requestItems.product'])
                        ->where('approver_id', $user->id);
        
        // Filter theo status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending'); // Mặc định show pending
        }
        
        // Filter theo level
        if ($request->has('level') && $request->level !== '') {
            $query->where('level', $request->level);
        }
        
        $approvals = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('approvals.index', compact('approvals'));
    }

    /**
     * Show approval details
     */
    public function show(Approval $approval)
    {
        if ($approval->approver_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền xem phê duyệt này.');
        }
        
        $approval->load(['request.user', 'request.requestItems.product.category']);
        
        return view('approvals.show', compact('approval'));
    }

    /**
     * Approve a request
     */
    public function approve(HttpRequest $request, Approval $approval)
    {
        if ($approval->approver_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền phê duyệt này.');
        }
        
        if ($approval->status !== 'pending') {
            return redirect()->route('approvals.show', $approval)
                ->with('error', 'Phê duyệt này đã được xử lý.');
        }

        $request->validate([
            'comments' => 'nullable|string|max:1000'
        ]);

        $approval->approve($request->comments);
        
        return redirect()->route('approvals.show', $approval)
            ->with('success', 'Đã phê duyệt yêu cầu thành công!');
    }

    /**
     * Reject a request
     */
    public function reject(HttpRequest $request, Approval $approval)
    {
        if ($approval->approver_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền phê duyệt này.');
        }
        
        if ($approval->status !== 'pending') {
            return redirect()->route('approvals.show', $approval)
                ->with('error', 'Phê duyệt này đã được xử lý.');
        }

        $request->validate([
            'comments' => 'required|string|max:1000'
        ], [
            'comments.required' => 'Vui lòng nhập lý do từ chối.'
        ]);

        $approval->reject($request->comments);
        
        return redirect()->route('approvals.show', $approval)
            ->with('success', 'Đã từ chối yêu cầu!');
    }

    /**
     * Bulk approve multiple requests
     */
    public function bulkApprove(HttpRequest $request)
    {
        $request->validate([
            'approval_ids' => 'required|array',
            'approval_ids.*' => 'exists:approvals,id',
            'comments' => 'nullable|string|max:1000'
        ]);

        $approvals = Approval::whereIn('id', $request->approval_ids)
                           ->where('approver_id', Auth::id())
                           ->where('status', 'pending')
                           ->get();

        $count = 0;
        foreach ($approvals as $approval) {
            $approval->approve($request->comments);
            $count++;
        }

        return redirect()->route('approvals.index')
            ->with('success', "Đã phê duyệt {$count} yêu cầu thành công!");
    }

    /**
     * Bulk reject multiple requests
     */
    public function bulkReject(HttpRequest $request)
    {
        $request->validate([
            'approval_ids' => 'required|array',
            'approval_ids.*' => 'exists:approvals,id',
            'comments' => 'required|string|max:1000'
        ], [
            'comments.required' => 'Vui lòng nhập lý do từ chối.'
        ]);

        $approvals = Approval::whereIn('id', $request->approval_ids)
                           ->where('approver_id', Auth::id())
                           ->where('status', 'pending')
                           ->get();

        $count = 0;
        foreach ($approvals as $approval) {
            $approval->reject($request->comments);
            $count++;
        }

        return redirect()->route('approvals.index')
            ->with('success', "Đã từ chối {$count} yêu cầu!");
    }
}