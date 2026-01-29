<?php

namespace Modules\Applications\Policies\TaskHoursPolicy;

use Modules\Core\Models\User;
use Modules\Applications\Models\TaskHour;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskHourPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
     public function viewAny(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager', 'evaluator']);
    }


    public function view(User $user, TaskHour $taskHour):bool 
    {
        if ($user->role === 'admin')
        return true;

        if ($user->role === 'volunteer'){
        return $taskHour->task &&
               $taskHour->task->application &&
               $taskHour->task->application->volunteer_id === $user->id;
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


    public function create(User $user):bool 
    {
         if ($user->role ==='admin')
            return true;

         if ($user->role ==='volunteer')
            return true;

         if ($user->role ==='coordinator')
            return true;

         return false;
    }


    public function update(User $user, TaskHour $taskHour):bool 
    {
        if ($user->role === 'admin')
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


     public function delete(User $user, TaskHour $taskHour):bool 
    {
        if ($user->role === 'admin')
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


    public function verify(User $user, TaskHour $taskHour):bool 
    {
        if ($user->role === 'admin')
            return true;
        
        if ($user->role === 'coodinator') {
            return $taskHour->task &&
                   $taskHour->task->application && 
                   $taskHour->task->application->coordinator_id  === $user->id;
        }

        if ($user->role === 'organization_admin'){
        return $taskHour->task &&
               $taskHour->task->application &&
               $taskHour->task->application->opportunity && 
               $taskHour->task->application->opportunity->organization_id  === $user->organization_id;
        }

        return false;
    }
    
    public function viewReports(User $user, TaskHour $taskHour):bool 
    {
        return $this->view($user, $taskHour);
    }


    public function export(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'evaluator']);
    }
}
