<?php

namespace Modules\Organizations\Policies;

use Modules\Core\Models\User;
use Modules\Organizations\Models\Opportunity;

/**
 * Policy: OpportunityPolicy
 *
 * Defines authorization rules for Opportunity actions.
 * Uses role checks and permission checks (via Spatie) to determine
 * whether a user can view, create, update, or delete opportunities.
 *
 * Methods:
 * - view(): Anyone can view opportunities.
 * - create(): Restricted to system-admin or users with create permissions.
 * - update(): Restricted to system-admin or users with update permissions.
 * - delete(): Restricted to system-admin or users with delete permissions.
 */
class OpportunityPolicy
{
    /**
     * Determine whether the user can view the opportunity.
     *
     * Input:
     * - User $user: the authenticated user
     * - Opportunity $opportunity: the opportunity instance
     *
     * Output:
     * - bool: true (all users can view opportunities)
     *
     * @param User $user
     * @param Opportunity $opportunity
     * @return bool
     */
    public function view(User $user, Opportunity $opportunity): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create an opportunity.
     *
     * Input:
     * - User $user: the authenticated user
     *
     * Output:
     * - bool: true if user is system-admin OR has organization/opportunity create permission
     *
     * @param User $user
     * @return bool
     */

    public function create(User $user): bool
    {
        return $user->hasRole('system-admin')
            || $user->can('organization.opportunities.create')
            || $user->can('opportunities.create');
    }

    /**
     * Determine whether the user can update the opportunity.
     *
     * Input:
     * - User $user: the authenticated user
     * - Opportunity $opportunity: the opportunity instance
     *
     * Output:
     * - bool: true if user is system-admin OR has organization/opportunity update permission
     *
     * @param User $user
     * @param Opportunity $opportunity
     * @return bool
     */

    public function update(User $user, Opportunity $opportunity): bool
    {
        return $user->hasRole('system-admin')
            || $user->can('organization.opportunities.update')
            || $user->can('opportunities.update.own');
    }
    
    /**
     * Determine whether the user can delete the opportunity.
     *
     * Input:
     * - User $user: the authenticated user
     * - Opportunity $opportunity: the opportunity instance
     *
     * Output:
     * - bool: true if user is system-admin OR has organization/opportunity delete permission
     *
     * @param User $user
     * @param Opportunity $opportunity
     * @return bool
     */

    public function delete(User $user, Opportunity $opportunity): bool
    {
        return $user->hasRole('system-admin')
            || $user->can('organization.opportunities.delete')
            || $user->can('opportunities.delete.own');
    }
}
