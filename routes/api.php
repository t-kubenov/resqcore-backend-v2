<?php

use App\Http\Controllers\V2\AuthController;
use App\Http\Controllers\V2\AuxiliaryDataController;
use App\Http\Controllers\V2\CompanyController;
use App\Http\Controllers\V2\CustomFieldController;
use App\Http\Controllers\V2\EmailController;
use App\Http\Controllers\V2\ImageController;
use App\Http\Controllers\V2\Report\ReportController;
use App\Http\Controllers\V2\Report\UpdateController;
use App\Http\Controllers\V2\Report\UpdateLogController;
use App\Http\Controllers\V2\StatisticsController;
use App\Http\Controllers\V2\Survey\SurveyController;
use App\Http\Controllers\V2\Survey\ViolationController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:global')->prefix('v2/')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', [AuthController::class, 'user'])->name('profile.show');
        Route::get('/image', [ImageController::class, 'show'])->name('image.show');
        Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar'])->name('profile.upload-avatar');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/send-feedback', [EmailController::class, 'send'])
            ->name('send-feedback');
        Route::post('/change-password', [AuthController::class, 'changePassword'])
            ->name('profile.change-password');

        Route::prefix('auxiliary')->name('auxiliary.')->controller(AuxiliaryDataController::class)->group(function () {
            Route::get('/report-fields', 'getReportFields')->name('report-fields');
            Route::get('/update-fields', 'getUpdateFields')->name('update-fields');
            Route::get('/survey-fields', 'getSurveyFields')->name('survey-fields');
            Route::get('/violation-fields', 'getViolationFields')->name('violation-fields');
            Route::get('/report-filters', 'getReportFilters')->name('report-filters');
            Route::get('/survey-filters', 'getSurveyFilters')->name('survey-filters');
            Route::get('/mineral-fields', 'getMineralFields')->name('mineral-fields'); // todo: bruh i gotta unite these like the field options
            Route::get('/oil-fields', 'getOilFields')->name('oil-fields');
            Route::get('/subsoil-contracts', 'getSubsoilContracts')->name('subsoil-contracts');
            Route::get('/field-options/{field_name}', 'getFieldOptions')->name('field-options');
        });

        Route::prefix('admin')
            ->name('admin.')
            ->middleware('role:admin')
            ->group(function (){
                Route::resource('custom-fields', CustomFieldController::class)->only(['store']);
            });

        Route::group(['middleware' => ['permission:user_register']], function () {
            Route::post('/register', [AuthController::class, 'register'])->name('register');
        });

        Route::group(['middleware' => ['permission:branch_report_view']], function () {

            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
            Route::get('/reports/{id}/log', [UpdateLogController::class, 'show'])->name('reports.id.log');
            Route::get('/export', [ReportController::class, 'export'])->name('reports.export');

            Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
            Route::get('/surveys/export', [SurveyController::class, 'export'])->name('surveys.export');
            Route::get('/surveys/{id}', [SurveyController::class, 'show'])->name('surveys.show');
            Route::get('/surveys/violations/{id}', [ViolationController::class, 'show'])->name('surveys.violations.show');
            Route::get('/surveys/{id}/violations', [ViolationController::class, 'index'])->name('surveys.violations.index');
            Route::get('/surveys/{id}/print', [SurveyController::class, 'print'])->name('surveys.print');

            Route::group(['middleware' => ['permission:branch_report_create']], function () {
                Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
                Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
                Route::post('/surveys/{id}/create-violation', [ViolationController::class, 'store'])->name('surveys.violations.store');
                Route::post('/surveys/close-violation/{id}', [ViolationController::class, 'close'])->name('surveys.violations.close');
            });

            Route::group(['middleware' => ['permission:branch_update_create']], function () {
                Route::post('/reports/update/{id}', [UpdateController::class, 'store'])->name('updates.store');
            });


            // Statistics Routes
            Route::prefix('statistics')
                ->name('statistics.')
                ->controller(StatisticsController::class)
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

        });

    });

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth')->name('login');
});
