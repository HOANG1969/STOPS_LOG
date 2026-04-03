<?php

namespace App\Http\Controllers;

use App\Models\OfficeSupply;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OfficeSupplyController extends Controller
{
    /**
     * Display a listing of office supplies
     */
    public function index(): JsonResponse
    {
        $supplies = OfficeSupply::where('is_active', true)
                                ->orderBy('category')
                                ->orderBy('name')
                                ->get();
        
        return response()->json($supplies);
    }

    /**
     * Store a newly created office supply
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|max:100'
        ]);

        $validated['is_active'] = true;
        
        $supply = OfficeSupply::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã thêm văn phòng phẩm thành công',
            'data' => $supply
        ], 201);
    }

    /**
     * Update the specified office supply
     */
    public function update(Request $request, OfficeSupply $supply): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean'
        ]);

        $supply->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật văn phòng phẩm thành công',
            'data' => $supply
        ]);
    }

    /**
     * Remove the specified office supply
     */
    public function destroy(OfficeSupply $supply): JsonResponse
    {
        // Soft delete by setting is_active to false
        $supply->update(['is_active' => false]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa văn phòng phẩm thành công'
        ]);
    }

    /**
     * Get office supplies for admin management
     */
    public function getAllForAdmin(): JsonResponse
    {
        $supplies = OfficeSupply::orderBy('category')
                                ->orderBy('name')
                                ->get();
        
        return response()->json($supplies);
    }
}
