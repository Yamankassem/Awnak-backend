<?

namespace Modules\Organizations\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Organizations\Models\Opportunity;

/**
 * Event: OpportunityMatchedVolunteer
 *
 * This event is dispatched when an Opportunity
 * matches volunteer profiles based on required skills.
 * It carries the Opportunity instance so that listeners
 * can notify the relevant volunteers.
 */
class OpportunityMatchedVolunteer
{
    // Traits used to simplify event handling
    use Dispatchable, SerializesModels;

    /**
     * The Opportunity instance that matched volunteers.
     *
     * @var Opportunity
     */
    public Opportunity $opportunity;

    /**
     * Create a new event instance.
     *
     * @param Opportunity $opportunity The opportunity that triggered the match
     */
    public function __construct(Opportunity $opportunity)
    {
        // Store the opportunity so listeners can access it
        $this->opportunity = $opportunity;
    }
}
