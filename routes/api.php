<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuxiliaryDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\DepartureController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmailController;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/image', [ImageController::class, 'show']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/send-feedback', [EmailController::class, 'send']);
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');

    Route::prefix('auxiliary')->name('auxiliary.')->controller(AuxiliaryDataController::class)->group(function () {
        Route::get('/transport-types', 'getTransportTypes')->name('transport-types');
        Route::get('/emergency-types', 'getEmergencyTypes')->name('emergency-types');
        Route::get('/branch-offices', 'getBranchOffices')->name('branch-offices');
        Route::get('/mineral-fields', 'getMineralFields')->name('mineral-fields');
        Route::get('/oil-fields', 'getOilFields')->name('oil-fields');
        Route::get('/subsoil-contracts', 'getSubsoilContracts')->name('subsoil-contracts');
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
        // Departures Routes
        Route::prefix('departures')->name('departures.')->controller(DepartureController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/count-table', 'countTable')->name('count-table');
        });

        // Statistics Routes
        Route::prefix('statistics')->name('statistics.')->controller(StatisticsController::class)->group(function () {
            Route::get('/card-statistics', 'getCardStatistics')->name('card-statistics');
            Route::get('/emergency-types-distribution', 'getEmergencyTypesDistribution')->name('emergency-types-distribution');
            Route::get('/emergencies-per-branch-office', 'getEmergenciesPerBranchOffice')->name('emergencies-per-branch-office');
            Route::get('/injury-counts-per-emergency-type', 'getInjuryCountsPerEmergencyType')->name('injury-counts-per-emergency-type');
            Route::get('/evacuated-people-medical-aid', 'getEvacuatedPeopleMedicalAid')->name('evacuated-people-medical-aid');
            Route::get('/response-time-per-emergency-type', 'getResponseTimePerEmergencyType')->name('response-time-per-emergency-type');
            Route::get('/worker-man-hours-per-emergency', 'getWorkerManHoursPerEmergency')->name('worker-man-hours-per-emergency');
            Route::get('/total-injuries-impact-over-time', 'getTotalInjuriesImpactOverTime')->name('total-injuries-impact-over-time');
        });
    });

});

Route::post('/login', [AuthController::class, 'login']);
