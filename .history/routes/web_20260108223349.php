<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficeSupplyController;
use App\Http\Controllers\SupplyRequestController;
use App\Http\Controllers\UserManagementController;
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
    // Dashboard routes
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/employee', [App\Http\Controllers\DashboardController::class, 'employee'])->name('dashboard.employee');
    Route::get('/dashboard/approval', [App\Http\Controllers\DashboardController::class, 'approval'])->name('dashboard.approval')->middleware('role:approver');
    Route::post('/dashboard/approve/{supplyRequest}', [App\Http\Controllers\DashboardController::class, 'approve'])->name('dashboard.approve')->middleware('role:approver');

    // Office Supplies Routes
    Route::get('/office-supplies', [OfficeSupplyController::class, 'index'])->name('office-supplies.index');
    Route::get('/office-supplies/create', [OfficeSupplyController::class, 'create'])->name('office-supplies.create')->middleware('can:admin-access');
    Route::get('/office-supplies/admin', [OfficeSupplyController::class, 'getAllForAdmin'])->name('office-supplies.admin')->middleware('can:admin-access');
    Route::post('/office-supplies', [OfficeSupplyController::class, 'store'])->name('office-supplies.store')->middleware('can:admin-access');
    Route::get('/office-supplies/{supply}', [OfficeSupplyController::class, 'show'])->name('office-supplies.show');
    Route::get('/office-supplies/{supply}/edit', [OfficeSupplyController::class, 'edit'])->name('office-supplies.edit')->middleware('can:admin-access');
    Route::put('/office-supplies/{supply}', [OfficeSupplyController::class, 'update'])->name('office-supplies.update')->middleware('can:admin-access');
    Route::delete('/office-supplies/{supply}', [OfficeSupplyController::class, 'destroy'])->name('office-supplies.destroy')->middleware('can:admin-access');

    // Supply Requests Routes
    Route::post('/supply-requests', [SupplyRequestController::class, 'store'])->name('supply-requests.store');
    Route::get('/supply-requests/my-requests', [SupplyRequestController::class, 'myRequests'])->name('supply-requests.my-requests');
    Route::get('/supply-requests/for-approval', [SupplyRequestController::class, 'forApproval'])->name('supply-requests.for-approval');
    Route::post('/supply-requests/{request}/approve', [SupplyRequestController::class, 'approve'])->name('supply-requests.approve');
    Route::post('/supply-requests/{request}/reject', [SupplyRequestController::class, 'reject'])->name('supply-requests.reject');
    Route::get('/supply-requests/{request}', [SupplyRequestController::class, 'show'])->name('supply-requests.show');

    // User Management Routes (Admin only)
    Route::prefix('admin/users')->name('users.')->middleware('can:admin-access')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/stats', [UserManagementController::class, 'getStats'])->name('stats');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::get('/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('toggle-status');
    });
});

// Debug routes
Route::get('/test-users', function () {
    $users = \App\Models\User::all(['id', 'name', 'email', 'role']);
    return response()->json($users);
});

Route::get('/create-mysql-db', function () {
    try {
        // Try to connect to MySQL and create database
        $host = '127.0.0.1';
        $username = 'root';
        $password = '';
        
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "CREATE DATABASE IF NOT EXISTS kdnvpp_office_supplies CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $pdo->exec($sql);
        
        return response()->json([
            'success' => true,
            'message' => 'MySQL database created successfully',
            'database' => 'kdnvpp_office_supplies'
        ]);
        
    } catch (PDOException $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'suggestion' => 'Please make sure MySQL server is running and credentials are correct'
        ], 500);
    }
});

Route::get('/test-mysql-connection', function () {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'success' => true,
            'message' => 'MySQL connection successful',
            'database' => config('database.connections.mysql.database')
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/sqlite-query/{query?}', function ($query = null) {
    try {
        $dbPath = database_path('database.sqlite');
        $pdo = new PDO("sqlite:$dbPath");
        
        if (!$query) {
            // Default query to show users table
            $query = "SELECT id, name, email, role, department, length(password) as pwd_len, created_at FROM users ORDER BY id";
        } else {
            $query = base64_decode($query);
        }
        
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $output = '<h2>SQLite Query Result</h2>';
        $output .= '<p><strong>Query:</strong> ' . htmlspecialchars($query) . '</p>';
        
        if ($results) {
            $output .= '<table border="1" cellpadding="5"><tr>';
            foreach (array_keys($results[0]) as $column) {
                $output .= '<th>' . $column . '</th>';
            }
            $output .= '</tr>';
            
            foreach ($results as $row) {
                $output .= '<tr>';
                foreach ($row as $value) {
                    $output .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $output .= '</tr>';
            }
            $output .= '</table>';
        } else {
            $output .= '<p>No results found.</p>';
        }
        
        $output .= '<br><h3>Common Queries:</h3>';
        $output .= '<a href="/sqlite-query/' . base64_encode("SELECT * FROM users") . '">All Users</a><br>';
        $output .= '<a href="/sqlite-query/' . base64_encode("SELECT name, email, role FROM users WHERE email LIKE '%example%'") . '">Users with example email</a><br>';
        $output .= '<a href="/sqlite-query/' . base64_encode("DELETE FROM users WHERE email = 'admin@example.com'") . '">Delete admin@example.com</a><br>';
        
        return $output;
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/database-users', function () {
    $users = \App\Models\User::all();
    $output = '<h2>All Users in Database</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Department</th><th>Password Hash Length</th><th>Created</th></tr>';
    foreach ($users as $user) {
        $output .= '<tr>';
        $output .= '<td>' . $user->id . '</td>';
        $output .= '<td>' . $user->name . '</td>';
        $output .= '<td>' . $user->email . '</td>';
        $output .= '<td>' . $user->role . '</td>';
        $output .= '<td>' . $user->department . '</td>';
        $output .= '<td>' . strlen($user->password) . '</td>';
        $output .= '<td>' . $user->created_at . '</td>';
        $output .= '</tr>';
    }
    $output .= '</table>';
    $output .= '<br><a href="/delete-old-user">Delete admin@example.com</a>';
    $output .= '<br><a href="/test-admin-example">Test admin@example.com password</a>';
    return $output;
});

Route::get('/delete-old-user', function () {
    try {
        $deleted = \App\Models\User::where('email', 'admin@example.com')->delete();
        return response()->json(['message' => 'Deleted admin@example.com', 'count' => $deleted]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/test-admin-example', function () {
    $user = \App\Models\User::where('email', 'admin@example.com')->first();
    if ($user) {
        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'password_hash' => $user->password,
            'test_password' => \Illuminate\Support\Facades\Hash::check('password', $user->password),
        ]);
    }
    return response()->json(['error' => 'User not found'], 404);
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

// Employee Request Management Routes
Route::middleware('auth')->prefix('employee')->name('employee.')->group(function() {
    Route::get('/requests', [App\Http\Controllers\EmployeeRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [App\Http\Controllers\EmployeeRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [App\Http\Controllers\EmployeeRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{request}', [App\Http\Controllers\EmployeeRequestController::class, 'show'])->name('requests.show');
    Route::patch('/requests/{request}/forward', [App\Http\Controllers\EmployeeRequestController::class, 'forward'])->name('requests.forward');
    Route::get('/requests/{request}/history', [App\Http\Controllers\EmployeeRequestController::class, 'history'])->name('requests.history');
});

Route::get('/test-employee-create', function() {
    try {
        $controller = new App\Http\Controllers\EmployeeRequestController();
        return $controller->create();
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
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