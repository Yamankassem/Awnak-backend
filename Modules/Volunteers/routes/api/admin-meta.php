<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteers\Http\Controllers\{
    SkillController,
    InterestController,
    LanguageController
};

Route::prefix('v1')->group(function () {

    Route::middleware('permission:skills.read')->get('skills', [SkillController::class, 'index']);
    Route::middleware('permission:skills.create')->post('skills', [SkillController::class, 'store']);
    Route::middleware('permission:skills.update')->put('skills/{skill}', [SkillController::class, 'update']);
    Route::middleware('permission:skills.delete')->delete('skills/{skill}', [SkillController::class, 'destroy']);

    Route::middleware('permission:interests.read')->get('interests', [InterestController::class, 'index']);
    Route::middleware('permission:interests.create')->post('interests', [InterestController::class, 'store']);
    Route::middleware('permission:interests.update')->put('interests/{interest}', [InterestController::class, 'update']);
    Route::middleware('permission:interests.delete')->delete('interests/{interest}', [InterestController::class, 'destroy']);

    Route::middleware('permission:languages.read')->get('languages', [LanguageController::class, 'index']);
    Route::middleware('permission:languages.create')->post('languages', [LanguageController::class, 'store']);
    Route::middleware('permission:languages.update')->put('languages/{language}', [LanguageController::class, 'update']);
    Route::middleware('permission:languages.delete')->delete('languages/{language}', [LanguageController::class, 'destroy']);

});
