<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\VolunteerProfileController;

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer')
    ->group(function () {

        Route::get('profile', [VolunteerProfileController::class, 'show']);
        Route::put('profile', [VolunteerProfileController::class, 'update']);
});
