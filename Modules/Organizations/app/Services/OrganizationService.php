<?php

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Organization;

/**
 * Service: OrganizationService
 *
 * Encapsulates the business logic for managing organizations,
 * including creation, updating, and deletion. By separating this
 * logic from the controller, we achieve cleaner code, improved
 * testability, and easier maintenance.
 *
 * Features:
 * - Automatically generates a license number if not provided.
 * - Normalizes website URLs to ensure they start with http/https.
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
        if (empty($data['license_number'])) {
            $data['license_number'] = strtoupper(uniqid('ORG-'));
        }

        if (!empty($data['website']) && !preg_match('/^https?:\/\//', $data['website'])) {
            $data['website'] = 'https://' . $data['website'];
        }

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
        if (!empty($data['website']) && !preg_match('/^https?:\/\//', $data['website'])) {
            $data['website'] = 'https://' . $data['website'];
        }

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
        return $organization->delete();
    }
}
