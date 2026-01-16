<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Http\Requests\OpportunityRequest;
use Modules\Organizations\Transformers\OpportunityResource;

/**
 * Controller: OpportunityController
 *
 * Handles CRUD operations for Opportunity entities.
 * Provides endpoints to list, create, view, update, and delete opportunities.
 */
class OpportunityController extends Controller
{
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
     * Store a newly created opportunity in the database.
     *
     * @param OpportunityRequest $request
     * @return OpportunityResource
     */
    public function store(OpportunityRequest $request)
    {
        // Create opportunity using validated request data
        $opportunity = Opportunity::create($request->validated());
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
     * Update an existing opportunity by ID.
     *
     * @param OpportunityRequest $request
     * @param int $id
     * @return OpportunityResource
     */
    public function update(OpportunityRequest $request, $id)
    {
        // Find opportunity by ID
        $opportunity = Opportunity::findOrFail($id);

        // Update with validated request data
        $opportunity->update($request->validated());

        // Return updated opportunity with its organization
        return new OpportunityResource($opportunity->load('organization'));
    }

    /**
     * Remove an opportunity from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find opportunity by ID and delete it
        Opportunity::findOrFail($id)->delete();

        // Return success message as JSON
        return response()->json(['message' => 'Opportunity deleted successfully']);
    }
}
