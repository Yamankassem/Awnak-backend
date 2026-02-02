<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerAvailability;
use Modules\Volunteers\Services\VolunteerAvailabilityService;
use Modules\Volunteers\Transformers\VolunteerAvailabilityResource;
use Modules\Volunteers\Http\Requests\Availability\StoreVolunteerAvailabilityRequest;
use Modules\Volunteers\Http\Requests\Availability\UpdateVolunteerAvailabilityRequest;

/**
 * Class VolunteerAvailabilityController
 *
 * Handles CRUD operations for volunteer availability schedules.
 * All actions are scoped to the authenticated volunteer profile.
 *
 * @package Modules\Volunteers\Http\Controllers
 */
class VolunteerAvailabilityController extends Controller
{
    public function __construct(private VolunteerAvailabilityService $service) {}

    /**
     * List availability entries for the authenticated volunteer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        $items = $this->service->list($profile);

        return static::success(
            data: VolunteerAvailabilityResource::collection($items)
        );
    }
    /**
     * Create a new availability slot.
     *
     * @param StoreVolunteerAvailabilityRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreVolunteerAvailabilityRequest $request)
    {
        $this->authorize('create', VolunteerAvailability::class);

        $profile = $request->user()->volunteerProfile;

        $availability = $this->service->create(
            $profile,
            $request->validated(),
            $request->user()
        );

        return static::success(
            data: new VolunteerAvailabilityResource($availability),
            message: 'availability.created',
            status: 201
        );
    }
    /**
     * Update an existing availability slot.
     *
     * @param UpdateVolunteerAvailabilityRequest $request
     * @param VolunteerAvailability $availability
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateVolunteerAvailabilityRequest $request, VolunteerAvailability $availability)
    {
        $this->authorize('update', $availability);

        $availability = $this->service->update(
            $availability,
            $request->validated(),
            $request->user()
        );

        return static::success(
            data: new VolunteerAvailabilityResource($availability),
            message: 'availability.updated'
        );
    }
    /**
     * Delete an availability slot.
     *
     * @param VolunteerAvailability $availability
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(VolunteerAvailability $availability,Request $request)
    {
        $this->authorize('delete', $availability);

        $this->service->delete($availability,$request->user());

        return static::success(
            message: 'availability.deleted'
        );
    }
}
