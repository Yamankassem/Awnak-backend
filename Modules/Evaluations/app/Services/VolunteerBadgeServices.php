<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\VolunteerBadge;
use Illuminate\Support\Facades\Auth;

class VolunteerBadgeServices
{
    /**
     * Award badge to volunteer
     */
    public function createBadge(array $data): VolunteerBadge
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }

        // if (!Auth::user()->hasAnyRole(['admin', 'coordinator'])) {
        //     throw new \Exception('Unauthorized', 403);
        // }

        $data['awarded_by'] = Auth::id();
        $data['awarded_at'] = now();

        return VolunteerBadge::create($data);
    }

    /**
     * Update awarded badge
     */
    public function updateBadge(VolunteerBadge $volunteerBadge, array $data): VolunteerBadge
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }
        // if (!Auth::user()->hasRole('admin')) {
        //     throw new \Exception('Unauthorized', 403);
        // }
        $volunteerBadge->update($data);
        return $volunteerBadge;
    }

    /**
     * Get volunteer badge by id
     */
    public function getById(int $id): VolunteerBadge
    {
        return VolunteerBadge::findOrFail($id);
    }

    /**
     * Get all badges for a volunteer
     */
    public function getByVolunteer( $volunteerId)
    {
        return VolunteerBadge::where('volunteer_id', $volunteerId)
            ->latest()
            ->get();
    }

    /**
     * Remove badge from volunteer
     */
    public function removeBadge(VolunteerBadge $volunteerBadge): bool
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }

        // if (!Auth::user()->hasRole('admin')) {
        //     throw new \Exception('Unauthorized', 403);
        // }

        $volunteerBadge->delete();
        return true;
    }
}
