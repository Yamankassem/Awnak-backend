<?php

namespace Modules\Evaluations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Evaluations\Http\Requests\VolunteerBadge\StoreVolunteerBadgeRequest;
use Modules\Evaluations\Http\Requests\VolunteerBadge\UpdateVolunteerBadgeRequest;
use Modules\Evaluations\Http\Resources\VolunteerBadgeResource;
use Modules\Evaluations\Http\Traits\ApiResponse;
use Modules\Evaluations\Models\VolunteerBadge;
use Modules\Evaluations\Services\VolunteerBadgeServices;

class VolunteerBadgeController extends Controller
{
    use ApiResponse;

    protected $volunteerBadgeService;

    public function __construct(VolunteerBadgeServices $volunteerBadgeService)
    {
        $this->volunteerBadgeService = $volunteerBadgeService;
    }

    // Display all badges for a volunteer
    public function index($volunteerId)
    {
        try {
            $badges = $this->volunteerBadgeService->getByVolunteer($volunteerId);

            return $this->successResponse(
                VolunteerBadgeResource::collection($badges),
                'Volunteer badges retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    // Show single volunteer badge
    public function show($id)
    {
        try {
            $badge = $this->volunteerBadgeService->getById($id);

            return $this->successResponse(
                new VolunteerBadgeResource($badge),
                'Volunteer badge retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Volunteer badge not found', 404);
        }
    }

    // Award badge to volunteer
    public function store(StoreVolunteerBadgeRequest $request)
    {
        try {
            $data = $request->validated();
            $data['awarded_by'] = Auth::id();

            $badge = $this->volunteerBadgeService->createBadge($data);

            return $this->successResponse(
                new VolunteerBadgeResource($badge),
                'Badge awarded successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    // Update volunteer badge (optional)
    public function update(UpdateVolunteerBadgeRequest $request, VolunteerBadge $volunteerBadge)
    {
        try {
            $updated = $this->volunteerBadgeService
                ->updateBadge($volunteerBadge, $request->validated());

            return $this->successResponse(
                new VolunteerBadgeResource($updated),
                'Volunteer badge updated successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }

    // Remove badge from volunteer
    public function destroy(VolunteerBadge $volunteerBadge)
    {
        try {
            $this->volunteerBadgeService->removeBadge($volunteerBadge);

            return $this->successResponse(
                null,
                'Volunteer badge removed successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
    }
}
