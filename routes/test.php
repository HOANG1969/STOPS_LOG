<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeRequestController;

Route::get('/test-employee-create', function() {
    try {
        $controller = new EmployeeRequestController();
        return $controller->create();
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    }
});