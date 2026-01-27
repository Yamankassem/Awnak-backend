<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\OrganizationRequest;
use Modules\Organizations\Transformers\OrganizationResource;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;

/**
 * Controller: OrganizationsController
 *
 * Handles CRUD operations for Organization entities.
 * Delegates business logic to OrganizationService for cleaner code,
 * improved testability, and easier maintenance.
 * All responses are returned as JSON for consistency.
 */
class OrganizationsController extends Controller
{

      use AuthorizesRequests;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $organizations = Organization::paginate(10);

        return response()->json([
            'data' => OrganizationResource::collection($organizations),
            'meta' => [
                'current_page' => $organizations->currentPage(),
                'total' => $organizations->total(),
            ]
        ]);
    }

    /**
     * Store a newly created organization.
     *
     * Accepts validated request data and creates an organization
     * using the OrganizationService. Returns the created organization
     * wrapped in a resource with a success message.
     *
     * @param OrganizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrganizationRequest $request)
    {
      //  $this->authorize('create', Organization::class);

        $organization = $this->organizationService->create($request->validated());

        return response()->json([
            'message' => __('organizations.created'),
            'data' => new OrganizationResource($organization)
        ], 201);
    }

    /**
     * Display a specific organization by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $organization = Organization::findOrFail($id);

        return response()->json([
            'data' => new OrganizationResource($organization)
        ]);
    }

    /**
     * Update an existing organization.
     *
     * Finds the organization by ID, applies updates using the service,
     * and returns the updated organization wrapped in a resource
     * with a success message.
     *
     * @param OrganizationRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OrganizationRequest $request, $id)
    {

        $organization = Organization::findOrFail($id);

        $this->authorize('update', $organization);

        $organization = $this->organizationService->update($organization, $request->validated());

        return response()->json([
            'message' => __('organizations.updated'),
            'data' => new OrganizationResource($organization)
        ]);
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

        $this->authorize('delete', $organization);

        $this->organizationService->delete($organization);

        return response()->json([
            'message' => __('organizations.deleted')
        ]);
    }

    /**
     * Display volunteers related to a specific organization.
     *
     * Loads the organization with related volunteers and returns
     * their basic information as JSON.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function volunteers($id)
    {
        $organization = Organization::with('volunteers:id,name,email,phone')
            ->findOrFail($id);

        return response()->json([
            'organization_id' => $organization->id,
            'organization_name' => $organization->license_number,
            'volunteers' => $organization->volunteers,
        ]);
    }
}
