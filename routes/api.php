<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyAreaController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CompanyDepartmentController;
use App\Http\Controllers\Api\EmployeeController;

Route::prefix('companies')->controller(CompanyController::class)->name('company.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::post('/update', 'update')->name('update');
    Route::get('/delete/{id}', 'destroy')->name('delete');
});

Route::prefix('company-areas')->controller(CompanyAreaController::class)->name('companyArea.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/byCompany/{company_department_id}', 'selector')->name('selector');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::post('/update', 'update')->name('update');
    Route::get('/delete/{id}', 'destroy')->name('delete');
});

Route::prefix('company-department')->controller(CompanyDepartmentController::class)->name('companyDepartment.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/byArea/{id}', 'selector')->name('selector');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::post('/update', 'update')->name('update');
    Route::get('/delete/{id}', 'destroy')->name('delete');
});

Route::prefix('employees')->controller(EmployeeController::class)->name('employee.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::post('/update', 'update')->name('update');
    Route::get('/delete/{id}', 'destroy')->name('delete');
});
