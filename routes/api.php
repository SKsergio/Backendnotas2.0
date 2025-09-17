<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\catalogue\DegreeController;
use App\Http\Controllers\catalogue\PeriodsController;

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
});



>>>>>>> desarrollo
