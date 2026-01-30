<?php

namespace Modules\Applications\Observers;

use Log;
use Modules\Applications\Models\Feedback;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Applications\Notifications\NewFeedbackNotification;

/**
 * Feedback Observer
 * 
 * Handles feedback model events and triggers related actions.
 * 
 * @package Modules\Applications\Observers
 * @author Your Name
 */
class FeedbackObserver
{
    /**
     * Handle the Feedback "created" event.
     * 
     * @param Feedback $feedback
     * @return void
     */
    public function created(Feedback $feedback): void
    {
        if ($feedback->isPerformanceEvaluation() && $feedback->rating) {
            $this->updateVolunteerAverageRating($feedback);
        }
        
        activity()
            ->performedOn($feedback)
            ->causedBy(auth()->user())
            ->withProperties([
                'type' => $feedback->isPerformanceEvaluation() ? 'performance_evaluation' : 'task_review',
                'rating' => $feedback->rating,
                'volunteer' => $feedback->name_of_vol,
                'organization' => $feedback->name_of_org
            ])
            ->log($feedback->isPerformanceEvaluation() 
                ? 'Volunteer performance has been evaluated' 
                : 'A task evaluation has been added');
    }

    /**
     * Handle the Feedback "updated" event.
     * 
     * @param Feedback $feedback
     * @return void
     */
    public function updated(Feedback $feedback): void
    {
        if ($feedback->isPerformanceEvaluation() && $feedback->isDirty('rating')) {
            $this->updateVolunteerAverageRating($feedback);
            
            activity()
                ->performedOn($feedback)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_rating' => $feedback->getOriginal('rating'),
                    'new_rating' => $feedback->rating
                ])
                ->log('Volunteer performance evaluation has been updated');
        }
    }

    /**
     * Handle the Feedback "deleted" event.
     * 
     * @param Feedback $feedback
     * @return void
     */
    public function deleted(Feedback $feedback): void
    {
        if ($feedback->isPerformanceEvaluation()) {
            $this->updateVolunteerAverageRating($feedback, true);
        }
        
        activity()
            ->performedOn($feedback)
            ->causedBy(auth()->user())
            ->log('Evaluation has been deleted');
    }

    
    private function updateVolunteerAverageRating(Feedback $feedback, bool $isDeleted = false): void
    {
        Log::info('Update volunteer evaluation', [
            'volunteer_name' => $feedback->name_of_vol,
            'feedback_id' => $feedback->id,
            'rating' => $feedback->rating,
            'action' => $isDeleted ? 'deleted' : ($feedback->wasRecentlyCreated ? 'created' : 'updated')
        ]);
        
        $volunteer = VolunteerProfile::where('name', $feedback->name_of_vol)->first();
        if ($volunteer) {
            $average = Feedback::where('name_of_vol', $volunteer->name)
                ->whereNotNull('rating')
                ->avg('rating');
            
            $volunteer->update(['average_rating' => round($average, 1)]);
        }
    }
}