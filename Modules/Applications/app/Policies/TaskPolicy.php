<?php

namespace Modules\Applications\Policies;

use Modules\Core\Models\User;
use Modules\Applications\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Task Policy
 * 
 * Defines authorization rules for task operations.
 * 
 * @package Modules\Applications\Policies
 * @author Your Name
 */
class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tasks.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager', 'volunteer', 'evaluator']);
    }
       
    /**
     * Determine whether the user can view the task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */
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

    /**
     * Determine whether the user can create task.
     * 
     * @param User $user
     * @return bool
     */   
    public function create(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager']);
    }


    /**
     * Determine whether the user can update task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */
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


    /**
     * Determine whether the user can delete task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */
     public function delete(User $user, Task $task):bool 
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can change status the task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */
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
    
    /**
     * Determine whether the user can log hours for the task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */
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

    /**
     * Determine whether the user can add feedback for task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */   
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

    /**
     * Determine whether the user can view hours the task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function viewHours(User $user, Task $task):bool 
    {
        return $this->view($user, $task);
    }

    /**
     * Determine whether the user can view feedbacks the task.
     * 
     * @param User $user
     * @param Task $task
     * @return bool
     */
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
