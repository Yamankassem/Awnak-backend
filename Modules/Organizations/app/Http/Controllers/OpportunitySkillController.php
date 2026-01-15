<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Models\OpportunitySkill;
use Modules\Organizations\Http\Requests\OpportunitySkillRequest;
use Modules\Organizations\Transformers\OpportunitySkillResource;

/**
 * Controller: OpportunitySkillController
 *
 * Handles CRUD operations for the pivot table "opportunity_skill"
 * which links Opportunities with Skills in a many-to-many relationship.
 */
class OpportunitySkillController extends Controller
{
    /**
     * Display a listing of all opportunity-skill records.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Return all records wrapped in the OpportunitySkillResource
        return OpportunitySkillResource::collection(OpportunitySkill::all());
    }

    /**
     * Store a newly created opportunity-skill record in storage.
     *
     * @param  OpportunitySkillRequest  $request
     * @return OpportunitySkillResource
     */
    public function store(OpportunitySkillRequest $request)
    {
        // Create a new pivot record using validated request data
        $pivot = OpportunitySkill::create($request->validated());

        // Return the newly created record as a resource
        return new OpportunitySkillResource($pivot);
    }

    /**
     * Display the specified opportunity-skill record.
     *
     * @param  OpportunitySkill  $opportunitySkill
     * @return OpportunitySkillResource
     */
    public function show(OpportunitySkill $opportunitySkill)
    {
        // Return a single record wrapped in the resource
        return new OpportunitySkillResource($opportunitySkill);
    }

    /**
     * Update the specified opportunity-skill record in storage.
     *
     * @param  OpportunitySkillRequest  $request
     * @param  OpportunitySkill  $opportunitySkill
     * @return OpportunitySkillResource
     */
    public function update(OpportunitySkillRequest $request, OpportunitySkill $opportunitySkill)
    {
        // Update the record with validated request data
        $opportunitySkill->update($request->validated());

        // Return the updated record as a resource
        return new OpportunitySkillResource($opportunitySkill);
    }

    /**
     * Remove the specified opportunity-skill record from storage.
     *
     * @param  OpportunitySkill  $opportunitySkill
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpportunitySkill $opportunitySkill)
    {
        // Delete the record from the database
        $opportunitySkill->delete();

        // Return an empty response with 204 No Content status
        return response()->json(null, 204);
    }
}
