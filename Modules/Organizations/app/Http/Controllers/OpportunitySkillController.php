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
      $skills = OpportunitySkill::all();
       return OpportunitySkillResource::collection($skills);
    }

    /**
     * Attach new skills to an opportunity using the service.
     *
     * @param OpportunitySkillRequest $request
     * @param int $opportunityId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OpportunitySkillRequest $request, $opportunityId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);

        // Attach skills to the opportunity
        $this->opportunitySkillService->attachSkills($opportunity, $request->validated()['skill_ids']);

        return response()->json(['message' => 'Skills attached successfully']);
    }

    /**
     * Update skills for an opportunity (sync) using the service.
     *
     * @param OpportunitySkillRequest $request
     * @param int $opportunityId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OpportunitySkillRequest $request, $opportunityId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);

        // Sync skills for the opportunity
        $this->opportunitySkillService->syncSkills($opportunity, $request->validated()['skill_ids']);

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
