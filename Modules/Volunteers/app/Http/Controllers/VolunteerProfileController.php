<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Services\VolunteerProfileService;
use Modules\Volunteers\Transformers\VolunteerProfileResource;
use Modules\Volunteers\Services\VolunteerProfileStatusService;
use Modules\Volunteers\Http\Requests\UpdateVolunteerProfileRequest;

/**
 * Class VolunteerProfileController
 *
 * Handles volunteer profile lifecycle:
 * viewing, updating, activation, suspension, and task history.
 *
 * @package Modules\Volunteers\Http\Controllers
 */
class VolunteerProfileController extends Controller
{
    public function __construct(
        private VolunteerProfileService $service,
        private VolunteerProfileStatusService $service1) {}
     /**
     * Display authenticated volunteer profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $profile = $this->service->getByUser($request->user());

        $this->authorize('view', $profile);

        return static::success(
            data: new VolunteerProfileResource($profile)
        );
    }
     /**
     * List volunteer task history.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $items = $this->service->list($request->user());

        return static::success(data: $items);
    }
    /**
     * Update volunteer profile data.
     *
     * @param UpdateVolunteerProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateVolunteerProfileRequest $request)
    {
        $profile = $this->service->getByUser($request->user());

        $this->authorize('update', $profile);

        $profile = $this->service->update(
            $profile,
            $request->validated(),
            $request->user()
        );

        return static::success(
            data: new VolunteerProfileResource($profile),
            message: 'profile.updated'
        );
    }
     /**
     * Activate a volunteer profile.
     *
     * @param Request $request
     * @param VolunteerProfile $volunteerProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate(Request $request, VolunteerProfile $volunteerProfile)
    {
        $this->authorize('manageStatus', $volunteerProfile);

        $volunteerProfile = $this->service1->activate($volunteerProfile, $request->user());

        return static::success(
            data: new VolunteerProfileResource($volunteerProfile),
            message: 'volunteer.activated'
        );
    }
    /**
     * Suspend a volunteer profile.
     *
     * @param Request $request
     * @param VolunteerProfile $volunteerProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function suspend(Request $request, VolunteerProfile $volunteerProfile)
    {
        $this->authorize('manageStatus', $volunteerProfile);

        $volunteerProfile = $this->service1->suspend($volunteerProfile, $request->user());

        return static::success(
            data: new VolunteerProfileResource($volunteerProfile),
            message: 'volunteer.suspended'
        );
    }
    
}
