<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluations\Http\Controllers\EvaluationsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('evaluations', EvaluationsController::class)->names('evaluations');
});
