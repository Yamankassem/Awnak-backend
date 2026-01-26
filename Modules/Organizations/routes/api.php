<?php

use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Controllers\DocumentController;
use Modules\Organizations\Http\Controllers\OrganizationsController;
use Modules\Organizations\Http\Controllers\OpportunityController;
use Modules\Organizations\Http\Controllers\OpportunitySkillController;



Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    /** * Organizations APIs */
    Route::get('/organizations', [OrganizationsController::class, 'index']);
    Route::post('/organizations', [OrganizationsController::class, 'store']);
    Route::get('/organizations/{id}', [OrganizationsController::class, 'show']);
    Route::put('/organizations/{id}', [OrganizationsController::class, 'update']);
    Route::patch('/organizations/{id}', [OrganizationsController::class, 'update']);
    Route::delete('/organizations/{id}', [OrganizationsController::class, 'destroy']);


    /** * Opportunities APIs */
    Route::get('/opportunities', [OpportunityController::class, 'index']);
    Route::post('/opportunities', [OpportunityController::class, 'store']);
    Route::get('/opportunities/{id}', [OpportunityController::class, 'show']);
    Route::put('/opportunities/{id}', [OpportunityController::class, 'update']);
    Route::patch('/opportunities/{id}', [OpportunityController::class, 'update']);
    Route::delete('/opportunities/{id}', [OpportunityController::class, 'destroy']);


    /** * Opportunity Skills APIs */
    Route::get('/opportunity-skills', [OpportunitySkillController::class, 'index']);
    Route::post('/opportunity-skills', [OpportunitySkillController::class, 'store']);
    Route::get('/opportunity-skills/{id}', [OpportunitySkillController::class, 'show']);
    Route::put('/opportunity-skills/{id}', [OpportunitySkillController::class, 'update']);
    Route::patch('/opportunity-skills/{id}', [OpportunitySkillController::class, 'update']);
    Route::delete('/opportunity-skills/{id}', [OpportunitySkillController::class, 'destroy']);


    /** * Opportunity Documents APIs */
    Route::get('/opportunity-documents', [DocumentController::class, 'index']);
    Route::post('/opportunity-documents', [DocumentController::class, 'store']);
    Route::get('/opportunity-documents/{id}', [DocumentController::class, 'show']);
    Route::put('/opportunity-documents/{id}', [DocumentController::class, 'update']);
    Route::patch('/opportunity-documents/{id}', [DocumentController::class, 'update']);
    Route::delete('/opportunity-documents/{id}', [DocumentController::class, 'destroy']);
});

// Volunteers by organization
Route::get('/organizations/{id}/volunteers', [OrganizationsController::class, 'volunteers']);
