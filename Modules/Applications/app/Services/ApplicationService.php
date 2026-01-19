<?php

namespace Modules\Applications\Services;

use Illuminate\Http\Request;
use Modules\Applications\Models\Application;
use Modules\Applications\Events\ApplicationAssignedToVolunteer;
use Modules\Applications\Events\ApplicationAssignedToCoordinator;
use Modules\Applications\Events\ApplicationAssignedToOpportunity;

class ApplicationService
{
    
    public function handleOpportunityAssignment(Request $request) 
    {
        $application = Application::create([
            'user_id' => auth->id(),
            'opportunity_id' => $request->opportunity_id,
        ]);

        event(new ApplicationAssignedToOpportunity($application));
    }


    public function handleVolunteerAssignment(Request $request)
    {
       $application = Application::create([
            'user_id' => auth->id(),
            'volunteer_id' => $request->volunteer_id,
        ]);

        event(new ApplicationAssignedToVolunteer($application));
    }


    public function handleCoordinatorAssignment(Application $application, $coordinatorId)
    {
       $application = Application::create([
            'user_id' => auth->id(),
            'coordinator_id' => $request->coordinator_id,
        ]);

        event(new ApplicationAssignedToCoordinator($application));
    }
}
