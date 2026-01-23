<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluations\Http\Controllers\EvaluationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('evaluations', EvaluationController::class)->names('evaluations');
});




