<?php

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Opportunity;

/**
 * Service: OpportunityService
 *
 * This service class encapsulates the business logic related to
 * creating, updating, and deleting opportunities. By separating
 * this logic from the controller, we achieve cleaner code,
 * better testability, and easier maintenance.
 */
class OpportunityService
{
    /**
     * Create a new opportunity.
     *
     * @param array $data Validated opportunity data
     * @return Opportunity Newly created opportunity instance
     */
    public function create(array $data): Opportunity
    {
        $opportunity = Opportunity::create($data);

        // هون اذا مبعوتة المهارة ضيفها
        if (!empty($data['skills'])) {
            foreach ($data['skills'] as $skillData) {
                $opportunity->skills()->create($skillData);
            }
        }

        return $opportunity;
    }



    /**
     * Update an existing opportunity.
     *
     * @param Opportunity $opportunity The opportunity instance to update
     * @param array $data Validated opportunity data
     * @return Opportunity Updated opportunity instance
     */
    public function update(Opportunity $opportunity, array $data): Opportunity
    {
        // Ensure start_date is before end_date if provided
        if (!empty($data['start_date']) && !empty($data['end_date']) && $data['start_date'] > $data['end_date']) {
            throw new \InvalidArgumentException('Start date must be before end date.');
        }

        // Apply updates to the opportunity model
        $opportunity->update($data);

        return $opportunity;
    }

    /**
     * Delete an opportunity.
     *
     * @param Opportunity $opportunity The opportunity instance to delete
     * @return bool True if deletion was successful
     */
    public function delete(Opportunity $opportunity): bool
    {
        // Perform deletion and return the result
        return $opportunity->delete();
    }
}
