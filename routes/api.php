<?php

use App\Auth\AuthController;
use App\Auth\UserPermissionController;
use App\CompanyList\CompanyController;
use App\CustomField\Http\CustomFieldController;
use App\Egov\AuxiliaryDataController;
use App\FieldOption\FieldOptionController;
use App\Filter\FilterController;
use App\Image\ImageController;
use App\Report\Http\ReportController;
use App\Report\Update\Http\UpdateController;
use App\Statistics\StatisticsController;
use App\Statistics\VehicleStatisticsController;
use App\Survey\Http\SurveyController;
use App\Survey\Violation\Http\ViolationController;
use App\UserFeedback\EmailController;
use App\Vehicle\Http\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:global')->prefix('v2/')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user'])->name('profile.show');
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/send-feedback', [EmailController::class, 'send'])->name('send-feedback');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar'])->name('profile.upload-avatar');

        Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
            Route::resource('custom-fields', CustomFieldController::class)->only(['store']);
            Route::post('register', [AuthController::class, 'register'])->name('register');
            Route::put('sync-permissions/{user}', [UserPermissionController::class, 'sync'])->name('sync-permissions');
        });

        Route::prefix('auxiliary')->name('auxiliary.')->controller(AuxiliaryDataController::class)
            ->group(function () {
                Route::get('/mineral-fields', 'getMineralFields')->name('mineral-fields');
                Route::get('/oil-fields', 'getOilFields')->name('oil-fields');
                Route::get('/subsoil-contracts', 'getSubsoilContracts')->name('subsoil-contracts');
            });

        Route::prefix('auxiliary')->name('auxiliary.')->controller(FilterController::class)
            ->group(function () {
                Route::get('/report-filters', 'getReportFilters')->name('report-filters');
                Route::get('/survey-filters', 'getSurveyFilters')->name('survey-filters');
            });

        Route::prefix('auxiliary')->name('auxiliary.')->controller(FieldOptionController::class)
            ->group(function () {
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


        Route::resource('reports', ReportController::class)->only('create');
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
            Route::get('/{id}', [ReportController::class, 'show'])->name('show');
            Route::post('/', [ReportController::class, 'store'])->name('store');


            Route::resource('updates', UpdateController::class)->only('create');
            Route::get('/{id}/log', [UpdateController::class, 'index'])->name('id.log');
            Route::post('/update/{report}', [UpdateController::class, 'store'])->name('updates.store');
        });

        Route::resource('surveys', SurveyController::class)->only('create');
        Route::prefix('surveys')->name('surveys.')->group(function () {
            Route::get('/', [SurveyController::class, 'index'])->name('index');
            Route::get('/export', [SurveyController::class, 'export'])->name('export');
            Route::get('/{id}', [SurveyController::class, 'show'])->name('show');
            Route::get('/{id}/print', [SurveyController::class, 'print'])->name('print');

            Route::post('/', [SurveyController::class, 'store'])->name('store');

            Route::resource('violations', ViolationController::class)->only('create');
            Route::name('violations.')->group(function () {
                Route::get('/violations/{id}', [ViolationController::class, 'show_v2'])->name('show');
                Route::get('/{id}/violations', [ViolationController::class, 'index'])->name('index');

                Route::post('/{id}/create-violation', [ViolationController::class, 'store'])->name('store');
                Route::post('/close-violation/{id}', [ViolationController::class, 'close_v2'])->name('close');
            });
        });

        Route::prefix('vehicles')->name('vehicles.')->group(function () {
            Route::get('filter', [VehicleController::class, 'filter'])->name('filter');
            Route::get('export', [VehicleController::class, 'export'])->name('export');
            Route::get('statistics/{graphName}', [VehicleStatisticsController::class, 'show'])->name('statistics');
        });
        Route::resource('vehicles', VehicleController::class)->only('index', 'show', 'create', 'store', 'edit', 'update', 'destroy');

        Route::post('/images/{directory}/{id}', [ImageController::class, 'store'])->name('images.store');
        Route::resource('images', ImageController::class)->only('destroy');

    });
});

Route::middleware(['throttle:global', 'auth:sanctum'])->prefix('v3/')->name('v3')->group(function () {

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('export', [ReportController::class, 'export'])->name('export');
    });
    Route::resource('reports', ReportController::class)->only(['index', 'create', 'store', 'show']);

    Route::resource('reports.updates', UpdateController::class)->only(['index', 'create', 'store']);


    Route::prefix('surveys')->name('surveys.')->group(function () {
        Route::get('export', [SurveyController::class, 'export'])->name('export');
        Route::get('/{survey}/print', [SurveyController::class, 'print'])->name('print');
    });
    Route::resource('surveys', SurveyController::class)->only(['index', 'create', 'store', 'show']);

    Route::name('surveys.violations.')->group(function () {
        Route::patch('surveys/{survey}/violations/{violation}/close', [ViolationController::class, 'close'])->name('close');
    });
    Route::resource('surveys.violations', ViolationController::class)->only(['index', 'create', 'store', 'show']);
});

