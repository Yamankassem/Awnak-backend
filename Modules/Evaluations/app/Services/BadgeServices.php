<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\Badge;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;


class BadgeServices
{
    /**
     * Get all badges
     */
    public function getAllBadges(int $perPage = 4)
    {
          return Badge::query()->paginate($perPage);
    }
    
    /**
     * Get badge by id
     */
    public function getBadgeById( $id): Badge
    {
        return Badge::findOrFail($id);
    }

    /**
     * Create a new badge
     */
    public function createBadge(array $data): Badge
    {
        $user = Auth::user();
        $badge= Badge::create($data );
         Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'badges.created',
                            'subject_type' => Badge::class,
                            'subject_id'   => $badge->id,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'badge_name' => $badge->name,
                                                'created_by' => $user->name,
                                              ],
                         ]);
        return $badge;
    }

    /**
     * Update badge
     */
    public function updateBadge(Badge $badge, array $data): Badge
    {
        $user = Auth::user();
        $badge->update($data);
        Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'badges.updated',
                            'subject_type' => Badge::class,
                            'subject_id'   => $badge->id,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'badge_name' => $badge->name,
                                                'updated_by' => $user->name,
                                              ],
                         ]);
        return $badge;
    }

    /**
     * Delete badge
     */
    public function deleteBadge(Badge $badge): void
    {
            $user = Auth::user();
            $badgeId   = $badge->id;
            $badgeName = $badge->name;
            $badge->delete();  
          Activity::create([
                            'log_name'     => 'audit',
                            'description'  => 'badges.deleted',
                            'subject_type' => Badge::class,
                            'subject_id'   => $badgeId,
                            'causer_type'  => get_class($user),
                            'causer_id'    => $user->id,
                            'properties'   => [
                                                'badge_name' => $badgeName,
                                                'deleted_by' => $user->name,
                                              ],
        ]);
    }

}
