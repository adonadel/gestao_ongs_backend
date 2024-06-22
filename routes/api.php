<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NgrController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group( function() {
    Route::controller(AuthController::class)->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('/me', 'me');

            Route::post('/logout', 'logout');
        });

        Route::middleware('api')->group(function () {
            Route::post('/login', 'login');
            Route::post('/refresh', 'refreshToken');
        });
    });
});

Route::prefix('users')->group( function() {
    Route::controller(UserController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', 'getUsers');
            Route::get('/{id}', 'getUserById');
            Route::get('/logged-user', 'getLoggedUser');

            Route::post('/', 'create');

            Route::put('/{id}', 'update');

            Route::patch('/{id}/enable', 'enable');
            Route::patch('/{id}/disable', 'disable');

            Route::delete('/{id}/delete', 'delete');
        });

        Route::middleware('api')->group(function () {
            Route::post('/forgot-password', 'forgotPassword');
            Route::post('/reset-password', 'resetPassword');
            Route::post('/external', 'createExternal');
            Route::get('/external/{id}', 'getUserByIdExternal');
        });
    });
});

Route::prefix('medias')->group( function() {
    Route::controller(MediaController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::post('/', 'create');
            Route::post('/bulk', 'bulkCreate');

            Route::post('/{id}', 'update');

            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('animals')->group( function() {
    Route::controller(AnimalController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', 'getAnimals');
            Route::get('/{id}', 'getAnimalById');

            Route::post('/', 'create');
            Route::post('/with-medias', 'createWithMedias');

            Route::put('/{id}', 'update');

            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('events')->group( function() {
    Route::controller(EventController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::post('/', 'create');
            Route::post('/with-medias', 'createWithMedias');

            Route::put('/{id}', 'update');

            Route::delete('/{id}', 'delete');
        });

        Route::middleware('api')->group(function () {
            Route::get('/', 'getEvents');
            Route::get('/{id}', 'getEventById');
        });
    });
});

Route::prefix('ngrs')->group( function() {
    Route::controller(NgrController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::get('/{id}', 'getNgrById');

            Route::put('/{id}', 'update');
        });
    });
});

Route::prefix('adoptions')->group( function() {
    Route::controller(AdoptionController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', 'getAdoptions');
            Route::get('/{id}', 'getAdoptionById');

            Route::post('/', 'create');
            Route::put('/{id}', 'update');

            Route::delete('/{id}', 'delete');

            Route::put('/{id}/confirm', 'confirmAdoption');
            Route::put('/{id}/cancel', 'cancelAdoption');
            Route::put('/{id}/deny', 'denyAdoption');
        });
    });
});

Route::prefix('finances')->group( function() {
    Route::controller(FinanceController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', 'getFinances');
            Route::get('/{id}', 'getFinanceById');
            Route::put('/{id}', 'update');

            Route::delete('/{id}', 'delete');
        });

        Route::middleware('api')->group(function () {
            Route::post('/', 'create');
            Route::put('/{id}/success', 'success');
            Route::put('/{id}/cancel', 'cancel');
        });
    });
});

Route::prefix('roles')->group( function() {
    Route::controller(RoleController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', 'getRoles');
            Route::get('/{id}', 'getRoleById');

            Route::post('/', 'create');
            Route::put('/{id}', 'update');

            Route::delete('/{id}', 'delete');
        });
    });
});

Route::prefix('permissions')->group( function() {
    Route::controller(PermissionController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', 'getPermissions');
        });
    });
});

Route::prefix('dashboards')->group( function() {
    Route::controller(DashboardController::class)->group( function() {
        Route::middleware('auth:api')->group(function () {
        });

        Route::middleware('api')->group(function () {
            Route::get('/animals', 'getAnimalsTotal');
            Route::get('/animals/castration', 'getAnimalsTotalCastration');
            Route::get('/finances', 'getFinancesTotal');
        });
    });
});
