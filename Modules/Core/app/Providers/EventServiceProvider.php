<?php

namespace Modules\Core\Providers;

use Modules\Core\Listeners\AttachApplicationToCoordinator;
use Modules\Applications\Events\ApplicationAssignedToCoordinator;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        ApplicationAssignedToCoordinator::class => [
            AttachApplicationToCoordinator::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
