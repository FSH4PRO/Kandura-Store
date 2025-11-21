<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Address\AddressController;




Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        
    });
});



Route::middleware('auth:customer')->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/{user}', [UserController::class, 'update']);
});


Route::middleware('auth:customer')->group(function () {
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
});
