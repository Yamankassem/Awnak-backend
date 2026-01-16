<?php

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Organization;

/**
 * Service: OrganizationService
 *
 * This service class encapsulates the business logic related to
 * creating and managing organizations. By separating this logic
 * from the controller, we achieve cleaner code, better testability,
 * and easier maintenance.
 */
class OrganizationService
{
    /**
     * Create a new organization.
     *
     * @param array $data Validated organization data
     * @return Organization Newly created organization instance
     */
    public function create(array $data): Organization
    {
        // Example of additional business logic:
        // Automatically generate a license number if not provided
        if (empty($data['license_number'])) {
            $data['license_number'] = strtoupper(uniqid('ORG-'));
        }

        // Normalize website URL (ensure it starts with http/https)
        if (!empty($data['website']) && !preg_match('/^https?:\/\//', $data['website'])) {
            $data['website'] = 'https://' . $data['website'];
        }

        // Create and return the organization
        return Organization::create($data);
    }

    /**
     * Update an existing organization.
     *
     * @param Organization $organization The organization instance to update
     * @param array $data Validated organization data
     * @return Organization Updated organization instance
     */
    public function update(Organization $organization, array $data): Organization
    {
        // Normalize website URL if provided
        if (!empty($data['website']) && !preg_match('/^https?:\/\//', $data['website'])) {
            $data['website'] = 'https://' . $data['website'];
        }

        // Apply updates to the organization model
        $organization->update($data);

        return $organization;
    }

    /**
     * Delete an organization.
     *
     * @param Organization $organization The organization instance to delete
     * @return bool True if deletion was successful
     */
    public function delete(Organization $organization): bool
    {
        // Perform deletion and return the result
        return $organization->delete();
    }
}
