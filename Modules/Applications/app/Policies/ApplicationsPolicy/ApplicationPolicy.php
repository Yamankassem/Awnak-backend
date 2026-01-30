<?php

namespace Modules\Applications\Policies\ApplicationsPolicy;

use Modules\Core\Models\User;
use Modules\Applications\Models\Application;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager', 'volunteer', 'evaluator']);
    }



    public function view(User $user, Application $application): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'volunteer') {
            return $application->volunteer_id === $user->id;
        }

        if ($user->role === 'coordinator') {
            return $application->coordinator_id === $user->id;
        }
        if ($user->role === 'opportunity_manager') {
            return $application->opportunity->created_by === $user->id;
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



    public function create(User $user): bool
    {
        return $user->role === 'volunteer';
    }



    public function update(User $user, Application $application): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'coordinator') {
            return $application->coordinator_id === $user->id;
        }

        if ($user->role === 'volunteer') {
            return $application->volunteer_id === $user->id &&
                   $application->status === 'pending';
        }

        if ($user->role === 'opportunity_manager') {
            return $application->opportunity &&
                   $application->opportunity->created_by === $user->id;
        }
        return false;
    }



    public function delete(User $user, Application $application): bool
    {
        return $user->role === 'admin';
    }


    public function changeStatus(User $user, Application $application): bool
    {
        if ($user->role === 'admin') {
            return true;
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

        return false;
    }


    public function assignCoordinator(User $user, Application $application): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'opportunity_manager') {
            return $application->opportunity &&
                   $application->opportunity->created_by === $user->id;
        }

        if ($user->role === 'coordinator') {
            return true;
        }

        return false;
    }

    public function viewReports(User $user, Application $application): bool
    {
        if (in_array($user->role, ['admin', 'evaluator'])) {
            return true;
        }

        if ($user->role === 'coordinator') {
            return $application->coordinator_id === $user->id;
        }

        if ($user->role === 'volunteer') {
            return $application->volunteer_id === $user->id;
        }


        return false;
    }

    public function viewTrashed(User $user): bool
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager']);
    }

    public function restore(User $user, Application $application): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Application $application): bool
    {
        return $user->role === 'admin';
    }

    public function viewArchived(User $user): bool
    {
        return $user->role === 'admin';
    }
}
