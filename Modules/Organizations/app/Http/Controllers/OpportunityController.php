<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Http\Requests\OpportunityRequest;
use Modules\Organizations\Transformers\OpportunityResource;
use Modules\Organizations\Services\OpportunityService;

/**
 * Controller: OpportunityController
 *
 * Manages CRUD operations for Opportunity entities.
 * Delegates business logic to OpportunityService for cleaner code,
 * improved testability, and easier maintenance. Uses OpportunityResource
 * to format API responses consistently.
 */
class OpportunityController extends Controller
{
    protected OpportunityService $opportunityService;

    /**
     * Inject the OpportunityService into the controller.
     *
     * @param OpportunityService $opportunityService
     */
    public function __construct(OpportunityService $opportunityService)
    {
        $this->opportunityService = $opportunityService;
    }

    /**
     * Retrieve a paginated list of opportunities with their related organization.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $opportunities = Opportunity::with('organization')->paginate(10);
        return OpportunityResource::collection($opportunities);
    }

    /**
     * Store a newly created opportunity.
     *
     * Accepts validated request data and creates an opportunity
     * using the OpportunityService. Returns the created opportunity
     * wrapped in a resource.
     *
     * @param OpportunityRequest $request
     * @return OpportunityResource
     */
    public function store(OpportunityRequest $request)
    {
        $opportunity = $this->opportunityService->create($request->validated());
        $opportunity->load('organization');

        return new OpportunityResource($opportunity);
    }

    /**
     * Display a specific opportunity by ID.
     *
     * @param int $id
     * @return OpportunityResource
     */
    public function show($id)
    {
        $opportunity = Opportunity::with('organization')->findOrFail($id);
        return new OpportunityResource($opportunity);
    }

    /**
     * Update an existing opportunity.
     *
     * Finds the opportunity by ID, applies updates using the service,
     * and returns the updated opportunity wrapped in a resource.
     *
     * @param OpportunityRequest $request
     * @param int $id
     * @return OpportunityResource
     */
    public function update(OpportunityRequest $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);
        $opportunity = $this->opportunityService->update($opportunity, $request->validated());
        $opportunity->load('organization');

        return new OpportunityResource($opportunity);
    }

    /**
     * Delete an opportunity.
     *
     * Removes the opportunity record from the database using the service.
     * Returns a success message as JSON.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $opportunity = Opportunity::findOrFail($id);
        $this->opportunityService->delete($opportunity);

        return response()->json(['message' => 'Opportunity deleted successfully']);
    }
}
