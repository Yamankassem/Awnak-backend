<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\VolunteerVerificationController;
use Modules\Volunteers\Http\Controllers\VolunteerProfileController;

Route::prefix('v1/volunteers')->group(function () {

    Route::post('{volunteerProfile}/verify', [VolunteerVerificationController::class, 'verify']);
    Route::post('{volunteerProfile}/reject', [VolunteerVerificationController::class, 'reject']);
    Route::get('pending', [VolunteerVerificationController::class, 'pending']);

    Route::post('{volunteerProfile}/activate', [VolunteerProfileController::class, 'activate']);
    Route::post('{volunteerProfile}/suspend', [VolunteerProfileController::class, 'suspend']);
});
