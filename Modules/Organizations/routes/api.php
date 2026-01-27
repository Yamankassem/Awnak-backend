<?php
use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Controllers\DocumentController;
use Modules\Organizations\Http\Controllers\OrganizationsController;
use Modules\Organizations\Http\Controllers\OpportunityController;
use Modules\Organizations\Http\Controllers\OpportunitySkillController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // هذول بدون org.access
     Route::get('/organizations', [OrganizationsController::class, 'index']);
     Route::post('/organizations', [OrganizationsController::class, 'store']);

     Route::get('/opportunities', [OpportunityController::class, 'index']);
     Route::post('/opportunities', [OpportunityController::class, 'store']);

     Route::get('/opportunity-documents', [DocumentController::class, 'index']);
     Route::post('/opportunity-documents', [DocumentController::class, 'store']);

    /** * Organizations APIs */
    Route::middleware(['org.access'])->group(function () {
        Route::get('/organizations/{organization}', [OrganizationsController::class, 'show']);
        Route::put('/organizations/{organization}', [OrganizationsController::class, 'update']);
        Route::patch('/organizations/{organization}', [OrganizationsController::class, 'update']);
        Route::delete('/organizations/{organization}', [OrganizationsController::class, 'destroy']);
    });

    /** * Opportunities APIs */
    Route::middleware(['opportunity.access'])->group(function () {
        Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show']);
        Route::put('/opportunities/{opportunity}', [OpportunityController::class, 'update']);
        Route::patch('/opportunities/{opportunity}', [OpportunityController::class, 'update']);
        Route::delete('/opportunities/{opportunity}', [OpportunityController::class, 'destroy']);
    });

    /** * Opportunity Skills APIs */
    // ما بدها middleware خاص
    Route::get('/opportunity-skills', [OpportunitySkillController::class, 'index']);
    Route::post('/opportunity-skills', [OpportunitySkillController::class, 'store']);
    Route::get('/opportunity-skills/{id}', [OpportunitySkillController::class, 'show']);
    Route::put('/opportunity-skills/{id}', [OpportunitySkillController::class, 'update']);
    Route::patch('/opportunity-skills/{id}', [OpportunitySkillController::class, 'update']);
    Route::delete('/opportunity-skills/{id}', [OpportunitySkillController::class, 'destroy']);

    /** * Opportunity Documents APIs */
    Route::middleware(['document.access'])->group(function () {
        Route::get('/opportunity-documents/{document}', [DocumentController::class, 'show']);
        Route::put('/opportunity-documents/{document}', [DocumentController::class, 'update']);
        Route::patch('/opportunity-documents/{document}', [DocumentController::class, 'update']);
        Route::delete('/opportunity-documents/{document}', [DocumentController::class, 'destroy']);
    });
});

// Volunteers by organization
Route::get('/organizations/{organization}/volunteers', [OrganizationsController::class, 'volunteers']);
