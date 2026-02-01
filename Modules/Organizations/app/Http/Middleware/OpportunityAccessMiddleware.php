<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Organizations\Models\Opportunity;

/**
 * Middleware: OpportunityAccessMiddleware
 *
 * Controls access to opportunity-related requests.
 *
 * Rules:
 * - GET (index/show): allowed for all authenticated users.
 * - POST (create): restricted to roles ['system-admin', 'opportunity-manager', 'organization-admin'].
 * - PUT/DELETE (update/delete): restricted to the same roles or the organization owner.
 *
 * @param Request $request The incoming HTTP request
 * @param Closure $next The next middleware/controller in the pipeline
 * @return \Illuminate\Http\JsonResponse|mixed
 *
 * @apiResponse 200 {
 *   "status": "success",
 *   "message": "Authorized request"
 * }
 *
 * @apiResponse 403 {
 *   "message": "Access denied: not authorized to create opportunities"
 * }
 *
 * @apiResponse 403 {
 *   "message": "Access denied: not authorized for this opportunity"
 * }
 *
 * @apiResponse 404 {
 *   "message": "Opportunity not found"
 * }
 */

class OpportunityAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $opportunityId = $request->route('opportunity') ?? $request->route('id');

        if ($request->isMethod('GET')) {
            return $next($request);
        }

        if ($request->isMethod('POST')) {
            if ($user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin'])) {
                return $next($request);
            }
            return response()->json(['message' => __('opportunities.create_denied')], 403);
        }

        
        $opportunity = Opportunity::with('organization')->find($opportunityId);

        if (! $opportunity) {
            return response()->json(['message' => __('opportunities.not_found')], 404);
        }

        $organization = $opportunity->organization;

        if (
            $user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin'])
            || $organization->user_id === $user->id
        ) {
            return $next($request);
        }

        return response()->json(['message' => __('opportunities.access_denied')], 403);
    }
}
