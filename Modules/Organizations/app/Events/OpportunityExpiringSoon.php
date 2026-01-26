<?

namespace Modules\Organizations\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Organizations\Models\Opportunity;

/**
 * Event: OpportunityExpiringSoon
 *
 * This event is dispatched when an Opportunity
 * is close to its end date. It carries the Opportunity
 * instance so listeners can notify the organization
 * and manager.
 */
class OpportunityExpiringSoon
{
    use Dispatchable, SerializesModels;

    public Opportunity $opportunity;

    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }
}
