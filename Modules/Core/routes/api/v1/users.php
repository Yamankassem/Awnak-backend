<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Users API Routes
|--------------------------------------------------------------------------
| Prefix: /api/v1/users
| Middleware: auth:sanctum
*/

Route::middleware(['auth:sanctum'])->group(function () {

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
