<?php

namespace Modules\Organizations\Http\Controllers;

use Modules\Organizations\Models\Organization;
use Modules\Organizations\Http\Requests\OrganizationRequest;
use Modules\Organizations\Transformers\OrganizationResource; 
use Illuminate\Routing\Controller;

class OrganizationsController extends Controller
{
    /**
     * Display a listing of organizations.
     *
     * Retrieves all organizations from the database
     * and returns them transformed into a consistent JSON format.
     */
    public function index()
    {
        $organizations = Organization::all();
        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created organization.
     *
     * Validates the request using OrganizationRequest,
     * creates a new organization record, and returns it transformed.
     */
    public function store(OrganizationRequest $request)
    {
        $organization = Organization::create($request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Display a single organization.
     *
     * Returns the specified organization transformed into JSON format.
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update an existing organization.
     *
     * Validates the request, updates the organization record,
     * and returns the updated organization transformed.
     */
    public function update(OrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Remove an organization.
     *
     * Deletes the specified organization record from the database
     * and returns a 204 No Content response.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->json(null, 204);
    }
}
