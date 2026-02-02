<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\{
    VolunteerSkillController,
    VolunteerInterestController,
    VolunteerLanguageController,
    VolunteerAvailabilityController
};

Route::prefix('v1/volunteer')->group(function () {

    Route::get('skills', [VolunteerSkillController::class, 'index']);
    Route::get('interests', [VolunteerInterestController::class, 'index']);
    Route::get('languages', [VolunteerLanguageController::class, 'index']);
    Route::get('availability', [VolunteerAvailabilityController::class, 'index']);

    Route::middleware('volunteer.active')->group(function () {

        Route::post('skills', [VolunteerSkillController::class, 'store']);
        Route::put('skills/{volunteerSkill}', [VolunteerSkillController::class, 'update']);
        Route::delete('skills/{volunteerSkill}', [VolunteerSkillController::class, 'destroy']);

        Route::post('interests', [VolunteerInterestController::class, 'store']);
        Route::put('interests/{volunteerInterest}', [VolunteerInterestController::class, 'update']);
        Route::delete('interests/{volunteerInterest}', [VolunteerInterestController::class, 'destroy']);

        Route::post('languages', [VolunteerLanguageController::class, 'store']);
        Route::put('languages/{volunteerLanguage}', [VolunteerLanguageController::class, 'update']);
        Route::delete('languages/{volunteerLanguage}', [VolunteerLanguageController::class, 'destroy']);

        Route::post('availability', [VolunteerAvailabilityController::class, 'store']);
        Route::put('availability/{availability}', [VolunteerAvailabilityController::class, 'update']);
        Route::delete('availability/{availability}', [VolunteerAvailabilityController::class, 'destroy']);

    });

});
