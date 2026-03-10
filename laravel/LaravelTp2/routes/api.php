<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::patch('loans/{id}/return', [LoanController::class, 'returnBook']);
Route::apiResource('loans', LoanController::class);
