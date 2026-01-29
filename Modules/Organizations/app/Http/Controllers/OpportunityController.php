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

    /**
     * Retrieve a paginated list of opportunities with their organizations.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Opportunity retrieved successfully.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Volunteer Program",
     *       "description": "Community volunteering opportunity",
     *       "organization": {
     *         "id": 2,
     *         "name": "Helping Hands"
     *       }
     *     },
     *     {
     *       "id": 2,
     *       "title": "Internship",
     *       "description": "Summer internship program",
     *       "organization": {
     *         "id": 3,
     *         "name": "Tech Corp"
     *       }
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
     * @param OpportunityRequest $request Validated request containing opportunity data
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 201 {
     *   "status": "success",
     *   "message": "Opportunity created successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Volunteer Program",
     *     "description": "Community volunteering opportunity",
     *     "organization": {
     *       "id": 2,
     *       "name": "Helping Hands"
     *     }
     *   }
     * }
     *
     * @apiResponse 400 {
     *   "status": "error",
     *   "message": "Invalid opportunity data provided."
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to create opportunity",
     *   "error": "Exception message"
     * }
     */
    public function store(OpportunityRequest $request): JsonResponse
    {
        try {
            $opportunity = $this->opportunityService->create($request->validated());
            $opportunity->load('organization');
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

    /**
     * Show: Display a specific opportunity by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
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
     * @param OpportunityRequest $request Validated request containing opportunity data
     * @param int $id The ID of the opportunity to update
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 {
     *   "status": "success",
     *   "message": "Opportunity updated successfully.",
     *   "data": {
     *     "id": 1,
     *     "title": "Updated Volunteer Program",
     *     "description": "New description",
     *     "organization": {
     *       "id": 2,
     *       "name": "Helping Hands"
     *     }
     *   }
     * }
     *
     * @apiResponse 404 {
     *   "status": "error",
     *   "message": "Opportunity not found."
     * }
     *
     * @apiResponse 500 {
     *   "status": "error",
     *   "message": "Failed to update opportunity",
     *   "error": "Exception message"
     * }
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
     * Destroy: Delete an opportunity.
     *
     * Removes the opportunity record from the database using the service.
     * Returns a success message as JSON.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $this->authorize('delete', $opportunity);

        $this->opportunityService->delete($opportunity);

        return response()->json([
            'message' => __('opportunities.deleted')
        ]);
    }
}
