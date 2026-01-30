<?php

namespace Modules\Evaluations\Policies;

use Modules\Core\Models\User;
use Modules\Evaluations\Models\Evaluation;

class EvaluationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('volunteer-coordinator');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Evaluation $evaluation): bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('volunteer-coordinator');
    }

    /**
     * Determine whether the user can create models.
     */
   public function create(User $user): bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('volunteer-coordinator');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user,Evaluation $evaluation):bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('volunteer-coordinator');
    }

    /**
     * Delete badge
     */
    public function delete(User $user, Evaluation $evaluation):bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('volunteer-coordinator');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Evaluation $evaluation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Evaluation $evaluation): bool
    {
        return false;
    }
}
