<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group( function() {
    Route::controller(UserController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getUsers');
            Route::get('/{id}', 'getUserById');
            Route::get('/logged-user', 'getLoggedUser');

            //post
            Route::post('/', 'create');
            Route::post('/logout', 'logout');

            //put
            Route::put('/{id}', 'update');

            //patch
            Route::patch('/{id}/enable', 'enable');
            Route::patch('/{id}/disable', 'disable');

            //delete
            Route::delete('/{id}/delete', 'delete');
        });

        Route::middleware('api')->group(function () {
            //post
            Route::post('/login', 'login');
            Route::post('/forgot-password', 'forgotPassword');
            Route::post('/reset-password', 'resetPassword');
        });
    });
});

Route::prefix('medias')->group( function() {
    Route::controller(MediaController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //post
            Route::post('/', 'create');
            Route::post('/{id}', 'update');
            Route::post('/bulk', 'bulkCreate');

            //delete
            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('medias')->group( function() {
    Route::controller(MediaController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //post
            Route::post('/', 'create');
            Route::post('/{id}', 'update');

            //delete
            Route::delete('/{id}', 'delete');
        });
    });
});
