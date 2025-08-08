<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::prefix('app') -> group(function () {



    Route::prefix('auth') -> group(function () {

        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);


    });

    Route::middleware('auth:sanctum') -> group(function () {

        Route::prefix('task') -> group(function () {

            Route::get('/index', [TaskController::class, 'index']);
            Route::post('/store', [TaskController::class, 'store']);
            Route::put('/update', [TaskController::class, 'update']);
            Route::delete('/delete/{id}', [TaskController::class, 'delete']);


        });

    });

});
