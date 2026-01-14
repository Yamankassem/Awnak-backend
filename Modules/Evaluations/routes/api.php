<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluations\Http\Controllers\EvaluationsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('evaluations', EvaluationsController::class)->names('evaluations');
});
