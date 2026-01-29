<?php

namespace Modules\Applications\Observers\FeedbacksObserver;

use Modules\Applications\Models\Feedback;
use Modules\Applications\Notifications\FeedbacksNotification\NewFeedbackNotification;

class FeedbackObserver
{
    /**
     * Handle the Feedback "created" event.
     */
    public function created (Feedback $feedback): void
    {
        $volunteer = $feedback->task->application->volunteer;
        $averageRating = Feedback::whereHas('task.application', function ($query) use ($volunteer){
            $query->where('volunteer_id', $volunteer->id);
        })->avg('rating');

        $volunteer->average_rating = round($averageRating, 1);
        $volunteer->save();

        $task = $feedback->task;
        $task->has_feedback = true;
        $task->save();

        if ($feedback->name_of_vol === $volunteer->name)
            $volunteer->notify(new NewFeedbackNotification($feedback));
    }
    
}
