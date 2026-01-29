<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\OrganizationRequest;
use Modules\Organizations\Transformers\OrganizationResource;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Organizations retrieved successfully.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Helping Hands",
     *       "description": "Community support organization"
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "total": 25
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        $organizations = Organization::paginate(10);
        return response()->json([
            'status'  => 'success',
            'message' => __('organizations.retrieved'),
            'data'    => OrganizationResource::collection($organizations),
            'meta'    => [
                'current_page' => $organizations->currentPage(),
                'total'        => $organizations->total(),
            ]
        ], 200);
    }

    /**
     * Create a new organization.
     *
     * @param OrganizationRequest $request Validated request containing organization data
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 201 {
     *   "status": "success",
     *   "message": "Organization created successfully.",
     *   "data": {
     *     "id": 1,
     *     "name": "Helping Hands",
     *     "description": "Community support organization",
     *     "created_at": "2026-01-29T00:00:00Z",
     *     "updated_at": "2026-01-29T00:00:00Z"
     *   }
     * }
     *
     * @apiResponse 400 {
     *   "status": "error",
     *   "message": "Invalid organization data provided."
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to create organization",
     *   "error": "Exception message"
     * }
     */
    public function store(OrganizationRequest $request): JsonResponse
    {
        try {
            //   $this->authorize('create', Organization::class);
            $organization = $this->organizationService->create($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => __('organizations.created'),

                'data' => new OrganizationResource($organization)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to create organization', 'error' => $e->getMessage(),], 500);
        }
    }

    /**
     * Display a single organization by ID.
     *
     * @param int $id The ID of the organization to retrieve
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Organization retrieved successfully.",
     *   "data": {
     *     "id": 1,
     *     "name": "Helping Hands",
     *     "description": "Community support organization",
     *     "created_at": "2026-01-29T00:00:00Z",
     *     "updated_at": "2026-01-29T00:00:00Z"
     *   }
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Organization not found."
     * }
     */
    public function show($id): JsonResponse
    {
        try {
            $organization = Organization::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'message' => __('organizations.retrieved'),

                'data' => new OrganizationResource($organization)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => __('organizations.not_found'),], 404);
        }
    }

    /**
     * Update an existing organization.
     *
     * @param OrganizationRequest $request Validated request containing organization data
     * @param int $id The ID of the organization to update
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Organization updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "name": "Updated Helping Hands",
     *     "description": "New description"
     *   }
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Organization not found."
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to update organization",
     *   "error": "Exception message"
     * }
     */
    public function update(OrganizationRequest $request, $id): JsonResponse
    {
        try {
            $organization = Organization::findOrFail($id);
            $this->authorize('update', $organization);
            $organization = $this->organizationService->update($organization, $request->validated());
            return response()->json(['status' => 'success', 'message' => __('organizations.updated'), 'data' => new OrganizationResource($organization)], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => __('organizations.not_found'),], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update organization', 'error' => $e->getMessage(),], 500);
        }
    }


    /**
     * Delete an existing organization.
     *
     * @param int $id The ID of the organization to delete
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Organization deleted successfully."
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Organization not found."
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to delete organization",
     *   "error": "Exception message"
     * }
     */
    public function destroy($id): JsonResponse
    {
        try {
            $organization = Organization::findOrFail($id);
            $this->authorize('delete', $organization);
            $this->organizationService->delete($organization);
            return response()->json(['status' => 'success', 'message' => __('organizations.deleted'),], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => __('organizations.not_found'),], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete organization', 'error' => $e->getMessage(),], 500);
        }
    }

    /**
     * Retrieve volunteers associated with a specific organization.
     *
     * @param int $id The ID of the organization
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Volunteers retrieved successfully.",
     *   "organization_id": 1,
     *   "organization_name": "ORG-12345",
     *   "volunteers": [
     *     {
     *       "id": 10,
     *       "name": "Aya Barghouth",
     *       "email": "aya@example.com",
     *       "phone": "+963999999999"
     *     },
     *     {
     *       "id": 11,
     *       "name": "Omar Khaled",
     *       "email": "omar@example.com",
     *       "phone": "+963888888888"
     *     }
     *   ]
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Organization not found."
     * }
     */
    public function volunteers($id): JsonResponse
    {
        try {
            $organization = Organization::with('volunteers:id,first_name,last_name,phone')->findOrFail($id);
            return response()->json(['status' => 'success', 'message' => 'Volunteers retrieved successfully', 'organization_id' => $organization->id, 'organization_name' => $organization->license_number, 'volunteers' => $organization->volunteers,], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => __('organizations.not_found'),], 404);
        }
    }
}
