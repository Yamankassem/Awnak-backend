<?php

namespace Modules\Volunteers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Volunteers\Models\VolunteerInterest;
use Modules\Volunteers\Services\VolunteerInterestService;
use Modules\Volunteers\Http\Requests\VolunteerInterest\StoreVolunteerInterestRequest;
use Modules\Volunteers\Http\Requests\VolunteerInterest\UpdateVolunteerInterestRequest;

class VolunteerInterestController extends Controller
{
    public function __construct(private VolunteerInterestService $service) {}

    public function index(Request $request)
    {
        $profile = $request->user()->volunteerProfile;

        return static::success(
            data: $this->service->list($profile)
        );
    }

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

    public function update(UpdateVolunteerInterestRequest $request, VolunteerInterest $volunteerInterest)
    {
        $this->authorize('update', $volunteerInterest);

        return static::success(
            data: $this->service->update($volunteerInterest, $request->validated(),$request->user()),
            message: 'interest.updated'
        );
    }

    public function destroy(VolunteerInterest $volunteerInterest,Request $request)
    {
        $this->authorize('delete', $volunteerInterest);

        $this->service->delete($volunteerInterest,$request->user());

        return static::success(message: 'interest.deleted');
    }
}
