<?php

namespace Modules\Volunteers\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Policies\VolunteerProfilePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        VolunteerProfile::class => VolunteerProfilePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}