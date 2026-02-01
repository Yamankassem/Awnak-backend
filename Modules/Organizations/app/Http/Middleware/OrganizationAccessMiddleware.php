<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Organizations\Models\Organization;
/**
 * Middleware: OrganizationAccessMiddleware
 *
 * Controls access to organization-related routes based on HTTP method
 * and user role. Ensures that only authorized users can perform
 * sensitive actions such as creating, updating, or deleting organizations.
 *
 * Rules:
 * - GET requests:
 *   - Route `api/v1/organizations/notactive`: only accessible by system-admin.
 *   - Other GET routes: accessible by all authenticated users.
 *
 * - POST, PUT, PATCH, DELETE requests:
 *   - Only accessible by system-admin.
 *
 * - Route parameters:
 *   - If an organization ID is provided in the route, ensures that the
 *     organization exists. Returns 404 if not found.
 *
 * Responses:
 * - 403: Access denied if user lacks required role.
 * - 404: Organization not found if ID is invalid.
 */
class OrganizationAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Allow GET requests
        if ($request->isMethod('GET')) {
            // Special case: notactive organizations require system-admin
            if ($request->is('api/v1/organizations/notactive')) {
                if ($user->hasRole('system-admin')) {
                    return $next($request);
                }
                return response()->json([
                    'message' => 'Access denied: only system-admin can view notactive organizations'
                ], 403);
            }

            // Other GET routes are allowed
            return $next($request);
        }

        // Restrict POST/PUT/PATCH/DELETE to system-admin
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if ($user->hasRole('system-admin')) {
                return $next($request);
            }
            return response()->json([
                'message' => 'Access denied: only system-admin can manage organizations'
            ], 403);
        }

        // Validate organization existence if ID is provided in route
        $organizationId = $request->route('organization') ?? $request->route('id');
        if ($organizationId) {
            $organization = Organization::find($organizationId);

            if (! $organization) {
                return response()->json(['message' => 'Organization not found'], 404);
            }
        }

        return $next($request);
    }
}
