<?php

namespace Modules\Organizations\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Policies\OrganizationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
    \Modules\Organizations\Models\Organization::class => \Modules\Organizations\Policies\OrganizationPolicy::class,
    \Modules\Organizations\Models\Opportunity::class => \Modules\Organizations\Policies\OpportunityPolicy::class,
    \Modules\Organizations\Models\Document::class => \Modules\Organizations\Policies\DocumentPolicy::class,

    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
