<?php

namespace Modules\Organizations\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Organizations\Models\Opportunity;
use Modules\Applications\Events\ApplicationAssignedToOpportunity;

class AttachApplicationToOportunity
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ApplicationAssignedToOpportunity $event): void {
        $opportunity = Opportunity ::find($event->application->opportunity_id);

        if(!$opportunity) {
            return;
        }
        
        $opportunity->applications()->attach($event->application->id);
    }
}
