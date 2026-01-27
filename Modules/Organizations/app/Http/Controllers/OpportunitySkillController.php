<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Http\Requests\OpportunitySkillRequest;
use Modules\Organizations\Models\OpportunitySkill;
use Modules\Organizations\Transformers\OpportunitySkillResource;
use Modules\Organizations\Services\OpportunitySkillService;

/**
 * Controller: OpportunitySkillController
 *
 * Manages the relationship between Opportunities and Skills.
 * Provides endpoints to attach, detach, and sync skills for a given opportunity.
 * Delegates business logic to OpportunitySkillService for cleaner code and better maintainability.
 * All responses are returned as JSON for consistency.
 */
class OpportunitySkillController extends Controller
{
    protected OpportunitySkillService $opportunitySkillService;

    /**
     * Inject the OpportunitySkillService into the controller.
     *
     * @param OpportunitySkillService $opportunitySkillService
     */
    public function __construct(OpportunitySkillService $opportunitySkillService)
    {
        $this->opportunitySkillService = $opportunitySkillService;
    }

    /**
     * Index: Retrieve all skills linked to opportunities.
     *
     * Loads all records from the opportunity_skills table with related opportunity and organization.
     * Returns the data wrapped in OpportunitySkillResource collection.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $skills = OpportunitySkill::with('opportunity.organization')->get();

        return response()->json([
            'data' => OpportunitySkillResource::collection($skills)
        ]);
    }

    /**
     * Show: Display a single opportunity skill by ID.
     *
     * Finds a specific record in opportunity_skills by its primary key.
     * Returns the skill-opportunity relationship details as JSON.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $opportunitySkill = OpportunitySkill::findOrFail($id);

        return response()->json([
            'id'             => $opportunitySkill->id,
            'opportunity_id' => $opportunitySkill->opportunity_id,
            'skill_id'       => $opportunitySkill->skill_id,
            'created_at'     => optional($opportunitySkill->created_at)->toDateTimeString(),
            'updated_at'     => optional($opportunitySkill->updated_at)->toDateTimeString(),
        ]);
    }

    /**
     * Store: Attach skills to an opportunity.
     *
     * Accepts validated request data containing opportunity_id and skill_id(s).
     * Normalizes input to always be an array of skill IDs.
     * Uses the service to attach skills to the given opportunity.
     *
     * @param OpportunitySkillRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OpportunitySkillRequest $request)
    {
        $data = $request->validated();
        $opportunity = Opportunity::findOrFail($data['opportunity_id']);

        $skillIds = isset($data['skill_ids'])
            ? $data['skill_ids']
            : [$data['skill_id']];

        $this->opportunitySkillService->attachSkills($opportunity, $skillIds);

        return response()->json(['message' => __('skills.attached')], 201);
    }

    /**
     * Update: Sync skills for an opportunity.
     *
     * Accepts validated request data containing opportunity_id and skill_id(s).
     * Normalizes input to always be an array of skill IDs.
     * Uses the service to sync skills for the given opportunity.
     *
     * @param OpportunitySkillRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OpportunitySkillRequest $request)
    {
        $data = $request->validated();
        $opportunity = Opportunity::findOrFail($data['opportunity_id']);

        $skillIds = isset($data['skill_ids'])
            ? $data['skill_ids']
            : [$data['skill_id']];

        $this->opportunitySkillService->syncSkills($opportunity, $skillIds);

        return response()->json(['message' => __('skills.attached')]);
    }

    /**
     * Destroy: Detach skills from an opportunity.
     *
     * Accepts validated request data containing skill_ids.
     * Uses the service to detach the provided skills from the given opportunity.
     *
     * @param OpportunitySkillRequest $request
     * @param int $opportunityId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpportunitySkillRequest $request, $opportunityId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);

        $this->opportunitySkillService->detachSkills($opportunity, $request->validated()['skill_ids']);

        return response()->json(['message' => __('skills.detached')]);
    }
}
