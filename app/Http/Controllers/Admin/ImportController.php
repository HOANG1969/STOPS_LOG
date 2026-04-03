<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeSupply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
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
     * Show import form
     */
    public function showImportForm()
    {
        return view('admin.import.office-supplies');
    }

    /**
     * Import office supplies from CSV/Excel file
     */
    public function importOfficeSupplies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        try {
            $data = [];
            
            if ($extension === 'csv') {
                $data = $this->parseCsvFile($file);
            } else {
                $data = $this->parseExcelFile($file);
            }

            $results = $this->processImportData($data);

            return redirect()->back()->with('success', 
                "Import thành công! Đã thêm {$results['success']} sản phẩm, " .
                "bỏ qua {$results['skipped']} sản phẩm, " .
                "lỗi {$results['errors']} sản phẩm."
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 
                'Có lỗi xảy ra khi import: ' . $e->getMessage()
            );
        }
    }

    /**
     * Parse CSV file
     */
    private function parseCsvFile($file)
    {
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        
        $records = [];
        foreach ($csv as $record) {
            $records[] = $record;
        }
        
        return $records;
    }

    /**
     * Parse Excel file
     */
    private function parseExcelFile($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();
        
        // Get header row
        $headers = array_shift($data);
        
        // Convert to associative array
        $records = [];
        foreach ($data as $row) {
            $record = [];
            foreach ($headers as $index => $header) {
                $record[strtolower(trim($header))] = $row[$index] ?? '';
            }
            $records[] = $record;
        }
        
        return $records;
    }

    /**
     * Process import data and save to database
     */
    private function processImportData($data)
    {
        $successCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        foreach ($data as $row) {
            try {
                // Map columns (flexible mapping)
                $mappedData = $this->mapColumns($row);
                
                if (empty($mappedData['name']) || empty($mappedData['unit'])) {
                    $skippedCount++;
                    continue;
                }

                // Check if product already exists
                $existingSupply = OfficeSupply::where('name', $mappedData['name'])->first();
                
                if ($existingSupply) {
                    // Update existing
                    $existingSupply->update($mappedData);
                } else {
                    // Create new
                    OfficeSupply::create($mappedData);
                }
                
                $successCount++;
                
            } catch (\Exception $e) {
                $errorCount++;
                \Log::error('Import error for row: ' . json_encode($row) . ' Error: ' . $e->getMessage());
            }
        }

        return [
            'success' => $successCount,
            'skipped' => $skippedCount,
            'errors' => $errorCount
        ];
    }

    /**
     * Map CSV/Excel columns to database fields
     */
    private function mapColumns($row)
    {
        // Flexible column mapping - support different column names
        $columnMappings = [
            'name' => ['name', 'ten_san_pham', 'tên sản phẩm', 'product_name'],
            'description' => ['description', 'mo_ta', 'mô tả', 'desc'],
            'unit' => ['unit', 'don_vi', 'đơn vị', 'donvi'],
            'price' => ['price', 'gia', 'giá', 'gia_ban', 'giá bán'],
            'stock_quantity' => ['stock_quantity', 'so_luong', 'số lượng', 'quantity', 'soluong'],
            'category' => ['category', 'danh_muc', 'danh mục', 'loai', 'loại']
        ];

        $mappedData = [
            'is_active' => true
        ];

        foreach ($columnMappings as $dbField => $possibleColumns) {
            foreach ($possibleColumns as $column) {
                if (isset($row[$column]) && !empty($row[$column])) {
                    $value = trim($row[$column]);
                    
                    // Type conversion
                    if ($dbField === 'price') {
                        $value = (float) str_replace([',', '.'], ['', '.'], $value);
                    } elseif ($dbField === 'stock_quantity') {
                        $value = (int) $value;
                    }
                    
                    $mappedData[$dbField] = $value;
                    break;
                }
            }
        }

        // Set defaults
        $mappedData['price'] = $mappedData['price'] ?? 0;
        $mappedData['stock_quantity'] = $mappedData['stock_quantity'] ?? 0;
        $mappedData['category'] = $mappedData['category'] ?? 'Khác';

        return $mappedData;
    }

    /**
     * Download sample template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="office_supplies_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Write BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($file, [
                'name',
                'description', 
                'unit',
                'price',
                'stock_quantity',
                'category'
            ]);
            
            // Write sample data
            fputcsv($file, [
                'Bút bi xanh',
                'Bút bi màu xanh, chất lượng cao',
                'cái',
                '5000',
                '100',
                'Văn phòng phẩm'
            ]);
            
            fputcsv($file, [
                'Giấy A4',
                'Giấy in A4, 70gsm',
                'ream',
                '95000',
                '50',
                'Giấy tờ'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}