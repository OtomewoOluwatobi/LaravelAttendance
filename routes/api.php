<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['middleware' => 'api',  'prefix' => 'auth'], function ($router) {
    Route::get('/', [UserController::class, 'create']);
});

Route::group(['middleware' => 'api',  'prefix' => 'admin'], function ($router) {

    Route::prefix('access_control')->group(function () {
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/store', [RoleController::class, 'store']);
            Route::get('/{id}/show', [RoleController::class, 'show']);
            Route::match(['post', 'put'],'/{id}/update', [RoleController::class, 'update']);
            Route::delete('/{id}/delete', [RoleController::class, 'destroy']);
            Route::post('/{id}/addPermissions', [RolePermissionController::class, 'addPermissions']);
        });

        Route::prefix('permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'index']);
            Route::post('/store', [PermissionController::class, 'store']);
            Route::get('/{id}/show', [PermissionController::class, 'show']);
            Route::match(['post', 'put'],'/{id}/update', [PermissionController::class, 'update']);
            Route::delete('/{id}/delete', [PermissionController::class, 'destroy']);
        });

//        Route::get('/', [RoleController::class, 'index']);
    });
});



