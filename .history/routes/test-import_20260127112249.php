<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficeSupplyController;

// Test import route
Route::post('/test-import', function(\Illuminate\Http\Request $request) {
    try {
        \Log::info('Test import started');
        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);
        
        $file = $request->file('file');
        $path = $file->getPathname();
        $extension = $file->getClientOriginalExtension();
        
        \Log::info('File info', [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $extension,
            'path' => $path
        ]);
        
        $data = [];
        
        if (in_array(strtolower($extension), ['csv', 'txt'])) {
            if (($handle = fopen($path, "r")) !== FALSE) {
                $header = fgetcsv($handle);
                \Log::info('CSV Header', ['header' => $header]);
                
                $rowCount = 0;
                while (($row = fgetcsv($handle)) !== FALSE && $rowCount < 5) {
                    \Log::info('CSV Row', ['row' => $row]);
                    $data[] = $row;
                    $rowCount++;
                }
                fclose($handle);
            }
        }
        
        \Log::info('Import test completed', ['data_count' => count($data)]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test import successful',
            'data' => $data,
            'file_info' => [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getClientMimeType()
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Import test error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
})->middleware('auth');