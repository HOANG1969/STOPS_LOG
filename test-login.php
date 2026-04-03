<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Login Functionality\n";
echo "==========================\n\n";

$testUsers = [
    'employee.it@company.com',
    'manager.it@company.com',
    'checker.tchc@company.com',
    'manager.tchc@company.com'
];

foreach ($testUsers as $email) {
    echo "Testing: $email\n";
    $user = App\Models\User::where('email', $email)->first();
    
    if ($user) {
        echo "  ✓ User found: {$user->name} ({$user->full_name})\n";
        echo "  ✓ Role: {$user->role}\n";
        echo "  ✓ Department: {$user->department}\n";
        $passwordCheck = Illuminate\Support\Facades\Hash::check('123456', $user->password);
        echo "  " . ($passwordCheck ? "✓" : "✗") . " Password '123456': " . ($passwordCheck ? "CORRECT" : "WRONG") . "\n";
    } else {
        echo "  ✗ User NOT found\n";
    }
    echo "\n";
}

echo "Login credentials:\n";
echo "==================\n";
echo "Email: [any email above]\n";
echo "Password: 123456\n";
