<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuxiliaryDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\DepartureController;
use App\Http\Controllers\CompanyController;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::post('/reports/update/{id}', [UpdateController::class, 'store'])->name('updates.store');

    Route::get('/departures', [DepartureController::class, 'index'])->name('departures.index');

    Route::get('/auxiliary/transport-types', [AuxiliaryDataController::class, 'getTransportTypes'])->name('auxiliary.transport-types');
    Route::get('/auxiliary/emergency-types', [AuxiliaryDataController::class, 'getEmergencyTypes'])->name('auxiliary.emergency-types');
    Route::get('/auxiliary/branch-offices', [AuxiliaryDataController::class, 'getBranchOffices'])->name('auxiliary.branch-offices');
    Route::get('/auxiliary/companies', [CompanyController::class, 'index'])->name('companies.index');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
