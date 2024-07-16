<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [\App\Http\Controllers\Apis\Auth\AuthController::class, 'login'])->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', [\App\Http\Controllers\Apis\Auth\AuthController::class, 'logout'])->name('logout');
        Route::get('/user', [\App\Http\Controllers\Apis\Auth\AuthController::class, 'user'])->name('user');

        Route::resource('transactions', \App\Http\Controllers\Apis\Transactions::class);
    });
});

