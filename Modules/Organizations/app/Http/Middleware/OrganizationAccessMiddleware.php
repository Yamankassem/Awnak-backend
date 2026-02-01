<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Organizations\Models\Organization;

/**
 * Middleware: OrganizationAccessMiddleware
 *
 * Controls access to organization-related requests.
 *
 * Rules:
 * - GET (index/show): allowed for all authenticated users.
 * - POST/PUT/DELETE (create/update/delete): restricted to role ['system-admin'] only.
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
 *   "message": "Access denied: only system-admin can manage organizations"
 * }
 *
 * @apiResponse 404 {
 *   "message": "Organization not found"
 * }
 */

class OrganizationAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $organizationId = $request->route('organization') ?? $request->route('id');


        if ($request->isMethod('GET')) {
            return $next($request);
        }

        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            if ($user->hasRole('system-admin')) {
                return $next($request);
            }
            return response()->json(['message' => 'Access denied: only system-admin can manage organizations'], 403);
        }

    
        if ($organizationId) {
            $organization = Organization::find($organizationId);

            if (! $organization) {
                return response()->json(['message' => 'Organization not found'], 404);
            }
        }

        return $next($request);
    }
}
