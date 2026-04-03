<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\RequestItem;
use App\Models\OfficeSupply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeRequestController extends Controller
{
    /**
     * Hiển thị danh sách yêu cầu của nhân viên
     */
    public function index()
    {
        $user = Auth::user();
        
        $requests = SupplyRequest::with(['requestItems.officeSupply', 'approver'])
                                ->where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('employee.requests.index', compact('requests'));
    }

    /**
     * Hiển thị form tạo yêu cầu mới
     */
    public function create()
    {
        try {
            $officeSupplies = OfficeSupply::where('stock_quantity', '>', 0)
                                         ->orderBy('name')
                                         ->get();
                                         
            return view('test-create', compact('officeSupplies'));
        } catch (\Exception $e) {
            \Log::error('Error in EmployeeRequestController@create: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Lưu yêu cầu mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:office_supplies,id',
            'items.*.quantity' => 'required|integer|min:1|max:999',
            'items.*.purpose' => 'required|string|max:255',
            'priority' => 'required|in:Normal,High,Urgent',
            'notes' => 'nullable|string|max:500',
            'needed_date' => 'required|date|after_or_equal:today'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            
            // Tạo mã yêu cầu
            $requestCode = $this->generateRequestCode();
            
            // Tạo supply request
            $supplyRequest = SupplyRequest::create([
                'request_code' => $requestCode,
                'user_id' => $user->id,
                'requester_name' => $user->full_name ?? $user->name,
                'requester_email' => $user->email,
                'department' => $user->department,
                'requester_department' => $user->department,
                'priority' => $validated['priority'],
                'needed_date' => $validated['needed_date'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null
            ]);

            // Tạo request items
            foreach ($validated['items'] as $item) {
                $supply = OfficeSupply::find($item['supply_id']);
                
                // Kiểm tra tồn kho
                if ($supply->stock_quantity < $item['quantity']) {
                    DB::rollBack();
                    return back()->withErrors([
                        'items' => "Không đủ tồn kho cho {$supply->name}. Còn lại: {$supply->stock_quantity}"
                    ])->withInput();
                }

                RequestItem::create([
                    'supply_request_id' => $supplyRequest->id,
                    'office_supply_id' => $item['supply_id'],
                    'quantity' => $item['quantity'],
                    'purpose' => $item['purpose']
                ]);
            }

            DB::commit();

            return redirect()->route('employee.requests.index')
                           ->with('success', 'Đã tạo yêu cầu văn phòng phẩm thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi tạo yêu cầu'])
                        ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết yêu cầu
     */
    public function show(SupplyRequest $request)
    {
        // Kiểm tra quyền xem
        if ($request->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem yêu cầu này');
        }

        $request->load(['requestItems.officeSupply', 'approver', 'user']);
        
        return view('employee.requests.show', compact('request'));
    }

    /**
     * Chuyển yêu cầu để phê duyệt
     */
    public function forward(SupplyRequest $request)
    {
        // Kiểm tra quyền
        if ($request->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này');
        }

        // Chỉ có thể chuyển yêu cầu đang pending
        if ($request->status !== 'pending') {
            return back()->withErrors(['error' => 'Chỉ có thể chuyển yêu cầu đang chờ xử lý']);
        }

        $request->update([
            'status' => 'forwarded',
            'forwarded_at' => now()
        ]);

        return back()->with('success', 'Đã chuyển yêu cầu để phê duyệt');
    }

    /**
     * Xem lịch sử phê duyệt của yêu cầu
     */
    public function history(SupplyRequest $request)
    {
        // Kiểm tra quyền xem
        if ($request->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem yêu cầu này');
        }

        $request->load(['requestItems.officeSupply', 'approver', 'user']);
        
        // Tạo timeline lịch sử
        $timeline = [];
        
        // Tạo yêu cầu
        $timeline[] = [
            'action' => 'created',
            'title' => 'Tạo yêu cầu',
            'description' => 'Yêu cầu được tạo bởi ' . $request->requester_name,
            'timestamp' => $request->created_at,
            'user' => $request->requester_name,
            'status' => 'info'
        ];

        // Chuyển phê duyệt
        if ($request->forwarded_at) {
            $timeline[] = [
                'action' => 'forwarded',
                'title' => 'Chuyển phê duyệt',
                'description' => 'Yêu cầu được chuyển để phê duyệt',
                'timestamp' => $request->forwarded_at,
                'user' => $request->requester_name,
                'status' => 'warning'
            ];
        }

        // Phê duyệt/Từ chối
        if ($request->approved_at) {
            $timeline[] = [
                'action' => $request->status,
                'title' => $request->status === 'approved' ? 'Phê duyệt' : 'Từ chối',
                'description' => $request->status === 'approved' 
                    ? 'Yêu cầu được phê duyệt'
                    : 'Yêu cầu bị từ chối: ' . $request->approval_notes,
                'timestamp' => $request->approved_at,
                'user' => $request->approver ? $request->approver->name : 'N/A',
                'status' => $request->status === 'approved' ? 'success' : 'danger'
            ];
        }

        // Sắp xếp theo thời gian
        usort($timeline, function($a, $b) {
            return $a['timestamp']->timestamp - $b['timestamp']->timestamp;
        });

        return view('employee.requests.history', compact('request', 'timeline'));
    }

    /**
     * Tạo mã yêu cầu
     */
    private function generateRequestCode()
    {
        $prefix = 'VP' . date('y') . date('m');
        $lastRequest = SupplyRequest::where('request_code', 'like', $prefix . '%')
                                  ->orderBy('request_code', 'desc')
                                  ->first();

        if ($lastRequest) {
            $lastNumber = intval(substr($lastRequest->request_code, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }
}