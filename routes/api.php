<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuxiliaryDataController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Report\UpdateController;
use App\Http\Controllers\Report\UpdateLogController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Survey\SurveyController;
use App\Http\Controllers\Survey\ViolationController;
use App\Http\Controllers\Vehicle\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:global')->prefix('v2/')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user'])->name('profile.show');
        Route::get('/image', [ImageController::class, 'show'])->name('image.show');
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/send-feedback', [EmailController::class, 'send'])->name('send-feedback');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar'])->name('profile.upload-avatar');

        Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
            Route::resource('custom-fields', CustomFieldController::class)->only(['store']);
            Route::post('register', [AuthController::class, 'register'])->name('register');
        });

        Route::prefix('auxiliary')->name('auxiliary.')->controller(AuxiliaryDataController::class)
            ->group(function () {
                Route::get('/report-fields', 'getReportFields')->name('report-fields');
                Route::get('/update-fields', 'getUpdateFields')->name('update-fields');
                Route::get('/survey-fields', 'getSurveyFields')->name('survey-fields');
                Route::get('/violation-fields', 'getViolationFields')->name('violation-fields');
                Route::get('/report-filters', 'getReportFilters')->name('report-filters');
                Route::get('/survey-filters', 'getSurveyFilters')->name('survey-filters');
                Route::get('/mineral-fields', 'getMineralFields')->name('mineral-fields');
                Route::get('/oil-fields', 'getOilFields')->name('oil-fields');
                Route::get('/subsoil-contracts', 'getSubsoilContracts')->name('subsoil-contracts');
                Route::get('/field-options/{field_name}', 'getFieldOptions')->name('field-options');
            });

        Route::prefix('statistics')->name('statistics.')->controller(StatisticsController::class)
            ->group(function () {
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

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
            Route::get('/{id}', [ReportController::class, 'show'])->name('show');
            Route::get('/{id}/log', [UpdateLogController::class, 'show'])->name('id.log');

            Route::post('/', [ReportController::class, 'store'])->name('store');
            Route::post('/update/{id}', [UpdateController::class, 'store'])->name('updates.store');
        });

        Route::prefix('surveys')->name('surveys.')->group(function () {
            Route::get('/', [SurveyController::class, 'index'])->name('index');
            Route::get('/export', [SurveyController::class, 'export'])->name('export');
            Route::get('/{id}', [SurveyController::class, 'show'])->name('show');
            Route::get('/{id}/print', [SurveyController::class, 'print'])->name('print');

            Route::post('/', [SurveyController::class, 'store'])->name('store');

            Route::name('violations.')->group(function () {
                Route::get('/violations/{id}', [ViolationController::class, 'show'])->name('show');
                Route::get('/{id}/violations', [ViolationController::class, 'index'])->name('index');

                Route::post('/{id}/create-violation', [ViolationController::class, 'store'])->name('store');
                Route::post('/close-violation/{id}', [ViolationController::class, 'close'])->name('close');
            });
        });

        Route::resource('vehicles', VehicleController::class)->only('index', 'show');
    });
});

