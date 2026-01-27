<?php

namespace Modules\Applications\Observers\TaskHoursObserver;

use Exception;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Notifications\TaskHoursNotification\HoursLoggedNotification;

class TaskHourObserver
{
    /**
     * Handle the TaskHour "creating" event.
     */
    public function creating(TaskHour $taskHour): void 
    {
        $overlapping = TaskHour::where('task_id', $taskHour->task_id)
            ->where('volunteer_id', $taskHour->volunteer_id)
            ->where(function ($query) use ($taskHour){
                $query->whereBetween('started_date', [$taskHour->started_date, $taskHour->ended_date])
                      ->OnWhereBetween('ended_date', [$taskHour->started_date, $taskHour->ended_date]);
            })->exists();

       if ($overlapping)
        throw new Exception('هناك تداخل في تواريخ الساعات المسجلة');     
    }
   
    /**
     * Handle the TaskHour "created" event.
     */
    public function created(TaskHour $taskhour): void 
    {
        $task = $taskHour->task;
        $totalHours = $task->taskHours()->sum('hours');
        $task->save();

        $volunteer = $taskHour->volunteer;
        $volunteer->totalHours += $taskHour->hours;
        $volunteer->save();

        if ($task->application->coordinator)
            $task->application->coordinator->notify(new HoursLoggedNotification($taskHour));
        
    }
    
}
