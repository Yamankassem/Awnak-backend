<?php

namespace Modules\Applications\Policies\FeedbacksPolicy;

use Modules\Core\Models\User;
use Modules\Applications\Models\Feedback;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager', 'evaluator']);
    }


    public function view(User $user, Feedback $feedback):bool 
    {
        if ($user->role === 'admin')
        return true;

        if ($user->role === 'volunteer'){
        return $feedback->task &&
               $feedback->task->application &&
               $feedback->task->application->volunteer_id === $user->id;
        }

        if ($user->role === 'coordinator'){
        return $feedback->task &&
               $feedback->task->application &&
               $feedback->task->application->coordinator_id === $user->id;
        }

        if ($user->role === 'opportunity_manager'){
        return $feedback->task &&
               $feedback->task->application &&
               $feedback->task->application->opportunity && 
               $feedback->task->application->opportunity->created_by  === $user->id;
        }

        if ($user->role === 'organization_admin'){
        return $feedback->task &&
               $feedback->task->application &&
               $feedback->task->application->opportunity && 
               $feedback->task->application->opportunity->organization_id  === $user->organization_id;
        }

        if ($user->role === 'evaluator')
        return true;

        return false;
    }


    public function create(User $user):bool 
    {
         if ($user->role === 'admin')
            return true;

         if ($user->role === 'volunteer')
            return true;

         if ($user->role === 'coordinator')
            return true;

         if ($user->role === 'organization_admin')
            return true;

         return false;
    }


    public function update(User $user, Feedback $feedback):bool 
    {
        if ($user->role === 'admin')
        return true;

        if ($feedback->created_by === $user->id)
        return true;

        if ($user->role === 'coordinator'){
        return $feedback->task &&
               $feedback->task->application &&
               $feedback->task->application->coordinator_id === $user->id;
        }

        return false;
    }


     public function delete(User $user, Feedback $feedback):bool 
    {
        if ($user->role === 'admin')
            return true;


         if ($feedback->created_by === $user->id)
        return true;


         if ($user->role === 'coordinator'){
        return $feedback->task &&
               $feedback->task->application &&
               $feedback->task->application->coordinator_id === $user->id;
        }

        return false;
    }


    public function viewOwn (User $user, Feedback $feedback): bool
    {
        return $feedback->created_by === $user->id;
    }


    public function viewOthers(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'evaluator']);
    }


    public function verify(User $user, Feedback $feedback):bool 
    {
        if ($user->role === 'admin')
            return true;


         if ($user->role === 'coordinator'){
        return $feedback->task &&
               $feedback->task->application &&
               $feedback->task->application->coordinator_id === $user->id;
        }

         if ($user->role === 'evaluator')
            return true;

        return false;
    }


    public function viewStatistics(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'evaluator']);
    }


    public function viewAverageRatings(User $user):bool 
    {
        return true;
    }

    public function export(User $user):bool 
    {
        return in_array($user->role, ['admin', 'coordinator', 'evaluator']);
    }
}
