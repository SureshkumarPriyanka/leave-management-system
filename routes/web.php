<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Group for Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('leave-requests', [App\Http\Controllers\Admin\LeaveRequestController::class, 'index'])->name('leave.index');
    Route::post('leave-requests/{id}/approve', [App\Http\Controllers\Admin\LeaveRequestController::class, 'approve'])->name('leave.approve');
    Route::post('leave-requests/{id}/reject', [App\Http\Controllers\Admin\LeaveRequestController::class, 'reject'])->name('leave.reject');
    Route::get('reports', [App\Http\Controllers\Admin\LeaveRequestController::class, 'report'])->name('leave.report');
    Route::get('export', [App\Http\Controllers\Admin\LeaveRequestController::class, 'export'])->name('leave.export');
});

// Group for Employee
Route::middleware(['auth', 'role:Employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('leave-requests', [App\Http\Controllers\Employee\LeaveRequestController::class, 'index'])->name('leave.index');
    Route::get('leave-requests/create', [App\Http\Controllers\Employee\LeaveRequestController::class, 'create'])->name('leave.create');
    Route::post('leave-requests', [App\Http\Controllers\Employee\LeaveRequestController::class, 'store'])->name('leave.store');
});
