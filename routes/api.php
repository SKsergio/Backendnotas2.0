<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\catalogue\DegreeController;
use App\Http\Controllers\catalogue\EvaluationTypesController;
use App\Http\Controllers\catalogue\PeriodsController;
use App\Http\Controllers\catalogue\SubjectsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*NOTA, ACA SE VA A TRABAJAR PARA QUE SE MANEJE CON AUTENTICACION DE RUTAS Y EN GRUPOS, DE MOMENTO 
LO VAMOS IR DEJANDO ASI, PERO EN CUANTO SE PUEDA HAREMOS EL CAMBIO PARA MENAJRSEE POR GRUPO*/

Route::prefix('catalog')->group(function() {

    //degrees routes
    Route::prefix('degrees')->group(function () {
        Route::get('/', [DegreeController::class, 'index']);
        Route::post('/', [DegreeController::class, 'store']);
        Route::patch('/{id}', [DegreeController::class, 'partialUpdate']);
        Route::get('/{id}', [DegreeController::class, 'show']);
        Route::delete('/{id}', [DegreeController::class, 'destroy']);
        Route::post('/{id}/restore', [DegreeController::class, 'restore']);
    });

        Route::prefix('periods')->group(function () {
        Route::get('/', [PeriodsController::class, 'index']);
        Route::post('/', [PeriodsController::class, 'create']);
        Route::patch('/{id}', [PeriodsController::class, 'edit']);
        Route::get('/{id}', [PeriodsController::class, 'show']);
        Route::delete('/{id}', [PeriodsController::class, 'destroy']);
        Route::post('/{id}/restore', [PeriodsController::class, 'restore']);
    });
        Route::prefix('evaluationType')->group(function () {
        Route::get('/', [EvaluationTypesController::class, 'index']);
        Route::post('/', [EvaluationTypesController::class, 'create']);
        Route::patch('/{id}', [EvaluationTypesController::class, 'edit']);
        Route::get('/{id}', [EvaluationTypesController::class, 'show']);
        Route::delete('/{id}', [EvaluationTypesController::class, 'destroy']);
        Route::post('/{id}/restore', [EvaluationTypesController::class, 'restore']);
    });
        Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectsController::class, 'index']);
        Route::post('/', [SubjectsController::class, 'create']);
        Route::patch('/{id}', [SubjectsController::class, 'edit']);
        Route::get('/{id}', [SubjectsController::class, 'show']);
        Route::delete('/{id}', [SubjectsController::class, 'destroy']);
        Route::post('/{id}/restore', [SubjectsController::class, 'restore']);
    });
});