<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\catalogue\DegreeController;
use App\Http\Controllers\catalogue\PeriodsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

