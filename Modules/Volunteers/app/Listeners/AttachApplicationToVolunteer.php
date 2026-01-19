<?php

namespace Modules\Volunteers\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Applications\Events\ApplicationAssignedToVolunteer;

class AttachApplicationToVolunteer
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ApplicationAssignedToVolunteer $event): void 
    {
        $volunteer = VolunteerProfile ::find($event->application->volunteer_id);

        if(!$volunteer) {
            return;
        }
        
        $volunteer->applications()->attach($event->application->id);
    }
}
