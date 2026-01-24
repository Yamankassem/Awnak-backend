<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\UserController;


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission:users.read');

    Route::post('users', [UserController::class, 'store'])
        ->middleware('permission:users.create');

    Route::put('users/{id}', [UserController::class, 'update'])
        ->middleware('permission:users.update');

    Route::delete('users/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete');

    Route::post('users/{id}/roles', [UserController::class, 'assignRoles'])
    ->middleware(['permission:roles.update']);
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    Route::get('roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.read');

    Route::post('roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.create');

    Route::put('roles/{id}', [RoleController::class, 'update'])
        ->middleware('permission:roles.update');

    Route::delete('roles/{id}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.delete');
});