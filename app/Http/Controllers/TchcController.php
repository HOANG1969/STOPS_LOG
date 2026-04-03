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

        // Lấy parameters tìm kiếm
        $department = request('department');
        $area = request('area');
        $status = request('status', 'all'); // Mặc định hiển thị tất cả
        $year = request('year');
        $period = request('period');

        // Build query - Lấy tất cả phiếu từ approved trở lên
        $query = SupplyRequest::whereIn('status', ['approved', 'tchc_checked', 'tchc_approved', 'tchc_rejected'])
            ->with(['user', 'approver', 'tchcChecker', 'requestItems.officeSupply']);

        // Filter theo status
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Filter theo bộ phận
        if ($department) {
            $query->where('requester_department', $department);
        }

        // Filter theo khu vực (nếu có)
        if ($area) {
            $query->where('area', $area);
        }

        // Filter theo năm
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Filter theo tháng
        if ($period) {
            $query->whereMonth('created_at', $period);
        }

        $requests = $query->orderBy('approved_at', 'desc')->paginate(10);

        // Lấy danh sách bộ phận để filter
        $departments = SupplyRequest::whereIn('status', ['approved', 'tchc_checked', 'tchc_approved', 'tchc_rejected'])
            ->distinct()
            ->pluck('requester_department')
            ->filter()
            ->sort();

        return view('tchc.checker.dashboard', compact('requests', 'departments'));
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

        // Lấy parameters tìm kiếm
        $department = request('department');
        $area = request('area');
        $status = request('status', 'tchc_checked'); // Mặc định là tchc_checked
        $year = request('year');
        $period = request('period');

        // Build query
        $query = SupplyRequest::with(['user', 'approver', 'tchcChecker', 'requestItems.officeSupply']);
        
        // Filter theo status
        if ($status === 'all') {
            $query->whereIn('status', ['tchc_checked', 'tchc_approved', 'tchc_rejected']);
        } else {
            $query->where('status', $status);
        }

        // Filter theo bộ phận
        if ($department) {
            $query->where('requester_department', $department);
        }

        // Filter theo khu vực (nếu có)
        if ($area) {
            $query->where('area', $area);
        }

        // Filter theo năm
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Filter theo tháng
        if ($period) {
            $query->whereMonth('created_at', $period);
        }

        $pendingRequests = $query->orderBy('tchc_checked_at', 'desc')->paginate(10);

        // Lấy danh sách bộ phận để filter
        $departments = SupplyRequest::whereIn('status', ['tchc_checked', 'tchc_approved', 'tchc_rejected'])
            ->distinct()
            ->pluck('requester_department')
            ->filter()
            ->sort();

        return view('tchc.manager.dashboard', compact('pendingRequests', 'departments'));
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