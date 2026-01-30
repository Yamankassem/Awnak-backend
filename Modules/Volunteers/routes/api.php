<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\SkillController;
use Modules\Volunteers\Http\Controllers\InterestController;
use Modules\Volunteers\Http\Controllers\VolunteerSkillController;
use Modules\Volunteers\Http\Controllers\VolunteerProfileController;
use Modules\Volunteers\Http\Controllers\VolunteerInterestController;
use Modules\Volunteers\Http\Controllers\VolunteerAvailabilityController;

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer')
    ->group(function () {

        Route::get('profile', [VolunteerProfileController::class, 'show']);
        Route::put('profile', [VolunteerProfileController::class, 'update']);
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/skills')
    ->group(function () {
        Route::get('/', [SkillController::class, 'index'])->middleware('permission:skills.read');
        Route::post('/', [SkillController::class, 'store'])->middleware('permission:skills.create');
        Route::put('{skill}', [SkillController::class, 'update'])->middleware('permission:skills.update');
        Route::delete('{skill}', [SkillController::class, 'destroy'])->middleware('permission:skills.delete');
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/interests')
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
    ->prefix('v1/volunteer/availability')
    ->group(function () {

        Route::get('/', [VolunteerAvailabilityController::class, 'index']);
        Route::post('/', [VolunteerAvailabilityController::class, 'store']);
        Route::put('{availability}', [VolunteerAvailabilityController::class, 'update']);
        Route::delete('{availability}', [VolunteerAvailabilityController::class, 'destroy']);
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer/skills')
    ->group(function () {
        Route::get('/', [VolunteerSkillController::class, 'index']);
        Route::post('/', [VolunteerSkillController::class, 'store']);
        Route::put('{volunteerSkill}', [VolunteerSkillController::class, 'update']);
        Route::delete('{volunteerSkill}', [VolunteerSkillController::class, 'destroy']);
    });

Route::middleware(['auth:sanctum'])
    ->prefix('v1/volunteer/interests')
    ->group(function () {
        Route::get('/', [VolunteerInterestController::class, 'index']);
        Route::post('/', [VolunteerInterestController::class, 'store']);
        Route::put('{volunteerInterest}', [VolunteerInterestController::class, 'update']);
        Route::delete('{volunteerInterest}', [VolunteerInterestController::class, 'destroy']);
    });
