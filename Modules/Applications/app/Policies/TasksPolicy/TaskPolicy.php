<?php

namespace Modules\Applications\Policies\TasksPolicy;

use Modules\Core\Models\User;
use Modules\Applications\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager', 'volunteer', 'evaluator']);
    }


    public function view(User $user, Task $task):bool 
    {
        if ($user->role === 'admin')
        return true;

        if ($user->role === 'volunteer'){
        return $task->application &&
               $task->application->volunteer_id === $user->id;
        }

        if ($user->role === 'coordinator'){
        return $task->application &&
               $task->application->coordinator_id === $user->id;
        }

        if ($user->role === 'opportunity_manager'){
        return $task->application &&
               $task->application->opportunity && 
               $task->application->opportunity->created_by  === $user->id;
        }

        if ($user->role === 'organization_admin'){
        return $task->application &&
               $task->application->opportunity && 
               $task->application->opportunity->organization_id  === $user->organization_id;
        }

        if ($user->role === 'evaluator')
        return true;

        return false;
    }


    public function create(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager']);
    }


    public function update(User $user, Task $task):bool 
    {
        if ($user->role === 'admin')
        return true;

        if ($user->role === 'coordinator')
        return $task->application &&
               $task->application->coordinator_id === $user->id;
        
        if ($user->role === 'opportunity_manager'){
        return $task->application &&
               $task->application->opportunity && 
               $task->application->opportunity->created_by  === $user->id;
        }

        return false;
    }


     public function delete(User $user, Task $task):bool 
    {
        return $user->role === 'admin';
    }


    public function changeStatus(User $user, Task $task):bool 
    {
        if ($user->role === 'admin')
        return true;

        if ($user->role === 'coordinator')
        return $task->application->coordinator_id === $user->id;

        if ($user->role === 'opportunity_manager'){
        return $task->application && 
               $task->application->opportunity->created_by === $user->id;
        }

        if ($user->role === 'volunteer'){
        return $task->application && 
               $task->application->volunteer_id === $user->id;
        }

        return false;
    }


    public function logHours(User $user, Task $task):bool 
    {
        if ($user->role === 'volunteer')
        return $task->application &&
               $task->application->volunteer_id === $user->id;
      
        if ($user->role === 'coordinator')
        return $task->application &&
               $task->application->coordinator_id === $user->id;
               
        if ($user->role === 'admin')
        return true;
       
        return false;
    }


    public function addFeedback(User $user, Task $task):bool 
    {
        if ($user->role === 'volunteer'){
        return $task->application &&
               $task->application->volunteer_id === $user->id &&
               $task->status === 'complete';
        }

        if ($user->role === 'coordinator'){
        return $task->application &&
               $task->application->coordinator_id === $user->id;
        }

        if ($user->role === 'organization_admin'){
        return $task->application &&
               $task->application->opportunity && 
               $task->application->opportunity->organization_id  === $user->organization_id;
        }

        if ($user->role === 'admin')
        return true;

        return false;
    }


    public function viewHours(User $user, Task $task):bool 
    {
        return $this->view($user, $task);
    }

     public function viewFeedbacks(User $user, Task $task):bool 
    {
        if (in_array($user->role, ['admin', 'evaluator']))
            return true;

        if ($user->role === 'volunteer'){
        return $task->application &&
               $task->application->volunteer_id === $user->id;
        }

        if ($user->role === 'coordinator'){
        return $task->application &&
               $task->application->coordinator_id === $user->id;
        }

        if ($user->role === 'organization_admin'){
        return $task->application &&
               $task->application->opportunity && 
               $task->application->opportunity->organization_id  === $user->organization_id;
        }

        return false;
    }
}
