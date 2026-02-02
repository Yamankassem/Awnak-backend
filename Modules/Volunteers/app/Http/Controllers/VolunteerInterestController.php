<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerInterest;
use Modules\Volunteers\Services\VolunteerInterestService;
use Modules\Volunteers\Http\Requests\VolunteerInterest\StoreVolunteerInterestRequest;
use Modules\Volunteers\Http\Requests\VolunteerInterest\UpdateVolunteerInterestRequest;

/**
 * Class VolunteerInterestController
 *
 * Manages interests associated with the authenticated volunteer profile.
 *
 * @package Modules\Volunteers\Http\Controllers
 */
class VolunteerInterestController extends Controller
{
    public function __construct(private VolunteerInterestService $service) {}
    /**
     * List volunteer interests.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        return static::success(
            data: $this->service->list($profile)
        );
    }
    /**
     * Attach a new interest to the volunteer profile.
     *
     * @param StoreVolunteerInterestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreVolunteerInterestRequest $request)
    {
        $this->authorize('create', VolunteerInterest::class);

        $profile = $request->user()->volunteerProfile;

        return static::success(
            data: $this->service->create($profile, $request->validated(),$request->user()),
            message: 'interest.added',
            status: 201
        );
    }
    /**
     * Update an existing volunteer interest.
     *
     * @param UpdateVolunteerInterestRequest $request
     * @param VolunteerInterest $volunteerInterest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateVolunteerInterestRequest $request, VolunteerInterest $volunteerInterest)
    {
        $this->authorize('update', $volunteerInterest);

        return static::success(
            data: $this->service->update($volunteerInterest, $request->validated(),$request->user()),
            message: 'interest.updated'
        );
    }
    /**
     * Remove an interest from the volunteer profile.
     *
     * @param VolunteerInterest $volunteerInterest
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(VolunteerInterest $volunteerInterest,Request $request)
    {
        $this->authorize('delete', $volunteerInterest);

        $this->service->delete($volunteerInterest,$request->user());

        return static::success(message: 'interest.deleted');
    }
}
