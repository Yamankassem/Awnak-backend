<?php

use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Controllers\DocumentController;
use Modules\Organizations\Http\Controllers\NearestController;
use Modules\Organizations\Http\Controllers\OrganizationsController;
use Modules\Organizations\Http\Controllers\OpportunityController;
use Modules\Organizations\Http\Controllers\OpportunitySkillController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {



    Route::post('/opportunities/nearest', [NearestController::class, 'nearest']);
    Route::post('/opportunities/farther', [NearestController::class, 'farther'])->middleware('throttle:5,1');;



    /** * Organizations APIs */
    /**
     * API Routes (v1)
     *
     * @group Organizations
     *
     * @route GET /api/v1/organizations
     * @description Get all organizations.
     * @apiResponse 200 { "status": "success", "data": [ { "id": 1, "name": "Org A" } ] }
     *
     * @route GET /api/v1/organizations/{organization}
     * @description Get a specific organization by ID.
     * @apiResponse 200 { "status": "success", "data": { "id": 1, "name": "Org A" } }
     * @apiResponse 404 { "status": "error", "message": "Organization not found." }
     *
     * @route POST /api/v1/organizations
     * @description Create a new organization.
     * @apiResponse 201 { "status": "success", "message": "Organization created successfully." }
     *
     * @route PUT|PATCH /api/v1/organizations/{organization}
     * @description Update an existing organization.
     * @apiResponse 200 { "status": "success", "message": "Organization updated successfully." }
     *
     * @route DELETE /api/v1/organizations/{organization}
     * @description Delete an organization.
     * @apiResponse 200 { "status": "success", "message": "Organization deleted successfully." }
     * @apiResponse 404 { "status": "error", "message": "Organization not found." }
     *      * * @route GET /api/v1/organizations/{id}/volunteers *
     *  @description Get volunteers belonging to a specific organization (via applications/opportunities). */

    Route::middleware(['org.access'])->group(function () {

        Route::get('/organizations/notactive', [OrganizationsController::class, 'listNotActive']);

        Route::get('/organizations', [OrganizationsController::class, 'index']);
        Route::get('/organizations/{organization}', [OrganizationsController::class, 'show']);
        Route::post('/organizations', [OrganizationsController::class, 'store']);
        Route::put('/organizations/{organization}', [OrganizationsController::class, 'update']);
        Route::patch('/organizations/{organization}', [OrganizationsController::class, 'update']);
        Route::delete('/organizations/{organization}', [OrganizationsController::class, 'destroy']);
        // Volunteers of specific Organization
        Route::get('/organizations/{id}/volunteers', [OrganizationsController::class, 'getOrganizationVolunteers']);
        /** Route for role System-admin for activate and list Organizations not active  */
        Route::patch('/organizations/{organization}/activate', [OrganizationsController::class, 'activate']);


    });



    /** * Opportunities APIs */
    /** * @group Opportunities *
     * * @route GET /api/v1/opportunities * @description Get all opportunities. *
     *  * @route GET /api/v1/opportunities/{opportunity}* @description Get a specific opportunity by ID. *
     *  * @route POST /api/v1/opportunities *  @description Create a new opportunity. *
     *  * @route PUT /api/v1/opportunities/{id} *@description Update an existing opportunity. *
     * * @route DELETE /api/v1/opportunities/{opportunity} * @description Delete an opportunity. */

    Route::middleware(['opportunity.access'])->group(function () {
        Route::get('/opportunities', [OpportunityController::class, 'index']);
        Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show']);
        Route::post('/opportunities', [OpportunityController::class, 'store']);
        Route::put('/opportunities/{id}', [OpportunityController::class, 'update']);
        Route::delete('/opportunities/{opportunity}', [OpportunityController::class, 'destroy']);
    });

    /** * Opportunity Skills APIs */
    /** * @group Opportunity Skills *
     * * @route GET /api/v1/opportunity-skills *
     *  @description Get all opportunity skills. *
     * * @route POST /api/v1/opportunity-skills * @description Create a new opportunity skill. *
     * * @route GET /api/v1/opportunity-skills/{id} * @description Get a specific opportunity skill. *
     * * @route PUT|PATCH /api/v1/opportunity-skills/{id} * @description Update an opportunity skill. *
     *  * @route DELETE /api/v1/opportunity-skills/{id} *  @description Delete an opportunity skill.
     *  */
    Route::middleware(['opportunity.skills.access'])->group(function () {
        Route::get('/opportunity-skills', [OpportunitySkillController::class, 'index']);
        Route::post('/opportunity-skills', [OpportunitySkillController::class, 'store']);
        Route::get('/opportunity-skills/{id}', [OpportunitySkillController::class, 'show']);
        Route::put('/opportunity-skills/{id}', [OpportunitySkillController::class, 'update']);
        Route::patch('/opportunity-skills/{id}', [OpportunitySkillController::class, 'update']);
        Route::delete('/opportunity-skills/{id}', [OpportunitySkillController::class, 'destroy']);
    });


    /** * Opportunity Documents APIs */
    /**
     * @group Opportunity Documents
     *
     * @route GET /api/v1/opportunities/{opportunity}/documents
     * @description Get all documents for a specific opportunity.
     *
     * @route POST /api/v1/opportunity-documents
     * @description Upload a new document for an opportunity.
     *
     * @route GET /api/v1/opportunity-documents/{id}
     * @description Get a specific opportunity document.
     *
     * @route DELETE /api/v1/opportunity-documents/{id}
     * @description Delete an opportunity document.
     */
    Route::middleware(['document.access'])->group(function () {
        Route::get('/opportunities/{opportunity}/documents', [DocumentController::class, 'index']);
        Route::post('/opportunity-documents', [DocumentController::class, 'store']);
        Route::get('/opportunity-documents/{id}', [DocumentController::class, 'show']);
        Route::delete('/opportunity-documents/{id}', [DocumentController::class, 'destroy']);
    });
});
