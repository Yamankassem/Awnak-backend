<?php

namespace Modules\Organizations\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\OrganizationRequest;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Transformers\OrganizationResource;

class OrganizationsController extends Controller
{
    /**
     * Display a listing of organizations.
     *
     * Returns all organizations in the system as a JSON collection.
     */
    public function index()
    {
        return OrganizationResource::collection(Organization::all());
    }

    /**
     * Store a newly created organization.
     *
     * Uses OrganizationRequest for validation and creates a new record.
     */
    public function store(OrganizationRequest $request)
    {
        $organization = Organization::create($request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Display the specified organization.
     *
     * Returns a single organization by its ID.
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified organization.
     *
     * Uses OrganizationRequest for validation and updates the record.
     */
    public function update(OrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified organization.
     *
     * Deletes the organization record from the database.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return response()->json([
            'message' => 'Organization deleted successfully.'
        ], 200);
    }
}
