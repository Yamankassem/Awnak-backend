<?php

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Opportunity;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * Service: OpportunityService
 *
 * Handles business logic for Opportunity entities including
 * creation, updating, and deletion. Keeps controllers clean
 * and improves testability and maintainability.
 */

class OpportunityService
{
    /**
     * Create a new opportunity.
     *
     * Input:
     * - array $data: validated opportunity data including:
     *   - title, description, organization_id
     *   - location_id OR location array (lat/lng, name, type)
     *   - optional skills array
     *
     * Process:
     * - If location_id is provided, use existing location.
     * - If location array is provided, create a new Location with coordinates.
     * - Create Opportunity record.
     * - Attach skills if provided.
     *
     * Output:
     * - Opportunity: newly created instance with relationships ready to load.
     *
     * @param array $data Validated opportunity data
     * @return Opportunity Newly created opportunity instance
     */
    public function create(array $data): Opportunity
    {
        if (isset($data['location']) && is_array($data['location'])) {
            $locationData = $data['location'];

            if (isset($locationData['id'])) {
                $data['location_id'] = $locationData['id'];
            } else {

                $location = \Modules\Core\Models\Location::create([
                    'name'        => $locationData['name'] ?? null,
                    'type'        => $locationData['type'] ?? null,
                    'parent_id'   => $locationData['parent_id'] ?? null,
                    'coordinates' => new Point($locationData['lng'], $locationData['lat']),
                ]);
                $data['location_id'] = $location->id;
            }
        }

        $opportunity = Opportunity::create($data);

        // Attach skills if provided
        if (!empty($data['skills'])) {
            foreach ($data['skills'] as $skillData) {
                $opportunity->skills()->create($skillData);
            }
        }

        return $opportunity;
    }

    /** * Update an existing opportunity. *
     *  * Input: * - Opportunity $opportunity: the instance to update *
     *  - array $data: validated opportunity data *
     * * Process: * - Ensures start_date < end_date if both provided. *
     *  - Applies updates to the model. *
     * * Output: *
     *  - Opportunity: updated instance. *
     *  * @param Opportunity $opportunity The opportunity instance to update
     *  * @param array $data Validated opportunity data
     *  * @return Opportunity Updated opportunity instance *
     *  * @throws \InvalidArgumentException If start_date is after end_date
     * */

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
     * Input:
     * - Opportunity $opportunity: the instance to delete
     *
     * Process:
     * - Removes the record from the database.
     *
     * Output:
     * - bool: true if deletion was successful.
     *
     * @param Opportunity $opportunity The opportunity instance to delete
     * @return bool True if deletion was successful
     */
    public function delete(Opportunity $opportunity): bool
    {
        return $opportunity->delete();
    }
}
