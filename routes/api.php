<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\AuxiliaryDataController;
use App\Http\Controllers\ReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // the middleware bit ensures the authentication of user before being able to use this route


Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::put('/reports/{id}', [OperationController::class, 'updateReport']);
Route::delete('/reports/{id}', [OperationController::class, 'deleteReport']);


Route::get('/departures', [OperationController::class, 'departures']);

Route::get('/auxiliary-data', [AuxiliaryDataController::class, 'getAuxiliaryData']);




