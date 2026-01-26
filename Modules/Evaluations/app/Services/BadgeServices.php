<?php

namespace Modules\Evaluations\Services;

use Modules\Evaluations\Models\Badge;
use Illuminate\Support\Facades\Auth;


class BadgeService
{
    /**
     * Get all badges
     */
    public function getAllBadges()
    {
        return Badge::orderBy('name')->get();
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
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }
        // if (!Auth::user()->hasRole('admin')) {
        //     throw new \Exception('Unauthorized', 403);
        // }
        return Badge::create([$data ]);
    }

    /**
     * Update badge
     */
    public function updateBadge(Badge $badge, array $data): Badge
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }
        // if (!Auth::user()->hasRole('admin')) {
        //     throw new \Exception('Unauthorized', 403);
        // }
        $badge->update([$data]);
        return $badge;
    }

    /**
     * Delete badge
     */
    public function deleteBadge(Badge $badge): void
    {
        if (!Auth::check()) {
            throw new \Exception('Unauthenticated', 401);
        }
        // if (!Auth::user()->hasRole('admin')) {
        //     throw new \Exception('Unauthorized', 403);
        // }
        $badge->delete();
    }
}
