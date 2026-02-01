<?php

namespace Modules\Applications\Observers;

use Illuminate\Support\Facades\Log;
use Modules\Applications\Models\Application;
use Modules\Applications\Services\CacheService;
use Modules\Applications\Notifications\ApplicationsNotification\ApplicationStatusChanged;
use Modules\Applications\Notifications\ApplicationsNotification\NewApplicationNotification;

/**
 * Application Observer
 * 
 * Handles application model events and triggers related actions.
 * 
 * @package Modules\Applications\Observers
 * @author Your Name
 */
class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     * 
     * @param Application $application
     * @return void
     */
    public function created(Application $application): void 
    {
        app(CacheService::class)->clearTags([
        'applications',
        'dashboard',
        'user_' . $application->volunteer_profile_id,
        'user_' . $application->coordinator_id
         ]);

        if ($application->opportunity && $application->opportunity->createdBy) {
            $application->opportunity->createdBy
              ->notify(new NewApplicationNotification($application));
        }

        if ($application->coordinator) {
            $application->coordinator
              ->notify(new NewApplicationNotification($application));
        }

        activity()
           ->performedOn($application)
           ->causedBy($application->volunteer)
           ->withProperties(['status' => 'pending'])
           ->log('A new volunteer application has been submitted');
    }

    /**
     * Handle the Application "updated" event.
     * 
     * @param Application $application
     * @return void
     */
    public function updated(Application $application): void
    {
        app(CacheService::class)->clearTags([
        'applications',
        'dashboard',
        'user_' . $application->volunteer_profile_id,
        'user_' . $application->coordinator_id
        ]);
        
        if ($application->isDirty('status')) {
            $oldStatus = $application->getOriginal('status');
            $newStatus = $application->status;
        
            if (($oldStatus === 'approved' && $newStatus !== 'approved') || 
                ($oldStatus !== 'approved' && $newStatus === 'approved')) {
                $this->manageWaitingList($application);
            }
        }
    }

    /**
     * Handle the Application "manage waiting list" event.
     * 
     * @param Application $application
     * @return void
     */
    private function manageWaitingList(Application $application): void
    {
        $opportunity = $application->opportunity;

        if (!$opportunity) return;
        $maxVolunteers = $opportunity->max_volunteers ?? 999; 

        $currentApproved = $opportunity->applications()
            ->where('status', 'approved')
            ->count();
            
        if ($currentApproved < $maxVolunteers) {
            $waitingApplications = $opportunity->applications()
                ->where('status', 'waiting_list')
                ->orderBy('created_at', 'asc')
                ->get();
        
            $spotsAvailable = $maxVolunteers - $currentApproved;
        
           foreach ($waitingApplications as $waitingApp) {
                if ($spotsAvailable <= 0) break;
            
                $waitingApp->update(['status' => 'approved']);
                $spotsAvailable--;
            }
        }
    }

    /**
     * Handle the Application "deleted" event.
     * 
     * @param Application $application
     * @return void
     */
    public function deleted(Application $application): void 
    {
        $application->tasks()->delete();

        Log::warning("The request {$application->id} was deleted by the user" . auth()->id());
        
        activity()
            ->performedOn($application)
            ->causedBy(auth()->user())
            ->log('The request has been deleted');
    }
    
    
}