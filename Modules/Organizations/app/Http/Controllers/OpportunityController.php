<?php

namespace Modules\Organizations\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Http\Requests\OpportunityRequest;
use Modules\Organizations\Transformers\OpportunityResource;
use Modules\Organizations\Services\OpportunityService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller: OpportunityController
 *
 * Manages CRUD operations for Opportunity entities.
 * Delegates business logic to OpportunityService for cleaner code,
 * improved testability, and easier maintenance.
 * All responses are returned as JSON for consistency.
 *
 * Authorization:
 * - Uses OpportunityPolicy for create, update, and delete actions.
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

    /** * Retrieve a paginated list of opportunities with their organizations. *
     * * Input: * - No parameters, retrieves all opportunities with pagination. *
     * * Output: * - JSON response containing paginated opportunities with organization data. *
     *  * @return JsonResponse *
     *  * @apiResponse 200 { * "status": "success", * "message": "Opportunity retrieved successfully.",
     * * "data": [...], * "meta": { "current_page": 1, "total": 25 } * }
     * */

    public function index(): JsonResponse
    {
        $opportunities = Opportunity::with('organization')->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => __('opportunities.retrieved'),
            'data' => OpportunityResource::collection($opportunities),
            'meta' => [
                'current_page' => $opportunities->currentPage(),
                'total' => $opportunities->total(),
            ]
        ], 200);
    }

    /**
     * Create a new opportunity.
     *
     * Authorizes via OpportunityPolicy::create.
     * Validates input with OpportunityRequest, delegates creation to OpportunityService,
     * and returns formatted data using OpportunityResource.
     *
     * Input:
     * - OpportunityRequest: validated data including title, description, organization_id,
     *   location_id OR location array, optional skills.
     *
     * Output:
     * - JSON response with created opportunity data.
     *
     * @param OpportunityRequest $request Validated request containing opportunity data
     * @return JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized
     *
     * @apiResponse 201 { "status": "success", "message": "Opportunity created successfully.", "data": {...} }
     * @apiResponse 403 { "status": "error", "message": "This action is unauthorized." }
     * @apiResponse 500 { "status": "error", "message": "Failed to create opportunity" }
     */

    public function store(OpportunityRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Opportunity::class);

            $data = $request->validated();

            // if send location_id direct when the location is already saved
            if ($request->has('location_id')) {
                $data['location'] = ['id' => $request->location_id];
            }

            //   if send new location  (lat/lng + name/type)
            if ($request->has('location')) {
                $data['location'] = $request->location;
            }

            $opportunity = $this->opportunityService->create($data);
            $opportunity->load(['organization', 'location', 'skills']);

            return response()->json([
                'status' => 'success',
                'message' => __('opportunities.created'),
                'data' => new OpportunityResource($opportunity)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create opportunity',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


  /** * Show a specific opportunity by ID. *
   *  * Input: * - int $id: Opportunity ID. *
   * * Output: * - JSON response with opportunity data. *
   *  * @param int $id * @return JsonResponse * * @apiResponse 200 { "data": {...} } *
   *  @apiResponse 404 { "status": "error", "message": "Opportunity not found." }
   *  */

    public function show($id)
    {
        $opportunity = Opportunity::with('organization')->findOrFail($id);

        return response()->json([
            'data' => new OpportunityResource($opportunity)
        ]);
    }

   /**
     * Update an existing opportunity.
     *
     * Authorizes via OpportunityPolicy::update.
     * Validates input with OpportunityRequest, delegates update to OpportunityService,
     * and returns formatted data using OpportunityResource.
     *
     * Input:
     * - OpportunityRequest: validated data.
     * - int $id: Opportunity ID.
     *
     * Output:
     * - JSON response with updated opportunity data.
     *
     * @param OpportunityRequest $request Validated request containing opportunity data
     * @param int $id The ID of the opportunity to update
     * @return JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized
     *
     * @apiResponse 200 { "status": "success", "message": "Opportunity updated successfully.", "data": {...} }
     * @apiResponse 403 { "status": "error", "message": "This action is unauthorized." }
     * @apiResponse 404 { "status": "error", "message": "Opportunity not found." }
     * @apiResponse 500 { "status": "error", "message": "Failed to update opportunity" }
     */

    public function update(OpportunityRequest $request, $id): JsonResponse
    {
        try {
            $opportunity = Opportunity::findOrFail($id);
            $this->authorize('update', $opportunity);
            $opportunity = $this->opportunityService->update($opportunity, $request->validated());
            $opportunity->load('organization');
            return response()->json([
                'status' => 'success',
                'message' => __('opportunities.updated'),
                'data' => new OpportunityResource($opportunity)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => __('opportunities.not_found'),], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update opportunity', 'error' => $e->getMessage(),], 500);
        }
    }


    /**
     * Delete an opportunity.
     *
     * Authorizes via OpportunityPolicy::delete.
     * Delegates deletion to OpportunityService.
     *
     * Input:
     * - int $id: Opportunity ID.
     *
     * Output:
     * - JSON response with success or error message.
     *
     * @param int $id The ID of the opportunity to delete
     * @return JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized
     *
     * @apiResponse 200 { "status": "success", "message": "Opportunity deleted successfully." }
     * @apiResponse 403 { "status": "error", "message": "This action is unauthorized." }
     * @apiResponse 404 { "status": "error", "message": "Opportunity not found." }
     * @apiResponse 500 { "status": "error", "message": "Failed to delete opportunity" }
     */

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $opportunity = Opportunity::findOrFail($id);

            $this->authorize('delete', $opportunity);

            $this->opportunityService->delete($opportunity);

            return response()->json([
                'status'  => 'success',
                'message' => __('opportunities.deleted'),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Opportunity not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete opportunity',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
