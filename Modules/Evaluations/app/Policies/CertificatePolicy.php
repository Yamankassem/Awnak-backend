<?php

namespace Modules\Evaluations\Policies;

use Modules\Core\Models\User;
use Modules\Evaluations\Models\Certificate;

class CertificatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('system-admin')
            || $user->hasRole('performance-auditor')
            || $user->hasRole('volunteer');
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user,  Certificate $certificate): bool
    {
        if ($user->hasRole('system-admin') || $user->hasRole('performance-auditor')) {
            return true;
        }

        return optional($certificate->task)
            ->application
            ->volunteer_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
   public function create(User $user): bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('performance-auditor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Certificate $certificate):bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('performance-auditor');
    }

    /**
     * Delete Certificate
     */
    public function delete(User $user, Certificate $certificate):bool
    {
        return $user->hasRole('system-admin')|| $user->hasRole('performance-auditor');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user,  Certificate $certificate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user,  Certificate $certificate): bool
    {
        return false;
    }
}
