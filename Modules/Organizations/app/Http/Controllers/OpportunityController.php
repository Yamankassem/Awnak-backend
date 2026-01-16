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
 * Handles CRUD operations for Opportunity entities.
 * Delegates business logic to OpportunityService for cleaner code and better maintainability.
 */
class OpportunityController extends Controller
{
    protected OpportunityService $opportunityService;

    /**
     * Inject the OpportunityService into the controller.
     */
    public function __construct(OpportunityService $opportunityService)
    {
        $this->opportunityService = $opportunityService;
    }

    /**
     * Display a paginated list of opportunities with their related organization.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Load opportunities with their organization relationship
        $opportunities = Opportunity::with('organization')->paginate(10);

        // Return as a collection of OpportunityResource
        return OpportunityResource::collection($opportunities);
    }

    /**
     * Store a newly created opportunity using the service.
     *
     * @param OpportunityRequest $request
     * @return OpportunityResource
     */
    public function store(OpportunityRequest $request)
    {
        // Create opportunity using validated request data via the service
        $opportunity = $this->opportunityService->create($request->validated());

        // Ensure organization relation is loaded
        $opportunity->load('organization');

        // Return the newly created opportunity wrapped in a resource
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
        // Find opportunity by ID and load its organization
        $opportunity = Opportunity::with('organization')->findOrFail($id);

        // Return as a resource
        return new OpportunityResource($opportunity);
    }

    /**
     * Update an existing opportunity using the service.
     *
     * @param OpportunityRequest $request
     * @param int $id
     * @return OpportunityResource
     */
    public function update(OpportunityRequest $request, $id)
    {
        // Find opportunity by ID
        $opportunity = Opportunity::findOrFail($id);

        // Update opportunity using the service with validated request data
        $opportunity = $this->opportunityService->update($opportunity, $request->validated());

        // Ensure organization relation is loaded
        $opportunity->load('organization');

        // Return updated opportunity wrapped in a resource
        return new OpportunityResource($opportunity);
    }

    /**
     * Remove an opportunity using the service.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find opportunity by ID
        $opportunity = Opportunity::findOrFail($id);

        // Delete opportunity using the service
        $this->opportunityService->delete($opportunity);

        // Return success message as JSON
        return response()->json(['message' => 'Opportunity deleted successfully']);
    }
}
