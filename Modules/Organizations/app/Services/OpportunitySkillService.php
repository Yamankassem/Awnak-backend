<?php

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Models\OpportunitySkill;

/**
 * Service: OpportunitySkillService
 *
 * This service class manages the link between opportunities and skill IDs.
 * Since the `skills` table is not yet available, the service stores `skill_id`
 * values directly in the `opportunity_skills` table without validating against
 * a skills model. This allows the API to accept and persist skill IDs as plain
 * integers until the skills module is integrated later.
 *
 * Methods:
 * - attachSkills: Add new skill IDs to an opportunity.
 * - detachSkills: Remove specific skill IDs from an opportunity.
 * - syncSkills: Replace all existing skill IDs with a new set.
 * - getSkills: Retrieve all skill IDs linked to an opportunity.
 *
 * This design keeps the API functional and avoids dependency errors,
 * while leaving room to reintroduce proper relationships once the
 * `skills` table is implemented.
 */
class OpportunitySkillService
{
    /**
     * Attach one or more skill IDs to an opportunity.
     *
     * @param Opportunity $opportunity
     * @param array $skillIds
     * @return void
     */
    public function attachSkills(Opportunity $opportunity, array $skillIds): void
    {
        foreach ($skillIds as $id) {
            OpportunitySkill::create([
                'opportunity_id' => $opportunity->id,
                'skill_id' => $id, // stored as plain integer
            ]);
        }
    }

    /**
     * Detach one or more skill IDs from an opportunity.
     *
     * @param Opportunity $opportunity
     * @param array $skillIds
     * @return void
     */
    public function detachSkills(Opportunity $opportunity, array $skillIds): void
    {
        OpportunitySkill::where('opportunity_id', $opportunity->id)
            ->whereIn('skill_id', $skillIds)
            ->delete();
    }

    /**
     * Sync skill IDs for an opportunity (replace existing with new set).
     *
     * @param Opportunity $opportunity
     * @param array $skillIds
     * @return void
     */
    public function syncSkills(Opportunity $opportunity, array $skillIds): void
    {
        // Remove old ones
        OpportunitySkill::where('opportunity_id', $opportunity->id)->delete();

        // Add new ones
        foreach ($skillIds as $id) {
            OpportunitySkill::create([
                'opportunity_id' => $opportunity->id,
                'skill_id' => $id,
            ]);
        }
    }

    /**
     * Get all skill IDs for a given opportunity.
     *
     * @param Opportunity $opportunity
     * @return \Illuminate\Support\Collection
     */
    public function getSkills(Opportunity $opportunity)
    {
        return OpportunitySkill::where('opportunity_id', $opportunity->id)->pluck('skill_id');
    }
}
