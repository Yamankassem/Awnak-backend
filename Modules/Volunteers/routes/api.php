<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\SkillController;
use Modules\Volunteers\Http\Controllers\InterestController;
use Modules\Volunteers\Http\Controllers\LanguageController;
use Modules\Volunteers\Http\Controllers\VolunteerSkillController;
use Modules\Volunteers\Http\Controllers\VolunteerProfileController;
use Modules\Volunteers\Http\Controllers\VolunteerInterestController;
use Modules\Volunteers\Http\Controllers\VolunteerLanguageController;
use Modules\Volunteers\Http\Controllers\VolunteerAvailabilityController;
use Modules\Volunteers\Http\Controllers\VolunteerVerificationController;
use Modules\Volunteers\Http\Controllers\VolunteerProfileDocumentController;

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer')
    ->group(function () {

        Route::get('profile', [VolunteerProfileController::class, 'show']);
        Route::get('tasks/history', [VolunteerProfileController::class, 'index']);
        Route::put('profile', [VolunteerProfileController::class, 'update'])->middleware('volunteer.active');;
        Route::post('{volunteerProfile}/activate', [VolunteerProfileController::class, 'activate']); //just admin and orginazition_admin
        Route::post('{volunteerProfile}/suspend', [VolunteerProfileController::class, 'suspend']); //just admin and orginazition_admin
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/skills') //just for admin
    ->group(function () {
        Route::get('/', [SkillController::class, 'index'])->middleware('permission:skills.read');
        Route::post('/', [SkillController::class, 'store'])->middleware('permission:skills.create');
        Route::put('{skill}', [SkillController::class, 'update'])->middleware('permission:skills.update');
        Route::delete('{skill}', [SkillController::class, 'destroy'])->middleware('permission:skills.delete');
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/interests') //just for admin
    ->group(function () {

        Route::get('/', [InterestController::class, 'index'])
            ->middleware('permission:interests.read');

        Route::post('/', [InterestController::class, 'store'])
            ->middleware('permission:interests.create');

        Route::put('{interest}', [InterestController::class, 'update'])
            ->middleware('permission:interests.update');

        Route::delete('{interest}', [InterestController::class, 'destroy'])
            ->middleware('permission:interests.delete');
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/languages')
    ->group(function () {

        Route::get('/', [LanguageController::class, 'index'])
            ->middleware('permission:languages.read');

        Route::post('/', [LanguageController::class, 'store'])
            ->middleware('permission:languages.create');

        Route::put('{language}', [LanguageController::class, 'update'])
            ->middleware('permission:languages.update');

        Route::delete('{language}', [LanguageController::class, 'destroy'])
            ->middleware('permission:languages.delete');
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer/availability') //for volunteers
    ->group(function () {

        Route::get('/', [VolunteerAvailabilityController::class, 'index']);
        Route::middleware('volunteer.active')->group(function () {
            Route::post('/', [VolunteerAvailabilityController::class, 'store']);
            Route::put('{availability}', [VolunteerAvailabilityController::class, 'update']);
            Route::delete('{availability}', [VolunteerAvailabilityController::class, 'destroy']);
        });
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer/skills') //for volunteers
    ->group(function () {
        Route::get('/', [VolunteerSkillController::class, 'index']);
        Route::middleware('volunteer.active')->group(function () {
            Route::post('/', [VolunteerSkillController::class, 'store']);
            Route::put('{volunteerSkill}', [VolunteerSkillController::class, 'update']);
            Route::delete('{volunteerSkill}', [VolunteerSkillController::class, 'destroy']);
        });
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer/interests') //for volunteers
    ->group(function () {
        Route::get('/', [VolunteerInterestController::class, 'index']);
        Route::middleware('volunteer.active')->group(function () {
            Route::post('/', [VolunteerInterestController::class, 'store']);
            Route::put('{volunteerInterest}', [VolunteerInterestController::class, 'update']);
            Route::delete('{volunteerInterest}', [VolunteerInterestController::class, 'destroy']);
        });
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer/languages')
    ->group(function () {

        Route::get('/', [VolunteerLanguageController::class, 'index']);
        Route::middleware('volunteer.active')->group(function () {
            Route::post('/', [VolunteerLanguageController::class, 'store']);
            Route::put('{volunteerLanguage}', [VolunteerLanguageController::class, 'update']);
            Route::delete('{volunteerLanguage}', [VolunteerLanguageController::class, 'destroy']);
        });
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer/profile/documents') //for volunteers
    ->group(function () {
        Route::get('/', [VolunteerProfileDocumentController::class, 'index']);
        Route::middleware('volunteer.active')->group(function () {
            Route::post('/', [VolunteerProfileDocumentController::class, 'store']);
            Route::delete('{media}', [VolunteerProfileDocumentController::class, 'destroy']);
        });
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteers') //for system_admin and orginazition_admin
    ->group(function () {

        Route::post('{volunteerProfile}/verify', [VolunteerVerificationController::class, 'verify']);
        Route::post('{volunteerProfile}/reject', [VolunteerVerificationController::class, 'reject']);
        Route::get('pending', [VolunteerVerificationController::class, 'pending']);
    });
