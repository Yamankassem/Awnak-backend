<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Services\VolunteerProfileService;
use Modules\Volunteers\Transformers\VolunteerProfileResource;
use Modules\Volunteers\Http\Requests\UpdateVolunteerProfileRequest;

class VolunteerProfileController extends Controller
{
    public function __construct(
        private VolunteerProfileService $service
    ) {}

    public function show(Request $request)
    {
        $profile = $this->service->getByUser($request->user());

        $this->authorize('view', $profile);

        return static::success(
            data: new VolunteerProfileResource($profile)
        );
    }

    public function update(UpdateVolunteerProfileRequest $request)
    {
        $profile = $this->service->getByUser($request->user());

        $this->authorize('update', $profile);

        $profile = $this->service->update(
            $profile,
            $request->validated()
        );

        return static::success(
            data: new VolunteerProfileResource($profile),
            message: 'profile.updated'
        );
    }
    
}
