<?php

namespace Modules\Organizations\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Organizations\Models\Organization;

class OrganizationAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $organizationId = $request->route('organization'); // اسم الباراميتر بالمسار

        $organization = Organization::find($organizationId);

        if (! $organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        // شرط: أدمن أو صاحب المنظمة
        if ($user->role === 'admin' || $organization->user_id === $user->id) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied: not authorized for this organization'], 403);
    }
}
