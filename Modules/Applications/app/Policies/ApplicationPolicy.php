<?php

namespace Modules\Applications\Policies;

use Modules\Core\Models\User;
use Modules\Applications\Models\Application;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Application Policy
 * 
 * Defines authorization rules for application operations.
 * 
 * @package Modules\Applications\Policies
 * @author Your Name
 */
class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any applications.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager','volunteer', 'organization_admin', 'evaluator']);
    }

    /**
     * Determine whether the user can view the application.
     * 
     * @param User $user
     * @param Application $application
     * @return bool
     */
    public function view(User $user, Application $application): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'volunteer') {
            return $application->volunteer_profile_id === $user->id;
        }

        if ($user->role === 'coordinator') {
            return $application->coordinator_id === $user->id;
        }
        if ($user->role === 'opportunity_manager') {
            return $application->opportunity &&
                   $application->opportunity->created_by === $user->id;
        }

        if ($user->role === 'organization_admin') {
            return $application->opportunity &&
                   $application->opportunity->organization_id === $user->organization_id;
        }

        if ($user->role === 'evaluator') {
            return true;
        }

        return false;
    }


    /**
     * Determine whether the user can create applications.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->role === 'volunteer';
    }


    /**
     * Determine whether the user can update applications.
     * 
     * @param User $user
     * @param Application $application
     * @return bool
     */
    public function update(User $user, Application $application): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'coordinator') {
            return $application->coordinator_id === $user->id;
        }

        if ($user->role === 'volunteer') {
            return $application->volunteer_profile_id === $user->id &&
                   $application->status === 'pending';
        }

        if ($user->role === 'opportunity_manager') {
            return $application->opportunity &&
                   $application->opportunity->created_by === $user->id;
        }
        return false;
    }


    /**
     * Determine whether the user can delete applications.
     * 
     * @param User $user
     * @param Application $application
     * @return bool
     */
    public function delete(User $user, Application $application): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can change status applications.
     * 
     * @param User $user
     * @param Application $application
     * @return bool
     */
    public function changeStatus(User $user, Application $application): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        

        if ($user->role === 'opportunity_manager') {
            return $application->opportunity &&
                   $application->opportunity->created_by === $user->id;
        }

        if ($user->role === 'organization_admin') {
            return $application->opportunity &&
                   $application->opportunity->organization_id === $user->organization_id;
        }

        if ($user->role === 'coordinator') 
            return $application->coordinator_id === $user->id;

        return false;
    }

    /**
     * Determine whether the user can assign coordinator applications.
     * 
     * @param User $user
     * @param Application $application
     * @return bool
     */
    public function assignCoordinator(User $user, Application $application): bool
    {
        return in_array($user->role, [
            'admin',
            'opportunity_manager',
            'organization_admin'
        ]);
    }
}
