<?

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Opportunity;

/**
 * Service: OpportunityService
 *
 * Encapsulates the business logic for managing opportunities,
 * including creation, updating, and deletion. By separating this
 * logic from the controller, we achieve cleaner code, improved
 * testability, and easier maintenance.
 */
class OpportunityService
{
    /**
     * Create a new opportunity.
     *
     * Accepts validated opportunity data including title, description,
     * organization_id, and optional skills. If skills are provided,
     * they are attached to the opportunity.
     *
     * @param array $data Validated opportunity data
     * @return Opportunity Newly created opportunity instance
     */
    public function create(array $data): Opportunity
    {
        $opportunity = Opportunity::create($data);

        // Attach skills if provided
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
     * Ensures that start_date is before end_date if both are provided.
     * Applies updates to the opportunity model.
     *
     * @param Opportunity $opportunity The opportunity instance to update
     * @param array $data Validated opportunity data
     * @return Opportunity Updated opportunity instance
     *
     * @throws \InvalidArgumentException If start_date is after end_date
     */
    public function update(Opportunity $opportunity, array $data): Opportunity
    {
        if (!empty($data['start_date']) && !empty($data['end_date']) && $data['start_date'] > $data['end_date']) {
            throw new \InvalidArgumentException('Start date must be before end date.');
        }

        $opportunity->update($data);

        return $opportunity;
    }

    /**
     * Delete an opportunity.
     *
     * Removes the opportunity record from the database.
     *
     * @param Opportunity $opportunity The opportunity instance to delete
     * @return bool True if deletion was successful
     */
    public function delete(Opportunity $opportunity): bool
    {
        return $opportunity->delete();
    }
}
