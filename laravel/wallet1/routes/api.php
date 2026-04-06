<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AdminWalletController;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

   
    Route::get('/wallet', [WalletController::class, 'show']);
    Route::post('/wallet/spend', [WalletController::class, 'spend']);

    
    Route::middleware('check.role:admin')->group(function () {
        Route::post('/admin/wallet/{user}/credit', [AdminWalletController::class, 'credit']);
        Route::post('/admin/wallet/{user}/debit', [AdminWalletController::class, 'debit']);
    });
});