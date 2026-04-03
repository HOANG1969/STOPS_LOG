<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test Request Code Generation ===\n\n";

// Test với các bộ phận khác nhau
$departments = ['KTSX', 'IT', 'Kế toán', 'Nhân sự'];

foreach ($departments as $dept) {
    $code = App\Models\SupplyRequest::generateRequestCode($dept);
    echo "$dept: $code\n";
}

echo "\n=== Current Requests ===\n";
$requests = App\Models\SupplyRequest::orderBy('created_at', 'desc')->limit(5)->get();
foreach ($requests as $req) {
    echo "ID: {$req->id}, Code: {$req->request_code}, Dept: {$req->requester_department}, Date: {$req->created_at}\n";
}
