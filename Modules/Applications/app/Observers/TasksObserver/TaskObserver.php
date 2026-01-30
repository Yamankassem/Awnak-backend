<?php

namespace Modules\Applications\Observers\TasksObserver;

use Exception;
use Modules\Applications\Models\Task;
use Modules\Applications\Notifications\TasksNotification\NewTaskNotification;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void 
    {
        if ($task->application && $task->application->volunteer){
            $task->application->volunteer
              ->notify(new NewTaskNotification($task));
        }

        if ($task->due_date){
            CalenderEvent::create([
                'user_id' => $task->application->volunteer_id,
                'title' => $task->title,
                'start_date' => $task->due_date,
                'description' => $task->description,
                'type' => 'task',
            ]);
        }
    }

    /**
     * Handle the Task "updating" event.
     */
    public function updating(Task $task): void 
    {
        if ($task->isDirty('status' && $task->status === 'complete'))
            $task->completed_at = now();
        
        if (!$task->taskHours()->exists())
            throw new Exception('يجب تسجيل ساعات العمل قبل إكمال المهمة');

    }


    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void 
    {
        if ($task->isDirty('status' && $task->status === 'complete')){
            $this->requestFeedbackFromVolunteer($task);
         
            $this->notifyCoordinatorOfCompletion($task);

            $this->updateVolunteerHours($task);
        }
    }
}
