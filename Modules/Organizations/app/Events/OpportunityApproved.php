<?

namespace Modules\Organizations\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Organizations\Models\Opportunity;

class OpportunityApproved
{
    use Dispatchable, SerializesModels;

    public $opportunity;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @param Opportunity $opportunity
     * @param int $userId
     */
    public function __construct(Opportunity $opportunity, int $userId)
    {
        $this->opportunity = $opportunity;
        $this->userId = $userId;
    }
}
