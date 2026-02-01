<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware: OpportunitySkillAccessMiddleware
 *
 * Controls access to opportunity-skills requests.
 *
 * Rules:
 * - Allowed only for roles ['system-admin', 'opportunity-manager', 'organization-admin'].
 * - All other users: denied (403).
 *
 * @param Request $request The incoming HTTP request
 * @param Closure $next The next middleware/controller in the pipeline
 * @return \Illuminate\Http\JsonResponse|mixed
 *
 * @apiResponse 200 {
 *   "message": "Authorized request"
 * }
 *
 * @apiResponse 403 {
 *   "message": "Access denied: not authorized to access opportunity skills"
 * }
 */

class OpportunitySkillAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->hasRole(['system-admin', 'opportunity-manager', 'organization-admin'])) {
            return $next($request);
        }
        return response()->json(['message' => 'Access denied: not authorized to access opportunity skills'], 403);
    }
}
