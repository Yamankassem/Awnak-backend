<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Modules\Core\Models\Media;

class MediaPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Media $media): bool
    {
        return
            $user->hasRole('system-admin') ||
            (
                $media->model_type === \Modules\Volunteers\Models\VolunteerProfile::class &&
                $media->model->user_id === $user->id
            );
    }
}
