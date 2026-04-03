<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficeSupplyController;
use App\Http\Controllers\SupplyRequestController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Thông tin đăng nhập không chính xác.',
    ])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Office Supplies Routes
    Route::get('/office-supplies', [OfficeSupplyController::class, 'index'])->name('office-supplies.index');
    Route::get('/office-supplies/admin', [OfficeSupplyController::class, 'getAllForAdmin'])->name('office-supplies.admin')->middleware('can:admin-access');
    Route::post('/office-supplies', [OfficeSupplyController::class, 'store'])->name('office-supplies.store')->middleware('can:admin-access');
    Route::put('/office-supplies/{supply}', [OfficeSupplyController::class, 'update'])->name('office-supplies.update')->middleware('can:admin-access');
    Route::delete('/office-supplies/{supply}', [OfficeSupplyController::class, 'destroy'])->name('office-supplies.destroy')->middleware('can:admin-access');

    // Supply Requests Routes
    Route::post('/supply-requests', [SupplyRequestController::class, 'store'])->name('supply-requests.store');
    Route::get('/supply-requests/my-requests', [SupplyRequestController::class, 'myRequests'])->name('supply-requests.my-requests');
    Route::get('/supply-requests/for-approval', [SupplyRequestController::class, 'forApproval'])->name('supply-requests.for-approval');
    Route::post('/supply-requests/{request}/approve', [SupplyRequestController::class, 'approve'])->name('supply-requests.approve');
    Route::post('/supply-requests/{request}/reject', [SupplyRequestController::class, 'reject'])->name('supply-requests.reject');
    Route::get('/supply-requests/{request}', [SupplyRequestController::class, 'show'])->name('supply-requests.show');
});

// Debug routes
Route::get('/test-users', function () {
    $users = \App\Models\User::all(['id', 'name', 'email', 'role']);
    return response()->json($users);
});

Route::get('/recreate-simple-users', function () {
    try {
        // Delete all existing users
        \App\Models\User::truncate();
        
        // Create new users with simple email domains
        $users = [
            [
                'name' => 'admin',
                'full_name' => 'Quản trị viên hệ thống',
                'email' => 'admin@test.com',
                'password' => bcrypt('admin123'),
                'department' => 'Quản trị',
                'position' => 'Quản trị viên hệ thống',
                'role' => 'admin',
                'phone' => '0901234567',
                'is_active' => true,
            ],
            [
                'name' => 'hr_approve',
                'full_name' => 'Nguyễn Thị Hạnh',
                'email' => 'hr@test.com',
                'password' => bcrypt('hr123'),
                'department' => 'Nhân sự',
                'position' => 'Trưởng phòng Nhân sự',
                'role' => 'approver',
                'phone' => '0901234568',
                'is_active' => true,
            ],
            [
                'name' => 'it_approve',
                'full_name' => 'Trần Văn Nam',
                'email' => 'it@test.com',
                'password' => bcrypt('it123'),
                'department' => 'IT',
                'position' => 'Trưởng phòng IT',
                'role' => 'approver',
                'phone' => '0901234569',
                'is_active' => true,
            ],
            [
                'name' => 'user1',
                'full_name' => 'Nhân viên 1',
                'email' => 'user1@test.com',
                'password' => bcrypt('user123'),
                'department' => 'IT',
                'position' => 'Nhân viên',
                'role' => 'employee',
                'phone' => '0901234571',
                'is_active' => true,
            ],
            [
                'name' => 'user2',
                'full_name' => 'Nhân viên 2',
                'email' => 'user2@test.com',
                'password' => bcrypt('user123'),
                'department' => 'Nhân sự',
                'position' => 'Nhân viên',
                'role' => 'employee',
                'phone' => '0901234572',
                'is_active' => true,
            ]
        ];
        
        foreach ($users as $userData) {
            \App\Models\User::create($userData);
        }
        
        return response()->json([
            'message' => 'Simple users created successfully',
            'users' => \App\Models\User::all(['id', 'name', 'email', 'role'])
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/recreate-users', function () {
    try {
        // Delete all existing users
        \App\Models\User::truncate();
        
        // Create new users with correct passwords
        $users = [
            [
                'name' => 'admin',
                'full_name' => 'Quản trị viên hệ thống',
                'email' => 'admin@company.com',
                'password' => bcrypt('admin123'),
                'department' => 'Quản trị',
                'position' => 'Quản trị viên hệ thống',
                'role' => 'admin',
                'phone' => '0901234567',
                'is_active' => true,
            ],
            [
                'name' => 'hr_approve',
                'full_name' => 'Nguyễn Thị Hạnh',
                'email' => 'hr.approve@company.com',
                'password' => bcrypt('hr123'),
                'department' => 'Nhân sự',
                'position' => 'Trưởng phòng Nhân sự',
                'role' => 'approver',
                'phone' => '0901234568',
                'is_active' => true,
            ],
            [
                'name' => 'it_approve',
                'full_name' => 'Trần Văn Nam',
                'email' => 'it.approve@company.com',
                'password' => bcrypt('it123'),
                'department' => 'IT',
                'position' => 'Trưởng phòng IT',
                'role' => 'approver',
                'phone' => '0901234569',
                'is_active' => true,
            ],
            [
                'name' => 'hr_emp',
                'full_name' => 'Lê Văn Hoàng',
                'email' => 'hr.employee@company.com',
                'password' => bcrypt('hr123'),
                'department' => 'Nhân sự',
                'position' => 'Nhân viên Nhân sự',
                'role' => 'employee',
                'phone' => '0901234571',
                'is_active' => true,
            ],
            [
                'name' => 'it_emp',
                'full_name' => 'Vũ Minh Tuấn',
                'email' => 'it.employee@company.com',
                'password' => bcrypt('it123'),
                'department' => 'IT',
                'position' => 'Lập trình viên',
                'role' => 'employee',
                'phone' => '0901234572',
                'is_active' => true,
            ]
        ];
        
        foreach ($users as $userData) {
            \App\Models\User::create($userData);
        }
        
        return response()->json([
            'message' => 'Users recreated successfully',
            'users' => \App\Models\User::all(['id', 'name', 'email', 'role'])
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/create-test-user', function () {
    try {
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'Test User',
                'full_name' => 'Test User',
                'password' => \Illuminate\Support\Facades\Hash::make('test123'),
                'department' => 'IT',
                'position' => 'Test Position',
                'role' => 'employee',
                'phone' => '0123456789',
                'is_active' => true,
            ]
        );
        
        return response()->json(['message' => 'Test user created', 'user' => $user]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-password/{email}/{password}', function ($email, $password) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        return response()->json([
            'user_email' => $user->email,
            'provided_password' => $password,
            'password_check' => \Illuminate\Support\Facades\Hash::check($password, $user->password),
            'stored_hash_length' => strlen($user->password)
        ]);
    }
    return response()->json(['error' => 'User not found'], 404);
});