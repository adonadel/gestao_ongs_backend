<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinancesController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NgrController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group( function() {
    Route::controller(AuthController::class)->group(function () {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/me', 'me');

            //post
            Route::post('/logout', 'logout');
            Route::post('/refresh', 'refreshToken');
        });
        Route::middleware('api')->group(function () {
            Route::post('/login', 'login');
        });
    });
});

Route::prefix('users')->group( function() {
    Route::controller(UserController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getUsers');
            Route::get('/{id}', 'getUserById');
            Route::get('/logged-user', 'getLoggedUser');

            //post
            Route::post('/', 'create');

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

Route::prefix('animals')->group( function() {
    Route::controller(AnimalController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getAnimals');
            Route::get('/{id}', 'getAnimalById');

            //post
            Route::post('/', 'create');
            Route::post('/{id}', 'update');

            //delete
            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('events')->group( function() {
    Route::controller(EventController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getEvents');
            Route::get('/{id}', 'getEventById');

            //post
            Route::post('/', 'create');
            Route::post('/{id}', 'update');

            //delete
            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('ngrs')->group( function() {
    Route::controller(NgrController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/{id}', 'getNgrById');

            //post
            Route::post('/{id}', 'update');
        });
    });
});

Route::prefix('adoptions')->group( function() {
    Route::controller(AdoptionController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getAdoptions');
            Route::get('/{id}', 'getAdoptionById');

            //post
            Route::post('/', 'create');
            Route::post('/{id}', 'update');

            //delete
            Route::delete('/{id}', 'delete');

            //put
            Route::put('/{id}/confirm', 'confirmAdotpion');
            Route::put('/{id}/cancel', 'cancelAdotpion');
            Route::put('/{id}/deny', 'denyAdotpion');
        });
    });
});

Route::prefix('finances')->group( function() {
    Route::controller(FinancesController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getFinances');
            Route::get('/{id}', 'getFinanceById');

            //post
            Route::post('/', 'create');
            Route::post('/{id}', 'update');

            //delete
            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('roles')->group( function() {
    Route::controller(RoleController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getRoles');
            Route::get('/{id}', 'getRoleById');

            //post
            Route::post('/', 'create');
            Route::put('/{id}', 'update');

            //delete
            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('permissions')->group( function() {
    Route::controller(PermissionsController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            //get
            Route::get('/', 'getPermissions');
        });
    });
});
