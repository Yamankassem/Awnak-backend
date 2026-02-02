<?php

namespace Modules\Volunteers\Providers;



use Modules\Core\Models\Media;
use Modules\Volunteers\Policies\MediaPolicy;
use Modules\Volunteers\Models\VolunteerSkill;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerInterest;
use Modules\Volunteers\Models\VolunteerAvailability;
use Modules\Volunteers\Policies\VolunteerSkillPolicy;
use Modules\Volunteers\Policies\VolunteerProfilePolicy;
use Modules\Volunteers\Policies\VolunteerInterestPolicy;
use Modules\Volunteers\Policies\VolunteerAvailabilityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        VolunteerProfile::class => VolunteerProfilePolicy::class,
        VolunteerAvailability::class => VolunteerAvailabilityPolicy::class,
        VolunteerSkill::class => VolunteerSkillPolicy::class,
        VolunteerInterest::class => VolunteerInterestPolicy::class,
        Media::class => MediaPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}