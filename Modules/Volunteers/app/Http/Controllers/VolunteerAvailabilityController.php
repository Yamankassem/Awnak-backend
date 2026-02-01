<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerAvailability;
use Modules\Volunteers\Services\VolunteerAvailabilityService;
use Modules\Volunteers\Transformers\VolunteerAvailabilityResource;
use Modules\Volunteers\Http\Requests\Availability\StoreVolunteerAvailabilityRequest;
use Modules\Volunteers\Http\Requests\Availability\UpdateVolunteerAvailabilityRequest;

class VolunteerAvailabilityController extends Controller
{
    public function __construct(
        private VolunteerAvailabilityService $service
    ) {}

    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        $items = $this->service->list($profile);

        return static::success(
            data: VolunteerAvailabilityResource::collection($items)
        );
    }

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

    public function destroy(VolunteerAvailability $availability,Request $request)
    {
        $this->authorize('delete', $availability);

        $this->service->delete($availability,$request->user());

        return static::success(
            message: 'availability.deleted'
        );
    }
}
