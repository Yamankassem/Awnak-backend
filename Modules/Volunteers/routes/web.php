<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\VolunteersController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('volunteers', VolunteersController::class)->names('volunteers');
});
