<?php

namespace Modules\Applications\Policies;

use Modules\Core\Models\User;
use Modules\Applications\Models\Feedback;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Feedback Policy
 * 
 * Defines authorization rules for feedback operations.
 * 
 * @package Modules\Applications\Policies
 * @author Your Name
 */
class FeedbackPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any feedbacks.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user):bool 
    {
       return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager','volunteer', 'evaluator']);
    }

    /**
     * Determine whether the user can view the feedback.
     * 
     * @param User $user
     * @param Feedback $feedback
     * @return bool
     */
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

    /**
     * Determine whether the user can create feedbacks.
     * 
     * @param User $user
     * @param Feedback $feedback
     * @return bool
     */
    public function create(User $user):bool 
    {
       return in_array($user->role, ['admin', 'coordinator', 'opportunity_manager','volunteer']);
    }

    /**
     * Determine whether the user can update feedbacks.
     * 
     * @param User $user
     * @param Feedback $feedback
     * @return bool
     */
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

    /**
     * Determine whether the user can delete feedbacks.
     * 
     * @param User $user
     * @param Feedback $feedback
     * @return bool
     */
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

    /**
     * Determine whether the user can evaluate performance feedbacks.
     * 
     * @param User $user
     * @return bool
     */
    public function evaluatePerformance(User $user): bool
    {
        return in_array($user->role, [
            'admin',
            'coordinator',
            'opportunity_manager',
            'organization_admin'
        ]);
    }

    /**
     * Determine whether the user can review tasks feedbacks.
     * 
     * @param User $user
     * @return bool
     */
    public function reviewTasks(User $user): bool
    {
        return in_array($user->role, [
            'admin',
            'volunteer', 
            'coordinator',
            'opportunity_manager',
            'organization_admin'
        ]);
    }

    /**
     * Determine whether the user can view performance reports feedbacks.
     * 
     * @param User $user
     * @return bool
     */
    public function viewPerformanceReports(User $user): bool
    {
        return in_array($user->role, [
            'admin',
            'coordinator',
            'opportunity_manager',
            'organization_admin',
            'evaluator'
        ]);
    }
}
