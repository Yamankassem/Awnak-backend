<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Roles API Routes
|--------------------------------------------------------------------------
| Prefix: /api/v1/roles
| Middleware: auth:sanctum
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.read');

    Route::post('roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.create');

    Route::put('roles/{id}', [RoleController::class, 'update'])
        ->middleware('permission:roles.update');

    Route::delete('roles/{id}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.delete');
});
