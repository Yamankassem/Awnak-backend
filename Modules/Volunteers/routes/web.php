<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\VolunteerProfileController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('volunteers', VolunteerProfileController::class)->names('volunteers');
});
