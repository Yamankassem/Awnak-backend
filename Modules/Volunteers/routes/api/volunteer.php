<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\VolunteerProfileController;

Route::prefix('v1/volunteer')->group(function () {

    Route::get('profile', [VolunteerProfileController::class, 'show']);
    Route::get('tasks/history', [VolunteerProfileController::class, 'index']);

    Route::middleware('volunteer.active')->group(function () {
        Route::put('profile', [VolunteerProfileController::class, 'update']);
    });

});