<?

namespace Modules\Organizations\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Organizations\Models\Opportunity as ModelsOpportunity;

/**
 * Event fired when multiple Skills are attached to an Opportunity.
 *
 * This event is dispatched whenever one or more skill IDs are linked
 * to a given Opportunity. It allows other modules (e.g., Skills, Notifications)
 * to react collectively to the new set of skills.
 */
class OpportunitySkillAttached
{
    use Dispatchable, SerializesModels;

    /**
     * The Opportunity instance.
     *
     * @var Opportunity
     */
    public $opportunity;

    /**
     * The array of skill IDs attached.
     *
     * @var array
     */
    public $skillIds;

    /**
     * Create a new event instance.
     *
     * @param ModelsOpportunity $opportunity The Opportunity model instance.
     * @param array $skillIds          The list of skill IDs attached.
     */
    public function __construct(ModelsOpportunity $opportunity, array $skillIds)
    {
        $this->opportunity = $opportunity;
        $this->skillIds = $skillIds;
    }
}
