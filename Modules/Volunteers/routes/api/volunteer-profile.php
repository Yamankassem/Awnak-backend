<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\VolunteerProfileDocumentController;

Route::prefix('v1/volunteer/profile')->group(function () {

    Route::get('documents', [VolunteerProfileDocumentController::class, 'index']);

    Route::middleware('volunteer.active')->group(function () {
        Route::post('documents', [VolunteerProfileDocumentController::class, 'store']);
        Route::delete('documents/{media}', [VolunteerProfileDocumentController::class, 'destroy']);
    });

});
