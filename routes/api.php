<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\AuxiliaryDataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // the middleware bit ensures the authentication of user before being able to use this route


Route::get('/reports', [OperationController::class, 'reports']);
Route::get('/reports/{id}', [OperationController::class, 'showReport']);
Route::post('/reports', [OperationController::class, 'storeReport']);

Route::get('/auxiliary-data', [AuxiliaryDataController::class, 'getAuxiliaryData']);

