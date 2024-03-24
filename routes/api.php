<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

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
