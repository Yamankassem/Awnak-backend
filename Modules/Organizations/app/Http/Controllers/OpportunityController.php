<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
 * improved testability, and easier maintenance.
 * All responses are returned as JSON for consistency.
 */
class OpportunityController extends Controller
{
    use AuthorizesRequests;

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
     * Index: Retrieve a paginated list of opportunities with their related organization.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $opportunities = Opportunity::with('organization')->paginate(10);

        return response()->json([
            'data' => OpportunityResource::collection($opportunities),
            'meta' => [
                'current_page' => $opportunities->currentPage(),
                'total' => $opportunities->total(),
            ]
        ]);
    }

    /**
     * Store: Create a new opportunity.
     *
     * Accepts validated request data and creates an opportunity
     * using the OpportunityService. Returns the created opportunity
     * wrapped in a resource with a success message.
     *
     * @param OpportunityRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OpportunityRequest $request)
    {
        $opportunity = $this->opportunityService->create($request->validated());
        $opportunity->load('organization');

        return response()->json([
            'message' => __('opportunities.created'),
            'data' => new OpportunityResource($opportunity)
        ], 201);
    }

    /**
     * Show: Display a specific opportunity by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $opportunity = Opportunity::with('organization')->findOrFail($id);

        return response()->json([
            'data' => new OpportunityResource($opportunity)
        ]);
    }

    /**
     * Update: Modify an existing opportunity.
     *
     * Finds the opportunity by ID, applies updates using the service,
     * and returns the updated opportunity wrapped in a resource
     * with a success message.
     *
     * @param OpportunityRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OpportunityRequest $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $this->authorize('update', $opportunity);

        $opportunity = $this->opportunityService->update($opportunity, $request->validated());
        $opportunity->load('organization');

        return response()->json([
            'message' => __('opportunities.updated'),
            'data' => new OpportunityResource($opportunity)
        ]);
    }

    /**
     * Destroy: Delete an opportunity.
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

        $this->authorize('delete', $opportunity);

        $this->opportunityService->delete($opportunity);

        return response()->json([
            'message' => __('opportunities.deleted')
        ]);
    }
}
