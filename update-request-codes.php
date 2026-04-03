<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Update Existing Request Codes ===\n\n";

$requests = App\Models\SupplyRequest::all();

foreach ($requests as $request) {
    $oldCode = $request->request_code;
    
    // Tạo mã mới dựa trên bộ phận và thời gian tạo
    $month = $request->created_at->format('m');
    $year = $request->created_at->format('Y');
    $department = $request->requester_department;
    
    // Đếm số phiếu trước đó trong cùng tháng của cùng bộ phận
    $previousCount = App\Models\SupplyRequest::where('requester_department', $department)
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->where('id', '<', $request->id)
        ->count();
    
    $sequence = $previousCount + 1;
    
    // Tạo prefix không dấu
    $deptPrefix = App\Models\SupplyRequest::removeVietnameseTones($department);
    $deptPrefix = strtoupper(str_replace(' ', '', $deptPrefix));
    
    $newCode = $deptPrefix . '_' . $month . '_' . $year . '_' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    
    $request->request_code = $newCode;
    $request->save();
    
    echo "Updated ID {$request->id}: {$oldCode} -> {$newCode} (Dept: {$department})\n";
}

echo "\n=== Done! ===\n";
