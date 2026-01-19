<?php

namespace Modules\Core\Listeners;

use Modules\Core\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Applications\Events\ApplicationAssignedToCoordinator;

class AttachApplicationToCoordinator
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ApplicationAssignedToCoordinator $event): void {
        $coordinator = User ::find($event->application->coordinator_id);

        if(!$coordinator) {
            return;
        }
        
        $coordinator->applications()->attach($event->application->id);
    }
}
