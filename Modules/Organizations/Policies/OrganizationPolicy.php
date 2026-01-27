<?php

namespace Modules\Organizations\Policies;

use Modules\Core\Models\User; 
use Modules\Organizations\Models\Organization;

class OrganizationPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'SuperAdmin';
    }

    public function update(User $user, Organization $organization): bool
    {
        return $user->role === 'SuperAdmin' || $organization->user_id === $user->id;
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $user->role === 'SuperAdmin' || $organization->user_id === $user->id;
    }

    public function view(User $user, Organization $organization): bool
    {
        return true;
    }
}
