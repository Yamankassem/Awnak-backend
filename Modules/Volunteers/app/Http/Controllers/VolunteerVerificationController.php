<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Services\VolunteerVerificationService;
use Modules\Volunteers\Transformers\VolunteerProfileResource;

class VolunteerVerificationController extends Controller
{
    public function __construct(
        private VolunteerVerificationService $service
    ) {}

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
