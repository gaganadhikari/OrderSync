<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/order',[\App\Http\Controllers\API\OrderController::class,'index']);
Route::get('/order/sync',[\App\Http\Controllers\API\OrderController::class,'syncOrder']);
