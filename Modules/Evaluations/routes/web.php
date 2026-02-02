<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluations\Http\Controllers\EvaluationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('evaluations', EvaluationController::class)->names('evaluations');
});
