<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\OrganizationRequest;
use Modules\Organizations\Transformers\OrganizationResource;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;

/**
 * Controller: OrganizationsController
 *
 * Manages CRUD operations for Organization entities.
 * Delegates business logic to OrganizationService for cleaner code,
 * improved testability, and easier maintenance. Uses OrganizationResource
 * to format API responses consistently.
 */
class OrganizationsController extends Controller
{
    protected OrganizationService $organizationService;

    /**
     * Inject the OrganizationService into the controller.
     *
     * @param OrganizationService $organizationService
     */
    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Retrieve a paginated list of organizations.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $organizations = Organization::paginate(10);
        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created organization.
     *
     * Accepts validated request data and creates an organization
     * using the OrganizationService. Returns the created organization
     * wrapped in a resource.
     *
     * @param OrganizationRequest $request
     * @return OrganizationResource
     */
    public function store(OrganizationRequest $request)
    {
        $organization = $this->organizationService->create($request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Display a specific organization by ID.
     *
     * @param int $id
     * @return OrganizationResource
     */
    public function show($id)
    {
        $organization = Organization::findOrFail($id);
        return new OrganizationResource($organization);
    }

    /**
     * Update an existing organization.
     *
     * Finds the organization by ID, applies updates using the service,
     * and returns the updated organization wrapped in a resource.
     *
     * @param OrganizationRequest $request
     * @param int $id
     * @return OrganizationResource
     */
    public function update(OrganizationRequest $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $organization = $this->organizationService->update($organization, $request->validated());
        return new OrganizationResource($organization);
    }

    /**
     * Delete an organization.
     *
     * Removes the organization record from the database using the service.
     * Returns a success message as JSON.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        $this->organizationService->delete($organization);

        return response()->json(['message' => 'Organization deleted successfully']);
    }

    /**
     * Display volunteers related to a specific organization.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function volunteers($id)
    {
        // Load organization with only the needed volunteer fields
        $organization = Organization::with('volunteers:id,name,email,phone')
            ->findOrFail($id);

        // Return volunteers as JSON
        return response()->json([
            'organization_id' => $organization->id,
            'organization_name' => $organization->license_number,
            'volunteers' => $organization->volunteers,
        ]);
    }
}
