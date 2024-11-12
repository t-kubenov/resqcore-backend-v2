<?php

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
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/image', function (Request $request) {
        $filePath = public_path('storage/' . $request->query('path'));

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->file($filePath);
    });

    Route::post('/send-feedback', [EmailController::class, 'send']);

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

        Route::get('/statistics/emergency-types-distribution', [StatisticsController::class, 'getEmergencyTypesDistribution'])
            ->name('statistics.emergency-types-distribution');
        Route::get('/statistics/emergencies-per-branch-office', [StatisticsController::class, 'getEmergenciesPerBranchOffice'])
            ->name('statistics.emergencies-per-branch-office');
        Route::get('/statistics/injury-counts-per-emergency-type', [StatisticsController::class, 'getInjuryCountsPerEmergencyType'])
            ->name('statistics.injury-counts-per-emergency-type');
        // Evacuated People & Medical Aid
        Route::get('/statistics/evacuated-people-medical-aid', [StatisticsController::class, 'getEvacuatedPeopleMedicalAid'])
            ->name('statistics.evacuated-people-medical-aid');
        //Response Time per Emergency Type
        Route::get('/statistics/response-time-per-emergency-type', [StatisticsController::class, 'getResponseTimePerEmergencyType'])
            ->name('statistics.response-time-per-emergency-type');
        // Worker Involvement per Emergency
        Route::get('/statistics/worker-man-hours-per-emergency', [StatisticsController::class, 'getWorkerManHoursPerEmergency'])
            ->name('statistics.worker-man-hours-per-emergency');
        // Total Injuries and Impact Over Time
        Route::get('/statistics/total-injuries-impact-over-time', [StatisticsController::class, 'getTotalInjuriesImpactOverTime'])
            ->name('statistics.total-injuries-impact-over-time');
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
