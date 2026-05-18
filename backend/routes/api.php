<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Employee APIs
|--------------------------------------------------------------------------
*/

Route::get('employees/list', [EmployeeController::class, 'getEmployeeList']);
Route::patch('employees/{employee}/active', [EmployeeController::class, 'active']);
Route::apiResource('employees', EmployeeController::class);

/*
|--------------------------------------------------------------------------
| Dashboard APIs
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')->group(function () {
    Route::get('summary', [DashboardController::class, 'summary']);

    Route::get('country-salary-insights', [DashboardController::class, 'countrySalaryInsights']);

    Route::get('job-title-insights', [DashboardController::class, 'jobTitleInsights']);

    Route::get('department-insights', [DashboardController::class, 'departmentInsights']);

    Route::get('salary-distribution', [DashboardController::class, 'salaryDistribution']);
});
