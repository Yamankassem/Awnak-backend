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
 * Handles operations for managing the relationship between Opportunities and Skills.
 * Delegates business logic (attach, detach, sync) to OpportunitySkillService
 * for cleaner code and better maintainability.
 */
class OpportunitySkillController extends Controller
{
    protected OpportunitySkillService $opportunitySkillService;

    /**
     * Inject the OpportunitySkillService into the controller.
     */
    public function __construct(OpportunitySkillService $opportunitySkillService)
    {
        $this->opportunitySkillService = $opportunitySkillService;
    }

    /**
     * Display all skills linked to a specific opportunity.
     *
     * @param int $opportunityId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $skills = OpportunitySkill::with('opportunity.organization')->get();
        return OpportunitySkillResource::collection($skills);
    }


    /**
     * Show: Display a single opportunity skill by ID.
     *
     * This method retrieves one record from the opportunity_skills table
     * using its primary key. It returns the skill data as JSON.
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
     * Store: Attach a skill (or multiple skills) to an opportunity.
     *
     * This method accepts the validated request payload containing
     * `opportunity_id` and either a single `skill_id` or an array of `skill_ids`.
     * Instead of expecting the opportunity ID from the route, it reads it directly
     * from the request body. The service then handles attaching the provided
     * skill IDs to the given opportunity.
     *
     * @param OpportunitySkillRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OpportunitySkillRequest $request)
    {
        $data = $request->validated();

        $opportunity = Opportunity::findOrFail($data['opportunity_id']);

        // Normalize skill IDs: wrap single value into an array if needed
        $skillIds = isset($data['skill_ids'])
            ? $data['skill_ids']                // case: array of skill IDs
            : [$data['skill_id']];              // case: single skill ID

        // Attach skills to the opportunity using the service
        $this->opportunitySkillService->attachSkills($opportunity, $skillIds);

        return response()->json(['message' => 'Skills attached successfully']);
    }


    /**
     * Update: Sync skills for an opportunity.
     *
     * This method accepts the validated request payload containing
     * `opportunity_id` and either a single `skill_id` or an array of `skill_ids`.
     * Since the service method signature requires an array, the code normalizes
     * the input: if only one `skill_id` is provided, it is wrapped into an array.
     * This ensures compatibility and prevents type errors.
     *
     * @param OpportunitySkillRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OpportunitySkillRequest $request)
    {
        $data = $request->validated();

        $opportunity = Opportunity::findOrFail($data['opportunity_id']);

        // Normalize skill IDs: wrap single value into an array if needed
        $skillIds = isset($data['skill_ids'])
            ? $data['skill_ids']          // case: array of skill IDs
            : [$data['skill_id']];        // case: single skill ID

        // Sync skills for the opportunity
        $this->opportunitySkillService->syncSkills($opportunity, $skillIds);

        return response()->json(['message' => 'Skills synced successfully']);
    }


    /**
     * Detach specific skills from an opportunity using the service.
     *
     * @param OpportunitySkillRequest $request
     * @param int $opportunityId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpportunitySkillRequest $request, $opportunityId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);

        // Detach skills from the opportunity
        $this->opportunitySkillService->detachSkills($opportunity, $request->validated()['skill_ids']);

        return response()->json(['message' => 'Skills detached successfully']);
    }
}
