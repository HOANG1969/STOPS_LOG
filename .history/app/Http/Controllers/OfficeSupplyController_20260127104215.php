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
    public function index()
    {
        return view('office-supplies.request');
    }

    /**
     * Display admin management page for office supplies
     */
    public function admin()
    {
        return view('office-supplies.admin');
    }

    /**
     * API endpoint to get office supplies for AJAX requests
     */
    public function getForRequest(): JsonResponse
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('office-supplies.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficeSupply $supply)
    {
        return view('office-supplies.show', compact('supply'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfficeSupply $supply)
    {
        return view('office-supplies.edit', compact('supply'));
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('office-supplies.import');
    }

    /**
     * Import office supplies from file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx'
        ]);

        $file = $request->file('file');
        $path = $file->getPathname();
        
        $data = [];
        $errors = [];
        $successCount = 0;

        // Read CSV file
        if (($handle = fopen($path, "r")) !== FALSE) {
            $header = fgetcsv($handle); // Skip header row
            $rowIndex = 1;
            
            while (($row = fgetcsv($handle)) !== FALSE) {
                $rowIndex++;
                
                if (count($row) < 6) {
                    $errors[] = "Dòng {$rowIndex}: Thiếu dữ liệu (cần ít nhất 6 cột)";
                    continue;
                }
                
                try {
                    OfficeSupply::create([
                        'name' => $row[0],
                        'description' => $row[1] ?? null,
                        'unit' => $row[2],
                        'price' => (float) $row[3],
                        'stock_quantity' => (int) $row[4],
                        'category' => $row[5],
                        'is_active' => true
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Dòng {$rowIndex}: " . $e->getMessage();
                }
            }
            fclose($handle);
        }

        return response()->json([
            'success' => true,
            'message' => "Đã import thành công {$successCount} văn phòng phẩm",
            'errors' => $errors,
            'total_success' => $successCount,
            'total_errors' => count($errors)
        ]);
    }
}
