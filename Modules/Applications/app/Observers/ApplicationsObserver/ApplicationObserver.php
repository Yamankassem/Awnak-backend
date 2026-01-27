<?php

namespace Modules\Applications\Observers\ApplicationsObserver;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Applications\Models\Application;
use Modules\Applications\Models\ArchivedApplication;
use Modules\Applications\Notifications\ApplicationsNotification\NewApplicationNotification;
use Modules\Applications\Notifications\ApplicationsNotification\ApplicationStatusChanged as ApplicationStatusChanged;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void 
    {
        if ($application->opportunity && $application->opportunity->createdBy){
            $application->opportunity->createdBy
              ->notify(new NewApplicationNotification($application));
        }

        if ($application->coordinator ){
            $application->coordinator
              ->notify(new NewApplicationNotification($application));
        }

        activity()
           ->performedOn($application)
           ->causedBy($application->volunteer)
           ->withProperties(['status' => 'pending'])
           ->log('تم تقديم طلب تطوع جديد ');
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void 
    {
        if ($application->isDirty('status')){
            $oldStatus = $application->getOriginal('status');
            $newStatus = $application->status;

            if ($application->volunteer && $application->volunteer->email){
                Mail::to($application->volunteer->email)
                  ->queue(new ApplicationStatusChanged($application, $oldStatus, $newStatus));
            }

            if ($newStatus === 'approved' && $oldStatus !== 'approved')
                $this->createDefaultTasks($application);

            if ($newStatus === 'rejected')
                $this->notifyRejection($application);
        }

    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void 
    {
        ArchivedApplication::create($application->toArray());

        $application->tasks()->delete();

        Log::warning("Application {$application->id} deleted by user" . auth()->id());
    }
}
