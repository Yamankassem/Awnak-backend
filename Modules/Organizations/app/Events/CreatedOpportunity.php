<?

namespace Modules\Organizations\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Organizations\Models\Opportunity;

/**
 * Event: CreatedOpportunity
 *
 * This event is dispatched whenever a new Opportunity
 * is created. It carries the Opportunity instance so
 * listeners can notify the organization and manager.
 */
class CreatedOpportunity
{
    use Dispatchable, SerializesModels;

    public Opportunity $opportunity;

    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }
}
