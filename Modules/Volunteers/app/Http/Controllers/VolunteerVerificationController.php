<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Services\VolunteerVerificationService;
use Modules\Volunteers\Transformers\VolunteerProfileResource;

/**
 * Class VolunteerVerificationController
 *
 * Handles verification workflow for volunteer profiles
 * (approve / reject / list pending).
 *
 * @package Modules\Volunteers\Http\Controllers
 */
class VolunteerVerificationController extends Controller
{
    public function __construct(private VolunteerVerificationService $service) {}
    /**
     * Verify a volunteer profile.
     *
     * @param Request $request
     * @param VolunteerProfile $volunteerProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request, VolunteerProfile $volunteerProfile)
    {
        $this->authorize('verify', $volunteerProfile);

        $volunteerProfile = $this->service->verify(
            $volunteerProfile,
            $request->user()
        );

        return static::success(
            data: new VolunteerProfileResource($volunteerProfile),
            message: 'volunteer.verified'
        );
    }
    /**
     * Reject a volunteer profile.
     *
     * @param Request $request
     * @param VolunteerProfile $volunteerProfile
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request, VolunteerProfile $volunteerProfile)
    {
        $this->authorize('verify', $volunteerProfile);

        $volunteerProfile = $this->service->reject(
            $volunteerProfile,
            $request->user()
        );

        return static::success(
            data: new VolunteerProfileResource($volunteerProfile),
            message: 'volunteer.rejected'
        );
    }
    /**
     * List pending volunteer profiles awaiting verification.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pending(Request $request)
    {
        $this->authorize('viewPending', VolunteerProfile::class);

        $profiles = $this->service->pending();

        return static::paginated(
            paginator: $profiles,
            message: 'volunteers.pending'
        );
    }
}
