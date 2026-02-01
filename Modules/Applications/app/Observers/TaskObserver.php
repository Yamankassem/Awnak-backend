<?php

namespace Modules\Applications\Observers;

use Exception;
use Modules\Applications\Models\Task;
use Modules\Applications\Services\CacheService;
use Modules\Applications\Notifications\TasksNotification\TaskStatusChanged;
use Modules\Applications\Notifications\TasksNotification\NewTaskNotification;

/**
 * Task Observer
 * 
 * Handles task model events and triggers related actions.
 * 
 * @package Modules\Applications\Observers
 * @author Your Name
 */
class TaskObserver
{
    /**
     * Handle the Task "created" event.
     * 
     * @param Task $task
     * @return void
     */
    public function created(Task $task): void 
    {
        if ($task->application && $task->application->volunteer){
            $task->application->volunteer
              ->notify(new NewTaskNotification($task));
        }
        
        activity()
            ->performedOn($task)
            ->causedBy(auth()->user())
            ->log('A new task has been created');
    }

    /**
     * Handle the Task "updating" event.
     * 
     * @param Task $task
     * @return void
     */
    public function updating(Task $task): void 
    {
        if ($task->isDirty('status')) {
            $oldStatus = $task->getOriginal('status');
            
            if ($task->status === 'complete') 
                $task->update(['completed_at' => now()]);
            
            
            activity()
                ->performedOn($task)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $task->status
                ])
                ->log('The task status has been changed');
        }
    }

    /**
     * Handle the Task "updated" event.
     * 
     * @param Task $task
     * @return void
     */
    public function updated(Task $task): void 
    {
        app(CacheService::class)->clearTags([
        'tasks',
        'applications',
        'user_' . $task->application->volunteer_id,
        'user_' . $task->application->coordinator_id
        ]);
        
        if ($task->isDirty('status') && $task->status === 'complete'){
            $this->requestFeedbackFromVolunteer($task);
         
            $this->notifyCoordinatorOfCompletion($task);

            $this->updateVolunteerHours($task);
        }
    }
}
