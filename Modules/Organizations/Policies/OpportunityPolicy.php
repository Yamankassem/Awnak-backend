<?php

namespace Modules\Organizations\Policies;

use Modules\Core\Models\User;
use Modules\Organizations\Models\Opportunity;

class OpportunityPolicy
{
    public function view(User $user, Opportunity $opportunity): bool
    {
        return true; // الكل يقدر يشوف أو حسب الحاجة
    }

    public function create(User $user): bool
    {
        return $user->role === 'SuperAdmin';
    }

    public function update(User $user, Opportunity $opportunity): bool
    {
        return $user->role === 'SuperAdmin' || $opportunity->organization->user_id === $user->id;
    }

    public function delete(User $user, Opportunity $opportunity): bool
    {
        return $user->role === 'SuperAdmin' || $opportunity->organization->user_id === $user->id;
    }
}
