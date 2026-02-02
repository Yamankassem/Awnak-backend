<?php

namespace Modules\Applications\Observers;

use Exception;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Notifications\HoursLoggedNotification;

/**
 * TaskHour Observer
 * 
 * Handles taskHour model events and triggers related actions.
 * 
 * @package Modules\Applications\Observers
 * @author Your Name
 */
class TaskHourObserver
{
    /**
     * Handle the TaskHour "creating" event.
     * 
     * @param TaskHour $taskHour
     * @return void
     */
    public function creating(TaskHour $taskHour): void 
    {
        $overlapping = TaskHour::where('task_id', $taskHour->task_id)
            ->where(function($query) use ($taskHour) {
                $query->whereBetween('started_date', [$taskHour->started_date, $taskHour->ended_date])
                      ->orWhereBetween('ended_date', [$taskHour->started_date, $taskHour->ended_date])
                      ->orWhere(function($q) use ($taskHour) {
                          $q->where('started_date', '<=', $taskHour->started_date)
                            ->where('ended_date', '>=', $taskHour->ended_date);
                      });
            })
            ->exists();

        if ($overlapping) {
            throw new Exception('There is an overlap in the recorded hours dates');
        }
    
    }
   
    /**
     * Handle the TaskHour "created" event.
     * 
     * @param TaskHour $taskHour
     * @return void
     */
    public function created(TaskHour $taskHour): void 
    {
        if ($taskHour->task->application->volunteer) {
            $volunteer = $taskHour->task->application->volunteer;
            $volunteer->total_hours = $volunteer->taskHours()->sum('hours');
            $volunteer->save();
        }
        
        activity()
            ->performedOn($taskHour)
            ->causedBy(auth()->user())
            ->withProperties(['hours' => $taskHour->hours])
            ->log('The working hours have been recorded');
    }
}
