<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuxiliaryDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\DepartureController;
use App\Http\Controllers\CompanyController;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/image', function (Request $request) {
//        return $request->query('path');
        $filePath = public_path('storage/' . $request->query('path'));

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->file($filePath);
    });


    Route::group(['middleware' => ['permission:user_register']], function () {
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::group(['middleware' => ['permission:branch_report_view']], function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    });

    Route::group(['middleware' => ['permission:branch_report_create']], function () {
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    });

    Route::group(['middleware' => ['permission:branch_update_create']], function () {
        Route::post('/reports/update/{id}', [UpdateController::class, 'store'])->name('updates.store');
    });

    Route::group(['middleware' => ['permission:departure_view']], function () {
        Route::get('/departures', [DepartureController::class, 'index'])->name('departures.index');
        Route::get('/departures/count-table/', [DepartureController::class, 'countTable'])->name('departures.count-table');
    });

    Route::get('/auxiliary/transport-types', [AuxiliaryDataController::class, 'getTransportTypes'])->name('auxiliary.transport-types');
    Route::get('/auxiliary/emergency-types', [AuxiliaryDataController::class, 'getEmergencyTypes'])->name('auxiliary.emergency-types');
    Route::get('/auxiliary/branch-offices', [AuxiliaryDataController::class, 'getBranchOffices'])->name('auxiliary.branch-offices');
    Route::get('/auxiliary/mineral-fields', [AuxiliaryDataController::class, 'getMineralFields'])->name('auxiliary.mineral-fields');
    Route::get('/auxiliary/oil-fields', [AuxiliaryDataController::class, 'getOilFields'])->name('auxiliary.oil-fields');
    Route::get('/auxiliary/subsoil-contracts', [AuxiliaryDataController::class, 'getSubsoilContracts'])->name('auxiliary.subsoil-contracts');
    Route::get('/auxiliary/companies', [CompanyController::class, 'index'])->name('companies.index');

});

Route::post('/login', [AuthController::class, 'login']);
