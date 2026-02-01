<?php

namespace Modules\Volunteers\Traits;

use Modules\Core\Models\User;

trait LogsVolunteerActivity
{
    protected function log(
        string $description,
        $subject,
        User $actor,
        array $properties = []
    ): void {
        activity('volunteer')
            ->performedOn($subject)
            ->causedBy($actor)
            ->withProperties($properties)
            ->log($description);
    }
}
