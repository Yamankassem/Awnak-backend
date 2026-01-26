<?php

use Illuminate\Support\Facades\Route;
use Modules\Evaluations\Http\Controllers\BadgeController;
use Modules\Evaluations\Http\Controllers\EvaluationController;
use Modules\Evaluations\Http\Controllers\VolunteerBadgeController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('evaluations', EvaluationController::class)->names('evaluations');
    Route::apiResource('badges', BadgeController::class)->names('badges');
    Route::apiResource('volunteerBadges', VolunteerBadgeController::class)->names('volunteerBadges');
    Route::apiResource('certificates', CertificateController::class)->names('certificates');
});




