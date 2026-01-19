<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\OrganizationRequest;
use Modules\Organizations\Transformers\OrganizationResource;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;

class OrganizationsController extends Controller
{
    protected OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Display a paginated list of organizations.
     */
    public function index()
    {
        $organizations = Organization::paginate(10);
        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created organization using the service.
     */
    public function store(OrganizationRequest $request)
    {
        $organization = $this->organizationService->create($request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Show a specific organization.
     */
    public function show($id)
    {
        $organization = Organization::findOrFail($id);
        return new OrganizationResource($organization);
    }

    /**
     * Update an existing organization using the service.
     */
    public function update(OrganizationRequest $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $organization = $this->organizationService->update($organization, $request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Delete an organization using the service.
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        $this->organizationService->delete($organization);

        return response()->json(['message' => 'Organization deleted successfully']);
    }
}
