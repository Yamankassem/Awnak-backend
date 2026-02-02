<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Organizations\Http\Requests\OrganizationRequest;
use Modules\Organizations\Transformers\OrganizationResource;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;
use Modules\Volunteers\Models\VolunteerProfile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller: OrganizationsController
 *
 * Handles CRUD operations for Organization entities.
 * Delegates business logic to OrganizationService for cleaner code,
 * improved testability, and easier maintenance.
 * All responses are returned as JSON for consistency.
 *
 * Authorization:
 * - Uses OrganizationPolicy for create, update, delete, and status updates.
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
     * Note:
     * - Only organizations with status = "active" are returned.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Organizations retrieved successfully.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "license_number": "LIC-001",
     *       "type": "NGO",
     *       "bio": "Humanitarian organization providing aid and relief.",
     *       "website": "https://redcrescent.org",
     *       "status": "active"
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
        $organizations = Organization::active()->paginate(10);;
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

    /** * Create a new organization. *
     *  * Authorizes via OrganizationPolicy::create.
     * Validates input with OrganizationRequest, delegates creation to OrganizationService,
     *  * and returns formatted data using OrganizationResource. *
     *  * @param OrganizationRequest $request Validated request containing organization data
     * * @return JsonResponse *
     *  * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized *
     *  * @apiResponse 201 { "status": "success", "message": "Organization created successfully.", "data": {...} } *
     *  @apiResponse 400 { "status": "error", "message": "Invalid organization data provided." } *
     *  @apiResponse 500 { "status": "error", "message": "Failed to create organization" }
     */
    public function store(OrganizationRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Organization::class);

            if ($request->user()->hasRole('system-admin') && $request->has('status')) {
                $data['status'] = $request->input('status');
            } else {
                $data['status'] = 'notactive';
            }

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


    /** * Delete an existing organization. *
     *  * Authorizes via OrganizationPolicy::delete. * Delegates deletion to OrganizationService. *
     * * @param int $id Organization ID * @return JsonResponse *
     * * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized *
     *  * @apiResponse 200 { "status": "success", "message": "Organization deleted successfully." }
     * * @apiResponse 404 { "status": "error", "message": "Organization not found." }
     * * @apiResponse 500 { "status": "error", "message": "Failed to delete organization" }
     *  */
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
     * Get Volunteers of a Specific Organization
     *
     * Retrieves all volunteers who applied to opportunities belonging to a given organization.
     * The volunteers are fetched via the applications table, which links volunteer_id with opportunity_id.
     *
     * @param int $id The ID of the organization
     * @return \Illuminate\Http\JsonResponse
     *
     * @route GET /api/v1/organizations/{id}/volunteers
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Volunteers retrieved successfully.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Volunteer A",
     *       "email": "volunteerA@example.com",
     *       "phone": "123456789"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Volunteer B",
     *       "email": "volunteerB@example.com",
     *       "phone": "987654321"
     *     }
     *   ]
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Organization not found"
     * }
     *
     * @apiResponse 403 {
     *   "status": "error",
     *   "message": "Access denied: not authorized to view volunteers of this organization"
     * }
     */
    public function getOrganizationVolunteers($id)
    {
        $volunteers = VolunteerProfile::whereIn('id', function ($query) use ($id) {
            $query->select('applications.volunteer_id')
                ->from('applications')
                ->join('opportunities', 'applications.opportunity_id', '=', 'opportunities.id')
                ->where('opportunities.organization_id', $id);
        })->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Volunteers retrieved successfully.',
            'data'    => $volunteers
        ], 200);
    }

    /**
     * Activate an organization.
     *
     * Authorizes via OrganizationPolicy::updateStatus.
     * Sets status to active and saves.
     *
     * @param Request $request
     * @param Organization $organization
     * @return JsonResponse
     *
     * @apiResponse 200 { "status": "success", "message": "Organization activated.", "data": {...} }
     * @apiResponse 500 { "status": "error", "message": "Failed to activate organization" }
     */

    public function activate(Request $request, Organization $organization): JsonResponse
    {
        try {
            // only system-admin can change status
            $this->authorize('updateStatus', $organization);

            $organization->status = 'active';
            $organization->save();

            return response()->json([
                'status' => 'success',
                'message' => __('organizations.activated'),
                'data' => new OrganizationResource($organization)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to activate organization',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List all not-active organizations.
     *
     * Restricted to system-admin role.
     *
     * @return JsonResponse
     *
     * @apiResponse 200 { "status": "success", "data": [...] }
     * @apiResponse 403 { "status": "error", "message": "Unauthorized" }
     */

    public function listNotActive(): JsonResponse
    {
        // System-admin role only can list
        if (!auth()->user()->hasRole('system-admin')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $organizations = Organization::where('status', 'notactive')->get();

        return response()->json([
            'status' => 'success',
            'data' => OrganizationResource::collection($organizations)
        ]);
    }
}
