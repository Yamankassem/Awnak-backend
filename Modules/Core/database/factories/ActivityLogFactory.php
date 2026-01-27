<?php

namespace Modules\Core\Database\Factories;

use Modules\Core\Models\User;
use Spatie\Activitylog\Models\Activity;

/**
 * Class ActivityLogFactory
 *
 * Factory helper for generating Spatie activity logs
 * for testing and seeding purposes.
 */
class ActivityLogFactory
{
    /**
     * Create an activity log entry.
     *
     * @param User   $causer
     * @param string $logName
     * @param string $description
     * @param array  $properties
     *
     * @return Activity
     */
    public static function create(
        User $causer,
        string $logName = 'core',
        string $description = 'Test activity log',
        array $properties = []
    ): Activity {
        return activity($logName)
            ->causedBy($causer)
            ->withProperties($properties)
            ->log($description);
    }
}
