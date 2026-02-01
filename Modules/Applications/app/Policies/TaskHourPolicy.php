<?php

namespace Modules\Applications\Policies;

use Modules\Core\Models\User;
use Modules\Applications\Models\TaskHour;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * TaskHour Policy
 * 
 * Defines authorization rules for task hour operations.
 * 
 * @package Modules\Applications\Policies
 * @author Your Name
 */
class TaskHourPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any task hours.
     * 
     * @param User $user
     * @return bool
     */
     public function viewAny(User $user):bool 
    {
        return in_array($user->role, ['super_admin', 'coordinator', 'opportunity_manager', 'evaluator']);
    }

    /**
     * Determine whether the user can view the taskHour.
     * 
     * @param User $user
     * @param TaskHour $taskHour
     * @return bool
     */
    public function view(User $user, TaskHour $taskHour):bool 
    {
        if ($user->role === 'super_admin')
        return true;

        if ($user->role === 'volunteer'){
        return $taskHour->task &&
               $taskHour->task->application &&
               $taskHour->task->application->volunteer_profile_id === $user->volunteer_profile->id;
        }

        if ($user->role === 'coordinator'){
        return $taskHour->task &&
               $taskHour->task->application &&
               $taskHour->task->application->coordinator_id === $user->id;
        }

        if ($user->role === 'opportunity_manager'){
        return $taskHour->task &&
               $taskHour->task->application &&
               $taskHour->task->application->opportunity && 
               $taskHour->task->application->opportunity->created_by  === $user->id;
        }

        if ($user->role === 'organization_admin'){
        return $taskHour->task &&
               $taskHour->task->application &&
               $taskHour->task->application->opportunity && 
               $taskHour->task->application->opportunity->organization_id  === $user->organization_id;
        }

        if ($user->role === 'evaluator')
        return true;

        return false;
    }

    /**
     * Determine whether the user can create task hours.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user):bool 
    {
         if ($user->role ==='super_admin')
            return true;

         if ($user->role ==='volunteer')
            return true;

         if ($user->role ==='coordinator')
            return true;

         return false;
    }

    /**
     * Determine whether the user can update task hours.
     * 
     * @param User $user
     * @param TaskHour $taskHour
     * @return bool
     */
    public function update(User $user, TaskHour $taskHour):bool 
    {
        if ($user->role === 'super_admin')
        return true;

        if ($taskHour->created_by === $user->id)
        return true;

        if ($user->role === 'coordinator'){
        return $taskHour->task &&
               $taskHour->task->application && 
               $taskHour->task->application->coordinator_id  === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete task hours.
     * 
     * @param User $user
     * @param TaskHour $taskHour
     * @return bool
     */
     public function delete(User $user, TaskHour $taskHour):bool 
    {
        if ($user->role === 'super_admin')
            return true;

        if ($taskHour->created_by === $user->id)
            return true;

        if ($user->role === 'coodinator') {
            return $taskHour->task &&
                   $taskHour->task->application && 
                   $taskHour->task->application->coordinator_id  === $user->id;
        }

        return false;
    }
}
