<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Organizations\Models\Opportunity;

class OpportunityAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $opportunityId = $request->route('opportunity');

        $opportunity = Opportunity::with('organization')->find($opportunityId);

        if (! $opportunity) {
            return response()->json(['message' => 'Opportunity not found'], 404);
        }

        $organization = $opportunity->organization;

        // شرط: أدمن أو صاحب المنظمة
        if ($user->role === 'admin' || $organization->user_id === $user->id) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied: not authorized for this opportunity'], 403);
    }
}
