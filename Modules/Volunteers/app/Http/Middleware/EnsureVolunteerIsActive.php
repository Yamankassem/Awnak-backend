<?php

namespace Modules\Volunteers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureVolunteerIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        //  Ignore auth paths completely
        if ($request->routeIs('auth.*')) {
            return $next($request);
        }

        // Unregistered user
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // Not a volunteer → Ignore
        if (!$user->volunteerProfile) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Volunteer profile not found.',
            ], 403);
        }

        $profile = $user->volunteerProfile;

        // Profile suspended 
        if ($user->volunteerProfile->status === 'suspended') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Your account has been suspended.',
            ], 403);
        }

        if ($profile->status !== 'active') {
            abort(403, 'Volunteer account inactive');
        }



        return $next($request);
    }
}
