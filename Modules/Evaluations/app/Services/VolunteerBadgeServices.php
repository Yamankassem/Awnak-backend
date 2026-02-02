<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\VolunteerBadge;
use Illuminate\Support\Facades\Auth;
use Modules\Applications\Models\Task;
use Spatie\Activitylog\Facades\Activity;

class VolunteerBadgeServices
{
    
    protected BadgeServices $badgeServices;

        public function __construct(BadgeServices $badgeServices)
        {
            $this->badgeServices = $badgeServices;
        }
    /**
     * Award badge to volunteer
     */
    public function create(array $data): ?VolunteerBadge
    {
        $user = Auth::user();
       
        if (!$this->badgeServices->checkBadgeCondition($data['volunteer_id'], $data['badge_id'])) {
            return null;
        }

        $existing = VolunteerBadge::where('volunteer_id', $data['volunteer_id'])
                                  ->where('badge_id', $data['badge_id'])
                                  ->first();
        if ($existing) {
            return $existing;
        }
        $volunteerBadge = VolunteerBadge::create($data);
        Activity::create([
            'log_name'     => 'audit',
            'description'  => 'volunteer_badges.created',
            'subject_type' => VolunteerBadge::class,
            'subject_id'   => $volunteerBadge->id,
            'causer_type'  => get_class($user),
            'causer_id'    => $user->id,
            'properties'   => [
                'volunteer_id' => $volunteerBadge->volunteer_id,
                'badge_id'     => $volunteerBadge->badge_id,
                'awarded_by'   => $user->name,
            ],
        ]);

        return $volunteerBadge;
    }
    /**
     * Update awarded volunteer badge
     */
    public function update(VolunteerBadge $volunteerBadge, array $data): VolunteerBadge
    {
        $user = Auth::user();
        $volunteerBadge->update($data);
        Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'volunteer_badges.updated',
                            'subject_type' => VolunteerBadge::class,
                            'subject_id'   => $volunteerBadge->id,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'volunteer_id' => $volunteerBadge->volunteer_id,
                                                'badge_id' => $volunteerBadge->badge_id,
                                                'awarded_by' => $user->name,
                                              ],
                         ]);
        return $volunteerBadge;
    }

    /**
     * Get volunteer badge by id
     */
     public function getVolunteerBadgeById(int $id): VolunteerBadge
    {
        return VolunteerBadge::with(['volunteer', 'badge'])->findOrFail($id);
    }

    /**
     * Get all badges for all volunteer
     */
    public function getAll(int $perPage = 4)
    {
        return VolunteerBadge::with(['volunteer', 'badge'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Remove badge from volunteer
     */
    public function delete(VolunteerBadge $volunteerBadge): void
    {
        $user = Auth::user();
            $volunteerBadgeId   = $volunteerBadge->id;
            $volunteerBadge->delete();  
          Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'volunteer_badges.deleted',
                            'subject_type' => VolunteerBadge::class,
                            'subject_id'   => $volunteerBadgeId,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'volunteer_id' => $volunteerBadge->volunteer_id,
                                                'badge_id' => $volunteerBadge->badge_id,
                                                'awarded_by' => $user->name,
                                              ],
        ]);
    }



        
}    
