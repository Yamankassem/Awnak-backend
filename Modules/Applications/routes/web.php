<?php

use Illuminate\Support\Facades\Route;
use Modules\Applications\Http\Controllers\ApplicationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('applications', ApplicationController::class)->names('applications');
});
