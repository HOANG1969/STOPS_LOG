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
    Route::post('/office-supplies', [OfficeSupplyController::class, 'store'])->name('office-supplies.store');
    Route::put('/office-supplies/{supply}', [OfficeSupplyController::class, 'update'])->name('office-supplies.update');
    Route::delete('/office-supplies/{supply}', [OfficeSupplyController::class, 'destroy'])->name('office-supplies.destroy');

    // Supply Requests Routes
    Route::post('/supply-requests', [SupplyRequestController::class, 'store'])->name('supply-requests.store');
    Route::get('/supply-requests/my-requests', [SupplyRequestController::class, 'myRequests'])->name('supply-requests.my-requests');
    Route::get('/supply-requests/for-approval', [SupplyRequestController::class, 'forApproval'])->name('supply-requests.for-approval');
    Route::post('/supply-requests/{request}/approve', [SupplyRequestController::class, 'approve'])->name('supply-requests.approve');
    Route::post('/supply-requests/{request}/reject', [SupplyRequestController::class, 'reject'])->name('supply-requests.reject');
    Route::get('/supply-requests/{request}', [SupplyRequestController::class, 'show'])->name('supply-requests.show');
});