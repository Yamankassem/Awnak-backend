<?

namespace Modules\Organizations\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // fetch all Opportunity that will Expiring at least need 3 days for Expiring
        $schedule->call(function () {
            $opportunities = \Modules\Organizations\Models\Opportunity::whereDate('end_date', '<=', now()->addDays(3))->get();

            foreach ($opportunities as $opportunity) {
                event(new \Modules\Organizations\Events\OpportunityExpiringSoon($opportunity));
            }
        })->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
