<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\catalogues\DegreeController;
use App\Http\Controllers\catalogues\PeriodsController;
use App\Http\Controllers\catalogues\SectionController;

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
    //periods routees
    Route::prefix('periods')->group(function () {
        Route::get('/', [PeriodsController::class, 'index']);
        Route::post('/periods', [PeriodsController::class, 'create']);
        Route::patch('/{id}', [PeriodsController::class, 'partialUpdate']);
        Route::get('/{id}', [PeriodsController::class, 'show']);
        Route::delete('/{id}', [PeriodsController::class, 'destroy']);
        Route::post('/{id}/restore', [PeriodsController::class, 'restore']);
    });
    //sections routes
    Route::prefix('sections')->group(function () {
        Route::get('/', [SectionController::class, 'index']);
        Route::post('/', [SectionController::class, 'store']);
        Route::patch('/{id}', [SectionController::class, 'partialUpdate']);
        Route::get('/{id}', [SectionController::class, 'show']);
        Route::delete('/{id}', [SectionController::class, 'destroy']);
        Route::post('/{id}/restore', [SectionController::class, 'restore']);
    });
});