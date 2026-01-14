<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\VolunteersController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('volunteers', VolunteersController::class)->names('volunteers');
});
