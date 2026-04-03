<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TchcController extends Controller
{
    /**
     * TCHC Checker Dashboard
     */
    public function checkerDashboard()
    {
        $user = Auth::user();
        
        if (!$user->isTchcChecker()) {
            abort(403, 'Bạn không có quyền truy cập trang này');
        }

        // Lấy danh sách phiếu đã được duyệt bởi approver, chờ TCHC check
        $pendingRequests = SupplyRequest::where('status', 'approved')
            ->with(['user', 'approver', 'requestItems.officeSupply'])
            ->orderBy('approved_at', 'asc')
            ->paginate(10);

        return view('tchc.checker.dashboard', compact('pendingRequests'));
    }

    /**
     * TCHC Manager Dashboard  
     */
    public function managerDashboard()
    {
        $user = Auth::user();
        
        if (!$user->isTchcManager()) {
            abort(403, 'Bạn không có quyền truy cập trang này');
        }

        // Lấy danh sách phiếu đã được TCHC check, chờ manager approve
        $pendingRequests = SupplyRequest::where('status', 'tchc_checked')
            ->with(['user', 'approver', 'tchcChecker', 'requestItems.officeSupply'])
            ->orderBy('tchc_checked_at', 'asc')
            ->paginate(10);

        return view('tchc.manager.dashboard', compact('pendingRequests'));
    }

    /**
     * TCHC Checker thực hiện check phiếu
     */
    public function checkRequest(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isTchcChecker()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này');
        }

        $supplyRequest = SupplyRequest::findOrFail($id);

        if ($supplyRequest->status !== 'approved') {
            return back()->with('error', 'Phiếu này không ở trạng thái cho phép check');
        }

        $request->validate([
            'tchc_check_notes' => 'nullable|string|max:1000'
        ]);

        $supplyRequest->update([
            'status' => 'tchc_checked',
            'tchc_checker_id' => $user->id,
            'tchc_checked_at' => Carbon::now(),
            'tchc_check_notes' => $request->tchc_check_notes
        ]);

        return back()->with('success', 'Đã check phiếu thành công. Phiếu đã được chuyển tới TCHC Manager để phê duyệt cuối.');
    }

    /**
     * TCHC Manager phê duyệt cuối
     */
    public function finalApprove(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isTchcManager()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này');
        }

        $supplyRequest = SupplyRequest::findOrFail($id);

        if ($supplyRequest->status !== 'tchc_checked') {
            return back()->with('error', 'Phiếu này không ở trạng thái cho phép phê duyệt cuối');
        }

        $request->validate([
            'tchc_approval_notes' => 'nullable|string|max:1000'
        ]);

        $supplyRequest->update([
            'status' => 'tchc_approved',
            'tchc_manager_id' => $user->id,
            'tchc_approved_at' => Carbon::now(),
            'tchc_approval_notes' => $request->tchc_approval_notes
        ]);

        return back()->with('success', 'Đã phê duyệt cuối thành công. Workflow hoàn tất.');
    }

    /**
     * TCHC Manager từ chối phiếu
     */
    public function finalReject(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isTchcManager()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này');
        }

        $supplyRequest = SupplyRequest::findOrFail($id);

        if ($supplyRequest->status !== 'tchc_checked') {
            return back()->with('error', 'Phiếu này không ở trạng thái cho phép từ chối');
        }

        $request->validate([
            'tchc_approval_notes' => 'required|string|max:1000'
        ]);

        $supplyRequest->update([
            'status' => 'tchc_rejected', 
            'tchc_manager_id' => $user->id,
            'tchc_approved_at' => Carbon::now(),
            'tchc_approval_notes' => $request->tchc_approval_notes
        ]);

        return back()->with('success', 'Đã từ chối phiếu. Workflow kết thúc.');
    }
}