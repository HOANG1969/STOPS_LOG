<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficeSupplyController;
use App\Http\Controllers\SupplyRequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/test-users', function () {
    $users = User::all(['id', 'name', 'email', 'role']);
    return response()->json($users);
});

Route::get('/test-auth/{email}', function ($email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'password_exists' => !empty($user->password),
            'password_length' => strlen($user->password)
        ]);
    }
    return response()->json(['error' => 'User not found'], 404);
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

    // Debug log
    \Log::info('Login attempt', [
        'email' => $credentials['email'],
        'password_provided' => !empty($credentials['password'])
    ]);

    $user = User::where('email', $credentials['email'])->first();
    
    if ($user) {
        \Log::info('User found', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'password_check' => Hash::check($credentials['password'], $user->password)
        ]);
    } else {
        \Log::info('User not found for email: ' . $credentials['email']);
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        \Log::info('Login successful for: ' . $credentials['email']);
        return redirect()->intended('dashboard');
    }

    \Log::info('Login failed for: ' . $credentials['email']);
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