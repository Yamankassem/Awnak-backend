<?php

namespace Modules\Organizations\Services;

use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Models\Skill;

/**
 * Service: OpportunitySkillService
 *
 * This service class encapsulates the business logic related to
 * managing the relationship between opportunities and skills.
 * It provides methods to attach, detach, and sync skills for a given opportunity.
 */
class OpportunitySkillService
{
    /**
     * Attach one or more skills to an opportunity.
     *
     * @param Opportunity $opportunity The opportunity instance
     * @param array $skillIds Array of skill IDs to attach
     * @return void
     */
    public function attachSkills(Opportunity $opportunity, array $skillIds): void
    {
        // Attach skills to the opportunity (avoid duplicates automatically)
        $opportunity->skills()->attach($skillIds);
    }

    /**
     * Detach one or more skills from an opportunity.
     *
     * @param Opportunity $opportunity The opportunity instance
     * @param array $skillIds Array of skill IDs to detach
     * @return void
     */
    public function detachSkills(Opportunity $opportunity, array $skillIds): void
    {
        // Detach skills from the opportunity
        $opportunity->skills()->detach($skillIds);
    }

    /**
     * Sync skills for an opportunity (replace existing with new set).
     *
     * @param Opportunity $opportunity The opportunity instance
     * @param array $skillIds Array of skill IDs to sync
     * @return void
     */
    public function syncSkills(Opportunity $opportunity, array $skillIds): void
    {
        // Sync skills (remove old ones, add new ones)
        $opportunity->skills()->sync($skillIds);
    }

    /**
     * Get all skills for a given opportunity.
     *
     * @param Opportunity $opportunity The opportunity instance
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSkills(Opportunity $opportunity)
    {
        // Return all related skills
        return $opportunity->skills;
    }
}
