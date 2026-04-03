<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OfficeSupply;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Access denied. Admin role required.');
            }
            return $next($request);
        });
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Lấy thống kê tổng quan
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'pending_requests' => SupplyRequest::where('status', 'pending')->count(),
            'total_supplies' => OfficeSupply::count(),
            'active_supplies' => OfficeSupply::where('is_active', true)->count(),
            'low_stock_supplies' => OfficeSupply::where('stock_quantity', '<=', 10)->count(),
        ];

        // Lấy yêu cầu gần nhất
        $recentRequests = SupplyRequest::with(['user', 'requestItems.officeSupply'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Lấy sản phẩm sắp hết hàng
        $lowStockSupplies = OfficeSupply::where('stock_quantity', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        // Thống kê theo tháng
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $month->format('m/Y'),
                'requests' => SupplyRequest::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'users' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentRequests', 'lowStockSupplies', 'monthlyStats'));
    }
}