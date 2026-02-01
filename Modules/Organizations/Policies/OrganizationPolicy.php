<?php

namespace Modules\Organizations\Policies;

use Modules\Core\Models\User;
use Modules\Organizations\Models\Organization;

/**
 * Policy: OrganizationPolicy
 *
 * Defines authorization rules for Organization actions.
 * - Any authenticated user can create an organization.
 * - Only system-admin or the organization owner can update or delete.
 * - All users can view organizations.
 * - Only system-admin can update organization status.
 */
class OrganizationPolicy
{
    /**
     * Determine whether the user can create an organization.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create
        return $user !== null;
    }

    /**
     * Determine whether the user can update the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return bool
     */
    public function update(User $user, Organization $organization): bool
    {
        return $user->hasRole('system-admin')
            || $organization->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return bool
     */
    public function delete(User $user, Organization $organization): bool
    {
        return $user->hasRole('system-admin')
            || $organization->user_id === $user->id;
    }

    /**
     * Determine whether the user can view the organization.
     *
     * @param User $user
     * @param Organization $organization
     * @return bool
     */
    public function view(User $user, Organization $organization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the organization's status.
     *
     * @param User $user
     * @param Organization $organization
     * @return bool
     */
    public function updateStatus(User $user, Organization $organization): bool
    {
        return $user->hasRole('system-admin');
    }
}
