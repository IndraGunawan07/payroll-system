<?php

use App\Http\Controllers\Attendances\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\OvertimeRequests\OvertimeRequestController;
use App\Http\Controllers\PayrollPeriods\PayrollPeriodController;
use App\Http\Controllers\Payrolls\PayrollController;
use App\Http\Controllers\Payslips\PayslipController;
use App\Http\Controllers\Reimbursements\ReimbursementController;
use App\Models\OvertimeRequest;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'attendance'], function () {
        Route::post('/', [AttendanceController::class, 'store']);
    });

    Route::group(['prefix' => 'overtime'], function () {
        Route::post('/', [OvertimeRequestController::class, 'store']);
    });

    Route::group(['prefix' => 'reimbursement'], function () {
        Route::post('/', [ReimbursementController::class, 'store']);
    });

    Route::group(['prefix' => 'payslip'], function () {
        Route::post('/', [PayslipController::class, 'store']);
    });

    
    Route::middleware('admin')->group(function () {
        Route::group(['prefix' => 'payroll-periods'], function () {
            Route::get('/', [PayrollPeriodController::class, 'index']);
            Route::post('/', [PayrollPeriodController::class, 'store']);
        });

        Route::post('/payroll', [PayrollController::class, 'run']);
        
        Route::get('/payslip', [PayslipController::class, 'summary']);

        Route::get('/employee', [EmployeeController::class, 'index']);
    });
});