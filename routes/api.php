<?php

use App\Http\Controllers\V2\AuthController;
use App\Http\Controllers\V2\AuxiliaryDataController;
use App\Http\Controllers\V2\CompanyController;
use App\Http\Controllers\V2\EmailController;
use App\Http\Controllers\V2\ImageController;
use App\Http\Controllers\V2\ReportController;
use App\Http\Controllers\V2\StatisticsController;
use App\Http\Controllers\V2\UpdateController;
use Illuminate\Support\Facades\Route;

Route::prefix('v2/')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/image', [ImageController::class, 'show']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/send-feedback', [EmailController::class, 'send']);
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');

        Route::prefix('auxiliary')->name('auxiliary.')->controller(AuxiliaryDataController::class)->group(function () {
            Route::get('/report-fields', 'getReportFields')->name('report-fields');
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

            Route::group(['middleware' => ['permission:branch_report_create']], function () {
                Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
            });

            Route::group(['middleware' => ['permission:branch_update_create']], function () {
                Route::post('/reports/update/{id}', [UpdateController::class, 'store'])->name('updates.store');
            });


            // Statistics Routes
            Route::prefix('statistics')->name('statistics.')->controller(StatisticsController::class)->group(function () {
                Route::get('/count-table/', 'getCountTable')->name('count-table');
                Route::get('/card-statistics', 'getCardStatistics')->name('card-statistics');
                Route::get('/emergency-types-distribution', 'getEmergencyTypesDistribution')->name('emergency-types-distribution');
                Route::get('/emergencies-per-branch-office', 'getEmergenciesPerBranchOffice')->name('emergencies-per-branch-office');
                Route::get('/injury-counts-per-emergency-type', 'getInjuryCountsPerEmergencyType')->name('injury-counts-per-emergency-type');
                Route::get('/evacuated-people-medical-aid', 'getEvacuatedPeopleMedicalAid')->name('evacuated-people-medical-aid');
                Route::get('/response-time-per-emergency-type', 'getResponseTimePerEmergencyType')->name('response-time-per-emergency-type');
                Route::get('/worker-man-hours-per-emergency', 'getWorkerManHoursPerEmergency')->name('worker-man-hours-per-emergency');
                Route::get('/total-injuries-impact-over-time', 'getTotalInjuriesImpactOverTime')->name('total-injuries-impact-over-time');
                Route::get('/transport-usage', 'getTransportUsage')->name('transport-usage');
            });

        });

    });

    Route::post('/login', [AuthController::class, 'login']);
});
