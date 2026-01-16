<?php

use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Controllers\DocumentController;
use Modules\Organizations\Http\Controllers\OrganizationsController;
use Modules\Organizations\Http\Controllers\OpportunityController;
use Modules\Organizations\Http\Controllers\OpportunitySkillController;

Route::apiResource('opportunity-skills', OpportunitySkillController::class);


Route::apiResource('documents',DocumentController::class);
Route::apiResource('opportunities', OpportunityController::class);

Route::apiResource('organizations', OrganizationsController::class);

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('organizations', OrganizationsController::class)->names('organizations');
// });
