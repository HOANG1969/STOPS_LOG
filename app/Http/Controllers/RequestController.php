<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Product;
use App\Models\RequestItem;
use App\Models\Approval;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(HttpRequest $request)
    {
        $query = Request::with(['user', 'requestItems.product', 'approvals.approver']);
        
        // Filter theo user role
        if (Auth::user()->isEmployee()) {
            $query->where('user_id', Auth::id());
        } elseif (Auth::user()->isManager()) {
            // Manager có thể xem requests của nhân viên dưới quyền và của chính mình
            $subordinateIds = Auth::user()->subordinates->pluck('id')->push(Auth::id());
            $query->whereIn('user_id', $subordinateIds);
        }
        // Director và Admin có thể xem tất cả requests
        
        // Filter theo status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('request_number', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }
        
        $requests = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('category')->active()->get()->groupBy('category.name');
        return view('requests.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'needed_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Tạo request
            $requestModel = Request::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'purpose' => $request->purpose,
                'needed_date' => $request->needed_date,
                'priority' => $request->priority,
                'notes' => $request->notes,
                'status' => $request->has('submit') ? 'submitted' : 'draft'
            ]);

            // Tạo request items
            foreach ($request->products as $productData) {
                RequestItem::create([
                    'request_id' => $requestModel->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                ]);
            }

            // Nếu submit, tạo approval workflow
            if ($request->has('submit')) {
                $this->createApprovalWorkflow($requestModel);
            }

            DB::commit();
            
            $message = $request->has('submit') ? 'Yêu cầu đã được gửi thành công!' : 'Yêu cầu đã được lưu nháp!';
            return redirect()->route('requests.show', $requestModel)->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $request->load(['user', 'requestItems.product.category', 'approvals.approver']);
        
        // Kiểm tra quyền xem
        if (!$this->canViewRequest($request)) {
            abort(403, 'Bạn không có quyền xem yêu cầu này.');
        }
        
        return view('requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        // Chỉ cho phép edit nếu là draft hoặc rejected
        if (!in_array($request->status, ['draft', 'rejected'])) {
            return redirect()->route('requests.show', $request)
                ->with('error', 'Chỉ có thể chỉnh sửa yêu cầu ở trạng thái bản nháp hoặc bị từ chối.');
        }
        
        // Kiểm tra quyền edit
        if ($request->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền chỉnh sửa yêu cầu này.');
        }
        
        $request->load(['requestItems.product']);
        $products = Product::with('category')->active()->get()->groupBy('category.name');
        
        return view('requests.edit', compact('request', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $httpRequest, Request $request)
    {
        // Kiểm tra quyền và trạng thái
        if (!in_array($request->status, ['draft', 'rejected'])) {
            return redirect()->route('requests.show', $request)
                ->with('error', 'Chỉ có thể chỉnh sửa yêu cầu ở trạng thái bản nháp hoặc bị từ chối.');
        }
        
        if ($request->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền chỉnh sửa yêu cầu này.');
        }

        $httpRequest->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'needed_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Update request
            $request->update([
                'title' => $httpRequest->title,
                'purpose' => $httpRequest->purpose,
                'needed_date' => $httpRequest->needed_date,
                'priority' => $httpRequest->priority,
                'notes' => $httpRequest->notes,
                'status' => $httpRequest->has('submit') ? 'submitted' : 'draft'
            ]);

            // Xóa request items cũ
            $request->requestItems()->delete();

            // Tạo request items mới
            foreach ($httpRequest->products as $productData) {
                RequestItem::create([
                    'request_id' => $request->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                ]);
            }

            // Nếu submit, tạo approval workflow mới
            if ($httpRequest->has('submit')) {
                $request->approvals()->delete(); // Xóa approvals cũ
                $this->createApprovalWorkflow($request);
            }

            DB::commit();
            
            $message = $httpRequest->has('submit') ? 'Yêu cầu đã được gửi thành công!' : 'Yêu cầu đã được cập nhật!';
            return redirect()->route('requests.show', $request)->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Chỉ cho phép xóa nếu là draft
        if ($request->status !== 'draft') {
            return redirect()->route('requests.index')
                ->with('error', 'Chỉ có thể xóa yêu cầu ở trạng thái bản nháp.');
        }
        
        // Kiểm tra quyền xóa
        if ($request->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền xóa yêu cầu này.');
        }

        $request->delete();
        
        return redirect()->route('requests.index')->with('success', 'Yêu cầu đã được xóa!');
    }

    /**
     * Submit a draft request
     */
    public function submit(Request $request)
    {
        if ($request->status !== 'draft') {
            return redirect()->route('requests.show', $request)
                ->with('error', 'Chỉ có thể gửi yêu cầu ở trạng thái bản nháp.');
        }
        
        if ($request->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền gửi yêu cầu này.');
        }

        DB::beginTransaction();
        try {
            $request->update(['status' => 'submitted']);
            $this->createApprovalWorkflow($request);
            
            DB::commit();
            return redirect()->route('requests.show', $request)
                ->with('success', 'Yêu cầu đã được gửi thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a request
     */
    public function cancel(Request $request)
    {
        if (in_array($request->status, ['completed', 'cancelled'])) {
            return redirect()->route('requests.show', $request)
                ->with('error', 'Không thể hủy yêu cầu này.');
        }
        
        if ($request->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Bạn không có quyền hủy yêu cầu này.');
        }

        $request->update(['status' => 'cancelled']);
        
        return redirect()->route('requests.show', $request)
            ->with('success', 'Yêu cầu đã được hủy!');
    }

    /**
     * Tạo workflow phê duyệt
     */
    private function createApprovalWorkflow(Request $request)
    {
        $user = $request->user;
        
        // Tạo manager approval
        if ($user->manager) {
            Approval::create([
                'request_id' => $request->id,
                'approver_id' => $user->manager->id,
                'level' => 'manager',
                'status' => 'pending'
            ]);
        }

        // Nếu tổng tiền >= 10 triệu, cần director approval
        if ($request->total_amount >= 10000000) {
            $director = \App\Models\User::where('role', 'director')->first();
            if ($director) {
                Approval::create([
                    'request_id' => $request->id,
                    'approver_id' => $director->id,
                    'level' => 'director',
                    'status' => 'pending'
                ]);
            }
        }
    }

    /**
     * Kiểm tra quyền xem request
     */
    private function canViewRequest(Request $request)
    {
        $user = Auth::user();
        
        // Admin có thể xem tất cả
        if ($user->isAdmin()) {
            return true;
        }
        
        // Người tạo request có thể xem
        if ($request->user_id === $user->id) {
            return true;
        }
        
        // Manager có thể xem requests của nhân viên dưới quyền
        if ($user->isManager()) {
            $subordinateIds = $user->subordinates->pluck('id');
            if ($subordinateIds->contains($request->user_id)) {
                return true;
            }
        }
        
        // Người phê duyệt có thể xem
        if ($request->approvals->contains('approver_id', $user->id)) {
            return true;
        }
        
        return false;
    }
}