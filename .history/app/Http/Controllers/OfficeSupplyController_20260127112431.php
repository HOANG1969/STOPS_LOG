<?php

namespace App\Http\Controllers;

use App\Models\OfficeSupply;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OfficeSupplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin-access')->only([
            'admin', 'create', 'store', 'edit', 'update', 'destroy', 
            'getAllForAdmin', 'showImportForm', 'import'
        ]);
    }
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
        return view('office-supplies.add');
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
        try {
            \Log::info('Import started', ['user' => auth()->id()]);
            
            $request->validate([
                'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120'
            ]);

            $file = $request->file('file');
            \Log::info('File received', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getClientMimeType()
            ]);
            
            $path = $file->getPathname();
            $extension = strtolower($file->getClientOriginalExtension());
            
            $errors = [];
            $successCount = 0;
            $rowIndex = 1;

            // Handle CSV files
            if (in_array($extension, ['csv', 'txt'])) {
                \Log::info('Processing CSV file');
                
                if (($handle = fopen($path, "r")) !== FALSE) {
                    // Skip header row
                    $header = fgetcsv($handle);
                    \Log::info('CSV header', ['header' => $header]);
                    
                    while (($row = fgetcsv($handle)) !== FALSE) {
                        $rowIndex++;
                        \Log::info("Processing row {$rowIndex}", ['row' => $row]);
                        
                        $result = $this->processRow($row, $rowIndex);
                        
                        if ($result['success']) {
                            $successCount++;
                        } else {
                            $errors[] = $result['error'];
                        }
                    }
                    fclose($handle);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Hiện tại chỉ hỗ trợ file CSV. File Excel sẽ được hỗ trợ trong phiên bản tiếp theo.'
                ], 400);
            }

            \Log::info('Import completed', [
                'success_count' => $successCount,
                'error_count' => count($errors)
            ]);

            $totalProcessed = $successCount + count($errors);
            
            if ($successCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Đã import thành công {$successCount}/{$totalProcessed} văn phòng phẩm",
                    'errors' => $errors,
                    'total_success' => $successCount,
                    'total_errors' => count($errors)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có dữ liệu nào được import thành công',
                    'errors' => $errors,
                    'total_success' => 0,
                    'total_errors' => count($errors)
                ], 400);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'File không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Import error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a single row of import data
     */
    private function processRow($row, $rowIndex)
    {
        try {
            // Validate required columns
            if (count($row) < 6) {
                return [
                    'success' => false,
                    'error' => "Dòng {$rowIndex}: Thiếu dữ liệu (cần ít nhất 6 cột: Tên, Mô tả, Đơn vị, Giá, Số lượng, Danh mục)"
                ];
            }

            $name = trim($row[0]);
            $description = isset($row[1]) ? trim($row[1]) : null;
            $unit = trim($row[2]);
            $price = trim($row[3]);
            $stockQuantity = trim($row[4]);
            $category = trim($row[5]);

            // Validate required fields
            if (empty($name)) {
                return [
                    'success' => false,
                    'error' => "Dòng {$rowIndex}: Tên văn phòng phẩm không được để trống"
                ];
            }

            if (empty($unit)) {
                return [
                    'success' => false,
                    'error' => "Dòng {$rowIndex}: Đơn vị không được để trống"
                ];
            }

            if (empty($category)) {
                return [
                    'success' => false,
                    'error' => "Dòng {$rowIndex}: Danh mục không được để trống"
                ];
            }

            // Validate and convert price
            if (!is_numeric($price) || (float)$price < 0) {
                return [
                    'success' => false,
                    'error' => "Dòng {$rowIndex}: Giá phải là số và lớn hơn hoặc bằng 0"
                ];
            }

            // Validate and convert stock quantity
            if (!is_numeric($stockQuantity) || (int)$stockQuantity < 0) {
                return [
                    'success' => false,
                    'error' => "Dòng {$rowIndex}: Số lượng tồn phải là số nguyên và lớn hơn hoặc bằng 0"
                ];
            }

            // Check if product already exists
            $existingProduct = OfficeSupply::where('name', $name)
                ->where('unit', $unit)
                ->first();

            if ($existingProduct) {
                // Update existing product
                $existingProduct->update([
                    'description' => $description,
                    'price' => (float)$price,
                    'stock_quantity' => (int)$stockQuantity,
                    'category' => $category,
                    'is_active' => true
                ]);
                
                return [
                    'success' => true,
                    'message' => "Cập nhật sản phẩm: {$name}"
                ];
            } else {
                // Create new product
                OfficeSupply::create([
                    'name' => $name,
                    'description' => $description,
                    'unit' => $unit,
                    'price' => (float)$price,
                    'stock_quantity' => (int)$stockQuantity,
                    'category' => $category,
                    'is_active' => true
                ]);

                return [
                    'success' => true,
                    'message' => "Thêm mới sản phẩm: {$name}"
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => "Dòng {$rowIndex}: " . $e->getMessage()
            ];
        }
    }
}
